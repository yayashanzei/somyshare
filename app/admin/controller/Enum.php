<?php
	namespace app\admin\controller;
	use app\common\controller\Admin;

	class Enum extends Admin {

		public function index() {

			$pid = I('get.pid', 0);
			if($pid){
				$list = D('enum')->where(array('pid' => $pid ))->select();
				$pos =  aNav(array('id'=>$pid,'table'=>'enum','home'=>'枚举管理','ope'=>'/admin/enum/index','assign'=>'pid'));
			}else{
				$list = D('enum')->where(array('pid'=>0))->select();
				$pos = "";
			}
			$this->assign('pos',$pos);
			$this->assign('list',$list);
			return $this->fetch();
		}

		public function add(){
			$menus = M( 'enum' )->field( true )->select();
			$menus = D( 'common/Tree' )->toFormatTree( $menus );
			$menus = array_merge( array( 0 => array( 'id' => 0 , 'title_show' => '顶级菜单' ) ) , $menus );
			$this->assign( 'Menus' , $menus );

			if(IS_POST) {

				$pid = I('pid',0);
				D('enum')->create();
				unset( D('enum')->id );
				$aid = D('enum')->add();
				if (($pid == 0) && $aid ){
					//根据唯一标识缓存组名缓存
					$this->ajaxSuccess('添加成功！');
				}elseif(($pid > 0) && $aid){
					$tid = getCateTid('enum',$aid);
					D('enum')->where(array('id'=>$aid))->save(array('tid'=>$tid));
					$this->ajaxSuccess('添加成功！');
				}else{
					$this->ajaxSuccess('添加失败！');
				}
			}else{
				$this->assign('info',array('pid' => I('pid')));
				return $this->fetch('edit');
			}

		}

		public function edit( $id = 0 ) {
			if ( IS_POST ) {
				$data = I('post.',0);
				if(!empty($data)){

					$enum = D('Enum');
					$pid = $data['pid'];

					if($pid == 0){
						//如果更新pid为0顶级栏目，则顶级栏目tid也为0
						$data['tid'] = '0';
					}else{
						//如果不是顶级栏目，则tid为顶级栏目的id
						$data['tid'] = getCateTid('enum',$data['pid']);
					}

					//获取当前更新的所有子级的id集合以英文逗号隔开
					$ids = getChildId('enum',$data['id'],'');
					$ids = explode(",",$ids);
					//判断是否有子级，更新所有子级的tid
					if(!empty($ids)){
						$map['id'] = array('in',$ids);
						if($data['pid'] == 0){
							//二级的tid
							$tid['tid'] = $data['id'];
						}else{
							$tid['tid'] = $data['tid'];
						}

						foreach($ids as $k=>$v){
							$enum->where($map)->save($tid);
						}
					}

					if ( $enum -> save($data) !== false ) {
						$this->ajaxSuccess( '更新成功！' );
					} else {
						$this->ajaxError( '更新失败！' );
					}
				}else{
					$this->ajaxError( '更新失败！' );
				}
			} else {

				$info = array();
				/* 获取数据 */
				$info  = M( 'enum' )->field( true )->find( $id );
				$menus = M( 'enum' )->field( true )->select();
				$menus = D( 'common/Tree' )->toFormatTree( $menus );
				$menus = array_merge( array( 0 => array( 'id' => 0 , 'title_show' => '顶级菜单' ) ) , $menus );

				$this->assign( 'Menus' , $menus );
				if ( false === $info ) {
					$this->ajaxError( '获取后台枚举信息错误！' );
				}
				$this->assign( 'info' , $info );
				return $this->fetch();
			}
		}

		public function del(){

			if(IS_POST){
				$result = I('post.');
				$id = empty($result['id']) ? '' :$result['id'];
			}else{
				$id = (array)I('get.id');
			}

			if (empty($id)) {
				$this->ajaxError('请选择要操作的数据！');
			}

			$checkId = array();
			foreach ( $id as $v ) {
				$checkChild = D( 'enum' )->where( array( 'pid' => $v ) )->find();
				if ( !empty( $checkChild ) ) {
					$checkId[] = $v;
				}
			}

			if ( !empty( $checkId ) ) {
				$checkId = implode( "," , $checkId );
				$this->ajaxError( $checkId . '有子级分类，请先删除子级分类，再删父类！' );
			}

			$map = array( 'id' => array( 'in' , $id ) );
			if ( M( 'enum' )->where( $map )->delete() ) {
				$this->ajaxSuccess( '删除成功！' );
			} else {
				$this->ajaxError( '删除失败！' );
			}
		}

		public function updateCache($tip = 1) {

			$results  = D( 'enum' )->field( 'id,pid,title,name,value' )->select( array( 'index' => 'id' ) );

			$_tree = array();
			if(!empty($results)){
				foreach ( $results as $key=> $value ) {
					if ( isset( $results[ $value['pid'] ] ) ) {
						$results[ $value['pid'] ]['_'][ $key ] = &$results[ $value['id'] ];
					}else{
						$_tree[ $key ] = & $results[$value['id']];
					}
				}
			}

			S('enum',$_tree);
			if($tip == 1){
				$this->ajaxSuccess('缓存成功！');
			}

		}

	}
