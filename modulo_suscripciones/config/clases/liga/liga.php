<?php

class Liga extends general implements InternacionalidadInterface
{

    const TABLA_nombre = "liga";
    const COLUMNA_id = "id";
    const COLUMNA_nombre = "Nombre";
    const COLUMNA_nombrePublico = "nombrePublico";
    const COLUMNA_codigoPais = "Idioma";
    const COLUMNA_idAliasLiga = "Alias_del_idLiga";
    const COLUMNA_esAutoactivacion = "LigaAutoactivacion";
    const COLUMNA_edicion = "Edicion";
    const COLUMNA_estado = "Estado";
    const COLUMNA_grupoLiga = "Grupo_Liga";
    const COLUMNA_fechaInicio = "Fecha_INI";
    const COLUMNA_fechaFin = "Fecha_FIN";
    const COLUMNA_fechaInicioPlazoInscripcion = "Fecha_INI_Plazo";
    const COLUMNA_emailContacto = "Email_Contacto";
    const COLUMNA_tipoLiga = "TipoDeLiga";
    const COLUMNA_numeroJugadoresGanadoresBonificar = "lbl_inscripcion3";
    const COLUMNA_numeroDiasJugadoresGanadoresBonificar = "txt_inscripcion3";
    const COLUMNA_minimoJugadoresCorte = "MinimoJugadores";
    const COLUMNA_numeroHombesPuedenTenerTrofeos = "Trofeos_Hombres";
    const COLUMNA_numeroMujeresPuedenTenerTrofeos = "Trofeos_Mujeres";
    const COLUMNA_numeroSustitucionesPorJugador = "Liga_Extra13";
    const COLUMNA_localidad = "localidad";
    const COLUMNA_descripcionOtraFormaPago = "Texto_OtraFormaPago";
    const COLUMNA_mostrarPartidosDeLaLigaPadreYAlias = "mostrarPartidosDeLaLigaPadreYAlias";
    const COLUMNA_mostrarOtrosCampos = "mostrarOtrosCampos";
    const COLUMNA_contabilizarJugadoresDeFormaUnicaEnLigaPadreYAliasEnPack = "contabilizarJugadoresDeFormaUnicaEnLigaPadreYAliasEnPack";
    const COLUMNA_packsValidosParaAliasYPrincipal = "packsValidosParaAliasYPrincipal";
    const COLUMNA_logotipo = "Foto_LOGO";
    const COLUMNA_logotipo2 = "Foto_DER";
    const COLUMNA_mostrarClasificacion = "mostrar_clasificacion";
    const COLUMNA_mostrarRanking = "mostrar_ranking";
    const COLUMNA_zonaGeografica = "ZonaGeografica";
    const COLUMNA_mostrarSelectorFiltroLigasEnClasificacion = "mostrarSelectorFiltroLigasEnClasificacion";
    const COLUMNA_permiteVisitantes = "LigaPermiteVisitante";
    const COLUMNA_idAnteriorEdicion = "id_ANT_edicion";
    const COLUMNA_esEnviarEmailJugadorAQueridoApuntarse = "esEnviarEmailJugadorAQueridoApuntarse";

    const TIPO_LIGA_suscripcion = "SUSCRIPCION";
    const TIPO_LIGA_pack = "";


    const ESTADO_activa = "ACTIVA";
    const ESTADO_terminada = "TERMINADA";

    const CODIGO_PAIS_espana = "ES";
    const CODIGO_PAIS_italia = "IT";

    const AUTOACTIVACION_no = 0;
    const AUTOACTIVACION_si_sinAccesoSiFinInscripcion = 1;
    const AUTOACTIVACION_si_conAccesoSiFinInscripcion = 2;

    const OP_obtenerIdsPartidosConResultados = "obtenerIdsPartidosConResultados";


    function Liga($id)
    {
        if ($id != '')
            parent::__construct(self::TABLA_nombre, self::COLUMNA_id, $id);
        else
            parent::__construct(self::TABLA_nombre, '', '');
    }

    static function obtenerIds(){
        global $bd;

        return $bd->select(self::TABLA_nombre,  self::COLUMNA_id, array("ORDER" => self::COLUMNA_nombre." ASC"));
    }

    function esEnviarEmailJugadorAQueridoApuntarse()
    {
        if ($this[self::COLUMNA_esEnviarEmailJugadorAQueridoApuntarse] == 1){
            return true;
        }

        return false;
    }

    function obtenerIdsPartidosConResultados(){
        return Partido::obtenerIdsPartidosConResultados($this->obtenerId());
    }

    function obtenerZonaGeografica(){
        return $this[self::COLUMNA_zonaGeografica];
    }

    function obtenerUrlLogotipo(){
        $nombreLogotipo = $this[self::COLUMNA_logotipo];

        if (empty($nombreLogotipo)){
            return WWWBASE_PRODUCCION."/images/minilogo.png";
        }
        else{
            return WWWBASE_PRODUCCION."PCU/fotos/$nombreLogotipo";
        }
    }

    function obtenerUrlLogotipo2(){
        $nombreLogotipo = $this[self::COLUMNA_logotipo2];

        if (empty($nombreLogotipo)){
            return WWWBASE_PRODUCCION."/images/minilogo.png";
        }
        else{
            return WWWBASE_PRODUCCION."PCU/fotos/$nombreLogotipo";
        }
    }



    function existe(){
        $id = $this->obtenerId();

        if ($id <= 0){
            return false;
        }

        return true;
    }

    function obtenerId(){
        return $this["id"];
    }

    function esEliminado(){

        if ($this->obtenerId() > 0){
            return false;
        }

        return true;
    }

    function obtenerDescripcionOtraFormaDePago(){
        return $this[self::COLUMNA_descripcionOtraFormaPago];
    }

    function obtenerNombre($incluidEdicion=false, $forzarNombreNoPublico=false){

        $nombre = $this[self::COLUMNA_nombre];
        $nombrePublico = $this->obtenerNombrePublico();

        if (!empty($nombrePublico)){
            $nombre = $nombrePublico;
        }

        if ($incluidEdicion){
            $nombre.= " (".$this->obtenerEdicion()." Ed.)";
        }

        return $nombre;
    }

