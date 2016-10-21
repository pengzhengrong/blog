<?php

namespace Home\Controller;
use Think\Controller;
use Home\Library\Weixin;
use Home\Library\Weixin\MyMenu\Button;

CLass WeixinController extends Controller {

	Public function test() { 
		$wechat = new Weixin\WechatCallback();

		$btn1 = new Button('今日歌曲', 'click', '', 'today_music');

		$btn2 = new Button('菜单');
		$btn21 = new Button('搜索', 'view', 'http://hello2world.top');
		$btn22 = new Button('视频', 'view', 'http://hello2world.top');
		$btn23 = new Button('赞一下', 'click', '', 'good');
		$btn2->addSubButton($btn21);
		$btn2->addSubButton($btn22);
		$btn2->addSubButton($btn23);

		$rest = $wechat->createMenu($btn1, $btn2);
	}
	
}
