<?php
class ReservaPista extends general implements ReservaPistaInterface
{
    const TABLA_nombre = "reservas_pista";
    const COLUMNA_id = "id";
    const COLUMNA_idGrupoReserva = "idGrupoReserva";
    const COLUMNA_idCampo  = "idCampo";
    const COLUMNA_idPartido  = "idPartido";
    const COLUMNA_idPista  = "idPista";
    const COLUMNA_idTipoReserva = "idTipoReserva";
    const COLUMNA_descripcion = "descripcion";
    const COLUMNA_idJugadorReserva = "idJugadorReserva";
    const COLUMNA_idTiempoReserva = "idTiempoReserva";
    const COLUMNA_tipoJugadorReserva = "tipoJugadorReserva";
    const COLUMNA_idJugador1 = "idJugador1";
    const COLUMNA_idJugador2 = "idJugador2";
    const COLUMNA_idJugador3 = "idJugador3";
    const COLUMNA_idJugador4 = "idJugador4";
    const COLUMNA_tipoJugador1 = "tipoJugador1";
    const COLUMNA_tipoJugador2 = "tipoJugador2";
    const COLUMNA_tipoJugador3 = "tipoJugador3";
    const COLUMNA_tipoJugador4 = "tipoJugador4";
    const COLUMNA_grupoJugador1 = "grupoJugador1";
    const COLUMNA_grupoJugador2 = "grupoJugador2";
    const COLUMNA_grupoJugador3 = "grupoJugador3";
    const COLUMNA_grupoJugador4 = "grupoJugador4";
    const COLUMNA_tipoPagoJugadorReserva = "tipoPagoJugadorReserva";
    const COLUMNA_numeroJugadoresMaximoPermitidos = "numeroJugadores";
    const COLUMNA_repartirImporte = "repartirImporte";
    const COLUMNA_partidoCompleto = "partidoCompleto";
    const COLUMNA_partidoPublico = "partidoPublico";
    const COLUMNA_fechaReserva = "fechaReserva";
    const COLUMNA_horaInicioReserva = "horaInicioReserva";
    const COLUMNA_horaFinReserva = "horaFinReserva";
    const COLUMNA_esReservaRealizadaPorClub = "reservaRealizadaPorClub";
    const COLUMNA_esReservaModificadaPorClub = "reservaModificadaPorClub";
    const COLUMNA_importeReserva = "importeReserva";
    const COLUMNA_fechaPagoReserva = "fechaPagoReserva";
    const COLUMNA_importePagoJugador1 = "importePagoJugador1";
    const COLUMNA_IMPORTEPAGOJUGADOR1 = "importePagoJugador1";
    const COLUMNA_importePagoJugador2 = "importePagoJugador2";
    const COLUMNA_importePagoJugador3 = "importePagoJugador3";
    const COLUMNA_importePagoJugador4 = "importePagoJugador4";
    const COLUMNA_tipoPagoJugador1 = "tipoPagoJugador1";
    const COLUMNA_tipoPagoJugador2 = "tipoPagoJugador2";
    const COLUMNA_tipoPagoJugador3 = "tipoPagoJugador3";
    const COLUMNA_tipoPagoJugador4 = "tipoPagoJugador4";
    const COLUMNA_pagadoJugador1 = "pagadoJugador1";
    const COLUMNA_pagadoJugador2 = "pagadoJugador2";
    const COLUMNA_pagadoJugador3 = "pagadoJugador3";
    const COLUMNA_pagadoJugador4 = "pagadoJugador4";
    const COLUMNA_aplazadoPagoJugador1 = "aplazadoPagoJugador1";
    const COLUMNA_aplazadoPagoJugador2 = "aplazadoPagoJugador2";
    const COLUMNA_aplazadoPagoJugador3 = "aplazadoPagoJugador3";
    const COLUMNA_aplazadoPagoJugador4 = "aplazadoPagoJugador4";
    const COLUMNA_fechaPagoJugador1 = "fechaPagoJugador1";
    const COLUMNA_fechaPagoJugador2 = "fechaPagoJugador2";
    const COLUMNA_fechaPagoJugador3 = "fechaPagoJugador3";
    const COLUMNA_fechaPagoJugador4 = "fechaPagoJugador4";
    const COLUMNA_descuento = "descuento";
    const COLUMNA_precioGeneral = "precioGeneral";
    const COLUMNA_precioRadical = "precioRadical";
    const COLUMNA_precioSocios = "precioSocios";
    const COLUMNA_precioGrupo1 = "precioGrupo1";
    const COLUMNA_precioGrupo2 = "precioGrupo2";
    const COLUMNA_iluminacionIncluida = "iluminacionIncluida";
    const COLUMNA_precioMonedero = "precioMonedero";
    const COLUMNA_precioSegundoMonedero = "precioSegundoMonedero";
    const COLUMNA_numeroPedido = "numeroPedido";
    const COLUMNA_fechaUltimaModificacion = "fecha";

    const TIPOPAGOJUGADORRESERVA_ENPISTA = "PISTA";
    const TIPOPAGOJUGADORRESERVA_MONEDERO = "MONEDERO";
    const TIPOPAGOJUGADORRESERVA_TPV = "TPV";
    const TIPOPAGOJUGADORRESERVA_GRATIS = "GRATIS";
    const TIPOPAGOJUGADORRESERVA_JUGADORORGANIZADOR = "JUGADOR_ORGANIZADOR";

    const ESPAGADOJUGADOR_si = 1;
    const ESPAGADOJUGADOR_no = 0;

    const TIPO_PAGO_MONEDERO_APLAZADO_NO = 0;
    const TIPO_PAGO_MONEDERO_APLAZADO_SI = 1;

    const REGISTRARPAGO_JUGADORRESERVA = "JUGADOR_RESERVA";
    const REGISTRARPAGO_JUGADOR2 = "JUGADOR2";
    const REGISTRARPAGO_JUGADOR3 = "JUGADOR3";
    const REGISTRARPAGO_JUGADOR4 = "JUGADOR4";

    const TIPOJUGADOR_EXTERNO = "externo";
    const TIPOJUGADOR_INTERNO = "interno";


    const OP_imprimirTablaJugadoresModalReservaPista = "imprimirTablaJugadoresModalReservaPista";
    const OP_imprimirTablaReservasPistas = "imprimirTablaReservasPistas";


    function __construct($id="", $tablaNombre = self::TABLA_nombre, $columnaId = self::COLUMNA_id)
    {
        if ($id != '')
            parent::__construct($tablaNombre, $columnaId, $id);
        else
            parent::__construct($tablaNombre, '', '');
    }

    function reordenarJugadores($actualizarCacheRedisTablaReserva=true){
        $this->obtenerPartido()->reordenarJugadores();
        if ($actualizarCacheRedisTablaReserva){
            ReservaPista::eliminarCacheRedisTablaReservaParaElCampoYFecha($this->obtenerCampo()->obtenerId(), $this->obtenerFechaReserva(), $this->obtenerDeporte()->obtenerId());
        }

    }

    static function obtenerTodos($idPista = ""){
        global $bd;

        if ($idPista == ""){
            $ids = $bd->select(self::TABLA_nombre, "id", array("ORDER" => "id DESC"));
        }
        else{
            $ids = $bd->select(self::TABLA_nombre, "id", array("idPista" => $idPista), array("ORDER" => "id DESC"));
        }

        $array = [];
        foreach ($ids as $id) {
            $array[] = new ReservaPista($id);
        }
        return $array;
    }

