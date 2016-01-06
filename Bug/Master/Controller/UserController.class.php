<?php

namespace Master\Controller;
use Think\Controller;

class UserController extends Controller{
	public function index(){
		if(IS_POST){
			$post_data = I('post.');
			// p($post);
			if($post_data['getform'] == 'login'){
				$this->login($post_data);
			}elseif($post_data['getform'] == 'register'){
				$this->register($post_data);
			}elseif($post_data['getform'] == 'forgetPassword'){
				$this->forgetPassword($post_data);
			}
		}else{
			layout(false);
			$this->display();
		}
	}

	public function login($post_data){
		extract($post_data);
		// p($post_data);
		$user_data = M("User")->find();
		// p($user_data);
		// p($user_data['password']."===".md5($password));
		if($username == $user_data['username'] && md5($password) == $user_data['password']){
			$session_time = ($remember != '1') ? 3600 : 3600*24;
			session(array('user' => $user_data, 'expire' => $session_time));
			// p(session('user'));
			$this->success("登錄成功！",U('Index/index'));
		}else{
			$this->error("賬號或密碼錯誤！");
		}
	}

	public function register($post_data){
		extract($post_data);
		// p($post_data);
	}

	public function forgetPassword($post_data){
		extract($post_data);
		// p($post_data);
	}

	public function logOut(){
		//
	}
}