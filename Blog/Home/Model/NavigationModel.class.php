<?php 

namespace Home\Model;

class NavigationModel extends BaseModelDB {

	public function __construct($tableName='navigation') {
		parent::__construct($tableName);
	}

	public function getNav($fields='*', $where=[], $order='') {
		$rest = $this->getData($fields, $where, $order);
		return $rest;
	}

}