<?php
namespace Admin\Controller;

class TradeController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","orderlist","orderinfo","chuli_ajax","adinfo","adfrozen","invit","addel","admindone","analysis");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function chatlist($orderid = NULL, $type = NULL){
		if(empty($orderid) || empty($type)){
			$this->error("页面不存在！");
		}
		$module = D('Chat');
		$records = $module->listbyOrderid($orderid,$type);
		var_dump($records);
	}
	public function tmplist($field = NULL, $name = NULL, $market = 'tmp', $status = NULL, $bs_type = NULL, $starttime = NULL, $endtime = NULL){

		  if ($field == 'buy_name') {
				$where['buy_id'] = M('User')->where(array('enname' => $name))->getField('id');
			}
			elseif ($field == 'sell_name') {
				$where['sell_id'] = M('User')->where(array('enname' => $name))->getField('id');
			}
			elseif ($field == 'sell_ad_no') {
				$where['sell_sid'] = M('Ad_sell')->where(array('ad_no' => $name))->getField('id');
			}
			elseif ($field == 'buy_ad_no') {
				$where['buy_bid'] = M('Ad_buy')->where(array('ad_no' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		// 时间--条件

		$time_type = 'ctime';

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
			$where['ctime'] =  array('EGT',$now_time);
		}

		$count = M('order_temp')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('order_temp')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['buy_name'] = M('User')->where(array('id' => $v['buy_id']))->getField('enname');
			$list[$k]['sell_name'] = M('User')->where(array('id' => $v['sell_id']))->getField('enname');
			if($v['ordertype']=='1'){
				$list[$k]['buy_ad_no'] = M('Ad_buy')->where(array('id' => $v['buy_bid']))->getField('ad_no');
			}else{
				$list[$k]['sell_ad_no'] = M('Ad_sell')->where(array('id' => $v['sell_sid']))->getField('ad_no');
			}
			$list[$k]['deal_ctype'] = M('Currency')->where(array('id' => $v['deal_ctype']))->getField('short_name');
		}
		$this->assign('market', $market);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	

	
	public function orderlist($field = NULL, $name = NULL, $market = 'buy', $status = NULL, $adminhandle = NULL, $bs_type = NULL, $starttime = NULL, $endtime = NULL){
		

		if($market == 'tmp'){
			$this->tmplist($field, $name, $market, $status, $bs_type, $starttime, $endtime);
			exit;
		}
		$where = array();
		if ($field && $name) {
			if ($field == 'buy_name') {
				$where['buy_id'] = M('User')->where(array('enname' => $name))->getField('id');
			}
			elseif ($field == 'sell_name') {
				$where['sell_id'] = M('User')->where(array('enname' => $name))->getField('id');
			}
			elseif ($field == 'sell_ad_no') {
				$where['sell_sid'] = M('Ad_sell')->where(array('ad_no' => $name))->getField('id');
			}
			elseif ($field == 'buy_ad_no') {
				$where['buy_bid'] = M('Ad_buy')->where(array('ad_no' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		if(!empty($adminhandle) && $adminhandle==1){
			$where['adminhandle'] = 1;
		}
		// 交易类型
		if ($bs_type) {
			$where['type'] = $bs_type;
		}


		// 时间--条件

		$time_type = 'addtime';

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


		// $list_new = M('Trade')->where($where)->order('id desc')->select();

		// $num_zong = 0;
		// $num_cj_zong = 0;
		// $money_zong = 0;
		// $fee = 0;
		// $tradeid = array();
		// foreach ($list_new as $k => $v) {
		// 	$num_zong += $v['num'];
		// 	$num_cj_zong += $v['deal'];
		// 	$money_zong += $v['mum'];
		// 	$fee += $v['fee'];
		// 	array_push($tradeid,$v['id']);
		// }
		// $this->assign('tradeid',implode(",",$tradeid));

		if($market=='sell'){
			$table='Order_sell';
		}else{
			$table='Order_buy';
		}

		$count = M($table)->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M($table)->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['buy_name'] = M('User')->where(array('id' => $v['buy_id']))->getField('enname');
			$list[$k]['sell_name'] = M('User')->where(array('id' => $v['sell_id']))->getField('enname');
			if($market=='sell'){
				$list[$k]['buy_ad_no'] = M('Ad_buy')->where(array('id' => $v['buy_bid']))->getField('ad_no');
			}else{
				$list[$k]['sell_ad_no'] = M('Ad_sell')->where(array('id' => $v['sell_sid']))->getField('ad_no');
			}
			//$list[$k]['deal_ctype'] = M('Currency')->where(array('id' => $v['deal_ctype']))->getField('short_name');
			$list[$k]['deal_ctype'] = get_price($v['deal_coin'],$v['deal_ctype'],0);
			$list[$k]['coin'] = M('Coin')->where("id={$list[$k]['deal_coin']}")->getField('js_yw');
		}
		$this->assign('market', $market);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function orderinfo($id = NULL, $market = 'buy'){

		if($market=='sell'){
			$table='Order_sell';
		}else{
			$table='Order_buy';
		}

		$order=M($table)->where(array('id' => $id))->find();
		$order['buy_name'] = M('User')->where(array('id' => $order['buy_id']))->getField('enname');
		$order['sell_name'] = M('User')->where(array('id' => $order['sell_id']))->getField('enname');
		if($market=='sell'){
			$order['buy_ad_no'] = M('Ad_buy')->where(array('id' => $order['buy_bid']))->getField('ad_no');
		}else{
			$order['sell_ad_no'] = M('Ad_sell')->where(array('id' => $order['sell_sid']))->getField('ad_no');
		}
		//$order['deal_ctype'] = M('Currency')->where(array('id' => $order['deal_ctype']))->getField('short_name');
		$order['deal_ctype'] = get_price($order['deal_coin'],$order['deal_ctype'],0);
		$order['coin'] = M('Coin')->where("id={$order['deal_coin']}")->getField('js_yw');
		$this->assign('market', $market);
		$this->assign('order', $order);
		$this->display();
	}
	//处理申诉
	public function chuli_ajax($market,$id,$type){
		if($market=='sell'){
			$table='order_sell';
			$log_type=2;
		}else{
			$table='order_buy';
			$log_type=1;
		}
		$order=M($table)->where(array('id' => $id))->find();
		$buyer = M('User')->where(array('id'=>$order['buy_id']))->find();
		$seller = M('User')->where(array('id'=>$order['sell_id']))->find();
		if(!$order){
			$this->error("交易订单不存在");
		}
		if($order['status']==4 || $order['status']==3){
			$this->error("交易订单已经完成，刷新试试看");
		}
		$coin_name = M('Coin')->where(array('id'=>$order['deal_coin']))->getField('name');
		$coin_table = "tw_".$coin_name."_log";
	    // 1是继续交易，给买家释放btc  2是取消交易还原给买家冻结比特币
		if($type==1){
			if($market=='sell'){
				try{
					$mo = M();
					$mo->startTrans();
					$rs = array();
					$buyer_up_number = $order['deal_num']-$order['fee'];
					//我减d冻结里 对方加
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $order['sell_id']))->setDec($coin_name.'d', $order['deal_num']);
					$rs[]=$mo->table($coin_table)->add(array('username'=>$seller['username'],'userid'=>$order['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>0,'amount'=>$order['deal_num'],'desc'=>'卖家释放币减冻结'.strtoupper($coin_name),'operator'=>1,'ctype'=>2,'action'=>7,'addip'=>get_client_ip()));
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $order['buy_id']))->setInc($coin_name, $buyer_up_number);
					$rs[]=$mo->table($coin_table)->add(array('username'=>$buyer['username'],'userid'=>$order['buy_id'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$buyer_up_number,'desc'=>'卖家释放币加可用'.strtoupper($coin_name).'，手续费'.$order['fee']*1,'operator'=>1,'ctype'=>1,'action'=>7,'addip'=>get_client_ip()));
					$rs[]=$mo->table('tw_order_sell') ->where(array('id'=>$id,'sell_id'=>$order['sell_id']))->save(array('status'=>3,'finished_time'=>time()));
					if($order['adminhandle']==1){
						$rs[] = $mo->table('tw_order_sell')->where(array('id'=>$id,'sell_id'=>$order['sell_id']))->save(array('adminhandle'=>0));
					}
					//记录第一次交易时间
					$buyer_ftt = $mo->table('tw_user')->where(array('id'=>$order['buy_id']))->getField('first_trade_time');
					if(empty($buyer_ftt)){
						$rs[] = $mo->table('tw_user')->where("id=".$order['buy_id'])->save(array('first_trade_time'=>time()));
					}
					$seller_ftt = $mo->table('tw_user')->where("id=".$order['sell_id'])->getField('first_trade_time');
					if(empty($seller_ftt)){
						$rs[] = $mo->table('tw_user')->where("id=".$order['sell_id'])->save(array('first_trade_time'=>time()));
					}
					
					//交易次数加1
					$rs[]=$mo->table('tw_user')->where("id=".$order['sell_id'])->setInc('transact',1);
					$rs[]=$mo->table('tw_user')->where("id=".$order['buy_id'])->setInc('transact',1);

					//s双方交易次数
					$buyer_trade_id = $mo->table('tw_user')->where(array('id'=>$order['buy_id']))->getField('trade_id');
					$new_bti = $buyer_trade_id.','.$order['sell_id'];
					$rs[] = $mo->table('tw_user')->where(array('id'=>$order['buy_id']))->setField('trade_id',$new_bti);
					$seller_trade_id = $mo->table('tw_user')->where(array('id'=>$order['sell_id']))->getField('trade_id');
					$new_sti = $seller_trade_id.','.$order['buy_id'];
					$rs[] = $mo->table('tw_user')->where(array('id'=>$order['sell_id']))->setField('trade_id',$new_sti);
					
					//发放交易手续费提成
					if (!empty($invit_buy)) {
						
						$coin_info = $mo->table('tw_coin')->where(array('id'=>$order['deal_coin']))->find();
						
						$invit_1 = $coin_info['fee_bili'];

						$invit_2 = $coin_info['fee_meitian'];

						$invit_3 = $coin_info['js_lt'];

						if ($invit_1) {

							if ($order['fee']) {

								if ($buyer['invit_1']) {
									$buyer_invit1_user = $mo->table('tw_user')->where(array('id'=>$buyer['invit_1']))->find();
									if(time()-$buyer_invit1_user['addtime']<=$invit_buy*30*24*3600){
										$invit_buy_save_1 = round(($order['fee'] / 100) * $invit_1, 8);
										if ($invit_buy_save_1) {
											$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buyer['invit_1']))->setInc($coin_info['name'], $invit_buy_save_1);
											$rs[]=$mo->table($coin_table)->add(array('username'=>$buyer_invit1_user['username'],'userid'=>$buyer['invit_1'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$invit_buy_save_1,'desc'=>'买广告交易完成发给买家一级上线佣金','operator'=>1,'ctype'=>1,'action'=>7,'addip'=>get_client_ip()));
											$rs[] = $mo->table('tw_invit')->add(array('userid' => $buyer['invit_1'], 'invit' => $buyer['id'], 'name' => 1, 'type' => $coin_info['id'], 'num' => $order['deal_num'], 'mum' => $order['deal_num'], 'fee' => $invit_buy_save_1, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'orderno'=>$order['order_no']));
										}
									}
								}

								if ($buyer['invit_2']) {
									$buyer_invit2_user = $mo->table('tw_user')->where(array('id'=>$buyer['invit_2']))->find();
									if(time()-$buyer_invit2_user['addtime']<=$invit_buy*30*24*3600){
										$invit_buy_save_2 = round(($order['fee'] / 100) * $invit_2, 8);
										if ($invit_buy_save_2) {
											$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buyer['invit_2']))->setInc($coin_info['name'], $invit_buy_save_2);
											$rs[]=$mo->table($coin_table)->add(array('username'=>$buyer_invit2_user['username'],'userid'=>$buyer['invit_2'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$invit_buy_save_2,'desc'=>'买广告交易完成发给买家二级上线佣金','operator'=>1,'ctype'=>1,'action'=>7,'addip'=>get_client_ip()));
											$rs[] = $mo->table('tw_invit')->add(array('userid' => $buyer['invit_2'], 'invit' => $buyer['id'], 'name' => 2, 'type' => $coin_info['id'], 'num' => $order['deal_num'], 'mum' => $order['deal_num'], 'fee' => $invit_buy_save_2, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'orderno'=>$order['order_no']));
										}
									}
								}

								if ($buyer['invit_3']) {
									$buyer_invit3_user = $mo->table('tw_user')->where(array('id'=>$buyer['invit_3']))->find();
									if(time()-$buyer_invit3_user['addtime']<=$invit_buy*30*24*3600){
										$invit_buy_save_3 = round(($order['fee'] / 100) * $invit_3, 8);
										if ($invit_buy_save_3) {
											$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $buyer['invit_3']))->setInc($coin_info['name'], $invit_buy_save_3);
											$rs[]=$mo->table($coin_table)->add(array('username'=>$buyer_invit3_user['username'],'userid'=>$buyer['invit_3'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$invit_buy_save_3,'desc'=>'买广告交易完成发给买家三级上线佣金','operator'=>1,'ctype'=>1,'action'=>7,'addip'=>get_client_ip()));
											$rs[] = $mo->table('tw_invit')->add(array('userid' => $buyer['invit_3'], 'invit' => $buyer['id'], 'name' => 3, 'type' => $coin_info['id'], 'num' => $order['deal_num'], 'mum' => $order['deal_num'], 'fee' => $invit_buy_save_3, 'addtime' => time(), 'status' => 1, 'buysell' => 2, 'orderno'=>$order['order_no']));
										}
									}
								}

							}

						}

					}

					if (check_arr($rs)) {
						$mo->commit();
						$this->success('释放成功！',$extra);
					}
					else {
						throw new \Think\Exception('释放失败！');
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error($e->getMessage(),$extra);
				}
			}else{
				try{
					$mo = M();
					$mo->startTrans();
					$rs = array();
					$seller_down_number = $order['deal_num']+$order['fee'];
					//我减冻结里减去 对方加
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $order['sell_id']))->setDec($coin_name.'d', $seller_down_number);
					$rs[]=$mo->table($coin_table)->add(array('username'=>$seller['username'],'userid'=>$order['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>0,'amount'=>$seller_down_number,'desc'=>'卖家释放币减冻结'.strtoupper($coin_name),'operator'=>1,'ctype'=>2,'action'=>7,'addip'=>get_client_ip()));
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $order['buy_id']))->setInc($coin_name, $order['deal_num']);
					$rs[]=$mo->table($coin_table)->add(array('username'=>$buyer['username'],'userid'=>$order['buy_id'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$order['deal_num'],'desc'=>'卖家释放币加可用'.strtoupper($coin_name),'operator'=>1,'ctype'=>1,'action'=>7,'addip'=>get_client_ip()));
					$rs[]=$mo->table('tw_order_buy') ->where(array('id'=>$id,'sell_id'=>$order['sell_id']))->save(array('status'=>3,'finished_time'=>time()));
					if($order['adminhandle'] == 1){
						$rs[] = $mo->table('tw_order_buy')->where(array('id'=>$id,'sell_id'=>$order['sell_id']))->save(array('adminhandle'=>0));
					}
					//交易时间设置                
					$buyer_ftt = $mo->table('tw_user')->where("id=".$order['buy_id'])->getField('first_trade_time');
					if(empty($buyer_ftt)){
						$rs[] = $mo->table('tw_user')->where("id=".$order['buy_id'])->save(array('first_trade_time'=>time()));
					}
						
					$seller_ftt = $mo->table('tw_user')->where("id=".$order['sell_id'])->getField('first_trade_time');
					if(empty($seller_ftt)){
						$rs[] = $mo->table('tw_user')->where("id=".$order['sell_id'])->save(array('first_trade_time'=>time()));
					}
					//记录一下卖家放行的时间
					if(!empty($order['dktime'])){
						$usetime=intval((time()-$order['dktime'])/60);
						if($usetime>0){
							$rs[] = $mo->table('tw_user')->where("id=".$order['sell_id'])->setInc('sftime_sum',$usetime);
						}
					}
					//交易次数加1
					$rs[]=$mo->table('tw_user')->where("id=".$order['sell_id'])->setInc('transact',1);
					$rs[]=$mo->table('tw_user')->where("id=".$order['buy_id'])->setInc('transact',1);

					//s双方交易次数
					$buyer_trade_id=$mo->table('tw_user')->where(array('id'=>$order['buy_id']))->getField('trade_id');
					$new_bti = $buyer_trade_id.','.$order['sell_id'];
					$rs[] = $mo->table('tw_user')->where(array('id'=>$order['buy_id']))->setField('trade_id',$new_bti);
					$seller_trade_id=$mo->table('tw_user')->where(array('id'=>$order['sell_id']))->getField('trade_id');
					$new_sti = $seller_trade_id.','.$order['buy_id'];
					$rs[] = $mo->table('tw_user')->where(array('id'=>$order['sell_id']))->setField('trade_id',$new_sti);
					
					//发放交易手续费提成
					if (!empty($invit_buy)) {
						
						$coin_info = $mo->table('tw_coin')->where(array('id'=>$order['deal_coin']))->find();
						
						$invit_1 = $coin_info['fee_bili'];

						$invit_2 = $coin_info['fee_meitian'];

						$invit_3 = $coin_info['js_lt'];

						if ($invit_1) {

							if ($order['fee']) {

								if ($seller['invit_1']) {
									$my_invit1_user = $mo->table('tw_user')->where(array('id'=>$seller['invit_1']))->find();
									if(time()-$my_invit1_user['addtime']<=$invit_buy*30*24*3600){
										$invit_buy_save_1 = round(($order['fee'] / 100) * $invit_1, 8);
										if ($invit_buy_save_1) {
											$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $seller['invit_1']))->setInc($coin_info['name'], $invit_buy_save_1);
											$rs[]=$mo->table($coin_table)->add(array('username'=>$my_invit1_user['username'],'userid'=>$seller['invit_1'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$invit_buy_save_1,'desc'=>'卖广告交易完成发给卖家一级上线佣金','operator'=>1,'ctype'=>1,'action'=>7,'addip'=>get_client_ip()));
											$rs[] = $mo->table('tw_invit')->add(array('userid' => $seller['invit_1'], 'invit' => $order['sell_id'], 'name' => 1, 'type' => $coin_info['id'], 'num' => $order['deal_num'], 'mum' => $order['deal_num'], 'fee' => $invit_buy_save_1, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'orderno'=>$order['order_no']));

										}
									}
								}

								if ($seller['invit_2']) {
									$my_invit2_user = $mo->table('tw_user')->where(array('id'=>$seller['invit_2']))->find();
									if(time()-$my_invit2_user['addtime']<=$invit_buy*30*24*3600){
										$invit_buy_save_2 = round(($order['fee'] / 100) * $invit_2, 8);
										if ($invit_buy_save_2) {
											$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $seller['invit_2']))->setInc($coin_info['name'], $invit_buy_save_2);
											$rs[]=$mo->table($coin_table)->add(array('username'=>$my_invit2_user['username'],'userid'=>$seller['invit_2'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$invit_buy_save_2,'desc'=>'卖广告交易完成发给卖家二级上线佣金','operator'=>1,'ctype'=>1,'action'=>7,'addip'=>get_client_ip()));
											$rs[] = $mo->table('tw_invit')->add(array('userid' => $seller['invit_2'], 'invit' => $order['sell_id'], 'name' => 2, 'type' => $coin_info['id'], 'num' => $order['deal_num'], 'mum' => $order['deal_num'], 'fee' => $invit_buy_save_2, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'orderno'=>$order['order_no']));
										}
									}
								}

								if ($seller['invit_3']) {
									$my_invit3_user = $mo->table('tw_user')->where(array('id'=>$seller['invit_3']))->find();
									if(time()-$my_invit3_user['addtime']<=$invit_buy*30*24*3600){
										$invit_buy_save_3 = round(($order['fee'] / 100) * $invit_3, 8);
										if ($invit_buy_save_3) {
											$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $seller['invit_3']))->setInc($coin_info['name'], $invit_buy_save_3);
											$rs[]=$mo->table($coin_table)->add(array('username'=>$my_invit3_user['username'],'userid'=>$seller['invit_3'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$invit_buy_save_3,'desc'=>'卖广告交易完成发给卖家三级上线佣金','operator'=>1,'ctype'=>1,'action'=>7,'addip'=>get_client_ip()));
											$rs[] = $mo->table('tw_invit')->add(array('userid' => $seller['invit_3'], 'invit' => $order['sell_id'], 'name' => 3, 'type' => $coin_info['id'], 'num' => $order['deal_num'], 'mum' => $order['deal_num'], 'fee' => $invit_buy_save_3, 'addtime' => time(), 'status' => 1, 'buysell' => 1, 'orderno'=>$order['order_no']));

										}
									}
								}

							}

						}

					}
				   
					if (check_arr($rs)) {
						$mo->commit();
						$this->success('释放成功！',$extra);
					}
					else {
						throw new \Think\Exception('释放失败！');
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error($e->getMessage(),$extra);
				}
			}
		}
		if($type==2){
			if($market=='sell'){
				try{
					$mo = M();
					$mo->startTrans();
					$rs[]=$mo->table('tw_order_sell')->where(array('id'=>$id,'buy_id'=>$order['buy_id']))->save(array('status'=>5));
					if($order['adminhandle'] == 1){
						$rs[] = $mo->table('tw_order_sell')->where(array('id'=>$id,'buy_id'=>$order['buy_id']))->save(array('adminhandle'=>0));
					}
					//冻结中减去
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$order['sell_id']))->setDec($coin_name.'d', $order['deal_num']);
					$rs[]=$mo->table($coin_table)->add(array('username'=>$seller['username'],'userid'=>$order['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>0,'amount'=>$order['deal_num'],'desc'=>'买家取消订单减冻结'.strtoupper($coin_name),'operator'=>1,'ctype'=>2,'action'=>8,'addip'=>get_client_ip()));
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$order['sell_id']))->setInc($coin_name, $order['deal_num']);
					$rs[]=$mo->table($coin_table)->add(array('username'=>$seller['username'],'userid'=>$order['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$order['deal_num'],'desc'=>'买家取消订单加可用'.strtoupper($coin_name),'operator'=>1,'ctype'=>1,'action'=>8,'addip'=>get_client_ip()));
					if(check_arr($rs)) {
						$mo->commit();
						$this->success('取消成功！',$extra);
					}
					else {
						throw new \Think\Exception('取消失败！');
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error($e->getMessage(),$extra);
				}
			}else{
				try{
					$mo = M();
					$mo->startTrans();
					$rs[]=$mo->table('tw_order_buy')->where(array('id'=>$id,'buy_id'=>$order['buy_id']))->save(array('status'=>5));
					if($order['adminhandle'] == 1){
						$rs[] = $mo->table('tw_order_buy')->where(array('id'=>$id,'buy_id'=>$order['buy_id']))->save(array('adminhandle'=>0));
					}
					$real_number = $order['deal_num'] + $order['fee'];
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$order['sell_id']))->setDec($coin_name.'d', $real_number);
					$rs[]=$mo->table($coin_table)->add(array('username'=>$seller['username'],'userid'=>$order['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>0,'amount'=>$real_number,'desc'=>'买家取消订单减冻结'.strtoupper($coin_name),'operator'=>1,'ctype'=>2,'action'=>8,'addip'=>get_client_ip()));
					$rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$order['sell_id']))->setInc($coin_name, $real_number);
					$rs[]=$mo->table($coin_table)->add(array('username'=>$seller['username'],'userid'=>$order['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$real_number,'desc'=>'买家取消订单加可用'.strtoupper($coin_name),'operator'=>1,'ctype'=>1,'action'=>8,'addip'=>get_client_ip()));
					if(check_arr($rs)) {
						$mo->commit();
						$this->success('取消成功！',$extra);
					}
					else {
						throw new \Think\Exception('取消失败！');
					}
				}catch(\Think\Exception $e){
					$mo->rollback();
					$this->error($e->getMessage(),$extra);
				}
			}
		}
	}
	
	//原交易平台方法
	 
	//广告列表管理
	public function index($field = NULL, $name = NULL, $market = sell, $state = NULL, $starttime = NULL, $endtime = NULL, $location = NULL, $currency = NULL, $pay_method = NULL, $coin = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('enname' => $name))->getField('id');
			} else {
				$where[$field] = $name;
			}
		}

		if ($location) {
			$where['location'] = $location;
		}
		if ($currency) {
			$where['currency'] = $currency;
		}
		if ($pay_method) {
			$where['pay_method'] = $pay_method;
		}

		if ($state) {
			$where['state'] = $state;
		}

		if ($coin) {
			$where['coin'] = $coin;
		}
		// 时间--条件

		$time_type = 'addtime';

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

		$location = M('Location')->select();
		$this->assign('location', $location);

		$currency = M('Btc')->select();
		$this->assign('currency', $currency);

		$pay_method = M('PayMethod')->select();
		$this->assign('pay_method', $pay_method);

		$coin = M('Coin')->where(array('status'=>1,'name'=>array('neq','cny')))->Field('id,js_yw')->select();
		$this->assign('coin', $coin);
/*		$list_new = M('Trade')->where($where)->order('id desc')->select();

		$num_zong = 0;
		$num_cj_zong = 0;
		$money_zong = 0;
		$fee = 0;
		$tradeid = array();
		foreach ($list_new as $k => $v) {
			$num_zong += $v['num'];
			$num_cj_zong += $v['deal'];
			$money_zong += $v['mum'];
			$fee += $v['fee'];
			array_push($tradeid,$v['id']);
		}
		$this->assign('tradeid',implode(",",$tradeid));*/
		$table = $market == "buy"?M('AdBuy'):M('AdSell');

		$count = $table->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = $table->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('enname');
			$list[$k]['location'] = M('Location')->where(array('id' => $v['location']))->getField('name');
			//$list[$k]['currency'] = M('Currency')->where(array('id' => $v['currency']))->getField('short_name').'&nbsp;'.M('Currency')->where(array('id' => $v['currency']))->getField('name');
			$list[$k]['currency'] = get_price($v['coin'],$v['currency'],2);
			$list[$k]['pay_method'] = M('PayMethod')->where(array('id' => $v['pay_method']))->getField('name');
			//$price = M('Currency')->where(array('id'=>$v['currency']))->getField('price');
			$price = get_price($v['coin'],$v['currency'],1);
			$list[$k]['price'] = round($price + $price * $v['margin']/100,2);
			$list[$k]['coin'] = M('Coin')->where("id={$v['coin']}")->getField('js_yw');
		}
/*		$datas = array();
		$datas['num_zong'] = $num_zong;
		$datas['num_cj_zong'] = $num_cj_zong;
		$datas['money_zong'] = $money_zong;
		$datas['fee'] = $fee;
		$this->assign('datas', $datas);*/
		$this->assign('market', $market);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	//广告详情页
	public function adinfo($id = NULL, $market){

		if($market=='sell'){
			$table='AdSell';
		}else{
			$table='AdBuy';
		}

		$order=M($table)->where(array('id' => $id))->find();
		$order['username'] = M('User')->where(array('id' => $order['userid']))->getField('enname');
		$order['location'] = M('Location')->where(array('id'=>$order['location']))->getField('short_name').'&nbsp;'.M('Location')->where(array('id'=>$order['location']))->getField('name');
		//$price = M('Currency')->where(array('id'=>$order['currency']))->getField('price');
		$price = get_price($order['coin'],$order['currency'],1);
		$order['price'] = round($price + $price * $order['margin']/100,2);
		$order['short_name'] = get_price($order['coin'],$order['currency'],0);
		$order['currency'] = $order['short_name'].'&nbsp;'.get_price($order['coin'],$order['currency'],2);
		$order['coin'] = M('Coin')->where("id={$order['coin']}")->getField('js_yw').'&nbsp;'.M('Coin')->where("id={$order['coin']}")->getField('title');
		$order['pay_method'] = M('PayMethod')->where(array('id'=>$order['pay_method']))->getField('name');
		$open_time_arr = explode(",",$order['open_time']);
		$order['open_time'] = '';
		$xingqi = array('一：','二：','三：','四：','五：','六：','日：');
		foreach($open_time_arr as $k => $v){
			if($v == '1'){$v = "开放";}
			elseif($v == '0'){$v = "隐藏";}
			else($v = $v.'点');
			$order['open_time'] = $order['open_time'].'星期'.$xingqi[$k].$v.'<br/>';
		}

		$this->assign('market', $market);
		$this->assign('order', $order);
		$this->display();
	}

	//冻结&解冻
	public function adfrozen($id, $type)
	{
		if($type == 'buy'){
			$table = M('AdBuy');
		}else{
			$table = M('AdSell');
		}
		$frozen = $table->where('id='.$id)->getField('state');

		if($frozen ==1 ){
			$result = $table->where(array('id' => $id))->setField('state',4);
		}elseif($frozen ==4){
			$result = $table->where(array('id' => $id))->setField('state',1);
		}

		if(!empty($result)){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}

	public function chexiao($id = NULL)
	{
		$rs = D('Trade')->chexiao($id);

		if ($rs[0]) {
			$this->success($rs[1]);
		}
		else {
			$this->error($rs[1]);
		}
	}

	public function log($field = NULL, $name = NULL, $market = NULL, $bs_type = NULL, $starttime = NULL, $endtime = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else if ($field == 'peername') {
				$where['peerid'] = M('User')->where(array('username' => $name))->getField('id');
			}
			else {
				$where[$field] = $name;
			}
		}

		if ($market) {
			$where['market'] = $market;
		}

		// 交易类型
		if ($bs_type) {
			$where['type'] = $bs_type;
		}

		// 时间--条件

		$time_type = 'addtime';

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


		$list_new = M('TradeLog')->where($where)->select();

		$num_zong = 0;
		$money_zong = 0;
		$fee_buy_zong = 0;
		$fee_sell_zong = 0;
		$logid = array();
		foreach ($list_new as $k => $v) {
			$num_zong += $v['num'];
			$money_zong += $v['mum'];
			$fee_buy_zong += $v['fee_buy'];
			$fee_sell_zong += $v['fee_sell'];
			array_push($logid,$v['id']);
		}
		$this->assign('logid',implode(',',$logid));
		$count = M('TradeLog')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('TradeLog')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['peername'] = M('User')->where(array('id' => $v['peerid']))->getField('username');
		}
		$datas = array();
		$datas['num_zong'] = $num_zong;
		$datas['money_zong'] = $money_zong;
		$datas['fee_buy'] = $fee_buy_zong;
		$datas['fee_sell'] = $fee_sell_zong;
		$this->assign('datas', $datas);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function chat($field = NULL, $name = NULL)
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

		$count = M('Chat')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Chat')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function chatStatus($id = NULL, $type = NULL, $mobile = 'Chat')
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
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function comment($field = NULL, $name = NULL, $coinname = NULL)
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

		if ($coinname) {
			$where['coinname'] = $coinname;
		}

		$count = M('CoinComment')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('CoinComment')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function commentStatus($id = NULL, $type = NULL, $mobile = 'CoinComment')
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
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function market($field = NULL, $name = NULL)
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

		$count = M('Market')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Market')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function marketEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = array();
			}
			else {
				$this->data = M('Market')->where(array('id' => $id))->find();
			}
			$time_arr = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
			$time_minute = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59');
			$this->assign('time_arr', $time_arr);
			$this->assign('time_minute', $time_minute);

			$this->display();
		}
		else {
			if (APP_DEMO) {
				$this->error('测试站暂时不能修改！');
			}

			$round = array(0, 1, 2, 3, 4, 5, 6);

			if (!in_array($_POST['round'], $round)) {
				$this->error('小数位数格式错误！');
			}

			if ($_POST['id']) {
				$rs = M('Market')->save($_POST);
			}
			else {
				$_POST['name'] = $_POST['sellname'] . '_' . $_POST['buyname'];
				unset($_POST['buyname']);
				unset($_POST['sellname']);

				if (M('Market')->where(array('name' => $_POST['name']))->find()) {
					$this->error('市场存在！');
				}

				$rs = M('Market')->add($_POST);
			}

			if ($rs) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}
		}
	}

	public function marketStatus($id = NULL, $type = NULL, $mobile = 'Market')
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
			$this->error('操作失败！');
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function invit($field = NULL, $name = NULL)
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

		$count = M('Invit')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Invit')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$coin_info = M('Coin')->field('id,title')->select();
		$coin_arr = array();
		foreach($coin_info as $arr){
			$coin_arr[$arr['id']] = $arr['title'];
		}
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['enname'] = M('User')->where(array('id' => $v['userid']))->getField('enname');
			$list[$k]['invit'] = M('User')->where(array('id' => $v['invit']))->getField('username');
			$list[$k]['inviten'] = M('User')->where(array('id' => $v['invit']))->getField('enname');
			$list[$k]['mum'] = $v['mum']*1;
			$list[$k]['fee'] = $v['fee']*1;
			if($v['name'] == 1){
				$list[$k]['name'] = "一代";
			}elseif($v['name'] == 2){
				$list[$k]['name'] = "二代";
			}elseif($v['name'] == 3){
				$list[$k]['name'] = "三代";
			}
			$list[$k]['type'] = $coin_arr[$v['type']];
			if($v['buysell'] == 1){
				$list[$k]['buysell'] = "卖广告";
			}elseif($v['buysell'] == 2){
				$list[$k]['buysell'] = "买广告";
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function tradeExcel(){
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
		// 处理搜索的数据=================================================

		$list = M('Trade')->where($where)->select();
		foreach ($list as $k => $v) {
			$list[$k]['addtime'] = addtime($v['addtime']);
			$list[$k]['userid'] = M('User')->where(array('id' => $v['userid']))->getField('username');

			if ($list[$k]['type'] == 1) {
				$list[$k]['type'] = '买入';
			}
			else {
				$list[$k]['type'] = '卖出';
			}
			if ($list[$k]['status'] == 0) {
				$list[$k]['status'] = '交易中';
			}
			elseif ($list[$k]['status'] == 1) {
				$list[$k]['status'] = '已成交';
			}
			elseif ($list[$k]['status'] == 2) {
				$list[$k]['status'] = '已撤销';
			}
		}

		$zd = M('Trade')->getDbFields();
		array_splice($zd, 9, 1);
		array_splice($zd, 10, 1);
		// array_splice($zd, 6, 1);
		// array_splice($zd, 7, 7);
		$xlsName = '委托记录';
		$xls = array();

		foreach ($zd as $k => $v) {
			$xls[$k][0] = $v;
			$xls[$k][1] = $v;
		}

		$xls[0][2] = 'ID';
		$xls[1][2] = '用户名';
		$xls[2][2] = '市场';
		$xls[3][2] = '单价';
		$xls[4][2] = '数量';
		$xls[5][2] = '已成交';
		$xls[6][2] = '总额';
		$xls[7][2] = '手续费';
		$xls[8][2] = '类型';
		$xls[9][2] = '时间';
		$xls[10][2] = '状态';

		$this->trade_exportExcel($xlsName, $xls, $list);
	}

	public function tradelogExcel(){
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
		// 处理搜索的数据=================================================

		$list = M('TradeLog')->where($where)->select();
		foreach ($list as $k => $v) {
			$list[$k]['addtime'] = addtime($v['addtime']);
			$list[$k]['userid'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			$list[$k]['peerid'] = M('User')->where(array('id' => $v['userid']))->getField('username');

			if ($list[$k]['type'] == 1) {
				$list[$k]['type'] = '买入';
			}
			else {
				$list[$k]['type'] = '卖出';
			}
		}

		$zd = M('TradeLog')->getDbFields();
		array_splice($zd, 10, 1);
		array_splice($zd, 11, 2);
		// array_splice($zd, 6, 1);
		// array_splice($zd, 7, 7);
		$xlsName = '成交记录';
		$xls = array();

		foreach ($zd as $k => $v) {
			$xls[$k][0] = $v;
			$xls[$k][1] = $v;
		}

		$xls[0][2] = 'ID';
		$xls[1][2] = '买家';
		$xls[2][2] = '卖家';
		$xls[3][2] = '市场';
		$xls[4][2] = '单价';
		$xls[5][2] = '数量';
		$xls[6][2] = '总额';
		$xls[7][2] = '买家手续费';
		$xls[8][2] = '卖家手续费';
		$xls[9][2] = '交易类型';
		$xls[10][2] = '时间';

		$this->trade_exportExcel($xlsName, $xls, $list);
	}

	public function ads($field = NULL, $name = NULL, $market = NULL, $status = NULL, $bs_type = NULL, $starttime = NULL, $endtime = NULL)
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

		if ($market) {
			$where['market'] = $market;
		}

		if ($status) {
			$where['status'] = $status - 1;
		}


		// 交易类型
		if ($bs_type) {
			$where['type'] = $bs_type;
		}


		// 时间--条件

		$time_type = 'addtime';

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


		$list_new = M('Trade')->where($where)->order('id desc')->select();

		$num_zong = 0;
		$num_cj_zong = 0;
		$money_zong = 0;
		$fee = 0;
		$tradeid = array();
		foreach ($list_new as $k => $v) {
			$num_zong += $v['num'];
			$num_cj_zong += $v['deal'];
			$money_zong += $v['mum'];
			$fee += $v['fee'];
			array_push($tradeid,$v['id']);
		}
		$this->assign('tradeid',implode(",",$tradeid));

		$count = M('Trade')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Trade')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}
		$datas = array();
		$datas['num_zong'] = $num_zong;
		$datas['num_cj_zong'] = $num_cj_zong;
		$datas['money_zong'] = $money_zong;
		$datas['fee'] = $fee;
		$this->assign('datas', $datas);
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	function addel($id,$market){

		$table1 = $market == "buy"?'AdBuy':'AdSell';
		$table2 = $market == "buy"?'OrderBuy':'OrderSell';
		$adid = $market == "buy"?"sell_sid":"buy_bid";

		$order = M($table2)->where(array($adid=>$id,'status'=>array('in','0,1,2,3,6')))->select();
		if($order){
			$this->error("还有未完成的订单");
		}else{
			$res = M($table1)->where("id={$id}")->delete();
			if($res){
				$this->success("删除成功");
			}
		}
	}
	
	public function admindone($id,$market){
		if(empty($id) || empty($market)){
			$this->error("缺少参数！");
		}
		if($market!='buy' && $market!='sell'){
			$this->error('参数错误！');
		}
		if($market=='buy'){
			$order = M('order_buy')->where(array('id'=>$id))->find();
			if(!empty($order)){
				if($order['adminhandle']==1){
					$rs = M('order_buy')->where(array('id'=>$id))->save(array('adminhandle'=>0));
					if(!empty($rs)){
						$this->success('操作成功！');
					}else{
						$this->error('操作失败！');
					}
				}else{
					$this->error('此订单没有申请管理员介入！');
				}
			}else{
				$this->error('找不到订单！');
			}
		}
		if($market=='sell'){
			$order = M('order_sell')->where(array('id'=>$id))->find();
			if(!empty($order)){
				if($order['adminhandle']==1){
					$rs = M('order_buy')->where(array('id'=>$id))->save(array('adminhandle'=>0));
					if(!empty($rs)){
						$this->success('操作成功！');
					}else{
						$this->error('操作失败！');
					}
				}else{
					$this->error('此订单没有申请管理员介入！');
				}
			}else{
				$this->error('找不到订单！');
			}
		}
	}
	
	public function analysis($coinname = NULL){
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
		$data['trance_fee'] = M('Myzc')->where(array('coinname' => $coinname))->sum('fee');
		$this->assign('data', $data);
		
		$addtime = time();
		$t = $addtime;
		$start = mktime(0, 0, 0, date('m', $t), date('d', $t), date('Y', $t));
		$end = mktime(23, 59, 59, date('m', $t), date('d', $t), date('Y', $t));

		if ($addtime) {
			$trade_num = M('UserCoin')->where(array(
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum($coinname);
			$trade_mum = M('UserCoin')->where(array(
				'addtime' => array(
					array('egt', $start),
					array('elt', $end)
					)
				))->sum($coinname . 'd');
			$aa = $trade_num + $trade_mum;

			if (C($coinname)['type'] == 'qbb') {
				$bb = $json['balance'];
			}
			else {
				$bb = 0;
			}

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
					)
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
}

?> 