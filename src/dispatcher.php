<?php
namespace touiteur;

use touiteur\Action\ActionLookUser;
use touiteur\Action\ActionSearch;
use touiteur\Action\ActionTouite;
use touiteur\Action\ActionSignin;
use touiteur\Action\ActionVote;
use touiteur\Action\ActionDestroyTouite;
use touiteur\Action\ActionChangePage;
use touiteur\Action\ActionFollowTag;
use touiteur\Action\ActionUnfollowTag;
use touiteur\Action\ActionFollowUser;
use touiteur\Action\ActionUnfollowUser;
use touiteur\Action\ActionLookTouite;
use touiteur\Action\ActionRegister;
use touiteur\Action\ActionDisplayPage;
use touiteur\Auth\ConnexionFactory;

class Dispatcher {
    public static function run() : void {
        if(!isset($_GET['action'])) $_GET['action'] = "";
    
        $action = null;
    
        switch ($_GET['action']) {
            case 'lookUser':
                $action = new ActionLookUser();
                break;
            case 'search':
                $action = new ActionSearch();
                break;
            case 'touite':
                $action = new ActionTouite();
                break;
            case 'sign-in':
                $action = new ActionSignin();
                break;
            case 'vote':
                $action = new ActionVote();
                break;
            case 'destroytouite':
                $action = new ActionDestroyTouite();
                break;
            case 'changepage':
                $action = new ActionChangePage();
                break;
            case 'followtag':
                $action = new ActionFollowTag();
                break;
            case 'unfollowtag':
                $action = new ActionUnfollowTag();
                break;
            case 'followuser':
                $action = new ActionFollowUser();
                break;
            case 'unfollowuser':
                $action = new ActionUnfollowUser();
                break;
            case 'looktouite':
                $action = new ActionLookTouite();
                break;
            case 'register':
                $action = new ActionRegister();
                break;
            default:
                $action = new ActionDisplayPage();
                break;
        }

        self::renderPage($action);
    }

    private static function renderPage($action) : void {

        if(isset($action)) $page = $action->execute();
        else $page = "";

        $hideConnection = (isset($_SESSION['user']))?"style='display:none'":"";
        $hidePost = (!isset($_SESSION['user']))?"style='display:none'":"";
        if($_GET['action'] == "lookuser" || $_GET['action'] == "looktag" || $_GET['action'] == "looktouite") {
            $hideConnection = "style='display:none'";
            $hidePost = "style='display:none'";
        }

        $html = "<!DOCTYPE html>
        <html lang=\"fr\">
           <head>
              <title>Touiteur</title>
              <meta charset=\"utf-8\">
              <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
              <link rel=\"stylesheet\" href=\"css/mainStyle.css\">
           </head>
        
           <body>
            <header>
                <img class=\"logo\" src=\"./ressource/Logo.png\" alt=\"Le logo de touiteur\">
            </header>
        
            <nav class=\"nav\">
                <a class=\"nav\" href=\"index.html\"><button class=\"stylized\">home</button></a>
                <a class=\"nav\" href=\"index.html?action=lookUser&iduser=id\"><button class=\"stylized\">profile</button></a>
                <a class=\"nav\" href=\"index.html?action=search\"><button class=\"stylized\">search</button></a>
            </nav>
        
            <aside class=\"mascotte\">
                <img src=\"https://media.tenor.com/BXQgJskV7LgAAAAj/9999.gif\">
            </aside>
        
            <div id=\"feed\">
                <!-- NOTE : only visible for connected user-->
                <form class=\"post\" method=\"post\" action=\"index.html?action=touite\" {$hideConnection}>
                    <h2>Envoyer un touite</h2>
                    <textarea name=\"text\" maxlength=\"235\" placeholder=\"Votre incroyable touite ici\"> </textarea>
                    <input type=\"file\" name=\"img\">
                    <input type=\"text\" name=\"img_desc\" placeholder=\"Une description de votre image\">
                    <button type=\"submit\">Envoyer le touite</button>
                </form>
        
                <!-- when not connected, show that instead -->
                <form class=\"connection\" method=\"post\" action=\"index.html?action=sign-in\" {$hidePost}>
                    <h2>Se connecter</h2>
                    <p>Email</p>
                    <input type=\"email\" name=\"email\" placeholder=\"Email\">
                    <p>Mot de passe</p>
                    <input type=\"password\" name=\"password\" placeholder=\"Mot de passe\">
                    <button type=\"submit\">Connexion</button>
                </form>
                ".$page."
            </div>
           </body>
        <html>";

        echo $html;
    }
}