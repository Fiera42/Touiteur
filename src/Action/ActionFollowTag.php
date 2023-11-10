<?php

namespace touiteur\Action;

use touiteur\Tag\Tag;
use touiteur\User\User;

class ActionFollowTag extends Action {
    public function execute() : string {
        $tag=Tag::getTagFromid($_GET['idtag']); //the id of the tag we want to follow
        if(isset($_SESSION['user'])) {
            $user2 = $_SESSION['user'];
            $user2->followTag($tag);
            $_GET['action'] = "looktag";
            $action = new ActionLookTag();
        }else{
            $_GET['action'] = "register" ;
            $action = new ActionRegister();
        }
        return $action->execute();
    }
}