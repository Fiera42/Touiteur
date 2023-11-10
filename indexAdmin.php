<?php

//----------------------Autoload

use touiteur\Action\ActionAdminSignIn;
use \touiteur\Auth\ConnexionFactory;
use \touiteur\DispatcherAdmin;
use touiteur\User\User;

require_once 'src/Loader/Autoloader.php';

$loader = new \touiteur\loader\Autoloader('touiteur', 'src');
$loader->register();

//----------------------db connection

ConnexionFactory::setConfig("./config/config.ini");
ConnexionFactory::makeConnection();

//----------------------Programme

session_start();

if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
    if ($user->getRole()===100){
        DispatcherAdmin::run();
    }else{
        header('HTTP/1.0 403 Forbidden');
    }
}else{
    $action = new ActionAdminSignIn();
    echo $action->execute();
}



//---------------------------------------