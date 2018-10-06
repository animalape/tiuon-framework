<?php
/**
 * 惯例配置文件 
 */
return array(
	/*
	 * -------------------------
	 * 		默认设置
	 * -------------------------
	 */
	// 应用模块名称
    'MODULE'                => 'index',
    // 应用控制器名称
    'CONTROLLER'            => 'index',
    // 应用操作名称
	'ACTION'                => 'index',
	// 默认模块获取变量
	'GET_MODULE'            => 'm',
	// 默认控制器获取变量
    'GET_CONTROLLER'        => 'c',
    // 默认操作获取变量
    'GET_ACTION'            => 'a',
    // 控制器目录名称
	'DEFAULT_C_LAYER'       => 'controller',
	// 模块目录名称
	'DEFAULT_M_LAYER'		=> 'model',
	// 公共目录名称
	'DEFAULT_C_COMMON'      => 'common',
	// 公共配置目录名称
    'DEFAULT_C_CONFIG'      => 'config',
    // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_AJAX_RETURN'   => 'json',
    // 默认JSONP格式返回的处理方法
    'DEFAULT_JSONP_HANDLER' => 'jsonpReturn',
    // 应用语言文件目录名称
    'DEFAULT_LANG_PATH'     => 'lang',
    // 默认关闭强制HTTPS
    'SSL_AUTO'				=> false,
    'SSL_DOMAIN'			=> '',
    
	/*
	 * -------------------------
	 * 		系统变量名称设置
	 * -------------------------
	 */
	// URL参数模式, false(關閉普通GET、POST模式) true(開啟普通GET、POST模式)
    'PARAM_AUTO'            => true,
    // 默认语言
	'DEFAULT_LANG'          => 'zh-cn',
	// 允许切换的语言列表 用逗号分隔
	'LANG_LIST'             => 'zh-cn,en-us',
    // 默认语言切换变量，注意到上面发的链接了么，l=zh-cn，就是在这里定义l这个变量
    'VAR_LANGUAGE'          => 'l',
    
	/*
	 * -------------------------
	 * 		Session配置
	 * -------------------------
	 */
	// Session状态，默认开启
    'SESSION_AUTO'          => true,
    
	/*
	 * -------------------------
	 * 		登录加密与登录时间配置
	 * -------------------------
	 */
	// 用于异位或加密的KEY
	'ENCTYPTION_KEY'        => 'nowphpcom',
	// 自动登录保存时间 ,一个星期
	'AUTO_LOGIN_TIME'       => time() + 3600 * 24 * 7,
	
	/*
	 * -------------------------
	 * 		URL设置
	 * -------------------------
	 */
	// URL访问模式,模式：0 (普通模式) 1 (PATHINFO 模式) 2 (兼容模式)  默认为兼容模式;
	'URL_MODEL'             => 2,
	// URL伪静态后缀设置
    'URL_HTML_SUFFIX'       => 'html',
    // PATHINFO模式下，各参数之间的分割符号
	'URL_PATHINFO_DEPR'     =>  '/',
	// URL禁止访问的后缀设置
    'URL_DENY_SUFFIX'       => '.rar|.zip',
    // 是否开启URL映射
	'URL_ROUTER_ON'         => false,
	// URL映射模块
    'URL_MAP_RULES'         => array(
        'admin'     => 'index'
    ),
    
	/*
	 * -------------------------
	 * 		数据库设置
	 * -------------------------
	 */
	// 数据库类型
	'DB_TYPE'               => 'Mysql',
	// SQLite数据库地址
	'DB_DATAFILE'			=> '',
	// 服务器地址
    'DB_HOSTNAME'           => 'localhost',
    // 用户名
    'DB_USERNAME'           => '',
    // 密码
    'DB_PASSWORD'           => '',
    // 数据库名
    'DB_DATANAME'           => '',
    // 数据库表前缀
    'DB_PREFIX'             => '',
    // 数据库端口
    'DB_PORT'               => '',
    // 数据库编码默认采用utf8
    'DB_CHARSET'            => 'utf8',
    
	/*
	 * -------------------------
	 * 		模板引擎设置
	 * -------------------------
	 */
	// 调试模式
	'TMP_DEBUG'				=> false,
	// 模板类型
	'TMP_TYPE'				=> 'smarty',
	// 变量临时数组
    'TMP_ARR'               => array(),
    // 模板所在文件夹
    'TMP_VIEWS'             => 'views',
    // 是否使用缓存， (默认项目调试期间会关闭)
	'TMP_CACHE_AUTO'        => false,
    // 编译文件存放目录
    'TMP_COMPILE_DIR'       => 'temp',
    // 编译后存放的文件目录
    'TMP_CACHE_PATH'        => 'cache',
    // 缓存生命周期 （按秒）
    'TMP_CACHE_TIME'        => 60*60*24*7, // 默认设置缓存时间为1周
    // 模板引擎普通标签开始标记
    'TMP_L_DELIM'           => '{',
    // 模板引擎普通标签结束标记
    'TMP_R_DELIM'           => '}',
    // 模板编译后的后缀
	'TMP_CACHFILE_SUFFIX'   => 'php',
	// 模板编译后缀
    'TMP_STATIC_SUFFIX'     => 'html',
    // 模板文件后缀
    'TMP_SUFFIX'            => 'html',
    
	/*
	 * -------------------------
	 * 		错误设置
	 * -------------------------
	 */
	// 错误显示信息,非调试模式有效
    // 非法访问页
    'TMP_ERROR_ACCESS'      => RES_PATH . 'template/errors/dispatch_jump.php',
    // 调试模式错误页
    'TMP_ERROR_DEBUG'       => RES_PATH . 'template/errors/debug.php',
    // 404错误页
    'TMP_ERROR_404'         => RES_PATH . 'template/errors/404.php',
    
	/*
	 * -------------------------
	 * 		日志设置
	 * -------------------------
	 */
	// 日志状态，默认开启
    'LOG_AUTO'              => true,
    // 日志驱动文件
    'LOG_TYPE'				=> 'File',
    // 日志文件大小限制
    'LOG_FILE_SIZE'         => 2097152,
    // 日志时间格式
    'LOG_TIME_FORMAT'       => ' c ',
    // 日志目录
    'LOG_PATH'              => RUNTIME_PATH . 'logs/',
    
	/*
	 * -------------------------
	 * 		阿里云OSS设置
	 * -------------------------
	 */
	// 默认关闭
	'ALIOSS_AUTO'           => false,
	// 上傳成功后是否刪除本地文件，默认关闭                
	'ALIOSS_DEL_FILES'      => false,
	'ALIOSS_CONFIG'         => array(
		// 阿里云oss key_id
		'KEY_ID'       => '',
		// 阿里云oss key_secret
		'KEY_SECRET'   => '',
		// 阿里云oss endpoint
		'END_POINT'    => '',
		// bucken 名称
		'BUCKET'       => ''
    )
);