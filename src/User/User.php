<?php

namespace touiteur\User;

class User {
    private String $email;
    private String $passwd;
    private int $role;

    function __construct(String $email, String $passwd, int $role = 0) {
        $this->email = $email;
        $this->passwd = $passwd;
        $this->role = $role;
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

}