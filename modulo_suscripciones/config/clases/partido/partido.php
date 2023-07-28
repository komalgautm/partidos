<?php

class Partido extends general implements PartidoInterface, InterfazObjeto
{
    const TABLA_NOMBRE = "partidos";
    const COLUMNA_id = "id";
    const COLUMNA_idJugador1 = "id_Jugador1";
    const COLUMNA_idJugador2 = "id_Jugador2";
    const COLUMNA_idJugador3 = "id_Jugador3";
    const COLUMNA_idJugador4 = "id_Jugador4";
    const COLUMNA_fecha = "Fecha";
    const COLUMNA_hora = "Hora";
    const COLUMNA_tipoPuntuacion = "TipoPuntuacion";
    const COLUMNA_idCampo = "id_Campo";
    const COLUMNA_nombreOtroCampo = "Otro_Campo";
    const COLUMNA_idLiga = "id_Liga";
    const COLUMNA_parejaJugador1 = "Puntos_J1";
    const COLUMNA_parejaJugador2 = "Puntos_J2";
    const COLUMNA_parejaJugador3 = "Puntos_J3";
    const COLUMNA_parejaJugador4 = "Puntos_J4";
    const COLUMNA_idJugadorApuntaResultado = "id_Jugador_ApuntaResult";
    const COLUMNA_fechaApuntaResultado = "Fecha_Result";
    const COLUMNA_observaciones = "Observaciones";
    const COLUMNA_nivelMininimo = "nivel_min";
    const COLUMNA_nivelMaximo = "nivel_max";
    const COLUMNA_favoritos = "favoritos";
    const COLUMNA_notasDelClub = "invitacion";
    const COLUMNA_idsJugadoresBuscandoSustituto = "Extra1";
    const COLUMNA_sexo = "Extra2";
    const COLUMNA_zonaGeografica = "Extra3";
    const COLUMNA_tieneCredito = "tienecreditos";


    const RESULTADO_recalcular = "";
    const RESULTADO_empate = "EMPATE";
    const RESULTADO_ganaParejaA = "GANADORES";
    const RESULTADO_ganaParejaB = "PERDEDORES";
    const RESULTADO_eliminarResultados = "ELIMINAR";

    const OP_obtenerIdsPartidosConResultados = "obtenerIdsPartidosConResultados";

    const TIPO_PARTIDO_torneo = 0;
    const TIPO_PARTIDO_amistoso = 1;





    function __construct($id="", $partidoArchivado=false)
    {
        Log::v(__FUNCTION__, "ID Partido: $id | ¿Partido Archivado? $partidoArchivado", false);
        $tablaNombre = self::TABLA_NOMBRE;

        if ($partidoArchivado){
            $tablaNombre = PartidoArchivado::TABLA_NOMBRE;
        }

        if ($id != ""){
            parent::__construct($tablaNombre, self::COLUMNA_id, $id);

            if (!$this->existe() && !$partidoArchivado){
                Log::v(__FUNCTION__, "Buscando ID Partido en PartidosArchivados", false);
                $this->__construct($id, true);
            }
        }
        else{
            parent::__construct($tablaNombre, '', '');
        }



        if ($this->obtenerId() == 0 && $partidoArchivado){
            Log::v(__FUNCTION__, "ID PartidoArchivado 0 -> Eliminar Partido", false);
            $this->guardarEnHistorial();
            $this->eliminar();

        }




    }

    static function obtenerIds(){
        global $bd;

        return $bd->select(self::TABLA_NOMBRE, self::COLUMNA_id);
    }

    function archivarPartido(){
        global $bd;

        Log::v(__FUNCTION__,"Id Partido: ".$this->obtenerId(), true);
        $Partido = new Partido($this->obtenerId());
        $PartidoArchivado = new PartidoArchivado();
        $PartidoArchivado[PartidoArchivado::COLUMNA_id] = $Partido[self::COLUMNA_id];
        $PartidoArchivado[PartidoArchivado::COLUMNA_idJugador1] = $Partido[self::COLUMNA_idJugador1];
        $PartidoArchivado[PartidoArchivado::COLUMNA_idJugador2] = $Partido[self::COLUMNA_idJugador2];
        $PartidoArchivado[PartidoArchivado::COLUMNA_idJugador3] = $Partido[self::COLUMNA_idJugador3];
        $PartidoArchivado[PartidoArchivado::COLUMNA_idJugador4] = $Partido[self::COLUMNA_idJugador4];
        $PartidoArchivado[PartidoArchivado::COLUMNA_fecha] = $Partido[self::COLUMNA_fecha];
        $PartidoArchivado[PartidoArchivado::COLUMNA_hora] = $Partido[self::COLUMNA_hora];
        $PartidoArchivado[PartidoArchivado::COLUMNA_tipoPuntuacion] = $Partido[self::COLUMNA_tipoPuntuacion];
        $PartidoArchivado[PartidoArchivado::COLUMNA_idCampo] = $Partido[self::COLUMNA_idCampo];
        $PartidoArchivado[PartidoArchivado::COLUMNA_nombreOtroCampo] = $Partido[self::COLUMNA_nombreOtroCampo];
        $PartidoArchivado[PartidoArchivado::COLUMNA_idLiga] = $Partido[self::COLUMNA_idLiga];
        $PartidoArchivado[PartidoArchivado::COLUMNA_parejaJugador1] = $Partido[self::COLUMNA_parejaJugador1];
        $PartidoArchivado[PartidoArchivado::COLUMNA_parejaJugador2] = $Partido[self::COLUMNA_parejaJugador2];
        $PartidoArchivado[PartidoArchivado::COLUMNA_parejaJugador3] = $Partido[self::COLUMNA_parejaJugador3];
        $PartidoArchivado[PartidoArchivado::COLUMNA_parejaJugador4] = $Partido[self::COLUMNA_parejaJugador4];
        $PartidoArchivado[PartidoArchivado::COLUMNA_idJugadorApuntaResultado] = $Partido[self::COLUMNA_idJugadorApuntaResultado];
        $PartidoArchivado[PartidoArchivado::COLUMNA_fechaApuntaResultado] = $Partido[self::COLUMNA_fechaApuntaResultado];
        $PartidoArchivado[PartidoArchivado::COLUMNA_observaciones] = $Partido[self::COLUMNA_observaciones];
        $PartidoArchivado[PartidoArchivado::COLUMNA_nivelMininimo] = $Partido[self::COLUMNA_nivelMininimo];
        $PartidoArchivado[PartidoArchivado::COLUMNA_nivelMaximo] = $Partido[self::COLUMNA_nivelMaximo];
        $PartidoArchivado[PartidoArchivado::COLUMNA_favoritos] = $Partido[self::COLUMNA_favoritos];
        $PartidoArchivado[PartidoArchivado::COLUMNA_notasDelClub] = $Partido[self::COLUMNA_notasDelClub];
        $PartidoArchivado[PartidoArchivado::COLUMNA_idsJugadoresBuscandoSustituto] = $Partido[self::COLUMNA_idsJugadoresBuscandoSustituto];
        $PartidoArchivado[PartidoArchivado::COLUMNA_sexo] = $Partido[self::COLUMNA_sexo];
        $PartidoArchivado[PartidoArchivado::COLUMNA_zonaGeografica] = $Partido[self::COLUMNA_zonaGeografica];
        $PartidoArchivado[PartidoArchivado::COLUMNA_tieneCredito] = $Partido[self::COLUMNA_tieneCredito];
        $idPartidoArchivado = $PartidoArchivado->guardar();
        if (!empty($idPartidoArchivado)){
            $bd->delete(self::TABLA_NOMBRE, array(self::COLUMNA_id  => $idPartidoArchivado));
        }
        else{
            $idPartido = $this->obtenerId();
            $descripcion = $this->obtenerNombreRepresentativoDiaHoraClubATexto();
            Email::enviarEmail(Email::EMAIL_ADMINISTRACION, "[$idPartido] - ERROR PARTIDO ARCHIVADO", "El partido ($descripcion) no se ha podido archivar", false, false, false, true);
        }

    }

