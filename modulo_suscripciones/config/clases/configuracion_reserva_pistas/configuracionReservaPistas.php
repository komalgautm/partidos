<?php

class ConfiguracionReservaPistas extends general
{
    const NOMBRE_TABLA = "configuracion_reserva_pistas";

    const COLUMNA_mostrarSelectorPistaFormatoTabla = "mostrarSelectorPistaFormatoTabla";
    const COLUMNA_nombrePrimerMonedero = "nombrePrimerMonedero";
    const COLUMNA_nombreSegundoMonedero = "nombreSegundoMonedero";
    const COLUMNA_idTipoReservaSegundoMonedero = "idTipoReservaSegundoMonedero";
    const COLUMNA_aplazarPagoMonedero = "aplazarPagoMonedero";
    const COLUMNA_esPermitidoReservarAJugadores = "esPermitidoReservarAJugadores";
    const COLUMNA_esPermitidoReservarAJugadoresNoActivos = "esPermitidoReservarAJugadoresNoActivos";
    const COLUMNA_esPermitidoPrerreservas = "esPermitidoPrerreservas";
    const COLUMNA_horasAntelacionMinimaSePermiteRealizarReserva = "horasAntelacionMinimaSePermiteRealizarReserva";
    const COLUMNA_esPermitidoVerListadoPartidosAVisitantes = "esPermitidoVerListadoPartidosAVisitantes";
    const COLUMNA_esMostrarPartidosEnTablaReservas = "esMostrarPartidosEnTablaReservas";

    const PORDEFECTO_diasAntesGeneral = 90;
    const PORDEFECTO_diasAntesRadical = 90;
    const PORDEFECTO_diasAntesSocios = 90;
    const PORDEFECTO_diasAntesGrupo1 = 90;
    const PORDEFECTO_diasAntesGrupo2 = 90;
    const PORDEFECTO_diasAntesMonedero = 90;

    const PORDEFECTO_horasAntesCancelarGeneral = 12;
    const PORDEFECTO_horasAntesCancelarRadical = 12;
    const PORDEFECTO_horasAntesCancelarSocios = 12;
    const PORDEFECTO_horasAntesCancelarGrupo1 = 12;
    const PORDEFECTO_horasAntesCancelarGrupo2 = 12;
    const PORDEFECTO_horasAntesCancelarMonedero = 12;

    const PORDEFECTO_pagoOnlineGeneral = -1;
    const PORDEFECTO_pagoOnlineRadical = -1;
    const PORDEFECTO_pagoOnlineSocios = -1;
    const PORDEFECTO_pagoOnlineGrupo1 = -1;
    const PORDEFECTO_pagoOnlineGrupo2 = -1;
    const PORDEFECTO_pagoOnlineMonedero = -1;

    const PORDEFECTO_pagoProporcionalGeneral = true;
    const PORDEFECTO_pagoProporcionalRadical = true;
    const PORDEFECTO_pagoProporcionalSocios = true;
    const PORDEFECTO_pagoProporcionalGrupo1 = true;
    const PORDEFECTO_pagoProporcionalGrupo2 = true;
    const PORDEFECTO_pagoProporcionalMonedero = true;

    const PORDEFECTO_colorResaltadoPistaOcupada = "#f0d4d1";
    const PORDEFECTO_colorResaltadoPistaLibre = "#f7f7f7";
    const PORDEFECTO_colorResaltadoSinOferta = "#9bfdbd";
    const PORDEFECTO_colorResaltadoConOferta = "#bdff7a";

    const PORDEFECTO_mostrarPrecios = true;
    const PORDEFECTO_mostrarOferta = false;
    const PORDEFECTO_seleccionarPistas = true;
    const PORDEFECTO_seleccionarDuracion = true;
    const PORDEFECTO_mostrarSelectorPistaFormatoTabla = false;

    const PORDEFECTO_nombrePrimerMonedero = "";
    const PORDEFECTO_nombreSegundoMonedero = "";
    const PORDEFECTO_idTipoReservaSegundoMonedero = 0;

    const PORDEFECTO_aplazarPagoMonedero = 0;
    const PORDEFECTO_esPermitidoReservarAJugadores = 0;
    const PORDEFECTO_esPermitidoReservarAJugadoresNoActivos = 0;
    const PORDEFECTO_esPermitidoPrerreservas = 1;
    const PORDEFECTO_horasAntelacionMinimaSePermiteRealizarReserva = 0;

