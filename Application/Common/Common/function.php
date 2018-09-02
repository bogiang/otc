<?php
if (!function_exists('array_column')) {
	function array_column(array $input, $columnKey, $indexKey = NULL)
	{
		$result = array();

		if (NULL === $indexKey) {
			if (NULL === $columnKey) {
				$result = array_values($input);
			}
			else {
				foreach ($input as $row) {
					$result[] = $row[$columnKey];
				}
			}
		}
		else if (NULL === $columnKey) {
			foreach ($input as $row) {
				$result[$row[$indexKey]] = $row;
			}
		}
		else {
			foreach ($input as $row) {
				$result[$row[$indexKey]] = $row[$columnKey];
			}
		}

		return $result;
	}
}

// 资金变更日志（前后台都含有）

/*
*币种类型 opstype($number,1)
*动作类型 opstype($number,2)
*动作类型数组 opstype($number,88)
*
*/

function opstype($number,$type)
{
	$ops_array = array(
		0 => '其他',
		//1 => '充值(可用)',
		//2 => '提现',
		3 => '加减币',
		//4 => '提现撤销',
		//5 => '提现申请(可用)',
		6 => '转出申请',
		7 => '站内转入',
		/*10 => '买入(可用)',
		11 => '卖出(可用)',
		12 => '买入差价(可用)',
		13 => '买入(冻结)',
		14 => '卖出(冻结)',
		15 => '清理(冻结)',
		16 => '撤销买入(可用)',
		17 => '撤销卖出(可用)',
		18 => '委托买入(可用)',
		19 => '委托卖出(可用)',
		20 => '委托买入(冻结)',
		21 => '委托卖出(冻结)',
		22 => '买入差价(冻结)',
		23 => '清理(可用)',
		24 => '提现申请撤销(可用)',
		25 => '撤销买入(冻结)',
		26 => '撤销卖出(冻结)',*/
		27 =>'转出撤销',
		28 =>'购买商品'
	);

	if($type == 2){
		return $ops_array[$number];
	}else if($type == 88){
		return $ops_array;
	}
}

function authgame($name)
{
	if (!check($name, 'w')) {
		return 0;
		exit();
	}

	if (M('VersionGame')->where(array('name' => $name, 'status' => 1))->find()) {
		return 1;
	}
	else {
		return 0;
		exit();
	}
}

function getUrl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	$data = curl_exec($ch);
	return $data;
}

function huafei($mobile = NULL, $num = NULL, $orderid = NULL)
{
	if (empty($mobile)) {
		return NULL;
	}

	if (empty($num)) {
		return NULL;
	}

	if (empty($orderid)) {
		return NULL;
	}

	header('Content-type:text/html;charset=utf-8');
	$appkey = C('huafei_appkey');
	$openid = C('huafei_openid');
	$recharge = new \Common\Ext\Recharge($appkey, $openid);
	$telRechargeRes = $recharge->telcz($mobile, $num, $orderid);

	if ($telRechargeRes['error_code'] == '0') {
		return 1;
	}
	else {
		return NULL;
	}
}

function mlog($text)
{
	$text = addtime(time()) . ' ' . $text . "\n";
	file_put_contents(APP_PATH . '/../sitetrade.log', $text, FILE_APPEND);
}

function authUrl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	$data = curl_exec($ch);
	return $data;
}

function userid($username = NULL, $type = 'username')
{
	if ($username && $type) {
		$userid = (APP_DEBUG ? NULL : S('userid' . $username . $type));

		if (!$userid) {
			$userid = M('User')->where(array($type => $username))->getField('id');
			S('userid' . $username . $type, $userid);
		}
	}
	else {
		$userid = session('userId');
	}

	return $userid ? $userid : NULL;
}

function username($id = NULL, $type = 'id')
{
	if ($id && $type) {
		$username = (APP_DEBUG ? NULL : S('username' . $id . $type));

		if (!$username) {
			$username = M('User')->where(array($type => $id))->getField('username');
			S('username' . $id . $type, $username);
		}
	}
	else {
		$username = session('userName');
	}

	return $username ? $username : NULL;
}


function op_t($text, $addslanshes = false)
{
	$text = nl2br($text);
	$text = real_strip_tags($text);

	if ($addslanshes) {
		$text = addslashes($text);
	}

	$text = trim($text);
	return $text;
}

function text($text, $addslanshes = false)
{
	return op_t($text, $addslanshes);
}

function html($text)
{
	return op_h($text);
}

