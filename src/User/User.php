<?php

namespace touiteur\User;


use PDO;
use touiteur\auth\ConnexionFactory;

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

    function getTouit() : array {
        ConnexionFactory::makeConnection();

        $query = "";

        $prepared_query = ConnexionFactory::$db->prepare($query);

        $prepared_query->bindParam(1, $this->email, PDO::PARAM_STR, 32);

        $prepared_query->execute();

        return $prepared_query->fetchAll(PDO::FETCH_ASSOC);
    }

    function getFollower() : array{

    }

    function getFollowing() : array{

    }

    function getId() : int
    {

    }
}