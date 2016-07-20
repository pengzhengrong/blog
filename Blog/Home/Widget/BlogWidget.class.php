<?php

namespace Home\Widget;
use Think\Controller;

Class BlogWidget extends Controller {

	Public function parentDir($cat_id) {
		$title = M('category')->cache(true,60)->where("id=$cat_id")->getField('title');
		return $title;
	}

}