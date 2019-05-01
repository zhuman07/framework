<?php
/**
 * Created by PhpStorm.
 * User: Zhuman
 * Date: 01-May-19
 * Time: 7:42 PM
 */

namespace Core\Classes;


class Debug
{

    public static function dump()
    {
        if (func_num_args() === 0)
            return;

        // Get all passed variables
        $variables = func_get_args();
        foreach ($variables as $var)
        {
            echo '<pre class="debug">';
            var_dump($var);
            echo '</pre>';
        }
    }

}