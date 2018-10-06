<?php
/* ======== 初始化类 =======
 * Author:Tiuon.com
 * ======================== */
namespace library;
class Build{
	static public function init(){
		$app_path = str_replace(array('\\','/'), DS, APP_PATH);
		$res_path = str_replace(array('\\','/'), DS, RES_PATH);
		// 
		$config = array(
		    'file' => array(
		        'app'    => $app_path,
		        'res'    => $res_path . 'template' . DS,
		        'module' => $app_path . strtolower(C('MODULE')) . DS,
		        'common' => $app_path . C('DEFAULT_C_COMMON') . DS
		    ),
		    'module'		=> strtolower(C('MODULE')),
		    'common'		=> C('DEFAULT_C_COMMON'),
		    'config'		=> C('DEFAULT_C_CONFIG'),
		    'controller'	=> C('DEFAULT_C_LAYER'),
		    'model'			=> C('DEFAULT_M_LAYER'),
		    'lang'			=> C('DEFAULT_LANG_PATH'),
		    'view'			=> C('TMP_VIEWS')
		);
       // 判斷應用文件夾是否存在
	   if(!file_exists($config['file']['app'])){
            $directory = array(
                $config['file']['common'] . $config['common'],							// common
                $config['file']['common'] . $config['config'],							// config
                $config['file']['common'] . $config['lang'],							// lang
                $config['file']['module'] . $config['controller'],						// controller
                $config['file']['module'] . $config['model'],							// controller
                $config['file']['module'] . $config['common']. DS . $config['config'],	// common/config
                $config['file']['module'] . $config['view']  . DS . $config['common'],	// view/common
                $config['file']['module'] . $config['view']  . DS . $config['module']	// view/Index
            );
            $file = array(
                $config['file']['res'] . "demo". DS ."function.php"		=> $config['file']['common'] . $config['common']  . DS . "function.php",
                $config['file']['res'] . "demo". DS ."convention.php"	=> $config['file']['common'] . $config['config'] . DS . "config.php",
                $config['file']['res'] . "demo". DS ."config.php"		=> $config['file']['module'] . $config['common'] . DS . $config['config'] . DS . "config.php",
                $config['file']['res'] . "demo". DS ."common.php"		=> $config['file']['module'] . $config['controller'] . DS . "commonController.class.php",
                $config['file']['res'] . "demo". DS ."index.php"		=> $config['file']['module'] . $config['controller'] . DS . "indexController.class.php",
                $config['file']['res'] . "demo". DS ."model.php"		=> $config['file']['module'] . $config['model'] . DS . "indexModel.class.php",
                $config['file']['res'] . "demo". DS ."default.php"		=> $config['file']['module'] . $config['view'] . DS . $config['module'] . DS . "index.html"
            );
            // 生成目标文件
            foreach($directory as $key){
                make_dir($key);
            }
            // 生成php文件
            foreach($file as $key => $value){
                CreateFile($key, $value);
            }
	    }
        // 判斷應用公共文件夾是否存在
        if(!file_exists($config['file']['public'])){
            make_dir($config['file']['public']);
        }
        // 判斷vendor文件夾是否存在
        if(!file_exists($config['file']['vendor'])){
            make_dir($config['file']['vendor']);
        }
	}
}