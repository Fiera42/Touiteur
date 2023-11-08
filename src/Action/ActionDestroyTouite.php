<?php

namespace touiteur\Action;

use touiteur\User\Touite;
use touiteur\User\User;

class ActionDestroyTouite extends Action {
    public function execute() : string {
        $Touite=Touite::getTouiteFromId($_GET['idtouite']);//the id of the touite we want to destory
        $user= User::getUserFromId($_SESSION['iduser']);
        $Touite->deleteTouite($user);
        $html='reload the page';
        return $html;
    }
}