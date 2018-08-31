<?php

namespace Home\Controller;

class UserController extends HomeController
{
    protected function _initialize()
    {
        parent::_initialize();
        $allow_action = array("index", "security",'jianjie', "nameauth", "upauth", "password", "uppassword", "paypassword", "uppaypassword", "qianbao", "upqianbao", "delqianbao", "log", "upauth2", "idcard", 'upuserinfo', 'myad', 'trusted', 'coinlog', "mobile", "upmobile", "mytj", "mywd", "myjp", "follower", "mytjj", "skaccount", "upskaccount", "delskaccount", "skqrcode");
        if (!in_array(ACTION_NAME, $allow_action)) {
            $this->error("非法操作！");
        }
    }

    public function __construct()
    {
        parent::__construct();
        $display_action = array("index", "security", "nameauth", "password", "paypassword", "qianbao", "log", "myad","trusted",'coinlog',"mobile", "mytj", "mywd", "myjp", "mytjj");
        if (in_array(ACTION_NAME, $display_action)) {
            $this->common_content();
        }
    }
	
	public function idcard(){
		if(IS_POST){
			$upload = new \Think\Upload();//实列化上传类
			$upload->maxSize = 3145728;//设置上传文件最大，大小
			$upload->exts = array('jpg', 'gif', 'png', 'jpeg');//后缀
			$upload->rootPath = './Upload/lanch/idcard/';//上传目录
			$upload->savePath = ''; // 设置附件上传（子）目录
			$upload->autoSub = true;
			$upload->subName = array('date', 'Ymd');
			$upload->saveName = array('uniqid', '');//设置上传文件规则
			$info = $upload->upload();//执行上传方法
			if (!$info) {
				$this->error($upload->getError());
			} else {
				$image = new \Think\Image();
				foreach ($info as $key => $file) {
					$image->open('./Upload/lanch/idcard/' . $file['savepath'] . $file['savename']);
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
					$image->thumb($new_width, $new_height)->save('./Upload/lanch/idcard/' . $file['savepath'] . "s_" . $file['savename']);
					unlink('./Upload/lanch/idcard/' . $file['savepath'] . $file['savename']);
				}
				echo json_encode(array('name'=>$file['savename']));
			}
		}
	}
	
