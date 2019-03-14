<?php

require_once('routes.php');

function __autoload($class_name)
{
    if(file_exists('./classes/'.$class_name.'.php')){
        require_once './classes/'.$class_name.'.php';
    }else if(file_exists('./api.lotto.com/'.$class_name.'.php')) {
        require_once './api.lotto.com/' . $class_name . '.php';
    }
}


