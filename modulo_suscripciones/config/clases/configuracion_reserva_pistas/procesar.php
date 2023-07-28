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
            $ConfiguracionReservaPistas = new ConfiguracionReservaPistas($id);
        }
        else if($idCampo > 0 || $idCampo == ""){
            $ConfiguracionReservaPistas = ConfiguracionReservaPistas::obtenerConfiguracionReservaPistas($idCampo);
        }
        else{
            $ConfiguracionReservaPistas = new ConfiguracionReservaPistas();
        }

        $ConfiguracionReservaPistas["idCampo"] = $idCampo;
        $ConfiguracionReservaPistas["diasAntesGeneral"] = $diasAntesGeneral;
        $ConfiguracionReservaPistas["diasAntesRadical"] = $diasAntesRadical;
        $ConfiguracionReservaPistas["diasAntesSocios"] = $diasAntesSocios;
        $ConfiguracionReservaPistas["diasAntesGrupo1"] = $diasAntesGrupo1;
        $ConfiguracionReservaPistas["diasAntesGrupo2"] = $diasAntesGrupo2;
        $ConfiguracionReservaPistas["diasAntesMonedero"] = $diasAntesMonedero;
        $ConfiguracionReservaPistas["horasAntesCancelarGeneral"] = $horasAntesCancelarGeneral;
        $ConfiguracionReservaPistas["horasAntesCancelarRadical"] = $horasAntesCancelarRadical;
        $ConfiguracionReservaPistas["horasAntesCancelarSocios"] = $horasAntesCancelarSocios;
        $ConfiguracionReservaPistas["horasAntesCancelarGrupo1"] = $horasAntesCancelarGrupo1;
        $ConfiguracionReservaPistas["horasAntesCancelarGrupo2"] = $horasAntesCancelarGrupo2;
        $ConfiguracionReservaPistas["horasAntesCancelarMonedero"] = $horasAntesCancelarMonedero;
        $ConfiguracionReservaPistas["pagoOnlineGeneral"] = $pagoOnlineGeneral;
        $ConfiguracionReservaPistas["pagoOnlineRadical"] = $pagoOnlineRadical;
        $ConfiguracionReservaPistas["pagoOnlineSocios"] = $pagoOnlineSocios;
        $ConfiguracionReservaPistas["pagoOnlineGrupo1"] = $pagoOnlineGrupo1;
        $ConfiguracionReservaPistas["pagoOnlineGrupo2"] = $pagoOnlineGrupo2;
        $ConfiguracionReservaPistas["pagoOnlineMonedero"] = $pagoOnlineMonedero;
        $ConfiguracionReservaPistas["pagoProporcionalGeneral"] = $pagoProporcionalGeneral;
        $ConfiguracionReservaPistas["pagoProporcionalRadical"] = $pagoProporcionalRadical;
        $ConfiguracionReservaPistas["pagoProporcionalSocios"] = $pagoProporcionalSocios;
        $ConfiguracionReservaPistas["pagoProporcionalGrupo1"] = $pagoProporcionalGrupo1;
        $ConfiguracionReservaPistas["pagoProporcionalGrupo2"] = $pagoProporcionalGrupo2;
        $ConfiguracionReservaPistas["pagoProporcionalMonedero"] = $pagoProporcionalMonedero;
        $ConfiguracionReservaPistas["colorResaltadoPistaOcupada"] = $colorResaltadoPistaOcupada;
        $ConfiguracionReservaPistas["colorResaltadoPistaLibre"] = $colorResaltadoPistaLibre;
        $ConfiguracionReservaPistas["colorResaltadoSinOferta"] = $colorResaltadoSinOferta;
        $ConfiguracionReservaPistas["colorResaltadoConOferta"] = $colorResaltadoConOferta;
        $ConfiguracionReservaPistas["mostrarPrecios"] = $mostrarPrecios;
        $ConfiguracionReservaPistas["mostrarOferta"] = $mostrarOferta;
        $ConfiguracionReservaPistas["seleccionarPistas"] = $seleccionarPistas;
        $ConfiguracionReservaPistas["seleccionarDuracion"] = $seleccionarDuracion;
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_mostrarSelectorPistaFormatoTabla] = $mostrarSelectorPistaFormatoTabla;
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_nombrePrimerMonedero] = $nombrePrimerMonedero;
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_nombreSegundoMonedero] = $nombreSegundoMonedero;
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_idTipoReservaSegundoMonedero] = $idTipoReservaSegundoMonedero;
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_aplazarPagoMonedero] = $aplazarPagoMonedero;
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_esPermitidoReservarAJugadores] = $esPermitidoReservarAJugadores;
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_esPermitidoReservarAJugadoresNoActivos] = $_REQUEST[ConfiguracionReservaPistas::COLUMNA_esPermitidoReservarAJugadoresNoActivos];
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_esPermitidoPrerreservas] = $_REQUEST[ConfiguracionReservaPistas::COLUMNA_esPermitidoPrerreservas];
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_horasAntelacionMinimaSePermiteRealizarReserva] = $_REQUEST[ConfiguracionReservaPistas::COLUMNA_horasAntelacionMinimaSePermiteRealizarReserva];
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_esPermitidoVerListadoPartidosAVisitantes] = $_REQUEST[ConfiguracionReservaPistas::COLUMNA_esPermitidoVerListadoPartidosAVisitantes];
        $ConfiguracionReservaPistas[ConfiguracionReservaPistas::COLUMNA_esMostrarPartidosEnTablaReservas] = $_REQUEST[ConfiguracionReservaPistas::COLUMNA_esMostrarPartidosEnTablaReservas];
        $ConfiguracionReservaPistas->guardar();
        echo 1;
        break;
        

    case "eliminar":
        $ConfiguracionReservaPistas = new ConfiguracionReservaPistas($id);
        $ConfiguracionReservaPistas->eliminar();
        echo 1;
        break;
}