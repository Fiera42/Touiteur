<?php

namespace touiteur\action;

class ActionPersonne extends Action
{
public function execute(): string
{
    $html='Page de la personne avec ces touites les plus récents (TO DO)';
    return $html;
}
}