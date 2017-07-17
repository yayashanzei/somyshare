<?php

	/**
	 * 通过子分类id查找顶级分类的id
	 * @param $id
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getCateTid( $table , $id ) {
		$cate = M( $table )->find( $id );
		if ( $cate['pid'] != 0 ) {
			return getCateTid( $table , $cate['pid'] );
		} else {
			return $cate['id'];
		}
	}

	/**
	 * 找分类所有子级的id字符串
	 * @param $table
	 * @param $id
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getChildId( $table , $id , $ids ) {
		$temp       = array();
		$map['pid'] = array( 'in' , $id );
		$child      = D( $table )->field( 'id' )->where( $map )->select();
		if ( !empty( $child ) ) {
			foreach ( $child as $k => $v ) {
				$temp[] = $v['id'];
			}

			if ( !empty( $ids ) ) {
				//得到所有子级的id
				foreach ( $temp as $v ) {
					array_push( $ids , $v );
				}
			} else {
				$ids = $temp;
			}

			//如果有子级继续递归
			return getChildId( $table , $temp , $ids );
		} else {
			return implode( ',' , (array)$ids );
		}
	}

	/**
	 *  获取分类所有子类ID包括自己
	 * @param $tid
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getChildAllId( $tid ) {
		$ids = [ ];
		$id  = M( 'category' )->field( 'id' )->where( array( 'tid' => $tid ) )->select();
		foreach ( $id as $k => $v ) {
			$ids[] = $v['id'];
		}
		$ids = implode( "," , $ids );
		return $tid . ',' . $ids;
	}

	/**
	 * 获取分类所有子类ID不包括自己(要求分类有pid,tid)
	 * @param $table
	 * @param $tid
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getChildIds( $table , $tid ) {
		$ids = [ ];
		$id  = M( $table )->field( 'id' )->where( array( 'tid' => $tid ) )->select();
		foreach ( $id as $k => $v ) {
			$ids[] = $v['id'];
		}
		$ids = implode( "," , $ids );
		return $ids;
	}

	/**
	 * 时间戳转日期
	 * @param $time
	 * User: Sam:yyzm@vip.qq.com
	 */
	function timeToDate( $time ) {
		return date( "Y-m-d H:i" , $time );
	}

	/**
	 * 获取分类字段值根据字段id
	 * @param        $id
	 * @param string $field 默认获取title
	 *                      User: Sam:yyzm@vip.qq.com
	 */
	function cateFieldbyId( $id , $field = 'title' ) {
		$result = M( 'category' )->where( array( 'id' => $id ) )->getField( $field );
		return $result;
	}

	/**
	 * 获取图片url根据图片表id
	 * @param $cover
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getCover( $cover , $field = 'path' ) {
		$map['id'] = array( 'in' , $cover );
		$result    = M( 'picture' )->where( $map )->getField( $field );
		return $result;
	}

	/**
	 * 获取文章内容根据内容表id
	 * @param $ids
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getContent( $ids , $field = 'content' ) {
		$map['id'] = array( 'in' , $ids );
		$result    = M( 'content' )->where( $map )->getField( $field );
		return $result;
	}

	/**
	 * 根据视频分类获取分类下的课程的总数量
	 * @param $id
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getCourseByCate($ids){
		$map['category_id'] = array( 'in' , $ids );
		$result    = M( 'course' )->where( $map )->select();
		return count($result);
	}

	/**
	 * 检测验证码
	 * @param     $code
	 * @param int $id
	 * User: Sam:yyzm@vip.qq.com
	 */
	function check_verify( $code , $id = '' ) {
		$verify = new \org\Verify();
		return $verify->check( $code , $id );
	}

	/**
	 * 获取文件夹下大小写不同的相同文件名
	 * @param $ids
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getFileByCaps( $dir , $file ) {
		$rs      = '';
		$handler = opendir( $dir );
		while ( ( $filename = readdir( $handler ) ) !== false ) {
			//务必使用!==，防止目录下出现类似文件名“0”等情况
			if ( $filename != "." && $filename != ".." ) {
				$filename = basename( $filename , ".php" );
				if ( strtolower( $filename ) == $file ) {
					$rs = $filename;
				}
			}
		}
		closedir( $handler );
		return $rs;
	}

	/**
	 * 编辑时获取视频所属的课程
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getCourseHtml($cid){

		$map['id'] = array( 'in' , $cid );
		$map['status'] = 1;
		$course = M('course')->where($map)->find();

		$html = "<select id='cid' name='cid'  class='input input-auto' style='width:433px'>";
		$html .= "<option value=".$course['id'].">".$course['title']."</option>";
		$html .= "</select>";
		echo $html;
	}

	/**
	 * 获取用户信息
	 * @param $uid
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getUserBase($uid, $field = 'name'){
		$result = M('user')->where(array('uid'=>$uid))->getField($field);
		if(empty($result)){
			$result = M('user')->where(array('uid'=>$uid))->getField('email');
		}
		return $result;
	}

	/**
	 * 获取用户的头像根据用户uid
	 * @param        $uid
	 * @param string $field
	 * User: Sam:yyzm@vip.qq.com
	 */
	function getUserAvatar($uid){
		$avatar = M('user_info')->where(array('uid'=>$uid))->getField('avatar');
		if(!empty($avatar)){
			$path = M('avatar')->where(array('id'=>$avatar))->getField('path');
			$temp = pathinfo($path);
			$result = $temp['dirname'].'/crop_'.$temp['basename'];
		}else{
			$result = _IMG.'/nologin.gif';
		}
		return $result;
	}

	/**
	 * @method 多维数组变成一维数组
	 * @staticvar array $result_array
	 * @param type $array
	 * @return type $array
	 * @author    yanhuixian
	 */
	function multi2array( $array ) {
		static $result_array = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				multi2array( $value );
			} else
				$result_array[ $key ] = $value;
		}
		return $result_array;
	}

	/*
	 * 检测数据库中某个表是否存在
	 *  return  false 没有传表名 -1 表不存在 1 表存在
	 */
	function tableExists( $table ) {
		if ( empty( $table ) ) {
			return false;
		}
		$_rs = M()->query( "SHOW TABLES LIKE '" . \think\Config::get( 'database.prefix' ) . $table . "'" );

		if ( !$_rs ) {
			return -1;
		} else {
			return 1;
		}
	}

	/**
	 * 解密
	 *
	 * @param string $plainText
	 * @return string
	 */
	function decrypt( $encryptedText , $key ) {
		$cryptText   = base64_decode( $encryptedText );
		$ivSize      = mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256 , MCRYPT_MODE_ECB );
		$iv          = mcrypt_create_iv( $ivSize , MCRYPT_DEV_URANDOM );
		$decryptText = mcrypt_decrypt( MCRYPT_RIJNDAEL_256 , $key , $cryptText , MCRYPT_MODE_ECB , $iv );
		return trim( $decryptText );
	}

	/**
	 * 加密
	 *
	 * @param string $plainText
	 * @return string
	 */
	function encrypt( $plainText , $key ) {
		$ivSize      = mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256 , MCRYPT_MODE_ECB );
		$iv          = mcrypt_create_iv( $ivSize , MCRYPT_DEV_URANDOM );
		$encryptText = mcrypt_encrypt( MCRYPT_RIJNDAEL_256 , $key , $plainText , MCRYPT_MODE_ECB , $iv );
		return trim( base64_encode( $encryptText ) );
	}

	/*
	 * 解析get_param参数
	 */
	function getParam( $url , $param , $paramVal ) {

		if ( empty( $param ) ) {
			return $url;
		}
		$param = explode( ',' , $param );

		$query = '';
		foreach ( $param as $val ) {
			$query[ $val ] = isset( $paramVal[ $val ] ) ? $paramVal[ $val ] : '';
		}

		return $url . '?' . http_build_query( $query );
	}

	/*
	 * behind this create by icebr
	 */
	function multiMergeRecursive() {
		$args  = func_get_args();
		$first = array_shift( $args );

		if ( !isset( $args[0] ) ) {
			return $first;
		}

		foreach ( $args as $arg ) {

			foreach ( $arg as $key1 => $value1 ) {

				if ( is_array( $value1 ) ) {
					if ( isset( $first[ $key1 ] ) ) {
						$first[ $key1 ] = multiMergeRecursive( $first[ $key1 ] , $value1 );
						continue;
					}
					$first[ $key1 ] = $value1;
				} else {
					if ( is_numeric( $key1 ) ) {
						if ( !isset( $_first ) ) {
							$_first = array_flip( $first );
						}
						if ( !isset( $_first[ $value1 ] ) ) {
							$first[] = $value1;
						}
						$_first[ $value1 ] = $value1;
						continue;
					}
					$first[ $key1 ] = $value1;
				}

			}

		}

		return $first;
	}


	/**
	 * 格式化字节大小
	 * @param  number $size      字节数
	 * @param  string $delimiter 数字和单位分隔符
	 * @return string            格式化后的带单位的大小
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	function format_bytes( $size , $delimiter = '' ) {
		$units = array( 'B' , 'KB' , 'MB' , 'GB' , 'TB' , 'PB' );
		for ( $i = 0 ; $size >= 1024 && $i < 5 ; $i++ ) $size /= 1024;
		return round( $size , 2 ) . $delimiter . $units[ $i ];
	}

	/**
	 * 创建目录文件夹、文件
	 * @param $files
	 * User: Sam:yyzm@vip.qq.com
	 */
	function create_dir_or_files( $files ) {
		foreach ( $files as $k => $v ) {
			if ( substr( $v , -1 ) == '/' ) {
				mkdir( $v );
			} else {
				file_put_contents( $v , '' );
			}
		}
	}

	/**
	 * 模板钩子标签监听
	 * @param       $hook
	 * @param array $params
	 * User: Sam:yyzm@vip.qq.com
	 */
	function hook( $hook , $params = array() ) {
		\think\Hook::listen( $hook , $params );
	}

	/**
	 * 后台通用面包屑导航链接
	 * @param  array $paraArray
	 * User: Sam:yyzm@vip.qq.com
	 */
	function aNav( array $paraArray ) {

		$nav = array();
		//面包屑初始设置
		$initArray = array(
			'id'     => '' ,
			'table'  => 'enum' ,
			'home'   => 'Admin' ,
			'link'   => '>' ,
			'ope'    => '' ,
			'assign' => '' ,
		);

		$nav = array_merge( $initArray , $paraArray );

		$cat  = M( $nav['table'] );
		$here = '<a href="' . $nav['ope'] . '.html" onclick="adminAjaxAsk(this);return false;">' . $nav['home'] . '</a>&nbsp;';
		//获得当前位置信息
		$tTitle = $cat->field( 'id,name,pid,tid,title' )->where( array( 'id' => $nav['id'] ) )->find();
		if ( $tTitle['pid'] == 0 ) {
			//一级面包屑链接
			$here .= $nav['link'] . '&nbsp;<a href ="' . $nav['ope'] . '/' . $nav['assign'] . '/' . $tTitle['id'] . '.html" onclick="adminAjaxAsk(this);return false;">' . $tTitle['title'] . "</a>";
		} else {
			$level2 = $cat->field( 'id,name,pid,tid,title' )->where( array( 'tid' => $tTitle['tid'] ) )->select();
			/*			$child = array();
						foreach($level2 as $k => $v){
							if($v['pid'] == $tTitle['id']) {
								$child[] = $v;
							}
						}
						$fTitle = $cat->field('id,name,pid,tid,title')->where(array('id'=>$tTitle['tid']))->find();
						$here .= $nav['link'].'&nbsp;<a href = "'.$nav['ope'].'/'.$nav['assign'].'/'.$tTitle['tid'].'.html" onclick="adminAjaxAsk(this);return false;">'.$fTitle['title']."</a>&nbsp;";
						foreach($child as $k => $v){
							if($v['pid'] == $tTitle['id']) {
								$here .= $nav['link'].'&nbsp;<a href ="'.$nav['ope'].'/'.$nav['assign'].'/'.$tTitle['id'].'.html" onclick="adminAjaxAsk(this);return false;">'.$tTitle['title']."</a>";
								break;
							}
							$here .= $nav['link'].'&nbsp;<a href ="'.$nav['ope'].'/'.$nav['assign'].'/'.$v['id'].'.html" onclick="adminAjaxAsk(this);return false;">'.$v['title']."</a>";
						}*/

			//echo "<pre>";
			//print_r($bbb);exit;

			/*
			//一级面包屑链接
			$fTitle = $cat->field('id,name,pid,tid,title')->where(array('id'=>$tTitle['tid']))->find();
			$here .= $nav['link'].'&nbsp;<a href = "'.$nav['ope'].'/'.$nav['assign'].'/'.$tTitle['tid'].'.html" onclick="adminAjaxAsk(this);return false;">'.$fTitle['title']."</a>&nbsp;";
			//二级三级无限级链接
			$level2 = $cat->field('id,name,pid,tid,title')->where(array('tid'=>$tTitle['tid']))->select();
			//当前的
			foreach($level2 as $k => $v){
				if($tTitle['pid'] == $v['pid']) {
					$here .= $nav['link'].'&nbsp;<a href ="'.$nav['ope'].'/'.$nav['assign'].'/'.$tTitle['id'].'.html" onclick="adminAjaxAsk(this);return false;">'.$tTitle['title']."</a>";
					break;
				}
				$here .= $nav['link'].'&nbsp;<a href ="'.$nav['ope'].'/'.$nav['assign'].'/'.$v['id'].'.html" onclick="adminAjaxAsk(this);return false;">'.$v['title']."</a>";
			}
			*/

		}
		return $here;
	}

	/**
	 * 网站前台通用面包屑导航链接
	 * @param        $id
	 * @param string $table
	 * @param string $home
	 * @param string $link
	 * @param int    $flag 0-前台 1-后台
	 *                     User: Sam:yyzm@vip.qq.com
	 */
	function bNav( array $paraArray ) {

		$nav = array();
		//面包屑初始设置
		$initArray = array(
			'id'    => '' ,
			'table' => 'category' ,
			'home'  => 'Home' ,
			'link'  => '>' ,
			'flag'  => 0 ,
		);

		$nav = array_merge( $initArray , $paraArray );

		$cat  = M( $nav['table'] );
		$here = '<a href="/">' . $nav['home'] . '</a>&nbsp;';
		//获得当前位置信息
		$tTitle = $cat->field( 'id,name,pid,tid,title' )->where( array( 'id' => $nav['id'] ) )->find();
		if ( $tTitle['pid'] == 0 ) {
			//一级面包屑链接
			$here .= $nav['link'] . '&nbsp;<a href ="/' . $tTitle['name'] . '.html">' . $tTitle['title'] . "</a>";
		} else {
			//一级面包屑链接
			$fTitle = $cat->field( 'id,name,pid,tid,title' )->where( array( 'id' => $tTitle['tid'] ) )->find();
			$here .= $nav['link'] . '&nbsp;<a href = "/' . $fTitle['name'] . '.html">' . $fTitle['title'] . "</a>&nbsp;";
			//二级三级无限级链接
			$level2 = $cat->field( 'id,name,pid,tid,title' )->where( array( 'tid' => $tTitle['tid'] ) )->select();
			//当前的
			foreach ( $level2 as $k => $v ) {
				if ( $tTitle['pid'] == $v['pid'] ) {
					$here .= $nav['link'] . '&nbsp;<a href ="/' . $tTitle['name'] . '.html">' . $tTitle['title'] . "</a>";
					break;
				}
				$here .= $nav['link'] . '&nbsp;<a href ="/' . $v['name'] . '.html">' . $v['title'] . "</a>";
			}
		}
		return $here;
	}

	/**
	 * 数据集转换为树
	 * @param $list
	 * User: Sam:yyzm@vip.qq.com
	 */
	function list2tree( $list ) {
		$_tree = array();
		if ( !empty( $list ) ) {
			foreach ( $list as $key => $value ) {
				if ( isset( $list[ $value['pid'] ] ) ) {
					$list[ $value['pid'] ]['_'][ $key ] = &$list[ $value['id'] ];
				} else {
					$_tree[ $key ] = &$list[ $value['id'] ];
				}
			}
		}

		return $_tree;
	}

	/**
	 * 把返回的数据集转换成Tree
	 */
	function list_to_tree( $list , $pk = 'id' , $pid = 'pid' , $child = '_child' , $root = 0 ) {
		// 创建Tree
		$tree = array();
		if ( is_array( $list ) ) {
			// 创建基于主键的数组引用
			$refer = array();
			foreach ( $list as $key => $data ) {
				$refer[ $data[ $pk ] ] =& $list[ $key ];
			}

			foreach ( $list as $key => $data ) {
				// 判断是否存在parent
				$parentId = $data[ $pid ];
				if ( $root == $parentId ) {
					$tree[] =& $list[ $key ];
				} else {
					if ( isset( $refer[ $parentId ] ) ) {
						$parent =& $refer[ $parentId ];

						$parent[ $child ][] =& $list[ $key ];
					}
				}
			}
		}
		return $tree;
	}

	// 获取数据的状态操作
	function show_status_op( $status ) {
		switch ( $status ) {
			case 1  :
				return '正常';
			break;
			case 0  :
				return '禁用';
			break;
			case 2  :
				return '审核';
			break;
			default :
				return false;
			break;
		}
	}

	//select返回的数组进行整数映射转换
	function int_to_string( &$data , $map = array( 'status' => array( 1 => '正常' , -1 => '删除' , 0 => '禁用' , 2 => '未审核' , 3 => '草稿' ) ) ) {
		if ( $data === false || $data === null ) {
			return $data;
		}
		$data = (array)$data;
		foreach ( $data as $key => $row ) {
			foreach ( $map as $col => $pair ) {
				if ( isset( $row[ $col ] ) && isset( $pair[ $row[ $col ] ] ) ) {
					$data[ $key ][ $col . '_text' ] = $pair[ $row[ $col ] ];
				}
			}
		}
		return $data;
	}

	function delAllFilesAndDir( $dir ) {
		//先删除目录下的文件：
		$dh = opendir( $dir );
		while ( $file = readdir( $dh ) ) {
			if ( $file != "." && $file != ".." ) {
				$fullpath = $dir . "/" . $file;
				if ( !is_dir( $fullpath ) ) {
					unlink( $fullpath );
				} else {
					deldir( $fullpath );
				}
			}
		}
		closedir( $dh );
		//删除当前文件夹：
		if ( rmdir( $dir ) ) {
			return true;
		} else {
			return false;
		}

	}

	/*删除空格*/
	function trimAll( $str ) {
		$before = array( ' ' , '　' , '\t' , '\n' , '\r' );
		return str_replace( $before , '' , $str );
	}




