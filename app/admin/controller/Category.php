<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/7
	 * Time: 14:00
	 */

	namespace app\admin\controller;

	use app\common\controller\Addons;
	use app\common\controller\Admin;

	class Category extends Admin {

		use Addons;

		public function index() {
			$tree = D( 'Category' )->getTree( 0 , 'id,name,title,sort,pid,allow_publish,status' );
			$this->assign( 'tree' , $tree );
			return $this->fetch();
		}

		public function tree( $tree = null ) {
			$this->assign( 'tree' , $tree );
			return $this->fetch( 'tree' );
		}

		/**
		 * 添加分类
		 * @param int $pid
		 * User: Sam:yyzm@vip.qq.com
		 */
		public function add( $pid = 0 ) {

			$Category = D( 'Category' );
			if ( IS_POST ) { //提交表单
				$pid = I( 'id' , 0 );
				$Category->create();
				unset( $Category->id );
				$aid = $Category->add();
				if ( ( $pid == 0 ) && $aid ) {
					$this->ajaxSuccess( '添加成功！' );
				} elseif ( ( $pid > 0 ) && $aid ) {
					$Category->where( array( 'id' => $aid ) )->save( array( 'pid' => $pid ) );
					$tid = getCateTid( 'category' , $aid );
					$Category->where( array( 'id' => $aid ) )->save( array( 'tid' => $tid ) );
					$this->ajaxSuccess( '添加成功！' );
				} else {
					$this->ajaxSuccess( '添加失败！' );
				}

			} else {
				$cate = array();
				if ( $pid ) {
					/* 获取上级分类信息 */
					$cate = $Category->field( 'id,title,status' )->where( array( 'id' => $pid ) )->find();
					if ( !( $cate && 1 == $cate['status'] ) ) {
						$this->ajaxSuccess( '指定的上级分类不存在或被禁用！' );
					}
				}
				$model = M( 'model' )->where( array( 'id' => array( 'NEQ' , 1 ) ) )->field( 'id,title' )->select();
				$this->assign( 'model' , $model );
				$this->assign( 'info' , null );
				$this->assign( 'category' , $cate );
				return $this->fetch( 'edit' );
			}
		}

		/**
		 * 编辑分类
		 * @param null $id
		 * @param int  $pid
		 * User: Sam:yyzm@vip.qq.com
		 */
		public function edit( $id = null , $pid = 0 ) {
			$category = D( 'Category' );
			if ( IS_POST ) {
				$data   = $category->create();
				$update = $category->save( $data );
				if ( $update !== false ) {
					$this->ajaxSuccess( '更新成功！' );
				} else {
					$this->ajaxSuccess( '更新失败！' );
				}

			} else {
				$cate = $category->where( array( 'id' => $id ) )->find();
				if ( $pid > 0 ) {
					$category = $category->field( 'id,title' )->where( array( 'id' => $pid ) )->select();
					$arr      = array();
					foreach ( $category as $v ) {
						$arr = $v;
					}
				} else {
					$arr = '';
				}
				$model = M( 'model' )->where( array( 'id' => array( 'NEQ' , 1 ) ) )->field( 'id,title' )->select();
				$this->assign( 'model' , $model );
				$this->assign( 'category' , $cate );
				$this->assign( 'info' , array( 'id' => $id ) );
				$this->assign( 'cate' , $cate );
				return $this->fetch();
			}
		}

		public function remove() {

			if ( IS_POST ) {
				$result = I( 'post.' );
				$id     = empty( $result['id'] ) ? '' : $result['id'];
			} else {
				$id = (array)I( 'get.id' );
			}

			if ( empty( $id ) ) {
				$this->ajaxError( '请选择要操作的数据！' );
			}

			$checkId = array();
			foreach ( $id as $v ) {
				$checkChild = D( 'category' )->where( array( 'pid' => $v ) )->find();
				if ( !empty( $checkChild ) ) {
					$checkId[] = $v;
				}
			}

			if ( !empty( $checkId ) ) {
				$checkId = implode( "," , $checkId );
				$this->ajaxError( $checkId . '有子级分类，请先删除子级分类，再删父类！' );
			}

			$map = array( 'id' => array( 'in' , $id ) );
			if ( M( 'category' )->where( $map )->delete() ) {
				$this->ajaxSuccess( '删除成功！' );
			} else {
				$this->ajaxError( '删除失败！' );
			}

		}

	}