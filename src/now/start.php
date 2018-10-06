<?php
/* ======== 启动类 =======
 * Author:Tiuon.com
 * ====================== */
namespace library;

// 定义编码
header("Content-type:text/html;charset=utf-8");

// 加载基础文件
require(__DIR__ . DIRECTORY_SEPARATOR . 'base.php');

// 判斷應用文件夾是否已創建
if(is_dir(APP_PATH) == false){
	Build::init();
}

// 执行应用
Engine::start();