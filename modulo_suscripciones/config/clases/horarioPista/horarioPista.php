<?php

class HorarioPista extends general
{
    const NOMBRE_TABLA = "horarios_pista";
    const COLUMNA_id = "id";
    const COLUMNA_idPista = "idPista";
    const COLUMNA_diaSemana = "diaSemana";
    const COLUMNA_horaInicio = "horaInicio";
    const COLUMNA_horaFin = "horaFin";
    const COLUMNA_fechaInicio = "fechaInicio";
    const COLUMNA_fechaFin = "fechaFin";

    const DIASEMANA_LUNES = "1";
    const DIASEMANA_MARTES = "2";
    const DIASEMANA_MIERCOLES = "3";
    const DIASEMANA_JUEVES = "4";
    const DIASEMANA_VIERNES = "5";
    const DIASEMANA_SABADO = "6";
    const DIASEMANA_DOMINGO = "7";



    function HorarioPista($id)
    {
        if ($id != '')
            parent::__construct(self::NOMBRE_TABLA, 'id', $id);
        else
            parent::__construct(self::NOMBRE_TABLA, '', '');
    }

    static function obtenerTodos($idPista = "", $diaSemana = ""){
        global $bd;

        if ($idPista == ""){
            $ids = $bd->select(self::NOMBRE_TABLA, "id", array("ORDER" => "id DESC"));
        }
       else{
           $ids = $bd->select(self::NOMBRE_TABLA, "id", array("AND" => array("idPista" => $idPista, "diaSemana" => $diaSemana)), array("ORDER" => "id DESC"));
       }

        $array = [];
        foreach ($ids as $id) {
            $array[] = new HorarioPista($id);
        }
        return $array;
    }

    static function obtenerIdsHorarioPistaPorIdPistaYDiaSemana($idPista, $diaSemana){
        global $bd;

        $where["AND"][self::COLUMNA_idPista] = $idPista;
        $where["AND"][self::COLUMNA_diaSemana] = $diaSemana;
        return $bd->select(self::NOMBRE_TABLA, self::COLUMNA_id, $where);
    }


    static function existeAlgunHorarioPistaParaLaPistaYDiaSemana($idPista, $diaSemana){
        global $bd;


        if ($diaSemana == 0){
            $diaSemana = 7;
        }

        $filas = $bd->query("SELECT COUNT(id) FROM horarios_pista WHERE idPista = $idPista AND diaSemana = $diaSemana AND horaInicio != '00:00:00'")->fetchAll();

        $numeroHorariosPista = $filas[0][0];

        if ($numeroHorariosPista == 0){
            return false;
        }

        return true;


    }


    static function existeAlgunHorarioPistaParaElCampoYFecha($idCampo, $fechaMYSQL){
        global $bd;

        if (!empty($fechaMYSQL)){
            //JMAM: Obtiene el día de la semana de la Reserva
            $fecha = strtotime($fechaMYSQL);
            $diaSemana = jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m",$fecha),date("d",$fecha), date("Y",$fecha)) , 0);
        }

        if ($diaSemana == 0){
            $diaSemana = 7;
        }

        $filas = $bd->query("SELECT COUNT(id) FROM horarios_pista WHERE idPista IN (SELECT id FROM pistas WHERE idCampo = $idCampo AND desactivado=0) AND diaSemana = $diaSemana AND horaInicio != '00:00:00'")->fetchAll();

        $numeroHorariosPista = $filas[0][0];

        if ($numeroHorariosPista == 0){
            return false;
        }

        return true;


    }

    static function obtenerTodosIdsHorariosPista($idPista){
        global $bd;

        return $bd->select(self::NOMBRE_TABLA, self::COLUMNA_id, array(self::COLUMNA_idPista => $idPista));
    }


    static function duplicarHorario($idPistaOrigen, $diaSemanaOrigen, $idPistaCopia, $diaSemanaCopia){

        $array_Horarios = HorarioPista::obtenerTodos($idPistaOrigen, $diaSemanaOrigen);

        foreach ($array_Horarios as $HorarioPista){
            $HorarioPista->duplicar($idPistaCopia, $diaSemanaCopia);
        }
    }

    static function eliminarTodos($idPista, $diaSemana){
        global $bd;

        $ids = $bd->select(self::NOMBRE_TABLA, "id", array("AND" => array("idPista" => $idPista, "diaSemana" => $diaSemana)));

        foreach ($ids as $id) {
            $HorarioPista = new HorarioPista($id);
            $HorarioPista->eliminarTodosLosTiemposReserva();
            $HorarioPista->eliminar();
        }

    }