    const TIPO_aplazarPagoMonedero_NO = 0;
    const TIPO_aplazarPagoMonedero_OBLIGATORIO = 1;
    const TIPO_aplazarPagoMonedero_OPCIONAL = 2;

    const PORDEFECTO_esPermitidoVerListadoPartidosAVisitantes = 1;
    const PORDEFECTO_esMostrarPartidosEnTablaReservas = 0;




    function ConfiguracionReservaPistas($id)
    {
        if ($id != '')
            parent::__construct(self::NOMBRE_TABLA, 'id', $id);
        else
            parent::__construct(self::NOMBRE_TABLA, '', '');
    }



    static function obtenerTodos(){
        global $bd;

        $ids = $bd->select(self::NOMBRE_TABLA, "id", array("ORDER" => "id DESC"));


        $array = [];
        foreach ($ids as $id) {
            $array[] = new ConfiguracionReservaPistas($id);
        }
        return $array;
    }

    static function obtenerConfiguracionReservaPistas($idCampo){
        global $bd;

        $id = $bd->get(self::NOMBRE_TABLA, "id", array("idCampo" => $idCampo));

        return new ConfiguracionReservaPistas($id);
    }

    function esPermitidoPagoProporcional($idGrupoJugador){

        switch ($idGrupoJugador){

            case GrupoJugador::ID_GENERAL:
                return $this->esPermitidoPagoProporcionalGeneral();
                break;

            case GrupoJugador::ID_RADICAL:
                return $this->esPermitidoPagoProporcionalRadical();
                break;

            case GrupoJugador::ID_SOCIOS:
                return $this->esPermitidoPagoProporcionalSocios();
                break;

            case GrupoJugador::ID_GRUPO1:
                return $this->esPermitidoPagoProporcionalGrupo1();
                break;

            case GrupoJugador::ID_GRUPO2:
                return $this->esPermitidoPagoProporcionalGrupo2();
                break;

            case GrupoJugador::ID_MONEDERO:
                return $this->esPermitidoPagoProporcionalMonedero();
                break;
        }
    }

    function obtenerDiasAntesPuedeReservar($idGrupoJugador){

        switch ($idGrupoJugador){

            case GrupoJugador::ID_GENERAL:
                return $this->obtenerDiasAntesReservarGeneral();
                break;

            case GrupoJugador::ID_RADICAL:
                return $this->obtenerDiasAntesReservarRadical();
                break;

            case GrupoJugador::ID_SOCIOS:
                return $this->obtenerDiasAntesReservarSocios();
                break;

            case GrupoJugador::ID_GRUPO1:
                return $this->obtenerDiasAntesReservarGrupo1();
                break;

            case GrupoJugador::ID_GRUPO2:
                return $this->obtenerDiasAntesReservarGrupo2();
                break;

            case GrupoJugador::ID_MONEDERO:
                return $this->obtenerDiasAntesReservarMonedero();
                break;
        }
    }

    function obtenerHorasAntesCancelar($idGrupoJugador){

        switch ($idGrupoJugador){

            case GrupoJugador::ID_GENERAL:
                return $this->obtenerHorasAntesCancelarGeneral();
                break;

            case GrupoJugador::ID_RADICAL:
                return $this->obtenerHorasAntesCancelarRadical();
                break;

            case GrupoJugador::ID_SOCIOS:
                return $this->obtenerHorasAntesCancelarSocios();
                break;

            case GrupoJugador::ID_GRUPO1:
                return $this->obtenerHorasAntesCancelarGrupo1();
                break;

            case GrupoJugador::ID_GRUPO2:
                return $this->obtenerHorasAntesCancelarGrupo2();
                break;

            case GrupoJugador::ID_MONEDERO:
                return $this->obtenerHorasAntesCancelarMonedero();
                break;
        }
    }

    function obtenerPagoOnline($idGrupoJugador){

        switch ($idGrupoJugador){

            case GrupoJugador::ID_GENERAL:
                return $this->obtenerPagoOnlineGeneral();
                break;

            case GrupoJugador::ID_RADICAL:
                return $this->obtenerPagoOnlineRadical();
                break;

            case GrupoJugador::ID_SOCIOS:
                return $this->obtenerPagoOnlineSocios();
                break;

            case GrupoJugador::ID_GRUPO1:
                return $this->obtenerPagoOnlineGrupo1();
                break;

            case GrupoJugador::ID_GRUPO2:
                return $this->obtenerPagoOnlineGrupo2();
                break;

            case GrupoJugador::ID_MONEDERO:
                return $this->obtenerPagoOnlineMonedero();
                break;
        }
    }


