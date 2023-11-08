<?php

namespace touiteur\action;

class ActionSignin extends Action {
    public function execute() : string {
        $_GET['iduser']; //the id of the user we want to look at
        $_POST['mail']; //the mail of the person wanting to signin
        $_POST['password']; //the password of the person wanting to signin
        //register the person into the session
        $html='if signed in, nothing, else we show the sign-in page';
        return $html;
    }
}