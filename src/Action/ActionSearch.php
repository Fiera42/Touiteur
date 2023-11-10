<?php

namespace touiteur\Action;

use touiteur\action\ActionLookUser ;
use touiteur\auth\ConnexionFactory;
use touiteur\TouiteList\TouiteList;
use touiteur\User\Touite;

class ActionSearch extends Action {
    public function execute() : string {

        if ($_SERVER['REQUEST_METHOD']==='GET') {
            $html = "<form class=\"connection\" method=\"post\" action=\"?action=search\">
                    <h2>Rechercher</h2>
                    <input type=\"text\" name=\"search\" placeholder=\"Recherche\">
                    <button type=\"submit\">Rechercher</button>
                </form>";
        }
        else
        {
            $tag =$_POST['search'];
            ConnexionFactory::makeConnection();
            $pdo = ConnexionFactory::$db;
            $query="Select idTouit from touittag natural join tag where tagname=?";
            $prepared = $pdo->prepare($query);
            $prepared->bindParam(1,$tag);
            $list=new TouiteList();
            $prepared->execute();
            while($donne=$prepared->fetch())
            {
                $id = Touite::getTouiteFromId($donne['idTouit']);
                $list->addTouite($id);
            }
            $html = $list->displayAllTouites();

        }
        return $html;
    }
}