function op_h($text, $type = 'html')
{
	$text_tags = '';
	$link_tags = '<a>';
	$image_tags = '<img>';
	$font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
	$base_tags = $font_tags . '<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
	$form_tags = $base_tags . '<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
	$html_tags = $base_tags . '<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>';
	$all_tags = $form_tags . $html_tags . '<!DOCTYPE><meta><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
	$text = real_strip_tags($text, $$type . '_tags');

	if ($type != 'all') {
		while (preg_match('/(<[^><]+)(ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background[^-]|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat)) {
			$text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
		}

		while (preg_match('/(<[^><]+)(window\\.|javascript:|js:|about:|file:|document\\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
			$text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
		}
	}

	return $text;
}

function real_strip_tags($str, $allowable_tags = '')
{
	return strip_tags($str, $allowable_tags);
}

function clean_cache($dirname = './Runtime/')
{
	$dirs = array($dirname);

	foreach ($dirs as $value) {
		rmdirr($value);
	}

	@(mkdir($dirname, 511, true));
}

function getSubByKey($pArray, $pKey = '', $pCondition = '')
{
	$result = array();

	if (is_array($pArray)) {
		foreach ($pArray as $temp_array) {
			if (is_object($temp_array)) {
				$temp_array = (array) $temp_array;
			}

			if ((('' != $pCondition) && ($temp_array[$pCondition[0]] == $pCondition[1])) || ('' == $pCondition)) {
				$result[] = ('' == $pKey ? $temp_array : isset($temp_array[$pKey]) ? $temp_array[$pKey] : '');
			}
		}

		return $result;
	}
	else {
		return false;
	}
}

function debug($value, $type = 'DEBUG', $verbose = false, $encoding = 'UTF-8')
{
	if (M_DEBUG || MSCODE) {
		if (!IS_CLI) {
			Common\Ext\FirePHP::getInstance(true)->log($value, $type);
		}
	}
}

function CoinClient($username, $password, $ip, $port, $timeout = 3, $headers = array(), $suppress_errors = false)
{
	return new \Common\Ext\CoinClient($username, $password, $ip, $port, $timeout, $headers, $suppress_errors);
}

function coinname($type){
	if( !S('COINNAME') ){
		$coin_list = D('Coin')->get_all_name_list();
		S('COINNAME',$coin_list,3600);
	}
	$coinname = S('COINNAME');
	return $coinname[$type];

}

function createQRcode($save_path, $qr_data = 'PHP QR Code :)', $qr_level = 'L', $qr_size = 4, $save_prefix = 'qrcode')
{
	if (!isset($save_path)) {
		return '';
	}

	$PNG_TEMP_DIR = &$save_path;
	vendor('PHPQRcode.class#phpqrcode');

	if (!file_exists($PNG_TEMP_DIR)) {
		mkdir($PNG_TEMP_DIR);
	}

	$filename = $PNG_TEMP_DIR . 'test.png';
	$errorCorrectionLevel = 'L';

	if (isset($qr_level) && in_array($qr_level, array('L', 'M', 'Q', 'H'))) {
		$errorCorrectionLevel = &$qr_level;
	}

	$matrixPointSize = 4;

	if (isset($qr_size)) {
		$matrixPointSize = &min(max((int) $qr_size, 1), 10);
	}

	if (isset($qr_data)) {
		if (trim($qr_data) == '') {
			exit('data cannot be empty!');
		}

		$filename = $PNG_TEMP_DIR . $save_prefix . md5($qr_data . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
		QRcode::png($qr_data, $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);
	}
	else {
		QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);
	}

	if (file_exists($PNG_TEMP_DIR . basename($filename))) {
		return basename($filename);
	}
	else {
		return false;
	}
}

function NumToStr($num)
{
	if (!$num) {
		return $num;
	}else{
		return number_format($num,2,'.','');
	}
}

function Num($num)
{
	if (!$num) {
		return $num;
	}

	if ($num == 0) {
		return 0;
	}

	$num = round($num, 8);
	$min = 0.0001;

	if ($num <= $min) {
		$times = 0;

		while ($num <= $min) {
			$num *= 10;
			$times++;

			if (10 < $times) {
				break;
			}
		}

		$arr = explode('.', $num);
		$arr[1] = str_repeat('0', $times) . $arr[1];
		return $arr[0] . '.' . $arr[1] . '';
	}

	return ($num * 1) . '';
}

function check_verify($code, $id = ".cn")
{
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

function get_city_ip($ip = NULL)
{
	if (empty($ip)) {
		$ip = get_client_ip();
	}

	$Ip = new Org\Net\IpLocation();
	$area = $Ip->getlocation($ip);
	$str = $area['country'] . $area['area'];
	$str = mb_convert_encoding($str, 'UTF-8', 'GBK');

	if (($ip == '127.0.0.1') || ($str == false) || ($str == 'IANA保留地址用于本地回送')) {
		$str = '未分配或者内网IP';
	}

	return $str;
}

function send_post($url, $post_data)
{
	$postdata = http_build_query($post_data);
	$options = array(
		'http' => array('method' => 'POST', 'header' => 'Content-type:application/x-www-form-urlencoded', 'content' => $postdata, 'timeout' => 15 * 60)
		);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	return $result;
}

function request_by_curl($remote_server, $post_string)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $remote_server);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'mypost=' . $post_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'qianyunlai.com\'s CURL Example beta');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function tradeno()
{
	return substr(str_shuffle('ABCDEFGHIJKLMNPQRSTUVWXYZ'), 0, 2) . substr(str_shuffle(str_repeat('123456789', 4)), 0, 9);
}

function tradenoa()
{
	return substr(str_shuffle('ABCDEFGHIJKLMNPQRSTUVWXYZ'), 0, 9);
}

function tradenob()
{
	return substr(str_shuffle(str_repeat('123456789', 4)), 0, 2);
}

function get_user($id, $type = NULL, $field = 'id')
{
	$key = md5('get_user' . $id . $type . $field);
	$data = S($key);

	if (!$data) {
		$data = M('User')->where(array($field => $id))->find();
		S($key, $data);
	}

	if ($type) {
		$rs = $data[$type];
	}
	else {
		$rs = $data;
	}

	return $rs;
}

function ismobile()
{
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}

	if (isset($_SERVER['HTTP_CLIENT']) && ('PhoneClient' == $_SERVER['HTTP_CLIENT'])) {
		return true;
	}

	if (isset($_SERVER['HTTP_VIA'])) {
		return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
	}

	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');

		if (preg_match('/(' . implode('|', $clientkeywords) . ')/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}

	if (isset($_SERVER['HTTP_ACCEPT'])) {
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && ((strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false) || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}

	return false;
}

function send_mobiles($mobile, $content)
{
	debug(array($content, $mobile), 'send_mobile');
	$url = C('mobile_url') . '/?Uid=' . C('mobile_user') . '&Key=' . C('mobile_pwd') . '&smsMob=' . $mobile . '&smsText=' . $content;

	if (function_exists('file_get_contents')) {
		$file_contents = file_get_contents($url);
	}
	else {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
	}

	return $file_contents;
}


function addtime($time = NULL, $type = NULL)
{
	if (empty($time)) {
		return '---';
	}

	if (($time < 2545545) && (1893430861 < $time)) {
		return '---';
	}

	if (empty($type)) {
		$type = 'Y-m-d H:i:s';
	}

	return date($type, $time);
}


function check_enname($str){
    if(preg_match("/^[a-zA-Z]\w{3,15}$/",$str)){
          return true;
    }else{
        return false;
    }
}



function check($data, $rule = NULL, $ext = NULL)
{
	$data = trim(str_replace(PHP_EOL, '', $data));

//	if (empty($data)) {
//		return false;
//	}

	$validate['require'] = '/.+/';
	$validate['url'] = '/^http(s?):\\/\\/(?:[A-za-z0-9-]+\\.)+[A-za-z]{2,4}(?:[\\/\\?#][\\/=\\?%\\-&~`@[\\]\':+!\\.#\\w]*)?$/';
	$validate['currency'] = '/^\\d+(\\.\\d+)?$/';
	$validate['number'] = '/^\\d+$/';
	$validate['zip'] = '/^\\d{6}$/';
	$validate['cny'] = '/^(([1-9]{1}\\d*)|([0]{1}))(\\.(\\d){1,2})?$/';
	$validate['integer'] = '/^[\\+]?\\d+$/';
	$validate['double'] = '/^[\\+]?\\d+(\\.\\d+)?$/';
	$validate['english'] = '/^[A-Za-z]+$/';
	$validate['idcard'] = '/^([0-9]{15}|[0-9]{17}[0-9a-zA-Z])$/';
	$validate['truename'] = '/^[\\x{4e00}-\\x{9fa5}A-Za-z\s]{2,20}$/u';
	$validate['username'] = '/^[a-zA-Z]{1}[0-9a-zA-Z_]{5,15}$/';
	$validate['email'] = '/^\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*$/';
	$validate['mobile'] = '/^1(([3][0123456789])|([4][0123456789])|([5][0123456789])|([7][0123456789])|([8][0123456789]))[0-9]{8}$/';
	// $validate['password'] = '/^[a-zA-Z0-9_\\@\\#\\$\\%\\^\\&\\*\\(\\)\\!\\,\\.\\?\\-\\+\\|\\=]{6,16}$/';
	$validate['password'] = '/^[a-zA-Z0-9]{6,16}$/';
	$validate['xnb'] = '/^[a-zA-Z]{2,10}$/';
	$validate['margin'] = '/^[+-]?(([1-9]\\d{0,1})|(0))(\\.\\d{1,2})?$/';
	$validate['duetime'] = '/^0*$|^0*[5-9]$|^[1-3][0-9]$|^[1-5][0-9]|60$/';

	if (isset($validate[strtolower($rule)])) {
		$rule = $validate[strtolower($rule)];
		return preg_match($rule, $data);
	}

	$Ap = '\\x{4e00}-\\x{9fff}' . '0-9a-zA-Z\\@\\#\\$\\%\\^\\&\\*\\(\\)\\!\\,\\.\\?\\-\\+\\|\\=';
	$Bp = '，。？！“”：；￥（）@@.,\s!?';
	$Cp = '\x{4e00}-\x{9fa5}';
	$Dp = '0-9';
	$Wp = 'a-zA-Z';
	$Np = 'a-z';
	$Tp = '@#$%^&*()-+=';
	$_p = '_';
	$pattern = '/^[';
	$OArr = str_split(strtolower($rule));
	in_array('a', $OArr) && ($pattern .= $Ap);
	in_array('b', $OArr) && ($pattern .= $Bp);
	in_array('c', $OArr) && ($pattern .= $Cp);
	in_array('d', $OArr) && ($pattern .= $Dp);
	in_array('w', $OArr) && ($pattern .= $Wp);
	in_array('n', $OArr) && ($pattern .= $Np);
	in_array('t', $OArr) && ($pattern .= $Tp);
	in_array('_', $OArr) && ($pattern .= $_p);
	isset($ext) && ($pattern .= $ext);
	$pattern .= ']+$/u';
	return preg_match($pattern, $data);
}

function check_arr($rs)
{
	foreach ($rs as $v) {
		if (!$v) {
			return false;
		}
	}

	return true;
}

function maxArrayKey($arr, $key)
{
	$a = 0;

	foreach ($arr as $k => $v) {
		$a = max($v[$key], $a);
	}

	return $a;
}

function arr2str($arr, $sep = ',')
{
	return implode($sep, $arr);
}

function str2arr($str, $sep = ',')
{
	return explode($sep, $str);
}

function url($link = '', $param = '', $default = '')
{
	return $default ? $default : U($link, $param);
}

function rmdirr($dirname)
{
	if (!file_exists($dirname)) {
		return false;
	}

	if (is_file($dirname) || is_link($dirname)) {
		return unlink($dirname);
	}

	$dir = dir($dirname);

	if ($dir) {
		while (false !== $entry = $dir->read()) {
			if (($entry == '.') || ($entry == '..')) {
				continue;
			}

			rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
		}
	}

	$dir->close();
	return rmdir($dirname);
}

function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
	$tree = array();

	if (is_array($list)) {
		$refer = array();

		foreach ($list as $key => $data) {
			$refer[$data[$pk]] = &$list[$key];
		}

		foreach ($list as $key => $data) {
			$parentId = $data[$pid];

			if ($root == $parentId) {
				$tree[] = &$list[$key];
			}
			else if (isset($refer[$parentId])) {
				$parent = &$refer[$parentId];
				$parent[$child][] = &$list[$key];
			}
		}
	}

	return $tree;
}

function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array())
{
	if (is_array($tree)) {
		$refer = array();

		foreach ($tree as $key => $value) {
			$reffer = $value;

			if (isset($reffer[$child])) {
				unset($reffer[$child]);
				tree_to_list($value[$child], $child, $order, $list);
			}

			$list[] = $reffer;
		}

		$list = list_sort_by($list, $order, $sortby = 'asc');
	}

	return $list;
}

function list_sort_by($list, $field, $sortby = 'asc')
{
	if (is_array($list)) {
		$refer = $resultSet = array();

		foreach ($list as $i => $data) {
			$refer[$i] = &$data[$field];
		}

		switch ($sortby) {
		case 'asc':
			asort($refer);
			break;

		case 'desc':
			arsort($refer);
			break;

		case 'nat':
			natcasesort($refer);
		}

		foreach ($refer as $key => $val) {
			$resultSet[] = &$list[$key];
		}

		return $resultSet;
	}

	return false;
}

function list_search($list, $condition)
{
	if (is_string($condition)) {
		parse_str($condition, $condition);
	}

	$resultSet = array();

	foreach ($list as $key => $data) {
		$find = false;

		foreach ($condition as $field => $value) {
			if (isset($data[$field])) {
				if (0 === strpos($value, '/')) {
					$find = preg_match($value, $data[$field]);
				}
				else if ($data[$field] == $value) {
					$find = true;
				}
			}
		}

		if ($find) {
			$resultSet[] = &$list[$key];
		}
	}

	return $resultSet;
}

function d_f($name, $value, $path = DATA_PATH)
{
	if (APP_MODE == 'sae') {
		return false;
	}

	static $_cache = array();
	$filename = $path . $name . '.php';

	if ('' !== $value) {
		if (is_null($value)) {
		}
		else {
			$dir = dirname($filename);

			if (!is_dir($dir)) {
				mkdir($dir, 493, true);
			}

			$_cache[$name] = $value;
			$content = strip_whitespace('<?php' . "\t" . 'return ' . var_export($value, true) . ';?>') . PHP_EOL;
			return file_put_contents($filename, $content, FILE_APPEND);
		}
	}

	if (isset($_cache[$name])) {
		return $_cache[$name];
	}

	if (is_file($filename)) {
		$value = include $filename;
		$_cache[$name] = $value;
	}
	else {
		$value = false;
	}

	return $value;
}

function DownloadFile($fileName)
{
	ob_end_clean();
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Length: ' . filesize($fileName));
	header('Content-Disposition: attachment; filename=' . basename($fileName));
	readfile($fileName);
}

function download_file($file, $o_name = '')
{
	if (is_file($file)) {
		$length = filesize($file);
		$type = mime_content_type($file);
		$showname = ltrim(strrchr($file, '/'), '/');

		if ($o_name) {
			$showname = $o_name;
		}

		header('Content-Description: File Transfer');
		header('Content-type: ' . $type);
		header('Content-Length:' . $length);

		if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
			header('Content-Disposition: attachment; filename="' . rawurlencode($showname) . '"');
		}
		else {
			header('Content-Disposition: attachment; filename="' . $showname . '"');
		}

		readfile($file);
		exit();
	}
	else {
		exit('文件不存在');
	}
}

