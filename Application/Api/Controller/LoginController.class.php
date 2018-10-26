<?php

namespace Home\Controller;

class LoginController extends HomeController

{
    protected function _initialize()
    {
        parent::_initialize();
        $allow_action = array("index", "register", "upregister", "register4", "chkUser", "chkmobile", "submit", "loginout", "findpwd", "findpaypwd", "getBrowser", "chkemail", "chkusemibao", 'chkusepaymibao',"jiancega");
        if (!in_array(ACTION_NAME, $allow_action)) {
            $this->error("非法操作！");
        }
    }

    public function __construct()
    {
        parent::__construct();
        $display_action = array("index", "register", "register4", "findpwd", "findpaypwd");
        if (in_array(ACTION_NAME, $display_action)) {
            $this->common_content();
        }
    }
    public function tishi(){
        $ga=session('guga');
        if(empty($ga)){
             $this->display();
         }else{
            redirect('/');
         }
    }



    public function chkusemibao($mobile = '', $email = '')
    {
        if (empty($mobile) && empty($email)) {
            $this->error('参数错误！');
        }
        if (!empty($mobile)) {
			if(!check($mobile,'mobile')){
				$this->error(L('phone_format_err'));
			}
            $user = M('User')->where(array('mobile' => $mobile))->find();
            if (empty($user)) {
                $this->error('此手机号没有注册！');
            } else {
                echo json_encode(array('usemibao' => $user['findpwd_mibao'], 'status' => 1));
                exit;
            }
        }
        if (!empty($email)) {
			if(!check($email,'email')&&!check($email,'mobile')){
				$this->error(L('g_yx_gs_error'));
			}
            $user = M('User')->where(array('email' => $email))->find();
            if (empty($user)) {
                $this->error('此邮箱没有注册！');
            } else {
                echo json_encode(array('usemibao' => $user['findpwd_mibao'], 'status' => 1));
                exit;
            }
        }
    }

    public function chkusepaymibao($mobile = '', $email = '')
    {
        if (empty($mobile) && empty($email)) {
            $this->error('参数错误！');
        }
        if (!empty($mobile)) {
			if(!check($mobile,'email')&&!check($mobile,'mobile')){
				$this->error(L('g_yx_gs_error'));
			}
            $user = M('User')->where(array('mobile' => $mobile))->find();
            if (empty($user)) {
                $this->error('此手机号没有注册！');
            } else {
                echo json_encode(array('usemibao' => $user['findpaypwd_mibao'], 'status' => 1));
                exit;
            }
        }
        if (!empty($email)) {
			if(!check($email,'email')&&!check($email,'mobile')){
				$this->error(L('g_yx_gs_error'));
			}
            $user = M('User')->where(array('email' => $email))->find();
            if (empty($user)) {
                $this->error('此邮箱没有注册！');
            } else {
                echo json_encode(array('usemibao' => $user['findpaypwd_mibao'], 'status' => 1));
                exit;
            }
        }
    }

    public function register()
    {
        if (!empty($_SESSION['reguserId'])) {
            $user = M('User')->where(array('id' => $_SESSION['reguserId']))->find();
            if (!empty($user)) {
                header("Location:/Login/register4");
            }
        }

        $this->display();

    }

