<?php 

namespace Home\Library\Weixin\Message;

// include('Blog/Library/FirePHPCore/fb.php');

/**
 * news :item最多10条
 */
class Response {

	// 基本信息 必要
	protected $toUsername = '<ToUserName><![CDATA[toUser]]></ToUserName>';
	protected $fromUsername = '<FromUserName><![CDATA[fromUser]]></FromUserName>';
	protected $createTime = '<CreateTime>sendTime</CreateTime>';
	protected $msgType = '<MsgType><![CDATA[msgType]]></MsgType>';

	protected $event = '<Event><![CDATA[event]]></Event>';
	protected $eventKey = '<EventKey><![CDATA[eventKey]]></EventKey>';

	// 文本消息
	protected $content = '<Content><![CDATA[content]]></Content>';

	// 图文消息
	protected $title = '<Title><![CDATA[title]]></Title>';
	protected $description = '<Description><![CDATA[description]]></Description>';
	protected $picUrl = '<PicUrl><![CDATA[picUrl]]></PicUrl>';// 图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
	protected $url = '<Url><![CDATA[url]]></Url>';
	protected $articleCount = '<ArticleCount>articleCount</ArticleCount>';// 必要

	// 图片消息
	protected $mediaId = '<Image><MediaId><![CDATA[media_id]]></MediaId></Image>';

	protected $xml = '<xml>
	%toUsername%
	%fromUsername%
	%createTime%
	%msgType%
	%content%
	%articleCount%
	%articles%
	</xml>';
	//	%event% %eventKey%

	public $msgInfo = array(
		'toUsername' => '',
		'fromUsername' => '',
		'msgType' => '',
		'content' => '',
		'event' => '',
		'eventKey' => '',
		'createTime' => '',
		'msgId' => '',
		'latitude' => '',		//地理位置纬度
		'longitude' => '',		//地理位置经度
		'precision' => '',		//地理位置精度
		);

	public function __construct() {
		$this->getMsgInfo();
	}

	public function getMsgInfo() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		error_log($postStr, 3, '/tmp/pzrlog.log');
		if( !empty($postStr) ) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->msgInfo['msgType'] = $postObj->MsgType;
			$this->msgInfo['fromUsername'] = $postObj->FromUserName;
			$this->msgInfo['toUsername'] = $postObj->ToUserName;
			$this->msgInfo['eventKey'] = $postObj->EventKey;
			$this->msgInfo['event'] = $postObj->Event;
			$this->msgInfo['msgType'] = $postObj->MsgType;
			$this->msgInfo['createTime'] = $postObj->CreateTime;
			$this->msgInfo['content'] = $postObj->Content;
			$this->msgInfo['msgId'] = $postObj->MsgId;
			$this->msgInfo['latitude'] = $postObj->Latitude;
			$this->msgInfo['longitude'] = $postObj->Longitude;
			$this->msgInfo['precision'] = $postObj->Precision;
		}
	}

	/*
	<xml>ToUserName,FromUserName,CreateTime,MsgType,Content</xml>
	文本消息回复
	 */
	public function responseText($content) {
		$content = str_replace('content', $content, $this->content);
		$params = array('content' => $content);
		return $this->replaceXml($params);

	}

	public function responseImage($mediaId) {
		$mediaId = str_replace('media_id', $mediaId, $this->mediaId);
		$params = array('mediaId' => $mediaId);
		return $this->replaceXml($params, 'image');
	}

	/*
	 xml ToUserName,FromUserName,CreateTime,MsgType,ArticleCount,Articles,item,Title,Description,PicUrl,Url,/item,Articles /xml
	 图文消息回复
	 */
	public function responseNews(array $items=array()) {
		if (empty($items)) {
			$this->replaceXml(array(), 'news');
		}
		$itemArr = array();
		if (count($items) > 10) {
			$items = array_slice($items, 0, 10);
		}
		foreach ($items as $k => $v) {
			$title = $v['title']==''?'':str_replace('title', $v['title'], $this->title);
			$description = $v['description']==''?'':str_replace('description', $v['description'], $this->description);
			$picUrl = $v['picUrl']==''?'':str_replace('picUrl', $v['picUrl'], $this->picUrl);
			$url = $v['url']==''?'':str_replace('url', $v['url'], $this->url);
			$itemTemp = '<item>'.$title.$description.$picUrl.$url.'</item>';
			$itemArr[] = $itemTemp;
		}
		$articleCount = str_replace('articleCount', count($items), $this->articleCount);
		$articles = '<Articles>'.implode(' ', $itemArr).'</Articles>';
		$params = array(
			'articleCount' => $articleCount,
			'articles' => $articles
			);
		return $this->replaceXml($params, 'news');
	}

	public function replaceXml($params=array(), $msgType='text') {
		$msgInfo = $this->getMsgInfo();
		$msgTypeTmp = $msgType;
		if (!$fromUsernameTmp = $this->msgInfo['fromUsername']) {
			exit('fromUsername 不能为空!');
		}
		if (!$toUsernameTmp = $this->msgInfo['toUsername']) {
			exit('toUsername 不能为空!');
		}
		// 发件人变成收件人 收件人变成发件人
		$toUsername = str_replace('toUser', $fromUsernameTmp, $this->toUsername);
		$fromUsername = str_replace('fromUser', $toUsernameTmp, $this->fromUsername);
		// 发送时间
		$time = str_replace('sendTime', time(), $this->createTime);
		// 发送类型
		$msgType = str_replace('msgType', $msgType, $this->msgType);

		if ($msgTypeTmp == 'text') {
			$content = $this->getParam($params, 'content');
			$arr1 = array('%toUsername%', '%fromUsername%', '%createTime%',  '%content%', '%msgType%');
			$arr2 = array($toUsername, $fromUsername, $time, $content, $msgType);
		} elseif ($msgTypeTmp == 'news') {
			$articles = $this->getParam($params, 'articles');
			$articleCount = $this->getParam($params, 'articleCount');
			$arr1 = array('%toUsername%', '%fromUsername%', '%createTime%', '%msgType%', '%articleCount%', '%articles%');
			$arr2 = array($toUsername, $fromUsername, $time, $msgType, $articleCount, $articles);
		} elseif ($msgTypeTmp == 'image') {
			$mediaId = $this->getParam($params, 'mediaId');
			$arr1 = array('%toUsername%', '%fromUsername%', '%createTime%', '%msgType%', '%mediaId%');
			$arr2 = array($toUsername, $fromUsername, $time, $msgType, $mediaId);
		}
		$this->xml = '<xml>'.implode('', $arr1).'</xml>';
		return str_replace( $arr1, $arr2, $this->xml );
	}

	public function getParam($params, $key) {
		return isset($params[$key])?$params[$key]:'';
	}
}