<?php

	namespace addons\editorforadmin;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 后台编辑器插件
	 * @author
	 */
	class EditorForAdmin extends Controller{

		use Addons;
		public $info = array(
			'name'        => 'EditorForAdmin' ,
			'title'       => '后台编辑器' ,
			'description' => '后台富文本编辑器' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);

		private static $static_ueditor = 1;

		
		//实现的adminArticleEdit钩子方法
		public function adminArticleEdit( $param ) {
			$common         = _COMMON;
			$static_ueditor = null;

			if ( self::$static_ueditor == 1 ) {
				$static_ueditor       = <<<static
	 <script type="text/javascript" charset="utf-8">window.UEDITOR_HOME_URL = "{$common}/ueditor/";</script>
    <script type="text/javascript" charset="utf-8" src="{$common}/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="{$common}/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="{$common}/ueditor/lang/zh-cn/zh-cn.js"></script>

static;
				self::$static_ueditor = 2;
			}
			$this->assign( array(
				               'edit'           => $param ,
				               'static_ueditor' => $static_ueditor ,
			               ) );
			$this->echoAddons( 'editorforadmin' . DS . 'editor.html' );
		}

	}