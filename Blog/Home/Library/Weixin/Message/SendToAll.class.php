<?php 

namespace Home\Library\Weixin\Message;

use Home\Library\Weixin\Basic\Common;
/**
 * 群发功能
 */
class SendToAll{

	protected static $baseNewsUpload = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=';
	// 临时上传素材
	protected static $baseMediaUpload = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='; //type=thumb|image|voice|video
	// 获取临时上传素材
	protected static $baseGetMedia = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='; //&media_id=MEDIA_ID
	// protected static $baseImgUpload = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=';
	
	// 群发
	protected static $baseSendMsg = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=';

	protected static $mediaType = array('thumb', 'image', 'voice', 'video');
	protected static $contentType = array(
		'image' => 'image/*',
		'video' => 'video/mpeg4',
		'thumb' => 'image/jpeg',
		'voice' => 'audio/mp3'
		);

	public function __construct() {
		// self::$accessToken = Common::getAccessToken();
	}

	public static function init() {

	}

	/**
	 * 上传多媒体
	 * image: 2M bmp/png/jpeg/jpg/gif
	 * voice: 2M 60s amr\mp3
	 * video: 10M mp4
	 * thumb: 64k jpg
	 */
	public function upload($fileName, $type) {
		if (!in_array($type, self::$mediaType)) {
			exit('params is illegla');
		}
		$accessToken = Common::getAccessToken();
		$url = self::$baseMediaUpload.$accessToken."&type={$type}";
		$realPath = "{$_SERVER['DOCUMENT_ROOT']}{$filename}";
		$fileInfo = array(
			'filename' => $filename,
			'content-type' => self::$contentType[$type],
			'filelength' => filesize($realPath)
			);
		$json = array(
			'media' => "@{$realPath}",
			'form-data' => $fileInfo
			);
		$data = Common::curl($url, $json);
	}

	// 群发
	public function send2All(array $params, $type) {
		$is_to_all = (boolean)$params['is_to_all'];
		$group_id = $params['group_id'];
		if ($type == 'mpnews') {
			$media_id = $params['media_id'];
			$jsonStr = $this->sendMpnews($is_to_all, $group_id, $media_id);
		} elseif ($type == 'text') {
			$content = $params['content'];
			$jsonStr = $this->sendText($is_to_all, $group_id, $content);
		}
		$url = self::$baseSendMsg.Common::getAccessToken();
		$rs = Common::curl($url, $data);
		P($rs);
	}

	/*
	'{"filter":{"is_to_all":false,"group_id":2},
	"mpnews":{"media_id":"uPWDTrVFsG85RVQwllsFV7XGmMtI5R2AeJ85-zNcpj1XBI-Qx753Yyi6Kb0-47ex"},
	"msgtype":"mpnews"}';
	*/
	public static function sendMpnews($is_to_all, $group_id, $media_id) {
		$json['filter'] = array('is_to_all' => $is_to_all, 'group_id' => $group_id);
		$json['mpnews'] = array('media_id' => $media_id);
		$json['msgtype'] = 'mpnews'; 
		$jsonStr = json_encode($json);
		return $jsonStr;
	}

	/*
	'{"filter":{"is_to_all":true,"group_id":2},
	"text":{"content":"uPWDTrVFsG85RVQwllsFV7XGmMtI5R2AeJ85-zNcpj1XBI-Qx753Yyi6Kb0-47ex"},
	"msgtype":"text"}'
	 */
	public static function sendText() {
		$json['filter'] = array('is_to_all' => $is_to_all, 'group_id' => $group_id);
		$json['text'] = array('content' => $content);
		$json['msgtype'] = 'text'; 
		$jsonStr = json_encode($json);
		return $jsonStr;
	}

	/*
	* 返回 media_id: lPs7vyT1FfFPHGwr8tvQZRi4x1qZjxwuMtJdg0hXLUloBuWEZeS-hGNxyDLYZHAc
	 */
	public static function createTpl(array $tplArr=array()) {
		$tpl = array();
		foreach ($tplArr as $k => $v) {
			$tpl['articles'][] = $v;
		}
		$url = self::$baseNewsUpload.Common::getAccessToken();

		$data = Common::curl($url, self::$article);
		fb($data);
		P($data);
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