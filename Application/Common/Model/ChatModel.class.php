<?php
namespace Common\Model;

class ChatModel extends \Think\Model
{
	protected $connection = 'DB_Chat';

	//根据订单查询聊天记录
	public function listbyOrderid($orderid,$ordertype,$status=0,$orderby='ASC',$offset=NULL)
	{
		if(empty($orderid) || empty($ordertype)){
			return array();
		}
		if(empty($offset)){
			$list = $this->table('tw_chat')->where(array('orderid'=>$orderid,'ordertype'=>$ordertype,'status'=>$status))->order('addtime '.$orderby)->select();
		}else{
			$list = $this->table('tw_chat')->where(array('orderid'=>$orderid,'ordertype'=>$ordertype,'status'=>$status))->order('addtime '.$orderby)->limit($offset,1)->select();
		}
		return $list;
	}
	//根据广告查询聊天记录
	public function listbyAdvid($advid,$advtype,$userid,$orderby='ASC',$offset=0){
		if(empty($advid) || !isset($advtype) || empty($userid)){
			return array();
		}
		if(empty($offset)){
			$list = $this->table('tw_chat')->where("advid=$advid and advtype=$advtype and (fromuid=$userid or touid=$userid) and isfinished=0")->order('addtime '.$orderby)->select();
		}else{
			$list = $this->table('tw_chat')->where("advid=$advid and advtype=$advtype and (fromuid=$userid or touid=$userid) and isfinished=0")->order('addtime '.$orderby)->limit($offset,1)->select();
		}
		return $list;
	}
	
	//添加一条记录
	public function addRecord($fromuid, $touid, $orderid, $ordertype, $status=0, $content='', $addon = NULL, $advid, $advtype){
		if(empty($fromuid) && empty($touid)){
			return 0;
		}
		if(empty($orderid) || empty($ordertype) || empty($advid) || !isset($advtype)){
			return 0;
		}
		$data = array();
		if(!empty($fromuid)){
			$data['fromuid'] = $fromuid;
		}
		if(!empty($touid)){
			$data['touid'] = $touid;
		}

		$data['addtime'] = time();
		$data['orderid'] = $orderid;
		$data['ordertype'] = $ordertype;
		$data['status'] = $status;
		$data['advid'] = $advid;
		$data['advtype'] = $advtype;
		$data['content'] = $content;
		if(!empty($addon)){
			$data['addon'] = $addon;
		}
		$data['isread'] = 0;
		$result = $this->table('tw_chat')->add($data);
		return $result;
	}
	
	//删除一条记录
	public function delRecord($id){
		if(empty($id)){
			return false;
		}
		$result = $this->table('tw_chat')->where(array('id'=>$id))->delete();
		return $result;
	}
	
	//根据用户id查询和他相关的聊天信息
	public function listbyUid($userid){
		if(empty($userid)){
			return false;
		}
		$condition = array();
		$condition['fromuid'] = $userid;
		$condition['touid'] = $userid;
		$condition['_logic'] = 'OR';
		$result = $this->table('tw_chat')->where($condition)->order('addtime asc')->select();
	}
	
	//查询用户未读信息数量
	public function listUnread($condition=array()){
		$condition['isread'] = 0;
		$result = $this->table('tw_chat')->where($condition)->count();
		return $result;
	}
	//修改聊天表中的记录
	public function updateChat($condition=array(),$update=array()){
		if(empty($update)){
			return 0;
		}
		$result = $this->table('tw_chat')->where($condition)->save($update);
		return $result;
	}
	//下单成功删除临时表
	public function deletetmp($bid,$sid,$type,$id,$advid){
		$where['buy_id']=$bid;
		$where['sell_id']=$sid;
		$where['ordertype']=$type;
		if($type == 1){
			$where['sell_sid'] = $advid;
		}elseif($type == 2){
			$where['buy_bid'] = $advid;
		}
		$tmp=M('order_temp')->where($where)->find();
		
		if($type==1){
			$aid = $tmp['sell_sid'];
			$adtype=1;
		}elseif($type==2){
			$aid = $tmp['buy_bid'];
			$adtype=0;
		}

		if($tmp){
			//更改聊天记录的id
			$update['orderid']=$id;
			$update['ordertype']=$type;
			$update['isfinished']=1;
			$update['status'] = 0;
			$this->table('tw_chat')->where(array('advid'=>$aid,'advtype'=>$adtype,'isfinished'=>0))->save($update);
			//然后删除订单
			M('order_temp')->where("id=".$tmp['id'])->delete();
		}
	}

}

?>