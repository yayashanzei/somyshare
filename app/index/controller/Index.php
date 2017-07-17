<?php
namespace app\index\controller;
use org\Upload;
use think\Controller;
use app\common\controller\Index as commonIndex;

class Index extends commonIndex
{
    public function index()
    {
	    return $this->fetch();
    }

}
