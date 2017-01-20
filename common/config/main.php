<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'runtimePath' => '@app/runtime',
    'timezone' => 'PRC',
    'language' => 'zh-CN',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [

        ],
        'i18n' => [
        	'translations' => [
        		'*' => [
        			'class' => 'yii\i18n\PhpMessageSource',
        			'basePath' => '@common/messages',
        			'fileMap' => [
        				'common' => 'common.php',
        				'backend' => 'backend.php',
        				'frontend' => 'frontend.php',
        			],
        		],
        		'on missingTranslation' => ['\backend\modules\i18n\Module', 'missingTranslation']
        	], 
        ],
       /* 'aliases' => [
        	'@config' => '@common/modules/config',
        ],*/
        'as locale' => [
        	'class' => 'common\behaviors\LocaleBehavior',
        	'enablePreferredLanguage' => true,
        ],
    ],
];