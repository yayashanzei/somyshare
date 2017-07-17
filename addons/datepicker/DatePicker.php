<?php

	namespace addons\datepicker;

	use app\common\controller\Addons;
	use think\Controller;

	/**
	 * 日期选择器插件
	 * @author
	 */
	class DatePicker extends Controller {
		use Addons;
		public $info = array(
			'name'        => 'DatePicker' ,
			'title'       => '日期选择器' ,
			'description' => '日期选择功能的插件' ,
			'status'      => 1 ,
			'author'      => '' ,
			'version'     => '' ,
		);

		private static $static_WdatePicker = 1;

		//实现的datepicker钩子方法
		public function datePicker( $params ) {
			// type 0 日期/ 1 时间/ 2 日期带时间/ 3 日期区间/ 4 时间区间/ 5 酒店飞机票限制天数日期区间/ 6 日期区间带时间
			if ( empty( $params ) ) {
				$params = array( 'type' => 0 );
			}

			if ( empty( $params['create_time'] ) ) {
				$params['create_time'] = time();
			}

			$common             = _COMMON;
			$static_WdatePicker = null;
			if ( self::$static_WdatePicker == 1 ) {
				$static_WdatePicker       = <<<static
	<script type="text/javascript" src="{$common}/datepicker/WdatePicker.js"></script>
static;
				self::$static_WdatePicker = 2;
			}

			$this->assign( array(
				               'date'               => $params ,
				               'date2'              => $params ,
				               'static_WdatePicker' => $static_WdatePicker ,
			               ) );

			$this->echoAddons( 'datepicker' . DS . 'date.html' );

		}
	}