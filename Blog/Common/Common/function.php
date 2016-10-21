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

/** 
 * 获取栏目导航
 * @param  array $rest 栏目数组
 * @param  int $id   栏目id
 * @return array 栏目id的父集
 */
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

/**
 * 废弃原来的tree方法和tree2方法，将项目后台模块的Rbac控制器中的tree2方法的调用替换成tree。
 * 树形菜单，真正意义上的树
 * @param  array  $rest 含有id，pid，title的数组
 * @param  integer $pid  父节点的id
 * @param  boolean $root 为了显示当前树的父节点但是pid != 0 的情况，查询的时候作用
 * @param  boolean $checkbox 兼容复选框的情况，如果只是显示树形菜单可以为false
 * @param  array $selected 当$checkbox为true的时候，这个参数有效。默认选中的复选框。
 * @return array       返回处理好的树形菜单
 */
function tree($rest , $pid=0 , $root=false, $checkbox=false, $selected=array()) {
	static $arr = array();
	static $current_depth = 0; //当前遍历树的深度
	static $tree_grow_node = array();
	$space = str_repeat('&nbsp;',6);
	if( $root ) {
		foreach ($rest as $key => $value) {
			if( $value['id'] == $pid ) {
				$arr[] = $checkbox?warp_checkbox($value, $selected):$value;
				break;
			}
		}
	}
	foreach ($rest as $key => $value) {
		
		if( $value['pid'] == $pid) {
			if( $pid == 0 ) {
				$current_depth = 0;
				$tree_grow_node = array();
				$value = $checkbox?warp_checkbox($value, $selected):$value;
			} else {
				$current_depth++;
				
				$tree_grow_node[] = $current_depth;
				tree_replace( $tree_grow_node, $arr );
				$value = $checkbox?warp_checkbox($value, $selected):$value;
				$title = str_repeat($space.'│',$current_depth-1);
				$title .= $space.'├─ '.$value['title'];
				$value['title'] = $title;
			}
			$arr[] = $value;
			tree( $rest , $value['id'], false, $checkbox, $selected );
		}
	}

	// 当递归结束的时候，处理最后一个节点上的title。
	// 因为在结束时，tree_grow_node不会增加当前的节点0，所以需要手动增加
	if( $current_depth == 0 ) {
		$tree_grow_node[] = 0;
		tree_replace( $tree_grow_node, $arr );
	}
	$current_depth--;
	return $arr;
}

/**
 * [tree_replace 替换子节点上的├─为└─，并且将└─子节点对应列的│替换成空格]
 * @param  [type] &$tree_grow_node [递归增加当前的节点数的数组,类似一棵树从树根到树枝的数组]
 * @param  [type] &$arr         [递归增加父节点和子节点数组]
 */
function tree_replace(&$tree_grow_node, &$arr) {
	$tree_node_len = count($tree_grow_node);
	// $last_node 当前树枝的末节点
	// $small_node 比较前后两个节点，只取更小的节点
	// $prev 当前节点的前一个节点
	if ( $tree_grow_node[$tree_node_len-1] < $tree_grow_node[$tree_node_len-2] ) {
		$last_node = $tree_grow_node[$tree_node_len-1];
		$small_node = $tree_grow_node[$tree_node_len-2];
		$once = array();
		for ( $i=0; $i<$tree_node_len; $i++ ) {
			$prev = $tree_grow_node[$tree_node_len-2-$i];
			//$prev > $last_node 末节点一旦变小，则认为上一个节点则是树枝的尽头
			//!in_array( $prev, $once ) 避免重复替换相同节点上的├─
			//$small_node >= $prev 避免重复处理已经处理过的节点
			if( $prev > $last_node 
				// && strpos( $arr[count($arr)-1-$i]['title'], '├─' ) > -1 
				&& !in_array( $prev, $once ) 
				&& $small_node >= $prev) {
				$arr[ count($arr)-1-$i ]['title'] = str_replace('├─', '└─', $arr[count($arr)-1-$i ]['title']);

			title_replace( $tree_grow_node, $arr, $prev );
			array_push( $once, $prev );
			if ( $small_node >= $prev ) {
				$small_node = $prev;
			}
		}
		//$last_node==0 && $prev==1 增加这个条件是为了处理最后一个节点上的title
		//$last_node == $prev 这个条件的目的类似于：
		// (1)(2)(3)(4)(4)(5)(6)(3) 当最后一个节点是3的时候，向前查找节点也为3的节点，那么这两个节点之间的节点是属于需要处理的节点
		if( $last_node == $prev || ( $last_node==0 && $prev==1 ) ) { 
			break;
		}

	}

}
}

// 循环替换└─节点下的│
function title_replace(&$tree_grow_node, &$arr, $current_node) {
	$len = count($tree_grow_node);
	for ( $i=0; $i<$len; $i++ ) {
		if ( $tree_grow_node[$len-1-$i] == $current_node ) {
			break;
		}
		$arr[ count($arr)-1-$i ]['title'] =  str_preg_replace( $arr[ count($arr)-1-$i ]['title'], $current_node );
	}
}
// 替换└─节点下的│为空格
function str_preg_replace($title, $current_node) {
	$space = str_repeat('&nbsp;',6);
	$str = str_repeat( $space.'│', $current_node-1 ).$space;
	$pattern = "/($str)(│)(.*)/";
	return preg_replace($pattern,'$1   $3', $title );
}

function warp_checkbox( $value = array(), $node_ids = array() ) {
	$checked = in_array($value['id'], $node_ids)?'checked':'';
	$value['title'] = "<input type='checkbox' {$checked} level=child_of_{$value['id']} name='node_id[]' value={$value['id']}>{$value['title']}";
	return $value;
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

function  p( $param, $var_dump=false ) {

	if ($var_dump) {
		var_dump($param);
	}

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
