<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/7
	 * Time: 14:00
	 */

	namespace app\admin\controller;

	use app\common\controller\Admin;

	class Hooks extends Admin {

		private static $static_dragsort = 1;

		public function index() {
			$lists = M( 'hooks' )->select();
			foreach ( $lists as $k => $v ) {
				$lists[ $k ]['type'] = S( 'enum' )[122]['_'][123]['_'][ $v['type'] ]['title'];
			}
			$this->assign( 'list' , $lists );
			$this->meta_title = '钩子列表';
			return $this->fetch();
		}

		public function add() {

			if ( IS_POST ) {
				D( 'hooks' )->create();
				unset( D( 'hooks' )->id );
				$id = D( 'hooks' )->add();
				if ( $id ) {
					$this->ajaxSuccess( '添加成功！' );
				} else {
					$this->ajaxSuccess( '添加失败！' );
				}
			} else {
				$common          = _COMMON;
				$static_dragsort = null;
				if ( self::$static_dragsort == 1 ) {
					$static_dragsort       = <<<static
	<script type="text/javascript" src="{$common}/js/jquery.dragsort-0.5.2.min.js"></script>
static;
					self::$static_dragsort = 2;
				}
				$this->assign( 'static_dragsort' , $static_dragsort );
				$hook_type = S( 'enum' )[122]['_'][123]['_'];
				$this->assign( 'hook_type' , $hook_type );
				return $this->fetch( 'edit' );
			}

		}

		public function edit() {

			if ( IS_POST ) {
				M( 'hooks' )->create();
				if ( M( 'hooks' )->save() !== false ) {
					$this->ajaxSuccess( '更新成功！' );
				} else {
					$this->ajaxSuccess( '更新失败！' );
				}
			} else {
				$id = I( 'get.id' );
				$this->assign( 'info' , array( 'id' => $id ) );

				$common          = _COMMON;
				$static_dragsort = null;
				if ( self::$static_dragsort == 1 ) {
					$static_dragsort       = <<<static
	<script type="text/javascript" src="{$common}/js/jquery.dragsort-0.5.2.min.js"></script>
static;
					self::$static_dragsort = 2;
				}
				$this->assign( 'static_dragsort' , $static_dragsort );

				$hook_type = S( 'enum' )[122]['_'][123]['_'];
				$this->assign( 'hook_type' , $hook_type );

				$hook_info   = M( 'hooks' )->field( true )->find( $id );
				$hook_addons = $hook_info['addons'];

				$addons              = array_filter( explode( "," , $hook_info['addons'] ) );
				$hook_info['addons'] = $addons;

				$this->assign( 'hook_addons' , $hook_addons );
				$this->assign( 'hook_info' , $hook_info );

				return $this->fetch( 'edit' );
			}

		}

		public function del() {

			if ( IS_POST ) {
				$result = I( 'post.' );
				$id     = empty( $result['id'] ) ? '' : $result['id'];
			} else {
				$id = I( 'get.id' );
			}

			if ( empty( $id ) ) {
				$this->ajaxError( '请选择要操作的数据！' );
			}

			$map = array( 'id' => array( 'in' , $id ) );
			if ( M( 'hooks' )->where( $map )->delete() ) {
				$this->ajaxSuccess( '删除成功！' );
			} else {
				$this->ajaxError( '删除失败！' );
			}
		}
	}