    static function obtenerIds(){
        global $bd;

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id);
    }

    static function obtenerIdsReservasPistaDesdeHoyEnAdelante($limit=0){
        global $bd;

        $where["AND"][self::COLUMNA_fechaReserva."[>=]"] = date("Y-m-d");
        if ($limit > 0){
            $where["LIMIT"] = $limit;
        }

        $where["ORDER"] = self::COLUMNA_fechaReserva." ASC";

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, $where);
    }

    static function obtenerIdsReservasPistaConPagosIncorrectosMonederoDesdeHoyEnAdelante($limit=0){
        $array_idsReservasPista = self::obtenerIdsReservasPistaDesdeHoyEnAdelante($limit);

        $array_idsReservasPistaConPagosIncorrectos = array();
        foreach ($array_idsReservasPista as $idReservaPista){
            $ReservaPista = new ReservaPista($idReservaPista);

            if (!$ReservaPista->esCorrectoPagosMonedero()){
                $array_idsReservasPistaConPagosIncorrectos[] = $idReservaPista;
            }
        }

        return $array_idsReservasPistaConPagosIncorrectos;
    }

    static function obtenerIdsReservasPagoJugador($idJugador, $tipoPagoJugadorReserva, $fechaInicio="", $fechaFin="", $anos="", $idClub=-1, $idLiga=-1, $idCampo=-1, $idPista=-1, $esPagado=-1, $esSuperiorACero=-1){
        global $bd;

        Log::v(__FUNCTION__,"$idJugador, $tipoPagoJugadorReserva, $fechaInicio, $fechaFin, $anos, $idClub, $idLiga, ID CAMPO: ($idCampo), $idPista, $esPagado, $esSuperiorACero", false);


        $whereFechaInicio = "";
        if (!empty($fechaInicio)){
            $whereFechaInicio =  "AND ".self::COLUMNA_fechaReserva." >= '$fechaInicio'";
        }

        $whereFechaFin = "";
        if (!empty($fechaFin)){
            $whereFechaFin =  "AND ".self::COLUMNA_fechaReserva." <= '$fechaFin'";
        }

        $whereAnos = "";
        if (!empty($anos)){
            $whereAnos =  "AND ".self::COLUMNA_fechaReserva." LIKE '$anos%'";
        }

        $whereIdClub = "";
        if ($idClub != -1){
            $whereIdClub =  self::COLUMNA_idPista." IN (SELECT ".Pista::COLUMNA_id." FROM ".Pista::NOMBRE_TABLA." WHERE ".self::COLUMNA_idCampo." IN (SELECT ".Pista::COLUMNA_id." FROM ".Campo::TABLA_nombre." WHERE ".Campo::COLUMNA_idClub." = $idClub))";
        }

        $whereIdLiga = "";
        if ($idLiga != -1){
            $whereIdLiga =  "AND ".self::COLUMNA_idPartido." IN (SELECT ".Pista::COLUMNA_id." FROM ".Partido::TABLA_NOMBRE." WHERE ".Partido::COLUMNA_idLiga." = $idLiga)";
        }


        $whereIdCampo = "";
        if ($idCampo != -1){
            $whereIdCampo =  "AND ".self::COLUMNA_idPista." IN (SELECT ".Pista::COLUMNA_id." FROM ".Pista::NOMBRE_TABLA." WHERE ".Pista::COLUMNA_idCampo." = $idCampo)";
        }

        $whereIdPista = "";
        if ($idPista != -1){
            $whereIdPista =  "AND ".self::COLUMNA_idPista." = $idPista";
        }



        $whereEsPagadoJugador = "";



        $whereEsSuperiorACeroJugador1 = "";
        $whereEsSuperiorACeroJugador2 = "";
        $whereEsSuperiorACeroJugador3 = "";
        $whereEsSuperiorACeroJugador4 = "";

        if ($esSuperiorACero != ""){

            $valor = "";
            switch ($esSuperiorACero){

                case 0:
                    $valor = "=0";
                    break;
                case 1:
                    $valor = " > 0";
                    break;
            }

            if (!empty($valor)){
                $whereImportePagoEsSuperiorACero  = "AND ".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_importePago.$valor.")";
                /*
                $whereEsSuperiorACeroJugador1 =  "AND ".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_numeroJugador."= 1 AND ".PartidoJugador::COLUMNA_importePago.$valor.")";
                $whereEsSuperiorACeroJugador2 =  "AND ".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_numeroJugador."= 2 AND ".PartidoJugador::COLUMNA_importePago.$valor.")";
                $whereEsSuperiorACeroJugador3 =  "AND ".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_numeroJugador."= 3 AND ".PartidoJugador::COLUMNA_importePago.$valor.")";
                $whereEsSuperiorACeroJugador4 =  "AND ".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_numeroJugador."= 4 AND ".PartidoJugador::COLUMNA_importePago.$valor.")";
                */
            }

        }

        switch ($tipoPagoJugadorReserva){
            case self::TIPOPAGOJUGADORRESERVA_ENPISTA:
                $whereTipoPagoJugadorReserva  = "(".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_tipoPagoJugador." = '".self::TIPOPAGOJUGADORRESERVA_ENPISTA."' OR ".PartidoJugador::COLUMNA_tipoPagoJugador." = ''))";
                /*
                $whereTipoPagoJugadorReserva1 = "(".self::COLUMNA_tipoPagoJugador1."='".self::TIPOPAGOJUGADORRESERVA_ENPISTA."' OR ".self::COLUMNA_tipoPagoJugador1."='')";
                $whereTipoPagoJugadorReserva2 = "(".self::COLUMNA_tipoPagoJugador2."='".self::TIPOPAGOJUGADORRESERVA_ENPISTA."' OR ".self::COLUMNA_tipoPagoJugador2."='')";
                $whereTipoPagoJugadorReserva3 = "(".self::COLUMNA_tipoPagoJugador3."='".self::TIPOPAGOJUGADORRESERVA_ENPISTA."' OR ".self::COLUMNA_tipoPagoJugador3."='')";
                $whereTipoPagoJugadorReserva4 = "(".self::COLUMNA_tipoPagoJugador4."='".self::TIPOPAGOJUGADORRESERVA_ENPISTA."' OR ".self::COLUMNA_tipoPagoJugador4."='')";
                */
                break;

            case self::TIPOPAGOJUGADORRESERVA_MONEDERO:
                $whereTipoPagoJugadorReserva  = "(".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_tipoPagoJugador." = '".self::TIPOPAGOJUGADORRESERVA_MONEDERO."'))";
                /*
                $whereTipoPagoJugadorReserva1 = self::COLUMNA_tipoPagoJugador1."='".self::TIPOPAGOJUGADORRESERVA_MONEDERO."'";
                $whereTipoPagoJugadorReserva2 = self::COLUMNA_tipoPagoJugador2."='".self::TIPOPAGOJUGADORRESERVA_MONEDERO."'";
                $whereTipoPagoJugadorReserva3 = self::COLUMNA_tipoPagoJugador3."='".self::TIPOPAGOJUGADORRESERVA_MONEDERO."'";
                $whereTipoPagoJugadorReserva4 = self::COLUMNA_tipoPagoJugador4."='".self::TIPOPAGOJUGADORRESERVA_MONEDERO."'";
                */
                break;

            default:
                $whereTipoPagoJugadorReserva = "(1)";
                /*
                $whereTipoPagoJugadorReserva1 = "(1)";
                $whereTipoPagoJugadorReserva2 = "(1)";
                $whereTipoPagoJugadorReserva3 = "(1)";
                $whereTipoPagoJugadorReserva4 = "(1)";
                */
                break;
        }


        $where = "WHERE";
        $where .= " $whereIdClub $whereFechaInicio $whereFechaFin $whereAnos  $whereIdLiga $whereIdCampo $whereIdPista AND";

        if ($idJugador > 0){

            if ($esPagado != -1){
                $whereEsPagadoJugador  = "AND ".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_esPagadoJugador." = $esPagado)";
                /*
                $whereEsPagadoJugador1 =  "AND ".self::COLUMNA_pagadoJugador1." = $esPagado";
                $whereEsPagadoJugador2 =  "AND ".self::COLUMNA_pagadoJugador2." = $esPagado";
                $whereEsPagadoJugador3 =  "AND ".self::COLUMNA_pagadoJugador3." = $esPagado";
                $whereEsPagadoJugador4 =  "AND ".self::COLUMNA_pagadoJugador4." = $esPagado";
                */

            }

            $where .= " (($whereTipoPagoJugadorReserva $whereEsPagadoJugador $whereImportePagoEsSuperiorACero))";

            /*
            $where .= " ((".self::COLUMNA_idJugador1."='$idJugador' AND $whereTipoPagoJugadorReserva1 $whereEsPagadoJugador1 $whereEsSuperiorACeroJugador1)";
            $where .= " OR (".self::COLUMNA_idJugador2."='$idJugador' AND $whereTipoPagoJugadorReserva2 $whereEsPagadoJugador2 $whereEsSuperiorACeroJugador2)";
            $where .= " OR (".self::COLUMNA_idJugador3."='$idJugador' AND $whereTipoPagoJugadorReserva3 $whereEsPagadoJugador3 $whereEsSuperiorACeroJugador3)";
            $where .= " OR (".self::COLUMNA_idJugador4."='$idJugador' AND $whereTipoPagoJugadorReserva4 $whereEsPagadoJugador4 $whereEsSuperiorACeroJugador4))";
            */
        }
        else{
            $where .= " (($whereTipoPagoJugadorReserva $whereImportePagoEsSuperiorACero))";
            /*
            $where .= " (($whereTipoPagoJugadorReserva1 $whereEsSuperiorACeroJugador1)";
            $where .= " OR ($whereTipoPagoJugadorReserva2 $whereEsSuperiorACeroJugador2)";
            $where .= " OR ($whereTipoPagoJugadorReserva3 $whereEsSuperiorACeroJugador3)";
            $where .= " OR ($whereTipoPagoJugadorReserva4 $whereEsSuperiorACeroJugador4))";
            */

            if ($esPagado != -1) {
                $where  .= " AND ".self::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idReservaPista." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_esPagadoJugador." = $esPagado)";

                /*
                switch ($esPagado) {

                    case true:
                        $where .= " AND (" . self::COLUMNA_pagadoJugador1 . " = $esPagado OR " . self::COLUMNA_pagadoJugador2 . " = $esPagado OR " . self::COLUMNA_pagadoJugador3 . " = $esPagado OR " . self::COLUMNA_pagadoJugador4 . " = $esPagado)";
                        break;

                    case false:
                        $where .= " AND ((" .self::COLUMNA_idJugador1."!='' AND ". self::COLUMNA_pagadoJugador1 . " = $esPagado) OR (" .self::COLUMNA_idJugador2."!='' AND ". self::COLUMNA_pagadoJugador2 . " = $esPagado) OR (" .self::COLUMNA_idJugador3."!='' AND ". self::COLUMNA_pagadoJugador3 . " = $esPagado) OR (" .self::COLUMNA_idJugador4."!='' AND ". self::COLUMNA_pagadoJugador4 . " = $esPagado))";
                        break;
                }
                */
            }
        }


        $where .= " AND ".self::COLUMNA_idTipoReserva." != '".TipoReserva::ID_TIPORESERVA_BLOQUEO."'  AND ".self::COLUMNA_idTipoReserva." != '".TipoReserva::ID_TIPORESERVA_DESBLOQUEO."' AND ".self::COLUMNA_idTipoReserva." != '".TipoReserva::ID_TIPORESERVA_ESCUELA."' ORDER BY ".self::COLUMNA_fechaReserva." ASC, ".self::COLUMNA_idPista." ASC, ".self::COLUMNA_horaInicioReserva." ASC";

        Log::v(__FUNCTION__, $where, false);
        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, $where);
    }

    static function imprimirTablaPagosReservas($idJugador, $tipoPagoJugadorReserva, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, $esPagado="", $esSuperiorACero=""){

        Log::v(__FUNCTION__, "$idJugador, $tipoPagoJugadorReserva, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, ID CAMPO: ($idCampo), $idPista, $esPagado, $esSuperiorACero", false);


        $Club = new Club($idClub);
        $arrayIdsReservasPista = ReservaPista::obtenerIdsReservasPagoJugador($idJugador, -1, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, $esPagado, $esSuperiorACero);


        $contador = 0;
        foreach ($arrayIdsReservasPista as $idReservaPista){
            $Jugador = new Jugador($idJugador);
            $nombreJugador = $Jugador->obtenerNombreRepresentativo(true, true);
            $ReservaPista = new ReservaPista($idReservaPista);
            $Liga = $ReservaPista->obtenerLiga();
            $nombreLiga = $Liga->obtenerNombre()." ".$Liga->obtenerEdicion();
            $nombrePista = $ReservaPista->obtenerPista()->obtenerNombre();
            $Campo = $ReservaPista->obtenerCampo();
            $idCampo = $ReservaPista->obtenerCampo()->obtenerId();
            $nombreCampo = $ReservaPista->obtenerCampo()->obtenerNombre();
            $Partido = $ReservaPista->obtenerPartido();
            $fechaPartidoMYSQL = $ReservaPista->obtenerFechaReserva();
            $fechaPartido = formatearFecha($fechaPartidoMYSQL, true);
            $horaPartido = $ReservaPista->obtenerHoraInicioReserva(true);
            $fechaYHoraPartido = $fechaPartido." ".$horaPartido;

            $importePagadoMonedero = $ReservaPista->obtenerSumaTotalImportesPagados(self::TIPOPAGOJUGADORRESERVA_MONEDERO, true, $idJugador);
            $importeNoPagadoMonedero = $ReservaPista->obtenerSumaTotalImportesPagados(self::TIPOPAGOJUGADORRESERVA_MONEDERO, false, $idJugador);

            $importePagadoPista = $ReservaPista->obtenerSumaTotalImportesPagados(self::TIPOPAGOJUGADORRESERVA_ENPISTA, true, $idJugador);
            $importeNoPagadoPista = $ReservaPista->obtenerSumaTotalImportesPagados(self::TIPOPAGOJUGADORRESERVA_ENPISTA, false, $idJugador);

            $importePagadoEnReserva = $importePagadoMonedero + $importePagadoPista;
            $importeNoPagadoEnReserva = $importeNoPagadoMonedero + $importeNoPagadoPista;


            $style_celdaPendienteMonedero = "";
            if ($importeNoPagadoMonedero > 0){
                $style_celdaPendienteMonedero = "color: red; font-weight:bolder";
            }

            $style_celdaPendienteEnPista = "";
            if ($importeNoPagadoPista > 0){
                $style_celdaPendienteEnPista = "color: red; font-weight:bolder";
            }

            $style_celdaNoPagadoEnReserva = "";
            if ($importeNoPagadoEnReserva > 0){
                $style_celdaNoPagadoEnReserva = "color: red; font-weight:bolder";
            }

            $style_cabeceraSeparador = "";
            if ($idJugador > 0){
                $style_cabeceraSeparador = "display:none";
            }


            $title_fila= Traductor::traducir("Pagado");
            //$class_fila = "table-success";
            //$class_fila = "";
            $style_fila = "";
            if ($importeNoPagadoEnReserva <= 0){
                $fechaPagoMysql = $ReservaPista->obtenerFechaPagoJugador($idJugador);
                if ($fechaPagoMysql == "0000-00-00 00:00:00"){
                    $fechaPagoJugador = Traductor::traducir("NO REGIST").".";
                }
                else{
                    $fechaPagoJugador = formatearFecha($fechaPagoMysql, true);
                }


            }
            else{
                $fechaPagoJugador = "S/N";
                //$class_fila = "table-danger";
                $style_fila = "color: red; font-weight:bolder";
                $title_fila= Traductor::traducir("Sin Pagar");
            }

            $class_monedero = "";
            if ($importeNoPagadoMonedero > 0){
                $class_monedero = "table-danger";
            }

            $class_enPista = "";
            if ($importeNoPagadoPista > 0){
                $class_enPista = "table-danger";
            }



            Log::v(__FUNCTION__, "Fecha Partido: $fechaPartidoMYSQL", false);
            if ($fechaAnteriorPartidoMYSQL != $fechaPartidoMYSQL && !empty($fechaPartidoMYSQL)){
                $fechaAnteriorPartidoMYSQL = $fechaPartidoMYSQL;



                $importePendienteSeccionMonedero = ReservaPista::obtenerImporteReservasTotalPagoJugador($idJugador, self::TIPOPAGOJUGADORRESERVA_MONEDERO, $fechaPartidoMYSQL, $fechaPartidoMYSQL, $ano, $idClub, $idLiga, $idCampo, $idPista, 0);
                $importePagadoSeccionMonedero = ReservaPista::obtenerImporteReservasTotalPagoJugador($idJugador, self::TIPOPAGOJUGADORRESERVA_MONEDERO, $fechaPartidoMYSQL, $fechaPartidoMYSQL, $ano, $idClub, $idLiga, $idCampo, $idPista, 1);

                $importePendienteSeccionEnPista = ReservaPista::obtenerImporteReservasTotalPagoJugador($idJugador, self::TIPOPAGOJUGADORRESERVA_ENPISTA, $fechaPartidoMYSQL, $fechaPartidoMYSQL, $ano, $idClub, $idLiga, $idCampo, $idPista, 0);
                $importePagadoSeccionEnPista = ReservaPista::obtenerImporteReservasTotalPagoJugador($idJugador, self::TIPOPAGOJUGADORRESERVA_ENPISTA, $fechaPartidoMYSQL, $fechaPartidoMYSQL, $ano, $idClub, $idLiga, $idCampo, $idPista, 1);

                $importeTotalNoPagadoEnReserva = $importePendienteSeccionMonedero + $importePendienteSeccionEnPista;
                $importeTotalPagadoEnReserva = $importePagadoSeccionMonedero + $importePagadoSeccionEnPista;






                echo " </tbody></table>";

                ?>
                <table class="table table-striped table-hover" style="margin-top: 15px">

                <thead>
                <tr style="<?php echo $style_cabeceraSeparador; ?>">
                    <td colspan="4" class="col-md-8"><div style="font-weight: bold; border-bottom: solid 2px black;"><?php echo $fechaPartido;?></td>
                    <td class="col-md-1" style="text-align: center;">
                        <div style='color: green; margin-left: -15px'>
                            <i class='fas fa-coins' title='<?php echo Traductor::traducir("Pagado Monedero");?>'></i>
                            <span><?php echo $importePagadoSeccionMonedero;?></span><?php echo Traductor::traducir('€', false, $Club->obtenerCodigoPais());?>
                        </div>
                    </td>
                    <td class="col-md-1" style="text-align: center;">
                        <div style='color: red;  margin-left: -15px'>
                            <i class='fas fa-coins' title='<?php echo Traductor::traducir("Pendiente Monedero");?>'></i>
                            <span><?php echo $importePendienteSeccionMonedero;?></span><?php echo Traductor::traducir('€', false, $Club->obtenerCodigoPais());?>
                        </div>
                    </td>
                    <td class="col-md-1" style="text-align: center;">
                        <div style='color: green;  margin-left: -15px'>
                            <i class='fas fa-coins' title='<?php echo Traductor::traducir("Pagado En Pista");?>'></i>
                            <span><?php echo $importePagadoSeccionEnPista;?></span><?php echo Traductor::traducir('€', false, $Club->obtenerCodigoPais());?>
                        </div>
                    </td>
                    <td class="col-md-1" style="text-align: center;">
                        <div style='color: red;  margin-left: -15px'>
                            <i class='fas fa-coins' title='<?php echo Traductor::traducir("Pendiente En Pista");?>'></i>
                            <span><?php echo $importePendienteSeccionEnPista;?></span><?php echo Traductor::traducir('€', false, $Club->obtenerCodigoPais());?>
                        </div>
                    </td>
                    <td class="col-md-1" style="text-align: center;">
                        <div style='color: green;  margin-left: -15px'>
                            <i class='fas fa-coins' title='<?php echo Traductor::traducir("Pagado En Reserva");?>'></i>
                            <span><?php echo $importeTotalPagadoEnReserva;?></span><?php echo Traductor::traducir('€', false, $Club->obtenerCodigoPais());?>
                        </div>
                    </td>
                    <td class="col-md-1" style="text-align: center;">
                        <div style='color: red;  margin-left: -15px'>
                            <i class='fas fa-coins' title='<?php echo Traductor::traducir("Pendiente En Reserva");?>'></i>
                            <span><?php echo $importeTotalNoPagadoEnReserva;?></span><?php echo Traductor::traducir('€', false, $Club->obtenerCodigoPais());?>
                        </div>
                    </td>
                </tr>
                </thead>

                <tbody>
                <?php
            }

            $texto_importePagadoMondero = "-";
            if ($importePagadoMonedero > 0){
                $texto_importePagadoMondero = $importePagadoMonedero.Traductor::traducir("€", false, $Campo->obtenerCodigoPais());
            }

            $texto_importeNoPagadoMondero = "-";
            if ($importeNoPagadoMonedero > 0){
                $texto_importeNoPagadoMondero = $importeNoPagadoMonedero.Traductor::traducir("€", false, $Campo->obtenerCodigoPais());
            }

            $texto_importePagadoEnPista = "-";
            if ($importePagadoPista > 0){
                $texto_importePagadoEnPista = $importePagadoPista.Traductor::traducir("€", false, $Campo->obtenerCodigoPais());
            }

            $texto_importeNoPagadoEnPista = "-";
            if ($importeNoPagadoPista > 0){
                $texto_importeNoPagadoEnPista = $importeNoPagadoPista.Traductor::traducir("€", false, $Campo->obtenerCodigoPais());
            }

            $texto_importePagadoEnReserva = "-";
            if ($importePagadoEnReserva > 0){
                $texto_importePagadoEnReserva = $importePagadoEnReserva.Traductor::traducir("€", false, $Campo->obtenerCodigoPais());
            }

            $texto_importeNoPagadoEnReserva = "-";
            if ($importeNoPagadoEnReserva > 0){
                $texto_importeNoPagadoEnReserva = $importeNoPagadoEnReserva.Traductor::traducir("€", false, $Campo->obtenerCodigoPais());
            }

            echo "
                    <tr class='$class_fila' title='$title_fila' style='cursor: pointer;' onclick='onclick_verReserva($idCampo, $idReservaPista, \"$fechaPartidoMYSQL\")'>";
            echo "
                            <td style='width: 13%; $style_fila'>$nombreLiga</td>
                            <td style='width: 11%; $style_fila'>$nombreCampo</td>
                            <td class='col-md-1' style='$style_fila'>$nombrePista</td>
                            <td class='col-md-2' style='$style_fila' title='ID RESERVA: ($idReservaPista)'>$fechaYHoraPartido</td>
                            <td class='col-md-1' style='text-align: center; border-left: solid 1px;'>$texto_importePagadoMondero</td>
                            <td class='col-md-1' class='$class_monedero' style='text-align: center; $style_celdaPendienteMonedero'>$texto_importeNoPagadoMondero</td>
                            <td class='col-md-1' style='text-align: center; border-left: solid 1px;'>$texto_importePagadoEnPista</td>
                            <td class='col-md-1' class='$class_enPista' style='text-align: center; $style_celdaPendienteEnPista'>$texto_importeNoPagadoEnPista</td>
                            <td class='col-md-1' style='text-align: center; border-left: solid 1px;'>$texto_importePagadoEnReserva</td>
                            <td class='col-md-1' class='$class_enPista' style='text-align: center; $style_celdaNoPagadoEnReserva'>$texto_importeNoPagadoEnReserva</td>
             
                    <tr>";


            $contador++;

        }
        ?>
        </tbody>
        </table>

        <?php
    }

    static function obtenerImporteReservasTotalPagoLiga($tipoPagoJugadorReserva, $idClub, $idLiga, $esPagado=""){
        $Liga = new Liga($idLiga);

        $array_Jugadores = $Liga->obtenerJugadores();
        $importePagoJugadorSumado = 0;
        foreach ($array_Jugadores as $Jugador){
            $importePagoJugadorSumado += self::obtenerImporteReservasTotalPagoJugador($Jugador->obtenerId(), $tipoPagoJugadorReserva, $fechaInicio="", $fechaFin="", $ano="", $idClub, $idLiga, $idCampo=-1, $idPista=-1, $esPagado);
        }

        return $importePagoJugadorSumado;
    }

    static function obtenerImporteReservasTotalPagoJugador($idJugador, $tipoPagoJugadorReserva, $fechaInicio, $fechaFin, $ano="", $idClub, $idLiga, $idCampo, $idPista, $esPagado=""){

        $array_idsReservasPagoJugador = self::obtenerIdsReservasPagoJugador($idJugador, $tipoPagoJugadorReserva, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, $esPagado);
        $importePagoJugadorSumado = 0;
        foreach ($array_idsReservasPagoJugador as $idReservaPista){
            $ReservaPista = new ReservaPista($idReservaPista);
            $importePagadoReserva = $ReservaPista->obtenerSumaTotalImportesPagados($tipoPagoJugadorReserva, $esPagado, $idJugador);

            Log::v(__FUNCTION__, $importePagadoReserva, false);
            $importePagoJugadorSumado +=  $importePagadoReserva;
        }

        Log::v(__FUNCTION__, "TOTAL IMPORTE [$tipoPagoJugadorReserva] ¿Pagado? [$esPagado]: $importePagadoReserva", false);

        return $importePagoJugadorSumado;
    }

    static function notificarAAdministradorReservasIncorrectas(){
        global $bd;

        $fechaHoy = date("Y-m-d");

        $ids = $bd->query("
                            SELECT ".self::COLUMNA_id." FROM ".self::TABLA_nombre." 
                            WHERE ".self::COLUMNA_fechaReserva." >= '$fechaHoy'
                            ORDER BY ".self::COLUMNA_fechaReserva." ASC"
        )->fetchAll();


        $mensajeEmail = "";
        $numeroDeReservasEncontradas = 0;
        foreach ($ids as $id) {
            $id = $id[self::COLUMNA_id];
            $ReservaPista = new ReservaPista($id);

            if (!$ReservaPista->comprobarIntegridadReserva(false, false, false)){
                $mensajeEmail.=$ReservaPista->comprobarIntegridadReserva(true, true, false);
                $numeroDeReservasEncontradas++;
            }
        }

        if ($mensajeEmail != ""){

            $asunto = "Se han encontrado $numeroDeReservasEncontradas Reservas Incorrectas";
            $mensaje = $asunto."<hr/><br/>$mensajeEmail";

            //Email::enviarEmail("desarrollo@narixasoft.es", $asunto, $mensaje);
            Email::enviarEmail(Email::EMAIL_ADMINISTRACION, $asunto, $mensaje);
            Email::enviarEmail("desarrollo@narixasoft.es", $asunto, $mensaje);
        }
    }

    static function realizarCobrosAJugadoresDeTodasLasReservasPistaAplazadosPagoConMonedero($fecha){
        global $bd;

        $where["AND"][self::COLUMNA_fechaReserva] = $fecha;
        $where["AND"]["OR"][self::COLUMNA_aplazadoPagoJugador1] = self::TIPO_PAGO_MONEDERO_APLAZADO_SI;
        $where["AND"]["OR"][self::COLUMNA_aplazadoPagoJugador2] = self::TIPO_PAGO_MONEDERO_APLAZADO_SI;
        $where["AND"]["OR"][self::COLUMNA_aplazadoPagoJugador3] = self::TIPO_PAGO_MONEDERO_APLAZADO_SI;
        $where["AND"]["OR"][self::COLUMNA_aplazadoPagoJugador4] = self::TIPO_PAGO_MONEDERO_APLAZADO_SI;


        $array_idsReservaPista = $bd->select(self::TABLA_nombre, self::COLUMNA_id, $where);

        foreach ($array_idsReservaPista as $idReservaPista){
            $ReservaPista = new ReservaPista($idReservaPista);

            Log::v(__FUNCTION__, "Reserva: ".$ReservaPista->obtenerNombreDescriptivo());

            if ($ReservaPista->esAplazadoPagoJugador1() && $ReservaPista->puedePagarConMonederoJugador1() && $ReservaPista->esPagadoJugador1() == false){
                Log::v(__FUNCTION__, "RESERVA: $idReservaPista |Jugador 1 con pago aplazado paga reserva con monedero",true);
                $ReservaPista->pagarDevolverImporteJugador($ReservaPista->obtenerJugador1()->obtenerId(), ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO, true);
            }
            else{
                Log::v(__FUNCTION__, "RESERVA: $idReservaPista |Jugador 1 con pago aplazado YA HA PAGADO",true);
            }

            if ($ReservaPista->esAplazadoPagoJugador2() && $ReservaPista->puedePagarConMonederoJugador2()  && $ReservaPista->esPagadoJugador2() == false){
                Log::v(__FUNCTION__, "RESERVA: $idReservaPista |Jugador 2 con pago aplazado paga reserva con monedero",true);
                $ReservaPista->pagarDevolverImporteJugador($ReservaPista->obtenerJugador2()->obtenerId(), ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO, true);
            }
            else{
                Log::v(__FUNCTION__, "RESERVA: $idReservaPista |Jugador 2 con pago aplazado YA HA PAGADO",true);
            }

            if ($ReservaPista->esAplazadoPagoJugador3() && $ReservaPista->puedePagarConMonederoJugador3()  && $ReservaPista->esPagadoJugador3() == false){
                Log::v(__FUNCTION__, "RESERVA: $idReservaPista |Jugador 3 con pago aplazado paga reserva con monedero",true);
                $ReservaPista->pagarDevolverImporteJugador($ReservaPista->obtenerJugador3()->obtenerId(), ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO, true);
            }
            else{
                Log::v(__FUNCTION__, "RESERVA: $idReservaPista |Jugador 3 con pago aplazado YA HA PAGADO",true);
            }

            if ($ReservaPista->esAplazadoPagoJugador4() && $ReservaPista->puedePagarConMonederoJugador4()  && $ReservaPista->esPagadoJugador4() == false){
                Log::v(__FUNCTION__, "RESERVA: $idReservaPista |Jugador 1 con pago aplazado paga reserva con monedero",true);
                $ReservaPista->pagarDevolverImporteJugador($ReservaPista->obtenerJugador4()->obtenerId(), ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO, true);
            }
            else{
                Log::v(__FUNCTION__, "RESERVA: $idReservaPista |Jugador 4 con pago aplazado YA HA PAGADO",true);
            }
        }
    }

    static function realizarModificarReservaPCU($id,
                                                $idGrupoReserva,
                                                $idPartido,
                                                $idPista,
                                                $idTiempoReserva,
                                                $idTipoReserva,
                                                $descripcion,
                                                $idJugador1,
                                                $idJugador2,
                                                $idJugador3,
                                                $idJugador4,
                                                $tipoJugador1,
                                                $tipoJugador2,
                                                $tipoJugador3,
                                                $tipoJugador4,
                                                $numeroJugadores,
                                                $repartirImporte,
                                                $partidoCompleto,
                                                $partidoPublico,
                                                $fechaReserva,
                                                $horaInicioReserva,
                                                $horaFinReserva,
                                                $importePagoJugador1,
                                                $importePagoJugador2,
                                                $importePagoJugador3,
                                                $importePagoJugador4,
                                                $valorPagadoJugador1,
                                                $valorPagadoJugador2,
                                                $valorPagadoJugador3,
                                                $valorPagadoJugador4,
                                                $descuento = 0,
                                                $actualizarCacheReserva=true
    ){

        if ($id > 0){
            //JMAM: Modificar
            $ReservaPista = new ReservaPista($id);
            $ReservaPista[self::COLUMNA_esReservaModificadaPorClub] = 1;

        }
        else{
            //JMAM: Añadir
            $ReservaPista = new ReservaPista();
            $ReservaPista[self::COLUMNA_idGrupoReserva] = $idGrupoReserva;
            $ReservaPista[self::COLUMNA_idTipoReserva] = $idTipoReserva;
            $ReservaPista[self::COLUMNA_esReservaRealizadaPorClub] = 1;
            $ReservaPista[self::COLUMNA_esReservaModificadaPorClub] = 1;
        }

        $Partido = new Partido($idPartido);

        $Jugador1 = new Jugador($idJugador1);
        $Jugador2 = new Jugador($idJugador2);
        $Jugador3 = new Jugador($idJugador3);
        $Jugador4 = new Jugador($idJugador4);

        $grupoJugador1 = $Jugador1->obtenerGrupoJugador($Partido->obtenerIdLiga())->obtenerId();
        $grupoJugador2 = $Jugador2->obtenerGrupoJugador($Partido->obtenerIdLiga())->obtenerId();
        $grupoJugador3 = $Jugador3->obtenerGrupoJugador($Partido->obtenerIdLiga())->obtenerId();
        $grupoJugador4 = $Jugador4->obtenerGrupoJugador($Partido->obtenerIdLiga())->obtenerId();

        $ReservaPista[self::COLUMNA_descripcion] = $descripcion;
        $ReservaPista[self::COLUMNA_idPartido] = $idPartido;
        $ReservaPista[self::COLUMNA_idPista] = $idPista;
        $ReservaPista[self::COLUMNA_idJugador1] = $idJugador1;
        $ReservaPista[self::COLUMNA_idJugador2] = $idJugador2;
        $ReservaPista[self::COLUMNA_idJugador3] = $idJugador3;
        $ReservaPista[self::COLUMNA_idJugador4] = $idJugador4;
        $ReservaPista[self::COLUMNA_tipoJugador1] = $tipoJugador1;
        $ReservaPista[self::COLUMNA_tipoJugador2] = $tipoJugador2;
        $ReservaPista[self::COLUMNA_tipoJugador3] = $tipoJugador3;
        $ReservaPista[self::COLUMNA_tipoJugador4] = $tipoJugador4;
        $ReservaPista[self::COLUMNA_grupoJugador1] = $grupoJugador1;
        $ReservaPista[self::COLUMNA_grupoJugador2] = $grupoJugador2;
        $ReservaPista[self::COLUMNA_grupoJugador3] = $grupoJugador3;
        $ReservaPista[self::COLUMNA_grupoJugador4] = $grupoJugador4;
        $ReservaPista[self::COLUMNA_numeroJugadoresMaximoPermitidos] = $numeroJugadores;
        $ReservaPista[self::COLUMNA_repartirImporte] = $repartirImporte;
        $ReservaPista[self::COLUMNA_partidoCompleto] = $partidoCompleto;
        $ReservaPista[self::COLUMNA_partidoPublico] = $partidoPublico;
        $ReservaPista[self::COLUMNA_fechaReserva] = $fechaReserva;
        $ReservaPista[self::COLUMNA_horaInicioReserva] = $horaInicioReserva;
        /*
        $ReservaPista[self::COLUMNA_IMPORTEPAGOJUGADOR1] = $importePagoJugador1;
        $ReservaPista[self::COLUMNA_IMPORTEPAGOJUGADOR2] = $importePagoJugador2;
        $ReservaPista[self::COLUMNA_IMPORTEPAGOJUGADOR3] = $importePagoJugador3;
        $ReservaPista[self::COLUMNA_IMPORTEPAGOJUGADOR4] = $importePagoJugador4;

        $ReservaPista[self::COLUMNA_PAGADOJUGADOR1] = $pagadoJugador1;
        $ReservaPista[self::COLUMNA_PAGADOJUGADOR2] = $pagadoJugador2;
        $ReservaPista[self::COLUMNA_PAGADOJUGADOR3] = $pagadoJugador3;
        $ReservaPista[self::COLUMNA_PAGADOJUGADOR4] = $pagadoJugador4;


        $ReservaPista[self::COLUMNA_aplazadoPagoJugador1] = $aplazadoPagoJugador1;
        $ReservaPista[self::COLUMNA_aplazadoPagoJugador2] = $aplazadoPagoJugador2;
        $ReservaPista[self::COLUMNA_aplazadoPagoJugador3] = $aplazadoPagoJugador3;
        $ReservaPista[self::COLUMNA_aplazadoPagoJugador4] = $aplazadoPagoJugador4;
        */

        $ReservaPista[self::COLUMNA_descuento] = $descuento;


        $ReservaPista[self::COLUMNA_idTiempoReserva] = $idTiempoReserva;
        if ($idTiempoReserva != -1){
            //JMAM: Guarda el Tiempo Reserva
            $TiempoReserva = new TiempoReserva($idTiempoReserva);
            $ReservaPista[self::COLUMNA_precioGeneral] = $TiempoReserva->obtenerPrecioGeneral();
            $ReservaPista[self::COLUMNA_precioRadical] = $TiempoReserva->obtenerPrecioRadical();
            $ReservaPista[self::COLUMNA_precioGrupo1] = $TiempoReserva->obtenerPrecioGrupo1();
            $ReservaPista[self::COLUMNA_precioGrupo2] = $TiempoReserva->obtenerPrecioGrupo2();
            $ReservaPista[self::COLUMNA_precioMonedero] = $TiempoReserva->obtenerPrecioMonedero();
            $ReservaPista[self::COLUMNA_precioSegundoMonedero] = $TiempoReserva->obtenerPrecioSegundoMonedero();
            $ReservaPista[self::COLUMNA_horaFinReserva] = $TiempoReserva->obtenerHoraFinTiempoReserva($horaInicioReserva);

        }
        else{
            $ReservaPista[self::COLUMNA_horaFinReserva] = $horaFinReserva;
        }

        //print_r($ReservaPista);

        $idReservaPistaGuardado = $ReservaPista->guardar();
        if ($id > 0){
            $idReservaPista = $id;
        }
        else{
            $idReservaPista = $idReservaPistaGuardado;
        }




        //JMAM: Gestiona los Pagos
        //$ReservaPista = new ReservaPista($idReservaPista);

        //JMAM: Jugador 1 **********************************************************************************************/

        if (!empty($idJugador1)){
            switch ($valorPagadoJugador1){

                case 0:
                    //JMMA: NO pagado
                    Monedero::devolverPagoMonedero($idJugador1, $idReservaPista);
                    $PartidoJugador = $ReservaPista->obtenerPartidoJugadorPorIdJugador($idJugador1);
                    $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importePagoJugador1;
                    $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = ReservaPista::ESPAGADOJUGADOR_no;
                    $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = "";
                    $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = ReservaPista::TIPO_PAGO_MONEDERO_APLAZADO_NO;
                    $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = "";
                    $PartidoJugador->guardar();
                    break;

                case 1:
                    //JMAM: Pagado en Pista
                    Monedero::devolverPagoMonedero($idJugador1, $idReservaPista);
                    $PartidoJugador = $ReservaPista->obtenerPartidoJugadorPorIdJugador($idJugador1);
                    $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importePagoJugador1;
                    $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = ReservaPista::ESPAGADOJUGADOR_si;
                    $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                    $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = ReservaPista::TIPO_PAGO_MONEDERO_APLAZADO_NO;
                    $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = date("Y-m-d H:i:s");
                    $PartidoJugador->guardar();
                    break;

                case 2:
                    //JMAM: Pagado Con Monedero
                    Monedero::pagarConMonedero($idJugador1, $idReservaPista, $importePagoJugador1, false);
                    break;

                case 3:
                    //JMAM: Pagado con Monedero (Aplazado)
                    Monedero::devolverPagoMonedero($idJugador1, $idReservaPista);
                    Monedero::pagarConMonedero($idJugador1, $idReservaPista, $importePagoJugador1, true);
                    break;
            }
        }



        //JMAM: Jugador 2 **********************************************************************************************/
        if (!empty($idJugador2)){
            switch ($valorPagadoJugador2){

                case 0:
                    //JMMA: NO pagado
                    Monedero::devolverPagoMonedero($idJugador2, $idReservaPista);
                    $PartidoJugador = $ReservaPista->obtenerPartidoJugadorPorIdJugador($idJugador2);
                    $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importePagoJugador2;
                    $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = ReservaPista::ESPAGADOJUGADOR_no;
                    $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = "";
                    $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = ReservaPista::TIPO_PAGO_MONEDERO_APLAZADO_NO;
                    $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = "";
                    $PartidoJugador->guardar();
                    break;

                case 1:
                    //JMAM: Pagado en Pista
                    Monedero::devolverPagoMonedero($idJugador2, $idReservaPista);
                    $PartidoJugador = $ReservaPista->obtenerPartidoJugadorPorIdJugador($idJugador2);
                    $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importePagoJugador2;
                    $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = ReservaPista::ESPAGADOJUGADOR_si;
                    $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                    $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = ReservaPista::TIPO_PAGO_MONEDERO_APLAZADO_NO;
                    $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = date("Y-m-d H:i:s");
                    $PartidoJugador->guardar();
                    break;

                case 2:
                    //JMAM: Pagado Con Monedero
                    Monedero::pagarConMonedero($idJugador2, $idReservaPista, $importePagoJugador2, false);
                    break;

                case 3:
                    //JMAM: Pagado con Monedero (Aplazado)
                    Monedero::devolverPagoMonedero($idJugador2, $idReservaPista);
                    Monedero::pagarConMonedero($idJugador2, $idReservaPista, $importePagoJugador2, true);
                    break;
            }
        }



        //JMAM: Jugador 3 **********************************************************************************************/
        if (!empty($idJugador3)){
            switch ($valorPagadoJugador3){

                case 0:
                    //JMMA: NO pagado
                    Monedero::devolverPagoMonedero($idJugador3, $idReservaPista);
                    $PartidoJugador = $ReservaPista->obtenerPartidoJugadorPorIdJugador($idJugador3);
                    $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importePagoJugador3;
                    $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = ReservaPista::ESPAGADOJUGADOR_no;
                    $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = "";
                    $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = ReservaPista::TIPO_PAGO_MONEDERO_APLAZADO_NO;
                    $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = "";
                    $PartidoJugador->guardar();
                    break;

                case 1:
                    //JMAM: Pagado en Pista
                    Monedero::devolverPagoMonedero($idJugador3, $idReservaPista);
                    $PartidoJugador = $ReservaPista->obtenerPartidoJugadorPorIdJugador($idJugador3);
                    $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importePagoJugador3;
                    $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = ReservaPista::ESPAGADOJUGADOR_si;
                    $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                    $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = ReservaPista::TIPO_PAGO_MONEDERO_APLAZADO_NO;
                    $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = date("Y-m-d H:i:s");
                    $PartidoJugador->guardar();
                    break;

                case 2:
                    //JMAM: Pagado Con Monedero
                    Monedero::pagarConMonedero($idJugador3, $idReservaPista, $importePagoJugador3, false);
                    break;

                case 3:
                    //JMAM: Pagado con Monedero (Aplazado)
                    Monedero::devolverPagoMonedero($idJugador3, $idReservaPista);
                    Monedero::pagarConMonedero($idJugador3, $idReservaPista, $importePagoJugador3, true);
                    break;
            }

        }


        //JMAM: Jugador 4 *********************************************************************************************/
        if (!empty($idJugador4)){
            switch ($valorPagadoJugador4){

                case 0:
                    //JMMA: NO pagado
                    Monedero::devolverPagoMonedero($idJugador4, $idReservaPista);
                    $PartidoJugador = $ReservaPista->obtenerPartidoJugadorPorIdJugador($idJugador4);
                    $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importePagoJugador4;
                    $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = ReservaPista::ESPAGADOJUGADOR_no;
                    $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = "";
                    $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = ReservaPista::TIPO_PAGO_MONEDERO_APLAZADO_NO;
                    $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = "";
                    $PartidoJugador->guardar();
                    break;

                case 1:
                    //JMAM: Pagado en Pista
                    Monedero::devolverPagoMonedero($idJugador4, $idReservaPista);
                    $PartidoJugador = $ReservaPista->obtenerPartidoJugadorPorIdJugador($idJugador4);
                    $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importePagoJugador4;
                    $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = ReservaPista::ESPAGADOJUGADOR_si;
                    $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                    $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = ReservaPista::TIPO_PAGO_MONEDERO_APLAZADO_NO;
                    $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = date("Y-m-d H:i:s");
                    $PartidoJugador->guardar();
                    break;

                case 2:
                    //JMAM: Pagado Con Monedero
                    Monedero::pagarConMonedero($idJugador4, $idReservaPista, $importePagoJugador4, false);
                    break;

                case 3:
                    //JMAM: Pagado con Monedero (Aplazado)
                    Monedero::devolverPagoMonedero($idJugador4, $idReservaPista);
                    Monedero::pagarConMonedero($idJugador4, $idReservaPista, $importePagoJugador4, true);
                    break;
            }
        }






        $ReservaPista->reordenarJugadores(false);

        if ($actualizarCacheReserva){
            $ReservaPista->actualizarCacheReserva();
        }

        /*
        if ($ReservaPista->obtenerCampo()->obtenerId() != $idCampo_anterior || $ReservaPista->obtenerFechaReserva() != $fechaReserva_anterior || $ReservaPista->obtenerIdPista() != $idPista_anterior || $ReservaPista->obtenerHoraInicioReserva() != $horaInicio_anterior || $ReservaPista->obtenerHoraFinReserva() != $horaFin_anterior)
        self::actualizarCacheRedisTablaReserva($idCampo_anterior, $fechaReserva_anterior, $ReservaPista->obtenerDeporte()->obtenerId());
        */

        $Partido->asociarPartidoJugadoresDelPartidoALaIdReservaPista($idReservaPista);

        return $idReservaPista;

    }
    static function realizarReservaTipo($idTipoReserva, $idCampo, $array_idsPistas, $array_diasSemana, $fechaMYSQLInicio, $horaInicio, $fechaMYSQLFin, $horaFin, $idTiempoReserva, $descripcion=""){

        $idGrupoReserva = uniqid();
        Log::v(__FUNCTION__, "ID GRUPO RESERVA: $idGrupoReserva");

        if ($idTipoReserva == TipoReserva::ID_TIPORESERVA_BLOQUEO || $idTipoReserva == TipoReserva::ID_TIPORESERVA_ESCUELA){
            $array_ReservasPista = array();

            foreach ($array_idsPistas as $idPista){


                $fechaInicio=strtotime("$fechaMYSQLInicio");
                $fechaFin=strtotime("$fechaMYSQLFin");
                for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){

                    $diaSemanaBuscar = HorarioPista::obtenerDiaSemanaDeFecha(date("Y-m-d", $i));

                    foreach ($array_diasSemana as $diaSemana){

                        if ($diaSemana == $diaSemanaBuscar){
                            $fechaReserva = date("Y-m-d", $i);
                            $horaInicioReserva = $horaInicio;
                            $horaFinReserva = $horaFin;

                            if (!CacheTablaReserva::esPistaReservadaEnTramo($idPista, $fechaMYSQLInicio, $horaInicioReserva, $horaFinReserva)){
                                $ReservaPista = new ReservaPista();
                                $ReservaPista[self::COLUMNA_idCampo] = $idCampo;
                                $ReservaPista[self::COLUMNA_idPista] = $idPista;
                                $ReservaPista[self::COLUMNA_idTipoReserva] = $idTipoReserva;
                                $ReservaPista[self::COLUMNA_fechaReserva] = $fechaReserva;
                                $ReservaPista[self::COLUMNA_horaInicioReserva] = $horaInicioReserva;
                                //$ReservaPista[self::COLUMNA_HORAFINRESERVA] = $horaFinReserva;
                                $ReservaPista[self::COLUMNA_descripcion] = $descripcion;
                                $ReservaPista[self::COLUMNA_partidoPublico] = 0;
                                $ReservaPista[self::COLUMNA_partidoCompleto] = 0;
                                $ReservaPista[self::COLUMNA_esReservaRealizadaPorClub] = 1;
                                $ReservaPista[self::COLUMNA_esReservaModificadaPorClub] = 1;

                                $ReservaPista[self::COLUMNA_idTiempoReserva] = $idTiempoReserva;
                                if ($idTiempoReserva != -1){
                                    //JMAM: Guarda el Tiempo Reserva
                                    $TiempoReserva = new TiempoReserva($idTiempoReserva);
                                    $ReservaPista[self::COLUMNA_precioGeneral] = $TiempoReserva->obtenerPrecioGeneral();
                                    $ReservaPista[self::COLUMNA_precioRadical] = $TiempoReserva->obtenerPrecioRadical();
                                    $ReservaPista[self::COLUMNA_precioGrupo1] = $TiempoReserva->obtenerPrecioGrupo1();
                                    $ReservaPista[self::COLUMNA_precioGrupo2] = $TiempoReserva->obtenerPrecioGrupo2();
                                    $ReservaPista[self::COLUMNA_precioMonedero] = $TiempoReserva->obtenerPrecioMonedero();
                                    $ReservaPista[self::COLUMNA_precioSegundoMonedero] = $TiempoReserva->obtenerPrecioSegundoMonedero();
                                    $ReservaPista[self::COLUMNA_horaFinReserva] = $TiempoReserva->obtenerHoraFinTiempoReserva($horaInicioReserva);

                                }
                                else{
                                    $ReservaPista[self::COLUMNA_horaFinReserva] = $horaFinReserva;
                                }

                                $array_ReservasPista[] = $ReservaPista;
                            }
                            else{
                                //JMAM: NO se pueden bloquear las pistas, existe una reserva
                                return false;
                            }

                        }
                    }
                }


            }



            $bool_seHaProducidoError = false;
            foreach ($array_ReservasPista as $ReservaPista) {
                $ReservaPista[self::COLUMNA_idGrupoReserva] = $idGrupoReserva;
                $idReservaGuardado = $ReservaPista->guardar();


                $ReservaPista = new ReservaPista($idReservaGuardado);
                if (!$ReservaPista->comprobarIntegridadReserva(false, false, false)){
                    $asunto = "Reserva Incorrecta: PCU - Reserva Repetitiva";
                    $ReservaPista->comprobarIntegridadReserva(true, false, true, $asunto);

                    $bool_seHaProducidoError = true;
                }


                //JMAM: Actualizar caché sólo de las 5 primeras reservas
                if ($contador < 5){
                    $ReservaPista->actualizarCacheReserva();
                }
                else{
                    $ReservaPista->actualizarCacheReserva(true);
                }

                $contador++;

            }

            if ($bool_seHaProducidoError){
                echo Traductor::traducir("Se ha producido un error en el tratamiento de los datos de la reserva, ponte en contacto con administración si el error persiste");
                return false;
            }
            else{
                return true;
            }



        }
        else if ($idTipoReserva == TipoReserva::ID_TIPORESERVA_DESBLOQUEO){

        }
        else{
            echo "ID TIPO DE RESERVA NO IMPLEMENTADO.";
        }

    }

    static function eliminarReservasRango($array_idsPistas, $array_diasSemana, $fechaMYSQLInicio, $horaInicio, $fechaMYSQLFin, $horaFin)
    {

        $array_ReservasPista = array();

        foreach ($array_idsPistas as $idPista) {

            $fechaInicio = strtotime("$fechaMYSQLInicio");
            $fechaFin = strtotime("$fechaMYSQLFin");
            for ($i = $fechaInicio; $i <= $fechaFin; $i += 86400) {

                $diaSemanaBuscar = HorarioPista::obtenerDiaSemanaDeFecha(date("Y-m-d", $i));
                foreach ($array_diasSemana as $diaSemana) {

                    if ($diaSemana == $diaSemanaBuscar){
                        $fechaReserva = date("Y-m-d", $i);
                        $horaInicioReserva = $horaInicio;

                        $Pista = new Pista($idPista);

                        if ($Pista->esPistaReservada($fechaReserva, $horaInicioReserva)) {
                            $ReservaPista = $Pista->obtenerReservaPista($fechaReserva, $horaInicioReserva);
                            $idTipoReserva = $ReservaPista->obtenerIdTipoReserva();

                            if ($idTipoReserva == TipoReserva::ID_TIPORESERVA_BLOQUEO){
                                $array_ReservasPista[] = $ReservaPista;
                            }
                            else{
                                return false;
                            }
                        }

                    }

                }
            }
        }

        foreach ($array_ReservasPista as $ReservaPista) {
            $ReservaPista->eliminar();
        }

        return true;
    }

    static function obtenerParametrosReservaRepetitiva($array_idsPistas, $array_diasSemana, $fechaMYSQLInicio, $horaInicio, $fechaMYSQLFin, $horaFin, $idTiempoReserva){

        if ($idTiempoReserva == -1) {
            $horaFin = $horaFin;
        }
        else{
            $TiempoReserva = new TiempoReserva($idTiempoReserva);
            $horaFin = $TiempoReserva->obtenerHoraFinTiempoReserva($horaInicio);
        }


        $array_FechaYHoraReservar = array();

        foreach ($array_idsPistas as $idPista) {

            $fechaInicio = strtotime("$fechaMYSQLInicio");
            $fechaFin = strtotime("$fechaMYSQLFin");
            for ($i = $fechaInicio; $i <= $fechaFin; $i += 86400) {

                $diaSemanaBuscar = HorarioPista::obtenerDiaSemanaDeFecha(date("Y-m-d", $i));
                foreach ($array_diasSemana as $diaSemana) {

                    if ($diaSemana == $diaSemanaBuscar) {
                        $fechaReserva = date("Y-m-d", $i);
                        $horaInicioReserva = $horaInicio;
                        $horaFinReserva = $horaFin;

                        if (!CacheTablaReserva::esPistaReservadaEnTramo($idPista, $fechaReserva, $horaInicioReserva, $horaFinReserva)) {
                            Log::v(__FUNCTION__,"Es pista disponible en tramo: ID Pista: $idPista, Fecha Reserva: $fechaReserva, Hora Inicio: $horaInicioReserva, Hora Fin: $horaFinReserva",false);
                            $array_FechaYHoraReservar[] = array("idPista" => $idPista, "diaSemana" => $diaSemana, "fechaInicio" => $fechaReserva, "horaInicio" => $horaInicioReserva);
                        } else {
                            return false;
                        }
                    }

                }
            }
        }

        return $array_FechaYHoraReservar;
    }

    static function obtenerReservaPistaPartido($idPartido){
        global $bd;

        $id = $bd->get(self::TABLA_nombre, self::COLUMNA_id, array(self::COLUMNA_idPartido => $idPartido));

        return new ReservaPista($id);
    }

    static function obtenerReservaPistaPorNumeroPedido($numeroPedido){
        global $bd;

        $id = $bd->get(self::TABLA_nombre, self::COLUMNA_id, array(self::COLUMNA_numeroPedido => $numeroPedido));

        return new ReservaPista($id);
    }

    static function obtenerTipoJugadorReserva($idPartido){
        global $bd;
        return $bd->get(self::TABLA_nombre, self::COLUMNA_tipoJugadorReserva, array("idPartido" => $idPartido));
    }

    static function obtenerIdJugadorReserva($idPartido){
        global $bd;
        return $bd->get(self::TABLA_nombre, self::COLUMNA_idJugadorReserva, array("idPartido" => $idPartido));
    }

    static function guardarTipoJugadorReserva($idPartido, $tipoJugadorReserva){
        global $bd;
        $bd->update(self::TABLA_nombre, array(self::COLUMNA_tipoJugadorReserva => $tipoJugadorReserva), array("idPartido" => $idPartido));
        return $bd->update(self::TABLA_nombre, array(self::COLUMNA_tipoJugador1 => $tipoJugadorReserva), array("idPartido" => $idPartido));
    }

    static function guardarIdJugadorReserva($idPartido, $idJugadorReserva){
        global $bd;
        $bd->update(self::TABLA_nombre, array(self::COLUMNA_idJugadorReserva => $idJugadorReserva), array("idPartido" => $idPartido));
        return $bd->update(self::TABLA_nombre, array(self::COLUMNA_idJugador1 => $idJugadorReserva), array("idPartido" => $idPartido));
    }

    static function imprimirSelectorPistasDisponibles($idCampo, $fechaMYSQL, $hora){

        $array_PistasDisponibles = Pista::obtenerPistasDisponibles($idCampo, $fechaMYSQL, $hora);

        if (count($array_PistasDisponibles) > 0){
            echo "<select name='idPista' id='idPista' class='botonancho selectorPistaYTiemposReserva selectorPista' onchange='onchange_selectorPistasDisponibles(this.id)'>";
            foreach ($array_PistasDisponibles as $Pista){
                $idPista = $Pista["id"];
                $nombre = $Pista["nombre"];
                $descripcionPista = ucfirst($Pista->obtenerTipoPared()." - ".$Pista->obtenerTipoCubierta());
                echo "<option value='$idPista' idCampo='$idCampo'>$nombre</option>";
                echo "<option disabled>$descripcionPista</option>";
                echo "<option disabled></option>";
            }
            echo "</select>";
        }
        else{
            echo "Selecciona otro día u otra hora.";
        }



    }

    static function imprimirSelectorPistasDisponiblesAbrirPartido($idCampo, $fechaMYSQL, $hora, $idPistaSeleccionado=0, $idReservaIgnorar=""){

        Log::v(__FUNCTION__, "Hora: $hora", false);
        $Campo = new Campo($idCampo);
        $ConfiguracionReservaPistas = $Campo->obtenerConfiguracionReservaPistas();
        $esPermitidoSeleccionarPistas = $ConfiguracionReservaPistas->esPermitidoSeleccionarPistas();
        $esPermitidoMostrarSelectorPistaFormatoTabla = $ConfiguracionReservaPistas->esPermitidoMostrarSelectorPistaFormatoTabla();

        //JMAM: Comprueba si el selector de pista es en Formato Tabla
        if ($esPermitidoMostrarSelectorPistaFormatoTabla){
            //JMAM: Muestra todas las pistas que tiene el campo, ya que está limitada la selección por la tabla de reservas
            $array_PistasDisponibles = $Campo->obtenerPistas(Sesion::obtenerDeporte()->obtenerId());
        }
        else{
            //JMAM: Muestra sólo las pistas disponibles para reservar
            $array_PistasDisponibles = CacheTablaReserva::obtenerPistasDisponibles($idCampo, $fechaMYSQL, $hora);
        }

        Log::v(__FUNCTION__, "Pistas Disponibles: ".count($array_PistasDisponibles), false);


        if (count($array_PistasDisponibles) > 0){
            echo "<select name='idPista' id='idPista' class='botonancho selectorPistaYTiemposReserva selectorPista' onclick='onclick_selectorPista();' onchange='onchange_selectorPistasDisponibles(this.id)'>";

            $bool_salirBucle = false;
            $imprimir_option = true;
            foreach ($array_PistasDisponibles as $Pista){
                $idPista = $Pista["id"];
                $nombre = $Pista["nombre"];
                $descripcionPista = ucfirst($Pista->obtenerTipoPared()." - ".$Pista->obtenerTipoCubierta());

                if ($idPista == $idPistaSeleccionado){
                    $selected = "selected";
                    if ($idReservaIgnorar > 0){
                        $ReservaPistaIgnorar = new ReservaPista($idReservaIgnorar);
                        $horaFin_reservaPistaIgnorar = $ReservaPistaIgnorar->obtenerHoraFinReserva();

                        if (CacheTablaReserva::esPistaReservadaEnTramo($idPista, $fechaMYSQL, $hora, $horaFin_reservaPistaIgnorar, $idReservaIgnorar)){
                            $selected = "";
                        }
                    }

                }
                else {
                    $selected = "";
                }

                if ($esPermitidoSeleccionarPistas == false){
                    $imprimir_option = false;
                }


                $disabled = "disabled";
                //$imprimir_option == false;
                $colorDescripcion = "";

                //$CacheTablaReservaPistasEnFechaYHora = ReservaPista::generarCacheTablaReservaPistasEnFechaYHora($Pista->obtenerId(), $fechaMYSQL, $horaFin_reservaPistaIgnorar);
                if (!$Pista->esPistaReservadaRedis($fechaMYSQL, $hora, $idReservaIgnorar)){
                    $disabled = "";
                    $colorDescripcion = "gray";

                    if ($esPermitidoSeleccionarPistas == false){
                        $nombre = "Asignada por el Club";
                        $descripcionPista = "Pregunta al llegar por tu pista asignada.";

                        if ($idPistaSeleccionado != 0){
                            if ($idPista == $idPistaSeleccionado){
                                $imprimir_option = true;
                                $bool_salirBucle = true;
                            }
                            else{
                                $imprimir_option = false;
                            }
                        }
                        else{
                            $imprimir_option = true;
                            $bool_salirBucle = true;
                        }



                    }
                }

                if ($imprimir_option){
                    echo "<option $disabled value='$idPista' idCampo='$idCampo' $selected>$nombre</option>";
                    echo "<option disabled style='color: $colorDescripcion; font-size: 10px;'>$descripcionPista</option>";
                    echo "<option disabled style='color: $colorDescripcion'></option>";
                }


                if ($bool_salirBucle == true){
                    return;
                }
            }
            echo "</select>";
        }
        else{
            echo "Selecciona otro día u otra hora.";
        }



    }

    static function imprimirSelectorPistasAbrirPartido($idCampo, $fechaMYSQL, $hora, $idPistaSeleccionado=0){

        $array_PistasDisponibles = Pista::obtenerPistasDisponibles($idCampo, $fechaMYSQL, $hora);

        $Campo = new Campo($idCampo);
        $ConfiguracionReservaPistas = $Campo->obtenerConfiguracionReservaPistas();

        $esPermitidoSeleccionarPistas = $ConfiguracionReservaPistas->esPermitidoSeleccionarPistas();


        if (count($array_PistasDisponibles) > 0){
            echo "<select name='idPista' id='idPista' class='botonancho selectorPistaYTiemposReserva selectorPista' onchange='onchange_selectorPistasDisponibles(this.id)'>";

            $bool_salirBucle = false;
            $imprimir_option = true;
            foreach ($array_PistasDisponibles as $Pista){
                $idPista = $Pista["id"];
                $nombre = $Pista["nombre"];
                $descripcionPista = ucfirst($Pista->obtenerTipoPared()." - ".$Pista->obtenerTipoCubierta());

                if ($idPista == $idPistaSeleccionado){
                    $selected = "selected";
                }
                else {
                    $selected = "";
                }

                if ($esPermitidoSeleccionarPistas == false){
                    $imprimir_option = false;
                }


                $disabled = "disabled";
                $colorDescripcion = "";
                if ($Pista->esPistaSinReservar($fechaMYSQL, $hora)){
                    $disabled = "";
                    $colorDescripcion = "gray";

                    if ($esPermitidoSeleccionarPistas == false){
                        $nombre = "Asignada por el Club";
                        $descripcionPista = "Pregunta al llegar por tu pista asignada.";

                        if ($idPistaSeleccionado != 0){
                            if ($idPista == $idPistaSeleccionado){
                                $imprimir_option = true;
                                $bool_salirBucle = true;
                            }
                            else{
                                $imprimir_option = false;
                            }
                        }
                        else{
                            $imprimir_option = true;
                            $bool_salirBucle = true;
                        }



                    }
                }

                if ($imprimir_option){
                    echo "<option $disabled value='$idPista' idCampo='$idCampo' $selected>$nombre</option>";
                    echo "<option disabled style='color: $colorDescripcion; font-size: 10px;'>$descripcionPista</option>";
                    echo "<option disabled style='color: $colorDescripcion'></option>";
                }


                if ($bool_salirBucle == true){
                    return;
                }
            }
            echo "</select>";
        }
        else{
            echo "Selecciona otro día u otra hora.";
        }



    }

    static function imprimirSelectorTiemposReservaDisponibles($idPista, $fechaMYSQL, $hora, $onchange="", $valorSeleccionadoEnMinutos="", $ignorarHoraPistaBloqueada=false, $disabled = false){


        Log::v(__FUNCTION__, "Valor seleccionado en minutos ($valorSeleccionadoEnMinutos)", false);

        if ($onchange != ""){
            $onchange = "onchange='$onchange'";
        }


        if ($idPista > 0){
            $Pista = new Pista($idPista);
            $array_TiemposReserva = $Pista->obtenerTiemposReservaDisponibles($fechaMYSQL,$hora, $ignorarHoraPistaBloqueada);

        }
        else{
            $array_TiemposReserva = array();
        }


        if ($disabled){
            $disabled = "disabled";
        }

        $bool_algunValorSelected = false;

        echo "<select id='idTiempoReserva' name='idTiempoReserva' class='botonancho selectorPistaYTiemposReserva selectorTiempoReserva' $onchange $disabled>";
        if (count($array_TiemposReserva) > 0) {
            foreach ($array_TiemposReserva as $TiempoReserva) {
                $idTiempoReserva = $TiempoReserva["id"];
                $idHorarioPista = $TiempoReserva["idHorario"];
                $tiempoReserva = $TiempoReserva->obtenerTiempoReserva(true);


                if ($tiempoReserva == $valorSeleccionadoEnMinutos){
                    $selected = "selected";
                    $bool_algunValorSelected = true;
                }
                else {
                    $selected = "";
                }

                echo "<option value='$idTiempoReserva' $selected>$tiempoReserva m</option>";

            }
        }

        if ($bool_algunValorSelected == false && (!empty($valorSeleccionadoEnMinutos) || $valorSeleccionadoEnMinutos == -1)){
            $selected = "selected";
        }
        else {
            $selected = "";
        }

        echo "<option value='-1' $selected>".Traductor::traducir("Personalizada")."</option>";
        echo "</select>";


    }
    /**
     * @autor JMAM
     * Devuelve los tiempo de Reservas Disponibles para una Fecha y Hora dados
     *
     * @param $idPista
     * ID de la Pista en la que se quiere encontrar los Tiempos de Reseva, si se indica un ID de Campo, sirve para hacer la preselección correcta
     * @param $fechaMYSQL
     * Fecha en la que se quiere encontrar los Tiempos de Reserva disponible
     * @param $hora
     * Hora en los que se quiere encontrar los Tiempos de Reserva disponible
     * @param $idCampo
     * Si se indica, buscarla los Tiempos de Reservas disponible en todas las Pistas del Campo
     */
    static function imprimirSelectorTiemposReservaDisponiblesAbrirPartido($idPista, $fechaMYSQL, $hora, $idCampo=0, $onchange="", $idTiempoReservaSeleccionado=0, $idReservaPistaIgnorar=""){

        $array_TiemposReserva = array();
        $array_TiemposReservaTodos = array();

        //echo "ID RESERVA PISTA IGNORAR: $idReservaPistaIgnorar";
        if ($idReservaPistaIgnorar > 0){
            $ReservaPista_ignorar = new ReservaPista($idReservaPistaIgnorar);
            $duraciton_reservaPistaIgnorar = $ReservaPista_ignorar->obtenerDuracion(true);
        }

        if ($idPista > 0 || $idCampo == 0){
            $Pista = new Pista($idPista);
            $array_TiemposReserva = $Pista->obtenerTiemposReservaDisponibles($fechaMYSQL,$hora);

        }
        else if ($idCampo > 0){
            $Campo = new Campo($idCampo);
            $array_Pistas = $Campo->obtenerPistas();


            if ($idPista > 0){
                $Pista = new Pista($idPista);
                $array_TiemposReservaTodos = $Pista->obtenerTiemposReservaDisponibles($fechaMYSQL,$hora);

            }

            //JMAM: Obtiene todos los Tiempos de Reservas Disponblie de todas las Pistas
            foreach ($array_Pistas as $Pista){

                $array_TiemposReservaPista = $Pista->obtenerTiemposReservaDisponibles($fechaMYSQL,$hora);
                $array_TiemposReservaTodos = array_merge($array_TiemposReservaTodos,$array_TiemposReservaPista);


            }

            $array_aparacionesDuracion = array();
            //JMAM: Elimina Tiempos de Reservas Repetidos
            foreach ($array_TiemposReservaTodos as $TiempoReserva){
                $duracion = $TiempoReserva->obtenerTiempoReserva(true);

                $bool_noExsite = true;
                foreach ($array_aparacionesDuracion as $aparacionesDuracion){

                    if ($aparacionesDuracion == $duracion){
                        $bool_noExsite = false;
                        break;
                    }
                }

                //JMAM: Comprueba los Tiempos de Reserva, para que no se repitan
                if ($bool_noExsite){

                    $Pista = $TiempoReserva->obtenerPista();
                    $horaFin = $TiempoReserva->obtenerHoraFinTiempoReserva($hora);
                    if (!CacheTablaReserva::esPistaReservadaEnTramo($Pista->obtenerId(), $fechaMYSQL, $hora, $horaFin, $idReservaPistaIgnorar)){
                        $array_TiemposReserva[] = $TiempoReserva;
                        $array_aparacionesDuracion[] = $duracion;
                    }
                }


            }

        }
        else{
            $array_TiemposReserva = array();
        }





        echo "<select id='idTiempoReserva' name='idTiempoReserva' class='botonancho selectorPistaYTiemposReserva selectorTiempoReserva' onclick='onclick_selectorTiempoReserva()' onchange='$onchange' $disabled>";
        if (count($array_TiemposReserva) > 0) {

            $contador = 0;
            $array_options = [];
            foreach ($array_TiemposReserva as $TiempoReserva) {
                $idTiempoReserva = $TiempoReserva["id"];
                $idHorarioPista = $TiempoReserva["idHorario"];
                $tiempoReserva = $TiempoReserva->obtenerTiempoReserva(true);
                $horaFin = $TiempoReserva->obtenerHoraFinTiempoReserva($hora);
                $idPistaTiempoReserva = $TiempoReserva->obtenerHorarioPista()->obtenerPista()->obtenerId();


                if ($idPistaTiempoReserva == $idPista){
                    if ($idTiempoReserva == $idTiempoReservaSeleccionado || $tiempoReserva == $duraciton_reservaPistaIgnorar){
                        $selected = "selected";
                        //echo "selected";
                    }
                    else if ($tiempoReserva == "90" && empty($idTiempoReservaSeleccionado) && empty($duraciton_reservaPistaIgnorar)){
                        $selected = "selected";
                    }
                    else{
                        $selected = "";
                    }
                }
                else{
                    $selected = "";
                }

                if (!CacheTablaReserva::esPistaReservadaEnTramo($Pista->obtenerId(), $fechaMYSQL, $hora, $horaFin, $idReservaPistaIgnorar)){
                    $array_options[$tiempoReserva] = "<option value='$idTiempoReserva' $selected $disabled>$tiempoReserva m</option>";
                    //print_r($array_options);
                    //echo "<option value='$idTiempoReserva' $selected $disabled>$tiempoReserva m</option>";
                    $contador++;
                }

            }
            //JMAM: Ordenación de opción por duración
            ksort($array_options);
            foreach ($array_options as $option) {
                echo $option;
            }

            if ($contador == 0){
                echo "<option value=''>".Traductor::traducir("Selecciona otra Pista")."</option>";
            }
        }
        else{
            echo "<option value=''>".Traductor::traducir("Selecciona otra Pista")."</option>";
        }
        echo "</select>";

        foreach ($array_TiemposReserva as $TiempoReserva) {
            $idTiempoReserva = $TiempoReserva->obtenerId();
            $idPistaTiempoReserva = $TiempoReserva->obtenerPista()->obtenerId();
            echo "<input type='hidden' id='idPista_tiempoReserva_$idTiempoReserva' value='$idPistaTiempoReserva'/>";
        }


    }

    static function imprimirSelectorHorasDisponiblesAbrirPartido($idCampo, $fechaMYSQL, $horaSeleccionada="", $idReservaPistaIgnorar=""){
        $fechaHoy = date('Y-m-d');

        $Campo = new Campo($idCampo);


        if ($Campo->activadoModuloReserva() && $Campo->esPermitidoRealizarReservasPorJugadores()){
            //Log::v(__FUNCTION__, "Módulo de reserva Activado para el campo", true);
            $horaInicioMinimoCampo = HorarioPista::obtenerHoraInicioMinimoCampoDisponible($idCampo, $fechaMYSQL);
            $horaFinMaximoCampo = HorarioPista::obtenerHoraFinMaximoCampoDisponible($idCampo, $fechaMYSQL);
        }
        else{
            //Log::v(__FUNCTION__, "Módulo de reserva NO Activado para el campo", true);

            $horaInicioMinimoCampo = "07:30";
            $horaFinMaximoCampo = "23:00";
        }


        //JMAM: Comprueba si es hoy
        if ($fechaHoy == $fechaMYSQL){
            //JMAM: Hoy, no se deja seleccionar una hora inferior a la actual
            $horaInicioMinimoCampo = date("H:00");
            $horaInicioCampo = strtotime("+60 minutes", strtotime("$horaInicioMinimoCampo"));
        }
        else{
            $horaInicioCampo = strtotime("$horaInicioMinimoCampo");

        }



        $strtotime_horaFinMediaNoche = strtotime("06:00");
        $strotime_horaInicioMinimoCampo = strtotime($horaInicioMinimoCampo);
        $strotime_horaFinMaximoCampo = strtotime($horaFinMaximoCampo);


        if ($strotime_horaFinMaximoCampo < $strtotime_horaFinMediaNoche){
            $minutosDesdeAntesDeMediaNoche = (strtotime("23:59:00") - $strotime_horaInicioMinimoCampo)/60;
            $minutosDesdeMediaNoche = abs(((strtotime("00:01:00") - $strotime_horaFinMaximoCampo)/60));

            $minutos = $minutosDesdeAntesDeMediaNoche + $minutosDesdeMediaNoche;

            Log::v(__FUNCTION__, "minutosDesdeAntesDeMediaNoche: $minutosDesdeAntesDeMediaNoche | minutosDesdeMediaNoche: $minutosDesdeMediaNoche");

        }
        else{
            $minutos = ($strotime_horaFinMaximoCampo - $strotime_horaInicioMinimoCampo)/60;
            Log::v(__FUNCTION__, "strotime_horaFinMaximoCampo: $strotime_horaFinMaximoCampo | strotime_horaInicioMinimoCampo: $strotime_horaInicioMinimoCampo | MINUTOS: $minutos");
        }





        //$minutos = (strtotime($horaInicioMinimoCampo)-strtotime($horaFinMaximoCampo))/60;
        $minutos = abs($minutos); $minutos = floor($minutos);
        $intervalos = ($minutos / 30) + 1;

        Log::v(__FUNCTION__, "HORA INICIO: $horaInicioMinimoCampo | HORA FIN: $horaFinMaximoCampo | MINUTOS: $minutos | INTERVALOS: $intervalos", false);

        $horaInicioCampo = strtotime("-30 minutes", $horaInicioCampo);

        ?>
        <select name="Hora" id="Hora" class="botonancho-nofloat horas" style="display:inline;padding:0px;  padding-left: 0px !important; border:0px; width: 100% !important; text-align-last:center;" onclick="onclick_selectorHora();" onchange="actualizarSelectoresPistasYTiemposReserva();">
            <?php
            for ($i = 0; $i < $intervalos; $i++) {

                $horaAImprimir = date('H:i', $horaInicioCampo);

                $selected = "";
                if ($horaAImprimir == $horaSeleccionada){
                    $selected = "selected";
                }
                if ($Campo->activadoModuloReserva() && $Campo->esPermitidoRealizarReservasPorJugadores()){
                    if (CacheTablaReserva::esAlgunaPistaLibreYSinReservar($idCampo, $fechaMYSQL, $horaAImprimir, $idReservaPistaIgnorar) && Sesion::obtenerJugador()->puedeRealizarReservaPorHoraAntelacionMinima($idCampo, $horaAImprimir)) {
                        ?>
                        <option <?php echo $selected;?> value="<?php echo $horaAImprimir;?>"><?php echo $horaAImprimir;?> h</option>
                        <?php
                    }
                }
                else{
                    ?>
                    <option <?php echo $selected;?> value="<?php echo $horaAImprimir;?>"><?php echo $horaAImprimir;?> h</option>
                    <?php
                }


                $horaInicioCampo = strtotime("+30 minutes", $horaInicioCampo);
            }
            ?>
        </select>
        <?php


    }

    static function imprimirSelectorHorasAbrirPartido($idCampo, $fechaMYSQL, $horaSeleccionada=""){


        $Campo = new Campo($idCampo);

        if ($Campo->activadoModuloReserva()){
            $horaInicioMinimoCampo = HorarioPista::obtenerHoraInicioMinimoCampoDisponible($idCampo, $fechaMYSQL);
            $horaFinMaxinoCampo = HorarioPista::obtenerHoraFinMaximoCampoDisponible($idCampo, $fechaMYSQL);

            $horaInicioMinimo_array = explode(":", $horaInicioMinimoCampo);
            $horaInicioMinimo = $horaInicioMinimo_array[0];
            $minutoInicioMinimo = $horaInicioMinimo_array[1];

            $horaFinMaxinoCampo_array = explode(":", $horaFinMaxinoCampo);
            $horaFinMaximo = $horaFinMaxinoCampo_array[0];
            $minutoFinMaxino = $horaFinMaxinoCampo_array[1];
        }
        else{
            $horaInicioMinimoCampo = "07:00";
            $horaFinMaxinoCampo = "23:00";

            $horaInicioMinimo_array = explode(":", $horaInicioMinimoCampo);
            $horaInicioMinimo = $horaInicioMinimo_array[0];
            $minutoInicioMinimo = $horaInicioMinimo_array[1];

            $horaFinMaxinoCampo_array = explode(":", $horaFinMaxinoCampo);
            $horaFinMaximo = $horaFinMaxinoCampo_array[0];
            $minutoFinMaxino = $horaFinMaxinoCampo_array[1];
        }





        ?>


        <select name="Hora" id="Hora" class="botonancho-nofloat horas" style="display:inline;padding:0px;  padding-left: 0px !important; border:0px; width: 100% !important; text-align-last:center;" onchange="actualizarSelectorMinutos()">
            <?php
            $count = 0;
            for ($i = $horaInicioMinimo; $i <= $horaFinMaximo; $i++){
                $horaFormateada = str_pad($i, 2, "0", STR_PAD_LEFT);


                if ($Campo->activadoModuloReserva()) {
                    $disabled = "disabled";
                    for ($j = $minutoInicioMinimo; $j <= $minutoFinMaxino; $j += 15) {
                        $minutoFormateado = str_pad($j, 2, "0", STR_PAD_LEFT);


                        echo "Hora Completa: $i:$minutoFormateado";

                        if (self::esAlgunaPistaLibreYSinReservar($idCampo, $fechaMYSQL, "$horaFormateada:$minutoFormateado")) {
                            $disabled = "";
                        }
                    }



                }

                if ($disabled == "") {
                    if ($count == 0) {
                        $selected = "selected";
                    } else {

                        if ($horaFormateada == "09" && $horaSeleccionada != ""){
                            $selected = "selected";
                        }
                        else{
                            $selected = "";
                        }

                    }
                    $count++;
                }


                echo "<option value='$horaFormateada' $selected $disabled>$horaFormateada</option>";
            }
            ?>
        </select>
        <?php

    }

    static function imprimirSelectorMinutosAbrirPartido($idCampo, $fechaMYSQL, $horaSolo, $minutosSeleccionado){

        $Campo = new Campo($idCampo);
        $horaInicioMinimoCampo = "00:00";
        $horaFinMaxinoCampo = "00:55";

        $horaInicioMinimo_array = explode(":", $horaInicioMinimoCampo);
        $horaInicioMinimo = $horaInicioMinimo_array[0];
        $minutoInicioMinimo = $horaInicioMinimo_array[1];

        $horaFinMaxinoCampo_array = explode(":", $horaFinMaxinoCampo);
        $horaFinMaximo = $horaFinMaxinoCampo_array[0];
        $minutoFinMaxino = $horaFinMaxinoCampo_array[1];
        ?>

        <select name="Minutos" id="Minutos" class="botonancho minutos" style="display:inline;  padding-left: 0px !important; width: 100% !important; text-align-last:center;" onchange="actualizarSelectoresPistasYTiemposReserva()">
            <?php


            $count++;
            for($i = $minutoInicioMinimo; $i <= $minutoFinMaxino; $i+=5){



                $minutoFormateado = str_pad($i, 2, "0", STR_PAD_LEFT);


                if ($Campo->activadoModuloReserva()) {
                    $disabled = "disabled";
                    if (self::esAlgunaPistaLibreYSinReservar($idCampo, $fechaMYSQL, "$horaSolo:$minutoFormateado")) {
                        $disabled = "";
                    }

                }

                if ($disabled == "") {
                    if ($count == 0) {
                        $selected = "selected";
                    } else {
                        if ($minutoFormateado == $minutosSeleccionado && $minutosSeleccionado != ""){
                            $selected = "selected";
                        }
                        else{
                            $selected = "";
                        }
                    }
                }


                echo "<option value='$minutoFormateado' $selected $disabled>$minutoFormateado</option>";
            }
            ?>
        </select>
        <?php
    }

    static function esPistaLibre($idPista, $fechaMYSQl, $hora){

        $Pista = new Pista($idPista);
        return $Pista->esPistaLibre($fechaMYSQl, $hora);
    }

    static function esPistaReservada($idPista, $fechaMYSQL, $hora, $idReservaPistaIgnorar=""){


        $Pista = new Pista($idPista);
        $ReservaPista = $Pista->obtenerReservaPista($fechaMYSQL, $hora, $idReservaPistaIgnorar);

        if ($ReservaPista == null){
            return false;
        }
        else{
            return true;
        }
    }

    static function esAlgunaPistaLibre($idCampo, $fechaMYSQL, $horaCompleta){

        $array_Pistas = Pista::obtenerTodos($idCampo);

        foreach ($array_Pistas as $Pista) {

            if ($Pista->esPistaLibre($fechaMYSQL, $horaCompleta)){
                return true;
            }
        }

        return false;

    }

    static function esPistaSinReservar($idPista, $fechaMYSQL, $horaInicio, $idReservaPistaIgnorar="", $conRedis){
        Log::v(__FUNCTION__,"Función Llamada");



        $Pista = new Pista($idPista);
        $array_TiemposReserva = $Pista->obtenerTiemposReservaDisponibles($fechaMYSQL, $horaInicio);


        foreach ($array_TiemposReserva as $TiempoReserva){
            $horaFin = $TiempoReserva->obtenerHoraFinTiempoReserva($horaInicio);
            Log::v(__FUNCTION__,"CAMPO: $idCampo FECHA: $fechaMYSQL INICIO: $horaInicio FIN: $horaFin ID RESERVA IGNORAR:$idReservaPistaIgnorar");
            if (!$Pista->esPistaReservadaEnTramo($fechaMYSQL, $horaInicio, $horaFin,$idReservaPistaIgnorar, $conRedis)){
                Log::v(__FUNCTION__,"Es pista reservada en tramo: ID PISTA: $idPista | FECHA:$fechaMYSQL | HORA INICIO: $horaInicio");

                return true;
            }
        }
        return false;
    }

    static function esAlgunaPistaLibreYSinReservar($idCampo, $fechaMYSQL, $horaInicio, $idReservaPistaIgnorar=""){
        $array_Pistas = Pista::obtenerTodos($idCampo);

        foreach ($array_Pistas as $Pista) {
            //JMAM: Comprueba si la Pista está Libre, es decir, tiene horarios disponibles
            if (self::esPistaLibre($Pista->obtenerId(),$fechaMYSQL, $horaInicio)){
                //JMAM: Pista con Horario Disponible

                //JMAM: Comprueba si la Pista está sin Reservar en ese Horario
                if (self::esPistaSinReservar($Pista->obtenerId(), $fechaMYSQL, $horaInicio, $idReservaPistaIgnorar)){
                    return true;
                }
            }
        }

        return false;
    }

    static function esPistaReservadaEnTramo($idPista, $idTiempoReserva, $fechaMYSQL, $horaInicio, $idReservaPistaIgnorar = 0, $horaFin=""){


        if ($idTiempoReserva > 0){
            $TiempoReserva =  new TiempoReserva($idTiempoReserva);
            $horaFin = $TiempoReserva->obtenerHoraFinTiempoReserva($horaInicio);
        }

        //echo "ID PISTA:$idPista, ID TIEMPO RESERVA:$idTiempoReserva, FECHA:$fechaMYSQL, HORA INICIO:$horaInicio, HORA FIN:$horaFin";


        $Pista = new Pista($idPista);
        return $Pista->esPistaReservadaEnTramo($fechaMYSQL, $horaInicio, $horaFin, $idReservaPistaIgnorar);
    }

    static function obtenerReservaPista($idPista, $fechaMYSQL, $hora){
        $Pista = new Pista($idPista);
        return $Pista->obtenerReservaPista($fechaMYSQL, $hora);

    }

    static function obtenerIdsReservaPista($fechaMysql_inicio, $fechaMYSQL_fin, $idClub, $idCampo=-1, $idPista="-1"){
        global $bd;


        $where = array();
        $where["AND"][self::COLUMNA_fechaReserva."[>=]"] = $fechaMysql_inicio;
        $where["AND"][self::COLUMNA_fechaReserva."[<=]"] = $fechaMYSQL_fin;

        if ($idPista != -1){
            $where["AND"][self::COLUMNA_idPista] = $idPista;
        }
        else{
            $Club = new Club($idClub);
            $array_Pistas = $Club->obtenerPistas($idCampo);

            $array_idsPistas = array();
            foreach ($array_Pistas as $Pista){
                $array_idsPistas[] = $Pista->obtenerId();
            }

            $idsPistas_separados = implode(",", $array_idsPistas);
            Log::v(__FUNCTION__, "ID PISTAS: ".$idsPistas_separados, true);

            $where["AND"][self::COLUMNA_idPista] = $array_idsPistas;
        }

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, $where);
    }

    static function modificarReservaPista($idPartido, $idPista, $horaInicio, $fechaReserva, $idTiempoReserva,  $partidoCompleto, $idJugadorPagaReserva, $importePagoReserva, $tipoPagoJugadorReserva){
        $Partido = new Partido($idPartido);
        if ($partidoCompleto == 1){
            $partidoCompleto = true;
        }
        else{
            $partidoCompleto = false;
        }


        //JMAM: Comprueba si el Partido es una Reserva de Pista
        if ($Partido->esReservaPistaPartido()){
            //JMAM: El Partido es una Reserva de Pista
            $ReservaPista = $Partido->obtenerReservaPistaPartido();
            $ReservaPista->actualizarCacheReserva(false);

            $importeReservaJugador1 = $ReservaPista->obtenerImportePagoJugador($idJugadorPagaReserva);

            //JMAM: Comprueba si el importe pagado por el Jugador y el importe actual es el mismo
            if ($importeReservaJugador1 != $importePagoReserva){
                //JMAM: Importe pagado antes y ahora a pagar son diferentes

                //JMAM: Devolver importe pagado a todos los jugadores
                $ReservaPista->devolverPagoATodosLosJugadores();

                //JMAM: Realizar el cobro del Jugador que modifica la reserva
                if ($tipoPagoJugadorReserva == self::TIPOPAGOJUGADORRESERVA_MONEDERO){
                    $esPagadoConMonedero = Monedero::pagarConMonedero($idJugadorPagaReserva, $ReservaPista->obtenerId(), $importePagoReserva);
                    if ($esPagadoConMonedero == false){
                        $ReservaPista = new ReservaPista($ReservaPista->obtenerId());
                        $ReservaPista[self::COLUMNA_importePagoJugador1] = $importePagoReserva;
                        $ReservaPista[self::COLUMNA_tipoPagoJugador1] = self::TIPOPAGOJUGADORRESERVA_ENPISTA;
                        $ReservaPista[self::COLUMNA_pagadoJugador1] = 1;
                        $ReservaPista->guardar();
                    }
                }
                else if ($tipoPagoJugadorReserva == self::TIPOPAGOJUGADORRESERVA_ENPISTA){
                    $ReservaPista = new ReservaPista($ReservaPista->obtenerId());
                    $ReservaPista[self::COLUMNA_importePagoJugador1] = $importePagoReserva;
                    $ReservaPista[self::COLUMNA_tipoPagoJugador1] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                    $ReservaPista[self::COLUMNA_pagadoJugador1] = 1;
                    $ReservaPista->guardar();
                }

            }

            $ReservaPista = $Partido->obtenerReservaPistaPartido();

            if ($idPista > 0){
                $ReservaPista[self::COLUMNA_idPista] = $idPista;
            }

            $ReservaPista[self::COLUMNA_horaInicioReserva] = $horaInicio;
            $ReservaPista[self::COLUMNA_fechaReserva] = $fechaReserva;
            $ReservaPista[self::COLUMNA_partidoCompleto] = $partidoCompleto;
            $ReservaPista[self::COLUMNA_esReservaModificadaPorClub] = 0;


            if (is_numeric($idTiempoReserva)){

                $TiempoReserva = new TiempoReserva($idTiempoReserva);
                $duracion = $TiempoReserva->obtenerTiempoReserva();
                //$horaFinReserva = self::sumarHoras($horaInicio, $duracion);
                $horaFinReserva = $TiempoReserva->obtenerHoraFinTiempoReserva($horaInicio);
                $precioGeneral = $TiempoReserva->obtenerPrecioGeneral();
                $precioRadical = $TiempoReserva->obtenerPrecioRadical();
                $precioSocios = $TiempoReserva->obtenerPrecioSocios();
                $precioGrupo1 = $TiempoReserva->obtenerPrecioGrupo1();
                $precioGrupo2 = $TiempoReserva->obtenerPrecioGrupo2();
                $iluminacionIncluida = $TiempoReserva->esIluminacionIncluida();
                $precioMonedero = $TiempoReserva->obtenerPrecioMonedero();
                $precioSegundoMonedero = $TiempoReserva->obtenerPrecioSegundoMonedero();

                $ReservaPista[self::COLUMNA_precioGeneral] = $precioGeneral;
                $ReservaPista[self::COLUMNA_precioRadical] = $precioRadical;
                $ReservaPista[self::COLUMNA_precioSocios] = $precioSocios;
                $ReservaPista[self::COLUMNA_precioGrupo1] = $precioGrupo1;
                $ReservaPista[self::COLUMNA_precioGrupo2] = $precioGrupo2;
                $ReservaPista[self::COLUMNA_iluminacionIncluida] = $iluminacionIncluida;
                $ReservaPista[self::COLUMNA_precioMonedero] = $precioMonedero;
                $ReservaPista[self::COLUMNA_precioSegundoMonedero] = $precioSegundoMonedero;
            }
            else{
                $horaFinReserva = Fecha::anadirMinutosAHora(90, $horaInicio);
            }

            $ReservaPista[self::COLUMNA_horaFinReserva] = $horaFinReserva;

            $ReservaPista->guardar();
            $ReservaPista->reordenarJugadores();
            $ReservaPista->actualizarCacheReserva();


        }
    }

    static function realizarReservaPista($idPartido, $idPista, $horaInicio, $fechaReserva, $idTiempoReserva,  $idJugador1, $numeroJugadores, $repartirImporte, $importeReserva, $aplazadoPagoJugador1, $tipoPagoJugador1, $partidoCompleto, $reservaRealizadaPorClub, $numeroPedido){
        global $bd;

        $Partido = new Partido($idPartido);
        $Jugador1 = new Jugador($idJugador1);

        $fechaHoy = date("Y-m-d H:i:s");

        //$fechaReserva = $fechaHoy;
        $fechaPagoReserva = $fechaHoy;

        if ($partidoCompleto == 1){
            $partidoCompleto = true;
        }
        else{
            $partidoCompleto = false;
        }

        if ($repartirImporte == "true"){
            $repartirImporte = true;
        }
        else{
            $repartirImporte = false;
        }


        $TiempoReserva = new TiempoReserva($idTiempoReserva);
        $duracion = $TiempoReserva->obtenerTiempoReserva();
        //$horaFinReserva = self::sumarHoras($horaInicio, $duracion);
        $horaFinReserva = $TiempoReserva->obtenerHoraFinTiempoReserva($horaInicio);


        $importeCadaJugador = (FLOAT)$importeReserva / $numeroJugadores;

        Log::v(__FUNCTION__, "Importe Reserva: $importeReserva | ¿Repartir Importe?:$repartirImporte | Número Jugadores: $numeroJugadores | Importe Cada Jugador: $importeCadaJugador", true);


        $importePagoJugador1 = 0;
        $importePagoJugador2 = 0;
        $importePagoJugador3 = 0;
        $importePagoJugador4 = 0;

        $pagadoJugador1 = false;
        $pagadoJugador2 = false;
        $pagadoJugador3 = false;
        $pagadoJugador4 = false;

        $aplazadoPagoJugador1 = $aplazadoPagoJugador1;
        $aplazadoPagoJugador2 = false;
        $aplazadoPagoJugador3 = false;
        $aplazadoPagoJugador4 = false;

        $fechaPagoJugador1 = "";
        $fechaPagoJugador2 = "";
        $fechaPagoJugador3 = "";
        $fechaPagoJugador4 = "";

        $precioGeneral = $TiempoReserva->obtenerPrecioGeneral();
        $precioRadical = $TiempoReserva->obtenerPrecioRadical();
        $precioSocios = $TiempoReserva->obtenerPrecioSocios();
        $precioGrupo1 = $TiempoReserva->obtenerPrecioGrupo1();
        $precioGrupo2 = $TiempoReserva->obtenerPrecioGrupo2();
        $iluminacionIncluida = $TiempoReserva->esIluminacionIncluida();
        $precioMonedero = $TiempoReserva->obtenerPrecioMonedero();
        $precioSegundoMonedero = $TiempoReserva->obtenerPrecioSegundoMonedero();

        //JMAM: Cálculo de Importe y Jugadores que han pagado según el número de Jugadores y Repartir Importe
        switch ($numeroJugadores){

            case 2:

                if ($repartirImporte){

                    $importePagoJugador1 = $importeCadaJugador;
                    $importePagoJugador2 = $importeCadaJugador;

                    /*
                    $pagadoJugador1 = true;
                    $pagadoJugador2 = true;
                    */

                    $fechaPagoJugador1 = $fechaHoy;
                    $fechaPagoJugador2 = $fechaHoy;
                }
                else{
                    $importePagoJugador1 = $importeReserva;
                    //$pagadoJugador1 = true;
                    $fechaPagoJugador1 = $fechaHoy;
                }

                break;

            case 3:
                if ($repartirImporte){

                    $importePagoJugador1 = $importeCadaJugador;
                    $importePagoJugador2 = $importeCadaJugador;
                    $importePagoJugador3 = $importeCadaJugador;
                    /*
                    $pagadoJugador1 = true;
                    $pagadoJugador2 = true;
                    $pagadoJugador3 = true;
                    */
                    $fechaPagoJugador1 = $fechaHoy;
                    $fechaPagoJugador2 = $fechaHoy;
                    $fechaPagoJugador3 = $fechaHoy;
                }
                else{
                    $importePagoJugador1 = $importeReserva;
                    $pagadoJugador1 = true;
                    $fechaPagoJugador1 = $fechaHoy;
                }
                break;

            case 4:
                if ($repartirImporte){

                    $importePagoJugador1 = $importeCadaJugador;
                    $importePagoJugador2 = $importeCadaJugador;
                    $importePagoJugador3 = $importeCadaJugador;
                    $importePagoJugador4 = $importeCadaJugador;
                    /*
                    $pagadoJugador1 = true;
                    $pagadoJugador2 = true;
                    $pagadoJugador3 = true;
                    $pagadoJugador4 = true;
                    */
                    $fechaPagoJugador1 = $fechaHoy;
                    $fechaPagoJugador2 = $fechaHoy;
                    $fechaPagoJugador3 = $fechaHoy;
                    $fechaPagoJugador4 = $fechaHoy;
                }
                else{
                    $importePagoJugador1 = $importeReserva;
                    //$pagadoJugador1 = true;
                    $fechaPagoJugador1 = $fechaHoy;
                }
                break;
        }

        //JMAM: Comprueba si el Pago es Tipo TPV
        if ($tipoPagoJugador1 == self::TIPOPAGOJUGADORRESERVA_TPV){
            //JMAM: Pago tipo TPV, la reserva no se marca como pagada, queda pendiente de que el TPV la marque como pagada
            $pagadoJugador1 = false;
            $pagadoJugador2 = false;
            $pagadoJugador3 = false;
            $pagadoJugador4 = false;

            $fechaPagoJugador1 = "";
            $fechaPagoJugador2 = "";
            $fechaPagoJugador3 = "";
            $fechaPagoJugador4 = "";
        }




        $grupoJugador1 = $Jugador1->obtenerGrupoJugador($Partido->obtenerIdLiga())->obtenerId();
        if ($grupoJugador1 == null){
            $grupoJugador1 = 0;
        }

        $ReservaPista = $Partido->obtenerReservaPistaPartido();
        $ReservaPista[self::COLUMNA_idPartido] = $idPartido;
        $ReservaPista[self::COLUMNA_idPista] = $idPista;
        $ReservaPista[self::COLUMNA_idTipoReserva] = 3;                           //JMAM: Normal
        $ReservaPista[self::COLUMNA_tipoJugadorReserva] = "interno";              //JMAM: Tipo Jugador Interno
        $ReservaPista[self::COLUMNA_tipoPagoJugadorReserva] = $tipoPagoJugador1;
        $ReservaPista[self::COLUMNA_idJugadorReserva] = $idJugador1;
        $ReservaPista[self::COLUMNA_idJugador1] = $idJugador1;
        $ReservaPista[self::COLUMNA_grupoJugador1] = $grupoJugador1;
        $ReservaPista[self::COLUMNA_grupoJugador2] = 0;
        $ReservaPista[self::COLUMNA_grupoJugador3] = 0;
        $ReservaPista[self::COLUMNA_grupoJugador4] = 0;
        $ReservaPista[self::COLUMNA_numeroJugadoresMaximoPermitidos] = $numeroJugadores;
        $ReservaPista[self::COLUMNA_repartirImporte] = $repartirImporte;
        $ReservaPista[self::COLUMNA_partidoCompleto] = $partidoCompleto;
        $ReservaPista[self::COLUMNA_partidoPublico] = 1;
        $ReservaPista[self::COLUMNA_fechaReserva] = $fechaReserva;
        $ReservaPista[self::COLUMNA_horaInicioReserva] = $horaInicio;
        $ReservaPista[self::COLUMNA_horaFinReserva] = $horaFinReserva;
        $ReservaPista[self::COLUMNA_esReservaRealizadaPorClub] = 0;
        $ReservaPista[self::COLUMNA_esReservaModificadaPorClub] = 0;
        $ReservaPista[self::COLUMNA_importeReserva] = $importeReserva;
        $ReservaPista[self::COLUMNA_fechaPagoReserva] = $fechaPagoReserva;
        $ReservaPista[self::COLUMNA_importePagoJugador1] = $importePagoJugador1;
        $ReservaPista[self::COLUMNA_importePagoJugador2] = $importePagoJugador2;
        $ReservaPista[self::COLUMNA_importePagoJugador3] = $importePagoJugador3;
        $ReservaPista[self::COLUMNA_importePagoJugador4] = $importePagoJugador4;
        $ReservaPista[self::COLUMNA_tipoPagoJugador1] = $tipoPagoJugador1;
        $ReservaPista[self::COLUMNA_tipoPagoJugador2] = "";
        $ReservaPista[self::COLUMNA_tipoPagoJugador3] = "";
        $ReservaPista[self::COLUMNA_tipoPagoJugador4] = "";
        $ReservaPista[self::COLUMNA_pagadoJugador1] = $pagadoJugador1;
        $ReservaPista[self::COLUMNA_pagadoJugador2] = $pagadoJugador2;
        $ReservaPista[self::COLUMNA_pagadoJugador3] = $pagadoJugador3;
        $ReservaPista[self::COLUMNA_pagadoJugador4] = $pagadoJugador4;
        $ReservaPista[self::COLUMNA_aplazadoPagoJugador1] = $aplazadoPagoJugador1;
        $ReservaPista[self::COLUMNA_aplazadoPagoJugador2] = $aplazadoPagoJugador2;
        $ReservaPista[self::COLUMNA_aplazadoPagoJugador3] = $aplazadoPagoJugador3;
        $ReservaPista[self::COLUMNA_aplazadoPagoJugador4] = $aplazadoPagoJugador4;
        $ReservaPista[self::COLUMNA_fechaPagoJugador1] = $fechaPagoJugador1;
        $ReservaPista[self::COLUMNA_fechaPagoJugador2] = $fechaPagoJugador2;
        $ReservaPista[self::COLUMNA_fechaPagoJugador3] = $fechaPagoJugador3;
        $ReservaPista[self::COLUMNA_fechaPagoJugador4] = $fechaPagoJugador4;
        $ReservaPista[self::COLUMNA_precioGeneral] = $precioGeneral;
        $ReservaPista[self::COLUMNA_precioRadical] = $precioRadical;
        $ReservaPista[self::COLUMNA_precioSocios] = $precioSocios;
        $ReservaPista[self::COLUMNA_precioGrupo1] = $precioGrupo1;
        $ReservaPista[self::COLUMNA_precioGrupo2] = $precioGrupo2;
        $ReservaPista[self::COLUMNA_iluminacionIncluida] = $iluminacionIncluida;
        $ReservaPista[self::COLUMNA_precioMonedero] = $precioMonedero;
        $ReservaPista[self::COLUMNA_precioSegundoMonedero] = $precioSegundoMonedero;
        $ReservaPista[self::COLUMNA_numeroPedido] = $numeroPedido;
        $idReservaPista = $ReservaPista->guardar();


            /*
        $idReservaPista = $bd->insert(self::TABLA_nombre, array(
            self::COLUMNA_idPartido => $idPartido,
            self::COLUMNA_idPista => $idPista,
            self::COLUMNA_idTipoReserva => 3,                           //JMAM: Normal
            self::COLUMNA_tipoJugadorReserva => "interno",              //JMAM: Tipo Jugador Interno
            self::COLUMNA_tipoPagoJugadorReserva => $tipoPagoJugador1,
            self::COLUMNA_idJugadorReserva => $idJugador1,
            self::COLUMNA_idJugador1 => $idJugador1,
            self::COLUMNA_grupoJugador1 => $grupoJugador1,
            self::COLUMNA_grupoJugador2 => 0,
            self::COLUMNA_grupoJugador3 => 0,
            self::COLUMNA_grupoJugador4 => 0,
            self::COLUMNA_numeroJugadoresMaximoPermitidos => $numeroJugadores,
            self::COLUMNA_repartirImporte => $repartirImporte,
            self::COLUMNA_partidoCompleto => $partidoCompleto,
            self::COLUMNA_partidoPublico => 1,
            self::COLUMNA_fechaReserva => $fechaReserva,
            self::COLUMNA_horaInicioReserva => $horaInicio,
            self::COLUMNA_horaFinReserva => $horaFinReserva,
            self::COLUMNA_esReservaRealizadaPorClub => 0,
            self::COLUMNA_esReservaModificadaPorClub => 0,
            self::COLUMNA_importeReserva => $importeReserva,
            self::COLUMNA_fechaPagoReserva => $fechaPagoReserva,
            self::COLUMNA_importePagoJugador1 => $importePagoJugador1,
            self::COLUMNA_importePagoJugador2 => $importePagoJugador2,
            self::COLUMNA_importePagoJugador3 => $importePagoJugador3,
            self::COLUMNA_importePagoJugador4 => $importePagoJugador4,
            self::COLUMNA_tipoPagoJugador1 => $tipoPagoJugador1,
            self::COLUMNA_tipoPagoJugador2 => "",
            self::COLUMNA_tipoPagoJugador3 => "",
            self::COLUMNA_tipoPagoJugador4 => "",
            self::COLUMNA_pagadoJugador1 => $pagadoJugador1,
            self::COLUMNA_pagadoJugador2 => $pagadoJugador2,
            self::COLUMNA_pagadoJugador3 => $pagadoJugador3,
            self::COLUMNA_pagadoJugador4 => $pagadoJugador4,
            self::COLUMNA_aplazadoPagoJugador1 => $aplazadoPagoJugador1,
            self::COLUMNA_aplazadoPagoJugador2 => $aplazadoPagoJugador2,
            self::COLUMNA_aplazadoPagoJugador3 => $aplazadoPagoJugador3,
            self::COLUMNA_aplazadoPagoJugador4=> $aplazadoPagoJugador4,
            self::COLUMNA_fechaPagoJugador1 => $fechaPagoJugador1,
            self::COLUMNA_fechaPagoJugador2 => $fechaPagoJugador2,
            self::COLUMNA_fechaPagoJugador3 => $fechaPagoJugador3,
            self::COLUMNA_fechaPagoJugador4 => $fechaPagoJugador4,
            self::COLUMNA_precioGeneral => $precioGeneral,
            self::COLUMNA_precioRadical => $precioRadical,
            self::COLUMNA_precioSocios => $precioSocios,
            self::COLUMNA_precioGrupo1 => $precioGrupo1,
            self::COLUMNA_precioGrupo2 => $precioGrupo2,
            self::COLUMNA_iluminacionIncluida => $iluminacionIncluida,
            self::COLUMNA_precioMonedero => $precioMonedero,
            self::COLUMNA_precioSegundoMonedero => $precioSegundoMonedero,
            self::COLUMNA_numeroPedido => $numeroPedido,
        ));
            */

        $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($idPartido, $idJugador1);
        $PartidoJugador[PartidoJugador::COLUMNA_idReservaPista] = $idReservaPista;
        $PartidoJugador[$PartidoJugador::COLUMNA_importePago] = $importePagoJugador1;
        $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = $tipoPagoJugador1;
        $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = $aplazadoPagoJugador1;
        $PartidoJugador->guardar();

        if ($tipoPagoJugador1 == self::TIPOPAGOJUGADORRESERVA_MONEDERO){
            Monedero::pagarConMonedero($idJugador1, $idReservaPista, $importePagoJugador1, $aplazadoPagoJugador1);
        }

        $ReservaPista = new ReservaPista($idReservaPista);
        //$ReservaPista->actualizarGuardarTablaReservaEnCacheEnSegundoPlano();
        $ReservaPista->actualizarCacheReserva();

        return $idReservaPista;

    }

    static function registrarPagoTPV($idJugador, $importe, $numeroPedido, $registrarPago = self::REGISTRARPAGO_JUGADORRESERVA){
        global $bd;

        $fechaHoy = date("Y-m-d H:i:s");

        switch ($registrarPago){

            case self::REGISTRARPAGO_JUGADORRESERVA:
                $bd->update(self::TABLA_nombre,
                    array(
                        self::COLUMNA_idJugadorReserva => $idJugador,
                        self::COLUMNA_importePagoJugador1 => $importe,
                        self::COLUMNA_tipoPagoJugador1 => self::TIPOPAGOJUGADORRESERVA_TPV,
                        self::COLUMNA_pagadoJugador1 => 1,
                        self::COLUMNA_fechaPagoJugador1 => $fechaHoy
                    ),
                    array(self::COLUMNA_numeroPedido => $numeroPedido)
                );
                break;

            case self::REGISTRARPAGO_JUGADOR2:
                $bd->update(self::TABLA_nombre,
                    array(
                        self::COLUMNA_importePagoJugador2 => $importe,
                        self::COLUMNA_tipoPagoJugador2 => self::TIPOPAGOJUGADORRESERVA_TPV,
                        self::COLUMNA_pagadoJugador2 => 1,
                        self::COLUMNA_fechaPagoJugador2 => $fechaHoy
                    ),
                    array(self::COLUMNA_numeroPedido => $numeroPedido)
                );
                break;

            case self::REGISTRARPAGO_JUGADOR3:
                $bd->update(self::TABLA_nombre,
                    array(
                        self::COLUMNA_importePagoJugador3 => $importe,
                        self::COLUMNA_tipoPagoJugador3 => self::TIPOPAGOJUGADORRESERVA_TPV,
                        self::COLUMNA_pagadoJugador3 => 1,
                        self::COLUMNA_fechaPagoJugador3 => $fechaHoy
                    ),
                    array(self::COLUMNA_numeroPedido => $numeroPedido)
                );
                break;

            case self::REGISTRARPAGO_JUGADOR4:
                $bd->update(self::TABLA_nombre,
                    array(
                        self::COLUMNA_importePagoJugador4 => $importe,
                        self::COLUMNA_tipoPagoJugador4 => self::TIPOPAGOJUGADORRESERVA_TPV,
                        self::COLUMNA_pagadoJugador4 => 1,
                        self::COLUMNA_fechaPagoJugador4 => $fechaHoy
                    ),
                    array(self::COLUMNA_numeroPedido => $numeroPedido)
                );
                break;
        }




    }

    static function actualizarCacheTablaReservas($idCampo, $idPista, $fechaMYSQL)
    {

        Log::v(__FUNCTION__,"ID CAMPO:$idCampo | ID PISTA:$idPista | FECHA:$fechaMYSQL");


        $Pista = new Pista($idPista);


        $horaInicioMinimoCampo = HorarioPista::obtenerHoraInicioMinimoCampoDisponible($idCampo, $fechaMYSQL);
        $horaInicioCampo = strtotime("$horaInicioMinimoCampo");


        $horaFinMaximoCampo = HorarioPista::obtenerHoraFinMaximoCampoDisponible($idCampo, $fechaMYSQL);


        $strtotime_horaFinMediaNoche = strtotime("06:00");
        $strotime_horaInicioMinimoCampo = strtotime($horaInicioMinimoCampo);
        $strotime_horaFinMaximoCampo = strtotime($horaFinMaximoCampo);


        if ($strotime_horaFinMaximoCampo < $strtotime_horaFinMediaNoche) {
            $minutosDesdeAntesDeMediaNoche = (strtotime("23:59:00") - $strotime_horaInicioMinimoCampo) / 60;
            $minutosDesdeMediaNoche = abs(((strtotime("00:01:00") - $strotime_horaFinMaximoCampo) / 60));

            $minutos = $minutosDesdeAntesDeMediaNoche + $minutosDesdeMediaNoche;

            //Log::v(__FUNCTION__, "minutosDesdeAntesDeMediaNoche: $minutosDesdeAntesDeMediaNoche | minutosDesdeMediaNoche: $minutosDesdeMediaNoche");

        } else {
            $minutos = ($strotime_horaFinMaximoCampo - $strotime_horaInicioMinimoCampo) / 60;
            //Log::v(__FUNCTION__, "strotime_horaFinMaximoCampo: $strotime_horaFinMaximoCampo | strotime_horaInicioMinimoCampo: $strotime_horaInicioMinimoCampo | MINUTOS: $minutos");
        }


        //Log::v(__FUNCTION__, "HORA INICIO: $horaInicioMinimoCampo | HORA FIN: $horaFinMaximoCampo | MINUTOS: $minutos");


        $minutos = abs($minutos);
        $minutos = floor($minutos);
        $intervalos = round(($minutos / 30) + 2);

        //Log::v(__FUNCTION__, "MINUTOS: $minutos | INTERVALOS: $intervalos");

        $horaInicioCampo = strtotime("-30 minutes", $horaInicioCampo);


        $anterior_idReserva = "";
        $contador_reservasMostradas = 0;
        $numeroReservasEnPista = $Pista->obtenerNumeroDeReservasEnPista($fechaMYSQL);
        Log::v(__FUNCTION__, "Número de reservas en Pista: " . $numeroReservasEnPista);


        for ($i = 0; $i < $intervalos; $i++) {

            $horaAImprimir = date('G:i', $horaInicioCampo);


            $idPista_columna = $Pista->obtenerId();
            $nombrePista = $Pista->obtenerNombre();

            $existenReservasEnPista = true;
            if ($numeroReservasEnPista == 0 || $numeroReservasEnPista < $contador_reservasMostradas) {
                $existenReservasEnPista = false;
            }


            //Log::v(__FUNCTION__, "¿Existen Reservas?  FECHA: $fechaMYSQL | PISTA: $nombrePista | ¿EXISTE?: $existenReservasEnPista");

            if ($existenReservasEnPista == false) {
                $esPistaReservada = false;
                $esPistaDisponibleParaReservarPorTramo = $Pista->esPistaDisponibleParaReservar($fechaMYSQL, $horaAImprimir);
            }
            else{
                $esPistaReservada = $Pista->esPistaReservada($fechaMYSQL, $horaAImprimir);
                $esPistaDisponibleParaReservarPorTramo = $Pista->esPistaDisponibleParaReservar($fechaMYSQL, $horaAImprimir);
            }



            $idReservaPista = 0;
            if ($esPistaReservada){
                $idReservaPista = $Pista->obtenerReservaPista($fechaMYSQL, $horaAImprimir)->obtenerId();
                if ($anterior_idReserva != $idReservaPista){
                    $contador_reservasMostradas += 1;
                    $anterior_idReserva = $idReservaPista;
                }
            }
            else{
                $esPistaDisponible = $Pista->esPistaLibre($fechaMYSQL, $horaAImprimir);
            }

            CacheTablaReserva::generarCacheTablaReserva($idCampo, $idPista_columna, $fechaMYSQL, $horaAImprimir, $esPistaDisponible, $esPistaDisponibleParaReservarPorTramo, $esPistaReservada, $idReservaPista);


            $horaInicioCampo = strtotime("+30 minutes", $horaInicioCampo);

        }
    }

    static function generarCacheTablaReservaPistasEnFechaYHora($idPista, $fechaMYSQL, $hora, $numeroReservasEnPista="", $actualizarRedis=false, $actualizarRedisEsPistaReservada=-1){

        $hora = date('H:i:s', strtotime($hora));

        if ($actualizarRedisEsPistaReservada == -1){
            $actualizarRedisEsPistaReservada = $actualizarRedis;
        }

        $callStartTime = microtime(true);
        
        Log::v(__FUNCTION__, "generarCacheTablaReservaPistasEnFechaYHora($idPista, $fechaMYSQL, $hora, $numeroReservasEnPista, $actualizarRedis, $actualizarRedisEsPistaReservada)", false);

        // var_dump($idPista);
        $Pista = new Pista($idPista);
        $idCampo = $Pista->obtenerCampo()->obtenerId();
        $idPista_columna = $Pista->obtenerId();

        $callStartTime1 = microtime(true);
        if (empty($numeroReservasEnPista)){
            // var_dump("entra tiempo 1");
            $numeroReservasEnPista = $Pista->obtenerNumeroDeReservasEnPistaRedis($fechaMYSQL, $actualizarRedis);
        }
        $callEndTime1 = microtime(true);
        $callTime = $callEndTime1 - $callStartTime1;
        $tiempo='<br>El tiempo transcurrido 1 es de:  '.sprintf("%.4f",$callTime).' seconds';
        // echo  $tiempo;

       
        if ($numeroReservasEnPista > 0){
            // $callStartTime2 = microtime(true);
            $esPistaReservada = $Pista->esPistaReservadaRedis($fechaMYSQL, $hora, "", $actualizarRedisEsPistaReservada);
            // $callEndTime2 = microtime(true);
            // $callTime = $callEndTime2 - $callStartTime2;
            // $tiempo='<br>El tiempo transcurrido 2 es de:  '.sprintf("%.4f",$callTime).' seconds';
            // echo  $tiempo;


            // $callStartTime3 = microtime(true);
            $esPistaDisponibleParaReservarPorTramo = $Pista->esPistaDisponibleParaReservarRedis($fechaMYSQL, $hora, $actualizarRedisEsPistaReservada);

            

            
            // $callEndTime3 = microtime(true);
            // $callTime = $callEndTime3 - $callStartTime3;
            // $tiempo='<br>El tiempo transcurrido 3 es de:  '.sprintf("%.4f",$callTime).' seconds';
            // echo  $tiempo;

            $callStartTime4 = microtime(true);

            $idReservaPista = 0;
            if ($esPistaReservada){
                $ReservaPista = $Pista->obtenerReservaPistaRedis($fechaMYSQL, $hora, "", $actualizarRedisEsPistaReservada);
                if ($ReservaPista != null){
                    $idReservaPista = $ReservaPista->obtenerId();
                }
                else{
                    $esPistaReservada = false;
                }
            }

            $callEndTime4 = microtime(true);
            $callTime = $callEndTime4 - $callStartTime4;
            $tiempo='<br>El tiempo transcurrido 4 es de:  '.sprintf("%.4f",$callTime).' seconds';
            // echo  $tiempo;
            
            
            }
        else{
            $esPistaReservada = false;
            $esPistaDisponibleParaReservarPorTramo = true;
        }
        

        if ($esPistaReservada == false){
            $esPistaDisponible = $Pista->esPistaLibreRedis($fechaMYSQL, $hora, $actualizarRedis);
        }

        //carga de un archivo excel por ejemplo

        $a=CacheTablaReserva::generarCacheTablaReserva($idCampo, $idPista_columna, $fechaMYSQL, $hora, $esPistaDisponible, $esPistaDisponibleParaReservarPorTramo, $esPistaReservada, $idReservaPista);
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        $tiempo='<br>El tiempo transcurrido total es de:  '.sprintf("%.4f",$callTime).' seconds';
        // echo  $tiempo;
        return $a;
    }


    static function comprobar_pista_reservada_new($idPista,$hora_acortada,$array_horario_intervalos){
        if (isset($array_horario_intervalos[$idPista][$hora_acortada]) && $array_horario_intervalos[$idPista][$hora_acortada]!='' && $array_horario_intervalos[$idPista][$hora_acortada]!=null){
            $esPistaReservada =true;
        }else{
            $esPistaReservada =false;            
        }

        return $esPistaReservada;
    }

    static function generarCacheTablaReservaPistasEnFechaYHoraVersionTablaReservas($idPista, $fechaMYSQL, $hora, $array_horario_intervalos, $numeroReservasEnPista="", $actualizarRedis=false, $actualizarRedisEsPistaReservada=-1){


        $str_hora=strtotime($hora);
        $hora = date('H:i:s', $str_hora);
        $hora_acortada=date('H:i', $str_hora);


        if ($actualizarRedisEsPistaReservada == -1){
            $actualizarRedisEsPistaReservada = $actualizarRedis;
        }

        $callStartTime = microtime(true);
        
        Log::v(__FUNCTION__, "generarCacheTablaReservaPistasEnFechaYHoraVersionTablaReservas($idPista, $fechaMYSQL, $hora, $array_horario_intervalos, $numeroReservasEnPista, $actualizarRedis, $actualizarRedisEsPistaReservada)", false);

        // var_dump($idPista);
        $Pista = new Pista($idPista);
        $idCampo = $Pista->obtenerCampo()->obtenerId();
        $idPista_columna = $Pista->obtenerId();

        $callStartTime1 = microtime(true);
        if (empty($numeroReservasEnPista)){
            $numeroReservasEnPista = $Pista->obtenerNumeroDeReservasEnPistaRedis($fechaMYSQL, $actualizarRedis);
        }
        $callEndTime1 = microtime(true);
        $callTime = $callEndTime1 - $callStartTime1;
        $tiempo='<br>El tiempo transcurrido 1 es de:  '.sprintf("%.4f",$callTime).' seconds';
        // echo  $tiempo;

       
        if ($numeroReservasEnPista > 0){
            // $callStartTime2 = microtime(true);
            // $esPistaReservada = $Pista->esPistaReservadaRedis($fechaMYSQL, $hora, "", $actualizarRedisEsPistaReservada);
            $esPistaReservada=ReservaPista::comprobar_pista_reservada_new($idPista,$hora_acortada,$array_horario_intervalos);
            


            $callStartTime3 = microtime(true);
            $esPistaDisponibleParaReservarPorTramo = $Pista->esPistaDisponibleParaReservarRedis($fechaMYSQL, $hora, $actualizarRedisEsPistaReservada);

            

            
            $callEndTime3 = microtime(true);
            $callTime = $callEndTime3 - $callStartTime3;
            $tiempo='<br>El tiempo transcurrido 3 es de:  '.sprintf("%.4f",$callTime).' seconds';
            // echo  $tiempo;

            $callStartTime4 = microtime(true);

            $idReservaPista = 0;
            if ($esPistaReservada){
                //Versión antigua. Falta comprobar para dos fechas
                // $ReservaPista = $Pista->obtenerReservaPistaRedis($fechaMYSQL, $hora, "", $actualizarRedisEsPistaReservada);
                // var_dump($fechaMYSQL);
                // var_dump($hora);
                // var_dump($idPista);
                
                // var_dump($hora_acortada);
                // var_dump($array_horario_intervalos[$idPista][$hora_acortada]);
                // var_dump("este es 2");
                // var_dump($ReservaPista->id);
                // var_dump($ReservaPista->obtenerId());
                // die();
                
                //Versión antigua.
                // if ($ReservaPista != null){
                //     $idReservaPista = $ReservaPista->obtenerId();
                // }
                // else{
                //     $esPistaReservada = false;
                // }

                if (isset($array_horario_intervalos[$idPista][$hora_acortada]) && $array_horario_intervalos[$idPista][$hora_acortada]!='' && $array_horario_intervalos[$idPista][$hora_acortada]!=null){
                    $idReservaPista = $array_horario_intervalos[$idPista][$hora_acortada];
                }
                else{
                    $esPistaReservada = false;
                }
            }

            $callEndTime4 = microtime(true);
            $callTime = $callEndTime4 - $callStartTime4;
            $tiempo='<br>El tiempo transcurrido 4 es de:  '.sprintf("%.4f",$callTime).' seconds';
            // echo  $tiempo;
            
            
            }
        else{
            $esPistaReservada = false;
            $esPistaDisponibleParaReservarPorTramo = true;
        }
        

        if ($esPistaReservada == false){
            $esPistaDisponible = $Pista->esPistaLibreRedis($fechaMYSQL, $hora, $actualizarRedis);
        }

        //carga de un archivo excel por ejemplo

        $a=CacheTablaReserva::generarCacheTablaReserva($idCampo, $idPista_columna, $fechaMYSQL, $hora, $esPistaDisponible, $esPistaDisponibleParaReservarPorTramo, $esPistaReservada, $idReservaPista);
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        $tiempo='<br>El tiempo transcurrido total es de:  '.sprintf("%.4f",$callTime).' seconds';
        // echo  $tiempo;
        return $a;
    }


    static function eliminarCacheRedisTablaReservaParaElCampo($idCampo){
        global $Redis;
        $Redis->del($Redis->keys(WWWBASE."imprimirTablaReservasPistas($idCampo"."*"));
    }

    static function eliminarCacheRedisTablaReservaParaElCampoYFecha($idCampo, $fechaMYSQL, $idDeporte="",$comprobar_duplicado=false,&$array_keys_duplicados=array()){
        global $Redis;
       
        if($comprobar_duplicado){
            $key_set="imprimirTablaReservasPistas ".$idCampo."_".$fechaMYSQL;
            if(!array_key_exists($key_set,$array_keys_duplicados)){
                $array_keys_duplicados[$key_set]=true;
                $Redis->del($Redis->keys(WWWBASE."imprimirTablaReservasPistas($idCampo, $fechaMYSQL"."*"));
            }else{
                // var_dump("ya existe imprimirTablaReservasPistas ".$idCampo."_".$fechaMYSQL);
            }
        }else{
            // var_dump("antiguo comprobar imprimirTablaReservasPistas ".$idCampo."_".$fechaMYSQL);
            $Redis->del($Redis->keys(WWWBASE."imprimirTablaReservasPistas($idCampo, $fechaMYSQL"."*"));
        }
    }


    static function imprimirTablaReservasPistas($idCampo, $fechaMYSQL, $modoAdministrador=false, $modoInvitado=false, $idDeporte="", $conRedis=true, $devolverHmlt=true){

        global $Redis;
        $idioma = Sesion::obtenerIdioma();
        $keyRedis = WWWBASE."imprimirTablaReservasPistas($idCampo, $fechaMYSQL, $modoAdministrador, $modoInvitado, $idDeporte, $idioma)";
        $Campo = new Campo($idCampo);


        $start = microtime(true);


        Log::v(__FUNCTION__, "Con Redis: $conRedis -> imprimirTablaReservasPistas($idCampo, $fechaMYSQL, $modoAdministrador, $modoInvitado, $idDeporte)");
        if ($Redis->exists($keyRedis) && REDIS_RESERVAS_TABLA && $conRedis){
            Log::v(__FUNCTION__, "Desde Caché Redis", false);
            echo $Redis->get($keyRedis);
            return;
        }

        ob_start();


        switch ($Campo->obtenerNumeroPistas($idDeporte)){

            case 0:
                Traductor::traducir("No hay pistas");
                break;

            case 1:
                Log::v(__FUNCTION__, "Solo hay una pista para el campo");
                ?>
                <table class="table table-striped table-sm tabla_reservas" id="tablaReservas">
                    <thead>
                    <th class='sticky-top ocultarMovil columna_hora' style='width: 70px; background:white;'>
                        <button class='btn btn-primary px-3' onclick='onclick_limpiarCacheTablaReservas(1);' title='".Traductor::traducir("Limpiar caché de la tabla de reservas")."'><i class='fas fa-redo pr-2' aria-hidden='true'></i></button>
                    </th>

                    <?php
                    $fechaMYSQL_cabecera = $fechaMYSQL;
                    for ($i = 0; $i < 7; $i++){
                        $fechaMYSQL_cabeceraTexto = Fecha::fechaMYSQLATexto($fechaMYSQL_cabecera, "",false, true, false);
                        echo "<th class='sticky-top th-lg columna_pista th_columna' scope='col' style='background: black; color: white; text-align: center;'>$fechaMYSQL_cabeceraTexto</th>";
                        $fechaMYSQL_cabecera = Fecha::anadirDiasAFecha($fechaMYSQL_cabecera, 1);
                    }
                    ?>
                    </thead>

                    <tbody id="cuerpo_tablaReservas">
                    <tr>
                        <?php

                        $fechaMYSQL_cuerpo = $fechaMYSQL;
                        for ($i = 0; $i < 8; $i++) {

                            $imprimirSoloColumnaHoras = true;
                            $class_celda = "columna_hora";
                            if ($i > 0){
                                $imprimirSoloColumnaHoras = false;
                                $class_celda = "";

                            }
                            echo "<td class='td_tabla_TbodyTablaReservasPistasDelCampoPorDia $class_celda'><table class='tabla_TbodyTablaReservasPistasDelCampoPorDia' style='width: 100%'>";
                            ReservaPista::imprimirTbodyTablaReservasPistasDelCampo($idCampo, $fechaMYSQL_cuerpo, 7, $modoAdministrador, $devolverHmlt, $modoInvitado, $idDeporte, "", $imprimirSoloColumnaHoras);
                            echo "</table></td>";

                            if ($imprimirSoloColumnaHoras == false){
                                $fechaMYSQL_cuerpo = Fecha::anadirDiasAFecha($fechaMYSQL_cuerpo, 1);
                            }
                        }
                        ?>
                    </tr>
                    </tbody>
                </table>
                <?php
                break;

            default:
                ?>
                <table class="table table-striped table-sm tabla_reservas" id="tablaReservas">
                    <thead>
                    <?php
                    Pista::imprimirTodasLasPistas(Pista::IMPRIMIRTODASLASPISTAS_FORMATO_CABECERATABLA, $idCampo, $fechaMYSQL, "", false, false, "", $idDeporte);
                    ?>
                    </thead>

                    <tbody id="cuerpo_tablaReservas">
                    <tr>
                        <?php

                        echo "<td class='td_tabla_TbodyTablaReservasPistasDelCampoPorDia columna_hora'><table class='tabla_TbodyTablaReservasPistasDelCampoPorDia' style='width: 100%'>";
                        ReservaPista::imprimirTbodyTablaReservasPistasDelCampo($idCampo, $fechaMYSQL, 0, $modoAdministrador, $devolverHmlt, $modoInvitado, $idDeporte, "", true);
                        echo "</table></td>";

                        $array_idsPistas = $Campo->obtenerIdsPistas($idDeporte);
                        for ($i = 0; $i < count($array_idsPistas); $i++) {


                            echo "<td class='td_tabla_TbodyTablaReservasPistasDelCampoPorDia $class_celda'><table class='tabla_TbodyTablaReservasPistasDelCampoPorDia' style='width: 100%'>";
                            ReservaPista::imprimirTbodyTablaReservasPistasDelCampo($idCampo, $fechaMYSQL, 0, $modoAdministrador, $devolverHmlt, $modoInvitado, $idDeporte, $array_idsPistas[$i], false);
                            echo "</table></td>";
                        }
                        ?>
                    </tr>
                    </tbody>
                </table>
                <?php
                break;
        }

        $end = microtime(true);
        $time = $end-$start;
        Log::v(__FUNCTION__, "Tiempo: {$time}", true);



        $html = ob_get_clean();
        $Redis->set($keyRedis, $html);

        if ($devolverHmlt){
            echo  $html;
        }




    }

    static function imprimirTbodyTablaReservasPistasDelCampo($idCampo, $fechaMYSQL, $enNumeroDias, $modoAdministrador=false, $devolverHTML=false, $modoInvitado=false, $idDeporte="", $idPista="", $imprimirSoloColumnaHoras=true)
    {

        /*
        if ($devolverHTML){
            ob_start();
        }*/

        //self::actualizarCacheTablaReservas($idCampo, $fechaMYSQL);


        $array_ReservaPistaYaImprimidas = array();



        $Campo = new Campo($idCampo);
        if ($Campo->existeAlgunHorarioPistaParaElCampo($fechaMYSQL) == false){
            Interfaz::imprimirInfoMensajeError(Traductor::traducir("No existen Tramos Horarios para el día seleccionado"));
            return;
        }

        $ConfiguracionReservaPistas = $Campo->obtenerConfiguracionReservaPistas();


        if (!empty($idPista)){
            $array_Pistas[] = new Pista($idPista);
        }
        else{
            $array_Pistas = Pista::obtenerTodos($idCampo, false, $idDeporte);
        }


        $horaInicioMinimoCampo = HorarioPista::obtenerHoraInicioMinimoCampoDisponibleRedis($idCampo,$fechaMYSQL, $enNumeroDias, -1, $idDeporte);
        $horaInicioCampo = strtotime("$horaInicioMinimoCampo");

        $horaFinMaximoCampo = HorarioPista::obtenerHoraFinMaximoCampoDisponibleRedis($idCampo,$fechaMYSQL, $enNumeroDias, "", $idDeporte);



        $strtotime_horaFinMediaNoche = strtotime("06:00");
        $strotime_horaInicioMinimoCampo = strtotime($horaInicioMinimoCampo);
        $strotime_horaFinMaximoCampo = strtotime($horaFinMaximoCampo);


        if ($strotime_horaFinMaximoCampo < $strtotime_horaFinMediaNoche){
            $minutosDesdeAntesDeMediaNoche = (strtotime("23:59:00") - $strotime_horaInicioMinimoCampo)/60;
            $minutosDesdeMediaNoche = abs(((strtotime("00:01:00") - $strotime_horaFinMaximoCampo)/60));

            $minutos = $minutosDesdeAntesDeMediaNoche + $minutosDesdeMediaNoche;

            Log::v(__FUNCTION__, "minutosDesdeAntesDeMediaNoche: $minutosDesdeAntesDeMediaNoche | minutosDesdeMediaNoche: $minutosDesdeMediaNoche");

        }
        else{
            $minutos = ($strotime_horaFinMaximoCampo - $strotime_horaInicioMinimoCampo)/60;
            Log::v(__FUNCTION__, "strotime_horaFinMaximoCampo: $strotime_horaFinMaximoCampo | strotime_horaInicioMinimoCampo: $strotime_horaInicioMinimoCampo | MINUTOS: $minutos", false);
        }



        Log::v(__FUNCTION__, "HORA INICIO: $horaInicioMinimoCampo | HORA FIN: $horaFinMaximoCampo | MINUTOS: $minutos", false);



        $minutos = abs($minutos); $minutos = floor($minutos);
        $intervalos = round(($minutos / 30) + 2);

        Log::v(__FUNCTION__,"MINUTOS: $minutos | INTERVALOS: $intervalos");

        $horaInicioCampo = strtotime("-30 minutes", $horaInicioCampo);


        $callStartTime8 = microtime(true);

        $array_reservas_pistas=array();
        $array_reservas_pistas_hora_inicio=array();
        $array_horario_intervalos=array();
        $array_reservas_pistas_tramos_final=array();
        global $bd;
        // echo "<tr></tr><tr>";
        $horaInicioCampo = strtotime("$horaInicioMinimoCampo");
        // $horaConDosDigitos = date('H:i', $horaInicioCampo);
        // var_dump($horaConDosDigitos);
        // die();
        for ($i = 0; $i < $intervalos; $i++) {
            $horaConDosDigitos = date('H:i', $horaInicioCampo);
            foreach ($array_Pistas as $Pista) {
                $array_horario_intervalos[$Pista->id][$horaConDosDigitos]="";
            }
            $horaInicioCampo = strtotime("+15 minutes", $horaInicioCampo);
        }
        // var_dump($array_horario_intervalos);
        // die();
        foreach ($array_Pistas as $Pista) {
            $horaInicioCampo = strtotime("$horaInicioMinimoCampo");
                // SELECT id,horaInicioReserva,horaFinReserva FROM reservas_pista WHERE 1 and idPista = ".$Pista->id." AND fechaReserva = DATE('$fechaMYSQL') AND (";
            $sql_tramos="
                SELECT id,horaInicioReserva,horaFinReserva,fechaReserva FROM reservas_pista WHERE 1 and idPista = ".$Pista->id." AND fechaReserva = DATE('$fechaMYSQL') AND (";
            // for ($i = 0; $i < $intervalos; $i++) {
            //     // $horaAImprimir = date('G:i', $horaInicioCampo);
            //     $horaConDosDigitos = date('H:i', $horaInicioCampo);
            //     // var_dump($Pista->id);
            //     // var_dump("ejecucion");  
            //     if($i>0){
            //         $sql_tramos.=" OR ";
            //     }
            //     $sql_tramos.="(horaInicioReserva <= TIME('$horaConDosDigitos') AND IF(horaFinReserva = '00:00:00', '23:59:00', horaFinReserva) > TIME('$horaConDosDigitos'))";
                
            //     $horaInicioCampo = strtotime("+30 minutes", $horaInicioCampo);
            // }
            // $sql_tramos.=" )";
                $sql_tramos="
                SELECT id,horaInicioReserva,horaFinReserva,fechaReserva FROM reservas_pista WHERE 1 and idPista = ".$Pista->id." AND fechaReserva = DATE('$fechaMYSQL')";

            $filasReservasEnElMismoDia = $bd->query($sql_tramos)->fetchAll();

            // var_dump($sql_tramos);
            // var_dump($filasReservasEnElMismoDia);
        // die();                
            if(count($filasReservasEnElMismoDia)>0){
                
                foreach ($filasReservasEnElMismoDia as $key => $value) {
                    $array_reservas_pistas_hora_inicio[$Pista->id][$value["horaInicioReserva"]]=$value;
                }
                // var_dump($array_reservas_pistas_hora_inicio);
                // die();
                //Creamos los tramos de media hora en media hora
                
                foreach($array_reservas_pistas_hora_inicio[$Pista->id] as $kP => $vP){
                   $tiempo_inicio=strtotime($vP["horaInicioReserva"]);
                   $tiempo_fin=strtotime($vP["horaFinReserva"]);
                   // var_dump(date('H:i', $tiempo_fin));
                   // var_dump($vP["horaFinReserva"]);
                   
                   // die();
                   $centinela_tramo=0;
                   if($tiempo_inicio>$tiempo_fin){
                    // var_dump($vP);
                    //Puede ser un día más
                    // var_dump(date('ymdHis', $tiempo_fin));
                    // var_dump(date('ymdHis', $tiempo_inicio));
                    $pp=new DateTime($vP["fechaReserva"].'');
                    // var_dump($pp->format('Y-m-d'));
                    $dia_hora_inicio=new DateTime($pp->format('Y-m-d').' '.date('His', $tiempo_inicio));
                    // var_dump($dia_hora_inicio->format('Y-m-d H:i'));
                    $dia_hora_inicio=strtotime($dia_hora_inicio->format('Y-m-d H:i'));

                    $dia_hora_fin=new DateTime($pp->format('Y-m-d').' '.date('His', $tiempo_fin).' +1 day');
                    // var_dump($dia_hora_fin->format('Y-m-d H:i'));
                    $dia_hora_fin=strtotime($dia_hora_fin->format('Y-m-d H:i'));
                    // $dia_hora_inicio=new DateTime($pp->format('Y-m-d H:i').' '.date('His', $tiempo_inicio));
                    // var_dump($dia_hora_inicio);
                    
                    // var_dump($dia_hora_fin);
                    // var_dump($dia_hora_inicio-$dia_hora_fin);
                    // var_dump($dia_hora_fin-$dia_hora_inicio);
                    if(($dia_hora_fin-$dia_hora_inicio)>0){
                        $tiempo_inicio=$dia_hora_inicio;
                        $tiempo_fin=$dia_hora_fin;
                    }
                    // die();
                   }
                   while ($tiempo_fin>$tiempo_inicio) {
                        // var_dump(date('H:i', $tiempo_inicio));
                        // var_dump($vP["id"]);
                       $array_horario_intervalos[$Pista->id][date('H:i', $tiempo_inicio)]=$vP["id"];
                       $tiempo_inicio = strtotime("+15 minutes", $tiempo_inicio);
                        // break;
                   }

                }
            }
        }

        //Tramos generados correctamente $array_horario_intervalos
        // var_dump($array_horario_intervalos);
        // die();
        $tiempo_nuevo=0;
        $tiempo_antiguo=0;
        // var_dump($intervalos);
        $horaInicioCampo = strtotime("$horaInicioMinimoCampo");
        for ($i = 0; $i < $intervalos; $i++) {
            $alto_contenedorInformacionReserva = "40px";

            $horaAImprimir = date('G:i', $horaInicioCampo);
            $horaConDosDigitos = date('H:i', $horaInicioCampo);

            echo "<tr>";

            if ($imprimirSoloColumnaHoras){
                echo "<th class='ocultarMovil columna_hora'>$horaAImprimir</th>";
            }
            else{
                foreach ($array_Pistas as $Pista) {
                   
                    //carga de un archivo excel por ejemplo

                    
                    // var_dump($horaConDosDigitos);

                    $idPista_columna = $Pista->obtenerId();
                    // $callStartTimeP = microtime(true);
                    // $CacheTablaReserva = self::generarCacheTablaReservaPistasEnFechaYHora($idPista_columna, $fechaMYSQL, $horaConDosDigitos);
                    //carga de un archivo excel por ejemplo

                    // $callEndTimeP = microtime(true);
                    // $callTimeP = $callEndTimeP - $callStartTimeP;

                    
                    // $callStartTimeP1 = microtime(true);
                    $CacheTablaReserva = self::generarCacheTablaReservaPistasEnFechaYHoraVersionTablaReservas($idPista_columna, $fechaMYSQL, $horaConDosDigitos, $array_horario_intervalos);
                    //carga de un archivo excel por ejemplo

                    // $callEndTimeP1 = microtime(true);
                    // $callTimeP1 = $callEndTimeP1 - $callStartTimeP1;
                    //Antigua
                    

                    // $tiempo_nuevo.=$callTimeP1;
                    // $tiempo_antiguo.=$callTimeP;
                    // $tiempo='<br>Tiempo antiguo :  '.sprintf("%.4f",$callTimeP).' seconds.<br>Tiempo nuevo :  '.sprintf("%.4f",$callTimeP1).' seconds';
                    // echo  $tiempo;
                    $esPistaReservada = $CacheTablaReserva->esPistaReservada();
                    $esPistaDisponible = $CacheTablaReserva->esPistaLibre();
                    $esPistaDisponibleParaReservarPorTramo = $CacheTablaReserva->esPistaDisponibleParaReservar();

                    $style = "";
                    $textoInformativo = "";
                    $parametrosReserva = "";
                    $id_ReservaPista = 0;
                    $nombreJugadorJugador1 = "";
                    if ($esPistaReservada == true) {
                        //$ReservaPista = $Pista->obtenerReservaPista($fechaMYSQL, $horaAImprimir);
                        $ReservaPista = $CacheTablaReserva->obtenerReservaPista();
                        $id_ReservaPista = $ReservaPista->obtenerId();
                        //echo "ID RESERVA: $id_ReservaPista";
                        Log::v(__FUNCTION__, "MOSTRAR RESERVA ID:  ".$id_ReservaPista, false);

                        $duracionMinutos = $ReservaPista->obtenerDuracion(true);
                        $Partido = $ReservaPista->obtenerPartido();
                        $Jugador = $ReservaPista->obtenerJugador1();

                        $idReserva = $ReservaPista->obtenerId();
                        $nombreJugadorJugador1 = $Jugador->obtenerNombre();
                        $telefono = $Jugador->obtenerMovil();




                        //JMAM: Obtiene los parámetros de la reserva
                        $reservaRealizadaPorElClub = $ReservaPista->esReservaRealizadaPorClub();
                        $reservaModificadaPorElClub = $ReservaPista->esReservaModificadaPorClub();
                        $idPista = $ReservaPista->obtenerIdPista();
                        $idPartido = $ReservaPista->obtenerIdPartido();
                        $idLiga = $ReservaPista->obtenerLiga()->obtenerId();
                        $idLigaEsDeSuscripcion = $ReservaPista->obtenerLiga()->esSuscripcion();
                        $idTipoReserva = $ReservaPista->obtenerIdTipoReserva();
                        $fechaReserva = $ReservaPista->obtenerFechaReserva();
                        $horaInicio = $ReservaPista->obtenerHoraInicioReserva(true);
                        $horaFin = $ReservaPista->obtenerHoraFinReserva();
                        $descripcion = $ReservaPista->obtenerDescripcion();
                        $pinAcceso = $ReservaPista->obtenerNumeroPinAcceso();

                        if (!empty($descripcion)){
                            $nombreJugadorJugador1 = $descripcion;
                            $descripcion = "";
                        }


                        $enlaceCompartirPartido = $ReservaPista->obtenerPartido()->obtenerUrlEnlaceCompartirPorWhatsApp();

                        //JMAM: Obtiene los datos del Partido
                        $nivelMinimo = $Partido->obtenerNivelMinimo();
                        $nivelMaximo = $Partido->obtenerNivelMaximo();

                        if ($nivelMinimo != 0 && $nivelMaximo != 0 && $Partido->obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                            $informacionNivelPartido = Traductor::traducir("Nivel").": $nivelMaximo a $nivelMinimo";
                        }


                        //JMAM: Estados de la reserva
                        $iconosEstadoReserva = $ReservaPista->obtenerIconosEstadoReserva($modoAdministrador);

                        //JMAM: Estado de los pagos
                        $iconosJugadoresPagado = $ReservaPista->obtenerIconosJugadoresPagado($modoAdministrador);



                        $TipoReserva = new TipoReserva($idTipoReserva);
                        $nombreTipoReserva = $TipoReserva->obtenerNombre();

                        if ($modoAdministrador == false){
                            $nombreJugadorJugador1 = "";

                            if ($ReservaPista->obtenerNumeroJugadores() < $ReservaPista->obtenerNumeroJugadoresMaximoPermitidosReservaPista() && $ReservaPista->esPartidoPublico() && !$ReservaPista->esPartidoCompleto() && $ReservaPista->obtenerDeporte()->obtenerId() != Deporte::ID_padel){
                                $nombreJugadorJugador1 = $ReservaPista->obtenerDescripcion();
                                if (empty($descripcion)){
                                    //$descripcion = Traductor::traducir("Apuntarme");
                                }
                            }
                            else{
                                //$descripcion = Traductor::traducir("Reservado");
                            }

                            /*
                            if (Sesion::obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                                $descripcion = Traductor::traducir("Reservado");
                            }
                            else{
                                if ($ReservaPista->obtenerNumeroJugadores() < $ReservaPista->obtenerNumeroJugadoresMaximoPermitidosReservaPista()){
                                    $nombreJugadorJugador1 = $ReservaPista->obtenerDescripcion();
                                    if (empty($descripcion)){
                                        $descripcion = Traductor::traducir("Apuntarme");
                                    }
                                }
                                else{
                                    $descripcion = Traductor::traducir("COMPLETO");
                                }
                            }*/


                        }


                        $horaInicioReservaPista = date('G:i', strtotime($horaInicio));



                        $alto_contenedorInformacionReserva = (($duracionMinutos/30) * 40)."px";
                        /*
                        if ($alto_contenedorInformacionReserva < 60){
                            $alto_contenedorInformacionReserva = 60;
                        }
                        */


                        $textoInformativo = "
                            <div class='container-fluid contenedorInformacionReserva' style='background: $backgroundColor;' title='".Traductor::traducir($nombreTipoReserva)."'>
                                <div class='row' style='background: initial'>
                                    <div class='col-7 text-left'>".$horaInicioReservaPista."h</div>
                                     <div class='col-5 text-right'>".$duracionMinutos."m</div>
                                </div>
                                <div class='row' style='background: initial; display: grid'>
                                    <div class='col-md-12 text-left nombreJugador'>$nombreJugadorJugador1</div>
                                     <div class='col-md-12'>
                                        <div style='float: left'>$iconosEstadoReserva</div>
                                        <div style='float: left'>$descripcion</div>
                                        <div class='d-inline-flex' style='float: right'>$iconosJugadoresPagado</div>
                                     </div>
                                    <!--<div class='col-md-6 text-right'>$telefono</div>-->
                                   
                                </div>
                            </div>
                        
                        ";

                        //JMAM: Añade los parámetros de la reserva
                        $parametrosReserva = "
                            <input type='hidden' id='idLigaEsDeSuscripcion_$idReserva' value='$idLigaEsDeSuscripcion'>
                            <input type='hidden' id='reservaRealizadaPorElClub_$idReserva' value='$reservaRealizadaPorElClub'>
                            <input type='hidden' id='reservaModificadaPorElClub_$idReserva' value='$reservaModificadaPorElClub'>
                            <input type='hidden' id='idTipoReserva_$idReserva' value='$idTipoReserva'>
                            <input type='hidden' id='idPista_$idReserva' value='$idPista'>
                            <input type='hidden' id='idPartido_$idReserva' value='$idPartido'>
                            <input type='hidden' id='idLiga_$idReserva' value='$idLiga'>
                            <input type='hidden' id='idTipoReserva_$idReserva' value='$idTipoReserva'>
                            <input type='hidden' id='fechaReserva_$idReserva' value='$fechaReserva'>
                            <input type='hidden' id='horaInicioReserva_$idReserva' value='$horaInicio'>
                            <input type='hidden' id='horaFinReserva_$idReserva' value='$horaFin'>
                            <input type='hidden' id='enlaceCompartirPartido_$idReserva' value='$enlaceCompartirPartido'>
                            <input type='hidden' id='informacionNivelPartido_$idReserva' value='$informacionNivelPartido'>
                            <input type='hidden' id='pinAccesoReservaPista_$idReserva' value='$pinAcceso'>
                        ";

                        $numeroFilasOcupadas = ($duracionMinutos / 30);

                        $array_ReservaPistaYaImprimidas[] = $ReservaPista;

                        //JMAM: Estilos y Parámetros de la Celda para la Reserva Realizada


                        switch ($ReservaPista->obtenerTipoReserva()->obtenerId()){

                            case TipoReserva::ID_TIPORESERVA_NORMAL:
                                $class = "reservaNormal";
                                break;

                            case TipoReserva::ID_TIPORESERVA_OFERTA:
                                $class = "reservaNormal";
                                break;

                            case TipoReserva::ID_TIPORESERVA_ESCUELA:
                                $class = "reservaEscuela";
                                break;

                            case TipoReserva::ID_TIPORESERVA_CLASES:
                                $class = "reservaEscuela";
                                break;

                            case TipoReserva::ID_TIPORESERVA_REPETIDA:
                                $class = "reservaFija";
                                break;

                            case TipoReserva::ID_TIPORESERVA_BLOQUEO:
                                $class = "reservaBloqueada";
                                break;

                            case TipoReserva::ID_TIPORESERVA_DESBLOQUEO:
                                $class = "reservaNormal";
                                break;

                            case TipoReserva::ID_TIPORESERVA_MANUAL:
                                $class = "reservaNormal";
                                break;

                            case TipoReserva::ID_TIPORESERVA_SIN_RESERVA:
                                $class = "reservaSinReserva";
                                break;

                            default:
                                $class = "pistaSinOferta";
                                break;
                        }


                        if ($modoAdministrador){
                            $onclick = "onclick_mostrarDetallesReserva($id_ReservaPista)";
                        }
                        else{
                            if ($ReservaPista->obtenerNumeroJugadores() < $ReservaPista->obtenerNumeroJugadoresMaximoPermitidosReservaPista() && $ReservaPista->esPartidoPublico() && !$ReservaPista->esPartidoCompleto() && $ReservaPista->obtenerDeporte()->obtenerId() != Deporte::ID_padel){
                                $onclick = "modal_apuntarseJugadorReserva($id_ReservaPista, ".Sesion::obtenerJugador()->obtenerId().");";
                            }
                            else if ($ReservaPista->esPartidoCompleto()){
                                //$onclick = "onclick_mostrarMensajeMotivoNoSePuedeReservar(\"".Traductor::traducir("Lo sentimos, esta reserva es CERRADA y no permite nuevos jugadores")."\");";
                            }
                            else if ($ReservaPista->esPartidoPublico()){
                                $onclick = "";
                            }
                            else{
                                $onclick = "onclick_mostrarMensajeMotivoNoSePuedeReservar(\"".Traductor::traducir("Lo sentimos, esta reserva a superado ya el máximo de jugadores inscritos")."\");";
                            }
                            $class = "reservaNormal";
                        }
                        $title = Traductor::traducir("Ocupada");


                    } else if ($esPistaDisponible == true) {

                        $textoInformativo = "<div class='contenedor_horaImprimir'>$horaAImprimir</div>";

                        if ($esPistaDisponibleParaReservarPorTramo == false){
                            $title = Traductor::traducir("Libre pero sólo disponible para RESERVA MANUAL");
                            $class = "pistaLibrePeroNoPorTramo";

                        }
                        else{
                            $class = "pistaLibre";
                            $title = Traductor::traducir("Libre");

                        }

                        if ($modoAdministrador){
                            $onclick = "onclick_abrirPartido(\"$horaAImprimir\",$idPista_columna, \"$fechaMYSQL\")";
                        }
                        else if ($modoInvitado){
                            $onclick = "onclick_irALogin()";
                        }
                        else{
                            $onclick = "onclick_seleccionarPista($idPista_columna, \"$fechaMYSQL\", \"$horaConDosDigitos\")";
                        }
                        $numeroFilasOcupadas = 1;
                    }
                    else if ($esPistaDisponible == false) {
                        $textoInformativo = "<div class='contenedor_horaImprimir'>$horaAImprimir</div>";

                        $colorResaltadoPistaOcupada = $ConfiguracionReservaPistas->obtenerColorRestaladoPistaOcupada($idTipoReserva);
                        $class = "pistaOcupada";
                        if ($modoAdministrador){
                            $onclick = "onclick_abrirPartido(\"$horaAImprimir\",$idPista_columna, \"$fechaMYSQL\")";
                        }
                        else{
                            $onclick = "onclick_mostrarMensajeHorarioOcupado();";
                        }

                        $title =  Traductor::traducir("No Disponible");
                        $numeroFilasOcupadas = 1;
                    }


                    $imprimirCelda = true;
                    $apariciones = 0;
                    if ($id_ReservaPista > 0){
                        foreach ($array_ReservaPistaYaImprimidas as $ReservaPistaExistente) {

                            if ($ReservaPistaExistente->obtenerId() == $id_ReservaPista){
                                $apariciones++;
                            }
                        }

                        if ($apariciones > 1){
                            $imprimirCelda = false;
                        }
                    }


                    if ($imprimirCelda){
                        echo "
                        <td class='columna_pista $class' id='celdaReserva_$idReserva' style='$style; height: $alto_contenedorInformacionReserva' rowspan='$numeroFilasOcupadas' onclick='$onclick' title='$title'>
                            $textoInformativo
                            $parametrosReserva
                        </td>";
                    }

                    

                }
            }

            echo "</tr>";

            $horaInicioCampo = strtotime("+30 minutes", $horaInicioCampo);


        }

        // echo '<br>Tiempo antiguo total:  '.sprintf("%.4f",$tiempo_antiguo).' seconds.<br>Tiempo nuevo total:  '.sprintf("%.4f",$tiempo_nuevo).' seconds';

        // $callEndTime8 = microtime(true);
        // $callTime8 = $callEndTime8 - $callStartTime8;
        // $tiempo8='<br><br>TIEMPO:  '.sprintf("%.4f",$callTime8).' seconds.<br><br>';
        // echo  $tiempo8;
        /*
        if ($devolverHTML){
            return ob_get_clean();
        }*/
    }

    static function generarNumeroPedidoReservaPista($idLiga, $idCampo, $idPista, $idJugador, $fecha, $hora){

        $valorUnico = uniqid();
        $numeroPedido_sinCodificar = $idLiga.$idCampo.$idPista.$idJugador.$fecha.$hora.$valorUnico;

        return base64_encode($numeroPedido_sinCodificar);
    }

    static function generarFormularioInvisiblePagoReservaPista($idJugador, $idTarjeta, $importe, $numeroPedido, $apuntarNuevoJugador = 0){
        global $bd;

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ERROR );

        //require_once BASE."redsys/apiRedsys.php";


        $importe = $importe * 100;

        $urlMerchant=WWWBASE."modulo_reservas/registrar_pago_tpv.php";

        $url_tpvv=TPV_URL;
        $claveTPV=TPV_CLAVE;

        $code=TPV_CODIGO;
        $terminal='001';
        $order=date('ymdHis');
        $currency='978';
        $transactionType='0';
        $consumerlng='001';
        $versionTPV="HMAC_SHA256_V1";
        $nombreParaTPV='RadicalPadel';

        $token = "1";
        $URL_OK = WWWBASE."modulo_reservas/pago_realizado.php?pagoRealizado=1";
        $URL_NoOK = WWWBASE."modulo_reservas/pago_realizado.php?pagoRealizado=0";



        //JMAM: Obtener token tarjeta
        $tokenTarjeta = $bd->get("tarjetas_tokenizadas", "token", array("AND" => array("id_jugador" => $idJugador, "id" => $idTarjeta)));


        //JMAM: Comprueba si ha sido posible obtener el Token
        $ref_identificador = "REQUIRED";
        if ($tokenTarjeta != ""){
            $ref_identificador = $tokenTarjeta;
        }


        $merchantData = "$idJugador,$numeroPedido,$apuntarNuevoJugador";


        $miObj = new RedsysAPI;
        $miObj->setParameter("DS_MERCHANT_AMOUNT",$importe);
        $miObj->setParameter("DS_MERCHANT_CURRENCY",$currency);
        $miObj->setParameter("DS_MERCHANT_ORDER",$order);
        $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$code);
        $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
        $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$transactionType);
        $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$urlMerchant);
        $miObj->setParameter("DS_MERCHANT_URLOK",$URL_OK);
        $miObj->setParameter("DS_MERCHANT_URLKO",$URL_NoOK);
        $miObj->setParameter("DS_MERCHANT_MERCHANTNAME",$nombreParaTPV);
        $miObj->setParameter("DS_MERCHANT_MERCHANTDATA",$merchantData);
        $miObj->setParameter("DS_MERCHANT_CONSUMERLANGUAGE",$consumerlng);

        //NUEVO JM: este es el parametro para el pago con referencia
        $miObj->setParameter("Ds_Merchant_Identifier",$ref_identificador);

        $params = $miObj->createMerchantParameters();
        $signature = $miObj->createMerchantSignature($claveTPV);

        echo "

    <form id='formulario_realizarPago' action='$url_tpvv' target='_blank' method='POST' ENCTYPE='application/xwww-form-urlencoded' style='display: none'>

        <input type='hidden' name='Ds_SignatureVersion' value='$versionTPV'> 
        <input type='hidden' name='Ds_MerchantParameters' value='$params'> 
        <input type='hidden' name='Ds_Signature' value='$signature'> 

        <a onclick='onclick_realizarPago()'>
          Continuar
        </a>
    </form>