    function obtenerIdsLigasPertenecerAlGrupoDeEstaLiga(){
        global $bd;

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, array(self::COLUMNA_grupoLiga => $this[self::COLUMNA_grupoLiga]));
    }

    function obtenerIdsLigasPertenecientesALigaPadreYAlias(){
        $array_idsLigas[] = $this->obtenerId();
        $array_idsLigas = array_merge($array_idsLigas, $this->obtenerIdsLigaAliasLiga());
        $array_idsLigas[] = $this->obtenerLigaPadre()->obtenerId();
        $array_idsLigas = array_unique($array_idsLigas);

        $array_idsLigasObtenidas = array();
        foreach ($array_idsLigas as $idLiga){

            if (!empty($idLiga)){
                $array_idsLigasObtenidas[] = $idLiga;
            }
        }

        return $array_idsLigasObtenidas;
    }

    function esPermitidoMostrarPartidosDeLaLigaPadreYAlias(){

        if ($this[self::COLUMNA_mostrarPartidosDeLaLigaPadreYAlias] == 1){
            return true;
        }

        return false;
    }

    function esPermitidoMostrarRanking(){

        if ($this[self::COLUMNA_mostrarRanking] == 1){
            return true;
        }

        return false;
    }

    function esPermitidoMostrarSelectorFitroLigasEnClasificacion(){
        if ($this[self::COLUMNA_mostrarSelectorFiltroLigasEnClasificacion] == 1){
            Log::v(__FUNCTION__, "Es permitido: mostrarSelectorFiltroLigasEnClasificacion", false);
            return true;
        }
        return false;
    }

    function esPermitidoMostrarOtrosCampos(){

        if ($this[self::COLUMNA_mostrarOtrosCampos] == 1){
            return true;
        }

        return false;
    }

    function esPermitidoVisitantes(){
        if ($this[self::COLUMNA_permiteVisitantes] == 1){
            return true;
        }
        return false;
    }

    function esContabilizarJugadoresDeFormaUnicaEnLigaPadreYAliasEnPack(){

        if ($this[self::COLUMNA_contabilizarJugadoresDeFormaUnicaEnLigaPadreYAliasEnPack] == 1){
            return true;
        }

        return false;
    }

    function esPacksValidosParaAliasYPrincipal(){

        if ($this[self::COLUMNA_packsValidosParaAliasYPrincipal] == 1){
            Log::v(__FUNCTION__, "Los Packs son válidos para alias y principal", true);
            return true;
        }

        return false;
    }

    function puedeApuntarseJugadorEnLigaPorPacks(){

        if ($this->esSuscripcion()){
            Log::v(__FUNCTION__, "Es de suscripción", true);
            return true;
        }

        $numeroInscripcionesDisponibles = $this->obtenerNumeroInscripcionesPacksDisponiblesTotalesEnLaLiga($this->obtenerId(), $this->obtenerIdsClubs()[0]);
        Log::v(__FUNCTION__, "Nº de Inscripciones disponibles: $numeroInscripcionesDisponibles", true);

        if ($numeroInscripcionesDisponibles > 0){
            Log::v(__FUNCTION__, "Puede apuntarse jugadores por packs", true);
            return true;
        }

        Log::v(__FUNCTION__, "Liga no tiene Packs disponibles", true);
        return false;


    }

    function obtenerJuegalaliga($idJugador)
    {
        return new Juegalaliga($idJugador, $this->obtenerId());
    }

    function tieneAlgunPackContratado(){
        return Pack::tieneAlgunPackContratadoEnLaLiga($this->obtenerId());
    }

    private function obtenerNombrePublico(){
        return $this[self::COLUMNA_nombrePublico];
    }

    function obtenerNumeroHombresPuedenTenerTrofeos(){
        return $this[self::COLUMNA_numeroHombesPuedenTenerTrofeos];
    }

    function obtenerNumeroMujeresPuedenTenerTrofeos(){
        return $this[self::COLUMNA_numeroMujeresPuedenTenerTrofeos];
    }

    function obtenerNumeroJugadoresGanadoresBonificar(){
        return $this[self::COLUMNA_numeroJugadoresGanadoresBonificar];
    }

    function obtenerNumeroDiasJugadoresGanadoresBonificar(){
        return $this[self::COLUMNA_numeroDiasJugadoresGanadoresBonificar];

    }

    function obtenerNumeroSustitucionesPorJugador(){
        $numeroSustitucionesPorJugador = $this[self::COLUMNA_numeroSustitucionesPorJugador];

        if ($numeroSustitucionesPorJugador > 0){
            return $numeroSustitucionesPorJugador;
        }

        return -1;
    }

    function obtenerLigaPadre(){
        return new Liga($this[self::COLUMNA_idAliasLiga]);
    }

    function obtenerIdsLigaAliasLiga(){
        global $bd;

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, array(self::COLUMNA_idAliasLiga => $this->obtenerId()));
    }

    function estaLigaEsAlias(){

        if ($this[self::COLUMNA_idAliasLiga] == 0){
            return false;
        }

        return true;
    }


    function obtenerPartidosMensualesPlanGratis(){
        return $this['Liga_Extra7'];
    }

    function obtenerDiasDiferenciaEntrePartidosPlanGratis(){
        return $this['Liga_Extra8'];
    }

    function obtenerTiempoBonificacionJugadorRecomendadoPrimeraInscripcion(){
        return $this['Liga_Extra12'];
    }

    function esPermitidoVerListadoPartidosAVisitantes(){
        $array_idsCampos = $this->obtenerIdsCampos();
        foreach ($array_idsCampos as $idCampo){
            $Campo = new Campo($idCampo);
            if (!$Campo->obtenerConfiguracionReservaPistas()->esPermitidoVerListadoPartidosAVisitantes()){
                return false;
            }
        }

        return true;
    }

    function permiteCrearPartidosPlanGeneral(){

        if ($this["Liga_Extra9"] == 1){
            return true;
        }
        else{
            return false;
        }
    }

    function obtenerTipoDeLiga(){
        return $this[self::COLUMNA_tipoLiga];
    }

    function esSuscripcion(){

        if ($this[self::COLUMNA_tipoLiga] == self::TIPO_LIGA_suscripcion){
            Log::v(__FUNCTION__, "Liga es de Suscripción",false);
            return true;
        }

        return false;
    }

    /**
     * @author JMAM
     * Determina si la Liga se activa sin Pagar
     * @return bool
     */
    function esAutoActivacion(){
        if ($this[self::COLUMNA_esAutoactivacion] >= 1){
            Log::v(__FUNCTION__, "Liga es AutoActivacion", true);
            return true;
        }

        return false;
    }

    function esAutoActualizarFechaFinInscripcionJugadorEnLigaDelClub(){
        Log::v(__FUNCTION__, "¿Autoactivación?: ".$this[self::COLUMNA_esAutoactivacion], true);
        if ($this[self::COLUMNA_esAutoactivacion] == self::AUTOACTIVACION_si_conAccesoSiFinInscripcion){
            Log::v(__FUNCTION__, "SI", true);
            return true;
        }

        Log::v(__FUNCTION__, "NO", true);
        return false;
    }

    function esJugadorActivoEnLiga($idJugador){
        return Juegalaliga::esJugadorActivoEnLiga($idJugador, $this->obtenerId());
    }

    function obtenerNumeroJugadoresConPlan($idPlan){

        return Juegalaliga::obtenerNumeroJugadoresConPlan($this["id"], $idPlan);
    }

    function tieneJugadorAdministrador(){

        if ($this["idJugadorAdministrador"] > 0) {
            return true;
        }

        return false;
    }

    function obtenerJugadorAdministrador(){

        if ($this["idJugadorAdministrador"] > 0) {
            return new Jugador($this["idJugadorAdministrador"]);
        }

        return null;
    }

    function  obtenerNumeroJugadodesInscritos(){
        return Juegalaliga::obtenerNumeroJugadores($this["id"]);
    }

    function obtenerEdicion(){
        return $this[self::COLUMNA_edicion];
    }

    function esPrimeraEdicion(){
        if ($this->obtenerEdicion() == 1){
            return true;
        }

        return false;
    }

    function obtenerNumeroMinimoJugadoresCorte(){
        return $this[self::COLUMNA_minimoJugadoresCorte];
    }


    function obtenerJugadores(){
        return Juegalaliga::obtenerJugadoresLiga($this["id"]);
    }

    function tieneAlgunPackDisponibleYActivo(){
        return Pack::tieneAlgunPackEnLaLigaDisponibleYActivo($this->obtenerId());
    }


    function imprimirSelectorJugadores(){
        $array_Jugadores = $this->obtenerJugadores();

        echo "<select id='selector_idJugadores' class='form-control selectpicker' data-live-search='true' onchange='onchange_selectorJugadores()'>";
        echo "<option value='-1'>".Traductor::traducir("NINGUNO")."</option>";
        foreach ($array_Jugadores as $Jugador){
            $id = $Jugador->obtenerId();
            $nombre = $Jugador->obtenerNombre(true);
            $telefono = $Jugador->obtenerTelefono(true);

            $nombreCompleto = "$nombre ($telefono)";
            echo "<option value='$id'>$nombreCompleto</option>";
        }
        echo "</select>";
    }

    function obtenerNumeroJugadoresActivos(){
        return Juegalaliga::obtenerNumeroJugadoresActivosInscritosEnPackEnLiga($this->obtenerId());
    }

    function obtenerNumeroJugadoresQueHanJugadoYEstanActivos(){
        global $bd;

        $resultado = $bd->query("SELECT count(id) as total FROM juegalaliga WHERE id_Liga=".$this->obtenerId()." AND estado='ACTIVO' AND id_Jugador IN (SELECT id_jugador FROM puntos WHERE id_liga=".$this->obtenerId().")")->fetchAll();

        return $resultado[0][0];
    }

    function obtenerImporteTotalPagosEfectivo(){
        return Juegalaliga::obtenerImporteTotalPagosEfectivoLiga($this->obtenerId());
    }

    function obtenerImporteTotalPagosTarjeta(){
        return Juegalaliga::obtenerImporteTotalPagosTarjetaLiga($this->obtenerId());
    }

    function obtenerImporteTotalSaldosMonedero(){
        return Monedero::obtenerImporteTotalSaldosMonederoLiga($this->obtenerId());
    }

    function obtenerIdsJugadores($estado="", $fechaFinActivacionEnLigaPackMayorOIgual="", $idEstadoActivacionEnPack=""){

        return Juegalaliga::obtenerIdsJugadores($this->obtenerId(), $estado, $fechaFinActivacionEnLigaPackMayorOIgual, $idEstadoActivacionEnPack);
    }



    function anadirBonificacionSuscripcionAJugadoresGanadores($noComprobarSiLigaEsSucripcion=false){

        $contador = 0;
        if ($this->esSuscripcion() || $noComprobarSiLigaEsSucripcion){
            $array_idsJugadores = Juegalaliga::obtenerIdsJugadoresGanadoresClasificacion($this->obtenerId(),  $this->obtenerNumeroJugadoresGanadoresBonificar());
            foreach ($array_idsJugadores as $idJugador){

                if(Plan::anadirBonificacionGanadorLiga($idJugador, $this->obtenerId())){
                    $contador++;
                }
            }
        }

        return $contador;


    }

    function enviarNotificacionesAJugadoresGanadores($idLigaVerHistorico){
        $array_idsJugadoresHombres = Juegalaliga::obtenerIdsJugadoresGanadoresClasificacion($this->obtenerId(), $this->obtenerNumeroHombresPuedenTenerTrofeos(), Juegalaliga::SEXO_hombre);

        $contadorFelicitacionesEnviadas = 0;
        foreach ($array_idsJugadoresHombres as $idJugador){
           $Jugador = new Jugador($idJugador);
           $Jugador->enviarNotificacionFelicitacionDeGanador($this->obtenerId(), $idLigaVerHistorico);
           $Jugador->enviarNotificacionFelicitacionAJugadoresQueHanJugadorConGanador($this->obtenerId());

           $contadorFelicitacionesEnviadas++;
        }

        $array_idsJugadoresMujeres = Juegalaliga::obtenerIdsJugadoresGanadoresClasificacion($this->obtenerId(), $this->obtenerNumeroMujeresPuedenTenerTrofeos(), Juegalaliga::SEXO_mujer);

        foreach ($array_idsJugadoresMujeres as $idJugador){
            $Jugador = new Jugador($idJugador);
            $Jugador->enviarNotificacionFelicitacionDeGanador($this->obtenerId(), $idLigaVerHistorico);
            $Jugador->enviarNotificacionFelicitacionAJugadoresQueHanJugadorConGanador($this->obtenerId());

            $contadorFelicitacionesEnviadas++;
        }

        return $contadorFelicitacionesEnviadas;
    }


    function anadirTrofeosAJugadoresGanadores($eliminarTrofeosAnteriores=false){
        $array_idsJugadoresHombres = Juegalaliga::obtenerIdsJugadoresGanadoresClasificacion($this->obtenerId(), $this->obtenerNumeroHombresPuedenTenerTrofeos(), Juegalaliga::SEXO_hombre);

        $contadorTotalTrofeosAsignados = 0;
        $contador = 1;
        foreach ($array_idsJugadoresHombres as $idJugador){
            if ($eliminarTrofeosAnteriores){
                Trofeo::eliminarTrofeos($idJugador, $this->obtenerId());
            }
            Trofeo::anadirTrofeo($idJugador, $this->obtenerId(), $contador);
            $contador++;
            $contadorTotalTrofeosAsignados++;
        }

        $array_idsJugadoresMujeres = Juegalaliga::obtenerIdsJugadoresGanadoresClasificacion($this->obtenerId(), $this->obtenerNumeroMujeresPuedenTenerTrofeos(), Juegalaliga::SEXO_mujer);

        $contador = 1;
        foreach ($array_idsJugadoresMujeres as $idJugador){
            if ($eliminarTrofeosAnteriores){
                Trofeo::eliminarTrofeos($idJugador, $this->obtenerId());
            }
            Trofeo::anadirTrofeo($idJugador, $this->obtenerId(), $contador);
            $contador++;
            $contadorTotalTrofeosAsignados++;
        }

        return $contadorTotalTrofeosAsignados;
    }

    function enviarPushBienvenidaJugadorALiga($idJugadorOrigen){

        require_once BASE."modulo_suscripciones/config/config.php";
        require_once BASE."modulo_suscripciones/config/clases/funciones.php";

        ////// CONFIGURACION DEL ACCESO A BASES DE DATOS
        $servidor=BD_SERVIDOR; $usuar=USUARIO_BD;$clave=CONTRASENA_BD;$bdold=NOMBRE_BD;
        mysql_connect($servidor,$usuar,$clave);
        @mysql_select_db ($bdold) or die("S_control no puede acceder a la base de datos $bdold");

        $JugadorOrigen = new Jugador($idJugadorOrigen);

        $arrayJugadores = $this->obtenerJugadoresSuscritosActivosGratuito();
        foreach ($arrayJugadores AS $JugadorDestino){

            $idJugadorDestino = $JugadorDestino["id"];

            $asunto = traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_BIENVENIDA_JUGADOR_ASUNTO");
            $menaje = str_replace("%NOMBRE_JUGADOR%", $JugadorOrigen["Nick"], traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_BIENVENIDA_JUGADOR_MENSAJE"));


            if ($idJugadorDestino != $idJugadorOrigen){
                //MensajePUSH($idJugadorDestino, (Componer_Notificacion($idJugadorDestino, $asunto, $menaje, '1', $this["id"], $idJugadorOrigen)),$idJugadorOrigen,"");
            }

        }
    }


    function enviarPushNumeroAlcanzadoJugadoresALiga($idJugadorOrigen){

        require_once BASE."modulo_suscripciones/config/config.php";
        require_once BASE."modulo_suscripciones/config/clases/funciones.php";

        ////// CONFIGURACION DEL ACCESO A BASES DE DATOS
        $servidor=BD_SERVIDOR; $usuar=USUARIO_BD;$clave=CONTRASENA_BD;$bdold=NOMBRE_BD;
        mysql_connect($servidor,$usuar,$clave);
        @mysql_select_db ($bdold) or die("S_control no puede acceder a la base de datos $bdold");

        $JugadorOrigen = new Jugador($idJugadorOrigen);

        $arrayJugadores = $this->obtenerJugadoresSuscritosActivos();
        $numeroJugadoresActivos = $this->obtenerNumeroJugadoresSuscritosActivos();
        foreach ($arrayJugadores AS $JugadorDestino){

            $idJugadorDestino = $JugadorDestino["id"];

            $asunto = str_replace("%NUMERO_JUGADORES%", $numeroJugadoresActivos, traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_NUMERO_ALCANZADO_JUGADORES_ASUNTO"));

            if ($this->obtenerNumeroPartidosAbiertos() > 0){
                $tipoMensaje = 9;
                $mensaje = traducirAIdiomaJugador($idJugadorDestino, "LANG_PUSH_NUMERO_ALCANZADO_JUGADORES_CON_PARTIDOS_MENSAJE");
            }
            else{
                $tipoMensaje = 7;
                $mensaje = traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_NUMERO_ALCANZADO_JUGADORES_SIN_PARTIDOS_MENSAJE");
            }


            if ($idJugadorDestino != $idJugadorOrigen){
                MensajePUSH($idJugadorDestino, (Componer_Notificacion($idJugadorDestino, $asunto, $mensaje, $tipoMensaje, $this["id"], $idJugadorOrigen)),$idJugadorOrigen,"");
            }

        }
    }

    function enviarPushInvitaATusAmigosALiga($idJugadorOrigen){

        require_once BASE."modulo_suscripciones/config/config.php";
        require_once BASE."modulo_suscripciones/config/clases/funciones.php";
        require_once BASE."funciones.php";

        ////// CONFIGURACION DEL ACCESO A BASES DE DATOS
        $servidor=BD_SERVIDOR; $usuar=USUARIO_BD;$clave=CONTRASENA_BD;$bdold=NOMBRE_BD;
        mysql_connect($servidor,$usuar,$clave);
        @mysql_select_db ($bdold) or die("S_control no puede acceder a la base de datos $bdold");

        $JugadorOrigen = new Jugador($idJugadorOrigen);

        $arrayJugadores = $this->obtenerJugadoresSuscritosActivos();
        $numeroJugadoresActivos = $this->obtenerNumeroJugadoresSuscritosActivos();

        foreach ($arrayJugadores AS $JugadorDestino){

            $idJugadorDestino = $JugadorDestino["id"];
            echo "ID JUGADOR DESTINO: $idJugadorDestino<br/>";

            $asunto = str_replace("%NOMBRE_JUGADOR%", $JugadorDestino["Nombre"], traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_INVITA_A_TUS_AMIGOS_ASUNTO"));

            $tipoMensaje = 8;
            $mensaje = traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_INVITA_A_TUS_AMIGOS_MENSAJE");


            if ($idJugadorDestino != $idJugadorOrigen){
                Notificacion::enviarNotificacion($idJugadorDestino, $asunto, $mensaje, Notificacion::TIPO_NOTIFICACION_verEnlaceRecomendacionWhatsApp, $this->obtenerId());
                //MensajePUSH($idJugadorDestino, (Componer_Notificacion($idJugadorDestino, $asunto, $mensaje, $tipoMensaje, $this["id"], $idJugadorOrigen)),$idJugadorOrigen,"");
            }

        }
    }

    function enviarPushAnimateALiga($idJugadorOrigen){
        require_once BASE."funciones.php";
        require_once BASE."modulo_suscripciones/config/config.php";
        require_once BASE."modulo_suscripciones/config/clases/funciones.php";

        /*
        ////// CONFIGURACION DEL ACCESO A BASES DE DATOS
        $servidor="BD_SERVIDOR"; $usuar=USUARIO_BD;$clave=CONTRASENA_BD;$bdold=NOMBRE_BD;
        mysql_connect($servidor,$usuar,$clave);
        @mysql_select_db ($bdold) or die("S_control no puede acceder a la base de datos $bdold");*/

        $JugadorOrigen = new Jugador($idJugadorOrigen);

        $numeroHoy = date("d");
        $arrayJugadores = $this->obtenerJugadoresSuscritosActivos();
        $numeroJugadoresActivos = $this->obtenerNumeroJugadoresSuscritosActivos();
        $numeroPartidosAbiertos = $this->obtenerNumeroPartidosAbiertos();
        $enviarPush = false;
        foreach ($arrayJugadores AS $JugadorDestino){

            $idJugadorDestino = $JugadorDestino["id"];

            //JMAM: Comprueba número de jugadores activos
            if ($numeroJugadoresActivos < 100/*30*/){
                //JMAM: Jugadores activos menor que 30

                //JMAM: Comprueba el número de partidos abiertos
                if ($numeroPartidosAbiertos >= 3){
                    //JMAM: Número de partidos abierto igual a 3 o más

                    //JMAM: Enviar PUSH partidos abiertos
                    $tipoMensaje = 9;
                    $asunto = str_replace("%NOMBRE_JUGADOR%", $JugadorDestino["Nombre"], traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_ANIMATE_CON_PARTIDOS_EN_LIGA_ASUNTO"));
                    $mensaje = traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_ANIMATE_CON_PARTIDOS_EN_LIGA_MENSAJE");
                    $enviarPush = true;
                }
                else{
                    //JMAM: Enviar PUSh sin partidos suficientes
                    $tipoMensaje = 7;
                    $asunto = str_replace("%NOMBRE_JUGADOR%", $JugadorDestino["Nombre"], traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_ANIMATE_SIN_PARTIDOS_EN_LIGA_ASUNTO"));
                    $mensaje = traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_ANIMATE_SIN_PARTIDOS_EN_LIGA_MENSAJE");
                    $enviarPush = true;
                }
            }
            else if ($numeroJugadoresActivos >= 100/*30*/){


                if ($numeroPartidosAbiertos <= ($numeroJugadoresActivos / 10) && $numeroHoy % 1 == 0){
                    //JMAM: Número de partidos es igual o menor que el 10% del número de jugadores y es día impar

                    $tipoMensaje = 7;
                    $asunto = str_replace("%NOMBRE_JUGADOR%", $JugadorDestino["Nombre"], traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_ANIMATE_SIN_PARTIDOS_EN_LIGA_ASUNTO"));
                    $mensaje = traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_ANIMATE_SIN_PARTIDOS_EN_LIGA_MENSAJE");
                    $enviarPush = true;
                }
                else if ($numeroPartidosAbiertos > ($numeroJugadoresActivos / 10) && $numeroHoy % 5 == 0){
                    //JMAM: Si número de partidos es mayor que el 10% del número de jugadores y cada 5 dias

                    $tipoMensaje = 9;
                    $asunto = str_replace("%NOMBRE_JUGADOR%", $JugadorDestino["Nombre"], traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_ANIMATE_CON_PARTIDOS_EN_LIGA_ASUNTO"));
                    $mensaje = traducirAIdiomaJugador($idJugadorDestino,"LANG_PUSH_JUGADOR_ANIMATE_CON_PARTIDOS_EN_LIGA_MENSAJE");
                    $enviarPush = true;
                }

            }





            if ($enviarPush == true){


                if ($idJugadorDestino != $idJugadorOrigen){
                    MensajePUSH($idJugadorDestino, (Componer_Notificacion($idJugadorDestino, $asunto, $mensaje, $tipoMensaje, $this["id"], $idJugadorOrigen)),$idJugadorOrigen,"");
                }
            }


        }
    }



    function obtenerNumeroJugadoresSuscritosActivos(){

        return Juegalaliga::obtenerNumeroJugadoresSuscritosActivos($this["id"]);
    }

    function obtenerJugadoresSuscritosActivos(){

        return Juegalaliga::obtenerJugadoresSuscritosActivos($this["id"]);
    }

    function obtenerJugadoresSuscritosActivosGratuito(){
        return Juegalaliga::obtenerJugadoresSuscritosActivosGratuito($this["id"]);
    }

    /**
     * @author JMAM
     *
     * Obtiene el número de partidos abiertos desde la fecha de hoy
     * @return bool|int|mixed
     */
    function obtenerNumeroPartidosAbiertos(){
        return Partido::obtenerNumeroPartidosAbiertos($this["id"]);
    }

    function obtenerNumeroPartidos(){
        return Partido::obtenerNumeroPartidos($this["id"]);
    }

    function obtenerIdsPartidos(){
        return Partido::obtenerIdsPartidosPorLiga($this->obtenerId());
    }


    function obtenerLigaEdicionAnterior(){
        return new Liga($this[self::COLUMNA_idAnteriorEdicion]);
    }

    function obtenerEdicionLigaMasReciente(){
        global $bd;
        $idLiga = $bd->get("liga", "id", array("id_ANT_edicion" => $this->obtenerId()));
        return new Liga($idLiga);
    }
    function obtenerNumeroEdicionLigaMasReciente(){

        return $this->obtenerEdicionLigaMasReciente()->obtenerEdicion();
    }

    function obtenerEdicionLigaActivaMasReciente(){
        global $bd;

        $idLiga = $bd->get("liga", "id", array("id_ANT_edicion" => $this["id"]));

        $idLigaEdicionActivaMasReciente = $idLiga;
        while ($idLiga != ""){
            $idLiga = $bd->get("liga", "id", array("id_ANT_edicion" => $idLiga));
            $Liga = new Liga($idLiga);

            if ($Liga["Estado"] == "ACTIVA"){
                $idLigaEdicionActivaMasReciente = $idLiga;
            }
        }

        if ($idLigaEdicionActivaMasReciente == ""){
            return $this;
        }
        else{
            return new Liga($idLigaEdicionActivaMasReciente);
        }


    }

    function existeEdicionMasReciente(){

        if ($this->obtenerNumeroEdicionLigaMasReciente() > $this->obtenerEdicion()){
            return true;
        }

        return false;
    }

    function obtenerIdsCampos(){

        $array_idsCampos = array();

        $array_Campos = $this->obtenerCampos();
        foreach ($array_Campos as $Campo){
            $array_idsCampos[] = $Campo->obtenerId();
        }

        return $array_idsCampos;
    }

    function obtenerCampos(){
        global $bd;

        $ids = $bd->select("camposporligas", "id_campo", array("id_liga" => $this->obtenerId(), "ORDER" => "id DESC"));
        $array = [];
        foreach ($ids as $id) {
            //JMAM: Evita obtener campos con ID 0
            if (!empty($id)){
                $array[] = new Campo($id);
            }

        }
        return $array;
    }

    function obtenerIdsCamposConModuloDeReservaActivado(){
        $array_idsCampos = array();

        foreach ($this->obtenerCamposConModuloDeReservaActivado() as $Campo){
            $array_idsCampos[] = $Campo->obtenerId();
        }

        return $array_idsCampos;
    }

    function obtenerCamposConModuloDeReservaActivado(){

        $array_CamposModuloReservaActivado = array();
        $array_Campos = $this->obtenerCampos();

        foreach ($array_Campos as $Campo){

            if ($Campo->activadoModuloReserva()){
                $array_CamposModuloReservaActivado[] = $Campo;
            }
        }

        return $array_CamposModuloReservaActivado;
    }

    function esModuloReservaActivadoParaAlgunCampo(){
        $array_Campos = $this->obtenerCampos();

        foreach ($array_Campos as $Campo){

            if ($Campo->activadoModuloReserva()){
                return true;
            }
        }

        return false;
    }

    function esPermitidorReservarAJugadoresNoActivoParaAlgunCampo(){
        $array_Campos = $this->obtenerCampos();

        foreach ($array_Campos as $Campo){
            if ($Campo->activadoModuloReserva() && $Campo->obtenerConfiguracionReservaPistas()->esPermitidoReservarAJugadoresNoActivos()){
                return true;
            }
        }

        return false;
    }

    function obtenerCodigoPais(){
        return $this[self::COLUMNA_codigoPais];
    }

    function obtenerIdioma(){
        return strtolower($this->obtenerCodigoPais());
    }

    function obtenerTiempoZona(){
        $codigoPais = $this->obtenerCodigoPais();

        switch ($codigoPais){
            case "AR":
                //JMAM: Argentina
                return "America/Argentina/Buenos_Aires";

            case "CL":
                //JMAM: Chile
                return "America/Santiago";

            case "KW":
                //JMAM: Kuwait
                return "Asia/Kuwait";

            default:
                return "Europe/Madrid";
        }
    }

    function establecerEnServidorFechaYHoraPais(){

        $codigoPais = $this->obtenerCodigoPais();

        switch ($codigoPais){
            case "AR":
                //JMAM: Argentina
                date_default_timezone_set("America/Argentina/Buenos_Aires");
                Log::v(__FUNCTION__, "Establecido Horario ARGENTINA: ".date("d-m-y H:i"), true);
                break;

            case "CL":
                //JMAM: Chile
                date_default_timezone_set("America/Santiago");
                Log::v(__FUNCTION__, "Establecido Horario CHILE: ".date("d-m-y H:i"), true);
                break;

            case "KW":
                //JMAM: Chile
                date_default_timezone_set("Asia/Kuwait");
                Log::v(__FUNCTION__, "Establecido Horario KUWAIT: ".date("d-m-y H:i"), true);
                break;

            default:
                date_default_timezone_set("Europe/Madrid");
                break;
        }

    }

    function obtenerJugadoresAdministradores(){
        return Juegalaliga::obtenerJugadoresAdministradores($this->obtenerId());
    }


    function obtenerEstado(){
        return $this[self::COLUMNA_estado];
    }

    function actualizarEstado($estado){
        $this[self::COLUMNA_estado] = $estado;
        $this->guardar();
    }

    function actualizarCacheClasificacion(){
        CacheClasificacion::actualizarCacheClasificacionPorLiga($this->obtenerId());
    }

    function obtenerIdsClubs(){
        $array_Campos = $this->obtenerCampos();

        $array_idsClubs = array();
        foreach ($array_Campos as $Campo){
            $Campo = new Campo($Campo->obtenerId());
            $idClub = $Campo->obtenerClub()->obtenerId();

            if (is_numeric($idClub)){
                if (in_array($idClub, $array_idsClubs) == false){
                    $array_idsClubs[] = $idClub;
                }


            }

        }

        return $array_idsClubs;
    }

    function obtenerClub(){
        return new Club($this->obtenerIdsClubs()[0]);
    }

    function obtenerFechaInicioPlazoInscripcion(){
        return $this[self::COLUMNA_fechaInicioPlazoInscripcion];
    }

    function obtenerFechaInicio(){
        return $this[self::COLUMNA_fechaInicio];
    }

    function obtenerFechaFin(){
        return $this[self::COLUMNA_fechaFin];
    }

    function obtenerEmailContacto(){
        return $this[self::COLUMNA_emailContacto];
    }

    function enviarEmailNuevoJugadorAQueriorApuntarseAlClub()
    {
        $Juegalaliga = new Juegalaliga(Sesion::obtenerJugador()->obtenerId(), $this->obtenerId());
        if ($Juegalaliga->esEnviadoEmailJugadorAQueridoApuntarse()){
            return;
        }

        $esEnviarEmailJugadorAQueridoApuntarsePorClub = false;
        if (count($this->obtenerIdsClubs()) <= 1){
            $esEnviarEmailJugadorAQueridoApuntarsePorClub = $this->obtenerClub()->esEnviarEmailJugadorAQueridoApuntarse();
        }

        if ($this->esEnviarEmailJugadorAQueridoApuntarse() || $esEnviarEmailJugadorAQueridoApuntarsePorClub){
            $asunto = Traductor::traducir("Nuevo Jugador ha querido Apuntarse", false, $this->obtenerIdioma());
            $array_search = array("%NOMBRE_JUGADOR%", "%TELEFONO_JUGADOR%", "%NOMBRE_LIGA%");
            $array_replace = array(Sesion::obtenerJugador()->obtenerNombre(), Sesion::obtenerJugador()->obtenerTelefono(true), $this->obtenerNombre(true));
            $mensaje = str_replace($array_search, $array_replace, Traductor::traducir("El Jugador %NOMBRE_JUGADOR% con teléfono %TELEFONO_JUGADOR% ha querido apuntarse a la Liga %NOMBRE_LIGA%", false, $this->obtenerIdioma()).".");

            Email::enviarEmail($this->obtenerEmailContacto(), $asunto, $mensaje, true, false, false, false, true);
            $Juegalaliga[Juegalaliga::COLUMNA_esEnviadoEmailJugadorAQueridoApuntarse] = 1;
            $Juegalaliga->guardar();
        }

    }

    function enviarEmailNuevaLigaCreadaAAdministradores(){

        $nombreLiga = $this->obtenerNombre();
        $fechaInicio = formatearFecha($this->obtenerFechaInicio(), true);
        $fechaFin = formatearFecha($this->obtenerFechaFin(), true);
        $urlLoginPcu = WWWBASE."PCU";
        $urlNormas = WWWBASE."normasdelaliga.php";
        $urlContactar = WWWBASE."contactar.php";

        $array_idsUsuariosAdministradores = $this->obtenerIdsUsuariosAdministradores();
        Log::v(__FUNCTION__, "IDS USUARIOS ADMINISTRADORES: ".print_r($array_idsUsuariosAdministradores, true));
        foreach ($array_idsUsuariosAdministradores as $idUsuarioAdministrador){
            $UsuarioAdministrador = new UsuarioAdministrador($idUsuarioAdministrador);
            $usuario = $UsuarioAdministrador->obtenerUsuario();
            $contrasena = $UsuarioAdministrador->obtenerContrasena();

            $search = array("%NOMBRE_LIGA%", "%FECHA_INICIO%", "%FECHA_FIN%", "%URL_LOGIN_PCU%", "%URL_NORMAS%", "%USUARIO%", "%CONTRASEÑA%", "%URL_CONTACTAR%");
            $remplace = array($nombreLiga, $fechaInicio, $fechaFin, $urlLoginPcu, $urlNormas, $usuario, $contrasena, $urlContactar);
            $mensajeEmail = str_replace($search, $remplace, Traductor::traducir("EMAIL_NUEVA_LIGA_NOTIFICAR_ADMINISTRADOR", false, $this->obtenerCodigoPais()));

            $emailContacto = $this->obtenerEmailContacto();
            $asunto = Traductor::traducir("Nueva liga Rádical", false, $this->obtenerCodigoPais());

            Log::v(__FUNCTION__, "Email contacto administrador: $emailContacto");
            Email::enviarEmail($emailContacto, $asunto, $mensajeEmail, true, true, "", true, true);

        }

    }

    function obtenerIdsUsuariosAdministradores(){
        return UsuarioAdministrador::obtenerIdsUsuariosAdministradoresLiga($this->obtenerId());
    }

    function obtenerUsuarioAdministrador(){
        return UsuarioAdministrador::obtenerUsuarioAdministrador($this->obtenerId());
    }

    function esIndicadaNOAdministradaPorClub($idClub){
        $Club = new Club($idClub);
        $array_idsLigasNoAdministra = $Club->obtenerIdsLigasNoAdministra();


        foreach ($array_idsLigasNoAdministra as $idLigaNoAdministra){
            if ($this->obtenerId() == $idLigaNoAdministra){
                return true;
            }
        }

        return false;
    }

    function tieneAlgunPackContratadoDisponibleTotalesEnLaLiga(){
        if ($this->obtenerUsuarioAdministrador()->tienePermitidoVerSoloPackDeLigasAdministradas()){
            $array_idsLigasAdministradas = $this->obtenerUsuarioAdministrador()->obtenerIdsLigasAdministradas();
            foreach ($array_idsLigasAdministradas as $idLigaAdministrada){
                $LigaAdministrada = new Liga($idLigaAdministrada);
                if($LigaAdministrada->tieneAlgunPackContratado()){
                    Log::v(__FUNCTION__,"Tiene Packs Contratados", false);
                    return true;
                }
            }
            Log::v(__FUNCTION__,"NO Tiene Packs Contratados", false);
            return false;
        }
        else{
            return $this->tieneAlgunPackContratado();
        }

    }

    function obtenerNumeroInscripcionesPacksDisponiblesTotalesEnLaLiga($idLiga, $idClub){

        require_once BASE."PCU/funciones_PACKs.php";

        $Liga  = new Liga($idLiga);
        $S_Ligas = implode(",", $Liga->obtenerUsuarioAdministrador()->obtenerIdsLigasAdministradas());
        $S_permitidoVerSoloPacksDeLigasAdministradas = $Liga->obtenerUsuarioAdministrador()->tienePermitidoVerSoloPackDeLigasAdministradas();


        Log::v(__FUNCTION__, "ID LIGA: $idLiga | ID CLUB: $idClub");

        if ($S_permitidoVerSoloPacksDeLigasAdministradas == 1){
            Log::v(__FUNCTION__, "Mostrar solo ligas administradas en PACKS:".$S_Ligas, true);
            $ArraydeIDdePACKS=los_packs_de_las_ligas ($S_Ligas,$idClub,'1',true, true, $idLiga);
        }
        else{
            if ($idLiga>0){
                $Liga = new Liga($idLiga);


                /*
                if ($Liga->estaLigaEsAlias()){
                    $Liga = $Liga->obtenerLigaPadre();
                }*/


                $idLigaACargarPacks = $Liga->obtenerId();
                Log::v(__FUNCTION__, "ID Liga a cargar Packs:".$idLigaACargarPacks, false);
                $ArraydeIDdePACKS=los_packs_de_las_ligas ($idLigaACargarPacks,$idClub,'1');
            }
            else{
                $array_idsLigas_paraLigaEnCuestion = implode(",",$S_Ligas);


                //Log::v(__FUNCTION__, "Liga seleccionada: $LIGAenCUESTION", true);
            }
        }

        $numeroInscripcionesTotales = 0;
        foreach ($ArraydeIDdePACKS as $id_pack)
        {
            Log::v(__FUNCTION__,"ID PACK 1: $id_pack", false);

            $Pack = new Pack($id_pack);
            $numeroInscripcionesTotales += $Pack->obtenerNumeroInscripcionesTotales();
            $qPACK="SELECT * FROM packs WHERE id=$id_pack";
            $resPACK=mysql_query($qPACK);
        }


        $numeroInscripcionesUsadasNormalPacks = Juegalaliga::obtenerNumeroJugadoresActivosInscritosEnPackEnLiga($idLiga, Juegalaliga::TIPO_ACTIVACION_EN_PACK_normal, "");
        $numeroInscripcionesProrrogadasPacks = Juegalaliga::obtenerNumeroJugadoresActivosInscritosEnPackEnLiga($idLiga, Juegalaliga::TIPO_ACTIVACION_EN_PACK_prorrogada);
        $numeroInscripcionesCaducadasPacks = Juegalaliga::obtenerNumeroJugadoresInscritosEnPackEnLiga($idLiga, Juegalaliga::TIPO_ESTADO_pendiente, Juegalaliga::TIPO_ACTIVACION_EN_PACK_caducadaProrroga);

        $numeroInscripcionesDisponiblesPacks = $numeroInscripcionesTotales - $numeroInscripcionesUsadasNormalPacks;

        Log::v(__FUNCTION__, "Inscripciones Normales: $numeroInscripcionesUsadasNormalPacks | Inscripciones Prorrogadas: $numeroInscripcionesProrrogadasPacks | Inscripciones Caducadas: $numeroInscripcionesCaducadasPacks  | Totales disponibles: $numeroInscripcionesDisponiblesPacks", true);
        return $numeroInscripcionesDisponiblesPacks;
    }

    function esActiva(){
        if ($this[self::COLUMNA_estado] == self::ESTADO_activa){
            return true;
        }

        return false;
    }

    function esTerminada(){

        if ($this[self::COLUMNA_estado] == self::ESTADO_terminada){
            Log::v(__FUNCTION__, "Liga es terminada", false);
            return true;
        }

        return false;
    }

    function obtenerIdsDeportes(){
        $array_idsDeportes = array();

        $array_idsCampos = $this->obtenerIdsCampos();
        foreach ($array_idsCampos as $idCampo){
            $Campo = new Campo($idCampo);

            $array_idsDeportes_Campo = $Campo->obtenerIdsDeportes();
            Log::v(__FUNCTION__, "ID Campo: $idCampo | ID Deporte: ".print_r($array_idsDeportes_Campo, true), false);
           $array_idsDeportes = array_merge($array_idsDeportes, $array_idsDeportes_Campo);
        }

        Log::v(__FUNCTION__, print_r($array_idsDeportes, true), false);
        return array_unique($array_idsDeportes);
    }

    function obtenerNumeroDeportes(){
        return count($this->obtenerIdsDeportes());
    }

    function obtenerNumeroDePuntosTotalesMaximoPorCualquierJugador($idCampo=""){
        return Resultado::obtenerNumeroDePuntosTotalesMaximoPorCualquierJugadorDeLaIdLiga($this->obtenerId(), $idCampo);
    }

    function obtenerIdsPistas(){

        $array_idsPistas = array();
        foreach ($this->obtenerIdsCamposConModuloDeReservaActivado() as $idCampo){
            $Campo = new Campo($idCampo);
            $array_idsPistas = array_merge($array_idsPistas, $Campo->obtenerIdsPistas());
        }

        return $array_idsPistas;
    }

    static function existenLigasEnLocalidad($localidad){
        global $bd;

        $resultado = $bd->query("SELECT id FROM liga WHERE (Estado='ACTIVA' OR Estado='PREINSCRIPCION') AND ZonaGeografica = '$localidad'")->fetchAll();
        if (count($resultado) == 0){
            return false;
        }
        else{
            return true;
        }

    }

    static function esLigaSuscripcion($idLiga){

        $Liga = new Liga($idLiga);

        return $Liga->esSuscripcion();
    }

    static function obtenerLigasQueComienzanEnFecha($fecha, $tieneJugadorAdministrador = false){
        global $bd;

        $arrayLigas = array();

        if ($tieneJugadorAdministrador){
            $ligasObtenidas = $bd->query("SELECT id FROM liga WHERE Fecha_INI = '$fecha' AND Estado = 'ACTIVA' AND idJugadorAdministrador > 0")->fetchAll();
        }
        else{
            $ligasObtenidas = $bd->query("SELECT id FROM ligas WHERE Fecha_INI = '$fecha' AND Estado = 'ACTIVA'")->fetchAll();
        }



        foreach ($ligasObtenidas as $ligasObtenida){

            $idLiga = $ligasObtenida["id"];
            array_push($arrayLigas, new Liga($idLiga));
        }

        return $arrayLigas;
    }

    static function obtenerLigas($idCampo = ""){
        global $bd;

        $arrayLigas = array();

        $fechaHoy = date("Y-m-d");

        if ($idCampo > 0){
            $where = "id IN (SELECT id_liga FROM camposporligas WHERE id_campo=$idCampo AND id_liga!=0) OR ".self::COLUMNA_idAliasLiga." IN (SELECT id_liga FROM camposporligas WHERE id_campo=$idCampo AND id_liga!=0)";
        }

        Log::v(__FUNCTION__, "SELECT id FROM liga WHERE $where ORDER BY id DESC");

        $ligasObtenidas = $bd->query("SELECT id FROM liga WHERE $where ORDER BY id DESC")->fetchAll();



        foreach ($ligasObtenidas as $ligasObtenida){

            $idLiga = $ligasObtenida["id"];
            array_push($arrayLigas, new Liga($idLiga));
        }

        return $arrayLigas;
    }

    static function obtenerIdLigaPorDefecto($array_idsLigas){

        $fechaHoy = date("Y-m-d");

        foreach ($array_idsLigas as $idLiga){
            $Liga = new Liga($idLiga);

            if ($Liga->obtenerFechaInicio() <= $fechaHoy && $Liga->obtenerFechaFin() >= $fechaHoy && !$Liga->esTerminada()){
                return $idLiga;
            }
        }

        return "";

    }

    static function obtenerIdsLigasActivas($idCampo="", $sinFinalizar=false){

        $array_idsLigas = array();
        $array_Ligas = self::obtenerLigasActivas($idCampo, $sinFinalizar);

        foreach ($array_Ligas as $Liga){
            $array_idsLigas[] = $Liga->obtenerId();
        }

        return $array_idsLigas;
    }

    static function obtenerLigasActivas($idCampo = "", $sinFinalizar=false){
        global $bd;

        $arrayLigas = array();

        $fechaHoy = date("Y-m-d");

        if ($idCampo > 0){
            $where = "AND id IN (SELECT id_liga FROM camposporligas WHERE id_campo=$idCampo)";
        }

        if ($sinFinalizar){
            $where .= " AND ".Liga::COLUMNA_fechaInicio." <= '$fechaHoy' AND ".Liga::COLUMNA_fechaFin." >= '$fechaHoy'";
        }

        $ligasObtenidas = $bd->query("SELECT id FROM liga WHERE Estado != '".self::ESTADO_terminada."' $where")->fetchAll();



        foreach ($ligasObtenidas as $ligasObtenida){

            $idLiga = $ligasObtenida["id"];
            array_push($arrayLigas, new Liga($idLiga));
        }

        return $arrayLigas;
    }

    static function  obtenerUltimaLigaTerminaActivaEnClub($idClub){

        $array_LigasActivasClub = self::obtenerLigasActivasClub($idClub);


        $Liga_ultimaLigaTerminaActivaEnCLub = null;
        $fechaFinLigaAnterior_strotime  = 0;
        foreach ($array_LigasActivasClub as $Liga){
            $Liga = new Liga($Liga->obtenerId());

            $fechaFinLiga_strotime = strtotime($Liga->obtenerFechaFin());

            Log::v(__FUNCTION__, "fechaFinLigaAnterior_strotime: $fechaFinLigaAnterior_strotime | fechaFinLiga_strotime: $fechaFinLiga_strotime", false);
            if ($fechaFinLiga_strotime > $fechaFinLigaAnterior_strotime){
                $Liga_ultimaLigaTerminaActivaEnCLub = $Liga;
            }

            $fechaFinLigaAnterior_strotime = $fechaFinLiga_strotime;
        }

        return $Liga_ultimaLigaTerminaActivaEnCLub;
    }

    static function obtenerLigasActivasClub($idClub, $sinFinalizar=false, $ocultarLigasNoAdministradas=false){
        global $bd;

        $array_LigasClub = array();
        $array_Campos = Campo::obtenerTodos($idClub);

        //print_r($array_Campos);
        foreach ($array_Campos as $Campo){
           $array_LigasCampo =  $Campo->obtenerLigasActivas($sinFinalizar);
           $array_LigasClub = (array)$array_LigasClub + (array)$array_LigasCampo;
        }

        if ($ocultarLigasNoAdministradas){
            //JMAM: Comprueba las ligas que no administra
            $array_LigasClubAdministra = array();
            foreach ($array_LigasClub as $Liga){

                if ($Liga->esIndicadaNOAdministradaPorClub($idClub) == false){
                    $array_LigasClubAdministra[] = $Liga;
                }
            }

            return $array_LigasClubAdministra;
        }
        else{
            return $array_LigasClub;
        }

        //return $array_LigasClub;
    }

    static function obtenerLigasClub($idClub, $ocultarLigasNoAdministradas=false){
        global $bd;

        $Club = new Club($idClub);
        $array_LigasClub = array();
        $array_Campos = Campo::obtenerTodos($idClub);

        foreach ($array_Campos as $Campo){
            $array_LigasCampo =  $Campo->obtenerLigas();
            $array_LigasClub = (array)$array_LigasClub + (array)$array_LigasCampo;
        }


        if ($ocultarLigasNoAdministradas){
            //JMAM: Comprueba las ligas que no administra
            $array_LigasClubAdministra = array();
            foreach ($array_LigasClub as $Liga){

                if ($Liga->esIndicadaNOAdministradaPorClub($idClub) == false){

                    if ($Liga->esEliminado() == false){
                        $array_LigasClubAdministra[] = $Liga;
                    }

                }
            }

            return $array_LigasClubAdministra;
        }
        else{
            return $array_LigasClub;
        }


    }







}