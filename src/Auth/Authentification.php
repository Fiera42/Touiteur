<?php

namespace touiteur\src\Auth;

use touiteur\src\Auth\ConnexionFactory as ConnexionFactory;

class Auth
{



    public static function register(string $email,
                                    string $pass): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
        try {


            $pdo = ConnexionFactory::makeconexion()->query("insert into user values(15,?,?)");
            $pdo->execute($email, $hash);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }


    public static function authenticate(string $email,
                                        string $passwd2check): bool
    {
        $pdo = ConnexionFactory::makeconexion();
        $query = "Select passwd from User where email = ? ";
        $res = $pdo->prepare($query);
        $bool=false;
        try {
            $res->execute([$email]);
            if ($donne = $res->fetch()) {
                $hash = $donne['passwd'];
                 $boll=password_verify($passwd2check, $hash);
            } else {
                $boll=false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $boll;
    }

}