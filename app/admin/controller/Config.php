<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/3/3
	 * Time: 14:32
	 */

	namespace app\admin\controller;

	use app\common\controller\Admin;

	class Config extends Admin {

		public function index() {
			$lists = D( 'Config' )->select();
			foreach ( $lists as $k => $v ) {
				$lists[ $k ]['group'] = S( 'enum' )[1]['_'][101]['_'][ $v['group'] ]['title'];
				$lists[ $k ]['type']  = S( 'enum' )[1]['_'][42]['_'][ $v['type'] ]['title'];
				//$lists[$k]['group'] = M('enum')->where(array('id'=>$v['group']))->getField('title');
				//$lists[$k]['type'] = M('enum')->where(array('id'=>$v['type']))->getField('title');
			}
			$this->assign( 'list' , $lists );
			return $this->fetch();
		}

		public function set() {

			if ( IS_POST ) {
				$config = M( 'Config' );
				$arr    = I( 'post.' );
				unset( $arr['id'] );
				foreach ( $arr as $k => $v ) {
					$data['cvalue'] = $v;
					$config->where( array( 'ckey' => $k ) )->save( $data );
				}
				$this->updateCache( 0 );
				$this->ajaxSuccess( '更新成功！' );
			} else {
				$config = $enum = array();

				//获取网站设置的枚举项
				$group = S( 'enum' )[1]['_'][101]['_'];
				$key   = key( $group );
				$id    = I( 'get.id' , $key );
				//找出分组下的配置项
				if ( !empty( $id ) ) {
					$configs = S( 'config' );
					foreach ( $configs as $key => $value ) {
						if ( $value['group'] == $id ) {
							$config[] = $value;
							foreach ( $config as $k => $v ) {
								if ( $v['type'] == 49 ) {
									$pid                  = $v['eid'];
									$config[ $k ]['enum'] = D( 'enum' )->field( 'id,title,name,pid' )->where( array( 'pid' => $pid ) )->select();

								}
							}

						}
					}
				}

				$this->assign( 'id' , $id );
				$this->assign( 'config' , $config );
				$this->assign( 'group' , $group );
				return $this->fetch();
			}

		}


		public function add() {
			if ( IS_POST ) {
				$config = D( 'Config' );
				$config->create();
				unset( $config->id );
				if ( $config->add() ) {
					$this->ajaxSuccess( '添加成功！' );
				} else {
					$this->ajaxSuccess( '添加失败！' );
				}
			} else {
				$enum = S( 'enum' );
				$this->assign( 'group' , $enum[1]['_'][101]['_'] );
				$this->assign( 'type' , $enum[1]['_'][42]['_'] );
				return $this->fetch( 'edit' );
			}
		}

		public function edit() {
			if ( IS_POST ) {
				$data = M( 'Config' )->create();
				if ( M( 'Config' )->save() !== false ) {
					$this->ajaxSuccess( '更新成功！' );
				} else {
					$this->ajaxSuccess( '更新失败！' );
				}
			} else {
				$id = I( 'get.id' );
				$this->assign( 'info' , array( 'id' => $id ) );

				$enum = array();
				$enum = S( 'enum' );

				$this->assign( 'group' , $enum[1]['_'][101]['_'] );
				$this->assign( 'type' , $enum[1]['_'][42]['_'] );

				$config = M( 'Config' )->field( true )->find( $id );
				$this->assign( 'config' , $config );

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
			if ( M( 'config' )->where( $map )->delete() ) {
				$this->ajaxSuccess( '删除成功！' );
			} else {
				$this->ajaxError( '删除失败！' );
			}
		}

		public function updateCache( $tip = 1 ) {

			$result = D( 'config' )->select();
			S( 'config' , $result );
			if ( $tip == 1 ) {
				$this->ajaxSuccess( '缓存成功！' );
			}
		}

	}