<?php

namespace touiteur\action;

class ActionSearch extends Action {
    public function execute() : string {
        $_GET['iduser']; //the user we want to search
        $_GET['idtag']; //the tag we want to search
        $html='Page de l\'utilisateur ou du tag, si non trouvé affiché à l\écran un message disant qu\'il n\'y a eu aucun resultat';
        return $html;
    }
}