";


    }

    static function unirJugadorExternoAReal($idJugadorExterno, $idJugadorReal){

        global $bd;
        $bool_cambiadoJugadorExternoPorReal = false;
        $array_idsReservasPista = $bd->select(self::TABLA_nombre, self::COLUMNA_id, array("OR" => array(self::COLUMNA_idJugador1 => $idJugadorExterno, self::COLUMNA_idJugador2 => $idJugadorExterno, self::COLUMNA_idJugador3 => $idJugadorExterno, self::COLUMNA_idJugador4 => $idJugadorExterno)));
        //print_r($array_ReservasPista);

        foreach ($array_idsReservasPista as $idReservaPista) {

            $ReservaPista = new ReservaPista($idReservaPista);

            //JMAM: Buscar Jugadores Externos

            $idJugador1 = $ReservaPista->obtenerJugador1(true)->obtenerId();
            $idJugador2 = $ReservaPista->obtenerJugador2(true)->obtenerId();
            $idJugador3 = $ReservaPista->obtenerJugador3(true)->obtenerId();
            $idJugador4 = $ReservaPista->obtenerJugador4(true)->obtenerId();

            $tipoJugador1 = $ReservaPista->obtenerTipoJugador1();
            $tipoJugador2 = $ReservaPista->obtenerTipoJugador2();
            $tipoJugador3 = $ReservaPista->obtenerTipoJugador3();
            $tipoJugador4 = $ReservaPista->obtenerTipoJugador4();


            if ($tipoJugador1 == self::TIPOJUGADOR_EXTERNO){

                if ($idJugador1 == $idJugadorExterno){
                    $ReservaPista[self::COLUMNA_idJugador1] = $idJugadorReal;
                    $ReservaPista[self::COLUMNA_tipoJugador1] = self::TIPOJUGADOR_INTERNO;

                    $Partido = $ReservaPista->obtenerPartido();
                    if ($Partido->obtenerId() != ""){
                        $Partido[Partido::COLUMNA_idJugador1] = $idJugadorReal;
                        $Partido->guardar();
                    }

                    $ReservaPista->guardar();

                    $bool_cambiadoJugadorExternoPorReal = true;
                }
            }

            if ($tipoJugador2 == self::TIPOJUGADOR_EXTERNO){

                if ($idJugador2 == $idJugadorExterno){
                    $ReservaPista[self::COLUMNA_idJugador2] = $idJugadorReal;
                    $ReservaPista[self::COLUMNA_tipoJugador2] = self::TIPOJUGADOR_INTERNO;

                    $Partido = $ReservaPista->obtenerPartido();
                    if ($Partido->obtenerId() != ""){
                        $Partido[Partido::COLUMNA_idJugador2] = $idJugadorReal;
                        $Partido->guardar();
                    }

                    $ReservaPista->guardar();

                    $bool_cambiadoJugadorExternoPorReal = true;
                }
            }

            if ($tipoJugador3 == self::TIPOJUGADOR_EXTERNO){

                if ($idJugador3 == $idJugadorExterno){
                    $ReservaPista[self::COLUMNA_idJugador3] = $idJugadorReal;
                    $ReservaPista[self::COLUMNA_tipoJugador3] = self::TIPOJUGADOR_INTERNO;

                    $Partido = $ReservaPista->obtenerPartido();
                    if ($Partido->obtenerId() != ""){
                        $Partido[Partido::COLUMNA_idJugador3] = $idJugadorReal;
                        $Partido->guardar();
                    }

                    $ReservaPista->guardar();

                    $bool_cambiadoJugadorExternoPorReal = true;
                }
            }

            if ($tipoJugador4 == self::TIPOJUGADOR_EXTERNO){

                if ($idJugador4 == $idJugadorExterno){
                    $ReservaPista[self::COLUMNA_idJugador4] = $idJugador4;
                    $ReservaPista[self::COLUMNA_tipoJugador4] = self::TIPOJUGADOR_INTERNO;

                    $Partido = $ReservaPista->obtenerPartido();
                    if ($Partido->obtenerId() != ""){
                        $Partido[Partido::COLUMNA_idJugador4] = $idJugador1;
                        $Partido->guardar();
                    }

                    $ReservaPista->guardar();

                    $bool_cambiadoJugadorExternoPorReal = true;
                }
            }


        }

        return $bool_cambiadoJugadorExternoPorReal;

    }
    /*
     * @author JMAM
     *
     * Pemrite sincronizar los Ids de los Jugadores en la Reserva para que concuerde con el del partido
     */
    static function sincronizarJugadoresReservaPistaYPartido($simular = false){
        global $bd;

        $ids_reservasPista = $bd->query("SELECT id FROM ".self::TABLA_nombre." WHERE idJugador1 <> (SELECT id_Jugador1 FROM partidos WHERE id = reservas_pista.idPartido) OR idJugador2 <> (SELECT id_Jugador2 FROM partidos WHERE id = reservas_pista.idPartido) OR idJugador3 <> (SELECT id_Jugador3 FROM partidos WHERE id = reservas_pista.idPartido) OR idJugador4 <> (SELECT id_Jugador4 FROM partidos WHERE id = reservas_pista.idPartido)")->fetchAll();

        echo "<br/>Número de Reservas a Sincronizar: ".count($ids_reservasPista);
        foreach ($ids_reservasPista as $idReservaPista) {
            $idReservaPista = $idReservaPista['id'];
            $ReservaPista = new ReservaPista($idReservaPista);
            $Partido = $ReservaPista->obtenerPartido();
            $idPartido = $Partido->obtenerId();

            $idJugador1Reserva = $ReservaPista[self::COLUMNA_idJugador1];
            $idJugador2Reserva = $ReservaPista[self::COLUMNA_idJugador2];
            $idJugador3Reserva = $ReservaPista[self::COLUMNA_idJugador3];
            $idJugador4Reserva = $ReservaPista[self::COLUMNA_idJugador4];

            $idJugador1Partido = $Partido[Partido::COLUMNA_idJugador1];
            $idJugador2Partido = $Partido[Partido::COLUMNA_idJugador2];
            $idJugador3Partido = $Partido[Partido::COLUMNA_idJugador3];
            $idJugador4Partido = $Partido[Partido::COLUMNA_idJugador4];


            echo "
                <br/>Reserva Sincronizada: $idReservaPista
                <br/>ID PARTIDO: $idPartido
                 <ul>
                    <li>ID Jugador1: $idJugador1Reserva | $idJugador1Partido</li>
                    <li>ID Jugador2: $idJugador2Reserva | $idJugador2Partido</li>
                    <li>ID Jugador3: $idJugador3Reserva | $idJugador3Partido</li>
                    <li>ID Jugador4: $idJugador4Reserva | $idJugador4Partido</li>
                </ul>
               
        
        
        ";


            if ($ReservaPista->obtenerTipoJugador1() == self::TIPOJUGADOR_INTERNO){
                $ReservaPista[self::COLUMNA_idJugador1] = $Partido[Partido::COLUMNA_idJugador1];
            }
            if ($ReservaPista->obtenerTipoJugador2() == self::TIPOJUGADOR_INTERNO){
                $ReservaPista[self::COLUMNA_idJugador2] = $Partido[Partido::COLUMNA_idJugador2];
            }
            if ($ReservaPista->obtenerTipoJugador3() == self::TIPOJUGADOR_INTERNO){
                $ReservaPista[self::COLUMNA_idJugador3] = $Partido[Partido::COLUMNA_idJugador3];
            }
            if ($ReservaPista->obtenerTipoJugador4() == self::TIPOJUGADOR_INTERNO){
                $ReservaPista[self::COLUMNA_idJugador4] = $Partido[Partido::COLUMNA_idJugador4];
            }

            if ($simular == false){
                $ReservaPista->guardar();
            }

        }
    }

    static function obtenerNumeroDeReservasEnPista($idPista, $fechaMYSQL){
        global $bd;

        return $bd->count(self::TABLA_nombre, array("AND" => array(self::COLUMNA_idPista => $idPista, self::COLUMNA_fechaReserva => $fechaMYSQL)));
    }

    static function existenReservasEnPista($idPista, $fechaMYSQL="", $idReservaIgnorar=""){
        global $bd;

        $where["AND"][self::COLUMNA_idPista] = $idPista;

        if (!empty($fechaMYSQL)){
            $where["AND"][self::COLUMNA_fechaReserva] = $fechaMYSQL;
        }

        if (!empty($idReservaIgnorar)){
            $where["AND"][self::COLUMNA_id."[!]"] = $idReservaIgnorar;
        }

        $apariciones = $bd->count(self::TABLA_nombre, $where);

        if ($apariciones > 0){
            return true;
        }

        return false;
    }

    static function obtenerIdsReservasPistaReservadaEnTramo($idPista, $idTiempoReserva, $fechaMYSQL, $horaInicio, $idReservaPistaIgnorar = 0, $horaFin=""){

        $Pista = new Pista($idPista);

        if ($idTiempoReserva > 0){
            $TiempoReserva =  new TiempoReserva($idTiempoReserva);
            $horaFin = $TiempoReserva->obtenerHoraFinTiempoReserva($horaInicio);
        }

        $horaFin = anadirRestarMinutosAHora($horaFin, -30);

        $ReservaPistaHoraInicio = $Pista->obtenerReservaPista($fechaMYSQL, $horaInicio, $idReservaPistaIgnorar, true);
        $ReservaPistaHoraFin =  $Pista->obtenerReservaPista($fechaMYSQL, $horaFin, $idReservaPistaIgnorar, true);

        $array_idsReservasPistaReservadaEnTramo = array();
        if ($ReservaPistaHoraInicio != null && $ReservaPistaHoraInicio->existe()){
            $array_idsReservasPistaReservadaEnTramo[] = $ReservaPistaHoraInicio->obtenerId();
        }

        if ($ReservaPistaHoraFin != null && $ReservaPistaHoraFin->existe()){
            $array_idsReservasPistaReservadaEnTramo[] = $ReservaPistaHoraFin->obtenerId();
        }

        return $array_idsReservasPistaReservadaEnTramo;

    }

    static function pagarDevolverMonederoAJugadoresSiEsNecesario($idReservaPista, $pagadoJugador1, $pagadoJugador2, $pagadoJugador3, $pagadoJugador4, $aplazadoPagoJugador1, $aplazadoPagoJugador2, $aplazadoPagoJugador3, $aplazadoPagoJugador4, $importePagoJugador1, $importePagoJugador2, $importePagoJugador3, $importePagoJugador4){

        //Log::v(__FUNCTION__, "PAGADO JUGADOR1: $pagadoJugador1 | PAGADO JUGADOR2: $pagadoJugador2 | PAGADO JUGADOR3: $pagadoJugador3 | PAGADO JUGADOR4: $pagadoJugador4", true);

        //JMAM: Gestiona los Pagos
        $fechaHoy = date('Y-m-d');



        $ReservaPista = new ReservaPista($idReservaPista);
        $idJugador1 = $ReservaPista->obtenerJugador1()->obtenerId();
        $idJugador2 = $ReservaPista->obtenerJugador2()->obtenerId();
        $idJugador3 = $ReservaPista->obtenerJugador3()->obtenerId();
        $idJugador4 = $ReservaPista->obtenerJugador4()->obtenerId();

        Log::v(__FUNCTION__, "ID JUGADOR 1: $idJugador1 | ID JUGADOR 2: $idJugador2 | ID JUGADOR 3: $idJugador3 | ID JUGADOR 4: $idJugador4", false);

        //JMAM: Jugador 1
        if ($pagadoJugador1 == 1 && $aplazadoPagoJugador1 == 0){
            $esPagadoConMonedero = Monedero::pagarConMonedero($idJugador1, $idReservaPista, $importePagoJugador1, $aplazadoPagoJugador1);
            if ($esPagadoConMonedero == false){
                $ReservaPista = new ReservaPista($idReservaPista);
                $ReservaPista[ReservaPista::COLUMNA_importePagoJugador1] = $importePagoJugador1;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador1] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[ReservaPista::COLUMNA_pagadoJugador1] = 1;
                $ReservaPista[$ReservaPista::COLUMNA_fechaPagoJugador1] = $fechaHoy;
                $ReservaPista[$ReservaPista::COLUMNA_aplazadoPagoJugador1] = 0;
                $ReservaPista->guardar();
            }
        }
        else{
            $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador1, $idReservaPista);

            if ($esDevueltoPagoMonedero == false){
                $ReservaPista = new ReservaPista($idReservaPista);
                $ReservaPista[ReservaPista::COLUMNA_importePagoJugador1] = $importePagoJugador1;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador1] = "";
                $ReservaPista[ReservaPista::COLUMNA_pagadoJugador1] = 0;
                $ReservaPista[$ReservaPista::COLUMNA_fechaPagoJugador1] = "";
                $ReservaPista[$ReservaPista::COLUMNA_aplazadoPagoJugador1] = 0;
                $ReservaPista->guardar();
            }
        }

        //JMAM: Jugador 2
        if ($pagadoJugador2 == 1 && $aplazadoPagoJugador2 == 0){
            $esPagadoConMonedero = Monedero::pagarConMonedero($idJugador2, $idReservaPista, $importePagoJugador2, $aplazadoPagoJugador2);
            if ($esPagadoConMonedero == false){
                $ReservaPista = new ReservaPista($idReservaPista);
                $ReservaPista[ReservaPista::COLUMNA_importePagoJugador2] = $importePagoJugador2;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador2] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[ReservaPista::COLUMNA_pagadoJugador2] = 1;
                $ReservaPista[$ReservaPista::COLUMNA_fechaPagoJugador2] = $fechaHoy;
                $ReservaPista[$ReservaPista::COLUMNA_aplazadoPagoJugador2] = 0;
                $ReservaPista->guardar();
            }
        }
        else{
            $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador2, $idReservaPista);

            if ($esDevueltoPagoMonedero == false){
                $ReservaPista = new ReservaPista($idReservaPista);
                $ReservaPista[ReservaPista::COLUMNA_importePagoJugador2] = $importePagoJugador2;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador2] = "";
                $ReservaPista[ReservaPista::COLUMNA_pagadoJugador2] = 0;
                $ReservaPista[$ReservaPista::COLUMNA_fechaPagoJugador2] = "";
                $ReservaPista[$ReservaPista::COLUMNA_aplazadoPagoJugador2] = 0;
                $ReservaPista->guardar();
            }
        }

        //JMAM: Jugador3
        if ($pagadoJugador3 == 1 && $aplazadoPagoJugador3 == 0){
            $esPagadoConMonedero = Monedero::pagarConMonedero($idJugador3, $idReservaPista, $importePagoJugador3, $aplazadoPagoJugador3);
            if ($esPagadoConMonedero == false){
                $ReservaPista = new ReservaPista($idReservaPista);
                $ReservaPista[ReservaPista::COLUMNA_importePagoJugador3] = $importePagoJugador3;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador3] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[ReservaPista::COLUMNA_pagadoJugador3] = 1;
                $ReservaPista[$ReservaPista::COLUMNA_fechaPagoJugador3] = $fechaHoy;
                $ReservaPista[$ReservaPista::COLUMNA_aplazadoPagoJugador3] = 0;
                $ReservaPista->guardar();
            }
        }
        else{
            $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador3, $idReservaPista);

            if ($esDevueltoPagoMonedero == false){
                $ReservaPista = new ReservaPista($idReservaPista);
                $ReservaPista[ReservaPista::COLUMNA_importePagoJugador3] = $importePagoJugador3;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador3] = "";
                $ReservaPista[ReservaPista::COLUMNA_pagadoJugador3] = 0;
                $ReservaPista[$ReservaPista::COLUMNA_fechaPagoJugador3] = "";
                $ReservaPista[$ReservaPista::COLUMNA_aplazadoPagoJugador3] = 0;
                $ReservaPista->guardar();
            }
        }

        //JMAM: Jugador4
        if ($pagadoJugador4 == 1 && $aplazadoPagoJugador4 == 0){
            $esPagadoConMonedero = Monedero::pagarConMonedero($idJugador4, $idReservaPista, $importePagoJugador4, $aplazadoPagoJugador4);
            Log::v(__FUNCTION__, "PAGADO Jugador 4: $esPagadoConMonedero", false);

            if ($esPagadoConMonedero == false){
                $ReservaPista = new ReservaPista($idReservaPista);
                $ReservaPista[ReservaPista::COLUMNA_importePagoJugador4] = $importePagoJugador4;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador4] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[ReservaPista::COLUMNA_pagadoJugador4] = 1;
                $ReservaPista[$ReservaPista::COLUMNA_fechaPagoJugador4] = $fechaHoy;
                $ReservaPista[$ReservaPista::COLUMNA_aplazadoPagoJugador4] = 0;
                $ReservaPista->guardar();
            }

        }
        else{
            Log::v(__FUNCTION__, "Devolver Pago Jugador 4", false);
            $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador4, $idReservaPista);

            if ($esDevueltoPagoMonedero == false){
                $ReservaPista = new ReservaPista($idReservaPista);
                $ReservaPista[ReservaPista::COLUMNA_importePagoJugador4] = $importePagoJugador4;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador4] = "";
                $ReservaPista[ReservaPista::COLUMNA_pagadoJugador4] = 0;
                $ReservaPista[$ReservaPista::COLUMNA_fechaPagoJugador4] = "";
                $ReservaPista[$ReservaPista::COLUMNA_aplazadoPagoJugador4] = 0;
                $ReservaPista->guardar();
            }
        }
    }

    static function sumarHoras24H($hora1, $hora2){

        $horas = array($hora1,$hora2);
        foreach($horas as $h) {
            $parts = explode(":", $h);
            $total += $parts[2] + $parts[1]*60 + $parts[0]*3600;
        }
        $horaSumadas = gmdate("H:i", $total);


        return $horaSumadas;
    }

    static function sumarHoras($hora1,$hora2){

        $hora1=explode(":",$hora1);
        $hora2=explode(":",$hora2);
        $temp=0;

        //sumo segundos
        $segundos=(int)$hora1[2]+(int)$hora2[2];
        while($segundos>=60){
            $segundos=$segundos-60;
            $temp++;
        }

        //sumo minutos
        $minutos=(int)$hora1[1]+(int)$hora2[1]+$temp;
        $temp=0;
        while($minutos>=60){
            $minutos=$minutos-60;
            $temp++;
        }

        //sumo horas
        $horas=(int)$hora1[0]+(int)$hora2[0]+$temp;

        if($horas<10)
            $horas= '0'.$horas;

        if($minutos<10)
            $minutos= '0'.$minutos;

        if($segundos<10)
            $segundos= '0'.$segundos;

        $sum_hrs = $horas.':'.$minutos;

        return ($sum_hrs);

    }

    function obtenerIconosEstadoReserva($modoAdministrador=false){
        //JMAM: Estados de la reserva
        $iconosEstadoReserva = "";
        if ($modoAdministrador == true){
            if ($this->esPartidoPublico()){
                //$iconosEstadoReserva .= "<i class='fas fa-eye icono' style='color: green' title='".Traductor::traducir("Partido Publicado")."'></i>";
                $iconosEstadoReserva .= "<img class='icono' src='".WWWBASE."PCU/images/icon_partidoPublicado.png' title='".Traductor::traducir("Partido Publicado")."' style='margin-top:-3px'/>";

            }
            else{
                //$iconosEstadoReserva .= "<i class='fas fa-eye-slash icono' style='color: red' title='".Traductor::traducir("Partido NO Publicado")."'></i>";
                $iconosEstadoReserva .= "<img class='icono' src='".WWWBASE."PCU/images/icon_partidoNoPublicado.png' title='".Traductor::traducir("Partido NO Publicado")."' style='margin-top:-3px'/>";

            }

            if ($this->esPartidoCompleto()){
                //$iconosEstadoReserva .= "<i class='fas fa-lock icono' style='color: red' title='".Traductor::traducir("Partido Completo")."'></i>";
                $iconosEstadoReserva .= "<img class='icono' src='".WWWBASE."PCU/images/icon_partidoCerrado.png' title='".Traductor::traducir("Partido Cerrado")."' style='margin-top:-3px'/>";

            }
            else{
                //$iconosEstadoReserva .= "<i class='fas fa-unlock icono' style='color: green' title='".Traductor::traducir("Partido Abierto")."'></i>";
                $iconosEstadoReserva .= "<img class='icono' src='".WWWBASE."PCU/images/icon_partidoAbierto.png' title='".Traductor::traducir("Partido Abierto")."' style='margin-top:-3px'/>";
            }

            if ($this->obtenerIdTipoReserva() == TipoReserva::ID_TIPORESERVA_REPETIDA){
                $nombreTipoReserva = $this->obtenerTipoReserva()->obtenerNombre();
                //$iconosEstadoReserva .= "<i class='fas fa-thumbtack icono' style='color: green' title='".Traductor::traducir($nombreTipoReserva)."'></i>";
                $iconosEstadoReserva .= "<img class='icono' src='".WWWBASE."PCU/images/icon_reservaFija.png' title='".Traductor::traducir($nombreTipoReserva)."' style='margin-top:-3px'/>";
            }
            else if ($this->obtenerIdTipoReserva() == TipoReserva::ID_TIPORESERVA_BLOQUEO){
                $nombreTipoReserva = $this->obtenerTipoReserva()->obtenerNombre();
                $iconosEstadoReserva = "<i class='fas fa-link icono' style='color: green;' title='".Traductor::traducir($nombreTipoReserva)."'></i>";
            }
            else if ($this->obtenerIdTipoReserva() == TipoReserva::ID_TIPORESERVA_ESCUELA){
                $nombreTipoReserva = $this->obtenerTipoReserva()->obtenerNombre();
                $iconosEstadoReserva = "<i class='fas fa-link icono' style='color: green;' title='".Traductor::traducir("Bloqueado")."'></i>";
                $iconosEstadoReserva .= "<i class='fas fa-graduation-cap icono' style='color: green;' title='".Traductor::traducir($nombreTipoReserva)."'></i>";
            }


            if ($this->esTiempoReservaPersonalizado() && $this->obtenerIdTipoReserva() != TipoReserva::ID_TIPORESERVA_BLOQUEO && $this->obtenerIdTipoReserva() != TipoReserva::ID_TIPORESERVA_ESCUELA){
                //$iconosEstadoReserva .= "<i class='fas fa-pen icono' style='color: green; background:yellow' title='".Traductor::traducir("Reserva Personalizada")."'></i>";
                $iconosEstadoReserva .= "<img class='icono' src='".WWWBASE."PCU/images/icon_reservaPersonalizada.png' title='".Traductor::traducir("Reserva Personalizada")."' style='margin-top:-3px'/>";
            }
        }

        return $iconosEstadoReserva;
    }

    function esCorrectoPagosMonedero($enviarNotificacionPorEmail=true){
        return Monedero::esCorrectoPagosMonederoIdReserva($this->obtenerId(), $enviarNotificacionPorEmail);
    }

    function obtenerIconosJugadoresPagado($modoAdministrador=false){
        $iconosJugadoresPagado = "";

        if ($this->obtenerIdTipoReserva() == TipoReserva::ID_TIPORESERVA_BLOQUEO || $this->obtenerIdTipoReserva() == TipoReserva::ID_TIPORESERVA_ESCUELA){
            //JMAM: Reserva de bloqueo o clases no tienen jugadores
            return "";
        }

        if ($modoAdministrador == true){


            if ($this->obtenerPista()->obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                $numeroJugadoresNoPagado = $this->obtenerNumeroJugadoresNoPagado(false, true);
                $numeroJugadoresAplazadoPago = $this->obtenerNumeroJugadoresAplazadoPago();
                $numeroJugadoresBuscandoSustito = $this->obtenerNumeroJugadoresBuscandoSustituto();

                Log::v(__FUNCTION__, "Número Jugadores No Pagado: $numeroJugadoresNoPagado", false);

                if ($this->esPartidoPublico() == false || $this->esPartidoCompleto()){
                    //$iconoNoPagado = "<i class='fas fa-user-circle icono' style='color: green'></i>";
                    $iconoNoPagado = "<img class='icono' src='".WWWBASE."PCU/images/icon_jugadorApuntado.png' title='".Traductor::traducir("Jugador Apuntado")."' style='margin-top:-3px'/>";

                }
                else{
                    //$iconoNoPagado = "<i class='fas fa-user-circle icono' style='color: blue'></i>";
                    $iconoNoPagado = "<img class='icono' src='".WWWBASE."PCU/images/icon_jugadorNoApuntado.png' title='".Traductor::traducir("Jugador Apuntado")."' style='margin-top:-3px'/>";

                }

                for ($j = 0; $j < $numeroJugadoresBuscandoSustito; $j++){
                    //$iconosJugadoresPagado.= "<i class='fas fa-user-circle icono' style='color: red'></i>";
                    $iconosJugadoresPagado .= "<img class='icono' src='".WWWBASE."PCU/images/icon_jugadorBuscaSustituto.png' title='".Traductor::traducir("Jugador buscando sustituto")."' style='margin-top:-3px'/>";

                }


                for ($j = 0; $j < $numeroJugadoresNoPagado - $numeroJugadoresBuscandoSustito - $numeroJugadoresAplazadoPago; $j++){
                    $iconosJugadoresPagado.= $iconoNoPagado;
                }

                for ($j = 0; $j < $numeroJugadoresAplazadoPago; $j++){
                    //$iconosJugadoresPagado.= "<i class='fas fa-user-circle icono' style='color: red'></i>";
                    if ($this->esPartidoPublico() == false || $this->esPartidoCompleto()){
                        //$iconoNoPagado = "<i class='fas fa-user-circle icono' style='color: green'></i>";
                        $iconosJugadoresPagado .= "<img class='icono' src='".WWWBASE."PCU/images/icon_jugadorApuntado_pagoAplazado.png' title='".Traductor::traducir("Jugador Apuntado").". F".Traductor::traducir("Pago Aplazado")."' style='margin-top:-3px'/>";

                    }
                    else{
                        //$iconoNoPagado = "<i class='fas fa-user-circle icono' style='color: blue'></i>";
                        $iconosJugadoresPagado .= "<img class='icono' src='".WWWBASE."PCU/images/icon_jugadorNoApuntado_pagoAplazado.png' title='".Traductor::traducir("Jugador Apuntado").". ".Traductor::traducir("Pago Aplazado")."' style='margin-top:-3px'/>";

                    }

                }


                $numeroJugadoresPagado = $this->obtenerNumeroJugadoresPagado(false);
                for ($j = 0; $j < $numeroJugadoresPagado; $j++){

                    $title = Traductor::traducir("Pagado");
                    $style_border = "";
                    if ($j < $this->obtenerNumeroJugadoresAplazadoPago()){
                        $style_border = "border: solid 3px #e69809";
                        $title = Traductor::traducir("Aplazado");
                    }
                    $iconosJugadoresPagado.= "<img class='icono' src='".WWWBASE."PCU/images/icon_pagado.png' title='$title' style='margin-top:-3px; $style_border'/>";;
                }
            }
            else{
                $numeroTotalJugadores = $this->obtenerNumeroJugadores();
                if (!empty($numeroTotalJugadores)){
                    $iconosNumeroTotalJugadores = "<div class='icono_estadoPagoJugador' style='background: white; color:black; border-color:gray' title='".Traductor::traducir("Número total de Jugadores")."'>$numeroTotalJugadores</div>";
                }

                $numeroJugadoresPagado = $this->obtenerNumeroJugadoresPagado(false);
                if (!empty($numeroJugadoresPagado)){
                    $iconosJugadoresPagado = "<div class='icono_estadoPagoJugador' style='background: darkorange; color:white' title='".Traductor::traducir("Jugadores que han pagado")."'>$numeroJugadoresPagado</div>";
                }


                $iconosJugadoresPagado = $iconosNumeroTotalJugadores.$iconosJugadoresPagado;
            }


        }
        else{

            $iconosJugadoresPagado = "";


            if (Sesion::obtenerDeporte()->obtenerId() == Deporte::ID_padel){

                if (Sesion::obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                    //JMAM: Modo NO Administrador, visualiza las imágenes de los jugadores
                    $Jugador1 = $this->obtenerJugador1();
                    if ($Jugador1->existe()){
                        $urlFotoPerfilJugador1 = $Jugador1->obtenerUrlFotoPerfil();
                        $iconosJugadoresPagado .= "<img class='icono_fotoPerfilJugador' src='$urlFotoPerfilJugador1'/>";
                    }

                    $Jugador2 = $this->obtenerJugador2();
                    if ($Jugador2->existe() ){
                        $urlFotoPerfilJugador2 = $Jugador2->obtenerUrlFotoPerfil();
                        $iconosJugadoresPagado .= "<img class='icono_fotoPerfilJugador' src='$urlFotoPerfilJugador2'/>";
                    }

                    $Jugador3 = $this->obtenerJugador3();
                    if ($Jugador3->existe()){
                        $urlFotoPerfilJugador3 = $Jugador3->obtenerUrlFotoPerfil();
                        $iconosJugadoresPagado .= "<img class='icono_fotoPerfilJugador' src='$urlFotoPerfilJugador3'/>";
                    }

                    $Jugador4 = $this->obtenerJugador4();
                    if ($Jugador4->existe()){
                        $urlFotoPerfilJugador4 = $Jugador4->obtenerUrlFotoPerfil();
                        $iconosJugadoresPagado .= "<img class='icono_fotoPerfilJugador' src='$urlFotoPerfilJugador4'/>";
                    }
                }

            }
            else{
                $numeroTotalJugadores = $this->obtenerNumeroJugadores();
                if (!empty($numeroTotalJugadores) && Sesion::obtenerDeporte()->obtenerId() != Deporte::ID_padel){
                    $iconosJugadoresPagado .= "<div class='icono_estadoPagoJugador' style='background: white; color:black; border-color:gray' title='".Traductor::traducir("Número total de Jugadores")."'>$numeroTotalJugadores</div>";
                }
            }

            if ($this->obtenerNumeroJugadores() < $this->obtenerNumeroJugadoresMaximoPermitidosReservaPista() && $this->esPartidoPublico() && !$this->esPartidoCompleto() && $this->obtenerDeporte()->obtenerId() != Deporte::ID_padel){
                $iconosJugadoresPagado .= "<img class='icono_fotoPerfilJugador' src='".WWWBASE."images/icon_mas.png' title='".Traductor::traducir("Puedes Apuntarte")."'/>";
            }


        }

        return $iconosJugadoresPagado;

    }


    function obtenerIdsJugadores(){
        return PartidoJugador::obtenerIdsJugadoresPartido($this->obtenerIdPartido());
    }

    function obtenerIdsPartidoJugadores(){
        return PartidoJugador::obtenerIdsPartidoJugadoresDelPartido($this->obtenerIdPartido());
    }

    function obtenerIdPartidoJugadorPorNumeroJugador($numeroJugador){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorNumeroJugador($this->obtenerIdPartido(), $numeroJugador);
    }

    function imprimirTablaJugadoresModalReservaPista(){

        ?>

        <table class="table ">
            <thead>
            <tr>
                <th scope="col" colspan="2"><?php echo Traductor::traducir("Jugador");?></th>
                <th scope="col"><?php echo Traductor::traducir("Importe");?></th>
                <th scope="col"><?php echo Traductor::traducir("Pagado");?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $array_idsPartidoJugadores = $this->obtenerIdsPartidoJugadores();
            foreach ($array_idsPartidoJugadores as $idPartidoJugador){
                $PartidoJugador = new PartidoJugador($idPartidoJugador);
                $Jugador = $PartidoJugador->obtenerJugador();
                $idJugador = $Jugador->obtenerId();
                $numeroJugador = $PartidoJugador->obtenerNumeroJugador();
                $nombreJugador = $Jugador->obtenerNombre();
                $urlFotoPerfil = $Jugador->obtenerUrlFotoPerfil();
                $nivelJugador = $Jugador->obtenerTextoNivel(true);
                $telefonoJugador = $Jugador->obtenerTelefono();
                $importeMonederoJugador = $Jugador->obtenerImporteTotalJugadorEnIdMonederoEnIdClub($this->obtenerClub()->obtenerId(), $this->obtenerIdMonederoPagarReserva());

                $texto_importeMonederoJugador = "";
                if ($importeMonederoJugador > 0) {
                    $texto_importeMonederoJugador = $importeMonederoJugador . Traductor::traducir("€", false, $this->obtenerCampo()->obtenerCodigoPais());
                }

                $esImportePagadoJugadorEnLigaPack = $Jugador->esImportePagadoJugadorEnLigadePack($this->obtenerLiga()->obtenerId());

                $class_modal_imgFotoPerfilJugador = "";
                if ($esImportePagadoJugadorEnLigaPack){
                    $class_modal_imgFotoPerfilJugador = "importePagadoEnLigaDePack";
                }



                $importePago = $PartidoJugador->obtenerImportePago();
                $esPagado = $PartidoJugador->esPagadoJugador();

                $esAplazadoPago = $PartidoJugador->esAplazadoPagoJugador();
                $display_esAplazadoPago = "none";
                if ($esAplazadoPago){
                    $display_esAplazadoPago = "block";
                }

                $style_colorNombreJugador = "";
                if ($PartidoJugador->esBuscandoSustituto()){
                    $style_colorNombreJugador = "red";
                }



                ?>
                <tr id="fila_jugador<?php echo $numeroJugador;?>">
                    <td class="celda_imagen">
                        <div class="contenedor_modalFotoPerfilJugador">
                            <img id="modal_imgFotoPerfilJugador<?php echo $numeroJugador;?>" class="<?php echo $class_modal_imgFotoPerfilJugador;?>" src="<?php echo $urlFotoPerfil;?>" />
                        </div>
                    </td>
                    <td class="celda_nombre">
                        <div>
                            <a id="modal_telefonoJugador<?php echo $numeroJugador;?>" href="tel:<?php echo $telefonoJugador;?>">
                                <span class="nombreJugador" id="modal_nombreJugador<?php echo $numeroJugador;?>" style="color: <?php echo $style_colorNombreJugador;?>"><?php echo $nombreJugador;?></span>
                                <br/>
                                <i class="fas fa-phone icono_telefono"></i>
                                <span id="modal_nivelJugador<?php echo $numeroJugador;?>" class="modal_indicadorNivelJugador"><?php echo $nivelJugador;?></span>
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="input-group mb-3" style="width: 90px;">
                            <input id="modal_importePagoJugador<?php echo $numeroJugador;?>" name="importePagoJugador<?php echo $numeroJugador;?>" type="number" step="0.01" class="form-control" value="<?php echo $importePago;?>" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text"><?php echo Traductor::traducir("€", false, $this->obtenerCampo()->obtenerCodigoPais());?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group mb-3 modal_contenedorPagarReserva">
                            <div style="display: flex" id="modal_contenedorPagadoJugador<?php echo $numeroJugador;?>">
                                <div id="modal_iconoPagoAplazadoJugador<?php echo $numeroJugador;?>" class="icono_pagoAplazado" title="<?php echo Traductor::traducir("Este jugador ha Aplazado el Pago");?>" style="display: <?php echo $display_esAplazadoPago;?>"></div>
                                <input type="checkbox" class="modal_pagadoJugador" id="modal_pagadoJugador<?php echo $numeroJugador;?>" onchange="onchange_toggleButtonPagado(<?php echo $numeroJugador;?>, '<?php echo $idJugador;?>', this.checked);" name="pagadoJugador<?php echo $numeroJugador;?>" data-toggle="toggle" data-on="<?php echo Traductor::traducir("SI");?>" data-off="<?php echo Traductor::traducir("NO");?>" value="<?php echo $esPagado;?>">
                            </div>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <div id="modal_importeMonederoJugador<?php echo $numeroJugador;?>"><?php echo $texto_importeMonederoJugador;?> </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php

            }
            ?>
            </tbody>
        </table>
        <?php
    }

    function obtenerIdGrupoReserva(){
        return $this[self::COLUMNA_idGrupoReserva];
    }

    function esTiempoReservaPersonalizado(){

        if($this[self::COLUMNA_idTiempoReserva] == -1){
            return true;
        }

        return false;
    }

    function obtenerIdsReservasIdGrupoReserva(){
        global $bd;

        if (!empty($this->obtenerIdGrupoReserva())){
            return $bd->select(self::TABLA_nombre, self::COLUMNA_id, array(self::COLUMNA_idGrupoReserva => $this->obtenerIdGrupoReserva()));
        }

        return  array();

    }

    function obtenerTiempoReservaMaximoSinTenerEnCuentaDisponibilidad(){
        return HorarioPista::obtenerTiempoReservaMaximo($this->obtenerIdPista());
    }

    function comprobarIntegridadReserva($corregirReserva=true, $devolverMensajesError=true, $enviarEmail=true, $asunto="Reserva Incorrecta"){

        return true;

        $corregirReservaDuplicada = false;
        $corregirHoraFinReserva = false;
        $mensajeErrores = "";

        //JMAM: OBTENER DATOS DE LA RESERVA ////////////////////////////////////////////////////////////////////////////
        $idReservaPista = $this->obtenerId();
        $idPartido = $this->obtenerIdPartido();
        $idPista = $this->obtenerIdPista();
        $TipoReserva = $this->obtenerTipoReserva();
        $TiempoReservaMaximoPosible = $this->obtenerTiempoReservaMaximoSinTenerEnCuentaDisponibilidad();
        $duracionMaximaPosible = $TiempoReservaMaximoPosible->obtenerDuracion(true);
        $idTipoReserva = $TipoReserva->obtenerId();
        $nombreTipoReserva = $TipoReserva->obtenerNombre();
        $fechaReserva = $this->obtenerFechaReserva();
        $horaInicio = $this->obtenerHoraInicioReserva(true);
        $horaFin = $this->obtenerHoraFinReserva();
        $duracion = $this->obtenerDuracion(true);
        $Partido = $this->obtenerPartido();
        $fechaPartido = $Partido->obtenerFecha();
        $horaInicioPartido = $Partido->obtenerHora();

        $Jugador1_reserva = $this->obtenerJugador1(true);
        $Jugador2_reserva = $this->obtenerJugador2(true);
        $Jugador3_reserva = $this->obtenerJugador3(true);
        $Jugador4_reserva = $this->obtenerJugador4(true);

        $Jugador1_partido = $Partido->obtenerJugador1();
        $Jugador2_partido = $Partido->obtenerJugador2();
        $Jugador3_partido = $Partido->obtenerJugador3();
        $Jugador4_partido = $Partido->obtenerJugador4();


        if (self::esPistaReservada($idPista, $fechaReserva, $horaInicio, $idReservaPista)){
            $mensajeErrores .= "<br/>- Ya existe una reserva en la Pista ($idPista) para la Fecha  ($fechaReserva) y Hora Inicio ($horaInicio)";
            $corregirReservaDuplicada = true;
        }

        //JMAM: COMPROBAR LA INTEGRIDAD DE LA RESERVA //////////////////////////////////////////////////////////////////
        if ($horaInicio == $horaFin){
            $mensajeErrores .= "<br/>- La Hora de Inicio ($horaInicio H) y Hora Fin ($horaFin H) son iguales";
        }


        if (($idTipoReserva != TipoReserva::ID_TIPORESERVA_BLOQUEO &&  $idTipoReserva != TipoReserva::ID_TIPORESERVA_DESBLOQUEO && $idTipoReserva != TipoReserva::ID_TIPORESERVA_MANUAL)) {

            if ($this->esTiempoReservaPersonalizado() == false){
                if ($duracion > $duracionMaximaPosible){
                    $mensajeErrores .= "<br/>- La Duración ($duracion M) de la reserva es superior a la Duración Máxima posible (".$duracionMaximaPosible."M)";
                    $corregirHoraFinReserva = true;
                }
                else if ($duracion < 30){
                    $mensajeErrores .= "<br/>- La Duración ($duracion M) de la reserva es inferior a 30M";
                    $corregirHoraFinReserva = true;
                }
            }


            if ($idTipoReserva != TipoReserva::ID_TIPORESERVA_ESCUELA){
                if (empty($idPartido)){
                    //JMAM: Reserva no es de Tipo Bloquedo ni de Tipo Desbloqueo y no existe ID Partido
                    $mensajeErrores .= "<br/>- El ID del Partido ($idPartido) es incorrecto";
                }

                //JMAM: Comprobación de los datos del Partido y de la Reserva
                if ($fechaPartido != $fechaReserva){
                    $mensajeErrores .= "<br/>La Fecha de la Reserva ($fechaReserva) no se corresponde con la Fecha del Partido ($fechaPartido)";
                }

                if ($horaInicioPartido != $horaInicio){
                    $mensajeErrores .= "<br/>La Hora de la Reserva ($horaInicio H) no se corresponde con la Hora del Partido ($horaInicioPartido H)";
                }
            }


        }
        else{
            if ($duracion > 1020){
                $mensajeErrores .= "<br/>- La Duración ($duracion M) de la reserva es superior a 1020M";
                $corregirHoraFinReserva = true;
            }
            else if ($duracion < 30){
                $mensajeErrores .= "<br/>- La Duración ($duracion M) de la reserva es inferior a 30M";
                $corregirHoraFinReserva = true;
            }
        }


        //JMAM: Comprobación integridad Jugadores
        if ($this->obtenerTipoJugador1() != self::TIPOJUGADOR_EXTERNO && ($Jugador1_reserva->obtenerId() != $Jugador1_partido->obtenerId())){
            $textoInformativoJugadores = "Jugador 1 RESERVA: (".$Jugador1_reserva->obtenerId().") ".$Jugador1_reserva->obtenerNombre()." | Jugador 1 PARTIDO: (".$Jugador1_partido->obtenerId().") ".$Jugador1_partido->obtenerNombre();
            $mensajeErrores .= "<br/>- El Jugador 1 de La Reserva no coincide con el Jugador 1 del Partido -> [$textoInformativoJugadores]";
            $corregirIDJugador1 = true;
        }

        if ($this->obtenerTipoJugador2() != self::TIPOJUGADOR_EXTERNO && ($Jugador2_reserva->obtenerId() != $Jugador2_partido->obtenerId())){
            $textoInformativoJugadores = "Jugador 2 RESERVA: (".$Jugador2_reserva->obtenerId().") ".$Jugador2_reserva->obtenerNombre()." | Jugador 2 PARTIDO: (".$Jugador2_partido->obtenerId().") ".$Jugador2_partido->obtenerNombre();
            $mensajeErrores .= "<br/>- El Jugador 2 de La Reserva no coincide con el Jugador 2 del Partido -> [$textoInformativoJugadores]";
            $corregirIDJugador2 = true;
        }

        if ($this->obtenerTipoJugador3() != self::TIPOJUGADOR_EXTERNO && ($Jugador3_reserva->obtenerId() != $Jugador3_partido->obtenerId())){
            $textoInformativoJugadores = "Jugador 3 RESERVA: (".$Jugador3_reserva->obtenerId().") ".$Jugador3_reserva->obtenerNombre()." | Jugador 3 PARTIDO: (".$Jugador3_partido->obtenerId().") ".$Jugador3_partido->obtenerNombre();
            $mensajeErrores .= "<br/>- El Jugador 3 de La Reserva no coincide con el Jugador 3 del Partido -> [$textoInformativoJugadores]";
            $corregirIDJugador3 = true;
        }

        if ($this->obtenerTipoJugador4() != self::TIPOJUGADOR_EXTERNO && ($Jugador4_reserva->obtenerId() != $Jugador4_partido->obtenerId())){
            $textoInformativoJugadores = "Jugador 4 RESERVA: (".$Jugador4_reserva->obtenerId().") ".$Jugador4_reserva->obtenerNombre()." | Jugador 4 PARTIDO: (".$Jugador4_partido->obtenerId().") ".$Jugador4_partido->obtenerNombre();
            $mensajeErrores .= "<br/>- El Jugador 4 de La Reserva no coincide con el Jugador 4 del Partido -> [$textoInformativoJugadores]";
            $corregirIDJugador4 = true;
        }


        //JMAM CONTRUIR MENSAJE ERROR //////////////////////////////////////////////////////////////////////////////////
        if (!empty($mensajeErrores)){
            $id = $this->obtenerId();

            $Partido = $this->obtenerPartido();
            $JugadorApuntaResultado = $Partido->obtenerJugadorApuntaResultado();
            $idJugadorApuntaResultado = $JugadorApuntaResultado->obtenerId();
            $nombreJugadorApuntaResultado = $JugadorApuntaResultado->obtenerNombre(true);
            $fechaApuntaResultado = $Partido->obtenerFechaResultado();

            $Liga = $this->obtenerLiga();
            $idLiga = $Liga->obtenerId();
            $nombreLiga = $Liga->obtenerNombre();
            $urlEditarPartidoPCU = $Partido->obtenerURLEditarPartidoPCU();
            $Campo = $this->obtenerCampo();
            $idCampo = $Campo->obtenerId();
            $nombreCampo = $Campo->obtenerNombre();
            $Pista = $this->obtenerPista();
            $idPista = $Pista->obtenerId();
            $nombrePista = $Pista->obtenerNombre();

            $Jugador1 = $this->obtenerJugador1(true);
            $idJugador1 = $Jugador1->obtenerId();
            $nombreJugador1 = $Jugador1->obtenerNombre(true);

            $Jugador2 = $this->obtenerJugador2(true);
            $idJugador2 = $Jugador2->obtenerId();
            $nombreJugador2 = $Jugador2->obtenerNombre(true);

            $Jugador3 = $this->obtenerJugador3(true);
            $idJugador3 = $Jugador3->obtenerId();
            $nombreJugador3 = $Jugador3->obtenerNombre(true);

            $Jugador4 = $this->obtenerJugador4(true);
            $idJugador4 = $Jugador4->obtenerId();
            $nombreJugador4 = $Jugador4->obtenerNombre(true);

            $numeroJugadores = $this->obtenerNumeroJugadoresMaximoPermitidosReservaPista();


            $texto_esTiempoReservaPersonalizado = "NO";
            if ($this->esTiempoReservaPersonalizado()) {
                $texto_esTiempoReservaPersonalizado = "SI";
            }

            $texto_realizadaPorClub = "NO";
            if ($this->esReservaRealizadaPorClub()) {
                $texto_realizadaPorClub = "SI";
            }

            $texto_modificadaPorClub = "NO";
            if ($this->esReservaModificadaPorClub()) {
                $texto_modificadaPorClub = "SI";
            }

            $fechaUltimaModificacion = $this->obtenerFechaUltimaModificacion();


            $mensajeError = "
                        <hr/><br/>
                        <h3>".$_SERVER['HTTP_HOST']."</h3>
                        <a href='$urlEditarPartidoPCU'>Reserva Incorrecta: ($id)</a>
                        <ul>
                            <li>ID RESERVA: ($id)</li>
                            <li>ID PARTIDO: ($idPartido)</li>
                            <br/>
                            <li>Fecha RESERVA: $fechaReserva</li>
                            <li>Fecha PARTIDO: ($fechaPartido)</li>
                            <br/>
                            <li>Hora INICIO RESERVA: $horaInicio</li>
                            <li>Hora INICO PARTIDO: ($horaInicioPartido)</li>
                            <br/>
                            <li>Hora FIN RESERVA: $horaFin</li>
                            <li>¿Tiempo Reserva Personalizado?: $texto_esTiempoReservaPersonalizado</li>
                            <li>Duración RESERVA: " . $duracion . "M</li>
                            <li>Duración MÁXIMA POSIBE: (" . $duracionMaximaPosible . "M)</li>
                            <br/>
                            <li>Liga: ($idLiga) $nombreLiga </li>
                            <li>Campo: ($idCampo) $nombreCampo</li>
                            <li>Pista: ($idPista) $nombrePista</li>
                            <br/>
                            <li>Tipo Reserva ($idTipoReserva) $nombreTipoReserva</li>
                            <br/>
                            <li>¿Realizada por Club?: $texto_realizadaPorClub</li>
                            <li>¿Modificada por Club?: $texto_modificadaPorClub</li>
                            <br/>
                            <li>Fecha Último Registro Reserva: $fechaUltimaModificacion</li>
                            <br/>
                            <li>Número Jugadores Reserva: $numeroJugadores</li>
                            <li>Jugador 1 RESERVA: ($idJugador1) $nombreJugador1</li>
                            <li>Jugador 1 PARTIDO: (".$Jugador1_partido->obtenerId().") ".$Jugador1_partido->obtenerNombre()."</li>
                            <br/>
                            <li>Jugador 2 RESERVA: ($idJugador2) $nombreJugador2</li>
                            <li>Jugador 2 PARTIDO: (".$Jugador2_partido->obtenerId().") ".$Jugador2_partido->obtenerNombre()."</li>
                            <br/>
                            <li>Jugador 3 RESERVA: ($idJugador3) $nombreJugador3</li>
                            <li>Jugador 3 PARTIDO: (".$Jugador3_partido->obtenerId().") ".$Jugador3_partido->obtenerNombre()."</li>
                            <br/>
                            <li>Jugador 4 RESERVA: ($idJugador4) $nombreJugador4</li>
                            <li>Jugador 4 PARTIDO: (".$Jugador4_partido->obtenerId().") ".$Jugador4_partido->obtenerNombre()."</li>
                            <br/>
                            <li>Jugador Apunta Resultado: ($idJugadorApuntaResultado) $nombreJugadorApuntaResultado</li>
                            <li>Fecha Apunta Resultado: $fechaApuntaResultado</li>
                        </ul>
                        ";
        }


        //JMAM CORREGIR RESERVAS ///////////////////////////////////////////////////////////////////////////////////////
        $mensajesCorreciones="";
        if ($corregirReserva) {

            $mensajesCorreciones .= "</br></br><b>----- CORRECCIONES APLICADAS-----</b>";

            if ($corregirHoraFinReserva) {
                $mensajesCorreciones .= "</br></br><b>CORREGIR HORA FIN RESERVA</b>";

                $horaInicio = $this->obtenerHoraInicioReserva();
                $duracionMaximaPosible_minutos = $this->obtenerTiempoReservaMaximoSinTenerEnCuentaDisponibilidad()->obtenerDuracion(true);
                $duracionMaximaPosible_hora = $this->obtenerTiempoReservaMaximoSinTenerEnCuentaDisponibilidad()->obtenerDuracion();

                //JMAM: Comrpueba si la dureción es más o igual que 90 minutos
                if ($duracionMaximaPosible_minutos >= 90){
                    //JMAM: Establece 90 minutos por defecto
                    $horaFin = self::sumarHoras24H($horaInicio, "1:30");
                }
                else{
                    //JMAM: La duración máxima es menor que la duración por defecto en caso de error
                    //JMAM: Establece la duración máxima posible (inferior a la duración por defecto) a la reserva
                    $horaFin = self::sumarHoras24H($horaInicio, $duracionMaximaPosible_hora);
                }


                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_horaFinReserva] = $horaFin;
                $ReservaPista->guardar();
                $ReservaPista->actualizarCacheReserva();

                $mensajesCorreciones .= "</br>SE HA CORREGIDO LA HORA FIN DE RESERVA";
                $mensajesCorreciones .= "<br/>- Hora Fin RESERVA: $horaFin.";
                $mensajesCorreciones .= "</br/><div style='background: red'>" . $ReservaPista->comprobarIntegridadReserva(false, true, false)."</div>";

            }

            if ($corregirReservaDuplicada){
                $mensajesCorreciones .= "</br></br><b>CORREGIR RESERVA DUPLICADA</b>";

                $Partido->eliminar();
                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista->eliminar();

                $mensajesCorreciones .= "</br>SE HA CORREGIDO LA RESERVA DUPLICADA";
                $mensajesCorreciones .= "<br/>- ID Partido Eliminado: $idPartido";
                $mensajesCorreciones .= "<br/>- ID Reserva Eliminado: $id";

            }



            if ($corregirIDJugador1){
                $mensajesCorreciones .= "</br></br><b>CORREGIR ID JUGADOR 1</b>";

                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_idJugador1] = $Jugador1_partido->obtenerId();
                $ReservaPista->guardar();

                $mensajesCorreciones .= "</br>SE HA CORREGIDO EL ID JUGADOR 1";
                $mensajesCorreciones .= "<br/>- ID JUGADOR 1 RESERVA: ".$Jugador1_partido->obtenerId();
                $mensajesCorreciones .= "<br/>- ID JUGADOR 1 PARTIDO: ".$Jugador1_partido->obtenerId();

            }

            if ($corregirIDJugador2){
                $mensajesCorreciones .= "</br></br><b>CORREGIR ID JUGADOR 2</b>";

                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_idJugador2] = $Jugador2_partido->obtenerId();
                $ReservaPista->guardar();

                $mensajesCorreciones .= "</br>SE HA CORREGIDO EL ID JUGADOR 2";
                $mensajesCorreciones .= "<br/>- ID JUGADOR 2 RESERVA: ".$Jugador2_partido->obtenerId();
                $mensajesCorreciones .= "<br/>- ID JUGADOR 2 PARTIDO: ".$Jugador2_partido->obtenerId();

            }

            if ($corregirIDJugador3){
                $mensajesCorreciones .= "</br></br><b>CORREGIR ID JUGADOR 3</b>";

                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_idJugador3] = $Jugador3_partido->obtenerId();
                $ReservaPista->guardar();

                $mensajesCorreciones .= "</br>SE HA CORREGIDO EL ID JUGADOR 3";
                $mensajesCorreciones .= "<br/>- ID JUGADOR 3 RESERVA: ".$Jugador3_partido->obtenerId();
                $mensajesCorreciones .= "<br/>- ID JUGADOR 3 PARTIDO: ".$Jugador3_partido->obtenerId();

            }

            if ($corregirIDJugador4){
                $mensajesCorreciones .= "</br></br><b>CORREGIR ID JUGADOR 4</b>";

                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_idJugador4] = $Jugador4_partido->obtenerId();
                $ReservaPista->guardar();

                $mensajesCorreciones .= "</br>SE HA CORREGIDO EL ID JUGADOR 4";
                $mensajesCorreciones .= "<br/>- ID JUGADOR 4 RESERVA: ".$Jugador4_partido->obtenerId();
                $mensajesCorreciones .= "<br/>- ID JUGADOR 4 PARTIDO: ".$Jugador4_partido->obtenerId();

            }
        }



        //JMAM: ENVIAR EMAILS //////////////////////////////////////////////////////////////////////////////////////////
        if ($enviarEmail && (!empty($mensajeErrores) || !empty($mensajesCorreciones))){
            $mensajeEmail = $mensajeError.$mensajeErrores.$mensajesCorreciones;

            Email::enviarEmail("desarrollo@narixasoft.es", $asunto, $mensajeEmail);
            Email::enviarEmail("info@radicalpadel.com", $asunto, $mensajeEmail);
            Email::enviarEmail(Email::EMAIL_ADMINISTRACION, $asunto, $mensajeEmail);

            if ($this->obtenerPartido()->obtenerIdLiga() == 366){
                Email::enviarEmail("info@jugarmaspadel.com", $asunto, $mensajeEmail);
            }
        }





        //JMAM: DEVOLVER RESULTADO /////////////////////////////////////////////////////////////////////////////////////
        if ($devolverMensajesError){
            return $mensajeError.$mensajeErrores.$mensajesCorreciones;
        }
        else{
            if (empty($mensajeErrores) && empty($mensajesCorreciones)){
                return true;
            }
            else{
                return false;
            }
        }
    }

    function existe(){

        if ($this == null){
            return false;
        }


        $id = $this->obtenerId();
        if ($id > 0){
            return true;
        }

        return false;
    }

    function obtenerNombreDescriptivo(){
        $nombreClub = $this->obtenerClub()->obtenerNombre();
        $nombreLiga = $this->obtenerLiga()->obtenerNombre(true);
        $nombreCampo = $this->obtenerCampo()->obtenerNombre();
        $nombrePista = $this->obtenerPista()->obtenerNombre();

        $fecha = $this->obtenerFechaReserva();
        $hora = $this->obtenerHoraInicioReserva();
        $duracion = $this->obtenerDuracion(true);

        $fechaATexto = fechaATexto($fecha, $hora, false);


        return "$fechaATexto, $duracion m, en Pista $nombrePista";
    }

    function obtenerId(){
        return $this["id"];
    }

    function obtenerIdPista(){
        return $this["idPista"];
    }

    function obtenerPista(){
        return new Pista($this->obtenerIdPista());
    }

    function obtenerDeporte(){
        return $this->obtenerPista()->obtenerDeporte();
    }

    function obtenerIdPartido(){
        return $this[self::COLUMNA_idPartido];
    }

    function obtenerPartido($tambienEliminados=false){

        if ($tambienEliminados){
            $idPartido = (new Partido($this->obtenerIdPartido()))->obtenerId();

            if ($idPartido > 0){
                return new Partido($idPartido);
            }
            else{
                return new PartidoEliminado($this->obtenerIdPartido());
            }
        }
        else{
            return new Partido($this->obtenerIdPartido());
        }

    }

    function obtenerLiga(){
        $idLiga =  $this->obtenerPartido()->obtenerLiga()->obtenerId();

        if ($idLiga > 0){
            return new Liga($idLiga);
        }
        else{
            return $this->obtenerClub()->obtenerLigaMasActiva();
        }
    }

    function obtenerIdTipoReserva(){
        $idTipoReserva =  $this[self::COLUMNA_idTipoReserva];

        if ($idTipoReserva == 0 || $idTipoReserva == TipoReserva::ID_TIPORESERVA_MANUAL){
            $idTipoReserva = TipoReserva::ID_TIPORESERVA_NORMAL;
        }


        return $idTipoReserva;
    }

    function obtenerTipoReserva(){
        return new TipoReserva($this->obtenerIdTipoReserva());
    }

    function obtenerDescripcion(){
        return $this[self::COLUMNA_descripcion];
    }

    function obtenerCampo(){

        $idPartido = $this->obtenerIdPartido();

        if ($idPartido > 0){
            return $this->obtenerPartido()->obtenerCampo();
        }
        else{
            return new Campo($this[self::COLUMNA_idCampo]);
        }


    }

    function obtenerClub(){
        return $this->obtenerPista()->obtenerCampo()->obtenerClub();
    }

    function obtenerNombreMonederoPagarReserva($idCampo, $idTipoReserva=""){

        return Monedero::obtenerTextoPorIdMonedero($this->obtenerIdMonederoPagarReserva($idCampo, $idTipoReserva), $idCampo);
    }

    function obtenerIdMonederoPagarReserva($idCampo="", $idTipoReserva=""){

        if (empty($idTipoReserva)){
            $idTipoReserva = $this->obtenerTipoReserva()->obtenerId();
        }

        if (empty($idCampo)){
            $idTipoReservaSegundoMonedero = $this->obtenerConfiguracionReservaPistas()->obtenerIdTipoReservaSegundoMonedero();
        }
        else{
            $idTipoReservaSegundoMonedero = (new Campo($idCampo))->obtenerConfiguracionReservaPistas()->obtenerIdTipoReservaSegundoMonedero();
        }





        if ($idTipoReserva == $idTipoReservaSegundoMonedero && !empty($idTipoReservaSegundoMonedero)){
            return Monedero::IDMONEDERO_segundoMonedero;
        }
        else{
            return Monedero::IDMONEDERO_primerMonedero;
        }
    }

    function obtenerNumeroJugador($idJugador){
        return $this->obtenerPartido()->obtenerNumeroJugador($idJugador);
    }

    function actualizarCacheReserva($forzarPistaReservadaEntreHoraInicioYHoraFin=true,$comprobar_duplicado=false,&$array_keys_duplicados=array()){
        Log::v(__FUNCTION__,"actualizarCacheReserva", false);
        $idReservaPista = $this->obtenerId();
        $idCampo = $this->obtenerCampo()->obtenerId();
        $Pista = $this->obtenerPista();
        $idPista = $Pista->obtenerId();
        $Deporte = $Pista->obtenerDeporte();
        $fechaMYSQL = $this->obtenerFechaReserva();
        $horaInicio = $this->obtenerHoraInicioReserva();
        $horaFin = $this->obtenerHoraFinReserva();        

        CacheTablaReserva::actualizarCacheReserva($idCampo, $idPista, $fechaMYSQL, $horaInicio, $horaFin, $forzarPistaReservadaEntreHoraInicioYHoraFin, $idReservaPista,$Deporte->obtenerId(),$comprobar_duplicado,$array_keys_duplicados);

        /*
        Log::v(__FUNCTION__, "Actualizar la caché de la RESERVA: ".$this->obtenerId());

        $existeReservaPistaEnCache = CacheTablaReserva::existeIdReservaPistaEnCache($this->obtenerId());
        if ($existeReservaPistaEnCache){
            Log::v(__FUNCTION__, "EXISTE caché de la RESERVA: ".$this->obtenerId());
            $Pista = CacheTablaReserva::obtenerPistaReservaPistaEnCache($this->obtenerId());
            $fechaMYSQL = CacheTablaReserva::obtenerFechaReservaPistaEnCache($this->obtenerId());
            $hora = CacheTablaReserva::obtenerHoraReservaPistaEnCache($this->obtenerId());
        }



        CacheTablaReserva::eliminarDeCacheIdReservaPista($this->obtenerId());
        CacheTablaReserva::guardarActualizarCacheTablaReservaEnSegundoPlano($this->obtenerPista()->obtenerCampo()->obtenerId(), $this->obtenerIdPista(), $this->obtenerFechaReserva());


        if ($actualizarCacheEnSegundoPlano == false && $existeReservaPistaEnCache && ($Pista->obtenerId() != $this->obtenerPista()->obtenerId() || $fechaMYSQL != $this->obtenerFechaReserva() || $hora != $this->obtenerHoraInicioReserva(false, true))){
            //JMAM: Existe Reserva en Caché y guardada, pero su datos son distintos a la reserva actual

            //JMAM: Actualizar la caché correspondiente
            Log::v(__FUNCTION__, "Se ha encontrado reserva en otra pista/fecha/hora, actualizando caché: $hora | ".$this->obtenerHoraInicioReserva(false, true));
            self::actualizarCacheTablaReservas($Pista->obtenerCampo()->obtenerId(), $Pista->obtenerId(), $fechaMYSQL);
        }

        self::actualizarCacheRedisTablaReserva($this->obtenerCampo()->obtenerId(), $this->obtenerFechaReserva(), $this->obtenerPista()->obtenerDeporte()->obtenerId());

        //self::actualizarCacheTablaReservas($this->obtenerPista()->obtenerCampo()->obtenerId(), $this->obtenerIdPista(), $this->obtenerFechaReserva());
        //CacheTablaReserva::guardarActualizarCacheTablaReservaEnSegundoPlano($this->obtenerPista()->obtenerCampo()->obtenerId(), $this->obtenerIdPista(), $this->obtenerFechaReserva());
        //self::actualizarCacheTablaReservas($this->obtenerCampo()->obtenerId(), $this->obtenerFechaReserva(), $this->obtenerIdPista());
        */
    }

    function registrarPagoMonedero($idJugador, $importePago){
        Monedero::pagarConMonedero($idJugador, $this->obtenerId(), $importePago);
        self::eliminarCacheRedisTablaReservaParaElCampoYFecha($this->obtenerCampo()->obtenerId(), $this->obtenerFechaReserva(), $this->obtenerPista()->obtenerDeporte()->obtenerId());

    }

    function registrarPagoEnPista($idJugador, $importePago){
        $fechaHoy = date("Y-m-d H:i:s");

        switch ($idJugador){

            case $this->obtenerJugador1(true)->obtenerId();
                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_importePagoJugador1] = $importePago;
                $ReservaPista[self::COLUMNA_tipoPagoJugador1] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[self::COLUMNA_pagadoJugador1] = 1;
                $ReservaPista[self::COLUMNA_fechaPagoJugador1] = $fechaHoy;
                $ReservaPista->guardar();
                break;

            case $this->obtenerJugador2(true)->obtenerId();
                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_importePagoJugador2] = $importePago;
                $ReservaPista[self::COLUMNA_tipoPagoJugador2] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[self::COLUMNA_pagadoJugador2] = 1;
                $ReservaPista[self::COLUMNA_fechaPagoJugador2] = $fechaHoy;
                $ReservaPista->guardar();
                break;

            case $this->obtenerJugador3(true)->obtenerId();
                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_importePagoJugador3] = $importePago;
                $ReservaPista[self::COLUMNA_tipoPagoJugador3] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[self::COLUMNA_pagadoJugador3] = 1;
                $ReservaPista[self::COLUMNA_fechaPagoJugador3] = $fechaHoy;
                $ReservaPista->guardar();
                break;

            case $this->obtenerJugador4(true)->obtenerId();
                $ReservaPista = new ReservaPista($this->obtenerId());
                $ReservaPista[self::COLUMNA_importePagoJugador4] = $importePago;
                $ReservaPista[self::COLUMNA_tipoPagoJugador4] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[self::COLUMNA_pagadoJugador4] = 1;
                $ReservaPista[self::COLUMNA_fechaPagoJugador4] = $fechaHoy;
                $ReservaPista->guardar();
                break;

            default:
                return 0;
        }

        self::eliminarCacheRedisTablaReservaParaElCampoYFecha($this->obtenerCampo()->obtenerId(), $this->obtenerFechaReserva(), $this->obtenerPista()->obtenerDeporte()->obtenerId());


    }

    function obtenerNumeroJugadoresMaximoPermitidosReservaPista(){
        return $this[self::COLUMNA_numeroJugadoresMaximoPermitidos];
    }

    function obtenerNumeroJugadores(){
        return $this->obtenerPartido()->obtenerNumeroJugadores();
    }

    function obtenerFechaReserva(){
        return $this[self::COLUMNA_fechaReserva];
    }

    function obtenerHoraInicioReserva($formateada = false, $redondeada=false){


        $horaInicioReserva = $this["horaInicioReserva"];

        if ($redondeada){
            $array_hora = explode(":",$horaInicioReserva);

            $hora = $array_hora[0];
            $minutos = $array_hora[1];
            $segundos = $array_hora[2];


            if ($minutos > 0 && $minutos <= 30){
                $minutos = 30;
            }
            else if ($minutos > 30 || $minutos = 0){
                $minutos = 0;
                $hora += 1;
            }

            //JMAM: Añade los dos dígitos a las horas y minutos
            $hora = str_pad($hora, 2, "0", STR_PAD_LEFT);
            $minutos = str_pad($minutos, 2, "0", STR_PAD_LEFT);


            $horaInicioReserva = $hora.":".$minutos.":".$segundos;
        }



        if ($formateada){
            $array_hora = explode(":",$horaInicioReserva);

            $hora = $array_hora[0];
            $minutos = $array_hora[1];

            return "$hora:$minutos";
        }



        return $horaInicioReserva;

    }

    function obtenerHoraFinReserva(){
        $horaFinReserva = $this[self::COLUMNA_horaFinReserva];

        if ($horaFinReserva != ""){
            $array_horaFinReserva = explode(":", $this[self::COLUMNA_horaFinReserva]);
            return $array_horaFinReserva[0].":".$array_horaFinReserva[1];
        }
        else{
            return "";
        }


        //return $this["horaFinReserva"];
    }

    function obtenerDuracion($fomartoMinutos = false){

        $horaInicio = $this->obtenerHoraInicioReserva();
        $horaFin = $this->obtenerHoraFinReserva();

        if ($horaFin == "00:00"){
            $horaFin = "23:59:00";
        }


        $dateTime_horaInicio = new DateTime($horaInicio);
        $dateTime_horaFin = new DateTime($horaFin);

        $apertura = new DateTime($horaFin);
        $cierre = new DateTime($horaInicio);



        $tiempo = $apertura->diff($cierre);

        $minutosReserva = "";
        //JMAM: Obtener duración de la reserva en minutos
        $tiempoTransucurrido = $tiempo->format('%H:%I');
        $v_HorasPartes = explode(":", $tiempoTransucurrido);
        $minutosTotales= ($v_HorasPartes[0] * 60) + $v_HorasPartes[1];

        if ($horaFin == "23:59:00"){
            $minutosTotales ++;
        }

        if ($dateTime_horaFin > $dateTime_horaInicio){
            $minutosReserva =  $minutosTotales;
        }
        else{
            $minutosReserva =  1440 - $minutosTotales;
        }


        if ($fomartoMinutos){
            return $minutosReserva;
        }
        else{
            $zero    = new DateTime('@0');
            $offset  = new DateTime('@' . $minutosReserva * 60);
            return $zero->diff($offset)->format('%H:%I');
        }



    }

    function esReservaRealizadaPorClub(){

        $esReservaRealizadaPorClub = $this[self::COLUMNA_esReservaRealizadaPorClub];

        if ($esReservaRealizadaPorClub == 1){
            return true;
        }
        else{
            return false;
        }

    }

    function esReservaModificadaPorClub(){

        $esReservaModificadaPorClub = $this[self::COLUMNA_esReservaModificadaPorClub];

        if ($esReservaModificadaPorClub == 1){
            return true;
        }
        else{
            return false;
        }

    }

    function obtenerImporteTotalReserva($idGrupoJugador = ""){

        if ($idGrupoJugador == ""){
            return $this[self::COLUMNA_importeReserva];
        }
        else{
            switch ($idGrupoJugador){

                case GrupoJugador::ID_GENERAL:
                    return $this->obtenerPrecioGeneral();
                    break;

                case GrupoJugador::ID_RADICAL:
                    return $this->obtenerPrecioRadical();
                    break;

                case GrupoJugador::ID_SOCIOS:
                    return $this->obtenerPrecioSocios();
                    break;

                case GrupoJugador::ID_GRUPO1:
                    return $this->obtenerPrecioGrupo1();
                    break;

                case GrupoJugador::ID_GRUPO2:
                    return $this->obtenerPrecioGrupo2();
                    break;

                case GrupoJugador::ID_MONEDERO:
                    return $this->obtenerPrecioMonedero();
                    break;

                case GrupoJugador::ID_segundoMonedero:
                    return $this->obtenerPrecioSegundoMonedero();
                    break;
            }
        }

    }

    function sincronizarOrdenJugadoresReservaConPartido(){
        global $bd;

        $array_senteciasActualizacion = array();

        $idJugador1_Partido = $this->obtenerJugador1()->obtenerId();
        $array_senteciasActualizacion[] = $this->obtenerSentenciaActualizacionDatosJugador($idJugador1_Partido, 1);

        $idJugador2_Partido = $this->obtenerJugador2()->obtenerId();
        $array_senteciasActualizacion[] = $this->obtenerSentenciaActualizacionDatosJugador($idJugador2_Partido, 2);

        $idJugador3_Partido = $this->obtenerJugador3()->obtenerId();
        $array_senteciasActualizacion[] = $this->obtenerSentenciaActualizacionDatosJugador($idJugador3_Partido, 3);

        $idJugador4_Partido = $this->obtenerJugador4()->obtenerId();
        $array_senteciasActualizacion[] = $this->obtenerSentenciaActualizacionDatosJugador($idJugador4_Partido, 4);

        //print_r($array_senteciasActualizacion);
        foreach ($array_senteciasActualizacion as $value) {

            $bd->update(self::TABLA_nombre, array(
                $value[0][0] => $value[0][1],
                $value[1][0] => $value[1][1],
                $value[2][0] => $value[2][1],
                $value[3][0] => $value[3][1]
            ),
                array(self::COLUMNA_id => $this->obtenerId()));
        }
    }

    function eliminarJugador($idJugador){

        $numeroJugadorPartido = $this->obtenerNumeroJugador($idJugador);

        switch ($numeroJugadorPartido){

            case 1:
                $columna_idJugador = self::COLUMNA_idJugador1;
                $columna_importeJugador = self::COLUMNA_importePagoJugador1;
                $columna_pagadoJugador = self::COLUMNA_pagadoJugador1;
                $columna_tipoPagoJugador = self::COLUMNA_tipoPagoJugador1;
                break;


            case 2:
                $columna_idJugador = self::COLUMNA_idJugador2;
                $columna_importeJugador = self::COLUMNA_importePagoJugador2;
                $columna_pagadoJugador = self::COLUMNA_pagadoJugador2;
                $columna_tipoPagoJugador = self::COLUMNA_tipoPagoJugador2;
                break;


            case 3:
                $columna_idJugador = self::COLUMNA_idJugador3;
                $columna_importeJugador = self::COLUMNA_importePagoJugador3;
                $columna_pagadoJugador = self::COLUMNA_pagadoJugador3;
                $columna_tipoPagoJugador = self::COLUMNA_tipoPagoJugador3;
                break;

            case 4:
                $columna_idJugador = self::COLUMNA_idJugador4;
                $columna_importeJugador = self::COLUMNA_importePagoJugador4;
                $columna_pagadoJugador = self::COLUMNA_pagadoJugador4;
                $columna_tipoPagoJugador = self::COLUMNA_tipoPagoJugador4;
                break;

        }


        $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador, $this->obtenerId());
        if ($esDevueltoPagoMonedero == false){
            $ReservaPista = new ReservaPista($this->obtenerId());
            $ReservaPista[$columna_idJugador] = 0;
            $ReservaPista[$columna_importeJugador] = 0;
            $ReservaPista[$columna_tipoPagoJugador] = "";
            $ReservaPista[$columna_pagadoJugador] = 0;
            $ReservaPista->guardar();
        }

        $this->sincronizarOrdenJugadoresReservaConPartido();
    }

    function apuntarJugador($idJugador, $importe, $tipoJugadorReserva, $pagoAplazado=false, $idJugadorSustituir=""){

        Log::v(__FUNCTION__, "ID JUGADOR Apuntar: $idJugador", true);

        $this->obtenerPartido()->apuntarJugador($idJugador, $idJugadorSustituir);
        if ($tipoJugadorReserva == self::TIPOPAGOJUGADORRESERVA_MONEDERO){
            Monedero::pagarConMonedero($idJugador, $this->obtenerId(), $importe, $pagoAplazado);
        }
        else{
            $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($this->obtenerPartido()->obtenerId(), $idJugador);
            $PartidoJugador[PartidoJugador::COLUMNA_idPartido] = $this->obtenerPartido()->obtenerId();
            $PartidoJugador[PartidoJugador::COLUMNA_idReservaPista] = $this->obtenerId();
            $PartidoJugador[PartidoJugador::COLUMNA_idJugador] = $idJugador;
            //$PartidoJugador[PartidoJugador::COLUMNA_numeroJugador] = $ReservaPista->obtenerNumeroJugadores() + 1;
            $PartidoJugador[PartidoJugador::COLUMNA_importePago] = $importe;
            $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = 0;
            $PartidoJugador[PartidoJugador::COLUMNA_tipoJugador] = PartidoJugador::TIPOJUGADOR_interno;
            $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = $tipoJugadorReserva;
            $PartidoJugador->guardar();
        }

        ReservaPista::eliminarCacheRedisTablaReservaParaElCampoYFecha($this->obtenerCampo()->obtenerId(), $this->obtenerFechaReserva());

    }

    function obtenerImporteReservaPorJugador(){
        return $this->obtenerImporteTotalReserva() / $this->obtenerNumeroJugadoresMaximoPermitidosReservaPista();
    }

    function esPartidoCompleto(){

        $esPartidoCompleto = $this[self::COLUMNA_partidoCompleto];

        if ($esPartidoCompleto == 1){
            return true;
        }
        else{
            return false;
        }

    }

    function esPartidoPublico(){

        $esPartidoPublico = $this[self::COLUMNA_partidoPublico];

        if ($esPartidoPublico == 1){
            return true;
        }
        else{
            return false;
        }

    }

    function esPartidoCompletoPorJugadores(){

        if ($this->obtenerNumeroJugadores() >= $this->obtenerNumeroJugadoresMaximoPermitidosReservaPista()){
            return true;
        }

        return false;
    }

    function obtenerImportePagado(){
        return $this["importeReserva"];
    }

    function esRepartirImporte(){

        if ($this->obtenerId() > 0){
            if ($this[self::COLUMNA_repartirImporte] == 1){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return true;
        }


    }

    function obtenerImportePersonalizadoPagoJugadorApuntarse(){

        if ($this->esTiempoReservaPersonalizado()){
            $numeroJugadoresInscritos = $this->obtenerNumeroJugadores();
            return $this->obtenerImportePagoJugadorNumero($numeroJugadoresInscritos+1);
        }

        return 0;

    }

    function obtenerImportePagoJugadorNumero($numeroJugador){

        $PartidoJugador = $this->obtenerPartidoJugadorPorNumeroJugador($numeroJugador);
        $PartidoJugador->obtenerImportePago();
    }

    function obtenerImportePagoJugador1(){
        $PartidoJugador = $this->obtenerPartidoJugadorPorNumero(1);
        return $PartidoJugador->obtenerImportePago();
    }

    function actualizarTipoPagoJugador($idJugador, $tipoPago){
        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        $PartidoJugador[PartidoJugador::COLUMNA_tipoPagoJugador] = $tipoPago;
        $PartidoJugador->guardar();
    }

    function actualizarAplazadoPagoJugador($idJugador, $aplazadoPagoJugador){
        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        $PartidoJugador[PartidoJugador::COLUMNA_esAplazadoPagoJugador] = $aplazadoPagoJugador;
        $PartidoJugador->guardar();
    }

    function esAplazadoPagoJugador1(){
        return $this->obtenerPartidoJugadorPorNumeroJugador(1)->esAplazadoPagoJugador();
    }

    function esAplazadoPagoJugador2(){
        return $this->obtenerPartidoJugadorPorNumeroJugador(2)->esAplazadoPagoJugador();
    }

    function esAplazadoPagoJugador3(){
        return $this->obtenerPartidoJugadorPorNumeroJugador(3)->esAplazadoPagoJugador();
    }

    function esAplazadoPagoJugador4(){
        return $this->obtenerPartidoJugadorPorNumeroJugador(4)->esAplazadoPagoJugador();
    }

    function obtenerFechaPagoJugador1(){
        return $this->obtenerPartidoJugadorPorNumeroJugador(1)->obtenerFechaPagoJugador();
    }

    function obtenerFechaPagoJugador2(){
        return $this->obtenerPartidoJugadorPorNumeroJugador(2)->obtenerFechaPagoJugador();
    }

    function obtenerFechaPagoJugador3(){
        return $this->obtenerPartidoJugadorPorNumeroJugador(3)->obtenerFechaPagoJugador();
    }

    function obtenerFechaPagoJugador4(){
        return $this->obtenerPartidoJugadorPorNumeroJugador(4)->obtenerFechaPagoJugador();
    }


    function actualizarFechaPagoJugador($idJugador, $fechaPago){

        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        $PartidoJugador[PartidoJugador::COLUMNA_fechaPagoJugador] = $fechaPago;
        $PartidoJugador->guardar();
    }

    function existeJugadorEnRerserva($idJugadorAEncontrar){

        $array_idsJugadores = $this->obtenerIdsJugadores();
        foreach ($array_idsJugadores as $idJugador){
            if ($idJugador == $idJugadorAEncontrar){
                return true;
            }
        }

        return false;
    }

    function obtenerJugadorPorNumero($numeroJugador){

        return PartidoJugador::obtenerJugadorDelPartido($this->obtenerIdPartido(), $numeroJugador);
    }

    function obtenerPartidoJugadorPorNumero($numeroJugador){
        return PartidoJugador::obtenerPartidoJugadorDelPartido($this->obtenerIdPartido(), $numeroJugador);

    }

    function obtenerJugador1($guardadoEnLaReserva=false){


        if ($this->obtenerTipoJugador1() == "externo"){
            return new JugadorExterno($this[self::COLUMNA_idJugador1]);
        }
        else{
            if ($guardadoEnLaReserva){
                return new Jugador($this[self::COLUMNA_idJugador1]);
            }
            else{
                return $this->obtenerPartido()->obtenerJugador1();
            }
        }

    }

    function obtenerJugador2($guardadoEnLaReserva=false){

        if ($this->obtenerTipoJugador2() == "externo"){
            return new JugadorExterno($this[self::COLUMNA_idJugador2]);
        }
        else{
            if ($guardadoEnLaReserva){
                return new Jugador($this[self::COLUMNA_idJugador2]);
            }
            else{
                return $this->obtenerPartido()->obtenerJugador2();
            }
        }
    }

    function obtenerJugador3($guardadoEnLaReserva=false){

        if ($this->obtenerTipoJugador3() == "externo"){
            return new JugadorExterno($this[self::COLUMNA_idJugador3]);
        }
        else{
            if ($guardadoEnLaReserva){
                return new Jugador($this[self::COLUMNA_idJugador3]);
            }
            else{
                return $this->obtenerPartido()->obtenerJugador3();
            }
        }
    }

    function obtenerJugador4($guardadoEnLaReserva=false){

        if ($this->obtenerTipoJugador4() == "externo"){
            return new JugadorExterno($this[self::COLUMNA_idJugador4]);
        }
        else{
            if ($guardadoEnLaReserva){
                return new Jugador($this[self::COLUMNA_idJugador4]);
            }
            else{
                return $this->obtenerPartido()->obtenerJugador4();
            }
        }
    }

    function obtenerParejaJugadorPorNumeroJugador($numeroJugador){
        return $this->obtenerPartido()->obtenerParejaJugadorPorNumeroJugador($numeroJugador);
    }

    function obtenerResultadoJugadorPorNumeroJugador($numeroJugador){
        return $this->obtenerPartido()->obtenerResultadoJugadorPorNumeroJugador($numeroJugador);
    }

    function obtenerTipoJugador1(){
        return $this[self::COLUMNA_tipoJugador1];
    }

    function obtenerTipoJugador2(){
        return $this[self::COLUMNA_tipoJugador2];
    }

    function obtenerTipoJugador3(){
        return $this[self::COLUMNA_tipoJugador3];
    }

    function obtenerTipoJugador4(){
        return $this[self::COLUMNA_tipoJugador4];
    }

    function obtenerTipoPagoJugador1(){
        return $this[self::COLUMNA_tipoPagoJugador1];
    }

    function obtenerTipoPagoJugador2(){
        return $this[self::COLUMNA_tipoPagoJugador2];
    }

    function obtenerTipoPagoJugador3(){
        return $this[self::COLUMNA_tipoPagoJugador3];
    }

    function obtenerTipoPagoJugador4(){
        return $this[self::COLUMNA_tipoPagoJugador4];
    }

    function actualizarEsPagadoJugador($idJugador, $esPagadoJugador){

        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        $PartidoJugador[PartidoJugador::COLUMNA_esPagadoJugador] = $esPagadoJugador;
        $PartidoJugador->guardar();
    }

    function esPagadoJugador1($ignorarPagoAplazado=true){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorNumeroJugador($this->obtenerIdPartido(), 1)->esPagadoJugador($ignorarPagoAplazado);
    }

    function esPagadoJugador2($ignorarPagoAplazado=true){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorNumeroJugador($this->obtenerIdPartido(), 2)->esPagadoJugador($ignorarPagoAplazado);
    }

    function esPagadoJugador3($ignorarPagoAplazado=true){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorNumeroJugador($this->obtenerIdPartido(), 3)->esPagadoJugador($ignorarPagoAplazado);
    }

    function esPagadoJugador4($ignorarPagoAplazado=true){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorNumeroJugador($this->obtenerIdPartido(), 4)->esPagadoJugador($ignorarPagoAplazado);
    }

    function esAlgunJugadorDeTipo($tipoJugador){

        if ($this->obtenerNumeroJugadoresPorTipo($tipoJugador) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function obtenerNumeroJugadoresPorTipo($tipoJugador){
        $count = 0;

        $array_idsPartidoJugadores = PartidoJugador::obtenerIdsPartidoJugadoresDelPartido($this->obtenerIdPartido());
        foreach ($array_idsPartidoJugadores as $idPartidoJugador){
            $PartidoJugador = new PartidoJugador($idPartidoJugador);
            if ($PartidoJugador->obtenerTipoJugador() == $tipoJugador){
                $count++;
            }
        }

        return $count;
    }

    function obtenerNumeroJugadoresPagado($ignorarPagoAplazados=true){

        if ($this->esRepartirImporte()){

            $array_idsPartidoJugadores = PartidoJugador::obtenerIdsPartidoJugadoresDelPartido($this->obtenerIdPartido());
            foreach ($array_idsPartidoJugadores as $idPartidoJugador){
                $PartidoJugador = new PartidoJugador($idPartidoJugador);
                if ($PartidoJugador->esPagadoJugador() && $PartidoJugador->obtenerJugador()->existe()){
                    $count++;
                }
            }

            return $count;
        }
        else{
            $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartido($this->obtenerIdPartido(), 1);
            if ($PartidoJugador->esPagadoJugador() && $PartidoJugador->obtenerJugador()->existe()){
                return $this->obtenerNumeroJugadores();
            }
            else{
                return 0;
            }

        }
    }

    function obtenerNumeroJugadoresAplazadoPago(){

        if ($this->esRepartirImporte()){

            $array_idsPartidoJugadores = PartidoJugador::obtenerIdsPartidoJugadoresDelPartido($this->obtenerIdPartido());
            foreach ($array_idsPartidoJugadores as $idPartidoJugador){
                $PartidoJugador = new PartidoJugador($idPartidoJugador);
                if ($PartidoJugador->esAplazadoPagoJugador() && $PartidoJugador->obtenerJugador()->existe()){
                    $count++;
                }
            }

            return $count;
        }
        else{
            $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartido($this->obtenerIdPartido(), 1);
            if ($PartidoJugador->esAplazadoPagoJugador()){
                return $this->obtenerNumeroJugadores();
            }
            else{
                return 0;
            }

        }
    }

    function obtenerNumeroJugadoresBuscandoSustituto(){

        $count = 0;

        $array_idsJugadores = $this->obtenerIdsJugadores();
        foreach ($array_idsJugadores as $idJugador){
            $Jugador = new Jugador($idJugador);
            if ($Jugador->esBuscandoSustituto($this->obtenerIdPartido())){
                $count++;
            }
        }


        return $count;
    }

    function obtenerNumeroJugadoresNoPagado($segunJugadoresInscritos = false, $ignorarPagoAplazados=true){

        if ($segunJugadoresInscritos){
            $numeroJugadores = $this->obtenerNumeroJugadoresMaximoPermitidosReservaPista();
        }
        else{
            $numeroJugadores = $this->obtenerNumeroJugadores();
        }

        Log::v(__FUNCTION__, "Número Jugadores: $numeroJugadores", false);

        if ($this->esRepartirImporte()){

            $count = 0;
            $array_idsPartidoJugadores = PartidoJugador::obtenerIdsPartidoJugadoresDelPartido($this->obtenerIdPartido());
            foreach ($array_idsPartidoJugadores as $idPartidoJugador){
                $PartidoJugador = new PartidoJugador($idPartidoJugador);
                if (!$PartidoJugador->esPagadoJugador($ignorarPagoAplazados) && $PartidoJugador->obtenerJugador()->existe() && $numeroJugadores >= 1){
                    $count++;
                }
            }

            return $count;
        }
        else{
            $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartido($this->obtenerIdPartido(), 1);
            if (!$PartidoJugador->esPagadoJugador()){
                return $numeroJugadores;
            }
            else{
                return 0;
            }

        }
    }

    function obtenerPrecioGeneral(){
        return $this[self::COLUMNA_precioGeneral];
    }

    function obtenerPrecioRadical(){
        return $this[self::COLUMNA_precioRadical];
    }

    function obtenerPrecioSocios(){
        return $this[self::COLUMNA_precioSocios];
    }

    function obtenerPrecioGrupo1(){
        return $this[self::COLUMNA_precioGrupo1];
    }

    function obtenerPrecioGrupo2(){
        return $this[self::COLUMNA_precioGrupo2];
    }

    function obtenerPrecioIluminacion(){
        return $this[self::COLUMNA_iluminacionIncluida];
    }

    function obtenerPrecioMonedero(){
        return $this[self::COLUMNA_precioMonedero];
    }

    function obtenerPrecioSegundoMonedero(){
        return $this[self::COLUMNA_precioSegundoMonedero];
    }

    function obtenerPrecioIdMonedero($idMonedero){

        switch ($idMonedero){

            case self::COLUMNA_precioMonedero:
                return $this->obtenerPrecioMonedero();
                breaK;

            case self::COLUMNA_precioSegundoMonedero:
                return $this->obtenerPrecioSegundoMonedero();
                breaK;
        }

        return -1;
    }

    function puedePagarConMonederoJugador1(){
        return $this->puedeJugadorPagarConMonedero($this->obtenerJugador1()->obtenerId());
    }

    function puedePagarConMonederoJugador2(){
        return $this->puedeJugadorPagarConMonedero($this->obtenerJugador2()->obtenerId());
    }

    function puedePagarConMonederoJugador3(){
        return $this->puedeJugadorPagarConMonedero($this->obtenerJugador3()->obtenerId());
    }

    function puedePagarConMonederoJugador4(){
        return $this->puedeJugadorPagarConMonedero($this->obtenerJugador4()->obtenerId());
    }

    function puedeJugadorPagarConMonedero($idJugador){
        return Monedero::pagarConMonedero($idJugador, $this->obtenerId(), $this->obtenerImportePagoJugador($idJugador), false, true);
    }

    function obtenerDescuento(){
        return $this[self::COLUMNA_descuento];
    }

    function obtenerPrecioPorGrupoJugador($idGrupoJugador, $idGrupoJugadorSiNoTienePrecio = ""){

        $importePersonalizadoJugadorReserva = $this->obtenerImportePersonalizadoPagoJugadorApuntarse();
        Log::v(__FUNCTION__, "Aplicado importe personalizado: $importePersonalizadoJugadorReserva", true);

        if (!empty($importePersonalizadoJugadorReserva)){
            $numeroJugadores = $this->obtenerNumeroJugadoresMaximoPermitidosReservaPista();
            return $importePersonalizadoJugadorReserva * $numeroJugadores;
        }


        $precioPorGrupoJugador = 0;
        //$descuento = $this->obtenerDescuento();


        switch ($idGrupoJugador){

            case GrupoJugador::ID_GENERAL:
                $precioPorGrupoJugador = $this->obtenerPrecioGeneral();
                break;

            case GrupoJugador::ID_RADICAL:
                $precioPorGrupoJugador = $this->obtenerPrecioRadical();
                break;

            case GrupoJugador::ID_SOCIOS:
                $precioPorGrupoJugador = $this->obtenerPrecioSocios();
                break;

            case GrupoJugador::ID_GRUPO1:
                $precioPorGrupoJugador = $this->obtenerPrecioGrupo1();
                break;

            case GrupoJugador::ID_GRUPO2:
                $precioPorGrupoJugador = $this->obtenerPrecioGrupo2();
                break;

            case GrupoJugador::ID_MONEDERO:
                $precioPorGrupoJugador = $this->obtenerPrecioMonedero();
                break;

            case GrupoJugador::ID_segundoMonedero:
                $precioPorGrupoJugador = $this->obtenerPrecioSegundoMonedero();
                break;
        }

        Log::v(__FUNCTION__, "Precio por grupo Jugador: $precioPorGrupoJugador", true);

        if ($precioPorGrupoJugador == -1){
            //JMAM: NO tiene precio el Grupo de Jugador
            if ($idGrupoJugador == GrupoJugador::ID_MONEDERO){
                //JMAM: Si es Monedero

                //JMAM: Obtener el Grupo del Jugador
                $precioPorGrupoJugador = $this->obtenerPrecioPorGrupoJugador($idGrupoJugadorSiNoTienePrecio);
            }
            else{
                //JMAM: Si no es Monedero

                //JMMA: Obtiene el precio general
                $precioPorGrupoJugador =  $this->obtenerPrecioGeneral();

            }
        }


        if ($descuento > 0){
            return $precioPorGrupoJugador - (($precioPorGrupoJugador*$descuento)/100);
        }
        else{
            Log::v(__FUNCTION__, "PRECIO GRUPO JUGADOR: $precioPorGrupoJugador", true);
            return $precioPorGrupoJugador;
        }

    }

    function obtenerNumeroPedido(){
        return $this[self::COLUMNA_numeroPedido];
    }

    function esPagadoJugador($idJugador){

        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        return $PartidoJugador->esPagadoJugador();
    }

    function esAplazadoPagoJugador($idJugador){

        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        return $PartidoJugador->esAplazadoPagoJugador();
    }

    function obtenerTipoPagoJugador($idJugador){

        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        return $PartidoJugador->obtenerTipoPagoJugador();
    }

    function obtenerIdGrupoJugador($idJugador){

        $Jugador = new Jugador($idJugador);
        $Jugador->obtenerGrupoJugador($this->obtenerLiga()->obtenerId());

    }

    function obtenerTipoJugador($idJugador){

        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        return $PartidoJugador->obtenerTipoJugador();
    }

    function obtenerSumaTotalImportesPagados($tipoPagoJugador, $pagado=true, $idJugador=""){

        Log::v(__FUNCTION__, "TIPO PAGO: $tipoPagoJugador | PAGADO: $pagado | ID JUGADOR: $idJugador", false);

        $sumasImportesPagados = 0;
        if ($idJugador > 0){
            if($this->obtenerTipoPagoJugador($idJugador) == $tipoPagoJugador && $this->esJugadorPagadoReserva($idJugador) == $pagado){
                return $this->obtenerImportePagoJugador($idJugador);
            }
            else{
                return 0;
            }
        }
        else{

            $array_idsPartidoJugadores = $this->obtenerIdsPartidoJugadores();
            foreach ($array_idsPartidoJugadores as $idPartidoJugador){
                $PartidoJugador = new PartidoJugador($idPartidoJugador);

                if ($PartidoJugador->obtenerTipoJugador() == $tipoPagoJugador && $PartidoJugador->esPagadoJugador() == $pagado){
                    $sumasImportesPagados += $PartidoJugador->obtenerImportePago();

                }
            }


            return $sumasImportesPagados;
        }



    }

    function esPagadoAlgunJugador($tipoPagoJugador){

        $array_idsPartidoJugadores = $this->obtenerIdsPartidoJugadores();
        foreach ($array_idsPartidoJugadores as $idPartidoJugador){
            $PartidoJugador = new PartidoJugador($idPartidoJugador);

            if ($PartidoJugador->obtenerTipoJugador() == $tipoPagoJugador && $PartidoJugador->esPagadoJugador() == true){
                return true;
            }
        }

        return false;

    }

    function obtenerPartidoJugadorPorIdJugador($idJugador){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($this->obtenerIdPartido(), $idJugador);
    }

    function obtenerPartidoJugadorPorNumeroJugador($numeroJugador){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorNumeroJugador($this->obtenerIdPartido(), $numeroJugador);
    }

    function obtenerNumeroJugadoresPermitidos(){
        return $this->obtenerPista()->obtenerNumeroJugadoresPermitidos();
    }

    function obtenerImportePagoJugador($idJugador){
        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        return $PartidoJugador->obtenerImportePago();
    }

    function obtenerImporteTotalPagadoIdJugador($idJugador){
        return Monedero::obtenerImporteTotalPagadoIdJugadorParaIdReservaPista($idJugador, $this->obtenerId());
    }

    function obtenerFechaPagoJugador($idJugador){

        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        return $PartidoJugador->obtenerFechaPagoJugador();
    }

    function obtenerNumeroPinAcceso(){
        if ($this->obtenerClub()->esActivadoAutomatizacionPuertas()){
            return substr($this->obtenerId(), -4,4);
        }

        return "";
    }

    function esJugadorPagadoReserva($idJugador){

        $PartidoJugador = $this->obtenerPartidoJugadorPorIdJugador($idJugador);
        return $PartidoJugador->esPagadoJugador();
    }

    function pagarDevolverImporteJugador($idJugador, $tipoPago, $pagar){

        if (!$this->existeJugadorEnRerserva($idJugador)){
            echo Traductor::traducir("No existe el Jugador en la Reserva");
            return false;
        }

        if ($pagar){

            switch ($tipoPago){

                case self::TIPOPAGOJUGADORRESERVA_MONEDERO:
                    $respuesta = Monedero::pagarConMonedero($idJugador, $this->obtenerId(), $this->obtenerImportePagoJugador($idJugador), 0);
                    break;


                case self::TIPOPAGOJUGADORRESERVA_ENPISTA:
                    $this->actualizarTipoPagoJugador($idJugador, self::TIPOPAGOJUGADORRESERVA_ENPISTA);
                    $this->actualizarEsPagadoJugador($idJugador, true);
                    $this->actualizarFechaPagoJugador($idJugador, date("Y-m-d"));
                    $this->actualizarAplazadoPagoJugador($idJugador, 0);
                    $respuesta = true;
                    break;

                default:
                    $this->actualizarTipoPagoJugador($idJugador, self::TIPOPAGOJUGADORRESERVA_ENPISTA);
                    $this->actualizarEsPagadoJugador($idJugador, true);
                    $this->actualizarFechaPagoJugador($idJugador, date("Y-m-d"));
                    $this->actualizarAplazadoPagoJugador($idJugador, 0);
                    $respuesta = true;
            }
        }
        else{
            $tipoPagoJugador = $this->obtenerTipoPagoJugador($idJugador);
            Log::v(__FUNCTION__, "Tipo pago Jugador: $tipoPagoJugador");
            switch ($tipoPagoJugador){

                case self::TIPOPAGOJUGADORRESERVA_MONEDERO:
                    Log::v(__FUNCTION__, "Devolución con monedero", false);
                    $respuesta = Monedero::devolverPagoMonedero($idJugador, $this->obtenerId());
                    break;


                case self::TIPOPAGOJUGADORRESERVA_ENPISTA:
                    Log::v(__FUNCTION__, "Devolución en pista", false);
                    $this->actualizarTipoPagoJugador($idJugador, "");
                    $this->actualizarEsPagadoJugador($idJugador, false);
                    $this->actualizarFechaPagoJugador($idJugador, "");
                    $this->actualizarAplazadoPagoJugador($idJugador, 0);
                    $respuesta = true;
                    break;

                default:
                    //echo Traductor::traducir("El tipo de pago del jugador no está definido");
                    Log::v(__FUNCTION__, "Devolución en pista", false);
                    $this->actualizarTipoPagoJugador($idJugador, "");
                    $this->actualizarEsPagadoJugador($idJugador, false);
                    $this->actualizarFechaPagoJugador($idJugador, "");
                    $this->actualizarAplazadoPagoJugador($idJugador, 0);
                    $respuesta = true;
            }

        }

        self::eliminarCacheRedisTablaReservaParaElCampoYFecha($this->obtenerCampo()->obtenerId(), $this->obtenerFechaReserva());
        return $respuesta;
    }

    function devolverPagoATodosLosJugadores(){

        $array_idsJugadores = $this->obtenerIdsJugadores();
        foreach ($array_idsJugadores as $idJugador){
            Monedero::devolverPagoMonedero($idJugador, $this->obtenerId());
        }

    }

    function obtenerConfiguracionReservaPistas(){

        $Partido = $this->obtenerPartido();
        return $Partido->obtenerConfiguracionReservaPistas();
    }

    function obtenerFechaUltimaModificacion(){
        return $this[self::COLUMNA_fechaUltimaModificacion];
    }


    function suma($date_1 , $date_2)
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = ($datetime1 + $datetime2);

        return $interval;

    }

    function obtenerTodosIdsReservasHistorial(){
        return ReservaPistaHistorial::obtenerTodosIdsReservasHistorialDelPartido($this->obtenerId());
    }

    function guardarEnHistorial(){
        $ReservaPistaHistorial = new ReservaPistaHistorial();
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idReservaPista] = $this[self::COLUMNA_id];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idGrupoReserva] = $this[self::COLUMNA_idGrupoReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idCampo] = $this[self::COLUMNA_idCampo];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idPista] = $this[self::COLUMNA_idPista];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idPartido] = $this[self::COLUMNA_idPartido];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idTipoReserva] = $this[self::COLUMNA_idTipoReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idTiempoReserva] = $this[self::COLUMNA_idTiempoReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idJugadorReserva] = $this[self::COLUMNA_idJugadorReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoJugadorReserva] = $this[self::COLUMNA_tipoJugadorReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_descripcion] = $this[self::COLUMNA_descripcion];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idJugador1] = $this[self::COLUMNA_idJugador1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idJugador2] = $this[self::COLUMNA_idJugador2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idJugador3] = $this[self::COLUMNA_idJugador3];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_idJugador4] = $this[self::COLUMNA_idJugador4];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoJugador1] = $this[self::COLUMNA_tipoJugador1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoJugador2] = $this[self::COLUMNA_tipoJugador2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoJugador3] = $this[self::COLUMNA_tipoJugador3];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoJugador4] = $this[self::COLUMNA_tipoJugador4];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_grupoJugador1] = $this[self::COLUMNA_grupoJugador1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_grupoJugador2] = $this[self::COLUMNA_grupoJugador2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_grupoJugador3] = $this[self::COLUMNA_grupoJugador3];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_grupoJugador4] = $this[self::COLUMNA_grupoJugador4];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoPagoJugadorReserva] = $this[self::COLUMNA_tipoPagoJugadorReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_numeroJugadoresMaximoPermitidos] = $this[self::COLUMNA_numeroJugadoresMaximoPermitidos];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_repartirImporte] = $this[self::COLUMNA_repartirImporte];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_partidoCompleto] = $this[self::COLUMNA_partidoCompleto];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_partidoPublico] = $this[self::COLUMNA_partidoPublico];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_fechaReserva] = $this[self::COLUMNA_fechaReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_horaInicioReserva] = $this[self::COLUMNA_horaInicioReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_horaFinReserva] = $this[self::COLUMNA_horaFinReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_esReservaRealizadaPorClub] = $this[self::COLUMNA_esReservaRealizadaPorClub];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_esReservaModificadaPorClub] = $this[self::COLUMNA_esReservaModificadaPorClub];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_importeReserva] = $this[self::COLUMNA_importeReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_fechaPagoReserva] = $this[self::COLUMNA_fechaPagoReserva];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_importePagoJugador1] = $this[self::COLUMNA_importePagoJugador1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_importePagoJugador2] = $this[self::COLUMNA_importePagoJugador2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_importePagoJugador3] = $this[self::COLUMNA_importePagoJugador3];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_importePagoJugador4] = $this[self::COLUMNA_importePagoJugador4];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoPagoJugador1] = $this[self::COLUMNA_tipoPagoJugador1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoPagoJugador2] = $this[self::COLUMNA_tipoPagoJugador2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoPagoJugador3] = $this[self::COLUMNA_tipoPagoJugador3];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_tipoPagoJugador4] = $this[self::COLUMNA_tipoPagoJugador4];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_pagadoJugador1] = $this[self::COLUMNA_pagadoJugador1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_pagadoJugador2] = $this[self::COLUMNA_pagadoJugador2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_pagadoJugador3] = $this[self::COLUMNA_pagadoJugador3];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_pagadoJugador4] = $this[self::COLUMNA_pagadoJugador4];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_aplazadoPagoJugador1] = $this[self::COLUMNA_aplazadoPagoJugador1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_aplazadoPagoJugador2] = $this[self::COLUMNA_aplazadoPagoJugador2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_aplazadoPagoJugador3] = $this[self::COLUMNA_aplazadoPagoJugador3];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_aplazadoPagoJugador4] = $this[self::COLUMNA_aplazadoPagoJugador4];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_fechaPagoJugador1] = $this[self::COLUMNA_fechaPagoJugador1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_fechaPagoJugador2] = $this[self::COLUMNA_fechaPagoJugador2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_fechaPagoJugador3] = $this[self::COLUMNA_fechaPagoJugador3];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_fechaPagoJugador4] = $this[self::COLUMNA_fechaPagoJugador4];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_descuento] = $this[self::COLUMNA_descuento];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_precioGeneral] = $this[self::COLUMNA_precioGeneral];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_precioRadical] = $this[self::COLUMNA_precioRadical];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_precioSocios] = $this[self::COLUMNA_precioSocios];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_precioGrupo1] = $this[self::COLUMNA_precioGrupo1];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_precioGrupo2] = $this[self::COLUMNA_precioGrupo2];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_iluminacionIncluida] = $this[self::COLUMNA_iluminacionIncluida];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_precioMonedero] = $this[self::COLUMNA_precioMonedero];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_precioSegundoMonedero] = $this[self::COLUMNA_precioSegundoMonedero];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_numeroPedido] = $this[self::COLUMNA_numeroPedido];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_fechaUltimaModificacion] = $this[self::COLUMNA_fechaUltimaModificacion];
        $ReservaPistaHistorial[ReservaPistaHistorial::COLUMNA_valoresSesionGuardadoHistorial] = json_encode($_SESSION);
        $ReservaPistaHistorial->guardar();

    }

    function guardar($valor = null, $nombreTablaClonar = "", $noGuardarEnHistorial=false)
    {
        $id = parent::guardar($valor, $nombreTablaClonar); // TODO: Change the autogenerated stub


        if ($noGuardarEnHistorial == false){
            //JMAM: Guardado en historial
            $this->guardarEnHistorial();
        }


        return $id;
    }

    function eliminar($eliminarTodosLosJugadoresDelPartido=true){
        global $bd;

        $idCampo = $this->obtenerPista()->obtenerCampo()->obtenerId();
        $Pista = $this->obtenerPista();
        $idPista = $Pista->obtenerId();
        $Deporte = $Pista->obtenerDeporte();
        $fechaMYSQL = $this->obtenerFechaReserva();
        $horaInicio = $this->obtenerHoraInicioReserva();
        $horaFin = $this->obtenerHoraFinReserva();




        $id = $this->obtenerId();
        //JMAM: Elimina la Reserva [MEJORAR PARA HACERLO MEDIANTE LA CLASE GENERAL]
        if ($id == null){
            $id = $this->data["id"];
        }

        if ($eliminarTodosLosJugadoresDelPartido){
            PartidoJugador::eliminarTodosLosJugadoresDelPartido($this->obtenerIdPartido());
        }
        //CacheTablaReserva::eliminarDeCacheIdReservaPista($id);


        $bd->delete($this->table_name, array("id" => $id));
        CacheTablaReserva::actualizarCacheReserva($idCampo, $idPista, $fechaMYSQL, $horaInicio, $horaFin, $forzarPistaReservadaEntreHoraInicioYHoraFin, "",$Deporte->obtenerId(),$comprobar_duplicado,$array_keys_duplicados);

    }

    private function obtenerSentenciaActualizacionDatosJugador($idJugador, $nuevaPosicion){

        $numeroJugadorPartido = $this->obtenerNumeroJugador($idJugador);


        $idJugador_ReservaPista = "";
        $importeJugador_ReservaPista = "";
        $pagadoJugador_ReservaPista = "";
        $tipoPagoJugador_ReservaPista = "";

        switch ($numeroJugadorPartido){

            case 1:
                $idJugador_ReservaPista = $this[self::COLUMNA_idJugador1];
                $importeJugador_ReservaPista = $this[self::COLUMNA_importePagoJugador1];
                $pagadoJugador_ReservaPista = $this[self::COLUMNA_pagadoJugador1];
                $tipoPagoJugador_ReservaPista = $this[self::COLUMNA_tipoPagoJugador1];
                break;


            case 2:
                $idJugador_ReservaPista = $this[self::COLUMNA_idJugador2];
                $importeJugador_ReservaPista = $this[self::COLUMNA_importePagoJugador2];
                $pagadoJugador_ReservaPista = $this[self::COLUMNA_pagadoJugador2];
                $tipoPagoJugador_ReservaPista = $this[self::COLUMNA_tipoPagoJugador2];
                break;


            case 3:
                $idJugador_ReservaPista = $this[self::COLUMNA_idJugador3];
                $importeJugador_ReservaPista = $this[self::COLUMNA_importePagoJugador3];
                $pagadoJugador_ReservaPista = $this[self::COLUMNA_pagadoJugador3];
                $tipoPagoJugador_ReservaPista = $this[self::COLUMNA_tipoPagoJugador3];
                break;

            case 4:
                $idJugador_ReservaPista = $this[self::COLUMNA_idJugador4];
                $importeJugador_ReservaPista = $this[self::COLUMNA_importePagoJugador4];
                $pagadoJugador_ReservaPista = $this[self::COLUMNA_pagadoJugador4];
                $tipoPagoJugador_ReservaPista = $this[self::COLUMNA_tipoPagoJugador4];
                break;

        }


        switch ($nuevaPosicion){

            case 1:
                $columna_idJugador = self::COLUMNA_idJugador1;
                $columna_importeJugador = self::COLUMNA_importePagoJugador1;
                $columna_pagadoJugador = self::COLUMNA_pagadoJugador1;
                $columna_tipoPagoJugador = self::COLUMNA_tipoPagoJugador1;
                break;


            case 2:
                $columna_idJugador = self::COLUMNA_idJugador2;
                $columna_importeJugador = self::COLUMNA_importePagoJugador2;
                $columna_pagadoJugador = self::COLUMNA_pagadoJugador2;
                $columna_tipoPagoJugador = self::COLUMNA_tipoPagoJugador2;
                break;


            case 3:
                $columna_idJugador = self::COLUMNA_idJugador3;
                $columna_importeJugador = self::COLUMNA_importePagoJugador3;
                $columna_pagadoJugador = self::COLUMNA_pagadoJugador3;
                $columna_tipoPagoJugador = self::COLUMNA_tipoPagoJugador3;
                break;

            case 4:
                $columna_idJugador = self::COLUMNA_idJugador4;
                $columna_importeJugador = self::COLUMNA_importePagoJugador4;
                $columna_pagadoJugador = self::COLUMNA_pagadoJugador4;
                $columna_tipoPagoJugador = self::COLUMNA_tipoPagoJugador4;
                break;

        }

        return array(
            array($columna_idJugador, $idJugador_ReservaPista),
            array($columna_importeJugador, $importeJugador_ReservaPista),
            array($columna_pagadoJugador, $pagadoJugador_ReservaPista),
            array($columna_tipoPagoJugador, $tipoPagoJugador_ReservaPista)
        );



    }


}