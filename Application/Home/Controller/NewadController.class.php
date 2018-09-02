<?php
namespace Home\Controller;

class NewadController extends HomeController
{
	protected function _initialize()
	{
		parent::_initialize();
		$allow_action = array("index", "upad", "ediad", "upediad", "setShelf", "advdetail", "upChat", "chatPic", "truban", "upload");
		if (!in_array(ACTION_NAME, $allow_action)) {
			$this->error("页面不存在！");
		}
	}

	public function __construct()
	{
		parent::__construct();
		$display_action = array("index", "upad", "ediad", "upediad", "setShelf", "advdetail");
		if (in_array(ACTION_NAME, $display_action)) {
			$this->common_content();
		}
	}

	public function index($coinid=null)
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}
		if(empty($coinid)){
			$coin_default = M('Coin')->where(array('name'=>C('xnb_mr')))->find();
			$coinid = $coin_default['id'];
			$coinname = $coin_default['name'];
		}

		if (!check($coinid,'d')) {
			$this->error('参数错误！');
		}
		$coin_info = M('Coin')->where(array('id'=>$coinid))->find();
		$coinname = $coin_info['name'];

        $user_info = M('User')->where(array('id' => userid()))->find();
        $balanceAll = M('UserCoin')->where(array('userid' => $user_info['id']))->find();