	public function mytjj(){
		if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);
		$where=array();
		$where['userid'] = userid();
		$count = M('reg_prize')->where($where)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = M('reg_prize')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$coin_arr = M('Coin')->where(array('status'=>1))->select();
		$coins = array();
		foreach($coin_arr as $arr){
			$coins[$arr['id']] = $arr['title'];
		}
		foreach($list as $k=>$v){
			$list[$k]['addtime'] = date("Y-m-d H:i:s",$v['addtime']);
			$list[$k]['cointype'] = $coins[$v['coinid']];
			$list[$k]['amount'] = $v['amount']*1;
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function coinlog($coinname=NULL){
		if (!userid()) {
            redirect('/Login/index.html');
        }
		
		if(!empty($coinname) && !check($coinname,'xnb')){
			$this->error("参数错误！");
		}
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);
		
		if (!$coinname) {
            $coinname = $coinlist[0]['name'];
        }
        $this->assign('xnb', $coinname);

		$table = "tw_".$coinname."_log";
		$mo=M();
		$res = $mo->query("select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA='txierwa' and TABLE_NAME='".$table."'");
		if(empty($res)){
            $this->display();
            exit;
			$this->error("数据不存在");
		}
		$where=array();
		$where['userid'] = userid();
		$count = $mo->table($table)->where($where)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = $mo->table($table)->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k=>$v){
			$list[$k]['ctime']=date("Y-m-d H:i:s",$v['ctime']);
			if($v['plusminus']==1){
				$list[$k]['jiajian'] = "加";
			}else{
				$list[$k]['jiajian'] = "减";
			}
			if($v['operator']==0){
				$list[$k]['operator']="系统";
			}elseif($v['operator']==1){
				$list[$k]['operator']="管理员";
			}elseif($v['operator']==userid()){
				$list[$k]['operator']="自己";
			}else{
				$list[$k]['operator']=M('User')->where(array('id'=>$v['operator']))->getField('enname');
			}
			$list[$k]['ctype'] = $v['ctype']==1 ? "可用".strtoupper($coinname) : "冻结".strtoupper($coinname);
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

    public function security(){
        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);
        $this->display();
    }
    public function feedback()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
        $this->display();
    }

    public function subfeedback()
    {
        if (!userid()) {
            $this->error("请先登录！");
        }
        $update = array();
        $upload = new \Think\Upload();//实列化上传类
        $upload->maxSize = 3145728;//设置上传文件最大，大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');//后缀
        $upload->rootPath = './Upload/lanch/pic/';//上传目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        $upload->autoSub = true;
        $upload->subName = array('date', 'Ymd');
        $upload->saveName = array('uniqid', '');//设置上传文件规则
        $info = $upload->upload();//执行上传方法
        if ($info) {
            $image = new \Think\Image();
            foreach ($info as $key => $file) {
                if (!empty($file)) {
                    $image->open('./Upload/lanch/pic/' . $file['savepath'] . $file['savename']);
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
                    $image->thumb($new_width, $new_height)->save('./Upload/lanch/pic/' . $file['savepath'] . "s_" . $file['savename']);
                    unlink('./Upload/lanch/pic/' . $file['savepath'] . $file['savename']);
                }
            }
        }
        if (!empty($info['attachone'])) {
            $update['attachone'] = "http://Worldcoin.oss-ap-southeast-1.aliyuncs.com/pic/" . $info['attachone']['savepath'] . "s_" . $info['attachone']['savename'];
        }
        if (!empty($info['attachtwo'])) {
            $update['attachtwo'] = "http://Worldcoin.oss-ap-southeast-1.aliyuncs.com/pic/" . $info['attachtwo']['savepath'] . "s_" . $info['attachtwo']['savename'];
        }

        $update['title'] = trim($_POST['title']);
        if (!preg_match('/^[\\x{4e00}-\\x{9fa5}0-9a-zA-Z，。？\s]{2,50}$/u', $update['title'])) {
            $this->error('标题只能写中英文数字和空格，长度2-50字！');
        }
        $update['content'] = trim($_POST['content']);
        if (!preg_match('/^[\\x{4e00}-\\x{9fa5}0-9a-zA-Z，。？\s]{2,200}$/u', $update['content'])) {
            $this->error('描述只能写中英文数字和空格，长度2-200字！');
        }
        if (!empty($_POST['txid'])) {
            $txid = trim($_POST['txid']);
            if (!preg_match('/^[a-zA-Z0-9]{10,100}$/u', $txid)) {
                $this->error('TxID格式错误！');
            }
            $update['txid'] = $txid;
        }
        $update['subject'] = $_POST['subject'];
        if (!in_array($update['subject'], array('用户注册', '人民币充值', '人民币提现', '虚拟币充值', '虚拟币提现', '虚拟币交易', '绑定安全措施', '修改账号资料', '被盗找回', 'API问题', '其它'))) {
            $this->error('问题类型错误！');
        }
        $time = time();
        $update['userid'] = userid();
        $update['username'] = username();
        $update['addtime'] = $time;
        $update['freshtime'] = $time;
        $update['userstatus'] = 0;
        $update['adminstatus'] = 1;
        $update['recordno'] = $time . userid();
        $result = M('Feedback')->add($update);
        if ($result) {
            $this->success('提交成功！', '/User/feedbacklist.html');
        } else {
            $this->error('提交失败！');
        }
    }

    public function feedbacklist()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
        $mo = M('Feedback');
        $where = array();
        $where['userid'] = userid();
        $count = $mo->where($where)->count();
        $Page = new \Think\Page($count, 15);
        $show = $Page->show();
        $list = $mo->where($where)->order('freshtime desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k => $v) {
            $list[$k]['addtime'] = date("Y-m-d H:i:s", $v['addtime']);
            $list[$k]['freshtime'] = !empty($v['freshtime']) ? date("Y-m-d H:i:s", $v['freshtime']) : "---";
            if (!empty($v['userstatus'])) {
                $list[$k]['status'] = "<span style='color:#e55600;'>有新回复</span>";
            } else {
                if (!empty($v['adminstatus'])) {
                    $list[$k]['status'] = "<span style='color:#e55600;'>等待管理员回复</span>";
                } else {
                    $list[$k]['status'] = "<span style='color:#e55600;'>---</span>";
                }
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function feedbackdetail($id)
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
        if (empty($id)) {
            $this->error('参数错误！');
        }
        if (!check($id, 'd')) {
            $this->error('参数错误！');
        }
        $feedback_record = M('Feedback')->where(array('id' => $id, 'userid' => userid()))->find();
        if (!empty($feedback_record)) {
            if (!empty($feedback_record['adminstatus'])) {
                $feedback_record['status'] = "<span style='color:#e55600;'>等待管理员回复</span>";
            } elseif (!empty($feedback_record['userstatus'])) {
                $feedback_record['status'] = "<span style='color:#e55600;'>有新回复</span>";
            } else {
                $feedback_record['status'] = "---";
            }
            $this->assign('feedback_record', $feedback_record);
            $feedback_reply = M('Feedback_reply')->where(array('fid' => $feedback_record['id']))->order('addtime asc')->select();
            if (!empty($feedback_reply)) {
                $this->assign('feedback_reply', $feedback_reply);
            }
            $mo = M();
            $mo->table('tw_feedback')->where(array('id' => $id))->save(array('userstatus' => 0));
        }
        $this->display();
    }

    public function addreply()
    {
        if (!userid()) {
            $this->error("请先登录！");
        }
        if (empty($_POST['fid'])) {
            $this->error("参数错误！");
        }
        if (empty($_POST['content'])) {
            $this->error("请填写回复内容！");
        }
        $data['fid'] = $_POST['fid'];
        if (!check($data['fid'], 'd')) {
            $this->error("参数错误！");
        }
        $feedback_record = M('Feedback')->where(array('id' => $data['fid']))->find();
        if (empty($feedback_record) || $feedback_record['userid'] != userid()) {
            $this->error("参数错误！");
        }
        $data['content'] = $_POST['content'];
        if (!preg_match('/^[\\x{4e00}-\\x{9fa5}0-9a-zA-Z，。？\s]{2,200}$/u', $data['content'])) {
            $this->error('回复只能写中英文数字和空格，长度2-200字！');
        }
        $data['userid'] = userid();
        $data['username'] = $feedback_record['username'];
        $data['addtime'] = time();
        $result = M('Feedback_reply')->add($data);
        if ($result) {
            $update['id'] = $_POST['fid'];
            $update['adminstatus'] = 1;
            $update['userstatus'] = 0;
            $update['freshtime'] = time();
            $update['isread'] = 0;
            $res = M('Feedback')->save($update);
            if (!$res) {
                $this->error("更新留言状态失败！");
            } else {
                $this->success("留言成功！", "/User/feedbackdetail.html?id=" . $feedback_record['id']);
            }
        } else {
            $this->error("留言失败！");
        }
    }

    public function index()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);
        $this->assign('trust', gettrust(userid()));
        $this->display();
    }

    public function nameauth()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        $arr = array('sfz' => '身份证', 'hz' => '护照');
        $idcardtype = array(
            array('sfz', '身份证'),
            array('hz', '护照'),
        );
        $this->assign('idcardtype', $idcardtype);

        $user = M('User')->where(array('id' => userid()))->find();
        $user['zhengjian'] = $arr[$user['zhengjian']];
        $this->assign('user', $user);
        $shiming = shiming($user['id']);
        $this->assign('shiming', $shiming);
        $this->display();
    }

    public function upauth()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
        $user = M('User')->where(array('id' => userid()))->find();

        $shiming = shiming($user['id']);
        if ($shiming > 0) {
            $this->error('您已提交过实名认证信息！');
        }
        $update = array();
        $update['truename'] = trim($_POST['truename']);
        if (!check($update['truename'], 'truename')) {
            $this->error('证件姓名格式错误！');
        }
        $update['idcard'] = trim($_POST['idcard']);
        if (!validation_filter_id_card($update['idcard']) && $_POST['zhengjian'] == 'sfz') {
            $this->error('证件输入格式错误！');
        }
        $useridcard = M('User')->where(array('idcard' => $update['idcard'],'id'=>array('neq',userid())))->find();
        if ($useridcard) {
            $this->error('此证件号已存在');
        }
        if (!validation_filter_id_gcard($update['idcard']) && $_POST['zhengjian'] == 'gsfz') {
            $this->error('证件输入格式错误！');
        }
        $update['zhengjian'] = $_POST['zhengjian'];
        if (!in_array($update['zhengjian'], array('sfz', 'gsfz', 'hz', 'qt'))) {
            $this->error('证件类型错误！');
        }

        $result = M('User')->where(array('id' => userid()))->save($update);
        if ($result) {
            $this->success('提交成功！');
        } else {
            $this->error('提交失败！');
        }
    }

    public function upauth2()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
        $user = M('User')->where(array('id' => userid()))->find();
        $shiming = shiming($user['id']);

		$update = array();
		$update['truename'] = trim($_POST['truename']);
		if (!check($update['truename'], 'truename')) {
			$this->error('证件姓名格式错误！');
		}

		$update['idcard'] = trim($_POST['idcard']);
        if (checkstr($update['idcard'])) {
            $this->error('证件输入信息错误！');
        }

		if (!validation_filter_id_card($update['idcard']) && $_POST['zhengjian'] == 'sfz') {
			// $this->error('证件输入格式错误！');
		}
		$useridcard = M('User')->where(array('idcard' => $update['idcard'],'id'=>array('neq',userid())))->find();
		if ($useridcard) {
			$this->error('此证件号已存在');
		}
		if (!validation_filter_id_gcard($update['idcard']) && $_POST['zhengjian'] == 'gsfz') {
			$this->error('证件输入格式错误！');
		}
		$update['zhengjian'] = $_POST['zhengjian'];
		if (!in_array($update['zhengjian'], array('sfz', 'hz'))) {
			$this->error('证件类型错误！');
		}
        if ($shiming > 1) {
            $this->error('您已经提交了证件照片！');
        }

        $update['is_agree'] = 0;
        $idcard_zheng = trim($_POST['idcard_zheng']);
        $idcard_fan = trim($_POST['idcard_fan']);
        $idcard_shouchi = trim($_POST['idcard_shouchi']);
		if(!preg_match("/^[A-Za-z0-9\.]+$/u",$idcard_zheng)){
			$this->error('请上传身份证正面照片！');
		}
		if(!preg_match("/^[A-Za-z0-9\.]+$/u",$idcard_fan)){
			$this->error('请上传身份证反面照片！');
		}
		if(!preg_match("/^[A-Za-z0-9\.]+$/u",$idcard_shouchi)){
			$this->error('请上传手持身份证照片！');
		}
		$date = date("Ymd");
		$update['idcard_zheng'] = '/Upload/lanch/idcard/'.$date.'/s_'.$idcard_zheng;
        $update['idcard_fan'] = '/Upload/lanch/idcard/'.$date.'/s_'.$idcard_fan;
        $update['idcard_shouchi'] = '/Upload/lanch/idcard/'.$date.'/s_'.$idcard_shouchi;
        $result = M('User')->where(array('id' => userid()))->save($update);
        if ($result) {
            $this->success('提交成功！');
        } else {
            $this->error('提交失败！');
        }
    }

    public function password()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);
		
        $mobile = M('User')->where(array('id' => userid()))->getField('mobile');
        $email = M('User')->where(array('id' => userid()))->getField('email');
        if (empty($mobile) && empty($email)) {
            $this->error("请先绑定手机或邮箱中的一种！");
        } elseif ($mobile) {
            $mobile = substr_replace($mobile, '****', 3, 4);
        }
        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);
        $this->assign('mobile', $mobile);

        $this->display();
    }

    public function mibao()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }

        $mobile = M('User')->where(array('id' => userid()))->getField('mobile');
        $email = M('User')->where(array('id' => userid()))->getField('email');
        if (empty($mobile) && empty($email)) {
            $this->error("请先绑定手机或邮箱中的一种！");
        }

        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);
        $this->display();
    }

    public function upmibao($chkstyle, $mobile_verify, $email_verify, $mibao_question, $mibao_answer, $new_mibao_question, $new_mibao_answer, $findpwd_mibao, $findpaypwd_mibao)
    {

        // 过滤非法字符----------------S

        if (checkstr($mibao_question) || checkstr($mibao_answer) || checkstr($new_mibao_question) || checkstr($new_mibao_answer)) {
            $this->error('您输入的信息有误！');
        }

        // 过滤非法字符----------------E

        if (!userid()) {
            $this->error('请先登录！');
        }

        if (empty($chkstyle) || (!empty($chkstyle) && $chkstyle != 'mobile' && $chkstyle != 'email')) {
            $this->error('请选择验证方式！');
        }

        if (empty($findpwd_mibao)) {
            $findpwd_mibao = 0;
        } else {
            $findpwd_mibao = 1;
        }

        if (empty($findpaypwd_mibao)) {
            $findpaypwd_mibao = 0;
        } else {
            $findpaypwd_mibao = 1;
        }

        if ($chkstyle == 'mobile' && !check($mobile_verify, 'd')) {
            $this->error('短信验证码格式错误！');
        }
        if ($chkstyle == 'email' && !check($email_verify, 'd')) {
            $this->error('邮箱验证码格式错误！');
        }

        $user = M('User')->where(array('id' => userid()))->find();

        if ($chkstyle == 'mobile' && $user['mobile'] != session('chkmobile')) {
            $this->error('短信验证码错误！');
        }

        if ($chkstyle == 'mobile' && $mobile_verify != session('mibao_verify')) {
            $this->error('短信验证码错误！');
        }

        if ($chkstyle == 'email' && $user['email'] != session('chkemail')) {
            $this->error('邮箱验证码错误！');
        }

        if ($chkstyle == 'email' && $email_verify != session('emailmibao_verify')) {
            $this->error('邮箱验证码错误！');
        }

        if (($user['mibao_question'] != '' || $user['mibao_question'] != NULL) && $user['mibao_question'] != $mibao_question) {

            $this->error('密保问题错误！');

        }

        if (($user['mibao_answer'] != '' || $user['mibao_answer'] != NULL) && $user['mibao_answer'] != $mibao_answer) {

            $this->error('密保答案错误！');

        }

        if (empty($user['mibao_question'])) {
            $rs = M('User')->where(array('id' => userid()))->save(array('mibao_question' => $new_mibao_question, 'mibao_answer' => $new_mibao_answer, 'findpwd_mibao' => $findpwd_mibao, 'findpaypwd_mibao' => $findpaypwd_mibao));
        } else {
            if (empty($new_mibao_answer)) {
                $rs = M('User')->where(array('id' => userid()))->save(array('findpwd_mibao' => $findpwd_mibao, 'findpaypwd_mibao' => $findpaypwd_mibao));
            } else {
                $rs = M('User')->where(array('id' => userid()))->save(array('mibao_question' => $new_mibao_question, 'mibao_answer' => $new_mibao_answer, 'findpwd_mibao' => $findpwd_mibao, 'findpaypwd_mibao' => $findpaypwd_mibao));
            }
        }

        if ($rs) {
            session('mibao_verify', null);
            session('chkmobile', null);
            session('emailmibao_verify', null);
            session('chkemail', null);
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    public function uppassword($mobile_verify, $oldpassword, $newpassword, $repassword, $chkstyle, $email_verify)
    {
        if (!userid()) {
            $this->error('请先登录！');
        }

        $user_info = M('user')->where(array('id' => userid()))->find();
        if ($chkstyle == 'mobile') {
            if (!check($mobile_verify, 'd')) {
                $this->error('短信验证码格式错误！');
            }
            if ($user_info['mobile'] != session('chkmobile')) {
                $this->error('短信验证码错误！');
            }

            if ($mobile_verify != session('pass_verify')) {
                $this->error('短信验证码错误！');
            }
        } elseif ($chkstyle == 'email') {
            if (!check($email_verify, 'd')) {
                $this->error('邮箱验证码格式错误！');
            }
            if ($user_info['email'] != session('chkemail')) {
                $this->error('邮箱验证码错误！');
            }

            if ($email_verify != session('emailpass_verify')) {
                $this->error('邮箱验证码错误！');
            }
        }

        if (!check($oldpassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if (strlen($newpassword) > 16 || strlen($newpassword) < 6) {

            $this->error('密码格式为6~16位，不含特殊符号！');

        }

        if (!check($newpassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if ($newpassword != $repassword) {
            $this->error('两次输入的密码不一致');
        }

        $password = M('User')->where(array('id' => userid()))->getField('password');
        $paypasswords = M('User')->where(array('id' => userid()))->getField('paypassword');

        if (md5($oldpassword) != $password) {
            $this->error('旧登录密码错误！');
        }

        if (md5($newpassword) == $paypasswords) {
            $this->error('登录密码不能和交易密码相同！');
        }

        if (md5($newpassword) == $password) {
            $this->error('新登录密码跟原密码相同，修改失败！');
        }

        $rs = M('User')->where(array('id' => userid()))->save(array('password' => md5($newpassword)));

        if ($rs) {
            session('pass_verify', null);
            session('chkmobile', null);
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    public function paypassword()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        $mobile = M('User')->where(array('id' => userid()))->getField('mobile');
        $email = M('User')->where(array('id' => userid()))->getField('email');
        if (empty($mobile) && empty($email)) {
            $this->error("请先绑定手机或邮箱中的一种！");
        }
        $paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');
        if ($mobile) {
            $mobile = substr_replace($mobile, '****', 3, 4);
        }
        if ($paypassword == NULL) {
            $paypwd = 0;
        } else {
            $paypwd = 1;
        }
        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);
        $this->assign('paypwd', $paypwd);
        $this->assign('mobile', $mobile);

        $this->display();
    }

    public function uppaypassword($mobile_verify, $oldpaypassword, $newpaypassword, $repaypassword, $email_verify, $chkstyle)
    {
        if (!userid()) {
            $this->error('请先登录！');
        }
        if (empty($chkstyle) || (!empty($chkstyle) && $chkstyle != 'mobile' && $chkstyle != 'email')) {
            $this->error('请选择验证方式！');
        }
        $user_info = M('user')->where(array('id' => userid()))->find();
        $orgempty = !empty($user_info['paypassword']) ? false : true;
        if ($chkstyle == 'mobile') {
            if ($user_info['mobile'] != session('chkmobile')) {
                $this->error('短信验证码错误！');
            }

            if (!check($mobile_verify, 'd')) {
                $this->error('短信验证码格式错误！');
            }

            if ($mobile_verify != session('paypass_verify')) {
                $this->error('短信验证码错误！');
            }
        } elseif ($chkstyle == 'email') {
            if ($user_info['email'] != session('chkemail')) {
                $this->error('邮箱验证码错误！');
            }

            if (!check($email_verify, 'd')) {
                $this->error('邮箱验证码格式错误！');
            }

            if ($email_verify != session('emailpaypwd_verify')) {
                $this->error('邮箱验证码错误！');
            }
        }

        if (($user_info['paypassword'] != '' || $user_info['paypassword'] != NULL) && (!check($oldpaypassword, 'password'))) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if (strlen($newpaypassword) > 16 || strlen($newpaypassword) < 6) {

            $this->error('密码格式为6~16位，不含特殊符号！');

        }

        if (!check($newpaypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if ($newpaypassword != $repaypassword) {
            $this->error('两次输入的密码不一致');
        }

        $user = M('User')->where(array('id' => userid()))->find();

        if (($user_info['paypassword'] != '' || $user_info['paypassword'] != NULL) && (md5($oldpaypassword) != $user['paypassword'])) {
            $this->error('旧交易密码错误！');
        }

        if (($user_info['paypassword'] != '' || $user_info['paypassword'] != NULL) && (md5($newpaypassword) == $user['paypassword'])) {
            $this->error('新交易密码跟原交易密码相同，修改失败！');
        }

        if (md5($newpaypassword) == $user['password']) {
            $this->error('交易密码不能和登录密码相同！');
        }

        $rs = M('User')->where(array('id' => userid()))->save(array('paypassword' => md5($newpaypassword)));

        if ($rs) {
            session('chkmobile', null);
            session('paypass_verify', null);
            if ($orgempty) {
                $this->success('设置成功');
            } else {
                $this->success('修改成功');
            }
        } else {
            $this->error('修改失败');
        }
    }

    public function ga()
    {
        if (empty($_POST)) {
            if (!userid()) {
                redirect('/Login/index.html');
            }
			
			$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
			$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
			$new_coinlist=array();
			foreach($coinlist as $k=>$v){
				$new_coinlist[$v['name']]['name'] = $v['name'];
				$new_coinlist[$v['name']]['img'] = $v['img'];
				$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
				$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
				$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
				$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
				$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
			}
			$this->assign('coin_list', $new_coinlist);

            $user = M('User')->where(array('id' => userid()))->find();
            $is_ga = ($user['ga'] ? 1 : 0);
            $this->assign('is_ga', $is_ga);
            
            if (!$is_ga) {
                $ga = new \Common\Ext\GoogleAuthenticator();
                $secret = $ga->createSecret();
                session('secret', $secret);
                $this->assign('Asecret', $secret);
                $zhanghu = $user['username'] . ' - ' . $_SERVER['HTTP_HOST'];
                $this->assign('zhanghu', $zhanghu);
                $qrCodeUrl = $ga->getQRCodeGoogleUrl($user['username'] . '%20-%20' . $_SERVER['HTTP_HOST'], $secret);
                $this->assign('qrCodeUrl', $qrCodeUrl);
                $this->display();
            } else {
                $arr = explode('|', $user['ga']);
                $this->assign('ga_login', $arr[1]);
                $this->assign('ga_transfer', $arr[2]);
                $this->display();
            }
        } else {
            foreach ($_POST as $k => $v) {
                // 过滤非法字符----------------S

                if (checkstr($v)) {
                    $this->error('您输入的信息有误！');
                }

                // 过滤非法字符----------------E
            }

            if (!userid()) {
                $this->error('登录已经失效,请重新登录!');
            }

            $delete = '';
            $gacode = trim(I('ga'));
            $type = trim(I('type'));
            $ga_login = (I('ga_login') == false ? 0 : 1);
            $ga_transfer = (I('ga_transfer') == false ? 0 : 1);

            if (!$gacode) {
                $this->error('请输入验证码!');
            }

            if ($type == 'add') {
                $secret = session('secret');

                if (!$secret) {
                    $this->error('验证码已经失效,请刷新网页!');
                }
            } else if (($type == 'updat') || ($type == 'delet')) {
                $user = M('User')->where('id = ' . userid())->find();

                if (!$user['ga']) {
                    $this->error('还未设置谷歌验证码!');
                }

                $arr = explode('|', $user['ga']);
                $secret = $arr[0];
                $delete = ($type == 'delet' ? 1 : 0);
            } else {
                $this->error('操作未定义');
            }

            $ga = new \Common\Ext\GoogleAuthenticator();

            if ($ga->verifyCode($secret, $gacode, 1)) {
                $ga_val = ($delete == '' ? $secret . '|' . $ga_login . '|' . $ga_transfer : '');
                $mo = M();
                //
                $rs = $mo->table('tw_user')->save(array('id' => userid(), 'ga' => $ga_val));
                if ($rs) {
                    $this->success('操作成功');
                } else {
                    $this->error('操作失败');
                }
            } else {
                $this->error('验证失败');
            }
        }
    }

    public function mobile()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        $user = M('User')->where(array('id' => userid()))->find();
        if ($user['mobile']) {
            $user['mobile'] = substr_replace($user['mobile'], '****', 3, 4);
        }
        //国际区号
        $list=M('area_code')->select();
        $this->assign('list', $list);
        $this->assign('user', $user);
        $this->display();
    }

    public function upmobile($quhao,$mobile, $mobile_verify)
    {

        // 过滤非法字符----------------S

        if (checkstr($mobile)  || checkstr($mobile_verify)|| checkstr($quhao)) {
            $this->error('您输入的信息有误！');
        }

        // 过滤非法字符----------------E

        if (!userid()) {
            $this->error('您没有登录请先登录！');
        }
		
        if($quhao=="-1"){
            $this->error('请选择国际区号');
        }
        if (!check($quhao, 'd')) {
                $this->error('区号格式错误！');
            }
        if($quhao==86){
            if (!check($mobile, 'mobile')) {
                $this->error('手机号码格式错误！');
            }
        }else{
            if(!preg_match("/^[1-9]\d*$/",$mobile)){
                $this->error('手机号码格式错误！');
            }

        }

        if ($mobile != session('chkmobile')) {
            $this->error('短信验证码错误！');
        }

        if (!check($mobile_verify, 'd')) {
            $this->error('短信验证码格式错误！');
        }

        if ($mobile_verify != session('mobilebd_verify')) {
            $this->error('短信验证码错误！');
        }

        if (M('User')->where(array('mobile' => $mobile))->find()) {
            $this->error('手机号码已存在！');
        }

        $rs = M('User')->where(array('id' => userid()))->save(array('mobile' => $mobile, 'mobiletime' => time(),'gjcode'=>$quhao*1));

        if ($rs) {
            $this->success('手机认证成功！');
        } else {
            $this->error('手机认证失败！');
        }
    }

    public function email()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
        $user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        $user = M('User')->where(array('id' => userid()))->find();
        if (!empty($user['email'])) {
            $user['email'] = substr_replace($user['email'], '***', 1, 3);
        }
        $this->assign('user', $user);
        $this->display();
    }

    public function upemail($email = NULL, $email_verify)
    {
        if (!userid()) {
            $this->error('您没有登录请先登录！');
        }

        if (!check($email, 'email')) {
            $this->error('邮箱格式错误！');
        }

        $user = M('User')->where(array('email' => $email))->find();

        if (!empty($user)) {
            $this->error('邮箱已存在！');
        }

        if (!check($email_verify, 'd')) {
            $this->error('邮箱验证码格式错误！');
        }

        if ($email_verify != session('emailbd_verify')) {
            $this->error('邮箱验证码错误！');
        }

        $rs = M('User')->where(array('id' => userid()))->save(array('email' => $email));

        if ($rs) {
            $this->success('邮箱绑定成功！');
        } else {
            $this->error('邮箱绑定失败！');
        }
    }

    public function tpwdset()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);
        $this->display();
    }

    public function tpwdsetting()
    {
        if (userid()) {
            $tpwdsetting = M('User')->where(array('id' => userid()))->getField('tpwdsetting');
            exit($tpwdsetting);
        }
    }

    public function uptpwdsetting($paypassword, $tpwdsetting)
    {
        if (!userid()) {
            $this->error('请先登录！');
        }

        if (!check($paypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if (($tpwdsetting != 1) && ($tpwdsetting != 2) && ($tpwdsetting != 3)) {
            $this->error('选项错误！' . $tpwdsetting);
        }

        $user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');


        if (md5($paypassword) != $user_paypassword) {
            $this->error('交易密码错误！');
        }

        $rs = M('User')->where(array('id' => userid()))->save(array('tpwdsetting' => $tpwdsetting));

        if ($rs) {
            $this->success('成功！');
        } else {
            $this->error('失败！');
        }
    }

    public function bank()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }

        $UserBankType = M('UserBankType')->where(array('status' => 1))->order('id desc')->select();
        $this->assign('UserBankType', $UserBankType);
        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);
        $UserBank = M('UserBank')->where(array('userid' => userid(), 'status' => 1))->order('id desc')->select();
        $this->assign('UserBank', $UserBank);
        $this->display();
    }

    public function upbank($name, $bank, $bankprov, $bankcity, $bankaddr, $bankcard, $paypassword)
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }

        if (!check($name, 'a')) {
            $this->error('备注名称格式错误！');
        }

        if (!check($bank, 'a')) {
            $this->error('开户银行格式错误！');
        }

        if (!check($bankprov, 'c')) {
            $this->error('开户省市格式错误！');
        }

        if (!check($bankcity, 'c')) {
            $this->error('开户省市格式错误2！');
        }

        if (!check($bankaddr, 'a')) {
            $this->error('开户行地址格式错误！');
        }

        if (!check($bankcard, 'd')) {
            $this->error('银行账号格式错误！');
        }

        if (!preg_match('/^\d{13,}$/', $bankcard)) {
            $this->error('银行账号不低于13位数字！');
        }

        if (!check($paypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        $user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

        if (md5($paypassword) != $user_paypassword) {
            $this->error('交易密码错误！');
        }

        if (!M('UserBankType')->where(array('title' => $bank))->find()) {
            $this->error('开户银行错误！');
        }

        $userBank = M('UserBank')->where(array('userid' => userid()))->select();

        foreach ($userBank as $k => $v) {
            if ($v['name'] == $name) {
                $this->error('请不要使用相同的备注名称！');
            }

            if ($v['bankcard'] == $bankcard) {
                $this->error('银行卡号已存在！');
            }
        }

        if (10 <= count($userBank)) {
            $this->error('每个用户最多只能添加10个地址！');
        }

        if (M('UserBank')->add(array('userid' => userid(), 'name' => $name, 'bank' => $bank, 'bankprov' => $bankprov, 'bankcity' => $bankcity, 'bankaddr' => $bankaddr, 'bankcard' => $bankcard, 'addtime' => time(), 'status' => 1))) {
            $this->success('银行添加成功！');
        } else {
            $this->error('银行添加失败！');
        }
    }

    public function delbank($id, $paypassword)
    {

        if (!userid()) {
            redirect('/Login/index.html');
        }

        if (!check($paypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if (!check($id, 'd')) {
            $this->error('参数错误！');
        }

        $user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

        if (md5($paypassword) != $user_paypassword) {
            $this->error('交易密码错误！');
        }

        if (!M('UserBank')->where(array('userid' => userid(), 'id' => $id))->find()) {
            $this->error('非法访问！');
        } else if (M('UserBank')->where(array('userid' => userid(), 'id' => $id))->delete()) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    public function qianbao($coin = NULL)
    {

        // 过滤非法字符----------------S

        if (!empty($coin) && !check($coin,'xnb')) {
            $this->error('您输入的信息有误！');
        }

        // 过滤非法字符----------------E

        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        if (!$coin) {
            $coin = $coinlist[0]['name'];
        }
        $this->assign('xnb', $coin);
		
        $userQianbaoList = M('UserQianbao')->where(array('userid' => userid(), 'status' => 1, 'coinname' => $coin))->order('id desc')->select();
        $this->assign('userQianbaoList', $userQianbaoList);

        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);
		
		$myzr_token = set_token('myzr');
        $this->assign('myzr_token', $myzr_token);

        $this->display();
    }

    public function upqianbao($coin, $name, $addr, $paypassword, $token)
    {

		if (!userid()) {
            redirect('/Login/index.html');
        }
		
		if(!session('myzrtoken')) {
			set_token('myzr');
		}
		if(!empty($token)){
			$res = valid_token('myzr',$token);
			if(!$res){
				$this->error("请不要频繁提交！",session('myzrtoken'));
			}
		}else{
			$this->error("缺少参数！",session('myzrtoken'));
		}
		$extra=session('myzrtoken');

        if (!empty($coin) && !check($coin,'xnb')) {
            $this->error('您输入的信息有误！',$extra);
        }

        if (!check($name, 'a')) {
            $this->error('备注名称格式错误！',$extra);
        }

		$addr = trim($addr);
        if (!check($addr, 'dw')) {
            $this->error('钱包地址格式错误！',$extra);
        }

        if (!check($paypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！',$extra);
        }

        $user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

        if (md5($paypassword) != $user_paypassword) {
            $this->error('交易密码错误！',$extra);
        }
		
		$coin_info = M('Coin')->where(array('name' => $coin))->find();
        if (empty($coin_info)) {
            $this->error('币种不存在！',$extra);
        }
		if($coin_info['tp_qj'] == "eth" || $coin_info['tp_qj'] == "erc20"){
			if(substr($addr,0,2) != '0x' || strlen($addr) <= 10){
				$this->error('钱包地址格式错误！',$extra);
			}
		}

        $userQianbao = M('UserQianbao')->where(array('userid' => userid(), 'coinname' => $coin))->select();

        foreach ($userQianbao as $k => $v) {
            if ($v['name'] == $name) {
                $this->error('请不要使用相同的钱包标识！',$extra);
            }

            if ($v['addr'] == $addr) {
                $this->error('钱包地址已存在！',$extra);
            }
        }

        if (100 <= count($userQianbao)) {
            $this->error('每个人最多只能添加100个地址！',$extra);
        }

        if (M('UserQianbao')->add(array('userid' => userid(), 'name' => $name, 'addr' => trim($addr), 'coinname' => $coin, 'addtime' => time(), 'status' => 1))) {
            $this->success('添加成功！',$extra);
        } else {
            $this->error('添加失败！',$extra);
        }
    }

    public function delqianbao($id, $paypassword)
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }

        if (!check($paypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if (!check($id, 'd')) {
            $this->error('参数错误！');
        }

        $user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

        if (md5($paypassword) != $user_paypassword) {
            $this->error('交易密码错误！');
        }

        if (!M('UserQianbao')->where(array('userid' => userid(), 'id' => $id))->find()) {
            $this->error('非法访问！');
        } else if (M('UserQianbao')->where(array('userid' => userid(), 'id' => $id))->delete()) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    public function log()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

        $where['status'] = array('egt', 0);
        $where['userid'] = userid();
        $Model = M('UserLog');
        $count = $Model->where($where)->count();
        $Page = new \Think\Page($count, 10);
        $show = $Page->show();
        $list = $Model->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);

        $this->display();
    }


    public function upuserinfo()
    {
        if (!userid()) {
            $this->error("请先登录！");
        }
        $update = array();
        $upload = new \Think\Upload();//实列化上传类
        $upload->maxSize = 3145728;//设置上传文件最大，大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');//后缀
        $upload->rootPath = './Upload/lanch/hportrait/';//上传目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        $upload->autoSub = true;
        $upload->subName = array('date', 'Ymd');
        $upload->saveName = array('uniqid', '');//设置上传文件规则
        $info = $upload->upload();//执行上传方法    101.13    19764.83   70638.44
        if ($info) {
            $image = new \Think\Image();
            foreach ($info as $key => $file) {
                if (!empty($file)) {
                    $image->open('./Upload/lanch/hportrait/' . $file['savepath'] . $file['savename']);
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
                    $image->thumb($new_width, $new_height)->save('./Upload/lanch/hportrait/' . $file['savepath'] . "s_" . $file['savename']);
                    unlink('./Upload/lanch/' . $file['savepath'] . $file['savename']);
                }
            }
            $headimg = M('User')->where(array('id' => userid()))->getField('headimg');
            $headroot = 'http://tdcw.oss-cn-hongkong.aliyuncs.com/hportrait/' . $file['savepath'] . "s_" . $file['savename'];
            $result = M('User')->where(array('id' => userid()))->save(array('headimg' => $headroot));
            if ($headimg != '') {
                unlink('.' . $headimg);
            }
            if ($result) {
                $this->success('上传成功');
            } else {
                $this->error('上传失败');
            }
        }
    }

    public function myad($type,$state=1)
    {

        if (!userid()) {
            redirect('/Login/index.html');
        }
		
		if(!isset($type) || empty($state)){
			$this->error("参数错误！");
		}
		
		if ($type != 0 && $type != 1) {
            $this->error("广告类型错误！");
        }
		
		if ($state != 1 && $state != 2) {
			$this->error("广告状态错误！");
		}
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

		if($state==2){
			$where['state'] = array('in','2,4');
		}else{
			$where['state'] = $state;
		}
        $where['userid'] = userid();
        $Mobile = ($type == 0) ? M('Ad_buy') : M('Ad_sell');
        $count = $Mobile->where($where)->count();
        $Page = new \Think\Page($count, 15);
        $show = $Page->show();
        $list = $Mobile->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        foreach ($list as $k => $v) {
			$list[$k]['coin'] = M('Coin')->where(array('id' => $v['coin']))->getField('name');
            $list[$k]['location'] = M('Location')->where(array('id' => $v['location']))->getField('short_name');
            $list[$k]['currency_type'] = get_price($v['coin'],$v['currency'],0);
            $price = get_price($v['coin'],$v['currency'],1);
            $list[$k]['price'] = round($price + $price * $v['margin'] / 100, 2);
            $list[$k]['margin'] = floatval($list[$k]['margin']);
        }

        $this->assign('type', $type);
		$this->assign('state', $state);
        $this->assign('list', $list);
        $this->assign('page', $show);

        $cancel_token = set_token('cancel');
        $this->assign('cancel_token', $cancel_token);
		
		$shelf_token = set_token('shelf');
        $this->assign('shelf_token', $shelf_token);
        $this->display();
    }


    public function jianjie()
    {
        if (!userid()) {
            redirect('/Login/index.html');
        }
        $jianjie = $_POST['jianjie'];
		if (empty($jianjie)) {
            $this->error('内容不能为空');
        }
		if(!check($jianjie,'bcdw')){
			$this->error('您输入的信息有误！');
		}

        $upd = M('User')->where(array('id'=>userid()))->setField('jianjie', $jianjie);
        if($upd){
            $this->success('设置成功');
        }else{
            $this->error('请重新设置');
        }
    }

    //受信任的
	public function trusted($type=1){
		if (!userid()) {
            redirect('/Login/index.html');
        }
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);
		
		if($type == 1){
			$user_id = M('User')->where(array('id'=>userid()))->getField('ixinren');
		}
		if($type == 2){
			$user_id = M('User')->where(array('id'=>userid()))->getField('ipingbi');
		}
    	if($type == 3){
    		$user_id = M('User')->where(array('id'=>userid()))->getField('xinren');
    	}
		$user_id = trim($user_id,",");
		$user_ids = explode(",",$user_id);
		$count = M('User')->where(array('id'=>array('in',$user_ids)))->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('User')->where(array('id'=>array('in',$user_ids)))->select();
		foreach ($list as $k=> $v){
			$list[$k]['trust'] = gettrust($v['id']);
			$list[$k]['trade_times'] = $this->gettrade($v['id']);
		}

		$this->assign('list', $list);
		$this->assign('type', $type);
		$this->assign('page', $show);
		$this->display();
	}
	//统计与对方的交易次数
	public function gettrade($id){
		$ids = M('User')->where(array('id'=>userid()))->getField('trade_id');
		$ids_arr = explode(",",$ids);
		$ids_arr_times = array_count_values($ids_arr);
		return $ids_arr_times[$id];
	}
	
	public function mytj()
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		$yj_arr = array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
			$yj = M('Invit')->where(array('userid'=>userid(),'type'=>$v['id']))->sum('fee');
			$yj_arr[$v['name']] = array(strtoupper($v['name']),$yj*1);
		}
		$this->assign('coin_list', $new_coinlist);
		$this->assign('yj_arr',$yj_arr);
		
		$rssum = M('User')->where(array('invit_1'=>userid()))->count();
		$this->assign('rssum',$rssum);

		$user = M('User')->where(array('id' => userid()))->find();

		if (!$user['invit']) {
			for (; true; ) {
				$tradeno = tradenoa();

				if (!M('User')->where(array('invit' => $tradeno))->find()) {
					break;
				}
			}

			M('User')->where(array('id' => userid()))->save(array('invit' => $tradeno));
			$user = M('User')->where(array('id' => userid()))->find();
		}

		$this->assign('user', $user);
		$this->display();
	}
	
	public function mywd()
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

		$where['invit_1'] = userid();
		$count = M('User')->where($where)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = M('User')->field('id,email,enname,addtime,invit_1,status')->where($where)->order('id asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$below = M('User')->where(array('invit_1'=>$v['id']))->count();
			$list[$k]['below'] = intval($below);
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function follower($userid = NULL){
		if(empty($userid)){
			$userlist = array();
		}else{
			if(!check($userid,'d')){
				$this->ajaxReturn(array());
			}
			$userlist = M('User')->where(array('invit_1'=>$userid))->field('id,enname,email,addtime')->order('id asc')->select();
			if(!empty($userlist)){
				foreach($userlist as $key=>$user){
					$below = M('User')->where(array('invit_1'=>$user['id']))->count();
					$userlist[$key]['below'] = intval($below);
					$userlist[$key]['addtime'] = date("Y-m-d H:i:s",$user['addtime']);
					if(empty($user['enname'])){
						$userlist[$key]['enname'] = "-";
					}
				}
			}
		}
		$this->ajaxReturn($userlist);
	}

	public function myjp()
	{
		if (!userid()) {
			redirect('/Login/index.html');
		}
		
		$user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
		$coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
		$new_coinlist=array();
		foreach($coinlist as $k=>$v){
			$new_coinlist[$v['name']]['name'] = $v['name'];
			$new_coinlist[$v['name']]['img'] = $v['img'];
			$new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
			$new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
			$new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
			$new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
		}
		$this->assign('coin_list', $new_coinlist);

		$where['userid'] = userid();
		$count = M('Invit')->where($where)->count();
		$Page = new \Think\Page($count, 10);
		$show = $Page->show();
		$list = M('Invit')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$coin_info = M('Coin')->field('id,title')->select();
		$coin_arr = array();
		foreach($coin_info as $arr){
			$coin_arr[$arr['id']] = $arr['title'];
		}
		foreach ($list as $k => $v) {
			$list[$k]['invit'] = M('User')->where(array('id' => $v['invit']))->getField('enname');
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
				$list[$k]['buysell'] = "卖出广告";
			}elseif($v['buysell'] == 2){
				$list[$k]['buysell'] = "买入广告";
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	//收款账号
    public function skaccount(){

        if (!userid()) {
            redirect('/Login/index.html');
        }

        $user_coin = M('user_coin')->where(array('userid'=>userid()))->find();
        $coinlist = M('Coin')->where(array('type'=>array('neq','rmb'),'status'=>1))->select();
        $new_coinlist=array();
        foreach($coinlist as $k=>$v){
            $new_coinlist[$v['name']]['name'] = $v['name'];
            $new_coinlist[$v['name']]['img'] = $v['img'];
            $new_coinlist[$v['name']]['title'] = $v['title'] . '(' . strtoupper($v['name']) . ')';
            $new_coinlist[$v['name']]['xnb'] = round($user_coin[$v['name']],8);
            $new_coinlist[$v['name']]['xnbd'] = round($user_coin[$v['name'] . 'd'],8);
            $new_coinlist[$v['name']]['xnbz'] = round($user_coin[$v['name']]+$user_coin[$v['name'] . 'd'],8);
            $new_coinlist[$v['name']]['zr_dz'] = $v['zr_dz'];
        }
        $this->assign('coin_list', $new_coinlist);

        if (!$coin) {
            $coin = $coinlist[0]['name'];
        }
        $this->assign('xnb', $coin);

        $user = M('User')->where(array('id' => userid()))->find();
        $this->assign('user', $user);

        //导航
        $daohang = S('daohang');
        $this->assign('daohang', $daohang);

        //支付方式
        $payMethod = M('pay_method')->select();
        $this->assign('payMethod', $payMethod);

        //收款账号列表

        $myzr_token = set_token('myzr');
        $this->assign('myzr_token', $myzr_token);
        $userSkaccountList = M('user_skaccount')->where(array('userid' => userid()))->select();

        foreach ($userSkaccountList as $ke => $val){
            $userSkaccountList[$ke]['pay_name'] = $payMethod[$val['pay_method_id'] - 1]['name'];
        }
        $this->assign('userSkaccountList', $userSkaccountList);
//        print_r($userSkaccountList);exit();


        $this->display();
    }

    //新增、修改收款账号
    public function upskaccount($id, $pay_method, $name, $account, $qrcode, $bank, $desc, $paypassword, $token){

        if (!userid()) {
            redirect('/Login/index.html');
        }

        if(!session('myzrtoken')) {
            set_token('myzr');
        }
        if(!empty($token)){
            $res = valid_token('myzr',$token);
            if(!$res){
                $this->error("请不要频繁提交！",session('myzrtoken'));
            }
        }else{
            $this->error("缺少参数！",session('myzrtoken'));
        }
        $extra=session('myzrtoken');

        if (!check($paypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！',$extra);
        }

        $user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

        if (md5($paypassword) != $user_paypassword) {
            $this->error('交易密码错误！',$extra);
        }

        $userSkaccount = M('user_skaccount')->where(array('user_id' => userid()))->select();

        if (100 <= count($userSkaccount)) {
            $this->error('每个人最多只能添加100个收款账号！',$extra);
        }

        if($id == false){
            //新增
            if (M('user_skaccount')->add(array(
                'user_id' => userid(),
                'pay_method_id' => intval($pay_method),
                'name' => $name,
                'account' => $account,
                'qrcode' => $qrcode,
                'bank' => $bank,
                'desc' => $desc,
                'addtime' => time()
                ))) {
                $this->success('添加成功！',$extra);
            } else {
                $this->error('添加失败！',$extra);
            }
        } else {

            if (!M('user_skaccount')->where(array('userid' => userid(), 'id' => $id))->find()) {
                $this->error('非法访问！');
            }
            //修改
            M('user_skaccount')->save(array(
                'pay_method_id' => intval($pay_method),
                'name' => $name,
                'account' => $account,
                'qrcode' => $qrcode,
                'bank' => $bank,
                'desc' => $desc,
            ), array('id' => $id));
            $this->success('修改成功！',$extra);
        }


    }

    //删除收款账号
    public function delskaccount($id, $paypassword){
        if (!userid()) {
            redirect('/Login/index.html');
        }

        if (!check($paypassword, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if (!check($id, 'd')) {
            $this->error('参数错误！');
        }

        $user_paypassword = M('User')->where(array('id' => userid()))->getField('paypassword');

        if (md5($paypassword) != $user_paypassword) {
            $this->error('交易密码错误！');
        }

        if (!M('user_skaccount')->where(array('userid' => userid(), 'id' => $id))->find()) {
            $this->error('非法访问！');
        } else if (M('user_skaccount')->where(array('userid' => userid(), 'id' => $id))->delete()) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    //上传收款照片
    public function skqrcode(){
        if(IS_POST){
            $upload = new \Think\Upload();//实列化上传类
            $upload->maxSize = 3145728;//设置上传文件最大，大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');//后缀
            $upload->rootPath = './Upload/skqrcode/';//上传目录
            $upload->savePath = ''; // 设置附件上传（子）目录
            $upload->autoSub = true;
            $upload->subName = array('date', 'Ymd');
            $upload->saveName = array('uniqid', '');//设置上传文件规则
            $info = $upload->upload();//执行上传方法
            if (!$info) {
                $this->error($upload->getError());
            } else {
                $image = new \Think\Image();
                foreach ($info as $key => $file) {
                    $image->open('./Upload/skqrcode/' . $file['savepath'] . $file['savename']);
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
                    $image->thumb($new_width, $new_height)->save('./Upload/skqrcode/' . $file['savepath'] . $file['savename']);
                    unlink('./Upload/skqrcode/' . $file['savepath'] . $file['savename']);
                }
                echo json_encode(array('name'=>$file['savename']));
            }
        }
    }
}

?>