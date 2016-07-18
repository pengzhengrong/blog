<?php

namespace Home\Model;
use Think\Model\RelationModel;

Class BlogRelationModel extends RelationModel {

	protected $tableName = 'blog';

	/*protected $_link = array(
		'category' => array(
			'mapping_type' => self::BELONGS_TO,
			// 'mapping_name' => 'blog',
			'foreign_key' => 'id',
			// 'parent_key' => 'blog_id',
			// 'relation_foreign_key' => 'blog_id',
			'relation_table' => 'think_cate_blog',
			'mapping_fields' => 'title',
			)
		);*/

	protected $_link = array(
		/*'category' => array(
			'mapping_type' =>self::MANY_TO_MANY,
			'foreign_key' => 'blog_id',
			'relation_foreign_key' => 'cat_id',
			'relation_table' => 'think_cate_blog',
			'mapping_fields' => 'id,title,pid',
			'mapping_order' => 'sort',
			),*/
		'category' =>array(
			'mapping_type' => self::HAS_ONE,
			'mapping_name' => 'category',
			'mapping_key' => 'cat_id',
			'foreign_key' => 'id',
			'mapping_fields'=> 'title,id,pid',
			),
		'attr' => array(
			'mapping_type' => self::MANY_TO_MANY,
			'foreign_key' => 'blog_id',
			'relation_foreign_key' => 'attr_id',
			'relation_table' => 'think_blog_attr',
			'mapping_fields' => 'id,title,color,status,attr_count',
			'mapping_order' => 'sort',
			'condition' => 'status=0',
			)
		);

}