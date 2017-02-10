<?php 

require '../../ThinkPHP/ThinkPHP.php';

class SyncClick {
	public function init() {
		echo 1;
	}
}

$sync = new SyncClick();
$sync->init();