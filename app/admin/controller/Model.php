<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/3/3
	 * Time: 14:31
	 */

	namespace app\admin\controller;

	use app\common\controller\Admin;

	class Model extends Admin {
		private static $static_dragsort = 1;

		/**
		 * info
		 * @return mixed
		 */
		public function info() {

			$models = D( 'model' )->field( 'id,title,name,status' )->select();

			$_status = array( '-1' => '已删除' , '0' => '禁用' , '1' => '正常' );
			foreach ( $models as $key => $val ) {
				$models[ $key ]['status'] = $_status[ $val['status'] ];
			}

			$this->assign( array(
				               'models' => $models ,
			               ) );

			return $this->fetch();
		}


		public function infoEdit() {

			if ( IS_POST ) {

				$_model = D( 'model' );
				$_data  = $_model->create();

				if ( empty( $_data['title'] ) ) {
					$this->ajaxError( '请输入模型标题！' );
				}

				if ( empty( $_data['name'] ) ) {
					$this->ajaxError( '请输入模型标识！' );
				}

				$_modelInfo = $_model->where( array( 'id' => $_data['id'] ) )->find();
				$_oldTable  = C( 'database.prefix' ) . $_modelInfo['name'];
				$_tableName = C( 'database.prefix' ) . $_data['name'];

				if ( tableExists( $_data['name'] ) == 1 || $_modelInfo['name'] != $_data['name'] ) {
					M()->db()->startTrans();
					if ( $_modelInfo['name'] != $_data['name'] ) {
						$sql = <<<sql
                ALTER TABLE `{$_oldTable}` RENAME TO `{$_tableName}`;
sql;
						if ( M()->execute( $sql ) === false ) {
							M()->db()->rollback();
							$this->ajaxTip( '重命名表失败！' );
						}
					}

					if ( $_modelInfo['engine_type'] != $_data['engine_type'] ) {
						$engineType = S( 'enum' )[80]['_'][108]['_'][ $_data['engine_type'] ]['value'];
						$sql        = <<<sql
                ALTER TABLE `{$_tableName}` ENGINE = {$engineType};
sql;
						if ( M()->execute( $sql ) === false ) {
							M()->db()->rollback();
							$this->ajaxTip( '更改表引擎失败！' );
						}
					}

					if ( $_modelInfo['need_pk'] != $_data['need_pk'] ) {

						if ( $_data['need_pk'] == 113 ) {
							$sql = <<<sql
                ALTER TABLE `{$_tableName}` CHANGE id id int(10) NOT NULL;
sql;
							if ( M()->execute( $sql ) === false ) {
								M()->db()->rollback();
								$this->ajaxTip( '更改主键自增长失败！' );
							}

							$sql = <<<sql
                ALTER TABLE `{$_tableName}` DROP PRIMARY KEY;
sql;
							if ( M()->execute( $sql ) === false ) {
								M()->db()->rollback();
								$this->ajaxTip( '更改主键失败！' );
							}

							$sql = <<<sql
                ALTER TABLE `{$_tableName}` DROP COLUMN id;
sql;
						}

						if ( $_data['need_pk'] == 112 ) {

							$fields = M( $_data['name'] )->getFields();
							if ( in_array( 'id' , $fields ) ) {
								$sql = <<<sql
                ALTER TABLE `{$_tableName}` ADD PRIMARY KEY (id);
sql;
							} else {
								$sql = <<<sql
                ALTER TABLE `{$_tableName}`
ADD COLUMN `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' FIRST,ADD PRIMARY KEY (id),AUTO_INCREMENT = 1;
sql;
							}

						}

						if ( M()->execute( $sql ) === false ) {
							M()->db()->rollback();
							$this->ajaxTip( '更改表主键失败！' );
						}
					}

					if ( $_model->save( $_data ) === false ) {
						M()->db()->rollback();
						$this->ajaxError( '更新失败！' );
					} else {
						M()->db()->commit();
						$this->ajaxSuccess( '更新成功！' );
					}

				} else {
					$_model->save( $_data ) === false ? $this->ajaxError( '更新失败！' ) : $this->ajaxSuccess( '更新成功！' );
				}

			} else {

				$id = I( 'get.id' );

				if ( empty( $id ) ) {
					$this->ajaxError( '用户不存在！' );
				}

				$_info = D( 'model' )->field( 'id,title,name,engine_type,need_pk,status' )->where( array( 'id' => $id ) )->select();

				$fieldEnum = S( 'enum' )[80]['_'];
				$this->assign( 'fieldEnum' , $fieldEnum );
				$this->assign( array( 'info' => $_info[0] ) );

				return $this->fetch( 'info_edit' );
			}
		}


		public function infoAdd() {

			if ( IS_POST ) {

				$_model = D( 'model' );
				$_data  = $_model->create();

				if ( empty( $_data['title'] ) ) {
					$this->ajaxTip( '请输入模型标题！' );
				}

				if ( empty( $_data['name'] ) ) {
					$this->ajaxTip( '请输入模型标识！' );
				}

				$_name = $_model->where( array( 'name' => $_data['name'] ) )->field( 'id' )->select();

				if ( !empty( $_name ) ) {
					$this->ajaxTip( '模型已经存在！' );
				}

				$_data['create_time'] = substr( microtime() , -10 );

				$_model->add( $_data ) === false ? $this->ajaxTip( '添加失败！' ) : $this->ajaxSuccess( '添加成功！' );

			} else {

				$fieldEnum = S( 'enum' )[80]['_'];

				$this->assign( 'fieldEnum' , $fieldEnum );
				$this->assign( 'info' , array() );
				return $this->fetch( 'info_edit' );
			}
		}

		public function infoDel() {

			if ( IS_POST ) {

				$id = I( 'get.id' );
				//获取表名
				$model = $this->field( 'name' )->find( $id );

				$table_name = C( 'database.prefix' ) . strtolower( $model['name'] );

				//删除属性数据
				M( 'model_field' )->where( array( 'model_id' => $id ) )->delete();
				//删除模型数据
				$this->delete( $id );
				//删除该表
				$sql = <<<sql
                DROP TABLE {$table_name};
sql;
				$res = M()->execute( $sql );
				return $res !== false;

			} else {

				$id    = I( 'get.id' );
				$model = D( 'model' );
				M()->db()->startTrans();
				//获取表名
				$_model = $model->field( 'name' )->find( $id );

				$_tableName = C( 'database.prefix' ) . strtolower( $_model['name'] );

				//删除属性数据
				if ( M( 'model_field' )->where( array( 'model_id' => $id ) )->delete() === false ) {
					M()->db()->rollback();
					$this->ajaxTip( '删除改模型字段表中对应字段失败！' );
				}

				//删除模型数据
				if ( $model->delete( $id ) === false ) {
					M()->db()->rollback();
					$this->ajaxTip( '删除改模型表中对应模型失败！' );
				}

				//删除该表
				if ( tableExists( $_model['name'] ) ) {
					$sql = <<<sql
                DROP TABLE {$_tableName};
sql;
					if ( M()->execute( $sql ) === false ) {
						M()->db()->rollback();
						$this->ajaxTip( '删除对应表失败！' );
					}
				}

				$this->ajaxSuccess( '删除模型成功！' );

			}
		}

		public function infoEnable() {

			if ( IS_POST ) {

				$id = I( 'post.' );

				if ( empty( $id ) ) {
					$this->ajaxTip( '请选择模型！' );
				}

				$id = implode( ',' , $id['id'] );

				$_data['status'] = 1;

				D( 'model' )->where( array( 'id' => array( 'in' , $id ) ) )->save( $_data ) === false ? $this->ajaxTip( '启用失败！' ) : $this->ajaxSuccess( '启用成功！' );


			} else {
				$this->ajaxTip( '非法操作！' );
			}
		}

		public function infoDisable() {

			if ( IS_POST ) {

				$id = I( 'post.' );

				if ( empty( $id ) ) {
					$this->ajaxTip( '请选择用户！' );
				}

				$id = implode( ',' , $id['id'] );

				$_data['status'] = 0;

				D( 'model' )->where( array( 'id' => array( 'in' , $id ) ) )->save( $_data ) === false ? $this->ajaxTip( '禁用失败！' ) : $this->ajaxSuccess( '禁用成功！' );


			} else {
				$this->ajaxTip( '非法操作！' );
			}
		}


		public function fieldEdit() {

			if ( IS_POST ) {

				$_modelField = D( 'model_field' );
				$_data       = $_modelField->create();

				if ( empty( $_data['model_id'] ) ) {
					$this->ajaxTip( '没有要操作的模型！' );
				}

				if ( empty( $_data['name'] ) ) {
					$this->ajaxTip( '请输入字段名！' );
				}

				if ( !$_modelField->checkName() ) {
					$this->ajaxTip( '字段名已经存在！' );
				}

				if ( empty( $_data['title'] ) ) {
					$this->ajaxTip( '请输入字段标题！' );
				}

				if ( empty( $_data['field_define'] ) ) {
					$this->ajaxTip( '请输入字段定义！' );
				}

				$_data['update_time'] = substr( microtime() , -10 );

				$create = true;
				if ( $_data['model_id'] == 1 ) {
					$create = false;
				}

				$_modelField->update( $_data , $create ) === false ? $this->ajaxTip( '更新失败！' ) : $this->ajaxSuccess( '更新成功！' );

			} else {

				$_get = I( 'get.' );

				$id      = $_get['id'];
				$modelId = $_get['model'];

				if ( empty( $id ) ) {
					$this->ajaxTip( '字段有误！' );
				}

				$_info = D( 'model_field' )->where( array( 'id' => $id ) )->find();

				if ( $_info['model_id'] != $modelId ) {
					$this->ajaxTip( '此模型下无此字段！' );
				}

				$fieldEnum = S( 'enum' )[80]['_'];
				$this->assign( array(
					               'info'      => $_info ,
					               'fieldEnum' => $fieldEnum ,
					               'model_id'  => $modelId ,
				               ) );

				return $this->fetch( 'field_edit' );
			}
		}

		public function modelDesign() {

			if ( IS_POST ) {

				$_post = I( 'post.' );

				$id   = $_post['id'];
				$sort = $_post['listSortOrder'];
				if ( empty( $id ) ) {
					$this->ajaxTip( '模型不存在！' );
				}

				if ( empty( $sort ) ) {
					$this->ajaxTip( '模型信息错误！' );
				}

				$sort = explode( ',' , $sort );

				$fieldSort = $_sort = array();

				$table = C( 'database.prefix' ) . 'model_field';

				$sql = "UPDATE $table SET field_group = CASE id ";

				foreach ( $sort as $key => $val ) {
					if ( !empty( $val ) ) {
						$_val        = explode( '-' , $val );
						$fieldSort[] = $_val[0];
						$sql .= sprintf( "WHEN %d THEN %d " , $_val[0] , $_val[1] );
					}
				}

				$fieldSort = implode( ',' , $fieldSort );

				$sql .= "END WHERE id IN ($fieldSort)";

				if ( D( 'model' )->where( array( 'id' => $id ) )->save( array( 'field_sort' => $fieldSort ) ) === false ) {
					$this->ajaxTip( '更新模型失败!' );
				}

				if ( M()->execute( $sql ) === false ) {
					$this->ajaxTip( '更新表分组失败！' );
				} else {
					$this->ajaxSuccess( '更新成功！' );
				}

			} else {

				$_id = I( 'get.id' );

				if ( empty( $_id ) ) {
					$this->ajaxTip( '模型不存在！' );
				}
				$this->assign( 'model_id' , $_id );

				$id = implode( ',' , array( '1' , $_id ) );

				$_model = D( 'model' )->field( 'id,field_sort' )->where( array( 'id' => $_id ) )->find();
				$_info  = D( 'model_field' )->field( 'id,model_id,name,title,field_group' )->where( array( 'model_id' => array( 'in' , $id ) ) )->select( array( 'index' => 'id' ) );

				$base = $info = $fields = array();
				foreach ( $_info as $key => $val ) {
					if ( $val['model_id'] == 1 ) {
						$base[] = $val;
					} else {
						$info[ $key ] = $val;
					}
				}


				if ( !empty( $_model ) ) {
					$_model = explode( ',' , $_model['field_sort'] );
					foreach ( $_model as $key => $val ) {
						if ( isset( $info[ $val ] ) ) {
							$fields[ $info[ $val ]['field_group'] ][ $val ] = $info[ $val ];
							unset( $info[ $val ] );
						}
					}
					foreach ( $info as $key => $val ) {
						$fields[ $val['field_group'] ][] = $val;
					}
				}


				$fieldEnum = S( 'enum' )[80]['_'];

				$common          = _COMMON;
				$static_dragsort = null;
				if ( self::$static_dragsort == 1 ) {
					$static_dragsort       = <<<static
	<script type="text/javascript" src="{$common}/js/jquery.dragsort-0.5.2.min.js"></script>
static;
					self::$static_dragsort = 2;
				}

				$this->assign( array(
					               'info'            => $fields ,
					               'base'            => $base ,
					               'fieldEnum'       => $fieldEnum ,
					               'static_dragsort' => $static_dragsort ,
				               ) );

				return $this->fetch( 'model_design' );
			}
		}

		public function fieldManage() {

			if ( IS_POST ) {

				$_post = I( 'post.' );

				$id   = $_post['id'];
				$sort = $_post['listSortOrder'];
				if ( empty( $id ) ) {
					$this->ajaxTip( '模型不存在！' );
				}

				if ( empty( $sort ) ) {
					$this->ajaxTip( '模型信息错误！' );
				}

				$sort = explode( ',' , $sort );

				$fieldSort = $_sort = array();

				$table = C( 'database.prefix' ) . 'model_field';

				$sql = "UPDATE $table SET field_group = CASE id ";

				foreach ( $sort as $key => $val ) {
					$_val        = explode( '-' , $val );
					$fieldSort[] = $_val[0];
					$sql .= sprintf( "WHEN %d THEN %d " , $_val[0] , $_val[1] );
				}

				$fieldSort = implode( ',' , $fieldSort );

				$sql .= "END WHERE id IN ($fieldSort)";

				if ( D( 'model' )->where( array( 'id' => $id ) )->save( array( 'field_sort' => $fieldSort ) ) === false ) {
					$this->ajaxTip( '更新模型失败!' );
				}

				if ( M()->execute( $sql ) === false ) {
					$this->ajaxTip( '更新表分组失败！' );
				} else {
					$this->ajaxSuccess( '更新成功！' );
				}

			} else {

				$id = I( 'get.id' );

				if ( empty( $id ) ) {
					$this->ajaxTip( '模型不存在！' );
				}

				$fields = M( 'model_field' )->where( array( 'model_id' => $id ) )->field( 'id,title,name,remark' )->select();

				$this->assign( array(
					               'fields'   => $fields ,
					               'model_id' => $id ,
				               ) );


				return $this->fetch( 'field_manage' );
			}
		}


		public function fieldAdd() {

			if ( IS_POST ) {

				$_modelField = D( 'model_field' );
				$_data       = $_modelField->create();


				if ( empty( $_data['model_id'] ) ) {
					$this->ajaxTip( '没有要操作的模型！' );
				}

				if ( empty( $_data['name'] ) ) {
					$this->ajaxTip( '请输入字段名！' );
				}

				if ( !$_modelField->checkName() ) {
					$this->ajaxTip( '字段名已经存在！' );
				}

				if ( empty( $_data['title'] ) ) {
					$this->ajaxTip( '请输入字段标题！' );
				}

				if ( empty( $_data['field_define'] ) ) {
					$this->ajaxTip( '请输入字段定义！' );
				}

				$_data['create_time'] = $_data['update_time'] = substr( microtime() , -10 );

				$create = true;
				if ( $_data['model_id'] == 1 ) {
					$create = false;
				}

				$_modelField->update( $_data , $create ) === false ? $this->ajaxTip( '添加失败！' ) : $this->ajaxSuccess( '添加成功！' );

			} else {

				$model_id = I( 'get.model' );
				$id       = I( 'get.id' );

				if ( !empty( $model_id ) && !empty( $id ) ) {

					$_modelField = D( 'model_field' );
					$_fieldInfo  = $_modelField->where( array( 'id' => $id ) )->find();

					if ( !$_fieldInfo ) {
						return false;
					}

					$_exist = $_modelField->where( array( 'name' => $_fieldInfo['name'] , 'model_id' => $model_id ) )->find();
					if ( !empty( $_exist ) ) {
						$this->ajaxTip( '字段已经存在！' );
					}
					unset( $_fieldInfo['id'] );
					$_fieldInfo['model_id'] = $model_id;
					$_data                  = $_modelField->create( $_fieldInfo );

					if ( $_modelField->update( $_data , true ) === false ) {
						$this->ajaxTip( '添加失败！' , array( 'icon' => 5 ) );
					} else {

						$_info = $_modelField->field( 'id,model_id,name,title,field_group' )->where( array( 'model_id' => $_fieldInfo['model_id'] ) )->select();
						$info  = array();
						foreach ( $_info as $key => $val ) {
							$info[ $val['field_group'] ][] = $val;
						}
						$fieldEnum = S( 'enum' )[80]['_'];
						$this->assign( array(
							               'info'      => $info ,
							               'fieldEnum' => $fieldEnum ,
						               ) );

						$this->ajaxTip( '添加成功！' , array( 'icon' => 6 , 'data' => $this->fetch( 'field_sort' ) ) );

					}

				}

				$fieldEnum = S( 'enum' )[80]['_'];
				$this->assign( array(
					               'fieldEnum' => $fieldEnum ,
					               'info'      => array() ,
					               'model_id'  => I( 'get.id' ) ,
				               ) );

				return $this->fetch( 'field_edit' );
			}

		}


		public function fieldDel() {

			if ( IS_POST ) {

				$_modelField = D( 'model_field' );
				$_data       = $_modelField->create();

				if ( empty( $_data['model_id'] ) ) {
					$this->ajaxTip( '没有要操作的模型！' );
				}

				if ( empty( $_data['name'] ) ) {
					$this->ajaxTip( '请输入字段名！' );
				}

				if ( !$_modelField->checkName() ) {
					$this->ajaxTip( '字段名已经存在！' );
				}

				if ( empty( $_data['title'] ) ) {
					$this->ajaxTip( '请输入字段标题！' );
				}

				if ( empty( $_data['field_define'] ) ) {
					$this->ajaxTip( '请输入字段定义！' );
				}

				$_data['create_time'] = $_data['update_time'] = substr( microtime() , -10 );

				$create = true;
				if ( $_data['model_id'] == 1 ) {
					$create = false;
				}

				$_modelField->update( $_data , $create ) === false ? $this->ajaxTip( '添加失败！' ) : $this->ajaxSuccess( '添加成功！' );

			} else {

				$id      = I( 'get.id' );
				$name    = I( 'get.name' );
				$modelId = I( 'get.model' );

				if ( ( empty( $id ) && empty( $name ) ) || empty( $modelId ) ) {
					$this->ajaxTip( '字段或者模型不存在！' );
				}

				$_modelField = D( 'model_field' );

				$where['model_id'] = $modelId;

				$_fieldInfo = $_modelField->field( 'id,model_id,name' )->where( $where )->select();

				if ( !$_fieldInfo ) {
					return false;
				}

				$rs = null;
				foreach ( $_fieldInfo as $key => $val ) {
					if ( $val['id'] == $id || $val['name'] == $name ) {
						$rs = $val;
					}
				}

				if ( empty( $rs ) ) {
					return false;
				}

				M()->db()->startTrans();

				if ( $_modelField->deleteField( $rs ) === false ) {
					$this->ajaxTip( '删除表中字段失败！' );
				}

				if ( $_modelField->delete( $rs['id'] ) === false ) {
					M()->rollback();
					$this->ajaxTip( '删除模型中字段失败！' );
				} else {

					if ( !empty( $name ) ) {
						$_info = $_modelField->field( 'id,model_id,name,title,field_group' )->where( array( 'model_id' => $modelId ) )->select();
						$info  = array();
						foreach ( $_info as $key => $val ) {
							$info[ $val['field_group'] ][] = $val;
						}
						$fieldEnum = S( 'enum' )[80]['_'];
						$this->assign( array(
							               'info'      => $info ,
							               'fieldEnum' => $fieldEnum ,
						               ) );

						$this->ajaxTip( '删除成功！' , array( 'icon' => 6 , 'data' => $this->fetch( 'field_sort' ) ) );

					} else {
						M()->db()->commit();
						$this->ajaxSuccess( '删除成功！' );
					}
				}
			}

		}


		public function getSider() {
			$this->assign( 'sider' , array(
				'0' => array(
					'0' => array( 'title' => '用户管理' , 'url' => '' , 'icon' => 'icon-user' ) ,
					'1' => array( 'title' => '用户信息' , 'url' => 'User/info' ) ,
					'2' => array( 'title' => '用户组管理' , 'url' => 'User/group' ) ,
					'3' => array( 'title' => '权限组管理' , 'url' => 'User/auth' ) ,
				) ,
				'1' => array(
					'0' => array( 'title' => '行为管理' , 'url' => '' , 'icon' => 'icon-user-md' ) ,
					'1' => array( 'title' => '用户行为' , 'url' => 'User/action' ) ,
					'2' => array( 'title' => '行为日志' , 'url' => 'User/actionLog' ) ,
				) ,
			) );


			return $this->fetch( 'public/getsider' );
		}

	}