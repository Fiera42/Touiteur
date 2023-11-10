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
    private int $idimage;

    public function __construct(User $aut, String $t, String $date, String $srcimage, array $tags, $idTouit , $idimage) {
        $this->srcimage= $srcimage;
        $this->author=$aut;
        $this->texte=$t;
        $this->datePublication=$date;
        $this->tags = $tags;
        $this->idTouit = $idTouit;
        $this->idimage = $idimage;
    }

    static function getTouiteFromId(int $id) : Touite {
        $query = "select * from Touit LEFT JOIN image ON Touit.idimage = image.idimage where touit.idTouit = ?";
        $prepared_query = ConnexionFactory::$db->prepare($query);
        $prepared_query->bindParam(1, $id, PDO::PARAM_INT, 32);
        $prepared_query->execute();
        $touit = $prepared_query->fetchAll(PDO::FETCH_ASSOC)[0];

        if(!isset($touit['imagePath'])) {
            $touit['imagePath'] = "";
        }

        if(!isset($touit['idimage'])) {
            $touit['idimage'] = 0;
        }


        return new Touite(User::getUserFromId($touit['idUser']), $touit['text'], $touit['date'], $touit['imagePath'], Touite::getTagListFromTouiteID($id), $id ,$touit['idimage'] );
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
        $text=$text." ";
        if (str_contains($text,'#')){
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
        }else $res[0]="";
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

    static function publishTouite(User $aut, string $t, int $idimage = -1) : void {
        ConnexionFactory::makeConnection();
        $pdo = ConnexionFactory::$db;
        $t = htmlentities($t);

        //generate date
        date_default_timezone_set('Europe/Paris');
        $date = date(DATE_ATOM);
        $date = str_replace("+01:00", "", $date);
        $date = str_replace("T", " ", $date);

        //insert the touite
        if($idimage == -1) $query = "INSERT INTO touit (idUser, text, date) values (?, ?, ?)";
        else $query = "INSERT INTO touit (idUser, text, date, idimage) values (?, ?, ?, ?)";

        $idUser = $aut->getId();
        $prepared = $pdo->prepare($query);
        $prepared->bindParam(1, $idUser, PDO::PARAM_INT, 50);
        $prepared->bindParam(2, $t, PDO::PARAM_STR, 235);
        $prepared->bindParam(3, $date, PDO::PARAM_STR, 50);
        if($idimage != -1) $prepared->bindParam(4, $idimage, PDO::PARAM_INT, 50);
        $prepared->execute();

        $idTouit = $pdo->prepare("SELECT idTouit FROM touit WHERE idUser = ? AND date LIKE ?");
        $idTouit->bindParam(1, $idUser, PDO::PARAM_INT, 50);
        $idTouit->bindParam(2, $date, PDO::PARAM_STR, 235);
        $idTouit->execute();
        $idTouit = $idTouit->fetchAll(PDO::FETCH_ASSOC);
        $idTouit = $idTouit[0]['idTouit'];

        //update tags        
        $query = "Select idtag from tag where tagname LIKE ?";
        $prepared = $pdo->prepare($query);
        $list = Touite::findTag($t);
        
        foreach ($list as $value) {
            $prepared->bindParam(1,$value,PDO::PARAM_STR,50);
            $prepared->execute();
            $dbResponse = $prepared->fetchAll(PDO::FETCH_ASSOC);
            $dbResponse = $dbResponse[0]['idtag'];

            $value = htmlentities($value);

            if(isset($dbResponse)) {
                $insertTag = $pdo->prepare("Update tag set nbusage = nbusage + 1 where idtag = $dbResponse");
                $insertTag->execute();
            }
            else {
                $insertTag = $pdo->prepare("Insert into Tag (tagName, description, nbUsage) values (?,'des touites parlant de '.$value,1)");
                $insertTag->bindParam(1,$value, PDO::PARAM_STR, 50);
                $insertTag->execute();
            }

            $idTag = $pdo->prepare("Select idtag from tag where tagname = ?");
            $idTag->bindParam(1, $value, PDO::PARAM_STR, 50);
            $idTag->execute();
            $idTag = $idTag->fetchAll(PDO::FETCH_ASSOC)[0]['idtag'];

            $insertTag = $pdo->prepare("INSERT INTO touittag (idtag, idtouit) values (?, ?)");
            $insertTag->bindParam(1,$idTag, PDO::PARAM_STR, 50);
            $insertTag->bindParam(2,$idTouit, PDO::PARAM_STR, 50);
            $insertTag->execute();
        }
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

        if(isset($_SESSION['user'])) {$hideVote='';$hideDelete = ($this->author->getId() == $_SESSION['user']->getId())?"style='display:none'":"";}
        else {
            $hideDelete = "style='display:none'";
            $hideVote = "style='display:none'";
        }
        $text = $this->texte ;
        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                $tagLink = "<a href=\"?action=looktag&idtag={$tag->getId()}\">#{$tag->getName()}</a>";
                $text = str_replace('#'.$tag->getName(), $tagLink, $text);
            }


    }

        $html = "<div class=\"touite\" onclick=\"location.href='?action=looktouite&idtouite={$this->idTouit}'\">
                    <a class=\"touite-userName\" href=\"?action=lookUser&iduser={$this->author->getId()}\">{$this->author->getDisplayName()}</a>
                    <time>$this->datePublication</time>
                    <p class=\"touite-content\"> {$text} </p>
                    <div class=\"vote\">
                        <!-- idTouite should be the same as the id of the touite-->
                        <a href=\"?action=vote&idtouite={$this->idTouit}&value=true\" $hideVote><button>&#11205;</button><a></a>
                        <a href=\"?action=vote&idtouite={$this->idTouit}&value=false\" $hideVote><button>&#11206;</button></a>

                        <!-- only show this if it's a touite of the user -->
                        <!-- ofc still check if it's the good user when clicking -->
                        <a href=\"?action=destroyTouite&idtouite={$this->idTouit}\" {$hideDelete}><button>&#9587;</button></a>
                    </div>
                </div>";
        
        return $html;
    }

    public function displayDetaille() :string
    {
        $text = $this->texte ;
        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                $tagLink = "<a href=\"?action=looktag&idtag={$tag->getId()}\">#{$tag->getName()}</a>";
                $text = str_replace('#' . $tag->getName(), $tagLink, $text);
            }
        }
        $src = $this->altImage();

        $rep="<div id=\"bigtouite\">
        <div class=\"touite\">
            <a class=\"touite\" href=\"?action=lookUser&iduser={$this->author->getId()}\">{$this->author->getDisplayName()}</a>
            <time>$this->datePublication</time>
            <!-- tags are ofc visible in any touite, not only in big -->
            <!-- dont forget to change the idtag -->
            <p class=\"touite\">{$text}</p>
            <img src=\"{$this->srcimage}\" alt=\"$src\">
            <div class=\"vote\">
                <!-- idTouite should be the same as the id of the touite-->
                <a href=\"?action=vote&idtouite={$this->idTouit}&value=true\"><button>&#11205;</button></a>
                <a href=\"?action=vote&idtouite={$this->idTouit}&value=false\"><button>&#11206;</button></a>
            </div>
        </div>";

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

    public function altImage() : string {
        if($this->idimage !==0){
            $query = "SELECT altImage FROM Image WHERE idimage=?";
            $prepared_query = ConnexionFactory::$db->prepare($query);
            $prepared_query->bindParam(1, $this->idimage, PDO::PARAM_STR, 32);
            $prepared_query->execute();
            return $prepared_query->fetchAll(PDO::FETCH_ASSOC)[0]['altImage'];
        }else{
            return  "" ;
        }
    }
}