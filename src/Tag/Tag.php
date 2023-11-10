<?php

namespace touiteur\Tag;

use touiteur\TouiteList\TouiteList;
use touiteur\Auth\ConnexionFactory;
use PDO;
use touiteur\User\Touite;
use touiteur\User\User;

class Tag{

    private string $name;
    private int $nbUsage;
    private int $id ;
    private string $description ;

    public function __construct(String $name , int $id , string $des = "", int $nbUsage = 0){
        $this->name = $name;
        $this->nbUsage = $nbUsage;
        $this->id = $id;
        $this->description = $des;
    }

    public function getTouiteListFromTag() :TouiteList{
        ConnexionFactory::makeConnection();

        $query = "SELECT Touit.iduser, Touit.idtouit from Touit join TouitTag on Touit.idtouit = TouitTag.idtouit 
                                                join TouiteurUser on Touit.iduser = TouiteurUser.iduser where idtag =?";

        $prepared_query = ConnexionFactory::$db->prepare($query);
        $id = $this->getId() ;
        $prepared_query->bindParam(1, $id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $list = new TouiteList();
        foreach ($res as $row){
            $aut = User::getUserFromId($row['iduser']);
            $touit = Touite::getTouiteFromId($row['idtouit']);
            $list->addTouite($touit);
        }
        return $list;
    }

    public static function getTagFromName(string $name) : Tag {
        $query = "SELECT * FROM tag WHERE tagname LIKE ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $name, PDO::PARAM_STR, 32);
        $prepared_query->execute();
        $touit = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        return new Tag($name, $touit['idtag'], $touit['description'], $touit['nbUsage']);
    }

    public static function getTagFromId(string $id) : Tag {
        $query = "SELECT * FROM Tag WHERE idtag LIKE ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $id, PDO::PARAM_INT, 32);
        $prepared_query->execute();
        $touit = $prepared_query->fetchAll(PDO::FETCH_ASSOC)[0];

        return new Tag($touit['tagName'], $id, $touit['description'], $touit['nbUsage']);
    }

    function updateNbUsage() {
        $this->nbUsage ++;
    }

    function getId() : int
    {
        return $this->id ;
    }

    function getName() : string {
        return $this->name ;
    }
}