<?php
	/**
	 * Created by PhpStorm.
	 * User: icebr:ice_br2046@163.com
	 * Date: 2016/3/11
	 * Time: 10:54
	 */

	namespace app\index\model;
	T( 'model/Auto' );

	class Video extends \think\Model {

		//��ȡ��Ƶ����
		public function getVideoCate( $id ) {
			$ids       = getChildIds( 'category' , $id );
			$map['id'] = array( 'in' , $ids );
			$cateInfo  = M( 'category' )->field('id,name,pid,tid,title,model,cataid')->where( $map )->select(array('index'=>'id'));
			$result = list2tree($cateInfo);
			return $result;
		}

		//��ȡ��Ƶ�����б���Ϣ
		public function getPlayList() {
			$readtoken = 'dec8c822-1906-4e0e-aee8-6cea2ae5c160';
			$result    = file_get_contents( 'http://v.polyv.net/uc/services/rest?method=getPlayList&readtoken=' . $readtoken.'&pageNum=1' );
			return json_decode( $result );
		}

		//��ȡ������Ƶ�б�
		public function getHotList(){
			$readtoken = 'dec8c822-1906-4e0e-aee8-6cea2ae5c160';
			$result    = file_get_contents( 'http://v.polyv.net/uc/services/rest?method=getHotList&readtoken=' . $readtoken.'&pageNum=1' );
			return json_decode( $result );
		}

		//��ȡ������Ƶ�б�
		public function getNewList(){
			$readtoken = 'dec8c822-1906-4e0e-aee8-6cea2ae5c160';
			$result    = file_get_contents( 'http://v.polyv.net/uc/services/rest?method=getNewList&readtoken=' . $readtoken.'&pageNum=1' );
			return json_decode( $result );
		}

		//��ȡ���������б�
		public function getPlayListById($id){
			$readtoken = 'dec8c822-1906-4e0e-aee8-6cea2ae5c160';
			$result    = file_get_contents( 'http://v.polyv.net/uc/services/rest?method=getPlayListById&readtoken=' . $readtoken.'&id='.$id );
			return json_decode( $result );
		}

		//��ȡ������Ƶ����Ϣ
		public function getById($vid){
			$readtoken = 'dec8c822-1906-4e0e-aee8-6cea2ae5c160';
			$result    = file_get_contents( 'http://v.polyv.net/uc/services/rest?method=getById&readtoken=' . $readtoken.'&vid='.$vid );
			return json_decode( $result );
		}

		//��ȡ�����Ƶ��ʱ���ʹ�С
		public function getvidsInfo($vids){
			$result    = file_get_contents( 'http://v.polyv.net/uc/video/info?vids='.$vids );
			return json_decode( $result );
		}

		//����ǩ������Ƶ
		public function searchByTag($tag){
			$readtoken = 'dec8c822-1906-4e0e-aee8-6cea2ae5c160';
			$result    = file_get_contents( 'http://v.polyv.net/uc/services/rest?method=searchByTag&readtoken=' . $readtoken.'&tag='.$tag );
			return json_decode( $result );
		}

	}