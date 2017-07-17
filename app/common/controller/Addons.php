<?php
	/**
	 * Created by PhpStorm.
	 * User: Sam:yyzm@vip.qq.com
	 * Date: 2016/3/8
	 * Time: 18:27
	 */
	namespace app\common\controller;
	use org\Upload;

	trait Addons {

		//插件目录模板输出
		public function echoAddons( $template ) {
			echo $this->fetch( ADDONS_PATH . $template );
		}

		//获取插件设置信息
		public function getConfig( $name=''){
			if(empty($name)){
				$addons = $this->getName();
				$_config = M('addons')->where(array('name'=>$addons))->getField('config');
			}
			return unserialize($_config);
		}

		//获取类名称
		public function getName(){
			$class = get_class($this);
			return substr($class,strripos($class, '\\')+1);
		}

		//上传单张图片
		public function uploadOne(){

			if(!empty($_FILES)){

				$config = array(
					'maxSize'    =>    3145728,
					'exts'       =>    array('jpg', 'gif', 'png'),
					'rootPath'   =>    UPLOAD_PATH,
					'savePath'   =>    'picture/',
				);

				$upload = new Upload($config,'LOCAL');
				$info = $upload -> uploadOne($_FILES['file']);

				if(!empty($info)){

					$path =  _UPLOAD."/".$info['savepath'].$info['savename'];

					$data = [
						'path' => $path,
					    'md5'  => $info['md5'],
					    'sha1' => $info['sha1'],
					    'create_time' => time()
					];

					$result = M('picture')->add($data);
					return empty($result) ? 0 : $result.','.$path;

				}else{
					return $this->ajaxError($upload->getError());
				}

			}

		}

		//上传多张图片
		public function uploadMulti(){

			if(!empty($_FILES)){

				$config = array(
					'maxSize'    =>    3145728,
					'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
					'rootPath'   =>    UPLOAD_PATH,
					'savePath'   =>    'picture/',
				);

				$upload = new Upload($config,'LOCAL');
				$info = $upload -> upload();

				if(!empty($info)){
					foreach($info as $file){
						return _UPLOAD."/".$file['savepath'].$file['savename'];
					}
				}else{
					return $this->ajaxError($upload->getError());
				}

			}

		}

		//单附件上传
		public function attchmentOne(){

			if(!empty($_FILES)){

				$config = array(
					'maxSize'    =>    3145728,
					'exts'       =>    array('pdf', 'doc', 'xls', 'docx','xlsx','txt'),
					'rootPath'   =>    UPLOAD_PATH,
					'savePath'   =>    'attchment/',
				);

				$upload = new Upload($config,'LOCAL');
				$info = $upload -> uploadOne($_FILES['Filedata']);

				if(!empty($info)){
					return _UPLOAD."/".$info['savepath'].$info['savename'];
				}else{
					return $this->ajaxError($upload->getError());
				}

			}

		}

		//多附件上传
		public function attchmentMulti(){

			if(!empty($_FILES)){

				$config = array(
					'maxSize'    =>    3145728,
					'exts'       =>    array('pdf', 'doc', 'xls', 'docx','xlsx','txt'),
					'rootPath'   =>    UPLOAD_PATH,
					'savePath'   =>    'attchment/',
				);

				$upload = new Upload($config,'LOCAL');
				$info = $upload -> upload();

				if(!empty($info)){
					foreach($info as $file){
						return _UPLOAD."/".$file['savepath'].$file['savename'];
					}
				}else{
					return $this->ajaxError($upload->getError());
				}

			}

		}

		public function getCourse(){

			$id = I('post.id');
			$map['category_id'] = $id;
			$map['status'] = 1;
			$course = M('course')->where($map)->select();

			$html = "<select id='cid' name='cid'  class='input input-auto' style='width:433px'>";
			foreach($course as $k=>$v){
				$html .= "<option value=".$v['id'].">".$v['title']."</option>";
			}
			$html .= "</select>";
			echo $html;

		}

	}


