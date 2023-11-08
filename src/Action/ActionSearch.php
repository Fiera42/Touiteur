<?php

namespace touiteur\action;

use touiteur\action\ActionLookUser ;

class ActionSearch extends Action {
    public function execute() : string {
           

       
            $html = "<form class=\"connection\" method=\"post\" action=\"htmlTemplate_MAINPAGE.html?action=search\">
                        <h2>Rechercher</h2>
                        <input type=\"text\" name=\"search\" placeholder=\"Recherche\">
                        <button type=\"submit\">Rechercher</button>
                    </form>";
                    
            return $html ;

    }
}