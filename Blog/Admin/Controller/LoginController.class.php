<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $this->module_name = MODULE_NAME;
        $this->display();
     }

     public function verify() {
     	$verify = new  \Think\Verify;
     	$verify->codeSet = '0123456789';
     	// $verify->imageW = 100;
     	// $verify->imageH = 30;
     	$verify->length = 4;
     	$verify->fontSize = 16;
     	$verify->entry();
     }

     public function handle() {
     	$verify = new \Think\Verify;
     	$rest = $verify->check( I('code') );
     	$rest || $this->error('CODE ERROR');
                $where = array(
                    'username' => I('username'),
                    'password' => I('passwd',0,'md5')
                    );
                $rest = M('commenter')->where($where)->find();
                $rest || $this->error('LOGIN FAILED');
     	session('username',I('username'));
                if( C('URL_ROUTER_ON') )
                    $this->redirect('/blog');
                else
                    $this->redirect(U(MODULE_NAME.'/Index/index'));
     }
      public function register() {
                $this->module_name = MODULE_NAME;
                if( IS_POST ){
                    $data = array(
                        'username' => I('username'),
                        'password' => I('passwd',0,'md5'),
                        'login_time' => time(),
                        'login_ip' => get_client_ip()
                        );
                    $rest = M('commenter')->add($data);
                    $rest || $this->error(' REGISTER FAILED');
                    $this->success('REGISTER SUCCESS',U(MODULE_NAME.'/Login/index'));
                }
                $this->display();
     }

     public function logout() {
            session_unset($_SESSION);
            session_destroy($_SESSION);
           $this->redirect('index');
     }

}