<?php

namespace touiteur\Action;

use touiteur\Tag\Tag;

class ActionLookTag extends Action {
    public function execute() : string {

        $tag = Tag::getTagFromId($_GET['idtag']);
        $name = $tag->getName() ;
        if(isset($_SESSION['user'])) {
            $user2 = $_SESSION['user'];
            $hideFollow = ($user2->isFollowingTag($_GET['idtag']))?"style='display:none'":"";
            $hideUnFollow = ($hideFollow === "")?"style='display:none'":"";
        }
        else {
            $hideFollow = "style='display:none'";
            $hideUnFollow = "style='display:none'";
        }
        
        
            $html = "<div id=\"followable\">
            <h1>$name</h1>

            <!-- when the user is not connected, hide -->
            <a class=\"tag\" href=\"?action=followtag&idtag=".$_GET['idtag']."\"$hideFollow><button class=\"stylized\">Suivre</button></a>
            <!-- when the user is following this tag, we change the button to this-->
            <a class=\"tag\" href=\"?action=unfollowtag&idtag=".$_GET['idtag']."\"$hideUnFollow><button class=\"stylized\">Ne plus suivre</button></a>
        </div>";
            $tag = Tag::getTagFromId($_GET['idtag']);
            $_SESSION['touites'] = $tag->getTouiteListFromTag();
            $html .= $_SESSION['touites']->displayPage(1) ;
            return $html;

    }
}