function wb_substr($str, $len = 140, $dots = 1, $ext = '')
{
	$str = htmlspecialchars_decode(strip_tags(htmlspecialchars($str)));
	$strlenth = 0;
	$output = '';
	preg_match_all('/[' . "\x1" . '-]|[' . "\xc2" . '-' . "\xdf" . '][' . "\x80" . '-' . "\xbf" . ']|[' . "\xe0" . '-' . "\xef" . '][' . "\x80" . '-' . "\xbf" . ']{2}|[' . "\xf0" . '-' . "\xff" . '][' . "\x80" . '-' . "\xbf" . ']{3}/', $str, $match);

	foreach ($match[0] as $v) {
		preg_match('/[' . "\xe0" . '-' . "\xef" . '][' . "\x80" . '-' . "\xbf" . ']{2}/', $v, $matchs);

		if (!empty($matchs[0])) {
			$strlenth += 1;
		}
		else if (is_numeric($v)) {
			$strlenth += 0.54500000000000004;
		}
		else {
			$strlenth += 0.47499999999999998;
		}

		if ($len < $strlenth) {
			$output .= $ext;
			break;
		}

		$output .= $v;
	}

	if (($len < $strlenth) && $dots) {
		$output .= '...';
	}

	return $output;
}

function msubstr($str, $start = 0, $length, $charset = 'utf-8', $suffix = true)
{
	if (function_exists('mb_substr')) {
		$slice = mb_substr($str, $start, $length, $charset);
	}
	else if (function_exists('iconv_substr')) {
		$slice = iconv_substr($str, $start, $length, $charset);

		if (false === $slice) {
			$slice = '';
		}
	}
	else {
		$re['utf-8'] = '/[' . "\x1" . '-]|[' . "\xc2" . '-' . "\xdf" . '][' . "\x80" . '-' . "\xbf" . ']|[' . "\xe0" . '-' . "\xef" . '][' . "\x80" . '-' . "\xbf" . ']{2}|[' . "\xf0" . '-' . "\xff" . '][' . "\x80" . '-' . "\xbf" . ']{3}/';
		$re['gb2312'] = '/[' . "\x1" . '-]|[' . "\xb0" . '-' . "\xf7" . '][' . "\xa0" . '-' . "\xfe" . ']/';
		$re['gbk'] = '/[' . "\x1" . '-]|[' . "\x81" . '-' . "\xfe" . '][@-' . "\xfe" . ']/';
		$re['big5'] = '/[' . "\x1" . '-]|[' . "\x81" . '-' . "\xfe" . ']([@-~]|' . "\xa1" . '-' . "\xfe" . '])/';
		preg_match_all($re[$charset], $str, $match);
		$slice = join('', array_slice($match[0], $start, $length));
	}

	if($suffix && strlen($str) > $length){
		return $slice . '...';
	}else{
		return $slice;
	}
}

function highlight_map($str, $keyword)
{
	return str_replace($keyword, '<em class=\'keywords\'>' . $keyword . '</em>', $str);
}

function del_file($file)
{
	$file = file_iconv($file);
	@(unlink($file));
}

function status_text($model, $key)
{
	if ($model == 'Nav') {
		$text = array('无效', '有效');
	}

	return $text[$key];
}

function user_auth_sign($user)
{
	ksort($user);
	$code = http_build_query($user);
	$sign = sha1($code);
	return $sign;
}

function get_link($link_id = NULL, $field = 'url')
{
	$link = '';

	if (empty($link_id)) {
		return $link;
	}

	$link = D('Url')->getById($link_id);

	if (empty($field)) {
		return $link;
	}
	else {
		return $link[$field];
	}
}

function get_cover($cover_id, $field = NULL)
{
	if (empty($cover_id)) {
		return false;
	}

	$picture = D('Picture')->where(array('status' => 1))->getById($cover_id);

	if ($field == 'path') {
		if (!empty($picture['url'])) {
			$picture['path'] = $picture['url'];
		}
		else {
			$picture['path'] = __ROOT__ . $picture['path'];
		}
	}

	return empty($field) ? $picture : $picture[$field];
}

