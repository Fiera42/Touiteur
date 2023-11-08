<?php

namespace touiteur\Tag;

use touiteur\TouiteList\TouiteList;

class Tag{
    private string $name;
    private int $nbUsage;
    private int $id ;
    private string $description ;

    public function __construct(String $name , int $id , string $des = ""){
        $this->name = $name;
        $this->nbUsage = 0;
        $this->id = $id;
        $this->description = $des;
    }

    public function getTouiteListFromTag() :TouiteList{
        ConnexionFactory::makeConnection();

        $query = "SELECT email , password , role , text, date from touit join touittag on touit.idtouit = touittag.idtouit 
                                                join touiteuruser on touit.iduser = touiteuruser.iduser where idtag =?";

        $prepared_query = ConnexionFactory::$db->prepare($query);
        $id = this->getId() ;
        $prepared_query->bindParam(1, $id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $list = new TouiteList();
        foreach ($res as $row){
            $aut = new User($row['email'] , $row['passwd'] , $row['role']);
            $touit = new Touite($aut , $row['text'] , $row['date']);
            $list->addTouit($touit);
        }
        return $list;
    }

    function updateNbUsage() {
        $this->nbUsage ++;
    }

    function getId() : int
    {
        return $this->id ;
    }
}