    function obtenerId(){
        return $this["id"];
    }

    function obtenerIdCampo(){
        return $this["idCampo"];
    }

    function obtenerDiasAntesReservarGeneral(){
        $diasAntesGeneral =  $this["diasAntesGeneral"];

        if ($diasAntesGeneral == ""){
            return self::PORDEFECTO_diasAntesGeneral;
        }
        else{
            return $diasAntesGeneral;
        }
    }

    function obtenerDiasAntesReservarRadical(){
        $diasAntesRadical =  $this["diasAntesRadical"];

        if ($diasAntesRadical == ""){
            return self::PORDEFECTO_diasAntesRadical;
        }
        else{
            return $diasAntesRadical;
        }
    }

    function obtenerDiasAntesReservarSocios(){
        $diasAntesSocios = $this["diasAntesSocios"];

        if ($diasAntesSocios == ""){
            return self::PORDEFECTO_diasAntesSocios;
        }
        else{
            return $diasAntesSocios;
        }
    }

    function obtenerDiasAntesReservarGrupo1(){
        $diasAntesGrupo1 =  $this["diasAntesGrupo1"];

        if ($diasAntesGrupo1 == ""){
            return self::PORDEFECTO_diasAntesGrupo1;
        }
        else{
            return $diasAntesGrupo1;
        }
    }

    function obtenerDiasAntesReservarGrupo2(){
        $diasAntesGrupo2 = $this["diasAntesGrupo2"];

        if ($diasAntesGrupo2 == ""){
            return self::PORDEFECTO_diasAntesGrupo2;
        }
        else{
            return $diasAntesGrupo2;
        }
    }

    function obtenerDiasAntesReservarMonedero(){
        $diasAntesMonedero = $this["diasAntesMonedero"];

        if ($diasAntesMonedero == ""){
            return self::PORDEFECTO_diasAntesMonedero;
        }
        else{
            return $diasAntesMonedero;
        }
    }

    function obtenerHorasAntesCancelarGeneral(){
        $horasAntesCancelarGeneral =  $this["horasAntesCancelarGeneral"];

        if ($horasAntesCancelarGeneral == ""){
            return self::PORDEFECTO_horasAntesCancelarGeneral;
        }
        else{
            return $horasAntesCancelarGeneral;
        }
    }

    function obtenerHorasAntesCancelarRadical(){
        $horasAntesCancelarRadical =  $this["horasAntesCancelarRadical"];

        if ($horasAntesCancelarRadical == ""){
            return self::PORDEFECTO_horasAntesCancelarRadical;
        }
        else{
            return $horasAntesCancelarRadical;
        }
    }

    function obtenerHorasAntesCancelarSocios(){
        $horasAntesCancelarSocios = $this["horasAntesCancelarSocios"];

        if ($horasAntesCancelarSocios == ""){
            return self::PORDEFECTO_horasAntesCancelarSocios;
        }
        else{
            return $horasAntesCancelarSocios;
        }
    }

    function obtenerHorasAntesCancelarGrupo1(){
        $horasAntesCancelarGrupo1 =  $this["horasAntesCancelarGrupo1"];

        if ($horasAntesCancelarGrupo1 == ""){
            return self::PORDEFECTO_horasAntesCancelarGrupo1;
        }
        else{
            return $horasAntesCancelarGrupo1;
        }
    }

    function obtenerHorasAntesCancelarGrupo2(){
        $horasAntesCancelarGrupo2 = $this["horasAntesCancelarGrupo2"];

        if ($horasAntesCancelarGrupo2 == ""){
            return self::PORDEFECTO_horasAntesCancelarGrupo2;
        }
        else{
            return $horasAntesCancelarGrupo2;
        }
    }

    function obtenerHorasAntesCancelarMonedero(){
        $horasAntesCancelarMonedero = $this["horasAntesCancelarMonedero"];

        if (horasAntesCancelarMonedero == ""){
            return self::PORDEFECTO_horasAntesCancelarMonedero;
        }
        else{
            return $horasAntesCancelarMonedero;
        }
    }

