<?php

namespace Home\Controller;
use Think\Controller;
use Home\Library\Weixin;
use Home\Library\Weixin\Basic\Common;
use Home\Library\Weixin\MyMenu\Button;
use Home\Library\Weixin\MyMenu\MyMenu;
use Home\Library\Weixin\Message\Response;

//PdWVZbn7JxRz2-iZs-eyJ0-6Dfbtvj7vSKkcN0247FEVfRqaNau3-_5uO1p_MtYZMKx9qsh33m_rAVnyfxJEcsEfmqJBffS1FQRq653iMg0MGCgAFABVR

CLass WeixinController extends Controller {

	public function _initialize() {
		$this->res();
	}

	public function res() {
		$res = new Response();
		$res->response();
	}

	public function initMenu() {
		$btn1 = new Button('音乐', 'click', '', 'today_music');
		$btn2 = new Button('menu');
		$btn21 = new Button('search', 'view', 'http://hello2world.top');
		$btn22 = new Button('video', 'view', 'http://hello2world.top');
		$btn23 = new Button('good', 'click', '', 'good');
		$btn2->addSubButton($btn21);
		$btn2->addSubButton($btn22);
		$btn2->addSubButton($btn23);

		$menu = new MyMenu($btn1, $btn2);
		$rest = $menu->createMenu();
		exit($rest);
	}

	Public function test() { 
		Common::valid();
	}
	
}