function get_admin_name()
{
	$user = session(C('USER_AUTH_KEY'));
	return $user['admin_name'];
}

function is_login()
{
	$user = session(C('USER_AUTH_KEY'));

	if (empty($user)) {
		return 0;
	}
	else {
		return session(C('USER_AUTH_SIGN_KEY')) == user_auth_sign($user) ? $user['admin_id'] : 0;
	}
}

function is_administrator($uid = NULL)
{
	$uid = (is_null($uid) ? is_login() : $uid);
	return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}

function show_tree($tree, $template)
{
	$view = new View();
	$view->assign('tree', $tree);
	return $view->fetch($template);
}

function int_to_string(&$data, $map = array(
	'status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')
	))
{
	if (($data === false) || ($data === NULL)) {
		return $data;
	}

	$data = (array) $data;

	foreach ($data as $key => $row) {
		foreach ($map as $col => $pair) {
			if (isset($row[$col]) && isset($pair[$row[$col]])) {
				$data[$key][$col . '_text'] = $pair[$row[$col]];
			}
		}
	}

	return $data;
}

function hook($hook, $params = array())
{
	return \Think\Hook::listen($hook, $params);
}

function get_addon_class($name)
{
	$type = (strpos($name, '_') !== false ? 'lower' : 'upper');

	if ('upper' == $type) {
		$dir = \Think\Loader::parseName(lcfirst($name));
		$name = ucfirst($name);
	}
	else {
		$dir = $name;
		$name = \Think\Loader::parseName($name, 1);
	}

	$class = 'addons\\' . $dir . '\\' . $name;
	return $class;
}

function get_addon_config($name)
{
	$class = get_addon_class($name);

	if (class_exists($class)) {
		$addon = new $class();
		return $addon->getConfig();
	}
	else {
		return array();
	}
}

function addons_url($url, $param = array())
{
	$url = parse_url($url);
	$case = C('URL_CASE_INSENSITIVE');
	$addons = ($case ? parse_name($url['scheme']) : $url['scheme']);
	$controller = ($case ? parse_name($url['host']) : $url['host']);
	$action = trim($case ? strtolower($url['path']) : $url['path'], '/');

	if (isset($url['query'])) {
		parse_str($url['query'], $query);
		$param = array_merge($query, $param);
	}

	$params = array('_addons' => $addons, '_controller' => $controller, '_action' => $action);
	$params = array_merge($params, $param);
	return U('Addons/execute', $params);
}

function get_addonlist_field($data, $grid, $addon)
{
	foreach ($grid['field'] as $field) {
		$array = explode('|', $field);
		$temp = $data[$array[0]];

		if (isset($array[1])) {
			$temp = call_user_func($array[1], $temp);
		}

		$data2[$array[0]] = $temp;
	}

	if (!empty($grid['format'])) {
		$value = preg_replace_callback('/\\[([a-z_]+)\\]/', function($match) use($data2) {
			return $data2[$match[1]];
		}, $grid['format']);
	}
	else {
		$value = implode(' ', $data2);
	}

	if (!empty($grid['href'])) {
		$links = explode(',', $grid['href']);

		foreach ($links as $link) {
			$array = explode('|', $link);
			$href = $array[0];

			if (preg_match('/^\\[([a-z_]+)\\]$/', $href, $matches)) {
				$val[] = $data2[$matches[1]];
			}
			else {
				$show = (isset($array[1]) ? $array[1] : $value);
				$href = str_replace(array('[DELETE]', '[EDIT]', '[ADDON]'), array('del?ids=[id]&name=[ADDON]', 'edit?id=[id]&name=[ADDON]', $addon), $href);
				$href = preg_replace_callback('/\\[([a-z_]+)\\]/', function($match) use($data) {
					return $data[$match[1]];
				}, $href);
				$val[] = '<a href="' . U($href) . '">' . $show . '</a>';
			}
		}

		$value = implode(' ', $val);
	}

	return $value;
}

function get_config_type($type = 0)
{
	$list = C('CONFIG_TYPE_LIST');
	return $list[$type];
}

function get_config_group($group = 0)
{
	$list = C('CONFIG_GROUP_LIST');
	return $group ? $list[$group] : '';
}

function parse_config_attr($string)
{
	$array = preg_split('/[,;\\r\\n]+/', trim($string, ',;' . "\r\n"));

	if (strpos($string, ':')) {
		$value = array();

		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[$k] = $v;
		}
	}
	else {
		$value = $array;
	}

	return $value;
}

function parse_field_attr($string)
{
	if (0 === strpos($string, ':')) {
		return eval(substr($string, 1) . ';');
	}

	$array = preg_split('/[,;\\r\\n]+/', trim($string, ',;' . "\r\n"));

	if (strpos($string, ':')) {
		$value = array();

		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[$k] = $v;
		}
	}
	else {
		$value = $array;
	}

	return $value;
}

function api($name, $vars = array())
{
	$array = explode('/', $name);
	$method = array_pop($array);
	$classname = array_pop($array);
	$module = ($array ? array_pop($array) : 'Common');
	$callback = $module . '\\Api\\' . $classname . 'Api::' . $method;

	if (is_string($vars)) {
		parse_str($vars, $vars);
	}

	return call_user_func_array($callback, $vars);
}

function think_encrypt($data, $key = '', $expire = 0)
{
	$key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
	$data = base64_encode($data);
	$x = 0;
	$len = strlen($data);
	$l = strlen($key);
	$char = '';
	$i = 0;

	for (; $i < $len; $i++) {
		if ($x == $l) {
			$x = 0;
		}

		$char .= substr($key, $x, 1);
		$x++;
	}

	$str = sprintf('%010d', $expire ? $expire + time() : 0);
	$i = 0;

	for (; $i < $len; $i++) {
		$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)) % 256));
	}

	return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
}

function think_decrypt($data, $key = '')
{
	$key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
	$data = str_replace(array('-', '_'), array('+', '/'), $data);
	$mod4 = strlen($data) % 4;

	if ($mod4) {
		$data .= substr('====', $mod4);
	}

	$data = base64_decode($data);
	$expire = substr($data, 0, 10);
	$data = substr($data, 10);

	if ((0 < $expire) && ($expire < time())) {
		return '';
	}

	$x = 0;
	$len = strlen($data);
	$l = strlen($key);
	$char = $str = '';
	$i = 0;

	for (; $i < $len; $i++) {
		if ($x == $l) {
			$x = 0;
		}

		$char .= substr($key, $x, 1);
		$x++;
	}

	$i = 0;

	for (; $i < $len; $i++) {
		if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
			$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
		}
		else {
			$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
		}
	}

	return base64_decode($str);
}

function data_auth_sign($data)
{
	if (!is_array($data)) {
		$data = (array) $data;
	}

	ksort($data);
	$code = http_build_query($data);
	$sign = sha1($code);
	return $sign;
}

function format_bytes($size, $delimiter = '')
{
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	$i = 0;

	for (; $i < 5; $i++) {
		$size /= 1024;
	}

	return round($size, 2) . $delimiter . $units[$i];
}

function set_redirect_url($url)
{
	cookie('redirect_url', $url);
}

function get_redirect_url()
{
	$url = cookie('redirect_url');
	return empty($url) ? __APP__ : $url;
}

function time_format($time = NULL, $format = 'Y-m-d H:i')
{
	$time = ($time === NULL ? NOW_TIME : intval($time));
	return date($format, $time);
}

function create_dir_or_files($files)
{
	foreach ($files as $key => $value) {
		if ((substr($value, -1) == '/') && !is_dir($value)) {
			mkdir($value);
		}
		else {
			@(file_put_contents($value, ''));
		}
	}
}

function get_table_name($model_id = NULL)
{
	if (empty($model_id)) {
		return false;
	}

	$Model = M('Model');
	$name = '';
	$info = $Model->getById($model_id);

	if ($info['extend'] != 0) {
		$name = $Model->getFieldById($info['extend'], 'name') . '_';
	}

	$name .= $info['name'];
	return $name;
}

