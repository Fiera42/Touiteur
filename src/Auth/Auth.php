<?php

namespace touiteur\auth;

use \iutnc\touiteur\auth\ConnexionFactory;

class Auth {

    public static function register(string $email, string $password, int $role = 1) : bool {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        ConnexionFactory::makeConnection();
        $query = "select * from touiteuruser where email = ?;";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $email, PDO::PARAM_STR, 32);
        $prepared_query->execute();
        $bdUser = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        if(isset($bdUser[0])) throw new AuthException("Email is already in use");

        if(!self::checkPasswordStrength($password, 10)) throw new AuthException("Password not strong enought");

        $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $query = "insert into touiteuruser (email, password, role) values (?, ?, ?)";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $email, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(2, $password, PDO::PARAM_STR, 32);
        $prepared_query->bindParam(3, $role, PDO::PARAM_INT);
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

        if(!isset($bdPass[0]['passwd'])) throw new AuthException("Unknown user");

        if(password_verify($password, $bdPass[0]['passwd'])) {
            $_SESSION['user'] = new User($email, $password, $bdPass[0]['role']);
            return true;
        }
        else throw new AuthException("Invalid password");
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