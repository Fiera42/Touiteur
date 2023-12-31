<?php

namespace touiteur\Action;

use touiteur\auth\Auth;
use touiteur\User\User;

class ActionAdminSignIn extends Action {
    public function execute() : string {
        $mail=$_POST['email']; //the mail of the person wanting to signin
        $passwd=$_POST['password']; //the password of the person wanting to signin
        if (Auth::authenticate($mail,$passwd))
        {
            $acttion=new ActionDisplayPage();
            $html=$acttion->execute();
            //register the person into the session
        }
        else{$html ='<form class="connection" method="post" action="?action=adminregister">
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
            <p>Etes vous sur d\'avoir un compte ?</p>
        </form>';}

        return $html;
    }
}