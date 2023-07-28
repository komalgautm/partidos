<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR );
*/
require_once "../../config.php";

extract($_GET);
extract($_POST);


switch ($op){

    case "guardar":
        if ($id > 0){
            $HorarioPista = new HorarioPista($id);
        }
        else{
            $HorarioPista = new HorarioPista();
        }

        //JMAM: Guarda el Horario
        $HorarioPista["idPista"] = $idPista;
        $HorarioPista["diaSemana"] = $diaSemana;
        $HorarioPista["horaInicio"] = $horaInicio;
        $HorarioPista["horaFin"] = $horaFin;
        $idHorario = $HorarioPista->guardar();

        //JMAM: Guarda el Tiempo de Reserva
        $TiempoReserva = new TiempoReserva();
        $TiempoReserva["idHorario"] = $idHorario;
        $TiempoReserva["tiempoReserva"] = $tiempoReserva;
        $TiempoReserva["precioGeneral"] = $precioGeneral;
        $TiempoReserva["precioSocios"] = $precioSocios;
        $TiempoReserva["precioGrupo1"] = $precioGrupo1;
        $TiempoReserva["precioGrupo2"] = $precioGrupo2;
        $TiempoReserva["iluminacionIncluida"] = $iluminacionIncluida;
        $TiempoReserva["precioMonedero"] = $precioMonedero;
        $TiempoReserva->guardar();
        
        
        echo 1;
        break;

    case "editar":

        $fechaHoy = date("Y-m-d H:i:s");


        $ReservaPista = new ReservaPista($id);
        $ReservaPista["idPista"] = $idPista;
        $ReservaPista["fechaReserva"] = $fechaReserva;
        $ReservaPista["horaInicioReserva"] = $horaInicioReserva;
        $ReservaPista["horaFinReserva"] = $horaFinReserva;
        $ReservaPista->guardar();

        $idJugador1 = $ReservaPista->obtenerJugador1()->obtenerId();
        $idJugador2 = $ReservaPista->obtenerJugador2()->obtenerId();
        $idJugador3 = $ReservaPista->obtenerJugador3()->obtenerId();
        $idJugador4 = $ReservaPista->obtenerJugador4()->obtenerId();





        //JMAM: Gestiona los Pagos

        ReservaPista::pagarDevolverMonederoAJugadoresSiEsNecesario($ReservaPista->obtenerId(), $pagadoJugador1, $pagadoJugador2, $pagadoJugador3, $pagadoJugador4, $aplazadoPagoJugador1, $aplazadoPagoJugador2, $aplazadoPagoJugador3, $aplazadoPagoJugador4, $importePagoJugador1, $importePagoJugador2, $importePagoJugador3, $importePagoJugador4);

            /*
        //JMAM: Jugador 1
        if ($pagadoJugador1 == 1){
            $esPagadoConMonedero = Monedero::pagarConMonedero($idJugador1, $ReservaPista->obtenerId(), $importePagoJugador1);
            if ($esPagadoConMonedero == false){
                $ReservaPista = new ReservaPista($id);
                $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR1] = $importePagoJugador1;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador1] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[ReservaPista::COLUMNA_PAGADOJUGADOR1] = 1;
                $ReservaPista[$ReservaPista::COLUMNA_FECHAPAGOJUGADOR1] = $fechaHoy;
                $ReservaPista->guardar();
            }
        }
        else{
            $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador1, $ReservaPista->obtenerId());

            if ($esDevueltoPagoMonedero == false){
                $ReservaPista = new ReservaPista($id);
                $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR1] = $importePagoJugador1;
                $ReservaPista[ReservaPista::COLUMNA_tipoPagoJugador1] = "";
                $ReservaPista[ReservaPista::COLUMNA_PAGADOJUGADOR1] = 0;
                $ReservaPista[$ReservaPista::COLUMNA_FECHAPAGOJUGADOR1] = "";
                $ReservaPista->guardar();
            }
        }

        //JMAM: Jugador 2
        if ($pagadoJugador2 == 1){
            $esPagadoConMonedero = Monedero::pagarConMonedero($idJugador2, $ReservaPista->obtenerId(), $importePagoJugador2);
            if ($esPagadoConMonedero == false){
                $ReservaPista = new ReservaPista($id);
                $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR2] = $importePagoJugador2;
                $ReservaPista[ReservaPista::COLUMNA_TIPOPAGOJUGADOR2] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[ReservaPista::COLUMNA_PAGADOJUGADOR2] = 1;
                $ReservaPista[$ReservaPista::COLUMNA_FECHAPAGOJUGADOR2] = $fechaHoy;
                $ReservaPista->guardar();
            }
        }
        else{
            $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador2, $ReservaPista->obtenerId());

            if ($esDevueltoPagoMonedero == false){
                $ReservaPista = new ReservaPista($id);
                $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR2] = $importePagoJugador2;
                $ReservaPista[ReservaPista::COLUMNA_TIPOPAGOJUGADOR2] = "";
                $ReservaPista[ReservaPista::COLUMNA_PAGADOJUGADOR2] = 0;
                $ReservaPista[$ReservaPista::COLUMNA_FECHAPAGOJUGADOR2] = "";
                $ReservaPista->guardar();
            }
        }

        //JMAM: Jugador3
        if ($pagadoJugador3 == 1){
            $esPagadoConMonedero = Monedero::pagarConMonedero($idJugador3, $ReservaPista->obtenerId(), $importePagoJugador3);
            if ($esPagadoConMonedero == false){
                $ReservaPista = new ReservaPista($id);
                $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR3] = $importePagoJugador3;
                $ReservaPista[ReservaPista::COLUMNA_TIPOPAGOJUGADOR3] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[ReservaPista::COLUMNA_PAGADOJUGADOR3] = 1;
                $ReservaPista[$ReservaPista::COLUMNA_FECHAPAGOJUGADOR3] = $fechaHoy;
                $ReservaPista->guardar();
            }
        }
        else{
            $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador3, $ReservaPista->obtenerId());

            if ($esDevueltoPagoMonedero == false){
                $ReservaPista = new ReservaPista($id);
                $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR3] = $importePagoJugador3;
                $ReservaPista[ReservaPista::COLUMNA_TIPOPAGOJUGADOR3] = "";
                $ReservaPista[ReservaPista::COLUMNA_PAGADOJUGADOR3] = 0;
                $ReservaPista[$ReservaPista::COLUMNA_FECHAPAGOJUGADOR3] = "";
                $ReservaPista->guardar();
            }
        }

        //JMAM: Jugador4
        if ($pagadoJugador4 == 1){
            $esPagadoConMonedero = Monedero::pagarConMonedero($idJugador4, $ReservaPista->obtenerId(), $importePagoJugador4);
            if ($esPagadoConMonedero == false){
                $ReservaPista = new ReservaPista($id);
                $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR4] = $importePagoJugador4;
                $ReservaPista[ReservaPista::COLUMNA_TIPOPAGOJUGADOR4] = ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA;
                $ReservaPista[ReservaPista::COLUMNA_PAGADOJUGADOR4] = 1;
                $ReservaPista[$ReservaPista::COLUMNA_FECHAPAGOJUGADOR4] = $fechaHoy;
                $ReservaPista->guardar();
            }
        }
        else{
            $esDevueltoPagoMonedero = Monedero::devolverPagoMonedero($idJugador4, $ReservaPista->obtenerId());

            if ($esDevueltoPagoMonedero == false){
                $ReservaPista = new ReservaPista($id);
                $ReservaPista[ReservaPista::COLUMNA_IMPORTEPAGOJUGADOR4] = $importePagoJugador4;
                $ReservaPista[ReservaPista::COLUMNA_TIPOPAGOJUGADOR4] = "";
                $ReservaPista[ReservaPista::COLUMNA_PAGADOJUGADOR4] = 0;
                $ReservaPista[$ReservaPista::COLUMNA_FECHAPAGOJUGADOR4] = "";
                $ReservaPista->guardar();
            }
        }
            */


        $Partido = new Partido($idPartido);
        $Partido["Fecha"] = $fechaReserva;
        $Partido["Hora"]  = $horaInicioReserva;
        $Partido->guardar();

        echo 1;

        break;

    case "eliminar":

        $ReservaPista =  new ReservaPista($id);

        if ($borrarGrupoReserva == 1){
            $array_idsReservasPistaEliminar  = $ReservaPista->obtenerIdsReservasIdGrupoReserva();
        }
        else{
            $array_idsReservasPistaEliminar[] = $id;
        }

        $bool_existenReservasQueNoSePuedenEliminar = false;
        foreach ($array_idsReservasPistaEliminar as $idReservaPistaEliminar){
            $ReservaPista = new ReservaPista($idReservaPistaEliminar);
            $Partido = $ReservaPista->obtenerPartido();
            $nombreReservaPista = $ReservaPista->obtenerNombreDescriptivo();

            $strotime_fechaHoy = strtotime(date("Y-m-d"));
            $strotime_fechaInicioReserva = strtotime($ReservaPista->obtenerFechaReserva());




            /*
            if ($strotime_fechaInicioReserva < $strotime_fechaHoy){
                $bool_existenReservasQueNoSePuedenEliminar = false;
                //echo Traductor::traducir("<br/>- Fecha anterior a Hoy:")." ".$nombreReservaPista;
            }
            */
            if ($ReservaPista->obtenerNumeroJugadoresPagado() > 0){
                echo Traductor::traducir("<br/>- Existen Pagos:")." ".$nombreReservaPista;
                $bool_existenReservasQueNoSePuedenEliminar = true;
            }
            else{
                //JMAM: Comprueba si la Reserva tiene un partido, para evitar errores
                if ($Partido->obtenerId() > 0){
                    //JMAM: Guarda el Partido a borrar en la tabla borrados y lo elimina
                    $Partido->guardar(null, PartidoEliminado::TABLA_nombre);

                    if ($realizadoPor == "PCU"){
                        if (!empty($Partido->obtenerJugador1()->obtenerId())){
                            RegistroActividad::anadirEnRegistroActividad(RegistroActividad::ACTIVIDAD_eliminacionPartidoDesdePCU, $Partido->obtenerJugador1()->obtenerId(), $Partido->obtenerIdLiga(), $Partido->obtenerId());
                        }

                        if (!empty($Partido->obtenerJugador2()->obtenerId())){
                            RegistroActividad::anadirEnRegistroActividad(RegistroActividad::ACTIVIDAD_eliminacionPartidoDesdePCU, $Partido->obtenerJugador2()->obtenerId(), $Partido->obtenerIdLiga(), $Partido->obtenerId());
                        }

                        if (!empty($Partido->obtenerJugador3()->obtenerId())){
                            RegistroActividad::anadirEnRegistroActividad(RegistroActividad::ACTIVIDAD_eliminacionPartidoDesdePCU, $Partido->obtenerJugador3()->obtenerId(), $Partido->obtenerIdLiga(), $Partido->obtenerId());
                        }

                        if (!empty($Partido->obtenerJugador4()->obtenerId())){
                            RegistroActividad::anadirEnRegistroActividad(RegistroActividad::ACTIVIDAD_eliminacionPartidoDesdePCU, $Partido->obtenerJugador4()->obtenerId(), $Partido->obtenerIdLiga(), $Partido->obtenerId());
                        }
                    }

                    $Partido->eliminar();


                }

                //JMAM: Guarda la Reserva a borrar en la tabla borrados y la elimina
                $ReservaPista->guardar(null, ReservaPistaEliminado::TABLA_nombre);
                $ReservaPista->eliminar();
            }

        }

        if ($bool_existenReservasQueNoSePuedenEliminar == false){
            echo 1;
        }

        break;

    case "imprimirSelectorPistasDisponibles":
        //$fechaMYQL = cambiaf_a_mysql($fecha);
        $fechaMYQL = $fecha;
        echo ReservaPista::imprimirSelectorPistasDisponibles($idCampo, $fechaMYQL, $hora);
        break;

    case "imprimirSelectorTiemposReservaDisponibles":
        //$fechaMYQL = cambiaf_a_mysql($fecha);

        $fechaMYQL = $fecha;

        if ($idReservaPista > 0){
            $ReservaPista = new ReservaPista($idReservaPista);
            $duracion = $ReservaPista->obtenerDuracion(true);
        }

        if ($valorSeleccionadoEnMinutos == -1){
            $duracion = -1;
        }


        Log::v(__FUNCTION__, "Duración: $duracion", false);

        echo ReservaPista::imprimirSelectorTiemposReservaDisponibles($idPista, $fechaMYQL, $hora, $onchange, $duracion, $ignorarHoraPistaBloqueada);
        break;



    case ReservaPista::OP_imprimirTablaReservasPistas:

        $conRedis = true;
        if ($recargarCache == 1){
            $conRedis = false;
            //ReservaPista::eliminarCacheRedisTablaReservaParaElCampoYFecha($idCampo, $fechaMYQL);
            CacheTablaReserva::eliminarCacheRedisIdCampoYFecha($idCampo, $fechaMYSQL);
        }
        Sesion::guardarValorSesion(Sesion::SESION_idDeporte, $idDeporte);

        echo ReservaPista::imprimirTablaReservasPistas($idCampo, $fechaMYQL, $modoAdministrador, $modoInvitado, $idDeporte, $conRedis);
        break;

    case "imprimirTbodyTablaReservasPistasDelCampo":

        $conRedis = true;
        if ($recargarCache == 1){
            $conRedis = false;
            CacheTablaReserva::eliminarCacheRedisIdCampoYFecha($idCampo, $fechaMYSQL);
            //ReservaPista::eliminarCacheRedisTablaReservaParaElCampoYFecha($idCampo, $fechaMYQL);
        }

        echo ReservaPista::imprimirTbodyTablaReservasPistasDelCampo($idCampo, $fechaMYQL, 0, $modoAdministrador, false, $modoInvitado, $idDeporte);

        break;

    case "generarBotonPagoReservaPista":
        echo ReservaPista::generarFormularioInvisiblePagoReservaPista($_SESSION['S_id_usuario'], $idTarjeta, $importe, $numerPedido);
        break;

    case "obtenerPrecioPorGrupoJugador":
        if ($idJugador > 0 && $idLiga > 0 && $idCampo > 0 && $idTipoReserva > 0 && $idTiempoReserva > 0){

            $TiempoReserva = new TiempoReserva($idTiempoReserva);
            $Campo = new Campo($idCampo);
            $idClub = $Campo->obtenerClub()->obtenerId();
            $ConfiguracionReservaPistas = $Campo->obtenerConfiguracionReservaPistas();

            if ($tipoJugador != "externo"){

                $Jugador = new Jugador($idJugador);
                $GrupoJugador = $Jugador->obtenerGrupoJugador($idLiga);

                Log::v(__FUNCTION__, "GRUPO JUGADOR en procesar.php: (".$GrupoJugador->obtenerId().")", false);



                if ($idTipoReserva == $ConfiguracionReservaPistas->obtenerIdTipoReservaSegundoMonedero()){
                    $importeJugadorMonedero = $Jugador->obtenerImporteTotalJugadorEnIdMonederoEnIdClub($idClub, Monedero::IDMONEDERO_segundoMonedero);
                    $importeReserva = $TiempoReserva->obtenerPrecioPorGrupoJugador(GrupoJugador::ID_segundoMonedero, $descuento, $GrupoJugador->obtenerId());

                }
                else{
                    $importeJugadorMonedero = $Jugador->obtenerImporteTotalJugadorEnIdMonederoEnIdClub($idClub, Monedero::IDMONEDERO_primerMonedero);
                    $importeReserva = $TiempoReserva->obtenerPrecioPorGrupoJugador(GrupoJugador::ID_MONEDERO, $descuento, $GrupoJugador->obtenerId());

                }

                //JMAM: Repartir el importe de la reserva si procede
                if ($repartirImporte == 1){
                    $importeReservaRepartido = $importeReserva / $numeroJugadores;
                    Log::v(__FUNCTION__, "Importe reserva Repartido: ".$importeReservaRepartido, false);
                }
                else{
                    $importeReservaRepartido = $importeReserva;
                }

                //JMAM: Comprueba si se requiere comprobar si es posible pago con Monedero
                if ($prioridadMonedero == 1 && ($importeJugadorMonedero >= $importeReservaRepartido) && $importeJugadorMonedero != 0){
                    echo $importeReserva;
                }
                else{
                    echo $importeReserva = $TiempoReserva->obtenerPrecioPorGrupoJugador($GrupoJugador->obtenerId(), $descuento, $GrupoJugador->obtenerId());
                }


            }
            else{
                echo $importeReserva = $TiempoReserva->obtenerPrecioPorGrupoJugador(GrupoJugador::ID_GENERAL, $descuento);
            }

        }
        else{
            //JMAM: Calculo precio personalizado
            if (!empty($idReservaPista)){
                $ReservaPista = new ReservaPista($idReservaPista);

                $importePagoJugador = $ReservaPista->obtenerImportePagoJugador($idJugador);
                Log::v(__FUNCTION__, "Importe Pago Jugador: $importePagoJugador", false);

                if (!empty($importePagoJugador)){


                    //JMAM: Repartir el importe de la reserva si procede
                    if ($repartirImporte == 1){
                        $importeReservaRepartido = $importePagoJugador * $numeroJugadores;
                    }
                    else{
                        $importeReservaRepartido = $importePagoJugador;
                    }

                    echo $importeReservaRepartido;
                    return;
                }
            }
            else{
                echo 0;
            }
        }
        breaK;

    case "obtenerPrecioPorGrupoJugadorTodosNew":
        // echo 'ntra';
        // var_dump($array_jugadores);
        // var_dump($idLiga);
        // var_dump($idCampo);
        // die();
        $devolver_array=array();
        foreach ($array_jugadores as $kA => $vA) {
            $idJugador=$vA["idJugador"];
            $tipoJugador=$vA["tipoJugador"];
            $devolver_array[$idJugador]="";
            if ($idJugador > 0 && $idLiga > 0 && $idCampo > 0 && $idTipoReserva > 0 && $idTiempoReserva > 0){

                $TiempoReserva = new TiempoReserva($idTiempoReserva);
                $Campo = new Campo($idCampo);
                $idClub = $Campo->obtenerClub()->obtenerId();
                $ConfiguracionReservaPistas = $Campo->obtenerConfiguracionReservaPistas();

                if ($tipoJugador != "externo"){

                    $Jugador = new Jugador($idJugador);
                    $GrupoJugador = $Jugador->obtenerGrupoJugador($idLiga);

                    // Log::v(__FUNCTION__, "GRUPO JUGADOR en procesar.php: (".$GrupoJugador->obtenerId().")", false);



                    if ($idTipoReserva == $ConfiguracionReservaPistas->obtenerIdTipoReservaSegundoMonedero()){
                        $importeJugadorMonedero = $Jugador->obtenerImporteTotalJugadorEnIdMonederoEnIdClub($idClub, Monedero::IDMONEDERO_segundoMonedero);
                        $importeReserva = $TiempoReserva->obtenerPrecioPorGrupoJugador(GrupoJugador::ID_segundoMonedero, $descuento, $GrupoJugador->obtenerId());

                    }
                    else{
                        $importeJugadorMonedero = $Jugador->obtenerImporteTotalJugadorEnIdMonederoEnIdClub($idClub, Monedero::IDMONEDERO_primerMonedero);
                        $importeReserva = $TiempoReserva->obtenerPrecioPorGrupoJugador(GrupoJugador::ID_MONEDERO, $descuento, $GrupoJugador->obtenerId());

                    }

                    //JMAM: Repartir el importe de la reserva si procede
                    if ($repartirImporte == 1){
                        $importeReservaRepartido = $importeReserva / $numeroJugadores;
                        Log::v(__FUNCTION__, "Importe reserva Repartido: ".$importeReservaRepartido, false);
                    }
                    else{
                        $importeReservaRepartido = $importeReserva;
                    }

                    //JMAM: Comprueba si se requiere comprobar si es posible pago con Monedero
                    if ($prioridadMonedero == 1 && ($importeJugadorMonedero >= $importeReservaRepartido) && $importeJugadorMonedero != 0){
                        $devolver_array[$idJugador]=$importeReserva;
                    }
                    else{
                        $devolver_array[$idJugador]=$TiempoReserva->obtenerPrecioPorGrupoJugador($GrupoJugador->obtenerId(), $descuento, $GrupoJugador->obtenerId());
                    }


                }
                else{
                    $devolver_array[$idJugador]=$TiempoReserva->obtenerPrecioPorGrupoJugador(GrupoJugador::ID_GENERAL, $descuento);
                }

            }
            else{
                //JMAM: Calculo precio personalizado
                if (!empty($idReservaPista)){
                    $ReservaPista = new ReservaPista($idReservaPista);

                    $importePagoJugador = $ReservaPista->obtenerImportePagoJugador($idJugador);
                    Log::v(__FUNCTION__, "Importe Pago Jugador: $importePagoJugador", false);

                    if (!empty($importePagoJugador)){


                        //JMAM: Repartir el importe de la reserva si procede
                        if ($repartirImporte == 1){
                            $importeReservaRepartido = $importePagoJugador * $numeroJugadores;
                        }
                        else{
                            $importeReservaRepartido = $importePagoJugador;
                        }

                        $devolver_array[$idJugador]=$importeReservaRepartido;
                        // return;
                    }
                }
                else{
                    $devolver_array[$idJugador]=0;
                }
            }
        }
        echo json_encode($devolver_array);
        break;
    case "imprimirSelectorHorasDisponiblesAbrirPartido":
        echo ReservaPista::imprimirSelectorHorasDisponiblesAbrirPartido($idCampo, $fechaMYSQL, $hora, $idReservaPistaIgnorar);
        break;

    case "imprimirSelectorHorasAbrirPartido":
        echo ReservaPista::imprimirSelectorHorasAbrirPartido($idCampo, $fechaMYSQL, $hora);
        break;

    case "imprimirSelectorMinutosAbrirPartido":
        echo ReservaPista::imprimirSelectorMinutosAbrirPartido($idCampo, $fechaMYSQL, $hora, $minutos);
        break;

    case "imprimirSelectorPistasDisponiblesAbrirPartido":
        echo ReservaPista::imprimirSelectorPistasDisponiblesAbrirPartido($idCampo, $fechaMYSQL, $hora, $idPistaSeleccionado, $idReservaPistaIgnorar);
        break;

    case "imprimirSelectorPistasAbrirPartido":
        echo ReservaPista::imprimirSelectorPistasDisponiblesAbrirPartido($idCampo, $fechaMYSQL, $hora, $idPistaSeleccionado);
        break;

    case "imprimirSelectorTiemposReservaDisponiblesAbrirPartido":

        if ($todasLasPistas == true){
            echo ReservaPista::imprimirSelectorTiemposReservaDisponiblesAbrirPartido($idPista, $fechaMYSQL, $hora, $idCampo, "onchange_tiempoReserva(this.value)", $idTiempoReservaSeleccionado, $idReservaPistaIgnorar);
        }
        else{
            echo ReservaPista::imprimirSelectorTiemposReservaDisponiblesAbrirPartido($idPista, $fechaMYSQL, $hora);
        }
        break;


    case "imprimirTablaPagosReservas":

        ReservaPista::imprimirTablaPagosReservas($idJugador, $tipoPagoJugadorReserva, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, $esPagado, $esSuperiorACero);
        break;

    case "obtenerImportesTotalReservasLiga":


        $Liga = new Liga($idLiga);
        $array_Jugadores = $Liga->obtenerJugadores();

        $importeLigaPendienteMonedero = 0;
        $importeLigaPagadoMonedero = 0;
        $importeLigaTotalMonedero = 0;

        $importeLigaPendienteEnPista = 0;
        $importeLigaPagadoEnPista = 0;
        $importeLigaTotalEnPista = 0;

        $importeLigaPendienteMonedero = ReservaPista::obtenerImporteReservasTotalPagoLiga(ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO, $idClub, $idLiga, 0);
        $importeLigaPagadoMonedero = ReservaPista::obtenerImporteReservasTotalPagoLiga(ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO, $idClub, $idLiga, 1);
        $importeLigaTotalMonedero = $importeLigaPendienteMonedero+$importeLigaPagadoMonedero;

        $importeLigaPendienteEnPista = ReservaPista::obtenerImporteReservasTotalPagoLiga(ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA, $idClub, $idLiga,0);
        $importeLigaPagadoEnPista = ReservaPista::obtenerImporteReservasTotalPagoLiga(ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA, $idClub, $idLiga,1);
        $importeLigaTotalEnPista = $importeLigaPendienteEnPista+$importeLigaPagadoEnPista;

        $importeTotalPendienteEnReservas = $importeLigaPendienteEnPista + $importeLigaPendienteMonedero;
        $importeTotalPagadoEnReservas = $importeLigaPagadoEnPista + $importeLigaPagadoMonedero;
        $importeTotalEnReservas = $importeTotalPendienteEnReservas + $importeTotalPagadoEnReservas;


        echo json_encode(
            array(
                "importeLigaPendienteMonedero" => $importeLigaPendienteMonedero,
                "importeLigaPagadoMonedero" => $importeLigaPagadoMonedero,
                "importeLigaTotalMonedero" => $importeLigaTotalMonedero,
                "importeLigaPendienteEnPista" => $importeLigaPendienteEnPista,
                "importeLigaPagadoEnPista" => $importeLigaPagadoEnPista,
                "importeLigaTotalEnPista" => $importeLigaTotalEnPista,
                "importeTotalPendienteEnReservas" => $importeTotalPendienteEnReservas,
                "importeTotalPagadoEnReservas" => $importeTotalPagadoEnReservas,
                "importeTotalEnReservas" => $importeTotalEnReservas
            ));

        break;

    case "obtenerImportesTotalesReservaJugador":


        $importePendienteMonedero = ReservaPista::obtenerImporteReservasTotalPagoJugador($idJugador, ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, 0);
        $importePagadoMonedero = ReservaPista::obtenerImporteReservasTotalPagoJugador($idJugador, ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, 1);
        $importeTotalMonedero = $importePendienteMonedero+$importePagadoMonedero;

        $importePendienteEnPista = ReservaPista::obtenerImporteReservasTotalPagoJugador($idJugador, ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, 0);
        $importePagadoEnPista = ReservaPista::obtenerImporteReservasTotalPagoJugador($idJugador, ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, 1);
        $importeTotalEnPista = $importePendienteEnPista+$importePagadoEnPista;

        $importeTotalPendienteEnReservas = $importePendienteEnPista + $importePendienteMonedero;
        $importeTotalPagadoEnReservas = $importePagadoEnPista + $importePagadoMonedero;
        $importeTotalEnReservas = $importeTotalPendienteEnReservas + $importeTotalPagadoEnReservas;

        echo json_encode(
                array(
                    "importePendienteMonedero" => $importePendienteMonedero,
                    "importePagadoMonedero" => $importePagadoMonedero,
                    "importeTotalMonedero" => $importeTotalMonedero,
                    "importePendienteEnPista" => $importePendienteEnPista,
                    "importePagadoEnPista" => $importePagadoEnPista,
                    "importeTotalEnPista" => $importeTotalEnPista,
                    "importeTotalPendienteEnReservas" => $importeTotalPendienteEnReservas,
                    "importeTotalPagadoEnReservas" => $importeTotalPagadoEnReservas,
                    "importeTotalEnReservas" => $importeTotalEnReservas
                ));

        break;

    case "establecerPagadasTodasLasReservas":

        $array_idsReservasPista = ReservaPista::obtenerIdsReservasPagoJugador($idJugador, $tipoPagoJugadorReserva, $fechaInicio, $fechaFin, $ano, $idClub, $idLiga, $idCampo, $idPista, 0);

        foreach ($array_idsReservasPista as $idReservaPista){
            $ReservaPista = new ReservaPista($idReservaPista);

            switch ($tipoPagoJugadorReserva){

                case ReservaPista::TIPOPAGOJUGADORRESERVA_ENPISTA:
                    $ReservaPista->registrarPagoEnPista($idJugador, $ReservaPista->obtenerImportePagoJugador($idJugador));
                    break;

                case ReservaPista::TIPOPAGOJUGADORRESERVA_MONEDERO:
                    $ReservaPista->registrarPagoMonedero($idJugador, $ReservaPista->obtenerImportePagoJugador($idJugador));
                    break;
            }

        }

        break;


    case "pagarDevolverImporteReservaJugador":
        $ReservaPista = new ReservaPista($idReservaPista);
        echo $ReservaPista->pagarDevolverImporteJugador($idJugador, $tipoPago, $pagado);
        break;

    case "puedeJugadorPagarReservaConMonedero":
        $ReservaPista = new ReservaPista($idReservaPista);
        echo $ReservaPista->puedeJugadorPagarConMonedero($idJugador);
        break;

    case "imprimirTablaJugadoresModalReservaPista":
        $ReservaPista = new ReservaPista($idReservaPista);
        echo $ReservaPista->imprimirTablaJugadoresModalReservaPista();
        break;
}


function cambiaf_a_mysql($fecha)
{
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
    return $lafecha;
};