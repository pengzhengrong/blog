<?php

namespace Home\Model;

class LoginModel extends BaseModelDB {

	public function __construct($tableName='user') {
		parent::__construct($tableName);
	}

	public function checkUser($field="*", $where) {
		$data = $this->getRow($field, $where);
		return $data;
	}

	public function saveLoginInfo($data) {
		$rest = $this->update($data);
		return $rest;
	}


}