<?php
return new \Phalcon\Config([
    'mongo'  => [
        'host'     => 'localhost:27017',
        'user' => '',
        'pass' => '',
        'dbname'   => 'rbt',
    ],
    'application' => [
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'formsDir'       => __DIR__ . '/../../app/forms/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'publicDir'      => __DIR__ . '/../../public/',
        'baseUri'        => 'http://mpetcu.netx.ro/',
    ],
    'mail' => [
        'host'         => 'smtp.gmail.com',
        'user'         => 'mihai.costin.petcu@gmail.com',
        'pass'         => 'pmc252525',
        'security'     => 'ssl',
        'port'         => 465,
        'SMTPAuth'     => true,
        'email'        => 'mihai.costin.petcu@gmail.com'
    ],
    'devEnv' => true,
    'hash' => '233bc15198fad59d9ec2fa192e4b058c74ea7757',
    'reportsPath' => 'reports/'
]);
