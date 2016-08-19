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
		$where = array(
			'user_id' => session('user_id')
			);
		$rest = M('event')->where($where)->select();
		$data = '';
		foreach ($rest as $v) {
			$data .= "<div id='{$v['id']}' class='external-event'>".$v['title']."</div>";
		}
		return $data;
	}

}