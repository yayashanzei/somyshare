<?php
	// +----------------------------------------------------------------------
	// | ThinkPHP [ WE CAN DO IT JUST THINK ]
	// +----------------------------------------------------------------------
	// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
	// +----------------------------------------------------------------------
	// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
	// +----------------------------------------------------------------------
	// | Author: liu21st <liu21st@gmail.com>
	// +----------------------------------------------------------------------

	// 应用入口文件

	// 定义项目路径
	define( 'APP_PATH' , 'app' . DIRECTORY_SEPARATOR );
	// 开启调试模式
	define( 'APP_DEBUG' , true );
	// 定义运行时目录
	define( 'RUNTIME_PATH' , 'runtime' . DIRECTORY_SEPARATOR );
	// 定义插件目录
	define( 'ADDONS_PATH' , 'addons' . DIRECTORY_SEPARATOR );
	//定义方件图片上传目录
	define( 'UPLOAD_PATH' , 'upload' . DIRECTORY_SEPARATOR );

	define( 'APP_HOOK' , true );
	define( 'APP_ROOT' , __DIR__ );

	// 加载框架引导文件
	require 'core' . DIRECTORY_SEPARATOR . 'start.php';



