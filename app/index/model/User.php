<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/3/11
	 * Time: 10:54
	 */

	namespace app\index\model;


	use think\model\Relation;

	class User extends Relation {

		protected $link = array(
			'user_info' => array(
				'mapping_name'   => 'user_info' ,
				'mapping_type'   => HAS_ONE ,
				'class_name'     => 'user_info' ,
				'foreign_key'    => 'uid' ,
				'mapping_key'    => 'uid' ,
				'mapping_fields' => 'groupid,sex,linkman,company,address,scale,trade,avatar' ,
				'as_fields'      => 'groupid,sex,linkman,company,address,scale,trade,avatar',
			) ,
		);

		protected function _after_delete( $data , $options = [ ] ) {
			$_rs = false;
			if ( !empty( $this->link ) ) {
				foreach ( $this->link as $key => $val ) {
					if ( !empty( $val['class_name'] ) ) {
						if ( !empty( $data ) ) {
							foreach ( $data as $dkey => $dval ) {
								$_table        = D( $val['class_name'] );
								$_map[ $dkey ] = $options['bind'][$dkey][0];
								$_rs[]         = $_table->where( $_map )->delete();
							}
						}
					}
				}
			}

			return $_rs;
		}

	}