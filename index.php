<?php
//----------------------Autoload

require_once composer;

use \iutnc\deefy\db\ConnexionFactory;
use \iutnc\deefy\dispatcher\Dispatcher;

//Init db connexion

ConnexionFactory::setConfig("./config/config.ini");

//----------------------Programme

//$email = 'user1@mail.com';
//$passwd = 'user1';
//$role = 1;

session_start();

Dispatcher::run();

//---------------------------------------