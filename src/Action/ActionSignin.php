<?php

namespace touiteur\Action;

use touiteur\auth\Auth;
use touiteur\User\User;

class ActionSignin extends Action {
    public function execute() : string {
        $mail=$_POST['mail']; //the mail of the person wanting to signin
        $passwd=$_POST['password']; //the password of the person wanting to signin
        if (Auth::authenticate($mail,$passwd))
        {
            $html='if signed in, nothing,';
        }
        else{$html ='else we show the sign-in page';}
        //register the person into the session
        return $html;
    }
}