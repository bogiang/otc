<?php
session_start();
class Sms {
    function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }
    function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }
    function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }
}
function checkstr($strsql)
{     //检测字符串是否有注入风险
       
    $strsql=trim($strsql);
    $check=preg_match('/select|or|and|char|create|drop|database|table|insert|script|function|update|delete|exec|system|passthru|shell_exec|<|\`|\%|\"|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i',$strsql);
  
    if($check)
    {   
        if($strsql !='chark_sheng' ){
            echo "<script language='javascript'>alert('您输入的信息存在非法字符！');history.go(-1)</script>";
            exit;
        }
    }        
    return  $strsql;                      
               
}
$mobile = checkstr($_GET['phone']);
$sms=new Sms();
$mobile_code = $sms->random(6,1);
if(empty($mobile)){
	echo json_encode(array('res'=>'手机号码不能为空'));
	exit;
}
if(!empty($_SESSION["a".$mobile])){
        if(time()-$_SESSION["a".$mobile]<60){
            echo json_encode(array('res'=>'一分钟只允许发一次短信！'));
            exit;
        }
}
$nat= isset($_GET['nat'])? checkstr($_GET['nat']):86;
$type=0;  //默认国内
if($nat!=86){
    $gjmobile=$nat.' '.$mobile;
    $type=1;
}

if($type==0){
    $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
    $post_data = "account=cf_lideguoji&password=b0d7b06c60144ff511ad3c8af041e7da&mobile=".$mobile."&content=".rawurlencode("尊敬的会员，您的校验码是".$mobile_code."。切勿把校验码泄露给其他人！");
}else{
    $target = "http://api.isms.ihuyi.com/webservice/isms.php?method=Submit";
    $post_data = "account=cf_lideguoji&password=a3812492cc3893c8b30792e50d2b9066&mobile=".$gjmobile."&content=Your verification code is ".$mobile_code;
}

$gets = $sms->xml_to_array($sms->Post($post_data, $target));
if($gets['SubmitResult']['code']==2){
    $_SESSION["a".$mobile]=time();
	$_SESSION['mobile'] = $mobile;
	$_SESSION['mobile_code'] = $mobile_code;
    $_SESSION['nat']=$nat;
   
    // if($type==0){
    //     echo json_encode(array('res'=>"发送成功，请注意查收！"));
    // }else{
    //     echo json_encode(array('res'=>"Send successfully, please check!"));

    // }
    echo json_encode(array("res"=>$mobile_code));
	
	exit;
}else{
     echo json_encode(array('res'=>"failed"));
}
?>