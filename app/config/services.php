<?php

use Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View\Engine\Volt,
    Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
    Phalcon\Session\Adapter\Files as SessionAdapter,
    Phalcon\Http\Response\Cookies,
    Phalcon\Events\Manager as EventsManager,
    Utility\Utility,
    Utility\HTMLMinify;


/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Set config as service
 */
$di->set('config', $config);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/* Router service */
$di->set('router', function () {
    return include "routes.php";
},true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config, $di) {
    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    $view->registerEngines([
        '.volt' => function ($view, $di) use ($config) {
            $volt = new Volt($view, $di);
            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
                'compileAlways' => $config->devEnv
            ]);
            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ]);
    return $view;
}, true);

/* MONGO connection is created based in the parameters defined in the configuration file */
$di->set('mongo', function () use ($config) {
    try {
        if (!$config->mongo->user OR !$config->mongo->pass) {
            $mongo = new MongoClient('mongodb://' . $config->mongo->host);
        } else {
            $mongo = new MongoClient("mongodb://" . $config->mongo->user . ":" . $config->mongo->pass . "@" . $config->mongo->host, array("db" => $config->mongo->dbname));
        }
    }catch(Exception $e){
        return $e;
    }
    return $mongo->selectDb($config->mongo->dbname);
}, false);

$di->set('collectionManager', function(){
    return new \Phalcon\Mvc\Collection\Manager();
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

// Start the session the first time some component request the session service
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});

// Cookie service
$di->set('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption(false);
    return $cookies;
});

$di->set('flash', function () {
    $flash = new Phalcon\Flash\Session(
        [
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        ]
    );
    return $flash;
});

$di->set('utility', function() {
    return new Utility();
});

$di->set('mail', function () use ($config) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->CharSet      = 'UTF-8';
        $mail->Host         = $config->mail->host;
        $mail->SMTPAuth     = $config->mail->SMTPAuth;
        if($config->mail->user)
            $mail->Username     = $config->mail->user;
        if($config->mail->pass)    
            $mail->Password     = $config->mail->pass;
        if($config->mail->security)
            $mail->SMTPSecure   = $config->mail->security;
        if($config->mail->port)
            $mail->Port         = $config->mail->port;
        $mail->SMTPKeepAlive = true;
        $mail->setFrom($config->mail->email);

        ob_start();
        $mail->SMTPDebug = 0;
        $error = false;
        if($mail->smtpConnect()){
            $mail->SMTPDebug = false;
        }else{
            $error = true;
        }
        $errorMessage = ob_get_contents();
        ob_end_clean();
        if($error) {
            throw new Exception($errorMessage);
        }
    }catch (Exception $e) {
        return $e;
    }
    return $mail;
});

$di->set('dispatcher', function() use ($di) {
        $em = new EventsManager();
        $em->attach("dispatch:beforeException",
            function($event, $dispatcher, $exception){
                switch ($exception->getCode()) {
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward(
                            array(
                                'controller' => 'index',
                                'action'     => 'error404',
                            )
                        );
                        return false;
                    default:
                        //return true;
                        $dispatcher->forward(
                            array(
                                'controller' => 'index',
                                'action'     => 'error404',
                            )
                        );
                        return false;
                }
            }
        );
        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($em);
        return $dispatcher;
    },
    true
);

