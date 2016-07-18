<?php

namespace Home\Library;

Class Image {

	Public function imageHandler() {
		$im = imagecreatefromjpeg('/home/pzr/downloads/monkey.jpg');
		
		imagejpeg($im);

	}

	Public function test() {

	}
}