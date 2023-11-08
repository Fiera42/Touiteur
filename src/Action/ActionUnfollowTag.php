<?php

namespace touiteur\Action;

use touiteur\Tag\Tag;
use touiteur\User\User;

class ActionUnfollowTag extends Action {
    public function execute() : string {
        $tag=Tag::getTagFromid($_GET['idtag']); //the id of the tag we want to follow
        $user=User::getUserFromId($_SESSION['iduser']);
        $user->UnfollowTag($tag);
        $html= '';
        return $html;

    }
}