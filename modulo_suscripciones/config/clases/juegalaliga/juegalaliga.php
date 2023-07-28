<?php

class Juegalaliga extends general{

    const TABLA_nombre = "juegalaliga";
    const COLUMNA_id = "id";
    const COLUMNA_idJugador = "id_Jugador";
    const COLUMNA_idLiga = "id_Liga";
    const COLUMNA_estado = "estado";
    const COLUMNA_pagadoEnLigaPack = "pagado";
    const COLUMNA_fechaInscripcion = "fecha";
    const COLUMNA_observaciones1 = "Extra1";
    const COLUMNA_observaciones2 = "Extra2";
    const COLUMNA_posicion = "posicion";
    const COLUMNA_puntuacion = "puntuacion";
    const COLUMNA_contrincantes = "contrincantes";
    const COLUMNA_partidosJugados = "pjugados";
    const COLUMNA_partidosOrganizados= "porganizados";
    const COLUMNA_partidosEmpatados = "pempatados";
    const COLUMNA_partidosPerdidos = "pperdidos";
    const COLUMNA_partidosGanados = "pganados";
    const COLUMNA_rankingInicial = "ranking_inicial";
    const COLUMNA_rakingFinal = "ranking_final";
    const COLUMNA_tipoRecordatorio = "tiporecordatorio";
    const COLUMNA_horaRecordatorio = "horarecordatorio";
    const COLUMNA_rol = "rol";
    const COLUMNA_cuotaAPagar = "CuotaPagar";
    const COLUMNA_codigoDescuento = "CodigoDescuento";
    const COLUMNA_idLigaAlias = "id_Liga_ALIAS";
    const COLUMNA_notas = "Juegalaliga_Extra1";
    const COLUMNA_fechaFinInscripcionJugadorEnLigaDelClub = "Juegalaliga_Extra2";
    const COLUMNA_fechaPagoInscripcionJugadorEnLigaDelClub = "Juegalaliga_Extra3";
    const COLUMNA_idPlan = "id_plan";
    const COLUMNA_recomendaciones = "recomendaciones";
    const COLUMNA_fechaFinSuscripcion = "fecha_fin_suscripcion";
    const COLUMNA_fechaCaducidad = "fecha_caducidad";
    const COLUMNA_primeraVez = "primera_vez";
    const COLUMNA_apuntadoConOferta = "apuntado_con_oferta";
    const COLUMNA_tokenTarjeta = "token_tarjeta";
    const COLUMNA_estadoSuscripcion = "estado_suscripcion";
    const COLUMNA_referenciaPago = "referencia_pago";
    const COLUMAN_fechaYHoraInscripcion = "fechaYHoraInscripcion";
    const COLUMNA_numeroPartidosJugadosJugador= "numeroPartidosJugadosJugador";
    const COLUMNA_numeroPartidosApuntadosJugador = "numeroPartidosApuntadoJugador";
    const COLUMNA_fechaActivacionJugadorEnPackDeRadicalPadel = "fechaActivacionEnPack";
    const COLUMNA_estadoActivacionJugadorEnPackDeRadicalPadel = "estadoActivacionEnPack";
    const COLUMNA_permitirVisitarLiga = "permitirVisitarLiga";
    const COLUMNA_certificadoMedico = "certificadoMedico";
    const COLUMNA_esPermitidoVerListadoPartidos = "esPermitidoVerListadoPartidos";
    const COLUMNA_esEnviadoEmailJugadorAQueridoApuntarse = "esEnviadoEmailJugadorAQueridoApuntarse";

    const ESTADO_SUSCRIPCION_alta = "ALTA";
    const ESTADO_SUSCRIPCION_impago = "IMPAGO";
    const ESTADO_SUSCRIPCION_baja = "BAJA";



    const TIPO_ESTADO_activo = "ACTIVO";
    const TIPO_ESTADO_pendiente = "PENDIENTE";
    const TIPO_ESTADO_preinscrito = "PREINSCRITO";
    const TIPO_ESTADO_bloqueado = "BLOQUEADO";

    const SEXO_hombre = "Hombre";
    const SEXO_mujer = "Mujer";

    const TIPO_ACTIVACION_EN_PACK_normal = 0;
    const TIPO_ACTIVACION_EN_PACK_prorrogada = 1;
    const TIPO_ACTIVACION_EN_PACK_caducadaProrroga = 2;

    const PERMITIR_VISITA_A_Liga_segunConfiguracionLiga = -1;
    const PERMITIR_VISITA_A_Liga_noPermitido = 0;
    const PERMITIR_VISITA_A_Liga_siPermitido = 1;


    const OP_permitirVisitarLiga = "permitirVisitarLiga";
    const OP_actualizarEstado = "actualizarEstado";
    const OP_actualizarEstadoBloquearDesbloquear = "actualizarEstadoBloquearDesbloquear";
    const OP_actualizarEsPermitidoVerListadoPartidos = "actualizarEsPermitidoVerListadoPartidos";
    const OP_activarDesactivarEsPermitidoVerListadoPartidos = "activarDesactivarEsPermitidoVerListadoPartidos";



    const ROL_6 = 6;

