<?php

namespace touiteur\action;

use touiteur\User\User;

class ActionLookUser extends Action {
    public function execute() : string {
        $user = User::getUserFromId($_GET['iduser']);; //the id of the user we want to look at

        $hideFollow = ($user->isFollowingUser($_GET['iduser']))?"style='display:none'":"";
        $hideUnFollow = ($hideFollow === "")?"style='display:none'":"";

        $html="<div id=\"followable\">
            <h1>{$user->getDisplayName()}</h1>

            <!-- when the user is not connected, hide -->
            <a class=\"user\" href=\"htmlTemplate_USERPAGE.html?action=followuser&iduser=".$_GET['iduser']."\" $hideFollow><button class=\"stylized\">Suivre</button></a>
            <!-- when the user is following this user, we change the button to this-->
            <a class=\"user\" href=\"htmlTemplate_USERPAGE.html?action=unfollowuser&iduser=".$_GET['iduser']."\" $hideUnFollow><button class=\"stylized\">Ne plus suivre</button></a>
        </div>";

        
        $touitList = $user->getTouit();
        $html .= $touitList->displayAllTouites() ;
        return $html;
    }
}