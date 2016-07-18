<?php

namespace Home\Widget;
use Think\Controller;

Class BlogWidget extends Controller {

	Public function parentDir($cat_id) {
		$sql = "select title from think_category where id=(select pid from think_category where id=$cat_id)";
		$rest = M('category')->cache(true,60)->query($sql);
		return $rest[0]['title'];
	}

}