<?php

namespace touiteur\Action;

use touiteur\auth\Auth;
use touiteur\User\User;

class ActionSignin extends Action {
    public function execute() : string {
        $mail=$_POST['email']; //the mail of the person wanting to signin
        $passwd=$_POST['password']; //the password of the person wanting to signin
        if (Auth::authenticate($mail,$passwd))
        {
            $acttion=new ActionDisplayPage();
            $html=$acttion->execute();
        }
        else{$html ='else we show the register page';}
        //register the person into the session
        return $html;
    }
}