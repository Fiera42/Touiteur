<?php
namespace touiteur;

use touiteur\src\Action\ActionAbonnerTag;
use touiteur\src\Action\ActionDetail;
use touiteur\src\Action\ActionEffacer;
use touiteur\src\Action\ActionEvaluer;
use touiteur\src\Action\ActionPersonne;
use touiteur\src\Action\ActionRetour;
use touiteur\src\Action\ActionSuivre;
use touiteur\src\Action\ActionTag;
use touiteur\src\Action\ActionTouiter;
use touiteur\src\Auth\ConnexionFactory as ConnexionFactory;

class dispatcher
{
    public String $action;
    public function __construct()
    {
        if (isset($_GET['action'])) $this->action =$_GET['action'];
        else $this->action ='';

        ConnexionFactory::setConfig('C:\xampp\htdocs\CoursPhp\PHP\TD13\conf\conf.ini');
    }

    public function run()
    {
        $html='';
        $methode = $_SERVER['REQUEST_METHOD'];
        switch ($this->action)

        {
            case 'add-user':
                $a =new ActionAdUser();
                $html = $a->execute();
                break;

            case 'Detail' :
                $a=new ActionDetail();
                $html=$a->execute();
                break;

            case 'Tag' :
                $a=new ActionTag();
                $html=$a->execute();
                break;

            case 'Personne' :
                $a=new ActionPersonne();
                $html=$a->execute();
                break;

            case 'Retour' :
                $a=new ActionRetour();
                $html=$a->execute();
                break;

            case 'Touiter' :
                $a=new ActionTouiter();
                $html=$a->execute();
                break;

            case 'Effacer' :
                $a=new ActionEffacer();
                $html=$a->execute();
                break;

            case 'S\'abonner au tag' :
                $a=new ActionAbonnerTag();
                $html=$a->execute();
                break;

            case 'Evaluer' :
                $a=new ActionEvaluer();
                $html=$a->execute();
                break;

            case 'Suivre' :
                $a=new ActionSuivre();
                $html=$a->execute();
                break;

            default:
                echo'Page d\'acceuille';
        }

        $this->render($html);
    }
    public function render($html)
    {
        $html="<head></head>
    <body>
    $html
    </body>";
        echo $html;
    }
}