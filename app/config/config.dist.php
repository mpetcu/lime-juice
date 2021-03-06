<?php
/**
 * Please rename this file to "config.php" after your setup is ready
 */
return new \Phalcon\Config([
    'mongo'  => [
        'host'     => '', //mongo hostname required (default port :27017)
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
        'publicDir'      => __DIR__ . '/../../public/',
        'baseUri'        => '', //full app path. Must end with '/' (Eg. http://mydomain.com/reporter/)
    ],
    'mail' => [
        'host'         => '', //hostname required
        'port'         => '', //(465, 578, ...)
        'security'     => '', //(ssl, tls ...)
        'SMTPAuth'     => false, //(SMTP Authentication true or false)
        'user'         => '',
        'pass'         => '',
        'email'        => '' //sender email address
    ],
    'devEnv' => false, //run as development enviroment (bool)
    'hash' => '233bc15198fad59d9ec2fa192e4b058c74ea7757', //should change it with your hash
    'reportsPath' => 'reports/' //name of directory where reports will be saved
]);
