<?php
namespace Home\Controller;

class VerifyController extends \Think\Controller
{
	protected function _initialize(){
		$allow_action=array("code","regss","regssemail","mytx","paypass","pass","mibao","mibaoemail","mobilebd","emailbd","findpwd","findpaypwd","myzc","paypwdemail","passemail","findpwdemail","findpaypwdemail","mytxemail","myzcemail");
		if(!in_array(ACTION_NAME,$allow_action)){
			$this->error("非法操作！");
		}
	}
	
	public function __construct() {
		parent::__construct();
	}

	public function code()
	{
		$config['useNoise'] = false;
		$config['useCurve'] = false;
		$config['length'] = 4;
		$config['codeSet'] = 'abcdfghkrstuxyz23456789';
		ob_clean();
		$verify = new \Think\Verify($config);
		$verify->entry(1);
	}

	public function regss($mobile, $verify)
	{
		// 过滤非法字符----------------S

		if (checkstr($verify)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!check_verify(strtoupper($verify),"1")) {
			$this->error('图形验证码错误!');
		}
		
		if (!check($mobile, 'mobile')) {
			$this->error('手机号码格式错误！');
		}

		if (M('User')->where(array('mobile' => $mobile))->find()) {
			$this->error('手机号码已存在！');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile,1,1);
		
		if ($code>0) {
			session('mobileregss_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}
	
	public function regssemail($email, $verify){
		// 过滤非法字符----------------S

		if (checkstr($verify)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!check_verify(strtoupper($verify),"1")) {
			$this->error('图形验证码错误!');
		}
		
		if (!check($email, 'email')) {
			$this->error('电子邮箱格式错误！');
		}

		if (M('User')->where(array('email' => $email))->find()) {
			$this->error('电子邮箱已存在！');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailregss_verify', $code);
		$content = "<p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p>";
		$result = send_email($email, '大V网', '大V网验证信息', $content);

		if ($result) {
			$this->success('邮箱验证码已发送，请查收');
		}
		else {
			$this->error('邮箱验证码发送失败，请重新点击发送');
		}
	}

	public function mytx()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('你的手机没有认证');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile,1,2);
		if ($code>0) {
			session('mytx_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}

	public function paypass()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('你的手机没有认证');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile,1,2);

		if ($code>0) {
			session('paypass_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}

	public function pass()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('你的手机没有认证');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile,1,2);

		if ($code>0) {
			session('pass_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}

	public function mibao()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('您没有绑定手机');
		}

		session('chkmobile',$mobile);
		$code=smssend($mobile,1,2);

		if ($code>0) {
			session('mibao_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}
	
	public function mibaoemail()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmibao_verify', $code);
		$content = "<p>您正在使用：vcoinpro.co</p><p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p><p>官方网址：http://vcoinpro.co</p>";

		if (send_email($email, '', '设置密保问题', $content)) {
			$this->success('邮箱验证码已发送，请查收');
		}
		else {
			$this->error('邮箱验证码发送失败，请重新点击发送');
		}
	}
	public function paypwdemail()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailpaypwd_verify', $code);
		$content = "<p>您正在使用：vcoinpro.co</p><p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p><p>官方网址：http://vcoinpro.co</p>";


		if (send_email($email, '', '修改交易密码', $content)) {
			$this->success('邮箱验证码已发送，请查收');
		}
		else {
			$this->error('邮箱验证码发送失败，请重新点击发送');
		}
	}

	public function passemail()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailpass_verify', $code);
		$content = "<p>您正在使用：vcoinpro.co</p><p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p><p>官方网址：http://vcoinpro.co</p>";

		

		if (send_email($email, '', '修改登录密码', $content)) {
			$this->success('邮箱验证码已发送，请查收');
		}
		else {
			$this->error('邮箱验证码发送失败，请重新点击发送');
		}
	}

	public function mytxemail()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmytx_verify', $code);
		$content = "<p>您正在使用：vcoinpro.co</p><p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p><p>官方网址：http://vcoinpro.co</p>";

		if (send_email($email, '', '人民币提现', $content)) {
			$this->success('邮箱验证码已发送，请查收');
		}
		else {
			$this->error('邮箱验证码发送失败，请重新点击发送');
		}
	}

	public function myzcemail()
	{
		if (!userid()) {
			$this->error('请先登录');
		}

		$email = M('User')->where(array('id' => userid()))->getField('email');

		if (!$email) {
			$this->error('您没有绑定邮箱');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailmyzc_verify', $code);
		$content = "<p>您正在使用：vcoinpro.co</p><p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p><p>官方网址：http://vcoinpro.co</p>";
		

		if (send_email($email, '', '虚拟币提现', $content)) {
			$this->success('邮箱验证码已发送，请查收');
		}
		else {
			$this->error('邮箱验证码发送失败，请重新点击发送');
		}
	}

	public function mobilebd($mobile,$quhao)
	{

		// 过滤非法字符----------------S

		if (checkstr($mobile)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录');
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

		if (M('User')->where(array('mobile' => $mobile))->find()) {
			$this->error('手机号码已存在！');
		}

		$code = rand(111111, 999999);
		session('chkmobile',$mobile);
		//美联软通
		// $code = smssend($mobile,$quhao);
		$code = smssend($mobile,1,2);
		// $code = sendsms_shantong_yzm($mobile,$quhao);
		//阿里云短信
		// $jieguo = sendsms_aliyun_yzm($mobile,$code);

		if ($code >0) {
			session('mobilebd_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}
	
	public function emailbd($email)
	{

		// 过滤非法字符----------------S

		if (checkstr($email)) {
			$this->error('您输入的信息有误！');
		}

		// 过滤非法字符----------------E


		if (!userid()) {
			$this->error('请先登录');
		}
		
		if (!check($email, 'email')) {
			$this->error('邮箱格式错误！');
		}

		if (M('User')->where(array('email' => $email))->find()) {
			$this->error('邮箱已存在！');
		}

		$code = rand(111111, 999999);
		session('chkemail',$email);
		session('emailbd_verify', $code);
		$content = "<p>您正在使用：vcoinpro.co</p><p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p><p>官方网址：http://vcoinpro.co</p>";

		

		if (send_email($email, '', '绑定邮箱', $content)) {
			$this->success('邮箱验证码已发送，请查收');
		}
		else {
			$this->error('邮箱验证码发送失败，请重新点击发送');
		}
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

			if (!check_verify(strtoupper($input['verify']),"1")) {
				$this->error('图形验证码错误!');
			}

			if (!check($input['mobile'], 'mobile')) {
				$this->error('手机号码格式错误！');
			}

			$user = M('User')->where(array('mobile' => $input['mobile']))->find();

			if (!$user) {
				$this->error('用户不存在！');
			}

			if ($user['mobile'] != $input['mobile']) {
				$this->error('手机号码错误！');
			}

			session('chkmobile',$user['mobile']);
			$code = smssend($user['mobile'],1,2);

			if ($code>0) {
				session('findpwd_verify', $code);
				$this->success('短信验证码已发送到你的手机，请查收');
			}
			else {
				$this->error('短信验证码发送失败，请重新点击发送');
			}
		}
	}
	
	public function findpwdemail()
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

			if (!check_verify(strtoupper($input['verify']),"1")) {
				$this->error('图形验证码错误!');
			}

			if (!check($input['email'], 'email')) {
				$this->error('电子邮箱格式错误！');
			}

			$user = M('User')->where(array('email' => $input['email']))->find();

			if (!$user) {
				$this->error('用户不存在！');
			}

			if ($user['email'] != $input['email']) {
				$this->error('电子邮箱错误！');
			}

			$code = rand(111111, 999999);
			session('chkemail',$user['email']);
			session('emailfindpwd_verify', $code);
			$content = "<p>您正在使用：vcoinpro.co</p><p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p><p>官方网址：http://vcoinpro.co</p>";
			// $content = "验证信息:".$code;
			if (send_email($user['email'], '', '找回登录密码', $content)) {

				$this->success('邮箱验证码已发送，请查收');
			} else {
				$this->error('邮箱验证码发送失败，请重新点击发送');
			}
		}
	}
	
	public function findpaypwdemail(){
		if (IS_POST) {
			$input = I('post.');

			foreach ($input as $k => $v) {
				// 过滤非法字符----------------S

				if (checkstr($v)) {
					$this->error('您输入的信息有误！');
				}

				// 过滤非法字符----------------E
			}

			if (!check_verify(strtoupper($input['verify']),"1")) {
				$this->error('图形验证码错误!');
			}

			if (!check($input['email'], 'email')) {
				$this->error('电子邮箱格式错误！');
			}

			$user = M('User')->where(array('email' => $input['email']))->find();

			if (!$user) {
				$this->error('用户不存在！');
			}

			if ($user['email'] != $input['email']) {
				$this->error('电子邮箱错误！');
			}

			$code = rand(111111, 999999);
			session('chkemail',$user['email']);
			session('emailfindpaypwd_verify', $code);
			$content = "<p>验证码：".$code."。该验证码非常重要，请勿将此邮件泄露给任何人。</p><p>系统发言，请勿回信。</p>";

			
			if (send_email($user['email'], '大V网', '找回交易密码', $content)) {
				$this->success('邮箱验证码已发送，请查收');
			}
			else {
				$this->error('邮箱验证码发送失败，请重新点击发送');
			}
		}
	}

	public function findpaypwd()
	{
		$input = I('post.');

		foreach ($input as $k => $v) {
			// 过滤非法字符----------------S

			if (checkstr($v)) {
				$this->error('您输入的信息有误！');
			}

			// 过滤非法字符----------------E
		}

		if (!check_verify(strtoupper($input['verify']),"1")) {
				$this->error('图形验证码错误!');
		}

		if (!check($input['mobile'], 'mobile')) {
			$this->error('手机号码格式错误！');
		}

		$user = M('User')->where(array('mobile' => $input['mobile']))->find();

		if (!$user) {
			$this->error('用户名不存在！');
		}

		if ($user['mobile'] != $input['mobile']) {
			$this->error('手机号码错误！');
		}

		session('chkmobile',$user['mobile']);
		$code = smssend($user['mobile'],1,2);

		if ($code>0) {
			session('findpaypwd_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}

	public function myzc()
	{
		if (!userid()) {
			$this->error('您没有登录请先登录!');
		}

		$mobile = M('User')->where(array('id' => userid()))->getField('mobile');

		if (!$mobile) {
			$this->error('你的手机没有认证');
		}

		session('chkmobile',$mobile);
		$code = smssend($mobile,1,2);

		if ($code>0) {
			session('myzc_verify', $code);
			$this->success('短信验证码已发送到你的手机，请查收');
		}
		else {
			$this->error('短信验证码发送失败，请重新点击发送');
		}
	}
}

?>