<?php

namespace Pickle\Controller;
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
			$this->display();
		}
	}

	public function login($post_data){
		extract($post_data);
		// p($post_data);
		if($username == "pp" && md5($password) == 'c483f6ce851c9ecd9fb835ff7551737c'){
			$session_time = ($remember != '1') ? 3600 : 3600*24;
			session(array('user' => array('username' => $username, 'last_log_time' => time()), 'expire' => $session_time));
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