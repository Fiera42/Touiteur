<?php

namespace touiteur\action;

class ActionRegister extends Action {
    public function execute() : string {
        $_POST['name']; //the name of the register user
        $_POST['fullname']; //the fullname of the registering user
        $_POST['mail']; //the email of the registering user
        $_POST['password']; //the password of the registering user
        $html='page';
        return $html;
    }
}