<?php
return array(
	
	//'配置项'=>'配置值'
	'TMPL_PARSE_STRING'  =>array(     
		'__PUBLICS__' => __ROOT__.'/Application/'.MODULE_NAME.'/Public',	//用于模块模板资源获取的扩展定义
		'__UPLOAD__' => '/Uploads/',										//文件存储路径
		'HTTP_PUBLICS'=> 'http://127.0.0.1/servers/Application/'.MODULE_NAME.'/Public'	
	),
	
	'REDIS' => array(
		'host'=>'127.0.0.1', 			//地址
		'port'=>'6379', 				//端口
		'type'=>'Redis', 				//缓存类型
		'prefix'=>'api', 				//缓存前缀
		'expire'=>3600					//默认缓存时间
	),
	
	//-------------------------------------------------------------------------文件相关配置区域[开始]
	'FILEM'=> array(				//上传文件一级目录范围
		'Edu',
		'User'
	),
		
	'FILEF'=> array(				//上传文件二级目录范围	
	),
	
	'FILESTYPE'=> array(			//可上传的文件类型
		'jpg',
		'gif', 
		'png', 
		'jpeg',
        'pjpeg',        //IE6下文件类型
        'x-png'                
	),

	'FILEURL'=> 'http://127.0.0.1/servers/Uploads',	//文件访问URL
	'UEDITOR'=> 'http://127.0.0.1/servers',	//富文本文件前缀地址
	
);
