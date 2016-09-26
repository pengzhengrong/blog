<?php

namespace Home\Model;

Class BlogModel extends BaseModelDB {
	
	public function __construct($tableName, $tablePrefix='think_') {
		parent::__construct($tableName);
	}

	public function getBlogInfo(array $field=array(), array $where=array()) {
		$data = $this->field($field)->where($where)->select();
		return $data;
	}

}