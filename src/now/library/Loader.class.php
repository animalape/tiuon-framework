<?php
/* ======== 加载程序类 =======
 * Author:Tiuon.com
 * ========================== */
namespace library;
class Loader{
	
    protected static $_map = array();  // 类名映射
    
    // 注册自动加载机制
    public static function register(){
        // 注册系统自动加载
        spl_autoload_register('library\Loader::autoload');
    }
    
    // 自动加载
    public static function autoload($class) {
        // 检查是否存在映射
        if(isset(self::$_map[$class])) {
            include(self::$_map[$class]);
        }elseif(false !== strpos($class,'\\')){
        	$name = strstr($class, '\\', true);
        	$path = APP_PATH;
        	// Library目录下面的命名空间自动定位
        	if(in_array($name, array('library'))){ 
            	$path = NOW_PATH;
        	}
        	$filename = str_replace(array('\\','/'), DS, $path . $class . EXT);
        	if(is_file($filename)) {
            	// Win环境下面严格区分大小写
            	if (IS_WIN && false === strpos(str_replace(array('\\','/'), DS, realpath($filename)), $class . EXT)){
                	return ;
            	}
            	include($filename);
        	}
        }
    }
}