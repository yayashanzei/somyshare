<?php

	namespace addons\picture;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 上传图片插件
	 * @author
	 */
	class Picture extends Controller {

		use Addons;
		public $info = array(
			'name'        => 'Picture' ,
			'title'       => '上传图片' ,
			'description' => '上传图片功能插件' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);

		private static $static_plupload = 1;

		//实现的picture钩子方法
		public function picture( $param ) {

			$config          = $this->getConfig();
			$common          = _COMMON;
			$static_plupload = null;
			if ( self::$static_plupload == 1 ) {
				$static_plupload       = <<<static
	<script type="text/javascript" charset="utf-8" src="{$common}/plupload/plupload.full.min.js"></script>
static;
				self::$static_plupload = 2;
			}
			$this->assign( array(
				               'pic'             => $param ,
				               'static_plupload' => $static_plupload ,
			               ) );
			$this->echoAddons( 'picture' . DS . 'pic.html' );
		}

	}