<?php

namespace touiteur\action;

use touiteur\User\User;

class ActionLookUser extends Action {
    public function execute() : string {
        $user = User::getUserFromId($_GET['iduser']);; //the id of the user we want to look at
        $user2 = $_SESSION['user'];
        $hideFollow = ($user2->isFollowingUser($_GET['iduser']))?"style='display:none'":"";
        $hideUnFollow = ($hideFollow === "")?"style='display:none'":"";
        if ($_GET['iduser'] == $_SESSION['user']->getId()){
            $hideFollow = "style='display:none'";
            $hideUnFollow = "style='display:none'";
        }

        $html="<div id=\"followable\">
            <h1>{$user->getDisplayName()}</h1>

            <!-- when the user is not connected, hide -->
            <a class=\"user\" href=\"?action=followuser&iduser=".$_GET['iduser']."\" $hideFollow><button class=\"stylized\">Suivre</button></a>
            <!-- when the user is following this user, we change the button to this-->
            <a class=\"user\" href=\"?action=unfollowuser&iduser=".$_GET['iduser']."\" $hideUnFollow><button class=\"stylized\">Ne plus suivre</button></a>
        </div>";

        
        $touitList = $user->getTouit();
        $html .= $touitList->displayAllTouites() ;
        return $html;
    }
}