<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/3/11
	 * Time: 10:54
	 */

	namespace app\index\model;
	T( 'model/Auto' );

	class Course extends \think\Model {

		//获取视频分类
		public function getVideoCate( $id ) {
			$ids       = getChildIds( 'category' , $id );
			$map['id'] = array( 'in' , $ids );
			$cateInfo  = M( 'category' )->field('id,name,pid,tid,title,model,cataid')->where( $map )->select(array('index'=>'id'));
			$result = list2tree($cateInfo);
			return $result;
		}

	}