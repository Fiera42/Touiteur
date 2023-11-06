<?php

namespace touiteur\action;

use touiteur\Action\Action;
use touiteur\Auth\Auth;

class ActionAdUser extends Action
{
    public function execute(): string
    {
        $methode = $_SERVER['REQUEST_METHOD'];
        if ($methode==='GET')
        {$html='Veuillez rentrer vos identifiants et mot de passe<br>
                <form action ="?action=add-user" method = "post">
                Identifiant :<br><input type="text" name="identifiants"/><br>
                Passwd : <br><input type="password" name="passwd">
                <button type="submit">Connexion</button>
                </form><br>';}
        else if ($methode === 'POST')
        {
            $identifiant = $_POST['Identifiant'];
            $mdp = $_POST["passwd"];
            if(Auth::authenticate($identifiant,$mdp))
            {
                $html = "Vous êtes connecté";
            }
            else{
                Auth::register($identifiant,$mdp);
                $html = "Création de votre compte terminé <br>";
            }

        }
        return $html;
    }
}