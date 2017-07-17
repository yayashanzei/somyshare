<?php
return array(
    'fileExts'=>array(//配置在表单中的键名
        'title'=>'允许上传附件类型:', //表单的文字
        'type'=>'checkbox',	//表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(	//select 和radion、checkbox的子选项
            '0'=>'PDF',		//值=>文字
            '1'=>'DOC',
            '2'=>'XLS',
			'3'=>'DOCX',
			'4'=>'XLSX',
			'5'=>'TXT',
        ),
        'value'=>'0,1,2,3,4,5',	//表单的默认值
    ),
);
				