<?php
namespace Home\Controller;

class FinanceController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","upmyzc");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$display_action=array("index");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}
	
	public function index($xnb='',$act='')
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}
		//默认显示比特币
		if(empty($_GET['xnb'])){
			$xnb=C('xnb_mr');
		}else{
			$xnb=$_GET['xnb'];
		}
		if(!check($xnb,'w')){
			$this->error("参数错误！");
		}
		$this->assign('xnb',$xnb);
		//默认显示转入
		if(empty($_GET['act'])){
			$act="zr";
		}else{
			$act=$_GET['act'];
		}
		if(!check($act,'w')){
			$this->error("参数错误！");
		}
		$this->assign('act',$act);
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>'qbb','status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = strtoupper($v['name']);
			$new_coinlist[$v['name']]['xnb'] = numform($user_coin[$v['name']]);
			$new_coinlist[$v['name']]['xnbd'] = numform($user_coin[$v['name'] . 'd']);
			$new_coinlist[$v['name']]['xnbz'] = numform($user_coin[$v['name']]+$user_coin[$v['name'] . 'd']);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
			$new_coinlist[$v['name']]['zc_fee'] = $v['zc_fee']*1;
		}
		$this->assign('coin_list', $new_coinlist);
		
		$Coins = M('Coin')->where(array('name' => $xnb))->find();
		if($Coins['zc_fee']==0 && $Coins['zc_fee_bili']==0){
			$myzc_fee_desc = "0";
		}elseif($Coins['zc_fee']==0){
			$myzc_fee_desc = numform($Coins['zc_fee_bili'])."%";
		}elseif($Coins['zc_fee_bili']==0){
			$myzc_fee_desc = numform($Coins['zc_fee']);
		}else{
			$myzc_fee_desc = numform($Coins['zc_fee'])."+".numform($Coins['zc_fee_bili'])."%";
		}
		$this->assign('myzc_fee', $myzc_fee_desc);

		if($act=="zr"){
			if (!$Coins['zr_jz']) {
				$qianbao = '当前禁止转入！';
			}else {
				//钱包地址
				if ($Coins['type'] == 'qbb') {
					if($Coins['tp_qj'] == 'btc'){



						$qbdz = $xnb . 'b';
						if (!$user_coin[$qbdz]) {

							$dj_username = $Coins['dj_yh'];
							$dj_password = $Coins['dj_mm'];
							$dj_address = $Coins['dj_zj'];
							$dj_port = $Coins['dj_dk'];
							$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);

							$json = $CoinClient->getnetworkinfo();

//                            echo "<pre>";
//                            print_r($json);
//                            echo "</pre>";
//                            exit();

							if (!isset($json['version']) || !$json['version']) {
								$this->error('钱包链接失败！');
							}

							$qianbao_addr = $CoinClient->getaddressesbyaccount(username());

							if (!is_array($qianbao_addr)) {
								$qianbao_ad = $CoinClient->getnewaddress(username());

								if (!$qianbao_ad) {
									$this->error('生成钱包地址出错1！');
								}
								else {
									$qianbao = $qianbao_ad;
								}
							}
							else {
								$qianbao = $qianbao_addr[0];
							}

							if (!$qianbao) {
								$this->error('生成钱包地址出错2！');
							}
							
							if(!empty($qianbao) && strstr($qianbao,'SERVER') && strstr($qianbao,'data')){
								$this->error('钱包连接失败，请稍后重试！');
							}

							$rs = M('user_coin')->where(array('userid' => userid()))->save(array($qbdz => $qianbao));

							if (!$rs) {
								$this->error('钱包地址添加出错3！');
							}
						}else {
							$qianbao = $user_coin[$xnb . 'b'];
						}
					}else{
						if (empty($user_coin['ethb'])) {
							$coinpassword = 'eth'.$user_coin['userid'].mt_rand(10000000,99999999);
							$new_account = \Common\Ext\EthWallet::newAccount($coinpassword);
							$account = json_decode($new_account,true);
							if(!empty($account['result'])){
								$rs = array();
								$mo = M();
								$mo->startTrans();
								$rs[] = $mo->table('tw_user')->where(array('id'=>$user_coin['userid']))->save(array('ethpassword'=>$coinpassword));
								$rs[] = $mo->table('tw_user_coin')->where(array('userid'=>$user_coin['userid']))->save(array('ethb'=>$account['result']));
								if(check_arr($rs)){
									$mo->commit();
									$qianbao = $account['result'];
								}else{
									$mo->rollback();
									$this->error("生成钱包地址失败！");
								}
							}
						}else{
							$qianbao = $user_coin['ethb'];
						}
					}
				}else{
					$qianbao = '';
				}
			}

			//显示转入地址
			$this->assign('qianbao', $qianbao);
			
			$where['userid'] = userid();
			$where['coinname'] = $xnb;
			$where['from_user'] = '0';
			$count = M('Myzr')->where($where)->count();
			$Page = new \Think\Page($count, 10);
			$show = $Page->show();
			$list = M('Myzr')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			$this->assign('list', $list);
			$this->assign('page', $show);
		}elseif($act=="zc"){
			//生成token
			$myzc_token = set_token('myzc');
			$this->assign('myzc_token',$myzc_token);
			$myzr_token = set_token('myzr');
			$this->assign('myzr_token',$myzr_token);
			
			$where['userid'] = userid();
			$where['coinname'] = $xnb;
			$where['to_user'] = array('neq','1' );
			$Mzc = M('Myzc');
			$count = $Mzc->where($where)->count();
			$Page = new \Think\Page($count, 10);
			$show = $Page->show();
			$list = $Mzc->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			$this->assign('list', $list);
			$this->assign('page', $show);
		}
		
		$user = M("User")->where(array("id"=>userid()))->find();
		$this->assign('user', $user);

		$ti_open=0;
		if(!empty($user['ga'])){
			$is_ti=explode("|",$user['ga']);
			if($is_ti[2]==1){
				$ti_open=1;
			}
		}
		$this->assign('ti_open', $ti_open);

		$mobile = $user['mobile'];
        $email = $user['email'];
        if (empty($mobile) && empty($email)) {
            $this->error("请先绑定手机或邮箱中的一种！");
        }
        if ($mobile) {
            $mobile = substr_replace($mobile, '****', 3, 4);
        }
        $this->assign('mobile', $mobile);
		//查找地址
		$userQianbaoList = M('UserQianbao')->where(array('userid' => userid(), 'status' => 1, 'coinname' => $xnb))->order('id desc')->select();
		$this->assign('userQianbaoList', $userQianbaoList);

		$this->display();
	}

	public function mycz($status = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($status)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		$myczType = M('MyczType')->where(array('status' => 1))->select();

		foreach ($myczType as $k => $v) {
			$myczTypeList[$v['name']] = $v['title'];
		}

		$this->assign('myczTypeList', $myczTypeList);
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$user_coin['cny'] = intval($user_coin['cny']*100)/100;
		$user_coin['cnyd'] = round($user_coin['cnyd'], 2);
		$user_coin['cny'] = sprintf("%.2f", $user_coin['cny']);
		$user_coin['cnyd'] = sprintf("%.2f", $user_coin['cnyd']);
		$this->assign('user_coin', $user_coin);

		if (($status == 1) || ($status == 2) || ($status == 3) || ($status == 4)) {
			$where['status'] = $status - 1;
		}

		$this->assign('status', $status);
		$where['userid'] = userid();
		$count = M('Mycz')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Mycz')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['type'] = M('MyczType')->where(array('name' => $v['type']))->getField('title');
			$list[$k]['num'] = (Num($v['num']) ? Num($v['num']) : '');
			$list[$k]['mum'] = (Num($v['mum']) ? Num($v['mum']) : '');
			$list[$k]['mum'] = sprintf("%.2f", $list[$k]['mum']);
			$list[$k]['num'] = sprintf("%.2f", $list[$k]['num']);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);

		$user_info=M('user')->where(array('id'=>userid()))->find();
		$this->assign('user_info', $user_info);

		$UserBankType = M('UserBankType')->where(array('status' => 1))->order('id desc')->select();
		$this->assign('UserBankType', $UserBankType);


		$this->display();
	}

	public function myczHuikuan($id = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录！');
		}

		if (!check($id, 'd')) {
			$this->error('参数错误！');
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();

		if (!$mycz) {
			$this->error('充值订单不存在！');
		}

		if ($mycz['userid'] != userid()) {
			$this->error('非法操作！');
		}

		if ($mycz['status'] != 0) {
			$this->error('订单已经处理过！');
		}

		$rs = M('Mycz')->where(array('id' => $id))->save(array('status' => 3));

		if ($rs) {
			$this->success('操作成功');
		}
		else {
			$this->error('操作失败！');
		}
	}
	//获取充值手续费费率
	public function myczFee($cztype){


		// 过滤非法字符----------------S

		if (checkstr($cztype)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录！');
		}
		$cztype_list=M('mycz_type')->where(array('status'=>1))->select();
		$cztype_arr=array();
		foreach($cztype_list as $val){
			$cztype_arr[]=$val['name'];
		}
		if (!in_array($cztype, $cztype_arr)) {
			$this->error('充值类型错误！');
		}
		$fee=M('mycz_type')->where(array('status'=>1,'name'=>$cztype))->find();
		echo json_encode(array('fee'=>$fee['fee']));
		exit;
	}

	public function myczRes($id){


		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error('请先登录！');
		}

		if (!check($id, 'd')) {
			$this->error('参数错误！');
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();

		if (!$mycz) {
			$this->error('充值订单不存在！');
		}

		if ($mycz['userid'] != userid()) {
			$this->error('非法操作！');
		}

		echo json_encode(array('status'=>$mycz['status'],'tradeno'=>$mycz['tradeno']));
		exit;
	}

	public function myczChakan($id = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录！');
		}

		if (!check($id, 'd')) {
			$this->error('参数错误！');
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();

		if (!$mycz) {
			$this->error('充值订单不存在！');
		}

		if ($mycz['userid'] != userid()) {
			$this->error('非法操作！');
		}

		if ($mycz['status'] != 0) {
			$this->error('订单已经处理过！');
		}

		$rs = M('Mycz')->where(array('id' => $id))->save(array('status' => 3));

		if ($rs) {
			$this->success('', array('id' => $id));
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function myczUp($bankt = '', $type, $num, $mum, $truename, $aliaccount)
	{


		// 过滤非法字符----------------S

		if (checkstr($bankt) || checkstr($type) || checkstr($num) || checkstr($mum) || checkstr($truename) || checkstr($aliaccount)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E

		if (!userid()) {
			$this->error('请先登录！');
		}

		if (!check($type, 'n')) {
			$this->error('充值方式格式错误！');
		}

		if (!check($num, 'cny') || !check($mum, 'cny')) {
			$this->error('充值金额格式错误！');
		}
		$myczType = M('MyczType')->where(array('name' => $type))->find();

		if (!$myczType) {
			$this->error('充值方式不存在！');
		}

		if ($myczType['status'] != 1) {
			$this->error('充值方式没有开通！');
		}

		$mycz_min = ($myczType['min'] ? $myczType['min'] : 1);
		$mycz_max = ($myczType['max'] ? $myczType['max'] : 100000);

		if ($num < $mycz_min || $mum < $mycz_min) {
			$this->error('充值金额不能小于' . $mycz_min . '元！');
		}

		if ($mycz_max < $num || $mycz_max < $mum) {
			$this->error('充值金额不能大于' . $mycz_max . '元！');
		}

		$cztimes = M('Mycz')->where(array('userid'=>userid()))->count();
		for ($i=1; true;$i++ ) {
			$tradeno = userid()."-".($cztimes+$i);
			if (!M('Mycz')->where(array('tradeno' => $tradeno))->find()) {
				break;
			}
		}
		if($type=='alipay'){
			if(empty($truename)){
				$this->error('请填写您支付宝账号认证的真实姓名！');
			}
			if(!check($truename, 'chinese')){
				$this->error('真实姓名必须是汉字！');
			}
			if(empty($aliaccount)){
				$this->error('请填写支付宝账号！');
			}
			if (!check($aliaccount, 'mobile')) {
				if (!check($aliaccount, 'email')) {
					$this->error('支付宝账号格式错误！');
				}
			}
		}elseif($type=='bank'){
			if(empty($bankt)){
				$this->error('请选择汇款银行！');
			}
			if(empty($truename)){
				$this->error('请填写您银行账号认证的真实姓名！');
			}
			if(!check($truename, 'chinese')){
				$this->error('真实姓名必须是汉字！');
			}
			if(empty($aliaccount)){
				$this->error('请填写银行卡号！');
			}
			if(!is_numeric($aliaccount) || strlen(trim($aliaccount))<13){
				$this->error('银行卡号是不低于13位的数字');
			}
		}

		$mycz = M('Mycz')->add(array('userid' => userid(), 'bank' => $bankt, 'num' => $mum, 'mum' => $mum, 'type' => $type, 'tradeno' => $tradeno, 'addtime' => time(), 'status' => 0, 'alipay_truename'=>$truename, 'alipay_account'=>$aliaccount, 'fee'=>$myczType['fee']));
		
		if ($mycz) {
			if($type!='weixin'){
				$this->success('充值订单创建成功！', array('id' => $mycz));
			}elseif($type='weixin'){
				Vendor("Pay.JSAPI","",".php");
				$wxpay_obj=new \WxPayApi;
				$wxpayorder=new \WxPayUnifiedOrder;
				$wxpayorder->SetOut_trade_no($tradeno);
				$wxpayorder->SetBody('账户充值');
				$wxpayorder->SetTotal_fee($mum*100);
				$wxpayorder->SetTrade_type("NATIVE");
				$wxpayorder->SetProduct_id($mycz);
				$wxpayorder->SetNotify_url("http://xnb.huiz.net.cn/Home/Pay/mycz.html");
				$wxpayorder->SetSpbill_create_ip("120.77.221.213");
				$wxpayorder->SetFee_type("CNY");
				$wxpay=$wxpay_obj->unifiedOrder($wxpayorder);
				if(!empty($wxpay['code_url'])){
					Vendor("RandEx.RandEx","",".php");
					$rand = new \RandEx;
					$imgname = $rand->random(30,'all',0).".png";
					Vendor("PHPQRcode.phpqrcode","",".php");
					$level = 'L';
					$size = 4;
					$url = "./Upload/ewm/wxpay/".$imgname;
					\QRcode::png($wxpay['code_url'], $url, $level, $size);
					M('Mycz')->where(array('id'=>$mycz))->save(array('ewmname'=>$imgname));
					$res=array();
					$res['cztype']="wxpay";
					$res['status']=1;
					$res['id']=$mycz;
					echo json_encode($res);
					exit;
				}
			}else{
				$this->success('充值订单创建成功！', array('id' => $mycz));
			}
		}
		else {
			$this->error('提现订单创建失败！');
		}
	}

	public function mytx($status = NULL)
	{


		// 过滤非法字符----------------S

		if (checkstr($status)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		$this->assign('prompt_text', D('Text')->get_content('finance_mytx'));
		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');
		$email = M('User')->where(array('id' => userid()))->getField('email');
		if(empty($mobile) && empty($email)){
			$this->error("请先绑定手机或邮箱中的一种！");
		}
		if ($mobile) {
			$mobile = substr_replace($mobile, '****', 3, 4);
		}
		$user = M('User')->where(array('id' => userid()))->find();
		if(shiming($user['id']) < 3){
			$this->error("请先完成实名认证并通过管理员审核！",'/User/nameauth.html');
		}
		$this->assign('user', $user);
		$this->assign('mobile', $mobile);
		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();
		$user_coin['cny'] = intval($user_coin['cny']*100)/100;
		$user_coin['cnyd'] = round($user_coin['cnyd'], 2);
		$user_coin['cny'] = sprintf("%.2f", $user_coin['cny']);
		$user_coin['cnyd'] = sprintf("%.2f", $user_coin['cnyd']);
		$this->assign('user_coin', $user_coin);
		$userBankList = M('UserBank')->where(array('userid' => userid(), 'status' => 1))->order('id desc')->select();

		$truenames = M('User')->where(array('id' => userid()))->getField('truename');

		foreach ($userBankList as $k => $v) {
			$userBankList[$k]['truename'] = $truenames;
		}

		$this->assign('userBankList', $userBankList);

		if (($status == 1) || ($status == 2) || ($status == 3) || ($status == 4)) {
			$where['status'] = $status - 1;
		}

		$this->assign('status', $status);
		$where['userid'] = userid();
		$count = M('Mytx')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Mytx')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['num'] = (Num($v['num']) ? Num($v['num']) : '');
			$list[$k]['fee'] = (Num($v['fee']) ? Num($v['fee']) : '');
			$list[$k]['fees'] = $list[$k]['fee']/$list[$k]['num']*100;
			$list[$k]['mum'] = (Num($v['mum']) ? Num($v['mum']) : '');
			$list[$k]['names'] = $v['bank'].' '.$v['bankcard'].' '.$v['truename'];
			$list[$k]['num'] = sprintf("%.2f", $list[$k]['num']);
			$list[$k]['fee'] = sprintf("%.2f", $list[$k]['fee']);
			$list[$k]['mum'] = sprintf("%.2f", $list[$k]['mum']);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		//生成token
		$mytx_token = set_token('mytx');
		$txcancel_token = set_token('txcancel');
		$this->assign('mytx_token',$mytx_token);
		$this->assign('txcancel_token',$txcancel_token);
		
		$this->display();
	}

	public function mytxUp($mobile_verify, $num, $paypassword, $type, $token, $chkstyle, $email_verify)
	{

		$extra='';
		
		// 过滤非法字符----------------S

		if (checkstr($mobile_verify) || checkstr($num) || checkstr($type)|| checkstr($chkstyle)|| checkstr($email_verify)) {
			$this->error('您输入的信息有误！',$extra);
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录！',$extra);
		}
		
		if(!session('mytxtoken')) {
			set_token('mytx');
		}
		if(!empty($token)){
			$res = valid_token('mytx',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('mytxtoken'));
			}
		}
		$extra=session('mytxtoken');
		
		$user_info = M('user')->where(array('id'=>userid()))->find();
		if(shiming($user_info['id']) < 3){
			$this->error("请先完成实名认证并通过管理员审核！",'/User/nameauth.html');
		}
		if($chkstyle=='mobile'){
			if (!check($mobile_verify, 'd')) {
			 	$this->error('短信验证码格式错误！',$extra);
			}

			if ($user_info['mobile'] != session('chkmobile')) {
				$this->error('短信验证码错误！',$extra);
			}

			if ($mobile_verify != session('mytx_verify')) {
			 	$this->error('短信验证码错误！',$extra);
			}
		}elseif($chkstyle=='email'){
			if (!check($email_verify, 'd')) {
			 	$this->error('邮箱验证码格式错误！',$extra);
			}

			if ($user_info['email'] != session('chkemail')) {
				$this->error('邮箱验证码错误！',$extra);
			}

			if ($email_verify != session('emailmytx_verify')) {
			 	$this->error('邮箱验证码错误！',$extra);
			}
		}

		if (!check($num, 'd')) {
			$this->error('提现金额格式错误！',$extra);
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！',$extra);
		}

		if (!check($type, 'd')) {
			$this->error('提现方式格式错误！',$extra);
		}

		$userCoin = M('UserCoin')->where(array('userid' => userid()))->find();

		if ($userCoin['cny'] < $num) {
			$this->error('可用人民币余额不足！',$extra);
		}

		$user = M('User')->where(array('id' => userid()))->find();

		if (md5($paypassword) != $user['paypassword']) {
			$this->error('交易密码错误！',$extra);
		}

		$userBank = M('UserBank')->where(array('id' => $type))->find();

		if (!$userBank) {
			$this->error('提现地址错误！',$extra);
		}

		$mytx_min = (C('mytx_min') ? C('mytx_min') : 2);
		$mytx_max = (C('mytx_max') ? C('mytx_max') : 50000);
		$mytx_day_max = (C('mytx_day_max') ? C('mytx_day_max') : 200000);
		$start_time = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$end_time = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
		$today_tx_sum=M('mytx')->where(array('addtime'=>array('between',"$start_time,$end_time"),'status'=>array('neq',3),'userid' => session('userId')))->field('sum(num) as ttamount')->find();
		$today_tx_amount=intval($today_tx_sum['ttamount']);
		if($today_tx_amount+$num>$mytx_day_max){
			$this->error('今天累计提现的金额超出最大值！最多还能提出：'.($mytx_day_max-$today_tx_amount),$extra);
		}
		$mytx_bei = C('mytx_bei');
		$mytx_fee = C('mytx_fee');
		$mytx_fee_min = (C('mytx_fee_min') ? C('mytx_fee_min') : 0);
		if($mytx_min<=$mytx_fee_min){
			$mytx_min=$mytx_fee_min;
		}
		if ($num < $mytx_min) {
			$this->error('每次提现金额不能小于' . $mytx_min . '元！',$extra);
		}

		if ($mytx_max < $num) {
			$this->error('每次提现金额不能大于' . $mytx_max . '元！',$extra);
		}

		if ($mytx_bei) {
			if ($num % $mytx_bei != 0) {
				$this->error('每次提现金额必须是' . $mytx_bei . '的整倍数！',$extra);
			}
		}

		$fee = round(($num / 100) * $mytx_fee, 2);
		if($fee<$mytx_fee_min){
			$fee = $mytx_fee_min;
		}
		$mum = round(($num- $fee), 2);
		try{
			$mo = M();
			$mo->startTrans();
			//$mo->execute('lock tables tw_mytx write , tw_user_coin write ,tw_finance write,tw_finance_log write');
			$rs = array();
			$finance = $mo->table('tw_finance')->where(array('userid' => userid()))->order('id desc')->find();
			$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();
			$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec('cny', $num);
			$rs[] = $finance_nameid = $mo->table('tw_mytx')->add(array('userid' => userid(), 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'name' => $userBank['name'], 'truename' => $user['truename'], 'bank' => $userBank['bank'], 'bankprov' => $userBank['bankprov'], 'bankcity' => $userBank['bankcity'], 'bankaddr' => $userBank['bankaddr'], 'bankcard' => $userBank['bankcard'], 'addtime' => time(), 'status' => 0));
			$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();
			$finance_hash = md5(userid() . $finance_num_user_coin['cny'] . $finance_num_user_coin['cnyd'] . $mum . $finance_mum_user_coin['cny'] . $finance_mum_user_coin['cnyd'] . MSCODE . 'tp3.net.cn');
			$finance_num = $finance_num_user_coin['cny'] + $finance_num_user_coin['cnyd'];

			if ($finance['mum'] < $finance_num) {
				$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
			}
			else {
				$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
			}

			$rs[] = $mo->table('tw_finance')->add(array('userid' => userid(), 'coinname' => 'cny', 'num_a' => $finance_num_user_coin['cny'], 'num_b' => $finance_num_user_coin['cnyd'], 'num' => $finance_num_user_coin['cny'] + $finance_num_user_coin['cnyd'], 'fee' => $num, 'type' => 2, 'name' => 'mytx', 'nameid' => $finance_nameid, 'remark' => '人民币提现-申请提现', 'mum_a' => $finance_mum_user_coin['cny'], 'mum_b' => $finance_mum_user_coin['cnyd'], 'mum' => $finance_mum_user_coin['cny'] + $finance_mum_user_coin['cnyd'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

			// 处理资金变更日志-----------------S

			// 'position' => 1前台-操作位置 optype=5 提现申请-动作类型 'cointype' => 1人民币-资金类型 'plusminus' => 0减少类型

			$mo->table('tw_finance_log')->add(array('username' => session('userName'), 'adminname' => session('userName'), 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 5, 'position' => 1, 'cointype' => 1, 'old_amount' => $finance_num_user_coin['cny'], 'new_amount' => $finance_mum_user_coin['cny'], 'userid' => session('userId'), 'adminid' => session('userId'),'addip'=>get_client_ip()));

			// 处理资金变更日志-----------------E

			if (check_arr($rs)) {
				session('mytx_verify', null);
				session('chkmobile', null);
				$mo->commit();
				$this->success('提现订单创建成功！',$extra);
			}
			else {
				throw new \Think\Exception('提现订单创建失败！');
			}
		}catch(\Think\Exception $e){
			$mo->rollback();
			$this->error('提现订单创建失败！',$extra);
		}
	}

	public function mytxChexiao($id)
	{


		// 过滤非法字符----------------S

		if (checkstr($id)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录！');
		}

		if (!check($id, 'd')) {
			$this->error('参数错误！');
		}

		$mytx = M('Mytx')->where(array('id' => $id))->find();

		if (!$mytx) {
			$this->error('提现订单不存在！');
		}

		if ($mytx['userid'] != userid()) {
			$this->error('非法操作！');
		}

		if ($mytx['status'] != 0) {
			$this->error('订单不能撤销！');
		}

		$mo = M();
		$mo->startTrans();
		//$mo->execute('lock tables tw_user_coin write,tw_mytx write,tw_finance write,tw_finance_log write');
		$rs = array();
		$finance = $mo->table('tw_finance')->where(array('userid' => $mytx['userid']))->order('id desc')->find();
		$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mytx['userid']))->find();
		$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $mytx['userid']))->setInc('cny', $mytx['num']);
		$rs[] = $mo->table('tw_mytx')->where(array('id' => $mytx['id']))->setField('status', 2);
		$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mytx['userid']))->find();
		$finance_hash = md5($mytx['userid'] . $finance_num_user_coin['cny'] . $finance_num_user_coin['cnyd'] . $mytx['num'] . $finance_mum_user_coin['cny'] . $finance_mum_user_coin['cnyd'] . MSCODE . 'tp3.net.cn');
		$finance_num = $finance_num_user_coin['cny'] + $finance_num_user_coin['cnyd'];

		if ($finance['mum'] < $finance_num) {
			$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
		}
		else {
			$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
		}

		$rs[] = $mo->table('tw_finance')->add(array('userid' => $mytx['userid'], 'coinname' => 'cny', 'num_a' => $finance_num_user_coin['cny'], 'num_b' => $finance_num_user_coin['cnyd'], 'num' => $finance_num_user_coin['cny'] + $finance_num_user_coin['cnyd'], 'fee' => $mytx['num'], 'type' => 1, 'name' => 'mytx', 'nameid' => $mytx['id'], 'remark' => '人民币提现-撤销提现', 'mum_a' => $finance_mum_user_coin['cny'], 'mum_b' => $finance_mum_user_coin['cnyd'], 'mum' => $finance_mum_user_coin['cny'] + $finance_mum_user_coin['cnyd'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));


		// 处理资金变更日志-----------------S

		$mo->table('tw_finance_log')->add(array('username' => session('userName'), 'adminname' => session('userName'), 'addtime' => time(), 'plusminus' => 1, 'amount' => $mytx['num'], 'optype' => 24, 'position' => 1, 'cointype' => 1, 'old_amount' => $finance_num_user_coin['cny'], 'new_amount' => $finance_mum_user_coin['cny'], 'userid' => session('userId'), 'adminid' => session('userId'),'addip'=>get_client_ip()));

		// 处理资金变更日志-----------------E

		if (check_arr($rs)) {
			$mo->commit();
			$this->success('操作成功！');
		}
		else {
			$mo->rollback();
			$this->error('操作失败！');
		}
	}

	public function upmyzc($coin, $num, $addr, $paypassword, $mobile_verify, $token, $chkstyle, $email_verify,$guga)
	{


		$extra='';
		
		if(!session('myzctoken')) {
			set_token('myzc');
		}
		if(!empty($token)){
			$res = valid_token('myzc',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('myzctoken'));
			}
		}else{
			$this->error('缺少参数！',session('myzctoken'));
		}
		$extra=session('myzctoken');

		if (!userid()) {
			$this->error('您没有登录请先登录！',$extra);
		}
		
		$user = M('User')->where(array('id' => userid()))->find();
		
		if($chkstyle=='mobile'){
			if (!check($mobile_verify, 'd')) {
				$this->error('短信验证码格式错误！',$extra);
			}

			if ($user['mobile'] != session('chkmobile') ) {
				$this->error('短信验证码错误！',$extra);
			}

			if ($mobile_verify != session('myzc_verify')) {
				$this->error('短信验证码错误！',$extra);
			}
		}elseif($chkstyle=='email'){
			if (!check($email_verify, 'd')) {
				$this->error('邮箱验证码格式错误！',$extra);
			}
			
			if ($user['email'] != session('chkemail')) {
				$this->error('邮箱验证码错误！',$extra);
			}

			if ($email_verify != session('emailmyzc_verify')) {
				$this->error('邮箱验证码错误！',$extra);
			}
		}else{
			$this->error("请选择验证方式",$extra);
		}

		$num = abs($num);

		if (!check($num, 'currency')) {
			$this->error('数量格式错误！',$extra);
		}
		$addr = trim($addr);
		if (!check($addr, 'dw')) {
			$this->error('钱包地址格式错误！',$extra);
		}

		if (!check($paypassword, 'password')) {
			$this->error('密码格式为6~16位，不含特殊符号！',$extra);
		}

		if (!check($coin, 'n')) {
			$this->error('币格式错误！',$extra);
		}

		$Coins = M('Coin')->where(array('name' => $coin))->find();
		$cointype = getCoinType($coin);

		if (!$Coins) {
			$this->error('币种不存在！',$extra);
		}

		$myzc_min = ($Coins['zc_min'] ? abs($Coins['zc_min']) : 0.0001);
		$myzc_max = ($Coins['zc_max'] ? abs($Coins['zc_max']) : 10000000);

		if ($num < $myzc_min) {
			$this->error('转出数量超过系统最小限制！',$extra);
		}

		if ($myzc_max < $num) {
			$this->error('转出数量超过系统最大限制！',$extra);
		}

		if(!empty($user['ga'])){
			$is_ti=explode("|",$user['ga']);
			if($is_ti[2]==1){
				//谷歌验证码
				$secret = $is_ti[0];
				if (!$secret) {
					$this->error('谷歌验证错误！',$extra);
				}
				$ga = new \Common\Ext\GoogleAuthenticator();
				if (!$ga->verifyCode($secret, $guga, 1)) {
					$this->error('谷歌验证码错误！',$extra);
				}
        	}
		}

		if(shiming($user['id']) < 3){
			$this->error("请先完成实名认证并通过管理员审核！",$extra);
		}
		
		if (md5($paypassword) != $user['paypassword']) {
			$this->error('交易密码错误！',$extra);
		}

		$user_coin = M('UserCoin')->where(array('userid' => userid()))->find();

		if ($user_coin[$coin] < $num) {
			$this->error('可用余额不足',$extra);
		}

        //超出当日最大限额
        $today_start = strtotime(date('Y-m-d 00:00:00', time()));
        $today_end = $today_start + 86399;
        $zcjl = M('myzc')->where(array('userid' => userid(), 'coinname' => $coin, 'status' => 1))->select();
        $sum_num = 0;
        foreach ($zcjl as $key => $val){
            if ($val['addtime'] > $today_start && $val['addtime'] < $today_end){
                $sum_num += $val['num'];
            }
        }
        $all_sum = $sum_num + $num;
        $coin_info = M("$coin")->where(array('short_name' => 'CNY'))->find();
        $all_money = $all_sum * $coin_info['price'];
        $day_withdraw = C('day_withdraw');
        if ($all_money > $day_withdraw){
            $this->error('超出当日提现金额',$extra);
        }


		if (!empty($Coins['zc_fee']) || !empty($Coins['zc_fee_bili'])) {
			$fee = round($Coins['zc_fee'], 8) + round($num*$Coins['zc_fee_bili']/100, 8);
			$mum = round($num - $fee, 8);

			if ($mum <= 0) {
				$this->error('转出数量必须大于手续费！',$extra);
			}

			if ($fee < 0) {
				$this->error('转出手续费设置错误！',$extra);
			}
		}
		else {
			$fee = 0;
			$mum = $num;
		}

		if ($Coins['type'] == 'qbb') {
			//钱包地址
			if($Coins['tp_qj'] == "eth" || $Coins['tp_qj'] == "erc20"){
				$qbdz = "ethb";
			}else{
				$qbdz = $coin . 'b';
			}
			$peer = M('UserCoin')->where(array($qbdz => $addr))->find();
			if (!empty($peer)) {
				try{
					$mo = M();
					$mo->startTrans();

					$rs = array();
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $user['id']))->setDec($coin, $num);
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->setInc($coin, $mum);

					$zcid = $rs[] = $mo->table('tw_myzc')->add(array('userid' => $user['id'], 'username' => $addr, 'coinname' => $coin, 'txid' => md5($addr . $user_coin[$qbdz] . time()), 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => 1, 'cointype'=>$cointype));
					$rs[] = $mo->table('tw_myzr')->add(array('userid' => $peer['userid'], 'username' => $user_coin[$qbdz], 'coinname' => $coin, 'txid' => md5($user_coin[$qbdz] . $addr . time()), 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => 1));

					if ($fee > 0) {
						$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => $zcid, 'coinname' => $coin, 'txid' => md5($user_coin[$qbdz] . $user['username'] . time()), 'num' => $num, 'fee' => $fee, 'type' => 1, 'mum' => $mum, 'addtime' => time(), 'status' => 1));
					}

					// 处理资金变更日志-----------------S

					$user_zj_coin = $mo->table('tw_user_coin')->where(array('userid' => userid()))->find();

					// 转出人记录
					$mo->table('tw_finance_log')->add(array('username' => $user['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 6, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $user_coin[$coin], 'new_amount' => $user_zj_coin[$coin], 'userid' => $user['id'], 'adminid' => $user['id'], 'addip'=>get_client_ip()));

					// 获取用户信息
					$peer_info = $mo->table('tw_user')->where(array('id' => $peer['userid']))->find();
					$user_peer_coin = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->find();

					// 接受人记录
					$mo->table('tw_finance_log')->add(array('username' => $peer_info['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $mum, 'optype' => 7, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $peer[$coin], 'new_amount' => $user_peer_coin[$coin], 'userid' => $peer['userid'], 'adminid' => $user['id'], 'addip'=>get_client_ip()));

					// 处理资金变更日志-----------------E

					if (check_arr($rs)) {
						$mo->commit();
						session('myzc_verify', null);
						session('chkmobile', null);
						$this->success('转账成功！',$extra);
					}else {
						throw new \Think\Exception('转账失败!');
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error('转账失败!',$extra);
				}
			}else {
				if($Coins['tp_qj'] == 'btc'){
					$dj_username = $Coins['dj_yh_mytx'];
					$dj_password = $Coins['dj_mm_mytx'];
					$dj_address = $Coins['dj_zj_mytx'];
					$dj_port = $Coins['dj_dk_mytx'];
					$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
					
					if($Coins['name'] == 'usdt'){
						$json = $CoinClient->getnetworkinfo();

						if (!isset($json['version']) || !$json['version']) {
							$this->error('钱包链接失败！',$extra);
						}
					}else{
						$json = $CoinClient->getwalletinfo();
						if (!isset($json['walletversion']) || !$json['walletversion']) {
							$this->error('钱包链接失败！',$extra);
						}
					}

					$valid_res = $CoinClient->validateaddress($addr);

					if (!$valid_res['isvalid']) {
						$this->error($addr . '不是一个有效的钱包地址！',$extra);
					}

					$auto_status = ($Coins['zc_zd'] && ($num < $Coins['zc_zd']) ? 1 : 0);

					if ($auto_status == 1 && $json['balance'] < $num) {
						$this->error('钱包余额不足',$extra);
					}
					try{

						$mo = M();
						$mo->startTrans();

						$rs = array();
						$rs[] = $r = $mo->table('tw_user_coin')->where(array('userid' => $user['id']))->setDec($coin, $num);
						$rs[] = $aid = $mo->table('tw_myzc')->add(array('userid' => $user['id'], 'username' => $addr, 'coinname' => $coin, 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => $auto_status, 'cointype'=>$cointype));

						if ($fee && $auto_status) {
							$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => $aid, 'coinname' => $coin, 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'type' => 2, 'addtime' => time(), 'status' => 1));
						}

						// 处理资金变更日志-----------------S

						$user_zj_coin = $mo->table('tw_user_coin')->where(array('userid' => $user['id']))->find();

						// 转出人记录
						
						$mo->table('tw_finance_log')->add(array('username' => $user['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 6, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $user_coin[$coin], 'new_amount' => $user_zj_coin[$coin], 'userid' => $user['id'], 'adminid' => $user['id'], 'addip'=>get_client_ip()));

						// 处理资金变更日志-----------------E

						if (check_arr($rs)) {
							if ($auto_status) {
								if($Coins['name'] == 'usdt'){
									$sendrs = $CoinClient->omni_send((string) C('usdtzcaddr'), (string) $addr, 1, (string) $mum);
								}else{
									$sendrs = $CoinClient->sendtoaddress($addr, floatval($mum));
								}
								if ($sendrs) {
									$res = $mo->table('tw_myzc')->where(array('id'=>$aid))->save(array('txid'=>$sendrs));
									$mo->commit();
								}else{
									throw new \Think\Exception('转出失败!');
								}
							}else {
								$mo->commit();
								session('myzc_verify', null);
								session('chkmobile', null);
								$this->success('转出申请成功,请等待审核！',$extra);
							}
						}else {
							throw new \Think\Exception('转出失败!');
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						$this->error('转出失败!',$extra);
					}
					if(!$auto_status){
						$flag = 1;
					}else if ($auto_status && $sendrs) {
						$flag = 1;
						$arr = json_decode($sendrs, true);
						if (isset($arr['status']) && ($arr['status'] == 0)) {
							$flag = 0;
						}
					}else {
						$flag = 0;
					}

					if (!$flag) {
						$this->error('钱包服务器转出失败',$extra);
					}
					else {
						$this->success('转出成功!',$extra);
					}
				}else{
					if(strlen($addr) < 10 || substr($addr, 0, 2) != "0x"){
						$this->error("转出地址格式错误！",$extra);
					}
					
					try{
						$mo = M();
						$mo->startTrans();
						$rs = array();
						$rs[] = $r = $mo->table('tw_user_coin')->where(array('userid' => $user['id']))->setDec($coin, $num);
						$rs[] = $aid = $mo->table('tw_myzc')->add(array('userid' => $user['id'], 'username' => $addr, 'coinname' => $coin, 'num' => $num, 'fee' => $fee, 'mum' => $mum, 'addtime' => time(), 'status' => 0, 'cointype'=>$cointype));

						// 处理资金变更日志-----------------S

						$user_zj_coin = $mo->table('tw_user_coin')->where(array('userid' => $user['id']))->find();

						// 转出人记录
						
						$mo->table('tw_finance_log')->add(array('username' => $user['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 0, 'amount' => $num, 'optype' => 6, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $user_coin[$coin], 'new_amount' => $user_zj_coin[$coin], 'userid' => $user['id'], 'adminid' => $user['id'],'addip'=>get_client_ip()));

						// 处理资金变更日志-----------------E

						if (check_arr($rs)) {
							$mo->commit();
							$this->success("转出申请成功，请等待管理员审核！",$extra);
						}else {
							throw new \Think\Exception("转出失败！");
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						$this->error("转出失败！",$extra);
					}
				}
			}
		}
	}

	public function mywt($market = NULL, $type = NULL, $status = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($market) || checkstr($type) || checkstr($status)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		$this->assign('prompt_text', D('Text')->get_content('finance_mywt'));
		check_server();
		$Coins = M('Coin')->where(array('status' => 1))->select();

		foreach ($Coins as $k => $v) {
			$coin_list[$v['name']] = $v;
		}

		$this->assign('coin_list', $coin_list);
		$Market = M('Market')->where(array('status' => 1))->select();

		foreach ($Market as $k => $v) {
			$v['xnb'] = explode('_', $v['name'])[0];
			$v['rmb'] = explode('_', $v['name'])[1];
			$market_list[$v['name']] = $v;
		}

		$this->assign('market_list', $market_list);

		if (!$market_list[$market]) {
			$market = $Market[0]['name'];
		}

		$where['market'] = $market;

		if (($type == 1) || ($type == 2)) {
			$where['type'] = $type;
		}

		if (($status == 1) || ($status == 2) || ($status == 3)) {
			$where['status'] = $status - 1;
		}

		$where['userid'] = userid();
		$this->assign('market', $market);
		$this->assign('type', $type);
		$this->assign('status', $status);
		$Mobile = M('Trade');
		$count = $Mobile->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$Page->parameter .= 'type=' . $type . '&status=' . $status . '&market=' . $market . '&';
		$show = $Page->show();
		$list = $Mobile->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$market_round=M('market')->where(array('name'=>$v['market']))->field('round')->find();
			$list[$k]['num'] = number_format($v['num'],8-$market_round['round'],'.','');
			$list[$k]['price'] = number_format($v['price'],$market_round['round'],'.','');
			$list[$k]['deal'] = number_format($v['deal'],4,'.','');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$cancel_token = set_token('cancel');
		$this->assign('cancel_token',$cancel_token);
		$this->display();
	}

	public function mycj($market = NULL, $type = NULL)
	{

		// 过滤非法字符----------------S

		if (checkstr($market) || checkstr($type)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			redirect('/#login');
		}

		$this->assign('prompt_text', D('Text')->get_content('finance_mycj'));
		check_server();
		$Coins = M('Coin')->where(array('status' => 1))->select();

		foreach ($Coins as $k => $v) {
			$coin_list[$v['name']] = $v;
		}

		$this->assign('coin_list', $coin_list);
		$Market = M('Market')->where(array('status' => 1))->select();

		foreach ($Market as $k => $v) {
			$v['xnb'] = explode('_', $v['name'])[0];
			$v['rmb'] = explode('_', $v['name'])[1];
			$market_list[$v['name']] = $v;
		}

		$this->assign('market_list', $market_list);

		if (!$market_list[$market]) {
			$market = $Market[0]['name'];
		}

		if ($type == 1) {
			$where = 'userid=' . userid() . ' && market=\'' . $market . '\'';
		}
		else if ($type == 2) {
			$where = 'peerid=' . userid() . ' && market=\'' . $market . '\'';
		}
		else {
			$where = '((userid=' . userid() . ') || (peerid=' . userid() . ')) && market=\'' . $market . '\'';
		}

		$this->assign('market', $market);
		$this->assign('type', $type);
		$this->assign('userid', userid());
		$Mobile = M('TradeLog');
		$count = $Mobile->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$Page->parameter .= 'type=' . $type . '&market=' . $market . '&';
		$show = $Page->show();
		$list = $Mobile->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$market_info = M('market')->where(array('name'=>$v['market']))->field('round')->find();
			$list[$k]['num'] = number_format($v['num'],8-$market_info['round'],'.','');
			$list[$k]['price'] = number_format($v['price'],$market_info['round'],'.','');
			$list[$k]['mum'] = number_format($v['mum'],4,'.','');
			$list[$k]['fee_buy'] = number_format($v['fee_buy'],4,'.','');
			$list[$k]['fee_sell'] = number_format($v['fee_sell'],4,'.','');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function incentive()
	{
		if (!userid()) {
			redirect('/#login');
		}

		$where['userid'] = userid();
		$count = M('Incentive')->where($where)->count();
		$Page = new \Think\Page($count, 15);

		$show = $Page->show();
		$list = M('incentive')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

}

?>