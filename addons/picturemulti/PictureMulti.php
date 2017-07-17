<?php

	namespace addons\picturemulti;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 上传多张图片插件
	 * @author
	 */
	class PictureMulti extends Controller {
		use Addons;
		public $info = array(
			'name'        => 'PictureMulti' ,
			'title'       => '上传多张图片' ,
			'description' => '上传多张图片功能插件' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);

		private static $static_plupload = 1;

		//实现的picture_multi钩子方法
		public function pictureMulti( $param ) {
			$config          = $this->getConfig();
			$common          = _COMMON;
			$static_plupload = null;
			if ( self::$static_plupload == 1 ) {
				$static_plupload       = <<<static
	<script type="text/javascript" charset="utf-8" src="{$common}/plupload/plupload.full.min.js"></script>
static;
				self::$static_plupload = 2;
			}
			$this->assign( 'static_plupload' , $static_plupload );
			$this->echoAddons( 'picturemulti' . DS . 'pic.html' );
		}
	}