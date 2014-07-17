<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Ford: The Project',

	// preloading 'log' component
	'preload'=>array('log'),

    //default controller to be called
    'defaultController' => 'pages',


    // auto-loading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.vendor.*',
        'ext.yii-mail.YiiMailMessage',
        'ext.EUploadedImage',
        'ext.YiiMailer.YiiMailer',

	),

	'modules'=>array(
	),

	// application components
	'components'=>array(
        'session' => array (
            'autoStart' => true,
            'sessionName' => 'FORDSESSION',
            'cookieMode' => 'only',
            'savePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'../runtime/',
            'timeout' => 3600
        ),
        'services'=>array(
            'class' => 'application.extensions.Services',
        ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
            'caseSensitive'=>false,
			'rules'=>array(
                //- <hostname>
                '/' => 'pages/index',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    		),
		),

        'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

        'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, trace, info',
				),
				// uncomment the following to show log messages on web pages
                array(
                    'class' => 'CWebLogRoute',
                    'enabled' => YII_DEBUG,
                    'levels' => 'error, warning, trace, notice',
                    'categories' => 'application',
                    'showInFireBug' => false,
                ),
			),
		),
    ),

    'params'=>array(
        //this is used in contact page
        'SITE'              => 'form',
        'ENV'               => 'base',
        'SITE_ID'           => '00012',
        'APP_ID '           => '51002',
        'LANG'              => 'en',
        'MEDIA_FB'          => 'facebook',
        'MEDIA_YT'          => 'youtube',
        'MEDIA_BASE'        => 'base',
        'BASE'              => 1,
        'YOUTUBE'           => 2,
        'FACEBOOK'          => 3,
        'MAX_USER_UPLOAD'   => 5,
        'videoExtensions'   => array('MP4', 'MPEG4', 'AVI','MOV'),
        'imageExtensions'   => array('JPG', 'GIF', 'PNG','JPEG'),
        'fileExtensions'    => array('DOC', 'PDF','PPT','PPS','PPTX',"DOCX"),
        'videoMaxSize'      => 150,
        'DOCMaxSize'        => 2,
        'imageMaxSize'      => 5,
        'PDFMaxSize'        => 1,
        'PPTMaxSize'        => 5,
        'PPTXMaxSize'       => 5,
        'DOCXMaxSize'       => 2,
        'PPSMaxSize'        => 5,
        'extension'         => 'jpg',
        'contentImageName'  => 'content_',
        'uploadPath'        => 'upload',
        'EMAIL_FROM'        => '',
        'EMAIL_TO'          => '',
        'EMAIL_DEV_TESTING' => false,
        'thumb'             => 'thumb',
        'celebrity'         => array(
                                    'rocky' => array('name'=>'Rocky & Mayur', 'img' => 'rocky-mayur.png','color'=>'yellow'),
                                    'gaurav' => array('name' => 'Gaurav Kapoor', 'img' => 'gaurav-kapoor.png','color'=>'orange'),
                                    'anushka' => array('name' => 'Anushka Dandekar', 'img' => 'anushka-dandekar.png','color'=>'green' )
                                ),
    ),
);