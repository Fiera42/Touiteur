<?php

namespace touiteur\TouiteList;

use touiteur\User\Touite;

class TouiteList{
    private array $touites;
    private int $nbTouitePerPage;
    public function __construct(array $touite = [], int $nbTouitePerPage = 2 ){
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
            $res .= $value->displaySimple();
        }
        unset($value);
        return $res;
    }
    
    public function displayPage(int $page) : string{
        $nbpage =  ceil((count($this->touites) / $this->nbTouitePerPage));

        //Vérification de la page, cela doit être une page valide + faire protection injection html
        if ($page <= 0 || $page > $nbpage){
            header('HTTP/1.0 404 Not Found');
            $html = "" ;
        }

        else
        {
            $html = "";//Génération des touites à afficher
            for ($i = ($page - 1) * $this->nbTouitePerPage; $i < ($page - 1) * $this->nbTouitePerPage + $this->nbTouitePerPage ; $i++ ) {
                if(isset($this->touites[$i])) $html = $html.  $this->touites[$i]->displaySimple() ;
            }
            if ($page == 1) {
                $hidePreviousBtn = "style='display:none'";
            } else {
                $hidePreviousBtn = "";
            }
            if ($page == $nbpage) {
                $hideNextBtn = "style='display:none'";
            } else {
                $hideNextBtn = "";
            }
            $lastPage = $nbpage;//On trouve quel est le numéro de la dernière page
            $previousPage = $page - 1;//page courante -1, pas besoin de vérifier car sera caché si prblm
            $nextPage = $page + 1;//page courante +1, pas besoin de vérifier car sera caché si prblm

            $hideChangePage = ($nbpage == 1)?"style='display:none'":"";

//On met à la suite des touites la partie changement de page
            $html = $html . "<div id=\"changePage\" {$hideChangePage}>
                    <!-- this page should be the previous one -->
                    <!-- we add an \"hidden\" attribute when we reached the edge of the list-->
                    <a href=\"?action=changepage&page={$previousPage}\" {$hidePreviousBtn}><button>&#129032;</button><a>
                        <!-- this page should be the first one -->
                    <a href=\"?action=changepage&page=1\">1</a>
                    <p>...</p>
                    <!-- this page should be the last one -->
                    <a href=\"?action=changepage&page={$lastPage}\">{$lastPage}</a>
                    <!-- this page should be the next one -->
                    <a href=\"?action=changepage&page={$nextPage}\" {$hideNextBtn}><button>&#129034;</button></a>
                </div>";
        }
        return $html ;
    }
    public function getMoyenne() : float
    {
        $scoreT=0;
        for ($i =0;$i<count($this->touites);$i++)
        {
            $scoreT=$this->touites[$i]->getScore()+$scoreT;
        }
        return number_format($scoreT/count($this->touites),3);
    }
}