<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/5/17
	 * Time: 18:18
	 */

	namespace app\admin\controller;

	class Verify {

		public function index(){

			$config =    array(
				'fontSize'    =>    100,
				'length'      =>    4,
				'useCurve'    =>    false,
				'bg'       => [191, 250, 222],
				'fontttf'  => '2.ttf',
			);
			$Verify = new \org\Verify($config);
			$Verify->entry();
		}

	}