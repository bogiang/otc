<?php
namespace Home\Controller;

class OrderController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","myzc","upmyzc","mywt","mycj","upmyzr","mytj","mywd","uptrade_ajax","chongqi_ajax","ordercancle_ajax","orderbjcancle_ajax","orderinfo","trade_ajax","sfbtc_ajax","orderlist","comment_ajax","upChat","shensu_ajax","markRead","tmpbill_ajax","upload","chatPic","inupload","inchatPic","toadmin");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function __construct() {
		parent::__construct();
		$display_action=array("index","orderinfo","trade_ajax","orderlist");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}
	
	public function index(){
		
	}
	public function listtemp(){

        $list=M('order_temp')->where("(buy_id=".userid()." or sell_id=".userid().")")->select();

        $module = D('Chat');
        foreach ($list as $key => $vv) {
            //查找币的类型
            $coinname = M('Coin')->where(array('id'=>$vv['deal_coin']))->getfield('name');
            $coinname=strtoupper($coinname);
            //新加交易伙伴头像 用户名 
            if($vv['buy_id']==userid()){
                $list[$key]['hinfo']=getinfo($vv['sell_id']);
                $list[$key]['ztype']='购买'.$coinname;
            }
            if($vv['sell_id']==userid()){
                $list[$key]['hinfo']=getinfo($vv['buy_id']);
                $list[$key]['ztype']='出售'.$coinname;
            }
            $list[$key]['order_no']='未创建';
            $list[$key]['deal_num']=0;
            if($vv['ordertype']==2){
                $list[$key]['advtype']=0;
				$list[$key]['aid']=$vv['buy_bid'];
            }else{
                $list[$key]['advtype']=1;
				$list[$key]['aid']=$vv['sell_sid'];
            }
            $list[$key]['deal_amount']=0;
            $list[$key]['zt']='';
            $list[$key]['chatlist'] = $module->listbyOrderid($vv['id'],$vv['ordertype'],3);
            $list[$key]['chatnum'] = count($list[$key]['chatlist']);
			$list[$key]['type'] = $vv['ordertype'];
        }

        //生成token
        $chatorder_token = set_token('chatorder');
        $this->assign('chatorder_token',$chatorder_token);
        $this->assign("status",3);
		$this->assign("myid",userid());
        $this->assign("list",$list);
        $this->display();
       
    }
	public function orderlist($status=0){

		//0代表进行中1已完成的
		if(!userid()){
			redirect('/#login');
		}
		if($status!=0 && $status!=1 && $status!=3){
			$this->error("参数错误");
		}
        if($status==3){
            $this->listtemp();
            exit;
        }
        $user=M('user')->where("id=".userid())->find();
		$this->assign('myname',$user['enname']);
        
		if($status==0){
			$where="(status<=3 or status=6)";
		}
		else{
			$where="(status=4 or status=5)";
		}
		
		$where .= " and (buy_id=".userid()." or sell_id=".userid().")";

		$query = M()->query("select id from tw_order_buy where $where union all select id from tw_order_sell where $where");
		$count = count($query);
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$limit = $Page->firstRow . " , " . $Page->listRows;
		$list = M()->query("select type,order_no from (select ctime,status,buy_id,sell_id,type,order_no from tw_order_buy union all select ctime,status,buy_id,sell_id,type,order_no from tw_order_sell) as temp where $where order by ctime desc limit $limit");
		$module = D('Chat');
		foreach ($list as $key => $vv) {
			if($vv['type'] == 1){
				$vv = M('order_buy')->where(array('order_no'=>$vv['order_no']))->find();
			}
			if($vv['type'] == 2){
				$vv = M('order_sell')->where(array('order_no'=>$vv['order_no']))->find();
			}
            //查找币的类型
            $coinname = M('Coin')->where(array('id'=>$vv['deal_coin']))->getfield('name');
            $coinname=strtoupper($coinname);
			//新加交易伙伴头像 用户名 
			if($vv['buy_id']==userid()){
				$list[$key]['hinfo']=getinfo($vv['sell_id']);
				$list[$key]['ztype']='购买'.$coinname;
			}
			if($vv['sell_id']==userid()){
				$list[$key]['hinfo']=getinfo($vv['buy_id']);
				$list[$key]['ztype']='出售'.$coinname;
			}
            

			$list[$key]['zt']=getstatus($vv['status'],$vv['buy_id'],$vv['sell_id'],userid(),$vv['buy_pj'],$vv['sell_pj']);
			$list[$key]['chatlist'] = $module->listbyOrderid($vv['id'],$vv['type'],0);
			$list[$key]['chatnum'] = count($list[$key]['chatlist']);
			$list[$key]['id'] = $vv['id'];
			$list[$key]['ctime'] = $vv['ctime'];
			$list[$key]['deal_amount'] = $vv['deal_amount'];
			$list[$key]['deal_num'] = $vv['deal_num'];
			$list[$key]['type'] = $vv['type'];
			$list[$key]['status'] = $vv['status'];
			$list[$key]['buy_id'] = $vv['buy_id'];
			$list[$key]['buy_pj'] = $vv['buy_pj'];
			$list[$key]['sell_id'] = $vv['sell_id'];
			$list[$key]['sell_pj'] = $vv['sell_pj'];
            //货币的名字
            $table = M('Coin')->where("id=".$vv['deal_coin'])->getField('name');
            $deal_ctype ='';
            if($table){
                $deal_ctype = M($table)->where("id=".$vv['deal_ctype'])->getField('short_name');
            }
            $list[$key]['hb'] = $deal_ctype;
		}

		//生成token
		$mybj_token = set_token('mybj');
		$this->assign('mybj_token',$mybj_token);

		$myxdc_token = set_token('myxdc');
		$this->assign('myxdc_token',$myxdc_token);
		
		$mysfb_token = set_token('mysfb');
		$this->assign('mysfb_token',$mysfb_token);
		
		//生成token
		$chatorder_token = set_token('chatorder');
		$this->assign('chatorder_token',$chatorder_token);
		
		$this->assign("status",$status);
		$this->assign("myid",userid());

        $tishi = cookie('tishi');
        $this->assign('tishi',$tishi);
        $this->assign('user',$user);
		
		$this->assign('list',$list);
		$this->assign("page",$show);
		$this->display();
	}

	public function trade_ajax($type,$num,$tid,$tamount,$token,$baojia,$lmessage){
		//type0买广告1卖广告 num用户要交易的数量 tid广告的id tamount用户要交易的价格
		if(!userid()){
			redirect("/Login/index.html");
		}
		
        if(!session('myxdtoken')) {
			set_token('myxd');
		}
		if(!empty($token)){
			$res = valid_token('myxd',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('myxdtoken'));
			}
		}else{
			$this->error('缺少参数！',session('myxdtoken'));
		}
		$extra=session('myxdtoken');
		
		$my=M('user')->where(array('id'=>userid()))->find();
		$mycoin=M('user_coin')->where(array('userid'=>userid()))->find();

		if($tid<=0){
			$this->error('广告不存在',$extra);
		}
		if(!check($tid,'d')){
			$this->error('参数错误！',$extra);
		}
		if($type!=0 && $type!=1){
			$this->error('参数错误！',$extra);
		}
		if($num<=0){
			$this->error('交易数量必须大于0',$extra);
		}
		if(!check($num,'currency')){
			$this->error('交易数量格式错误',$extra);
		}
		if($tamount<=0){
			$this->error('交易金额必须大于0',$extra);
		}
		if(!check($tamount,'currency')){
			$this->error('交易金额格式错误',$extra);
		}
		if(!check($baojia,'currency')){
			$this->error('参数错误',$extra);
		}
		if(!empty($lmessage) && !check($lmessage,'bcdw')){
			$this->error('留言格式错误',$extra);
		}

		/**************我要购买*******************/
		if($type==1){

			$orderinfo=M('ad_sell')->where(array('id'=>$tid))->find();
       		if(!$orderinfo){
        		$this->error('此广告不存在',$extra);
        	}
        	//时间函数判断
        	$is_time=opentime($orderinfo['open_time']);
        	if(!$is_time){
        		$this->error('此广告在当前时间未开放',$extra);
        	}
        	

        	//实名认证判断
        	if($orderinfo['safe_option']==1){
        		//购买者需要进行实名认证
        		if($my['is_agree']==0){
        			$this->error('你需要先进行实名认证，再来交易',$extra);
        		}
        	}
        	//是否是我的信任用户
        	if($orderinfo['trust_only']==1){
                $trus=truspingbi($orderinfo['userid']);
                $trus=explode(",",$trus['xinren']);
                if(!in_array(userid(),$trus)){
                    $this->error('你不是对方的信任用户',$extra);
                }
        	}
			$coin_name = M('Coin')->where(array('id'=>$orderinfo['coin']))->getField('name');
			$table = "tw_".$coin_name."_log";
            //搜索卖家的账户余额
            $sellcoin=M('user_coin')->where('userid='.$orderinfo['userid'])->find();

        	//当市场价低于保底价格是按照保底价格展现
        	//币种
        	//$coin=M('currency')->where(array('id'=>$orderinfo['currency']))->find();
			$coin = get_price($orderinfo['coin'],$orderinfo['currency'],1);
			$price=intval($coin*(1+$orderinfo['margin']/100)*100)/100;
        	//$price=intval($coin['price']*(1+$orderinfo['margin']/100)*100)/100;
        	$bdprice=$price>$orderinfo['minprice']?$price:$orderinfo['minprice'];

			//卖家要扣手续费
			$fee = round($num*$orderinfo['fee']/100,8);
			$realnum = $num+$fee;
            if($realnum*1>$sellcoin[$coin_name]*1){
				$seller_left = $sellcoin[$coin_name]*$bdprice;
                $this->error('卖家'.strtoupper($coin_name).'余额'.$sellcoin[$coin_name]."，修改购买信息或者联系卖家充值后才能下单",$extra);
            }

            //手机认证
            $sellinfo=M('user')->where("id=".$orderinfo['userid'])->find();

            //搜索系统设置
            $sconfig=M('config')->where("id=1")->find();
            $ltime=$sconfig['sfk_time']?$sconfig['sfk_time']:60;
			if(abs($tamount-($baojia*$num))>($tamount*0.001)){
				$this->error('下单失败！',$extra);
			}
        	$arr=array();
        	$arr['buy_id']=userid();

        	$arr['sell_id']= $orderinfo['userid'];
        	$arr['sell_sid']= $orderinfo['id'];

        	$arr['deal_amount']=$tamount;
        	$arr['deal_num']=$num ;
        	$arr['deal_price']=$baojia;
        	$arr['deal_ctype']=$orderinfo['currency'] ;
			$arr['deal_coin']=$orderinfo['coin'] ;
        	$arr['ctime']=time() ;
        	$arr['ltime']= $ltime ;
        	$arr['order_no']=$this->getorderno(userid(),$orderinfo['userid']);
			$arr['fee'] = $fee;
			if($lmessage!="告诉ta您的要求"){
				$arr['lmessage'] = $lmessage;
			}
			
            try{
                $mo = M();
                $mo->startTrans();
        	    $rs[]=$id=$mo->table('tw_order_buy')->add($arr);

                //卖家的btc需要冻结起来
                $rs[]=$mo->table('tw_user_coin')->where("userid=".$orderinfo['userid'])->setDec($coin_name,$realnum);
				$rs[]=$mo->table($table)->add(array('username'=>$sellinfo['username'],'userid'=>$sellinfo['id'],'ctime'=>time(),'type'=>1,'plusminus'=>0,'amount'=>$realnum,'desc'=>'买家下单减可用'.strtoupper($coin_name).'，手续费'.$fee,'operator'=>userid(),'ctype'=>1,'action'=>1,'addip'=>get_client_ip()));
                $rs[]=$mo->table('tw_user_coin')->where("userid=".$orderinfo['userid'])->setInc($coin_name.'d',$realnum);
				$rs[]=$mo->table($table)->add(array('username'=>$sellinfo['username'],'userid'=>$sellinfo['id'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$realnum,'desc'=>'买家下单加冻结'.strtoupper($coin_name).'，手续费'.$fee,'operator'=>userid(),'ctype'=>2,'action'=>1,'addip'=>get_client_ip()));
        	   if (check_arr($rs)) {
                    $mo->commit();
                    //处理临时单
                    $module = D('Chat');
                    $module->deletetmp(userid(),$orderinfo['userid'],1,$id,$orderinfo['id']);
                    $this->success($sellinfo['enname'],$id);
                }
                else {
                    throw new \Think\Exception('下单失败！');
                }
            }catch(\Think\Exception $e){
                $mo->rollback();
                $this->error('下单失败！',$extra);
            }
		}

		/**************我要出售*******************/

		if($type==0){
        	$orderinfo=M('ad_buy')->where(array('id'=>$tid))->find();
       		if(!$orderinfo){
        		$this->error('此广告不存在',$extra);
        	}
        	//时间函数判断
        	$is_time=opentime($orderinfo['open_time']);
        	if(!$is_time){
        		$this->error('此广告在当前时间未开放',$extra);
        	}
        	//实名认证判断
        	if($orderinfo['safe_option']==1){
        		//购买者需要进行实名认证
        		if($my['is_agree']==0){
        			$this->error('你需要先进行实名认证，再来交易',$extra);
        		}
        	}
        	//是否是我的信任用户
        	if($orderinfo['trust_only']==1){
                $trus=truspingbi($orderinfo['userid']);
                $trus=explode(",",$trus['xinren']);
                if(!in_array(userid(),$trus)){
                    $this->error('你不是对方的信任用户',$extra);
                }
        	}
			$coin_name = M('Coin')->where(array('id'=>$orderinfo['coin']))->getField('name');
			$table = "tw_".$coin_name."_log";
            //手机认证
            $buyinfo=M('user')->where("id=".$orderinfo['userid'])->find();

        	//重新计算一下交易金额
        	
        	//判断交易范围
        	if($tamount<$orderinfo['min_limit']){
        		$this->error('交易金额超出范围',$extra);
        	}
        	if($tamount>$orderinfo['max_limit']){
        		$this->error('交易金额超出范围',$extra);
        	}
            
        	if($mycoin[$coin_name]*1<$num*1){
        		$this->error('您的'.strtoupper($coin_name).'余额不足，请先充值，再进行出售',$extra);
        	}
			
        	//计算交易数量吧
        	//$coin=M('currency')->where(array('id'=>$orderinfo['currency']))->find();
        	//$price=intval($coin['price']*(1+$orderinfo['margin']/100)*100)/100;
			$coin = get_price($orderinfo['coin'],$orderinfo['currency'],1);
			$price=intval($coin*(1+$orderinfo['margin']/100)*100)/100;
			
			$fee = round($num*$orderinfo['fee']/100,8);
        	if(abs($tamount-($baojia*$num))>($tamount*0.001)){
				$this->error('下单失败！',$extra);
			}
        	$arr=array();
        	$arr['buy_id']=$orderinfo['userid'] ;
        	$arr['buy_bid']=$orderinfo['id'] ;
        	$arr['sell_id']=userid() ;
        	$arr['deal_amount']=$tamount ;
        	$arr['deal_num']=$num ;
        	$arr['deal_price']=$baojia ;
        	$arr['deal_ctype']=$orderinfo['currency'] ;
			$arr['deal_coin']=$orderinfo['coin'] ;
        	$arr['ctime']=time() ;
        	$arr['ltime']=$orderinfo['due_time'] ;
        	$arr['order_no']=$this->getorderno(userid(),$orderinfo['userid']);
			$arr['fee'] = $fee;
			if($lmessage!="告诉ta您的要求"){
				$arr['lmessage'] = $lmessage;
			}
            try{
                $mo = M();
                $mo->startTrans();
                $rs[]=$id=$mo->table('tw_order_sell')->add($arr);

                //卖家的btc需要冻结起来
                
                $rs[]=$mo->table('tw_user_coin')->where("userid=".userid())->setDec($coin_name,$num);
				$rs[]=$mo->table($table)->add(array('username'=>$my['username'],'userid'=>$my['id'],'ctime'=>time(),'type'=>2,'plusminus'=>0,'amount'=>$num,'desc'=>'卖家下单减可用'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>1,'action'=>2,'addip'=>get_client_ip()));
                $rs[]=$mo->table('tw_user_coin')->where("userid=".userid())->setInc($coin_name.'d',$num);
				$rs[]=$mo->table($table)->add(array('username'=>$my['username'],'userid'=>$my['id'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$num,'desc'=>'卖家下单加冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>2,'addip'=>get_client_ip()));

               if (check_arr($rs)) {
                    $mo->commit();
                    //处理临时单
                    $module = D('Chat');
                    $module->deletetmp($orderinfo['userid'],userid(),2,$id,$orderinfo['id']);
                    if(!empty($buyinfo['mobile'])){
                        smssend($buyinfo['mobile'],1,3,'',round($num,4),strtoupper($coin_name));
                    }

                    $this->success($buyinfo['enname'],$id);
                }
                else {
                    throw new \Think\Exception('下单失败！');
                }
            }catch(\Think\Exception $e){
                $mo->rollback();
                $this->error('下单失败！'.$e->getMessage(),$extra);
            }
        }

	}
	
	private function getorderno($uid,$orderuid){
		$orderno = time().$uid.$orderuid.rand(1000,9999);
		$order_buy = M('order_buy')->where(array('order_no'=>$orderno))->find();
		$order_sell = M('order_sell')->where(array('order_no'=>$orderno))->find();
		if(!empty($order_buy) || !empty($order_sell)){
			$this->getorderno($uid,$orderuid);
		}else{
			return $orderno;
		}
	}
	
	public function orderinfo($type,$id){
		if(!userid()){
			redirect('/#login');
		}

		$userid = userid();
		
		if(!check($id,'d')){
			$this->error("参数错误！");
		}
		if(!check($type,'d')){
			$this->error("参数错误！");
		}
        if($type!=1 && $type!=2){
        	$this->error("参数错误！".$type);
        }
		$module = D('Chat');
        $list = $module->listbyOrderid($id,$type,0);
		$this->assign('list',$list);
		$this->assign('chatnum',count($list));
		$this->assign('myid',$userid);
		
		$myname = getname($userid);
		$this->assign('myname',$myname);
		
        $buyname='';
        $sellname='';

        //购买的订单 对应发布广告的表
        if($type==1){
        	$orderinfo=M('order_buy')->where("id=".$id." and (buy_id=".$userid." or sell_id=".$userid.")")->find();
        	if(!$orderinfo){
        		$this->error("找不到订单");
        	}
        	$adinfo=M('ad_sell')->where(array('id'=>$orderinfo['sell_sid']))->find();
        }

        if($type==2){
        	$orderinfo=M('order_sell')->where("id=".$id." and (sell_id=".$userid." or buy_id=".$userid.")")->find();
        	if(!$orderinfo){
        		$this->error("找不到订单");
        	}
        	$adinfo=M('ad_buy')->where(array('id'=>$orderinfo['buy_bid']))->find();
        }

        $buyname=getname($orderinfo['buy_id']);
        $sellname=getname($orderinfo['sell_id']);
		if($buyname == $myname){
			$othername = $sellname;
			$otherid = $orderinfo['sell_id'];
		}elseif($sellname == $myname){
			$othername = $buyname;
			$otherid = $orderinfo['buy_id'];
		}
		$this->assign('othername',$othername);
		$this->assign('otherid',$otherid);

        //币种
        //$coin=M('currency')->where(array('id'=>$orderinfo['deal_ctype']))->find();
		$table = M('Coin')->where("id={$orderinfo['deal_coin']}")->getField('name');
		$coin = M($table)->where(array('id'=>$orderinfo['deal_ctype']))->find();
		$coin['coin'] = M('Coin')->where("id={$orderinfo['deal_coin']}")->getField('name');

        //剩余时间
        $sytime=0;
        if($orderinfo['status']==0){
        	if(time()-$orderinfo['ctime']<$orderinfo['ltime']*60){
        		 $sytime=$orderinfo['ltime']-intval((time()-$orderinfo['ctime'])/60);
        		}else{
        			 $sytime=0;
        		}
        }
		$pay_method = explode(",",$adinfo['pay_method']);
        if ($type == 0) {
            $payd_arr=[];
            foreach($pay_method as $pm){
                $pmname = M('pay_method')->where(array('id'=>$pm))->find();
                $payd_arr[] = $pmname['name'];
            }
            $payd = implode(" ",$payd_arr);
        } else {
            $payd = skaccount_get_account($adinfo['skaccount']);
        }


        //生成token
		$mybj_token = set_token('mybj');
		$this->assign('mybj_token',$mybj_token);

		$myxdc_token = set_token('myxdc');
		$this->assign('myxdc_token',$myxdc_token);
		
		$chatorder_token = set_token('chatorder');
		$this->assign('chatorder_token',$chatorder_token);
		
		$ddss_token = set_token('ddss');
		$this->assign('ddss_token',$ddss_token);
		
		$mysfb_token = set_token('mysfb');
		$this->assign('mysfb_token',$mysfb_token);
		
		$pj_token = set_token('pj');
		$this->assign('pj_token',$pj_token);

        $this->assign('type',$type);
        $this->assign('adinfo',$adinfo);
        $this->assign('payd',$payd);
        $this->assign('sytime',$sytime);
        $this->assign('coin',$coin);
        $this->assign('buyname',$buyname);
        $this->assign('sellname',$sellname);
       	$this->assign('orderinfo',$orderinfo);
		
		$truban_token = set_token('truban');
		$this->assign('truban_token',$truban_token);
		$this->assign('iftrust', $this->iftruban($otherid,1));
        $this->display();
	}
	//取消订单
	public function ordercancle_ajax($type,$id,$token){
		$extra = '';
		if(!session('myxdctoken')) {
			set_token('myxdc');
		}
		if(!empty($token)){
			$res = valid_token('myxdc',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('myxdctoken'));
			}
		}else{
			$this->error('缺少参数！',session('myxdctoken'));
		}
		$extra=session('myxdctoken');
		
		if(!userid()){
			$this->error("请先登录！",$extra);
		}
		
		if(!check($id,'d')){
			$this->error("参数错误！",$extra);
		}

		if($type!=1 && $type!=2){
			$this->error('参数错误！',$extra);
		}
		$id=intval($id*1);
		if($type==1){
			//购买的交易单
			$orderinfo=M('order_buy')->where(array('id'=>$id,'buy_id'=>userid()))->find();
			$seller = M('User')->where(array('id'=>$orderinfo['sell_id']))->find();
			if(!$orderinfo){
        		$this->error('订单不存在',$extra);
        	}
        	if($orderinfo['status']==5){
        		$this->error('你已经取消了订单',$extra);
        	}
        	if($orderinfo['status']>0){
        		$this->error('交易正在进行中，无法取消，有问题请申诉',$extra);
        	}
			$coin_name = M('Coin')->where(array('id'=>$orderinfo['deal_coin']))->getField('name');
			$table = "tw_".$coin_name."_log";
            try{
                $mo = M();
                $mo->startTrans();
        	    $rs[]=$mo->table('tw_order_buy')->where(array('id'=>$id,'buy_id'=>userid()))->save(array('status'=>5));
				$real_number = $orderinfo['deal_num'] + $orderinfo['fee'];
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$orderinfo['sell_id']))->setDec($coin_name.'d', $real_number);
				$rs[]=$mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$orderinfo['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>0,'amount'=>$real_number,'desc'=>'买家取消订单减冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>3,'addip'=>get_client_ip()));
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$orderinfo['sell_id']))->setInc($coin_name, $real_number);
				$rs[]=$mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$orderinfo['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$real_number,'desc'=>'买家取消订单加可用'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>1,'action'=>3,'addip'=>get_client_ip()));
        	    if(check_arr($rs)) {
                    $mo->commit();
                    $this->success($seller['enname'],$extra);
                }
                else {
                    throw new \Think\Exception('取消失败！');
                }
            }catch(\Think\Exception $e){
                $mo->rollback();
                $this->error('取消失败！',$extra);
            }

		}
		
		if($type==2){
			//出售交易订单
			$orderinfo=M('order_sell')->where(array('id'=>$id,'buy_id'=>userid()))->find();
			$seller = M('User')->where(array('id'=>$orderinfo['sell_id']))->find();
			if(!$orderinfo){
        		$this->error('订单不存在！',$extra);
        	}
        	if($orderinfo['status']==5){
        		$this->error('你已经取消了订单',$extra);
        	}
        	if($orderinfo['status']>0){
        		$this->error('交易正在进行中，无法取消，有问题请申诉',$extra);
        	}
			$coin_name = M('Coin')->where(array('id'=>$orderinfo['deal_coin']))->getField('name');
			$table = "tw_".$coin_name."_log";
            try{
                $mo = M();
                $mo->startTrans();
        	    $rs[]=$mo->table('tw_order_sell')->where(array('id'=>$id,'buy_id'=>userid()))->save(array('status'=>5));
                //冻结中减去
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$orderinfo['sell_id']))->setDec($coin_name.'d', $orderinfo['deal_num']);
				$rs[]=$mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$orderinfo['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>0,'amount'=>$orderinfo['deal_num'],'desc'=>'买家取消订单减冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>3,'addip'=>get_client_ip()));
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$orderinfo['sell_id']))->setInc($coin_name, $orderinfo['deal_num']);
				$rs[]=$mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$orderinfo['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$orderinfo['deal_num'],'desc'=>'买家取消订单加可用'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>1,'action'=>3,'addip'=>get_client_ip()));
                if(check_arr($rs)) {
                    $mo->commit();
                    $this->success($seller['enname'],$extra);
                }
                else {
                    throw new \Think\Exception('取消失败！');
                }
            }catch(\Think\Exception $e){
                $mo->rollback();
                $this->error('取消失败！',$extra);
            }
        	
		}
		
	}
    //买家标记完之后买家取消订单
    public function orderbjcancle_ajax($type,$id,$token){
        $extra = '';
		if(!session('myxdctoken')) {
            set_token('myxdc');
        }
        if(!empty($token)){
            $res = valid_token('myxdc',$token);
            if(!$res){
                $this->error('请不要频繁提交！',session('myxdctoken'));
            }
        }else{
			$this->error('缺少参数！',session('myxdctoken'));
		}
        $extra=session('myxdctoken');
		
        if(!userid()){
            $this->error("请先登录！",$extra);
        }

		if(!check($id,'d')){
			$this->error("参数错误！",$extra);
		}

        if($type!=1 && $type!=2){
            $this->error('参数错误！',$extra);
        }
        $id=intval($id*1);
        if($type==1){
            //购买的交易单
            $orderinfo=M('order_buy')->where(array('id'=>$id,'buy_id'=>userid()))->find();
            $seller = M('User')->where(array('id'=>$orderinfo['sell_id']))->find();
            if(!$orderinfo){
                $this->error('订单不存在',$extra);
            }
            if($orderinfo['status']==5){
                $this->error('你已经取消了订单',$extra);
            }
            if($orderinfo['status']==6){
                $this->error('交易处于申诉状态',$extra);
            }
            if($orderinfo['status']>=3){
                $this->error('卖家已放行，无法取消',$extra);
            }
            $coin_name = M('Coin')->where(array('id'=>$orderinfo['deal_coin']))->getField('name');
            $table = "tw_".$coin_name."_log";
            try{
                $mo = M();
                $mo->startTrans();

                $rs[]=$mo->table('tw_order_buy')->where(array('id'=>$id,'buy_id'=>userid()))->save(array('status'=>5));
                $real_number = $orderinfo['deal_num'] + $orderinfo['fee'];

                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$orderinfo['sell_id']))->setDec($coin_name.'d', $real_number);
                $rs[]=$mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$orderinfo['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>0,'amount'=>$real_number,'desc'=>'买家取消订单减冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>3,'addip'=>get_client_ip()));
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$orderinfo['sell_id']))->setInc($coin_name, $real_number);
                $rs[]=$mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$orderinfo['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$real_number,'desc'=>'买家取消订单加可用'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>1,'action'=>3,'addip'=>get_client_ip()));
                if(check_arr($rs)) {
                    $mo->commit();
                    $this->success('取消成功！',$extra);
                }
                else {
                    throw new \Think\Exception('取消失败！');
                }
            }catch(\Think\Exception $e){
                $mo->rollback();
                $this->error('取消失败！',$extra);
            }

        }
        
        if($type==2){
            //出售交易订单
            $orderinfo=M('order_sell')->where(array('id'=>$id,'buy_id'=>userid()))->find();
            $seller = M('User')->where(array('id'=>$orderinfo['sell_id']))->find();
            if(!$orderinfo){
                $this->error('订单不存在！',$extra);
            }
            if($orderinfo['status']==5){
                $this->error('你已经取消了订单',$extra);
            }
            if($orderinfo['status']==6){
                $this->error('交易处于申诉状态',$extra);
            }
            if($orderinfo['status']>=3){
                $this->error('卖家已放行，无法取消',$extra);
            }
            $coin_name = M('Coin')->where(array('id'=>$orderinfo['deal_coin']))->getField('name');
            $table = "tw_".$coin_name."_log";
            try{
                $mo = M();
                $mo->startTrans();
                $rs[]=$mo->table('tw_order_sell')->where(array('id'=>$id,'buy_id'=>userid()))->save(array('status'=>5));
                //冻结中减去
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$orderinfo['sell_id']))->setDec($coin_name.'d', $orderinfo['deal_num']);
                $rs[]=$mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$orderinfo['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>0,'amount'=>$orderinfo['deal_num'],'desc'=>'买家取消订单减冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>3,'addip'=>get_client_ip()));
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$orderinfo['sell_id']))->setInc($coin_name, $orderinfo['deal_num']);
                $rs[]=$mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$orderinfo['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$orderinfo['deal_num'],'desc'=>'买家取消订单加可用'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>1,'action'=>3,'addip'=>get_client_ip()));
                if(check_arr($rs)) {
                    $mo->commit();
                    $this->success('取消成功！',$extra);
                }
                else {
                    throw new \Think\Exception('取消失败！');
                }
            }catch(\Think\Exception $e){
                $mo->rollback();
                $this->error('取消失败！',$extra);
            }
            
        }
        
    }
	//标记已付款
	public function uptrade_ajax($type,$id,$token){
		if(!session('mybjtoken')) {
			set_token('mybj');
		}
		if(!empty($token)){
			$res = valid_token('mybj',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('mybjtoken'));
			}
		}else{
			$this->error('缺少参数！',session('mybjtoken'));
		}
		$extra=session('mybjtoken');
		
		if(!userid()){
			$this->error("请先登录！",$extra);
		}

		if(!check($id,'d')){
			$this->error("参数错误！",$extra);
		}

		if($type!=1 && $type!=2){
			$this->error('提交方式有误',$extra);
		}
		//买家用户信息
		$buyinfo = M('User')->where(array('id'=>userid()))->find();

		$id=intval($id*1);
		if($type==1){
			//购买的交易单
			$orderinfo=M('order_buy')->where(array('id'=>$id,'buy_id'=>userid()))->find();
			if(!$orderinfo){
        		$this->error('交易单子不存在',$extra);
        	}
        	if($orderinfo['status']==5){
        		$this->error('此订单已取消',$extra);
        	}
        	if($orderinfo['status']>=1){
        		$this->error('你已经标记了已付款完成',$extra);
        	}

        	$coinname=getcoinname($orderinfo['deal_coin']);

        	$rs=M('order_buy')->where(array('id'=>$id,'buy_id'=>userid()))->save(array('status'=>1,'dktime'=>time()));
        	if($rs<=0){
        		$this->error('标记失败',$extra);
        	}else{
        		$sellinfo = M('User')->where(array('id'=>$orderinfo['sell_id']))->find();
				if(!empty($sellinfo['mobile'])){
					smssend($sellinfo['mobile'],1,4,$buyinfo['enname'],round($orderinfo['deal_num'],4),$coinname);
				}
        		$this->success($sellinfo['enname']);
        	}
		}
		if($type==2){
			$orderinfo=M('order_sell')->where(array('id'=>$id,'buy_id'=>userid()))->find();
			if(!$orderinfo){
        		$this->error('交易单子不存在',$extra);
        	}
        	if($orderinfo['status']==5){
        		$this->error('此订单已取消',$extra);
        	}
        	if($orderinfo['status']>=1){
        		$this->error('你已经标记了已付款完成',$extra);
        	}
        	//获取交易币种
        	$coinname=getcoinname($orderinfo['deal_coin']);

        	$rs=M('order_sell')->where(array('id'=>$id,'buy_id'=>userid()))->save(array('status'=>1,'dktime'=>time()));
        	if($rs<=0){
        		$this->error('标记失败',$extra);
        	}else{
				$sellinfo = M('User')->where(array('id'=>$orderinfo['sell_id']))->find();
				if(!empty($sellinfo['mobile'])){
					smssend($sellinfo['mobile'],1,4,$buyinfo['enname'],round($orderinfo['deal_num'],4),$coinname);
				}
        		$this->success($sellinfo['enname']);
        	}
		}
	}
    //重启交易
    public function chongqi_ajax($type,$id,$token){
		$extra = '';

        if(!session('mybjtoken')) {
            set_token('mybj');
        }
        if(!empty($token)){
            $res = valid_token('mybj',$token);
            if(!$res){
                $this->error('请不要频繁提交！',session('mybjtoken'));
            }
        }else{
			$this->error('缺少参数！',session('mybjtoken'));
		}
        $extra=session('mybjtoken');
		
		if(!userid()){
            $this->error("请先登录，再来操作",$extra);
        }

        if($type!=1 && $type!=2){
            $this->error('提交方式有误',$extra);
        }
		$my = M('User')->where(array('id'=>userid()))->find();
        $mycoin=M("user_coin")->where("userid=".userid())->find();

		if(!check($id,'d')){
			$this->error("参数错误！",$extra);
		}
		
        $id=intval($id*1);

        if($type==1){
            //购买的交易单
            $orderinfo=M('order_buy')->where(array('id'=>$id,'sell_id'=>userid()))->find();
            if(!$orderinfo){
                $this->error('订单不存在',$extra);
            }
            if($orderinfo['status']!=5){
                $this->error('订单不是取消的状态',$extra);
            }
			$coin_name = M('Coin')->where(array('id'=>$orderinfo['deal_coin']))->getField('name');
			$table = "tw_".$coin_name."_log";
            //重启交易需要冻结我的比特币 然后修改订单的创建的时间
			$fee = $orderinfo['fee'];
			$realnum = $orderinfo['deal_num']+$fee;
            if($mycoin[$coin_name]*1<$realnum*1){
                $this->error('你的'.strtoupper($coin_name).'余额不足，无法重启',$extra);
            }
			
            try{
                $mo = M();
                $mo->startTrans();
                $rs[]=$mo->table('tw_order_buy')->where(array('id'=>$id))->save(array('ctime'=>time(),'status'=>0));
                //冻结中减去
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>userid()))->setDec($coin_name, $realnum);
				$rs[]=$mo->table($table)->add(array('username'=>$my['username'],'userid'=>userid(),'ctime'=>time(),'type'=>1,'plusminus'=>0,'amount'=>$realnum,'desc'=>'卖家重启交易减可用'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>1,'action'=>4,'addip'=>get_client_ip()));
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>userid()))->setInc($coin_name.'d', $realnum);
				$rs[]=$mo->table($table)->add(array('username'=>$my['username'],'userid'=>userid(),'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$realnum,'desc'=>'卖家重启交易加冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>4,'addip'=>get_client_ip()));
                if(check_arr($rs)) {
                    $mo->commit();
                    $this->success('重启成功！',$extra);
                }
                else {
                    throw new \Think\Exception('重启失败！');
                }
            }catch(\Think\Exception $e){
                $mo->rollback();
                $this->error('重启失败！',$extra);
            }
        }
        if($type==2){
            $orderinfo=M('order_sell')->where(array('id'=>$id,'sell_id'=>userid()))->find();
            if(!$orderinfo){
                $this->error('订单不存在',$extra);
            }
            if($orderinfo['status']!=5){
                $this->error('交易订单不是取消的状态',$extra);
            }
			$coin_name = M('Coin')->where(array('id'=>$orderinfo['deal_coin']))->getField('name');
			$table = "tw_".$coin_name."_log";
            //重启交易需要冻结我的比特币 然后修改订单的创建的时间
            if($mycoin[$coin_name]*1<$orderinfo['deal_num']*1){
                $this->error('你的'.strtoupper($coin_name).'余额不足，无法重启',$extra);
            }

            try{
                $mo = M();
                $mo->startTrans();
                $rs[]=$mo->table('tw_order_sell')->where(array('id'=>$id))->save(array('ctime'=>time(),'status'=>0));
                //冻结中减去
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>userid()))->setDec($coin_name, $orderinfo['deal_num']);
				$rs[]=$mo->table($table)->add(array('username'=>$my['username'],'userid'=>userid(),'ctime'=>time(),'type'=>2,'plusminus'=>0,'amount'=>$orderinfo['deal_num'],'desc'=>'卖家重启交易减可用'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>1,'action'=>4,'addip'=>get_client_ip()));
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>userid()))->setInc($coin_name.'d', $orderinfo['deal_num']);
				$rs[]=$mo->table($table)->add(array('username'=>$my['username'],'userid'=>userid(),'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$orderinfo['deal_num'],'desc'=>'卖家重启交易加冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>4,'addip'=>get_client_ip()));
                if(check_arr($rs)) {
                    $mo->commit();
                    $this->success('重启成功！',$extra);
                }
                else {
                    throw new \Think\Exception('重启失败！');
                }
            }catch(\Think\Exception $e){
                $mo->rollback();
                $this->error('重启失败！',$extra);
            }
        }
    }
	//卖家释放币
	public function sfbtc_ajax($type,$id,$token,$paypassword){
		$extra = '';
		if(!session('mysfbtoken')) {
			set_token('mysfb');
		}
		if(!empty($token)){
			$res = valid_token('mysfb',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('mysfbtoken'));
			}
		}else{
			$this->error('缺少参数！',session('mysfbtoken'));
		}
		$extra=session('mysfbtoken');
		
		if(!userid()){
			$this->error("请先登录",$extra);
		}

        if (!check($paypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！',$extra);
        }
        $user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

        if (md5($paypassword) != $user_paypassword) {
            $this->error('交易密码错误！',$extra);
        }
		if($type!=1 && $type!=2){
			$this->error('参数错误！',$extra);
		}
		$my=M('User')->where(array('id'=>userid()))->find();
		$mycoin=M("user_coin")->where("userid=".userid())->find();
		if(!check($id,'d')){
			$this->error("参数错误！",$extra);
		}
		$id=intval($id*1);
		//系统设置的发放提成月数
		$configset = M('Config')->where(array('id'=>1))->find();
		$invit_buy = $configset['huafei_text_index'];
		if($type==1){
			//我是发布的卖单  我释放给买家
			$orderinfo=M('order_buy')->where(array('id'=>$id,'sell_id'=>userid()))->find();
			$buyer=M('User')->where(array('id'=>$orderinfo['buy_id']))->find();
			if(!$orderinfo){
        		$this->error('订单不存在',$extra);
        	}
        	if($orderinfo['status']==5){
        		$this->error('订单已经被取消',$extra);
        	}
            if($orderinfo['status']==6){
                $this->error('订单申诉中，无法释放',$extra);
            }
        	if($orderinfo['status']==0){
        		$this->error('此订单对方已经拍下还未付款',$extra);
        	}
        	if($orderinfo['status']>=3){
        		$this->error('此订单已经释放无需再次释放',$extra);
        	}
			$coin_name = M('Coin')->where(array('id'=>$orderinfo['deal_coin']))->getField('name');
			$table = "tw_".$coin_name."_log";
        	//释放比特币给对方 并且更改状态
        	$seller_down_number = ($orderinfo['deal_num']+$orderinfo['fee'])*1;
        	$standard = $mycoin[$coin_name.'d']*1-$seller_down_number;
			if(round($standard,8)<0){
				$this->error('您的冻结'.strtoupper($coin_name).'余额不足'.$standard,$extra);
        	}

        	try{
				$mo = M();
				$mo->startTrans();
				$rs = array();
			
				//我减冻结里减去 对方加
				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec($coin_name.'d', $seller_down_number);
				$rs[]=$mo->table($table)->add(array('username'=>$my['username'],'userid'=>userid(),'ctime'=>time(),'type'=>1,'plusminus'=>0,'amount'=>$seller_down_number,'desc'=>'卖家释放币减冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>5,'addip'=>get_client_ip()));
				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $orderinfo['buy_id']))->setInc($coin_name, $orderinfo['deal_num']);
				$rs[]=$mo->table($table)->add(array('username'=>$buyer['username'],'userid'=>$orderinfo['buy_id'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$orderinfo['deal_num'],'desc'=>'卖家释放币加可用'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>1,'action'=>5,'addip'=>get_client_ip()));
        		$rs[]=$mo->table('tw_order_buy') ->where(array('id'=>$id,'sell_id'=>userid()))->save(array('status'=>3,'finished_time'=>time()));
				//如果订单申请了管理员介入，把状态去掉
				if($orderinfo['adminhandle']==1){
					$rs[] = $mo->table('tw_order_buy')->where(array('id'=>$id,'sell_id'=>userid()))->save(array('adminhandle'=>0));
				}
                //交易时间设置                
				$buyer_ftt = $mo->table('tw_user')->where("id=".$orderinfo['buy_id'])->getField('first_trade_time');
				if(empty($buyer_ftt)){
					$rs[] = $mo->table('tw_user')->where("id=".$orderinfo['buy_id'])->save(array('first_trade_time'=>time()));
				}
                    
                $seller_ftt = $mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->getField('first_trade_time');
                if(empty($seller_ftt)){
                    $rs[] = $mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->save(array('first_trade_time'=>time()));
                }
                //记录一下卖家放行的时间
                if(!empty($orderinfo['dktime'])){
                    $usetime=intval((time()-$orderinfo['dktime'])/60);
					if($usetime>0){
						$rs[] = $mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->setInc('sftime_sum',$usetime);
					}
                }
                //交易次数加1
                $rs[]=$mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->setInc('transact',1);
                $rs[]=$mo->table('tw_user')->where("id=".$orderinfo['buy_id'])->setInc('transact',1);

                //s双方交易次数
				$buyer_trade_id=$mo->table('tw_user')->where(array('id'=>$orderinfo['buy_id']))->getField('trade_id');
				$new_bti = $buyer_trade_id.','.$orderinfo['sell_id'];
				$rs[] = $mo->table('tw_user')->where(array('id'=>$orderinfo['buy_id']))->setField('trade_id',$new_bti);
				$seller_trade_id=$mo->table('tw_user')->where(array('id'=>$orderinfo['sell_id']))->getField('trade_id');
				$new_sti = $seller_trade_id.','.$orderinfo['buy_id'];
				$rs[] = $mo->table('tw_user')->where(array('id'=>$orderinfo['sell_id']))->setField('trade_id',$new_sti);
				
				//发放交易手续费提成
				if (!empty($invit_buy)) {
					
					$coin_info = $mo->table('tw_coin')->where(array('id'=>$orderinfo['deal_coin']))->find();
					
					$invit_1 = $coin_info['fee_bili'];

					$invit_2 = $coin_info['fee_meitian'];

					$invit_3 = $coin_info['js_lt'];

					if ($invit_1) {

						if ($orderinfo['fee']) {

							if ($my['invit_1']) {
								$my_invit1_user = $mo->table('tw_user')->where(array('id'=>$my['invit_1']))->find();
								if(time()-$my_invit1_user['addtime']<=$invit_buy*30*24*3600){
									$invit_buy_save_1 = round(($orderinfo['fee'] / 100) * $invit_1, 8);
									if ($invit_buy_save_1) {
										$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $my['invit_1']))->setInc($coin_info['name'], $invit_buy_save_1);
										$rs[]=$mo->table($table)->add(array('username'=>$my_invit1_user['username'],'userid'=>$my['invit_1'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$invit_buy_save_1,'desc'=>'卖广告交易完成发给卖家一级上线佣金','operator'=>userid(),'ctype'=>1,'action'=>9,'addip'=>get_client_ip()));
										$rs[] = $mo->table('tw_invit')->add(array('userid' => $my['invit_1'], 'invit' => userid(), 'name' => 1, 'type' => $coin_info['id'], 'num' => $orderinfo['deal_num'], 'mum' => $orderinfo['deal_num'], 'fee' => $invit_buy_save_1, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'orderno'=>$orderinfo['order_no']));

									}
								}
							}

							if ($my['invit_2']) {
								$my_invit2_user = $mo->table('tw_user')->where(array('id'=>$my['invit_2']))->find();
								if(time()-$my_invit2_user['addtime']<=$invit_buy*30*24*3600){
									$invit_buy_save_2 = round(($orderinfo['fee'] / 100) * $invit_2, 8);
									if ($invit_buy_save_2) {
										$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $my['invit_2']))->setInc($coin_info['name'], $invit_buy_save_2);
										$rs[]=$mo->table($table)->add(array('username'=>$my_invit2_user['username'],'userid'=>$my['invit_2'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$invit_buy_save_2,'desc'=>'卖广告交易完成发给卖家二级上线佣金','operator'=>userid(),'ctype'=>1,'action'=>9,'addip'=>get_client_ip()));
										$rs[] = $mo->table('tw_invit')->add(array('userid' => $my['invit_2'], 'invit' => userid(), 'name' => 2, 'type' => $coin_info['id'], 'num' => $orderinfo['deal_num'], 'mum' => $orderinfo['deal_num'], 'fee' => $invit_buy_save_2, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'orderno'=>$orderinfo['order_no']));
									}
								}
							}

							if ($my['invit_3']) {
								$my_invit3_user = $mo->table('tw_user')->where(array('id'=>$my['invit_3']))->find();
								if(time()-$my_invit3_user['addtime']<=$invit_buy*30*24*3600){
									$invit_buy_save_3 = round(($orderinfo['fee'] / 100) * $invit_3, 8);
									if ($invit_buy_save_3) {
										$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $my['invit_3']))->setInc($coin_info['name'], $invit_buy_save_3);
										$rs[]=$mo->table($table)->add(array('username'=>$my_invit3_user['username'],'userid'=>$my['invit_3'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$invit_buy_save_3,'desc'=>'卖广告交易完成发给卖家三级上线佣金','operator'=>userid(),'ctype'=>1,'action'=>9,'addip'=>get_client_ip()));
										$rs[] = $mo->table('tw_invit')->add(array('userid' => $my['invit_3'], 'invit' => userid(), 'name' => 3, 'type' => $coin_info['id'], 'num' => $orderinfo['deal_num'], 'mum' => $orderinfo['deal_num'], 'fee' => $invit_buy_save_3, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'orderno'=>$orderinfo['order_no']));

									}
								}
							}

						}

					}

				}
               
        		if (check_arr($rs)) {
					$mo->commit();
					if(!empty($buyer['mobile'])){
                        smssend($buyer['mobile'],1,5,$my['enname'],round($orderinfo['deal_num'],4),strtoupper($coin_name));
                    }
					$this->success($buyer['enname'],$extra);
				}
				else {
					throw new \Think\Exception('放行失败！');
				}
			}catch(\Think\Exception $e){
				$mo->rollback();
				$this->error('放行失败！'.$e->getMessage(),$extra);
			}
		}
		
		if($type==2){
			//我卖出，对方发布的买单
			$orderinfo=M('order_sell')->where(array('id'=>$id,'sell_id'=>userid()))->find();
			$buyer=M('User')->where(array('id'=>$orderinfo['buy_id']))->find();
			if(!$orderinfo){
        		$this->error('订单不存在',$extra);
        	}
        	if($orderinfo['status']==5){
        		$this->error('订单已经被取消了',$extra);
        	}
            if($orderinfo['status']==6){
                $this->error('订单申诉中，无法释放',$extra);
            }
        	if($orderinfo['status']==0){
        		$this->error('此订单对方已经拍下还未付款',$extra);
        	}
        	if($orderinfo['status']>=3){
        		$this->error('此订单已经释放无需再次释放',$extra);
        	}
			$coin_name = M('Coin')->where(array('id'=>$orderinfo['deal_coin']))->getField('name');
			$table = "tw_".$coin_name."_log";
			$standard = $mycoin[$coin_name.'d']*1-$orderinfo['deal_num']*1;
        	if(round($standard,8)<0){
				$this->error('您的冻结'.strtoupper($coin_name).'余额不足',$extra);
        	}
			$buyer_up_number = $orderinfo['deal_num']-$orderinfo['fee'];
        	try{
				$mo = M();
				$mo->startTrans();
				$rs = array();
				//我减d冻结里 对方加
				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => userid()))->setDec($coin_name.'d', $orderinfo['deal_num']);
				$rs[]=$mo->table($table)->add(array('username'=>$my['username'],'userid'=>userid(),'ctime'=>time(),'type'=>2,'plusminus'=>0,'amount'=>$orderinfo['deal_num'],'desc'=>'卖家释放币减冻结'.strtoupper($coin_name),'operator'=>userid(),'ctype'=>2,'action'=>5,'addip'=>get_client_ip()));
				$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $orderinfo['buy_id']))->setInc($coin_name, $buyer_up_number);
				$rs[]=$mo->table($table)->add(array('username'=>$buyer['username'],'userid'=>$orderinfo['buy_id'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$buyer_up_number,'desc'=>'卖家释放币加可用'.strtoupper($coin_name).'，手续费'.$orderinfo['fee']*1,'operator'=>userid(),'ctype'=>1,'action'=>5,'addip'=>get_client_ip()));
        		$rs[]=$mo->table('tw_order_sell') ->where(array('id'=>$id,'sell_id'=>userid()))->save(array('status'=>3,'finished_time'=>time()));
				if($orderinfo['adminhandle']==1){
					$rs[] = $mo->table('tw_order_sell')->where(array('id'=>$id,'sell_id'=>userid()))->save(array('adminhandle'=>0));
				}
                //记录第一次交易时间
				$buyer_ftt = $mo->table('tw_user')->where(array('id'=>$orderinfo['buy_id']))->getField('first_trade_time');
                if(empty($buyer_ftt)){
                    $rs[] = $mo->table('tw_user')->where("id=".$orderinfo['buy_id'])->save(array('first_trade_time'=>time()));
                }
				$seller_ftt = $mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->getField('first_trade_time');
                if(empty($seller_ftt)){
                    $rs[] = $mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->save(array('first_trade_time'=>time()));
                }

				//交易次数加1
                $rs[]=$mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->setInc('transact',1);
                $rs[]=$mo->table('tw_user')->where("id=".$orderinfo['buy_id'])->setInc('transact',1);
				
                //s双方交易次数
				$buyer_trade_id = $mo->table('tw_user')->where(array('id'=>$orderinfo['buy_id']))->getField('trade_id');
				$new_bti = $buyer_trade_id.','.$orderinfo['sell_id'];
				$rs[] = $mo->table('tw_user')->where(array('id'=>$orderinfo['buy_id']))->setField('trade_id',$new_bti);
				$seller_trade_id = $mo->table('tw_user')->where(array('id'=>$orderinfo['sell_id']))->getField('trade_id');
				$new_sti = $seller_trade_id.','.$orderinfo['buy_id'];
				$rs[] = $mo->table('tw_user')->where(array('id'=>$orderinfo['sell_id']))->setField('trade_id',$new_sti);
				
				//发放交易手续费提成
				if (!empty($invit_buy)) {
					
					$coin_info = $mo->table('tw_coin')->where(array('id'=>$orderinfo['deal_coin']))->find();
					
					$invit_1 = $coin_info['fee_bili'];

					$invit_2 = $coin_info['fee_meitian'];

					$invit_3 = $coin_info['js_lt'];

					if ($invit_1) {

						if ($orderinfo['fee']) {

							if ($buyer['invit_1']) {
								$buyer_invit1_user = $mo->table('tw_user')->where(array('id'=>$buyer['invit_1']))->find();
								if(time()-$buyer_invit1_user['addtime']<=$invit_buy*30*24*3600){
									$invit_buy_save_1 = round(($orderinfo['fee'] / 100) * $invit_1, 8);
									if ($invit_buy_save_1) {
										$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buyer['invit_1']))->setInc($coin_info['name'], $invit_buy_save_1);
										$rs[]=$mo->table($table)->add(array('username'=>$buyer_invit1_user['username'],'userid'=>$buyer['invit_1'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$invit_buy_save_1,'desc'=>'买广告交易完成发给买家一级上线佣金','operator'=>userid(),'ctype'=>1,'action'=>10,'addip'=>get_client_ip()));
										$rs[] = $mo->table('tw_invit')->add(array('userid' => $buyer['invit_1'], 'invit' => $buyer['id'], 'name' => 1, 'type' => $coin_info['id'], 'num' => $orderinfo['deal_num'], 'mum' => $orderinfo['deal_num'], 'fee' => $invit_buy_save_1, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'orderno'=>$orderinfo['order_no']));
									}
								}
							}

							if ($buyer['invit_2']) {
								$buyer_invit2_user = $mo->table('tw_user')->where(array('id'=>$buyer['invit_2']))->find();
								if(time()-$buyer_invit2_user['addtime']<=$invit_buy*30*24*3600){
									$invit_buy_save_2 = round(($orderinfo['fee'] / 100) * $invit_2, 8);
									if ($invit_buy_save_2) {
										$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buyer['invit_2']))->setInc($coin_info['name'], $invit_buy_save_2);
										$rs[]=$mo->table($table)->add(array('username'=>$buyer_invit2_user['username'],'userid'=>$buyer['invit_2'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$invit_buy_save_2,'desc'=>'买广告交易完成发给买家二级上线佣金','operator'=>userid(),'ctype'=>1,'action'=>10,'addip'=>get_client_ip()));
										$rs[] = $mo->table('tw_invit')->add(array('userid' => $buyer['invit_2'], 'invit' => $buyer['id'], 'name' => 2, 'type' => $coin_info['id'], 'num' => $orderinfo['deal_num'], 'mum' => $orderinfo['deal_num'], 'fee' => $invit_buy_save_2, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'orderno'=>$orderinfo['order_no']));
									}
								}
							}

							if ($buyer['invit_3']) {
								$buyer_invit3_user = $mo->table('tw_user')->where(array('id'=>$buyer['invit_3']))->find();
								if(time()-$buyer_invit3_user['addtime']<=$invit_buy*30*24*3600){
									$invit_buy_save_3 = round(($orderinfo['fee'] / 100) * $invit_3, 8);
									if ($invit_buy_save_3) {
										$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buyer['invit_3']))->setInc($coin_info['name'], $invit_buy_save_3);
										$rs[]=$mo->table($table)->add(array('username'=>$buyer_invit3_user['username'],'userid'=>$buyer['invit_3'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$invit_buy_save_3,'desc'=>'买广告交易完成发给买家三级上线佣金','operator'=>userid(),'ctype'=>1,'action'=>10,'addip'=>get_client_ip()));
										$rs[] = $mo->table('tw_invit')->add(array('userid' => $buyer['invit_3'], 'invit' => $buyer['id'], 'name' => 3, 'type' => $coin_info['id'], 'num' => $orderinfo['deal_num'], 'mum' => $orderinfo['deal_num'], 'fee' => $invit_buy_save_3, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'orderno'=>$orderinfo['order_no']));
									}
								}
							}

						}

					}

				}

        		if (check_arr($rs)) {
					$mo->commit();
					if(!empty($buyer['mobile'])){
                        smssend($buyer['mobile'],1,5,$my['enname'],round($orderinfo['deal_num'],4),strtoupper($coin_name));
                    }
					$this->success($buyer['enname'],$extra);
				}
				else {
					throw new \Think\Exception('放行失败！');
				}
			}catch(\Think\Exception $e){
				$mo->rollback();
				$this->error('放行失败！'.$e->getMessage(),$extra);
			}
		}
	}
	//评价
	public function comment_ajax($type,$id,$pj,$token){
		$extra = '';
		if(!userid()){
			$this->error("请先登录！",$extra);
		}
		if(!session('pjtoken')) {
			set_token('pj');
		}
		if(!empty($token)){
			$res = valid_token('pj',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('pjtoken'));
			}
		}else{
			$this->error('缺少参数！',session('pjtoken'));
		}
		$extra=session('pjtoken');
		
		if($type!=1 && $type!=2){
			$this->error('参数错误！',$extra);
		}
		if($pj!=1 && $pj!=2 && $pj!=3){
			$this->error('评价方式请选择中评 好评 差评',$extra);
		}
		if(!check($id,'d')){
			$this->error("参数错误！",$extra);
		}
		$id=intval($id*1);
		try{
			$mo=M();
			$mo->startTrans();
			if($type==1){
				$orderinfo=$mo->table('tw_order_buy')->where("id=".$id." and (buy_id=".userid()." or sell_id=".userid().")")->find();
			}else{
				$orderinfo=$mo->table('tw_order_sell')->where("id=".$id." and (buy_id=".userid()." or sell_id=".userid().")")->find();
			}		
			if(!$orderinfo){
				$this->error('订单不存在',$extra);
			}
			if($orderinfo['status']==5){
				$this->error('该订单已经被取消',$extra);
			}
			if($orderinfo['status']==0){
				$this->error('该订单已经被拍下，还未付款',$extra);
			}
			if($orderinfo['status']==1){
				$this->error('该订单已经付款，还未释放币',$extra);
			}

			if($orderinfo['sell_id']==userid()){
				if($orderinfo['sell_pj']>0){
					$this->error('你已经发布了评价');
				}
				//卖家给出评价
				if($type==1){
					$rs[]=$mo->table('tw_order_buy')->where(array('id'=>$id))->save(array('sell_pj'=>$pj));
				}else{
					$rs[]=$mo->table('tw_order_sell')->where(array('id'=>$id))->save(array('sell_pj'=>$pj));
				}
				//买家好评次数加1
				if($pj==1){
					$rs[]=$mo->table('tw_user')->where("id=".$orderinfo['buy_id'])->setInc('goodnum',1);
				}
				$user=$mo->table("tw_user")->where("id=".$orderinfo['buy_id'])->find();
				$hpv=intval($user['goodnum']/$user['transact']*100);
				if($user['goodcomm']!=$hpv){
					$rs[] = $mo->table('tw_user')->where("id=".$orderinfo['buy_id'])->setField('goodcomm',$hpv);
				}
			}else{
				if($orderinfo['buy_pj']>0){
					$this->error('你已经发布了评价',$extra);
				}
				if($type==1){
					$rs[]=$mo->table('tw_order_buy')->where(array('id'=>$id))->save(array('buy_pj'=>$pj));
				}else{
					$rs[]=$mo->table('tw_order_sell')->where(array('id'=>$id))->save(array('buy_pj'=>$pj));
				}
				if($pj==1){
					$rs[]=$mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->setInc('goodnum',1);
				}
				$user=$mo->table("tw_user")->where("id=".$orderinfo['sell_id'])->find();
				$hpv=intval($user['goodnum']/$user['transact']*100);
				if($user['goodcomm'] != $hpv){
					$rs[] = $mo->table('tw_user')->where("id=".$orderinfo['sell_id'])->setField('goodcomm',$hpv);
				}
			}
			//查看一下状态
			$module = D('Chat');
			if($type==1){
				$order=$mo->table('tw_order_buy')->where(array('id'=>$id))->find();
				if($order['buy_pj']>0 && $order['sell_pj']>0){
					$rs[]=$mo->table('tw_order_buy')->where(array('id'=>$id))->save(array('status'=>4));
					$condition=array();
					$condition['orderid'] = $order['id'];
					$condition['ordertype'] = 1;
					$condition['status'] = 0;
					$condition['isfinished'] = 0;
					$update = array();
					$update['isfinished'] = 1;
					$module->updateChat($condition,$update);
				}
			}else{
				$order=$mo->table('tw_order_sell')->where(array('id'=>$id))->find();
				if($order['buy_pj']>0 && $order['sell_pj']>0){
					$rs[]=$mo->table('tw_order_sell')->where(array('id'=>$id))->save(array('status'=>4));
					$condition=array();
					$condition['orderid'] = $order['id'];
					$condition['ordertype'] = 2;
					$condition['status'] = 0;
					$condition['isfinished'] = 0;
					$update = array();
					$update['isfinished'] = 1;
					$module->updateChat($condition,$update);
				}
			}
			
			if (check_arr($rs)) {
				$mo->commit();
				$this->success('评价成功！',$extra);
			}
			else {
				throw new \Think\Exception('评价失败！');
			}
		}catch(\Think\Exception $e){
			$mo->rollback();
			$this->error('评价失败',$extra);
		}
	}
	
	public function upChat($content, $chatpic="", $ordertype, $orderid, $token, $status){
		$extra='';
		
		if(!session('chatordertoken')) {
			set_token('chatorder');
		}
		if(!empty($token)){
			$res = valid_token('chatorder',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('chatordertoken'));
			}
		}else{
			$this->error('缺少参数！',session('chatordertoken'));
		}
		$extra=session('chatordertoken');

		if (!userid()) {
			$this->error('您没有登录请先登录！',$extra);
		}
		
		if(!empty($chatpic) && !check($chatpic,'a') && !check($chatpic,'url')){
			$this->error('参数错误！',$extra);
		}
		
		if(empty($content)){
			$this->error("请输入对话内容！",$extra);
		}
		
		if(!check($content,'bcdw')){
			$this->error("只能输入中英文和数字！",$extra);
		}
		
		if(empty($ordertype) || empty($orderid)){
			$this->error("缺少参数！",$extra);
		}
		
		if(!check($ordertype,'d')){
			$this->error("参数错误！",$extra);
		}
		
		if(!check($orderid,'d')){
			$this->error("参数错误！",$extra);
		}
		
		$time = time();
		
		$module = D('Chat');
		
		if($status == 3){
			$temp_order = M('order_temp')->where(array('id'=>$orderid,'ordertype'=>$ordertype))->find();
			if(empty($temp_order)){
				$this->error("找不到订单！",$extra);
			}else{
				if($temp_order['buy_id'] == userid()){
					$touid = $temp_order['sell_id'];
				}elseif($temp_order['sell_id'] == userid()){
					$touid = $temp_order['buy_id'];
				}
				if($ordertype == 1){
					$adv = M('ad_sell')->where(array('id'=>$temp_order['sell_sid']))->find();
					if(empty($adv)){
						$this->error("广告不存在！",$extra);
					}else{
						$advtype = 1;
					}
				}
				if($ordertype == 2){
					$adv = M('ad_buy')->where(array('id'=>$temp_order['buy_bid']))->find();
					if(empty($adv)){
						$this->error("广告不存在！",$extra);
					}else{
						$advtype = 0;
					}
				}
			}
			$result = $module->addRecord(userid(),$touid,$orderid,$ordertype,3,$content,$chatpic,$adv['id'],$advtype);
		}else{
			if($ordertype==1){
				$order = M('order_buy')->where(array('id'=>$orderid))->find();
				if(empty($order)){
					$this->error("订单不存在！",$extra);
				}else{
					$adv = M('ad_sell')->where(array('id'=>$order['sell_sid']))->find();
					if(empty($adv)){
						$this->error("广告不存在！",$extra);
					}else{
						$advtype = 1;
					}
				}
			}elseif($ordertype==2){
				$order = M('order_sell')->where(array('id'=>$orderid))->find();
				if(empty($order)){
					$this->error("订单不存在！",$extra);
				}else{
					$adv = M('ad_buy')->where(array('id'=>$order['buy_bid']))->find();
					if(empty($adv)){
						$this->error("广告不存在！",$extra);
					}else{
						$advtype = 0;
					}
				}
			}else{
				$this->error("参数错误！",$extra);
			}
			
			if($order['buy_id'] == userid()){
				$touid = $order['sell_id'];
			}elseif($order['sell_id'] == userid()){
				$touid = $order['buy_id'];
			}
			
			$result = $module->addRecord(userid(),$touid,$orderid,$ordertype,0,$content,$chatpic,$adv['id'],$advtype);
		}

		if(!empty($result)){
			$this->success("提交成功",$extra);
		}else{
			$this->error("提交失败！",$extra);
		}
	}
	
	public function chatPic(){
		$extra = '';
		$token = $_POST['token'];
		if(!session('imgtoken')) {
			set_token('img');
		}
		if(!empty($token)){
			$res = valid_token('img',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('imgtoken'));
			}
		}else{
			$this->error('缺少参数！',session('imgtoken'));
		}
		$extra = session('imgtoken');
		if (!userid()) {
			$this->error('您没有登录请先登录！',$extra);
		}
		
		$ordertype = intval($_POST['ordertype']);
		$orderid = intval($_POST['orderid']);
		$status = intval($_POST['status']);
		
		if(empty($ordertype) || empty($orderid) || !isset($status)){
			$this->error("缺少参数！",$extra);
		}
		
		if(!check($ordertype,'d') || !check($orderid,'d') || !check($status,'d')){
			$this->error("参数错误！",$extra);
		}
		
		if(!empty($_FILES['upchatpic']['size'])) {
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
				$module = D('Chat');
				if($status == 3){
					$temp_order = M('order_temp')->where(array('id'=>$orderid,'ordertype'=>$ordertype))->find();
					if(empty($temp_order)){
						$this->error("找不到订单！",$extra);
					}else{
						if($temp_order['buy_id'] == userid()){
							$touid = $temp_order['sell_id'];
						}elseif($temp_order['sell_id'] == userid()){
							$touid = $temp_order['buy_id'];
						}
						if($ordertype == 1){
							$adv = M('ad_sell')->where(array('id'=>$temp_order['sell_sid']))->find();
							if(empty($adv)){
								$this->error("广告不存在！",$extra);
							}else{
								$advtype = 1;
							}
						}
						if($ordertype == 2){
							$adv = M('ad_buy')->where(array('id'=>$temp_order['buy_bid']))->find();
							if(empty($adv)){
								$this->error("广告不存在！",$extra);
							}else{
								$advtype = 0;
							}
						}
					}
					$result = $module->addRecord(userid(),$touid,$orderid,$ordertype,3,"",$chatpic,$adv['id'],$advtype);
				}else{
					if($ordertype==1){
						$order = M('order_buy')->where(array('id'=>$orderid))->find();
						if(empty($order)){
							$this->error("订单不存在！",$extra);
						}else{
							$adv = M('ad_sell')->where(array('id'=>$order['sell_sid']))->find();
							if(empty($adv)){
								$this->error("广告不存在！",$extra);
							}else{
								$advtype = 1;
							}
						}
					}elseif($ordertype==2){
						$order = M('order_sell')->where(array('id'=>$orderid))->find();
						if(empty($order)){
							$this->error("订单不存在！",$extra);
						}else{
							$adv = M('ad_buy')->where(array('id'=>$order['buy_bid']))->find();
							if(empty($adv)){
								$this->error("广告不存在！",$extra);
							}else{
								$advtype = 0;
							}
						}
					}else{
						$this->error("参数错误！",$extra);
					}
					
					if($order['buy_id'] == userid()){
						$touid = $order['sell_id'];
					}elseif($order['sell_id'] == userid()){
						$touid = $order['buy_id'];
					}
					
					$result = $module->addRecord(userid(),$touid,$orderid,$ordertype,0,"",$chatpic,$adv['id'],$advtype);
				}
				if(!empty($result)){
					header('Location:/Order/upload.html?orderid='.$orderid.'&ordertype='.$ordertype.'&status='.$status);
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
	
	public function inchatPic(){
		$extra = '';
		$token = $_POST['token'];
		if(!session('upimgtoken')) {
			set_token('upimg');
		}
		if(!empty($token)){
			$res = valid_token('upimg',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('upimgtoken'));
			}
		}else{
			$this->error('缺少参数！',session('upimgtoken'));
		}
		$extra = session('upimgtoken');
		
		if (!userid()) {
			$this->error('您没有登录请先登录！',$extra);
		}
		
		$ordertype = intval($_POST['ordertype']);
		$orderid = intval($_POST['orderid']);
		$status = intval($_POST['status']);
		
		if(empty($ordertype) || empty($orderid) || !isset($status)){
			$this->error("缺少参数！",$extra);
		}
		if(!check($ordertype,'d') || !check($orderid,'d') || !check($status,'d')){
			$this->error("参数错误！",$extra);
		}
		
		if(!empty($_FILES['upchatpic']['size'])) {
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
				$module = D('Chat');
				if($status == 3){
					$temp_order = M('order_temp')->where(array('id'=>$orderid,'ordertype'=>$ordertype))->find();
					if(empty($temp_order)){
						$this->error("找不到订单！",$extra);
					}else{
						if($temp_order['buy_id'] == userid()){
							$touid = $temp_order['sell_id'];
						}elseif($temp_order['sell_id'] == userid()){
							$touid = $temp_order['buy_id'];
						}
						if($ordertype == 1){
							$adv = M('ad_sell')->where(array('id'=>$temp_order['sell_sid']))->find();
							if(empty($adv)){
								$this->error("广告不存在！",$extra);
							}else{
								$advtype = 1;
							}
						}
						if($ordertype == 2){
							$adv = M('ad_buy')->where(array('id'=>$temp_order['buy_bid']))->find();
							if(empty($adv)){
								$this->error("广告不存在！",$extra);
							}else{
								$advtype = 0;
							}
						}
					}
					$result = $module->addRecord(userid(),$touid,$orderid,$ordertype,3,"",$chatpic,$adv['id'],$advtype);
				}else{
					if($ordertype==1){
						$order = M('order_buy')->where(array('id'=>$orderid))->find();
						if(empty($order)){
							$this->error("订单不存在！",$extra);
						}else{
							$adv = M('ad_sell')->where(array('id'=>$order['sell_sid']))->find();
							if(empty($adv)){
								$this->error("广告不存在！",$extra);
							}else{
								$advtype = 1;
							}
						}
					}elseif($ordertype==2){
						$order = M('order_sell')->where(array('id'=>$orderid))->find();
						if(empty($order)){
							$this->error("订单不存在！",$extra);
						}else{
							$adv = M('ad_buy')->where(array('id'=>$order['buy_bid']))->find();
							if(empty($adv)){
								$this->error("广告不存在！",$extra);
							}else{
								$advtype = 0;
							}
						}
					}else{
						$this->error("参数错误！",$extra);
					}
					
					if($order['buy_id'] == userid()){
						$touid = $order['sell_id'];
					}elseif($order['sell_id'] == userid()){
						$touid = $order['buy_id'];
					}
					
					$result = $module->addRecord(userid(),$touid,$orderid,$ordertype,0,"",$chatpic,$adv['id'],$advtype);
				}
				if(!empty($result)){
					header('Location:/Order/upload.html?orderid='.$orderid.'&ordertype='.$ordertype.'&status='.$status);
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
	//申诉
    public function shensu_ajax(){
		$extra="";
		$token=I('token');
		if(!session('ddsstoken')) {
			set_token('ddss');
		}
		if(!empty($token)){
			$res = valid_token('ddss',$token);
			if(!$res){
				$this->error('请不要频繁提交！',session('ddsstoken'));
			}
		}else{
			$this->error('缺少参数！',session('ddsstoken'));
		}
		$extra=session('ddsstoken');
		
        if(!userid()){
            $this->error("请先登录！",$extra);
        }
		
        $sutype=I('sutype');
        $id=I('id');
		if(!check($id,'d')){
			$this->error("参数错误！",$extra);
		}
		$id=$id*1;
        $type=I('type');
        $cont=I('content');
		$sutp=$_FILES['sutp'];
		
		if(!check($cont,'bcdw')){
			$this->error("只能输入中英文和数字！",$extra);
		}
       
        if($sutype!=1 && $sutype!=2){
            $this->error('请选择申诉原因',$extra);
        }
        $mo=($type==1)?M('order_buy'):M('order_sell');

        $orderinfo=$mo->where("id=".$id." and (buy_id=".userid()." or sell_id=".userid().")")->find();

		if(!$orderinfo){
			$this->error('订单不存在',$extra);
		}
		if($orderinfo['status']==5){
			$this->error('该订单已经被取消',$extra);
		}
		if($orderinfo['status']==0){
			$this->error('该订单已经被拍下，还未付款,不能申诉',$extra);
		}
		if($orderinfo['status']==6){
			$this->error('该订单已经处于申诉状态，请耐心等待',$extra);
		}
		if($orderinfo['status']==4 || $orderinfo['status']==3){
			$this->error('该订单已经完成，无法申诉',$extra);
		}
		if(!empty($sutp)){
			$upload = new \Think\Upload();//实列化上传类
			$upload->maxSize = 3145728;//设置上传文件最大，大小
			$upload->exts = array('jpg', 'gif', 'png', 'jpeg');//后缀
			$upload->rootPath = './Upload/lanch/sutp/';//上传目录
			$upload->savePath = ''; // 设置附件上传（子）目录
			$upload->autoSub = true;
			$upload->subName = array('date', 'Ymd');
			$upload->saveName = array('uniqid', '');//设置上传文件规则
			$info = $upload->upload();//执行上传方法
			if (!$info) {
				$pic='';
			} else {
				$image = new \Think\Image();
				foreach ($info as $key => $file) {
					$image->open('./Upload/lanch/sutp/' . $file['savepath'] . $file['savename']);
					$width = $image->width();
					$height = $image->height();
					if (empty($width) || empty($height)) {
						$bili = 1;
					} else {
						$bili = intval($width / $height);
					}
					$new_width = 600;
					$new_height = intval($new_width / $bili);
					// 按照原图的比例生成一个最大宽度为600像素的缩略图并删除原图
					$image->thumb($new_width, $new_height)->save('./Upload/lanch/sutp/' . $file['savepath'] . "s_" . $file['savename']);
					unlink('./Upload/lanch/sutp/' . $file['savepath'] . $file['savename']);
				}
			}
			if(!empty($info['sutp']['savename'])){
				$pic = "/Upload/lanch/sutp/" . $info['sutp']['savepath'] . "s_" . $info['sutp']['savename'];
			}else{
				$pic='';
			}
		}else{
			$pic='';
		}

        $suid=$mo->where("id=".$id)->save(array("status"=>6,'su_type'=>$sutype,'su_reason'=>$cont,'sutp'=>$pic));
        if(empty($suid)){
             $this->error('申诉提交失败',$extra);
        }else{
             $this->success('申诉提交成功，请耐心等待审核',$extra);
        }
    }
	public function markRead($orderid=NULL,$ordertype=NULL,$status=0){
		if(!check($orderid,'d') || !check($ordertype,'d') || !check($status,'d')){
			$this->error('参数错误');
		}
		$condition=array();
		$condition['orderid'] = $orderid;
		$condition['ordertype'] = $ordertype;
		$condition['status'] = $status;
		$update = array();
		$update['isread'] = 1;
		$module = D('Chat');
		$res = $module->updateChat($condition,$update);
	}
    public function tmpbill_ajax($type,$tid){
        if(!userid()){
             $this->error('请先登录');
        }
        if(checkstr($type) || checkstr($tid)){
            $this->error('信息有误');
        }
        if($type!=1){
            $this->error('生成方式不正确');
        }
		if(!check($tid,'d')){
			$this->error('参数错误');
		}

        $adv = M('ad_sell')->where(array('id'=>$tid))->find();
        if(empty($adv)){
            $this->error("广告不存在！");
        }else{
            $advtype = 1;
        }
        //是否已经产生了临时单
        $has_tmp=M('order_temp')->where(array('ordertype'=>1,'buy_id'=>userid(),'sell_sid'=>$adv['id'],'sell_id'=>$adv['userid']))->find();
        if($has_tmp){
            $this->success("你已经有一笔临时单，点击确定查看");
        }

        $res = M('order_temp')->add(array('ordertype'=>1,'buy_id'=>userid(),'sell_sid'=>$adv['id'],'sell_id'=>$adv['userid'],'ctime'=>time()));
        if(!empty($res)){
            $this->success("生成临时单成功");
        }else{
            $this->error("生成临时单失败");
        }


    }
	function upload(){
		$orderid = intval($_GET['orderid']);
		$ordertype = intval($_GET['ordertype']);
		$status = intval($_GET['status']);
		$id = intval($_GET['id']);
		$this->assign('orderid',$orderid);
		$this->assign('ordertype',$ordertype);
		$this->assign('status',$status);
		$this->assign('id',$id);
		//生成token
		$img_token = set_token('img');
		$this->assign('img_token',$img_token);
		$this->display();
	}
	function inupload(){
		$orderid = intval($_GET['orderid']);
		$ordertype = intval($_GET['ordertype']);
		$status = intval($_GET['status']);
		$this->assign('orderid',$orderid);
		$this->assign('ordertype',$ordertype);
		$this->assign('status',$status);
		//生成token
		$upimg_token = set_token('upimg');
		$this->assign('upimg_token',$upimg_token);
		$this->display();
	}
	public function toadmin($type,$id){
		if(!userid()){
			$this->error("请先登录");
		}
		if(!check($id,'d')){
			$this->error("参数错误！");
		}
		if(!check($type,'d')){
			$this->error("参数错误！");
		}
		if($type == 1){
			$order = M('order_buy')->where(array('type'=>$type,'id'=>$id))->find();
			if(!empty($order)){
				if($order['adminhandle']==0){
					if($order['status'] == 1){
						$rs = M('order_buy')->where(array('id'=>$order['id']))->save(array('adminhandle'=>1));
						if(!empty($rs)){
							$this->success("申请成功！");
						}else{
							$this->error("申请失败！");
						}
					}else{
						$this->error("订单状态错误！");
					}
				}else{
					$this->error("您已经申请过了！");
				}
			}else{
				$this->error("找不到订单！");
			}
		}
		if($type == 2){
			$order = M('order_sell')->where(array('type'=>$type,'id'=>$id))->find();
			if(!empty($order)){
				if($order['adminhandle']==0){
					if($order['status'] == 1){
						$rs = M('order_sell')->where(array('id'=>$order['id']))->save(array('adminhandle'=>1));
						if(!empty($rs)){
							$this->success("申请成功！");
						}else{
							$this->error("申请失败！".$type."|".$id);
						}
					}else{
						$this->error("订单状态错误！");
					}
				}else{
					$this->error("您已经申请过了！");
				}
			}else{
				$this->error("找不到订单！");
			}
		}
	}
}

?>