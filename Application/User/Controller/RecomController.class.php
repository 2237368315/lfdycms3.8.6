<?php
namespace User\Controller;
use Think\Controller;

class RecomController extends UserController {
    public function index(){
		$this->display();
    }
	
	public function sign(){
		if(IS_GET){
			if(!C('USER_ALLOW_SIGN')){
	            $this->error('签到已经关闭，请稍后签到~');
	        }
			$this->ajaxReturn(D('Recom')->sign());
		}
	}
	
	public function play(){
		if(C('USER_ALLOW_PLAY')){
        	$this->ajaxReturn(D('Recom')->play());
        }
	}
}