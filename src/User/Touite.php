<?php

namespace touiteur\User;

use touiteur\User\User as User;
use PDO;
use touiteur\Auth\Auth;
use touiteur\Auth\ConnexionFactory;
use touiteur\TouiteList\TouiteList;
use touiteur\Tag\Tag;

//var_dump(Touite::findTag("Ceci #est une #phrase-de-test"));

class Touite {
    private User $author;
    private String $texte;
    private String $srcimage;
    private String $datePublication;
    private array $tags;
    private int $score;
    private int $idTouit;

    public function __construct(User $aut, String $t, String $date, String $srcimage, array $tags, $idTouit) {
        $this->srcimage= $srcimage;
        $this->author=$aut;
        $this->texte=$t;
        $this->datePublication=$date;
        $this->tags = $tags;
        $this->idTouit = $idTouit;
    }

    static function getTouiteFromId(int $id) : Touite {
        $query = "select * from Touit LEFT JOIN image ON Touit.idimage = image.idimage where touit.idTouit = ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $id, PDO::PARAM_INT, 32);
        $prepared_query->execute();
        $touit = $prepared_query->fetchAll(PDO::FETCH_ASSOC)[0];

        if(!isset($touit['srcimage'])) $touit['srcimage'] = "";

        return new Touite(User::getUserFromId($touit['idUser']), $touit['text'], $touit['srcimage'], $touit['date'], Touite::getTagListFromTouiteID($id), $id);
    }

    static function getTagListFromTouiteID(int $id) : array {
        $query = "SELECT idtag FROM TouitTag WHERE idtouit LIKE ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $id, PDO::PARAM_STR, 32);
        $prepared_query->execute();
        $tagsid = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        $res = [];
        foreach($tagsid as $key => $value) {
            $res[] = Tag::getTagFromId($value['idtag']);
        }

        return $res;
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

    static function getAllTouite() : TouiteList {
        ConnexionFactory::makeConnection();
        $pdo=ConnexionFactory::$db;
        $query="Select idtouit from touit";
        $prepared = $pdo->prepare($query);
        $prepared->execute();
        $reponse = new TouiteList();

        $donnee = $prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach($donnee as $key => $value) {
            $reponse->addTouite(Touite::getTouiteFromId($value['idtouit']));
        }

        return $reponse;
    }




    static function publishTouite(User $aut, string $t) : array
    {
        ConnexionFactory::makeConnection();
        $pdo = ConnexionFactory::$db;
        $query = "Select TagName from TAG";
        $prepared = $pdo->prepare($query);
        $list = Touite::findTag($t);


        foreach ($list as $value) {
            $trouve = false;
            $prepared->execute();
            while ($donne = $prepared->fetch()) {
                if ($value == $donne['TagName']) {
                    $trouve = true;
                }
            }

            if ($trouve = true) {
                $query = "Update Tag set nbUsage=nbUsage+1 where TagName= $value";
                $value='';
            } else {
                $query = "Insert into Tag (tagName, description, nbUsage) values ($value,'des touites parlant de'.$value,1)";
            }
        }
        return $list;
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

                $prepared=$pdo->prepare("Update Touit set note=note+1 where idTouit=$this->idTouit");
                $prepared->execute();
            } else {
                $this->score--;
                $preparedquery = $pdo->prepare("insert into VoteTouit values (?,?,?)");
                $preparedquery->bindParam(1,$user->getId(),PDO::PARAM_INT,50);
                $preparedquery->bindParam(2,$this->idTouit,PDO::PARAM_INT,50);
                $preparedquery->bindParam(3,$eval,PDO::PARAM_BOOL,50);
                $preparedquery->execute();

                $prepared=$pdo->prepare("Update Touit set note=note-1 where idTouit=$this->idTouit");
                $prepared->execute();
            }
        }else echo "vous avez deja voter pour ce touite";
    }
    public function displaySimple() : string {

        if(isset($_SESSION['user'])) $hideDelete = ($this->author->getId() == $_SESSION['user']->getId())?"HIDDEN":"";
        else $hideDelete = "HIDDEN";

        $html = "<div class=\"touite\" onclick=\"location.href='index.html?action=looktouite&idtouite={$this->idTouit}'\">
                    <a class=\"touite-userName\" href=\"index.html?action=lookUser&iduser={$this->author->getId()}\">{$this->author->getDisplayName()}</a>
                    <p class=\"touite-content\"> {$this->texte} </p>
                    <div class=\"vote\">
                        <!-- idTouite should be the same as the id of the touite-->
                        <a href=\"index.html?action=vote&idtouite={$this->idTouit}&value=true\"><button>&#11205;</button><a></a>
                        <a href=\"index.html?action=vote&idtouite={$this->idTouit}&value=false\"><button>&#11206;</button></a>

                        <!-- only show this if it's a touite of the user -->
                        <!-- ofc still check if it's the good user when clicking -->
                        <a href=\"index.html?action=destroyTouite&idtouite={$this->idTouit}\" {$hideDelete}><button>&#9587;</button></a>
                    </div>
                </div>";
        
        return $html;
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