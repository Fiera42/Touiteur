<?php

namespace touiteur\Action;

use touiteur\User\User;

class ActionLookUser extends Action {
    public function execute() : string {
        $user = User::getUserFromId($_GET['iduser']);; //the id of the user we want to look at
        
        if(isset($_SESSION['user'])) {
            $user2 = $_SESSION['user'];
            $hideFollow = ($user2->isFollowingTag($_GET['idtag']))?"style='display:none'":"";
            $hideUnFollow = ($hideFollow === "")?"style='display:none'":"";
        }
        else {
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

        $html .= "<div id=\"followable\">

            <!-- when the user is not connected, hide -->
            <a class=\"user\" href=\"?action=lookfollower&iduser=".$_GET['iduser']."\"><button class=\"stylized\">Follower</button></a>
            <!-- when the user is following this user, we change the button to this-->
        </div>";

        
        $_SESSION['touites'] = $user->getTouit();
        $html .= $_SESSION['touites']->displayPage(1) ;
        return $html;
    }
}