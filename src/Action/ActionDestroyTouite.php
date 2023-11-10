<?php

namespace touiteur\Action;

use touiteur\User\Touite;
use touiteur\User\User;

class ActionDestroyTouite extends Action {
    public function execute() : string {
        $Touite=Touite::getTouiteFromId($_GET['idtouite']);//the id of the touite we want to destroy
        $user= User::getUserFromId($_SESSION['iduser']);
        $Touite->deleteTouite($user);
        $_GET['action'] = '';
        $action = new ActionDisplayPage();
        return $action->execute();
    }
}