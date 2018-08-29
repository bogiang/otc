<?php
require dirname(__FILE__).'/secure.php';
if(!defined('WHERECOME')){
	define('WHERECOME','Home');
}
return array(
	'DB_TYPE'              => DB_TYPE,
	'DB_HOST'              => '127.0.0.1',
	'DB_NAME'              => 'otc',
	'DB_USER'              => 'root',
	'DB_PWD'               => 'root',
	'DB_PORT'              => '3306',
	'DB_PREFIX'            => 'tw_',
	'ACTION_SUFFIX'        => '',
	'MULTI_MODULE'         => true,
	'MODULE_DENY_LIST'     => array('Common', 'Runtime'),
	'MODULE_ALLOW_LIST'    => array('Home', 'Admin'),
	'DEFAULT_MODULE'       => WHERECOME,
	'URL_CASE_INSENSITIVE' => false,
	'URL_MODEL'            => 2,
	'DATA_CACHE_KEY'=>'sTJJPF6Kw1fcu8vV',
	'DATA_CACHE_PREFIX'=>'xYTwfbglvr',
	'DB_Chat' => array(
		'db_type' => DB_TYPE,
		'db_host' => '127.0.0.1',
		'db_name' => 'otc',
		'db_user' => 'root',
		'db_pwd' => 'root',
		'db_port' => '3306',
	),
	'think_email' => array(
		'smtp_host' => 'smtp.mxhichina.com', //SMTP服务器
		'smtp_port' => '465', //SMTP服务器端口
		'smtp_user' => 'admin@vcoinpro.co', //SMTP服务器用户名
		'smtp_pass' => 'DAvuelrpi1@128', //SMTP服务器密码
		'from_email' => 'admin@vcoinpro.co',
		'from_name' => 'admin', //发件人名称
		'reply_email' => '', //回复EMAIL（留空则为发件人EMAIL）
		'reply_name' => '', //回复名称（留空则为发件人名称）
		'session_expire'=>'72',
	), 
);
?>
