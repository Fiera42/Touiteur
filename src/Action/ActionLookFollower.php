<?php

namespace touiteur\Action;

use touiteur\User\User;

class ActionLookFollower extends Action
{

    public function execute(): string
    {
        $user = User::getUserFromId($_GET['iduser']); //the id of the user we want to look at

        $listfollow = "" ;
        foreach ($user->getFollower() as $follow){
            if($follow == "") continue;
            $name = $follow->getDisplayName() ;
            $listfollow.="- ".$name."<br>";
        }

        $html="<div id=\"followable\">
            <h1>Follower de {$user->getDisplayName()}</h1>
            
        </div>";

        $html .= "<div id=\"followable\">
            <p>$listfollow</p>
        </div>";

        return $html;
    }
}