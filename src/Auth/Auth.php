<?php

namespace touiteur\Auth;

use touiteur\auth\ConnexionFactory;
use PDO;
use touiteur\User\User;

class Auth {

    public static function register(string $email, string $password, String $name,String $fullname, int $role = 1) : bool {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $name = htmlentities($name);
        $fullname = htmlentities($fullname);

        ConnexionFactory::makeConnection();
        $query = "select * from TouiteurUser where email = ?;";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $email, PDO::PARAM_STR, 32);
        $prepared_query->execute();
        $bdUser = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        if(isset($bdUser[0])) return false;

        if(!self::checkPasswordStrength($password, 10)){}
        $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $query = "insert into TouiteurUser (name,fullname,email, password, role) values (?, ?, ?,?,?)";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $name, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(2, $fullname, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(3, $email, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(4, $password, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(5, $role, PDO::PARAM_INT);
        $prepared_query->execute();

        $query = "select idUser from TouiteurUser where email like ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $email, PDO::PARAM_STR, 32);
        $prepared_query->execute();
        $bdUser = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['user'] = User::getUserFromId($bdUser[0]['idUser']);

        return true;
    }

    public static function authenticate(string $email, string $password) : bool {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        ConnexionFactory::makeConnection();
        $query = "select password, role from TouiteurUser where email = ?;";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $email);
        $prepared_query->execute();
        $bdPass = $prepared_query->fetch();

        if(!isset($bdPass['password'])) {return false;}
        else
            if(password_verify($password, $bdPass['password'])) {
                $query = "select iduser from TouiteurUser where email = ?;";
                $prepared_query = ConnexionFactory::$db->prepare($query);
                $prepared_query->bindParam(1, $email);
                $prepared_query->execute();
                $use=User::getUserFromId($prepared_query->fetch()['iduser']);
                $_SESSION['user'] = $use;
                return true;
            }
            else {return false;}
    }

    public static function checkAccessLevel(int $required): void {
        $userLevel = $_SESSION['user']['role'];

        if (!$userLevel >= $required) throw new Exception("droits insuffisants");
    }

    public static function checkPasswordStrength(string $pass, int $minimumLength): bool {
        $length = (strlen($pass) >= $minimumLength); // longueur minimale
        $digit = preg_match("#[\d]#", $pass); // au moins un digit
        $special = preg_match("#[\W]#", $pass); // au moins un car. spécial
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule

        if (!$length || !$digit || !$special || !$lower || !$upper) return false;
        else return true;
    }
}