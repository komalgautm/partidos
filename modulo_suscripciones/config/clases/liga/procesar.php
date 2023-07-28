<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR );


require_once "../../config.php";


extract($_GET);
extract($_POST);


switch ($op){

    case "imprimirSelectorJugadores":
        $Liga = new Liga($idLiga);
        $Liga->imprimirSelectorJugadores();

        break;

    case Liga::OP_obtenerIdsPartidosConResultados:
        $Liga = new Liga($idLiga);
        echo json_encode($Liga->obtenerIdsPartidosConResultados());
        break;
}