    function obtenerPagoOnlineGeneral(){
        $pagoOnlineGeneral = $this["pagoOnlineGeneral"];

        if ($pagoOnlineGeneral == ""){
            return self::PORDEFECTO_pagoOnlineGeneral;
        }
        else{
            return $pagoOnlineGeneral;
        }
    }

    function obtenerPagoOnlineRadical(){
        $pagoOnlineRadical = $this["pagoOnlineRadical"];

        if ($pagoOnlineRadical == ""){
            return self::PORDEFECTO_pagoOnlineRadical;
        }
        else{
            return $pagoOnlineRadical;
        }
    }

    function obtenerPagoOnlineSocios(){
        $pagoOnlineSocios = $this["pagoOnlineSocios"];

        if ($pagoOnlineSocios == ""){
            return self::PORDEFECTO_pagoOnlineSocios;
        }
        else{
            return $pagoOnlineSocios;
        }
    }

    function obtenerPagoOnlineGrupo1(){
        $pagoOnlineGrupo1 = $this["pagoOnlineGrupo1"];

        if ($pagoOnlineGrupo1 == ""){
            return self::PORDEFECTO_pagoOnlineGrupo1;
        }
        else{
            return $pagoOnlineGrupo1;
        }
    }

    function obtenerPagoOnlineGrupo2(){
        $pagoOnlineGrupo2 = $this["pagoOnlineGrupo2"];

        if ($pagoOnlineGrupo2 == ""){
            return self::PORDEFECTO_pagoOnlineGrupo2;
        }
        else{
            return $pagoOnlineGrupo2;
        }
    }

    function obtenerPagoOnlineMonedero(){
        $pagoOnlineMonedero = $this["pagoOnlineMonedero"];

        if ($pagoOnlineMonedero == ""){
            return self::PORDEFECTO_pagoOnlineMonedero;
        }
        else{
            return $pagoOnlineMonedero;
        }
    }

    function esPermitidoPagoProporcionalGeneral(){
        $pagoProporcionalGeneral = $this["pagoProporcionalGeneral"];

        if ($pagoProporcionalGeneral == ""){
            return self::PORDEFECTO_pagoProporcionalGeneral;
        }
        else{
            return $pagoProporcionalGeneral;
        }
    }

    function esPermitidoPagoProporcionalRadical(){
        $pagoProporcionalRadical = $this["pagoProporcionalRadical"];

        if ($pagoProporcionalRadical == ""){
            return self::PORDEFECTO_pagoProporcionalRadical;
        }
        else{
            return $pagoProporcionalRadical;
        }
    }

    function esPermitidoPagoProporcionalSocios(){
        $pagoProporcionalSocios = $this["pagoProporcionalSocios"];

        if ($pagoProporcionalSocios == ""){
            return self::PORDEFECTO_pagoProporcionalSocios;
        }
        else{
            return $pagoProporcionalSocios;
        }
    }

    function esPermitidoPagoProporcionalGrupo1(){
        $pagoProporcionalGrupo1 = $this["pagoProporcionalGrupo1"];

        if ($pagoProporcionalGrupo1 == ""){
            return self::PORDEFECTO_pagoProporcionalGrupo1;
        }
        else{
            return $pagoProporcionalGrupo1;
        }
    }

    function esPermitidoPagoProporcionalGrupo2(){
        $pagoProporcionalGrupo2 = $this["pagoProporcionalGrupo2"];

        if ($pagoProporcionalGrupo2 == ""){
            return self::PORDEFECTO_pagoProporcionalGrupo2;
        }
        else{
            return $pagoProporcionalGrupo2;
        }
    }

    function esPermitidoPagoProporcionalMonedero(){
        $pagoProporcionalMonedero = $this["pagoProporcionalMonedero"];

        if ($pagoProporcionalMonedero == ""){
            return self::PORDEFECTO_pagoProporcionalMonedero;
        }
        else{
            return $pagoProporcionalGrupo2;
        }
    }

    function obtenerColorRestaladoPistaOcupada($idTipoReserva = ""){
        $colorRestadoPistaOcupada = $this["colorResaltadoPistaOcupada"];

        if ($colorRestadoPistaOcupada == ""){
            return self::PORDEFECTO_colorResaltadoPistaOcupada;
        }
        else{
            return $colorRestadoPistaOcupada;
        }
    }

