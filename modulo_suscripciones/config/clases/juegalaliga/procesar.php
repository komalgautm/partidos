<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR );


require_once "../../config.php";


extract($_GET);
extract($_POST);


switch ($op){

    case "anadirJugadorALaLiga":
       Juegalaliga::anadirJugadorALaLiga($idJugador, $idLiga, $estado);

        break;

    case Juegalaliga::OP_permitirVisitarLiga:
        $Juegalaliga = new Juegalaliga('','', $idJuegalaliga);
        $Juegalaliga->actualizarPermitirVisitarLiga($permitirVisitarLiga, true);
        break;

    case Juegalaliga::OP_actualizarEstado:
        $Juegalaliga = new Juegalaliga('','', $idJuegalaliga);
        $Juegalaliga[Juegalaliga::COLUMNA_estado] = $estado;
        $Juegalaliga->guardar();
        break;

    case Juegalaliga::OP_actualizarEstadoBloquearDesbloquear:
        $Juegalaliga = new Juegalaliga('','', $idJuegalaliga);
        if ($Juegalaliga->obtenerEstado() == Juegalaliga::TIPO_ESTADO_bloqueado){
            $Juegalaliga->actualizarEstado(Juegalaliga::TIPO_ESTADO_pendiente, true);
        }
        else{
            $Juegalaliga->actualizarEstado(Juegalaliga::TIPO_ESTADO_bloqueado, true);
        }
        $Juegalaliga = new Juegalaliga('','', $idJuegalaliga);
        echo $Juegalaliga->obtenerEstado();
        break;

    case Juegalaliga::OP_actualizarEsPermitidoVerListadoPartidos:
        $Juegalaliga = new Juegalaliga("","", $idJuegalaliga);
        $Juegalaliga->actualizarEsPermitidoVerListadoDePartidos($esPermitidoVerListadoPartidos, true);
        $Juegalaliga = new Juegalaliga('','', $idJuegalaliga);
        echo $Juegalaliga->esPermitidoVerListadoDePartidos();
        break;

    case Juegalaliga::OP_activarDesactivarEsPermitidoVerListadoPartidos:
        $Juegalaliga = new Juegalaliga("","", $idJuegalaliga);
        $Juegalaliga->actualizarEsPermitidoVerListadoDePartidos(!$Juegalaliga->esPermitidoVerListadoDePartidos(), true);
        $Juegalaliga = new Juegalaliga('','', $idJuegalaliga);
        echo $Juegalaliga->esPermitidoVerListadoDePartidos();
        break;
}