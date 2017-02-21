<?php 

namespace Home\Model;

class CatModel extends BaseModelDB {

	public function __construct($tableName='category') {
		parent::__construct($tableName);
	}

	public function getList() {
		$field = array('id','pid','title','sort','isdisplay');
		$where = ['status'=>0];
		$order = '`sort` asc';
		$data = $this->getData($field, $where, $order);
		return $data;
	}

	public function getListByIsDisplay($isDisplay=0) {
		$field = array('id','pid','title','sort','isdisplay');
		$where = ['status'=>0, 'isdisplay'=>$isDisplay];
		$order = '`sort` asc';
		$data = $this->getData($field, $where, $order);
		return $data;
	}

}