    function obtenerColorRestaladoPistaLibre(){
        $colorRestadoPistaLibre = $this["colorResaltadoPistaLibre"];

        if ($colorRestadoPistaLibre == ""){
            return self::PORDEFECTO_colorResaltadoPistaLibre;
        }
        else{
            return $colorRestadoPistaLibre;
        }
    }

    function obtenerColorRestaladoSinOferta($idTipoReserva = ""){

        if ($idTipoReserva == ""){
            $colorRestadoSinOferta = $this["colorResaltadoSinOferta"];

            //echo "COLOR RESALTADO: ".$colorRestadoSinOferta;

            if ($colorRestadoSinOferta == ""){
                return self::PORDEFECTO_colorResaltadoSinOferta;
            }
            else{
                return $colorRestadoSinOferta;
            }
        }
        else{
            switch ($idTipoReserva) {
                case TipoReserva::ID_TIPORESERVA_CLASES:
                    return "orange";
                    break;

                case TipoReserva::ID_TIPORESERVA_ESCUELA:
                    return "orange";
                    break;


                case TipoReserva::ID_TIPORESERVA_BLOQUEO:
                    return "red";
                    break;

                default:
                    return $this->obtenerColorRestaladoSinOferta();
            }
        }

    }

    function obtenerColorRestaladoConOferta(){
        $colorRestadoConOferta = $this["colorResaltadoConOferta"];

        if ($colorRestadoConOferta == ""){
            return self::PORDEFECTO_colorResaltadoConOferta;
        }
        else{
            return $colorRestadoConOferta;
        }
    }

    function esPermitidoCancelarUsuariosActivos(){

        if ($this->obtenerHorasAntesCancelarRadical() > 0){
            return true;
        }
        return false;
    }

    function esPermitidoCancelarUsuariosNOActivos(){

        if ($this->obtenerHorasAntesCancelarGeneral() > 0){
            return true;
        }
        return false;
    }

    function esPermitidoCancelarSocios(){

        if ($this->obtenerHorasAntesCancelarSocios() > 0){
            return true;
        }
        return false;
    }

    function esPermitidoCancelarGrupo1(){

        if ($this->obtenerHorasAntesCancelarGrupo1() > 0){
            return true;
        }
        return false;
    }

    function esPermitidoCancelarGrupo2(){

        if ($this->obtenerHorasAntesCancelarGrupo2() > 0){
            return true;
        }
        return false;
    }

    function esPermitidoCancelarMonedero(){

        if ($this->obtenerHorasAntesCancelarMonedero() > 0){
            return true;
        }
        return false;
    }

    function esPermitidoMostrarPrecios(){
        $mostrarPrecios = $this["mostrarPrecios"];

        if ($mostrarPrecios == 1){
            return true;
        }
        else if ($mostrarPrecios == "")
            return self::PORDEFECTO_mostrarPrecios;
        else{
            return false;
        }
    }

    function esPermitidoMostrarOferta(){
        $mostrarOferta = $this["mostrarOferta"];

        if ($mostrarOferta == 1){
            return true;
        }
        else if ($mostrarOferta == "")
            return self::PORDEFECTO_mostrarOferta;
        else{
            return false;
        }
    }

    function esPermitidoSeleccionarPistas(){

        $seleccionarPistas = $this["seleccionarPistas"];

        if ($seleccionarPistas == 1){
            return true;
        }
        else if ($seleccionarPistas == ""){
            return self::PORDEFECTO_seleccionarPistas;
        }
        else{
            return false;
        }
    }

    function esPermitidoSeleccionarDuracion(){

        $seleccionarDuracion = $this["seleccionarDuracion"];

        if ($seleccionarDuracion == 1){
            return true;
        }
        else if ($seleccionarDuracion == ""){
            return self::PORDEFECTO_seleccionarDuracion;
        }
        else{
            return false;
        }
    }


    function esPermitidoMostrarSelectorPistaFormatoTabla(){

        $mostrarSelectorPistaFormatoTabla = $this[self::COLUMNA_mostrarSelectorPistaFormatoTabla];

        if ($mostrarSelectorPistaFormatoTabla == 1){
            return true;
        }
        else if ($mostrarSelectorPistaFormatoTabla == ""){
            return self::PORDEFECTO_mostrarSelectorPistaFormatoTabla;
        }
        else{
            return false;
        }
    }

