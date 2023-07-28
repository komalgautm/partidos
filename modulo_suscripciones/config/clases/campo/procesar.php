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
            $HorarioPista = new HorarioPista($id);
        }
        else{
            $HorarioPista = new HorarioPista();
        }

        //JMAM: Guarda el Horario
        $HorarioPista["idPista"] = $idPista;
        $HorarioPista["diaSemana"] = $diaSemana;
        $HorarioPista["horaInicio"] = $horaInicio;
        $HorarioPista["horaFin"] = $horaFin;
        $idHorario = $HorarioPista->guardar();

        //JMAM: Guarda el Tiempo de Reserva
        $TiempoReserva = new TiempoReserva();
        $TiempoReserva["idHorario"] = $idHorario;
        $TiempoReserva["tiempoReserva"] = $tiempoReserva;
        $TiempoReserva["precioGeneral"] = $precioGeneral;
        $TiempoReserva["precioSocios"] = $precioSocios;
        $TiempoReserva["precioGrupo1"] = $precioGrupo1;
        $TiempoReserva["precioGrupo2"] = $precioGrupo2;
        $TiempoReserva["iluminacionIncluida"] = $iluminacionIncluida;
        $TiempoReserva["precioMonedero"] = $precioMonedero;
        $TiempoReserva->guardar();
        
        
        echo 1;
        break;

    case "eliminar":
        $Campo = new Campo($id);
        $Campo->eliminar();
        echo 1;
        break;

    case "imprimirTodasLosHorarios":
        ?>


        <?php

        HorarioPista::imprimirTodosRegistros($idPista, $diaSemana);
        break;

    case "sePermiteRealizarReservaAJugadores":

        $array_respuesta = array();

        $Campo = new Campo($id);
        if ($Campo->activadoModuloReserva() == false || $Campo->esPermitidoRealizarReservasPorJugadores() == false){
            $array_respuesta["mostrarBotonReservar"] = 0;
            $array_respuesta["mostrarBotonPartido"] = 1;
            //echo 0;
            echo json_encode($array_respuesta);
            return;
        }


        $Campo = new Campo($id);
        $Club = $Campo->obtenerClub();
        if (!$Club->puedeAdministrarAbrirPartidoDesdePanelReservar($idLiga)){
            $array_respuesta["informacionAdicional"] = Traductor::traducir("Las reservas online no están operativas temporalmente. Contacta con el club");
            $array_respuesta["mostrarBotonReservar"] = 0;
            $array_respuesta["mostrarBotonPartido"] = 1;
            echo json_encode($array_respuesta);
            return;
        }

        if (empty($hora)){
            $array_respuesta["informacionAdicional"] = Traductor::traducir("No existen horarios disponibles para el día seleccionado");
            $array_respuesta["mostrarBotonReservar"] = 0;
            $array_respuesta["mostrarBotonPartido"] = 0;
            echo json_encode($array_respuesta);
            return;
        }

        //JMAM: Obtiene el número de días que faltan para el partido
        $Jugador = new Jugador($idJugador);
        if($Jugador->puedeRealizarReservaPorDiasAntelacion($id, $idLiga, $fechaMYSQL)){

            if ($Jugador->puedeRealizarReservaPorHoraAntelacionMinima($id, $hora)){
                $array_respuesta["mostrarBotonReservar"] = 1;
                $array_respuesta["mostrarBotonPartido"] = 0;
                echo json_encode($array_respuesta);
            }
            else{
                $horasAntelacionMinimaSePermiteRealizarReserva = $Campo->obtenerConfiguracionReservaPistas()->obtenerHorasAntelacionMinimaSePermiteRealizarReserva();
                $array_respuesta["mostrarBotonReservar"] = 0;
                $array_respuesta["mostrarBotonPartido"] = 0;
                $array_respuesta["mostrarInformacionAdicionalAlert"] = 1;
                $array_respuesta["informacionAdicional"] = Traductor::traducir("Es necesario realizar las reservas con una antelación mínima de").": $horasAntelacionMinimaSePermiteRealizarReserva h";
                echo json_encode($array_respuesta);
            }

            //echo 1;
        }
        else{
            //JMAM: NO se puede hacer la reserva, si la prerreserva

            //JMAM: Comprobar si es posible hacer la prerreserva
            if ($Campo->obtenerConfiguracionReservaPistas()->esPermitidoPrerreservas()){
                //JMAM: Prerreservas Permitidas
                $fechaPuedeReservarPista = formatearFecha($Jugador->obtenerFechaQuePuedeRealizarReservaPista($id, $idLiga, $fechaMYSQL),false, false, true);
                $texto = Traductor::traducir("La reserva de este partido se podrá efectuar a partir del %FECHA_SE_PUEDE_HACER_RESERVA%");
                $texto = str_replace("%FECHA_SE_PUEDE_HACER_RESERVA%", $fechaPuedeReservarPista, $texto);
                //echo $texto;
                $array_respuesta["mostrarBotonReservar"] = 0;
                $array_respuesta["mostrarBotonPartido"] = 1;
                $array_respuesta["informacionAdicional"] = $texto;
                echo json_encode($array_respuesta);
                return;
            }
            else{
                //JMAM: NO se permite Prerreservas
                $fechaPuedeReservarPista = formatearFecha($Jugador->obtenerFechaMaximaPuedeReservaPista($id, $idLiga),false, false, true);
                $texto = Traductor::traducir("¡Te has pasado!, elije una fecha para hacer tu Reserva antes del")." %FECHA_SE_PUEDE_HACER_RESERVA%";
                $texto = str_replace("%FECHA_SE_PUEDE_HACER_RESERVA%", $fechaPuedeReservarPista, $texto);
                $array_respuesta["mostrarBotonReservar"] = 0;
                $array_respuesta["mostrarBotonPartido"] = 0;
                $array_respuesta["mostrarInformacionAdicionalAlert"] = 1;
                $array_respuesta["informacionAdicional"] = $texto;
                echo json_encode($array_respuesta);
                //echo $texto;
                return;
            }


        }
        return;


        break;

    case "activadoModuloReserva":
        $Campo = new Campo($id);
        echo $Campo->activadoModuloReserva();
        break;

    case "activarDesactivarModuloReserva":

        echo "Valor1: $valor";
        $Campo = new Campo($id);
        $Campo[Campo::COLUMNA_MODULORESERVAACTIVADODESACTIVADO] = $valor;
        $Campo->guardar();

        echo "Valor: $valor";
        break;

    case "esModuloReservaActivado":
        $Campo = new Campo($id);
        echo $Campo->activadoModuloReserva();
        break;

    case "esActivadoMostrarSelectorPistaPorTabla":
        $Campo = new Campo($id);
        $ConfiguracionReservaPistas = $Campo->obtenerConfiguracionReservaPistas();
        echo $ConfiguracionReservaPistas->esPermitidoMostrarSelectorPistaFormatoTabla();
        break;
}