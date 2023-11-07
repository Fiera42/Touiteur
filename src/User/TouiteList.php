<?php

namespace touiteur\TouiteList;

class TouiteList{
    private Array<Touite> $touites;
    private int $nbTouitePerPage;

    public TouiteList(){
        $this->touites = array();
        $this->$nbTouitePerPage = 10;
    }

    public void addTouite(Touite $touite){
        array_push($this->touites, $touite);
    }

    public void removeTouite(Touite $touite){
        if (($key = array_search($touite, $this->touites)) !== false) {
            unset($this->touites[$key]);
        }
    }

    public String displayAllTouites(){
        string $res;
        foreach($this->touites as $value){
            $res += $value->displayTouiteSimple() . "/n" . "/n";
        }
        unset($value);
        return $res;
    }
    
    public string displayPage(int $page){
        //TO DO
    }
}