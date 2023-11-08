<?php

namespace touiteur\action;

class ActionFollowUser extends Action {
    public function execute() : string {
        $_GET['iduser']; //the id of the user we want to follow
        $html='Reload the page';
        return $html;
    }
}