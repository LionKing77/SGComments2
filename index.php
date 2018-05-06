<?php

// FRONT CONTROLLER

// Общие настройки
ini_set('display_errors',0);
error_reporting(E_ALL);
//header("Cache-Control: no-store, no-cache, must-revalidate");

session_start();

//настроки
define( 'COMM_SERVER', 'http://comm.vdoske.in.ua/' );//Домен сервеной части комментариев
define( 'DEEP_COMM', 4 );//глубина ответов
define( 'COMM_PER_PAGE', 5 ); //постов на странице
    
// Подключение файлов системы
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Autoload.php');


// Вызов Router
$router = new Router();
$router->run();