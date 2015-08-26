<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'mxtel',
    'defaultController'=>'org/index',

	// preloading 'log' component
	'preload'=>array(
        'log',
        'bootstrap',
    ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'qwertyui',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('10.112.28.19','10.112.28.33','127.0.0.1','::1'),
            'generatorPaths' => array(
                'bootstrap.gii'
            ),
		),

	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        'bootstrap' => array(
            'class' => 'ext.yiibooster.components.Bootstrap',
            'coreCss'=>false,
            'jqueryCss'=>false,
            'responsiveCss'=>false,
            'yiiCss'=>false,
        ),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=asterisk',
			'emulatePrepare' => true,
			'username' => 'asterisk',
			'password' => '',
			'charset' => 'utf8',
            'enableParamLogging'=>true,
            'enableProfiling'=>true,
		),
        'db2'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=compred',
            'emulatePrepare' => true,
            'username' => 'asterisk',
            'password' => '',
            'charset' => 'utf8',
            //'enableParamLogging'=>true,
            //'enableProfiling'=>true,
            'class' => 'CDbConnection',
        ),
        /*'db'=>array(
            'connectionString' => 'mysql:host=10.112.30.70;dbname=asterisk',
            'emulatePrepare' => true,
            'username' => 'asterisk',
            'password' => '12345',
            'charset' => 'utf8',
        ),*/

        'cache'=>array(
            'class'=>'system.caching.CMemCache',
            'servers'=>array(
                array('host'=>'localhost', 'port'=>11211, 'weight'=>10),
            ),
        ),
        /*'cache'=>array(
            'class'=>'system.caching.CApcCache',
        ),*/

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
                    /*'levels'=>'trace',
                    'categories'=>'system.db.*',
                    'logFile'=>'sql.log',*/
				),
                /*array(
                    'class'=>'CWebLogRoute',
                ),*/
                array(
                    // направляем результаты профайлинга в ProfileLogRoute (отображается
                    // внизу страницы)
                    'class'=>'CProfileLogRoute',
                    'levels'=>'profile',
                    'enabled'=>true,
                ),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
        'Smtpmail'=>array(
            'class'=>'application.extensions.smtpmail.PHPMailer',
            'Host'=>"smtp.mail.ru",
            'Username'=>'calc@list.ru',
            'Password'=>'Visual73851',
            'Mailer'=>'smtp',
            'Port'=>25,
            'SMTPAuth'=>true,
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);