function get_model_attribute($model_id, $group = true)
{
	static $list;

	if (empty($model_id) || !is_numeric($model_id)) {
		return '';
	}

	if (empty($list)) {
		$list = S('attribute_list');
	}

	if (!isset($list[$model_id])) {
		$map = array('model_id' => $model_id);
		$extend = M('Model')->getFieldById($model_id, 'extend');

		if ($extend) {
			$map = array(
				'model_id' => array(
					'in',
					array($model_id, $extend)
					)
				);
		}

		$info = M('Attribute')->where($map)->select();
		$list[$model_id] = $info;
	}

	$attr = array();

	foreach ($list[$model_id] as $value) {
		$attr[$value['id']] = $value;
	}

	if ($group) {
		$sort = M('Model')->getFieldById($model_id, 'field_sort');

		if (empty($sort)) {
			$group = array(1 => array_merge($attr));
		}
		else {
			$group = json_decode($sort, true);
			$keys = array_keys($group);

			foreach ($group as &$value) {
				foreach ($value as $key => $val) {
					$value[$key] = $attr[$val];
					unset($attr[$val]);
				}
			}

			if (!empty($attr)) {
				$group[$keys[0]] = array_merge($group[$keys[0]], $attr);
			}
		}

		$attr = $group;
	}

	return $attr;
}

function get_table_field($value = NULL, $condition = 'id', $field = NULL, $table = NULL)
{
	if (empty($value) || empty($table)) {
		return false;
	}

	$map[$condition] = $value;
	$info = M(ucfirst($table))->where($map);

	if (empty($field)) {
		$info = $info->field(true)->find();
	}
	else {
		$info = $info->getField($field);
	}

	return $info;
}

function get_tag($id, $link = true)
{
	$tags = D('Article')->getFieldById($id, 'tags');

	if ($link && $tags) {
		$tags = explode(',', $tags);
		$link = array();

		foreach ($tags as $value) {
			$link[] = '<a href="' . U('/') . '?tag=' . $value . '">' . $value . '</a>';
		}

		return join($link, ',');
	}
	else {
		return $tags ? $tags : 'none';
	}
}

function addon_model($addon, $model)
{
	$dir = \Think\Loader::parseName(lcfirst($addon));
	$class = 'addons\\' . $dir . '\\model\\' . ucfirst($model);
	$model_path = ONETHINK_ADDON_PATH . $dir . '/model/';
	$model_filename = \Think\Loader::parseName(lcfirst($model));
	$class_file = $model_path . $model_filename . '.php';

	if (!class_exists($class)) {
		if (is_file($class_file)) {
			\Think\Loader::import($model_filename, $model_path);
		}
		else {
			E('插件' . $addon . '的模型' . $model . '文件找不到');
		}
	}

	return new $class($model);
}

function check_server()
{
	return true;
}


function msgetUrl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	$data = curl_exec($ch);
	return $data;
}

