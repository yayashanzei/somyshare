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


	class Index extends Controller {

		use Addons;
		protected static $cryptKey    = '-+[{somy68686809';
		protected static $splitString = '=_=_=';
		protected        $userInfo    = null;
		protected        $userGroup   = null;

		public function __construct() {
			parent::__construct();

			$this->assignBase();
			$this->checkLogin();

			//导航菜单
			$nav = M( 'nav' )->field( 'title,url' )->where( array( 'status' => 1 ) )->order( 'sort asc' )->select();
			$this->assign( array(
				               'nav'      => $nav ,
				               'userInfo' => $this->userInfo ,
			               ) );

		}


		public function checkLogin() {
			$sessionTable   = C( 'session.table' ) ? 'session' : C( 'session.table' );
			$indexSessionId = decrypt( cookie( 'indexSessionId' ) , self::$cryptKey );
			$sessionModel   = M( $sessionTable );
			$sessionUser    = $sessionModel->where( array( 'session_id' => $indexSessionId , 'data_key' => 'indexUid' ) )->find();

			if ( empty( $sessionUser ) || $sessionUser['session_expire'] < time() ) {
				return false;
			} else {
				$indexUid = unserialize( $sessionUser['session_data'] );
				$userInfo = D( 'user' )->relation( 'user_info' )->where( array( 'uid' => decrypt( $indexUid['indexUid'] , self::$cryptKey ) ) )->find();
				if ( !empty( $userInfo ) ) {
					$this->userInfo = $userInfo;
					if ( !empty( $this->userInfo['avatar'] ) ) {
						$avatar   = M( 'avatar' )->where( array( 'id' => $this->userInfo['avatar'] ) )->field( 'path' )->find();
						$pathInfo = pathinfo( $avatar['path'] );
						if ( isset( $pathInfo['dirname'] ) ) {
							$this->userInfo['avatarPath'] = $avatar['path'];
							$this->userInfo['avatarPic'] = $pathInfo['dirname'] . '/crop_' . $pathInfo['basename'];
						}
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
    <link rel="stylesheet" href="{$css}/index.css">
    <script type="text/javascript" src="{$common}/js/respond.js"></script>
static;
			$this->assign( 'static_base' , $static_base );
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
				'ask'      => 97 ,
				'title'    => false ,
				'time'     => 1500 ,
				'icon'     => 0 ,
				'shift'    => 4 ,
				'callback' => false ,
				'sortBox'  => '#sort_list' ,
			);

			$_config['content'] = $content;
			!empty( $option ) ? $_config = array_merge( $_config , $option ) : true;

			Response::send( $_config , 'json' );
			exit;
		}


	}
