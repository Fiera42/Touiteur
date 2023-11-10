<?php
namespace touiteur;

use touiteur\Action\ActionAdminInfluenceur;
use touiteur\Action\ActionAdminSignIn;
use touiteur\Action\ActionAdminTendance;

class dispatcherAdmin
{
    public static function run(): void
    {
        if (!isset($_GET['action'])) $_GET['action'] = "";
        $action = null ;

        switch ($_GET['action']) {
            case 'influenceur':
                $action = new ActionAdminInfluenceur();
                break;
            case 'tendance' :
                $action = new ActionAdminTendance();
                break;
            case 'adminregister' :
                $action = new ActionAdminSignIn();
                break;
            default :
                break;
        }

        self::renderPage($action);
    }

    private static function renderPage(ActionAdminInfluenceur|ActionAdminTendance|null $action)
    {

    }
}