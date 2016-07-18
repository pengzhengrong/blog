<?php


function baiduAccount(){
	echo '<script>
	var _hmt = _hmt || [];
	(function() {
		var hm = document.createElement("script");
		hm.src = "//hm.baidu.com/hm.js?e58ba1963b5a50dd007b97734b0dbfd8";
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(hm, s);
	})();
</script>
';
}

function node_merge ( $node , $access = null , $pid=0) {

	$arr  = array();
	foreach ($node as $key => $value) {
		if( is_array( $access )){
			$value['access'] = in_array($value['id'], $access )?1:0;
		}
		if( $value['pid'] == $pid ){
			$value['child'] = node_merge( $node , $access ,$value['id'] );
			$arr[] = $value;
		}
	}
	return $arr;
}

/*将存在id和pid的乱序一维数组组合成一个有序的一维数组*/
function rest_merge($rest , $pid=0) {
	static $arr = array();
	foreach ($rest as $key => $value) {
		if( $value['pid'] == $pid ) {
			$arr[] = $value;
			rest_merge( $rest , $value['id'] );
		}
	}
	return $arr;
}

/*获取栏目导航*/
function getParents( $rest, $id ) {
	if( !is_array($rest) ) {
		return;
	}
	static $arr = array();
	foreach ($rest as $v) {
		if( $v['id'] == $id ) {
			$arr[] = $v;
			getParents( $rest , $v['pid'] );
		}
	}
	return array_reverse($arr);
}

/*
* 将存在id和pid的一维乱序数组组合成树状有序的数组
* $rest 一维数组，必须包含又字段id和pid
* $pid 父类id
*/
function tree($rest , $pid=0) {
	static $arr = array();
	static $count=0; //当前遍历树的深度
	static $depth = 0; //树的最大深度
	static $node = 0; //子节点的个数
	static $node_temp = 0; //在子节点的个数设置为0前，临时存储子节点的个数
	$space = str_repeat('&nbsp;',6);
	foreach ($rest as $key => $value) {
		if( $value['pid'] == $pid) {
			if( $pid == 0 ) {
				$count = 0;
				$depth = 0;
			} else {
				$count++;
				$depth = $count;
				$title = str_repeat($space.'│',$count-1);
				$title .= $space.'├─ '.$value['title'];
				$value['title'] = $title;
			}
			$arr[] = $value;
			tree( $rest , $value['id'] );
		}
	}
	// 替换中间节点的树枝
	if( $depth-1 == $count && $count >= 0 ) {
		$len = count($arr);
		$arr[$len-1]['title'] = str_replace('├─', '└─', $arr[$len-1]['title']);
	}

	if( $depth == $count ) {
		$node++;
		$node_temp = $node;
	} else {
		$node = 0;
	}
	// echo $depth.'_'.$count.'_'.$node_temp.' ';
	//替换最后节点上的树枝
	if( $count == 0 && $depth > 0 ) {
		$len = count($arr);
		// echo $len;
		for( $i=0;$i<$node_temp;$i++ ) {
			$arr[$len-1-$i]['title'] = str_replace('│', ' ', $arr[$len-1-$i]['title']);
		}
		if( $len-1-$node_temp >= 0  )
			$arr[$len-1-$node_temp]['title'] = str_replace('├─', '└─', $arr[$len-1-$node_temp]['title']);
	}
	$count--;
	return $arr;
}

/*从数组中获取select标签的选项*/
function options($rest , $key='id', $value='title' , $filter_key='',$filter_value='') {
	if( !empty($filter_key) && $filter_value != '' ) {
		$arr = array();
		foreach ($rest as $v) {
			if( $v[$filter_key] == $filter_value ) {
				$arr[] = $v;
			}
		}
		$rest = $arr;
	}
	$keys = array_column( $rest , $key );
	$values = array_column( $rest , $value );
	return array_combine($keys, $values);
}

function  p( $param ) {

	if( is_array( $param )){
		dump( $param );
		return;
	}
	echo $param."<br />";

}

function my_log( $key='' , $value=null ) {
	$value = empty($value)?$key:$value;
	@error_log( "\n $key=".$value  ,3 , '/tmp/pzrlog.log');
}

/**
@param $arr
@param $id
*/
function getChildrens( $arr , $id ){
	$databack = $id;
	foreach ($arr as $key => $value) {
		if( $value['pid'] == $id ){
			$databack .=  ','.getChildrens( $arr , $value['id'] );
		}
	}
	return $databack;
}

function getSearch( $rest ,$fields, $key='hits'){

	$databack = array();
	$rest = $rest[$key];
	// P($rest);die;
	if( $key == 'hits' ){
		foreach ($rest['hits'] as  $k=>$v) {
			foreach ($fields as $kk=>$vv) {
				if( strpos($vv,'_') === 0){
					$databack[$k][$kk] = $v[$vv];
				}elseif( $kk=='highlight' ){
					$databack[$k][$kk] = $v[$vv];
				}else{
					$databack[$k][$kk] = $v['fields'][$vv][0];
				}
			}
		}
	}
	// P($databack);die;
	return $databack;
}

function cutHighlightContent( $content ,$len = 100){
	// p($content);
	// $content = '<em>hello</em>';
	// $content = preg_replace('/.*?(<em>.*?<\/em>\S{20}).*/', '$1...' ,$content);
	$mb_len = mb_detect_encoding($content) == 'UTF-8' ? 2 : 1;
	// $content = preg_replace('/[\s\S]*(<em>.*?<\/em>[\s\S]{20})[\s\S]*/', '$1...' ,$content);
	$content = preg_replace('/[\s\S]*(<em>.*?<\/em>([\x00-\x7f]|[\x80-\xff].{' . $mb_len . '}){'.$len.'})[\s\S]*/', '$1...' ,$content);
	// p($content);die;
	return $content;
}

function logger($msg , $file='blog.log') {
	// \Think\Log::write('logic delete blog'.I('id'),'INFO','File','/tmp/blog.log');
	// \Think\Log::record('logic delete blog'.I('id'),'INFO');
	// \Think\Log::save('File','/tmp/blog.log');
	\Think\Log::write($msg,'INFO','File','/home/pzr/workspace/blog/log/'.$file);
}

function setAjaxReturn( $rest ,$msg='操作失败!',$data='') {
	if( $rest ) {
		$databack = array(
			'code' => 200,
			'msg' => '',
			'data' => $data
			);
	} else {
		$databack = array(
			'code' => 500,
			'msg' => $msg,
			'data' => $data
			);
	}
	// logger(json_encode($databack));
	return $databack;
}

