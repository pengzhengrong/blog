<?php

namespace Admin\Model;
use Think\Model\RelationModel;

Class CateRelationModel extends RelationModel {

	protected $tableName = 'category';

	protected $_link = array(
		'blog' => array(
			'mapping_type' => self::MANY_TO_MANY,
			'foreign_key' => 'cat_id',
			'relation_foreign_key' => 'blog_id',
			'relation_table' => 'think_cate_blog',
			'mapping_fields' => 'id',
			'mapping_order' => 'sort',
			)
		);

}