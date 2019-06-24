<?php

// [ 应用入口文件 ]

// 定义运行目录
define('WEB_PATH', __DIR__ . '/');

//定义微擎
define('WE7_THINKPATH', __DIR__ . '../../../vendor/topthink/thinkphp');

// 定义应用目录
define('APP_PATH', WEB_PATH . '../source/application/');

// 加载框架引导文件
require APP_PATH . '../start.php';