    static function obtenerTiempoReservaMaximo($idPista){

        $array_idsHorarioPista = HorarioPista::obtenerTodosIdsHorariosPista($idPista);

        $idTiempoReservaMaximo = 0;
        $duracionAnterior = 0;
        foreach ($array_idsHorarioPista as $idHorarioPista){
            $TiempoReserva = TiempoReserva::obtenerTiempoReservaMaximoHorario($idHorarioPista);
            $duracion = $TiempoReserva->obtenerDuracion(true);

            //echo "</br>".$duracion;

            if ($duracion > $duracionAnterior){
                $duracionAnterior = $duracion;
                $idTiempoReservaMaximo = $TiempoReserva->obtenerId();
            }

        }

        return new TiempoReserva($idTiempoReservaMaximo);
    }

    static function obtenerDiaSemanaDeFecha($fechaMYSQL){
        //JMAM: Obtiene el día de la semana de la Reserva
        $fecha = strtotime($fechaMYSQL);
        $diaSemana = jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m",$fecha),date("d",$fecha), date("Y",$fecha)) , 0);

        if ($diaSemana == 0){
            $diaSemana = 7;
        }

        return $diaSemana;
    }



    static function obtenerHorariosPistaDisponibles($idPista, $fechaMYSQL, $hora, $ignorarHoraPistaBloqueada=false, $devolverPrimeroSolo=false){
        global $bd;


        $array_HorariosPista = array();                                 //JMAM: Almacena los Horarios de Pista Posible

        $Pista = new Pista($idPista);
        $horaInicioMinimoPista = $Pista->obtenerHorarioInicioMinimoPista($fechaMYSQL);
        $horaFinMaximoPista = $Pista->obtenerHorarioFinMaximoPista($fechaMYSQL);


        $horaInicioMinimoPista_strotime = strtotime($horaInicioMinimoPista);
        $horaFinMaximoPista_strotime = strtotime($horaFinMaximoPista);
        $hora_strotime = strtotime($hora);

        $strtotime_horaFinLimiteDia = strtotime("06:00:00");


        if ($horaFinMaximoPista_strotime > $strtotime_horaFinLimiteDia){
            //JMAM: Comprueba si la hora está entre el horario mínimo y máximo de la pista
            if ($horaInicioMinimoPista_strotime > $hora_strotime && $horaFinMaximoPista_strotime > $hora_strotime){
                //JMAM: Hora no está entre horario mínimo y máximo de la pista

                //JMAM: No hay horarios disponibles.
                Log::v(__FUNCTION__, "NO HAY HORARIOS DISPONIBLES [HORARIO EN EL MISMO DÍA]", false);
                return array();
            }
        }
        else{
            //JMAM: Comprueba si la hora está entre el horario mínimo y máximo de la pista
            if ($horaInicioMinimoPista_strotime < $hora_strotime && $horaFinMaximoPista_strotime > $hora_strotime){
                //JMAM: Hora no está entre horario mínimo y máximo de la pista

                //JMAM: No hay horarios disponibles.
                Log::v(__FUNCTION__, "NO HAY HORARIOS DISPONIBLES [HORARIO EN DOS DIAS]", false);
                return array();
            }
        }



        //JMAM: Obtiene el día de la semana de la Reserva
        $diaSemana = self::obtenerDiaSemanaDeFecha($fechaMYSQL);


        if (!$ignorarHoraPistaBloqueada){
            //JMAM: Comprueba si la Hora no está Bloqueada
            $esHoraPistaBloqueada = HoraPistaBloqueada::esHoraPistaBloqueada($idPista,$diaSemana, $hora, $fechaMYSQL);
            if ($esHoraPistaBloqueada){
                //JMAM: Hora está Bloqueada

                //JMAM: NO hay horarios disponibles para esa Hora
                Log::v(__FUNCTION__, "NO HAY HORARIOS DISPONIBLES EN ESA HORA: ID PISTA: $idPista | DIA SEMANA: $diaSemana | HORA: $hora");

                return array();
            }
        }



        //echo "Día de la semana: $diaSemana";

        //JMAM: Obtiene los Horarios Posibles
        //$consulta_SQL = "SELECT id FROM horarios_pista WHERE idPista = $idPista AND diaSemana = $diaSemana AND (horaInicio <= TIME('$hora') OR horaFin >= TIME('$hora'))";





        /*
        $consulta_SQL = "
                    SELECT id FROM horarios_pista WHERE idPista = $idPista
                                                  AND diaSemana = $diaSemana
                                                  AND (horaInicio <= TIME('$hora') OR horaFin >= TIME('$hora'))
                                                  AND (fechaInicio <= '$fechaMYSQL' AND fechaFin >= '$fechaMYSQL')
                                                  ";
        */

        $consulta_SQL = "
                    SELECT id FROM horarios_pista WHERE idPista = $idPista
                                                  AND diaSemana = $diaSemana
                                                  AND (fechaInicio <= '$fechaMYSQL' AND fechaFin >= '$fechaMYSQL')
                                                  ";

        $filas = $bd->query($consulta_SQL);
        Log::v(__FUNCTION__, "Consulta: $consulta_SQL");


        foreach ($filas as $fila){
            $id = $fila["id"];
            $HorarioPista = new HorarioPista($id);
            if ($HorarioPista->tieneTiemposReservaDisponibles($hora) && $HorarioPista->esFechaEnRangoHorarioPista($fechaMYSQL)){
                $array_HorariosPista[] = $HorarioPista;

                if ($devolverPrimeroSolo){
                    return  $array_HorariosPista;
                }
            }

        }


        return $array_HorariosPista;

    }

