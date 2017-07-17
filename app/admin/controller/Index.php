<?php
	namespace app\admin\controller;

	use app\common\controller\Admin;

	class Index extends Admin {

		private static $static_nicescroll = 1;

		public function index() {
			$topSider = M( 'menu' )->where( array( 'group' => 1 ) )->select();


			$common            = _COMMON;
			$static_nicescroll = null;
			if ( self::$static_nicescroll == 1 ) {
				$static_nicescroll       = <<<static
	<script type="text/javascript" src="{$common}/nicescroll/jquery.nicescroll.min.js"></script>
static;
				self::$static_nicescroll = 2;
			}

			$this->assign( array(
				               'topSider'          => $topSider ,
				               'userInfo'          => $this->userInfo ,
				               'userGroup'         => $this->userGroup ,
				               'static_nicescroll' => $static_nicescroll ,
			               ) );

			return $this->fetch();
		}

		public function info() {

			return $this->fetch();
		}

	}
