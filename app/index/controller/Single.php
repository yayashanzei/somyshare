<?php
namespace app\index\controller;
use org\Upload;
use think\Controller;
use app\common\controller\Index as commonIndex;

class Single extends commonIndex
{
	public function __construct() {
		parent::__construct();

		//获取新闻的分类
		$ids       = getChildIds( 'category' , 5);
		$map['id'] = array( 'in' , $ids );
		$cateInfo  = M( 'category' )->field('id,name,pid,tid,title,model')->where( $map )->select(array('index'=>'id'));
		$singleCate = list2tree($cateInfo);
		$this->assign('singleCate',$singleCate);
	}

    public function index()
    {
		$name = I('get.name');
	    $map['name'] = $name;
	    $map['status'] = 1;
	    $single = M('single')->field('id,title,name,content,description,create_time,views')->where($map)->find();
	    M('single')->save(array('views'=> $single['views'] + 1));

	    $this->assign('single',$single);

	    return $this->fetch();
    }

}
