<?php

	namespace addons\attachmentmulti;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 上传多个附件插件
	 * @author
	 */
	class AttachmentMulti extends Controller {
		use Addons;
		public $info = array(
			'name'        => 'AttachmentMulti' ,
			'title'       => '上传多个附件' ,
			'description' => '上传多个附件的功能插件' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);

		//实现的attachmentmulti钩子方法
		public function attachmentMulti( $param ) {
			$config = $this->getConfig();
			$this->echoAddons( 'attachmentmulti' . DS . 'attchment.html' );
		}
	}