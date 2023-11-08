<?php

namespace touiteur\action;

class ActionPersonne extends Action {
    public function execute() : string {
        $_GET['idtouite']; //the id of the user we want to look closely
        $html='show the touite';
        return $html;
    }
}