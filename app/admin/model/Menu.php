<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/7
	 * Time: 13:59
	 */

	namespace app\admin\model;
	T( 'model/Auto' );

	class Menu extends \think\Model {
		use \traits\model\Auto;
		//自动验证
		protected $validate = array(
			array( 'sort' , 'number' , '排序只能为数字' ) ,
			array( 'title' , 'require' , '标题必须填写' ) ,
			array( 'url' , 'require' , '链接必须填写' ) ,
		);

		//自动完成
		protected $_auto = array(
			array( 'title' , 'htmlspecialchars' , self::MODEL_BOTH , 'function' ) ,
			array( 'hide' , '0' , self::MODEL_INSERT ) ,
			array( 'sort' , '0' , self::MODEL_INSERT ) ,
		);

	}