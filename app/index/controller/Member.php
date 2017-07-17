<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/5/23
	 * Time: 14:45
	 */


	namespace app\index\controller;

	use app\common\controller\Index as commonIndex;
	use org\Upload;
	use org\Image;

	class Member extends commonIndex {

		public function center() {

			$this->assign( 'info' , $this->userInfo );
			return $this->fetch();
		}

		public function avatar() {
			$this->assign( 'userInfo' , $this->userInfo );
			return $this->fetch( 'avatar' );
		}

		public function password() {
			if ( IS_POST ) {
				$_user = M( 'User' );
				$_data = $_user->create();

				if ( empty( $this->userInfo ) ) {
					$this->ajaxTip( '请登录' , array( 'refresh' => '/login' ) );
				}
				$_password = $_user->where( array( 'uid' => $this->userInfo['uid'] ) )->field( 'password,salt' )->find();

				if ( !empty( $_data['password'] ) ) {
					if ( empty( $_data['oldPassword'] ) ) {
						$this->ajaxTip( '请输入旧密码！' );
						exit;
					}
					if ( md5( $_data['oldPassword'] ) != $_password['password'] ) {
						$this->ajaxTip( '输入的旧密码有误！' );
						exit;
					}
					$_data['password'] = md5( $_data['password'] );
				} else {
					$_data['password'] = $_password['password'];
				}

				unset( $_data['oldPassword'] );

				$_user->where( array( 'uid' => $this->userInfo['uid'] ) )->save( $_data ) === false ? $this->ajaxTip( '更新失败！' ) : $this->ajaxSuccess( '更新成功！' , array( 'refresh' => 1 ) );

			} else {
				$this->assign( 'info' , $this->userInfo );
				return $this->fetch( 'password' );
			}
		}

		public function userInfo() {
			if ( IS_POST ) {
				$_user = D( 'User' );
				$_data = $_user->create();

				if ( empty( $this->userInfo ) ) {
					$this->ajaxTip( '请登录' , array( 'refresh' => '/login' ) );
				}

				$_data['user_info'] = array(
					'sex'     => $_data['sex'] ,
					'linkman' => $_data['linkman'] ,
					'company' => $_data['company'] ,
					'address' => $_data['address'] ,
					'scale'   => $_data['scale'] ,
					'trade'   => $_data['trade'] ,
				);

				unset( $_data['sex'] , $_data['linkman'] , $_data['company'] , $_data['address'] , $_data['scale'] , $_data['trade'] );

				$_user->relation( 'user_info' )->save( $_data ) === false ? $this->ajaxTip( '更新失败！' ) : $this->ajaxSuccess( '更新成功！' , array( 'refresh' => 1 ) );

			} else {
				$this->assign( 'info' , $this->userInfo );
				return $this->fetch( 'userinfo' );
			}
		}

		public function myShare() {

			$get = I( 'get.' );

			if ( empty( $this->userInfo ) ) {
				$this->ajaxTip( '请登录' , array( 'refresh' => '/login' ) );
			}

			if ( empty( $get['type'] ) ) {
				$get['type'] = 'article';
			}
			$article = array();
			$preUrl  = '';
			$type    = '';
			if ( $get['type'] === 'article' ) {
				$article = M( 'article' )->field( 'id,title,name,create_time,category_id,views' )->where( array( 'uid' => $this->userInfo['uid'],'status'=>array('neq',-1) ) )->select();
				$preUrl  = 'article';
				$type    = '干货';
			} else if ( $get['type'] === 'video' ) {
				$article = M( 'video' )->field( 'id,title,create_time,category_id,views' )->where( array( 'uid' => $this->userInfo['uid'],'status'=>array('neq',-1) ) )->select();
				$preUrl  = 'video';
				$type    = '视频';
			} else if ( $get['type'] === 'course' ) {
				$article = M( 'course' )->field( 'id,title,create_time,category_id' )->where( array( 'uid' => $this->userInfo['uid'],'status'=>array('neq',-1) ) )->select();
				$preUrl  = 'course';
				$type    = '视频';
			}

			$this->assign( array(
				               'info'    => $this->userInfo ,
				               'preUrl'  => $preUrl ,
				               'type'    => $type ,
				               'docList' => $article ,
			               ) );

			return $this->fetch( 'myshare' );


		}

		public function addShare() {

			$get = I( 'get.' );

			if ( empty( $this->userInfo ) ) {
				$this->ajaxTip( '请登录' , array( 'refresh' => '/login' ) );
			}

			if ( empty( $get['type'] ) ) {
				$get['type'] = 'article';
			}

			if ( $get['type'] === 'article' ) {
				$edit = $this->addArticle();
			} else if ( $get['type'] === 'video' ) {
				$edit = $this->addVideo();
			} else if ( $get['type'] === 'course' ) {
				$edit = $this->addCourse();
			}

			$this->assign( array(
				               'userInfo' => $this->userInfo ,
				               'edit'     => $edit ,
			               ) );

			return $this->fetch( 'addshare' );
		}

		public function editShare() {

			$get = I( 'get.' );

			if ( empty( $this->userInfo ) ) {
				$this->ajaxTip( '请登录' , array( 'refresh' => '/login' ) );
			}

			if ( empty( $get['type'] ) ) {
				$get['type'] = 'article';
			}

			if ( $get['type'] === 'article' ) {
				$edit = $this->editArticle();
			} else if ( $get['type'] === 'video' ) {
				$edit = $this->editVideo();
			} else if ( $get['type'] === 'course' ) {
				$edit = $this->editCourse();
			}

			$this->assign( array(
				               'userInfo' => $this->userInfo ,
				               'edit'     => $edit ,
			               ) );

			return $this->fetch( 'addshare' );
		}

		public function delShare() {

			$get = I( 'get.' );

			if ( empty( $this->userInfo ) ) {
				$this->ajaxTip( '请登录' , array( 'refresh' => '/login' ) );
			}
			$modelId = 0;
			if ( $get['type'] === 'article' ) {
				$modelId = 2;
			} else if ( $get['type'] === 'video' ) {
				$modelId = 3;
			} else if ( $get['type'] === 'course' ) {
				$modelId = 0;
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
				$this->ajaxSuccess( '删除成功！',array('refresh'=>1) );
			} else {
				$this->ajaxError( '删除失败！' );
			}

		}

		public function addArticle() {
			if ( IS_POST ) {
				$data = I( 'post.' );

				$tid = $data['tid'];

				$category = $model = $modelField = $addIds = $addFields = array();

				if ( empty( $tid ) ) {
					$this->ajaxTip( '没有此模块儿！' );
				}

				if ( empty( $data['title'] ) ) {
					$this->ajaxTip( '标题不能为空！' );
				}

				/*if ( empty( $data['name'] ) ) {
					$this->ajaxTip( '文档标识不能为空！' );
				}*/

				if ( empty( $data['content_value'] ) ) {
					$this->ajaxTip( '文档内容不能为空！' );
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

				$articleModel   = M( $model['name'] );
				$data           = $articleModel->create( $data );
				$data['uid']    = $this->userInfo['uid'];
				$data['status'] = 0;

				$rs = M( $model['name'] )->add( $data );

				if ( $rs === false ) {
					$this->ajaxTip( '更新基础内容失败！' );
				}

				$this->ajaxSuccess( '添加成功！' );

			} else {

				$tid = 2;

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
						if ( $v['name'] == 'uid' ) {
							unset( $modelField[ $k ] , $fieldSort[ $v['id'] ] );
							continue;
						}
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
					               'type'       => 'article' ,
					               'info'       => array(
						               'vid'         => '' ,
						               'views'       => '' ,
						               'duration'    => '' ,
						               'create_time' => '' ,
					               ) ,
				               ) );

				return $this->fetch( 'article_edit' );
			}
		}

		public function editArticle() {

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

				if ( empty( $data['title'] ) ) {
					$this->ajaxTip( '标题不能为空！' );
				}

				/*if ( empty( $data['name'] ) ) {
					$this->ajaxTip( '文档标识不能为空！' );
				}*/

				if ( empty( $data['content_value'] ) ) {
					$this->ajaxTip( '文档内容不能为空！' );
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
				$tid = 2;

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
					               'type'       => 'article' ,
					               'fieldGroup' => $fieldGroup ,
					               'fieldEnum'  => $fieldEnum ,
					               'info'       => $info ,
				               ) );

			}

			return $this->fetch( 'article_edit' );
		}

		public function addVideo() {
			if ( IS_POST ) {
				$data = I( 'post.' );

				$tid = $data['tid'];

				$category = $model = $modelField = $addIds = $addFields = array();

				if ( empty( $tid ) ) {
					$this->ajaxTip( '没有此模块儿！' );
				}

				if ( empty( $data['title'] ) ) {
					$this->ajaxTip( '标题不能为空！' );
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

				$articleModel   = M( $model['name'] );
				$data           = $articleModel->create( $data );
				$data['uid']    = $this->userInfo['uid'];
				$data['status'] = 0;

				$rs = M( $model['name'] )->add( $data );

				if ( $rs === false ) {
					$this->ajaxTip( '更新基础内容失败！' );
				}

				$this->ajaxSuccess( '添加成功！' );

			} else {

				$tid = 1;

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
						if ( $v['name'] == 'uid' ) {
							unset( $modelField[ $k ] , $fieldSort[ $v['id'] ] );
							continue;
						}
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
					               'type'       => 'video' ,
					               'info'       => array(
						               'vid'         => '' ,
						               'views'       => '' ,
						               'duration'    => '' ,
						               'create_time' => '' ,
					               ) ,
				               ) );

				return $this->fetch( 'article_edit' );
			}
		}

		public function editVideo() {
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

				if ( empty( $data['title'] ) ) {
					$this->ajaxTip( '标题不能为空！' );
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
				$tid = 1;

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
						if ( array_key_exists( $v['is_show'] , array( '78' => 0 , '79' => 1 ) ) ) {
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
					               'type'       => 'video' ,
					               'fieldGroup' => $fieldGroup ,
					               'fieldEnum'  => $fieldEnum ,
					               'info'       => $info ,
				               ) );

			}

			return $this->fetch( 'article_edit' );
		}

		public function addCourse() {
			if ( IS_POST ) {
				$data = I( 'post.' );
				if ( empty( $data['title'] ) ) {
					$this->ajaxTip( '课程主题不能为空！' );
				}
				$oTitle = M( 'course' )->where( array( 'title' => $data['title'] ) )->find();

				if ( !empty( $oTitle ) ) {
					$this->ajaxTip( '课程主题名已经存在！' );
				}

				unset( $data['id'] );
				$data['status']      = 0;
				$data['uid']         = $this->userInfo['uid'];
				$data['create_time'] = strtotime( $data['create_time'] );
				$result              = M( 'course' )->add( $data );
				if ( $result ) {
					$this->ajaxSuccess( "添加成功！" );
				} else {
					$this->ajaxError( "添加失败！" );
				}
			} else {
				//获取视频的分类
				$videoCate = D( 'Article' )->getVideoCate( 1 );
				$this->assign( array(
					               'videoCate' => $videoCate ,
					               'type'      => 'course' ,
				               ) );
				return $this->fetch( 'course_edit' );
			}
		}

		public function userInfoEdit() {

			if ( IS_POST ) {

				$_user = D( 'User' );
				$_data = $_user->create();

				if ( empty( $this->userInfo ) ) {
					$this->ajaxTip( '请登录' , array( 'refresh' => '/login' ) );
				}

				$_password = $_user->where( array( 'uid' => $this->userInfo['uid'] ) )->field( 'password,salt' )->find();

				if ( !empty( $_data['password'] ) ) {
					if ( empty( $_data['oldPassword'] ) ) {
						$this->ajaxTip( '请输入旧密码！' );
						exit;
					}
					if ( md5( $_data['oldPassword'] ) != $_password['password'] ) {
						$this->ajaxTip( '输入的旧密码有误！' );
						exit;
					}
					$_data['password'] = md5( $_data['password'] );
				} else {
					$_data['password'] = $_password['password'];
				}

				$_data['user_info'] = array(
					'sex'     => $_data['sex'] ,
					'linkman' => $_data['linkman'] ,
					'company' => $_data['company'] ,
					'address' => $_data['address'] ,
					'scale'   => $_data['scale'] ,
					'trade'   => $_data['trade'] ,
				);

				unset( $_data['oldPassword'] , $_data['sex'] , $_data['linkman'] , $_data['company'] , $_data['address'] , $_data['scale'] , $_data['trade'] );

				$_user->relation( 'user_info' )->save( $_data ) === false ? $this->ajaxTip( '更新失败！' ) : $this->ajaxSuccess( '更新成功！' , array( 'refresh' => 1 ) );

			}
		}

		public function cropPic() {

			$avatar = I( 'post.' );

			if ( empty( $avatar['avatar'] ) || !isset( $avatar['cropW'] ) || !isset( $avatar['cropH'] ) || !isset( $avatar['cropX'] ) || !isset( $avatar['cropY'] ) ) {
				$this->ajaxTip( '图片信息有误！' );
			}

			$src = M( 'avatar' )->where( array( 'id' => $avatar['avatar'] ) )->field( 'path' )->find();

			if ( empty($src) ) {
				$this->ajaxTip( '图片信息有误！' );
			}

			$path = APP_ROOT . str_replace( '/' , DS , $src['path'] );

			if(!is_file($path)){
				$this->ajaxTip('头像图片不存在，请重新上传！');
			}
			$pathInfo = pathinfo( $path );

			$savePath = $pathInfo['dirname'] . DS . 'crop_' . $pathInfo['basename'];

			$image = Image::init( 'Gd' , $path );

			$oW = $image->width();
			$oH = $image->height();

			$image->crop( $avatar['cropW']*( $oW/148 ) , $avatar['cropH']*( $oH/148 ) , $avatar['cropX']*( $oW/148 ) , $avatar['cropY']*( $oH/148 ) )->save( $savePath );

			$rs = str_replace( array( APP_ROOT , DS  ) , array( '' , '/' ) , $savePath );

			$this->ajaxTip( '裁剪成功！' , array( 'icon' => 6 , 'callback' => "cropSucess('" . $rs . "')" ) );
		}

		//上传单张图片
		public function uploadAvatar() {

			if ( !empty( $_FILES ) ) {

				$config = array(
					'maxSize'  => 3145728 ,
					'exts'     => array( 'jpg' , 'gif' , 'png' ) ,
					'rootPath' => UPLOAD_PATH ,
					'savePath' => 'avatar/' ,
				);

				$upload = new Upload( $config , 'LOCAL' );
				$info   = $upload->uploadOne( $_FILES['file'] );

				if ( !empty( $info ) ) {

					$path = _UPLOAD . "/" . $info['savepath'] . $info['savename'];

					$data = [
						'path'        => $path ,
						'md5'         => $info['md5'] ,
						'sha1'        => $info['sha1'] ,
						'create_time' => time() ,
					];

					$result = M( 'avatar' )->add( $data );
					$rs     = false;
					if ( isset( $this->userInfo['uid'] ) ) {
						if ( $result !== false ) {
							$rs = M( 'user_info' )->where( array( 'uid' => $this->userInfo['uid'] ) )->save( array( 'avatar' => $result ) );
						}
					} else {
						return 5;
					}

					return $rs === false ? 5 : $result . ',' . $path;

				} else {
					return $this->ajaxError( $upload->getError() );
				}

			}

		}

	}
