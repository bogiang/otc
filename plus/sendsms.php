<?php
    // session_start();
    include 'api_sdk/aliyun-php-sdk-core/Config.php';
    include_once 'api_sdk/Dysmsapi/Request/V20170525/SendSmsRequest.php';
    include_once 'api_sdk/Dysmsapi/Request/V20170525/QuerySendDetailsRequest.php';

    $mobile = isset($_GET['phone']) ? trim($_GET['phone']) : '';
    $mobile_code = isset($_GET['code']) ? trim($_GET['code']) : '';
    $new_code = isset($_GET['new_code']) ? trim($_GET['new_code']) : '';

    $s_new_code = md5(md5($mobile.'coin'.$mobile_code));


    if($s_new_code != $new_code){
        echo 'shibai';exit;
    }


    //此处需要替换成自己的AK信息
//    $accessKeyId = "LTAIboHXhYnEOqFO";
//    $accessKeySecret = "JswLGqpAAx4R84JFGuF20wvzaslYvp";
    $accessKeyId = "LTAI7iWgNQrHTNWv";
    $accessKeySecret = "kFOZtT8wyAmvYUryg31OhECutlf7kY";
    //短信API产品名
    $product = "Dysmsapi";
    //短信API产品域名
    $domain = "dysmsapi.aliyuncs.com";
    //暂时不支持多Region
    $region = "cn-hangzhou";

    //初始化访问的acsCleint
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
    $acsClient= new DefaultAcsClient($profile);

    $request = new Dysmsapi\Request\V20170525\SendSmsRequest;
    //必填-短信接收号码
    $request->setPhoneNumbers($mobile);
    //必填-短信签名
    $request->setSignName("币拉拉");
    //必填-短信模板Code
    if($mobile_code){
        $request->setTemplateCode("SMS_116560543");
        // //选填-假如模板中存在变量需要替换则为必填(JSON格式)
        $request->setTemplateParam("{\"code\":\"".$mobile_code."\"}");
    }else{
        $request->setTemplateCode("SMS_116590616");
    }
    

    

    //发起访问请求
    $acsResponse = $acsClient->getAcsResponse($request);
    // var_dump($acsResponse);
    $acsResponse =json_decode( json_encode( $acsResponse ), true );
    // var_dump($acsResponse);
    if($acsResponse['Code']=='OK'){
        echo 'success';exit;
    }else{
        echo 'shibai';exit;
    }


