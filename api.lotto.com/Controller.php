<?php

include './api.lotto.com/classes/DBConnection.php';
include 'Services.php';

/**
 * This controller is parent Controller and they performs view,connection and services.
 *
 * @author Nikola Zekavica <nikolazekavica88@yahoo.com>
 *
 * @since 1.0.0
 */
class Controller
{
    //View
    public static function CreateView($viewName)
    {
        require_once("./lotto.com/$viewName.html");
    }

    //Connection
    public function connection()
    {
        return DBConnection::connect();
    }

    //Services
    public function services()
    {
        return new Services();
    }
}