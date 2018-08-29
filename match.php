<?php
header("Content-Type: text/html; charset=UTF-8");
function http_gets($url){
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);
	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return true;
	}else{
		return false;
	}
}
if(PHP_SAPI == 'cli'){
	$domain="ddotc.com";//填写网站域名
	$queues = array(
		'Home/Queue/buydaojishi',      //同步钱包转入记录
		'Home/Queue/selldaojishi',      //处理交易状态:异常
		'Home/Queue/haopinglv'  //更新好评率
	);
	$fp = fopen("/data/tnusdcw/lockmatch.txt", "w+");
	if(flock($fp,LOCK_EX | LOCK_NB))
	{
		for($i=0;$i<count($queues);$i++){
			http_gets("http://".$domain."/".$queues[$i]);
		}
		flock($fp,LOCK_UN);
	}
	fclose($fp);
	echo "本次执行完毕";
}
?>