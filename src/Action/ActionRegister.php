<?php

namespace touiteur\Action;

use touiteur\Auth\Auth;

class ActionRegister extends Action {
    public function execute() : string
    {
        if ($_SERVER['REQUEST_METHOD']==='GET') {
            $html = '<form class="connection" method="post" action="?action=register">
            <h2>S\'inscrire</h2>
            <p>Prénom</p>
            <input type="text" name="name" placeholder="Prénom">
            <p>Nom</p>
            <input type="text" name="fullname" placeholder="Nom">
            <p>Email</p>
            <input type="email" name="mail" placeholder="Email">
            <p>Mot de passe</p>
            <input type="password" name="password" placeholder="Mot de passe">
            <button type="submit">Connexion</button>
        </form>';
        } else if ($_SERVER['REQUEST_METHOD']==='POST') {
            $name = $_POST['name']; //the name of the register user
            $fullllllllnammmeeeeeee = $_POST['fullname']; //the fullname of the registering user
            $mail = $_POST['mail']; //the email of the registering user
            $passwd = $_POST['password']; //the password of the registering user
            if (Auth::register($mail, $passwd, 1, $name, $fullllllllnammmeeeeeee)) {
                $action = new ActionDisplayPage();
                $html = $action->execute();
            } else {
                $html = 'else we show the register page (compte deja existant)';
            }
            return $html;
        }return $html;

    }

}