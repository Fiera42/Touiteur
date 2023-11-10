<?php

namespace touiteur\Action;

use touiteur\auth\ConnexionFactory;
use touiteur\Tag\Tag;

class ActionAdminTendance extends Action
{
    public function execute(): string
    {
        // TODO: Implement execute() method.

        ConnexionFactory::makeConnection();
        $pdo=ConnexionFactory::$db;
        $query="Select idtag from tag where nbUsage=(Select max(nbUsage)from tag)";
        $prepared = $pdo->prepare($query);
        $prepared->execute();
        $donne=$prepared->fetch();
        $tag=Tag::getTagFromId($donne['idtag']);
        /*TO DO affichage*/
        $html='';
        return $html;
    }
}