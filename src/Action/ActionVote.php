<?php

namespace touiteur\Action;

use touiteur\User\Touite;
use touiteur\User\User;

class ActionVote extends Action {
    public function execute() : string {
        if(isset($_SESSION['user']) && isset($_GET['value'])) {
            $touite=Touite::getTouiteFromId($_GET['idtouite']);
            if($_GET['value'] == "true") $bool = true;
            else if($_GET['value'] == "false") $bool = false;
            $user=($_SESSION['user']);
            $touite->evaluateTouite($user,$bool);
        }
        $_GET['action'] = "";
        $action= new ActionDisplayPage();
        return $action->execute();
        
    }
}