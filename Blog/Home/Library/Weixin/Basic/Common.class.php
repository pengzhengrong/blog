<?php 

namespace Home\Library\Weixin\Basic;

define('TOKEN','pengzhengrong');

class Common {

	public static function getAccessToken() {
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf148972e7bed8f21&secret=39307286e79edd79a04c5ab8c52192cf';
		$json_data = file_get_contents($url);
		$data = json_decode($json_data,true);
		return $data['access_token'];
	}

	public static function curl( $url  ,$post_data){
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

	public function valid() {
		if( $this->checkSignature() ) {
			ob_clean();
			$echoStr = $_GET['echostr'];
			echo $echoStr;
			exit;
		}
	}

	private function checkSignature() {
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
}