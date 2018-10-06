<?php
/* ======== 引擎类 =======
 * Author:Tiuon.com
 * ====================== */
namespace library;
class Engine {
	static $CONFIG,$PDO_OBJECT;
	protected static $cache_html;
	
	public static function start(){
		try{
			self::gostart();
		}catch(\PDOException $e){
			if($GLOBALS['System_Debug']){
				echo '<pre style="background-color:#ffc0cb;">[SQL错误:] {$e->getMessage()} [{$e->getFile()}] : {$e->getLine()}<br>{$e->getTraceAsString()}</pre>';
				Debug::ErrorHandler(1110,$e->getMessage(),$e->getFile(),$e->getLine());
				echo Debug::showMsg();
				return false;
			}else{
				die('SQL错误！！！');
			}
		}
	}
    // 应用程序初始化
	static private function gostart(){
		// 运行路由
        Route::start();
        //
        $class	= MODULE_NAME.'\\'.C('DEFAULT_C_LAYER').'\\'.CONTROLLER_NAME.'Controller';
        $action	= ACTION_NAME;
        if(class_exists($class)){
        	$obj = new $class;
        	$obj->$action();
        }else{
            // 类没有定义
            E('未定义类: ' . $class);
        }
        
	}
}