    public function upregister($reg_type, $mobile, $password, $repassword, $verify, $mobilecode ,$en_name, $invit)
    {
        // 过滤非法字符----------------S
        if (checkstr($verify) || checkstr($reg_type) || checkstr($mobilecode)) {
            $this->error('您输入的信息有误！');
        }

        // 过滤非法字符----------------E

        if (empty($reg_type) || ($reg_type != 'phone' && $reg_type != 'email_a')) {
            $this->error('参数错误!');
        }
        $email = trim($email);
        $reg_type = 'mobile';

        if (!check_verify(strtoupper($verify), '1')) {
            $this->error('图形验证码错误!');
        }

        if ($reg_type == 'email' && !check($email, 'email')) {
            $this->error('电子邮箱格式错误！');
        }

       /* if ($reg_type == 'email' && !check($email, 'email')) {
            $this->error('电子邮箱格式错误！');
        }*/
        $en = M('User')->where(array('enname' => $en_name))->find();
        if($en){
            $this->error('该英文用户名已被注册');
        }
        if ($reg_type == 'mobile' && !check_enname($en_name)) {
            $this->error('请输入4-16位英文用户名');
        }

        if (strlen($password) > 16 || strlen($password) < 6) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if (!check($password, 'password')) {
            $this->error('密码格式为6~16位，不含特殊符号！');
        }

        if($invit == "非必填"){
            unset($invit);
        }

		if(!empty($invit) && !check($invit,'dw')){
			$this->error('参数错误!');
		}

        // if ($reg_type == 'email' && $email != session('chkemail')) {
        //     $this->error('邮箱验证码错误！');
        // }

        // if ($reg_type == 'email' && !check($emailcode, 'd')) {
        //     $this->error('邮箱验证码格式错误！');
        // }

        // if ($reg_type == 'email' && $emailcode != session('emailregss_verify')) {
        //     $this->error('邮箱验证码错误！');
        // }

        // if ($password != $repassword) {
        //     $this->error('两次输入的密码不一致');
        // }
            if ($reg_type == 'mobile' && $mobile != session('chkmobile')) {
            $this->error('短信验证码错误！');
        }
        if ($reg_type == 'mobile' && !check($mobilecode, 'd')) {
            $this->error('短信验证码格式错误！');
        }

        if ($reg_type == 'mobile' && $mobilecode != session('mobileregss_verify')) {
            $this->error('短信验证码错误！');
        }
        if (!$invit) {

            $invit = session('invit');
        }
        $invituser = M('User')->where(array('invit' => $invit))->find();
        if (!$invituser) {
            $invituser = M('User')->where(array('id' => $invit))->find();
        }
        if (!$invituser) {
            $invituser = M('User')->where(array('username' => $invit))->find();
        }
        if (!$invituser) {
            $invituser = M('User')->where(array('email' => $invit))->find();
        }
        if ($invituser) {
            $invit_1 = $invituser['id'];
            $invit_2 = $invituser['invit_1'];
            $invit_3 = $invituser['invit_2'];
        } else {
            $invit_1 = 0;
            $invit_2 = 0;
            $invit_3 = 0;
        }
        for (; true;) {
            $tradeno = tradenoa();
            if (!M('User')->where(array('invit' => $tradeno))->find()) {
                break;
            }
        }
        if ($reg_type == 'mobile') {
            $registed_user = M('User')->where(array('username' => $mobile))->find();
        } 

      // if ($reg_type == 'email') {
      //       $registed_user = M('User')->where(array('username' => $email))->find();
      //   }
        if (!empty($registed_user)) {

            $this->error('该用户已注册，请登录！', '/Login/index.html');

        }

        $mo = M();
        $mo->startTrans();
        //$mo->execute('lock tables tw_user write , tw_user_coin write ');
        $rs = array();

        // if ($reg_type == 'email') {
        //     $uid = $rs[] = $mo->table('tw_user')->add(array('username' => $email, 'email' => $email, 'enname'=>$en_name,'mobiletime' => time(), 'password' => md5($password), 'invit' => $tradeno, 'invit_1' => $invit_1, 'invit_2' => $invit_2, 'invit_3' => $invit_3, 'tpwdsetting' => 1,'addip' => get_client_ip(), 'addr' => get_city_ip(), 'addtime' => time(), 'status' => 1));
        // }
        if ($reg_type == 'mobile') {
            $uid = $rs[] = $mo->table('tw_user')->add(array('username' => $mobile, 'mobile' => $mobile, 'enname'=>$en_name,'mobiletime' => time(), 'password' => md5($password), 'invit' => $tradeno, 'invit_1' => $invit_1, 'invit_2' => $invit_2, 'invit_3' => $invit_3, 'tpwdsetting' => 1,'addip' => get_client_ip(), 'addr' => get_city_ip(), 'addtime' => time(), 'status' => 1));
        }
        $rs[] = $mo->table('tw_user_coin')->add(array('userid' => $uid));
		if(!empty($invituser['id'])){
			$coin_info = $mo->table('tw_coin')->where(array('status'=>1,'cs_zl'=>array('gt',0)))->select();
			if(!empty($coin_info)){
				foreach($coin_info as $k=>$v){
					$rs[] = $mo->table('tw_user_coin')->where(array('userid'=>$invituser['id']))->setInc($v['name'],$v['cs_zl']);
					$rs[] = $mo->table('tw_reg_prize')->add(array('userid'=>$invituser['id'],'username'=>$invituser['username'],'addtime'=>time(),'amount'=>$v['cs_zl'],'childuid'=>$uid,'childuname'=>$email,'coinid'=>$v['id']));
				}
			}
		}
        if (check_arr($rs)) {

            $mo->commit();

            session('reguserId', $rs[0]);

            session('mobileregss_verify', null);

            session('chkmobile', null);

            session('chkemail', null);

            session('emailregss_verify', null);

            $this->success('注册成功！');

        } else {
            $mo->rollback();
            $this->error('注册失败！');

        }

    }

