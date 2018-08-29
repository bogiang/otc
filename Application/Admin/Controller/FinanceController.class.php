<?php
namespace Admin\Controller;

class FinanceController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","myzr","myzc","myzcQueren","myzcBatch","myzcBatchLog",'mytxfee',"money_log", "amountlog", "myzcSd", "myzcCancel","ethtransfer","ethbatch","ethtrans","ethamount","deallost","donelost");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	// 资金变更日志
	public function amountlog($coin=NULL, $position = 'all', $plusminus = 'all', $name = NULL, $field = NULL, $cointype = NULL, $starttime = NULL, $endtime = NULL)
	{
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->order('id asc')->select();
		$this->assign('coinlist',$coinlist);
		if(empty($coin)){
			$coin = $coinlist[0]['name'];
		}
		$this->assign('coin',$coin);
		
		$action_arr = array(0=>"未知",1=>"买家下单",2=>"卖家下单",3=>"买家取消订单",4=>"卖家重启交易",5=>"卖家释放",6=>"买家付款超时系统自动取消订单",7=>"管理员释放币",8=>"管理员取消交易",9=>"卖广告佣金",10=>"买广告佣金");
		
		$where = array();
		if($position != 'all'){
			if($position == 2){
				$where['operator'] = array('gt',1);
			}elseif($position == 1){
				$where['operator'] = 1;
			}elseif($position == -1){
				$where['operator'] = 0;
			}
		}
		
		if($plusminus != 'all'){
			if($plusminus == 'jia'){
				$where['plusminus'] = '1';
			}else if($plusminus == 'jian'){
				$where['plusminus'] = '0';
			}
		}

		if ($field && $name) {
			if($field=="enname"){
				$user = M('User')->where(array('enname'=>$name))->find();
				$where['userid'] = $user['id'];
			}else{
				$where[$field] = $name;
			}
		}

		if($cointype){
			$where['ctype'] = $cointype;
		}

		// 时间--条件

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where['ctime'] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where['ctime'] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where['ctime'] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 10*24*60*60;
			$where['ctime'] =  array('EGT',$now_time);
		}
		
		$table = $coin."_log";
		$count = M($table)->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M($table)->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['ctime'] = date("Y-m-d H:i:s",$v['ctime']);
			$list[$k]['enname'] = M('User')->where(array('id'=>$v['userid']))->getField('enname');
			if($v['ctype'] == 1){
				$list[$k]['ctype'] = "可用";
			}else{
				$list[$k]['ctype'] = "冻结";
			}
			if(!empty($v['plusminus'])){
				$list[$k]['plusminus'] = '增加';
			}else{
				$list[$k]['plusminus'] = '减少';
			}
			if($v['operator']==0){
				$list[$k]['operator'] = '系统';
			}elseif($v['operator']==1){
				$list[$k]['operator'] = '管理员';
			}else{
				$list[$k]['operator'] = M('User')->where(array('id'=>$v['operator']))->getField('enname');
			}
			$list[$k]['action'] = $action_arr[$v['action']];
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function index($field = NULL, $name = NULL)
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

		$count = M('Mytx')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Mytx')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$name_list = array('mycz' => '人民币充值', 'mytx' => '人民币提现', 'trade' => '委托交易', 'tradelog' => '成功交易', 'issue' => '用户认购');
		$nameid_list = array('mycz' => U('Mycz/index'), 'mytx' => U('Mytx/index'), 'trade' => U('Trade/index'), 'tradelog' => U('Tradelog/index'), 'issue' => U('Issue/index'));

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['num_a'] = Num($v['num_a']);
			$list[$k]['num_b'] = Num($v['num_b']);
			$list[$k]['num'] = Num($v['num']);
			$list[$k]['fee'] = Num($v['fee']);
			$list[$k]['type'] = ($v['fee'] == 1 ? '收入' : '支出');
			$list[$k]['name'] = ($name_list[$v['name']] ? $name_list[$v['name']] : $v['name']);
			$list[$k]['nameid'] = ($name_list[$v['name']] ? $nameid_list[$v['name']] . '?id=' . $v['nameid'] : '');
			$list[$k]['mum_a'] = Num($v['mum_a']);
			$list[$k]['mum_b'] = Num($v['mum_b']);
			$list[$k]['mum'] = Num($v['mum']);
			$list[$k]['addtime'] = addtime($v['addtime']);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function mycz($field = NULL, $name = NULL, $status = NULL, $mycz_type = NULL, $time_type = NULL, $starttime = NULL, $endtime = NULL)
	{
		// 获取搜索提交的数据，方便导出表使用
		$info = array('field'=>$field,'name'=>$name,'status'=>$status);
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		// 状态--条件
		if ($status) {
			$where['status'] = $status - 1;
		}

		// 充值方式--条件
		if ($mycz_type) {
			$where['type'] = $mycz_type;
		}

		// 时间--条件

		if($time_type == 'endtimes'){
			$time_type = 'endtime';
		}

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 10*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}
		$new_list = M('Mycz')->where($where)->select();
		$czid = array();
		foreach($new_list as $v){
			array_push($czid,$v['id']);
		}
		$this->assign('czid',implode(",",$czid));
		$count = M('Mycz')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Mycz')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			if(!empty($v['bank'])){
				$list[$k]['alipay_account'] = $v['bank'].'|'.$v['alipay_account'];
			}
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['type'] = M('MyczType')->where(array('name' => $v['type']))->getField('title');
		}
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	// 导出充值明细表
	public function myczExcel()
	{
		if (IS_POST) {
			if(is_array($_POST['id'])){
				$id = implode(',', $_POST['id']);
			}else{
				$id = $_POST['id'];
			}
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);

		$list = M('Mycz')->where($where)->select();

		foreach ($list as $k => $v) {
			$list[$k]['userid'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['addtime'] = addtime($v['addtime']);
			$list[$k]['endtime'] = addtime($v['endtime']);

			if ($list[$k]['status'] == 0) {
				$list[$k]['status'] = '未付款';
			}
			else if ($list[$k]['status'] == 2) {
				$list[$k]['status'] = '人工到账';
			}
			else if ($list[$k]['status'] == 3) {
				$list[$k]['status'] = '处理中';
			}
			else if ($list[$k]['status'] == 1) {
				$list[$k]['status'] = '充值成功';
			}
			else {
				$list[$k]['status'] = '错误';
			}
		}

		$zd = M('Mycz')->getDbFields();
		array_splice($zd, 6, 2);
		array_splice($zd, 11, 1);
		$xlsName = 'cade';
		$xls = array();

		foreach ($zd as $k => $v) {
			$xls[$k][0] = $v;
			$xls[$k][1] = $v;
		}

		$xls[0][2] = '编号';
		$xls[1][2] = '用户名';
		$xls[2][2] = '充值金额';
		$xls[3][2] = '到账金额';
		$xls[4][2] = '充值方式';
		$xls[5][2] = '充值订单号';
		$xls[6][2] = '充值添加时间';
		$xls[7][2] = '充值结束时间';
		$xls[8][2] = '充值状态';
		$xls[9][2] = '真实姓名';
		$xls[10][2] = '银行账号';
		$xls[11][2] = '手续费';
		$xls[12][2] = '银行';
		
		$this->cz_exportExcel($xlsName, $xls, $list);
	}

	// 人民币充值配置
	public function myczConfig()
	{
		if (empty($_POST)) {
			$this->display();
		}
		else if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function myczStatus($id = NULL, $type = NULL, $mobile = 'Mycz')
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

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败1！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败2！');
		}
	}

	public function myczQueren()
	{
		$id = $_GET['id'];

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$mycz = M('Mycz')->where(array('id' => $id))->find();
		
		if (($mycz['status'] != 0) && ($mycz['status'] != 3)) {
			$this->error('已经处理，禁止再次操作！');
		}

		$fp = fopen("lockcz.txt", "w+");

		if(flock($fp,LOCK_EX | LOCK_NB))
		{
			$mo = M();
			$mo->startTrans();

			$rs = array();

			$finance = $mo->table('tw_finance')->where(array('userid' => $mycz['userid']))->order('id desc')->find();

			// 数据未处理时的查询（原数据）
			$finance_num_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->find();
			// 用户账户数据处理
			$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->setInc('cny', $mycz['mum']);

			$rs[] = $mo->table('tw_mycz')->where(array('id' => $mycz['id']))->save(array('status' => 2, 'mum' => $mycz['mum'], 'endtime' => time()));

			// 数据处理完的查询（新数据）
			$finance_mum_user_coin = $mo->table('tw_user_coin')->where(array('userid' => $mycz['userid']))->find();
			$finance_hash = md5($mycz['userid'] . $finance_num_user_coin['cny'] . $finance_num_user_coin['cnyd'] . $mycz['mum'] . $finance_mum_user_coin['cny'] . $finance_mum_user_coin['cnyd'] . MSCODE);
			$finance_num = $finance_num_user_coin['cny'] + $finance_num_user_coin['cnyd'];

			if ($finance['mum'] < $finance_num) {
				$finance_status = (1 < ($finance_num - $finance['mum']) ? 0 : 1);
			}
			else {
				$finance_status = (1 < ($finance['mum'] - $finance_num) ? 0 : 1);
			}

			$rs[] = $mo->table('tw_finance')->add(array('userid' => $mycz['userid'], 'coinname' => 'cny', 'num_a' => $finance_num_user_coin['cny'], 'num_b' => $finance_num_user_coin['cnyd'], 'num' => $finance_num_user_coin['cny'] + $finance_num_user_coin['cnyd'], 'fee' => $mycz['num'], 'type' => 1, 'name' => 'mycz', 'nameid' => $mycz['id'], 'remark' => '人民币充值-人工到账', 'mum_a' => $finance_mum_user_coin['cny'], 'mum_b' => $finance_mum_user_coin['cnyd'], 'mum' => $finance_mum_user_coin['cny'] + $finance_mum_user_coin['cnyd'], 'move' => $finance_hash, 'addtime' => time(), 'status' => $finance_status));

			// 处理资金变更日志-----------------S

			// 获取用户信息
			$user_info = $mo->table('tw_user')->where(array('id' => $mycz['userid']))->find();

			// optype=1 充值类型 'cointype' => 1人民币类型 'plusminus' => 1增加类型

			$rs[] = $mo->table('tw_finance_log')->add(array('username' => $user_info['username'], 'adminname' => session('admin_username'), 'addtime' => time(), 'plusminus' => 1, 'amount' => $mycz['mum'], 'optype' => 1, 'cointype' => 1, 'old_amount' => $finance_num_user_coin['cny'], 'new_amount' => $finance_mum_user_coin['cny'], 'userid' => $user_info['id'], 'adminid' => session('admin_id'),'addip'=>get_client_ip()));

			// 处理资金变更日志-----------------E

			if (check_arr($rs)) {
				$mo->commit();
				$message="操作成功";
				$res=1;
			}
			else {
				$mo->rollback();
				$message="操作失败";
				$res=0;
			}
			flock($fp,LOCK_UN);
		}else{
			$message="请不要重复提交";
			$res=0;
		}
		fclose($fp);
		if($res==1){
			$this->success($message);
		}else{
			$this->error($message);
		}
	}

	public function myczType()
	{
		$where = array();
		$count = M('MyczType')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('MyczType')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myczTypeEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('MyczType')->where(array('id' => trim($id)))->find();
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
			$_POST['img'] = !empty($_POST['img']) ? addslashes($_POST['img']) : '';
			if ($_POST['id']) {
				$rs = M('MyczType')->save($_POST);
			}
			else {
				$rs = M('MyczType')->add($_POST);
			}

			if ($rs) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}
		}
	}

	public function myczTypeStatus($id = NULL, $type = NULL, $mobile = 'MyczType')
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

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败1！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败2！');
		}
	}

	public function mytx($field = NULL, $name = NULL, $status = NULL, $time_type = NULL, $starttime = NULL, $endtime = NULL)
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

		// 时间--条件

		if($time_type == 'endtimes'){
			$time_type = 'endtime';
		}

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 10*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}
		
		$count = M('Mytx')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Mytx')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$list_new = M('Mytx')->where($where)->select();
		$txamount = 0;
		$txfee = 0;
		$dzamount = 0;
		$txid = array();
		foreach($list_new as $val){
			$txamount = $txamount+$val['num'];
			$txfee = $txfee+$val['fee'];
			$dzamount = $dzamount+$val['mum'];
			array_push($txid,$val['id']);
		}
		$this->assign('txid',implode(",",$txid));
		$this->assign('txamount',$txamount);
		$this->assign('txfee',$txfee);
		$this->assign('dzamount',$dzamount);
		$this->display();
	}

	public function mytxStatus($id = NULL, $type = NULL, $mobile = 'Mytx')
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

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (M($mobile)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败1！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败2！');
		}
	}

	public function mytxChuli()
	{
		$id = $_GET['id'];

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		if (M('Mytx')->where(array('id' => $id))->save(array('status' => 3,'endtime' => time()))) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function mytxChexiao()
	{
		$id = $_GET['id'];

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$mytx = M('Mytx')->where(array('id' => trim($_GET['id'])))->find();

		$mo = M();
		$mo->startTrans();

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

		// 获取用户信息
		$user_info = $mo->table('tw_user')->where(array('id' => $mytx['userid']))->find();

		// optype=4 提现撤销-动作类型 'cointype' => 1人民币-资金类型 'plusminus' => 1增加类型

		$rs[] = $mo->table('tw_finance_log')->add(array('username' => $user_info['username'], 'adminname' => session('admin_username'), 'addtime' => time(), 'plusminus' => 1, 'amount' => $mytx['num'], 'optype' => 24, 'cointype' => 1, 'old_amount' => $finance_num_user_coin['cny'], 'new_amount' => $finance_mum_user_coin['cny'], 'userid' => $user_info['id'], 'adminid' => session('admin_id'),'addip'=>get_client_ip()));

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

	public function mytxQueren()
	{
		$id = $_GET['id'];

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		if (M('Mytx')->where(array('id' => $id))->save(array('status' => 1))) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	// 导出提现明细表
	public function mytxExcel()
	{
		if (IS_POST) {
			if(is_array($_POST['id'])){
				$id = implode(',', $_POST['id']);
			}else{
				$id = $_POST['id'];
			}
		}
		else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}

		$where['id'] = array('in', $id);
		$list = M('Mytx')->where($where)->select();

		foreach ($list as $k => $v) {
			$list[$k]['userid'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['addtime'] = addtime($v['addtime']);

			if ($list[$k]['status'] == 0) {
				$list[$k]['status'] = '未处理';
			}
			else if ($list[$k]['status'] == 1) {
				$list[$k]['status'] = '已划款';
			}
			else if ($list[$k]['status'] == 2) {
				$list[$k]['status'] = '已撤销';
			}
			else if ($list[$k]['status'] == 3) {
				$list[$k]['status'] = '正在处理';
			}else{
				$list[$k]['status'] = '错误';
			}

			$list[$k]['bankcard'] = ' ' . $v['bankcard'] . ' ';
		}

		$zd = M('Mytx')->getDbFields();
		array_splice($zd, 12, 1);
		$xlsName = 'cade';
		$xls = array();

		foreach ($zd as $k => $v) {
			$xls[$k][0] = $v;
			$xls[$k][1] = $v;
		}

		$xls[0][2] = '编号';
		$xls[1][2] = '用户名';
		$xls[2][2] = '提现金额';
		$xls[3][2] = '手续费';
		$xls[4][2] = '到账金额';
		$xls[5][2] = '姓名';
		$xls[6][2] = '银行备注';
		$xls[7][2] = '银行名称';
		$xls[8][2] = '开户省份';
		$xls[9][2] = '开户城市';
		$xls[10][2] = '开户地址';
		$xls[11][2] = '银行卡号';
		$xls[12][2] = '提现时间';
		$xls[13][2] = '导出时间';
		$xls[14][2] = '提现状态';
		
		$this->exportExcel($xlsName, $xls, $list);
	}

	public function mytxConfig()
	{
		if (empty($_POST)) {
			$this->display();
		}
		else if (M('Config')->where(array('id' => 1))->save($_POST)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		}
	}

	public function myzr($field = NULL, $name = NULL, $coinname = NULL, $time_type = 'addtime', $starttime = NULL, $endtime = NULL, $num_start = NULL, $num_stop = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			elseif($field == 'zcaddr'){
				$where['username'] = $name;
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($coinname) {
			$where['coinname'] = $coinname;
		}

		// 转入数量--条件
		if(is_numeric($num_start) && !is_numeric($num_stop)){

			$where['num'] = array('EGT',$num_start);

		}else if(!is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array('ELT',$num_stop);

		}else if(is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array(array('EGT',$num_start),array('ELT',$num_stop));
			
		}


		// 时间--条件

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 10*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}




		$count = M('Myzr')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Myzr')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['usernamea'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myzc($field = NULL, $name = NULL, $coinname = NULL, $time_type = 'addtime', $starttime = NULL, $endtime = NULL, $num_start = NULL, $num_stop = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}elseif($field == 'zcaddr'){
				$where['username'] = $name;
			}
			else {
				$where[$field] = $name;
			}
		}
		
		$coin_list = M('Coin')->where(array('type'=>'qbb','status'=>1))->select();
		$this->assign('coin_list',$coin_list);

		if ($coinname) {
			$where['coinname'] = $coinname;
		}else{
			$where['coinname'] = $coin_list[0]['name'];
		}

		// 转入数量--条件
		if(is_numeric($num_start) && !is_numeric($num_stop)){

			$where['num'] = array('EGT',$num_start);

		}else if(!is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array('ELT',$num_stop);

		}else if(is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array(array('EGT',$num_start),array('ELT',$num_stop));
			
		}


		// 时间--条件

		if($time_type == 'endtimes'){
			$time_type = 'endtime';
		}

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 1000*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}

		$count = M('Myzc')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Myzc')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['usernamea'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['tp_qj'] = M('Coin')->where(array('name'=>$v['coinname']))->getField('tp_qj');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$total_amount = M('Myzc')->where($where)->sum('num');
		$total_fee = M('Myzc')->where($where)->sum('fee');
		$this->assign('total_amount',$total_amount);
		$this->assign('total_fee',$total_fee);
		
		//生成token
        $cancelzc_token = set_token('cancelzc');
        $this->assign('cancelzc_token',$cancelzc_token);
		$adminzc_token = set_token('adminzc');
        $this->assign('adminzc_token',$adminzc_token);
		$this->display();
	}

	public function myzcQueren($id = NULL, $token = NULL)
	{
		if(!session('adminzctoken')) {
			set_token('adminzc');
		}
		if(!empty($token)){
			$res = valid_token('adminzc',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('adminzctoken'));
			}
		}else{
			$this->error('缺少参数！',session('adminzctoken'));
		}
		$extra=session('adminzctoken');

		$myzc = M('Myzc')->where(array('id' => trim($id)))->find();

		if (!$myzc) {
			$this->error('找不到记录！',$extra);
		}

		if ($myzc['status']) {
			$this->error('已经处理过！',$extra);
		}
		$user = M('User')->where(array('id' => $myzc['userid']))->find();
		
		$user_coin = M('UserCoin')->where(array('userid' => $myzc['userid']))->find();
		
		$coin = $myzc['coinname'];
		
		$Coins = M('Coin')->where(array('name' => $coin))->find();
		
		if($Coins['tp_qj'] == "eth" || $Coins['tp_qj'] == "erc20"){
			$qbdz = 'ethb';
		}else{
			$qbdz = $coin.'b';
		}
		$peer = M('UserCoin')->where(array($qbdz => $myzc['username']))->find();
		if(!empty($peer)){
			$mo = M();
			$mo->startTrans();
			$rs = array();

			if (0 < $myzc['fee']) {
				$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => trim($id), 'coinname' => $coin, 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 1, 'addtime' => time(), 'status' => 1));
			}

			$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($id)))->save(array('status' => 1,'endtime'=>time()));
			$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->setInc($coin, $myzc['mum']);
			
			$peer_info = $mo->table('tw_user')->where(array('id' => $peer['userid']))->find();
			$user_peer_coin = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->find();

			// 接受人记录
			$rs[] = $mo->table('tw_finance_log')->add(array('username' => $peer_info['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $myzc['mum'], 'optype' => 7, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $peer[$coin], 'new_amount' => $user_peer_coin[$coin], 'userid' => $peer['userid'], 'adminid' => $user['id'],'addip'=>get_client_ip()));
			$rs[] = $mo->table('tw_myzr')->add(array('userid' => $peer['userid'], 'username' => $user_coin[$qbdz], 'coinname' => $coin, 'txid' => md5($user_coin[$qbdz] . $myzc['username'] . time()), 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'addtime' => time(), 'status' => 1));
			if (check_arr($rs)) {
				$mo->commit();
				$this->success('转账成功！',$extra);
			}
			else {
				$mo->rollback();
				$this->error('转出失败!' . implode('|', $rs),$extra);
			}
		}elseif(!empty($Coins) && $Coins['tp_qj'] == 'btc'){
			$dj_username = C('coin')[$coin]['dj_yh_mytx'];
			$dj_password = C('coin')[$coin]['dj_mm_mytx'];
			$dj_address = C('coin')[$coin]['dj_zj_mytx'];
			$dj_port = C('coin')[$coin]['dj_dk_mytx'];
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
			
			$mo = M();
			$mo->startTrans();
			$rs = array();

			if (0 < $myzc['fee']) {
				$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => trim($id), 'coinname' => $coin, 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));
			}

			$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($id)))->save(array('status' => 1,'endtime'=>time()));

			if (check_arr($rs)) {
				if($Coins['name'] == 'usdt'){
					$sendrs = $CoinClient->omni_send((string) C('usdtzcaddr'), (string) $myzc['username'], 1, (string) $myzc['mum']);
				}else{
                    $CoinClient->walletpassphrase("Pi3.53589793",60);
					$sendrs = $CoinClient->sendtoaddress($myzc['username'], (double) $myzc['mum']);
				}
				if ($sendrs) {
					$mo->table('tw_myzc')->where(array('id'=>trim($id)))->save(array('txid'=>$sendrs));
					$flag = 1;
					$arr = json_decode($sendrs, true);
					if (isset($arr['status']) && ($arr['status'] == 0)) {
						$flag = 0;
					}
				}
				else {
					$flag = 0;
				}

				if (!$flag) {
//				    print_r($sendrs);
					$mo->rollback();
					$this->error('钱包服务器转出币失败!',$extra);
				}
				else {
					$mo->commit();
					$this->success('转账成功！',$extra);
				}
			}
			else {
				$mo->rollback();
				$this->error('转出失败!' . implode('|', $rs),$extra);
			}
		}elseif(!empty($Coins) && $Coins['name'] == 'eth'){
			$user_addr = $user_coin['ethb'];
			$wallet_password = $user['ethpassword'];
			if(empty($user_addr)){
				$user_addr = M('Config')->where(array('id'=>1))->getField('ethaddress');
				$wallet_password = M('Config')->where(array('id'=>1))->getField('ethpassword');
				$eth_account = \Common\Ext\EthWallet::getBalance($user_addr);
				$account_info = json_decode($eth_account);
				$account_yue = \Common\Ext\EthWallet::toEth($account_info->result);
				if($account_yue<($myzc['mum']+0.005)){
					$this->error('钱包余额不足！',$extra);
				}
			}else{
				$eth_account = \Common\Ext\EthWallet::getBalance($user_addr);
				$account_info = json_decode($eth_account);
				$account_yue = \Common\Ext\EthWallet::toEth($account_info->result);
				if($account_yue<($myzc['mum']+0.005)){
					$new_user_addr = M('Config')->where(array('id'=>1))->getField('ethaddress');
					$new_eth_account = \Common\Ext\EthWallet::getBalance($new_user_addr);
					$new_account_info = json_decode($new_eth_account);
					$new_account_yue = \Common\Ext\EthWallet::toEth($new_account_info->result);
					if($new_account_yue<($myzc['mum']+0.005)){
						$this->error('钱包余额不足！',$extra);
					}else{
						$user_addr = $new_user_addr;
						$wallet_password = M('Config')->where(array('id'=>1))->getField('ethpassword');
					}
				}
			}
			
			$mo = M();
			$mo->startTrans();
			$rs = array();

			if (0 < $myzc['fee']) {
				$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => trim($id), 'coinname' => $coin, 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));
			}

			$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($id)))->save(array('status' => 1,'endtime'=>time()));

			if (check_arr($rs)) {
				ob_start();
				$accountInfo = \Common\Ext\EthWallet::transaction($user_addr, $myzc['username'], $myzc['mum'], $wallet_password);
				ob_get_clean();
				if (!empty($accountInfo->error->message)){
					$message = "转账失败！".$accountInfo->error->message;
					$flag = 0;
				}else{
					if (!empty($accountInfo->result)){
						$hash = $accountInfo->result;
						$record_insert = $mo->table('tw_myzc')->where(array('id'=>$id))->save(array('txid'=>$hash));
						$flag = 1;
					}else {
						$message = "转账失败！";
						$flag = 0;
					}
				}
				if (!$flag) {
					$mo->rollback();
					$this->error($message);
					ob_end_flush();
				}
				else {
					$mo->commit();
					$this->success('转账成功！',$extra);
					ob_end_flush();
				}
			}
			else {
				$mo->rollback();
				$this->error('转出失败!' . implode('|', $rs),$extra);
			}
		}
	}
	
	public function myzcBatch($id){
		if(!empty($id)){
			foreach($id as $zcid){
				$myzc = M('Myzc')->where(array('id' => $zcid))->find();

				if (!$myzc) {
					M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"记录不存在"));
					continue;
				}

				if ($myzc['status']) {
					M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"已经处理过",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
					continue;
				}
				$user = M('User')->where(array('id' => $myzc['userid']))->find();
				$username = $user['username'];
				$user_coin = M('UserCoin')->where(array('userid' => $myzc['userid']))->find();
				$coin = $myzc['coinname'];
				$Coins = M('Coin')->where(array('name' => $coin))->find();
				if($Coins['tp_qj'] == "eth" || $Coins['tp_qj'] == "erc20"){
					$qbdz = 'ethb';
				}else{
					$qbdz = $coin.'b';
				}
				$peer = M('UserCoin')->where(array($qbdz => $myzc['username']))->find();
				if(!empty($peer)){
					$mo = M();
					$mo->startTrans();
					$rs = array();

					if (0 < $myzc['fee']) {
						$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => trim($zcid), 'coinname' => $coin, 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 1, 'addtime' => time(), 'status' => 1));
					}

					$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($zcid)))->save(array('status' => 1,'endtime'=>time()));
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->setInc($coin, $myzc['mum']);
					
					$peer_info = $mo->table('tw_user')->where(array('id' => $peer['userid']))->find();
					$user_peer_coin = $mo->table('tw_user_coin')->where(array('userid' => $peer['userid']))->find();

					// 接受人记录
					$rs[] = $mo->table('tw_finance_log')->add(array('username' => $peer_info['username'], 'adminname' => $user['username'], 'addtime' => time(), 'plusminus' => 1, 'amount' => $myzc['mum'], 'optype' => 7, 'position' => 1, 'cointype' => $Coins['id'], 'old_amount' => $peer[$coin], 'new_amount' => $user_peer_coin[$coin], 'userid' => $peer['userid'], 'adminid' => $user['id'],'addip'=>get_client_ip()));
					$rs[] = $mo->table('tw_myzr')->add(array('userid' => $peer['userid'], 'username' => $user_coin[$qbdz], 'coinname' => $coin, 'txid' => md5($user_coin[$qbdz] . $myzc['username'] . time()), 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'addtime' => time(), 'status' => 1));
					
					if (check_arr($rs)) {
						$mo->commit();
					}
					else {
						$mo->rollback();
					}
				}elseif(!empty($Coins) && $Coins['tp_qj'] == 'btc'){
					$dj_username = C('coin')[$coin]['dj_yh_mytx'];
					$dj_password = C('coin')[$coin]['dj_mm_mytx'];
					$dj_address = C('coin')[$coin]['dj_zj_mytx'];
					$dj_port = C('coin')[$coin]['dj_dk_mytx'];
					$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
					
					if($Coins['name'] == 'usdt'){
						$json = $CoinClient->getnetworkinfo();
						if (!isset($json['version']) || !$json['version']) {
							M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"钱包连接失败",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
							continue;
						}
					}else{
						$json = $CoinClient->getwalletinfo();
						if (!isset($json['walletversion']) || !$json['walletversion']) {
							M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"钱包连接失败",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
							continue;
						}
					}
					
					$zhannei = M('UserCoin')->where(array($coin . 'b' => $myzc['username']))->find();
					$mo = M();
					$mo->startTrans();
					$rs = array();

					if (0 < $myzc['fee']) {
						$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => trim($zcid), 'coinname' => $coin, 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));
					}

					$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($zcid)))->save(array('status' => 1,'endtime'=>time()));

					if (check_arr($rs)) {
						if($Coins['name'] == 'usdt'){
							$sendrs = $CoinClient->omni_send((string) C('usdtzcaddr'), (string) $myzc['username'], 1, (string) $myzc['mum']);
						}else{
							$sendrs = $CoinClient->sendtoaddress($myzc['username'], (double) $myzc['mum']);
						}
						if ($sendrs) {
							$mo->table('tw_myzc')->where(array('id'=>trim($zcid)))->save(array('txid'=>$sendrs));
							$flag = 1;
							$arr = json_decode($sendrs, true);
							if (isset($arr['status']) && ($arr['status'] == 0)) {
								$flag = 0;
							}
						}
						else {
							$flag = 0;
						}

						if (!$flag) {
							$mo->rollback();
							M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"钱包服务器转出币失败",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
						}
						else {
							$mo->commit();
						}
					}
					else {
						$mo->rollback();
						M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>'转出失败!' . implode('|', $rs) . $myzc['fee'],'userid'=>$myzc['userid'],'username'=>$myzc['username']));
					}
				}elseif(!empty($Coins) && $Coins['name'] == 'eth'){					
					$user_addr = $user_coin['ethb'];
					$wallet_password = $user['ethpassword'];
					if(empty($user_addr)){
						$user_addr = M('Config')->where(array('id'=>1))->getField('ethaddress');
						$wallet_password = M('Config')->where(array('id'=>1))->getField('ethpassword');
						$eth_account = \Common\Ext\EthWallet::getBalance($user_addr);
						$account_info = json_decode($eth_account);
						$account_yue = \Common\Ext\EthWallet::toEth($account_info->result);
						if($account_yue<($myzc['mum']+0.005)){
							M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"钱包余额不足",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
							continue;
						}
					}else{
						$eth_account = \Common\Ext\EthWallet::getBalance($user_addr);
						$account_info = json_decode($eth_account);
						$account_yue = \Common\Ext\EthWallet::toEth($account_info->result);
						if($account_yue<($myzc['mum']+0.005)){
							$new_user_addr = M('Config')->where(array('id'=>1))->getField('ethaddress');
							$new_eth_account = \Common\Ext\EthWallet::getBalance($new_user_addr);
							$new_account_info = json_decode($new_eth_account);
							$new_account_yue = \Common\Ext\EthWallet::toEth($new_account_info->result);
							if($new_account_yue<($myzc['mum']+0.005)){
								M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>"钱包余额不足",'userid'=>$myzc['userid'],'username'=>$myzc['username']));
								continue;
							}else{
								$user_addr = $new_user_addr;
								$wallet_password = M('Config')->where(array('id'=>1))->getField('ethpassword');
							}
						}
					}
					
					$mo = M();
					$mo->startTrans();
					$rs = array();

					if (0 < $myzc['fee']) {
						$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => trim($zcid), 'coinname' => 'eth', 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));
					}

					$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($zcid)))->save(array('status' => 1,'endtime'=>time()));

					if (check_arr($rs)) {
						ob_start();
						$accountInfo = \Common\Ext\EthWallet::transaction($user_addr, $myzc['username'], $myzc['mum'], $wallet_password);
						ob_get_clean();
						if (!empty($accountInfo->error->message)){
							$message = "转账失败！".$accountInfo->error->message;
							$flag = 0;
						}else{
							if (!empty($accountInfo->result)){
								$hash = $accountInfo->result;
								$record_insert = $mo->table('tw_myzc')->where(array('id'=>$zcid))->save(array('txid'=>$hash));
								$flag = 1;
							}else {
								$message = "转账失败！";
								$flag = 0;
							}
						}
						if (!$flag) {
							$mo->rollback();
							M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>$message,'userid'=>$myzc['userid'],'username'=>$myzc['username']));
							ob_end_flush();
						}
						else {
							$mo->commit();
							ob_end_flush();
						}
					}
					else {
						$mo->rollback();
						M("Zcbatch_error")->add(array('zcid'=>$zcid,'addtime'=>time(),'beizhu'=>'转出失败!' . implode('|', $rs) . $myzc['fee'],'userid'=>$myzc['userid'],'username'=>$myzc['username']));
					}
				}
			}
		}
		$this->success("执行完毕！");
	}
	
	public function myzcBatchLog($starttime='',$endtime='',$username=''){
		$where = array();

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where['addtime'] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where['addtime'] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where['addtime'] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{
			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 1000*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
			
		}
		
		if(!empty($username)){
			$where['username'] = $username;
		}
		
		$count = M('Zcbatch_error')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Zcbatch_error')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['uname'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function money_log($field = NULL, $name = NULL, $time_type = 'addtime', $starttime = NULL, $endtime = NULL, $num_start = NULL, $num_stop = NULL, $type=NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}elseif($field == 'zcaddr'){
				$where['username'] = $name;
			}
			else {
				$where[$field] = $name;
			}
		}

		// if ($coinname) {
		// 	$where['coinname'] = $coinname;
		// }

		// 转入数量--条件
		if(is_numeric($num_start) && !is_numeric($num_stop)){

			$where['num'] = array('EGT',$num_start);

		}else if(!is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array('ELT',$num_stop);

		}else if(is_numeric($num_start) && is_numeric($num_stop)){

			$where['num'] = array(array('EGT',$num_start),array('ELT',$num_stop));
			
		}


		// 时间--条件
		if(!empty($type)){
			$where['type'] = $type;
		}
		if($time_type == 'endtimes'){
			$time_type = 'endtime';
		}

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}
		// else{

		// 	// 无时间查询，显示申请时间类型十天以内数据
		// 	$now_time = time() - 1000*24*60*60;
		// 	$where['addtime'] =  array('EGT',$now_time);
		// }

		$count = M('Money_log')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Money_log')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['usernamea'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function myzcCancel($id = NULL, $token=NULL){
		$extra = '';
		if(!session('cancelzctoken')) {
			set_token('cancelzc');
		}
		if(!empty($token)){
			$res = valid_token('cancelzc',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('cancelzctoken'));
			}
		}else{
			$this->error('缺少参数！',session('cancelzctoken'));
		}
		$extra=session('cancelzctoken');
		if(empty($id) || !check($id,'d')){
			$this->error("参数错误！",$extra);
		}
		$myzc = M('Myzc')->where(array('id'=>$id))->find();
		if(empty($myzc)){
			$this->error("找不到记录！",$extra);
		}
		if($myzc['status']!=0){
			$this->error("订单状态错误！",$extra);
		}
		try{
			$mo = M();
			$mo->startTrans();
			$rs[] = $mo->table('tw_myzc')->where(array('id'=>$myzc['id']))->save(array('status'=>2,'endtime'=>time()));
			$rs[] = $mo->table('tw_user_coin')->where(array('userid'=>$myzc['userid']))->setInc($myzc['coinname'],$myzc['num']);
			$myzc_user = $mo->table('tw_user')->where(array('id'=>$myzc['userid']))->find();
			$myzc_user_coin = $mo->table('tw_user_coin')->where(array('userid'=>$myzc['userid']))->find();
			$myzc_coin = $mo->table('tw_coin')->where(array('name'=>$myzc['coinname']))->find();
			$rs[] = $mo->table('tw_finance_log')->add(array('username' => $myzc_user['username'], 'adminname' => session('admin_username'), 'addtime' => time(), 'plusminus' => 1, 'amount' => $myzc['num'], 'optype' => 27, 'position' => 0, 'cointype' => $myzc_coin['id'], 'old_amount' => $myzc_user_coin[$myzc['coinname']], 'new_amount' => ($myzc_user_coin[$myzc['coinname']]+$myzc['num']), 'userid' => $myzc['userid'], 'adminid' => session('admin_id'),'addip'=>get_client_ip()));
			if (check_arr($rs)) {
				$mo->commit();
				$this->success('撤消成功！',$extra);
			}
			else {
				throw new \Think\Exception('撤消失败！');
			}
		}catch(\Think\Exception $e){
			$mo->rollback();
			$this->error('撤消失败！',$extra);
		}
	}
	
	public function myzcSd($id){

		$myzc = M('Myzc')->where(array('id' => trim($id)))->find();

		if (!$myzc) {
			$this->error('转出错误！');
		}

		if ($myzc['status']) {
			$this->error('已经处理过！');
		}
		$user = M('User')->where(array('id' => $myzc['userid']))->find();
		
		$username = $user['username'];
		
		$coin = $myzc['coinname'];

		$Coins = M('Coin')->where(array('name' => $myzc['coinname']))->find();
		
		$user_coin = M('UserCoin')->where(array('userid' => $myzc['userid']))->find();
		
		if($Coins['tp_qj'] == "eth" || $Coins['tp_qj'] == "erc20"){
			$qbdz = "ethb";
			$zhannei = M('UserCoin')->where(array('ethb' => $myzc['username']))->find();
		}else{
			$qbdz = $coin . 'b';
			$zhannei = M('UserCoin')->where(array($coin . 'b' => $myzc['username']))->find();
		}
		
		$mo = M();
		$mo->startTrans();
		$rs = array();

		if ($zhannei) {
			$rs[] = $mo->table('tw_myzr')->add(array('userid' => $zhannei['userid'], 'username' => $myzc['username'], 'coinname' => $coin, 'txid' => md5($myzc['username'] . $user_coin[$qbdz] . time()), 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'addtime' => time(), 'status' => 1));
			$rs[] = $r = $mo->table('tw_user_coin')->where(array('userid' => $zhannei['userid']))->setInc($coin, $myzc['mum']);
		}

		if (0 < $myzc['fee']) {
			$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $user['id'], 'username' => trim($id), 'coinname' => $coin, 'num' => $myzc['num'], 'fee' => $myzc['fee'], 'mum' => $myzc['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));
		}

		$rs[] = $mo->table('tw_myzc')->where(array('id' => trim($id)))->save(array('status' => 1,'endtime'=>time()));

		if (check_arr($rs)) {
			$mo->commit();
			$this->success('转账成功！');
		}
		else {
			$mo->rollback();
			$this->error('转出失败!');
		}
	}
	
	public function ethtransfer(){
		if (IS_POST) {
			$zc_addr = $_POST['zc_addr'];
			$zr_addr = $_POST['zr_addr'];
			$zc_num = $_POST['zc_num'];
			$zcwallet_password = $_POST['zcwallet_password'];
			if(!empty($zc_addr) && !empty($zr_addr) && !empty($zc_num) && !empty($zcwallet_password)){
				if(strlen($zc_addr) >= 10 && substr($zc_addr, 0, 2) == "0x" && strlen($zr_addr) >= 10 && substr($zr_addr, 0, 2) == "0x" && $zc_num>0){
					ob_start();
					$accountInfo = \Common\Ext\EthWallet::transaction($zc_addr, $zr_addr, $zc_num, $zcwallet_password);
					ob_get_clean();
					if (!empty($accountInfo->error->message)){
						$message = "转账失败！".$accountInfo->error->message;
						$flag = 0;
					}else{
						if (!empty($accountInfo->result)){
							$hash = $accountInfo->result;
							$record_insert = M('eth_transfer')->add(array('zc_addr'=>$zc_addr,'zr_addr'=>$zr_addr,'zc_amount'=>$zc_num,'addtime'=>time(),'zchash'=>$hash));
							$flag = 1;
						}else {
							$message = "转账失败！";
							$flag = 0;
						}
					}
					if($flag == 1){
						$this->success('转账成功！');
					}else{
						$this->error($message);
					}
					ob_end_flush();
				}else{
					$this->error('参数错误!');
				}
			}else{
				$this->error('缺少参数!');
			}
		}else{
			$this->display();
		}
	}
	
	public function ethbatch(){
		if (IS_POST) {
			$token = trim($_POST['token']);
			if(!session('ethbatchtoken')) {
				set_token('ethbatch');
			}
			if(!empty($token)){
				$res = valid_token('ethbatch',$token);
				if(!$res){
					$this->error('参数错误1');
				}
			}else{
				$this->error('缺少参数');
			}
		
			$ethzr = trim($_POST['ethzr']);
			if(empty($ethzr) || substr($ethzr,0,2)!='0x'){
				$this->error('转入地址不是一个有效的地址');
			}
			$zcaddr = $_POST['zcaddr'];
			$zcamount = $_POST['zcamount'];
			if(!empty($zcaddr) && !empty($zcamount)){
				$n = count($zcaddr);
				$m = count($zcamount);
				if($m != $n){
					$this->error("参数错误".$m."|".$n);
				}
				if($n>0){
					for($i=0;$i<$n;$i++){
						$address = $zcaddr[$i];
						if(!empty($address) && substr($address,0,2) == '0x'){
							$user_coin = M('user_coin')->where(array('ethb'=>$address))->find();
							$user = M('User')->where(array('id'=>$user_coin['userid']))->find();
							$zcpwd = $user['ethpassword'];
							if(empty($zcpwd)){
								$this->error("没找到地址对应的密码");
							}
							$eth_account = \Common\Ext\EthWallet::getBalance($address);
							$account_info = json_decode($eth_account);
							$account_yue = \Common\Ext\EthWallet::toEth($account_info->result);
							if(floatval($account_yue)<=0.001){
								$this->error("所有转出地址中余额必须大于0.001");
							}
							if(empty($zcamount[$i])){
								$amount = floatval($account_yue-0.001);
							}else{
								if($account_yue-$zcamount[$i]<0.001){
									$this->error("转出数量输入过高，账号里至少留0.001个币作为手续费");
								}
								$amount = $zcamount[$i];
							}
							if($amount<=0){
								$this->error("转出数量必须大于0");
							}
							
							ob_start();
							$accountInfo = \Common\Ext\EthWallet::transaction($address, $ethzr, $amount, $zcpwd);
							ob_get_clean();
							if (!empty($accountInfo->error->message)){
								$this->error('转账失败！');
							}else{
								if (!empty($accountInfo->result)){
									$hash = $accountInfo->result;
									$record_insert = M('eth_transfer')->add(array('zc_addr'=>$address,'zr_addr'=>$ethzr,'zc_amount'=>$amount,'addtime'=>time(),'zchash'=>$hash));
									if(empty($record_insert)){
										$this->error('转账成功，记录写入数据库失败！');
									}
								}else {
									$this->error('转账失败！');
								}
							}
							ob_end_flush();
							
						}else{
							$this->error("转出地址中有错误");
						}
					}
					$this->success("操作成功，请等待2-5分钟后刷新页面，不要连续转出");
				}
			}else{
				$this->error("没有选择转出地址");
			}
		}else{
			$address_list = array();
			$accounts = json_decode(\Common\Ext\EthWallet::accounts());
			$n = sizeof($accounts->result);
			if($n > 0){
				for($i=0;$i<$n;$i++){
					$address = $accounts->result[$i];
					if(!empty($address) && substr($address,0,2) == '0x'){
						$usercoin = M('UserCoin')->where(array('ethb'=>$address))->find();
						if(!empty($usercoin)){
							$user = M('User')->where(array('id'=>$usercoin['userid']))->find();
							$username = $user['username'];
							$eth_account = \Common\Ext\EthWallet::getBalance($address);
							$account_info = json_decode($eth_account);
							$account_yue = \Common\Ext\EthWallet::toEth($account_info->result);
						}else{
							$username = '';
							$account_yue = 0;
						}
						$address_list[$i]['username'] = $username;
						$address_list[$i]['account_yue'] = $account_yue;
						$address_list[$i]['address'] = $address;
						if($address == C('ethaddress') || empty($username) || (!empty($username) && $username=='admins') || empty($account_yue) || (!empty($account_yue) && $account_yue<= 0.001)){
							$address_list[$i]['sel'] = 0;
						}else{
							$address_list[$i]['sel'] = 1;
						}
					}
				}
			}
			$this->assign('address_list',$address_list);
			//生成token
			$ethbatch_token = set_token('ethbatch');
			$this->assign('ethbatch_token',$ethbatch_token);
			$this->display();
		}
	}
	
	public function ethtrans($field = NULL, $name = NULL, $starttime = NULL, $endtime = NULL){
		$where = array();
		if ($field && $name) {
			$where[$field] = $name;
		}
		
		// 时间--条件
		if(!empty($starttime)){
			$starttime = str_replace("+",'',urldecode($starttime));
		}
		
		if(!empty($endtime)){
			$endtime = str_replace("+",'',urldecode($endtime));
		}
		
		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where['addtime'] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where['addtime'] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where['addtime'] =  array(array('EGT',$starttime),array('ELT',$endtime));

		}

		$count = M('eth_transfer')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('eth_transfer')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function ethamount(){
		if (IS_POST) {
			$addr = $_POST['zc_addr'];
			if(strlen($addr)>10 && substr($addr,0,2) == '0x'){
				$eth_account = \Common\Ext\EthWallet::getBalance($addr);
				$account_info = json_decode($eth_account);
				$account_yue = \Common\Ext\EthWallet::toEth($account_info->result);
				echo json_encode(array('status'=>1,'amount'=>$account_yue));
				exit;
			}
		}
	}
	
	public function deallost($field = NULL, $name = NULL, $coinname = NULL, $time_type = 'addtime', $starttime = NULL, $endtime = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}else {
				$where[$field] = $name;
			}
		}
		
		if(!empty($coinname)){
			$where['coinname'] = $coinname;
		}
		$where['islost'] = 1;
		$where['ischecked']=1;
		
		if($time_type == 'endtimes'){
			$time_type = 'endtime';
		}

		// 时间--条件

		if(!empty($starttime)){
			$starttime = str_replace("+",'',urldecode($starttime));
		}
		
		if(!empty($endtime)){
			$endtime = str_replace("+",'',urldecode($endtime));
		}
		
		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where[$time_type] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where[$time_type] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where[$time_type] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 1000*24*60*60;
			$where['timeStamp'] =  array('EGT',$now_time);
		}

		$count = M('ethapi')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('ethapi')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->order('timestamp desc')->select();
		foreach ($list as $k => $v) {
			$list[$k]['addtime'] = date("Y-m-d H:i:s",intval($v['timestamp']));
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['value'] = ceth($v['value']);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$coin_list = M('Coin')->where(array('tp_qj'=>array('in',"eth,erc20")))->select();
		$this->assign('coin_list',$coin_list);
		$this->assign("coin",$coinname);
		$this->display();
	}
	
	public function donelost($id){
		if(empty($id)){
			$this->error('参数错误！');
		}
		$result = M('ethapi')->where(array('id'=>$id))->find();
		if(empty($result)){
			$this->error('找不到记录');
		}
		if($result['ischecked'] != 1 || $result['islost'] != 1){
			$this->error('状态错误');
		}
		if($result['isdone'] == 1){
			$this->error('已经审核过');
		}
		$res = M('ethapi')->where(array('id'=>$id,'ischecked'=>1,'islost'=>1,'isdone'=>0))->save(array('isdone'=>1,'endtime'=>time()));
		if(!empty($res)){
			$this->success('审核成功');
		}else{
			$this->error('审核失败');
		}
	}
	
	public function mytxfee($field = NULL, $name = NULL, $coinname = NULL, $starttime = NULL, $endtime = NULL){
		$where = array();

		if ($field && $name) {
			if($field == "username"){
				$user = M('User')->where(array('username'=>$name))->find();
				$where['userid'] = $user['id'];
			}
			
			if($field == "txid"){
				$myzc_info = M('Myzc')->where(array('txid'=>$name))->find();
				if(!empty($myzc_info)){
					$where['username'] = $myzc_info['id'];
				}
			}
		}
		
		$coin_list = M('Coin')->where(array('type'=>'qbb','status'=>1))->select();
		$this->assign('coin_list',$coin_list);

		if (empty($coinname)) {
			$coinname = $coin_list[0]['name'];
		}
		$where['coinname'] = $coinname;

		// 时间--条件

		if (!empty($starttime) && empty($endtime)) {
			$starttime = strtotime($starttime);
			$where['addtime'] = array('EGT',$starttime);

		}else if(empty($starttime) && !empty($endtime)){
			$endtime = strtotime($endtime);
			$where['addtime'] = array('ELT',$endtime);

		}else if(!empty($starttime) && !empty($endtime)){
			$starttime = strtotime($starttime);
			$endtime = strtotime($endtime);
			$where['addtime'] =  array(array('EGT',$starttime),array('ELT',$endtime));
			
		}else{

			// 无时间查询，显示申请时间类型十天以内数据
			$now_time = time() - 1000*24*60*60;
			$where['addtime'] =  array('EGT',$now_time);
		}
		$count = M('MyzcFee')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('MyzcFee')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$myzc = M('Myzc')->where(array('id'=>$v['username']))->find();
			if(!empty($myzc)){
				$list[$k]['txid'] = $myzc['txid'];
				$list[$k]['zcaddr'] = $myzc['username'];
			}else{
				$list[$k]['txid'] = '';
				$list[$k]['zcaddr'] = '';
			}
			$list[$k]['username'] = M('User')->where(array('id'=>$v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		
		$this->display();
	}
}
?>