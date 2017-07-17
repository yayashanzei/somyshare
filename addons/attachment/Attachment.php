<?php

	namespace addons\attachment;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 上传附件插件
	 * @author
	 */
	class Attachment extends Controller {
		use Addons;
		public $info = array(
			'name'        => 'Attachment' ,
			'title'       => '上传附件' ,
			'description' => '上传单个附件的功能插件' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);

		//实现的attachment钩子方法
		public function attachment( $param ) {
			$config = $this->getConfig();
			$this->echoAddons( 'attachment' . DS . 'attchment.html' );
		}
	}