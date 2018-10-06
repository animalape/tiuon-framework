<?php
/* ======== 基础类 =======
 * Author:Tiuon.com
 * ====================== */
	
	define('NOW_VERSION', '1.0');																			//	框架版本
	define('NOW_START_TIME', microtime(true));																//	查看代码执行时间
	define('NOW_START_MEM', memory_get_usage());															//	获取当前脚本内存占用情况
	define('EXT', '.class.php');																			//	类文件后缀
	define('DS', DIRECTORY_SEPARATOR);																		//	路径分隔符 windows(\ or /) linux(/)
	define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
	define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);
	defined('NOW_PATH')			or define('NOW_PATH', __DIR__ . DS);										//	框架根目录
	defined('LIB_PATH')			or define('LIB_PATH', NOW_PATH . 'library'.DS);								//	类文件目录
	defined('NOW_DEBUG')    	or define('NOW_DEBUG',false);												//	调试
	defined('APP_PATH')     	or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);			//	项目根目录
	defined('ROOT_PATH')    	or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);					//	根目录
	defined('EXTEND_PATH')  	or define('EXTEND_PATH', ROOT_PATH . 'extend' . DS);						//	
	defined('RUNTIME_PATH') 	or define('RUNTIME_PATH', ROOT_PATH . 'runtime' . DS);						//
	defined('LOG_PATH')     	or define('LOG_PATH', RUNTIME_PATH . 'log' . DS);							//
	defined('CACHE_PATH')   	or define('CACHE_PATH', RUNTIME_PATH . 'cache' . DS);						//
	defined('TEMP_PATH')		or define('TEMP_PATH', RUNTIME_PATH . 'temp' . DS);							//
	defined('RES_PATH')			or define('RES_PATH', NOW_PATH . 'resources' . DS); 						// 资源文件目录
	defined('VENDOR_PATH')  	or define('VENDOR_PATH', RES_PATH . 'vendor' . DS);						    // 框架第三方类文件目录
	defined('CONF_PATH')		or define('CONF_PATH', LIB_PATH.'config'.DS);    							// 配置文件目录
	defined('APP_CONF_PATH')    or define('APP_CONF_PATH', APP_PATH.'common'.DS.'config'.DS);    			// 项目公共配置文件目录
	defined('CONF_EXT')			or define('CONF_EXT', '.php');                        						// 配置文件后缀
	defined('ENV_PREFIX')		or define('ENV_PREFIX', 'PHP_');
	
	// 检测PHP版本
	if(version_compare(PHP_VERSION,'5.5.0','<')){
		$php_name = 'Warning: the framework requires the PHP version to be no less than 5.5.0';
		$file = str_replace(array('\\','/'), DS, RES_PATH. 'template/errors/dispatch_jump.php');
		if(is_file($file)){
			$prompt['title']   = 'PHP版本錯誤';
			$prompt['message'] = $php_name;
			require_once($file);
	        exit;
		}
	    echo $php_name;
	    exit;
	}
	
	
	$_ma = str_replace(array('\\','/'), DS, LIB_PATH . 'engine/fire.php');
	$_ma = require_once($_ma);
	foreach($_ma as $key => $val){
		if(is_file($val)){
	        include($val);
	    }
	}
	
	// 判断是否调试模式
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
	$GLOBALS['System_Debug'] = false;
	if(NOW_DEBUG){
		register_shutdown_function('library\Debug::fatalErrorHandler');
		$GLOBALS['System_Debug'] = true;
		library\Debug::start(microtime(true));
		set_error_handler('library\Debug::ErrorHandler');
	}
	
	// 加载环境变量配置文件
	if(is_file(ROOT_PATH . '.env')) {
	    $env = parse_ini_file(ROOT_PATH . '.env', true);
	    foreach($env as $key => $val) {
	        $name = ENV_PREFIX . strtoupper($key);
	        if(is_array($val)){
	            foreach ($val as $k => $v) {
	                $item = $name . '_' . strtoupper($k);
	                putenv("{$item}={$v}");
	            }
	        } else {
	            putenv("{$name}={$val}");
	        }
	    }
	}
	
	// 注册自动加载
	library\Loader::register();