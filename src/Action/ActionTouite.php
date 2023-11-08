<?php

namespace touiteur\action;

class ActionTouite extends Action {
    public function execute() : string {
        $_POST['text']; //the text of the touite
        $_POST['img']; //the file of the touite
        $_POST['img_desc']; //the description of the img (the alt param of img)
        $_SESSION['user']->id; //the id of the person wanting to touite
        $html='Page de la personne avec ces touites les plus récents (TO DO)';
        return $html;
    }
}