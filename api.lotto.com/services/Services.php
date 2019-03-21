<?php

/**
 * This Services performs static function for converting response and creating draw.
 *
 * @author Nikola Zekavica <nikolazekavica88@yahoo.com>
 *
 * @since 1.0.0
 */
class Services
{
    /**Converting Array in json object*/
    public static function returnJson(array $array)
    {
        echo json_encode($array);
    }

    /**Converting message in json object*/
    public static function message($status,$message)
    {
        $array = array
        (
            $status => $message
        );

        self::returnJson($array);
    }

    /**Creating unique random array*/
    public static function uniqueRandomNumbers($min, $max, $quantity)
    {
        $numbers = range($min, $max);
        shuffle($numbers);

        return array_slice($numbers, 0, $quantity);
    }

    /**Create win amount*/
    public static function cashCalculator($count, $stake)
    {
        switch ($count)
        {
            case 1:
                $win = 1 * $stake;
                return $win;
                break;
            case 2:
                $win = 2 * $stake;
                return $win;
                break;
            case 3:
                $win = 4 * $stake;
                return $win;
                break;
            case 4:
                $win = 10 * $stake;
                return $win;
                break;
            case 5:
                $win = 25 * $stake;
                return $win;
                break;
            case 6:
                $win = 50 * $stake;
                return $win;
                break;
            case 7:
                $win = 100 * $stake;
                return $win;
                break;
        }
    }
}