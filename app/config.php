<?php
	// +----------------------------------------------------------------------
	// | ThinkPHP [ WE CAN DO IT JUST THINK ]
	// +----------------------------------------------------------------------
	// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
	// +----------------------------------------------------------------------
	// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
	// +----------------------------------------------------------------------
	// | Author: liu21st <liu21st@gmail.com>
	// +----------------------------------------------------------------------
	// $Id$

	return [
		'url_route_on'      => true ,   //启用路由
		'url_domain_deploy' => true ,  //启用域名绑定
		'base_url'          => '' ,

		//'default_return_type'=>'json',  //
		'extra_config_list' => [ 'database' , 'route' , 'tags' ] ,

		'session'           => [
			'id'             => '' ,
			'expire'         => 1800 ,    // 有效期(秒)
			'table'          => 'session' ,
			'var_session_id' => '' , // SESSION_ID的提交变量,解决flash上传跨域
			'prefix'         => 'disk' ,
			'auto_start'     => true ,
		] ,

	];
