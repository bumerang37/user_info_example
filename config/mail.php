<?php

use yii\debug\models\search\Mail;

return
    [
        'class' => 'yii\swiftmailer\Mailer',
        'viewPath' => '@views/mail',
        'useFileTransport' => true,//set this property to false to send mails to real email addresses
        //comment the following array to send mail using php's mail function
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'username@gmail.com',
            'password' => 'password',
            'port' => '587',
            'encryption' => 'tls',
        ],
    ];