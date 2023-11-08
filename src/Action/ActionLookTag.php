<?php

namespace touiteur\action;

class ActionLookTag extends Action {
    public function execute() : string {
        $_GET['idtag']; //the id of the user we want to look at
        $html='Page de la personne avec ces touites les plus récents (TO DO)';
        return $html;
    }
}