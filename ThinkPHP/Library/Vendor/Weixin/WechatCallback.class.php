<?php

define('TOKEN','pengzhengrong');
Class WechatCallback {

	Public function __construct() {
		$this->wx_json = include_once( dirname(__FILE__).'/conf.php' );
		// $this->valid();
		// $this->responseText();
		$this->responseMsg();
	}

	Public function responseMsg() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		logger($postStr , '/tmp/wx.log');
		if( !empty($postStr) ) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$type = $postObj->MsgType;
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$time = time();
			$textTpl = $this->wx_json["$type"];
			if( $type == 'text' ) {
				$keyword = trim($postObj->Content);
				$contentStr = $type;
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "$type", $contentStr);
			} else {
				$url = 'www.baidu.com';
				$MediaId = $postObj->MediaId;
				$MsgId = $postObj->MsgId;
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "$type", $url,$MediaId,$MsgId);
			}

			echo $resultStr;
		}else{
			echo 'nothing';
			exit;
		}
	}

	Public function responseText() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		logger($postStr , '/tmp/wx.log');
		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$type = $postObj->MsgType;
			$time = time();
			$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0<FuncFlag>
			</xml>";
			if(!empty( $keyword ))
			{
				$msgType = "text";
				$contentStr = '你好啊，屌丝'.$type;
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}else{
				echo '咋不说哈呢';
			}
		}else {
			echo '咋不说哈呢';
			exit;
		}
	}

	Private function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];

		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	Public function buildCategory() {
		define('ACCESS_TOKEN',$this->getAccessToken());
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN;
		$rest = $this->curl($url,$this->wx_json['button']);
	}

	Public function valid() {
		if( $this->checkSignature() ) {
			ob_clean();
			$echoStr = $_GET['echostr'];
			echo $echoStr;
			exit;
		}
	}

	Private function getAccessToken() {
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf148972e7bed8f21&secret=39307286e79edd79a04c5ab8c52192cf';
		$json_data = file_get_contents($url);
		$data = json_decode($json_data,true);
		return $data['access_token'];
	}


	Private function curl( $url  ,$post_data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$rs = curl_exec($ch);
		$errno	= curl_errno($ch);
		curl_close($ch);
		return $rs;
	}
}

/*
Public  function createCategory() {

		define('ACCESS_TOKEN',$this->getAccessToken());
		$jsonCategory = include_once( dirname(__FILE__).'/category_conf.php' );
		$vars = json_decode($jsonCategory,true);
		// P($vars);die;
		$postdata  =  http_build_query ($vars);

		$opts  = array( 'http'  =>
			array(
				'method'   =>  'POST' ,
				'header'   =>  'Content-type: application/json; encoding=utf-8' ,
				'body'  =>  $postdata
				)
			);
		// P($opts);die;
		$context  =  stream_context_create ( $opts );
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN;

		$result  =  file_get_contents ( $url ,  false ,  $context );
		return $result;
	}
 */