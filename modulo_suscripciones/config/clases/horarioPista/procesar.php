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
            //$esRepetidoHorario = HorarioPista::esRepetidoTramoHorario($idPista, $diaSemana, $horaInicio, $horaFin, $fechaInicio, $fechaFin);

            if ($esRepetidoHorario){
                echo "2";
                return;
            }
            $HorarioPista = new HorarioPista();
        }

        if ($precioGeneral == ""){
            $precioGeneral = -1;
        }
        if ($precioRadical == ""){
            $precioRadical = -1;
        }
        if ($precioSocios == ""){
            $precioSocios = -1;
        }
        if ($precioGrupo1 == ""){
            $precioGrupo1 = -1;
        }
        if ($precioGrupo2 == ""){
            $precioGrupo2 = -1;
        }
        if ($precioMonedero == "" || $precioMonedero == 0){
            $precioMonedero = -1;
        }

        if (empty($fechaInicio)){
            $fechaInicio = date("Y-m-d");
        }

        if (empty($fechaFin)){
            $fechaFin = "3000-01-01";
        }



        //JMAM: Guarda el Horario
        $HorarioPista["idPista"] = $idPista;
        $HorarioPista["diaSemana"] = $diaSemana;
        $HorarioPista["horaInicio"] = $horaInicio;
        $HorarioPista["horaFin"] = $horaFin;
        $HorarioPista[HorarioPista::COLUMNA_fechaInicio] = $fechaInicio;
        $HorarioPista[HorarioPista::COLUMNA_fechaFin] = $fechaFin;
        $idHorario = $HorarioPista->guardar();
        HoraPistaBloqueada::eliminarHorariosPistasBloqueadaQueNoTenganNingunHorarioPista($idPista, $diaSemana);
        CacheTablaReserva::eliminarCacheRedisIdPistaYFecha($idPista);

        if ($id == ""){
            //JMAM: Sólo se añade el Tiempo de Reserva cuando se crea el horario, no al actualizar

            //JMAM: Guarda el Tiempo de Reserva
            $TiempoReserva = new TiempoReserva();
            $TiempoReserva[TiempoReserva::COLUMNA_idHORARIO] = $idHorario;
            $TiempoReserva[TiempoReserva::COLUMNA_TIEMPORESERVA] = $tiempoReserva;
            $TiempoReserva[TiempoReserva::COLUMNA_PRECIOGENERAL] = $precioGeneral;
            $TiempoReserva[TiempoReserva::COLUMNA_PRECIORADICAL] = $precioRadical;
            $TiempoReserva[TiempoReserva::COLUMNA_PRECIOSOCIOS] = $precioSocios;
            $TiempoReserva[TiempoReserva::COLUMNA_PRECIOGRUPO1] = $precioGrupo1;
            $TiempoReserva[TiempoReserva::COLUMNA_PRECIOGRUPO2] = $precioGrupo2;
            $TiempoReserva[TiempoReserva::COLUMNA_ILUMINACIONINCLUIDA] = $iluminacionIncluida;
            $TiempoReserva[TiempoReserva::COLUMNA_PRECIOMONEDERO] = $precioMonedero;
            $TiempoReserva->guardar();
        }


        
        
        echo 1;
        break;

    case "eliminar":
        $HorarioPista = new HorarioPista($id);
        $HorarioPista->eliminar();
        echo 1;
        break;

    case "imprimirTodasLosHorarios":
        ?>
        <?php
        HorarioPista::imprimirTodosRegistros($idPista, $diaSemana);
        break;

    case "imprimirTodosLosHorariosFijosDisponibles":
        break;


    case "copiarHorario":

        $array_idsDiaSemanaCopia = $idsDiaSemanaCopiar;
        $array_idsPistaCopiar = $idsPistasCopiar;

        foreach ($idsPistasCopiar as $idPistaCopia){

            foreach ($idsDiaSemanaCopiar as $idDiaSemanaCopia){

                if (!($idPista == $idPistaCopia && $diaSemana == $idDiaSemanaCopia)){

                    //JMAM: Copia los Horarios
                    echo "Eliminar: ID PISTA:$idPista ID PISTA COPIA:$idPistaCopia DIA SEMANA:$diaSemana DIA SEMANA COPIA:$idDiaSemanaCopia<br/>";
                    echo HorarioPista::eliminarTodos($idPistaCopia, $idDiaSemanaCopia);
                    HorarioPista::duplicarHorario($idPista, $diaSemana, $idPistaCopia, $idDiaSemanaCopia);

                    //JMAM: Copia las Horas de Pista
                    HoraPistaBloqueadaTramo::duplicarHorasPistaBloqueadasTramo($idPista, $diaSemana, $idPistaCopia, $idDiaSemanaCopia);
                    HoraPistaBloqueada::duplicarHorasPistaBloqueadas($idPista, $diaSemana, $idPistaCopia, $idDiaSemanaCopia);
                }




            }
        }
        break;
}