<?php

class Campo extends general implements InterfazObjeto, InternacionalidadInterface
{
    const TABLA_nombre = "campos";
    const COLUMNA_id = "id";
    const COLUMNA_idClub = "id_club";
    const COLUMNA_nombre = "Nombre";
    const COLUMNA_codigoPais = "codigoPais";
    const COLUMNA_MODULORESERVAACTIVADODESACTIVADO = "Extra3";
    const COLUMNA_mostrarEnFiltroSelectorClasificacion = "mostrarEnFiltroSelectorClasificacion";
    const COLUMNA_mostrarEnFiltroSelectorCamposAbrirPartido = "mostrarEnFiltroSelectorCamposAbrirPartido";

    const COLUMNA_tieneConvenio = "Convenio";
    const COLUMNA_provincia = "Provincia";
    const COLUMNA_imagen = "Foto_CAMPO";
    const COLUMNA_horariosYPrecios = "Condiciones";
    const COLUMNA_observaciones = "Observaciones";

    function __construct($id="")
    {
        if ($id != '')
            parent::__construct(self::TABLA_nombre, self::COLUMNA_id, $id);
        else
            parent::__construct(self::TABLA_nombre, '', '');
    }

    static function obtenerIdsCamposConModuloReservaActivado(){
        global $bd;

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, array(self::COLUMNA_MODULORESERVAACTIVADODESACTIVADO => 1));
    }

    static function obtenerIdsCamposDeIdClub($idClub){
        global $bd;

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, array(self::COLUMNA_idClub => $idClub));
    }

    static function obtenerTodos($idClub = ""){
        global $bd;

        if ($idClub == ""){
            $ids = $bd->select(self::TABLA_nombre, "id", array("ORDER" => "id DESC"));
        }
       else{
           $ids = $bd->select(self::TABLA_nombre, "id", array("id_club" => $idClub), array("ORDER" => "id DESC"));
       }

        $array = [];
        foreach ($ids as $id) {
            $array[] = new Campo($id);
        }
        return $array;
    }


    function obtenerId(){
        return $this[self::COLUMNA_id];
    }

    function obtenerIdClub(){
        return $this["id_club"];
    }

    function obtenerClub(){
        return new Club($this->obtenerIdClub());
    }

    function obtenerProvincia(){
        return $this[self::COLUMNA_provincia];
    }

    function obtenerLocalidad(){
        return $this["Localidad"];
    }

    function obtenerTextoHorariosYPrecios(){
        return $this[self::COLUMNA_horariosYPrecios];
    }

    function obtenerImagen(){

        $imagen = $this[self::COLUMNA_imagen];

        if ($imagen){
            return WWWBASE_PRODUCCION.'PCU/fotos/'.$imagen;
        }
        else{
            return WWWBASE_PRODUCCION.'PCU/fotos/SinFoto.gif';
        }
    }

    function tieneConvenio(){

        $convenito = $this[self::COLUMNA_tieneConvenio];

        if ($convenito == "S"){
            return true;
        }

        return false;
    }

    function activadoModuloReserva(){

        if ($this[self::COLUMNA_MODULORESERVAACTIVADODESACTIVADO] == 1){
            return true;
        }
        else{
            return false;
        }

    }

    function obtenerConfiguracionReservaPistas(){
        return ConfiguracionReservaPistas::obtenerConfiguracionReservaPistas($this->obtenerId());
    }

    function esPermitidoRealizarReservasPorJugadores(){
        $resultado =  $this->obtenerConfiguracionReservaPistas()->esPermitidoReservarAJugadores();
        if ($resultado){
            $estadoJugadorEnLiga = Sesion::obtenerJugador()->obtenerEstadoEnLiga(Sesion::obtenerLiga()->obtenerId());
            if ($estadoJugadorEnLiga != Juegalaliga::TIPO_ESTADO_activo){
                return $this->obtenerConfiguracionReservaPistas()->esPermitidoReservarAJugadoresNoActivos();
            }
        }

        return $resultado;
    }

    function sePuedeMostrarEnFiltroSelectorClasificacion(){
        $mostrarEnFiltroSelectorClasificacion = $this[self::COLUMNA_mostrarEnFiltroSelectorClasificacion];

        if ($mostrarEnFiltroSelectorClasificacion == 1){
            return true;
        }

        return false;
    }

    function tienePistas(){
        return Pista::campoTienePistas($this["id"]);
    }

    function obtenerPistas($idDeporte="", $desactivado=0){
        return Pista::obtenerTodos($this->obtenerId(), $desactivado,$idDeporte);
    }

    function obtenerIdsPistas($idDeporte="", $desactivado=0){
        return Pista::obtenerIds($this->obtenerId(), $desactivado, $idDeporte);
    }

    function obtenerNumeroPistas($idDeporte=""){
        return count($this->obtenerIdsPistas($idDeporte));
    }

    function obtenerNombre(){
        return $this["Nombre"];
    }

    function obtenerLigasActivas($sinFinalizar=false){
       return Liga::obtenerLigasActivas($this->obtenerId(), $sinFinalizar);
    }

    function obtenerLigas(){
        return Liga::obtenerLigas($this->obtenerId());
    }


    function obtenerCodigoPais(){
        return $this[self::COLUMNA_codigoPais];
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

        switch ($codigoPais) {
            case "AR":
                //JMAM: Argentina
                date_default_timezone_set("America/Argentina/Buenos_Aires");
                Log::v(__FUNCTION__, "Establecido Horario ARGENTINA: " . date("d-m-y H:i"), true);
                break;

            case "CL":
                //JMAM: Chile
                date_default_timezone_set("America/Santiago");
                Log::v(__FUNCTION__, "Establecido Horario CHILE: " . date("d-m-y H:i"), true);
                break;

            case "KW":
                //JMAM: Chile
                date_default_timezone_set("Asia/Kuwait");
                Log::v(__FUNCTION__, "Establecido Horario KUWAIT: " . date("d-m-y H:i"), true);
                break;


            default:
                date_default_timezone_set("Europe/Madrid");
                break;
        }

    }

    function obtenerIdsJugadoresApuntadosAPartidos(){
        return Partido::obtenerJugadoresApuntadosAIdCampo($this->obtenerId());
    }

    function obtenerIdsLigasConJugadoresApuntadosAPartidos(){
        return Partido::obtenerIdsLigasConJugadoresApuntadosAIdCampo($this->obtenerId());
    }

    function existeAlgunHorarioPistaParaElCampo($fechaMYSQL){
        return HorarioPista::existeAlgunHorarioPistaParaElCampoYFecha($this->obtenerId(), $fechaMYSQL);
    }

    function obtenerIdsDeportes(){

        $array_idsDeportes = array();

        $array_idsPistas = $this->obtenerIdsPistas();
        foreach ($array_idsPistas as $idPista){
            $Pista = new Pista($idPista);
            $idDeporte_Pista = $Pista->obtenerDeporte()->obtenerId();
            Log::v(__FUNCTION__, "ID PISTA: $idPista | ID DEPORTE: $idDeporte_Pista");
            $array_idsDeportes[] = $idDeporte_Pista;
        }

        return array_unique($array_idsDeportes);
    }

    function existe(){
        if ($this->obtenerId() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}