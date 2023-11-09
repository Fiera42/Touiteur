<?php

namespace touiteur\Action;

use touiteur\User\Touite;
use touiteur\User\User;

class ActionVote extends Action {
    public function execute() : string {
        $touite=Touite::getTouiteFromId($_GET['idtouite']); //the id of the tag we want to look vote to
        $bool=$_GET['value']; //True if positive vote, false if negative vote
        $user=($_SESSION['user']);
        $touite->evaluateTouite($user,$bool);
        //make the changes of the db
        $_GET['action'] = "";
        $action= new ActionDisplayPage();
        return $action->execute();
    }
}