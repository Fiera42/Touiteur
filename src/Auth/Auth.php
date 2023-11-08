<?php

namespace touiteur\Auth;

use touiteur\auth\ConnexionFactory;
use PDO;
use touiteur\User\User;

class Auth {

    public static function register(string $email, string $password, int $role = 1,String $name,String $fullname) : bool {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        ConnexionFactory::makeConnection();
        $query = "select * from touiteuruser where email = ?;";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $email, PDO::PARAM_STR, 32);
        $prepared_query->execute();
        $bdUser = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        if(isset($bdUser[0])) return false;

        if(!self::checkPasswordStrength($password, 10)){}
        $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $query = "insert into touiteuruser (name,fullname,email, password, role) values (?, ?, ?,?,?)";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $name, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(2, $fullname, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(3, $email, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(4, $password, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(5, $role, PDO::PARAM_INT);
        $prepared_query->execute();

        return true;
    }

    public static function authenticate(string $email, string $password) : bool {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        ConnexionFactory::makeConnection();
        $query = "select password, role from touiteuruser where email = ?;";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $email, PDO::PARAM_STR, 32);
        $prepared_query->execute();
        $bdPass = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        if(!isset($bdPass[0]['passwd'])) return false;

        if(password_verify($password, $bdPass[0]['passwd'])) {
            $query = "select iduser from touiteuruser where email = ?;";
            $prepared_query = ConnexionFactory::$db->prepare($query);
            $prepared_query->bindParam(1, $email, PDO::PARAM_STR, 32);
            $prepared_query->execute();
            $use=User::getUserFromId($prepared_query->fetch());
            $_SESSION['user'] = $use;
            return true;
        }
        else return false;
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