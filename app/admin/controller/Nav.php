<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/7
	 * Time: 14:00
	 */

	namespace app\admin\controller;
	use app\common\controller\Admin;

	class Nav extends Admin {
		public function index() {
			$lists = M('Nav')->select();
			$this->assign('list',$lists);
			$this->meta_title = '导航管理';
			return $this->fetch();
		}

		public function add() {

			if(IS_POST){
				$data = D('Nav')->create();
				unset(D('Nav')->id);
				$id = D('Nav')->add();
				if($id){
					$this->ajaxSuccess( '添加成功！' );
				}else{
					$this->ajaxSuccess( '添加失败！' );
				}
			}else{
				return $this->fetch('edit');
			}

		}

		public function edit(){

			if(IS_POST){
				$data = M('Nav')->create();
				if(M('Nav')->save()!==false){
					$this->ajaxSuccess( '更新成功！' );
				}else{
					$this->ajaxSuccess( '更新失败！' );
				}
			}else{
				$id = I('get.id');
				$this->assign('info',array('id'=> $id));
				$nav = M('Nav')->field(true)->find($id);
				$this->assign('nav',$nav);
				return $this->fetch('edit');
			}

		}

		public function del(){

			if(IS_POST){
				$result = I('post.');
				$id = empty($result['id']) ? '' :$result['id'];
			}else{
				$id = I('get.id');
			}

			if (empty($id)) {
				$this->ajaxError( '请选择要操作的数据！' );
			}

			$map = array( 'id' => array( 'in' , $id ) );
			if ( M( 'nav' )->where( $map )->delete() ) {
				$this->ajaxSuccess( '删除成功！' );
			} else {
				$this->ajaxError( '删除失败！' );
			}
		}
	}