<?php
namespace Home\Controller;

class IndexController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$this->common_content();
	}
	
	public function index()
	{
		$num1 =0;
		$num2 =0;

		$where=array();
		$wherea['a.state'] = 1;
		$wherea['b.is_agree']=1;
		
		$wherec['c.state'] = 1;
		$wherec['b.is_agree']=1;

		$Modulea = M('Ad_buy a');
		$count_id = $Modulea->join('tw_user b on a.userid=b.id')->where($wherea)->field('a.id as id,a.userid')->select();
		$id_list='';
		//进行过滤计算记录数并拼接id字符串
		foreach($count_id as $v){
			$ifopen = ifopen($v['id'],0);
			//过滤开放时间
			if($ifopen == 0){
				continue;
			}else {
				
				$id_list = $id_list . ',' . $v['id'];
			}
		}
		$id_list = trim($id_list,',');

		$Modulec = M('Ad_sell c');
		$count_idc = $Modulec->join('tw_user b on c.userid=b.id')->where($wherec)->field('c.id as id,c.userid')->select();

		$id_listc='';
		//进行过滤计算记录数并拼接id字符串
		foreach($count_idc as $v){
			$ifopen = ifopen($v['id'],1);

			//过滤开放时间
			if($ifopen == 0){
				continue;
			}else {
				
				$id_listc = $id_listc . ',' . $v['id'];
			}
		}
		$id_listc = trim($id_listc,',');
		
		if(!empty($id_list) || !empty($id_listc)){
			if(empty($id_list)){
				$list = M()->query("select * from (select id,add_time,userid,pay_method,currency,location,margin,min_limit,max_limit,coin,type from tw_ad_sell where(id in ($id_listc))) as temp  order by add_time desc limit 4");
			}
			if(empty($id_listc)){
				$list = M()->query("select * from (select id,add_time,userid,pay_method,currency,location,margin,min_limit,max_limit,coin,type from tw_ad_buy where(id in ($id_list))) as temp  order by add_time desc limit 4");
			}
			if(!empty($id_list) && !empty($id_listc)){
				$list = M()->query("select * from (select id,add_time,userid,pay_method,currency,location,margin,min_limit,max_limit,coin,type from tw_ad_buy where(id in ($id_list)) union all select id,add_time,userid,pay_method,currency,location,margin,min_limit,max_limit,coin,type from tw_ad_sell where(id in ($id_listc))) as temp  order by add_time desc limit 4");
			}
			
			$adinfo=array();
			$a=0;
			foreach ($list as $k => $vv) {
				$a++;
				if($a>4){
					break;
				}
				if($vv['type']==0){
					$fs='购买';
				}else{
					$fs='出售';
				}
				$hb = M()->table('tw_coin')->where("id=".$vv['coin'])->getField('name');
				$adinfo[$k]['fshb']=$fs.strtoupper($hb);

				$userinfo=M()->table('tw_user')->where(array('id'=>$vv['userid']))->find();
				$adinfo[$k]['enname']=$userinfo['enname'];
				$adinfo[$k]['goodcomm']=$userinfo['goodcomm']?$userinfo['goodcomm']:0;		
				$adinfo[$k]['transact']=$userinfo['transact']?$userinfo['transact']:0;
				$adinfo[$k]['trust']=gettrust($userinfo['id']);	
				$adinfo[$k]['currency']=get_price($vv['coin'],$vv['currency'],0);


				$price = get_price($vv['coin'],$vv['currency'],1);
				if($vv['type']==1){
					$min_price=M()->table('tw_ad_sell')->where(array('id'=>$vv['id']))->getField('min_price');
					if($price < $min_price){
						$price = $min_price;
					}
				}
				
				$adinfo[$k]['price'] = round(($price + $price * $vv['margin']/100),2);

				// $adinfo[$k]['price']=sprintf("%.2f",(get_price($vv['coin'],$vv['currency'],1))*(1+$vv['margin']/100));
				$adinfo[$k]['coin']=M()->table('tw_coin')->where(array('id'=>$vv['coin']))->getField('name');
				$location=M()->table('tw_location')->where(array('id'=>$vv['location']))->field('name,short_name')->find();
				$adinfo[$k]['location']=$location['short_name'].' '.$location['name'];
				$adinfo[$k]['headimgis']=1;
                if(empty($userinfo['headimg'])){
                	$adinfo[$k]['headimgis']=0;
                }
				$adinfo[$k]['headimg'] = !empty($userinfo['headimg']) ? $userinfo['headimg'] : "/Public/Home/images/hportrait/head_portrait60.png";
				$adinfo[$k]['min_limit']=$vv['min_limit'];
				$adinfo[$k]['max_limit']=$vv['max_limit'];
				$adinfo[$k]['paymethod']=getpaymethod($vv['pay_method']);//M()->table('tw_pay_method')->where(array('id'=>$vv['pay_method']))->getField('name');
				$adinfo[$k]['type']=$vv['type'];
				$adinfo[$k]['id']=$vv['id'];

			}
			$this->assign('adinfo', $adinfo);
		}

		$ad_buy=M('Ad_buy')->where(array('state'=>1))->order('add_time desc')->select();
		$ad_sell=M('Ad_sell')->where(array('state'=>1))->order('add_time desc')->select();
		$trad_ad_buy = array();
		$trad_ad_sell = array();
		foreach ($ad_buy as $k => $v) {
			if($num1 > 4){
				break;
			}else{
				if(ifShow($v['id'],0) == 1){
					continue;
				}else{
					$num1++;
				}
				$trad_ad_buy[$k]=$v;
				$userinfo=M('User')->where(array('id'=>$v['userid']))->find();
				$trad_ad_buy[$k]['enname']=$userinfo['enname'];
				$trad_ad_buy[$k]['goodcomm']=$userinfo['goodcomm']?$userinfo['goodcomm']:0;
				$trad_ad_buy[$k]['transact']=$userinfo['transact']?$userinfo['transact']:0;
				$trad_ad_buy[$k]['trust']=gettrust($v['userid']);
				$trad_ad_buy[$k]['currency']=get_price($v['coin'],$v['currency'],0);
				$trad_ad_buy[$k]['price']=sprintf("%.2f",(get_price($v['coin'],$v['currency'],1))*(1+$v['margin']/100));
				$trad_ad_buy[$k]['coin']=M('Coin')->where(array('id'=>$v['coin']))->getField('name');
				$trad_ad_buy[$k]['headimg'] = !empty($userinfo['headimg']) ? $userinfo['headimg'] : "/Public/Home/images/hportrait/head_portrait60.png";
			}
		}
		foreach ($ad_sell as $k => $v) {
			if($num2 > 4){
				break;
			}else{
				if(ifShow($v['id'],1) == 1){
					continue;
				}else{
					$num2++;
				}
				$trad_ad_sell[$k]=$v;
				$userinfo=M('User')->where(array('id'=>$v['userid']))->find();
				$trad_ad_sell[$k]['enname']=$userinfo['enname'];
				$trad_ad_sell[$k]['goodcomm']=$userinfo['goodcomm']?$userinfo['goodcomm']:0;		
				$trad_ad_sell[$k]['transact']=$userinfo['transact']?$userinfo['transact']:0;
				$trad_ad_sell[$k]['trust']=$userinfo['trust']?$userinfo['trust']:0;	
				$trad_ad_sell[$k]['currency']=get_price($v['coin'],$v['currency'],0);
				$trad_ad_sell[$k]['price']=sprintf("%.2f",(get_price($v['coin'],$v['currency'],1))*(1+$v['margin']/100));
				$trad_ad_sell[$k]['coin']=M('Coin')->where(array('id'=>$v['coin']))->getField('name');
				$trad_ad_sell[$k]['headimg'] = !empty($userinfo['headimg']) ? $userinfo['headimg'] : "/Public/Home/images/hportrait/head_portrait60.png";
			}
		}

		$this->assign('trad_ad_buy', $trad_ad_buy);
		$this->assign('trad_ad_sell', $trad_ad_sell);
		// 交易币种--------------------E

		// 首页轮播图 ----------------------S

		$indexAdver = (APP_DEBUG ? null : S('index_indexAdver'));

		if (!$indexAdver) {
			// $indexAdver = M('Adver')->where(array('status' => 1,'look' => 0))->order('id asc')->select();
			$indexAdver = M('Adver')->where(array('status' => 1,'look'=>0))->order('id asc')->select();
			foreach($indexAdver as $key=>$val){
				$indexAdver[$key]['img']=stripslashes($indexAdver[$key]['img']);
				$indexAdver[$key]['url']=stripslashes($indexAdver[$key]['url']);
			}
			S('index_indexAdver', $indexAdver);
		}

		$this->assign('indexAdver', $indexAdver);

		// 首页轮播图 ----------------------E
		
		// 官方公告 type为notice -----------------S

		$type = 'newuser';

		$type1 = M('ArticleType')->where(array('name' => $type))->find();
		$this->assign('type1', $type1);

		$where1 = array();
		// $where1['type'] = $type;
		$where1['status'] = 1;
		$where1['index'] = 1;

		$news_list1 = M('Article')->where($where1)->order('id desc')->limit(5)->select();
		// var_dump($news_list1);
		$this->assign('news_list1', $news_list1);

		// 官方公告 type为aaa -----------------E


		// 行业资讯 type为bbb -----------------S

		/*$type = 'hyzx';

		$type2 = M('ArticleType')->where(array('name' => $type))->find();
		$this->assign('type2', $type2);
		
		$where2 = array();
		$where2['type'] = $type;
		$where2['status'] = 1;
		$where2['index'] = 1;

		$news_list2 = M('Article')->where($where2)->order('id desc')->limit(7)->select();

		$this->assign('news_list2', $news_list2);*/

		// 行业资讯 type为bbb -----------------E

		// 新闻资讯 type为bbb -----------------S

		/*$type = 'news';

		$type3 = M('ArticleType')->where(array('name' => $type))->find();
		$this->assign('type3', $type3);
		
		$where3 = array();
		$where3['type'] = 'news';
		$where3['status'] = 1;
		$where3['index'] = 1;

		$news_list3 = M('Article')->where($where3)->order('id desc')->limit(7)->select();

		$this->assign('news_list3', $news_list3);*/

		// 行业资讯 type为bbb -----------------E

		

		// 战略合作  -----------------S

		/*$indexLink = (APP_DEBUG ? null : S('index_indexLink'));

		if (!$indexLink) {
			$indexLink = M('Link')->where(array('status' => 1,'look_type'=>0))->order('sort asc ,id desc')->select();
		}

		$this->assign('indexLink', $indexLink);*/

		// 战略合作  -----------------E



		/*if(userid()){
			$CoinList = M('Coin')->where(array('status' => 1))->select();
			$UserCoin = M('UserCoin')->where(array('userid' => userid()))->find();
			$Market = M('Market')->where(array('status' => 1))->select();

			foreach ($Market as $k => $v) {
				$Market[$v['name']] = $v;
			}

			$cny['zj'] = 0;

			foreach ($CoinList as $k => $v) {
				if ($v['name'] == 'cny') {
					$cny['ky'] = intval($UserCoin[$v['name']]*100)/100;
					$cny['dj'] = round($UserCoin[$v['name'] . 'd'], 2) * 1;
					$cny['zj'] = $cny['zj'] + $cny['ky'] + $cny['dj'];
				}
				else {
					if ($Market[$v['name'] . '_cny']['new_price']) {
						$jia = $Market[$v['name'] . '_cny']['new_price'];
					}
					else {
						$jia = 1;
					}

					$coinList[$v['name']] = array('name' => $v['name'], 'img' => $v['img'], 'title' => $v['title'] . '(' . strtoupper($v['name']) . ')', 'xnb' => round($UserCoin[$v['name']], 6) * 1, 'xnbd' => round($UserCoin[$v['name'] . 'd'], 6) * 1, 'xnbz' => round($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd'], 6), 'jia' => $jia * 1, 'zhehe' => round(($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd']) * $jia, 2));
					$cny['zj'] = round($cny['zj'] + (($UserCoin[$v['name']] + $UserCoin[$v['name'] . 'd']) * $jia), 2) * 1;
					
					$cny['zj'] = sprintf("%.4f", $cny['zj']);
				}
			}

			$this->assign('cny', $cny);
		}*/
		
		//交易排行榜
		$trade_max = M('User')->where(array('status'=>1,'transact'=>array('gt',0)))->order('transact desc')->limit(5)->select();
		foreach($trade_max as $kk=>$vv){
			$trade_max[$kk]['trust'] = gettrust($vv['id']);
			$trade_max[$kk]['headimg'] = !empty($vv['headimg']) ? $vv['headimg'] : "/Public/Home/images/hportrait/head_portrait60.png";
			$trade_max[$kk]['headimgis']=1;
			if(empty($vv['headimg'])){
				$trade_max[$kk]['headimgis']=0;
			}
		}
		$this->assign('trade_max',$trade_max);

		if (C('index_html')) {
			$this->display('Index/' . C('index_html') . '/index');
		}
		else {
			$this->display();
		}
	}
}

?>