function msCurl($url, $data, $type = 0)
{
	debug(array('url' => $url, 'parm' => $data, 'type' => $type), 'msCurl start');
	$data = array_merge(array('MSCODE' => MSCODE), $data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	$data = curl_exec($ch);
	debug($data, 'msCurl res');

	if ($type) {
		return $data;
	}

	$res = json_decode($data, true);

	if (!$res) {
		msMes('30001');
	}

	return $res;
}
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

function msMes($msg)
{
	debug($msg, 'Auth_RES');

	if (S('servers_url')) {
		echo time();
	}
	else {
		if (MODULE_NAME == 'Admin') {
			$url = U('Admin/Index/index');
		}
		else {
			$url = U('Home/Index/index');
		}

		redirect($url);
	}

	exit();
}

function checkstr($strsql)
{
	//检测字符串是否有注入风险
    $strsql = str_replace("'","",$strsql);
	$strsql=trim($strsql);
	$check=preg_match('/select|SELECT|or|OR|and|AND|char|CHAR|create|CREATR|drop|DROP|database|DATABASE|table|TABLE|insert|INSERT|script|SCRIPT|function|FUNCTION|update|UPDATE|delete|DELETE|exec|EXEC|system|SYSTEM|passthru|PASSTHRU|shell_exec|SHELL_EXEC|<|\`|\%|\"|\'|\/\*|\*|\.\.\/|\.\/|union|UNION|into|INTO|load_file|LOAD_FILE|outfile|OUTFILE/i',$strsql);

	if($check)
	{
		return 1;
	}

}

//短信商是https://api.heysky.com/
class SantoSms
{
    static function extract_msgid($resp)
    {
        preg_match('/mtmsgid=(.*?)&/', $resp, $re);
        if (!empty($re) && count($re) >= 2)
            return $re[1];

        return "";
    }
    
    /*
     * @cpid string Api 帐号
     * @cppwd string Api 密码
     * @to  number  目的地号码，国家代码+手机号码（国家号码、手机号码均不能带开头的0）
     * @content string 短信内容
     *
     * @Return string 消息ID，如果消息ID为空，或者代码抛出异常，则是发送未成功。
    */
    static function send($cpid, $cppwd, $to, $content)
    {
        $c = urlencode($content);
        // http接口，支持 https 访问，如有安全方面需求，可以访问 https开头
        $api = "http://api2.santo.cc/submit?command=MT_REQUEST&cpid={$cpid}&cppwd={$cppwd}&da={$to}&sm={$c}";
        // 建议记录 $resp 到日志文件，$resp里有详细的出错信息
        try {
            $resp = file_get_contents($api);
        } catch(Exception $e){
            return $e->getMessage();
        }
        return SantoSms::extract_msgid($resp);
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


//阿里云短信发送
function  sendsms_aliyun_yzm($mobile,$code){
	// 1. 初始化一个cURL会话
    $ch = curl_init();

    // 2. 设置请求选项, 包括具体的url
    // curl_setopt($ch, CURLOPT_URL, 'http://47.88.229.241/plus/sendsms.php?phone='.$mobile.'&code='.$code);

    $new_code = md5(md5($mobile.'coin'.$code));

    // 2. 设置请求选项, 包括具体的url
    // curl_setopt($ch, CURLOPT_URL, 'http://47.74.176.157/plus/sendsms.php?phone='.$mobile.'&code='.$code.'&new_code='.$new_code);
    curl_setopt($ch, CURLOPT_URL, 'http://47.88.231.122/plus/sendsms.php?phone='.$mobile.'&code='.$code.'&new_code='.$new_code);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);

    // 不验证证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // 不验证证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    // 3. 执行一个cURL会话并且获取相关回复
    $response = curl_exec($ch);

    // 4. 释放cURL句柄,关闭一个cURL会话
    curl_close($ch);

    return $response;
}

function sendsms_shantong_yzm($mobile,$quhao){
	$mobile_code = SantoSms::random(6,1);
	$smsID = SantoSms::send("hzxahy", "ubzyuEwY", $quhao.$mobile, "您的验证码为：".$mobile_code."，请于2分钟内输入验证，请勿向他人泄露。工作人员不会以任何方式向您索要短信验证码，谨防欺诈短信。");

    if($smsID>0){
    	return $mobile_code;
    }else{
    	return 0;
    }
}
function sendsms_shantong_tz($mobile,$quhao){
	
	if($quhao!=86){
		sendsms_aliyun_yzm($mobile,'');
		// $smsID = SantoSms::send("hzxahy", "ubzyuEwY", $quhao.$mobile, "您有新的订单正在进行，请及时处理！");
		// return $smsID;
		
	}else{
		sendsms_aliyun_yzm($mobile,'');
		// $smsID = smssend($mobile,"您有新的订单正在进行，请及时处理！");
		return $smsID;
	}
    
}
/*
//美联软通短信发送
function smssend($mobile,$quhao,$content=''){
	$sms=new Sms();
	$mobile_code = $sms->random(6,1);

	if(empty($mobile)){
		return;
	}

	if(empty($content)){
		$contentUrlEncode = urlencode("您的校验码是：".$mobile_code. "，请于2分钟内输入验证，请勿向他人泄露。工作人员不会以任何方式向您索要短信验证码，谨防欺诈短信。");
	}else{
		$contentUrlEncode = urlencode($content);
	}

	$result = $sms->sendSMS('ljsgr','ab9708cb255fe58422d9a1376746e79b','8d4e3e2beb5de6cef4501a254ddc7179',$quhao.$mobile,$contentUrlEncode,'UTF-8');  //进行发送
	// var_dump($result);
	// exit;
	if(strpos($result,"success")>-1) {
		//提交成功
		return $mobile_code;
	} else {
		return 0;
	}
}
*/
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
//秒赛科技短信
class SendCode{
    private $url = 'http://139.196.108.241:8080';
 
    private function post_curls($url, $post)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $res; // 返回数据，json格式
  
    }
 
    //$account 用户账号
    //$pswd 必填参数。用户密码
    //$mobile 必填参数。合法的手机号码
    //$msg  必填参数。短信内容
    //$ts  可选参数，时间戳，格式yyyyMMddHHmmss
    //$state 必填参数   状态  1:验证码短信  2:营销短信  3:语音验证码
    // public function send($account,$pswd,$mobile,$msg,$ts,$state){
    public function send($mobile,$state=1,$type=1,$name,$num,$cname){

    	$account='15727303293';
    	$pswd='Welcome2018!';
    	$ts='';

        if($ts != ''){
            $pswd = md5($account.$pswd.$ts);
        }
        $code = rand(1000, 9999);
		$code .= substr(time(), '-2');
		
        $url = '';
        switch ($state) {
            case 1:
            	if($type==1){
            		$msg='尊敬的用户您好，感谢您注册点点OTC，您的短信验证码是'.$code.'，10分钟内有效'; 
            	}
            	if($type==2){
            		$msg='您的手机验证码为: '.$code.'。请及时完成输入验证，否则验证码会失效。'; 
            	}
				if($type==3){
            		$msg='您有一笔购买'.$num.$cname.'的订单尚未支付，请尽快完成支付并在网页点击“标记已付款”，否则订单超时将被取消。如果您已经完成支付，请在订单页面点击“标记已付款”，否则将无法收到对方支付的数字资产。'; 
            	}
            	if($type==4){
            		$msg=$name.'已向您支付一笔'.$num.$cname.'的订单，如果您已经收到，请在订单页面点击“放行”数字资产。';

            	}
            	if($type==5){
            		$msg=$name.'已确认收到您的付款，稍后系统会自动将您所购买的'.$num.$cname.'发放到您的账户，请注意查收。';

            	}
                $url = $this->url.'/Api/HttpSendSMYzm.ashx';
                break;
            case 2:
            	if($type==3){
            		$msg='您有新的订单正在进行，请及时处理！'; 
            	}
            	if($type==4){
            		$msg='您的订单标记成功，请及时处理！'; 
            	}
                $url = $this->url.'/Api/HttpSendSMYx.ashx';
                break;
            case 3:
                $url = $this->url.'/Api/HttpSendSMVoice.ashx';
                break;
             
            default:
                $url = '';
                break;
        }

        $data =  array('account' => $account,'pswd'=>$pswd,'mobile'=>$mobile,'msg'=>$msg,'ts'=>$ts); 
        $huawei_res= $this->post_curls($url,$data);
        $huawei_res=json_decode($huawei_res,true);
 		if($huawei_res["result_msg"]=='提交成功'){
 			return $code;
 		}else{
 			return 0;
 		}
    }
 
}
function smssend($mobile,$state=1,$type,$name='',$num='',$cname=''){
	$send = new SendCode();
	$re = $send->send($mobile,$state,$type,$name,$num,$cname);
	return $re;
}
/*
function smssend($mobile){
	//发送短信服务器需要在短息服务商那添加白名单
	$encode='UTF-8';  //页面编码和短信内容编码为GBK。重要说明：如提交短信后收到乱码，请将GBK改为UTF-8测试。如本程序页面为编码格式为：ASCII/GB2312/GBK则该处为GBK。如本页面编码为UTF-8或需要支持繁体，阿拉伯文等Unicode，请将此处写为：UTF-8

	$username='';  //用户名

	$password_md5='';  //32位MD5密码加密，不区分大小写

	$apikey='922439dd6e5d25ec4d2c1939b0ed14df';  //apikey秘钥（请登录 http://m.5c.com.cn 短信平台-->账号管理-->我的信息 中复制apikey）

	$mobile=$mobile;  //手机号,只发一个号码：13800000001。发多个号码：13800000001,13800000002,...N 。使用半角逗号分隔。
	$code = rand(1000, 9999);
	$code .= substr(time(), '-2');
	// if($lang == 'en-us'){
	// 	$content='【大V网】Your verification code is: '.$code.'，Please don\'t tell anyone.'; 
	// }else{
		$content='【大V网】您的手机验证码为: '.$code.'。请及时完成输入验证，否则验证码会失效。'; 
	// }

	 //要发送的短信内容，特别注意：签名必须设置，网页验证码应用需要加添加【图形识别码】。
	$contentUrlEncode = urlencode($content);//执行URLencode编码  ，$content = urldecode($content);解码

	//发送链接（用户名，密码，apikey，手机号，内容）
    $url = "http://115.28.23.78/api/send/index.php?";  //如连接超时，可能是您服务器不支持域名解析，请将下面连接中的：【m.5c.com.cn】修改为IP：【115.28.23.78】
    $data=array
    (
        'username'=>$username,
        'password_md5'=>$password_md5,
        'apikey'=>$apikey,
        'mobile'=>$mobile,
        'content'=>$contentUrlEncode,
        'encode'=>$encode,
    );
    $result = curlSMS($url,$data);
    // var_dump($result);exit; //测试
    // return $result;
    if(strpos($result,"success")>-1) {
	   	return $code;
	} else {
		return 'dd';
	}

}
*/
function send_email($to, $name, $subject = '', $body = '', $attachment = null){

	$config = C('think_email');

	Vendor("PHPMailer.phpmailer","",".php");

	$mail = new PHPMailer(); //PHPMailer对象

	$mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码

	$mail->IsSMTP(); // 设定使用SMTP服务

	$mail->SMTPDebug = 0; // 关闭SMTP调试功能

	$mail->SMTPAuth = true; // 启用 SMTP 验证功能

	$mail->SMTPSecure = 'ssl'; // 使用安全协议

	$mail->Host = $config['smtp_host']; // SMTP 服务器

	$mail->Port = $config['smtp_port']; // SMTP服务器的端口号

	$mail->Username = $config['smtp_user']; // SMTP服务器用户名

	$mail->Password = $config['smtp_pass']; // SMTP服务器密码

	$mail->SetFrom($config['from_email'], $config['from_name']);

	$replyEmail = $config['reply_email']?$config['reply_email']:$config['from_email'];

	$replyName = $config['reply_name']?$config['reply_name']:$config['from_name'];

	$mail->AddReplyTo($replyEmail, $replyName);

	$mail->Subject = $subject;

	$mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";

	$mail->MsgHTML($body);

	$mail->AddAddress($to, $name);

	if(is_array($attachment)){
		foreach ($attachment as $file){
			is_file($file) && $mail->AddAttachment($file);
		}
	}
	$result = $mail->send();
	
	if($result){
		return true;
	}else{
		return false;
	}
}

function set_token($name) {
	$token_name = $name.'token';
	$token_value = md5(microtime(true));
	session($token_name,$token_value);
	return $token_value;
}
function valid_token($name,$token) {
	$token_name = $name.'token';
	$return = $token === session($token_name) ? true : false;
	set_token($name);
	return $return;
}
function remove_ling($number){
	if(empty($number)){
		return 0;
	}
	$number_arr = explode(".",$number);
	$k=0;
	for($i=1;$i<strlen($number_arr[1]);$i++){
		if(substr($number_arr[1],$i-1,$i)>0){
			$k=$i;
		}
	}
	if($k>0){
		$baoliu = substr($number_arr[1],0,$k);
		$result = $number_arr[0].".".$baoliu;
	}else{
		$result = $number_arr[0];
	}
	return $result;
}
function chkchuanhao($session_id,$userid){
	$result = M('user_log')->where(array('session_key'=>$session_id))->order('id desc')->limit(1)->find();
	if(!empty($result) && $result['userid']!=$userid && $result['state']==1){
		return true;
	}else{
		return false;
	}
}
function getbi_frommarket($market){
	$arr = explode("_",$market);
	$coin_info = M('coin')->where(array('name'=>$arr[0]))->find();
	if(!empty($coin_info)){
		return $coin_info;
	}else{
		return array();
	}
}
function getcoinname($id){
	$coin = M('coin')->where(array('id'=>$id))->find();
	return strtoupper($coin['name']);

}
function getmarket_frombi($coin){
	$market = $coin."_cny";
	$market_info = M('market')->where(array('name'=>$market))->find();
	if(!empty($market_info)){
		return $market_info;
	}else{
		return array();
	}
}
function shiming($uid){
	$res = 0;
	$user = M('User')->where(array('id'=>$uid))->find();
	if(!empty($user)){
		if(1){
			$res++;
		}
		if(!empty($user['idcard_zheng']) && !empty($user['idcard_fan']) && !empty($user['idcard_shouchi'])){
			$res++;
		}
		if(!empty($user['is_agree']) && $user['is_agree']==1){
			$res++;
		}
	}
	return $res;
}
function curl_get($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//参数为1表示传输数据，为0表示直接输出显示
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//参数为0表示不带头文件，为1表示带头文件
	curl_setopt($ch, CURLOPT_HEADER,0);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
function addstar($str,$type){
	if(empty($type) || (!empty($type) && $type=="mobile")){
		$result = substr_replace($str, '****', 3, 4);
	}else{
		$result = substr_replace($str, '***', 1, 3);
	}
	return $result;
}
//可逆加密算法
class encryptClass
{
	protected $key=7;
	public function encode($txt){
		for($i=0;$i<strlen($txt);$i++){
			$txt[$i]=chr(ord($txt[$i])+$this->key);
		}
		return $txt=base64_encode($txt);
	}
	//解密
	function decode($txt){
		$txt=base64_decode($txt);
		for($i=0;$i<strlen($txt);$i++){
			$txt[$i]=chr(ord($txt[$i])-$this->key);
		}
		return $txt;
	}
}
//加密与解密
function jiajiemi(){
	return new encryptClass();
}




//港澳台身份证
function validation_filter_id_gcard($id_card){
    return true;
}




function validation_filter_id_card($id_card){
    if(strlen($id_card)==18){
        return idcard_checksum18($id_card);
    }elseif((strlen($id_card)==15)){
        $id_card=idcard_15to18($id_card);
        return idcard_checksum18($id_card);
    }else{
        return false;
    }
}
// 计算身份证校验码，根据国家标准GB 11643-1999
function idcard_verify_number($idcard_base){
    if(strlen($idcard_base)!=17){
        return false;
    }
    //加权因子
    $factor=array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
    //校验码对应值
    $verify_number_list=array('1','0','X','9','8','7','6','5','4','3','2');
    $checksum=0;
    for($i=0;$i<strlen($idcard_base);$i++){
        $checksum += substr($idcard_base,$i,1) * $factor[$i];
    }
    $mod=$checksum % 11;
    $verify_number=$verify_number_list[$mod];
    return $verify_number;
}
// 将15位身份证升级到18位
function idcard_15to18($idcard){
    if(strlen($idcard)!=15){
        return false;
    }else{
        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
        if(array_search(substr($idcard,12,3),array('996','997','998','999')) !== false){
            $idcard=substr($idcard,0,6).'18'.substr($idcard,6,9);
        }else{
            $idcard=substr($idcard,0,6).'19'.substr($idcard,6,9);
        }
    }
    $idcard=$idcard.idcard_verify_number($idcard);
    return $idcard;
}
// 18位身份证校验码有效性检查
function idcard_checksum18($idcard){
    if(strlen($idcard)!=18){
        return false;
    }
    $idcard_base=substr($idcard,0,17);
    if(idcard_verify_number($idcard_base)!=strtoupper(substr($idcard,17,1))){
        return false;
    }else{
        return true;
    }
}
function getprice($type){
	if($type!='CNY' && $type!='USD'){
		return false;
	}
	$record = M('Currency')->where(array('short_name'=>$type))->find();
	return floatval($record['price']);
}
function opentime($times){
	$datelist= explode(',',$times);
    $xq=date('w');
	//xq0-6 op得到今天的字符串
	//数据库存放的是 1234567 所以需要修改星期
	$xq=$xq-1;
	if($xq<0){
		$xq=6;
	}
    $op=$datelist[$xq];
    if($op=='0'){
    	return false;
    }elseif($op=='1'){
    	return true;
    }else{
    	$ophour=explode('-',$op);
    	if(date("H")>=$ophour[0] && date("H")<$ophour[1]){
    		return true;
    	}else{
    		return false;
    	}
    }
}
function getname($id){
	$name=M('user')->Field('enname')->where("id=".$id)->find();
	return $name['enname'];
}
function getinfo($id){
	$info=M('user')->Field('id,username,enname,headimg,first_trade_time')->where("id=".$id)->find();
	return $info;
}
function headimg($userid){
	$img = M('user')->where(array('id'=>$userid))->getField('headimg');
	if(empty($img)){
		$img = "/Public/Home/images/hportrait/head_portrait60.png";
	}
	return $img;
}
function Isheadimg($userid){
	$img = M('user')->where(array('id'=>$userid))->getField('headimg');
	if(empty($img)){
		$name=M('user')->where(array('id'=>$userid))->getField('enname');
		return strbig($name);
	}
	return '';
}
function getstatus($status,$buyid,$sellid,$uid,$bpj,$spj){

	//1显示标记 取消交易按钮 2 显示释放btc按钮3 评价按钮
	$type=0;
	if($status==0){
		if($buyid==$uid){
			$type=1;
		}
		$zt='待付款';
	}
	if($status==1){

		if($buyid==$uid){
			$zt='已支付';
		}else{
			$type=2;
			$zt='待放行';
		}
	}
	if($status==3){
		if(($buyid==$uid && $bpj==0) || ($sellid==$uid && $spj==0)){
			$zt='待评价';
			$type=3;
		}else{
			$zt='已评价';
		}

	}
	if($status==4){
		$zt='交易完成';
	}
	if($status==5){
		$zt='交易关闭';
	}
	if($status==6){
		$zt='申诉中';
	}


	return array($zt,$type);

}
function truspingbi($id){
	$info=M('user')->Field('id,ixinren,pingbi')->where(array('id'=>$id))->find();
	return $info;
}
function tradepermit($userid,$adv){
	if(empty($userid) || empty($adv)){
		return array('status'=>0,'msg'=>'缺少参数');
	}
	if($userid == $adv['userid']){
		return array('status'=>0,'msg'=>'自己不能和自己交易');
	}
	if(!empty($adv['trust_only'])){
		$trust = truspingbi($adv['userid']);
		$trust_array = explode(",",$trust['ixinren']);
		if(!in_array($userid,$trust_array)){
			return array('status'=>0,'msg'=>'该用户限制了只和自己信任的用户交易');
		}else{
			return array('status'=>1,'msg'=>'可以交易');
		}
	}else{
		if(empty($adv['safe_option'])){
			return array('status'=>1,'msg'=>'可以交易');
		}else{
			$operator = M('User')->where(array('id'=>$userid))->getField('is_agree');
			if($operator==1){
				return array('status'=>1,'msg'=>'可以交易');
			}else{
				return array('status'=>0,'msg'=>'请先实名认证才能交易');
			}
		}
	}
}
//获取信任人数
function gettrust($id){
	$trust = M('User')->where(array("id"=>$id))->getField('xinren');
	if($trust) {
		$trust = trim($trust, ',');//去除前面的逗号
		$trust_arr = explode(",", $trust);
		return count($trust_arr);
	}else{
		return 0;
	}
}
function page_array($count,$page,$array,$order=0){
	global $countpage;
	global $totals;
	$page=empty($page)?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
	$start=($page-1)*$count; #计算每次分页的开始位置
	if($order==1){
		$array=array_reverse($array);
	}
	$totals=count($array);
	$countpage=ceil($totals/$count); #计算总页面数
	$pagedata=array();
	$pagedata=array_slice($array,$start,$count);
	return $pagedata; #返回查询数据
}

function show_array(){
	$countpage=$GLOBALS['countpage'];
	$totals = $GLOBALS['totals'];
	if($countpage <= 1){
		$str = $totals."条记录 1/1页";
		return $str;
	}
	$url=$_SERVER['REQUEST_URI'];
	//--------------处理url,防止出现多次的&page=n -------------------
	$deli='?';   //page前分隔符，？或者&
	/**/
	if(strpos($url,'?page')||strpos($url,'&page')){  //如果链接已经包含page参数
		if(strpos($url,'?page')){                     //如果链接 是url?page= 类型
			$url=strstr($url,'?page',true);
			$deli='?';
		}else if(strpos($url,'&page')){                     //如果是url?mod=''&page= 类型，去掉url 中的&page=，重新添加
			$url=strstr($url,'&page',true);
			$deli='&';
		}
	}else if(strpos($url,'?')){
		$deli='&';
	}else{
		$deli='?';
	}
	//--------------处理url结束----------------------------------------
	$page=empty($_GET['page'])?1:$_GET['page'];
	if($page > 1){
		$uppage=$page-1;
	}else{
		$uppage=1;
	}
	if($page < $countpage){
		$nextpage=$page+1;
	}else{
		$nextpage=$countpage;
	}
	$str = $totals."条记录 ".$page."/".$countpage."页";
	if($page!=1){
		$str.="<a href='{$url}{$deli}page={$uppage}'>上一页</a>";
	}
	if($page<$countpage){
		$str.="<a href='{$url}{$deli}page={$nextpage}'>下一页</a>";
	}
	if($countpage<=5){
		$begin=1;
		$end=$countpage;
	}else if($countpage>5){
		$begin=(ceil($page/5)-1)*5+1;
		$end=$begin+4>$countpage?$countpage:$begin+4;
	}
	
	if($begin>5){
		$prev = (ceil($page/5)-2)*5+1;
		$str .= "<a href='{$url}{$deli}page=$prev'>上5页</a>";
	}

	for($i=$begin;$i<=$end;$i++){
		if($_GET['page']==$i){
			$co='background-color:#108ee9;color:white;';
		}else if(empty($_GET['page']) && $i==1){
			$co='background-color:#108ee9;color:white;';
		}else{
			$co='';
		}
		$str.="<a style='$co' href='{$url}{$deli}page=$i'>$i</a>";
	}
	if($countpage-$begin>=5){
		$next = ceil($page/5)*5+1;
		$str .= "<a href='{$url}{$deli}page=$next'>下5页</a>";
	}
	if($countpage>5 && $page<$countpage){
		$str.="<a href='{$url}{$deli}page=$countpage'>最后一页</a>";
	}
	return $str;
}
//交易完成后将双方id写入双方trade_id字段
function tradeID($id1,$id2){
	$trade_id1=M('user')->where(array('id'=>$id1))->getField('trade_id');
	$new_ids1=$trade_id1.','.$id2;
	M('User')->where(array('id'=>$id1))->setField($new_ids1);
	$trade_id2=M('user')->where(array('id'=>$id2))->getField('trade_id');
	$new_ids2=$trade_id2.','.$id1;
	M('User')->where(array('id'=>$id2))->setField($new_ids2);
}
//判断现在是否在开放时间返回1显示0隐藏
function ifopen($id,$type){
	$Module = ($type == 0)?M('AdBuy'):M('AdSell');
	$open_time = $Module->where(array('id'=>$id))->getField('open_time');
	$res=opentime($open_time);
	if($res){
		return 1;
	}else{
		return 0;
	}
}
//是否显示广告0显示1不显示
function ifShow($id,$type){
	$table = $type ==1?'AdSell':'AdBuy';
	$userid = M($table)->where(array('id'=>$id))->getField('userid');
	$is_agree = M('User')->where(array('id'=>$userid))->getField('is_agree');
	if($is_agree == 0){
		return 1;
	}else{
		if(ifopen($id,$type) == 0){
			return 1;
		}
	}
	return 0;
}
//根据广告币种id,地区货币id获取价格和货币缩写
function get_price($coinid,$hbid,$sx){
	$table = M('Coin')->where("id=$coinid")->getField('name');
	if(empty($table)){
		return 0;
	}
	if($sx==1) {
		$res = M($table)->where("id=$hbid")->getField('price');
	}elseif($sx==0){
		$res = M($table)->where("id=$hbid")->getField('short_name');
	}elseif($sx==2){
		$res = M($table)->where("id=$hbid")->getField('name');
	}
	return $res;
}
//截取第一个字符串并且大写
function strbig($name){
	if(empty($name)){
		return $name;
	}
	return strtoupper(substr($name,0,1));
}
function stradno($adno){
	if(empty($adno)){
		return $adno;
	}
	$fir=strtoupper(substr($adno,0,1));
	$end=substr($adno,-5);
	return $fir.$end;
}
function strorderno($adno){
	if(empty($adno)){
		return $adno;
	}
	$fir=(substr($adno,0,3));
	$end=substr($adno,-3);
	return $fir.'***'.$end;
}
function getpaymethod($ids){
	$pays="";
	$name=M('pay_method')->where("FIND_IN_SET(id,'".$ids."' )")->field('name')->select();
	foreach ($name as $key => $v) {
		$pays.=$v['name']." ";
	}
	return $pays;
}
function getpaymethodli($ids){
	$pays="";
	$name=M('pay_method')->where("FIND_IN_SET(id,'".$ids."' )")->field('name')->limit(2)->select();
	foreach ($name as $key => $v) {
		$pays.=$v['name']." ";
	}
	return $pays;
}
function ohunderd($num){
	if($num>100){
		return 100;
	}
	return $num;
}
function getCoinType($coinname){
	$coin_info = M('Coin')->where(array('name'=>$coinname))->find();
	if($coin_info['tp_qj'] == 'btc' && $coin_info['name'] != 'usdt'){
		return 1;
	}
	if($coin_info['tp_qj'] == 'eth'){
		return 2;
	}
	if($coin_info['tp_qj'] == 'erc20'){
		return 3;
	}
	if($coin_info['name'] == 'usdt'){
		return 4;
	}
	return 0;
}

function makeRange($n){
	for($i=0;$i<$n;$i++){
		yield $i;
	}
}
function ceth($num){
	return $num / 1e18;
}
//删除字符串id中的id
function delID($arr, $id){
	foreach($arr as $k=>$v){
		if($v == $id){
			unset($arr[$k]);
		}
	}
	return implode(",",$arr);
}
function numform($number){
	$number = $number * 1;
	if(empty($number)){
		return 0;
	}else{
		if($number<0.0001){
			$new = $number*10000;
			$arr = explode(".",$new);
			$xiaoshu = end($arr);
			$weishu = strlen($xiaoshu);
			return number_format($number,($weishu+4),'.','');
		}else{
			return $number;
		}
	}
}
//通过skaccount来查询收款方式
function skaccount_get_account($ids){
    $pays_ids = M('user_skaccount')->where("FIND_IN_SET(id,'".$ids."' )")->field('pay_method_id')->select();
    $new_pays_ids = array();
    foreach ($pays_ids as $val){
        array_push($new_pays_ids, $val['pay_method_id']);
    }
    array_unique($new_pays_ids);
    $new_ids = implode(',', $new_pays_ids);
    $pays="";
    $name=M('pay_method')->where("FIND_IN_SET(id,'".$new_ids."' )")->field('name')->select();
    foreach ($name as $key => $v) {
        $pays.=$v['name']." ";
    }
    return $pays;
}
?>