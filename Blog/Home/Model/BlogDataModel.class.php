<?php

namespace Home\Model;

Class BlogDataModel extends BaseModelDB {
	
	public function __construct($tableName='blog_data', $tablePrefix='think_') {
		parent::__construct($tableName, $tablePrefix);
	}

}