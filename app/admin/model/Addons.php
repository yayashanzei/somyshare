<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/7
	 * Time: 13:59
	 */

	namespace app\admin\model;
	T( 'model/Auto' );

	class Addons extends \think\Model {
		use \traits\model\Auto;
		//自动验证
		protected $validate = array(//array( 'sort' , 'number' , '排序只能为数字' ) ,
		);

		//自动完成
		protected $_auto = array(//array( 'sort' , '0' , self::MODEL_INSERT ) ,
		);

		//获取插件列表
		public function getList() {

			$_lists = array();

			$lists = D( 'Addons' )->field( 'id,name,status' )->select();

			foreach ( $lists as $key => $val ) {
				$_lists[ strtolower( $val['name'] ) ] = $val;
			}

			S( 'hooks' , null );
			return $_lists;
		}

		//检测要创建的插件标识是否重复
		public function checkName( $name ) {
			$addon_dir = ADDONS_PATH;
			$dirs      = array_map( 'basename' , glob( $addon_dir . "*" , GLOB_ONLYDIR ) );
			if ( in_array( $name , $dirs ) ) {
				$Admin = A( 'common/Admin' );
				$Admin->ajaxTip( "要创建的插件标识已存在！" );
			}
		}
	}