    public function register2()

    {
        exit;
        if (!empty($_SESSION['reguserId'])) {
            $user = M('User')->where(array('id' => $_SESSION['reguserId']))->find();
            if (!empty($user) && !empty($user['paypassword'])) {
                header("Location:/Login/register3");
            }
        }
        $this->display();

    }


    public function upregister2($paypassword, $repaypassword)

    {

        exit;
        // 过滤非法字符----------------S

        if (checkstr($paypassword) || checkstr($repaypassword)) {
            $this->error('您输入的信息有误！');
        }

        // 过滤非法字符----------------E

        if (strlen($paypassword) > 16 || strlen($paypassword) < 6) {

            $this->error('密码格式为6~16位，不含特殊符号！');

        }

        if (!check($paypassword, 'password')) {

            $this->error('密码格式为6~16位，不含特殊符号！');

        }


        if ($paypassword != $repaypassword) {

            $this->error('两次输入的密码不一致');

        }


        if (!session('reguserId')) {

            $this->error('非法访问！');

        }


        if (M('User')->where(array('id' => session('reguserId'), 'password' => md5($paypassword)))->find()) {

            $this->error('交易密码不能和登录密码一样！');

        }


        if (M('User')->where(array('id' => session('reguserId')))->save(array('paypassword' => md5($paypassword)))) {

            $this->success('设置交易密码成功！');

        } else {

            $this->error('设置交易密码失败！');

        }

    }


    public function register3()

    {
        exit;
        if (!empty($_SESSION['reguserId'])) {
            $user = M('User')->where(array('id' => $_SESSION['reguserId']))->find();
            if (!empty($user) && !empty($user['truename'])) {
                header("Location:/Login/register4");
            }
        }
        $this->display();

    }


    public function upregister3($truename, $zhengjian, $idcard)

    {
        exit;

        // 过滤非法字符----------------S

        if (checkstr($truename) || checkstr($idcard) || checkstr($zhengjian)) {
            $this->error('您输入的信息有误！');
        }

        // 过滤非法字符----------------E


        if (!check($truename, 'truename')) {

            $this->error('真实姓名格式错误！');

        }

        if (!in_array($zhengjian, array('sfz', 'gsfz', 'hz', 'qt'))) {
            $this->error('证件类型错误！');
        }


        /*if (!check($idcard, 'idcard')) {

            $this->error('身份证号格式错误！');

        }

        if (M('User')->where(array('idcard' => $idcard))->find()) {

            $this->error('该身份证号已被注册');

        }*/


        if (!session('reguserId')) {

            $this->error('非法访问！');

        }


        if (M('User')->where(array('id' => session('reguserId')))->save(array('truename' => $truename, 'zhengjian' => $zhengjian, 'idcard' => $idcard))) {

            $this->success('身份验证成功！');

        } else {

            $this->error('身份验证失败！');

        }

    }


    public function register4()

    {
        $time = time();

        $user = M('User')->where(array('id' => session('reguserId')))->find();
		
		if(empty($user)){
			return;
		}

        session('userId', $user['id']);

        session('userName', $user['username']);

        cookie('userName', $user['username']);

        session('loginTime', $time);

        session('saveTime', $time);

        $member_session_id = M()->table('tw_user_log')->add(array('userid' => $user['id'], 'type' => 'login', 'remark' => "注册完成后自动登录", 'addtime' => $time, 'endtime' => $time, 'addip' => get_client_ip(), 'addr' => get_city_ip(), 'status' => 1, 'session_key' => session_id(), 'state' => 1));

        if (!empty($member_session_id)) {
            session('sessionId', $member_session_id);
        }

        $this->assign('user', $user);

        $this->display();

    }


