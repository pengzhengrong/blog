<?php

namespace Home\Widget;
use Think\Controller;

Class BlogWidget extends Controller {

	Public function parentDir($cat_id) {
		$title = M('category')->cache(true,60)->where("id=$cat_id")->getField('title');
		return $title;
	}

	Public function event() {
		// <div class='external-event'>My Event 2</div>
		$rest = M('event')->select();
		$data = '';
		foreach ($rest as $v) {
			$data .= "<div class='external-event'>".$v['title']."</div>";
		}
		return $data;
	}

}