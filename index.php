<?php
date_default_timezone_set('Asia/Shanghai');
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

require_once('local.php');

if(in_array(SERVER_NAME, array('yicheng','mac'))){
	define('SERVER_TEST',true);
}else{
	define('SERVER_TEST',false);
}

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


defined('DEBUG_URL_PATH') or define('DEBUG_URL_PATH','127.0.0.1');



require_once($yii);
Yii::createWebApplication($config)->run();
