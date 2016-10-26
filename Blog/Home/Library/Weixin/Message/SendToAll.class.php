<?php 

namespace Home\Library\Weixin\Message;

use Home\Library\Weixin\Basic\Common;
/**
 * 群发功能
 */
class SendToAll{

	// private $thumb_media_id = ''; // 图文消息缩略图的media_id
	// private $author
	protected static $baseNewsUpload = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=';
	protected static $baseMediaUpload = 'https://api.weixin.qq.com/cgi-bin/media/upload?type=thumb&access_token=';
	protected static $baseImgUpload = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=';
	protected static $baseSendMsg = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=';

	protected static $accessToken = '';

	public function __construct() {
		// self::$accessToken = Common::getAccessToken();
	}

	public static function init() {

	}

	public static function sendMpnews() {
		$data = '{
			"filter":{
				"is_to_all":false,
				"group_id":2
			},
			"mpnews":{
				"media_id":"uPWDTrVFsG85RVQwllsFV7XGmMtI5R2AeJ85-zNcpj1XBI-Qx753Yyi6Kb0-47ex"
			},
			"msgtype":"mpnews"
		}';
		$url = self::$baseSendMsg.Common::getAccessToken();
		$rs = Common::curl($url, $data);
		P($rs);
	}

	public static function sendText() {
		$data = '{
			"filter":{
				"is_to_all":true,
				"group_id":2
			},
			"text":{
				"content":"uPWDTrVFsG85RVQwllsFV7XGmMtI5R2AeJ85-zNcpj1XBI-Qx753Yyi6Kb0-47ex"
			},
			"msgtype":"text"
		}';
		$url = self::$baseSendMsg.Common::getAccessToken();
		$rs = Common::curl($url, $data);
		P($rs);
	}

	/*
	* 上传本地图片到微平台, 仅支持jpg/png
	* 上传的图片返回url作为图文消息素材的备用
	* 返回 url: http://mmbiz.qpic.cn/mmbiz_png/A5iaQqyLC3hjjicGAVzDgk4ZVXIWCdk4ibop8dEx7c78LvndWdicicgeMic5B1F9uuT6M0yeJeBbfM30G0z6D4eIqOAg/0
	* $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$accessToken}&type=image";
	 */ 
	public static function uploadImg() {
		$accessToken = Common::getAccessToken();
		$url = self::$baseImgUpload.$accessToken;
		
		fb($url);
		$fileInfo = array(
			'filename' => '/uploadfile/2016-10-10/栏目.png',
			'content-type' => 'image/png',
			'filelength' => '11011'
			);
		$realPath = "{$_SERVER['DOCUMENT_ROOT']}{$fileInfo['filename']}";
		fb($realPath);
		$data = array(
			'media' => "@{$realPath}",
			'form-data' => $fileInfo
			);
		$data = Common::curl($url, $data);
		fb($data);
		P($data);
	}

	/*
	*  返回 thumb_media_id : LsTjMzW0b33Brnz5EEwf57ST8q9mwM0_Lr6Ab-2ZPdjM8KmPkOBW_YuyHZ-Ild53
	 */
	public static function uploadThumb() {
		$accessToken = Common::getAccessToken();
		$url = self::$baseMediaUpload.$accessToken;
		// $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$accessToken}&type=image";
		$fileInfo = array(
			'filename' => '/uploadfile/2016-10-10/栏目.png',
			'content-type' => 'image/png',
			'filelength' => '11011'
			);
		$realPath = "{$_SERVER['DOCUMENT_ROOT']}{$fileInfo['filename']}";
		fb($realPath);
		$data = array(
			'media' => "@{$realPath}",
			'form-data' => $fileInfo
			);
		$data = Common::curl($url, $data);
		P($data);
	}

	/*
	* 返回 media_id: lPs7vyT1FfFPHGwr8tvQZRi4x1qZjxwuMtJdg0hXLUloBuWEZeS-hGNxyDLYZHAc
	 */
	public static function createTpl(array $tplArr=array()) {
		$url = self::$baseNewsUpload.Common::getAccessToken();
		$data = Common::curl($url, self::$article);
		fb($data);
		P($data);
	}

	public function respText() {

	}

	private static $article = '{
		"articles": [
		{
			"thumb_media_id":"1EUQscgpWsVS2YBdQw73K1ec1645Eyp8DrfvaC-v2ouV9ZJuWPKl_Q8IHx5C1PpW",
			"author":"xxx",
			"title":"Happy Day",
			"content_source_url":"www.qq.com",
			"content":"content",
			"digest":"digest",
			"show_cover_pic":1
		},
		{
			"thumb_media_id":"1EUQscgpWsVS2YBdQw73K1ec1645Eyp8DrfvaC-v2ouV9ZJuWPKl_Q8IHx5C1PpW",
			"author":"xxx",
			"title":"Happy Day",
			"content_source_url":"www.qq.com",
			"content":"content",
			"digest":"digest",
			"show_cover_pic":0
		}
		]
	}';
	//http://mmbiz.qpic.cn/mmbiz_png/A5iaQqyLC3hjjicGAVzDgk4ZVXIWCdk4ibop8dEx7c78LvndWdicicgeMic5B1F9uuT6M0yeJeBbfM30G0z6D4eIqOAg/0
}