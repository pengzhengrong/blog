<?php

namespace Home\Model;

Class BlogModel extends BaseModelDB {
	
	public function __construct($tableName='blog', $tablePrefix='think_') {
		parent::__construct($tableName, $tablePrefix);
	}

}