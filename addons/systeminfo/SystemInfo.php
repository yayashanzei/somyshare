<?php

	namespace addons\systeminfo;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 服务器环境信息插件
	 * @author
	 */
	class SystemInfo extends Controller {
		use Addons;
		public $info = array(
			'name'        => 'SystemInfo' ,
			'title'       => '环境信息' ,
			'description' => '用于后台首页的服务器环境信息' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);


		//实现的environment 钩子方法
		public function systemInfo( $param ) {
			$config = $this->getConfig();
			$this->assign( 'config' , $config );

			if ( !empty( $config['sys_display']['value'] ) || !empty( $config['data_display']['value'] ) ) {
				$this->echoAddons( 'systeminfo' . DS . 'system_info.html' );
			}
		}
	}