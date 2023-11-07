<?php

//----------------------Autoload

use \touiteur\Auth\ConnexionFactory;
use \touiteur\Dispatcher;

require_once 'src/Loader/Autoloader.php';

$loader = new \touiteur\loader\Autoloader('touiteur', 'src');
$loader->register();

//----------------------db connection

ConnexionFactory::setConfig("./config/config.ini");
ConnexionFactory::makeConnection()

//----------------------Programme

session_start();

Dispatcher::run();

//---------------------------------------