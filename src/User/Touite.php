<?php

namespace touiteur\User;

use touiteur\User\User as User;
use PDO;
use touiteur\Auth\Auth;
use touiteur\Auth\ConnexionFactory;

class Touite
{
private User $author;
private String $texte;
private String $srcimage;
private int $datePublication;
private array $tags;

private int $score;

private int $idTouit;
public function __construct(User $aut, String $t,int $date)
{
$this->srcimage='';
$this->author=$aut;
$this->texte=$t;
$this->datePublication=$date;
ConnexionFactory::makeConnection();

    $query = "select idTouit from Touit where iduser = ? and texte = ? ";
    $prepared_query = ConnexionFactory::$db->prepare($query);
    $id = $aut->getId();
    $prepared_query->bindParam(1, $id, PDO::PARAM_INT, 32);
    $prepared_query->bindParam(2, $this->texte, PDO::PARAM_STR, 32);
    $prepared_query->execute();
    $this->idTouit = $prepared_query->fetchAll(PDO::FETCH_ASSOC)[0];


    $pdo = ConnexionFactory::$db;
    $query="Select tagName from tag natural join TouitTag where idtouit = ?";
    $prepared = $pdo->prepare($query);
    $prepared->bindParam(1,$this->idTouit,PDO::PARAM_INT,50);
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
    $tag[0]=(substr($text,strpos($text, '#')));
    $reponse[0] = substr($tag[0], 1);
    $res[0] = substr($reponse[0], 0, strpos($reponse[0], ' '));
    $i=1;
    while ($tag[$i-1]!='' and strpos($reponse[$i-1], '#')!=null) {
        $tag[$i]=(substr($reponse[$i-1],strpos($reponse[$i-1], '#')));
        $reponse[$i]=substr($tag[$i], 1);
        $res[$i] = substr($reponse[$i],0,strpos($reponse[$i], ' '));
        $i++;
    }
return $res;
}




 static function getAllTouite() : array
{
    ConnexionFactory::makeConnection();
$pdo=ConnexionFactory::$db;
    $query="Select * from touit";
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

    }



    public function evaluateTouite(User $user,bool $eval)
    {
        ConnexionFactory::makeConnection();
        $pdo=ConnexionFactory::$db;
        $query="Select count(eval) from VoteTouit where iduser = ? and idTouit = ?";
        $prepared = $pdo->prepare($query);
        $prepared->bindParam(1,$user->getId(),PDO::PARAM_INT,50);
        $prepared->bindParam(2,$this->idTouit,PDO::PARAM_INT,50);
        $prepared->execute();
        $donne=$prepared->fetch();
        if ($donne==0) {
            if ($eval) {
                $this->score++;
                $preparedquery = $pdo->prepare("insert into VoteTouit values (?,?,?)");
                $preparedquery->bindParam(1,$user->getId(),PDO::PARAM_INT,50);
                $preparedquery->bindParam(2,$this->idTouit,PDO::PARAM_INT,50);
                $preparedquery->bindParam(3,$eval,PDO::PARAM_BOOL,50);
                $preparedquery->execute();
            } else {
                $this->score--;
                $preparedquery = $pdo->prepare("insert into VoteTouit values (?,?,?)");
                $preparedquery->bindParam(1,$user->getId(),PDO::PARAM_INT,50);
                $preparedquery->bindParam(2,$this->idTouit,PDO::PARAM_INT,50);
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

    public function deleteTouite(User $auth)
    {
        $pdo=ConnexionFactory::makeconexion();
        $query="Delete from Touit where idTouit = ? and iduser = ?";
        $prepared = $pdo->prepare($query);
        $prepared->bindParam(1,$this->idTouit,PDO::PARAM_INT,50);
        $prepared-> bindParm(2,$auth->getId(),PDO::PARAM_INT,50);
        $prepared->execute();
    }
}