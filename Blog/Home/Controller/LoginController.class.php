<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {


  Public $flag = false;

  Public function index(){

            $this->display();
  }

  Public function verify() {
            $verify = new  \Think\Verify;
            $verify->codeSet = '0123456789';
            // $verify->imageW = 100;
            // $verify->imageH = 30;
            $verify->length = 4;
            $verify->fontSize = 16;
            $verify->entry();
  }

  Public function handle() {
            /*$verify = new \Think\Verify();
            $check = $verify->check( I('code'));
            $check || notice('验证码错误,数据库是否没有开启？!','/login',1);*/

            $where = array(
                        'username' => I('username'),
                        'password' => I('password')
            );
            $rest = M('user')->where($where)->fetchSql(false)->find();
            if( $rest ) {
	            session( 'username' , $rest['username']);
	            session( 'user_id', $rest['id'] );
                  session('role_id',$rest['role_id'] );
	            // logger(session('user_id'));
            }

            $this->ajaxReturn( setAjaxReturn( $rest, '用户名或者密码错误！' ) );
  }

  Public function logout() {
  	$_SESSION = array();
  	session_destroy(session());
  	$this->ajaxReturn( setAjaxReturn( '退出登入', '退出失败！' ) );
  }

}