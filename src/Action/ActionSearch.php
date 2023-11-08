<?php

namespace touiteur\action;

use touiteur\action\ActionLookUser ;

class ActionSearch extends Action {
    public function execute() : string {

        if($this->http_method === "GET") {
            $html = "<form class=\"connection\" method=\"post\" action=\"?action=search\">
                    <h2>Rechercher</h2>
                    <input type=\"text\" name=\"search\" placeholder=\"Recherche\">
                    <button type=\"submit\">Rechercher</button>
                </form>";
            return $html ;
        }

        else return "search result";
    }
}