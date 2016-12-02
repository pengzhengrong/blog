<?php

namespace Home\Controller;
use Think\Controller;
use Home\Library\Weixin;
use Home\Library\Weixin\Basic\Common;
use Home\Library\Weixin\MyMenu\Button;
use Home\Library\Weixin\MyMenu\MyMenu;
use Home\Library\Weixin\Message\Response;
use Home\Library\Weixin\Message\Send2All;
use Home\Library\Weixin\User\UserGroup;

//PdWVZbn7JxRz2-iZs-eyJ0-6Dfbtvj7vSKkcN0247FEVfRqaNau3-_5uO1p_MtYZMKx9qsh33m_rAVnyfxJEcsEfmqJBffS1FQRq653iMg0MGCgAFABVR
// include('Blog/Library/FirePHPCore/fb.php');

CLass WeixinController extends Controller {

	private static $keyWords = array('你好','什么');

	public function _initialize() {
		// $this->initMenu();
		$this->res();
	}

	public function res() {
		$res = new Response();
		error_log(json_encode($res->msgInfo), 3, '/tmp/pzrlog.log');
		$sendMsg = $this->getResponseXml($res);
		if ($sendMsg) {
			error_log($sendMsg, 3, '/tmp/pzrlog.log');
			echo $sendMsg;
			exit;
		}
	}

	// 用户组操作
	public function group() {
		$operator = $_GET['o'];
		$user = new UserGroup();
		switch ($operator) {
			case 'create':
				if (isset($_GET['name'])) {
					$data = $user->createGroup($_GET['name']);
				}
				break;
			case 'update':
				if (isset($_GET['id']) && isset($_GET['name'])) {
					$data = $user->updateGroup($_GET['id'], $_GET['name']);
				}
				break;
			case 'get':
				$data = $user->getGroup();
				break;
			case 'delete':
				if (isset($_GET['id'])) {
					$data = $user->deleteGroup($_GET['id']);
				}
				break;
			case 'getId':
				if (isset($_GET['openId'])) {
					$data = $user->getGroupIdByOpenId($_GET['openId']);
				}
				break;
			case 'update_members': 
				if (isset($_GET['openId']) && isset($_GET['groupId'])) {
					$data = $user->updateMembers($_GET['openId'], $_GET['groupId']);
				}
				break;
			case 'batch_update':
				if (isset($_GET['openIds']) && isset($_GET['groupId'])) {
					$ids = explode(',', $_GET['openId']);
					$data = $user->updateBatchMembers($ids, $_GET['groupId']);
				}
				break;
			default:
				# code...
				break;
		}
		if (empty($data)) {
			$data = 'params error';
		}
		P($data);
	}

	public function getResponseXml($res) {
		$msgInfo = $res->msgInfo;
		// 菜单点击发送事件
		if ($msgInfo['event'] == 'CLICK') {
			if ($msgInfo['eventKey'] == 'today_music') {
				$content = '今日音乐被点击';
				$sendMsg = $res->responseText($content);
			} elseif ($msgInfo['eventKey'] == 'good') {
				$item = array(
					'title' => '被赞了,很开心!',
					'description' => '介绍下哦!',
					'picUrl' => 'http://hello2world.top/uploadfile/2016-10-25/pic_logo_1082168b9.png',
					'url' => 'http://hello2world.top'
					);
				$item2 = array(
					'title' => '被赞了,很开心!',
					'description' => '介绍下哦!',
					'picUrl' => 'http://hello2world.top/uploadfile/2016-10-25/vn.jpg',
					'url' => 'http://hello2world.top'
					);
				$sendMsg = $res->responseNews(array($item, $item2));
			} elseif ($msgInfo['eventKey'] == 'video') {
				// $mediaId = '1EUQscgpWsVS2YBdQw73K1ec1645Eyp8DrfvaC-v2ouV9ZJuWPKl_Q8IHx5C1PpW';
				$mediaId = 'http://mmbiz.qpic.cn/mmbiz_png/A5iaQqyLC3hjjicGAVzDgk4ZVXIWCdk4ibop8dEx7c78LvndWdicicgeMic5B1F9uuT6M0yeJeBbfM30G0z6D4eIqOAg/0';
				$sendMsg = $res->responseImage($mediaId);
			}
		}
		//首次关注自动回复
		elseif ($msgInfo['event'] == 'subscribe') {
			$conten = '感谢您的关注!';
			$sendMsg = $res->responseText($conten);
		}
		// 关键字自动回复
		if ( in_array($msgInfo['content'], self::$keyWords)  ) {
			$content = '你好我才好';
			$sendMsg = $res->responseText($content);
		}
		// 一般消息自动回复 
		elseif ( $msgInfo['content'] != '' ) {
			$content = '你好,有什么需要帮助的吗?';
			$sendMsg = $res->responseText($content);
		}

		

		return $sendMsg;
	}

	public function initMenu() {
		$btn1 = new Button('音乐', 'click', '', 'today_music');
		$btn2 = new Button('menu');
		$btn21 = new Button('search', 'view', 'http://hello2world.top');
		$btn22 = new Button('video', 'click', '', 'video');
		$btn23 = new Button('good', 'click', '', 'good');
		$btn2->addSubButton($btn21);
		$btn2->addSubButton($btn22);
		$btn2->addSubButton($btn23);
		// 基本菜单
		$menu = new MyMenu($btn1, $btn2);
		$rest = $menu->createMenu();
		// 个性化菜单
		$menu2 = new MyMenu($btn2, $btn1);
		// 个性化菜单匹配规则
		$menu2->otherMenu = array(
			'sex' => '1',
			// 'language' => 'zh_CN',
			// 'client_platform_type' => 2,
			// 'group_id' => '',
			);
		$rest = $menu2->createMenu();
	}

	Public function test() { 
		Common::valid();
	}

	public function createTpl() {
		Send2All::createTpl();
	}

	public function upload() {
		$type = $_GET['type'];
		if ( $type == 'img') {
			Send2All::uploadImg();
		} elseif ($type == 'thumb') {
			Send2All::uploadThumb();
		}
	}

	public function send2All() {
		$type = $_GET['type'];
		if ($type == 'mpnews') {
			Send2All::sendMpnews();
		} elseif ($type == 'text') {
			Send2All::sendText();
		}
	}

}
