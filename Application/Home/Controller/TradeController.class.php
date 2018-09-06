<?php

namespace Home\Controller;

class TradeController extends HomeController

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
		$display_action=array("index");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}

	public function index($type=0){
		if($type != 0 && $type != 1){
			$this->error("广告类型错误！");
		}
		$where=array();
		$where['a.state'] = 1;
		$where['b.is_agree']=1;
		if($_GET){
			$obj=$_GET['obj'];$coin=$_GET['coin'];$loca=$_GET['loca'];$curr=$_GET['curr'];$paym=$_GET['paym'];$uname=$_GET['uname'];
			if (checkstr($coin) ||checkstr($loca) || checkstr($curr) || checkstr($paym) || checkstr($uname)) {
				$this->error('您输入的信息有误！');
			}
			if($obj==1){
				if($coin != ''){$where['a.coin'] = $coin;}
				if($loca != ''){$where['a.location'] = $loca;}
				if($curr != ''){$where['a.currency'] = $curr;}
				if($paym != ''){$where['a.pay_method'] = $paym;}
			}elseif($obj==2){
				if($uname != ''){$where['b.enname'] = $uname;}
			}
		}

//        //判断有没有收款账号
//        $has_skaccount = M('user_skaccount')->where(array('user_id' => userid()))->select();
//        if (!count($has_skaccount)) {
//            $this->error("请先设置收款账号！", U('User/skaccount'));
//        }

		$Module = ($type == 0)?M('Ad_buy a'):M('Ad_sell a');
		//获取满足条件的记录id
        $count_id = $Module->join('tw_user b on a.userid=b.id')->where($where)->field('a.id as id,a.userid')->select();

		// var_dump($Module->getlastsql());
		//过滤在隐藏时间记录后的记录数
		$count=0;
		//过滤在隐藏时间记录后的记录id字符串
		$id_list = '';
		$uid_list = '';
		//进行过滤计算记录数并拼接id字符串
		foreach($count_id as $v){
			$ifopen = ifopen($v['id'],$type);
			//过滤开放时间
			if($ifopen == 0){
				continue;
			}else {
				$count++;
				$id_list = $id_list . ',' . $v['id'];
				$uid_list = $uid_list. ',' . $v['userid'];
			}
		}
		$id_list = trim($id_list,',');
		$uid_list = trim($uid_list,',');
		$this->assign('uid_list',$uid_list);

		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = $Module->where(array('id'=>array('in',$id_list)))->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['coin'] = M('Coin')->where(array('id'=>$v['coin']))->getField('name');
			$list[$k]['location'] = M('Location')->where(array('id'=>$v['location']))->getField('short_name');
			$list[$k]['currency_type'] = get_price($v['coin'],$v['currency'],0);
			$price = get_price($v['coin'],$v['currency'],1);
			if($price < $list[$k]['min_price']){
				$price = $list[$k]['min_price'];
			}
			$list[$k]['price'] = round($price + $price * $v['margin']/100,2);
			// $list[$k]['pay_method'] = M('PayMethod')->where(array('id'=>$v['pay_method']))->getField('name');
			if($type == 0){
                $list[$k]['pay_method'] = getpaymethod($v['pay_method']);
            } else {
                $list[$k]['pay_method'] = skaccount_get_account($v['skaccount']);
            }
			$adverinfo = M('User')->where(array('id'=>$v['userid']))->find();
			$list[$k]['avatar'] = $adverinfo['headimg'];
			$list[$k]['enname'] = $adverinfo['enname'];
			$list[$k]['transact'] = $adverinfo['transact'];
			$list[$k]['goodcomm'] = $adverinfo['goodcomm'];
			$list[$k]['trust'] = gettrust($adverinfo['id']);
			$list[$k]['status'] = $adverinfo['loginstatus'];
		}

		$coin = M('Coin')->field('id,title,js_yw,name')->where(array('status'=>1,'name'=>array('neq','cny')))->select();
		$this->assign('coin', $coin);

		$location = M('Location')->select();
		$this->assign('location', $location);

		$currency = M('Btc')->select();
		$this->assign('currency', $currency);

		$pay_method = M('PayMethod')->select();
		$this->assign('pay_method', $pay_method);

		$this->assign('type', $type);
		$this->assign('list', $list);
		$this->assign('page', $show);

		$this->display();
	}
}
?>