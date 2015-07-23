<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'快伞',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.controllers.*',
		'ext.YiiMongoDbSuite.*',
		'ext.php_AES.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'wanbin',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'curl' => array (
				'class' => 'ext.Curl'
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),
			
		'mongodb' => array(
				'class'            => 'EMongoDB', //主文件
				'connectionString' => 'mongodb://127.0.0.1:27017', //服务器地址
				'dbName'           => 'umbrella',//数据库名称
				'fsyncFlag'        => true, //mongodb的确保所有写入到数据库的安全存储到磁盘
				'safeFlag'         => true, //mongodb的等待检索的所有写操作的状态，并检查
				'useCursor'        => false,
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
					'categories'=>'system.*',
				),
				array (
						'class' => 'CFileLogRoute',
						'levels' => 'trace,error',
						'logFile' => 'trace.log',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'wxvaild'=>false,
		'appid'=>'wxf6de13c469cfd0f0',
		'appsecret'=>'137bac8913e64a3d1b2a3597f9a4bf0b',
		'encodingAesKey'=>'cQ4ydiNGta7ZXosbR9JXjhK98pPMUZz3OnxhX2l0X9D',
		'encodingAesKey2'=>'cQ4ydiNGta7ZXosbR9JXjhK98pPMUZz3OnxhX2l0X9D',
	),
);
