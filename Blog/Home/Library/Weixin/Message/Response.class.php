<?php 

namespace Home\Library\Weixin\Message;

include('Blog/Library/FirePHPCore/fb.php');

class Response {

	protected $toUsername = '<ToUserName><![CDATA[toUser]]></ToUserName>';
	protected $fromUsername = '<FromUserName><![CDATA[FromUser]]></FromUserName>';
	protected $createTime = '<CreateTime>SendTime</CreateTime>';
	protected $msgType = '<MsgType><![CDATA[event]]></MsgType>';
	protected $event = '<Event><![CDATA[CLICK]]></Event>';
	protected $eventKey = '<EventKey><![CDATA[EVENTKEY]]></EventKey>';
	protected $content = '<Content><![CDATA[reqContent]></Content>';

	protected $xml = '<xml> %toUsername% %fromUsername% %createTime%  %content% %msgType% %event% %eventKey% </xml>';

	/**
	 * <xml>
	<ToUserName><![CDATA[toUser]]></ToUserName>
	<FromUserName><![CDATA[FromUser]]></FromUserName>
	<CreateTime>123456789</CreateTime>
	<MsgType><![CDATA[event]]></MsgType>
	<Event><![CDATA[CLICK]]></Event>
	<EventKey><![CDATA[EVENTKEY]]></EventKey>
	</xml>
	 */
	public function response() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		fb($postStr);
		if( !empty($postStr) ) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$type = $postObj->MsgType;
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$eventKey = $postObj->EventKey;
			$msgType = $postObj->MsgType;
			$xml = $this->replaceXml($toUsername, $fromUsername, $msgType, $event, $eventKey);
			echo $xml;
		}
	}

	public function replaceXml($toUsername, $fromUsername, $msgType, $event, $eventKey) {
		if ($toUsername) {
			$toUsername = str_replace('toUser', $toUsername, $this->toUsername);
		}
		if ($fromUsername) {
			$fromUsername = str_replace('FromUser', $fromUsername, $this->fromUsername);
		}
		if ($event == 'click') {
			if ($eventKey == 'today_music') {
				$content = '今日音乐被点击';
			}
		}

		$time = str_replace('SendTime', time(), $this->createTime);

		$content = str_replace('reqContent', $content, $this->content);

		$arr1 = array('%toUsername%', '%fromUsername%', '%createTime%',  '%content%', '%msgType%', '%event%', '%eventKey%');
		$arr2 = array($toUsername, $fromUsername, $time, $content, 'text', '', '');
		return str_replace( $arr1, $arr2, $this->xml );

	}
}