//        $balance = $balanceAll[$coinname];

		$this->assign('balanceAll',$balanceAll);
		$this->assign('coin_info',$coin_info);

		if ($user_info['paypassword'] == '') {
			$this->assign('paypassset',1);
		}

		$config_info = M('Config')->where(array('id' => 1))->field('fee_bili')->find();
		$this->assign('config', $config_info);

		$coin = M('Coin')->field('id,title,js_yw,name')->where(array('status'=>1,'name'=>array('neq','cny')))->order('sort asc')->select();
		$this->assign('coin', $coin);

		$location = M('Location')->select();
		$this->assign('location', $location);

		$table = M('Coin')->where("id=$coinid")->getField('name');
		$currency = M($table)->select();
		$this->assign('currency', $currency);

		$pay_method = M('PayMethod')->select();
		$this->assign('pay_method', $pay_method);

		$str = '<option value="a">结束时间</option><option value="z">关闭</option><option value="0">0:00</option><option value="1">1:00</option><option value="2">2:00</option><option value="3">3:00</option><option value="4">4:00</option><option value="5">5:00</option><option value="6">6:00</option><option value="7">7:00</option><option value="8">8:00</option>																<option value="9">9:00</option><option value="10">10:00</option><option value="11">11:00</option><option value="12">12:00</option><option value="13">13:00</option><option value="14">14:00</option><option value="15">15:00</option><option value="16">16:00</option><option value="17">17:00</option><option value="18">18:00</option><option value="19">19:00</option><option value="20">20:00</option><option value="21">21:00</option><option value="22">22:00</option><option value="23">23:00</option><option value="24">24:00</option>';
		$arr = array(0=>array('xq'=>'星期一 ','start'=>'mon_s','end'=>'mon_e','option'=>$str),1=>array('xq'=>'星期二 ','start'=>'tue_s','end'=>'tue_e','option'=>$str),2=>array('xq'=>'星期三 ','start'=>'wed_s','end'=>'wed_e','option'=>$str),3=>array('xq'=>'星期四 ','start'=>'thu_s','end'=>'thu_e','option'=>$str),4=>array('xq'=>'星期五 ','start'=>'fri_s','end'=>'fri_e','option'=>$str),5=>array('xq'=>'星期六 ','start'=>'sat_s','end'=>'sat_e','option'=>$str),6=>array('xq'=>'星期日 ','start'=>'sun_s','end'=>'sun_e','option'=>$str));

		//收款账号
        $skaccountList = M('user_skaccount')->where(array("user_id" => userid()))->select();
        foreach ($skaccountList as $key => $value){
            $skaccountList[$key]['payname'] = $pay_method[$value['pay_method_id'] - 1]['name'];
        }

		$this->assign('skaccountList',$skaccountList);
		$this->assign('arr',$arr);
		$this->assign('coinid',$coinid);
		$this->assign('coinname',$coinname);

		$newad_token = set_token('newad');
        $this->assign('newad_token', $newad_token);
		$this->display();
	}

	public function upad($type, $coin, $location, $currency, $margin, $min_price, $min_limit, $max_limit, $due_time, $message, $pay_method, $safe_option, $trust_only, $open_time, $skaccount,$token)
	{
        if (!userid()) {
			redirect('/login/index');
		}
		
		$extra = '';
		
		if (!session('newadtoken')) {
			set_token('newad');
		}
		if (!empty($token)) {
			$res = valid_token('newad', $token);
			if (!$res) {
				$this->error('请不要频繁提交！', session('newadtoken'));
			}
		}else{
			$this->error('缺少参数！',session('newadtoken'));
		}
		$extra = session('newadtoken');

		// 过滤非法字符----------------S

		if (checkstr($margin) || checkstr($min_price) || checkstr($min_limit) || checkstr($max_limit) || checkstr($due_time)) {
			$this->error('您输入的信息有误！', $extra);
		}

		// 过滤非法字符----------------E

		if($type != 0 && $type != 1){
			$this->error("广告类型错误！", $extra);
		}

		if(!check($coin,'d')){
			$this->error("参数错误！", $extra);
		}
		
		if(!check($location,'d')){
			$this->error("参数错误！", $extra);
		}
		
		if(!check($trust_only,'d')){
			$this->error("参数错误！", $extra);
		}
		
		if(!check($safe_option,'d')){
			$this->error("参数错误！", $extra);
		}
		
		if(!check($currency,'currency')){
			$this->error("参数错误！", $extra);
		}

		if(!check($message,'bcdw')){
			$this->error("参数错误！", $extra);
		}
		
//		if(!empty($skaccount) && !check($skaccount,'bcdw')){
//			$this->error("参数错误！", $extra);
//		}
		
		$user_info = M('User')->where(array('id' => userid()))->find();
		if ($user_info['paypassword'] == '') {
			$this->error("请先设置交易密码！", $extra);
		}

		$table = ($type == 0)?'AdBuy':'AdSell';

		//检查发布广告是否超过两次
		$count = M($table)->where(array('userid' => userid()))->count();
		if($count >= 3){
			$this->error('同种类型广告最多发布三个！', $extra);
		}

		if (!check($margin, 'margin')) {
			$this->error('溢价为-99.99 至 99.99 的数值', $extra);
		}

		if($min_price != ''){
			$min_price = $min_price;
			if (!check($min_price, 'currency')) {
				$this->error('最低价格式错误！', $extra);
			}
		}

		if($due_time != ''){
			if (!check($due_time, 'duetime') || $due_time>60) {
				$this->error('付款期限请填写5到60分钟之间！', $extra);
			}
		}

		if (!check($min_limit, 'currency')) {
			$this->error('最小限额格式错误！', $extra);
		}else{
			if($min_limit < 50){$this->error('最小限额不能小于50！', $extra);}
		}
		if (!check($max_limit, 'currency')) {
			$this->error('最大限额格式错误！', $extra);
		}
		if ($min_limit > $max_limit) {
			$this->error('最小限额不能大于最大限额！', $extra);
		}
		
//		if(empty($pay_method)){
//			$this->error('请选择支付方式！', $extra);
//		}
		
//		if(!check($pay_method,'a')){
//			$this->error("参数错误！", $extra);
//		}
		
		if(!check($open_time,'a')){
			$this->error("参数错误！", $extra);
		}

		//重组开放时间
		$open_time_arr = explode(',',$open_time);
		foreach ($open_time_arr as $k => $v){
			if($this->checkopentime($v)){
				$this->error('请检查开放时间！', $extra);
			}else{
				if($v == 'a-a' || $v == '0-24'){
					$open_time_arr[$k] = 1;
				}
				if($v == 'z-z'){
					$open_time_arr[$k] = 0;
				}
			}
		}
		$open_time =implode(',',$open_time_arr);

		$ad_no = $this->getadvno();

		$coin_info = M('Coin')->where(array('id'=>$coin))->find();
		$pay_method =$pay_method;// implode(",",$pay_method);
		//插入出售表
		if($type == 1) {
			$rs = M($table)->add(array('userid' => userid(), 'add_time' => time(), 'coin'=>$coin, 'location' => $location, 'currency' => $currency, 'margin' => $margin, 'min_price' => $min_price, 'min_limit' => $min_limit, 'max_limit' => $max_limit, 'pay_method' => $pay_method, 'message' => $message, 'safe_option' => $safe_option, 'trust_only' => $trust_only, 'open_time' => $open_time, 'state' => 1,'ad_no' => $ad_no, 'fee'=>$coin_info['cs_ts'],'skaccount'=>$skaccount));
		}

		//插入购买表
		if($type == 0){
			$rs = M($table)->add(array('userid' => userid(), 'add_time' => time(), 'coin'=>$coin, 'location' => $location, 'currency' => $currency, 'margin' => $margin, 'due_time' => $due_time, 'min_limit' => $min_limit, 'max_limit' => $max_limit, 'pay_method' => $pay_method, 'message' => $message, 'safe_option' => $safe_option, 'trust_only' => $trust_only, 'open_time' => $open_time, 'state' => 1,'ad_no' => $ad_no, 'fee'=>$coin_info['cs_ts']));
		}

		if ($rs) {
			$this->success('发布成功！', $extra);
		} else {
			$this->error('发布失败!请重试', $extra);
		}

	}

	public function ediad($id,$type,$coinid=null)
	{

		if (!userid()) {
			redirect('/login/index');
		}

		// 过滤非法字符----------------S

		if (checkstr($id) || checkstr($type) || checkstr($coinid)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E
		
		if(!check($id,'d')){
			$this->error('参数错误！');
		}
		
		if(!empty($coinid) && !check($coinid,'d')){
			$this->error('参数错误！');
		}

		if($type != 0 && $type != 1){
			$this->error("广告类型错误！");
		}
		
		$pay_method = M('PayMethod')->select();
		$this->assign('pay_method', $pay_method);

		$table = ($type == 0)?'AdBuy':'AdSell';
		$ad_info = M($table)->where(array('id' => $id,'userid' => userid()))->find();
		if (!$ad_info) {
			$this->error("广告不存在！");
		}
		$ad_info['margin'] = floatval($ad_info['margin']);
		$ad_info['type'] = $type;

		//支付方式  备注掉
		$pay_method_arr = explode(",",$ad_info['pay_method']);
		$pay_method_option = '';
		foreach($pay_method as $pm){
			if(in_array($pm['id'],$pay_method_arr)){
				$pay_method_option .= '<option value="'.$pm['id'].'" selected="selected">'.$pm['name'].'</option>';
			}else{
				$pay_method_option .= '<option value="'.$pm['id'].'">'.$pm['name'].'</option>';
			}
		}
		$this->assign('pay_method_option', $pay_method_option);

		//收款账号
        $skaccount = M('user_skaccount')->where(array('user_id' => userid()))->select();
		$skaccount_arr = explode(",",$ad_info['skaccount']);
		$skaccount_option = '';
		foreach($skaccount as $key => $sa){
            $skaccount[$key]['payname'] = $pay_method[$sa['pay_method_id'] - 1]['name'];
		    if(in_array($sa['id'], $skaccount_arr)){
                $skaccount_option .= '<option value="'.$sa['id'].'" selected="selected">'.$skaccount[$key]['payname']. '|' .$sa['name']. '|'. $sa['account'] .'</option>';
            } else {
		        $skaccount_option .= '<option value="'.$sa['id'].'">'. $skaccount[$key]['payname']. '|' .$sa['name']. '|'. $sa['account'] .'</option>';
            }
		}
        $this->assign('skaccount_option', $skaccount_option);
//		dump($skaccout_arr);exit();

        $this->assign('ad_info', $ad_info);

		$coin = M('Coin')->field('id,title,js_yw,name')->where(array('status'=>1,'name'=>array('neq','cny')))->select();
		$this->assign('coin', $coin);

		$location = M('Location')->select();
		$this->assign('location', $location);

		if(!isset($coinid)){
			$table = M('Coin')->where("id={$ad_info['coin']}")->getField('name');
			$currency = M($table)->select();
		}else{
			$table = M('Coin')->where("id=$coinid")->getField('name');
			$currency = M($table)->select();
		}
		$this->assign('currency', $currency);

		$this->assign('type', $type);

		$str = '<option value="a">结束时间</option><option value="z">关闭</option><option value="0">0:00</option><option value="1">1:00</option><option value="2">2:00</option><option value="3">3:00</option><option value="4">4:00</option><option value="5">5:00</option><option value="6">6:00</option><option value="7">7:00</option><option value="8">8:00</option>																<option value="9">9:00</option><option value="10">10:00</option><option value="11">11:00</option><option value="12">12:00</option><option value="13">13:00</option><option value="14">14:00</option><option value="15">15:00</option><option value="16">16:00</option><option value="17">17:00</option><option value="18">18:00</option><option value="19">19:00</option><option value="20">20:00</option><option value="21">21:00</option><option value="22">22:00</option><option value="23">23:00</option><option value="24">24:00</option>';
		$arr = array(0=>array('xq'=>'星期一 ','start'=>'mon_s','end'=>'mon_e','option'=>$str),1=>array('xq'=>'星期二 ','start'=>'tue_s','end'=>'tue_e','option'=>$str),2=>array('xq'=>'星期三 ','start'=>'wed_s','end'=>'wed_e','option'=>$str),3=>array('xq'=>'星期四 ','start'=>'thu_s','end'=>'thu_e','option'=>$str),4=>array('xq'=>'星期五 ','start'=>'fri_s','end'=>'fri_e','option'=>$str),5=>array('xq'=>'星期六 ','start'=>'sat_s','end'=>'sat_e','option'=>$str),6=>array('xq'=>'星期日 ','start'=>'sun_s','end'=>'sun_e','option'=>$str));
		$this->assign('arr',$arr);
		
		$newad_token = set_token('newad');
        $this->assign('newad_token', $newad_token);
		$this->display();
	}

	public function upediad($type, $id, $coin, $location, $currency, $margin, $min_price, $min_limit, $max_limit, $due_time, $message, $pay_method, $safe_option, $trust_only, $open_time,$skaccount, $token)
	{

		if (!userid()) {
			redirect('/login/index');
		}
		
		$extra = '';
		
		if (!session('newadtoken')) {
			set_token('newad');
		}
		if (!empty($token)) {
			$res = valid_token('newad', $token);
			if (!$res) {
				$this->error('请不要频繁提交！', session('newadtoken'));
			}
		}else{
			$this->error('缺少参数！', session('newadtoken'));
		}
		$extra = session('newadtoken');

		// 过滤非法字符----------------S

		if (checkstr($margin) || checkstr($min_price) || checkstr($min_limit) || checkstr($max_limit) || checkstr($due_time)) {
			$this->error('您输入的信息有误！', $extra);
		}

		// 过滤非法字符----------------E

		if($type != 0 && $type != 1){
			$this->error("广告类型错误！", $extra);
		}

		if(!check($id,'d')){
			$this->error("参数错误1！", $extra);
		}
		
		$table = ($type == 0)?'AdBuy':'AdSell';
		$ad_info = M($table)->where(array('id' => $id,'userid' => userid()))->find();
		if (!$ad_info) {
			$this->error("广告不存在！", $extra);
		}
		
		if(!empty($coin) && !check($coin,'d')){
			$this->error("参数错误2！", $extra);
		}
		
		if(!check($location,'d')){
			$this->error("参数错误3！", $extra);
		}
		
		if(!check($trust_only,'d')){
			$this->error("参数错误4！", $extra);
		}
		
		if(!check($safe_option,'d')){
			$this->error("参数错误5！", $extra);
		}
		
		if(!check($currency,'currency')){
			$this->error("参数错误6！", $extra);
		}

		if(!check($message,'bcdw')){
			$this->error("参数错误7！", $extra);
		}
		
//		if(!empty($skaccount) && !check($skaccount,'bcdw')){
//			$this->error("参数错误8！", $extra);
//		}

		if (!check($margin, 'margin')) {
			$this->error('溢价为-99.99 至 99.99 的数值', $extra);
		}

		if($min_price != ''){
			if (!check($min_price, 'currency')) {
				$this->error('最低价格式错误！', $extra);
			}
		}

		if($due_time != ''){
			if (!check($due_time, 'duetime') || $due_time>60) {
				$this->error('付款期限请填写5到60分钟之间！', $extra);
			}
		}

		if ($min_limit > $max_limit) {
			$this->error('最小限额不能大于最大限额！', $extra);
		}
		if (!check($min_limit, 'currency')) {
			$this->error('最小限额格式错误！', $extra);
		}else{
			if($min_limit < 50){$this->error('最小限额不能小于50！', $extra);}
		}
		if (!check($max_limit, 'currency')) {
			$this->error('最大限额格式错误！', $extra);
		}
		
//		if(!check($pay_method,'a')){
//			$this->error("参数错误9！", $extra);
//		}
		
		if(!check($open_time,'a')){
			$this->error("参数错误10！", $extra);
		}

		//重组开放时间
		$open_time_arr = explode(',',$open_time);
		foreach ($open_time_arr as $k => $v){
			if($this->checkopentime($v)){
				$this->error('请检查开放时间！', $extra);
			}else {
				if ($v == 'a-a' || $v == '0-24') {
					$open_time_arr[$k] = 1;
				}
				if ($v == 'z-z') {
					$open_time_arr[$k] = 0;
				}
			}
		}
		$open_time =implode(',',$open_time_arr);

		//修改出售表
		if($type == 1) {
			$rs = M('AdSell')->save(array('id' => $id, 'coin'=>$coin, 'location' => $location, 'currency' => $currency, 'margin' => $margin, 'min_price' => $min_price, 'min_limit' => $min_limit, 'max_limit' => $max_limit, 'pay_method' => $pay_method, 'message' => $message, 'safe_option' => $safe_option, 'trust_only' => $trust_only, 'open_time' => $open_time,"skaccount"=>$skaccount));
		}

		//修改购买表
		if($type == 0){
			$rs = M('AdBuy')->save(array('id' => $id, 'coin'=>$coin, 'location' => $location, 'currency' => $currency, 'margin' => $margin, 'due_time' => $due_time, 'min_limit' => $min_limit, 'max_limit' => $max_limit, 'pay_method' => $pay_method, 'message' => $message, 'safe_option' => $safe_option, 'trust_only' => $trust_only, 'open_time' => $open_time));
		}

		if ($rs) {
			$this->success('保存成功！', $extra);
		} else {
			$this->error('保存失败!请重试', $extra);
		}
	}

	//检查开放时间,错误返回1
	public function checkopentime($time){
		//错误时间格式正则
		$regex = '/^\d{1,2}-[a,z]$|^[a,z]-\d{1,2}$|^a-z$|^z-a$/';
		if(preg_match($regex, $time)) {
			return 1;
		}else{
			$time_arr = explode('-',$time);
			if(is_numeric($time_arr[0]) && is_numeric($time_arr[1])){
				if($time_arr[0] >= $time_arr[1]){
					return 1;
				}
			}
		}
	}

	//广告上下架
	public function setShelf($id,$type,$act,$token){

		if (!userid()) {
			redirect('/login/index');
		}

		$extra = '';
		
		if(!session('shelftoken')) {
			set_token('shelf');
		}
		if(!empty($token)){
			$res = valid_token('shelf',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('shelftoken'));
			}
		}else{
			$this->error('缺少参数！',session('shelftoken'));
		}
		$extra=session('shelftoken');

		// 过滤非法字符----------------S

		if (checkstr($id) || checkstr($type) || checkstr($act)) {
			$this->error('您输入的信息有误！',$extra);
		}

		// 过滤非法字符----------------E
		
		if(!check($id,'d')){
			$this->error("参数错误！", $extra);
		}
		
		if(!check($act,'d')){
			$this->error("参数错误！", $extra);
		}

		if($type != 0 && $type != 1){
			$this->error("广告类型错误！",$extra);
		}

		$table = ($type == 0)?'AdBuy':'AdSell';
		$ad_info = M($table)->where(array('id' => $id,'userid' => userid()))->find();
		if (!$ad_info) {
			$this->error("广告不存在！",$extra);
		}else{
			if($ad_info['state'] ==4){
				$this->error("此广告已冻结禁止上下架操作！",$extra);
			}
		}

		$result = M($table)->where(array('id' => $id,'userid' => userid()))->setField('state',$act);
		if(!empty($result)){
			$this->success("操作成功",$extra);
		}else{
			$this->error("操作失败",$extra);
		}
	}

	//广告详情
	public function advdetail($type,$id){

        if (checkstr($id) ||checkstr($type)) {
            $this->error('您输入的信息有误！');
        }

        if($type!=0 && $type!=1 ){
        	$this->error('参数错误！');
        }

        if(!check($id,'d') || $id<=0){
        	$this->error('参数错误！');
        }

		//加载聊天信息
		if($type==0){
			$record = M('ad_buy')->where(array('id'=>$id))->find();
			if(empty($record)){
        		$this->error('此广告不存在');
        	}
        	$ltime=$record['due_time'];
		}
		if($type==1){
			$record = M('ad_sell')->where(array('id'=>$id))->find();
       		if(empty($record)){
        		$this->error('此广告不存在');
        	}
        	$ltime=C('sfk_time');
		}
		//币种
		$coin = M('Coin')->where(array('id'=>$record['coin']))->find();
		$this->assign("coin",$coin);

		//货币
		$currency = get_price($record['coin'],$record['currency'],0);
		//$currency = M('Currency')->where(array('id'=>$record['currency']))->find();
        $this->assign("currency",$currency);

		//付款方式
        $paymethod =getpaymethod($record['pay_method']);// M('pay_method')->where(array('id'=>$record['pay_method']))->find();
        $this->assign("paymethod",$paymethod);

		//查找用户的信息
        $adverinfo = M('user')->where(array('id'=>$record['userid']))->find();
        //如果在线查询一下账户的余额
        $coin_number=0;
        if(userid()){
        	$my_coin_info = M('user_coin')->where(array('userid'=>userid()))->find();
        	$coin_number = $my_coin_info[$coin['name']];
			if(userid() != $record['userid']){
				//判断一下双方能不能交易
				$trade_permit = tradepermit(userid(),$record);
				$this->assign('trade_permit',$trade_permit);
			}
        }

		$record['username'] = $adverinfo['enname'];
		$adverinfo['history'] = floatval($adverinfo['history']);
		//$cprice = M('Currency')->where(array('id'=>$record['currency']))->getField('price');
		$cprice = get_price($record['coin'],$record['currency'],1);
		if($cprice < $record['min_price']){$cprice = $record['min_price'];}
		$record['price'] = $cprice*(1+($record['margin']/100));
		$record['price'] = round($record['price'],2);
		$location = M('Location')->where(array('id'=>$record['location']))->find();
		$record['region'] = "（".$location['short_name']." ".$location['name']."）";
		$this->assign('adv',$record);

		//生成token
		$chat_token = set_token('chat');
		$this->assign('chat_token',$chat_token);

		$module = D('Chat');
		$chatlist = $module->listbyAdvid($id,$type,userid(),"ASC",0);
		foreach($chatlist as $key=>$val){
			$chatlist[$key]['fromuser_img'] = headimg($val['fromuid']);
			$chatlist[$key]['touser_img'] = headimg($val['touid']);
			$chatlist[$key]['fromuser_name'] = Isheadimg($val['fromuid']);
			$chatlist[$key]['touser_name'] = Isheadimg($val['touid']);
		}
		$this->assign('chatlist',$chatlist);

		$this->assign('chatnum',count($chatlist));

		$this->assign('iftrust', $this->iftruban($record['userid'],1));
		$this->assign('ifban', $this->iftruban($record['userid'],2));
		$this->assign('trust', gettrust($adverinfo['id']));
        $this->assign('coin_number', $coin_number);
        $this->assign('ltime', $ltime);
        $this->assign('type', $type);
        $this->assign('price', $record['price']);
        $this->assign('adverinfo', $adverinfo);


		$selllist = M('AdSell')->where(array('userid' => $record['userid'], 'state' => 1))->select();
		foreach ($selllist as $k=> $v){
			$selllist[$k]['pay_method'] =skaccount_get_account($v['skaccount']);
//			$selllist[$k]['pay_method'] =getpaymethod($v['pay_method']);// M('PayMethod')->where(array('id'=>$v['pay_method']))->getField('name');
			$selllist[$k]['currency_type'] = get_price($v['coin'],$v['currency'],0);
			$selllist[$k]['coin'] = M('Coin')->where(array('id'=>$v['coin']))->getField('name');
			$price = get_price($v['coin'],$v['currency'],1);
			if($price < $selllist[$k]['min_price']){
				$price = $selllist[$k]['min_price'];
			}
			$selllist[$k]['price'] = round($price + $price * $v['margin']/100,2);
			$ifopen = ifopen($v['id'],1);
			if($ifopen == 0){
				unset($selllist[$k]);
			}
		}

		$buylist = M('AdBuy')->where(array('userid'=>$record['userid'],'state'=>1))->select();
		foreach ($buylist as $k=> $v){
			$buylist[$k]['pay_method'] =getpaymethod($v['pay_method']);// M('PayMethod')->where(array('id'=>$v['pay_method']))->getField('name');
			$buylist[$k]['currency_type'] = get_price($v['coin'],$v['currency'],0);
			$buylist[$k]['coin'] = M('Coin')->where(array('id'=>$v['coin']))->getField('name');
			$price = get_price($v['coin'],$v['currency'],1);
			$buylist[$k]['price'] = round($price + $price * $v['margin']/100,2);
			$ifopen = ifopen($v['id'],0);
			if($ifopen == 0){
				unset($buylist[$k]);
			}
		}

		$this->assign('buylist', $buylist);
		$this->assign('selllist', $selllist);
		
		$truban_token = set_token('truban');
        $this->assign('truban_token', $truban_token);
		
		$myxd_token = set_token('myxd');
        $this->assign('myxd_token', $myxd_token);

        $this->display();
	}
	
	//修改信任屏蔽状态
	public function truban($id,$token,$act)
	{
		$extra = '';
		
		if(!session('trubantoken')) {
			set_token('truban');
		}
		if(!empty($token)){
			$res = valid_token('truban',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('trubantoken'));
			}
		}else{
			$this->error('缺少参数！',session('trubantoken'));
		}
		$extra=session('trubantoken');
		
		if (!userid()) {
			$this->error('请先登录再操作！',$extra);
		}

		// 过滤非法字符----------------S

		if (checkstr($id) || checkstr($act)) {
			$this->error('您输入的信息有误！',$extra);
		}

		// 过滤非法字符----------------E
		
		if(!check($id,'d')){
			$this->error("参数错误！", $extra);
		}
		
		if(!check($act,'c')){
			$this->error("参数错误！", $extra);
		}

		//获取信任字段id字符串
		$user = M('User')->where(array('id' => $id))->find();
		if (!$user) {
			$this->error("用户不存在！",$extra);
		}
		$trust_ids = $user['xinren'];
		$ban_ids = $user['pingbi'];
		//拆成数组
		$trust_ids_arr = explode(",",$trust_ids);
		$ban_ids_arr = explode(",",$ban_ids);
		$result=array();
		//设为信任
		if($act == '信任此用户') {
			if(in_array(userid(),$trust_ids_arr)){
				$this->error("您已信任过！",$extra);
			}else {
				//把自己的id拼入信任id字符串
				$new_trust_ids = $trust_ids.','.userid();
				$result[] = M('User')->where(array('id' => $id))->setField('xinren',$new_trust_ids);
				//把对方的id拼入我信任的id字符串
				$itrust_ids = M('User')->where(array('id' => userid()))->getField('ixinren');
				$new_itrust_ids = $itrust_ids.','.$id;
				$result[] = M('User')->where(array('id' => userid()))->setField('ixinren',$new_itrust_ids);
				if (!empty($result)) {
					$this->success("信任成功", $extra);
				} else {
					$this->error("信任失败!请重试", $extra);
				}
			}
		}
		//取消信任
		if($act == "取消信任") {
			if(!in_array(userid(),$trust_ids_arr)){
				$this->error("您没有信任过！",$extra);
			}else {
				//从信任id字符串去掉自己的id
				//$new_trust_ids = str_replace(','.userid(),'',$trust_ids);
				$new_trust_ids = delID($trust_ids_arr,userid());
				$result[] = M('User')->where(array('id' => $id))->setField('xinren',$new_trust_ids);
				//把对方的id从我信任的id字符串去掉
				$itrust_ids = M('User')->where(array('id' => userid()))->getField('ixinren');
				$itrust_ids_arr = explode(",",$itrust_ids);
				$new_itrust_ids = delID($itrust_ids_arr,$id);
				$result[] = M('User')->where(array('id' => userid()))->setField('ixinren',$new_itrust_ids);
				if (!empty($result)) {
					$this->success("取消信任成功", $extra);
				} else {
					$this->error("取消信任失败!请重试", $extra);
				}
			}
		}
		//设为屏蔽
		if($act == '屏蔽此用户') {
			if(in_array(userid(),$ban_ids_arr)){
				$this->error("您已屏蔽过！",$extra);
			}else {
				//把自己的id拼入屏蔽id字符串
				$new_ban_ids = $ban_ids.','.userid();
				$result[] = M('User')->where(array('id' => $id))->setField('pingbi',$new_ban_ids);
				//把对方的id拼入我屏蔽的id字符串
				$iban_ids = M('User')->where(array('id' => userid()))->getField('ipingbi');
				$new_iban_ids = $iban_ids.','.$id;
				$result[] = M('User')->where(array('id' => userid()))->setField('ipingbi',$new_iban_ids);
				if (!empty($result)) {
					$this->success("屏蔽成功", $extra);
				} else {
					$this->error("屏蔽失败!请重试", $extra);
				}
			}
		}
		//取消屏蔽
		if($act == "取消屏蔽") {
			if(!in_array(userid(),$ban_ids_arr)){
				$this->error("您没有屏蔽过！",$extra);
			}else {
				//从屏蔽id字符串去掉自己的id
				//$new_ban_ids = str_replace(','.userid(),'',$ban_ids);
				$new_ban_ids = delID($ban_ids_arr,userid());
				$result = M('User')->where(array('id' => $id))->setField('pingbi',$new_ban_ids);
				//把对方的id从我信任的id字符串去掉
				$iban_ids = M('User')->where(array('id' => userid()))->getField('ipingbi');
				$iban_ids_arr = explode(",",$iban_ids);
				$new_iban_ids = delID($iban_ids_arr,$id);
				$result[] = M('User')->where(array('id' => userid()))->setField('ipingbi',$new_iban_ids);
				if (!empty($result)) {
					$this->success("取消屏蔽成功", $extra);
				} else {
					$this->error("取消屏蔽失败!请重试", $extra);
				}
			}
		}
	}

	public function upChat($content, $chatpic="", $touid, $advid, $advtype, $token){
		$extra='';
		
		if(!session('chattoken')) {
			set_token('chat');
		}
		if(!empty($token)){
			$res = valid_token('chat',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('chattoken'));
			}
		}else{
			$this->error('缺少参数！',session('chattoken'));
		}
		$extra=session('chattoken');

		if (!userid()) {
			$this->error('您没有登录请先登录！',$extra);
		}

		if(empty($content)){
			$this->error("请输入对话内容！",$extra);
		}
		
		if(!check($content,'bcdw')){
			$this->error("只能输入中英文和数字！",$extra);
		}
		
		if(!empty($chatpic) && !check($chatpic,'a') && !check($chatpic,'url')){
			$this->error("参数错误！",$extra);
		}

		if(empty($touid) || empty($advid) || !isset($advtype)){
			$this->error("缺少参数！",$extra);
		}
		
		if(!check($touid,'d')){
			$this->error("参数错误！",$extra);
		}
		
		if(!check($advid,'d')){
			$this->error("参数错误！",$extra);
		}

		if($touid==userid()){
			$this->error("这是您自己发布的广告！",$extra);
		}

		$time = time();
		$status=0;
		if($advtype==0){
			$ordertype = 2;
			$adv_buy = M('ad_buy')->where(array('id'=>$advid))->find();
			if(empty($adv_buy)){
				$this->error("参数错误！",$extra);
			}else{
				$order_sell = M('order_sell')->where(array('buy_bid'=>$adv_buy['id'],'sell_id'=>userid(),'status'=>array('lt',4)))->order('ctime desc')->find();
				if(empty($order_sell)){
					$temp_order_sell = M('order_temp')->where(array('ordertype'=>2,'buy_id'=>$adv_buy['userid'],'buy_bid'=>$adv_buy['id'],'sell_id'=>userid()))->find();
					if(!empty($temp_order_sell)){
						$orderid = $temp_order_sell['id'];
						$status=3;
					}else{
						$res = M('order_temp')->add(array('ordertype'=>2,'buy_id'=>$adv_buy['userid'],'buy_bid'=>$adv_buy['id'],'sell_id'=>userid(),'ctime'=>$time));
						if(!empty($res)){
							$orderid = $res;
							$status=3;
						}else{
							$this->error("提交失败1！",$extra);
						}
					}
				}else{
					$orderid = $order_sell['id'];
				}
			}
		}elseif($advtype==1){
			$ordertype = 1;
			$adv_sell = M('ad_sell')->where(array('id'=>$advid))->find();
			if(empty($adv_sell)){
				$this->error("参数错误！",$extra);
			}else{
				$order_buy = M('order_buy')->where(array('sell_sid'=>$adv_sell['id'],'buy_id'=>userid(),'status'=>array('lt',4)))->order('ctime desc')->find();
				if(empty($order_buy)){
					$temp_order_buy = M('order_temp')->where(array('ordertype'=>1,'buy_id'=>userid(),'sell_sid'=>$adv_sell['id'],'sell_id'=>$adv_sell['userid']))->find();
					if(!empty($temp_order_buy)){
						$orderid = $temp_order_buy['id'];
						$status=3;
					}else{
						$res = M('order_temp')->add(array('ordertype'=>1,'buy_id'=>userid(),'sell_sid'=>$adv_sell['id'],'sell_id'=>$adv_sell['userid'],'ctime'=>$time));
						if(!empty($res)){
							$orderid = $res;
							$status=3;
						}else{
							$this->error("提交失败2！",$extra);
						}
					}
				}else{
					$orderid = $order_buy['id'];
				}
			}
		}

		$module = D('Chat');
		$result = $module->addRecord(userid(),($touid*1),$orderid,$ordertype,$status,$content,$chatpic,$advid,$advtype);
		if(!empty($result)){
			$this->success("提交成功",$extra);
		}else{
			$this->error("提交失败3！",$extra);
		}
	}

	public function chatPic(){
		$token = $_POST['token'];
		if(!session('pictoken')) {
			set_token('pic');
		}
		if(!empty($token)){
			$res = valid_token('pic',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('pictoken'));
			}
		}else{
			$this->error('缺少参数！',session('pictoken'));
		}
		$extra=session('pictoken');
		
		if (!userid()) {
			$this->error('您没有登录请先登录！',$extra);
		}
		$touid = intval($_POST['touid']);
		$advid = intval($_POST['advid']);
		$advtype = intval($_POST['advtype']);
		
		if(empty($touid) || empty($advid) || !isset($advtype)){
			$this->error("缺少参数！",$extra);
		}
		
		if(!check($touid,'d') || !check($advid,'d')){
			$this->error("参数错误！",$extra);
		}
		
		if($touid==userid()){
			$this->error("这是您自己发布的广告！",$extra);
		}
		$status=0;
		if(!empty($_FILES)) {
			$update = array();
			$upload = new \Think\Upload();//实列化上传类
			$upload->maxSize=3145728;//设置上传文件最大，大小
			$upload->exts= array('jpg','gif','png','jpeg');//后缀
			$upload->rootPath ='./Upload/lanch/chat/';//上传目录
			$upload->savePath      =  ''; // 设置附件上传（子）目录
			$upload->autoSub     = true;
			$upload->subName     = array('date','Ymd');
			$upload->saveName = array('uniqid','');//设置上传文件规则
			$info= $upload->upload();//执行上传方法
			if($info){
				$image = new \Think\Image();
				foreach($info as $key=>$file){
					if(!empty($file)){
						$image->open('./Upload/lanch/chat/'.$file['savepath'].$file['savename']);
						$width = $image->width();
						$height = $image->height();
						if(empty($width) || empty($height)){
							$bili = 1;
						}else{
							$bili = intval($width/$height);
						}
						$new_width = 600;
						$new_height = intval($new_width/$bili);
						// 按照原图的比例生成一个最大宽度为600像素的缩略图并删除原图
						$image->thumb($new_width, $new_height)->save('./Upload/lanch/chat/'.$file['savepath']."s_".$file['savename']);
						unlink('./Upload/lanch/chat/'.$file['savepath'].$file['savename']);
					}
				}
			}
			if(!empty($info['upchatpic']['savename'])){
				$chatpic = '/Upload/lanch/chat/'.$info['upchatpic']['savepath']."s_".$info['upchatpic']['savename'];
				$time = time();
				if($advtype==0){
					$ordertype = 2;
					$adv_buy = M('ad_buy')->where(array('id'=>$advid))->find();
					if(empty($adv_buy)){
						$this->error("参数错误！",$extra);
					}else{
						$order_sell = M('order_sell')->where(array('buy_bid'=>$adv_buy['id'],'sell_id'=>userid(),'status'=>array('lt',4)))->order('ctime desc')->find();
						if(empty($order_sell)){
							$temp_order_sell = M('order_temp')->where(array('ordertype'=>2,'buy_id'=>$adv_buy['userid'],'buy_bid'=>$adv_buy['id'],'sell_id'=>userid()))->find();
							if(!empty($temp_order_sell)){
								$orderid = $temp_order_sell['id'];
								$status = 3;
							}else{
								$res = M('order_temp')->add(array('ordertype'=>2,'buy_id'=>$adv_buy['userid'],'buy_bid'=>$adv_buy['id'],'sell_id'=>userid(),'ctime'=>$time));
								if(!empty($res)){
									$orderid = $res;
									$status = 3;
								}else{
									$this->error("提交失败！",$extra);
								}
							}
						}else{
							$orderid = $order_sell['id'];
						}
					}
				}elseif($advtype==1){
					$ordertype = 1;
					$adv_sell = M('ad_sell')->where(array('id'=>$advid))->find();
					if(empty($adv_sell)){
						$this->error("参数错误！",$extra);
					}else{
						$order_buy = M('order_buy')->where(array('sell_sid'=>$adv_sell['id'],'buy_id'=>userid(),'status'=>array('lt',4)))->order('ctime desc')->find();
						if(empty($order_buy)){
							$temp_order_buy = M('order_temp')->where(array('ordertype'=>1,'buy_id'=>userid(),'sell_sid'=>$adv_sell['id'],'sell_id'=>$adv_sell['userid']))->find();
							if(!empty($temp_order_buy)){
								$orderid = $temp_order_buy['id'];
								$status = 3;
							}else{
								$res = M('order_temp')->add(array('ordertype'=>1,'buy_id'=>userid(),'sell_sid'=>$adv_sell['id'],'sell_id'=>$adv_sell['userid'],'ctime'=>$time));
								if(!empty($res)){
									$orderid = $res;
									$status = 3;
								}else{
									$this->error("提交失败！",$extra);
								}
							}
						}else{
							$orderid = $order_buy['id'];
						}
					}
				}
				$module = D('Chat');
				$result = $module->addRecord(userid(),($touid*1),$orderid,$ordertype,$status,"",$chatpic,$advid,$advtype);
				if(!empty($result)){
					header('Location:/Newad/upload.html?touid='.$touid.'&advid='.$advid.'&advtype='.$advtype);
				}else{
					$this->error("提交失败！",$extra);
				}
			}else{
				$this->error("上传失败！",$extra);
			}
		}else{
			$this->error("请选择文件！",$extra);
		}
	}

	private function getadvno(){
		$code = '';
		for($i=1;$i<=5;$i++){
			$code .= chr(rand(97,122));
		}
		$adv_no = $code.time();
		$advbuy = M('ad_buy')->where(array('ad_no'=>$adv_no))->find();
		$advsell = M('ad_sell')->where(array('ad_no'=>$adv_no))->find();
		if(!empty($advbuy) || !empty($advsell)){
			$this->getadvno();
		}else{
			return $adv_no;
		}
	}

	public function upload(){
		$touid = intval($_GET['touid']);
		$advid = intval($_GET['advid']);
		$advtype = intval($_GET['advtype']);
		$this->assign('touid',$touid);
		$this->assign('advid',$advid);
		$this->assign('advtype',$advtype);
		//生成token
		$pic_token = set_token('pic');
		$this->assign('pic_token',$pic_token);
		$this->display();
	}
}