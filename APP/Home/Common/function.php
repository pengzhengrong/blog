<?php
function  p( $param ) {

	if( is_array( $param )){
		dump( $param );
		return;
	}
	echo $param."<br />";

}

function increBlogClick($id) {
	$key = "BLOG_IDS_CACHE";
	$value = S($key)==null?array():S($key);
	$value[] = $id;
	S($key, $value, 300);
	
	$cacheKey = "BLOG_ID_{$id}";
	S($cacheKey,S($cacheKey)+1,300);
}