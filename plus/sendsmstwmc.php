<?php
session_start();
class Sms {
    function curlSMS($url,$post_fields=array())
    {
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);//用PHP取回的URL地址（值将被作为字符串）
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//使用curl_setopt获取页面内容或提交数据，有时候希望返回的内容作为变量存储，而不是直接输出，这时候希望返回的内容作为变量
    curl_setopt($ch,CURLOPT_TIMEOUT,30);//30秒超时限制
    curl_setopt($ch,CURLOPT_HEADER,1);//将文件头输出直接可见。
    curl_setopt($ch,CURLOPT_POST,1);//设置这个选项为一个零非值，这个post是普通的application/x-www-from-urlencoded类型，多数被HTTP表调用。
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post_fields);//post操作的所有数据的字符串。
    $data = curl_exec($ch);//抓取URL并把他传递给浏览器
    curl_close($ch);//释放资源
    $res = explode("\r\n\r\n",$data);//explode把他打散成为数组
    return $res[2]; //然后在这里返回数组。
    }
    //发送接口
    function sendSMS($username,$password_md5,$apikey,$mobile,$contentUrlEncode,$encode)
    {
        //发送链接（用户名，密码，apikey，手机号，内容）
        $url = "http://m.5c.com.cn/api/send/index.php?";  //如连接超时，可能是您服务器不支持域名解析，请将下面连接中的：【m.5c.com.cn】修改为IP：【115.28.23.78】
        $data=array
            (
            'username'=>$username,
            'password_md5'=>$password_md5,
            'apikey'=>$apikey,
            'mobile'=>$mobile,
            'content'=>$contentUrlEncode,
            'encode'=>$encode,
        );
        $result = $this->curlSMS($url,$data);
        //print_r($data); //测试
        return $result;
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
            echo json_encode(array('res'=>"一分钟只允许发一次短信！"));
            exit;
        }
}
$nat= isset($_GET['nat'])? checkstr($_GET['nat']):86;
$type=0;  //默认国内
if($nat!=86){
    $gjmobile=$nat.' '.$mobile;
    $type=1;
}

$encode='UTF-8';  //页面编码和短信内容编码为GBK。重要说明：如提交短信后收到乱码，请将GBK改为UTF-8测试。如本程序页面为编码格式为：ASCII/GB2312/GBK则该处为GBK。如本页面编码为UTF-8或需要支持繁体，阿拉伯文等Unicode，请将此处写为：UTF-8

$username='twszcm';  //用户名

$password_md5='ec8905987b99c70a577510deb2e4e66f';  //32位MD5密码加密，不区分大小写

$apikey='922439dd6e5d25ec4d2c1939b0ed14df';  //apikey秘钥（请登录 http://m.5c.com.cn 短信平台-->账号管理-->我的信息 中复制apikey）

//$mobile=$mobile;  //手机号,只发一个号码：13800000001。发多个号码：13800000001,13800000002,...N 。使用半角逗号分隔。

$content='尊敬的会员，您的校验码是'.$mobile_code.'。切勿把校验码泄露给其他人！【小绿股】';  //要发送的短信内容，特别注意：签名必须设置，网页验证码应用需要加添加【图形识别码】。

// $content = iconv("GBK","UTF-8",$content);

$contentUrlEncode = urlencode($content);//执行URLencode编码  ，$content = urldecode($content);解码

$result = $sms->sendSMS($username,$password_md5,$apikey,$mobile,$contentUrlEncode,$encode);  //进行发送

if(strpos($result,"success")>-1) {
	//提交成功
	    $_SESSION["a".$mobile]=time();
        $_SESSION['mobile'] = $mobile;
        $_SESSION['mobile_code'] = $mobile_code;
        $_SESSION['nat']=$nat;
        if($type==0){
             echo json_encode(array('res'=>"发送成功，请注意查收！"));
        }else{
            echo json_encode(array('res'=>"Send successfully, please check!"));

        }
        exit;
} else {
	//提交失败
	echo json_encode(array('res'=>"failed"));
}
exit；
// echo $result;  //输出result内容，查看返回值，成功为success，错误为error，（错误内容在上面有显示）



?>