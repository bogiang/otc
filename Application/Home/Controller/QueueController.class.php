<?php
namespace Home\Controller;

class QueueController extends HomeController
{

	public function paicuo()
	{
		$this_miniute = date("i");
		if(!is_file("./Database/lastzc.txt")){
			if($this_miniute%4==0){
				file_put_contents("./Database/lastzc.txt",time());
			}
		}
		echo 'ok';
	}

	public function qianbao()
	{
		$coinList = M('Coin')->where(array('status' => 1,'type'=>'qbb','tp_qj'=>'btc','name'=>array('neq','usdt')))->select();
		$time = time();
		if(!empty($coinList)){
			foreach ($coinList as $k => $v) {
				$coin = $v['name'];
				if (empty($coin)) {
					continue;
				}

				$dj_username = C('coin')[$coin]['dj_yh'];
				$dj_password = C('coin')[$coin]['dj_mm'];
				$dj_address = C('coin')[$coin]['dj_zj'];
				$dj_port = C('coin')[$coin]['dj_dk'];
				$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
				$json = $CoinClient->getwalletinfo();
				if (!isset($json['walletversion']) || !$json['walletversion']) {
					continue;
				}

				$listtransactions = $CoinClient->listtransactions('*', 5000, 0);
				if($listtransactions!="nodata"){
					krsort($listtransactions);
					foreach ($listtransactions as $trans) {
						if (!$trans['account']) {
							continue;
						}

						if (!($user = M('User')->where(array('username' => $trans['account']))->find())) {
							continue;
						}

						if (M('Myzr')->where(array('txid' => $trans['txid'], 'status' => '1', 'username'=>$trans['address']))->find()) {
							continue;
						}

						if ($trans['category'] == 'receive') {
							$sfee = 0;
							$true_amount = $trans['amount'];

							if (C('coin')[$coin]['zr_zs']) {
								$song = round(($trans['amount'] / 100) * C('coin')[$coin]['zr_zs'], 8);
								if ($song) {
									$sfee = $song;
									$trans['amount'] = $trans['amount'] + $song;
								}
							}
							try{
								if ($trans['confirmations'] < C('coin')[$coin]['zr_dz']) {
									if ($res = M('myzr')->where(array('txid' => $trans['txid'],'username'=>$trans['address']))->find()) {
										M('myzr')->save(array('id' => $res['id'], 'addtime' => $trans['time'], 'status' => intval($trans['confirmations'] - C('coin')[$coin]['zr_dz'])));
									}
									else {
										M('myzr')->add(array('userid' => $user['id'], 'username' => $trans['address'], 'coinname' => $coin, 'fee' => $sfee, 'txid' => $trans['txid'], 'num' => $true_amount, 'mum' => $trans['amount'], 'addtime' => $trans['time'], 'status' => intval($trans['confirmations'] - C('coin')[$coin]['zr_dz'])));
									}
									continue;
								}
							
								$mo = M();
								$mo->startTrans();
								
								$rs = array();

								if ($res = $mo->table('tw_myzr')->where(array('txid' => $trans['txid'],'username'=>$trans['address'],'status'=>array('neq',1)))->find()) {
									$rs[] = $mo->table('tw_myzr')->save(array('id' => $res['id'], 'addtime' => $time, 'status' => 1));
								}
								else {
									$rs[] = $mo->table('tw_myzr')->add(array('userid' => $user['id'], 'username' => $trans['address'], 'coinname' => $coin, 'fee' => $sfee, 'txid' => $trans['txid'], 'num' => $true_amount, 'mum' => $trans['amount'], 'addtime' => $time, 'status' => 1));
								}

								$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $user['id']))->setInc($coin, $trans['amount']);
								if (check_arr($rs)) {
									$mo->commit();
								}
								else {
									throw new \Think\Exception('write databses fail');
								}
							}catch(\Think\Exception $e){
								file_put_contents("./Database/zrdebug.txt"," - ".$trans['txid']."|".$time." + ",FILE_APPEND);
								$mo->rollback();
							}
						}
					}
				}
				usleep(100000);
			}
		}
	}
	
	public function usdtqb()
	{
		$time = time();
		$coin = 'usdt';
		$dj_username = C('coin')[$coin]['dj_yh'];
		$dj_password = C('coin')[$coin]['dj_mm'];
		$dj_address = C('coin')[$coin]['dj_zj'];
		$dj_port = C('coin')[$coin]['dj_dk'];
		$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
		$json = $CoinClient->getnetworkinfo();

		if (!isset($json['version']) || !$json['version']) {
			continue;
		}

		$listtransactions = $CoinClient->omni_listtransactions('*', 5000, 0);
		if($listtransactions!="nodata"){
			krsort($listtransactions);
			foreach ($listtransactions as $trans) {
				if (!$trans['referenceaddress']) {
					continue;
				}

				$user_coin = M()->table('tw_user_coin')->where(array('usdtb'=>$trans['referenceaddress']))->find();
				if(empty($user_coin)){
					continue;
				}
				
				$myzr = M()->table('tw_myzr')->where(array('txid' => $trans['txid'], 'status' => '1', 'username'=>$trans['referenceaddress']))->find();
				if (!empty($myzr)) {
					continue;
				}
				
				if ($trans['type'] == 'Simple Send' && $trans['ismine']) {
					$sfee = 0;
					$true_amount = $trans['amount'];
					/*if (C('coin')[$coin]['zr_zs']) {
						$song = round(($trans['amount'] / 100) * C('coin')[$coin]['zr_zs'], 8);
						if ($song) {
							$sfee = $song;
							$trans['amount'] = $trans['amount'] + $song;
						}
					}*/
					try{
						if ($trans['confirmations'] < C('coin')[$coin]['zr_dz']) {
							if ($res = M()->table('tw_myzr')->where(array('txid' => $trans['txid'], 'username'=>$trans['referenceaddress']))->find()) {
								M()->table('tw_myzr')->save(array('id' => $res['id'], 'addtime' => $time, 'status' => intval($trans['confirmations'] - C('coin')[$coin]['zr_dz'])));
							}
							else {
								M()->table('tw_myzr')->add(array('userid' => $user_coin['userid'], 'username' => $trans['referenceaddress'], 'coinname' => $coin, 'fee' => $sfee, 'txid' => $trans['txid'], 'num' => $true_amount, 'mum' => $true_amount, 'addtime' => $time, 'status' => intval($trans['confirmations'] - C('coin')[$coin]['zr_dz'])));
							}
							continue;
						}
					
						$mo = M();
						$mo->startTrans();						
						$rs = array();
						$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $user_coin['userid']))->setInc($coin, $true_amount);

						if ($res = $mo->table('tw_myzr')->where(array('txid' => $trans['txid'], 'username'=>$trans['referenceaddress']))->find()) {
							$rs[] = $mo->table('tw_myzr')->save(array('id' => $res['id'], 'addtime' => $time, 'status' => 1));
						}
						else {
							$rs[] = $mo->table('tw_myzr')->add(array('userid' => $user_coin['userid'], 'username' => $trans['referenceaddress'], 'coinname' => $coin, 'fee' => $sfee, 'txid' => $trans['txid'], 'num' => $true_amount, 'mum' => $true_amount, 'addtime' => $time, 'status' => 1));
						}

						if (check_arr($rs)) {
							$mo->commit();
						}
						else {
							throw new \Think\Exception('write databses fail');
						}
					}catch(\Think\Exception $e){
						file_put_contents("./Database/zrdebug.txt"," - ".$trans['txid']."|".$time." + ",FILE_APPEND);
						$mo->rollback();
					}
				}
			}
		}
	}
	
	public function myzcQueue()
	{
		if(!is_file("./Database/lastzc.txt")){
			return;
		}
		$last_run = file_get_contents("./Database/lastzc.txt");
		if(time()-$last_run<10){
			return;
		}
		file_put_contents("./Database/lastzc.txt",time());
		
		$myzc = M('Myzc')->where(array('status' => 0,'cointype'=>1))->order('addtime asc')->limit(10)->select();

		if (empty($myzc)) {
			return;
		}
		
		foreach($myzc as $val){
			if ($val['status'] != 0) {
				continue;
			}

			$username = M('User')->where(array('id' => $val['userid']))->getField('username');
			$coin = $val['coinname'];
			$dj_username = C('coin')[$coin]['dj_yh_mytx'];
			$dj_password = C('coin')[$coin]['dj_mm_mytx'];
			$dj_address = C('coin')[$coin]['dj_zj_mytx'];
			$dj_port = C('coin')[$coin]['dj_dk_mytx'];
			$CoinClient = CoinClient($dj_username, $dj_password, $dj_address, $dj_port, 5, array(), 1);
			$json = $CoinClient->getwalletinfo();

			if (!isset($json['walletversion']) || !$json['walletversion']) {
				echo '钱包链接失败！';
				continue;
			}

			$Coins = M('Coin')->where(array('name' => $val['coinname']))->find();
			if(empty($Coins['sh_zd'])){
				continue;
			}
			if(empty($Coins['status'])){
				continue;
			}

			//单笔提现审核金额 C('single_withdraw'); 如果没设置也是人工审核
            if (empty(C('single_withdraw'))){
                continue;
            }
            $coin_info = M("{$coin}")->where(array('short_name' => 'CNY'))->find();
			$all_zc_num = $val['num'] * $coin_info['price'];
			if ($all_zc_num > C('single_withdraw')){
			    continue;
            }

			
			$user_coin = M('UserCoin')->where(array('userid' => $val['userid']))->find();
			
			$mo = M();
			$mo->startTrans();
			$rs = array();

			if (0 < $val['fee']) {
				$rs[] = $mo->table('tw_myzc_fee')->add(array('userid' => $val['userid'], 'username' => $username, 'coinname' => $coin, 'num' => $val['num'], 'fee' => $val['fee'], 'mum' => $val['mum'], 'type' => 2, 'addtime' => time(), 'status' => 1));
			}

			$rs[] = $mo->table('tw_myzc')->where(array('id' => $val['id']))->save(array('status' => 1));

			if (check_arr($rs)) {
				$sendrs = $CoinClient->sendtoaddress($val['username'], (double) $val['mum']);
				if ($sendrs) {
					$mo->table('tw_myzc')->where(array('id'=>$val['id']))->save(array('txid'=>$sendrs));
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
					unlink("./Database/lastzc.txt");
					exit;
				}
				else {
					$mo->commit();
					echo '转账成功！';
				}
			}
			else {
				$mo->rollback();
				unlink("./Database/lastzc.txt");
				exit;
			}
		}
	}
	
	public function huilvgenxin(){
		$time=time();
		$currency = array('USD','CNY','AUD','JPY','KRW','CAD','CHF','INR','RUB','EUR','GBP','HKD','BRL','IDR','MXN','TWD','MYR','SGD');
		for($i=0;$i<count($currency);$i++){
			$jiekou[$i] = file_get_contents("https://api.coinmarketcap.com/v2/ticker/?convert=".strtolower($currency[$i])."&limit=11");
			$biarr[$i] = json_decode($jiekou[$i],true);
			foreach($biarr[$i]['data'] as $k=>$v){
				if(!empty($v['symbol'])){
					$coin = strtolower($v['symbol']);
					if(in_array($coin,array("btc","eth","usdt"))){
						$record = M($coin)->where(array('short_name'=>$currency[$i]))->find();
						if(!empty($record)){
							$new_price = round($v['quotes'][$currency[$i]]['price'],8);
							if(!empty($new_price) && $new_price != $record['price']){
								M($coin)->where(array('short_name'=>$currency[$i]))->save(array('price'=>$new_price,'updatetime'=>$time));
							}
						}
					}
				}
			}
			usleep(2000000);
		}
	}
	
	public function haopinglv(){
		$order_buy = M('order_buy')->where(array('status'=>3,'finished_time'=>array('lt',(time()-24*60*60))))->select();
		if(!empty($order_buy)){
			foreach($order_buy as $ob){
				if($ob['buy_pj'] == 0){
					try{
						$mo=M();
						$mo->startTrans();
						//买家给好评
						$rs[] = $mo->table('tw_order_buy')->where(array('id'=>$ob['id']))->save(array('buy_pj'=>1));
						//卖家好评数加1
						$rs[] = $mo->table('tw_user')->where(array('id'=>$ob['sell_id']))->setInc('goodnum',1);
						$seller = $mo->table("tw_user")->where(array('id'=>$ob['sell_id']))->find();
						$hpv=intval($seller['goodnum']/$seller['transact']*100);
						if($seller['goodcomm']!=$hpv){
							//更新卖家好评率
							$rs[] = $mo->table('tw_user')->where(array('id'=>$ob['sell_id']))->setField('goodcomm',$hpv);
						}
						$order = $mo->table('tw_order_buy')->where(array('id'=>$ob['id']))->find();
						if($order['buy_pj']>0 && $order['sell_pj']>0){
							$module = D('Chat');
							$rs[]=$mo->table('tw_order_buy')->where(array('id'=>$order['id']))->save(array('status'=>4));
							$condition=array();
							$condition['orderid'] = $order['id'];
							$condition['ordertype'] = 1;
							$condition['status'] = 0;
							$condition['isfinished'] = 0;
							$update = array();
							$update['isfinished'] = 1;
							$module->updateChat($condition,$update);
						}
						if (check_arr($rs)) {
							$mo->commit();
						}
						else {
							throw new \Think\Exception('评价失败！');
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						continue;
					}
				}
				if($ob['sell_pj'] == 0){
					try{
						$mo=M();
						$mo->startTrans();
						//卖家给好评
						$rs[] = $mo->table('tw_order_buy')->where(array('id'=>$ob['id']))->save(array('sell_pj'=>1));
						//买家好评数加1
						$rs[] = $mo->table('tw_user')->where(array('id'=>$ob['buy_id']))->setInc('goodnum',1);
						$buyer = $mo->table("tw_user")->where(array('id'=>$ob['buy_id']))->find();
						$hpv=intval($buyer['goodnum']/$buyer['transact']*100);
						if($buyer['goodcomm']!=$hpv){
							//更新买家好评率
							$rs[] = $mo->table('tw_user')->where(array('id'=>$ob['buy_id']))->setField('goodcomm',$hpv);
						}
						$order = $mo->table('tw_order_buy')->where(array('id'=>$ob['id']))->find();
						if($order['buy_pj']>0 && $order['sell_pj']>0){
							$module = D('Chat');
							$rs[]=$mo->table('tw_order_buy')->where(array('id'=>$order['id']))->save(array('status'=>4));
							$condition=array();
							$condition['orderid'] = $order['id'];
							$condition['ordertype'] = 1;
							$condition['status'] = 0;
							$condition['isfinished'] = 0;
							$update = array();
							$update['isfinished'] = 1;
							$module->updateChat($condition,$update);
						}
						if (check_arr($rs)) {
							$mo->commit();
						}
						else {
							throw new \Think\Exception('评价失败！');
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						continue;
					}
				}
			}
		}
		$order_sell = M('order_sell')->where(array('status'=>3,'finished_time'=>array('lt',(time()-24*60*60))))->select();
		if(!empty($order_sell)){
			foreach($order_sell as $ob){
				if($ob['buy_pj'] == 0){
					try{
						$mo=M();
						$mo->startTrans();
						//买家给好评
						$rs[] = $mo->table('tw_order_sell')->where(array('id'=>$ob['id']))->save(array('buy_pj'=>1));
						//卖家好评数加1
						$rs[] = $mo->table('tw_user')->where(array('id'=>$ob['sell_id']))->setInc('goodnum',1);
						$seller = $mo->table("tw_user")->where(array('id'=>$ob['sell_id']))->find();
						$hpv=intval($seller['goodnum']/$seller['transact']*100);
						if($seller['goodcomm']!=$hpv){
							//更新卖家好评率
							$rs[] = $mo->table('tw_user')->where(array('id'=>$ob['sell_id']))->setField('goodcomm',$hpv);
						}
						$order = $mo->table('tw_order_sell')->where(array('id'=>$ob['id']))->find();
						if($order['buy_pj']>0 && $order['sell_pj']>0){
							$module = D('Chat');
							$rs[]=$mo->table('tw_order_sell')->where(array('id'=>$order['id']))->save(array('status'=>4));
							$condition=array();
							$condition['orderid'] = $order['id'];
							$condition['ordertype'] = 2;
							$condition['status'] = 0;
							$condition['isfinished'] = 0;
							$update = array();
							$update['isfinished'] = 1;
							$module->updateChat($condition,$update);
						}
						if (check_arr($rs)) {
							$mo->commit();
						}
						else {
							throw new \Think\Exception('评价失败！');
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						continue;
					}
				}
				if($ob['sell_pj'] == 0){
					try{
						$mo=M();
						$mo->startTrans();
						//卖家给好评
						$rs[] = $mo->table('tw_order_sell')->where(array('id'=>$ob['id']))->save(array('sell_pj'=>1));
						//买家好评数加1
						$rs[] = $mo->table('tw_user')->where(array('id'=>$ob['buy_id']))->setInc('goodnum',1);
						$buyer = $mo->table("tw_user")->where(array('id'=>$ob['buy_id']))->find();
						$hpv=intval($buyer['goodnum']/$buyer['transact']*100);
						if($buyer['goodcomm']!=$hpv){
							//更新买家好评率
							$rs[] = $mo->table('tw_user')->where(array('id'=>$ob['buy_id']))->setField('goodcomm',$hpv);
						}
						$order = $mo->table('tw_order_sell')->where(array('id'=>$ob['id']))->find();
						if($order['buy_pj']>0 && $order['sell_pj']>0){
							$module = D('Chat');
							$rs[]=$mo->table('tw_order_sell')->where(array('id'=>$order['id']))->save(array('status'=>4));
							$condition=array();
							$condition['orderid'] = $order['id'];
							$condition['ordertype'] = 2;
							$condition['status'] = 0;
							$condition['isfinished'] = 0;
							$update = array();
							$update['isfinished'] = 1;
							$module->updateChat($condition,$update);
						}
						if (check_arr($rs)) {
							$mo->commit();
						}
						else {
							throw new \Think\Exception('评价失败！');
						}
					}catch(\Think\Exception $e){
						$mo->rollback();
						continue;
					}
				}
			}
		}
	}

	//order_buy  超时不付款的自动取消  卖家冻结的币返还
	public function buydaojishi(){
		$list=M('order_buy')->where("".time()."-ctime>ltime*60 and status=0 ")->select();
		if(!$list){
			return;
		}
		foreach ($list as $key => $vv) {
			try{
				$mo = M();
				$mo->startTrans();
				$real_number = ($vv['deal_num']+$vv['fee'])*1;
				$coin_name = $mo->table('tw_coin')->where(array('id'=>$vv['deal_coin']))->getField('name');
				$table = "tw_".$coin_name."_log";
				$rs[] = $mo->table('tw_user_coin')->where("userid=".$vv['sell_id']." and ".$coin_name."d >=".$real_number)->setDec($coin_name.'d', $real_number);
				$seller = $mo->table('tw_user')->where(array('id'=>$vv['sell_id']))->find();
				$rs[] = $mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$vv['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>0,'amount'=>$real_number,'desc'=>'买家超时不付款订单取消卖家减冻结'.strtoupper($coin_name),'operator'=>0,'ctype'=>2,'action'=>6,'addip'=>get_client_ip()));
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$vv['sell_id']))->setInc($coin_name, $real_number);
				$rs[] = $mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$vv['sell_id'],'ctime'=>time(),'type'=>1,'plusminus'=>1,'amount'=>$real_number,'desc'=>'买家超时不付款订单取消卖家加可用'.strtoupper($coin_name),'operator'=>0,'ctype'=>1,'action'=>6,'addip'=>get_client_ip()));
                $rs[]=$mo->table('tw_order_buy')->where(array('id'=>$vv['id']))->save(array('status'=>5,'cancle_op'=>1));
				if(check_arr($rs)){
					$mo->commit();
				}else {
					throw new \Think\Exception('取消订单失败！');
				}
			}catch(\Think\Exception $e){
				$mo->rollback();
				continue;
			}
		}
	}
	//order_sell  超时不付款的自动取消  卖家冻结的币返还
	public function selldaojishi(){
		$list=M('order_sell')->where("".time()."-ctime>ltime*60 and status=0 ")->select();
		if(!$list){
			return;
		}
		foreach ($list as $key => $vv) {
			try{
				$mo = M();
				$mo->startTrans();
				$coin_name = $mo->table('tw_coin')->where(array('id'=>$vv['deal_coin']))->getField('name');
				$table = "tw_".$coin_name."_log";
				$rs[] = $mo->table('tw_user_coin')->where("userid=".$vv['sell_id']." and btcd >0 ")->setDec('btcd', $vv['deal_num']);
				$seller = $mo->table('tw_user')->where(array('id'=>$vv['sell_id']))->find();
				$rs[] = $mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$vv['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>0,'amount'=>$vv['deal_num'],'desc'=>'买家超时不付款订单取消卖家减冻结'.strtoupper($coin_name),'operator'=>0,'ctype'=>2,'action'=>6,'addip'=>get_client_ip()));
                $rs[] = $mo->table('tw_user_coin')->where(array('userid' =>$vv['sell_id']))->setInc('btc', $vv['deal_num']);
				$rs[] = $mo->table($table)->add(array('username'=>$seller['username'],'userid'=>$vv['sell_id'],'ctime'=>time(),'type'=>2,'plusminus'=>1,'amount'=>$vv['deal_num'],'desc'=>'买家超时不付款订单取消卖家加可用'.strtoupper($coin_name),'operator'=>0,'ctype'=>1,'action'=>6,'addip'=>get_client_ip()));
                $rs[]=$mo->table('tw_order_sell')->where(array('id'=>$vv['id']))->save(array('status'=>5,'cancle_op'=>1));
				if(check_arr($rs)){
					$mo->commit();
				}else {
					throw new \Think\Exception('取消订单失败！');
				}
			}catch(\Think\Exception $e){
				$mo->rollback();
				continue;
			}
		}
	}
	
	public function ethzr(){
		//写入转入记录
		$time = time();
		$record = M()->db(0)->table('tw_ethzr')->where(array('finished'=>0))->select();
		if(!empty($record)){
			foreach($record as $re){
				if(!empty($re['toaddress'])){
					$user_coin = M()->db(0)->table('tw_user_coin')->where(array('ethb'=>$re['toaddress']))->find();
					if(!empty($user_coin)){
						try{
							$mo = M()->db(0);
							$mo->startTrans();
							
							$rs = array();

							$rs[] = $mo->table('tw_myzr')->add(array('userid' => $user_coin['userid'], 'username' => $re['fromaddress'], 'coinname' => 'eth', 'fee' => 0, 'txid' => $re['transaction_hash'], 'num' => $re['amount'], 'mum' => $re['amount'], 'addtime' => $time, 'status' => 1));
							
							$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $user_coin['userid']))->setInc('eth', $re['amount']);
							
							$rs[] = $mo->table('tw_ethzr')->where(array('id'=>$re['id']))->save(array('finished'=>1));

							if (check_arr($rs)) {
								$mo->commit();
							}
							else {
								throw new \Think\Exception('write databses fail');
							}
						}catch(\Think\Exception $e){
							file_put_contents("./Database/dtczrdebug.txt"," - ".implode("#",$rs)." | ".$e->getMessage()." | ".$time." + ",FILE_APPEND);
							$mo->rollback();
						}
					}else{
						M()->db(0)->table('tw_ethzr')->where(array('id'=>$re['id']))->save(array('finished'=>1));
					}
				}
			}
		}
		//读取转入记录
		$dtczr = M()->db(0)->table('tw_ethzr')->order('id desc')->find();
		$start = !empty($dtczr['otherid']) ? $dtczr['otherid'] : 0;
		$list = M()->db(2,'DB_eth')->table('transactions')->where(array('id'=>array('gt',$start),'amount'=>array('gt',0)))->limit(10000)->select();
		if(!empty($list)){
			$mo = M()->db(0);
			$mo->startTrans();
			$rs = array();
			$i=1;
			foreach($list as $val){
				$rs[] = $mo->table('tw_ethzr')->add(array('fromaddress'=>$val['from'],'toaddress'=>$val['to'],'amount'=>$val['amount'],'addtime'=>$time,'orderno'=>($start+$i),'finished'=>0,'otherid'=>$val['id'],'transaction_hash'=>$val['transaction_hash'],'block_hash'=>$val['block_hash']));
				$i++;
			}
			if(check_arr($rs)){
				$mo->commit();
			}else{
				$mo->rollback();
			}
		}		
	}
	
	public function erczr(){
		//写入转入记录
		$coin_list = M()->db(0)->table('tw_coin')->where(array('tp_qj'=>'erc20','status'=>1))->select();
		$time = time();
		$k=3;
		foreach($coin_list as $value){
			$coin = $value['name'];
			$record = M()->db(0)->table('tw_'.$coin.'zr')->where(array('finished'=>0))->select();
			if(!empty($record)){
				foreach($record as $re){
					if(!empty($re['toaddress'])){
						$user_coin = M()->db(0)->table('tw_user_coin')->where(array('ethb'=>$re['toaddress']))->find();
						if(!empty($user_coin)){
							try{
								$mo = M()->db(0);
								$mo->startTrans();
								
								$rs = array();

								$rs[] = $mo->table('tw_myzr')->add(array('userid' => $user_coin['userid'], 'username' => $re['fromaddress'], 'coinname' => $coin, 'fee' => 0, 'txid' => $re['transaction_hash'], 'num' => $re['amount'], 'mum' => $re['amount'], 'addtime' => $time, 'status' => 1));
								
								$rs[] = $mo->table('tw_user_coin')->where(array('userid' => $user_coin['userid']))->setInc($coin, $re['amount']);
								
								$rs[] = $mo->table('tw_'.$coin.'zr')->where(array('id'=>$re['id']))->save(array('finished'=>1));

								if (check_arr($rs)) {
									$mo->commit();
								}
								else {
									throw new \Think\Exception('write databses fail');
								}
							}catch(\Think\Exception $e){
								file_put_contents("./Database/dtczrdebug.txt"," - ".implode("#",$rs)." | ".$e->getMessage()." | ".$time." + ",FILE_APPEND);
								$mo->rollback();
							}
						}else{
							M($coin.'zr')->where(array('id'=>$re['id']))->save(array('finished'=>1));
						}
					}
				}
			}
			//读取转入记录
			$dtczr = M()->db(0)->table('tw_'.$coin.'zr')->order('id desc')->find();
			$start = !empty($dtczr['otherid']) ? $dtczr['otherid'] : 0;
			$list = M()->db($k,'DB_'.$coin)->table('transactions')->where(array('id'=>array('gt',$start)))->select();
			if(!empty($list)){
				$mo = M()->db(0);
				$mo->startTrans();
				$rs = array();
				$i=1;
				foreach($list as $val){
					$rs[] = $mo->table('tw_'.$coin.'zr')->add(array('fromaddress'=>$val['from'],'toaddress'=>$val['to'],'amount'=>$val['amount'],'addtime'=>$time,'orderno'=>($start+$i),'finished'=>0,'otherid'=>$val['id'],'transaction_hash'=>$val['transaction_hash'],'block_hash'=>$val['block_hash']));
					$i++;
				}
				if(check_arr($rs)){
					$mo->commit();
				}else{
					$mo->rollback();
				}
			}
			$k++;
		}
	}
	
	public function ethscan(){
		Vendor("Etherscan.Etherscan","",".php");
		$scanobj = new \Etherscan('ZRP86SYTYPQHGDFE6TAEIDMAFWSZP31SC4');
		$address_list = M('UserCoin')->where("ethb !=''")->field('userid,ethb')->select();
		if(!empty($address_list)){
			$n = count($address_list);
			foreach(makeRange($n) as $i){
				$transactions = array();
				if(substr($address_list[$i]['ethb'],0,2) == '0x'){
					$lbres = M('Ethapi')->where(array('coinname'=>'eth','userid'=>$address_list[$i]['userid']))->max('blockNumber');
					$lastblock = intval($lbres);
					$transactions = $scanobj->transactionList($address_list[$i]['ethb'],'desc');
					if(!empty($transactions) && $transactions['status'] == 1 && !empty($transactions['result'])){
						foreach($transactions['result'] as $trans){
							if($trans['blockNumber'] > $lastblock){
								if($trans['to'] == $address_list[$i]['ethb'] && substr($trans['hash'],0,2) == "0x"){
									M('Ethapi')->add(array('userid'=>$address_list[$i]['userid'],'coinname'=>'eth','blockNumber'=>$trans['blockNumber'],'timeStamp'=>$trans['timeStamp'],'hash'=>$trans['hash'],'nonce'=>$trans['nonce'],'blockHash'=>$trans['blockHash'],'transactionIndex'=>$trans['transactionIndex'],'from'=>$trans['from'],'to'=>$trans['to'],'value'=>$trans['value'],'isError'=>$trans['isError'],'confirmations'=>$trans['confirmations'],'txreceipt_status'=>$trans['txreceipt_status']));
								}
							}elseif($trans['blockNumber'] == $lastblock){
								$repeat = M('Ethapi')->where(array('coinname'=>'eth','userid'=>$address_list[$i]['userid'],'blockNumber'=>$trans['blockNumber'],'hash'=>$trans['hash']))->field('id')->find();
								if(empty($repeat)){
									if($trans['to'] == $address_list[$i]['ethb'] && substr($trans['hash'],0,2) == "0x"){
										M('Ethapi')->add(array('userid'=>$address_list[$i]['userid'],'coinname'=>'eth','blockNumber'=>$trans['blockNumber'],'timeStamp'=>$trans['timeStamp'],'hash'=>$trans['hash'],'nonce'=>$trans['nonce'],'blockHash'=>$trans['blockHash'],'transactionIndex'=>$trans['transactionIndex'],'from'=>$trans['from'],'to'=>$trans['to'],'value'=>$trans['value'],'isError'=>$trans['isError'],'confirmations'=>$trans['confirmations'],'txreceipt_status'=>$trans['txreceipt_status']));
									}
								}
							}elseif($trans['blockNumber'] < $lastblock){
								break;
							}
						}
					}
				}
				usleep(1000000);
			}
		}
		
		$list = M('Ethapi')->where(array('ischecked'=>0,'timeStamp'=>array('lt',(time()-3600))))->select();
		if(!empty($list)){
			foreach($list as $k=>$v){
				$step1 = M('UserCoin')->where(array('ethb'=>$v['to']))->find();
				$step2 = M('Ethzr')->where(array('coinname'=>'eth','txid'=>$v['hash']))->find();
				if(!empty($step1) && empty($step2)){
					M('Ethapi')->where(array('id'=>$v['id']))->save(array('ischecked'=>1,'islost'=>1));
				}else{
					M('Ethapi')->where(array('id'=>$v['id']))->save(array('ischecked'=>1));
				}
			}
		}
	}
	
	public function ercscan(){
		Vendor("Etherscan.Etherscan","",".php");
		$scanobj = new \Etherscan('ZRP86SYTYPQHGDFE6TAEIDMAFWSZP31SC4');
		$coins = M('Coin')->where(array('status'=>1,'tp_qj'=>'erc20'))->select();
		$coin_list = array();
		foreach($coins as $cc){
			$coin_list[$cc['name']] = strtolower($cc['cs_jl']);
		}
		if(!empty($coin_list)){
			$address_list = M('UserCoin')->where(array('ethb'=>array('neq','')))->select();
			if(!empty($address_list)){
				$n = count($address_list);
				foreach(makeRange($n) as $i){
					$transactions = array();
					if(substr($address_list[$i]['ethb'],0,2) == '0x'){
						$lastblock = M('Ethapi')->where(array('coinname'=>array('neq','eth')))->max('blockNumber');
						$lastblock = intval($lastblock);
						$transactions = $scanobj->ercAllList($lastblock,$address_list[$i]['ethb'],'asc');
						if(!empty($transactions) && $transactions['status'] == 1 && !empty($transactions['result'])){
							foreach($transactions['result'] as $trans){
								if(!empty($trans['contractAddress'])){
									foreach($coin_list as $cname=>$contract){
										//echo $trans['contractAddress']."|".$contract."<br/>";
										if($trans['contractAddress'] == $contract){
											if($trans['blockNumber'] > $lastblock){
												if($trans['to'] == $address_list[$i]['ethb'] && substr($trans['hash'],0,2) == "0x"){
													M('Ethapi')->add(array('userid'=>$address_list[$i]['userid'],'coinname'=>$cname,'blockNumber'=>$trans['blockNumber'],'timeStamp'=>$trans['timeStamp'],'hash'=>$trans['hash'],'nonce'=>$trans['nonce'],'blockHash'=>$trans['blockHash'],'from'=>$trans['from'],'to'=>$trans['to'],'value'=>$trans['value'],'contractAddress'=>$trans['contractAddress'],'confirmations'=>$trans['confirmations'],'tokenSymbol'=>$trans['tokenSymbol'],'tokenDecimal'=>$trans['tokenDecimal']));
												}
											}elseif($trans['blockNumber'] == $lastblock){
												$repeat = M('Ethapi')->where(array('userid'=>$address_list[$i]['userid'],'blockNumber'=>$trans['blockNumber'],'hash'=>$trans['hash']))->find();
												if(empty($repeat)){
													if($trans['to'] == $address_list[$i]['ethb'] && substr($trans['hash'],0,2) == "0x"){
														M('Ethapi')->add(array('userid'=>$address_list[$i]['userid'],'coinname'=>$cname,'blockNumber'=>$trans['blockNumber'],'timeStamp'=>$trans['timeStamp'],'hash'=>$trans['hash'],'nonce'=>$trans['nonce'],'blockHash'=>$trans['blockHash'],'from'=>$trans['from'],'to'=>$trans['to'],'value'=>$trans['value'],'contractAddress'=>$trans['contractAddress'],'confirmations'=>$trans['confirmations'],'tokenSymbol'=>$trans['tokenSymbol'],'tokenDecimal'=>$trans['tokenDecimal']));
													}
												}
											}elseif($trans['blockNumber'] < $lastblock){
												break;
											}
										}
									}
								}
							}
						}
					}
					usleep(1000000);
				}
			}
			
			foreach($coin_list as $key=>$val){
				$list = M('Ethapi')->where(array('coinname'=>$key,'ischecked'=>0,'timeStamp'=>array('lt',(time()-3600))))->select();
				if(!empty($list)){
					foreach($list as $k=>$v){
						$step1 = M('UserCoin')->where(array('ethb'=>$v['to']))->find();
						$step2 = M($key.'zr')->where(array('transaction_hash'=>$v['hash'],'toaddress'=>$v['to'],'fromaddress'=>$v['from']))->find();
						if(!empty($step1) && empty($step2)){
							M('Ethapi')->where(array('id'=>$v['id']))->save(array('ischecked'=>1,'islost'=>1));
						}else{
							M('Ethapi')->where(array('id'=>$v['id']))->save(array('ischecked'=>1));
						}
					}
				}
			}
		}
	}
}
?>