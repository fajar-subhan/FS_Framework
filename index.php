<?php

use app\core\App;
use app\Core\Request;

/*
|--------------------------------------------------------------------------
|  Definition of path
|--------------------------------------------------------------------------
|
| Paths that need to be added please add here
|
*/
if(!defined('ROOT_PATH')) define('ROOT_PATH',dirname(__FILE__)); 

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/
require_once 'vendor/autoload.php';

/**
 * PHP dotenv by Vance Lucas & Graham Campbell 
 * 
 * @link https://github.com/vlucas/phpdotenv
 */
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/
$request = new Request();

$setup = 
[
    'request'   => $request,
    'env'       => $_ENV
];

$app = new App($setup);
$app->run();