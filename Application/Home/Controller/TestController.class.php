<?php

namespace Home\Controller;

class TestController extends HomeController

{
	
	public function __construct() {
		parent::__construct();
		$display_action=array("index");
		if(in_array(ACTION_NAME,$display_action)){
			$this->common_content();
		}
	}

	public function test(){
        print_r(pathinfo(__DIR__));
    }

}
?>