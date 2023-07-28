<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR );


require_once "../../config.php";


extract($_GET);
extract($_POST);


switch ($op){

    case "eliminar":

        if (empty($idPartido)){
            return false;
        }
        $Partido = new Partido($idPartido);
        $Partido->eliminar();
        break;
}