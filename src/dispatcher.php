<?php
namespace touiteur;

use touiteur\src\Action\ActionAbonnerTag;
use touiteur\src\Action\ActionDetail;
use touiteur\src\Action\ActionEffacer;
use touiteur\src\Action\ActionEvaluer;
use touiteur\src\Action\ActionPersonne;
use touiteur\src\Action\ActionRetour;
use touiteur\src\Action\ActionSuivre;
use touiteur\src\Action\ActionTag;
use touiteur\src\Action\ActionTouiter;
use touiteur\src\Auth\ConnexionFactory;

class Dispatcher {
    public static function run() : void {
        if(!isset($_GET['action'])) $_GET['action'] = "";
    
        $action = null;
    
        switch ($_GET['action']) {
            case 'lookUser':
                $action = new ConnectUser();
                break;
            case 'search':
                $action = new AddUser();
                break;
            case 'touite':
                $action = new AddPlaylist();
                break;
            case 'sign-in':
                $action = new AddPodcastTrack();
                break;
            case 'vote':
                $action = new DisplayPlaylist();
                break;
            case 'destroytouite':
                $action = new DisplayPlaylist();
                break;
            case 'changepage':
                $action = new DisplayPlaylist();
                break;
            case 'followtag':
                $action = new DisplayPlaylist();
                break;
            case 'unfollowtag':
                $action = new DisplayPlaylist();
                break;
            case 'followuser':
                $action = new DisplayPlaylist();
                break;
            case 'unfollowuser':
                $action = new DisplayPlaylist();
                break;
            case 'looktouite':
                $action = new DisplayPlaylist();
                break;
            case 'register':
                $action = new DisplayPlaylist();
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