    function esPermitidoPrerreservas(){

        $esPermitidoPrerreservas = $this[self::COLUMNA_esPermitidoPrerreservas];

        if ($esPermitidoPrerreservas == 1){
            return true;
        }
        else if ($esPermitidoPrerreservas == ""){
            return self::PORDEFECTO_esPermitidoPrerreservas;
        }
        else{
            return false;
        }
    }

    function obtenerHorasAntelacionMinimaSePermiteRealizarReserva(){
        $horasAntelacionMinimaSePermiteRealizarReserva = $this[self::COLUMNA_horasAntelacionMinimaSePermiteRealizarReserva];

        if ($horasAntelacionMinimaSePermiteRealizarReserva == ""){
            return self::PORDEFECTO_horasAntelacionMinimaSePermiteRealizarReserva;
        }
        else{
            return $horasAntelacionMinimaSePermiteRealizarReserva;
        }
    }

    function obtenerNombrePrimerMonedero(){
        $nombrePrimerMonedero = $this[self::COLUMNA_nombrePrimerMonedero];
        if (empty($nombrePrimerMonedero)){
            return self::PORDEFECTO_nombrePrimerMonedero;
        }
        return $nombrePrimerMonedero;
    }

    function obtenerNombreSegundoMonedero(){
        $nombreSegundoMonedero = $this[self::COLUMNA_nombreSegundoMonedero];
        if (empty($nombreSegundoMonedero)){
            return self::PORDEFECTO_nombreSegundoMonedero;
        }
        return $nombreSegundoMonedero;
    }

    function obtenerIdTipoReservaSegundoMonedero(){
        $idTipoReservaSegundoMonedero = $this[self::COLUMNA_idTipoReservaSegundoMonedero];
        if (empty($idTipoReservaSegundoMonedero)){
            return self::PORDEFECTO_idTipoReservaSegundoMonedero;
        }
        return $idTipoReservaSegundoMonedero;
    }

    function obtenerAplazarPagoMonedero(){

        $aplazadoPagoMonedero = $this[self::COLUMNA_aplazarPagoMonedero];

        if ($aplazadoPagoMonedero == ""){
            return self::PORDEFECTO_aplazarPagoMonedero;
        }
        else{
            return $aplazadoPagoMonedero;
        }
    }

    function esPermitidoReservarAJugadores(){

        $esPermitidoReservarAJugadores = $this[self::COLUMNA_esPermitidoReservarAJugadores];

        if ($esPermitidoReservarAJugadores == 1){
            return true;
        }
        else if ($esPermitidoReservarAJugadores == ""){
            return self::PORDEFECTO_esPermitidoReservarAJugadores;
        }
        else{
            return false;
        }
    }

    function esPermitidoReservarAJugadoresNoActivos(){

        $esPermitidoReservarAJugadoresNoActivos = $this[self::COLUMNA_esPermitidoReservarAJugadoresNoActivos];

        if ($esPermitidoReservarAJugadoresNoActivos == 1){
            return true;
        }
        else if ($esPermitidoReservarAJugadoresNoActivos == ""){
            return self::PORDEFECTO_esPermitidoReservarAJugadoresNoActivos;
        }
        else{
            return false;
        }
    }

    function esPermitidoVerListadoPartidosAVisitantes(){

        $esPermitidoVerListadoPartidosAVisitantes = $this[self::COLUMNA_esPermitidoVerListadoPartidosAVisitantes];

        if ($esPermitidoVerListadoPartidosAVisitantes == 1){
            return true;
        }
        else if ($esPermitidoVerListadoPartidosAVisitantes == ""){
            return self::PORDEFECTO_esPermitidoVerListadoPartidosAVisitantes;
        }
        else{
            return false;
        }
    }

    function esMostrarPartidosEnTablaReservas(){

        $esMostrarPartidosEnTablaReservas = $this[self::COLUMNA_esMostrarPartidosEnTablaReservas];

        if ($esMostrarPartidosEnTablaReservas == 1){
            return true;
        }
        else if ($esMostrarPartidosEnTablaReservas == ""){
            return self::PORDEFECTO_esMostrarPartidosEnTablaReservas;
        }
        else{
            return false;
        }
    }




}