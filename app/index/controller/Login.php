<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/5/23
	 * Time: 14:45
	 */


	namespace app\index\controller;

	use app\common\controller\Index as commonIndex;
	use think\ValidateNew;

	class Login extends commonIndex {

		protected $rule = array(
			'name'     => 'require' ,
			'password' => 'require' ,
			'verify'   => 'require' ,
		);

		protected $msg = array(
			'password.require' => '密码必须填写' ,
			'verify.require'   => '验证码必须填写' ,
			'name.require'     => '用户名必须填写' ,
		);


		public function login() {

			if ( IS_POST ) {

				$post = I( 'post.' );

				$validate = new ValidateNew( $this->rule , $this->msg );
				$result   = $validate->check( $post );

				if ( !$result ) {
					$this->ajaxTip( $validate->getError() );
				}

				if ( !check_verify( $post['verify'] ) ) {
					$this->ajaxTip( '验证码为空或不正确！' );
				}

				$userInfo = array();
				$data     = array( 'name' => $post['name'] );
				$check    = $validate->check( $data , array( 'name' => 'email' ) );
				if ( !$check ) {
					$userInfo = D( 'user' )->relation( 'user_info' )->where( array( 'name' => $post['name'] ) )->find();
				} else {
					$userInfo = D( 'user' )->relation( 'user_info' )->where( array( 'email' => $post['name'] ) )->find();
				}

				if ( empty( $userInfo ) ) {
					$this->ajaxTip( '该用户不存在！' );
				}

				if ( md5( $post['password'] ) !== $userInfo['password'] ) {
					$this->ajaxTip( '密码错误！' );
				}

				$this->userInfo = $userInfo;
				$sessionTable   = C( 'session.table' ) ? 'session' : C( 'session.table' );
				$sessionModel   = M( $sessionTable );

				cookie( 'indexSessionId' , encrypt( cookie( 'PHPSESSID' ) , parent::$cryptKey ) , 604800 );
				$session['session_id']     = cookie( 'PHPSESSID' );
				$session['data_key']       = 'indexUid';
				$session['session_expire'] = time() + 604800;
				$session['session_data']   = serialize( array( 'indexUid' => encrypt( $userInfo['uid'] , parent::$cryptKey ) ) );
				$sessionModel->add( $session );

				$this->ajaxSuccess( '登录成功！' , array( 'refresh' => '/' ) );
			} else {
				$userInfo = $this->checkLogin();
				if ( $userInfo === false ) {
					echo $this->fetch( 'public/login' );
					exit;
				} else {
					echo $this->success( '您已经登录过！' , '/' , true , 2 );
					exit;
				}

			}

		}


		public function logout() {
			$this->userInfo  = null;
			$this->userGroup = null;

			$sessionTable = C( 'session.table' ) ? 'session' : C( 'session.table' );
			$rs           = M( $sessionTable )->where( array( 'session_id' => decrypt( cookie( 'indexSessionId' ) , parent::$cryptKey ) , 'data_key' => 'indexUid' ) )->delete();
			if ( $rs !== false ) {
				cookie( 'indexSessionId' , null );
				$this->ajaxSuccess( '注销成功！' , array( 'refresh' => '/' ) );
			}
		}


	}