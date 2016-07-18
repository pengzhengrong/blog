<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {

	public function _initialize () {
/*		echo ACTION_NAME;
		if( CONTROLLER_NAME == 'Comment' && ACTION_NAME== 'add'){
			if( empty( session('username') )  ){
				$this->error('Please Login ',U(MODULE_NAME.'/Login/index'));
			}
		}*/

		$this->module_name = MODULE_NAME;
	}

}