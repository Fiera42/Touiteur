<?php

namespace touiteur\Tag;

class Tag{
    private string $name;
    private int $nbUsage;

    public Tag(string $name){
        $this->name = $name;
        $this->$nbUsage = 0;
    }

    public TouiteListe getTouiteListFromTag(){
        //TO DO
    }

    public void updateNbUsage(){
        $this->$nbUsage ++;
    }
}