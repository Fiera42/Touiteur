<?php

namespace touiteur\action;

use touiteur\User\User;

class ActionUnFollowUser extends Action {
    public function execute() : string {
        $user1 = User::getUserFromId($_GET['iduser']); //the id of the user we want to follow
        if(isset($_SESSION['user'])) {
            $user2 = $_SESSION['user'];
            $user2->unfollowUser($user1);
            $_GET['action'] = "lookUser";
            $action = new ActionLookUser();
        }else{
            $_GET['action'] = "register" ;
            $action = new ActionRegister();
        }
        return $action->execute();
    }
}