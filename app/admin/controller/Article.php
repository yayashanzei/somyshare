<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/3/3
	 * Time: 14:31
	 */


	namespace app\admin\controller;
	use app\common\controller\Admin;

	class Article extends Admin {

		public function index() {
			return $this->fetch();
		}

		public function channel() {
			$catId = I( 'id' );
			if ( empty( $catId ) ) {
				$this->ajaxTip( '没有此分类！' );
			}
			$category = $catIds = $list = $model = $modelField = array();
			$catInfo  = M( 'category' )->field( 'id,name,pid,tid,title,model' )->where( array( 'tid' => array( 'in' , '0,' . $catId ) ) )->select();
			$_catInfo = array();
			if ( !empty( $catInfo ) ) {
				foreach ( $catInfo as $key => $val ) {
					if ( $val['pid'] == 0 ) {
						if ( $val['id'] == $catId ) {
							$category              = $val;
							$catIds[]              = $val['id'];
							$_catInfo[ $val['id'] ] = $val;
						}
					} else {
						$catIds[]              = $val['id'];
						$_catInfo[ $val['id'] ] = $val;
					}
				}
			}

			$map['category_id'] = array( 'in' , implode( ',' , $catIds ) );
			$map['status']      = array( 'in' , '0,1' );

			$model = M( 'model' )->where( array( 'id' => $category['model'] ) )->find();

			if ( empty( $model ) ) {
				$this->ajaxTip( '此分类不属于任何模型，无法编辑！' );
			}

			$list = M( $model['name'] )->where( $map )->select();

			$this->assign( array(
				               'list'     => $list ,
				               'catInfo'  => $_catInfo ,
				               'category' => $category ,
			               ) );

			$this->getButtons();

			return $this->fetch();

		}

		public function add() {

			if ( IS_POST ) {

				$data = I( 'post.' );

				$tid = $data['tid'];

				$category = $model = $modelField = $addIds = $addFields = array();

				if ( empty( $tid ) ) {
					$this->ajaxTip( '没有此模块儿！' );
				}

				$category = M( 'category' )->field( 'id,name,pid,tid,title,model,cataid' )->where( array( 'id' => $tid ) )->find();

				if ( !empty( $category ) ) {
					$model = M( 'model' )->where( array( 'id' => $category['model'] ) )->find();
				}

				if ( !empty( $model ) ) {
					$modelField = M( 'model_field' )->where( array( 'model_id' => $model['id'] ) )->select();
					//处理新增不显示的表单
					foreach ( $modelField as $k => $v ) {

						if ( array_key_exists( $v['field_type'] , array( '66' => 0 , '67' => 1 ) ) ) {

							if ( empty( $data[ $v['name'] ] ) ) {
								if ( empty( $data[ $v['name'] . '_value' ] ) ) {
									$data[ $v['name'] ] = 0;
									unset( $data[ $v['name'] . '_value' ] );
									continue;
								} else {
									$addIds[]                = array( 'content' => $data[ $v['name'] . '_value' ] );
									$addFields[ $v['name'] ] = null;
									unset( $data[ $v['name'] ] , $data[ $v['name'] . '_value' ] );
									continue;
								}
							}
							unset( $data[ $v['name'] . '_value' ] );
						}
					}
				}

				if ( isset( $data['create_time'] ) ) {
					$data['create_time'] = strtotime( $data['create_time'] );
				}

				unset( $data['tid'] );

				if ( !empty( $addIds ) ) {
					$rs = M( 'content' )->addAll( $addIds );
					if ( $rs === false ) {
						$this->ajaxTip( '添加扩展内容失败！' );
					}
					foreach ( $addFields as $key => $val ) {
						$data[ $key ] = intval( $rs );
						$rs++;
					}
				}

				$articleModel = M( $model['name'] );
				$data         = $articleModel->create( $data );
				$data['uid'] = $this->userInfo['uid'];

				$rs = M( $model['name'] )->add( $data );

				if ( $rs === false ) {
					$this->ajaxTip( '更新基础内容失败！' );
				}

				$this->ajaxSuccess( '添加成功！' );

			} else {

				$tid = I( 'get.tid' );

				$category = $model = $fieldGroup = $fieldSort = $modelField = $conIds = array();

				if ( empty( $tid ) ) {
					$this->ajaxTip( '没有此模块儿！' );
				}

				$catInfo = M( 'category' )->field( 'id,name,pid,tid,title,model,cataid' )->where( array( 'tid' => array( 'in' , '0,' . $tid ) ) )->select();

				$_catInfo = array();
				if ( !empty( $catInfo ) ) {
					foreach ( $catInfo as $key => $val ) {
						if ( $val['pid'] == 0 ) {
							if ( $val['id'] == $tid ) {
								$category               = $val;
								$_catInfo[ $val['id'] ] = $val;
							}
						} else {
							$_catInfo[ $val['id'] ] = $val;
						}
					}
				}

				$_catInfo = D( 'common/Tree' )->toFormatTree( $_catInfo );

				if ( !empty( $category ) ) {
					$model = M( 'model' )->where( array( 'id' => $category['model'] ) )->find();
				}

				if ( !empty( $model ) ) {
					$fieldSort = explode( ',' , $model['field_sort'] );
					$fieldSort = array_combine( $fieldSort , $fieldSort );
					if ( empty( $fieldSort ) ) {
						$this->ajaxTip( '该模型没有字段或该模型字段没有排序值！' );
					}
					$modelField = M( 'model_field' )->where( array( 'model_id' => $model['id'] ) )->select();
					if ( empty( $modelField ) ) {
						$this->ajaxTip( '该模型没有字段！' );
					} else {
						if ( count( $fieldSort ) < count( $modelField ) ) {
							$this->ajaxTip( '该模型字段的排序值不是最新状态！' );
						}
					}
					//处理新增不显示的表单
					foreach ( $modelField as $k => $v ) {
						$fieldGroup[ $v['field_group'] ][ $v['id'] ] = $v;
						$fieldSort[ $v['id'] ]                       = $v;
						if ( array_key_exists( $v['is_show'] , array( '78' => 0 , '79' => 1 ) ) ) {
							unset( $modelField[ $k ] , $fieldSort[ $v['id'] ] );
							continue;
						}
					}
				}
				$fieldEnum = S( 'enum' )[80]['_'];

				$this->assign( array(
					               'catInfo'    => $_catInfo ,
					               'category'   => $category ,
					               'model'      => $model ,
					               'field'      => $fieldSort ,
					               'fieldGroup' => $fieldGroup ,
					               'fieldEnum'  => $fieldEnum ,
					               'info'       => array(
						               'vid'         => '' ,
						               'views'       => '' ,
						               'duration'    => '' ,
						               'create_time' => '' ,
					               ) ,
				               ) );

				return $this->fetch( 'edit' );
			}
		}

		public function edit() {

			if ( IS_POST ) {

				$data = I( 'post.' );

				$id  = $data['id'];
				$tid = $data['tid'];

				$category = $model = $modelField = $updateIds = $addIds = $addFields = array();

				if ( empty( $id ) ) {
					$this->ajaxTip( '没有此文章！' );
				}

				if ( empty( $tid ) ) {
					$this->ajaxTip( '没有此模块儿！' );
				}

				$category = M( 'category' )->field( 'id,name,pid,tid,title,model,cataid' )->where( array( 'id' => $tid ) )->find();

				if ( !empty( $category ) ) {
					$model = M( 'model' )->where( array( 'id' => $category['model'] ) )->find();
				}

				if ( !empty( $model ) ) {
					$modelField = M( 'model_field' )->where( array( 'model_id' => $model['id'] ) )->select();
					//处理新增不显示的表单
					foreach ( $modelField as $k => $v ) {
						if ( array_key_exists( $v['field_type'] , array( '66' => 0 , '67' => 1 ) ) ) {

							if ( empty( $data[ $v['name'] ] ) ) {
								if ( empty( $data[ $v['name'] . '_value' ] ) ) {
									unset( $data[ $v['name'] ] , $data[ $v['name'] . '_value' ] );
									continue;
								} else {
									$addIds[]                = array( 'content' => $data[ $v['name'] . '_value' ] );
									$addFields[ $v['name'] ] = null;
									unset( $data[ $v['name'] ] , $data[ $v['name'] . '_value' ] );
									continue;
								}
							} else {
								$updateIds[ $data[ $v['name'] ] ] = $data[ $v['name'] . '_value' ];
							}
							unset( $data[ $v['name'] . '_value' ] );
						}
					}
				}

				if ( isset( $data['create_time'] ) ) {
					$data['create_time'] = strtotime( $data['create_time'] );
				}

				unset( $data['tid'] );

				if ( !empty( $addIds ) ) {
					$rs = M( 'content' )->addAll( $addIds );
					if ( $rs === false ) {
						$this->ajaxTip( '添加扩展内容失败！' );
					}
					foreach ( $addFields as $key => $val ) {
						$data[ $key ] = intval( $rs );
						$rs++;
					}
				}

				$rs = M( $model['name'] )->save( $data );

				if ( $rs === false ) {
					$this->ajaxTip( '更新基础内容失败！' );
				}

				if ( !empty( $updateIds ) ) {
					$ids      = implode( ',' , array_keys( $updateIds ) );
					$conTable = C( 'database.prefix' ) . 'content';
					$sql      = "UPDATE $conTable SET content = CASE id ";
					foreach ( $updateIds as $id => $ordinal ) {
						$sql .= sprintf( "WHEN %d THEN '%s' " , $id , $ordinal );
					}
					$sql .= "END WHERE id IN ($ids)";

					$rs = M()->execute( $sql );

					if ( $rs === false ) {
						$this->ajaxTip( '更新扩展内容失败！' );
					}
				}

				$this->ajaxSuccess( '更新成功！' );

			} else {

				$id  = I( 'get.id' );
				$tid = I( 'get.tid' );

				$category = $fieldSort = $fieldGroup = $model = $modelField = $conIds = array();

				if ( empty( $id ) ) {
					$this->ajaxTip( '没有此文章！' );
				}

				if ( empty( $tid ) ) {
					$this->ajaxTip( '没有此模块儿！' );
				}

				$catInfo = M( 'category' )->field( 'id,name,pid,tid,title,model,cataid' )->where( array( 'tid' => array( 'in' , '0,' . $tid ) ) )->select();

				$_catInfo = array();
				if ( !empty( $catInfo ) ) {
					foreach ( $catInfo as $key => $val ) {
						if ( $val['pid'] == 0 ) {
							if ( $val['id'] == $tid ) {
								$category               = $val;
								$_catInfo[ $val['id'] ] = $val;
							}
						} else {
							$_catInfo[ $val['id'] ] = $val;
						}
					}
				}

				$_catInfo = D( 'common/Tree' )->toFormatTree( $_catInfo );

				if ( !empty( $category ) ) {
					$model = M( 'model' )->where( array( 'id' => $category['model'] ) )->find();
				}

				$info = M( $model['name'] )->where( array( 'id' => $id ) )->find();

				if ( !empty( $model ) ) {
					$fieldSort = explode( ',' , $model['field_sort'] );
					$fieldSort = array_combine( $fieldSort , $fieldSort );
					if ( empty( $fieldSort ) ) {
						$this->ajaxTip( '该模型没有字段或该模型字段没有排序值！' );
					}
					$modelField = M( 'model_field' )->where( array( 'model_id' => $model['id'] ) )->select();
					if ( empty( $modelField ) ) {
						$this->ajaxTip( '该模型没有字段！' );
					} else {
						if ( count( $fieldSort ) < count( $modelField ) ) {
							$this->ajaxTip( '该模型字段的排序值不是最新状态！' );
						}
					}
					//处理新增不显示的表单
					foreach ( $modelField as $k => $v ) {
						$fieldGroup[ $v['field_group'] ][ $v['id'] ] = $v;
						$fieldSort[ $v['id'] ]                       = $v;
						if ( array_key_exists( $v['is_show'] , array( '77' => 0 , '79' => 1 ) ) ) {
							unset( $modelField[ $k ] , $fieldSort[ $v['id'] ] );
							continue;
						}
						if ( array_key_exists( $v['field_type'] , array( '66' => 0 , '67' => 1 ) ) ) {
							$conIds[ $v['name'] ] = $info[ $v['name'] ];
						}
					}
				}

				if ( !empty( $conIds ) ) {
					$cons = M( 'content' )->where( array( 'id' => array( 'in' , implode( ',' , $conIds ) ) ) )->select( array( 'index' => 'id' ) );
				}
				//print_r($conIds);print_r($cons);exit;
				foreach ( $conIds as $key => $val ) {
					if ( $val == 0 ) {
						$info[ $key ] = array( 'id' => $val , 'content' => '' );
					} else {
						$info[ $key ] = $cons[ $val ];
					}
				}

				$fieldEnum = S( 'enum' )[80]['_'];
				$this->assign( array(
					               'catInfo'    => $_catInfo ,
					               'category'   => $category ,
					               'model'      => $model ,
					               'field'      => $fieldSort ,
					               'fieldGroup' => $fieldGroup ,
					               'fieldEnum'  => $fieldEnum ,
					               'info'       => $info ,
				               ) );

			}

			return $this->fetch();
		}

		public function remove() {

			$modelId = I( 'get.model' );

			if ( empty( $modelId ) ) {
				$this->ajaxTip( '模块儿不存在！' );
			}

			$model = M( 'model' )->where( array( 'id' => $modelId ) )->find();

			if ( $model === false ) {
				$this->ajaxTip( '模块儿不存在！' );
			}

			if ( IS_POST ) {
				$post = I( 'post.' );
				$id   = empty( $post['id'] ) ? '' : $post['id'];
			} else {
				$id = I( 'get.id' );
			}

			if ( empty( $id ) ) {
				$this->ajaxTip( '请选择要操作的数据！' );
			}

			if ( is_array( $id ) ) {
				$map = array( 'id' => array( 'in' , implode( ',' , $id ) ) );
			} else {
				$map = array( 'id' => $id );
			}

			$update['status'] = '-1';
			$result           = M( $model['name'] )->where( $map )->save( $update );

			if ( $result !== false ) {
				$this->ajaxSuccess( '删除成功！' );
			} else {
				$this->ajaxError( '删除失败！' );
			}

		}

		public function course(){

			$map['status'] = 1;
			$list = M('course')->where($map)->select();
			$this->assign('list',$list);
			return $this->fetch();
		}

		public function addC(){

			if(IS_POST){

				$data = I('post.');
				unset($data['id']);
				if ( empty( $data['title'] ) ) {
					$this->ajaxTip( '课程主题不能为空！' );
				}
				$oTitle = M( 'course' )->where( array( 'title' => $data['title'] ) )->find();

				if ( !empty( $oTitle ) ) {
					$this->ajaxTip( '课程主题名已经存在！' );
				}
				$data['create_time'] = strtotime($data['create_time']);
				$result = M('course')->add($data);
				if($result){
					$this->ajaxSuccess("添加成功！");
				}else{
					$this->ajaxError("添加失败！");
				}
			}else{
				//获取视频的分类
				$videoCate = D('Article')->getVideoCate(1);

				$this->assign('videoCate',$videoCate);
				return $this->fetch('editc');
			}
		}

		public function editC(){

			//时间原因先不写了
			if(IS_POST){

			}else{
				echo "时间原因，功能暂时未开发！";
				//return $this->fetch('editc');
			}
		}

		public function delC(){
			echo "时间原因，功能暂时未开发！";
		}

		public function draftbox() {

			return $this->fetch();
		}

		public function examine() {

			return $this->fetch();
		}

		public function recycle() {
			$models    = $temp = $result = $lists = $list = [ ];
			$modelName = M( 'model' )->field( 'id' )->select();

			foreach ( $modelName as $key => $value ) {
				if ( $modelName[ $key ]['id'] == 1 ) {
					unset( $modelName[ $key ] );
				} else {
					$models[] = $modelName[ $key ]['id'];
				}
			}

			foreach ( $models as $key => $value ) {
				$modelTable = M( 'model' )->where( array( 'id' => $value ) )->getField( 'name' );
				$temp       = M( $modelTable )->field( 'id,title,category_id,status' )->where( array( 'status' => -1 ) )->select();
				foreach ( $temp as $k => $v ) {
					$temp[ $k ]['model'] = $value;
				}

				$lists[] = $temp;
			}

			foreach ( $lists as $key => $value ) {
				foreach ( $value as $k => $v ) {
					$list[] = $v;
				}
			}

			$this->assign( 'list' , $list );
			$this->getButtons();
			return $this->fetch();
		}

		public function restore() {
			$result = $id = [ ];

			if ( IS_POST ) {
				$post          = I( 'post.' );
				$ids           = empty( $post['id'] ) ? '' : $post['id'];
				$map['status'] = 1;

				if ( empty( $ids ) ) {
					$this->ajaxTip( '请选择要操作的数据！' );
				}

				foreach ( $ids as $k => $v ) {
					$id         = explode( "_" , $v );
					$modelTable = M( 'model' )->where( array( 'id' => $id[1] ) )->getField( 'name' );
					$result     = M( $modelTable )->where( array( 'id' => $id[0] ) )->save( $map );
				}
			} else {

				$model         = I( 'get.model' );
				$ids['id']     = array( 'in' , I( 'get.id' ) );
				$map['status'] = 1;
				$modelTable    = M( 'model' )->where( array( 'id' => $model ) )->getField( 'name' );
				$result        = M( $modelTable )->where( $ids )->save( $map );
			}

			if ( $result !== false ) {
				$this->ajaxSuccess( "还原成功！" );
			} else {
				$this->ajaxError( "还原失败！" );
			}
		}

		//回收站清空，彻底删除
		public function del() {

			$result = [ ];
			$post   = I( 'post.' );
			$ids    = empty( $post['id'] ) ? '' : $post['id'];

			if ( empty( $ids ) ) {
				$this->ajaxTip( '请选择要操作的数据！' );
			}

			foreach ( $ids as $k => $v ) {
				$id         = explode( "_" , $v );
				$modelTable = M( 'model' )->where( array( 'id' => $id[1] ) )->getField( 'name' );
				$result     = M( $modelTable )->where( array( 'id' => $id[0] ) )->delete();
			}
			if ( $result !== false ) {
				$this->ajaxSuccess( '清空成功！' );
			} elseif ( $result == 0 ) {
				$this->ajaxTip( '没有清空任何数据！' );
			} else {
				$this->ajaxError( 'SQL出错！' );
			}
		}

		public function enable() {

			$modelId = I( 'get.model' );

			if ( empty( $modelId ) ) {
				$this->ajaxTip( '模块儿不存在！' );
			}

			$model = M( 'model' )->where( array( 'id' => $modelId ) )->find();

			if ( $model === false ) {
				$this->ajaxTip( '模块儿不存在！' );
			}

			if ( IS_POST ) {
				$post = I( 'post.' );
				$id   = empty( $post['id'] ) ? '' : $post['id'];
			} else {
				$id = I( 'get.id' );
			}

			if ( empty( $id ) ) {
				$this->ajaxTip( '请选择要操作的数据！' );
			}

			if ( is_array( $id ) ) {
				$map = array( 'id' => array( 'in' , implode( ',' , $id ) ) );
			} else {
				$map = array( 'id' => $id );
			}

			$update['status'] = '1';
			$result           = M( $model['name'] )->where( $map )->save( $update );

			if ( $result !== false ) {
				$this->ajaxSuccess( '启用成功！' );
			} else {
				$this->ajaxError( '启用失败！' );
			}
		}

		public function disable() {
			$modelId = I( 'get.model' );

			if ( empty( $modelId ) ) {
				$this->ajaxTip( '模块儿不存在！' );
			}

			$model = M( 'model' )->where( array( 'id' => $modelId ) )->find();

			if ( $model === false ) {
				$this->ajaxTip( '模块儿不存在！' );
			}

			if ( IS_POST ) {
				$post = I( 'post.' );
				$id   = empty( $post['id'] ) ? '' : $post['id'];
			} else {
				$id = I( 'get.id' );
			}

			if ( empty( $id ) ) {
				$this->ajaxTip( '请选择要操作的数据！' );
			}

			if ( is_array( $id ) ) {
				$map = array( 'id' => array( 'in' , implode( ',' , $id ) ) );
			} else {
				$map = array( 'id' => $id );
			}

			$update['status'] = '0';
			$result           = M( $model['name'] )->where( $map )->save( $update );

			if ( $result !== false ) {
				$this->ajaxSuccess( '禁用成功！' );
			} else {
				$this->ajaxError( '禁用失败！' );
			}
		}

	}