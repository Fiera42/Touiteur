<?php

namespace touiteur\Action;

use touiteur\User\Touite;
use touiteur\TouiteList\TouiteList;

class ActionDisplayPage extends Action {
    public function execute() : string {
        if(isset($_SESSION['touites'])) {
            $currentPage = $_SESSION['touites']->getCurrentPage();
        }
        
        $_SESSION['touites'] = Touite::getAllTouite();
        
        if(isset($currentPage)) $_SESSION['touites']->displayPage($currentPage);
        
        return $_SESSION['touites']->displayPage();
    }
}