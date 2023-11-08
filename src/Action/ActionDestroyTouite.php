<?php

namespace touiteur\action;

class ActionDestroyTouite extends Action {
    public function execute() : string {
        $_GET['idtouite']; //the id of the touite we want to destory
        $html='reload the page';
        return $html;
    }
}