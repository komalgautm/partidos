<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR );


require_once "../../config.php";


extract($_GET);
extract($_POST);


switch ($op){

    case "guardar":

        if ($id > 0){
            $Pista = new Pista($id);
        }
        else{
            $Pista = new Pista();
        }

        $Pista["idCampo"] = $idCampo;
        $Pista["nombre"] = $nombre;
        $Pista[Pista::COLUMNA_idDeporte] = $_REQUEST[Pista::COLUMNA_idDeporte];
        $Pista[Pista::COLUMNA_numeroJugadoresMaximo] = $_REQUEST[Pista::COLUMNA_numeroJugadoresMaximo];
        $Pista["tipoPared"] = $tipoPared;
        $Pista["tipoCubierta"] = $tipoCubierta;
        $Pista["informacionAdicional"] = $informacionAdicional;
        $Pista[Pista::COLUMNA_PERMITIR2JUGADORES] = $permitir2Jugadores;
        $Pista[Pista::COLUMNA_PERMITIR3JUGADORES] = $permitir3Jugadores;
        $Pista[Pista::COLUMNA_PERMITIR4JUGADORES] = $permitir4Jugadores;
        $Pista[Pista::COLUMNA_nombreImagen] = $_REQUEST[Pista::COLUMNA_nombreImagen];
        $Pista[Pista::COLUMNA_colorResaltado] = $_REQUEST[Pista::COLUMNA_colorResaltado];
        $Pista[Pista::COLUMNA_urlPatrocinador] = $_REQUEST[Pista::COLUMNA_urlPatrocinador];
        $Pista[Pista::COLUMNA_nombreImagenPatrocinador] = $_REQUEST[Pista::COLUMNA_nombreImagenPatrocinador];
        $Pista->guardar();
        CacheTablaReserva::eliminarCacheRedisTablaReservaParaElCampo($Pista->obtenerCampo()->obtenerId());
        echo 1;
        break;

    case "imprimirTodasLasPistasListado":
        echo Pista::imprimirTodasLasPistas(Pista::IMPRIMIRTODASLASPISTAS_FORMATO_LISTADO, $idCampo);
        break;

    case "imprimirTodasLasPistasBotones":
        echo Pista::imprimirTodasLasPistas(Pista::IMPRIMIRTODASLASPISTAS_FORMATO_BOTONES, $idCampo);
        break;

    case "imprimirTodasLasPistas":
        echo Pista::imprimirTodasLasPistas($formato, $idCampo, $fechaHoy, $opcionSeleccionada, $disabled, $multiple, $onchange, $idDeporte);
        break;


    case "eliminar":
        $Pista = new Pista($id);
        $Pista->eliminar();
        echo 1;
        break;

    case "imprimirFormularioPista":

        $Pista = new Pista($id);
        echo $Pista->imprimirFormulario($idCampo, $idioma);

        break;

    case "desactivarPista":
        $Pista = new Pista($id);
        $Pista["desactivado"] = 1;
        $Pista->guardar();
        break;

    case "activarPista":
        $Pista = new Pista($id);
        $Pista["desactivado"] = 0;
        $Pista->guardar();
        break;

    case "imprimirSelectorPistas":
        echo Pista::imprimirSelectorPistas($idCampo, $idClub);
        break;

    case Pista::OP_obtenerIdDeporte:
        $Pista = new Pista($idPista);
        echo $Pista->obtenerDeporte()->obtenerId();
        break;

    case Pista::OP_obtenerNumeroJugadoresMaximo:
        echo (new Pista($idPista))->obtenerNumeroJugadoresMaximo();
        break;

    case Pista::OP_obtenerInformacionEsPrecioPorJugador:
        $Pista = new Pista($idPista);

        if ($Pista->obtenerDeporte()->esPrecioPorJugador()){
            echo Traductor::traducir("Indicar precio de la reserva por jugador");
        }
        else{
            echo Traductor::traducir("Indicar precio total de la reserva");
        }
        break;


        /*
    case "imprimirFilaRegistro":
        if ($id > 0){
            $Pista = new Pista($id);
        }
        else{
            $Pista = new Pista();
            $ultimoID = $Pista->obtenerUltimoID();
            $Pista = new Pista($ultimoID);
        }

        $Pista->imprimirFilaRegistro();
        break;
        */
}