    static function obtenerHoraInicioMinimoPistaPorDiaSemana($idPista, $diaSemana=-1){
        global $bd;

        $where["AND"][self::COLUMNA_idPista] = $idPista;
        $where["AND"][self::COLUMNA_diaSemana] = $diaSemana;

        return $bd->min(self::NOMBRE_TABLA, self::COLUMNA_horaInicio, $where);
    }


    static function obtenerHoraFinMaximoPistaPorDiaSemana($idPista, $diaSemana=-1){
        global $bd;

        $where = array();
        $where["AND"][self::COLUMNA_idPista] = $idPista;
        $where["AND"][self::COLUMNA_diaSemana] = $diaSemana;
        $where["AND"][self::COLUMNA_horaFin] = "00:00:00";

        $horaFinMediaNoche = "";
        if ($bd->count(self::NOMBRE_TABLA,self::COLUMNA_id, $where) > 0){
            $horaFinMediaNoche = "00:00:00";
            Log::v(__FUNCTION__, "Existe Hora Fin de media noche: $horaFinMediaNoche", false);
        }

        $where = array();
        $where["AND"][self::COLUMNA_idPista] = $idPista;
        $where["AND"][self::COLUMNA_diaSemana] = $diaSemana;
        $horaFinMinimo = $bd->min(self::NOMBRE_TABLA, self::COLUMNA_horaFin, $where);
        $horaFinMaximo = $bd->max(self::NOMBRE_TABLA, self::COLUMNA_horaFin, $where);

        Log::v(__FUNCTION__, "Hora Fin Mínimo: $horaFinMinimo | Hora Fin Máximo: $horaFinMaximo", false);

        $strtotime_horaFinMediaNoche = strtotime($horaFinMediaNoche);
        $strtotime_horaFinMinimo = strtotime($horaFinMinimo);
        $strtotime_horaFinMaximo = strtotime($horaFinMaximo);


        $strtotime_horaSeparadorDia = strtotime("06:00");

        Log::v(__FUNCTION__, "strtotime_horaFinMediaNoche: $strtotime_horaFinMediaNoche | strtotime_horaFinMinimo: $strtotime_horaFinMinimo | strtotime_horaFinMaximo: $strtotime_horaFinMaximo | strtotime_horaSeparadorDia: $strtotime_horaSeparadorDia", false);


        if ($strtotime_horaFinMinimo < $strtotime_horaSeparadorDia && ($horaFinMinimo!= "" && $horaFinMaximo != "")){
            return $horaFinMinimo;
        }

        if ($horaFinMediaNoche == "00:00:00"){
            return $horaFinMediaNoche;
        }

        return $horaFinMaximo;

    }