    function __construct($idJugador="", $idLiga="", $id="", $nombreTabla=self::TABLA_nombre)
    {
        global $bd;

        if ($id != ''){
            parent::__construct($nombreTabla, 'id', $id);
        }
        else{
            $id = $bd->get($nombreTabla, "id", array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));
            Log::v(__FUNCTION__, "ID: $id", false);
            if ($id != ""){
                parent::__construct($nombreTabla, 'id', $id);
            }
            else{
                parent::__construct($nombreTabla, '', '');
            }
        }

    }

    function obtenerClub(){
        return $this->obtenerLiga()->obtenerClub();
    }

    function esPermitidoVisitarLiga(){
        Log::v(__FUNCTION__, "Permitir Visitar Liga: ".$this[self::COLUMNA_permitirVisitarLiga]);

        if($this[self::COLUMNA_permitirVisitarLiga] == self::PERMITIR_VISITA_A_Liga_siPermitido){
            return true;
        }
        else if ($this[self::COLUMNA_permitirVisitarLiga] == self::PERMITIR_VISITA_A_Liga_noPermitido){
            Log::v(__FUNCTION__, "NO permitido Visitar Liga");
            return false;
        }
        else{
            return $this->obtenerLiga()->esPermitidoVisitantes();
        }
    }

    static function obtenerIdsTodos($idLiga=""){
        global $bd;

        if (!empty($idLiga)){
            $where["AND"][self::COLUMNA_idLiga] = $idLiga;
        }
        $where["ORDER"] = self::COLUMNA_id." DESC";

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, $where);

    }

    function existe(){
        $id = $this->obtenerId();

        if ($id <= 0){
            return false;
        }

        return true;
    }

    function obtenerTodosIdsJuegalaligaHistorial(){
        return JuegalaligaHistorial::obtenerTodosIdsJuegalaligaHistorialDeIdJuegalaliga($this->obtenerId());
    }

    function obtenerEstadoActivacionJugadorEnPackDeRadicalPadel(){
        return $this[self::COLUMNA_estadoActivacionJugadorEnPackDeRadicalPadel];
    }

    function esPermitidoVerListadoDePartidos(){
        if ($this[self::COLUMNA_esPermitidoVerListadoPartidos] == 1){
            return true;
        }

        return false;
    }

    function esEnviadoEmailJugadorAQueridoApuntarse(){
        if ($this[self::COLUMNA_esEnviadoEmailJugadorAQueridoApuntarse] == 1){
            return true;
        }

        return false;
    }

    function obtenerNombreTipoEstadoActivacionInscripcionEnPack(){

        $idTipoEstadoActivacionEnPack = $this->obtenerEstadoActivacionJugadorEnPackDeRadicalPadel();

        switch ($idTipoEstadoActivacionEnPack){

            case self::TIPO_ACTIVACION_EN_PACK_normal:
                return Traductor::traducir("PACK");
                break;

            case self::TIPO_ACTIVACION_EN_PACK_prorrogada:
                return Traductor::traducir("PRORROGADA");
                break;

            case self::TIPO_ACTIVACION_EN_PACK_caducadaProrroga:
                return Traductor::traducir("CADUCADA");
                break;

            default:
                return Traductor::traducir("S/N");
        }
    }


    function guardarEnHistorial(){
        $JuegalaligaHistorial = new JuegalaligaHistorial();
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_idJuegalaliga] = $this[self::COLUMNA_id];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_idJugador] = $this[self::COLUMNA_idJugador];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_idLiga] = $this[self::COLUMNA_idLiga];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_estado] = $this[self::COLUMNA_estado];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_pagadoEnLigaPack] = $this[self::COLUMNA_pagadoEnLigaPack];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_fechaInscripcion] = $this[self::COLUMNA_fechaInscripcion];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_observaciones1] = $this[self::COLUMNA_observaciones1];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_observaciones2] = $this[self::COLUMNA_observaciones2];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_posicion] = $this[self::COLUMNA_posicion];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_puntuacion] = $this[self::COLUMNA_puntuacion];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_contrincantes] = $this[self::COLUMNA_contrincantes];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_partidosJugados] = $this[self::COLUMNA_partidosJugados];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_partidosOrganizados] = $this[self::COLUMNA_partidosOrganizados];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_partidosEmpatados] = $this[self::COLUMNA_partidosEmpatados];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_partidosPerdidos] = $this[self::COLUMNA_partidosPerdidos];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_partidosGanados] = $this[self::COLUMNA_partidosGanados];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_rankingInicial] = $this[self::COLUMNA_rankingInicial];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_rakingFinal] = $this[self::COLUMNA_rakingFinal];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_tipoRecordatorio] = $this[self::COLUMNA_tipoRecordatorio];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_horaRecordatorio] = $this[self::COLUMNA_horaRecordatorio];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_rol] = $this[self::COLUMNA_rol];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_cuotaAPagar] = $this[self::COLUMNA_cuotaAPagar];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_codigoDescuento] = $this[self::COLUMNA_codigoDescuento];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_idLigaAlias] = $this[self::COLUMNA_idLigaAlias];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_notas] = $this[self::COLUMNA_notas];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_fechaFinInscripcionJugadorEnLigaDelClub] = $this[self::COLUMNA_fechaFinInscripcionJugadorEnLigaDelClub];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_fechaPagoInscripcionJugadorEnLigaDelClub] = $this[self::COLUMNA_fechaPagoInscripcionJugadorEnLigaDelClub];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_idPlan] = $this[self::COLUMNA_idPlan];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_recomendaciones] = $this[self::COLUMNA_recomendaciones];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_fechaFinSuscripcion] = $this[self::COLUMNA_fechaFinSuscripcion];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_fechaCaducidad] = $this[self::COLUMNA_fechaCaducidad];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_primeraVez] = $this[self::COLUMNA_primeraVez];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_apuntadoConOferta] = $this[self::COLUMNA_apuntadoConOferta];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_tokenTarjeta] = $this[self::COLUMNA_tokenTarjeta];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_estadoSuscripcion] = $this[self::COLUMNA_estadoSuscripcion];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_referenciaPago] = $this[self::COLUMNA_referenciaPago];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMAN_fechaYHoraInscripcion] = $this[self::COLUMAN_fechaYHoraInscripcion];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_numeroPartidosJugadosJugador] = $this[self::COLUMNA_numeroPartidosJugadosJugador];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_numeroPartidosApuntadosJugador] = $this[self::COLUMNA_numeroPartidosApuntadosJugador];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_fechaActivacionJugadorEnPackDeRadicalPadel] = $this[self::COLUMNA_fechaActivacionJugadorEnPackDeRadicalPadel];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_estadoActivacionJugadorEnPackDeRadicalPadel] = $this[self::COLUMNA_estadoActivacionJugadorEnPackDeRadicalPadel];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_permitirVisitarLiga] = $this[self::COLUMNA_permitirVisitarLiga];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_certificadoMedico] = $this[self::COLUMNA_certificadoMedico];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_esPermitidoVerListadoPartidos] = $this[self::COLUMNA_esPermitidoVerListadoPartidos];
        $JuegalaligaHistorial[JuegalaligaHistorial::COLUMNA_valoresSesionGuardadoHistorial] = json_encode($_SESSION);
        $JuegalaligaHistorial->guardar();

    }

    function guardar($valor = null, $nombreTablaClonar = "", $noGuardarEnHistorial=false)
    {
        $id = parent::guardar($valor, $nombreTablaClonar); // TODO: Change the autogenerated stub



        if ($noGuardarEnHistorial == false){
            //JMAM: Guardado en historial
            $Juegalaliga = new Juegalaliga("","",$this->obtenerId());
            $Juegalaliga->guardarEnHistorial();
        }

        return $id;
    }

    function esPagadoInscripcionJugadorEnLigaDelClub(){

        if ($this->obtenerImportePagadoJugadorEnLigaPack() > 0){
            return true;
        }

        return false;
    }

    function existeFechaFinInscripcionJugadorEnLigaDelClub(){

        if ($this[self::COLUMNA_fechaFinInscripcionJugadorEnLigaDelClub] == "0000-00-00"){
            return false;
        }

        return true;
    }


    function obtenerFechaPagoInscripcionJugadorEnLigaDelClub(){
        return $this[self::COLUMNA_fechaPagoInscripcionJugadorEnLigaDelClub];
    }

    function obtenerJuegalaligaEdicionAnterior(){
        Log::v(__FUNCTION__, "ID Jugador: ".$this->obtenerJugador()->obtenerId()." | ID Liga: ".$this->obtenerLiga()->obtenerLigaEdicionAnterior()->obtenerId(), false);
        return new Juegalaliga($this->obtenerJugador()->obtenerId(), $this->obtenerLiga()->obtenerLigaEdicionAnterior()->obtenerId());
    }

    function obtenerFechaFinInscripcionJugadorEnLigaDelClub($sinAutocalcular=false){
        $Liga = $this->obtenerLiga();
        $fechaFinInscripcionJugadorEnLigaDelCub = $this[self::COLUMNA_fechaFinInscripcionJugadorEnLigaDelClub];

        if ($sinAutocalcular){
            return $fechaFinInscripcionJugadorEnLigaDelCub;
        }


        if ($fechaFinInscripcionJugadorEnLigaDelCub == "0000-00-00" && ($this->obtenerEstado() == self::TIPO_ESTADO_activo || $this->obtenerJuegalaligaEdicionAnterior()->obtenerEstado() == self::TIPO_ESTADO_activo || ($this->obtenerJuegalaligaEdicionAnterior()->existe() && $this->obtenerJuegalaligaEdicionAnterior()->obtenerFechaActivacionJugadorEnPackDeRadicalPadel(true) != "0000-00-00"))){

            if ($this->obtenerLiga()->obtenerClub()->obtenerNumeroDiasJugadorInscritoEnClub() > 0){

                if (Fecha::fechaYhoraMYSQLANormal($this->obtenerFechaYHoraInscripcion()) != "0000-00-00"){
                    return Fecha::anadirDiasAFecha($this->obtenerFechaYHoraInscripcion(true), $this->obtenerLiga()->obtenerClub()->obtenerNumeroDiasJugadorInscritoEnClub());
                }
                else{
                    return Fecha::anadirDiasAFecha($Liga->obtenerFechaInicio(), $this->obtenerLiga()->obtenerClub()->obtenerNumeroDiasJugadorInscritoEnClub());
                }
            }
            else if ($this->obtenerEstado() == self::TIPO_ESTADO_activo){
                return $Liga->obtenerFechaFin();
            }
        }

        return $fechaFinInscripcionJugadorEnLigaDelCub;

    }

    function obtenerNumeroDeDiasRestantesParaFinInscripcionJugadorEnLigaDelClub(){

        $numeroDiasRestantes = Fecha::obtenerDiferenciaDiasEntreFechas(date("Y-m-d"), $this->obtenerFechaFinInscripcionJugadorEnLigaDelClub());
        Log::v(__FUNCTION__, "Número dias. $numeroDiasRestantes", false);
        return $numeroDiasRestantes;
    }

    function esFinalizadaInscripcionJugadorEnLigaDelClub(){
        $fechaFinInscripcionEnLigaPack = $this->obtenerFechaFinInscripcionJugadorEnLigaDelClub();

        if ($fechaFinInscripcionEnLigaPack == "0000-00-00"){
            return false;
        }
        else{
            if ($this->obtenerNumeroDeDiasRestantesParaFinInscripcionJugadorEnLigaDelClub() >= 0){
                return false;
            }
        }

        return true;
    }

    function obtenerObservaciones1(){
        return $this[self::COLUMNA_observaciones1];
    }

    function obtenerObservaciones2(){
        return $this[self::COLUMNA_observaciones2];
    }

    function obtenerRol(){
        return $this[self::COLUMNA_rol];
    }




    function obtenerFechaActivacionJugadorEnPackDeRadicalPadel($sinAutoCalcular=false){


        $fechaActivacionEnPack =  $this[self::COLUMNA_fechaActivacionJugadorEnPackDeRadicalPadel];
        if ($sinAutoCalcular){
            return $fechaActivacionEnPack;
        }

        $fechaIniscripcion = $this->obtenerFechaInscripcion();
        Log::v(__FUNCTION__, "Fecha Inscripción: $fechaIniscripcion", false);

        if ($fechaActivacionEnPack != "0000-00-00"){
            Log::v(__FUNCTION__, "Obtener Fecha Activación en Pack", false);
            return $fechaActivacionEnPack;
        }
        else if ($fechaIniscripcion != "0000-00-00"){
            Log::v(__FUNCTION__, "Obtener Fecha Inscripción", false);
            return $fechaIniscripcion;
        }
        else{
            $Liga = $this->obtenerLiga()->obtenerLigaEdicionAnterior();
            Log::v(__FUNCTION__, "ID LIGA EDICION ANTERIOR: ".$Liga->obtenerId()." | ".$this->obtenerJugador()->obtenerId(), false);
            $Juegalaliga = new Juegalaliga($this->obtenerJugador()->obtenerId(), $Liga->obtenerId());

            if ($Juegalaliga->existe()){
                Log::v(__FUNCTION__, "Fecha EDICION ANTERIOR: ".$Liga->obtenerId()." | ".$Juegalaliga->obtenerFechaActivacionJugadorEnPackDeRadicalPadel(), false);
                return  Fecha::fechaYhoraMYSQLASoloFechaMYSQL($Juegalaliga->obtenerFechaYHoraInscripcion());
            }
            else{
                return Fecha::fechaYhoraMYSQLASoloFechaMYSQL($this->obtenerFechaYHoraInscripcion());
            }
        }
    }

    function actualizarFechaFinActivacionJugadorEnPackDeRadicalPadel($fechaFinActivacionEnPackDeRadicalPadel){
        $idClub = $this->obtenerLiga()->obtenerIdsClubs()[0];
        Log::v(__FUNCTION__, "ID CLUB: $idClub");
        $Club = new Club($idClub);

        $numeroDiasInscripcionEnPackDeRadicalPadel = $Club->obtenerNumeroDiasJugadorActivoEnPackDeRadicalPadel();
        if ($numeroDiasInscripcionEnPackDeRadicalPadel > 0 && !empty($fechaFinActivacionEnPackDeRadicalPadel)){
            $fechaActivacionJugadorEnPackDeRadicalPadel = Fecha::restarDiasAFecha($fechaFinActivacionEnPackDeRadicalPadel, $numeroDiasInscripcionEnPackDeRadicalPadel);
            $this[self::COLUMNA_fechaActivacionJugadorEnPackDeRadicalPadel] = $fechaActivacionJugadorEnPackDeRadicalPadel;
            $this->guardar();
            Log::v(__FUNCTION__, "Fecha Activación en Pack de RadicalPadel: ".$fechaFinActivacionEnPackDeRadicalPadel, true);
        }

        Log::v(__FUNCTION__, "Numero Días: $numeroDiasInscripcionEnPackDeRadicalPadel | Fecha Fin Activación en Pack RadicalPadel: $fechaFinActivacionEnPackDeRadicalPadel", true);

    }

    function obtenerFechaFinActivacionJugadorEnPackDeRadicalPadel($sinAutoCalcular=false){


        if ($sinAutoCalcular){
            return $this->obtenerFechaFinInscripcionJugadorEnLigaDelClub($sinAutoCalcular);

        }

        $idClub = $this->obtenerLiga()->obtenerClub()->obtenerID();
        Log::v(__FUNCTION__, "ID CLUB: $idClub");
        $Club = new Club($idClub);

        $numeroDiasInscripcionEnPackDeRadicalPadel = $Club->obtenerNumeroDiasJugadorActivoEnPackDeRadicalPadel();
        if ($numeroDiasInscripcionEnPackDeRadicalPadel > 0){
            return Fecha::anadirDiasAFecha($this->obtenerFechaActivacionJugadorEnPackDeRadicalPadel(), $numeroDiasInscripcionEnPackDeRadicalPadel);
        }
        else{
            return $this->obtenerLiga()->obtenerFechaFin();
        }
    }

    function obtenerNumeroDeDiasRestantesParaFinActivacionJugadorEnPackDeRadicalPadel(){

        $hoy = date("Y-m-d");
        //$hoy = "2022-06-27";

        if ($this->obtenerFechaFinActivacionJugadorEnPackDeRadicalPadel() == "0000-00-00"){
            return 0;
        }


        return Fecha::obtenerDiferenciaDiasEntreFechas($hoy, $this->obtenerFechaFinActivacionJugadorEnPackDeRadicalPadel());
    }

    function esFinalizadaActivacionJugadorEnPackDeRadicalPadel(){
        $fechaFinInscripcionEnLigaPack = $this->obtenerFechaFinActivacionJugadorEnPackDeRadicalPadel();
        $numeroDiasRestantes = $this->obtenerNumeroDeDiasRestantesParaFinActivacionJugadorEnPackDeRadicalPadel();
        Log::v(__FUNCTION__, $numeroDiasRestantes, false);

        if ($fechaFinInscripcionEnLigaPack == "0000-00-00"){
            return false;
        }
        else{
            if ($this->obtenerNumeroDeDiasRestantesParaFinActivacionJugadorEnPackDeRadicalPadel() > 0){
                return false;
            }
        }

        return true;
    }


    function obtenerFechaInscripcion(){
        return $this[self::COLUMNA_fechaInscripcion];
    }


    function obtenerFechaYHoraInscripcion($soloFecha=false){

        if ($soloFecha){
            return Fecha::fechaYhoraMYSQLASoloFechaMYSQL($this[self::COLUMAN_fechaYHoraInscripcion]);
        }
        else{
            return $this[self::COLUMAN_fechaYHoraInscripcion];

        }
    }

    function esJugadorJugadoAPartidos(){

        if ($this[self::COLUMNA_numeroPartidosJugadosJugador] > 0){
            return true;
        }

        return false;
    }

    function esAlgunaVezApuntadoAPartido(){
        return PartidoJugador::esAlgunaVezApuntadoAPartidoEnIdLigaIdJugador($this->obtenerLiga()->obtenerId(), $this->obtenerJugador()->obtenerId());
    }

    function obtenerEstado(){
        return $this[self::COLUMNA_estado];
    }

    function obtenerPlan(){
        return new Plan($this[self::COLUMNA_idPlan]);
    }

    function obtenerFechaFinSuscripcionPlan(){
        return $this[self::COLUMNA_fechaFinSuscripcion];
    }

    function obtenerEstadoSuscripcionPlan(){
        return $this[self::COLUMNA_estadoSuscripcion];
    }

    function obtenerReferenciaPago(){
        return $this[self::COLUMNA_referenciaPago];
    }

    function esAlgunaVezJugadorEstado($estado, $idEstadoActivacionEnPack){
        return JuegalaligaHistorial::esAlgunaVezJugadorEstadoEnJuegalaligaHistorial($this->obtenerId(),$estado, $idEstadoActivacionEnPack);
    }

    function obtenerCertificadoMedico(){
        return $this[self::COLUMNA_certificadoMedico];
    }

    function esImportePagadoEntreFechaActivacionJugadorEnPackDeRadicalPadelYFechaFinInscripcionJugadorEnLigaDelClub(){
        if ($this->esPagadoInscripcionJugadorEnLigaDelClub()){
            $fechaPagoInscripcionJugadorEnLigaDelClub = $this->obtenerFechaPagoInscripcionJugadorEnLigaDelClub();
            $fechaActivacionJugadorEnPackDeRadicalPadel = $this->obtenerFechaActivacionJugadorEnPackDeRadicalPadel();
            $fechaFinInscripcionJugadorEnLaLigaDelClub = $this->obtenerFechaFinInscripcionJugadorEnLigaDelClub();

            return Fecha::esFecha1EntreFecha2YFecha3($fechaPagoInscripcionJugadorEnLigaDelClub, $fechaActivacionJugadorEnPackDeRadicalPadel, $fechaFinInscripcionJugadorEnLaLigaDelClub);

        }
        else{
            return false;
        }

    }

    static function actualizarEstadoActivacionJugadorEnPackDeRadicalPadel($idLiga,$idJugador, $estadoActivacionJugadorEnPackDeRadicalPadel){
        $Juegalaliga = new Juegalaliga($idJugador, $idLiga);
        $Juegalaliga[self::COLUMNA_estadoActivacionJugadorEnPackDeRadicalPadel] = $estadoActivacionJugadorEnPackDeRadicalPadel;
        $Juegalaliga->guardar();
    }

    static function actualizarFechaActivacionJugadorEnPackDeRadicalPadel($idLiga,$idJugador, $fechaActivacionJugadorEnPackDeRadicalPadel){
        $Juegalaliga = new Juegalaliga($idJugador, $idLiga);
        $Juegalaliga[self::COLUMNA_fechaActivacionJugadorEnPackDeRadicalPadel] = $fechaActivacionJugadorEnPackDeRadicalPadel;
        $Juegalaliga->guardar();
    }

    static function actualizarPagoYFechaInicioInscripcionJugadorEnLigaDelClub($idLiga, $idJugador, $importe, $fechaPagoInscripcionEnLiga){
        $Liga = new Liga($idLiga);
        $Juegalaliga = new Juegalaliga($idJugador, $idLiga);
        $Juegalaliga[Juegalaliga::COLUMNA_pagadoEnLigaPack] = $importe;
        $Juegalaliga[Juegalaliga::COLUMNA_fechaPagoInscripcionJugadorEnLigaDelClub] = $fechaPagoInscripcionEnLiga;
        $Juegalaliga[Juegalaliga::COLUMNA_fechaFinInscripcionJugadorEnLigaDelClub] = Fecha::anadirDiasAFecha($fechaPagoInscripcionEnLiga, $Liga->obtenerClub()->obtenerNumeroDiasJugadorInscritoEnClub());
        $Juegalaliga->guardar();
    }

    static function actualizarEstadoActivacionInscripcionEnPackParaTodosJugadoresEnLaLiga($idLiga){
        $array_idsJugadores = self::obtenerIdsJugadoresEnLiga($idLiga, self::TIPO_ESTADO_activo, "", self::TIPO_ACTIVACION_EN_PACK_normal);
        foreach ($array_idsJugadores as $idJugador){
            $Juegalaliga = new Juegalaliga($idLiga, $idJugador);
            if($Juegalaliga->esFinalizadaActivacionJugadorEnPackDeRadicalPadel()){
                $Juegalaliga[self::COLUMNA_estado] = self::TIPO_ESTADO_pendiente;
                $Juegalaliga->guardar();
            }
        }

        $array_idsJugadores = self::obtenerIdsJugadoresEnLiga($idLiga, self::TIPO_ESTADO_activo, "", self::TIPO_ACTIVACION_EN_PACK_prorrogada);
        foreach ($array_idsJugadores as $idJugador){
            $Juegalaliga = new Juegalaliga($idLiga, $idJugador);
            if($Juegalaliga->esFinalizadaActivacionJugadorEnPackDeRadicalPadel()){
                $Juegalaliga[self::COLUMNA_estado] = self::TIPO_ESTADO_pendiente;
                $Juegalaliga[self::COLUMNA_estadoActivacionJugadorEnPackDeRadicalPadel] = self::TIPO_ACTIVACION_EN_PACK_caducadaProrroga;
                $Juegalaliga->guardar();
            }
        }
    }


    static function actualizarTodosNumeroPartidosApuntadoJugadorEnLiga(){
        $array_idsJuegalaliga = self::obtenerIdsTodos();
        foreach ($array_idsJuegalaliga as $idJuegalaliga){
            $Juegalaliga = new Juegalaliga("","", $idJuegalaliga);
            self::actualizarNumeroPartidosApuntadoJugadorEnLiga($Juegalaliga->obtenerJugador()->obtenerId(), $Juegalaliga->obtenerLiga()->obtenerId());
            Log::v(__FUNCTION__, "Número Partidos Jugados Actualizados para ID JUEGALALIGA: $idJuegalaliga", false);
        }
    }

    static function actualizarNumeroPartidosApuntadoJugadorEnLiga($idJugador, $idLiga){

        $Jugador = new Jugador($idJugador);
        $numeroPartidosApuntado = $Jugador->obtenerNumeroPartidosApuntado($idLiga);
        $Juegalaliga = new Juegalaliga($idJugador, $idLiga);
        $Juegalaliga[self::COLUMNA_numeroPartidosApuntadosJugador] =  $numeroPartidosApuntado;
        $Juegalaliga->guardar();

        Log::v(__FUNCTION__, "Numero partidos APUNTAOS actualizados: ($numeroPartidosApuntado)", false);


    }

    static function actualizarTodosNumeroPartidosJugadosJugadorEnLiga(){
        $array_idsJuegalaliga = self::obtenerIdsTodos();
        foreach ($array_idsJuegalaliga as $idJuegalaliga){
            $Juegalaliga = new Juegalaliga("","", $idJuegalaliga);
            self::actualizarNumeroPartidosJugadosJugadorEnLiga($Juegalaliga->obtenerJugador()->obtenerId(), $Juegalaliga->obtenerLiga()->obtenerId());
            Log::v(__FUNCTION__, "Número Partidos Jugados Actualizados para ID JUEGALALIGA: $idJuegalaliga", false);
        }
    }

    static function actualizarNumeroPartidosJugadosJugadorEnLiga($idJugador, $idLiga){

        $Jugador = new Jugador($idJugador);
        $numeroPartidosJugados = $Jugador->obtenerNumeroDePartidosJugados($idLiga);
        $Juegalaliga = new Juegalaliga($idJugador, $idLiga);
        $Juegalaliga[self::COLUMNA_numeroPartidosJugadosJugador] =  $numeroPartidosJugados;
        $Juegalaliga->guardar();

        Log::v(__FUNCTION__, "Numero partidos JUGADOS actualizados: ($numeroPartidosJugados)", false);


    }

    static function anadirJugadorALaLiga($idJugador, $idLiga, $estado){
        $Juegalaliga = new Juegalaliga();
        $Juegalaliga[self::COLUMNA_idJugador] = $idJugador;
        $Juegalaliga[self::COLUMNA_idLiga] = $idLiga;
        $Juegalaliga[self::COLUMNA_estado] = $estado;
        $Juegalaliga->guardar();
    }

    static function actualizarPlanJugador($idLiga, $idJugador, $idPlan){
        global $bd;

        $bd->update(self::TABLA_nombre, array(self::COLUMNA_idPlan => $idPlan), array("AND" => array(self::COLUMNA_idLiga => $idLiga, self::COLUMNA_idJugador => $idJugador)));
    }

    static function obtenerEstadoJugador($idJugador, $idLiga){
        global $bd;

        return $bd->get(self::TABLA_nombre, self::COLUMNA_estado, array("AND" => array(self::COLUMNA_idJugador => $idJugador, self::COLUMNA_idLiga => $idLiga)));
    }


    static function esJugadorActivoEnLiga($idJugador, $idLiga){
        Log::v(__FUNCTION__, "ID Jugador: $idJugador | ID Liga: $idLiga", false);

        $Juegalaliga = new Juegalaliga($idJugador, $idLiga);
        if ($Juegalaliga->obtenerEstado() == self::TIPO_ESTADO_activo){
            return true;
        }

        return false;

    }

    static function obtenerRolJugadorEnLiga($idJugador, $idLiga){
        global $bd;
        $rol =  $bd->get("juegalaliga", "rol", array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));

        return $rol;
    }

    static function actualizarRolJugadorEnLiga($idJugador, $idLiga, $rol){
        global $bd;
        $bd->update(self::TABLA_nombre, array(self::COLUMNA_rol => $rol), array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));

    }

    static function obtenerIdsLigasRolJugador($idJugador, $rol, $estado=""){
        global $bd;

        $where["AND"][self::COLUMNA_idJugador] = $idJugador;
        $where["AND"][self::COLUMNA_rol] = $rol;


        $array_idsLigas =  $bd->select(self::TABLA_nombre, self::COLUMNA_idLiga, $where);

        if (!empty($estado)){
            for ($i = 0; $i < count($array_idsLigas); $i++){
                $Liga = new Liga($array_idsLigas[$i]);

                if ($Liga->esTerminada()){
                    unset($array_idsLigas[$i]);
                }
            }
        }

        return $array_idsLigas;

    }


    static function esPrimeraVezEnLiga($idJugador, $idLiga , $estado = "" , $planPago = null){
        global $bd;

        $arraySearch = array("id_Jugador" => $idJugador, "id_Liga" => $idLiga);

        if ($estado != ""){
            $arraySearch["estado"] = $estado;
        }

        if ($planPago != null){

            if ($planPago == true){
                $arraySearch["id_plan[>]"] = 0;
            }
            else if ($planPago == false){
                $arraySearch["id_plan[<=]"] = 0;
            }
        }


        $apariciones =  $bd->count("juegalaliga", array("AND" => $arraySearch));

        if ($apariciones == 0){
            return true;
        }
        else{
            return false;
        }
    }

    static function actualizarEstadoJugadorPack($idJugador, $idLiga, $estado){
        global $bd;


        //JMAM: Se asegura de actualizar el Estado de Suscripción en la liga indicada (para cuando queremos que funcione por liga)
        return $bd->update("juegalaliga", array("estado" => $estado), array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));
    }



    static function apuntarJugadorEnLigaPack($idJugador, $idLiga, $idClub){
        require_once BASE."PCU/funciones_PACKs.php";
        global $bd;

        $Liga = new Liga($idLiga);
        $Jugador = new Jugador($idJugador);
        $numeroInscripcionesDisponibles = $Liga->obtenerNumeroInscripcionesPacksDisponiblesTotalesEnLaLiga($idLiga, $idClub);

        /*
        if ($_SESSION['S_permitidoVerSoloPacksDeLigasAdministradas'] == 1){

            $array_idsLigas = explode(",", $_SESSION['S_Ligas']);
            foreach ($array_idsLigas as $idLiga){
                $numeroInscripcionesDisponibles += Pack::obtenerNumeroInscripcionesDisponiblesEnLaLiga($idLiga, $idClub);
            }
        }
        else{
            $numeroInscripcionesDisponibles = Pack::obtenerNumeroInscripcionesDisponiblesEnLaLiga($idLiga, $idClub);
        }
        */

        Log::v(__FUNCTION__, "Número de Inscripciones Disponibles: $numeroInscripcionesDisponibles", false);


        if ($numeroInscripcionesDisponibles > 0){

            if(self::esPrimeraVezEnLiga($idJugador, $idLiga)){
                $fechaHoy = date('Y-m-j');

                $arrayDatos = array(
                    "id_Jugador" => $idJugador,
                    "id_Liga" => $idLiga,
                    "estado" => "ACTIVO",
                    "fecha" => $fechaHoy,
                );
                $idJuegalaliga = $bd->insert("juegalaliga", $arrayDatos);

                $Juegalaliga = new Juegalaliga($idJugador,$idLiga);
                $Juegalaliga[Juegalaliga::COLUMNA_fechaActivacionJugadorEnPackDeRadicalPadel] = date("Y-m-d");
                $Juegalaliga[Juegalaliga::COLUMNA_estadoActivacionJugadorEnPackDeRadicalPadel] = Juegalaliga::TIPO_ACTIVACION_EN_PACK_normal;
                $Juegalaliga->guardar();

            }
            else{
                self::actualizarEstadoJugadorPack($idJugador, $idLiga, "ACTIVO");
            }

            recalcula_packs_de_liga($idLiga);
            return true;
        }
        else{
            return false;
        }

    }


    static function obtenerPlanSuscripcion($idJugador, $idLiga = ""){
        global $bd;

        if ($idLiga != ""){
            $idPlan = $bd->get("juegalaliga", "id_plan", array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));
        }
        else{
            $idPlan = -1;
        }

        //JMAM: Comprueba si se ha encontrado el Plan de Suscripción
        if ($idPlan == ""){
            //JMAM: No se ha encontrado Plan de Suscricpión

            //JMAM: Indicar que Plan No es Suscripción
            $idPlan = -1;
        }

        $Plan = new Plan($idPlan);
        return $Plan;

    }

    static function obtenerPlanesSuscripcionJugador($idJugador, $fechaFinSuscripcion, $estadoSuscripcion){
        global $bd;


        $filtros = "";

        if ($fechaFinSuscripcion != ""){
            $filtros .= " AND fecha_fin_suscripcion = '$fechaFinSuscripcion'";
        }

        if ($estadoSuscripcion != ""){
            $filtros .= " AND estado_suscripcion = '$estadoSuscripcion'";
        }


        $planesObtenidos = $bd->query("SELECT id_plan FROM juegalaliga WHERE id_Jugador = $idJugador $filtros")->fetchAll();

        $planes = array();
        foreach ($planesObtenidos as $planObtenido) {
            array_push($planes,new Plan($planObtenido["id_plan"]));
        }

        return $planes;
    }


    static function obtenerPlanSuscripcionMayorJugador($idJugador, $fechaFinSuscripcion, $estadoSuscripcion){
        global $bd;


        $filtros = "";

        if ($fechaFinSuscripcion != ""){
            $filtros .= " AND fecha_fin_suscripcion = '$fechaFinSuscripcion'";
        }

        if ($estadoSuscripcion != ""){
            $filtros .= " AND estado_suscripcion = '$estadoSuscripcion'";
        }


        $planObtenido = $bd->query("SELECT id_plan FROM juegalaliga WHERE id_Jugador = $idJugador $filtros AND id_Liga IN (SELECT id FROM liga WHERE idJugadorAdministrador != $idJugador) ORDER BY id_plan DESC LIMIT 0,1")->fetchAll();
        $plan = $planObtenido[0];
        if ($plan["id_plan"] != ""){
            return new Plan($plan["id_plan"]);
        }
        else{

            return new Plan(-1);
        }
    }

    static function obtenerEstadoSuscripcionPlanSuscripcionMayor($idJugador, $fechaFinSuscripcion="", $estadoSuscripcion=""){

        global $bd;


        $filtros = "";

        if ($fechaFinSuscripcion != ""){
            $filtros .= " AND fecha_fin_suscripcion = '$fechaFinSuscripcion'";
        }

        if ($estadoSuscripcion != ""){
            $filtros .= " AND estado_suscripcion = '$estadoSuscripcion'";
        }


        $estadoSuscripcionObtenido = $bd->query("SELECT estado_suscripcion FROM juegalaliga WHERE id_Jugador = $idJugador $filtros AND id_Liga IN (SELECT id FROM liga WHERE idJugadorAdministrador != $idJugador) ORDER BY id_plan DESC LIMIT 0,1")->fetchAll();

        if ($estadoSuscripcionObtenido != ""){

            $estadoSuscripcion = $estadoSuscripcionObtenido[0];
            return $estadoSuscripcion["estado_suscripcion"];
        }

        return new null;
    }

    static function obtenerFechaFinSuscripcionPlanSuscripcionMayor($idJugador, $fechaFinSuscripcion="", $estadoSuscripcion=""){

        global $bd;


        $filtros = "";

        if ($fechaFinSuscripcion != ""){
            $filtros .= " AND fecha_fin_suscripcion = '$fechaFinSuscripcion'";
        }

        if ($estadoSuscripcion != ""){
            $filtros .= " AND estado_suscripcion = '$estadoSuscripcion'";
        }


        $fechaFinSuscripcionObtenida = $bd->query("SELECT fecha_fin_suscripcion FROM juegalaliga WHERE id_Jugador = $idJugador $filtros AND id_Liga IN (SELECT id FROM liga WHERE idJugadorAdministrador != $idJugador) ORDER BY id_plan DESC LIMIT 0,1")->fetchAll();

        if ($fechaFinSuscripcionObtenida != ""){

            $fechaFinSuscripcionPlan = $fechaFinSuscripcionObtenida[0];
            return $fechaFinSuscripcionPlan["fecha_fin_suscripcion"];
        }

        return new null;
    }


    static function obtenerLigaPlanSuscripcionMayorJugador($idJugador, $fechaFinSuscripcion, $estadoSuscripcion){
        global $bd;

        $Jugador = new Jugador($idJugador);
        $Plan = $Jugador->obtenerPlanSuscripcionMayor($fechaFinSuscripcion,$estadoSuscripcion);
        $idPlan = $Plan["id"];

        $filtros = "";

        if ($fechaFinSuscripcion != ""){
            $filtros .= " AND fecha_fin_suscripcion = '$fechaFinSuscripcion'";
        }

        if ($estadoSuscripcion != ""){
            $filtros .= " AND estado_suscripcion = '$estadoSuscripcion'";
        }


        $ligaObtenida = $bd->query("SELECT MAX(id_Liga) AS id_Liga FROM juegalaliga WHERE id_Jugador = $idJugador $filtros AND id_Liga IN (SELECT id FROM liga WHERE idJugadorAdministrador != $idJugador) AND id_plan = $idPlan LIMIT 0,1")->fetchAll();

        if ($ligaObtenida != ""){

            $liga = $ligaObtenida[0];
            return new Liga($liga["id_Liga"]);
        }

        return new null;
    }

    static function obtenerTarjetaPlanSuscripcionMayorJugador($idJugador, $fechaFinSuscripcion, $estadoSuscripcion){
        global $bd;


        $filtros = "";

        if ($fechaFinSuscripcion != ""){
            $filtros .= " AND fecha_fin_suscripcion = '$fechaFinSuscripcion'";
        }

        if ($estadoSuscripcion != ""){
            $filtros .= " AND estado_suscripcion = '$estadoSuscripcion'";
        }


        $tokenObtenido = $bd->query("SELECT token_tarjeta FROM juegalaliga WHERE id_Jugador = $idJugador $filtros AND id_Liga IN (SELECT id FROM liga WHERE idJugadorAdministrador != $idJugador) ORDER BY id_plan ASC LIMIT 0,1")->fetchAll();

        if (count($tokenObtenido) > 0){

            $tokenTarjeta = $tokenObtenido[0];
            return Tarjeta::obtenerTarjetaDesdeToken($idJugador, $tokenTarjeta);
        }

        return null;
    }

    static function  obtenerFechaFinSuscripcion($idJugador, $idLiga){
        global $bd;

        $fechaFinSuscripcionAnterior = $bd->get("juegalaliga", "fecha_fin_suscripcion", array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));
        if ($fechaFinSuscripcionAnterior == "0000-00-00"){
            return "";
        }

        $fechaFinSuscripcionAnterior = strtotime($fechaFinSuscripcionAnterior);
        return date('Y-m-j',$fechaFinSuscripcionAnterior);
    }

    static function obtenerEstadoSuscripcion($idJugador, $idLiga){

        global $bd;

        return $bd->get("juegalaliga", "estado_suscripcion", array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));
    }

    static function  obtenerNumeroJugadoresConPlan($idLiga, $idPlan){
        global $bd;

        return $bd->count("juegalaliga", array("AND" => array("id_Liga" => $idLiga, "id_plan" => $idPlan)));
    }

    static function  obtenerNumeroJugadores($idLiga){
        global $bd;

        return $bd->count("juegalaliga", array("id_Liga" => $idLiga));
    }

    static function obtenerIdsJugadores($idLiga, $estado="", $fechaFinActivacionEnLigaPackMayorOIgual="", $idEstadoActivacionEnPack=-1){
        global $bd;

        Log::v(__FUNCTION__, "FECHA FIN ACTIVACION: $fechaFinActivacionEnLigaPackMayorOIgual ID ACTIVACION EN PACK: $idEstadoActivacionEnPack", true);

        $where["AND"][self::COLUMNA_idLiga] = $idLiga;

        if (!empty($estado)){
            $where["AND"][self::COLUMNA_estado] = $estado;
        }
        
        if ($idEstadoActivacionEnPack != -1){
            $where["AND"][self::COLUMNA_estadoActivacionJugadorEnPackDeRadicalPadel] = $idEstadoActivacionEnPack;
        }



        $array_ids =  $bd->select(self::TABLA_nombre, self::COLUMNA_idJugador, $where);

        if (!empty($fechaFinActivacionEnLigaPackMayorOIgual)){
            Log::v(__FUNCTION__, "Comprobando fecha de fin Activación", false);
            // var_dump($array_ids);
            $liga_object=new Liga($idLiga);
            $club_object=$liga_object->obtenerClub();
            $idClub =$club_object->obtenerID();
            $numeroDiasInscripcionEnPackDeRadicalPadel = $club_object->obtenerNumeroDiasJugadorActivoEnPackDeRadicalPadel();

            foreach ($array_ids as $id){
                $Juegalaliga = new Juegalaliga($id);
                if (Fecha::obtenerDiferenciaDiasEntreFechas($fechaFinActivacionEnLigaPackMayorOIgual, $Juegalaliga->obtenerFechaFinActivacionJugadorEnPackDeRadicalPadelNew($numeroDiasInscripcionEnPackDeRadicalPadel)) <0 ){
                    $array_ids = array_diff($array_ids, array($id));
                }
            }
        }
        return $array_ids;
    }

    //Nueva Función Optimizada
    function obtenerFechaFinActivacionJugadorEnPackDeRadicalPadelNew($numeroDiasInscripcionEnPackDeRadicalPadel,$sinAutoCalcular=false){


        if ($sinAutoCalcular){
            return $this->obtenerFechaFinInscripcionJugadorEnLigaDelClub($sinAutoCalcular);
        }

        if ($numeroDiasInscripcionEnPackDeRadicalPadel > 0){
            return Fecha::anadirDiasAFecha($this->obtenerFechaActivacionJugadorEnPackDeRadicalPadel(), $numeroDiasInscripcionEnPackDeRadicalPadel);
        }
        else{
            return $this->obtenerLiga()->obtenerFechaFin();
        }
    }

    static function obtenerNumeroJugadoresSuscritosActivos($idLiga){
        global $bd;

        return $bd->count("juegalaliga", array("AND" => array("id_Liga" => $idLiga, "id_plan[>]" => -1, "estado" => "ACTIVO")));
    }

    static function obtenerJugadoresSuscritosActivos($idLiga){
        global $bd;
        $idJugadores = $bd->select("juegalaliga", "id_jugador", array("AND" => array("id_Liga" => $idLiga, "id_plan[>]" => -1, "estado" => "ACTIVO")));

        $arrayJugadores = [];
        foreach ($idJugadores AS $idJugador){

            $arrayJugadores[] = new Jugador($idJugador);
        }

        return $arrayJugadores;
    }

    static function obtenerJugadoresSuscritosActivosGratuito($idLiga){
        global $bd;
        $idJugadores = $bd->select("juegalaliga", "id_jugador", array("AND" => array("id_Liga" => $idLiga, "id_plan" => 0, "estado" => "ACTIVO")));

        $arrayJugadores = [];
        foreach ($idJugadores AS $idJugador){

            $arrayJugadores[] = new Jugador($idJugador);
        }

        return $arrayJugadores;
    }

    static function obtenerJugadoresLiga($idLiga){
        global $bd;

        $idJugadores = $bd->select("juegalaliga", "id_Jugador", array("id_Liga" => $idLiga));

        $arrayJugadores = [];
        foreach ($idJugadores AS $idJugador){

            $arrayJugadores[] = new Jugador($idJugador);
        }

        return $arrayJugadores;
    }

    static function obtenerNumeroJugadoresInscritosEnLigaQueHanTenidoAlgunaVezEstado($idLiga, $estado, $idEstadoActivacionEnPack){

        $array_idsJugadores = self::obtenerIdsJugadoresEnLiga($idLiga, self::TIPO_ESTADO_activo, "", $idEstadoActivacionEnPack);

        $contador = 0;
        foreach ($array_idsJugadores as $idJugador){
            $Juegalaliga = new Juegalaliga($idLiga, $idJugador);

            if ($Juegalaliga->esAlgunaVezJugadorEstado($estado, $idEstadoActivacionEnPack)){
                $contador++;
            }
        }

        return $contador;
    }

    static function obtenerNumeroJugadoresActivosInscritosEnPackEnLiga($idLiga, $idEstadoActivacionEnPack, $fechaFinActivacionEnLigaPackMayorOIgual = "0000-00-00"){
        Log::v(__FUNCTION__, "ID LIGA: $idLiga", true);

        if ($fechaFinActivacionEnLigaPackMayorOIgual == "0000-00-00"){
            $fechaFinActivacionEnLigaPackMayorOIgual = date("Y-m-d");
        }
        return self::obtenerNumeroJugadoresEnLiga($idLiga, self::TIPO_ESTADO_activo, $idEstadoActivacionEnPack, $fechaFinActivacionEnLigaPackMayorOIgual);
    }

    static function obtenerNumeroJugadoresInscritosEnPackEnLiga($idLiga, $estado, $idEstadoActivacionEnPack, $fechaFinActivacionEnLigaPackMayorOIgual = "0000-00-00"){
        Log::v(__FUNCTION__, "ID LIGA: $idLiga");

        if ($fechaFinActivacionEnLigaPackMayorOIgual == "0000-00-00"){
            $fechaFinActivacionEnLigaPackMayorOIgual = date("Y-m-d");
        }
        return self::obtenerNumeroJugadoresEnLiga($idLiga, $estado, $idEstadoActivacionEnPack, $fechaFinActivacionEnLigaPackMayorOIgual);
    }


    static function obtenerNumeroJugadoresEnLiga($idLiga, $estado="", $idEstadoActivacionEnPack="", $fechaFinActivacionEnLigaPackMayorOIgual=""){
        Log::v(__FUNCTION__, "ID LIGA: $idLiga");

        $Liga = new Liga($idLiga);
        $S_permitidoVerSoloPacksDeLigasAdministradas = $Liga->obtenerUsuarioAdministrador()->tienePermitidoVerSoloPackDeLigasAdministradas();


        $array_idsJugadores = self::obtenerIdsJugadoresEnLiga($idLiga, $estado, $fechaFinActivacionEnLigaPackMayorOIgual, $idEstadoActivacionEnPack);

        if ($S_permitidoVerSoloPacksDeLigasAdministradas){

            $array_idsLigasAdministradas = $Liga->obtenerUsuarioAdministrador()->obtenerIdsLigasAdministradas();
            Log::v(__FUNCTION__, "Ligas Administradas: ".print_r($array_idsLigasAdministradas, true), true);
            foreach ($array_idsLigasAdministradas as $idLigaAdministrada){
                $LigaAdministrada = new $Liga($idLigaAdministrada);
                if ($idLigaAdministrada != $idLiga && $LigaAdministrada->esActiva()){
                    $array_idsJugadores = array_merge($array_idsJugadores, self::obtenerIdsJugadoresEnLiga($idLigaAdministrada, $estado, $fechaFinActivacionEnLigaPackMayorOIgual, $idEstadoActivacionEnPack));
                    Log::v(__FUNCTION__, "Contabilizando jugadores de la liga que administra: ".$idLigaAdministrada." | ESTADO: $estado", true);
                }

            }

            $contadorJugadoresActivos = count(array_unique($array_idsJugadores));

        }
        else if ($Liga->esContabilizarJugadoresDeFormaUnicaEnLigaPadreYAliasEnPack()){
            Log::v(__FUNCTION__, "Contabilizar jugadores de forma conjunta", false);
            $contadorJugadoresActivos = count(array_unique($array_idsJugadores));

        }
        else{
            $array_idsJugadoresActivos = $Liga->obtenerIdsJugadores($estado, $fechaFinActivacionEnLigaPackMayorOIgual, $idEstadoActivacionEnPack);
            $contadorJugadoresActivos = count($array_idsJugadoresActivos);

        }

        Log::v(__FUNCTION__, "Jugadores Contabilizados: $contadorJugadoresActivos", false);


         return $contadorJugadoresActivos;

    }

    static function obtenerIdsJugadoresEnLiga($idLiga, $estado="", $fechaFinActivacionEnLigaPackMayorOIgual="", $idEstadoActivacionEnPack=""){
        $Liga = new Liga($idLiga);

        $array_idsJugadoresActivos = $Liga->obtenerIdsJugadores($estado, $fechaFinActivacionEnLigaPackMayorOIgual, $idEstadoActivacionEnPack);

        /*
        $array_idsLigasAlias = $Liga->obtenerIdsLigaAliasLiga();
        foreach ($array_idsLigasAlias as $idLigasAlias){
            $LigaAlias = new Liga($idLigasAlias);
            if ($LigaAlias->existe()){
                $array_idsJugadoresActivos = array_merge($array_idsJugadoresActivos,$LigaAlias->obtenerIdsJugadores($estado, $fechaFinActivacionEnLigaPackMayorOIgual, $idEstadoActivacionEnPack));
            }
        }

        $LigaPadre = $Liga->obtenerLigaPadre();
        if ($LigaPadre->existe()){
            $array_idsJugadoresActivos = array_merge($array_idsJugadoresActivos,$LigaPadre->obtenerIdsJugadores($estado, $fechaFinActivacionEnLigaPackMayorOIgual, $idEstadoActivacionEnPack));
        }*/

        return $array_idsJugadoresActivos;
    }

    static function obtenerImporteTotalPagosEfectivoLiga($idLiga){
        global $bd;

        $arrayIds = $bd->select(self::TABLA_nombre, self::COLUMNA_id,array("AND" => array("OR" => array(self::COLUMNA_idLiga => $idLiga, self::COLUMNA_idLigaAlias => $idLiga), self::COLUMNA_estado => self::TIPO_ESTADO_activo)));

        $sumaPagos = 0;
        foreach ($arrayIds as $id){
            $Juegalaliga = new Juegalaliga("","",$id);
            $sumaPagos += $Juegalaliga->obtenerImportePagadoEfectivo();
        }

        return $sumaPagos;

    }

    static function obtenerImporteTotalPagosTarjetaLiga($idLiga){
        global $bd;

        $arrayIds = $bd->select(self::TABLA_nombre, self::COLUMNA_id,array("AND" => array("OR" => array(self::COLUMNA_idLiga => $idLiga, self::COLUMNA_idLigaAlias => $idLiga), self::COLUMNA_estado => self::TIPO_ESTADO_activo)));

        $sumaPagos = 0;
        foreach ($arrayIds as $id){
            $Juegalaliga = new Juegalaliga("","", $id);
            $sumaPagos += $Juegalaliga->obtenerImportePagadoTarjeta();
        }

        return $sumaPagos;

    }

    /**
     * @author JMAM
     *
     * Permite actualizar el estado de Suspcrión del Jugador
     * Se actualiza en todas las ligas inscrito que tenga el tipo de liga en "SUSCRIPCIÓN"
     *
     * @param $idJugador
     *
     * ID del jugador al que se le quiere actualizar la fecha
     *
     * @param $idLiga
     * ID de la liga al que se quiere actualizar la fecha (actualmente es sólo informativo)
     *
     * @param $estadoSuscripcion
     * String, indica el Estado de Suscripción
     * ALTA | BAJA | IMPAGO
     *
     * @return
     * No devuelve ningún valor
     */
    static function actualizarEstadoSuscripcion($idJugador, $idLiga, $estadoSuscripcion){
        global $bd;

        //JMAM: Actualiza el Estado de Suscripción en todas las ligas
        self::actualizarEstadoSuscripcionEnTodasLasLigas($idJugador, $estadoSuscripcion);

        //JMAM: Se asegura de actualizar el Estado de Suscripción en la liga indicada (para cuando queremos que funcione por liga)
        return $bd->update("juegalaliga", array("estado_suscripcion" => $estadoSuscripcion), array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));
    }

    static function actualizarEstadoSuscripcionEnTodasLasLigas($idJugador, $estadoSuscripcion){
        global $bd;

        $bd->query("UPDATE juegalaliga SET estado_suscripcion = '$estadoSuscripcion' WHERE id_Jugador = $idJugador AND id_Liga IN (SELECT id FROM liga WHERE TipoDeLiga = 'SUSCRIPCION')");

    }



    static function pasarTodasSuscripcionesAGeneralJugador($idJugador){
        global $bd;
        $bd->query("UPDATE juegalaliga SET id_plan = 0, estado_suscripcion = 'ALTA', fecha_fin_suscripcion = '' WHERE id_Jugador = $idJugador AND id_plan > 0");
    }



    static function realizarBaja($idJugador, $idLiga){
        global $bd;
        echo $bd->update("juegalaliga", array("estado_suscripcion" => "BAJA"), array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));

    }

    static function realizarBajaEnTodasLasLigas($idJugador){
        global $bd;
        echo $bd->update("juegalaliga", array("estado_suscripcion" => "BAJA"), array("AND" => array("id_Jugador" => $idJugador, "id_plan[>]" => -1)));
    }

    static function cancelarBaja($idJugador, $idLiga){
        global $bd;
        echo $bd->update("juegalaliga", array("estado_suscripcion" => "ALTA"), array("AND" => array("id_Jugador" => $idJugador, "id_Liga" => $idLiga)));
    }

    static function cancelarBajaEnTodasLasLigas($idJugador){
        global $bd;
        echo $bd->update("juegalaliga", array("estado_suscripcion" => "ALTA"), array("AND" => array("id_Jugador" => $idJugador, "id_plan[>]" => -1)));
    }

    static function actualizarSuscripcionEnTodasLasLigas($idJugador, $idPlan, $estadoSuscripcion, $fechaFinSuscripcion, $todasSuscripciones){
        global $bd;

        if ($todasSuscripciones != 1){
            $whereTodasSuscripciones = "AND id_Liga IN (SELECT id FROM liga WHERE idJugadorAdministrador != $idJugador)";
        }

        $bd->query("UPDATE juegalaliga SET id_plan = $idPlan, estado_suscripcion = '$estadoSuscripcion', fecha_fin_suscripcion = '$fechaFinSuscripcion' WHERE id_Jugador = $idJugador $whereTodasSuscripciones AND id_Liga IN (SELECT id FROM liga WHERE TipoDeLiga = 'SUSCRIPCION')");
    }

    static function actualizarFechaFinSuscripcionEnTodasLasLigas($idJugador, $fechaFinSuscripcion){
        global $bd;

        $bd->query("UPDATE juegalaliga SET fecha_fin_suscripcion = '$fechaFinSuscripcion' WHERE id_Jugador = $idJugador AND id_Liga IN (SELECT id FROM liga WHERE idJugadorAdministrador != $idJugador) AND id_Liga IN (SELECT id FROM liga WHERE TipoDeLiga = 'SUSCRIPCION')");
    }

    static function actualizarUltimaReferenciaDePago($idJugador, $idLiga, $referenciaPago){
        global $bd;

        $bd->query("UPDATE juegalaliga SET referencia_pago = '$referenciaPago' WHERE id_Jugador = $idJugador AND id_Liga = $idLiga");
    }



    static function actualizarEstadoJugadorEnLiga($idJugador, $idLiga, $tipoEstado){
       $Juegalaliga = new Juegalaliga($idJugador, $idLiga);
       $Juegalaliga[self::COLUMNA_estado] = $tipoEstado;
       $Juegalaliga->guardar();
    }

    static function obtenerJugadoresAdministradores($idLiga){
        global $bd;

        $ids = $bd->select(self::TABLA_nombre, self::COLUMNA_idJugador,
                    array("AND" => array(
                            self::COLUMNA_idLiga => $idLiga,
                            self::COLUMNA_rol => 6
                        )
                    )
                );


        $array = [];
        foreach ($ids as $id) {
            $array[] = new Jugador($id);
        }
        return $array;
    }

    static function obtenerIdsJugadoresGanadoresClasificacion($idLiga, $numeroJugadoresMaximo="", $sexo=""){
        global $bd;

        $Liga = new Liga($idLiga);
        $numeroJugadoresMinimoCorte = $Liga->obtenerNumeroMinimoJugadoresCorte();

        if (!empty($sexo)){
            $where_sexo = "AND sexo='$sexo'";
        }

        $array_resultados = $bd->query("
                            SELECT id_jugador ,
                                   (SELECT HaJugadoCon FROM puntos p where p.id_jugador=c.id_jugador AND p.id_liga='$idLiga') as num_cont,
                                   (SELECT Extra1 FROM puntos p where p.id_jugador=c.id_jugador AND p.id_liga='$idLiga') as part_jug,
                                   nivel,penalizacion,g,e,p,
                                   (SELECT sum(PUNTOS) FROM resultados r WHERE r.id_jugador=c.id_jugador AND id_liga='$idLiga' AND id_partido = '0' ) as penalizacion2,
                                   (SELECT sum(PUNTOS) FROM resultados r WHERE r.id_jugador=c.id_jugador AND id_liga='$idLiga' ) as puntos_definitivos
                            FROM cache_clasificacion c WHERE c.id_liga=$idLiga AND c.num_cont >=$numeroJugadoresMinimoCorte $where_sexo
                            ORDER BY puntos_definitivos DESC, part_jug DESC, num_cont DESC LIMIT $numeroJugadoresMaximo");

        $array_idsJugadores = array();
        foreach ($array_resultados as $resultado){
            $array_idsJugadores[] = $resultado["id_jugador"];
        }

        return $array_idsJugadores;
    }


    function obtenerId(){
        return $this["id"];
    }

    function obtenerLiga(){
        return new Liga($this["id_Liga"]);
    }

    function obtenerJugador(){
        return new Jugador($this[self::COLUMNA_idJugador]);
    }

    function obtenerNotas(){
        return $this[self::COLUMNA_notas];
    }

    function obtenerImportePagadoJugadorEnLigaPack(){
        $Liga = $this->obtenerLiga();

        if ($Liga->esSuscripcion()){
            return 0;
        }
        else{
            $pagado = $this[self::COLUMNA_pagadoEnLigaPack];
            if ($pagado > 0){
                return $pagado;
            }
            else{
                return 0;
            }
        }
    }

    function obtenerImportePagadoTarjeta(){
        $extra1 = $this["Extra1"];
        $importePagado = $this[self::COLUMNA_pagadoEnLigaPack];
        if (is_numeric($extra1)){
            if (is_numeric($importePagado)){
                return $importePagado;
            }
        }

        return 0;
    }

    function obtenerImportePagadoEfectivo(){
        $extra1 = $this["Extra1"];
        $importePagado = $this[self::COLUMNA_pagadoEnLigaPack];
        if (!is_numeric($extra1)){

            if (is_numeric($importePagado)){
                return $importePagado;
            }
        }

        return 0;
    }

    function actualizarPermitirVisitarLiga($permitirVisitarLiga, $enTodasLasEdiciones=false){
        global $bd;
        if ($enTodasLasEdiciones){
            $array_idsLigasPerteneceAlGrupoDeEstaLiga = $this->obtenerLiga()->obtenerIdsLigasPertenecerAlGrupoDeEstaLiga();
            $set[self::COLUMNA_permitirVisitarLiga] = $permitirVisitarLiga;
            $where["AND"][self::COLUMNA_idJugador] = $this->obtenerJugador()->obtenerId();
            $where["AND"][self::COLUMNA_idLiga] = $array_idsLigasPerteneceAlGrupoDeEstaLiga;
            $bd->update(self::TABLA_nombre, $set, $where);
        }
        else{
            //JMAM: En todas las ediciones
            $this[self::COLUMNA_permitirVisitarLiga] = $permitirVisitarLiga;
            $this->guardar();
        }
    }

    function actualizarEsPermitidoVerListadoDePartidos($esPermitidoVerListadoDePartidos, $enTodasLasEdiciones=false){
        global $bd;

        if ($enTodasLasEdiciones){
            $array_idsLigasPerteneceAlGrupoDeEstaLiga = $this->obtenerLiga()->obtenerIdsLigasPertenecerAlGrupoDeEstaLiga();
            $set[self::COLUMNA_esPermitidoVerListadoPartidos] = $esPermitidoVerListadoDePartidos;
            $where["AND"][self::COLUMNA_idJugador] = $this->obtenerJugador()->obtenerId();
            $where["AND"][self::COLUMNA_idLiga] = $array_idsLigasPerteneceAlGrupoDeEstaLiga;
            $bd->update(self::TABLA_nombre, $set, $where);
        }
        else{
            //JMAM: En todas las ediciones
            $this[self::COLUMNA_esPermitidoVerListadoPartidos] = $esPermitidoVerListadoDePartidos;
            $this->guardar();
        }
    }

    function actualizarEstado($estado, $enTodasLasEdiciones=false){
        global $bd;

        if ($enTodasLasEdiciones){
            $array_idsLigasPerteneceAlGrupoDeEstaLiga = $this->obtenerLiga()->obtenerIdsLigasPertenecerAlGrupoDeEstaLiga();
            $set[self::COLUMNA_estado] = $estado;
            $where["AND"][self::COLUMNA_idJugador] = $this->obtenerJugador()->obtenerId();
            $where["AND"][self::COLUMNA_idLiga] = $array_idsLigasPerteneceAlGrupoDeEstaLiga;
            $bd->update(self::TABLA_nombre, $set, $where);
        }
        else{
            //JMAM: En todas las ediciones
            $this[self::COLUMNA_estado] = $estado;
            $this->guardar();
        }
    }


    static function obtenerNumeroDiasDesdeUltimaInscripcion($idJugador, $tipoLiga=""){
        global $bd;

        $fechaHoy = date("Y-m-d");

        if (!empty($tipoLiga)){
            $whereTipoLiga =  "AND ".self::COLUMNA_idLiga." IN (SELECT ".Liga::COLUMNA_id." FROM ".Liga::TABLA_nombre." WHERE ".Liga::COLUMNA_tipoLiga."='$tipoLiga')";
        }

        $resultado = $bd->query("SELECT MAX(".self::COLUMNA_fechaInscripcion.") as 'fechaInscripcionMayor' FROM ".self::TABLA_nombre." WHERE ".self::COLUMNA_idJugador."='$idJugador' $whereTipoLiga")->fetch();
        $fechaInscripcionMayor = $resultado["fechaInscripcionMayor"];

        return obtenerDiferenciaDiasEntreFechas($fechaHoy, $fechaInscripcionMayor);
    }

    static function limpiarRegistrosBasura($idJugador){
        global $bd;

        $bd->delete(self::TABLA_nombre, array("AND" => array(self::COLUMNA_idJugador => $idJugador, self::COLUMNA_idLiga => 0)));
        $bd->query("DELETE FROM ".self::TABLA_nombre." WHERE ".self::COLUMNA_idLiga." NOT IN (SELECT ".Liga::COLUMNA_id." FROM ".Liga::TABLA_nombre.")");
    }





}