    public function chkUser($username)

    {

        // 过滤非法字符----------------S

        if (checkstr($username)) {
            $this->error('您输入的信息有误！');
        }

        // 过滤非法字符----------------E


        if (!check($username, 'username')) {

            $this->error('用户名格式错误！');

        }


        if (M('User')->where(array('username' => $username))->find()) {

            $this->error('用户名已存在');

        }


        $this->success('');

    }

    public function chkmobile($mobile)

    {

        // 过滤非法字符----------------S

        if (!check($mobile, 'mobile')) {
            $this->error('您输入的手机号码格式错误！');
        }

        // 过滤非法字符----------------E

        if (M('User')->where(array('username' => $mobile))->find()) {

            $this->error('用户已存在，请登录', '/Login/index.html');

        } else {

            if (M('User')->where(array('mobile' => $mobile))->find()) {

                $this->error('手机号已存在');

            }
        }

        $this->success('');

    }

    public function chkemail($email)
    {
        // 过滤非法字符----------------S

        if (!check($email, 'email')) {
            $this->error('您输入的电子邮箱格式错误！');
        }

        // 过滤非法字符----------------E

        if (M('User')->where(array('username' => $email))->find()) {

            $this->error('用户已存在，请登录', '/Login/index.html');

        } else {

            if (M('User')->where(array('email' => $email))->find()) {

                $this->error('电子邮箱已存在，请换一个！');

            }
        }

        $this->success('');
    }

    public function submit($username, $password, $verify = NULL, $ga = '')