    static function archivarPartidosAnterioresAntesdeayer(){
        global $bd;
        $array_idsPartidos = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_id, array(self::COLUMNA_fecha."[<]" => Fecha::restarDiasAFecha(date("Y-m-d"), 2)));
        foreach ($array_idsPartidos as $idPartido){
            echo "ID Partido: $idPartido";
            $Partido = new Partido($idPartido);
            $Partido->archivarPartido();
        }
    }

    function importarJugadoresDeTablaPartido($forzarObtenerIdJugadorDesdeReserva=false){
        echo PartidoJugador::importarJugadoresDeTablaPartido($this->obtenerId(), $forzarObtenerIdJugadorDesdeReserva);
    }

    function sincronizarOrdenJugadoresConPartidoJugadores(){
        $idJugador1 = $this[self::COLUMNA_idJugador1];
        $idJugador2 = $this[self::COLUMNA_idJugador2];
        $idJugador3 = $this[self::COLUMNA_idJugador3];
        $idJugador4 = $this[self::COLUMNA_idJugador4];

        if (!empty($idJugador1)){
           $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($this->obtenerId(), $idJugador1);
           if ($PartidoJugador->existe()){
               $PartidoJugador[PartidoJugador::COLUMNA_numeroJugador] = 1;
               $PartidoJugador->guardar();
           }
        }

        if (!empty($idJugador2)){
            $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($this->obtenerId(), $idJugador2);
            if ($PartidoJugador->existe()){
                $PartidoJugador[PartidoJugador::COLUMNA_numeroJugador] = 2;
                $PartidoJugador->guardar();
            }
        }

        if (!empty($idJugador3)){
            $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($this->obtenerId(), $idJugador3);
            if ($PartidoJugador->existe()){
                $PartidoJugador[PartidoJugador::COLUMNA_numeroJugador] = 3;
                $PartidoJugador->guardar();
            }
        }

        if (!empty($idJugador4)){
            $PartidoJugador = PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($this->obtenerId(), $idJugador4);
            if ($PartidoJugador->existe()){
                $PartidoJugador[PartidoJugador::COLUMNA_numeroJugador] = 4;
                $PartidoJugador->guardar();
            }
        }

    }

    function reordenarJugadores(){
       PartidoJugador::reordenarJugadoresDelPartido($this->obtenerId());
    }

    function eliminarResultadoIntroducido(){

        $Partido = new Partido($this->obtenerId());
        $Partido[self::COLUMNA_parejaJugador1] = "0";
        $Partido[self::COLUMNA_parejaJugador2] = "0";
        $Partido[self::COLUMNA_parejaJugador3] = "0";
        $Partido[self::COLUMNA_parejaJugador4] = "0";
        $Partido[self::COLUMNA_idJugadorApuntaResultado] = "";
        $Partido[self::COLUMNA_fechaApuntaResultado] = "";
        $Partido->guardar();
    }

    function eliminarTodosLosJugadoresDelPartido(){
        PartidoJugador::eliminarTodosLosJugadoresDelPartido($this->obtenerId());
    }


    static function obtenerIdsPartidosPorLiga($idLiga){
        global $bd;

        return $bd->select(self::TABLA_NOMBRE, self::COLUMNA_id, array(self::COLUMNA_idLiga => $idLiga));
    }

    static function obtenerIdsPartidosConResultados($idLiga){
        global $bd;
        return Resultado::obtenerIdsPartidosLigaConResultados($idLiga);
    }

    static function obtenerIdsPartidosElDia($fechaMYSQL){
        global $bd;

        $where["AND"][self::COLUMNA_fecha] = $fechaMYSQL;
        return $bd->select(self::TABLA_NOMBRE, self::COLUMNA_id, $where);
    }

    function esConResultados(){
        return Resultado::esPartidoConResultados($this->obtenerId());
    }

    function actualizarPuntos($resultado=''){
        echo Resultado::actualizarPuntosPartido($this->obtenerId(), $resultado);
    }

    function obtenerObservaciones(){
        return $this[self::COLUMNA_observaciones];
    }

    function obtenerNotasDelClub(){
        return $this[self::COLUMNA_notasDelClub];
    }

    static function notificarAAdministracionPartidosSinJugadores(){
        global $bd;

        $ids = $bd->select(
                    self::TABLA_NOMBRE,
                    self::COLUMNA_id,
                    array("AND" =>
                        array(
                            self::COLUMNA_idJugador1 => 0,
                            self::COLUMNA_idJugador2 => 0,
                            self::COLUMNA_idJugador3 => 0,
                            self::COLUMNA_idJugador4 => 0,
                            self::COLUMNA_fecha."[>=]" => date("Y-m-d")
                        ),
                        "ORDER", array(self::COLUMNA_fecha => "ASC")
                )
            );


        $mensajeEmail = "";
        $numeroDePartidosEncontrados = 0;
        foreach ($ids as $id) {
            $Partido = new Partido($id);
            //echo "Entra";

            if ($Partido->esReservaPistaPartido()){
                $ReservaPista = $Partido->obtenerReservaPistaPartido();
                if ($ReservaPista->obtenerTipoJugador1() == $ReservaPista::TIPOJUGADOR_EXTERNO){
                    continue;
                }
            }


            $Liga = $Partido->obtenerLiga();
            $idLiga = $Liga->obtenerId();
            $nombreLiga = $Liga->obtenerNombre();
            $fecha = $Partido->obtenerFecha();
            $hora = $Partido->obtenerHora();
            $fechaCompleta = $fecha." ".$hora;
            $urlEditarPartidoPCU = $Partido->obtenerURLEditarPartidoPCU();

            $mensajeEmail.= "<br/><br/><a href='$urlEditarPartidoPCU'>[$id] Partido sin jugadores para el día $fechaCompleta en la Liga [$idLiga] $nombreLiga</a>";
            $numeroDePartidosEncontrados++;
        }

        if ($mensajeEmail != ""){

            $asunto = "Se han encontrado $numeroDePartidosEncontrados Partidos sin Jugadores";
            $mensaje = $asunto."<hr/><br/>$mensajeEmail";


            Email::enviarEmail(Email::EMAIL_ADMINISTRACION, $asunto, $mensaje);
        }


    }

    function obtenerTieneCreditos(){
        $tieneCredito = $this[self::COLUMNA_tieneCredito];

        if ($tieneCredito == 1){
            return true;
        }

        return false;
    }

    function obtenerJugadorOrganizador(){

        $idJugadorOrganizador = $this["id_Jugador1"];

        $Jugador = new Jugador($idJugadorOrganizador);
        return $Jugador;
    }

    function obtenerId(){
        return $this["id"];
    }

    function obtenerURLEditarPartidoPCU(){
        $idLiga = $this->obtenerIdLiga();
        return WWWBASE."PCU/partidos.php?menu=modificar&id=".$this->obtenerId()."&IDLIGA=$idLiga";
    }

    function esEliminado()
    {
        if ($this->obtenerId() > 0){
            return false;
        }
        return true;
    }

    function obtenerIdLiga(){
        return $this["id_Liga"];
    }

    function obtenerLiga(){
        return new Liga($this->obtenerIdLiga());
    }

    function obtenerFecha(){
        return $this[self::COLUMNA_fecha];
    }

    function obtenerHora(){
        return $this[self::COLUMNA_hora];
    }

    function obtenerFechaMYSQLYHoraPartido(){
        return $this->obtenerFecha()." ".$this->obtenerHora();
    }

    function obtenerJugador1(){
        return $this->obtenerPartidoJugadorDelPartidoPorNumeroJugador(1)->obtenerJugador();
    }

    function obtenerJugador2(){
        return $this->obtenerPartidoJugadorDelPartidoPorNumeroJugador(2)->obtenerJugador();
    }

    function obtenerJugador3(){
        return $this->obtenerPartidoJugadorDelPartidoPorNumeroJugador(3)->obtenerJugador();
    }

    function obtenerJugador4(){
        return $this->obtenerPartidoJugadorDelPartidoPorNumeroJugador(4)->obtenerJugador();
    }

    function obtenerParejaJugadorPorNumeroJugador($numeroJugador){
        switch ($numeroJugador){

            case 1:
                return $this->obtenerParejaJugador1();

            case 2:
                return $this->obtenerParejaJugador2();

            case 3:
                return $this->obtenerParejaJugador3();

            case 4:
                return $this->obtenerParejaJugador4();
        }
    }

    function obtenerParejaJugador1(){
        return $this[self::COLUMNA_parejaJugador1];
    }

    function obtenerParejaJugador2(){
        return $this[self::COLUMNA_parejaJugador2];
    }

    function obtenerParejaJugador3(){
        return $this[self::COLUMNA_parejaJugador3];
    }

    function obtenerParejaJugador4(){
        return $this[self::COLUMNA_parejaJugador4];
    }

    function obtenerPuntosJugador($idJugador){
        return Resultado::obtenerPuntosJugadorPartido($idJugador, $this->obtenerId());
    }

    function obtenerRankingJugador($idJugador){
        return Resultado::obtenerRankingJugadorPartido($idJugador, $this->obtenerId());
    }

    function obtenerJugadoresInscritos($idJugadorNoIncluir=""){

        $array_idsJugadoresInscritos = array();

        $idJugador1 = $this->obtenerJugador1()->obtenerId();
        $idJugador2 = $this->obtenerJugador2()->obtenerId();
        $idJugador3 = $this->obtenerJugador3()->obtenerId();
        $idJugador4 = $this->obtenerJugador4()->obtenerId();

        if ($idJugador1 > 0 && $idJugador1 != $idJugadorNoIncluir){
            $array_idsJugadoresInscritos[] = $idJugador1;
        }

        if ($idJugador2 > 0 && $idJugador2 != $idJugadorNoIncluir){
            $array_idsJugadoresInscritos[] = $idJugador2;
        }

        if ($idJugador3 > 0 && $idJugador3 != $idJugadorNoIncluir){
            $array_idsJugadoresInscritos[] = $idJugador3;
        }

        if ($idJugador4 > 0 && $idJugador4 != $idJugadorNoIncluir){
            $array_idsJugadoresInscritos[] = $idJugador4;
        }

        $array = [];
        foreach ($array_idsJugadoresInscritos as $id) {
            $array[] = new Jugador($id);
        }
        return $array;

    }

    function enviarNotificacionJugadorApuntadoAPartidoJugadoresInscritos($idJugadorInscrito){
        $Jugador = new Jugador($idJugadorInscrito);
        $nombreJugador = $Jugador->obtenerNombre(true);

        $array_JugadoresInscritos = $this->obtenerJugadoresInscritos($idJugadorInscrito);

        foreach ($array_JugadoresInscritos as $JugadoresInscrito){

            $asunto = Traductor::traducir("Sobre tu Partido", false, $JugadoresInscrito->obtenerIdioma())." ".$this->obtenerNombreRepresentativoDiaHoraClubATexto(true, false, false, $JugadoresInscrito->obtenerIdioma());
            $mensaje = $nombreJugador." ".Traductor::traducir("se ha apuntado a tu partido", false, $JugadoresInscrito->obtenerIdioma())." ".$this->obtenerNombreRepresentativoDiaHoraClubATexto(true, true, true, $JugadoresInscrito->obtenerIdioma());
            Notificacion::enviarNotificacion($JugadoresInscrito->obtenerId(), $asunto, $mensaje, Notificacion::TIPO_NOTIFICACION_verPartidosAgenda, $this->obtenerIdLiga(),"","");
        }
    }


    function enviarNotificacionJugadorSeHaQuitadoAPartidoJugadoresInscritos($idJugadorInscrito){
        $Jugador = new Jugador($idJugadorInscrito);
        $nombreJugador = $Jugador->obtenerNombre(true);

        $array_JugadoresInscritos = $this->obtenerJugadoresInscritos($idJugadorInscrito);

        foreach ($array_JugadoresInscritos as $JugadoresInscrito){

            $asunto = Traductor::traducir("Sobre tu Partido", false, $JugadoresInscrito->obtenerIdioma())." ".$this->obtenerNombreRepresentativoDiaHoraClubATexto(true, false, false, $JugadoresInscrito->obtenerIdioma());
            $mensaje = $nombreJugador." ".Traductor::traducir("se ha quitado de tu partido", false, $JugadoresInscrito->obtenerIdioma())." ".$this->obtenerNombreRepresentativoDiaHoraClubATexto(true, true, true, $JugadoresInscrito->obtenerIdioma());
            Notificacion::enviarNotificacion($JugadoresInscrito->obtenerId(), $asunto, $mensaje, Notificacion::TIPO_NOTIFICACION_verPartidosAgenda, $this->obtenerIdLiga(),"",Notificacion::ICONO_rojo);
        }
    }

    function enviarNotificacionAJugadoresInscritos($asunto, $mensaje, $idJugadorNoIncluir="", $iconoNotificacion=""){

        $array_JugadoresInscritos = $this->obtenerJugadoresInscritos($idJugadorNoIncluir);

        foreach ($array_JugadoresInscritos as $JugadoresInscrito){
            Notificacion::enviarNotificacion($JugadoresInscrito->obtenerId(), $asunto, $mensaje, Notificacion::TIPO_NOTIFICACION_verPartidosAgenda, $this->obtenerIdLiga(),"",$iconoNotificacion);
        }

    }

    function obtenerFechaHoraYLugarATexto(){

        $textoFecha = Fecha::fechaMYSQLATexto($this->obtenerFecha());
        $hora = $this->obtenerHora();
        $lugar = $this->obtenerCampo()->obtenerNombre();

        return $textoFecha." ".Traductor::traducir("a las")." ".$hora." ".Traductor::traducir("en")." ".$lugar;
    }

    function obtenerNombreRepresentativoDiaHoraClubATexto($motrarPreposicion=true, $mostrarDia=true, $mostrarCampo=true, $idiomaEspecifico=""){

        $fecha = $this->obtenerFecha();
        $textoPreposicion = "";
        $textoDia = "";
        $textoHora = "";
        $textoCampo = "";
        $textoDiaHoraClub = "";

        $hoy=date('Y-m-d');
        $manana=date('Y-m-d', time()+86400);


        $date = new DateTime($fecha);
        $dia_p=$date->format('d');

        if($fecha==$hoy){
            $textoPreposicion = Traductor::traducir("de", false, $idiomaEspecifico)." ";
            $textoDia = Traductor::traducir("hoy", false, $idiomaEspecifico);
        }
        elseif($fecha==$manana){
            $textoPreposicion = Traductor::traducir("de", false, $idiomaEspecifico)." ";
            $textoDia = Traductor::traducir("mañana", false, $idiomaEspecifico);
        }
        else{

            if ($motrarPreposicion){
                $textoDia = Traductor::traducir("del", false, $idiomaEspecifico)." ";
            }

            if ($mostrarDia){
                $textoDia .= obtenerDiaATexto($fecha)." $dia_p";
            }
            else{
                $textoDia .= obtenerDiaATexto($fecha);
            }
        }

        $textoHora = " ".Traductor::traducir("a las", false, $idiomaEspecifico)." ".$this->obtenerHora()."h";


        $Campo = $this->obtenerCampo();
        $textoCampo = Traductor::traducir("en", false, $idiomaEspecifico)." ".$Campo->obtenerNombre();



        if ($motrarPreposicion){
            $textoDiaHoraClub.= $textoPreposicion;
        }

        $textoDiaHoraClub.= $textoDia.$textoHora;

        if ($mostrarCampo){
            $textoDiaHoraClub.= " ".$textoCampo;
        }

        return $textoDiaHoraClub;
    }

    function obtenerNivelMinimo(){
        return $this[self::COLUMNA_nivelMininimo];
    }

    function obtenerNivelMaximo(){
        return $this[self::COLUMNA_nivelMaximo];
    }

    function esJugadorApuntadoAPartido($idJugador){

        return $this->existeJugadorApuntadoEnElPartido($idJugador);
        /*

        switch ($idJugador){

            case $this->obtenerJugador1()->obtenerId():
                return true;

            case $this->obtenerJugador2()->obtenerId():
                return true;

            case $this->obtenerJugador3()->obtenerId():
                return true;

            case $this->obtenerJugador4()->obtenerId():
                return true;
        }*/
    }

    function obtenerDeporte(){

        if ($this->esReservaPistaPartido()){
            return $this->obtenerReservaPistaPartido()->obtenerPista()->obtenerDeporte();
        }

        return new Deporte(Deporte::ID_padel);
    }

    function obtenerIdCampo(){
        return $this["id_Campo"];
    }

    function obtenerCampo(){
        return new Campo($this->obtenerIdCampo());
    }

    function obtenerConfiguracionReservaPistas(){
        return $this->obtenerCampo()->obtenerConfiguracionReservaPistas();
    }


    /**
     * @author JMAM
     *
     * Obtiene si se ha introduciodo el resultado del partido
     *
     * @return bool
     * true ->  SI existen resultados para este partido
     * false -> NO existen resultados para este partido
     */
    function sehaIntroducidoResultado(){

        global $bd;

        $numeroResultados = $bd->count("resultados", array("id_partido" => $this["id"]));

        if ($numeroResultados > 0){

            return true;
        }
        else{
            return false;
        }
    }


    function obtenerEnlaceCompartirPartido(){

        $idLiga = $this->obtenerIdLiga();
        $fecha = $this->obtenerFecha();
        $parametrosCodificados = base64_encode("0,$idLiga,$fecha");
        return WWWBASE_RAIZ."app?cd=$parametrosCodificados";
    }


    function esReservaPistaPartido($inclusoTipoSinReserva = true){


        $ReservaPista = $this->obtenerReservaPistaPartido();

        $idReservaPista = $ReservaPista->obtenerId();
        Log::v(__FUNCTION__, "ID Reserva: $idReservaPista", false);

        if ($ReservaPista->obtenerId() > 0){

            if (!$inclusoTipoSinReserva && $ReservaPista->obtenerIdTipoReserva() == TipoReserva::ID_TIPORESERVA_SIN_RESERVA){
                Log::v(__FUNCTION__, "No es Reserva porque es de Tipo Sin Reserva", true);
                return false;
            }

            return true;
        }
        else{
            return false;
        }
    }

    function existeReservaPistaPartido(){
        return $this->obtenerReservaPistaPartido()->existe();
    }

    function obtenerReservaPistaPartido(){
        return ReservaPista::obtenerReservaPistaPartido($this->obtenerId());
    }

    function esJugadorBuscandoSustituto($idJugador){

        $array_idsJugadoresBuscandoSustituto = explode(",",$this[self::COLUMNA_idsJugadoresBuscandoSustituto]);

        foreach ($array_idsJugadoresBuscandoSustituto as $idJugadorBuscandoSustituto){

            if ($idJugadorBuscandoSustituto > 0){
                if($idJugadorBuscandoSustituto == $idJugador){
                    return true;
                }
            }
        }

        return false;
    }

    function obtenerIdsPartidoJugadores(){
        return PartidoJugador::obtenerIdsPartidoJugadoresDelPartido($this->obtenerId());
    }

    function obtenerIdsJugadores(){
        return PartidoJugador::obtenerIdsJugadoresPartido($this->obtenerId());
    }

    function obtenerNumeroJugadores($desdeLaTablaPartidos=false){
        if ($desdeLaTablaPartidos){
            $numeroJugadores = 0;
            if ($this[self::COLUMNA_idJugador1] > 0){
                $numeroJugadores++;
            }

            if ($this[self::COLUMNA_idJugador2] > 0){
                $numeroJugadores++;
            }

            if ($this[self::COLUMNA_idJugador3] > 0){
                $numeroJugadores++;
            }

            if ($this[self::COLUMNA_idJugador4] > 0){
                $numeroJugadores++;
            }
        }
        else{
            $numeroJugadores = PartidoJugador::obtenerNumeroJugadores($this->obtenerId());
        }
        Log::v(__FUNCTION__,"Número Jugadores: $numeroJugadores", false);
        return $numeroJugadores;
    }

    function obtenerJugadorPorNumero($numeroJugador){
        return PartidoJugador::obtenerJugadorDelPartido($this->obtenerId(), $numeroJugador);
    }

    function obtenerPartidoJugadorPorNumero($numeroJugador){
        return PartidoJugador::obtenerPartidoJugadorDelPartido($this->obtenerId(), $numeroJugador);
    }

    function obtenerResultadoJugadorPorNumeroJugador($numeroJugador)
    {
        switch ($numeroJugador){
            case 1:
                return $this->obtenerResultadoJugador1();

            case 2:
                return $this->obtenerResultadoJugador2();

            case 3:
                return $this->obtenerResultadoJugador3();

            case 4:
                return $this->obtenerResultadoJugador4();
            }
    }

    function obtenerResultadoJugador1(){
        return Resultado::obtenerResultado($this->obtenerJugador1()->obtenerId(), $this->obtenerId());
    }

    function obtenerResultadoJugador2(){
        return Resultado::obtenerResultado($this->obtenerJugador2()->obtenerId(), $this->obtenerId());
    }

    function obtenerResultadoJugador3(){
        return Resultado::obtenerResultado($this->obtenerJugador3()->obtenerId(), $this->obtenerId());
    }

    function obtenerResultadoJugador4(){
        return Resultado::obtenerResultado($this->obtenerJugador4()->obtenerId(), $this->obtenerId());
    }

    function obtenerJugadorApuntaResultado(){
        return new Jugador($this[self::COLUMNA_idJugadorApuntaResultado]);
    }

    function obtenerFechaResultado(){
        return $this[self::COLUMNA_fechaApuntaResultado];
    }


    function esPartidoPreReservaDePista(){
        $Campo = $this->obtenerCampo();

        if ($Campo->activadoModuloReserva() && $Campo->esPermitidoRealizarReservasPorJugadores()){

            if ($this->existeReservaPistaPartido() == false){
                return true;
            }

        }

        return false;

    }

    function actualizarPartidosApuntadoParaLosJugadoresDelPartido(){
        Juegalaliga::actualizarNumeroPartidosApuntadoJugadorEnLiga($this->obtenerJugador1()->obtenerId(), $this->obtenerIdLiga());
        Juegalaliga::actualizarNumeroPartidosApuntadoJugadorEnLiga($this->obtenerJugador2()->obtenerId(), $this->obtenerIdLiga());
        Juegalaliga::actualizarNumeroPartidosApuntadoJugadorEnLiga($this->obtenerJugador3()->obtenerId(), $this->obtenerIdLiga());
        Juegalaliga::actualizarNumeroPartidosApuntadoJugadorEnLiga($this->obtenerJugador4()->obtenerId(), $this->obtenerIdLiga());
    }


    function actualizarPartidosJugadosParaLosJugadoresDelPartido(){
        Juegalaliga::actualizarNumeroPartidosJugadosJugadorEnLiga($this->obtenerJugador1()->obtenerId(), $this->obtenerIdLiga());
        Juegalaliga::actualizarNumeroPartidosJugadosJugadorEnLiga($this->obtenerJugador2()->obtenerId(), $this->obtenerIdLiga());
        Juegalaliga::actualizarNumeroPartidosJugadosJugadorEnLiga($this->obtenerJugador3()->obtenerId(), $this->obtenerIdLiga());
        Juegalaliga::actualizarNumeroPartidosJugadosJugadorEnLiga($this->obtenerJugador4()->obtenerId(), $this->obtenerIdLiga());
    }


    function puedeJugadorOrganizarRealizarReservaPistaYa(){
        $Jugador = $this->obtenerJugadorOrganizador();
        return $Jugador->puedeRealizarReservaPorDiasAntelacion($this->obtenerIdCampo(), $this->obtenerIdLiga(), $this->obtenerFecha());
    }

    function obtenerFechaMYSQLQueSePuedeReservarPista(){

        if ($this->esPartidoPreReservaDePista()){
            $Jugador = $this->obtenerJugadorOrganizador();
            return $Jugador->obtenerFechaQuePuedeRealizarReservaPista($this->obtenerIdCampo(), $this->obtenerIdLiga(), $this->obtenerFecha());
        }

        return "S/N";
    }

    function guardarEnHistorial(){
        $PartidoHistorial = new PartidoHistorial();
        $PartidoHistorial[PartidoHistorial::COLUMNA_idPartido] = $this[self::COLUMNA_id];
        $PartidoHistorial[PartidoHistorial::COLUMNA_idLiga] = $this[self::COLUMNA_idLiga];
        $PartidoHistorial[PartidoHistorial::COLUMNA_fecha] = $this[self::COLUMNA_fecha];
        $PartidoHistorial[PartidoHistorial::COLUMNA_hora] = $this[self::COLUMNA_hora];
        $PartidoHistorial[PartidoHistorial::COLUMNA_tipoPuntuacion] = $this[self::COLUMNA_tipoPuntuacion];
        $PartidoHistorial[PartidoHistorial::COLUMNA_idCampo] = $this[self::COLUMNA_idCampo];
        $PartidoHistorial[PartidoHistorial::COLUMNA_nombreOtroCampo] = $this[self::COLUMNA_nombreOtroCampo];
        $PartidoHistorial[PartidoHistorial::COLUMNA_idJugador1] = $this[self::COLUMNA_idJugador1];
        $PartidoHistorial[PartidoHistorial::COLUMNA_idJugador2] = $this[self::COLUMNA_idJugador2];
        $PartidoHistorial[PartidoHistorial::COLUMNA_idJugador3] = $this[self::COLUMNA_idJugador3];
        $PartidoHistorial[PartidoHistorial::COLUMNA_idJugador4] = $this[self::COLUMNA_idJugador4];
        $PartidoHistorial[PartidoHistorial::COLUMNA_parejaJugador1] = $this[self::COLUMNA_parejaJugador1];
        $PartidoHistorial[PartidoHistorial::COLUMNA_parejaJugador2] = $this[self::COLUMNA_parejaJugador2];
        $PartidoHistorial[PartidoHistorial::COLUMNA_parejaJugador3] = $this[self::COLUMNA_parejaJugador3];
        $PartidoHistorial[PartidoHistorial::COLUMNA_parejaJugador4] = $this[self::COLUMNA_parejaJugador4];
        $PartidoHistorial[PartidoHistorial::COLUMNA_idJugadorApuntaResultado] = $this[self::COLUMNA_idJugadorApuntaResultado];
        $PartidoHistorial[PartidoHistorial::COLUMNA_fechaApuntaResultado] = $this[self::COLUMNA_fechaApuntaResultado];
        $PartidoHistorial[PartidoHistorial::COLUMNA_observaciones] = $this[self::COLUMNA_observaciones];
        $PartidoHistorial[PartidoHistorial::COLUMNA_nivelMininimo] = $this[self::COLUMNA_nivelMininimo];
        $PartidoHistorial[PartidoHistorial::COLUMNA_nivelMaximo] = $this[self::COLUMNA_nivelMaximo];
        $PartidoHistorial[PartidoHistorial::COLUMNA_favoritos] = $this[self::COLUMNA_favoritos];
        $PartidoHistorial[PartidoHistorial::COLUMNA_notasDelClub] = $this[self::COLUMNA_notasDelClub];
        $PartidoHistorial[PartidoHistorial::COLUMNA_idsJugadoresBuscandoSustituto] = $this[self::COLUMNA_idsJugadoresBuscandoSustituto];
        $PartidoHistorial[PartidoHistorial::COLUMNA_sexo] = $this[self::COLUMNA_sexo];
        $PartidoHistorial[PartidoHistorial::COLUMNA_zonaGeografica] = $this[self::COLUMNA_zonaGeografica];
        $PartidoHistorial[PartidoHistorial::COLUMNA_tieneCredito] = $this[self::COLUMNA_tieneCredito];
        $PartidoHistorial[PartidoHistorial::COLUMNA_valoresSesionGuardadoHistorial] = json_encode($_SESSION);
        $PartidoHistorial->guardar();
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


    function actualizarCacheClasificacion(){
        CacheClasificacion::actualizarCacheClasificacionPorPartido($this->obtenerId());
    }

    function obtenerUrlEnlaceCompartirPorWhatsApp(){

        $textoNivel = "";
        if (!empty($this->obtenerNivelMaximo()) || !empty($this->obtenerNivelMinimo())){
            $textoNivel = Traductor::traducir("Nivel")." ".$this->obtenerNivelMaximo()." ".Traductor::traducir("al")." ".$this->obtenerNivelMinimo();
        }

        $texto = "*".Traductor::traducir("Apúntate al partido")."*%0A".ucfirst($this->obtenerNombreRepresentativoDiaHoraClubATexto(false, true, true)).". $textoNivel %0A%0A";


        $array_idsJugadores = $this->obtenerIdsJugadores();
        foreach ($array_idsJugadores as $idJugador){
            $Jugador = new Jugador($idJugador);
            $nombreJugador = $Jugador->obtenerNombre(true);
            $texto .= "🥎 $nombreJugador%0A";
        }

        for ($i = $this->obtenerNumeroJugadores(); $i < 4; $i++){
           $texto .= "⚾ ...?%0A";
        }

        $enlacePartido = $this->obtenerEnlaceCompartirPartido();

        return "https://wa.me/?text=$texto%0A👉 $enlacePartido";
    }


    static function obtenerIdsPartidosJugadorOrganizador($idJugadorOrganizador, $idLiga="", $desdeIncluidaFechaMYSQL=""){
        global $bd;

        $where["AND"][self::COLUMNA_idJugador1] = $idJugadorOrganizador;

        if (!empty($idLiga)){
            $where["AND"][self::COLUMNA_idLiga] = $idLiga;
        }

        if (!empty($desdeIncluidaFechaMYSQL)){
            $where["AND"][self::COLUMNA_fecha."[>=]"] = $desdeIncluidaFechaMYSQL;
        }

        return $bd->select(self::TABLA_NOMBRE, self::COLUMNA_id, $where);
    }


    function obtenerEnlaceEditarPartido(){
        $idJugadorOrganizador = $this->obtenerJugadorOrganizador()->obtenerId();
        $idLiga = $this->obtenerIdLiga();
        $idPartido = $this->obtenerId();


        $parametrosCodificados = base64_encode("7,$idJugadorOrganizador,$idLiga,$idPartido");
        return WWWBASE_RAIZ."code?cd=$parametrosCodificados";
    }

    function obtenerTiempoFaltaParaInicioPartidoEnMinutos(){


        $fechaActual = date("Y-m-d H:i:s");
        $fechaYHoraPartido = $this->obtenerFechaMYSQLYHoraPartido();

        $minutos = (strtotime($fechaYHoraPartido)-strtotime($fechaActual))/60;
        $minutos = floor($minutos);

        Log::v(__FUNCTION__, $minutos, true);

        return $minutos;
    }

    function esPartidoAmistoso(){
        return PartidoAmistoso::esPartidoAmistoso($this->obtenerId());
    }

    function obtenerTipoPartido(){
        if ($this->esPartidoAmistoso()){
            return Partido::TIPO_PARTIDO_amistoso;
        }
        else{
            return Partido::TIPO_PARTIDO_torneo;
        }
    }

    function existe(){

        if ($this->obtenerId() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    static function obtenerJugadoresApuntadosAIdCampo($idCampo){
        global $bd;

        $array_idsJugador1 = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_idJugador1, array("AND" => array(self::COLUMNA_idJugador1."[!]" => 0, self::COLUMNA_idCampo => $idCampo)));
        $array_idsJugador2 = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_idJugador2, array("AND" => array(self::COLUMNA_idJugador2."[!]" => 0, self::COLUMNA_idCampo => $idCampo)));
        $array_idsJugador3 = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_idJugador3, array("AND" => array(self::COLUMNA_idJugador3."[!]" => 0, self::COLUMNA_idCampo => $idCampo)));
        $array_idsJugador4 = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_idJugador4, array("AND" => array(self::COLUMNA_idJugador4."[!]" => 0, self::COLUMNA_idCampo => $idCampo)));

        $array_idsJugadores = array_merge($array_idsJugador1, $array_idsJugador2, $array_idsJugador3, $array_idsJugador4);
        return array_unique($array_idsJugadores);
    }

    static function obtenerIdsLigasConJugadoresApuntadosAIdCampo($idCampo){

        $array_idsLigaJugador1 = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_idLiga, array("AND" => array(self::COLUMNA_idJugador1."[!]" => 0, self::COLUMNA_idCampo => $idCampo)));
        $array_idsLigaJugador2 = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_idLiga, array("AND" => array(self::COLUMNA_idJugador2."[!]" => 0, self::COLUMNA_idCampo => $idCampo)));
        $array_idsLigaJugador3 = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_idLiga, array("AND" => array(self::COLUMNA_idJugador3."[!]" => 0, self::COLUMNA_idCampo => $idCampo)));
        $array_idsLigaJugador4 = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_idLiga, array("AND" => array(self::COLUMNA_idJugador4."[!]" => 0, self::COLUMNA_idCampo => $idCampo)));

        $array_idsLigas = array_merge($array_idsLigaJugador1, $array_idsLigaJugador2, $array_idsLigaJugador3, $array_idsLigaJugador4);
        return array_unique($array_idsLigas);
    }

    static function cronNotificarPartidosPrereservaQueYaSePuedenReservarHoy(){
        require_once BASE."funciones.php";
        $array_idsPartidosPrereservaSePuedenReservar = self::obtenerIdsPartidosPreservaQueSePuedenReservarEnFecha(date("Y-m-d"));
        //$array_idsPartidosPrereservaSePuedenReservar = self::obtenerIdsPartidosPreservaQueSePuedenReservarEnFecha("2021-10-30");
        foreach ($array_idsPartidosPrereservaSePuedenReservar as $idPartido){
            $Partido = new Partido($idPartido);
            $JugadorOrganizador = $Partido->obtenerJugadorOrganizador();
            $emailJugadorOrganizador = $JugadorOrganizador->obtenerEmail();
            $nombreRepresentativo = $Partido->obtenerNombreRepresentativoDiaHoraClubATexto();
            $enlaceEditarPartido = $Partido->obtenerEnlaceEditarPartido();

            $asunto = Traductor::traducir("Reserva Pista", false, $JugadorOrganizador->obtenerIdioma());
            $mensaje = Traductor::traducir("Ya puedes realizar la Reserva de Pista para el Partido", false, $JugadorOrganizador->obtenerIdioma())." ".$nombreRepresentativo;
            Notificacion::enviarNotificacion($JugadorOrganizador->obtenerId(), $asunto, $mensaje, Notificacion::TIPO_NOTIFICACION_editarPartido, $Partido->obtenerIdLiga(), $Partido->obtenerId());

            //JMAM: Añade el botón al Email para poder editar el partido y poder hacer la reserva
            $mensaje .= "<br/><br><div style='text-align: center'><a href='$enlaceEditarPartido' style='border-radius:50px;background: white;border: 1px solid #1469CD;color: #1469CD;font-size: 12px;font-weight: 600; text-decoration: none; padding: 15px; padding-top: 5px;padding-bottom: 5px;'>".Traductor::traducir("¡Reservar Pista Ahora!", false, $JugadorOrganizador->obtenerIdioma())."</a></div>";
            Email::enviarEmail($emailJugadorOrganizador, $asunto, $mensaje, true, "", "", true, true);

            echo "<br/>Partido Prereserva Notificado para Reservar: $idPartido - $nombreRepresentativo -> $emailJugadorOrganizador";
        }
    }

    static function obtenerIdsPartidosPreservaQueSePuedenReservarEnFecha($fechaMYSQL=""){
        global $bd;
        $array_idsPartidosSePuedenReservarEnFecha = array();


        $array_idsPartidos = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_id, array(self::COLUMNA_fecha."[>=]" => $fechaMYSQL));
        foreach ($array_idsPartidos as $idPartido){
            $Partido = new Partido($idPartido);
            if ($Partido->esPartidoPreReservaDePista() && $Partido->obtenerFechaMYSQLQueSePuedeReservarPista() == $fechaMYSQL){
                $array_idsPartidosSePuedenReservarEnFecha[] = $Partido->obtenerId();
            }
        }

        return $array_idsPartidosSePuedenReservarEnFecha;
    }


    /*
    static function obtenerNumeroPartidosApuntadoJugador($idJugador, $idLiga){
        return count(self::obtenerPartidosApuntadoJugador($idJugador, $idLiga));
    }
    */
    static function obtenerPartidosApuntadoJugador($idJugador, $idLiga){
        global $bd;

        $ids =  $bd->select(
            "partidosArchivadosYActuales",
            self::COLUMNA_id,
            array(
                "AND" => array(
                    "OR" => array(
                        self::COLUMNA_idJugador1 => $idJugador,
                        self::COLUMNA_idJugador2 => $idJugador,
                        self::COLUMNA_idJugador3 => $idJugador,
                        self::COLUMNA_idJugador4 => $idJugador,
                    ),
                    self::COLUMNA_idLiga => $idLiga
                )

            )
        );

        $array_Partidos = array();
        foreach ($ids as $id) {
            $array_Partidos[] = new Partido($id);
        }

        return $array_Partidos;
    }

    static function obtenerNumeroPartidosJugadorJugador($idJugador, $idLiga)
    {

        return count(Partido::obtenerPartidosJugadosJugador($idJugador, $idLiga));
    }


    static function obtenerPartidosJugadosJugador($idJugador, $idLiga){

        global $bd;

        $ids =  $bd->select(
                    self::TABLA_NOMBRE,
                    self::COLUMNA_id,
                    array(
                        "AND" => array(
                            "OR" => array(
                                self::COLUMNA_idJugador1 => $idJugador,
                                self::COLUMNA_idJugador2 => $idJugador,
                                self::COLUMNA_idJugador3 => $idJugador,
                                self::COLUMNA_idJugador4 => $idJugador,
                            ),
                            self::COLUMNA_idJugadorApuntaResultado."[>]" => 0,
                            self::COLUMNA_idLiga => $idLiga
                        )

                    )
                );

        $array_Partidos = array();
        foreach ($ids as $id) {
            $array_Partidos[] = new Partido($id);
        }

        return $array_Partidos;
    }

    function obtenerNumeroJugador($idJugador){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($this->obtenerId(), $idJugador)->obtenerNumeroJugador();
    }


    function existeJugadorApuntadoEnElPartido($idJugador){
        return PartidoJugador::existeJugadorApuntandoEnElPartido($this->obtenerId(), $idJugador);
    }

    function obtenerPartidoJugadorDelPartidoPorIdJugador($idJugador){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorIdJugador($this->obtenerId(), $idJugador);
    }

    function obtenerPartidoJugadorDelPartidoPorNumeroJugador($numeroJugador){
        return PartidoJugador::obtenerPartidoJugadorDelPartidoPorNumeroJugador($this->obtenerId(), $numeroJugador);
    }

    function actualizarTipoPartido($idTipoPartido){

        switch ($idTipoPartido){

            case Partido::TIPO_PARTIDO_torneo:
                PartidoAmistoso::eliminarIdPartidoDePartidosAmistosos($this->obtenerId());
                break;

            case Partido::TIPO_PARTIDO_amistoso:
                PartidoAmistoso::anadirIdPartidoEnAmistoso($this->obtenerId());
                break;
        }
    }



    function asociarPartidoJugadoresDelPartidoALaIdReservaPista($idReservaPista){
        $array_idsPartidoJugadores = $this->obtenerIdsPartidoJugadores();
        foreach ($array_idsPartidoJugadores as $idPartidoJugador){
            $PartidoJugador = new PartidoJugador($idPartidoJugador);
            $PartidoJugador[PartidoJugador::COLUMNA_idReservaPista] = $idReservaPista;
            $PartidoJugador->guardar();
        }
    }

    function esPartidoCompletoPorJugadores(){
        $numeroJugadoresApuntados = $this->obtenerNumeroJugadores();
        $numeroJugadoresMaximoDeporte = $this->obtenerDeporte()->obtenerNumeroMaximoJugadores();
        if ($numeroJugadoresApuntados >= $numeroJugadoresMaximoDeporte){
            Log::v(__FUNCTION__, "Número Jugadores Apuntados: $numeroJugadoresApuntados | Número Jugadores Máximo Deporte: $numeroJugadoresMaximoDeporte", true);
            return true;
        }

        if ($this->esReservaPistaPartido()){
            $numeroJugadoresMaximoPermitidosReservaPista = $this->obtenerReservaPistaPartido()->obtenerNumeroJugadoresMaximoPermitidosReservaPista();
            if ($numeroJugadoresApuntados >= $numeroJugadoresMaximoPermitidosReservaPista){
                Log::v(__FUNCTION__, "Número Jugadores Apuntados: $numeroJugadoresApuntados | Número Jugadores Máximo Permitidos Reserva Pista: $numeroJugadoresMaximoPermitidosReservaPista", true);
                return true;
            }
        }

        return false;
    }

    function apuntarJugador($idJugador, $idJugadorSustituir=""){

        Log::v(__FUNCTION__, "ID Jugador: $idJugador | ID Jugador Sutituir: $idJugadorSustituir");

        //JMAM: Elimina el Jugador a Sustituir
        if (!empty($idJugadorSustituir && $this->existeJugadorApuntadoEnElPartido($idJugadorSustituir))){
            $PartidoJugador = $this->obtenerPartidoJugadorDelPartidoPorIdJugador($idJugadorSustituir);
            $PartidoJugador->eliminar();
        }

        if (!$this->existeJugadorApuntadoEnElPartido($idJugador)){
            $PartidoJugador = new PartidoJugador();
            $PartidoJugador[PartidoJugador::COLUMNA_idPartido] = $this->obtenerId();
            $PartidoJugador[PartidoJugador::COLUMNA_idJugador] = $idJugador;
            $PartidoJugador[PartidoJugador::COLUMNA_tipoJugador] = PartidoJugador::TIPOJUGADOR_interno;
            $PartidoJugador[PartidoJugador::COLUMNA_numeroJugador] = $this->obtenerNumeroJugadores() + 1;
            if ($this->esReservaPistaPartido()){
                $PartidoJugador[PartidoJugador::COLUMNA_idReservaPista] = $this->obtenerReservaPistaPartido()->obtenerId();
            }

            Log::v(__FUNCTION__, print_r($PartidoJugador, true), true);

            $PartidoJugador->guardar();
        }

        ReservaPista::eliminarCacheRedisTablaReservaParaElCampoYFecha($this->obtenerCampo()->obtenerId(), $this->obtenerFecha());

    }

    function generarReservaPistaParaMostrarPartidoEnTablaReservas(){
        if ($this->esReservaPistaPartido(false)){
            //JMAM: Ya existe una reserva para este partido, no se genera
            Log::v(__FUNCTION__, "Ya existe una reserva para este partido, no se genera", true);
            return false;
        }


        $horaFin = Fecha::anadirMinutosAHora(90, $this[self::COLUMNA_hora]);



        $array_PistasDisponibles = CacheTablaReserva::obtenerPistasTramoLibresParaReservar($this->obtenerCampo()->obtenerId(), $this->obtenerFecha(), $this->obtenerHora(), $horaFin);
        if (count($array_PistasDisponibles) <= 0){
            Log::v(__FUNCTION__, "No hay pistas disponibles, no se genera reserva del partido", true);
            return false;
        }
        $idPista = $array_PistasDisponibles[0]->obtenerId();


        $ReservaPistaPistaAnterior = $this->obtenerReservaPistaPartido();
        $ReservaPista = $this->obtenerReservaPistaPartido();
        $ReservaPista[ReservaPista::COLUMNA_idPartido] = $this->obtenerId();
        $ReservaPista[ReservaPista::COLUMNA_idCampo] = $this->obtenerCampo()->obtenerId();
        $ReservaPista[ReservaPista::COLUMNA_idPista] = $idPista;
        $ReservaPista[ReservaPista::COLUMNA_idTipoReserva] = TipoReserva::ID_TIPORESERVA_SIN_RESERVA;
        $ReservaPista[ReservaPista::COLUMNA_idJugador1] = $this[self::COLUMNA_idJugador1];
        $ReservaPista[ReservaPista::COLUMNA_idJugador2] = $this[self::COLUMNA_idJugador2];
        $ReservaPista[ReservaPista::COLUMNA_idJugador3] = $this[self::COLUMNA_idJugador3];
        $ReservaPista[ReservaPista::COLUMNA_idJugador4] = $this[self::COLUMNA_idJugador4];
        $ReservaPista[ReservaPista::COLUMNA_tipoJugador1] = ReservaPista::TIPOJUGADOR_INTERNO;
        $ReservaPista[ReservaPista::COLUMNA_tipoJugador2] = ReservaPista::TIPOJUGADOR_INTERNO;
        $ReservaPista[ReservaPista::COLUMNA_tipoJugador3] = ReservaPista::TIPOJUGADOR_INTERNO;
        $ReservaPista[ReservaPista::COLUMNA_tipoJugador4] = ReservaPista::TIPOJUGADOR_INTERNO;
        $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador1] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
        $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador2] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
        $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador3] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
        $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador4] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
        $ReservaPista[ReservaPista::COLUMNA_numeroJugadoresMaximoPermitidos] = 4;
        $ReservaPista[ReservaPista::COLUMNA_fechaReserva] = $this[self::COLUMNA_fecha];
        $ReservaPista[ReservaPista::COLUMNA_horaInicioReserva] = $this[self::COLUMNA_hora];
        $ReservaPista[ReservaPista::COLUMNA_horaFinReserva] = Fecha::anadirMinutosAHora(90, $this[self::COLUMNA_hora]);
        $ReservaPista[ReservaPista::COLUMNA_partidoPublico] = 1;
        $ReservaPista[ReservaPista::COLUMNA_partidoCompleto] = 0;
        $idReservaPista = $ReservaPista->guardar();
        $ReservaPista = new ReservaPista($idReservaPista);
        $ReservaPista->actualizarCacheReserva(true);
        $ReservaPistaPistaAnterior->actualizarCacheReserva();

        Log::v(__FUNCTION__, "Generada Reserva para mostrar el partido en la tabla de reservas", true);
    }


    /**
     *
     * @author JMAM
     *
     * Obtiene los partidos abiertos desde hoy
     *
     * @param $idLiga
     * Id de la liga
     * @return bool|int|mixed
     */
    static function obtenerNumeroPartidosAbiertos($idLiga){
        global $bd;

        $fechaHoy = date("Y-m-d");

        return $bd->count("partidos", array("AND" => array("id_Liga" => $idLiga, "Fecha[>=]" => $fechaHoy)));
    }

    static function obtenerNumeroPartidos($idLiga){
        global $bd;
        return $bd->count("partidos", array("id_Liga" => $idLiga));
    }

    static function obtenerIdsPartidosNoCoincideJugadoresApuntandosConPartidoJugadoresAPartirDeHoy(){
        global $bd;

        $where["AND"][self::COLUMNA_fecha."[>=]"] = date("Y-m-d");
        $where["LIMIT"] = 200;
        $array_idsPartidos = $bd->select(self::TABLA_NOMBRE, self::COLUMNA_id, $where);
        foreach ($array_idsPartidos as $idPartido){
            $array_idsJugadores_partido = array();
            $Partido = new Partido($idPartido);
            $nombrePartido = $Partido->obtenerNombreRepresentativoDiaHoraClubATexto(false);
            $idJugador1 = $Partido[self::COLUMNA_idJugador1];
            $idJugador2 = $Partido[self::COLUMNA_idJugador2];
            $idJugador3 = $Partido[self::COLUMNA_idJugador3];
            $idJugador4 = $Partido[self::COLUMNA_idJugador4];

            if (!empty($idJugador1)){
                $array_idsJugadores_partido[] = $idJugador1;
            }
            if (!empty($idJugador2)){
                $array_idsJugadores_partido[] = $idJugador2;
            }
            if (!empty($idJugador3)){
                $array_idsJugadores_partido[] = $idJugador3;
            }
            if (!empty($idJugador4)){
                $array_idsJugadores_partido[] = $idJugador4;
            }

            $array_idsJugadores_partidosJugador = array();
            $array_idsJugadores_partidosJugador[] = PartidoJugador::obtenerPartidoJugadorDelPartido($idPartido, 1)->obtenerJugador()->obtenerId();
            $array_idsJugadores_partidosJugador[] = PartidoJugador::obtenerPartidoJugadorDelPartido($idPartido, 2)->obtenerJugador()->obtenerId();
            $array_idsJugadores_partidosJugador[] = PartidoJugador::obtenerPartidoJugadorDelPartido($idPartido, 3)->obtenerJugador()->obtenerId();
            $array_idsJugadores_partidosJugador[] = PartidoJugador::obtenerPartidoJugadorDelPartido($idPartido, 4)->obtenerJugador()->obtenerId();

            if (array_diff($array_idsJugadores_partido, $array_idsJugadores_partidosJugador)){
                echo "<br>Se ha encontrado una diferencia de jugadores en el ID Partido: $idPartido | $nombrePartido";
                echo "<br> ID Jugadores Partido";
                print_r($array_idsJugadores_partido);
                echo "<br> ID Jugadores Partido Jugador";
                print_r($array_idsJugadores_partidosJugador);
                echo "<br><hr>";
            }


        }
    }

    static function recuperarPartidosArchivadosIncorrectamenteConFechaDeHoyEnAdelante(){
        global $bd;
        $hoy = date("Y-m-d");
        $filas = $bd->query("SELECT id_partido FROM `resultados` WHERE id_partido NOT IN (SELECT id FROM partidosArchivados) AND FechaResult >= '$hoy' AND id_partido != 0 GROUP BY id_partido")->fetchAll();
        foreach ($filas as $fila){
            $idPartido = $fila[0];
            $Partido = new Partido($idPartido);
            echo "<br/>ID Partido: $idPartido";
            $PartidoHistorial = new PartidoHistorial(PartidoHistorial::obtenerUltimoIdPartidoHistorialDelPartido($idPartido));
            $fechaPartido = $PartidoHistorial->obtenerFechaPartido();
            echo "<br/> ID Partido Historial a Archivar: ".$PartidoHistorial->obtenerId()." | Fecha: $fechaPartido";
            $PartidoHistorial->guardarEnPartidoArchivado();
            $PartidoArchivado = new PartidoArchivado($PartidoHistorial->obtenerId());
            if($PartidoArchivado->esReservaPistaPartido()){
                $PartidoArchivado->obtenerReservaPistaPartido()->actualizarCacheReserva();
            }
        }
    }



}