<?php

namespace touiteur\TouiteList;

class TouiteList{
    private Array<Touite> $touite;
    private int $nbTouitePerPage;

    public TouiteList(){
        $this->touite = array();
        $this->$nbTouitePerPage = 10;
    }

    public void addTouite(Touite $touite){
        array_push($this->touite, $touite);
    }

    public void removeTouite(Touite $touite){
        //TO DO
    }

    public String displayAllTouites(){
        string $res;
        foreach($this->touite as $value){
            $res += $value->displayTouiteSimple() . "/n" . "/n";
        }
        unset($value);
        return $res;
    }
    
    public string displayPage(int $page){
        //TO DO
    }
}