    {
        $time = time();

        // 过滤非法字符----------------S

        if (checkstr($username) || (!empty($verify) && checkstr($verify)) || (!empty($ga) && checkstr($ga))) {
            $this->error('您输入的信息有误！');
        }
		
		if(!check($username,'mobile') && !check($username,'username')){
			$this->error('参数错误！');
		}

        // 过滤非法字符----------------E

        if (C('login_verify')) {
            if (!check_verify(strtoupper($verify), '1')) {
                $this->error('图形验证码错误!');
            }
        }

        if (empty($user)) {

            $user = M('User')->where(array('username' => $username))->find();

            $browser = $this->getBrowser();
            $remark = 'PC端' . $browser['os'] . "，" . $browser['browser'] . '登录';

        }


        if (empty($user)) {

            $this->error('用户不存在！');

        }
        if ($user['pwd_err'] >= 5) {

            $this->error('账号因密码错误超过5次被冻结，请联系管理员解封');

        }

        if (strlen($password) > 16 || strlen($password) < 6) {

            $this->error('密码格式为6~16位，不含特殊符号！');

        }

        if (!check($password, 'password')) {

            $this->error('密码格式为6~16位，不含特殊符号！');

        }

        if (md5($password) != $user['password']) {
            M('User')->where(array('id' => $user['id']))->setInc('pwd_err', 1);
            $user_pwd = M('User')->where(array('username' => $username))->find();
            if ($user_pwd['pwd_err'] >= 5) {
                M('User')->where(array('id' => $user['id']))->setField('status', 0);
            }
            $pwd_err = $user['pwd_err'] + 1;
            if ($pwd_err < 5) {
                $this->error('登录密码输入错误' . $pwd_err . '次，超过5次账号将被冻结！');
            } else {
                $this->error('登录密码输入错误超过5次,账号已被冻结，请联系管理员解封！');
            }
        }

        if (1!=1 && $user['ga']) {
            $ga_n = new \Common\Ext\GoogleAuthenticator();
            $arr = explode('|', $user['ga']);
            // 存储的信息为谷歌密钥
            $secret = $arr[0];
            // 存储的登录状态为1需要验证，0不需要验证
            $ga_is_login = $arr[1];
            // 判断是否需要验证
            if ($ga_is_login) {
                if (!$ga) {
                    $this->error('请输入谷歌验证码！');
                }
                if (!check($ga, 'd')) {
                    $this->error('谷歌验证码格式错误！');
                }
                // 判断登录有无验证码
                $aa = $ga_n->verifyCode($secret, $ga, 1);
                if (!$aa) {
                    $this->error('谷歌验证码错误！');
                }
            }
        }

        if (isset($user['status']) && $user['status'] != 1) {

            $this->error('你的账号已冻结请联系管理员！');

        }

        if (chkchuanhao(session_id(), $user['id'])) {
            $this->error('请先清除浏览器缓存之后再重新登录！');
        }

        $mo = M();

        $mo->startTrans();

        //$mo->execute('lock tables tw_user write , tw_user_log write');

        $rs = array();

        $rs[] = $mo->table('tw_user')->where(array('id' => $user['id']))->setInc('logins', 1);
		 $rs[] = $mo->table('tw_user')->where(array('id' => $user['id']))->save(array('logintime'=>$time,'loginstatus'=>1));
        if ($user['pwd_err'] > 0) {
            $rs[] = $mo->table('tw_user')->where(array('id' => $user['id']))->setField('pwd_err', 0);
        }
        $rs[] = $member_session_id = $mo->table('tw_user_log')->add(array('userid' => $user['id'], 'type' => 'login', 'remark' => $remark, 'addtime' => $time, 'endtime' => $time, 'addip' => get_client_ip(), 'addr' => get_city_ip(), 'status' => 1, 'session_key' => session_id(), 'state' => 1));

        if (check_arr($rs)) {

            $mo->commit();

            session('userId', $user['id']);
            
            // session('reguserId',$user['id']);
            session('guga',$user['ga']);

            session('userName', $user['username']);

            cookie('userName', $user['username']);

            cookie('tishi', 0);

            session('loginTime', $time);

            session('saveTime', $time);

            if (!$user['paypassword']) {

                session('regpaypassword', $rs[0]);

                session('reguserId', $user['id']);

            }

            if (!$user['truename']) {

                session('regtruename', $rs[0]);

                session('reguserId', $user['id']);

            }

            if (!empty($member_session_id)) {
                session('sessionId', $member_session_id);
            }

            $this->success('登录成功！');

        } else {

            $mo->rollback();

            $this->error('登录失败！');

        }

    }


    public function loginout()

    {

        M('user_log')->where(array('userid' => session('userId'), 'type' => 'login', 'state' => 1))->save(array('state' => 0, 'endtime' => time()));
			 $mo = M();
             $mo->startTrans();
             $mo->table('tw_user')->where(array('id' =>session('userId')))->save(array('loginstatus'=>0));
             $mo->commit();
        session(null);

        cookie(null);

        redirect('/');

    }


    public function findpwd()

