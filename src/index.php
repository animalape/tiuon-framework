<?php
/* ======== 入口文件 =======
 * Author:Tiuon.com
 * ======================== */

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为 false true
define('NOW_DEBUG',true);

// 定义应用目录
define('APP_PATH', __DIR__ . '/applie/');

// 引入入口文件
require __DIR__ . '/now/start.php';