<?php
namespace touiteur;

use touiteur\src\Action\ActionLookUser;
use touiteur\src\Action\ActionSearch;
use touiteur\src\Action\ActionTouite;
use touiteur\src\Action\ActionSignin;
use touiteur\src\Action\ActionVote;
use touiteur\src\Action\ActionDestroyTouite;
use touiteur\src\Action\ActionChangePage;
use touiteur\src\Action\ActionFollowTag;
use touiteur\src\Action\ActionUnfollowTag;
use touiteur\src\Action\ActionFollowUser;
use touiteur\src\Action\ActionUnfollowUser;
use touiteur\src\Action\ActionLookTouite;
use touiteur\src\Action\ActionRegister;
use touiteur\src\Auth\ConnexionFactory;

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
        }

        self::renderPage($action);
    }

    private static function renderPage(Action $action) : void {

        if(isset($action)) $page = $action->execute();

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <title>Exemple</title>
        </head>
        <body>
        <a href="?action=sign-in"> connect </a></br>
        <a href="?action=add-user"> add user </a></br>
        <a href="?action=add-playlist"> add playlist </a></br>
        <a href="?action=add-podcasttrack"> add podcast track </a></br>
        <a href="?action=display-playlist"> display playlist </a></br>
        <a href="?action="> default </a></br></br>
        '.$page.'
        </body>
        </html>';

        echo $html;
    }
}