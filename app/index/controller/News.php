<?php
namespace app\index\controller;
use think\Controller;
use app\common\controller\Index as commonIndex;

class News extends commonIndex
{

	public function __construct() {
		parent::__construct();

		//获取新闻的分类
		$ids       = getChildIds( 'category' , 15);
		$map['id'] = array( 'in' , $ids );
		$cateInfo  = M( 'category' )->field('id,name,pid,tid,title,model')->where( $map )->select(array('index'=>'id'));
		$newsCate = list2tree($cateInfo);
		$this->assign('newsCate',$newsCate);
	}

    public function index()
    {
	    //正常所有文章
	    $article = M('news')->where(array('status'=> 1))->order('create_time desc')->select();
	    $this->assign('article',$article);

	    return $this->fetch();
    }

	public function article(){

		$id = I('get.id');
		$map['id'] = $id;
		$map['status'] = 1;
		$article = M('news')->where($map)->find();

		M('news')->save(array('views'=> $article['views'] + 1));

		$this->assign('article',$article);
		return $this->fetch();
	}

	public function category(){

		$id = I('get.id');
		$map['category_id'] = $id;
		$map['status'] = 1;
		$article = M('news')->where($map)->order('create_time desc')->select();
		$this->assign('article',$article);
		return $this->fetch('list');
	}

}
