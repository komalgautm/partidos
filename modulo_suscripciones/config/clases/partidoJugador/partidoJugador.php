<?php

class PartidoJugador extends general
{
    const TABLA_nombre = "partidosJugadores";
    const COLUMNA_id = "id";
    const COLUMNA_idPartido = "idPartido";
    const COLUMNA_idReservaPista = "idReservaPista";
    const COLUMNA_idJugador = "idJugador";
    const COLUMNA_numeroJugador = "numeroJugador";
    const COLUMNA_tipoJugador = "tipoJugador";
    const COLUMNA_importePago = "importePago";
    const COLUMNA_tipoPagoJugador = "tipoPagoJugador";
    const COLUMNA_esPagadoJugador = "esPagadoJugador";
    const COLUMNA_esAplazadoPagoJugador = "esAplazadoPagoJugador";
    const COLUMNA_fechaPagoJugador = "fechaPagoJugador";
    const COLUMNA_fecha = "fecha";

    const TIPOJUGADOR_interno = "interno";
    const TIPOJUGADOR_externo = "externo";

    const ESPAGADOJUGADOR_si = 1;
    const ESPAGADOJUGADOR_no = 0;


    function __construct($id="")
    {
        if ($id != '')
            parent::__construct(self::TABLA_nombre, self::COLUMNA_id, $id);
        else
            parent::__construct(self::TABLA_nombre, '', '');
    }