    static function obtenerHoraInicioMinimoCampoDisponibleRedis($idCampo, $fechaMYSQL="", $enNumeroDias=0, $diaSemana=-1, $idDeporte=-1, $actualizarRedis=false){
        global $Redis;

        $keyRedis = WWWBASE."obtenerHoraInicioMinimoCampoDisponibleRedis($idCampo, $fechaMYSQL, $enNumeroDias, $diaSemana, $idDeporte)";
        Log::v(__FUNCTION__, $keyRedis, false);

        if ($Redis->exists($keyRedis) && REDIS_RESERVAS && !$actualizarRedis){
            Log::v(__FUNCTION__, "Desde Caché Redis", false);
            return unserialize($Redis->get($keyRedis));
        }


        $resultado =  self::obtenerHoraInicioMinimoCampoDisponible($idCampo, $fechaMYSQL, $enNumeroDias, $diaSemana, $idDeporte);
        $Redis->set($keyRedis, serialize($resultado));
        return $resultado;
    }



    static function obtenerHoraInicioMinimoCampoDisponible($idCampo, $fechaMYSQL="", $enNumeroDias=0, $diaSemana=-1, $idDeporte=-1, $ignorarPistaDesactivadas=false){
        global $bd;


        if (!empty($fechaMYSQL)){
            //JMAM: Obtiene el día de la semana de la Reserva
            $fecha = strtotime($fechaMYSQL);
            $diaSemana = jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m",$fecha),date("d",$fecha), date("Y",$fecha)) , 0);
        }

        if($enNumeroDias > 0){
            $diaSemana_minimo = $diaSemana;
            $diaSemana_maximo = $diaSemana + $enNumeroDias;
            if ($diaSemana_maximo == 0){
                $diaSemana_maximo = 7;
            }

            $where_diaSemana = "AND diaSemana >= $diaSemana_minimo AND diaSemana <= $diaSemana_maximo";
        }
        else{
            if ($diaSemana >= 0){
                if ($diaSemana == 0){
                    $diaSemana = 7;
                }

                $where_diaSemana = "AND diaSemana = $diaSemana";
            }
            else{
                $where_diaSemana = "";
            }
        }

        if ($idDeporte > 0){
            $where_idDeporte = "AND idPista IN (SELECT id FROM pistas WHERE idDeporte=$idDeporte)";
        }

        if ($ignorarPistaDesactivadas){
            $where_idPistasPorCampo = "SELECT id FROM pistas WHERE idCampo = $idCampo";
        }
        else{
            $where_idPistasPorCampo = "SELECT id FROM pistas WHERE idCampo = $idCampo AND desactivado=0";
        }




        $filas = $bd->query("SELECT MIN(horaInicio) FROM horarios_pista WHERE idPista IN ($where_idPistasPorCampo) $where_diaSemana $where_idDeporte AND horaInicio != '00:00:00'")->fetchAll();
        return $filas[0][0];

    }


    static function obtenerHoraFinMaximoCampoDisponibleRedis($idCampo, $fechaMYSQL="", $enNumeroDias=0, $diaSemana="", $idDeporte=-1, $actualizarRedis=false){
        global $Redis;
        $keyRedis = WWWBASE."obtenerHoraFinMaximoCampoDisponibleRedis($idCampo, $fechaMYSQL, $enNumeroDias, $diaSemana, $idDeporte)";
        Log::v(__FUNCTION__, $keyRedis, false);

        if ($Redis->exists($keyRedis) && REDIS_RESERVAS && !$actualizarRedis){
            Log::v(__FUNCTION__, "Desde Caché Redis", false);
            return unserialize($Redis->get($keyRedis));
        }


        $resultado =  self::obtenerHoraFinMaximoCampoDisponible($idCampo, $fechaMYSQL, $enNumeroDias, $diaSemana, $idDeporte);
        $Redis->set($keyRedis, serialize($resultado));
        return $resultado;
    }


