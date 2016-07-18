<?php

namespace Home\Model;
use Think\Model\RelationModel;
Class UserRelationModel  extends RelationModel{

	protected $tableName = 'user';

	protected $_link = array(
		'role' => array(
			'mapping_type' => self::MANY_TO_MANY,
			// 'class_name' => 'role',
			// 'mapping_name' => 'role',
			'foreign_key' => 'user_id',
			'relation_foreign_key' => 'role_id',
			'relation_table' => 'think_role_user',
			// 'mapping_fields' => 'name,remark',
			// 'condition' => 'user_id'
			)
		);


}

?>

