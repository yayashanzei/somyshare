<?php
	namespace app\behavior;

	use think\Hook;


	class Common {

		public function init() {

			define( '_UPLOAD' , '/upload' );
			define( '_STATIC' , '/static' );
			define( '_COMMON' , '/static/common' );
			define( '_IMG' , '/static/' . MODULE_NAME . '/default/images' );
			define( '_CSS' , '/static/' . MODULE_NAME . '/default/css' );
			define( '_JS' , '/static/' . MODULE_NAME . '/default/js' );

			//插件和钩子建立联系
			$data = S( 'hooks' );

			if ( empty( $data ) ) {
				$hooks_list = M( 'hooks' )->field( 'name,addons' )->select( array( 'index' => 'name' ) );
				if ( !empty( $hooks_list ) ) {
					$data = array();
					foreach ( $hooks_list as $key => $value ) {
						$addons = array_filter( explode( "," , $value['addons'] ) );
						if ( !empty( $addons ) ) {
							foreach ( $addons as $k => $v ) {
								$data[ $key ][] = "addons\\" . strtolower( $v ) . "\\" . $v;
							}
						}
					}
					S( 'hooks' , $data );
					Hook::import( $data , false );
				}
			} else {
				Hook::import( $data , false );
			}

		}

	}