    static function obtenerHoraFinMaximoCampoDisponible($idCampo, $fechaMYSQL="", $enNumeroDias=0, $diaSemana="", $idDeporte=-1){
        global $bd;

        if (!empty($fechaMYSQL)){
            //JMAM: Obtiene el día de la semana de la Reserva
            $fecha = strtotime($fechaMYSQL);
            $diaSemana = jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m",$fecha),date("d",$fecha), date("Y",$fecha)) , 0);
        }

        if ($diaSemana == 0){
            $diaSemana = 7;
        }

        if ($idDeporte > 0){
            $where_idDeporte = "AND idPista IN (SELECT id FROM pistas WHERE idDeporte=$idDeporte)";
        }

        $horaFinMediaNoche = "";
        $filas = $bd->query("SELECT COUNT(*) FROM horarios_pista WHERE idPista IN (SELECT id FROM pistas WHERE idCampo = $idCampo) $where_idDeporte AND diaSemana = $diaSemana AND horaFin='00:00:00'")->fetchAll();
        if ($filas[0][0] > 0){
            $horaFinMediaNoche = "00:00:00";
            Log::v(__FUNCTION__,"EXISTE HORA FIN MEDIA NOCHE: $horaFinMediaNoche");

        }


        $filas = $bd->query("SELECT MIN(horaFin) FROM horarios_pista WHERE idPista IN (SELECT id FROM pistas WHERE idCampo = $idCampo) $where_idDeporte AND diaSemana = $diaSemana AND horaFin !='00:00:00'")->fetchAll();
        $horaFinMinimo = $filas[0][0];

        $filas = $bd->query("SELECT MAX(horaFin) FROM horarios_pista WHERE idPista IN (SELECT id FROM pistas WHERE idCampo = $idCampo) $where_idDeporte AND diaSemana = $diaSemana AND horaFin !='00:00:00'")->fetchAll();
        $horaFinMaximo = $filas[0][0];


        Log::v(__FUNCTION__,"ID CAMPO: $idCampo | FECHA: $fechaMYSQL | DIA SEMANA: $diaSemana | HORA FIN MEDIA NOCHE: $horaFinMediaNoche | HORA FIN MINIMO: $horaFinMinimo | HORA FIN MAXIMO: $horaFinMaximo", false);


        $strtotime_horaFinMediaNoche = strtotime($horaFinMediaNoche);
        $strtotime_horaFinMinimo = strtotime($horaFinMinimo);
        $strtotime_horaFinMaximo = strtotime($horaFinMaximo);


        $strtotime_horaSeparadorDia = strtotime("06:00");

        Log::v(__FUNCTION__, "strtotime_horaFinMediaNoche: $strtotime_horaFinMediaNoche | strtotime_horaFinMinimo: $strtotime_horaFinMinimo | strtotime_horaFinMaximo: $strtotime_horaFinMaximo | strtotime_horaSeparadorDia: $strtotime_horaSeparadorDia", false);


        if ($strtotime_horaFinMinimo < $strtotime_horaSeparadorDia && ($horaFinMinimo!= "" && $horaFinMaximo != "")){
            return $horaFinMinimo;
        }

        if ($horaFinMediaNoche == "00:00:00"){
            return $horaFinMediaNoche;
        }

        return $horaFinMaximo;


    }



