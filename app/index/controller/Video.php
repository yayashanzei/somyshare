<?php
	namespace app\index\controller;

	use think\Controller;
	use app\common\controller\Index as commonIndex;

	class Video extends commonIndex {

		public function index() {
			$id            = I( 'get.id' );
			$map['id']     = $id;
			$map['status'] = 1;
			$video         = M( 'video' )->where( $map )->find();

			$status = '';
			if ( empty( $video ) ) {
				$status = '该视频暂未通过审核！';
			} else {
				M( 'video' )->save( array( 'views' => $video['views'] + 1 ) );
			}

			$this->assign( array(
				               'video'  => $video ,
				               'status' => $status ,
			               ) );
			return $this->fetch();
		}

	}
