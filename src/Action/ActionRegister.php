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
            <input type="text" name="name" placeholder="Prénom" required>
            <p>Nom</p>
            <input type="text" name="fullname" placeholder="Nom" required>
            <p>Email</p>
            <input type="email" name="mail" placeholder="Email" required>
            <p>Mot de passe</p>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Connexion</button>
        </form>';
        } else if ($_SERVER['REQUEST_METHOD']==='POST') {
            $name = htmlentities($_POST['name']);        
            $fullllllllnammmeeeeeee = htmlentities($_POST['fullname']); 
            $mail =  filter_var($_POST['mail'],FILTER_SANITIZE_EMAIL);                     
            $passwd = ($_POST['password']);                                                       
            if (Auth::register($mail, $passwd, $name, $fullllllllnammmeeeeeee)) {
                $action = new ActionDisplayPage();
                $html = $action->execute();
            } else {
                $html = '<form class="connection" method="post" action="?action=register">
            <h2>S\'inscrire</h2>
            <p>Prénom</p>
            <input type="text" name="name" placeholder="Prénom" required>
            <p>Nom</p>
            <input type="text" name="fullname" placeholder="Nom" required>
            <p>Email</p>
            <input type="email" name="mail" placeholder="Email" required>
            <p>Mot de passe</p>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Connexion</button>
            <p>Votre compte existe déjà</p>
        </form>';
            }
            return $html;
        }return $html;

    }

}