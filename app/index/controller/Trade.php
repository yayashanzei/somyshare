<?php
namespace app\index\controller;
use org\Upload;
use think\Controller;
use app\common\controller\Index as commonIndex;

class Trade extends commonIndex
{

	public function __construct() {
		parent::__construct();

		//获取外贸干货的分类
		$ids       = getChildIds( 'category' , 2);
		$map['id'] = array( 'in' , $ids );
		$cateInfo  = M( 'category' )->field('id,name,pid,tid,title,model')->where( $map )->select(array('index'=>'id'));
		$tradeCate = list2tree($cateInfo);
		$this->assign('tradeCate',$tradeCate);
	}

    public function index()
    {
	    //正常所有文章
	    $article = M('article')->where(array('status'=> 1))->order('create_time desc')->select();
	    $this->assign('article',$article);
	    return $this->fetch();
    }

	public function article(){
		$id = I('get.id');
		$map['id'] = $id;
		$map['status'] = 1;
		$article = M('article')->where($map)->find();
		M('article')->save(array('views'=> $article['views'] + 1));

		$this->assign('article',$article);
		return $this->fetch();
	}

	public function category(){

		$id = I('get.id');
		$map['category_id'] = $id;
		$map['status'] = 1;
		$article = M('article')->where($map)->order('create_time desc')->select();
		$this->assign('article',$article);
		return $this->fetch('list');
	}

}
