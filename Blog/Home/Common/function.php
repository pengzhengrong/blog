<?php

function explode_arr( $node_id ){
	return explode( '_' , $node_id);
}

function version() {
	return '1.0';
}

function notice( $msg='' , $jumpUrl='' , $waitSecond=0 ,$operator='delete'){
	if( empty($msg) ) $msg='Notice';
	if(I('_notice_')==1 ) return;
	if( empty($jumpUrl) ){
		$jumpUrl = __SELF__;
		if(   ($pos = strpos( __SELF__,  C('TMPL_TEMPLATE_SUFFIX') )) ){
			$jumpUrl =  substr( __SELF__, 0 ,$pos);
		}
	}
	// echo T('Common/notice','Tpl'); die;
	include  T('Common/notice','Tpl');
	exit;
}

function fixedSubmit(){
	return '<div class="fixed-bottom" >
	<div class="fixed-bottom fixed-but">
		<input   type="submit" value="SUBMIT" />
	</div>
</div>';
}

function dataclean( $data ){
		//trim &nbsp;
	$temp = preg_replace('/&nbsp;/', ' ', $data);
	$temp = preg_replace('/<br\/>/', '', $temp);
	$temp = preg_replace('/(<\/pre>)|(<pre.*?[^>]>)/', ' ', $temp);
	$temp = preg_replace('/(<\/p>)|(<p>)/', ' ', $temp);
		// $temp = htmlspecialchars_decode($temp , ENT_QUOTES);
	$temp = html_entity_decode($temp,ENT_QUOTES);
		//trim html&php tags
		// $temp = strip_tags($temp);
	return $temp;
}

function status( $status) {
	if( $status == 1 ) {
		return '<li class="fa fa-times">';
	} else {
		return '<li class="fa fa-check">';
	}
}

function increBlogClick($id) {
	$key = "BLOG_IDS_CACHE";
	$value = S($key)==null?array():S($key);
	$value[] = $id;
	S($key, $value, 300);
	
	$cacheKey = "BLOG_ID_{$id}";
	S($cacheKey,S($cacheKey)+1,300);
}

