<?php

namespace touiteur\action;

class ActionTag extends Action
{
    public function execute(): string
    {
        $hml = 'Afficher les touite les plus récent avec le tag donné (TO DO)';
        return $hml;
    }
}