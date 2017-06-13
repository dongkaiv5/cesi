<?php
return array(
	
	'TMPL_PARSE_STRING'  =>array(     
		'__PUBLICS__' => __ROOT__.'/Application/'.MODULE_NAME.'/Public',	//用于模块模板资源获取的扩展定义
		'__JSPUBLICS__' => 'Application/'.MODULE_NAME.'/Public',			//用于模块模板资源获取的扩展定义
		'__UPLOAD__' => '/Uploads/',										//文件存储路径
		'__MOBILE__' => 'http://m.pokpets.com/index.php',	//wap端入口
	),
	
	'SESSION_PREFIX'=>'sp_admin',		//本地化session前缀
	'PAGE_NUMBER'=>10,					//后台分页数
	'SHOW_PAGE_TRACE'=>false,
        'EXAM_UPLOADS'=>'./Uploads/Exams/',
);
