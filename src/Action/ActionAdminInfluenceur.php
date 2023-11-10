<?php

namespace touiteur\Action;

use touiteur\auth\ConnexionFactory;
use touiteur\User\User;

class ActionAdminInfluenceur extends Action
{
    public function execute(): string
    {
        ConnexionFactory::makeConnection();
        $pdo=ConnexionFactory::$db;
        $query="select iduser, sum(note) from touit group by iduser having sum(note) >= ALL (select sum(note) from touit group by iduser)";
        $prepared = $pdo->prepare($query);
        $prepared->execute();
        $donne=$prepared->fetch();
        $user=User::getUserFromId($donne['iduser']);
        /*TO DO*/
        $html='';
        return $html;
    }
}