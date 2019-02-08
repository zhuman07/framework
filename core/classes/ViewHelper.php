<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 06.02.2019
 * Time: 21:55
 */
namespace Core\Classes;

class ViewHelper
{

    public function render(string $path, array $variables = null, $print = false)
    {
        $output = null;
        $file_path = ROOT."app/views/".$path.".php";
        if(file_exists($file_path)){
            if(!empty($variables)){
                extract($variables);
            }

            ob_start();
            include($file_path);
            $output = ob_get_clean();
        } else {
            throw new \ErrorException("View file doesn't exist");
        }
        if ($print){
            print $output;
        }
        return $output;
    }

}