<?php
/**
 * Please rename this file to "config.dist.php" after your setup is ready
 */
return new \Phalcon\Config([
    'mongo'  => [
        'host'     => '', //mongo hostname required
        'user' => '', //mongo username optional
        'pass' => '', //mongo password optional
        'dbname'   => '', //mongo database required
    ],
    'application' => [
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'formsDir'       => __DIR__ . '/../../app/forms/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '', //full app path. (http://mydomain.com/reporter)
    ],
    'mail' => [
        'host'         => '', //hostname required
        'user'         => '',
        'pass'         => '',
        'security'     => 'ssl',
        'port'         => 465,
        'email'        => ''//email of sender
    ],
    'devEnv' => false, //run as development enviroment (bool)
    'hash' => '233bc15198fad59d9ec2fa192e4b058c74ea7757' //should change it with your hash
]);
