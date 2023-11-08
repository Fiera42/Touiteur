<?php

namespace touiteur\action;

class ActionPersonne extends Action {
    public function execute() : string {
        $_GET['idtag']; //the id of the tag we want to un follow
        $html='reload the page';
        return $html;
    }
}