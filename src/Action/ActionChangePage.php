<?php

namespace touiteur\action;

class ActionChangePage extends Action {
    public function execute() : string {
        $_GET['page']; //the page we want to go to
        $html='Load the new page';
        return $html;
    }
}