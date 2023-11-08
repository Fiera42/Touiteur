<?php

namespace touiteur\Action;

use touiteur\Tag\Tag;
use touiteur\User\User;

class ActionUnfollowTag extends Action {
    public function execute() : string {
        $tag=Tag::getTagFromid($_GET['idtag']); //the id of the tag we want to unfollow
        $user->UnfollowTag($tag);
        $_GET['action'] = "looktag";
        return new ActionLookTag()->execute();
    }
}