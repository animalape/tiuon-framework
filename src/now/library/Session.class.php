<?php
/* ======== Session类 =======
 * Author:Tiuon.com
 * ========================= */
namespace library;
/**
 *  session类
 */
class Session
{

	protected static $cache_name;   // 缓存名称
	protected static $cache_second; // 缓存周期,单位：秒
	
	// 开启session
	public static function start($config=null){
		if($config){
			self::$cache_name   = $config['name'];
			self::$cache_second = $config['second'];
		}
		if(C('SESSION_AUTO')){
			session_start();
			if(self::$cache_name){
				ini_set('session.name',self::$cache_name);
			}
			if(self::$cache_second){
				ini_set('session.auto_start',1);
			    ini_set('session.cookie_lifetime',self::$cache_second);
			}
		}
	}
	/**
	* 获取session
	* @param 名称 $name
	*/
	public static function get($name = null){
		if(!empty($name)){
			return (!empty($_SESSION[$name]))?($_SESSION[$name]):(FALSE);
		}
		return $_SESSION;
	}
	/**
	* 设置session
	* @param 名称 $name
	* @param 值 $value
	*/
	public static function set($name = null, $value = null){
		$_SESSION[$name] = $value;
		return $value;
	}
	/**
	* 删除session
	* @param 名称 $name
	* @return string
	*/
	public static function del($name = null){
		if(empty($name)){
			session_destroy();
		}else{
			$_SESSION[$name] = NULL;
		}
		return $name;
	}
}