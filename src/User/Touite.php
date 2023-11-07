<?php

namespace touiteur\src\User;

use iutnc\touiteur\User\User;
use PDO;
use touiteur\Auth\Auth;
use iutnc\touiteur\src\Auth\ConnexionFactory;

class Touite
{
private User $author;
private String $texte;
private String $srcimage;
private int $datePublication;
private array $tags;

private int $score;

private int $idTouite;
public function __construct(User $aut, String $t,int $date)
{
$this->srcimage='';
$this->author=$aut;
$this->texte=$t;
$this->datePublication=$date;

    $query = "select idTouit from Touite where iduser = ? and texte = ? ";
    $prepared_query = ConnexionFactory::$db->prepare($query);
    $id = $aut->getId();
    $prepared_query->bindParam(1, $id, PDO::PARAM_INT, 32);
    $prepared_query->bindParam(2, $this->texte, PDO::PARAM_STR, 32);
    $prepared_query->execute();
    $this->idTouite = $prepared_query->fetchAll(PDO::FETCH_ASSOC)[0];

    $pdo=ConnexionFactory::makeconexion();
    $query="Select tagName from tag natural join TouitTag natural join Touite  where touite.text = ? and iduser = ?";
    $prepared = $pdo->prepare($query);
    $prepared->bindParam(1,$this->texte,PDO::PARAM_STR,50);
    $prepared_query->bindParam(2, $id, PDO::PARAM_INT, 32);
    $prepared->execute();
    array ($reponse[0]='');
    $i=0;
    while($donne=$prepared->fetch())
    {
        $reponse[$i]=new Tag($donne['TagName']);
        $i++;
    }

    $this->tags = $reponse;

}


static function findTag(String $text) : array
{
    $tag[0] = htmlspecialchars (strpbrk($text, '# ')); // recherche en premier la presence de '#' puis de ' '


    $reponse[0]='';
        {
    $res = substr($tag, 1);
    $reponse[0] = $res;
}
return $reponse;
}




 static function getAllTouite() : array
{
$pdo=ConnexionFactory::makeconexion();
    $query="Select * from touite";
    $prepared = $pdo->prepare($query);
$prepared->execute();
    $reponse = new touiteListe;
    while($donne=$prepared->fetch())
    {
    $reponse->addTouite(new Touite($donne['iduser'],$donne['text'],$donne['date']));
    }
return $reponse;
}




    static function publishTouite(User $aut,string $t) : array
    {
        $pdo=ConnexionFactory::makeconexion();
        $query="Select tagName from tag natural join TouitTag natural join Touite  where touite.text = ?";
        $prepared = $pdo->prepare($query);
        $prepared->bindParam(1,$t,PDO::PARAM_STR,50);
        $prepared->execute();

        array ($reponse[0]='');
        $i=0;
        while($donne=$prepared->fetch())
        {
            $reponse[$i]=new Tag($donne['TagName']);
            $i++;
        }
        return $reponse;
    }



    public function evaluateTouite(User $user,bool $eval)
    {
        $pdo=ConnexionFactory::makeconexion();
        $query="Select count(eval) from VoteTouite where iduser = ? and idTouite";
        $prepared = $pdo->prepare($query);
        $prepared->bindParam(1,$user->getId(),PDO::PARAM_INT,50);
        $prepared->bindParam(2,$this->idTouite,PDO::PARAM_INT,50);
        $prepared->execute();
        $donne=$prepared->fetch();
        if ($donne==0) {
            if ($eval) {
                $this->score++;
                $preparedquery = $pdo->prepare("insert into VoteTouite values (?,?,?)");
                $preparedquery->bindParam(1,$user->getId(),PDO::PARAM_INT,50);
                $preparedquery->bindParam(2,$this->idTouite,PDO::PARAM_INT,50);
                $preparedquery->bindParam(3,$eval,PDO::PARAM_BOOL,50);
                $preparedquery->execute();
            } else {
                $this->score--;
                $preparedquery = $pdo->prepare("insert into VoteTouite values (?,?,?)");
                $preparedquery->bindParam(1,$user->getId(),PDO::PARAM_INT,50);
                $preparedquery->bindParam(2,$this->idTouite,PDO::PARAM_INT,50);
                $preparedquery->bindParam(3,$eval,PDO::PARAM_BOOL,50);
                $preparedquery->execute();
            }
        }else echo "vous avez deja voter pour ce touite";
    }
    public function displaySimple() :string
    {
        $auth=$this->author;
        $rep="$auth->getEmail()"."<br>"."$this->texte";

        return $rep;
    }

    public function displayDetaille() :string
    {
        $auth=$this->author;
        $rep="$auth->getEmail()"."<br>"."$this->texte"."<br>"."$this->datePublication"."<br>"."$this->tags";

        return $rep;
    }

    public function deletTouite(User $auth)
    {
        $pdo=ConnexionFactory::makeconexion();
        $query="Delete from Touite where idTouite = ? and iduser = ?";
        $prepared = $pdo->prepare($query);
        $prepared->bindParam(1,$this->idTouite,PDO::PARAM_INT,50);
        $prepared-> bindParm(2,$auth->getId(),PDO::PARAM_INT,50);
        $prepared->execute();
    }
}