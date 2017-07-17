<?php
	// +----------------------------------------------------------------------
	// | OneThink [ WE CAN DO IT JUST THINK IT ]
	// +----------------------------------------------------------------------
	// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
	// +----------------------------------------------------------------------
	// | Author: huajie <banhuajie@163.com>
	// +----------------------------------------------------------------------

	namespace app\admin\model;

	/**
	 * 文档基础模型
	 */
	class Model extends \think\Model {

		/* 自动验证规则 */
		/*protected $_validate = array(
			array( 'name' , 'require' , '标识不能为空' , self::MUST_VALIDATE , 'regex' , self::MODEL_INSERT ) ,
			array( 'name' , '/^[a-zA-Z]\w{0,39}$/' , '文档标识不合法' , self::VALUE_VALIDATE , 'regex' , self::MODEL_BOTH ) ,
			array( 'name' , '' , '标识已经存在' , self::VALUE_VALIDATE , 'unique' , self::MODEL_BOTH ) ,
			array( 'title' , 'require' , '标题不能为空' , self::MUST_VALIDATE , 'regex' , self::MODEL_BOTH ) ,
			array( 'title' , '1,30' , '标题长度不能超过30个字符' , self::MUST_VALIDATE , 'length' , self::MODEL_BOTH ) ,
			array( 'list_grid' , 'checkListGrid' , '列表定义不能为空' , self::MUST_VALIDATE , 'callback' , self::MODEL_UPDATE ) ,
		);*/

		/* 自动完成规则 */
		/*protected $_auto = array(
			array( 'name' , 'strtolower' , self::MODEL_INSERT , 'function' ) ,
			array( 'create_time' , NOW_TIME , self::MODEL_INSERT ) ,
			array( 'update_time' , NOW_TIME , self::MODEL_BOTH ) ,
			array( 'status' , '1' , self::MODEL_INSERT , 'string' ) ,
			array( 'field_sort' , 'getFields' , self::MODEL_BOTH , 'callback' ) ,
			array( 'attribute_list' , 'getAttribute' , self::MODEL_BOTH , 'callback' ) ,
		);*/




		/**
		 * 根据数据表生成模型及其属性数据
		 * @author huajie <banhuajie@163.com>
		 */
		public function generate( $table , $name = '' , $title = '' ) {
			//新增模型数据
			if ( empty( $name ) ) {
				$name = $title = substr( $table , strlen( C( 'DB_PREFIX' ) ) );
			}
			$data = array( 'name' => $name , 'title' => $title );
			$data = $this->create( $data );
			if ( $data ) {
				$res = $this->add( $data );
				if ( !$res ) {
					return false;
				}
			} else {
				$this->error = $this->getError();
				return false;
			}

			//新增属性
			$fields = M()->query( 'SHOW FULL COLUMNS FROM ' . $table );
			foreach ( $fields as $key => $value ) {
				$value = array_change_key_case( $value );
				//不新增id字段
				if ( strcmp( $value['field'] , 'id' ) == 0 ) {
					continue;
				}

				//生成属性数据
				$data          = array();
				$data['name']  = $value['field'];
				$data['title'] = $value['comment'];
				$data['type']  = 'string';    //TODO:根据字段定义生成合适的数据类型
				//获取字段定义
				$is_null          = strcmp( $value['null'] , 'NO' ) == 0 ? ' NOT NULL ' : ' NULL ';
				$data['field']    = $value['type'] . $is_null;
				$data['value']    = $value['default'] == null ? '' : $value['default'];
				$data['model_id'] = $res;
				$_POST            = $data;        //便于自动验证
				D( 'model_field' )->update( $data , false );
			}
			return $res;
		}

	}
