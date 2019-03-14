<?php

class Route
{
    public static $validRoutes = array();

    public static function set($route, $function)
    {
        self::$validRoutes[] = $route;
        $url   = isset($_GET['url']) ? $_GET['url'] : '/';
        $route = isset($route) ? $route : '/';

        if ($url == $route) {
            $function->__invoke();
        }
    }
}