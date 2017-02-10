<?php

namespace Home\Model;

Class BlogModel extends BaseModelDB {
	
	public function __construct($tableName='blog') {
		parent::__construct($tableName);
	}

	public function getList($field='*', $where='', $order='') {
		return $this->getData($field, $where, $order);
	}

}