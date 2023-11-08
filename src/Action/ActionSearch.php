<?php

namespace touiteur\action;

use touiteur\action\ActionLookUser ;

class ActionSearch extends Action {
    public function execute() : string {
        /**$_GET['iduser']; //the user we want to search
        $_GET['idtag']; //the tag we want to search
        $html='Page de l\'utilisateur ou du tag, si non trouvé affiché à l\écran un message disant qu\'il n\'y a eu aucun resultat';
        return $html;*/

    if ($_GET['iduser'] !== null){
            $_GET['action'] = 'lookUser' ;
            $ActionLookUser = new ActionLookUser() ;
            $html = $ActionLookUser->execute();
    }else if ($_GET['idtag'] !== null){
        $_GET['action'] = 'looktag' ;
        $ActionLookTag = new ActionLookTag() ;
        $html = $ActionLookTag->execute();
    }else{
            $html = "<p> Pas de resultat </p>" ;
        }
        return $html ;

    }
}