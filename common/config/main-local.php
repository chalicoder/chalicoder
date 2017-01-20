<?php
$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
            'tablePrefix'=>'charlie_',

            //master
            'masterConfig' => [
                'username' => 'root',
                'password' => '',
                'attributes' => [
                    PDO::ATTR_TIMEOUT => 10,
                ],
            ],
            'masters' => [
                ['dsn' => 'mysql:host=localhost;dbname=charlie'],
            ],

            //slave
            'slaveConfig' => [
                'username' => 'jack',
                'password' => 'jinxiang',
                'attributes' => [
                    PDO::ATTR_TIMEOUT => 10,
                ],
            ],
            'slaves' => [
                ['dsn' => 'mysql:host=139.196.203.158;dbname=charlie'],
            ]
        ],

        //后台
        'db_admin' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=charlie_admin',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix'=>'charlie_'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];

return $config;