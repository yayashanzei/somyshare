<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/3/3
	 * Time: 14:31
	 */

	namespace app\admin\controller;

	use app\common\controller\Admin;

	class User extends Admin {

		public static $uid;

		public function index() {
			return $this->fetch();
		}

		public function authCheck( $method ) {

		}

		/**
		 * info
		 * @return mixed
		 */
		public function info() {

			$users = D( 'user' )->field( 'uid,name,email,status' )->select();

			$_status = array( '-1' => '已删除' , '0' => '待审核' , '1' => '正常' , '2' => '审核未通过' , '3' => '正常' , '4' => '禁用' );
			foreach ( $users as $key => $val ) {
				$users[ $key ]['status'] = $_status[ $val['status'] ];
			}

			$this->assign( array(
				               'users' => $users ,
			               ) );
			return $this->fetch();
		}


		public function infoEdit() {

			if ( IS_POST ) {

				$_user     = D( 'User' );
				$_data     = $_user->create();
				$_password = $_user->where( array( 'uid' => $_data['uid'] ) )->field( 'password,salt' )->find();

				if ( !empty( $_data['password'] ) ) {
					if ( empty( $_data['oldPassword'] ) ) {
						$this->ajaxError( '请输入旧密码！' );
						exit;
					}
					if ( md5( md5( $_data['oldPassword'] ) . $_password['salt'] ) != $_password['password'] ) {
						$this->ajaxError( '输入的旧密码有误！' );
						exit;
					}
					$_data['password'] = md5( md5( $_data['password'] ) . $_password['salt'] );
				} else {
					$_data['password'] = $_password['password'];

				}

				$_data['user_info'] = array(
					'groupid' => $_data['groupid'] ,
					'sex'     => $_data['sex'] ,
					'linkman' => $_data['linkman'] ,
					'company' => $_data['company'] ,
					'address' => $_data['address'] ,
					'scale'   => $_data['scale'] ,
					'trade'   => $_data['trade'] ,
				);
				unset( $_data['oldPassword'] , $_data['groupid'] , $_data['sex'] , $_data['linkman'] , $_data['company'] , $_data['address'] , $_data['scale'] , $_data['trade'] );

				$_user->relation( 'user_info' )->save( $_data ) === false ? $this->ajaxError( '更新失败！' ) : $this->ajaxSuccess( '更新成功！' );

			} else {

				$uid = I( 'get.uid' );

				if ( empty( $uid ) ) {
					$this->ajaxError( '用户不存在！' );
				}

				$_info = D( 'User' )->field( 'uid,name,password' )->relation( 'user_info' )->where( array( 'uid' => $uid ) )->find();

				$userGroup = M( 'user_group' )->field( 'id,group_name' )->select();

				$this->assign( array(
					               'info'      => $_info ,
					               'userGroup' => $userGroup ,
				               ) );

				return $this->fetch( 'info_edit' );
			}
		}


		public function infoAdd() {


			if ( IS_POST ) {

				$_user = D( 'User' );
				$_data = $_user->create();

				if ( empty( $_data['name'] ) ) {
					$this->ajaxError( '请输入用户名！' );
				}

				$_name = $_user->where( array( 'name' => $_data['name'] ) )->field( 'uid' )->select();

				if ( !empty( $_name ) ) {
					$this->ajaxError( '用户已经存在！' );
				}
				if ( empty( $_data['password'] ) || empty( $_data['confirmPassword'] ) ) {
					$this->ajaxError( '请填写密码！' );
				}
				if ( $_data['password'] !== $_data['confirmPassword'] ) {
					$this->ajaxError( '两次密码输入不相同！' );
				}

				if ( empty( $_data['salt'] ) ) {
					$this->ajaxError( '必须填写个人加密字符！' );
				}

				$_data['password'] = md5( md5( $_data['password'] ) . $_data['salt'] );

				$_data['user_info'] = array(
					'groupid' => $_data['groupid'] ,
					'sex'     => $_data['sex'] ,
					'linkman' => $_data['linkman'] ,
					'company' => $_data['company'] ,
					'address' => $_data['address'] ,
					'scale'   => $_data['scale'] ,
					'trade'   => $_data['trade'] ,
					'regtime' => substr( microtime() , -10 ) ,
				);

				unset( $_data['confirmPassword'] , $_data['uid'] , $_data['groupid'] , $_data['sex'] , $_data['linkman'] , $_data['company'] , $_data['address'] , $_data['scale'] , $_data['trade'] );

				$_user->relation( 'user_info' )->add( $_data ) === false ? $this->ajaxError( '添加失败！' ) : $this->ajaxSuccess( '添加成功！' );

			} else {
				$userGroup = M( 'user_group' )->field( 'id,group_name' )->select();
				$this->assign( array(
					               'info'      => array() ,
					               'userGroup' => $userGroup ,
				               ) );
				return $this->fetch( 'info_edit' );
			}
		}

		public function infoDel() {

			if ( IS_POST ) {

				$uid = I( 'post.' );

				if ( empty( $uid ) ) {
					$this->ajaxError( '请选择用户！' );
				}

				$uid = implode( ',' , $uid['uid'] );

				$_data['status'] = -1;

				D( 'User' )->where( array( 'uid' => array( 'in' , $uid ) ) )->save( $_data ) === false ? $this->ajaxError( '删除失败！' ) : $this->ajaxSuccess( '删除成功！' );

			} else {
				$uid = I( 'get.uid' );

				if ( empty( $uid ) ) {
					$this->ajaxError( '用户不存在！' );
				}
				$_data['status'] = -1;

				D( 'User' )->where( array( 'uid' => $uid ) )->save( $_data ) === false ? $this->ajaxError( '删除失败！' ) : $this->ajaxSuccess( '删除成功！' );

			}
		}

		public function infoEnable() {

			if ( IS_POST ) {

				$uid = I( 'post.' );

				if ( empty( $uid ) ) {
					$this->ajaxError( '请选择用户！' );
				}

				$uid = implode( ',' , $uid['uid'] );

				$_data['status'] = 3;

				D( 'User' )->where( array( 'uid' => array( 'in' , $uid ) ) )->save( $_data ) === false ? $this->ajaxError( '启用失败！' ) : $this->ajaxSuccess( '启用成功！' );


			} else {
				$this->ajaxError( '非法操作！' );
			}
		}

		public function infoDisable() {

			if ( IS_POST ) {

				$uid = I( 'post.' );

				if ( empty( $uid ) ) {
					$this->ajaxError( '请选择用户！' );
				}

				$uid = implode( ',' , $uid['uid'] );

				$_data['status'] = 4;

				D( 'User' )->where( array( 'uid' => array( 'in' , $uid ) ) )->save( $_data ) === false ? $this->ajaxError( '禁用失败！' ) : $this->ajaxSuccess( '禁用成功！' );


			} else {
				$this->ajaxError( '非法操作！' );
			}
		}

		public function infoVerify() {

			if ( IS_POST ) {

				$uid = I( 'post.' );

				if ( empty( $uid ) ) {
					$this->ajaxError( '请选择用户！' );
				}

				$uid = implode( ',' , $uid['uid'] );

				$_data['status'] = 1;

				D( 'User' )->where( array( 'uid' => array( 'in' , $uid ) ) )->save( $_data ) === false ? $this->ajaxError( '审核失败！' ) : $this->ajaxSuccess( '审核成功！' );


			} else {
				$this->ajaxError( '非法操作！' );
			}
		}

		/**
		 * info
		 * @return mixed
		 */
		public function group() {

			$groups = D( 'user_group' )->field( 'id,group_name,group_remark,status' )->select();
			$enum   = S( 'enum' );;

			$_status = array( '0' => '禁用' , '1' => '正常' );

			foreach ( $groups as $key => $val ) {
				$groups[ $key ]['status'] = $_status[ $val['status'] ];
			}

			$_menu = D( 'menu' )->field( 'id,title' )->where( array( 'pid' => 0 ) )->select();

			$this->assign( array(
				               'groups'    => $groups ,
				               'authGroup' => $_menu ,
			               ) );

			return $this->fetch();
		}

		public function groupEdit() {

			if ( IS_POST ) {

				$authGroup = D( 'user_group' );
				$_data     = $authGroup->create();

				if ( empty( $_data['group_name'] ) ) {
					$this->ajaxError( '请输入用户组名！' );
				}

				$authGroup->save( $_data ) === false ? $this->ajaxError( '编辑失败！' ) : $this->ajaxSuccess( '编辑成功！' );

			} else {

				$id = I( 'get.id' );

				if ( empty( $id ) ) {
					$this->ajaxError( '用户组不存在！' );
				}

				$_info = D( 'user_group' )->where( array( 'id' => $id ) )->select();

				$this->assign( array( 'info' => $_info[0] ) );

				return $this->fetch( 'group_edit' );
			}
		}


		public function groupAdd() {

			if ( IS_POST ) {

				$authGroup = D( 'user_group' );
				$_data     = $authGroup->create();

				if ( empty( $_data['group_name'] ) ) {
					$this->ajaxError( '请输入用户组名！' );
				}
				unset( $_data['id'] );
				$authGroup->add( $_data ) === false ? $this->ajaxError( '添加失败！' ) : $this->ajaxSuccess( '添加成功！' );

			} else {
				$this->assign( 'info' , array() );
				return $this->fetch( 'group_edit' );
			}

		}

		public function groupDel() {

			if ( IS_POST ) {

				$id = I( 'post.' );

				if ( empty( $id ) ) {
					$this->ajaxError( '请选择用户组！' );
				}

				$id = implode( ',' , $id['id'] );

				D( 'user_group' )->where( array( 'id' => array( 'in' , $id ) ) )->delete() === false ? $this->ajaxError( '删除失败！' ) : $this->ajaxSuccess( '删除成功！' );

			} else {
				$id = I( 'get.id' );

				if ( empty( $id ) ) {
					$this->ajaxError( '用户组不存在！' );
				}

				D( 'user_group' )->delete( $id ) === false ? $this->ajaxError( '删除失败！' ) : $this->ajaxSuccess( '删除成功！' );

			}
		}

		public function groupEnable() {

			if ( IS_POST ) {

				$id = I( 'post.' );

				if ( empty( $id ) ) {
					$this->ajaxError( '请选择用户组！' );
				}

				$id = implode( ',' , $id['id'] );

				$_data['status'] = 1;

				D( 'user_group' )->where( array( 'id' => array( 'in' , $id ) ) )->save( $_data ) === false ? $this->ajaxError( '启用失败！' ) : $this->ajaxSuccess( '启用成功！' );


			} else {
				$this->ajaxError( '非法操作！' );
			}
		}

		public function groupDisable() {

			if ( IS_POST ) {

				$id = I( 'post.' );

				if ( empty( $id ) ) {
					$this->ajaxError( '请选择用户组！' );
				}

				$id = implode( ',' , $id['id'] );

				$_data['status'] = 0;

				D( 'user_group' )->where( array( 'id' => array( 'in' , $id ) ) )->save( $_data ) === false ? $this->ajaxError( '禁用失败！' ) : $this->ajaxSuccess( '禁用成功！' );


			} else {
				$this->ajaxError( '非法操作！' );
			}
		}

		public function authorize() {

			if ( IS_POST ) {
				$_post = I( 'post.' );

				$_userGroup = D( 'user_group' );
				$groupAuth  = $_userGroup->where( array( 'id' => $_post['group_id'] ) )->field( 'group_auth' )->find();

				if ( $groupAuth['group_auth'] != '0' ) {
					$_groupAuth = unserialize( $groupAuth['group_auth'] );
				}
				if ( empty( $_post['rules'] ) ) {
					$_post['rules'] = array();
				}

				$_groupAuth[ $_post['auth_group'] ] = array_flip( $_post['rules'] );

				$_data['id']         = $_post['group_id'];
				$_data['group_auth'] = serialize( $_groupAuth );

				if ( strcmp( $groupAuth['group_auth'] , $_data['group_auth'] ) == 0 ) {
					$this->ajaxError( '权限没有任何变更！' );
				}

				$_userGroup->save( $_data ) === false ? $this->ajaxError( '权限变更失败！' ) : $this->ajaxSuccess( '权限变更成功！' );

			} else {

				$_authId  = I( 'get.authid' );
				$_groupId = I( 'get.groupid' );

				if ( empty( $_groupId ) ) {
					$this->ajaxError( '用户组不存在！' );
				}

				if ( empty( $_authId ) ) {
					$this->ajaxError( '权限组不存在！' );
				}

				$_menu = D( 'menu' )->where( array( 'auth_group' => $_authId ) )->field( 'id,title,pid,sort,url,hide,tip,group,auth_group,is_dev,status' )->select();

				$_groupAuth = D( 'user_group' )->where( array( 'id' => $_groupId ) )->field( 'id,group_auth' )->find();

				if ( $_groupAuth['group_auth'] != '0' ) {
					$groupAuth = unserialize( $_groupAuth['group_auth'] );
				}


				if ( $_authId == 47 ) {
					$cate = M( 'category' )->field( 'id,name,title,model,icon' )->where( array( 'pid' => 0 , 'display' => array( 'NEQ' , 0 ) ) )->select( array( 'index' => 'id' ) );
				}

				if ( !empty( $cate ) ) {
					foreach ( $cate as $key => $val ) {
						$cate[ $key ]['id'] = 'cate' . $val['id'];
					}
					$this->assign( 'cate' , $cate );
				}

				$this->assign( array( 'menu' => $_menu , 'auth_group' => $_authId , 'group_id' => $_groupId , 'groupAuth' => ( isset( $groupAuth[ $_authId ] ) ? $groupAuth[ $_authId ] : array() ) ) );

				return $this->fetch( 'authorize' );
			}
		}

		/**
		 * info
		 * @return mixed
		 */
		public function auth() {

			$authGroup = D( 'auth_group' )->select();

			$_status = array( '0' => '禁用' , '1' => '正常' );

			foreach ( $authGroup as $key => $val ) {
				$authGroup[ $key ]['status'] = $_status[ $val['status'] ];
			}

			$this->assign( array(
				               'authGroup' => $authGroup ,
			               ) );
			return $this->fetch();
		}

		public function authEdit() {

			if ( IS_POST ) {

				$authGroup = D( 'auth_group' );
				$_data     = $authGroup->create();

				if ( empty( $_data['title'] ) ) {
					$this->ajaxError( '请输入权限组名！' );
				}
				if ( empty( $_data['name'] ) ) {
					$this->ajaxError( '请输入权限组标识名！' );
				}

				$_name = $authGroup->where( array( 'name' => $_data['name'] , 'id' => array( 'neq' , $_data['id'] ) ) )->field( 'id' )->select();

				if ( !empty( $_name ) ) {
					$this->ajaxError( '权限组已经存在！' );
				}

				$authGroup->save( $_data ) === false ? $this->ajaxError( '编辑失败！' ) : $this->ajaxSuccess( '编辑成功！' );

			} else {

				$id = I( 'get.id' );

				if ( empty( $id ) ) {
					$this->ajaxError( '用户不存在！' );
				}

				$_info = D( 'auth_group' )->where( array( 'id' => $id ) )->select();

				$this->assign( array( 'info' => $_info[0] ) );

				return $this->fetch( 'auth_edit' );
			}
		}


		public function authAdd() {


			if ( IS_POST ) {

				$authGroup = D( 'auth_group' );
				$_data     = $authGroup->create();

				if ( empty( $_data['title'] ) ) {
					$this->ajaxError( '请输入权限组名！' );
				}
				if ( empty( $_data['name'] ) ) {
					$this->ajaxError( '请输入权限组标识名！' );
				}

				$_name = $authGroup->where( array( 'name' => $_data['name'] ) )->field( 'id' )->select();

				if ( !empty( $_name ) ) {
					$this->ajaxError( '权限组已经存在！' );
				}
				unset( $_data['id'] );
				$authGroup->add( $_data ) === false ? $this->ajaxError( '添加失败！' ) : $this->ajaxSuccess( '添加成功！' );

			} else {
				$this->assign( 'info' , array() );
				return $this->fetch( 'auth_edit' );
			}

		}

		public function authDel() {

			if ( IS_POST ) {

				$id = I( 'post.' );

				if ( empty( $id ) ) {
					$this->ajaxError( '请选择权限组！' );
				}

				$id = implode( ',' , $id['id'] );

				$_menu = D( 'menu' )->where( array( 'auth_group' => array( 'in' , $id ) ) )->select();

				if ( !empty( $_menu ) ) {
					$this->ajaxError( '这些权限组下有菜单，请先删除那些菜单！' );
				}

				D( 'auth_group' )->where( array( 'id' => array( 'in' , $id ) ) )->delete() === false ? $this->ajaxError( '删除失败！' ) : $this->ajaxSuccess( '删除成功！' );

			} else {
				$id = I( 'get.id' );

				if ( empty( $id ) ) {
					$this->ajaxError( '权限组不存在！' );
				}
				$_menu = D( 'menu' )->where( array( 'auth_group' => $id ) )->select();

				if ( !empty( $_menu ) ) {
					$this->ajaxError( '此权限组下有菜单，请先删除此组下的菜单！' );
				}
				D( 'auth_group' )->delete( $id ) === false ? $this->ajaxError( '删除失败！' ) : $this->ajaxSuccess( '删除成功！' );

			}
		}

		public function authEnable() {

			if ( IS_POST ) {

				$id = I( 'post.' );

				if ( empty( $id ) ) {
					$this->ajaxError( '请选择权限组！' );
				}

				$id = implode( ',' , $id['id'] );

				$_data['status'] = 1;

				D( 'auth_group' )->where( array( 'id' => array( 'in' , $id ) ) )->save( $_data ) === false ? $this->ajaxError( '启用失败！' ) : $this->ajaxSuccess( '启用成功！' );

			} else {
				$this->ajaxError( '非法操作！' );
			}
		}

		public function authDisable() {

			if ( IS_POST ) {

				$id = I( 'post.' );

				if ( empty( $id ) ) {
					$this->ajaxError( '请选择权限组！' );
				}

				$id = implode( ',' , $id['id'] );

				$_data['status'] = 0;

				D( 'auth_group' )->where( array( 'id' => array( 'in' , $id ) ) )->save( $_data ) === false ? $this->ajaxError( '禁用失败！' ) : $this->ajaxSuccess( '禁用成功！' );


			} else {
				$this->ajaxError( '非法操作！' );
			}
		}


	}