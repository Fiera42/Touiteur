<?php

namespace touiteur\TouiteList;

class TouiteList{
    private Array<Touite> $touites;
    private int $nbTouitePerPage;

    public function __construct(){
        $this->touites = array();
        $this->$nbTouitePerPage = 10;
    }

    public function addTouite(Touite $touite){
        array_push($this->touites, $touite);
    }

    public function removeTouite(Touite $touite){
        if (($key = array_search($touite, $this->touites)) !== false) {
            unset($this->touites[$key]);
        }
    }

    public function displayAllTouites() : string{
        string $res;
        foreach($this->touites as $value){
            $res += $value->displayTouiteSimple() . "/n" . "/n";
        }
        unset($value);
        return $res;
    }
    
    public function displayPage(int $page) : string{
        //TO DO
    }
}