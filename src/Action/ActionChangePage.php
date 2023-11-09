<?php

namespace touiteur\action;

use touiteur\User\Touite;

class ActionChangePage extends Action {
    public function execute() : string {
        $touit = Touite::getAllTouite();
        return $touit->displayPage($_GET['page']);
    }
}