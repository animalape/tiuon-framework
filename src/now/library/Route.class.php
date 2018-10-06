<?php
/* ======== 路由类 =======
 * Author:Tiuon.com
 * ====================== */
namespace library;
/**
url模式参数说明：
0 普通模式      例如：http://www.tiuon.com/index.php?m=index&c=index&a=test&aid=5 
1 pathinfo模式  例如：http://www.tiuon.com/index.php/index/index/test/aid/5

$pathinfo_module： 模块变量名 例如：m、module
$pathinfo_controller：控制器变量名  例如：c、controller 
$pathinfo_action： 方法变量名 例如： a、action
**/
class Route{
	static private $config;
	static private $mca;
    // 初始化方法
    static public function start(){
		self::$config['_ru']		= $_SERVER['REQUEST_URI'];
		self::$config['_qs']		= $_SERVER['QUERY_STRING'];
		self::$config['_rm']		= $_SERVER['REQUEST_METHOD'];

		// 判断Url模式
		switch(C('URL_MODEL')){
			// 普通模式
			case 0:
			    self::$config['url_mode'] = 0;
			break;
			// Pathinfo模式
			case 1:
			    self::$config['url_mode'] = 1;
			break;
			// 兼容模式
			case 2:
			    $type_arr  = explode('=',trim(self::$config['_qs'],"="));
				self::$config['url_mode'] = 0;
				$get = array(C('GET_MODULE'),C('GET_CONTROLLER'),C('GET_ACTION'));
			    if(empty($type_arr[0]) || $type_arr[0] == 's' || in_array($type_arr[0],$get) == false){
					self::$config['url_mode'] = 1;
				}
			break;
		}
		
		
		if(self::$config['_qs']){
			$c_qs = str_replace(array('s=','S='),'',self::$config['_qs']);
			$_arr  = explode('/',trim($c_qs,'/'));
		}elseif(self::$config['_ru']){
			$_arr  = explode('/',trim(self::$config['_ru'],'/'));
		}
		$_arr = str_replace('.'.C('URL_HTML_SUFFIX'),'',$_arr);
		// 判断数组0是否存在特定的函数，存在即删除特定函数
		if(strstr($_arr[0], '.php')){
			$array['index'] = $_arr[0];
			// 删除数组的第一个元素
			array_shift($_arr);
		}
		
		self::$config['mode_arr'] = $_arr;
		
		self::$config['module']     = !empty($_arr[0])?$_arr[0]:C('MODULE');
		self::$config['controller'] = !empty($_arr[1])?$_arr[1]:C('CONTROLLER');
		self::$config['action']     = !empty($_arr[2])?$_arr[2]:C('ACTION');
		// 解析URL
		self::parseUrl(self::$config['url_mode']);
		// 默认模块名称
        define('MODULE_NAME', strtolower(self::$mca['module']));
		// 默认控制器名称
	    define('CONTROLLER_NAME', strtolower(self::$mca['controller']));
		// 默认操作名称
		$am_arr = explode('?',trim(self::$mca['action'],"?"));
	    define('ACTION_NAME', $am_arr[0]);
	    
    }
	// 解析URL
	static public function parseUrl($type=0){
		$arr = self::$config['mode_arr'];
		$param = self::makeUrl();
		$action_arr = explode('.',trim($param['action'],'.'));
		
		switch($type){
			// 普通模式
			case 0:
				if(is_array($arr) && $arr[1]){
					E('錯誤: 當前Url模式為普通模式，请使用普通模式访问！');
				}
				self::$mca['module']     = $param['get'][C('GET_MODULE')]?strtolower($param['get'][C('GET_MODULE')]):self::$config['module'];
				self::$mca['controller'] = $param['get'][C('GET_CONTROLLER')]?strtolower($param['get'][C('GET_CONTROLLER')]):self::$config['controller'];
				self::$mca['action']     = $param['get'][C('GET_ACTION')]?$param['get'][C('GET_ACTION')]:self::$config['action'];
			break;
			// Pathinfo模式
			case 1:
				if(is_array($arr)){
					$arrs  = explode('?',trim($arr[0],"?"));
					$_s = 0;
					if('s=' == $arrs[1] || 'S=' == $arrs[1]){
						$_s = 1;
					}elseif(strtolower($arr[0]) == strtolower($arrs[0])){
						$_s = 1;
					}
					if(empty($_s)){
						E('錯誤: 當前Url模式為Pathinfo模式，请使用Pathinfo模式访问！');
					}
				}
				self::$mca['module']     = $param['module']?strtolower($param['module']):self::$config['module'];
				self::$mca['controller'] = $param['controller']?strtolower($param['controller']):self::$config['controller'];
				self::$mca['action']     = $action_arr[0]?$action_arr[0]:self::$config['action'];
			break;
		}
        if($type){
			$isMca['m'] = $param['module']?$param['module']:self::$config['module'];
			$isMca['c'] = $param['controller']?$param['controller']:self::$config['controller'];
			$isMca['a'] = $action_arr[0]?$action_arr[0]:self::$config['action'];
		}else{
			$isMca['m'] = $param['get'][C('GET_MODULE')]?$param['get'][C('GET_MODULE')]:self::$config['module'];
			$isMca['c'] = $param['get'][C('GET_CONTROLLER')]?$param['get'][C('GET_CONTROLLER')]:self::$config['controller'];
			$isMca['a'] = $param['get'][C('GET_ACTION')]?$param['get'][C('GET_ACTION')]:self::$config['action'];
		}
		// 是否开启普通变量模式
    	if(C('PARAM_AUTO')){
    		$_GET  = $param['get'];
    	    $_POST = $param['post'];
    	}
		// URL映射
		if(C('URL_ROUTER_ON')){
			$routes  = C('URL_MAP_RULES');
			foreach ($routes as $key => $value){
				$key_ = explode('/',trim($key,'/'));
				$val_ = explode('/',trim($value,'/'));
				if(is_array($key_) && $key_[1]){
					if($key_[0] == $isMca['m']) self::$mca['module']     = $val_[0];
					if($key_[1] == $isMca['c']) self::$mca['controller'] = $val_[1];
					if($key_[2] == $isMca['a']) self::$mca['action']     = $val_[2];
				} else {
					if($key_[0] == $isMca['m']) self::$mca['module'] = $val_[0];
				}
			}
		}
	}
    // 获取Url参数
    static public function param($type,$value){
		// 获取参数
    	$param = self::makeUrl();
		// 判断参数模式
    	switch($type){
            //get 模式
            case 'get':
                $is_param = $param['get'];
                break;
            //post 模式
            case 'post':
                $is_param = $param['post'];
                break;
        }
		$is_get = $is_param;
    	if($value){
			$is_get = $is_param[$value];
		}
    	return $is_get;
    }
    // 获取url打包参数
    static public function makeUrl() {
        switch (self::$config['url_mode']) {
            //动态url传参 模式
            case 0:
                return self::getParamByDynamic();
            break;
            //pathinfo 模式
            case 1:
                return self::getParamByPathinfo();
            break;
        }
    }
    // 获取参数通过url传参模式
    static private function getParamByDynamic() {
        $arr = empty(self::$config['_qs']) ? array() : explode('&', trim(self::$config['_qs'],'&'));
        $_data_ = array(
            'module'     => '',
            'controller' => '',
            'action'     => '',
            'post'       => array(),
            'get'        => array()
        );
        if (!empty($arr)) {
            $tmp  = array();
            $part = array();
            foreach ($arr as $v) {
                $tmp = explode('=', trim($v,'='));
                $tmp[1] = isset($tmp[1]) ? trim($tmp[1]) : '';
                $part[$tmp[0]] = $tmp[1];
            }
            if (isset($part[$config['module']])) {
                $_data_['module'] = $part[$config['module']];
                unset($part[$config['module']]);
            }
            if (isset($part[$config['controller']])) {
                $_data_['controller'] = $part[$config['controller']];
                unset($part[$config['controller']]);
            }
            if (isset($part[$config['action']])) {
                $_data_['action'] = $part[$config['action']];
                unset($part[$config['action']]);
            }
            switch (self::$config['_rm']) {
                case 'GET':
                    unset($_GET[$config['controller']], $_GET[$config['action']], $_GET[$config['module']]);
                    $_data_['get'] = array_merge($part, $_GET);
                    unset($_GET);
                    break;
                case 'POST':
                    unset($_POST[$config['controller']], $_POST[$config['action']], $_GET[$config['module']]);
                    $_data_['post'] = array_merge($part, $_POST);
                    unset($_POST);
                    break;
                case 'HEAD':
                    break;
                case 'PUT':
                    break;
            }
        }
        return $_data_;
    }
    // 获取参数通过pathinfo模式
    static private function getParamByPathinfo() {
        $part = self::$config['mode_arr'];
        $_data_ = array(
            'module'     => '',
            'controller' => '',
            'action'     => '',
            'post'       => array(),
            'get'        => array()
        );
        if (!empty($part)) {
            krsort($part);
            $_data_['module']     = array_pop($part);
            $_data_['controller'] = array_pop($part);
            $_data_['action']     = array_pop($part);
            ksort($part);
            $part = array_values($part);
            $tmp  = array();
            if (count($part) > 0) {
                foreach ($part as $k => $v) {
                    if ($k % 2 == 0) {
                        $tmp[$v] = isset($part[$k + 1]) ? $part[$k + 1] : '';
                    }
                }
            }
            switch (self::$config['_rm']) {
                case 'GET':
                    unset($_GET[$config['controller']], $_GET[$config['action']]);
                    $_data_['get'] = array_merge($tmp, $_GET);
                    unset($_GET);
                    break;
                case 'POST':
                    unset($_POST[$config['controller']], $_POST[$config['action']]);
                    $_data_['post'] = array_merge($tmp, $_POST);
                    unset($_POST);
                    break;
                case 'HEAD':
                    break;
                case 'PUT':
                    break;
            }
        }
        return $_data_;
    }
 
}