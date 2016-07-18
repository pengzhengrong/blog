<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {


  public $flag = false;

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
            $verify = new \Think\Verify();
            $check = $verify->check( I('code'));
            $check || notice('验证码错误,数据库是否没有开启？!','/login',1);

            $where = array(
                        'username' => I('username'),
                        'password' => I('passwd',0,'md5')
            );
            $rest = M('user')->where($where)->fetchSql(false)->find();
            // P($rest); die;
            $rest || notice( 'Login failed','/login',1 );
            session( 'username' , $rest['username']);
            session( C('USER_AUTH_KEY'), $rest['id'] );

            //update user login info
             $login_ip = get_client_ip();
             $data = array(
                  'login_ip' => $login_ip,
                  'login_time' => time(),
                  'id' => $rest['id']
             );
            $user_rest = M('user')/*->data( $data )*/->save($data);

            if( in_array($rest['username'],  explode(',', C('RBAC_SUPERADMIN'))) )
              session( C('ADMIN_AUTH_KEY') , $rest['id'] );
            if( C('URL_ROUTER_ON') ){
              $this->redirect('/admin');
            }
            $this->redirect(U(MODULE_NAME.'/Index/index'));
  }

  public function checkcode(){
    $verify = new \Think\Verify;
    $check = $verify->check( I('code'));
    if( $check ){
      exit('1');
    }
    exit('0');
  }

  public function checkpassword(){
     $where = array(
        'username' => I('username'),
        'passwd' => I('password' , '','md5')
      );
     $rest = M('user')->where($where)->find();
     $rest || exit('0');
     exit('1');
  }

  public function checkusername(){
    $rest = M('user')->field('id')->getByUsername( I('username') );
    // my_log('rest',json_encode($rest));
    $rest || exit('0');
    exit('1');
  }

  public function logout() {
    $_SESSION = array();
    session_unset();
    session_destroy();
    $this->redirect('index');
  }

}