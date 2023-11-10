<?php

namespace touiteur\action;

use touiteur\User\Touite;
use touiteur\Auth\ConnexionFactory;
use PDO;

class ActionTouite extends Action {
    public function execute() : string {
        if(!isset($_SESSION['user'])) {
            $_GET['action'] = 'register';
            $_SERVER['REQUEST_METHOD'] = 'GET';
            $action =new ActionRegister();
            return $action->execute();
        }

        if(isset($_FILES['img'])) {
            //avoid injection from filenames
            $_FILES['img']['name'] = str_replace('<', '&lt;' , $_FILES['img']['name']);
            $_FILES['img']['name'] = str_replace('>', '&gt;' , $_FILES['img']['name']);
            $_FILES['img']['name'] = str_replace('"', "" , $_FILES['img']['name']);
            $_FILES['img']['name'] = str_replace("'", "" , $_FILES['img']['name']);

            if(!str_contains($_FILES['img']['type'], "image/")) {
                $idimage = null;
            }

            else {
                ConnexionFactory::makeConnection();
                $pdo = ConnexionFactory::$db;
                $idimage = $pdo->prepare('SELECT max(idimage) as id FROM image');
                $idimage->execute();
                $idimage = $idimage->fetchAll(PDO::FETCH_ASSOC)[0]['id'] + 1;

                $uploaddir = 'ressource/userimage/';
                $uploadfile = $uploaddir."{$idimage}_".basename($_FILES['img']['name']);

                if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)) {
                } else {
                    echo "Possible file upload attack!\n";
                }

                $_POST['img_desc'] = htmlentities($_POST['img_desc']);

                $query = "INSERT INTO image (idimage, altimage, imagepath) values (?, ?, ?)";
                $prepared = $pdo->prepare($query);
                $prepared->bindParam(1, $idimage, PDO::PARAM_INT, 50);
                $prepared->bindParam(2, $_POST['img_desc'], PDO::PARAM_STR, 235);
                $prepared->bindParam(3, $uploadfile, PDO::PARAM_STR, 50);
                $prepared->execute();
            }
        }

        if(isset($idimage)) Touite::publishTouite($_SESSION['user'], $_POST['text'], $idimage);
        else Touite::publishTouite($_SESSION['user'], $_POST['text']);

        
        $action = new ActionDisplayPage();
        return $action->execute();
        
    }
}