<?php

namespace touiteur\action;

use touiteur\User\Touite;
use touiteur\TouiteList\TouiteList;

class ActionDisplayPage extends Action {
    public function execute() : string {
        return Touite::getAllTouite()->displayPage(1);
    }
}