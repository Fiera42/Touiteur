<?php

namespace touiteur\action;

class ActionPersonne extends Action {
    public function execute() : string {
        $_GET['iduser']; //the id of the user we want to look at
        $html='Page de la personne avec ces touites les plus récents (TO DO)';
        return $html;
    }
}