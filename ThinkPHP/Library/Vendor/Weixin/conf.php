<?php

$button = 
'{
	"button":[
	{
		"type":"click",
		"name":"今日歌曲",
		"key":"V1001_TODAY_MUSIC"
	},
	{
		"name":"菜单",
		"sub_button":[
		{
			"type":"view",
			"name":"搜索",
			"url":"http://hello2world.top"
		},
		{
			"type":"view",
			"name":"视频",
			"url":"http://v.qq.com/"
		},
		{
			"type":"click",
			"name":"赞一下我们",
			"key":"V1001_GOOD"
		}]
	}]
}';

$text_msg = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>0<FuncFlag>
</xml>";

$img_msg = " <xml>
 <ToUserName><![CDATA[%s]]></ToUserName>
 <FromUserName><![CDATA[%s]]></FromUserName>
 <CreateTime>%s</CreateTime>
 <MsgType><![CDATA[%s]]></MsgType>
 <PicUrl><![CDATA[%s]]></PicUrl>
  <MediaId><![CDATA[%d]]></MediaId>
 <MsgId>%d</MsgId>
 </xml>";

return array(



	'image' => $img_msg,
	'text' => $text_msg,
	'button' => $button,
	);
