<?php
namespace Home\Controller;

class AjaxController extends HomeController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("getCurrency","showChat","orderChat","chatNum");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
	}

	public function getCurrency($id,$coin)
	{
		if(empty($id) || empty($coin)){
			$this->ajaxReturn(array());
		}
		if(!check($id,'integer') || !check($coin,'integer')){
			$this->ajaxReturn(array());
		}
		$table = M('Coin')->where(array('id'=>$coin))->getField('name');
		$currency_info = M($table)->where(array('id'=>$id))->find();
		$arr = array('pri'=>$currency_info['price'],'sname'=>$currency_info['short_name']);
		$this->ajaxReturn($arr);
	}
	
	public function showChat($advid,$advtype,$chatnum){
		if(!userid()){
			$this->ajaxReturn(array());
		}
		if(!check($advid,'integer') || !check($advtype,'integer') || !check($chatnum,'integer')){
			$this->ajaxReturn(array());
		}
		$module = D('Chat');
		$list = $module->listbyAdvid($advid,$advtype,userid(),"ASC",$chatnum);
		foreach($list as $k=>$v){
			$list[$k]['fromuser_img'] = headimg($v['fromuid']);
			$list[$k]['touser_img'] = headimg($v['touid']);
			if($v['touid'] == userid()){
				$module->updateChat(array('id'=>$v['id']),array('isread'=>1));
			}
		}
		$this->ajaxReturn($list);
	}
	
	public function orderChat($orderid,$ordertype,$chatnum,$status=0){
		if(!userid()){
			$this->ajaxReturn(array());
		}
		if(empty($orderid) || empty($ordertype)){
			$this->ajaxReturn(array());
		}
		if(!check($orderid,'integer') || !check($ordertype,'integer') || !check($chatnum,'integer') || !check($status,'integer')){
			$this->ajaxReturn(array());
		}
		$module = D('Chat');
		$list = $module->listbyOrderid($orderid,$ordertype,$status,'ASC',$chatnum);
		foreach($list as $k=>$v){
			$list[$k]['fromuser'] = headimg($v['fromuid']);
			$list[$k]['touser'] = headimg($v['touid']);

			
			$list[$k]['fromusername'] = Isheadimg($v['fromuid']);

			
			$list[$k]['tousername'] = Isheadimg($v['touid']);


		}
		$this->ajaxReturn($list);
	}
	
	public function chatNum($orderid,$ordertype,$status=0){
		if(!userid()){
			$this->ajaxReturn(array());
		}
		if(!check($orderid,'integer') || !check($ordertype,'integer') || !check($status,'integer')){
			$this->ajaxReturn(array());
		}
		$module = D('Chat');
		if(empty($orderid)){
			$condition['touid'] = userid();
			$number = $module->listUnread($condition);
		}else{
			$condition['orderid'] = $orderid;
			$condition['touid'] = userid();
			$condition['ordertype'] = $ordertype;
			$condition['status'] = $status;
			$number = $module->listUnread($condition);
		}
		$this->ajaxReturn(intval($number));
	}
}

?>