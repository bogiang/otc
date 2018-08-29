<?php
namespace Admin\Controller;

class ConfigController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","edit","image","mobile","mobileEdit","contact","contactEdit","coin","coinEdit","coinStatus","textStatus","coinInfo","coinUser","coinQing","coinImage","text","textEdit","qita","qitaEdit","daohang","daohangEdit","daohangStatus","createAddr","changeAddr");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function index()
	{
		$config_info = M('Config')->where(array('id' => 1))->find();
		$config_info['web_logo'] = stripslashes($config_info['web_logo']);
		$this->data = $config_info;
		$this->display();
	}

	public function edit()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}
		$_POST['web_logo'] = !empty($_POST['img']) ? addslashes($_POST['img']) : "";

		unset($_POST['img']);
		$_POST['web_reg']=!empty($_POST['ueditorcontent']) ? addslashes($_POST['ueditorcontent']) : "";
		unset($_POST['ueditorcontent']);
		$_POST['en_web_reg']=!empty($_POST['en_ueditorcontent']) ? addslashes($_POST['en_ueditorcontent']) : "";
		unset($_POST['en_ueditorcontent']);
		if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function mobile()
	{
		$this->data = M('Config')->where(array('id' => 1))->find();
		$this->display();
	}

	public function mobileEdit()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function contact()
	{
		$this->data = M('Config')->where(array('id' => 1))->find();
		$this->display();
	}

	public function contactEdit()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function coin($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Coin')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Coin')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function coinEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$data = array();
			}
			else {
				$data = M('Coin')->where(array('id' => trim($_GET['id'])))->find();
			}
			$zcmin = $data['zc_min'];
			$zcmin_arr = explode(".",$zcmin);
			$k=0;
			for($i=1;$i<strlen($zcmin_arr[1]);$i++){
				if(substr($zcmin_arr[1],$i-1,$i)>0){
					$k=$i;
				}
			}
			if($k>0){
				$baoliu = substr($zcmin_arr[1],0,$k);
				$data['zc_min'] = $zcmin_arr[0].".".$baoliu;
			}else{
				$data['zc_min'] = $zcmin_arr[0];
			}
			$zcmax = $data['zc_max'];
			$zcmax_arr = explode(".",$zcmax);
			$k=0;
			for($i=1;$i<strlen($zcmax_arr[1]);$i++){
				if(substr($zcmax_arr[1],$i-1,$i)>0){
					$k=$i;
				}
			}
			if($k>0){
				$baoliu = substr($zcmax_arr[1],0,$k);
				$data['zc_max'] = $zcmax_arr[0].".".$baoliu;
			}else{
				$data['zc_max'] = $zcmax_arr[0];
			}
			$this->assign('data',$data);
			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}
			$_POST['js_sm'] = $_POST['ueditorcontent'];
			unset($_POST['ueditorcontent']);
			$_POST['img'] = !empty($_POST['img']) ? addslashes($_POST['img']) : '';
			$_POST['fee_bili'] = floatval($_POST['fee_bili']);

			if ($_POST['fee_bili'] && (($_POST['fee_bili'] < 0.01) || (100 < $_POST['fee_bili']))) {
				$this->error('挂单比例只能是0.01--100之间(不用填写%)！');
			}

			$_POST['zr_zs'] = floatval($_POST['zr_zs']);

			if ($_POST['zr_zs'] && (($_POST['zr_zs'] < 0.01) || (100 < $_POST['zr_zs']))) {
				$this->error('转入赠送只能是0.01--100之间(不用填写%)！');
			}

			$_POST['zr_dz'] = intval($_POST['zr_dz']);
			$_POST['zc_fee'] = floatval($_POST['zc_fee']);
			$_POST['zc_fee_bili'] = floatval($_POST['zc_fee_bili']);

			if (!empty($_POST['zc_fee']) && ($_POST['zc_fee'] < 0.00001)) {
				$this->error('转出手续费必须大于0.00001！');
			}
			if (!empty($_POST['zc_fee_bili']) && ($_POST['zc_fee_bili'] < 0.01)) {
				$this->error('转出手续费比例必须大于0.01！');
			}

			if ($_POST['zc_user']) {
				if (!check($_POST['zc_user'], 'dw')) {
					$this->error('官方手续费地址格式不正确！');
				}

				$ZcUser = M('UserCoin')->where(array($_POST['name'] . 'b' => $_POST['zc_user']))->find();

				if (!$ZcUser) {
					$this->error('在系统中查询不到[官方手续费地址],请务必填写正确！');
				}
			}

			$_POST['zc_min'] = floatval($_POST['zc_min']);
			$_POST['zc_max'] = floatval($_POST['zc_max']);
			$_POST['sort'] = intval($_POST['sort']);
			$_POST['price'] = floatval($_POST['price']);

			if ($_POST['id']) {
				try{
					$mo = M();
					$mo->startTrans();
					$rs[] = $mo->table('tw_coin')->save($_POST);
					$ad_buy_fee = $mo->table('tw_ad_buy')->order('id desc')->find();
					if($ad_buy_fee['fee'] != $_POST['cs_ts']){
						 $mo->table('tw_ad_buy')->where(array('fee'=>array('neq',$_POST['cs_ts'])))->save(array('fee'=>$_POST['cs_ts']));
					}
					$ad_sell_fee = $mo->table('tw_ad_sell')->order('id desc')->find();
					if($ad_sell_fee['fee'] != $_POST['cs_ts']){
						 $mo->table('tw_ad_sell')->where(array('fee'=>array('neq',$_POST['cs_ts'])))->save(array('fee'=>$_POST['cs_ts']));
					}
    				if($_POST['name']=='znc' && $_POST['price']>0){
    					//修改tw_znc表格
    					$mo->table('tw_znc')->where("id=1")->save(array('price'=>$_POST['price'],'updatetime'=>time()));
    				}
					if(check_arr($rs)) {
						$mo->commit();
						$this->success('操作成功！');
					}
					else {
						throw new \Think\Exception('操作失败！');
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error($e->getMessage().serialize($rs));
				}
			}
			else {
				if (!check($_POST['name'], 'n')) {
					$this->error('币种简称只能是小写字母！');
				}

				$_POST['name'] = strtolower($_POST['name']);

				if (check($_POST['name'], 'username')) {
					$this->error('币种名称格式不正确！');
				}

				if (M('Coin')->where(array('name' => $_POST['name']))->find()) {
					$this->error('币种存在！');
				}

				$rea = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . '` DECIMAL(20,8) UNSIGNED NOT NULL');
				$reb = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . 'd` DECIMAL(20,8) UNSIGNED NOT NULL ');
				$rec = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . 'b` VARCHAR(200) NOT NULL ');
				try{
					$mo = M();
					$mo->startTrans();
					$rs[] = $mo->table('tw_coin')->add($_POST);
					$ad_buy_fee = $mo->table('tw_ad_buy')->order('id desc')->find();
					if($ad_buy_fee['fee'] != $_POST['cs_ts']){
						$rs[] = $mo->table('tw_ad_buy')->where(array('fee'=>array('neq',$_POST['cs_ts'])))->save(array('fee'=>$_POST['cs_ts']));
					}
					$ad_sell_fee = $mo->table('tw_ad_sell')->order('id desc')->find();
					if($ad_sell_fee['fee'] != $_POST['cs_ts']){
						$rs[] = $mo->table('tw_ad_sell')->where(array('fee'=>array('neq',$_POST['cs_ts'])))->save(array('fee'=>$_POST['cs_ts']));
					}
					if(check_arr($rs)) {
						$mo->commit();
						$this->success('操作成功！');
					}
					else {
						throw new \Think\Exception('操作失败！');
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error($e->getMessage().serialize($rs));
				}
			}
		}
	}

	public function coinStatus()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (IS_POST) {
			$id = array();
			$id = implode(',', $_POST['id']);
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		$method = $_GET['type'];

		switch (strtolower($method)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'delete':
			$rs = M('Coin')->where($where)->select();

			foreach ($rs as $k => $v) {
				$rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name']);
				$rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name'] . 'd');
				$rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name'] . 'b');
			}

			if (M('Coin')->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('参数非法');
		}

		if (M('Coin')->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}
	public function textStatus()
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (IS_POST) {
			$id = array();
			$id = implode(',', $_POST['id']);
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		$method = $_GET['type'];

		switch (strtolower($method)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;
		default:
			$this->error('参数非法');
		}

		if (M('text')->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function coinInfo($coin)
	{
		$coin_info = M('Coin')->where(array('name'=>$coin))->find();
		if(empty($coin_info)){
			$this->error('参数错误！');
		}
		if($coin_info['tp_qj'] == "eth"){
			$info['coin'] = 'eth';
			$eth_sum = 0;
			$ethsum = 0;
			$all_user = M('UserCoin')->where(array('ethb'=>array('neq','')))->select();
			foreach($all_user as $user){
				$eth_account = \Common\Ext\EthWallet::getBalance($user['ethb']);
				$account_info = json_decode($eth_account);
				$account_yue = \Common\Ext\EthWallet::toEth($account_info->result);
				$eth_sum = $eth_sum + $account_yue;
				$ethsum = $ethsum + $user['eth'] + $user['ethd'];
			}
			$info['qbnum'] = $eth_sum;
			$info['znnum'] = $ethsum;
		}elseif($coin_info['tp_qj'] == "btc"){
			$dj_username = C('coin')[$coin]['dj_yh'];
			$dj_password = C('coin')[$coin]['dj_mm'];
			$dj_address = C('coin')[$coin]['dj_zj'];
			$dj_port = C('coin')[$coin]['dj_dk'];
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port);

			if (!$CoinClient) {
				$this->error('收币钱包对接失败！');
			}

			$info['b'] = $CoinClient->getwalletinfo();
			$info['b']['balance'] = numform($info['b']['balance']);
			$info['qbnum'] = $info['b']['balance'];
			$info['znnum'] = M('UserCoin')->sum($coin) + M('UserCoin')->sum($coin . 'd');
			$info['coin'] = $coin;
			if($coin_info['name'] == "usdt"){
				$usdtsum = 0;
				$arr = $CoinClient->listaccounts();
				if(!empty($arr)){
					foreach($arr as $k=>$v){
						if(!empty($k)){
							$taddress = $CoinClient->getaddressesbyaccount((string)$k);
							if(!empty($taddress)){
								$usdt = $CoinClient->omni_getbalance((string)$taddress[0],31);
								if(!empty($usdt)){
									$usdtsum += $usdt['balance'];
								}
							}
						}
					}
				}
				$info['usdtsum'] = $usdtsum;
			}
			$dj_username = C('coin')[$coin]['dj_yh_mytx'];
			$dj_password = C('coin')[$coin]['dj_mm_mytx'];
			$dj_address = C('coin')[$coin]['dj_zj_mytx'];
			$dj_port = C('coin')[$coin]['dj_dk_mytx'];
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port);

			if (!$CoinClient) {
				$this->error('提币钱包对接失败！');
			}
			$info1['b'] = $CoinClient->getwalletinfo();
			$info1['b']['balance'] = numform($info1['b']['balance']);
			$info1['qbnum'] = $info1['b']['balance'];
			if(C('coin')[$coin]['dj_yh_mytx'] != C('coin')[$coin]['dj_yh'] && $coin_info['name'] == "usdt"){
				$usdtsum1 = 0;
				$arr1 = $CoinClient->listaccounts();
				if(!empty($arr1)){
					foreach($arr1 as $kk=>$vv){
						if(!empty($kk)){
							$taddress1 = $CoinClient->getaddressesbyaccount((string)$kk);
							if(!empty($taddress1)){
								$usdt1 = $CoinClient->omni_getbalance((string)$taddress1[0],31);
								if(!empty($usdt1)){
									$usdtsum1 += $usdt1['balance'];
								}
							}
						}
					}
				}
				$info1['usdtsum'] = $usdtsum1;
			}
		}elseif($coin_info['tp_qj'] == "erc20"){
			$info['coin'] = $coin;
			$info['znnum'] = M('UserCoin')->sum($coin) + M('UserCoin')->sum($coin . 'd');
			$info['qbnum'] = '-';
		}
		$this->assign('data', $info);
		$this->assign('data1', $info1);
		$this->display();
	}

	public function coinUser($coin)
	{
		$coin_info = M('Coin')->where(array('name'=>$coin))->find();
		if(empty($coin_info)){
			$this->error('参数错误！');
		}
		$list = array();
		if($coin_info['tp_qj'] == "erc20" || $coin_info['tp_qj'] == "eth"){
			$data = \Common\Ext\EthWallet::accounts();
			$accounts = json_decode($data);
			$n = sizeof($accounts->result);
			for($i=0;$i<$n; $i++){
				if(!empty($accounts->result[$i])){
					$list[$i]['addr'] = $accounts->result[$i];
					$user_coin = M('UserCoin')->where(array('ethb' => $list[$i]['addr']))->find();
					if(!empty($user_coin)){
						$list[$i]['key'] = M('User')->where(array('id'=>$user_coin['userid']))->getField('username');
						$amount = \Common\Ext\EthWallet::getBalance($list[$i]['addr']);
						$amount_obj = json_decode($amount);
						if(!empty($amount_obj->result)){
							$list[$i]['num'] = \Common\Ext\EthWallet::toEth($amount_obj->result).' eth';
						}
						if(empty($list[$i]['num'])){
							$list[$i]['num'] = 0;
						}
						$list[$i]['xnb'] = $user_coin[$coin];
						$list[$i]['xnbd'] = $user_coin[$coin . 'd'];
						$list[$i]['zj'] = $list[$k]['xnb'] + $list[$k]['xnbd'];
					}else{
						unset($list[$i]);
					}
				}
			}
		}elseif($coin_info['tp_qj'] == "btc"){
			$dj_username = C('coin')[$coin]['dj_yh'];
			$dj_password = C('coin')[$coin]['dj_mm'];
			$dj_address = C('coin')[$coin]['dj_zj'];
			$dj_port = C('coin')[$coin]['dj_dk'];
			
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port);
			if (!$CoinClient) {
				$this->error('钱包对接失败！');
			}

			$arr = $CoinClient->listaccounts();
			$ii=0;
			foreach ($arr as $k => $v) {
				if (!empty($k)) {
					$list[$ii]['key'] = $k;
					$list[$ii]['num'] = numform($v);					
					$userid = M('User')->where(array('username' => $k))->getField('id');
					$user_coin = M('UserCoin')->where(array('userid' => $userid))->find();
					$list[$ii]['xnb'] = $user_coin[$coin];
					$list[$ii]['xnbd'] = $user_coin[$coin . 'd'];
					$list[$ii]['zj'] = $list[$ii]['xnb'] + $list[$ii]['xnbd'];
					$list[$ii]['addr'] = $user_coin[$coin . 'b'];
					if(empty($list[$ii]['addr'])){
						unset($list[$ii]);
					}else{
						if($coin == "usdt"){
							$addr = (string) $list[$ii]['addr'];
							$usdt = $CoinClient->omni_getbalance($addr,31);
							$list[$ii]['usdt'] = numform($usdt['balance']);
						}
					}
					$ii++;
				}
			}
		}
		$this->assign('list', $list);
		$this->assign('coin',$coin);
		$this->display();
	}

	public function coinQing($coin)
	{
		if (!C('coin')[$coin]) {
			$this->error('参数错误！');
		}

		$info = M()->execute('UPDATE `tw_user_coin` SET `' . trim($coin) . 'b`=\'\' ;');

		if ($info) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function coinImage()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = './Upload/coin/';
		$upload->autoSub = false;
		$info = $upload->upload();

		foreach ($info as $k => $v) {
			$path = $v['savepath'] . $v['savename'];
			echo $path;
			exit();
		}
	}

	public function text($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Text')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Text')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function textEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('Text')->where(array('id' => trim($id)))->find();
			}
			else {
				$this->data = null;
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}
			
			$_POST['content'] = $_POST['ueditorcontent'];
			unset($_POST['ueditorcontent']);
			
			if ($_POST['id']) {
				$rs = M('Text')->save($_POST);
			}
			else {
				$_POST['adminid'] = session('admin_id');
				$rs = M('Text')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function qita()
	{
		$hor_price = M('Hor')->where(array('short_name'=>'CNY'))->find();
		$this->assign('horprice',$hor_price['price']);
		$gic_price = M('Gic')->where(array('short_name'=>'CNY'))->find();
		$this->assign('gicprice',$gic_price['price']);
		$this->data = M('Config')->where(array('id' => 1))->find();
		$this->display();
	}

	public function qitaEdit()
	{
		$k=0;
		if(!empty($_POST['horprice'])){
			$hor_price = M('Hor')->where(array('short_name'=>'CNY'))->find();
			if(!empty($hor_price) && $hor_price['price']*1 != $_POST['horprice']*1){
				M('Hor')->where(array('short_name'=>'CNY'))->save(array('price'=>$_POST['horprice']));
				$k=1;
			}
		}
		unset($_POST['horprice']);
		if(!empty($_POST['gicprice'])){
			$gic_price = M('Gic')->where(array('short_name'=>'CNY'))->find();
			if(!empty($gic_price) && $gic_price['price']*1 != $_POST['gicprice']*1){
				M('Gic')->where(array('short_name'=>'CNY'))->save(array('price'=>$_POST['gicprice']));
				$k=1;
			}
		}
		unset($_POST['gicprice']);
		$res = M('Config')->where(array('id' => 1))->save($_POST);
		if ($k || $res) {
			$this->success('修改成功！');
		}
		else {
			$this->error('没有修改数据');
		}
	}

	public function daohang($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else if ($field == 'title') {
				$where['title'] = array('like', '%' . $name . '%');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Daohang')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Daohang')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function daohangEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('Daohang')->where(array('id' => trim($id)))->find();
			}
			else {
				$this->data = null;
			}

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			if ($_POST['id']) {
				$rs = M('Daohang')->save($_POST);
			}
			else {
				$_POST['addtime'] = time();
				$rs = M('Daohang')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
		}
	}

	public function daohangStatus($id = NULL, $type = NULL, $mobile = 'Daohang')
	{
		if (APP_DEMO) {
			$this->error('测试站暂时不能修改！');
		}

		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'del':
			$data = array('status' => -1);
			break;

		case 'delete':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}
	
	public function createAddr($coin = null){
		if (empty($_POST)) {
			if(empty($coin)){
				$this->error('缺少参数');
			}
			$coin_info = M('Coin')->where(array('name'=>$coin))->find();
			if(empty($coin_info)){
				$this->error('参数错误！');
			}
			$this->assign('coin_info',$coin_info);
			$dtcaddr = M()->table('tw_config')->where(array('id'=>1))->getField('ethaddress');
			$this->assign('zcaddr',$dtcaddr);
			if(!empty($dtcaddr)){
				$eth_account = \Common\Ext\EthWallet::getBalance($dtcaddr);
				$account_info = json_decode($eth_account);
				$balance = \Common\Ext\EthWallet::toEth($account_info->result);
				$this->assign('balance',$balance);
			}
			$this->display();
		}else{
			$password = $_POST['password'];
			$dtcpassword = M()->table('tw_config')->where(array('id'=>1))->getField('ethpassword');
			$new_account = \Common\Ext\EthWallet::newAccount($password);
			$account = json_decode($new_account,true);
			if(!empty($account['result'])){
				try{
					$rs = array();
					$mo = M();
					$mo->startTrans();
					if($dtcpassword != $password){
						$rs[] = $mo->table('tw_config')->where(array('id'=>1))->save(array('ethpassword'=>$password));
					}
					$rs[] = $mo->table('tw_config')->where(array('id'=>1))->save(array('ethaddress'=>$account['result']));
					if(check_arr($rs)){
						$mo->commit();
						$this->success('生成钱包地址成功！');
					}else{
						throw new \Think\Exception(implode("|",$rs));
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error("生成钱包地址失败！".$e->getMessage());
				}
			}
		}
	}
	
	public function changeAddr($coin = null){
		if (empty($_POST)) {
			if(empty($coin)){
				$this->error('缺少参数');
			}
			$coin_info = M()->table('tw_coin')->where(array('name'=>$coin))->find();
			if(empty($coin_info)){
				$this->error('参数错误！');
			}
			$this->assign('coin_info',$coin_info);
			$dtcaddr = M()->table('tw_config')->where(array('id'=>1))->getField('ethaddress');
			$this->assign('zcaddr',$dtcaddr);
			$eth_account = \Common\Ext\EthWallet::getBalance($dtcaddr);
			$account_info = json_decode($eth_account);
			$balance = \Common\Ext\EthWallet::toEth($account_info->result);
			$this->assign('balance',$balance);
			$this->display();
		}else{
			try{
				$mo = M();
				$mo->startTrans();
				$address = $_POST['address'];
				$password = $_POST['password'];
				$config = $mo->table('tw_config')->where(array('id'=>1))->find();
				$rs = array();
				if(!empty($address) && $address != $config['ethaddress']){
					$rs[] = $mo->table('tw_config')->where(array('id'=>1))->save(array('ethaddress'=>$address));
				}
				if(!empty($password) && $password != $config['ethpassword']){
					$rs[] = $mo->table('tw_config')->where(array('id'=>1))->save(array('ethpassword'=>$password));
				}
				if(check_arr($rs)){
					$mo->commit();
					$this->success('修改成功！');
				}else{
					throw new \Think\Exception(implode("|",$rs));
				}
			}catch(\Think\Exception $e){
				$mo->rollback();
				$this->error("修改失败！".$e->getMessage());
			}
		}
	}
}

?>