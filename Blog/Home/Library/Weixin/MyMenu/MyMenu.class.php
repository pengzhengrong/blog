<?php 

namespace Home\Library\Weixin\MyMenu;
use Home\Library\Weixin\Basic\Common;
// include('Blog/Library/FirePHPCore/fb.php');


/**
 * 个性化菜单请求接口: https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=ACCESS_TOKEN
 */
class MyMenu {

	protected $btn1 = null;
	protected $btn2 = null;
	protected $btn3 = null;
	public $otherMenu = array();
	protected $otherMenuUrl = 'https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=';
	protected $basicMenuUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=';

	public function __construct($btn1=null, $btn2=null, $btn3=null) {
		$this->btn1 = $btn1;
		$this->btn2 = $btn2;
		$this->btn3 = $btn3;
	}

	public function createMenu() {
		$data['button'] = array();
		if ($this->btn1) {
			$data['button'][] = $this->btn1->getButton();
		}
		if ($this->btn2) {
			$data['button'][] = $this->btn2->getButton();
		}
		if ($this->btn3) {
			$data['button'][] = $this->btn3->getButton();
		}
		// 创建菜单请求接口
		$accessToken = Common::getAccessToken();
		$url = $this->basicMenuUrl.$accessToken;
		// 个性化菜单
		if ($this->otherMenu) {
			$data['matchrule'] = $this->otherMenu;
			$url = $this->otherMenuUrl.$accessToken;
		}
		// 如果存在中文, 不做unicode转码
		$jsonBtn = json_encode($data, JSON_UNESCAPED_UNICODE);
		fb($jsonBtn, 'menu');
		fb($url, '请求地址');

		$rest = Common::curl($url, $jsonBtn);
		
		fb($rest);

		if ($rest['errcode'] == 0) {
			return 0;
		} else {
			return -1;
		}

	}

}

/*include('./Button.class.php');

$btn1 = new Button('今日歌曲', 'click', '', 'today_music');

$btn2 = new Button('菜单');
$btn21 = new Button('搜索', 'view', 'http://hello2world.top');
$btn22 = new Button('视频', 'view', 'http://hello2world.top');
$btn23 = new Button('赞一下', 'click', '', 'good');
$btn2->addSubButton($btn21);
$btn2->addSubButton($btn22);
$btn2->addSubButton($btn23);

// $btn1->addSubButton($btn21);

$menu = new MyMenu($btn1, $btn2);
$data = $menu->createMenu();

echo $data;
*/