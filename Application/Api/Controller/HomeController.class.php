<?php
namespace Home\Controller;

class HomeController extends \Think\Controller
{
	protected function _initialize()
	{
		$allow_controller=array("Ajax","Article","Finance","Index","Login","Queue","Trade","User","Newad","Order");
		if(!in_array(CONTROLLER_NAME,$allow_controller)){
			$this->error("非法操作");
		}

		if (!session('userId')) {
			session('userId', 0);
		}else if (CONTROLLER_NAME != 'Login') {
			$member_session_id=session('sessionId');
			if(!empty($member_session_id)){
				$prev_login=M('user_log')->where(array('userid'=>session('userId'),'type' => 'login','state'=>1,'id'=>array('lt',$member_session_id)))->order('id desc')->find();
				if(!empty($prev_login)){
					M('user_log')->where(array('userid'=>session('userId'),'type' => 'login','state'=>1,'id'=>array('lt',$member_session_id)))->save(array('state'=>0,'endtime'=>time()));
				}
				$next_login=M('user_log')->where(array('userid'=>session('userId'),'type' => 'login','state'=>1,'id'=>array('gt',$member_session_id)))->order('id desc')->find();
				if(!empty($next_login)){
					redirect('/Login/loginout');
				}
			}else{
				redirect('/Login/loginout');
			}
			
			$user = M('user')->where('id = ' . session('userId'))->find();
			$this->assign('enname',$user['enname']);
			if(!session('loginTime')){
				redirect('/Login/loginout');
			}else{
				$login_records=M('user_log')->where(array('userid'=>$user['id'],'type' => 'login','session_key'=>session_id(),'state'=>1))->order('id desc')->find();
				if(empty($login_records) || (!empty($login_records)&&$login_records['addtime']!=session('loginTime'))){
					redirect('/Login/loginout');
				}
			}
			$cha=time()-session('saveTime');
			if(floor($cha/60)>30){
				redirect('/Login/loginout');
			}
			if (CONTROLLER_NAME != 'Ajax') {
				 $mo = M();
				 $mo->startTrans();
				 $mo->table('tw_user')->where(array('id' => $user['id']))->save(array('logintime'=>time(),'loginstatus'=>1));
				 $mo->commit();
			}
			session('saveTime', time());
			if(session('userName')&&cookie('userName')){
				if(session('userName')!=cookie('userName')){
					session(null);
					cookie(null);
					redirect("/");
				}
			}
		}
	}
	
