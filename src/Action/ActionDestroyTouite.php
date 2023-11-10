<?php

namespace touiteur\Action;

use touiteur\User\Touite;
use touiteur\User\User;

class ActionDestroyTouite extends Action {
    public function execute() : string {

        try {
            if(isset($_SESSION['user'])) {
                $Touite = Touite::getTouiteFromId($_GET['idtouite']);
                $user = $_SESSION['user'];
                $Touite->deleteTouite($user);
            }
        } catch (\Exception $e) {
        }
        

        $_GET['action'] = '';
        $action = new ActionDisplayPage();
        return $action->execute();
    }
}