    static function esRepetidoTramoHorario($idPista, $diaSemana, $horaInicio, $horaFin, $fechaInico, $fechaFin){
        global $bd;

        /*
        $aparciones = $bd->count(self::NOMBRE_TABLA, array("AND" => array(
                self::COLUMNA_idPista => $idPista,
                self::COLUMNA_diaSemana => $diaSemana,
                self::COLUMNA_fechaInicio."[>=]" => $fechaInico,
                self::COLUMNA_fechaFin."[<=]" => $fechaFin
        )));
        */

        $aparciones = $bd->query("
                        SELECT * FROM ".self::NOMBRE_TABLA." 
                        WHERE ".self::COLUMNA_idPista."=$idPista AND ".self::COLUMNA_diaSemana."=$diaSemana
                        AND
                            (
                                ('$fechaInico' >= ".self::COLUMNA_fechaInicio." AND '$fechaInico' < ".self::COLUMNA_fechaFin.")
                                OR
                                ('$fechaFin' > ".self::COLUMNA_fechaInicio." AND '$fechaFin' < ".self::COLUMNA_fechaFin.")
                                OR
                                ('$fechaInico' <= ".self::COLUMNA_fechaInicio." AND '$fechaFin' > ".self::COLUMNA_fechaFin.")
                            )
                        AND
                            (
                                ('$horaInicio' >= ".self::COLUMNA_horaInicio." AND '$horaInicio' < ".self::COLUMNA_horaFin.")
                                OR
                                ('$horaFin' > ".self::COLUMNA_horaInicio." AND '$horaFin' < ".self::COLUMNA_horaFin.")
                                OR
                                ('$horaInicio' <= ".self::COLUMNA_horaInicio." AND '$horaFin' > ".self::COLUMNA_horaFin.")
                            ) 
            ")->fetchAll();


        if (count($aparciones)){
            return true;
        }

        return false;
    }

    static function imprimirTodosRegistros($idPista, $diaSemana){

        $Pista = new Pista($idPista);
        if ($Pista->existeAlgunHorarioPistaParaLaPistaYDiaSemana($diaSemana) == false){
            return;
        }

        $array_horarioPista = self::obtenerTodos($idPista,$diaSemana);

        foreach ($array_horarioPista as $HorarioPista) {
            $HorarioPista->imprimirFilaRegistro();
        }
    }

    function duplicar($idPista, $diaSemana){
        global $bd;
        $HorarioPista = new HorarioPista($this["id"]);

        $HorarioPistaDuplicar = $HorarioPista;
        $HorarioPistaDuplicar["id"] = "";
        $HorarioPistaDuplicar["idPista"] = $idPista;
        $HorarioPistaDuplicar["diaSemana"] = $diaSemana;

        $idHorarioDuplicado = $HorarioPistaDuplicar->guardar();


        //Cogemos los tiempos_reserva ids que hay que clonar
        //Tiempo de ejecución de un proceso

        /*****************************
        PARTE NUEVA
        *******************************/
        $callStartTime = microtime(true);

        $array_ids = $bd->select('tiemposReserva_horario_pista', "id", array("idHorario" => $this["id"], "ORDER" => "id DESC"));
        $sql_condicion="";
        $i=0;
        foreach ($array_ids as $key => $value) {
            if($i>0){
                $sql_condicion.=" || ";
            }
            $i++;
            $sql_condicion.="id=".$value;
        }
        // var_dump($sql_condicion);

        // var_dump($array_ids);
        if($sql_condicion!=""){
            $sql_insert="
            INSERT INTO tiemposReserva_horario_pista 
            (idHorario,predeterminado,tiempoReserva,precioGeneral,precioRadical,precioSocios,precioGrupo1,precioGrupo2,precioMonedero,precioSegundoMonedero,iluminacionIncluida)
            SELECT ".$idHorarioDuplicado.",predeterminado,tiempoReserva,precioGeneral,precioRadical,precioSocios,precioGrupo1,precioGrupo2,precioMonedero,precioSegundoMonedero,iluminacionIncluida 
            FROM tiemposReserva_horario_pista t2
            WHERE ".$sql_condicion;

            $bd->query($sql_insert);
            $callEndTime = microtime(true);
            $callTime = $callEndTime - $callStartTime;
            $tiempo='El tiempo new es de:  '.sprintf("%.4f",$callTime).' seconds';
            // echo  $tiempo;
        }



        /*****************************
        PARTE ANTIGUA
        *******************************/
        //Tiempo de ejecución de un proceso
        // $callStartTime = microtime(true);
        // $array_TiemposReserva = $this->obtenerTiemposReserva();


        // $totalTiemposReserva = count($array_TiemposReserva);

        // echo "HORARIO PISTA:".$this["id"]."->".$idHorarioDuplicado." TIEMPOS RESERVA:".$totalTiemposReserva;

        // foreach ($array_TiemposReserva as $TiempoReserva){
        //     $TiempoReserva->duplicar($idHorarioDuplicado);


        // }
        // $callEndTime = microtime(true);
        // $callTime = $callEndTime - $callStartTime;
        // $tiempo='El tiempo actual es de:  '.sprintf("%.4f",$callTime).' seconds';
        // echo  $tiempo;

        // var_dump($sql_insert);
        // die();

        // CacheTablaReserva::eliminarCacheRedisIdPistaYFecha($idPista);

    }

    function eliminarTodosLosTiemposReserva(){
        TiempoReserva::eliminarTodos($this["id"]);
        CacheTablaReserva::eliminarCacheRedisIdPistaYFecha($this->obtenerPista()->obtenerId());

    }

    function tieneTiemposReservaDisponibles($hora){

        if (count($this->obtenerTiemposReservaDisponibles($hora, true)) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function obtenerTiemposReservaDisponibles($hora, $devolverPrimeroSolo=false){
        return TiempoReserva::obtenerTiemposReservaDisponibles($this["id"], $hora, $devolverPrimeroSolo);
    }
     function obtenerId(){
        return $this["id"];
     }

    function obtenerInformacionHorario(){

        $diaSemana_texto = $this->obtenerDiaSemana(true);
        $pista_texto = $this->obtenerPista()->obtenerNombre();
        $horaInicio = $this->obtenerHoraInicio();
        $horaFin = $this->obtenerHoraFin();


        return $diaSemana_texto." - ".$pista_texto." - De $horaInicio a $horaFin";



    }

    function obtenerDiaSemana($texto = false){

        if ($texto == true){

            switch ($this["diaSemana"]){

                case self::DIASEMANA_LUNES:
                    return "Lunes";
                    break;

                case self::DIASEMANA_MARTES:
                    return "Mares";
                    break;

                case self::DIASEMANA_MIERCOLES:
                    return "Miércoles";
                    break;

                case self::DIASEMANA_JUEVES:
                    return "Jueves";
                    break;

                case self::DIASEMANA_VIERNES:
                    return "Viernes";
                    break;

                case self::DIASEMANA_SABADO:
                    return "Sábado";
                    break;

                case self::DIASEMANA_DOMINGO:
                    return "Domingo";
                    break;
            }
        }
        else{
            return $this["diaSemana"];
        }
    }

    function obtenerPista(){

        return new Pista($this["idPista"]);
    }


    function obtenerHoraInicio(){
        $array_horaInicio = explode(":", $this[self::COLUMNA_horaInicio]);
        return $array_horaInicio[0].":".$array_horaInicio[1];
    }

    function obtenerHoraFin($strtotime=false){

        $horaFin = $this[self::COLUMNA_horaFin];

        if ($strtotime){
            if ($horaFin == "00:00"){
                return strtotime("23:59")+60;
            }

            return strtotime($horaFin);
        }


        $array_horaFin = explode(":", $horaFin);
        return $array_horaFin[0].":".$array_horaFin[1];
    }

    function obtenerFechaInicio(){
        $fechaInicio =  $this[self::COLUMNA_fechaInicio];

        /*
        if ($fechaInicio == "0000-00-00"){
            return "2000-01-01";
        }
        */

        return $fechaInicio;
    }

    function obtenerFechaFin(){
        $fechaFin = $this[self::COLUMNA_fechaFin];


        if ($fechaFin == "0000-00-00"){
            return "3000-01-01";
        }


        return $fechaFin;
    }

    function esFechaEnRangoHorarioPista($fechaMYSQL){

        /*
        echo "<br/>ID HORARIO: ".$this->obtenerId();
        echo "<br/>Fecha MYSQL: ".$fechaMYSQL;
        echo "<br/>Fecha Inicio: ".$this->obtenerFechaInicio();
        echo "<br/>Fecha Fin: ".$this->obtenerFechaFin();
        */

        $strtotime_fechaInicio = strtotime($this->obtenerFechaInicio());
        $strtotime_fechaFin = strtotime($this->obtenerFechaFin());
        $strtotime_fechaMYSQL = strtotime($fechaMYSQL);

        /*
        echo "<br/>Fecha MYSQL: ".$strtotime_fechaMYSQL;
        echo "<br/>Fecha Inicio: ".$strtotime_fechaInicio;
        echo "<br/>Fecha Fin: ".$strtotime_fechaFin;
        */

        if ($strtotime_fechaMYSQL >= $strtotime_fechaInicio && $strtotime_fechaMYSQL <= $strtotime_fechaFin){
            return true;
        }

        return false;
    }

    function obtenerTiemposReserva(){

        return TiempoReserva::obtenerTodos($this["id"]);
    }

    function imprimirFilaRegistro(){

        $fechaHoy = date('Y-m-d');
        $array_tiemposReserva = TiempoReserva::obtenerTodos($this["id"]);

        $numeroRegistro_tiemposReserva = count($array_tiemposReserva);
        $exiteTiempoReservaPredeterminado = TiempoReserva::exiteTiempoReservaPredeterminado($this["id"]);

        $id = $this->obtenerId();
        $idPista = $this->obtenerPista()->obtenerId();
        $diaSemana = $this->obtenerDiaSemana();
        $informacionHorario = $this->obtenerInformacionHorario();
        $horaInicio = $this->obtenerHoraInicio();
        $horaFin = $this->obtenerHoraFin();
        $fechaInicio = $this->obtenerFechaInicio();
        $fechaFin = $this->obtenerFechaFin();

        $onchange_horarioPista = "onchange_actualizarFilaHorarioPista(".$this["id"].");";
        ?>

        <tr id="fila_idHorario_<?php echo $this->obtenerId();?>">
            <th rowspan="<?php echo $numeroRegistro_tiemposReserva;?>" scope="row">
                <button type="button" class="btn btn-danger" onclick="onclick_eliminarHorarioCompleto(<?php echo $this["id"];?>)">X</button>
            </th>
            <input type="hidden" id="horarioPista_idPista_<?php echo $id;?>" value="<?php echo $idPista;?>"/>
            <input type="hidden" id="horarioPista_diaSemana_<?php echo $id;?>" value="<?php echo $diaSemana;?>"/>
            <td rowspan="<?php echo $numeroRegistro_tiemposReserva;?>">
                <div class="row">
                    <div class="form-group col-md-6">
                        <input type="time" id="horarioPista_horaInicio_<?php echo $id;?>" class="form-control" value="<?php echo $horaInicio;?>" onchange="<?php echo $onchange_horarioPista;?>" placeholder="--:--" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"/>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="time" id="horarioPista_horaFin_<?php echo $id;?>" class="form-control"  value="<?php echo $horaFin;?>" onchange="<?php echo $onchange_horarioPista;?>" placeholder="--:--" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label><?php echo Traductor::traducir("Fecha Inicio");?></label>
                        <input type="date" id="horarioPista_fechaInicio_<?php echo $id;?>" class="form-control" value="<?php echo $fechaInicio;?>" min="<?php echo $fechaHoy;?>" onchange="<?php echo $onchange_horarioPista;?> onchange_fechaInicio(this.value, <?php echo $id;?>)" placeholder="dd/mm/yyyy" pattern="(^(((0[1-9]|1[0-9]|2[0-8])[\/](0[1-9]|1[012]))|((29|30|31)[\/](0[13578]|1[02]))|((29|30)[\/](0[4,6,9]|11)))[\/](19|[2-9][0-9])\d\d$)|(^29[\/]02[\/](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)"/>
                    </div>
                    <div class="form-group col-md-6">
                        <label><?php echo Traductor::traducir("Fecha Fin");?></label>
                        <input type="date" id="horarioPista_fechaFin_<?php echo $id;?>" class="form-control" value="<?php echo $fechaFin;?>" min="<?php echo $fechaHoy;?>" onchange="<?php echo $onchange_horarioPista;?> onchange_fechaFin(this.value, <?php echo $id;?>)" placeholder="dd/mm/yyyy" pattern="(^(((0[1-9]|1[0-9]|2[0-8])[\/](0[1-9]|1[012]))|((29|30|31)[\/](0[13578]|1[02]))|((29|30)[\/](0[4,6,9]|11)))[\/](19|[2-9][0-9])\d\d$)|(^29[\/]02[\/](19|[2-9][0-9])(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)$)"/>
                    </div>
                </div>
                <div class="container">
                    <button type="button" class="boton_anadirHorario btn btn-outline-primary btn-sm btn-block" onclick="abrirModalAnadirTiempoReserva(<?php echo $this["id"]?>)"><?php echo Traductor::traducir("Añadir Periodo");?></button>
                    <input id="informacionHorario_<?php echo $this["id"]?>" type="hidden" value="<?php echo $informacionHorario; ?>">
                </div>
            </td>
            <?php
            if ($numeroRegistro_tiemposReserva > 0 || $exiteTiempoReservaPredeterminado == false || $numeroRegistro_tiemposReserva == 1){
                //JMAM: Imprime el Tiempo de Reserva por Defecto
                $TiempoReserva = $array_tiemposReserva[0];
                echo $TiempoReserva->imprimirFilaRegistro();
            }

            ?>
        </tr>

        <?php

        //JMAM: Elima el Tiempo de Reserva por Defecto, para no volverlo a imprimir
        unset($array_tiemposReserva[0]);

        //JMAM Imprime las demás opciones de Tiempo de Reserva
        foreach ($array_tiemposReserva as $TiempoReserva){
            echo $TiempoReserva->imprimirFilaRegistro();
        }


    }

    function guardar($valor = null, $nombreTablaClonar = "")
    {
        CacheTablaReserva::eliminarCacheRedisIdPista($this->obtenerPista()->obtenerId());
        return parent::guardar($valor, $nombreTablaClonar); // TODO: Change the autogenerated stub
    }

    function eliminar($id = null)
    {
        parent::eliminar($id); // TODO: Change the autogenerated stub
        HoraPistaBloqueada::eliminarHorariosPistasBloqueadaQueNoTenganNingunHorarioPista($this->obtenerPista()->obtenerId(), $this->obtenerDiaSemana());

    }


}