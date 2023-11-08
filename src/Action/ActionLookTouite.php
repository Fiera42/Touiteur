<?php

namespace touiteur\action;

use touiteur\User\Touite;

class ActionLookTouite extends Action {
    public function execute() : string {
        ; //the id of the user we want to look closely
        $touite = Touite::getTouiteFromId($_GET['idtouite']);
        return $touite->displayDetaille();
    }
}