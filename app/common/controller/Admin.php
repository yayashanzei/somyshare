<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/8
	 * Time: 18:27
	 */
	namespace app\common\controller;

	use think\Controller;
	use think\Response;

	class Admin extends Controller {
		use Addons;

		protected static $cryptKey    = '-+[{somy68686809';
		protected static $splitString = '=_=_=';
		protected        $userInfo    = null;
		protected        $userGroup   = null;

		public function __construct() {
			parent::__construct();

			$this->assignBase();
			$userInfo = $this->checkLogin();

			if ( empty( $userInfo ) ) {
				$this->login();
			} else {
				$this->checkPower( $userInfo['uid'] , $userInfo );
			}

		}

		public function login() {

			if ( IS_POST ) {

				$userPost = I( 'post.' );

				if ( empty( $userPost['username'] ) || empty( $userPost['password'] ) ) {
					$this->ajaxTip( '用户名或密码不能为空！' );
				}

				$_userInfo = $_info = D( 'User' )->field( 'uid,name,password,salt' )->relation( 'user_info' )->where( array( 'name' => $userPost['username'] ) )->find();

				if ( $_userInfo === false ) {
					$this->ajaxTip( '用户名或者密码错误！' );
				}

				if ( $_userInfo['password'] !== md5( md5( $userPost['password'] ) . $_userInfo['salt'] ) ) {
					$this->ajaxTip( '用户名或者密码错误！' );
				}

				if ( !check_verify( $userPost['verify'] ) ) {
					$this->ajaxTip( '验证码为空或不正确！' );
				}

				$this->userInfo = $_userInfo;

				$sessionTable = C( 'session.table' ) ? 'session' : C( 'session.table' );
				$sessionModel = M( $sessionTable );
				$sessionModel->where( array( 'session_expire' => array( 'LT' , ( time() ) ) ) )->delete();

				cookie( 'adminSessionId' , encrypt( cookie( 'PHPSESSID' ) , self::$cryptKey ) , 1800 );
				$data['data_key']       = 'adminUid';
				$data['session_id']     = cookie( 'PHPSESSID' );
				$data['session_expire'] = time() + 1800;
				$data['session_data']   = serialize( array( 'adminUid' => encrypt( $_userInfo['uid'] , self::$cryptKey ) ) );
				$sessionModel->add( $data );

				$this->ajaxSuccess( '登录成功！' , array( 'refresh' => '/' ) );

			} else {
				echo $this->fetch( 'public/login' );
				exit;
			}
		}

		public function logout() {
			$this->userInfo  = null;
			$this->userGroup = null;

			$sessionTable = C( 'session.table' ) ? 'session' : C( 'session.table' );
			$rs           = M( $sessionTable )->where( array( 'session_id' => decrypt( cookie( 'adminSessionId' ) , self::$cryptKey ) , 'data_key' => 'adminUid' ) )->delete();

			if ( $rs !== false ) {
				cookie( 'adminSessionId' , null );
				$this->ajaxSuccess( '注销成功！' , array( 'refresh' => '/admin' ) );
			}
		}

		public function checkLogin() {

			$sessionTable   = C( 'session.table' ) ? 'session' : C( 'session.table' );
			$adminSessionId = decrypt( cookie( 'adminSessionId' ) , self::$cryptKey );
			$sessionModel   = M( $sessionTable );
			$sessionUser    = $sessionModel->where( array( 'session_id' => $adminSessionId , 'data_key' => 'adminUid' ) )->find();

			if ( empty( $sessionUser ) || $sessionUser['session_expire'] < time() ) {
				return false;
			} else {

				$map['id']              = $sessionUser['id'];
				$map['session_id']      = $adminSessionId;
				$data['session_expire'] = time() + 1800;
				$sessionModel->where( $map )->save( $data );
				cookie( 'adminSessionId' , encrypt( $adminSessionId , self::$cryptKey ) , 1800 );

				$adminUid = unserialize( $sessionUser['session_data'] );
				if ( !empty( $adminUid['adminUid'] ) ) {
					$userInfo = D( 'user' )->relation( 'user_info' )->where( array( 'uid' => decrypt( $adminUid['adminUid'] , self::$cryptKey ) ) )->find();
					if ( !empty( $userInfo ) ) {
						$this->userInfo = $userInfo;
					}
				}
				return $this->userInfo;
			}
		}


		public function checkPower( $uid , $userInfo ) {
			if ( empty( $uid ) ) {
				$this->login();
			}

			$power = null;

			if ( empty( $userInfo ) ) {
				$userInfo = D( 'user' )->relation( 'user_info' )->where( array( 'uid' => $uid ) )->find();
			}

			$userGroup = M( 'user_group' )->where( array( 'id' => $userInfo['groupid'] ) )->find();

			if ( !empty( $userGroup ) && !empty( $userGroup['group_auth'] ) ) {
				$groupAuth = unserialize( $userGroup['group_auth'] );
			}

			$buttonId = cookie( 'buttonId' );

			$power = $this->specialButton( $buttonId );

			if ( !empty( $groupAuth ) ) {
				foreach ( $groupAuth as $key => $val ) {
					if ( $key == $buttonId || isset( $val[ $buttonId ] ) ) {
						$power = 1;
						break;
					}
				}
			}

			if ( $power !== 1 ) {
				$this->ajaxTip( '您无权进行此操作！' );
			}

			$this->userInfo  = $userInfo;
			$this->userGroup = $userGroup;
		}

		public function specialButton( $buttonId = null ) {

			$special = array( 'r-l' => 0 , 'r-d' => 1 , 'login' => 2 , 'logout' => 3 );
			if ( empty( $buttonId ) ) {
				return 1;
			}
			if ( array_key_exists( $buttonId , $special ) ) {
				return 1;
			}

			return null;
		}

		public function assignBase() {
			$common      = _COMMON;
			$css         = _CSS;
			$js          = _JS;
			$static_base = <<<static
	<script type="text/javascript" src="{$common}/js/jquery-1.12.3.min.js"></script>

	<link rel="stylesheet" href="{$common}/pintuer/css/pintuer.css">
    <script type="text/javascript" src="{$common}/pintuer/js/pintuer.js"></script>
    <script type="text/javascript" type="text/javascript" src="{$common}/layer/layer.js"></script>
    <link rel="stylesheet" href="{$css}/admin.css">
     <script type="text/javascript" src="{$common}/js/respond.js"></script>
    <script src="{$js}/admin.js"></script>
static;
			$this->assign( 'static_base' , $static_base );
		}


		public function getSider() {

			$buttonId = cookie( 'buttonId' );

			if ( empty( $buttonId ) ) {
				self::ajaxTip( '非法操作！' );
			}

			$side = M( 'menu' )->select( array( 'index' => 'id' ) );

			$sider = array();

			if ( !empty( $side ) ) {
				foreach ( $side as $key => $value ) {
					if ( isset( $side[ $value['pid'] ] ) ) {
						if ( $key == 70 || $value['title'] == '内容管理' ) {
							$cateSider =  &$side[ $value['id'] ];
						}
						$side[ $value['pid'] ]['_'][ $key ] = &$side[ $value['id'] ];
					}
					if ( $key == $buttonId ) {
						$sider = &$side[ $value['id'] ];
					}
				}

			}
			$cate = M( 'category' )->field( 'id,name,title,model,icon' )->where( array( 'pid' => 0 , 'display' => array( 'NEQ' , 0 ) ) )->select( array( 'index' => 'id' ) );

			foreach ( $cate as $key => $val ) {
				$cate[ $key ]['id']        = 'cate' . $val['id'];
				$cate[ $key ]['url']       = 'article/channel?id=' . $val['id'];
				$cate[ $key ]['get_param'] = 'id';
				$cate[ $key ]['group']     = '2';
				$cate[ $key ]['_']         = $cateSider['_'];
			}

			$cateSider['_'] = $cate;

			$this->assign( 'sider' , $sider );
			return $this->fetch( 'public/getsider' );

		}


		public function getButtons() {

			$buttonId = cookie( 'buttonId' );

			if ( empty( $buttonId ) ) {
				self::ajaxTip( '非法操作！' );
			}

			if ( $buttonId == 'r-l' ) {
				$l_con_bt = explode( self::$splitString , cookie( 'history_l_con_btn' ) );
				$buttonId = $l_con_bt[2];
			}

			$side = M( 'menu' )->select( array( 'index' => 'id' ) );

			$buttons = array();

			if ( !empty( $side ) ) {
				foreach ( $side as $key => $value ) {
					if ( isset( $side[ $value['pid'] ] ) ) {
						if ( $key == 70 || $value['title'] == '内容管理' ) {
							$cateSider =  &$side[ $value['id'] ];
						}
						$side[ $value['pid'] ]['_'][ $key ] = &$side[ $value['id'] ];
					}
					if ( $key == $buttonId ) {
						$buttons = &$side[ $value['id'] ];
					}
				}
			}

			if ( substr( $buttonId , 0 , 4 ) == 'cate' ) {
				$buttons['_'] = $cateSider['_'];
			}


			$this->assign( 'buttons' , $buttons );

		}


		public function ajaxSuccess( $content , array $option = array() ) {

			!$content ? $content = '操作成功！' : true;

			$_config = array(
				'ask'   => 98 ,
				'title' => false ,
				'time'  => 2000 ,
				'icon'  => 6 ,
			);

			$_config['content'] = $content;
			!empty( $option ) ? $_config = array_merge( $_config , $option ) : true;

			Response::send( $_config , 'json' );
			exit;
		}

		public function ajaxError( $content , array $option = array() ) {
			!$content ? $content = '操作失败！' : true;

			$_config = array(
				'ask'   => 98 ,
				'title' => false ,
				'time'  => 2000 ,
				'icon'  => 5 ,
			);

			$_config['content'] = $content;
			!empty( $option ) ? $_config = array_merge( $_config , $option ) : true;

			Response::send( $_config , 'json' );
			exit;
		}


		public function ajaxTip( $content , array $option = array() ) {
			!$content ? $content = '操作失败！' : true;

			$_config = array(
				'ask'     => 97 ,
				'title'   => false ,
				'time'    => 1500 ,
				'icon'    => 0 ,
				'shift'   => 4 ,
				'sortBox' => '#sort_list' ,
			);

			$_config['content'] = $content;
			!empty( $option ) ? $_config = array_merge( $_config , $option ) : true;

			Response::send( $_config , 'json' );
			exit;
		}

	}


