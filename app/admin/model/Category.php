<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/10
	 * Time: 14:17
	 */

	namespace app\admin\model;
	T('model/Auto');

	class Category extends \think\Model {
		use \traits\model\Auto;

		protected $validate = array(
			array('name', 'require', '标识不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
		);

		protected $_auto = array(
			array('status', '1', self::MODEL_BOTH),
		);

		public function info($id, $field = true){
			/* 获取分类信息 */
			$map = array();
			if(is_numeric($id)){ //通过ID查询
				$map['id'] = $id;
			} else { //通过标识查询
				$map['name'] = $id;
			}
			return $this->field($field)->where($map)->find();
		}

		public function getTree($id = 0, $field = true){
			/* 获取当前分类信息 */
			if($id){
				$info = $this->info($id);
				$id   = $info['id'];
			}

			/* 获取所有分类 */
			$map  = array('status' => array('gt', -1));
			$list = $this->field($field)->where($map)->order('sort')->select();

			$list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);

			/* 获取返回数据 */
			if(isset($info)){ //指定分类则返回当前分类极其子分类
				$info['_'] = $list;
			} else { //否则返回所有分类
				$info = $list;
			}

			return $info;
		}


	}