    static function obtenerIds(){
        global $bd;

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id);
    }

    static function eliminarTodosLosJugadoresDelPartido($idPartido){
        $array_idsPartidoJugadores = self::obtenerIdsPartidoJugadoresDelPartido($idPartido);
        foreach ($array_idsPartidoJugadores as $idPartidoJugador){
            $PartidoJugador = new PartidoJugador($idPartidoJugador);
            $PartidoJugador->eliminar();
        }
    }

    static function obtenerJugadorDelPartido($idPartido, $numeroJugador){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido] = $idPartido;
        $where["AND"][self::COLUMNA_numeroJugador] = $numeroJugador;

        return new Jugador($bd->get(self::TABLA_nombre, self::COLUMNA_idJugador, $where));
    }

    static function obtenerIdsPartidosDelJugadorApuntadoParaElDia($idJugador, $fechaMYSQL){
        $array_idsPartidosApuntadoJugador = array();

        $array_idsPartidos = Partido::obtenerIdsPartidosElDia($fechaMYSQL);
        foreach ($array_idsPartidos as $idPartido){
            $Partido = new Partido($idPartido);
            if ($Partido->esJugadorApuntadoAPartido($idJugador)){
                $array_idsPartidosApuntadoJugador[] = $idPartido;
            }
        }

        return $array_idsPartidosApuntadoJugador;
    }

    static function obtenerIdsPartidosDeIdLigaDelIdJugador($idLiga, $idJugador){
        global $bd;

        $Liga = new Liga($idLiga);
        $array_idsPartidosLiga = $Liga->obtenerIdsPartidos();

        $where["AND"][self::COLUMNA_idJugador] = $idJugador;
        $where["AND"][self::COLUMNA_idPartido] = $array_idsPartidosLiga;
        return $bd->select(self::TABLA_nombre, self::COLUMNA_idPartido, $where);
    }

    static function esAlgunaVezApuntadoAPartidoEnIdLigaIdJugador($idLiga, $idJugador){

        if (count(self::obtenerIdsPartidosDeIdLigaDelIdJugador($idLiga, $idJugador)) > 0){
            return true;
        }

        return false;
    }

    static function existeJugadorApuntandoEnElPartido($idPartido, $idJugador){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido] = $idPartido;
        $where["AND"][self::COLUMNA_idJugador] = $idJugador;

        if ($bd->count(self::TABLA_nombre, $where) > 0){
            return true;
        }

        return false;
    }

    static function obtenerPartidoJugadorDelPartido($idPartido, $numeroJugador){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido] = $idPartido;
        $where["AND"][self::COLUMNA_numeroJugador] = $numeroJugador;

        return new PartidoJugador($bd->get(self::TABLA_nombre, self::COLUMNA_id, $where));
    }

    static function obtenerPartidoJugadorDelPartidoPorIdJugador($idPartido, $idJugador){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido] = $idPartido;
        $where["AND"][self::COLUMNA_idJugador] = $idJugador;

        return new PartidoJugador($bd->get(self::TABLA_nombre, self::COLUMNA_id, $where));
    }

    static function obtenerPartidoJugadorDeLaReservaPistaPorIdJugador($idReservaPista, $idJugador){
        global $bd;

        $where["AND"][self::COLUMNA_idReservaPista] = $idReservaPista;
        $where["AND"][self::COLUMNA_idJugador] = $idJugador;

        return new PartidoJugador($bd->get(self::TABLA_nombre, self::COLUMNA_id, $where));
    }

    static function obtenerPartidoJugadorDelPartidoPorNumeroJugador($idPartido, $numeroJugador){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido] = $idPartido;
        $where["AND"][self::COLUMNA_numeroJugador] = $numeroJugador;

        return new PartidoJugador($bd->get(self::TABLA_nombre, self::COLUMNA_id, $where));
    }

    static function obtenerNumeroJugadores($idPartido){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido] = $idPartido;

        return $bd->count(self::TABLA_nombre, $where);
    }

    static function obtenerIdsJugadoresPartido($idPartido){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido] = $idPartido;
        return $bd->select(self::TABLA_nombre, self::COLUMNA_idJugador,  $where);

    }


    static function obtenerIdsPartidoJugadoresDelPartido($idPartido){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido] = $idPartido;
        $where["ORDER"]= self::COLUMNA_numeroJugador." ASC";

        return $bd->select(self::TABLA_nombre, self::COLUMNA_id, $where);
    }

    static function eliminarTodosLosPartidoJugadoresDelPartido($idPartido){
        $array_idsPartidoJugadores = self::obtenerIdsPartidoJugadoresDelPartido($idPartido);
        foreach ($array_idsPartidoJugadores as $idPartidoJugador){
            (new PartidoJugador($idPartidoJugador))->eliminar();
        }
    }

    function obtenerId(){
        return $this[self::COLUMNA_id];
    }

    function obtenerPartido(){
        return new Partido($this[self::COLUMNA_idPartido]);
    }

    function obtenerReservaPista(){
        return new ReservaPista($this[self::COLUMNA_idReservaPista]);
    }

    function obtenerJugador(){
        if (self::obtenerTipoJugador() == self::TIPOJUGADOR_externo){
            return new JugadorExterno($this[self::COLUMNA_idJugador]);
        }
        else{
            return new Jugador($this[self::COLUMNA_idJugador]);
        }

    }

    function obtenerTipoJugador(){

        $tipoJugador = $this[self::COLUMNA_tipoJugador];
        if (empty($tipoJugador)){
            return self::TIPOJUGADOR_interno;
        }
        else{
            return $tipoJugador;
        }
    }

    function obtenerNumeroJugador(){
        return $this[self::COLUMNA_numeroJugador];
    }

    function obtenerImportePago(){
        return $this[self::COLUMNA_importePago];
    }

    function obtenerTipoPagoJugador(){
        return $this[self::COLUMNA_tipoPagoJugador];
    }

    function esPagadoJugador($ignorarPagoAplazado=true){
        if ($this[self::COLUMNA_esPagadoJugador] == 1){
            return true;
        }
        else{
            if (!$ignorarPagoAplazado){
                return $this->esAplazadoPagoJugador();
            }
            else{
                return false;
            }
        }

        return false;
    }


    function esAplazadoPagoJugador(){

        if ($this[self::COLUMNA_esAplazadoPagoJugador] == 1){
            return true;
        }

        return false;
    }

    function obtenerFechaPagoJugador(){
        return $this[self::COLUMNA_fechaPagoJugador];
    }

    function esBuscandoSustituto(){
        return $this->obtenerJugador()->esBuscandoSustituto($this->obtenerPartido()->obtenerId());
    }

    static function reordenarJugadoresDelPartido($idPartido){
        $array_idsPartidoJugadores = self::obtenerIdsPartidoJugadoresDelPartido($idPartido);
        $numeroJugador = 1;
        foreach ($array_idsPartidoJugadores as $idPartidoJugador){
            $PartidoJugador = new PartidoJugador($idPartidoJugador);
            $PartidoJugador[self::COLUMNA_numeroJugador] = $numeroJugador;
            $PartidoJugador->guardar(null, "", false);
            $numeroJugador++;
        }
    }

    static function importarJugadoresDeTablaPartido($idPartido, $forzarObtenerIdJugadorDesdeReserva=false){
        $Partido = new Partido($idPartido);
        $ReservaPista = $Partido->obtenerReservaPistaPartido();

        if (!$ReservaPista->existe()){
            $forzarObtenerIdJugadorDesdeReserva = false;
        }

        Log::v(__FUNCTION__, "Importar ID Partido: $idPartido | Forzar Obtener Id Jugador Desde Reserva: $forzarObtenerIdJugadorDesdeReserva", true);


        //JUGADOR1
        if ($forzarObtenerIdJugadorDesdeReserva){
            $idJugador = $ReservaPista[ReservaPista::COLUMNA_idJugador1];
        }
        else{
            $idJugador = $Partido[Partido::COLUMNA_idJugador1];
        }

        Log::v(__FUNCTION__, "ID Jugador 1: $idJugador", true);

        if ($idJugador > 0){
            $PartidoJugador = self::obtenerPartidoJugadorDelPartidoPorIdJugador($idPartido, $idJugador);
            $PartidoJugador[self::COLUMNA_numeroJugador] = 1;
            $PartidoJugador[self::COLUMNA_idPartido] = $idPartido;
            $PartidoJugador[self::COLUMNA_idJugador] = $idJugador;
            if ($ReservaPista->existe()){
                $PartidoJugador[self::COLUMNA_idReservaPista] = $ReservaPista->obtenerId();
                $PartidoJugador[self::COLUMNA_importePago] = $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR1];
                $PartidoJugador[self::COLUMNA_tipoJugador] = $ReservaPista->obtenerTipoJugador1();
                $PartidoJugador[self::COLUMNA_tipoPagoJugador] = $ReservaPista->obtenerTipoPagoJugador1();
                $PartidoJugador[self::COLUMNA_esAplazadoPagoJugador] = $ReservaPista->esAplazadoPagoJugador1();
                $PartidoJugador[self::COLUMNA_fechaPagoJugador] = $ReservaPista->obtenerFechaPagoJugador1();
            }
            $PartidoJugador->guardar();
            Log::v(__FUNCTION__, "Importado Jugador1: $idJugador");
        }
        else{
            Log::v(__FUNCTION__, "NO existe Jugador1: $idJugador");
        }

        //JUGADOR2
        if ($forzarObtenerIdJugadorDesdeReserva){
            $idJugador = $ReservaPista[ReservaPista::COLUMNA_idJugador2];
        }
        else{
            $idJugador = $Partido[Partido::COLUMNA_idJugador2];
        }

        Log::v(__FUNCTION__, "ID Jugador 2: $idJugador", true);


        if ($idJugador > 0){
            $PartidoJugador = self::obtenerPartidoJugadorDelPartidoPorIdJugador($idPartido, $idJugador);
            $PartidoJugador[self::COLUMNA_numeroJugador] = 2;
            $PartidoJugador[self::COLUMNA_idPartido] = $idPartido;
            $PartidoJugador[self::COLUMNA_idJugador] = $idJugador;
            if ($ReservaPista->existe()){
                $PartidoJugador[self::COLUMNA_idReservaPista] = $ReservaPista->obtenerId();
                $PartidoJugador[self::COLUMNA_importePago] = $ReservaPista[ReservaPista::COLUMNA_importePagoJugador2];
                $PartidoJugador[self::COLUMNA_tipoJugador] = $ReservaPista->obtenerTipoJugador2();
                $PartidoJugador[self::COLUMNA_tipoPagoJugador] = $ReservaPista->obtenerTipoPagoJugador2();
                $PartidoJugador[self::COLUMNA_esAplazadoPagoJugador] = $ReservaPista->esAplazadoPagoJugador2();
                $PartidoJugador[self::COLUMNA_fechaPagoJugador] = $ReservaPista->obtenerFechaPagoJugador2();
            }
            $PartidoJugador->guardar();
            Log::v(__FUNCTION__, "Importado Jugador2: $idJugador");
        }
        else{
            Log::v(__FUNCTION__, "NO existe Jugador2: $idJugador");
        }


        //JUGADOR3
        if ($forzarObtenerIdJugadorDesdeReserva){
            $idJugador = $ReservaPista[ReservaPista::COLUMNA_idJugador3];
        }
        else{
            $idJugador = $Partido[Partido::COLUMNA_idJugador3];
        }

        Log::v(__FUNCTION__, "ID Jugador 3: $idJugador", true);

        if ($idJugador > 0){
            $PartidoJugador = self::obtenerPartidoJugadorDelPartidoPorIdJugador($idPartido, $idJugador);
            $PartidoJugador[self::COLUMNA_numeroJugador] = 3;
            $PartidoJugador[self::COLUMNA_idPartido] = $idPartido;
            $PartidoJugador[self::COLUMNA_idJugador] = $idJugador;
            if ($ReservaPista->existe()){
                $PartidoJugador[self::COLUMNA_idReservaPista] = $ReservaPista->obtenerId();
                $PartidoJugador[self::COLUMNA_importePago] = $ReservaPista[ReservaPista::COLUMNA_importePagoJugador3];
                $PartidoJugador[self::COLUMNA_tipoJugador] = $ReservaPista->obtenerTipoJugador3();
                $PartidoJugador[self::COLUMNA_tipoPagoJugador] = $ReservaPista->obtenerTipoPagoJugador3();
                $PartidoJugador[self::COLUMNA_esPagadoJugador] = $ReservaPista->esPagadoJugador3();
                $PartidoJugador[self::COLUMNA_esAplazadoPagoJugador] = $ReservaPista->esAplazadoPagoJugador3();
                $PartidoJugador[self::COLUMNA_fechaPagoJugador] = $ReservaPista->obtenerFechaPagoJugador3();
            }
            $PartidoJugador->guardar();
            Log::v(__FUNCTION__, "Importado Jugador3: $idJugador");
        }
        else{
            Log::v(__FUNCTION__, "NO existe Jugador3: $idJugador");
        }


        //JUGADOR4
        if ($forzarObtenerIdJugadorDesdeReserva){
            $idJugador = $ReservaPista[ReservaPista::COLUMNA_idJugador4];
        }
        else{
            $idJugador = $Partido[Partido::COLUMNA_idJugador4];
        }

        Log::v(__FUNCTION__, "ID Jugador 4: $idJugador", true);


        if ($idJugador > 0){
            $PartidoJugador = self::obtenerPartidoJugadorDelPartidoPorIdJugador($idPartido, $idJugador);
            $PartidoJugador[self::COLUMNA_numeroJugador] = 4;
            $PartidoJugador[self::COLUMNA_idPartido] = $idPartido;
            $PartidoJugador[self::COLUMNA_idJugador] = $idJugador;
            if ($ReservaPista->existe()){
                $PartidoJugador[self::COLUMNA_idReservaPista] = $ReservaPista->obtenerId();
                $PartidoJugador[self::COLUMNA_importePago] = $ReservaPista[ReservaPista::COLUMNA_importePagoJugador4];
                $PartidoJugador[self::COLUMNA_tipoJugador] = $ReservaPista->obtenerTipoJugador4();
                $PartidoJugador[self::COLUMNA_tipoPagoJugador] = $ReservaPista->obtenerTipoPagoJugador4();
                $PartidoJugador[self::COLUMNA_esPagadoJugador] = $ReservaPista->esPagadoJugador4();
                $PartidoJugador[self::COLUMNA_esAplazadoPagoJugador] = $ReservaPista->esAplazadoPagoJugador4();
                $PartidoJugador[self::COLUMNA_fechaPagoJugador] = $ReservaPista->obtenerFechaPagoJugador4();
            }
            $PartidoJugador->guardar();
            Log::v(__FUNCTION__, "Importado Jugador4: $idJugador");
        }
        else{
            Log::v(__FUNCTION__, "NO existe Jugador4: $idJugador");
        }


        $array_idsParidoJugadores = self::obtenerIdsPartidoJugadoresDelPartido($idPartido);
        foreach ($array_idsParidoJugadores as $idPartidoJugador){
            $PartidoJugador = new PartidoJugador($idPartidoJugador);

            if ($forzarObtenerIdJugadorDesdeReserva){
                if($PartidoJugador->obtenerJugador()->obtenerId() != $ReservaPista[ReservaPista::COLUMNA_idJugador1] && $PartidoJugador->obtenerJugador()->obtenerId() != $ReservaPista[ReservaPista::COLUMNA_idJugador2] && $PartidoJugador->obtenerJugador()->obtenerId() != $ReservaPista[ReservaPista::COLUMNA_idJugador3] && $PartidoJugador->obtenerJugador()->obtenerId() != $ReservaPista[ReservaPista::COLUMNA_idJugador4]){
                    $PartidoJugador->eliminar();
                    Log::v(__FUNCTION__, "Eliminado jugador que no existe en el partido");
                }
            }
            else{
                if($PartidoJugador->obtenerJugador()->obtenerId() != $Partido[Partido::COLUMNA_idJugador1] && $PartidoJugador->obtenerJugador()->obtenerId() != $Partido[Partido::COLUMNA_idJugador2] && $PartidoJugador->obtenerJugador()->obtenerId() != $Partido[Partido::COLUMNA_idJugador3] && $PartidoJugador->obtenerJugador()->obtenerId() != $Partido[Partido::COLUMNA_idJugador4]){
                    $PartidoJugador->eliminar();
                    Log::v(__FUNCTION__, "Eliminado jugador que no existe en el partido");
                }
            }

        }

        if ($Partido->existeReservaPistaPartido()){
            CacheTablaReserva::eliminarCacheRedisIdPistaYFecha($Partido->obtenerReservaPistaPartido()->obtenerIdPista(), $Partido->obtenerReservaPistaPartido()->obtenerFechaReserva());
            CacheTablaReserva::eliminarCacheRedisTablaReservaParaElCampoYFecha($Partido->obtenerReservaPistaPartido()->obtenerCampo()->obtenerId(), $Partido->obtenerReservaPistaPartido()->obtenerFechaReserva());

        }



    }

    static function eliminarJugadoresRepetidos(){
        global $bd;

        $where["AND"][self::COLUMNA_idPartido."[<]"] = 462963    ;
        $where["GROUP"]= self::COLUMNA_idPartido;
        $where["ORDER"]= self::COLUMNA_idPartido." DESC";
        $where["LIMIT"]= "700";


        $array_idsPartidos = $bd->select(self::TABLA_nombre, self::COLUMNA_idPartido, $where);


        foreach ($array_idsPartidos as $idPartido){

            $Partido = new Partido($idPartido);
            $array_idsPartidoJugadores =  $Partido->obtenerIdsPartidoJugadores();
            echo "<br/>ID Partido: $idPartido | Fecha: ".$Partido->obtenerFecha();


            $array_idJugadorPartido = array();
            foreach ($array_idsPartidoJugadores as $idPartidoJugador){
                $PartidoJugador = new PartidoJugador($idPartidoJugador);
                $Jugador = $PartidoJugador->obtenerJugador();

                //echo("<br/>ID Jugador:".$Jugador->obtenerId());

                if (in_array($Jugador->obtenerId(), $array_idJugadorPartido)){
                    echo "<br/>Eliminar Jugador Repetido: ".$Jugador->obtenerId();
                    $PartidoJugador->eliminar();
                    if ($Partido->esReservaPistaPartido()){
                        $Partido->obtenerReservaPistaPartido()->actualizarCacheReserva();
                    }
                }
                $array_idJugadorPartido[] = $Jugador->obtenerId();
            }

            echo "<br/>----------------------";
        }

    }


    function guardar($valor = null, $nombreTablaClonar = "", $actualizarCacheTablaReservas=false)
    {

        $id = parent::guardar($valor, $nombreTablaClonar); // TODO: Change the autogenerated stub

        /*if ($noReordenarJugadoresPartido){
            self::reordenarJugadoresDelPartido($this->obtenerPartido()->obtenerId());
        }*/

        if ($actualizarCacheTablaReservas){
            ReservaPista::eliminarCacheRedisTablaReservaParaElCampoYFecha($this->obtenerReservaPista()->obtenerCampo()->obtenerId(), $this->obtenerReservaPista()->obtenerFechaReserva(), $this->obtenerReservaPista()->obtenerDeporte()->obtenerId());
        }


        return $id;
    }

    function existe(){

        if ($this->obtenerId() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function eliminar($id = null)
    {
        Log::v(__FUNCTION__, "Eliminar Jugador del Partido", false);
        $idJugador = $this->obtenerJugador()->obtenerId();
        $idPartido = $this->obtenerPartido()->obtenerId();
        $ReservaPista = $this->obtenerReservaPista();
        if ($ReservaPista->existe()) {
            Monedero::devolverPagoMonedero($idJugador, $ReservaPista->obtenerId());
        }
        parent::eliminar($id); // TODO: Change the autogenerated stu
        self::reordenarJugadoresDelPartido($idPartido);
        if ($ReservaPista->existe()){
            ReservaPista::eliminarCacheRedisTablaReservaParaElCampoYFecha($ReservaPista->obtenerCampo()->obtenerId(), $ReservaPista->obtenerFechaReserva(), $ReservaPista->obtenerDeporte()->obtenerId());
        }


    }


}