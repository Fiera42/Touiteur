<?php

namespace touiteur\src\Action;

class ActionEffacer extends Action
{
public function execute(): string
{

    $html='La meme page moins le tweet + supression dans la base de donné';
    return $html;
}
}