    {

        if (IS_POST) {

            $input = I('post.');

            foreach ($input as $k => $v) {
                // 过滤非法字符----------------S

                if (checkstr($v)) {
                    $this->error('您输入的信息有误！');
                }

                // 过滤非法字符----------------E
            }


            if (!check_verify(strtoupper($input['verify']), '1')) {

                $this->error('图形验证码错误!');

            }

            $fpw_type = $input['fpw_type'];
            if ($fpw_type != "phone" && $fpw_type != "emailaddr") {
                $this->error('参数错误！');
            }


            if ($fpw_type == 'phone' && !check($input['mobile'], 'mobile')) {

                $this->error('手机号码格式错误！');

            }

            if ($fpw_type == 'phone' && $input['mobile'] != session('chkmobile')) {

                $this->error('短信验证码错误！');

            }

            if ($fpw_type == 'phone' && !check($input['mobile_verify'], 'd')) {

                $this->error('短信验证码格式错误！');

            }

            if ($fpw_type == 'phone' && $input['mobile_verify'] != session('findpwd_verify')) {

                $this->error('短信验证码错误！');

            }

            if ($fpw_type == 'emailaddr' && !check($input['email'], 'email')) {

                $this->error('电子邮箱格式错误！');

            }

            if ($fpw_type == 'emailaddr' && $input['email'] != session('chkemail')) {

                $this->error('邮箱验证码错误！');

            }

            if ($fpw_type == 'emailaddr' && !check($input['emailcode'], 'd')) {

                $this->error('邮箱验证码格式错误！');

            }

            if ($fpw_type == 'emailaddr' && $input['emailcode'] != session('emailfindpwd_verify')) {

                $this->error('邮箱验证码错误！');

            }

            if ($fpw_type == 'emailaddr') {
                $user = M('User')->where(array('email' => $input['email']))->find();
            } elseif ($fpw_type == 'phone') {
                $user = M('User')->where(array('mobile' => $input['mobile']))->find();
            }

            if (!$user) {

                $this->error('用户不存在！');

            }

            $isusemibao = $input['isusemibao'];
            if (!empty($isusemibao) && $isusemibao != 1) {
                $this->error('参数错误！');
            }

            if (!empty($isusemibao) && $user['mibao_question'] != $input['mibao_question']) {

                $this->error('密保问题错误！');

            }

            if (!empty($isusemibao) && $user['mibao_answer'] != $input['mibao_answer']) {

                $this->error('密保答案错误！');

            }

            if (strlen($input['password']) > 16 || strlen($input['password']) < 6) {

                $this->error('密码格式为6~16位，不含特殊符号！');

            }

            if (!check($input['password'], 'password')) {

                $this->error('密码格式为6~16位，不含特殊符号！');

            }

            if ($input['password'] != $input['repassword']) {

                $this->error('两次输入的密码不一致');

            }


            if ($user['paypassword'] == md5($input['password'])) {

                $this->error('登录密码不能和交易密码相同！');

            }

            if ($user['password'] == md5($input['password'])) {

                $this->error('新登录密码与旧登录密码一致！');

            }


            $rs = M('User')->where(array('id' => $user['id']))->save(array('password' => md5($input['password'])));

            if ($rs) {

                session('findpwd_verify', null);

                session('chkmobile', null);

                session('emailfindpwd_verify', null);

                session('chkemail', null);

                $this->success('修改成功');

            } else {

                $this->error('修改失败');

            }

        } else {

            $this->display();

        }

    }


    public function findpaypwd()

