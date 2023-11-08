<?php

namespace touiteur\action;

use touiteur\User\User;

class ActionUnFollowUser extends Action {
    public function execute() : string {
        $user1 = User::getUserFromId($_GET['iduser']); //the id of the user we want to follow
        $user2 = User::getUserFromId(($_SESSION['iduser']));
        $user2->UnfollowUser($user1);
        $_GET['action'] = "lookuser";
        return new ActionLookUser()->execute();
    }
}