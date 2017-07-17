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
	//T('controller/Jump');


	class Register extends commonIndex {
		//use \traits\controller\Jump;
		protected $rule = array(
			'email'    => 'email' ,
			'password' => 'require|min:6' ,
			'verify'   => 'require' ,
		);

		protected $msg = array(
			'password.require' => '密码必须填写' ,
			'verify.require'   => '验证码必须填写' ,
			'password.min'     => '密码最少6位' ,
			'email'            => '邮箱格式错误' ,
		);

		public function register() {

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

				unset( $post['verify'] );

				$emailExists = M('user')->where(array('email'=>$post['email']))->find();

				if(!empty($emailExists)){
					$this->ajaxTip('该邮箱已使用，请更换邮箱！');
				}

				$post['password']   = md5( $post['password'] );
				$post['user_info'] = array(
					'groupid' => 3 ,
					'regtime' => substr( microtime() , -10 ) ,
				);

				$rs = D( 'User' )->relation( 'user_info' )->add( $post );

				if ( $rs === false ) {
					$this->ajaxError( '注册失败！' , array( 'refresh' => '/register.html' ) );
				} else {
					$this->ajaxSuccess( '注册成功！' , array( 'refresh' => '/' ) );
				}


			} else {

				return $this->fetch( 'public/register' );
			}

		}

	}