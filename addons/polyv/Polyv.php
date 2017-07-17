<?php

	namespace addons\polyv;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 上传视频插件
	 * @author
	 */
	class Polyv extends Controller {
		use Addons;
		public         $info            = array(
			'name'        => 'Polyv' ,
			'title'       => '上传视频' ,
			'description' => '上传视频的插件' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);
		private static $static_plupload = 1;

		//实现的polyv钩子方法
		public function polyv( $param ) {
			if ( empty( $params['create_time'] ) ) {
				$params['create_time'] = time();
			}
			$common          = _COMMON;
			$static_plupload = null;
			if ( self::$static_plupload == 1 ) {
				$static_plupload       = <<<static
	<script type="text/javascript" charset="utf-8" src="{$common}/plupload/plupload.full.min.js"></script>
static;
				self::$static_plupload = 2;
			}
			$this->assign( array(
				               'pol'             => $param ,
				               'static_plupload' => $static_plupload ,
			               ) );
			$this->echoAddons( 'polyv' . DS . 'polyv.html' );
		}

		//获取保利威视视频分类信息
		public function getVideoCate() {
			$readtoken = 'dec8c822-1906-4e0e-aee8-6cea2ae5c160';
			$result    = file_get_contents( 'http://v.polyv.net/uc/services/rest?method=getCata&readtoken=' . $readtoken );
			return json_decode( $result );
		}
	}