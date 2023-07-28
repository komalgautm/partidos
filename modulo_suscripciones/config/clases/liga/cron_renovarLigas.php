<?php

function cronRenovarLigas(){

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR );

    require_once BASE."funciones.php";

    /*
    require_once "../../../../ligagolf_PCU_SinSeguridad.php";
    require_once "../../config.php";
    require_once "../../../../funciones.php";
    */
    mysql_pconnect(BD_SERVIDOR,USUARIO_BD,CONTRASENA_BD);
    @mysql_select_db (NOMBRE_BD);


    global $bd;
    $fechaHoy = date("Y-m-d");
    $fechaHoyMas5Dias = date("Y-m-d",strtotime($fechaHoy."+ 5 days"));

//JMAM: Obtener las ligas a renover automáticamente

    $where["AND"] = array(
        Liga::COLUMNA_fechaFin."[>=]" => $fechaHoy,
        Liga::COLUMNA_fechaFin."[<=]" => $fechaHoyMas5Dias,
        Liga::COLUMNA_estado => Liga::ESTADO_activa,
        //Liga::COLUMNA_tipoLiga."[!]" => Liga::TIPO_LIGA_suscripcion
    );
    $arrayIdsLigas = $bd->select(Liga::TABLA_nombre, Liga::COLUMNA_id, $where);
    print_r($arrayIdsLigas);

    echo "<br/>Ligas a renovar o liquidar: ".count($arrayIdsLigas);


    foreach ($arrayIdsLigas as $idLiga){

        $Liga = new Liga($idLiga);
        $LigaMasReciente = $Liga->obtenerEdicionLigaActivaMasReciente();


        //JMAM: Si la liga ya está renovada, existe una edición mayor
        if ($Liga->existeEdicionMasReciente()){
            //JMAM: NO ejecutar renovación
            echo "<br/>La liga ya está renovada: LIGA a RENOVAR: $idLiga -> Última Edicion ID Liga: ".$LigaMasReciente->obtenerId();
        }
        else{
            //JMAM: La liga no está renovada

            //JMAM: Renovar liga
            echo "Renovar pasa a renovarse: $idLiga";

            if ($fechaHoy == $Liga->obtenerFechaFin() && $Liga->esSuscripcion()){
                //JMAM: Fecha de suscripción se renuevan el mismo día de fin
                renovarLiga($idLiga);
            }
            else if ($fechaHoy != $Liga->obtenerFechaFin() && $Liga->esSuscripcion() == false){
                //JMAM: Resto de ligas se renuevan 5 días antes
                renovarLiga($idLiga);
            }




        }

        echo "<br/><br/><br/>";

        $LigaNueva = $Liga->obtenerEdicionLigaActivaMasReciente();

        $fechaFinLiga = formatearFecha($Liga->obtenerFechaFin());
        $nombreLiga = $Liga->obtenerNombre(true);
        $edicionNuevaLiga = $LigaNueva->obtenerEdicion();
        $fechaInicioNuevaliga = formatearFecha($LigaNueva->obtenerFechaInicio());
        $fechaFinNuevaLiga = formatearFecha($LigaNueva->obtenerFechaFin());
        $fechaFinMas1Dia = formatearFecha(date("Y-m-d",strtotime($Liga->obtenerFechaFin()."+ 1 days")));

        $asunto = Traductor::traducir("Finalización de la liga", false, $Liga->obtenerCodigoPais()).": ".$Liga->obtenerNombre()." ".$Liga->obtenerEdicion();


        if ($fechaHoy != $Liga->obtenerFechaFin() && $Liga->esSuscripcion() == false){
            //JMAM: La fecha de Fin de liga no es igual a Hoy
            $search = array("%FECHA_FIN_LIGA%", "%NOMBRE_LIGA%", "%EDICION_NUEVA_LIGA%", "%FECHA_INICIO_NUEVA_LIGA%", "%FECHA_FIN_NUEVA_LIGA%", "%FECHA_FIN_LIGA_MAS_1_DIA%");
            $replace = array($fechaFinLiga, $nombreLiga, $edicionNuevaLiga, $fechaInicioNuevaliga,$fechaFinNuevaLiga, $fechaFinMas1Dia);

            if ($Liga->tieneAlgunPackDisponibleYActivo()){
                $mensaje = str_replace($search, $replace, Traductor::traducir("EMAIL_FINALIZACION_LIGA_CON_PACK", false, $Liga->obtenerCodigoPais()));
            }
            else{
                $mensaje = str_replace($search, $replace, Traductor::traducir("EMAIL_FINALIZACION_LIGA_SIN_PACK", false, $Liga->obtenerCodigoPais()));
            }


            echo "<br/>".$asunto;
            Email::enviarEmail($Liga->obtenerEmailContacto(), $asunto, $mensaje, true, false, false, true, true);


        }

        if ($fechaHoy == $Liga->obtenerFechaFin()){
            //JMAM: La fecha fin de liga es igual a HOY

            //JMAM: SE FINALIZA LA LIGA ////////////////////////////////////////////////////////////////////////////////
            liquidarLiga($Liga->obtenerId());
            echo "<br/>Liga pasa a Terminada: ".$Liga->obtenerId();

        }

    }
}
