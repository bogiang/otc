<?php
namespace Admin\Controller;

class GoodsController extends AdminController
{
	protected function _initialize(){
		parent::_initialize();
		$allow_action=array("index","order","edit","status","upsend");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("页面不存在！");
		}
	}
	
	public function index($name = NULL, $field = NULL, $status = NULL)
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
			$where['goods_status'] = $status - 1;
		}else{
			$where['goods_status'] = ['lt',2];
		}

		$count = M('goods')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('goods')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	public function order($name = NULL, $field = NULL)
	{
		$where = array();
		if ($field && $name) {
			if ($field == 'uname') {
				$where['uname'] =$name;
			}
			
		}
		$count = M('good_order')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('good_order')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $key => $v) {
			$list[$key]['goodname']=M('goods')->where(array('id'=>$v['goodid']))->getField('goods_name');
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function edit($id = NULL, $type = NULL)
	{	

		if (empty($_POST)) {
			

			if ($id) {
				$this->data = M('goods')->where(array('id' => trim($id)))->find();
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
			$_POST['goods_content']=$_POST['ueditorcontent'];
			unset($_POST['ueditorcontent']);
			$_POST['goods_img']=!empty($_POST['goods_img']) ? addslashes($_POST['goods_img']) : '';
			if ($_POST['id']) {
				$_POST['goods_img']= M('goods')->where(array('id'=>$_POST['id']))->getField('goods_img');
			}
			if ($_POST['goods_adtime']) {
				if (addtime(strtotime($_POST['goods_adtime'])) == '---') {
					$this->error('添加时间格式错误');
				}
				else {
					$_POST['goods_adtime'] = strtotime($_POST['goods_adtime']);
				}
			}
			else {
				$_POST['goods_adtime'] = time();
			}

			$upload = new \Think\Upload();//实列化上传类
        	$upload->maxSize = 3145728;//设置上传文件最大，大小
        	$upload->exts = array('jpg', 'gif', 'png', 'jpeg');//后缀
        	$upload->rootPath = './Upload/goods/';//上传目录
        	$upload->savePath = ''; // 设置附件上传（子）目录
        	$upload->autoSub = true;
        	$upload->subName = array('date', 'Ymd');
        	$upload->saveName = array('uniqid', '');//设置上传文件规则
        	$info = $upload->upload();//执行上传方法
        	
        
        	if (!$info) {
        	    // $this->error($upload->getError());
        	   
        	} else {
            $image = new \Think\Image();
            foreach ($info as $key => $file) {
                $image->open('./Upload/goods/' . $file['savepath'] . $file['savename']);
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
                $image->thumb($new_width, $new_height)->save('./Upload/goods/' . $file['savepath'] . "s_" . $file['savename']);
                unlink('./Upload/goods/' . $file['savepath'] . $file['savename']);
            	}
            	$_POST['goods_img'] =  '/Upload/goods/'.$info['goods_img']['savepath'] . "s_" . $info['goods_img']['savename'];
        	}
        	if(empty($_POST['goods_name'])){
        		$this->error('商品名称不能为空');
        	}
        	if(empty($_POST['goods_kc'])){
        		$this->error('商品库存不能为空');
        	}
        	if(empty($_POST['goods_price'])){
        		$this->error('请填写商品价格');
        	}
        	if(empty($_POST['goods_img'])){
        		$this->error('请上传商品图片');
        	}
			if ($_POST['id']) {
				$rs = M('goods')->save($_POST);
			}
			else {
				
				$rs = M('goods')->add($_POST);
			}

			if ($rs) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！');
			}
			
		}
	}
	public function upsend($id){
		if (empty($id)) {
			$this->error('参数错误！');
		}
		if(!check($id,"d")){
			$this->error('参数错误！');
		}
		if (M('good_order')->where(array('id'=>$id))->save(array('status'=>1))) {
			$this->success('操作成功！');
		}
		else {
			
			$this->error('操作失败！');
		}

	}
	public function status($id = NULL, $type = NULL, $mobile = 'goods')
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
			$data = array('goods_status' => 0);
			break;

		case 'resume':
			$data = array('goods_status' => 1);
			break;

		case 'repeal':
			$data = array('goods_status' => 2);
			break;

		case 'delete':
			$data = array('goods_status' => -1);
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

}

?>