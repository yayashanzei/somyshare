<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/4/18
	 * Time: 10:00
	 */

	namespace app\admin\controller;

	use app\common\controller\Addons as commonAddons;
	use app\common\controller\Admin;
	use think\Hook;

	class Addons extends Admin {

		use commonAddons;

		/**
		 * 列出插件列表
		 * User: Sam:yyzm@vip.qq.com
		 */
		public function index() {

			$list   = array();
			$_lists = D( 'Addons' )->getList();

			$addonDir = ADDONS_PATH;
			$lists    = array_map( 'basename' , glob( $addonDir . "*" , GLOB_ONLYDIR ) );

			if ( empty( $lists ) || !file_exists( $addonDir ) ) {
				$this->ajaxError( '插件目录不存在或者不可写！' );
			}

			foreach ( $lists as $k => $v ) {
				isset( $_lists[ $v ] ) ? $name = $_lists[ $v ]['name'] : $name = '';
				if ( empty( $name ) ) {
					$status = "未安装";
					$name   = getFileByCaps( $addonDir . $v , $v );
				}

				$className  = "\\addons\\" . strtolower( $name ) . "\\" . $name;
				$addon      = new $className();
				$list[ $k ] = $addon->info;
				if ( !empty( $status ) ) {
					$list[ $k ]['status'] = $status;
				}
				//判断是否有配置文件，决定插件列表设置显示不显示
				$fromName = "\\addons\\" . strtolower( $name ) . "\\config.php";
				if ( file_exists( APP_ROOT . $fromName ) ) {
					$list[ $k ]['has_config'] = 1;
				} else {
					$list[ $k ]['has_config'] = 0;
				}

				if ( isset( $_lists[ $v ] ) ) {
					switch ( $_lists[ $v ]['status'] ) {
						case 0:
							$list[ $k ]['status'] = "禁用";
						break;
						case 1:
							$list[ $k ]['status'] = "启用";
						break;
						default:
							$list[ $k ]['status'] = "未知";
					}
				}


			}

			$this->assign( 'list' , $list );
			return $this->fetch();
		}

		public function install() {
			$data     = $infos = [ ];
			$name     = I( 'name' );
			$_name    = strtolower( $name );
			$mainName = "\\addons\\" . $_name . "\\" . $name;
			$fromName = "\\addons\\" . $_name . "\\config.php";

			$addons = new $mainName();
			$infos  = $addons->info;
			if ( property_exists( $addons , 'admin_list' ) ) {
				$admin_list = $addons->admin_list;
			}

			if ( file_exists( APP_ROOT . $fromName ) ) {
				$config = include( APP_ROOT . $fromName );
				$temp   = array();
				foreach ( $config as $k => $v ) {
					$temp[ $k ]['value'] = $v['value'];
				}
				$config         = $temp;
				$data['config'] = serialize( $config );
			}

			$data['name']        = $infos['name'];
			$data['title']       = $infos['title'];
			$data['description'] = $infos['description'];
			$data['status']      = $infos['status'];

			$data['create_time'] = time();
			!empty( $admin_list ) ? $data['has_adminlist'] = 1 : $data['has_adminlist'] = 0;

			M( 'addons' )->add( $data );
			$this->ajaxSuccess( "安装成功！" );
		}

		public function uninstall() {
			$map['name'] = I( 'name' );
			M( 'addons' )->where( $map )->delete();
			$this->ajaxSuccess( "卸载成功！" );
		}

		public function config() {
			$name    = I( 'name' );
			$config  = "\\addons\\" . strtolower( $name ) . "\\config.php";
			$config  = include( APP_ROOT . $config );
			$data    = M( 'addons' )->where( array( 'name' => $name ) )->find();
			$default = unserialize( $data['config'] );
			if ( empty( $default ) || count( $config ) > count( $default ) ) {
				$default = array();
			}
			$config = multiMergeRecursive( $config , $default );
			$this->assign( 'config' , $config );
			$this->assign( 'data' , $data );
			return $this->fetch();
		}

		//保存插件设置信息
		public function saveConfig() {

			//获取提交的表单内容与原配置数组合并
			$config = I( 'post.' );

			$name = $config['name'];
			//删除不用的项
			unset( $config['name'] );

			//更改数组层次
			$temp = array();
			foreach ( $config as $key => $value ) {
				if ( is_array( $value ) ) {
					$temp[ $key ]['value'] = implode( "," , $value );
				} else {
					$temp[ $key ]['value'] = $value;
				}
			}

			$map['config'] = serialize( $temp );
			M( 'addons' )->where( array( 'name' => $name ) )->save( $map );
			$this->ajaxSuccess( "设置成功" );
		}

		public function disable() {
			$map['name']    = I( 'name' );
			$data['status'] = 0;
			M( 'addons' )->where( $map )->save( $data );
			$this->ajaxSuccess( "禁用成功！" );
		}

		public function enable() {
			$map['name']    = I( 'name' );
			$data['status'] = 1;
			M( 'addons' )->where( $map )->save( $data );
			$this->ajaxSuccess( "启用成功！" );
		}

		/**
		 * 创建新插件
		 * User: Sam:yyzm@vip.qq.com
		 */
		public function create() {

			if ( IS_POST ) {

				$name          = trim( I( 'post.name' ) );
				$_name         = strtolower( $name );
				$has_adminlist = I( 'post.has_adminlist' , 0 );
				$has_config    = I( 'post.has_config' , 0 );
				$config        = I( 'post.config' );
				$has_outurl    = I( 'post.has_outurl' , 0 );

				$addons = D( 'Addons' );
				//如果标识重复，提示重复并返回
				$addons->checkName( $_name );

				//以插件标识创建插件文件夹
				$addons_dir  = ADDONS_PATH . $_name . "/";  //插件目录
				$addons_name = $addons_dir . $name . ".php"; //插件主文件

				$addonFile = $this->preview( false );

				$files = array();

				$files[] = $addons_dir;
				$files[] = $addons_name;

				//需要配置
				if ( $has_config == 1 ) {
					$files[] = $addons_dir . 'config.php';
				}

				//需要外部访问
				if ( $has_adminlist == 1 ) {
					$files[] = $addons_dir . "controller/";
					$files[] = $addons_dir . "controller/" . $name . ".php";
					$files[] = $addons_dir . "model/";
					$files[] = $addons_dir . "model/" . $name . ".php";
				}

				//创建插件文件、文件夹
				create_dir_or_files( $files );

				//写入插件主文件配置
				file_put_contents( $addons_name , $addonFile );

				//如果需要外部访问，则给创建的插件控制器、模型写入内容
				if ( $has_outurl == 1 ) {
					//要写入插件控制器的内容
					$addonController = <<<str
<?php
namespace addons\\{$_name}\controller;
use app\common\controller\Addons;

class {$name} extends Addons{

}
str;
					//写入插件控制器
					file_put_contents( "{$addons_dir}controller/{$name}.php" , $addonController );

					//要写入插件模型的内容
					$addonModel = <<<str
<?php
namespace addons\\{$_name}\model;
use think\Model;

class {$name} extends Model {


}
str;
					//写入插件模型
					file_put_contents( "{$addons_dir}model/{$name}.php" , $addonModel );
				}

				//写入插件配置信息
				if ( $has_config == 1 ) {
					file_put_contents( "{$addons_dir}config.php" , $config );
				}

				S( 'hooks' , null );
				$this->ajaxSuccess( "创建插件成功！" );

			} else {
				//创建插件
				if ( !is_writable( ADDONS_PATH ) ) {
					$this->ajaxError( "您没有插件目录写入权限，无法创建插件！" );
				}

				//获取所有挂载点
				$hook_list = D( 'hooks' )->field( 'id,name' )->select();
				$this->assign( 'hook_list' , $hook_list );
				return $this->fetch();
			}

		}

		//预览
		public function preview( $output = true ) {

			$name          = trim( I( 'post.name' ) );
			$_name         = strtolower( $name );
			$status        = I( 'post.status' , 0 );
			$title         = trim( I( 'post.title' ) );
			$version       = trim( I( 'post.version' ) );
			$description   = trim( I( 'post.description' ) );
			$author        = trim( I( 'post.author' ) );
			$admin_lists   = $_POST['admin_list'];
			$has_adminlist = I( 'post.has_adminlist' , 0 );

			$extend = array();
			if ( $has_adminlist ) {

				$admin_list = <<<str
public \$admin_list = array(
    {$admin_lists}
);
str;
				$extend[]   = $admin_list;
			}

			$extend = implode( '' , $extend );

			$hook = '';

			if ( !empty( $_POST['hook'] ) ) {
				//更新钩子表插件名称字段
				$hooks = M( 'hooks' )->field( 'id,name,addons' )->where( array( 'id' => array( 'in' , implode( ',' , $_POST['hook'] ) ) ) )->select();

				if ( $hooks !== false ) {

					$ids        = array();
					$hooksTable = C( 'database.prefix' ) . 'hooks';
					$sql        = "UPDATE $hooksTable SET addons = CASE id ";
					foreach ( $hooks as $id => $val ) {
						empty( $val['addons'] ) ? $names = $name : $names = $val['addons'] . ',' . $name;
						$sql .= sprintf( "WHEN %d THEN '%s' " , $val['id'] , $names );
						$ids[] = $val['id'];
						$hook .= <<<str

		//实现的{$val['name']}钩子方法
		public function {$val['name']}(\$param){

		}

str;
					}
					$ids = implode( ',' , $ids );
					$sql .= "END WHERE id IN ($ids)";

					if ( !$output ) {
						$rs = M()->execute( $sql );

						if ( $rs === false ) {
							$this->ajaxTip( '更新hooks失败！' );
						}
					}

				}

			}


			$tpl = <<<str
<?php

namespace addons\\{$_name};
use app\common\controller\Addons;

/**
 * {$title}插件
 * @author {$author}
 */

    class {$name} extends Addons {

        public \$info = array(
            'name'=>'{$name}',
            'title'=>'{$title}',
            'description'=>'{$description}',
            'status'=>{$status},
            'author'=>'{$author}',
            'version'=>'{$version}'
        );

        {$extend}

		{$hook}
    }
str;
			if ( $output ) {
				exit( $tpl );
			} else {
				return $tpl;
			}

		}


	}