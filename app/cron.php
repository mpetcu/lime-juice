<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 10.10.2015
 */
use Phalcon\DI\FactoryDefault\CLI as CliDI,
    Phalcon\CLI\Console as ConsoleApp;

try {


    // Using the CLI factory default services container
    $di = new CliDI();

    // Define path to application directory
    defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__)));

    // Load necessary
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(
        array(
            APP_PATH . '/tasks',
            APP_PATH . '/models',
            APP_PATH . '/library',
            APP_PATH . '/library/PHPExcel/Classes/'
        )
    );
    $loader->register();

    // Load the conf
    if (is_readable(APP_PATH . '/config/config.php')) {
        $config = include APP_PATH . '/config/config.php';
        $di->set('config', $config);
    }
    // Mongo connection
    $di->set('mongo', function () use ($config) {
        if (!$config->mongo->user OR !$config->mongo->pass) {
            $mongo = new MongoClient('mongodb://' . $config->mongo->host);
        } else {
            $mongo = new MongoClient("mongodb://" . $config->mongo->user . ":" . $config->mongo->pass . "@" . $config->mongo->host, array("db" => $config->mongo->dbname));
        }
        return $mongo->selectDb($config->mongo->dbname);
    }, false);

    // Load collection nanager
    $di->set('collectionManager', function () {
        return new \Phalcon\Mvc\Collection\Manager();
    });

    // Create a console app
    $console = new ConsoleApp();
    $console->setDI($di);

} catch (Exception $e) {
    echo $e->getMessage();
}

try {
    $console->handle();
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
}
