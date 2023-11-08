<?php

namespace touiteur\action;

use touiteur\User\User;

class ActionLookUser extends Action {
    public function execute() : string {
        ; //the id of the user we want to look at
        $html="<div id=\"followable\">
            <h1></h1>

            <!-- when the user is not connected, hide -->
            <a class=\"user\" href=\"htmlTemplate_USERPAGE.html?action=followuser&iduser=".$_GET['iduser']."\"><button class=\"stylized\">Suivre</button></a>
            <!-- when the user is following this user, we change the button to this-->
            <a class=\"user\" href=\"htmlTemplate_USERPAGE.html?action=unfollowuser&iduser=".$_GET['iduser']."\"><button class=\"stylized\">Ne plus suivre</button></a>
        </div>";

        $user = User::getUserFromId($_GET['iduser']);
        $touitList = $user->getTouit();
        $html .= $touitList->displayAllTouites() ;
        return $html;
    }
}