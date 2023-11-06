<?php

namespace touiteur\action;

class ActionTouiter extends Action
{
 public function execute(): string
 {

     $methode = $_SERVER['REQUEST_METHOD'];
     $html='';
     if ($methode==='GET'){
         $html='La ou on ecrit le texte plus le pseudo de la personne<br>
                <form action ="?action=touiter" method = "post"> ';

     }
     else if($methode==='SET')
     {
            /*il aura un ajout dans la BD*/
     }
     return $html;
 }
}