<?php
	return [
		'nbsharer'        => 'nbsharer/index' ,
		'course/:id'      => 'course/category' ,
		'course'          => 'course/index' ,
		'learn/:id'       => 'course/learn' ,
		'video/:id'       => 'video/index' ,
		'news/:id'        => 'news/category' ,
		'new/:id'         => 'news/article' ,
		'news'            => 'news/index' ,
		'trade/:id'       => 'trade/category' ,
		'trade'           => 'trade/index' ,
		'register'        => 'register/register' ,
		'login'           => 'login/login' ,
		'logout'          => 'login/logout' ,
		'member$'          => 'member/center' ,
		'member/avatar$'   => 'member/avatar' ,
		'member/password$' => 'member/password' ,
		'member/userinfo$' => 'member/userInfo' ,
		'member/myshare$'  => 'member/myShare' ,
		'member/addshare$' => 'member/addShare',
		'member/editshare$' => 'member/editShare',
		'member/delshare$' => 'member/delShare',

		'article/:id'     => 'trade/article' ,
		'about/:name'     => 'single/index' ,
	];