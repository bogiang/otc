<?php
namespace Admin\Controller;

class IndexController extends AdminController
{
	public function index()
	{
		
		//累计交易额，要根据货币划分
		$order_sum = M()->query('select deal_ctype,sum(deal_amount) as zonge from ((select deal_ctype,deal_amount from tw_order_buy where status=3 or status=4) union all (select deal_ctype,deal_amount from tw_order_sell where status=3 or status=4)) as a group by deal_ctype');
		foreach($order_sum as $key=>$os){
			$currency = M('Btc')->where(array('id'=>$os['deal_ctype']))->find();
			$order_sum[$key]['currency'] = $currency['name'].'（'.$currency['short_name'].'）';
		}
		$this->assign('order_sum',$order_sum);
		
		//各种币余额
		$coin_list = M('Coin')->where(array('status'=>1))->field('name,title,id')->order('sort asc')->select();
		foreach($coin_list as $k=>$v){
			$user_ky = M('user_coin')->sum($v['name']);
			$user_dj = M('user_coin')->sum($v['name']."d");
			$coin_list[$k]['user_sum'] = "<p>可用".strtoupper($v['title'])."：".numform($user_ky)."，冻结".strtoupper($v['title'])."：".numform($user_dj)."</p>";
		}
		$this->assign('coin_list',$coin_list);

		$arr = array();
		//注册总人数
		$arr['reg_sum'] = M('User')->count();
		//文章总数
		$arr['art_sum'] = M('Article')->count();
		$this->assign('arr', $arr);
		//过去30天注册人数走势
		$data = array();
		$time = time() - (30 * 24 * 60 * 60);
		$i = 0;
		for (; $i < 60; $i++) {
			$a = $time;
			$time = $time + (60 * 60 * 24);
			$date = addtime($time, 'Y-m-d');
			$user = M('User')->where(array(
				'addtime' => array(
					array('gt', $a),
					array('lt', $time)
					)
				))->count();

			if ($user) {
				$data['reg'][] = array('date' => $date, 'sum' => $user);
			}
		}
		$this->assign('reg', json_encode($data['reg']));
		$this->display();
	}

	public function coin($coinname = NULL)
	{
		if (!$coinname) {
			$coinname = C('xnb_mr');
		}

		if (empty($coinname)) {
			echo '请去设置--其他设置里面设置默认币种';
			exit();
		}

		if (!M('Coin')->where(array('name' => $coinname))->find()) {
			echo '币种不存在,请去设置里面添加币种，并清理缓存';
			exit();
		}

		$this->assign('coinname', $coinname);
		$data = array();
		$data['trance_b'] = M('UserCoin')->sum($coinname);
		$data['trance_s'] = M('UserCoin')->sum($coinname . 'd');
		$data['trance_num'] = $data['trance_b'] + $data['trance_s'];
		$data['trance_song'] = M('Myzr')->where(array('coinname' => $coinname))->sum('fee');
		$data['trance_fee'] = M('Myzc')->where(array('coinname' => $coinname,'status'=>1))->sum('fee');

		if (C('coin')[$coinname]['tp_qj'] == 'btc') {
			$dj_username = C('coin')[$coinname]['dj_yh'];
			$dj_password = C('coin')[$coinname]['dj_mm'];
			$dj_address = C('coin')[$coinname]['dj_zj'];
			$dj_port = C('coin')[$coinname]['dj_dk'];
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
			if($coinname == "usdt"){
				$json = $CoinClient->getnetworkinfo();
				if (!isset($json['version']) || !$json['version']) {
					$this->error('钱包链接失败！');
				}
				$bb = $data['trance_mum'] = 0;
			}else{
				$json = $CoinClient->getwalletinfo();
				if (!isset($json['walletversion']) || !$json['walletversion']) {
					$this->error('钱包链接失败！');
				}
				if($json['balance']>0 && $json['balance']<0.001){
					$bb = $data['trance_mum'] = number_format($json['balance'],8,'.','');
				}else{
					$bb = $data['trance_mum'] = $json['balance'];
				}
			}
			
		}
		else {
			$bb = $data['trance_mum'] = 0;
		}

		$this->assign('data', $data);
		$market_json = M('CoinJson')->where(array('name' => $coinname))->order('id desc')->find();

		if ($market_json) {
			$addtime = $market_json['addtime'] + 60;
		}
		else {
			$addtime = M('Myzr')->where(array('name' => $coinname))->order('id asc')->find()['addtime'];
		}

		if (!$addtime) {
			$addtime = time();
		}

		$t = $addtime;
		$start = mktime(0, 0, 0, date('m', $t), date('d', $t), date('Y', $t));
		$end = mktime(23, 59, 59, date('m', $t), date('d', $t), date('Y', $t));

		if ($addtime) {
			$trade_num = M('UserCoin')->sum($coinname);
			$trade_mum = M('UserCoin')->sum($coinname . 'd');
			$aa = $trade_num + $trade_mum;

			$trade_fee_buy = M('Myzr')->where(array(
				'name'    => $coinname,
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum('fee');
			$trade_fee_sell = M('Myzc')->where(array(
				'name'    => $coinname,
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					),
				'status'=>1
				))->sum('fee');
			$d = array($aa, $bb, $trade_fee_buy, $trade_fee_sell);

			if (M('CoinJson')->where(array('name' => $coinname, 'addtime' => $end))->find()) {
				M('CoinJson')->where(array('name' => $coinname, 'addtime' => $end))->save(array('data' => json_encode($d)));
			}
			else {
				M('CoinJson')->add(array('name' => $coinname, 'data' => json_encode($d), 'addtime' => $end));
			}
		}

		$tradeJson = M('CoinJson')->where(array('name' => $coinname))->order('id asc')->limit(100)->select();

		foreach ($tradeJson as $k => $v) {
			if ((addtime($v['addtime']) != '---') && (14634049 < $v['addtime'])) {
				$date = addtime($v['addtime'], 'Y-m-d H:i:s');
				$json_data = json_decode($v['data'], true);
				$cztx[] = array('date' => $date, 'num' => $json_data[0], 'mum' => $json_data[1], 'fee_buy' => $json_data[2], 'fee_sell' => $json_data[3]);
			}
		}

		$this->assign('cztx', json_encode($cztx));
		$this->display();
	}

	public function coinSet($coinname = NULL)
	{
		if (!$coinname) {
			$this->error('参数错误！');
		}

		if (M('CoinJson')->where(array('name' => $coinname))->delete()) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

}

?>