<?php
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->formsDir,
        $config->application->libraryDir,
        $config->application->libraryDir."PHPExcel/Classes/"
    ]
);
$loader->register();

//include PHPMailer
include_once $config->application->libraryDir."PHPMailer/PHPMailerAutoload.php";