	public function __construct() {
		parent::__construct();
		defined('APP_DEMO') || define('APP_DEMO', 0);
		
		if (isset($_GET['invit'])) {
			session('invit', $_GET['invit']);
		}

		$config = (APP_DEBUG ? null : S('home_config'));

		if (!$config) {
			$config = M('Config')->where(array('id' => 1))->find();
			S('home_config', $config);
		}

		if (!session('web_close')) {
			if (!$config['web_close']) {
				exit($config['web_close_cause']);
			}
		}

		C($config);
		C('contact_qq', explode('|', C('contact_qq')));
		C('contact_qqun', explode('|', C('contact_qqun')));
		C('contact_bank', explode('|', C('contact_bank')));
		$coin = (APP_DEBUG ? null : S('home_coin'));

		if (!$coin) {
			$coin = M('Coin')->where(array('status' => 1))->select();
			S('home_coin', $coin);
		}
		
		$coinList = array();

		foreach ($coin as $k => $v) {
			$coinList['coin'][$v['name']] = $v;

			if ($v['name'] != 'cny') {
				$coinList['coin_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'rmb') {
				$coinList['rmb_list'][$v['name']] = $v;
			}
			else {
				$coinList['xnb_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'rgb') {
				$coinList['rgb_list'][$v['name']] = $v;
			}

			if ($v['type'] == 'qbb') {
				$coinList['qbb_list'][$v['name']] = $v;
			}
		}

		C($coinList);
		
		$C = C();

		foreach ($C as $k => $v) {
			$C[strtolower($k)] = $v;
		}

		$this->assign('C', $C);
		
	}
	
	public function common_content(){
		if (userid()) {
			$coin_column = array('cny');
			foreach (C('market') as $k => $v) {
				if($v['status'] == 1){
					$v['xnb'] = explode('_', $v['name'])[0];
					array_push($coin_column,$v['xnb']);
				}
			}
			$coin_top = array();
			$coin_topd = array();
			$userCoin_top = M('UserCoin')->where(array('userid' => userid()))->find();
			if(!empty($coin_column)){
				for($i=0;$i<count($coin_column);$i++){
					$coin_mround[$i] = M('market')->where(array('name'=>$coin_column[$i]."_cny"))->getField('round');
					$coin_title[$i] = M('Coin')->where(array('name'=>$coin_column[$i]))->getField('title');
					$coin_img[$i] = M('Coin')->where(array('name'=>$coin_column[$i]))->getField('img');
					array_push($coin_top,array($coin_img[$i],$coin_title[$i],number_format($userCoin_top[$coin_column[$i]],$coin_mround[$i],'.','')));
					array_push($coin_topd,array($coin_img[$i],'冻结'.$coin_title[$i],number_format($userCoin_top[$coin_column[$i]."d"],$coin_mround[$i],'.','')));
				}
			}
			$this->assign('coin_top',$coin_top);
			$this->assign('coin_topd',$coin_topd);
			$this->assign('userCoin_top', $userCoin_top);
		}
		
		if (!S('daohang')) {
			$this->daohang = M('Daohang')->where(array('status' => 1))->order('sort asc')->select();
			S('daohang', $this->daohang);
		}
		else {
			$this->daohang = S('daohang');
		}
		
		// 交易币种列表--------------------S
		$data = array();
		foreach (C('market') as $k => $v) {
			if($v['status'] == 1){
				$v['xnb'] = explode('_', $v['name'])[0];
				$v['rmb'] = explode('_', $v['name'])[1];
				$data[$k]['name'] = $v['name'];
				$data[$k]['img'] = $v['xnbimg'];
				$data[$k]['title'] = $v['title'];
			}
		}
		$this->assign('market_ss', $data);
		// 交易币种列表--------------------E
		
		$footerArticleType = (APP_DEBUG ? null : S('footer_indexArticleType'));

		if (!$footerArticleType) {
			$footerArticleType = M('ArticleType')->where(array('status' => 1, 'footer' => 1, 'shang' => ''))->order('sort asc ,id desc')->limit(4)->select();

			foreach ($footerArticleType as $k => $v) {
				$child = M('ArticleType')->where(array('status' => 1, 'footer' => 1, 'shang' => $v['name']))->order('sort asc ,id desc')->select();
				if(!empty($child)){
					$footerArticleType[$k]['child_lei']='type';
					$footerArticleType[$k]['child']=$child;
				}
				else{
					$child = M('Article')->where(array('status' => 1, 'type' => $v['name']))->order('sort asc ,id desc')->limit(5)->select();
					
					$footerArticleType[$k]['child_lei']='wenzhang';
					$footerArticleType[$k]['child']=$child;	
				}
			}
			S('footer_indexArticleType', $footerArticleType);
		}

		$this->assign('footerArticleType', $footerArticleType);

		
		// 底部友情链接--------------------S
		$footerindexLink = (APP_DEBUG ? null : S('index_indexLink'));
		if (!$footerindexLink) {
			$footerindexLink = M('Link')->where(array('status' => 1,'look_type'=>1))->order('sort asc ,id desc')->select();
		}
		$this->assign('footerindexLink', $footerindexLink);
		// 底部友情链接--------------------E

		// qq--------------------S
		$qqs = C('contact_qqun');
		$qqqun='';
		foreach ($qqs as $k => $v) {
			if(!empty($v)){
				$qqqun .= '<p>QQ'.($k+1).'群：'.$v.'</p>';
			}
		}
		$this->assign('qqqun', $qqqun);
		$this->assign('contact_qq',implode("、",C('contact_qq')));
		// qq--------------------E
		
		$notice_info = M('Article')->where(array('type' => 'notice', 'status' => 1, 'index' => 1))->order('id desc')->find();
		if(!$notice_info){
			$notice_info['id'] = 0;
			$notice_info['title'] = '暂无公告';
			$notice_info['content'] = '暂无信息';
		}
		// 踢出内容中的标签
		$notice_info['content'] = strip_tags($notice_info['content']);
		$notice_type = M('ArticleType')->where(array('name' => 'notice'))->find();
		$this->assign('notice_info', $notice_info);
		$this->assign('footerArticle', $footerArticle);
	}
	
	//判断自己是否信任屏蔽该用户(type为1是判断信任为2是判断屏蔽),未信任屏蔽为0,反之为1
	public function iftruban($id,$type){
		if(!check($id,'d')){
			$this->error("参数错误！", $extra);
		}
		
		$user = M('User')->where(array('id' => $id))->find();
		$trust_ids = $user['xinren'];
		$ban_ids = $user['pingbi'];
		//拆成数组
		$trust_ids_arr = explode(",",$trust_ids);
		$ban_ids_arr = explode(",",$ban_ids);
		if(userid() == ''){return 0;}
		if($type == 1){
			if(in_array(userid(),$trust_ids_arr)){
				return 1;
			}else {
				return 0;
			}
		}
		if($type == 2){
			if(in_array(userid(),$ban_ids_arr)){
				return 1;
			}else {
				return 0;
			}
		}
	}
}
?>