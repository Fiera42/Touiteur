<?php

namespace touiteur\Action;

use touiteur\User\Touite;

class ActionChangePage extends Action {
    public function execute() : string {
        return $_SESSION['touites']->displayPage($_GET['page']);
    }
}