<?php
return array(
    'fileExts'=>array(//配置在表单中的键名
        'title'=>'允许上传图片类型:', //表单的文字
        'type'=>'checkbox',	//表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(	//select 和radion、checkbox的子选项
            '0'=>'JPG',		//值=>文字
            '1'=>'PNG',
            '2'=>'GIF',
        ),
        'value'=>'0,1',	//表单的默认值
    ),
);
				