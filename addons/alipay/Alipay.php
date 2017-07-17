<?php

	namespace addons\alipay;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 支付宝插件
	 * @author
	 */
	class Alipay extends Controller {
		use Addons;
		public $info = array(
			'name'        => 'Alipay' ,
			'title'       => '支付宝' ,
			'description' => '支付宝功能插件' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);


		//实现的alipay钩子方法
		public function alipay( $param ) {

		}
	}