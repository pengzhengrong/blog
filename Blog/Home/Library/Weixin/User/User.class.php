<?php 

namespace Home\Library\Weixin\User;

use Home\Library\Weixin\Basic\Common;

class User {

	protected $baseUserInfo = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='; //ACCESS_TOKEN&openid=OPENID&lang=zh_CN
	protected $baseBatchUserInfo = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=';
	protected $accessToken = '';

	public function __construct() {
		$this->accessToken = Common::getAccessToken();
	}

	public function getUserInfo($openId, $lang='zh_CN') {
		if (!$openId) {
			exit('openId不能为空!');
		}
		$url = $this->baseUserInfo.$this->accessToken."&openid={$openId}&lang={$lang}";
		return Common::curl($url);
	}

	public function getUserList(array $openIds) {
		if (empty($openIds)) {
			exit('openIds不能为空!');
		}
		$userList = array();
		foreach ($openIds as $k => $v) {
			$userList['user_list'][] = array(
				'openid' => $v,
				'lang' => 'zh_CN'
				);
		}
		$url = $this->baseBatchUserInfo.$this->accessToken;
		$jsonStr = json_encode($userList);
		return Common::curl($url, $jsonStr);
	}

}