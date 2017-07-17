<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/5/17
	 * Time: 18:18
	 */

	namespace app\index\controller;

	class Verify {

		public function index(){

			$config =    array(
				'fontSize'    =>    18,
				'length'      =>    4,
				'useCurve'    =>    false,
				'useNoise' => false,
				'imageW'=>140,
				'imageH'=>36,
				'bg'       => [255, 255, 255], // 背景颜色
				'fontttf'  => '2.ttf',
			);
			$Verify = new \org\Verify($config);
			$Verify->entry();
		}

	}