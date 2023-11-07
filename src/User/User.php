<?php

namespace touiteur\User;


use PDO;
use touiteur\auth\ConnexionFactory;
use touiteur\Tag\Tag;
use touiteur\TouiteList\TouiteList;

class User {
    private String $email;

    private String $passwd;
    private int $role;

    private int $id;

    function __construct(String $email, String $passwd, int $role = 0) {
        $this->email = $email;
        $this->passwd = $passwd;
        $this->role = $role;
        ConnexionFactory::makeConnection();
        $query = "select idUser from TouiteurUser where email=? and password = ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $this->email, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(2, $this->passwd, PDO::PARAM_STR, 32);
        $prepared_query->execute();
        $this->id = $prepared_query->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    function getTouit() : TouiteList {
        ConnexionFactory::makeConnection();

        $query = "SELECT text, date from touit where iduser = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $list = new TouiteList();
        foreach ($res as $row){
            $aut = new User($row[$this->email] , $row[$this->passwd] , $row[$this->role]);
            $touit = new Touite($aut , $row['text'] , $row['date']);
            $list->addTouit($touit);
        }
        return $list;
    }

    function getFollower() : array{
        ConnexionFactory::makeConnection();

        $query = "select email , password , role from TouiteurUser join
	followUser on touiteuruser.idUser = followuser.idFollower
where followuser.idUser = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $listFollow = null;

        foreach ($res as $row){
            $follow = new User($row['email'] , $row['password'] , $row['role']);
            $listFollow[] = $follow ;
        }
        return $listFollow ;

    }


    function getFollowing() : array{
        ConnexionFactory::makeConnection();

        $query = "select email , password , role from TouiteurUser join
	followUser on touiteuruser.idUser = followuser.idUser
where followuser.idFollower = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $listFollow = null;

        foreach ($res as $row){
            $follow = new User($row['email'] , $row['password'] , $row['role']);
            $listFollow[] = $follow ;
        }
        return $listFollow ;
    }

    function getFollowedTag(){
        ConnexionFactory::makeConnection();

        $query = "select tagName from Tag join
	followtag on tag.idtag = followtag.idTag
where followtag.idFollower = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $listTag = null;

        foreach ($res as $row){
            $tag = new Tag($row['name']);
            $listTag[] = $tag ;
        }
        return $listTag  ;
    }


    function followUser(User $target){
        ConnexionFactory::makeConnection();

        $query = "insert into followUser values (? , ?)";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(2, $target->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();
    }

    function followTag(Tag $target){
        ConnexionFactory::makeConnection();

        $query = "insert into followTag values (? , ?)";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);
        $id = $target->getId();
        $prepared_query->bindParam(2, $id, PDO::PARAM_STR, 32);

        $prepared_query->execute();
    }

    function UnfollowUser(User $target){
        ConnexionFactory::makeConnection();

        $query = "delete from followUser where iduser = ? and idfollower = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);
        $id = $target->getId();
        $prepared_query->bindParam(1, $id, PDO::PARAM_STR, 32);

        $prepared_query->bindParam(2, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();
    }

    function UnfollowTag(Tag $target){
        ConnexionFactory::makeConnection();

        $query = "delete from followTag where idtag = ? and idfollower = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);
        $id = $target->getId();
        $prepared_query->bindParam(1, $id, PDO::PARAM_STR, 32);

        $prepared_query->bindParam(2, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();
    }

}