<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/7
	 * Time: 13:59
	 */

	namespace app\admin\model;
	T( 'model/Auto' );

	class Article extends \think\Model {
		use \traits\model\Auto;
		//自动验证
		protected $validate = array(
			array( 'title' , 'require' , '标题必须填写' ) ,
		);

		//自动完成
		protected $_auto = array(
			array( 'title' , 'htmlspecialchars' , self::MODEL_BOTH , 'function' ) ,
		);

		//获取模型字段根据分类id
		public function getFieldByCid( $cid , $field = 'name' ) {
			$model      = M( 'category' )->where( array( 'id' => $cid ) )->getField( 'model' );
			$modelField = M( 'model' )->where( array( 'id' => $model ) )->getField( $field );
			return $modelField;
		}

		//获取视频分类
		public function getVideoCate( $id ) {
			$ids       = getChildAllId( $id );
			$map['id'] = array( 'in' , $ids );
			$cateInfo  = M( 'category' )->field( 'id,name,pid,tid,title,model,cataid' )->where( $map )->select( array( 'index' => 'id' ) );
			$result    = D( 'common/Tree' )->toFormatTree( $cateInfo );
			return $result;
		}

		//获取保利威视单个视频信息
		public function getVideoInfo() {

		}


	}