<?php

namespace touiteur\Tag;

use touiteur\TouiteList\TouiteList;

class Tag{
    private string $name;
    private int $nbUsage;

    public function __construct(String $name){
        $this->name = $name;
        $this->nbUsage = 0;
    }

    public function getTouiteListFromTag() :TouiteList{
        //TO DO
    }

    function updateNbUsage(){
        $this->nbUsage ++;
    }
}