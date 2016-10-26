<?php 

namespace Home\Library\Weixin\User;

use Home\Library\Weixin\Basic\Common;

class UserGroup {

	protected $baseCreateGroup = 'https://api.weixin.qq.com/cgi-bin/groups/create?access_token=';
	protected $baseGetGroup = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=';
	protected $baseGetIdGroup = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=';
	protected $baseUpdateGroup = 'https://api.weixin.qq.com/cgi-bin/groups/update?access_token=';
	protected $baseUpdateMembers = 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=';
	protected $baseBatchUpdateMembers = 'https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate?access_token=';
	protected $baseDeleteGroup = 'https://api.weixin.qq.com/cgi-bin/groups/delete?access_token=';
	protected $accessToken = '';

	public function __construct() {
		$this->accessToken = Common::getAccessToken();
	}

	public function createGroup($name) {
		// $json = '{"group":{"name":"test"}}';
		if (!$name) {
			exit('name不能为空!');
		}
		fb($name, 'name');
		$json = array(
			'group' => array(
				'name' => $name
				)
			);
		$jsonStr = json_encode($json, JSON_UNESCAPED_UNICODE);
		$url = $this->baseCreateGroup.$this->accessToken;
		$rs = Common::curl($url, $jsonStr);
		return $rs;
	}

	public function getGroup() {
		$url = $this->baseGetGroup.$this->accessToken;
		return Common::curl($url);
	}

	public function getGroupIdByOpenId($openId) {
		if (!$openId) {
			exit('openId不能为空!');
		}
		$json = array('openid'=>$openId);
		$jsonStr = json_encode($json);
		$url = $this->baseGetIdGroup.$this->accessToken;
		return Common::curl($url, $jsonStr);
	}

	public function updateGroup($id, $name) {
		if (empty($id) || empty($name)) {
			exit('id和name不能为空!');
		}
		$json = array(
			'id' => $id,
			'name' => $name
			);
		$jsonStr = json_encode($json, JSON_UNESCAPED_UNICODE);
		$url = $this->baseUpdateGroup.$this->accessToken;
		return Common::curl($url, $jsonStr);
	}

	public function updateMembers($openId, $groupId) {
		if (empty($openId) || empty($groupId)) {
			exit('openId和groupId不能为空!');
		}
		$json = array(
			'openid' => $openId,
			'to_groupid' => $groupId
			);
		$jsonStr = json_encode($json);
		$url = $this->baseUpdateMembers.$this->accessToken;
		return Common::curl($url, $jsonStr);
	}

	public function updateBatchMembers(array $openIds, $groupId) {
		if (empty($openIds) || empty($groupId)) {
			exit('openIds和groupId不能为空!');
		}
		$json = array(
			'openid_list' => implode(',', $openIds),
			'to_groupid' => $groupId
			);
		$jsonStr = json_encode($json);
		$url = $this->baseBatchUpdateMembers.$this->accessToken;
		return Common::curl($url, $jsonStr);
	}

	public function deleteGroup($groupId) {
		if (!$groupId) {
			exit('groupId不能为空!');
		}
		$json = array(
			'group' => array(
				'id' => $groupId
				)
			);
		$jsonStr = json_encode($json);
		$url = $this->baseDeleteGroup.$this->accessToken;
		return Common::curl($url, $jsonStr);
	}
}