<?php
namespace app\index\controller;
use org\Upload;
use think\Controller;
use app\common\controller\Index as commonIndex;

class Nbsharer extends commonIndex
{
    public function index()
    {
	    $map['status'] = 1;

	    $video = M('video')->field('id,title,create_time,uid')->where($map)->order('create_time desc')->limit(10)->select();

	    foreach($video as $k => $v ){
			$video[$k]['type'] = "视频";
			$video[$k]['url'] = '/video/'.$v['id'];
		}

	    $article = M('article')->field('id,title,create_time,uid')->where($map)->order('create_time desc')->limit(10)->select();
	    foreach($article as $k => $v ){
		    $article[$k]['type'] = "干货";
		    $article[$k]['url'] = '/article/'.$v['id'];
	    }

	    $results = array_merge($video,$article);

	    $result = $share = array();
	    foreach($results as $k => $v){
		    $result[$v['create_time']] = $v;
	    }

		krsort($result);

	    foreach($result as $k => $v ){
		    $share[] = $v;
	    }

	    //最新会员
	    $map['status'] = 3;
	    $newMember = M('user')->where($map)->select();
		foreach($newMember as $k => $v){
			if(empty($newMember[$k]['name'])){
				$newMember[$k]['name'] = $v['email'];
			}
		}

		$this->assign('newMember',$newMember);
	    $this->assign('share',$share);
	    return $this->fetch();
    }

}
