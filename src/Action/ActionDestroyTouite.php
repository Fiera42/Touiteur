<?php

namespace touiteur\Action;

use touiteur\User\Touite;
use touiteur\User\User;

class ActionDestroyTouite extends Action {
    public function execute() : string {
        if(isset($_SESSION['user'])) {
            $Touite=Touite::getTouiteFromId($_GET['idtouite']);//the id of the touite we want to destroy
            $user= $_SESSION['user'];
            $Touite->deleteTouite($user);
        }
        
        $_GET['action'] = '';
        $action = new ActionDisplayPage();
        return $action->execute();
    }
}