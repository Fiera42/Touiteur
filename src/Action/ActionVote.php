<?php

namespace touiteur\action;

class ActionPersonne extends Action {
    public function execute() : string {
        $_GET['idtouite']; //the id of the tag we want to look vote to
        $_GET['value']; //True if positive vote, false if negative vote
        //make the changes of the db
        $html='Nothing';
        return $html;
    }
}