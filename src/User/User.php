<?php

namespace touiteur\User;


use PDO;
use touiteur\auth\ConnexionFactory;
use touiteur\Tag\Tag;
use touiteur\TouiteList\TouiteList;

class User {
    private String $email;
    private String $name;
    private String $fullname;
    private String $passwd;
    private int $role;

    private int $id;

    function __construct(int $id, String $email, String $name, String $fullname, String $passwd, int $role = 1) {
        $this->email = $email;
        $this->passwd = $passwd;
        $this->role = $role;
        $this->name = $name;
        $this->fullname = $fullname;
        $this->id = $id;
    }

    function getTouit() : TouiteList {
        ConnexionFactory::makeConnection();

        $query = "SELECT idtouit FROM touit where iduser = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_INT, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $list = new TouiteList();
        foreach ($res as $row){
            $touit = Touite::GetTouiteFromId($row['idtouit']);
            $list->addTouite($touit);
        }
        return $list;
    }

    function getFollower() : array{
        ConnexionFactory::makeConnection();

        $query = "select idfollower from 
	followUser where followuser.idUser = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);
        if(empty($res)){
            $listFollow[] = "" ;
        }else {
            foreach ($res as $row) {
                $follow = User::getUserFromId($row['idfollower']);
                $listFollow[] = $follow;
            }
        }
        return $listFollow ;

    }


    function getFollowing() : array{
        ConnexionFactory::makeConnection();

        $query = "select touiteuruser.idUser from TouiteurUser join
	followUser on touiteuruser.idUser = followuser.idUser
where followuser.idFollower = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $listFollow = [];

        foreach ($res as $row){
            $follow = User::getUserFromId($row['idUser']);
            $listFollow[] = $follow ;
        }

        return $listFollow ;
    }

    function getFollowedTag() : array{
        ConnexionFactory::makeConnection();

        $query = "select tag.idtag from Tag join
	followtag on tag.idtag = followtag.idTag
where followtag.idFollower = ?";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $listTag = [];

        foreach ($res as $row){
            $tag = Tag::getTagFromId($row['idtag']);
            $listTag[] = $tag ;
        }
        return $listTag  ;
    }

    function isFollowingUser($id): bool
    {
        $query = "select * from followUser where idUser = ? AND idFollower = ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $id, PDO::PARAM_INT, 32);
        $prepared_query->bindParam(2, $this->id, PDO::PARAM_INT, 32);

        $prepared_query->execute();

        return isset($prepared_query->fetchAll(PDO::FETCH_ASSOC)[0]);
    }

    function isFollowingTag($id): bool
    {
        $query = "select * from followTag where idTag = ? AND idFollower = ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $id, PDO::PARAM_INT, 32);
        $prepared_query->bindParam(2, $this->id, PDO::PARAM_INT, 32);

        $prepared_query->execute();

        return isset($prepared_query->fetchAll(PDO::FETCH_ASSOC)[0]);
    }


    function followUser(User $target){
        ConnexionFactory::makeConnection();

        $query = "insert into followUser values (? , ?)";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(2, $this->id, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(1, $target->id, PDO::PARAM_STR, 32);

        $prepared_query->execute();
    }

    function followTag(Tag $target){
        ConnexionFactory::makeConnection();

        $query = "insert into followTag values (? , ?)";

        $prepared_query = ConnexionFactory::$db->prepare($query);
        $id = $target->getId();
        $prepared_query->bindParam(2, $this->id, PDO::PARAM_STR, 32);

        $prepared_query->bindParam(1, $id, PDO::PARAM_STR, 32);

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

    static function getUserFromId(int $id) : User {
        $query = "select * from touiteuruser where idUser = ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $id, PDO::PARAM_INT, 32);
        $prepared_query->execute();

        $result = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            throw new Exception("Aucun utilisateur trouvé");
        }
        
        $user = $result[0];

        return new User($id, $user['email'], $user['name'], $user['fullname'], $user['password'], $user['role']);
    }

    public function getId() {
        return $this->id;
    }

    public function getDisplayName(): string
    {
        return $this->name." ".$this->fullname;
    }

    public function getRole(): int
    {
        return $this->role ;
    }

}