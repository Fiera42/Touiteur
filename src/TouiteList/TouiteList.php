<?php

namespace touiteur\TouiteList;

class TouiteList{
    private array $touites;
    private int $nbTouitePerPage;
    public function __construct(array $touite = [], int $nbTouitePerPage = 10 ){
        $this->touites = $touite ;
        $this->nbTouitePerPage = $nbTouitePerPage;
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
        $res = "";
        foreach($this->touites as $value){
            $res .= $value->displayTouiteSimple() . "/n" . "/n";
        }
        unset($value);
        return $res;
    }
    
    public function displayPage(int $page) : string{
        $nbpage =  (count($this->touites) / $this->nbTouitePerPage)+1 ;

        //Vérification de la page, cela doit être une page valide + faire protection injection html
        if ($page < 0 || $page > $nbpage){
            header('HTTP/1.0 404 Not Found');
            $html = "" ;
        }

        else
        {
            $html = "";//Génération des touites à afficher
            for ($i = $page * $this->nbTouitePerPage ; $i <= $page * $this->nbTouitePerPage + $this->nbTouitePerPage ; $i++ ) {
                $html = $html.  $this->touites[$i]->displayTouiteSimple().'<br>' ;
            }
            if ($page === 1) {
                $hidePreviousBtn = "HIDDEN";
            } else {
                $hidePreviousBtn = "";
            }
            if ($page === $nbpage) {
                $hideNextBtn = "HIDDEN";
            } else {
                $hideNextBtn = "";
            }
            $lastPage = $nbpage;//On trouve quel est le numéro de la dernière page
            $previousPage = $page - 1;//page courante -1, pas besoin de vérifier car sera caché si prblm
            $nextPage = $page + 1;//page courante +1, pas besoin de vérifier car sera caché si prblm

//On met à la suite des touites la partie changement de page
            $html = $html . "<div id=\"changePage\">
        <!-- this page should be the previous one -->
        <!-- we add an \"hidden\" attribute when we reached the edge of the list-->
        <a href=\"htmlTemplate_MAINPAGE.html?action=changepage&page={$previousPage}\"><button {$hidePreviousBtn}>&#129032;</button><a>
            <!-- this page should be the first one -->
        <a href=\"htmlTemplate_MAINPAGE.html?action=changepage&page=1\">1</a>
        <p>...</p>
        <!-- this page should be the last one -->
        <a href=\"htmlTemplate_MAINPAGE.html?action=changepage&page={$lastPage}\">{$lastPage}</a>
        <!-- this page should be the next one -->
        <a href=\"htmlTemplate_MAINPAGE.html?action=changepage&page={$nextPage}\"><button {$hideNextBtn}>&#129034;</button></a>
    </div>";
        }
        return $html ;
    }
}