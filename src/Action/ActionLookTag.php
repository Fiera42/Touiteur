<?php

namespace touiteur\action;

use touiteur\Tag\Tag;

class ActionLookTag extends Action {
    public function execute() : string {
        {
            $html = "<div id=\"followable\">
            <h1>Tag</h1>

            <!-- when the user is not connected, hide -->
            <a class=\"tag\" href=\"htmlTemplate_TAGPAGE.html?action=followtag&idtag=".$_GET['idtag']."\"><button class=\"stylized\">Suivre</button></a>
            <!-- when the user is following this tag, we change the button to this-->
            <a class=\"tag\" href=\"htmlTemplate_TAGPAGE.html?action=unfollowtag&idtag=".$_GET['idtag']."\"><button class=\"stylized\">Ne plus suivre</button></a>
        </div>";
            $tag = Tag::getTagFromId($_GET['idtag']);
            $touitList = $tag->getTouiteListFromTag();
            $html .= $touitList->displayAllTouites() ;
            return $html;
        }
    }
}