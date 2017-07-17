<?php
namespace app\index\controller;
use org\Upload;
use think\Controller;
use app\common\controller\Index as commonIndex;

class Course extends commonIndex
{
	public function __construct() {
		parent::__construct();

		//获取视频的分类
		$videoCate = D('Course')->getVideoCate(1);
		$this->assign('videoCate',$videoCate);
	}

    public function index()
    {
	    //正常所有视频课程
	    $map['status'] = 1;
	    $course = M('course')->where($map)->select();
	    $this->assign('course',$course);

	    return $this->fetch();
    }

	public function learn(){
		$id = I('get.id');
		$map['id'] = $id;
		$map['status'] = 1;

		$data['cid'] = $id;
		$data['status'] = 1;

		$course = M('course')->where($map)->find();
		$video = M('video')->where($data)->select();
		$this->assign('video',$video);
		$this->assign('course',$course);
		return $this->fetch();
	}

	public function category(){

		$id = I('get.id');
		$map['category_id'] = $id;
		$map['status'] = 1;
		$course = M('course')->where($map)->select();
		$this->assign('course',$course);
		return $this->fetch('list');
	}

}
