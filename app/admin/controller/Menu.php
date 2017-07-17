<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/7
	 * Time: 14:00
	 */

	namespace app\admin\controller;

	use app\common\controller\Admin;

	class Menu extends Admin {

		public function index() {

			$pid = I( 'get.pid' , 0 );
			if ( $pid ) {
				$data = M( 'Menu' )->where( "id={$pid}" )->field( true )->find();
				$this->assign( 'data' , $data );
			}
			$title      = trim( I( 'get.title' ) );
			$all_menu   = M( 'Menu' )->getField( 'id,title' );
			$map['pid'] = $pid;
			if ( $title )
				$map['title'] = array( 'like' , "%{$title}%" );
			$list = M( "Menu" )->where( $map )->field( true )->order( 'sort asc,id asc' )->select();
			int_to_string( $list , array( 'hide' => array( 1 => '是' , 0 => '否' ) , 'is_dev' => array( 1 => '是' , 0 => '否' ) ) );
			if ( $list ) {
				foreach ( $list as &$key ) {
					if ( $key['pid'] ) {
						$key['up_title'] = $all_menu[ $key['pid'] ];
					}
				}
				$this->assign( 'list' , $list );
			}

			$this->getButtons();
			return $this->fetch();
		}

		public function add() {
			if ( IS_POST ) {
				$Menu = D( 'Menu' );
				$Menu->create();
				unset( $Menu->id );
				if ( $Menu->add() ) {
					$this->ajaxSuccess( '添加成功！' );
				} else {
					$this->ajaxError( '添加失败！' );
				}
			} else {
				$this->assign( 'info' , array( 'pid' => I( 'pid' ) ) );
				$menus = M( 'Menu' )->field( true )->select();

				foreach ( $menus as $key => $val ) {
					if ( $val['pid'] == 0 ) {
						$authGroup[] = $val;
					}
				}

				$enum = S( 'enum' );

				$menus = D( 'common/Tree' )->toFormatTree( $menus );
				$menus = array_merge( array( 0 => array( 'id' => 0 , 'title_show' => '顶级菜单' ) ) , $menus );

				$this->assign( array(
					               'Menus'     => $menus ,
					               'authGroup' => $authGroup ,
					               'group'     => $enum[127]['_'][128]['_'] ,
				               ) );

				return $this->fetch( 'edit' );
			}
		}

		public function edit( $id = 0 ) {
			if ( IS_POST ) {
				$Menu = D( 'Menu' );
				$data = $Menu->create();
				if ( $Menu->save() !== false ) {
					$this->ajaxSuccess( '更新成功！' );
				} else {
					$this->ajaxError( '更新失败！' );
				}
			} else {

				/* 获取数据 */
				$menus = M( 'Menu' )->field( true )->select();

				foreach ( $menus as $key => $val ) {
					if ( $val['id'] == $id ) {
						$info = $val;
					}
					if ( $val['pid'] == 0 ) {
						$authGroup[] = $val;
					}
				}

				$menus = D( 'common/Tree' )->toFormatTree( $menus );

				$menus = array_merge( array( 0 => array( 'id' => 0 , 'title_show' => '顶级菜单' ) ) , $menus );

				$enum = S( 'enum' );

				if ( empty( $info ) ) {
					$this->ajaxError( '获取后台菜单信息错误！' );
				}

				$this->assign( array(
					               'info'      => $info ,
					               'Menus'     => $menus ,
					               'authGroup' => $authGroup ,
					               'group'     => $enum[127]['_'][128]['_'] ,
				               ) );

				return $this->fetch();
			}
		}

		public function del() {

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
				$checkChild = D( 'menu' )->where( array( 'pid' => $v ) )->find();
				if ( !empty( $checkChild ) ) {
					$checkId[] = $v;
				}
			}

			if ( !empty( $checkId ) ) {
				$checkId = implode( "," , $checkId );
				$this->ajaxError( $checkId . '有子级分类，请先删除子级分类，再删父类！' );
			}

			$map = array( 'id' => array( 'in' , $id ) );
			if ( M( 'menu' )->where( $map )->delete() ) {
				$this->ajaxSuccess( '删除成功！' );
			} else {
				$this->ajaxError( '删除失败！' );
			}
		}

		public function fieldUpdate() {

			if ( IS_POST ) {
				$id   = I( 'get.id' );
				$sort = I( 'post.sort_' . $id );

				$update['sort'] = $sort;
				$where['id']    = $id;
				M( 'Menu' )->where( $where )->save( $update );
			}
		}

	}