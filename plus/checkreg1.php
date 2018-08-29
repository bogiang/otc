<?php
require ('../common.inc.php');
//判断手机验证码  
if($_POST["action"]=="reg1"){
	$phone = checkstr($_POST['rgmobile']);
	$pcode = checkstr($_POST['pcode']);
	if(empty($pcode)){
		echo json_encode(array('res'=>'验证码不能为空'));
		exit;
	}

	$mobile_code = $_SESSION['mobile_code'];
	if($mobile_code!=$pcode){
		echo json_encode(array('res'=>'验证码错误'));
		exit;
	}
	
	if($_SESSION['mobile']!=$phone){
		echo json_encode(array('res'=>'手机号码不对应'));
		exit;
	}

	$_SESSION['phone']=$phone;
	$_SESSION['pcode']=$pcode;
	echo json_encode(array('res'=>'验证通过'));
	exit;
}
?>