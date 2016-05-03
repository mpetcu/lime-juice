#Lime Juice

A small project based on Phalcon Framework where you can run SQL queries and exports output in CSV.
You can manage many **database connections**, each one will hold **many queries**.

Queries can be executed manually or with **cron job** *(using crontab)*. 

A small user account management is provided. There are 2 account types:
- **master** - access everything.  
- **operator**  - can see and run queries.


##Install & configure##
**Requirements:**
  - *PHP >= 5.3 - http://php.net/downloads.php*;
  - *PHP extension php_xml and php_zip enabled (required by PHPExcel)*;
  - *Phalcon Framework >= 2.0 - https://phalconphp.com/en/download*; 
  - *MongoDB(service & PHP extension) - https://www.mongodb.org/downloads*;

**How to:**

**Step 1**: Find **app/config/config.dist.php** *(Report manager root directory)* and change it with your own setup:
```
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
        'baseUri'        => '', //full app path. Must end with '/' (http://mydomain.com/reporter/)
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
```

**Step 2**: Set **recursively read & write** permissions to **public/reports/** directory and **read, write & execute** to **app/cache/**.

**Step 3**: Access your **Report manager** base path *(defined in app/config/config.php as baseUri)* and follow instructions. 

**Step 4**: In order to work properly, you should add the following job to your **crontab** *(5 min. granulation)*: 
```
*/5 * * * * php /path/to/your/reporter-manager/app/cron.php
```
**And ... Ready to run :)**

##Special thanks to##
- *PHP Cron Expression Parser* - https://github.com/mtdowling/cron-expression;
- *PHPMailer* - https://github.com/PHPMailer/PHPMailer;
- *PHPExcel* - https://github.com/PHPOffice/PHPExcel;
- *JQuery* - http://jquery.com;
- *Bootstrap* - http://getbootstrap.com;
   