    {

        if (IS_POST) {

            $input = I('post.');


            foreach ($input as $k => $v) {
                // 过滤非法字符----------------S

                if (checkstr($v)) {
                    $this->error('您输入的信息有误！');
                }

                // 过滤非法字符----------------E
            }

            if (!check_verify(strtoupper($input['verify']), '1')) {

                $this->error('图形验证码错误!');

            }

            $fpw_type = $input['fpw_type'];
            if ($fpw_type != "phone" && $fpw_type != "emailaddr") {
                $this->error('参数错误！');
            }

            if ($fpw_type == 'phone' && !check($input['mobile'], 'mobile')) {

                $this->error('手机号码格式错误！');

            }

            if ($fpw_type == 'phone' && $input['mobile'] != session('chkmobile')) {

                $this->error('短信验证码错误！');

            }

            if ($fpw_type == 'phone' && !check($input['mobile_verify'], 'd')) {

                $this->error('短信验证码格式错误！');

            }

            if ($fpw_type == 'phone' && $input['mobile_verify'] != session('findpaypwd_verify')) {

                $this->error('短信验证码错误！');

            }

            if ($fpw_type == 'emailaddr' && !check($input['email'], 'email')) {

                $this->error('电子邮箱格式错误！');

            }

            if ($fpw_type == 'emailaddr' && $input['email'] != session('chkemail')) {

                $this->error('邮箱验证码错误！');

            }

            if ($fpw_type == 'emailaddr' && !check($input['emailcode'], 'd')) {

                $this->error('邮箱验证码格式错误！');

            }

            if ($fpw_type == 'emailaddr' && $input['emailcode'] != session('emailfindpaypwd_verify')) {

                $this->error('邮箱验证码错误！');

            }

            $user = M('User')->where(array('mobile' =>$input['mobile']))->find();

            if (!$user) {

                $this->error('用户不存在！');

            }

            if ($fpw_type == 'phone' && $user['mobile'] != $input['mobile']) {

                $this->error('用户名或手机号码错误！');

            }

            if ($fpw_type == 'emailaddr' && $user['email'] != $input['email']) {

                $this->error('用户名或邮箱错误！');

            }

            $isusemibao = $input['isusemibao'];
            if (!empty($isusemibao) && $isusemibao != 1) {
                $this->error('参数错误！');
            }

            if (!empty($isusemibao) && $user['mibao_question'] != $input['mibao_question']) {

                $this->error('密保问题错误！');

            }

            if (!empty($isusemibao) && $user['mibao_answer'] != $input['mibao_answer']) {

                $this->error('密保答案错误！');

            }


            if (strlen($input['password']) > 16 || strlen($input['password']) < 6) {

                $this->error('密码格式为6~16位，不含特殊符号！');

            }


            if (!check($input['password'], 'password')) {

                $this->error('密码格式为6~16位，不含特殊符号！');

            }

            // if (!check($input['password'], 'password')) {

            // 	$this->error('新交易密码格式错误！');

            // }
            if (empty($user['paypassword'])) {

                $this->error('尚未设置交易密码！');

            }


            if ($input['password'] != $input['repassword']) {

                $this->error('两次输入的密码不一致');

            }

            if ($user['password'] == md5($input['password'])) {

                $this->error('交易密码不能和登录密码相同！');

            }

            if ($user['paypassword'] == md5($input['password'])) {

                $this->error('新交易密码与旧交易密码一致！');

            }
            $paypassword = $input['password'];
            $mo = M();
            // $rs = M('User')->where(array('id' => $user['id']))->save(array('paypassword' => md5($paypassword)));
            $rs = $mo->table('tw_user')->where(array('id' => $user['id']))->save(array('paypassword' => md5($paypassword)));
            // var_dump($mo->getLastSql());
            if ($rs) {

                session('findpaypwd_verify', null);

                session('chkmobile', null);

                session('emailfindpaypwd_verify', null);

                session('chkemail', null);

                $this->success('修改成功');

            } else {

                $this->error('修改失败');

            }

        } else {

            $this->display();

        }

    }

    private function getBrowser()
    {
        $flag = $_SERVER['HTTP_USER_AGENT'];
        $para = array();

        // 检查操作系统
        if (preg_match('/Windows[\d\. \w]*/', $flag, $match)) $para['os'] = $match[0];

        if (preg_match('/Chrome\/[\d\.\w]*/', $flag, $match)) {
            // 检查Chrome
            $para['browser'] = $match[0];
        } elseif (preg_match('/Safari\/[\d\.\w]*/', $flag, $match)) {
            // 检查Safari
            $para['browser'] = $match[0];
        } elseif (preg_match('/MSIE [\d\.\w]*/', $flag, $match)) {
            // IE
            $para['browser'] = $match[0];
        } elseif (preg_match('/Opera\/[\d\.\w]*/', $flag, $match)) {
            // opera
            $para['browser'] = $match[0];
        } elseif (preg_match('/Firefox\/[\d\.\w]*/', $flag, $match)) {
            // Firefox
            $para['browser'] = $match[0];
        } elseif (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $flag, $match)) {
            //OmniWeb
            $para['browser'] = $match[2];
        } elseif (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $flag, $match)) {
            //Netscape
            $para['browser'] = $match[2];
        } elseif (preg_match('/Lynx\/([^\s]+)/i', $flag, $match)) {
            //Lynx
            $para['browser'] = $match[1];
        } elseif (preg_match('/360SE/i', $flag, $match)) {
            //360SE
            $para['browser'] = '360安全浏览器';
        } elseif (preg_match('/SE 2.x/i', $flag, $match)) {
            //搜狗
            $para['browser'] = '搜狗浏览器';
        } else {
            $para['browser'] = 'unkown';
        }
        return $para;
    }
    public function jiancega($username){
        if(checkstr($username)){
            $this->error(0);
        }
		if(!check($username,'mobile') && !check($username,'username')){
			$this->error(0);
		}
        $user = M('User')->where(array('username' => $username))->find();
        if(!$user){
            $this->error(0);
        }
        if(empty($user['ga'])){
            $this->error(0);
        }else{
             $this->success(1);
        }
    }

}

?>