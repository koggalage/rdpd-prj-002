<?php

Class Controller
{
    public function view($path, $data = [])
    {
       // show("../app/views/" . THEME . "/" . $path . ".php");

        if(file_exists("../app/views/" . THEME . "/" . $path . ".php"))
        {
            include "../app/views/" . THEME . "/" . $path . ".php";
        }else
        {
            include "../app/views/" . THEME . "/" . "404.php";
        }
    }

    public function load_model($model)
    {
        if(file_exists("../app/models/" . strtolower($model) . ".class.php"))
        {
            include "../app/models/" . strtolower($model) . ".class.php";
            return $m = new $model();
        }
        
        return false;
    }
}