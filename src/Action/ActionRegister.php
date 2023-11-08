<?php

namespace touiteur\action;

use touiteur\Auth\Auth;

class ActionRegister extends Action {
    public function execute() : string {
        $name=$_POST['name']; //the name of the register user
        $fullllllllnammmeeeeeee=$_POST['fullname']; //the fullname of the registering user
        $mail=$_POST['email']; //the email of the registering user
        $passwd=$_POST['password']; //the password of the registering user
        if(Auth::register($mail,passwd,1,$name,$fullllllllnammmeeeeeee))
        {
            $acttion=new ActionDisplayPage();
            $html=$acttion->execute();
        }else {
            $html ='else we show the register page (compte deja existant)';
        }
        return $html;
    }
}