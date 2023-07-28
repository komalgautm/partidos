<?php

class Pista extends general
{
    const NOMBRE_TABLA = "pistas";
    const COLUMNA_id = "id";
    const COLUMNA_PERMITIR2JUGADORES = "permitir2Jugadores";
    const COLUMNA_PERMITIR3JUGADORES = "permitir3Jugadores";
    const COLUMNA_PERMITIR4JUGADORES = "permitir4Jugadores";
    const COLUMNA_numeroJugadoresMaximo = "numeroJugadoresMaximo";
    const COLUMNA_idCampo = "idCampo";
    const COLUMNA_idDeporte = "idDeporte";
    const COLUMNA_nombreImagen = "urlImagen";
    const COLUMNA_colorResaltado = "colorResaltado";
    const COLUMNA_nombreImagenPatrocinador = "nombreImagenPatrocinador";
    const COLUMNA_urlPatrocinador = "urlPatrocinador";
    const COLUMNA_desactivado = "desactivado";

    const IMPRIMIRTODASLASPISTAS_FORMATO_LISTADO = "LISTADO";
    const IMPRIMIRTODASLASPISTAS_FORMATO_BOTONES = "BOTONES";
    const IMPRIMIRTODASLASPISTAS_FORMATO_CABECERATABLA = "CABECERA_TABLA";
    const IMPRIMIRTODASLASPISTAS_FORMATO_SELECTOR = "SELECTOR";
    const IMPRIMIRTODASLASPISTAS_FORMATO_CHECKBOX = "CHECKBOX";
    const IMPRIMIRTODASLASPISTAS_FORMATO_LISTADO_BOTONES = "LISTADOS_BOTONES";
    const IMPRIMIRTODASLASPISTAS_FORMATO_LISTADO_BOTONES_HORARIO = "LISTADO_BOTONES_HORARIO";

    const BASE_IMAGENES = "imagenes/";

    const PORDEFECTO_numeroJugadoresMaximo = 4;
    const PORDEFECTO_idDeporte = 1;

    const OP_obtenerIdDeporte = "obtenerIdDeporte";
    const OP_obtenerNumeroJugadoresMaximo = "obtenerNumeroJugadoresMaximo";
    const OP_obtenerInformacionEsPrecioPorJugador = "obtenerInformacionEsPrecioPorJugador";


    function Pista($id)
    {
        if ($id != '')
            parent::__construct(self::NOMBRE_TABLA, self::COLUMNA_id, $id);
        else
            parent::__construct(self::NOMBRE_TABLA, '', '');
    }

    function obtenerColorResaltado(){
        return $this[self::COLUMNA_colorResaltado];
    }

    function obtenerNombreImagenPatrocinador(){
        return $this[self::COLUMNA_nombreImagenPatrocinador];
    }

    function obtenerUrlImagenPatrocinador(){
        $nombreImagenPatrocinador = $this->obtenerNombreImagenPatrocinador();

        if ($nombreImagenPatrocinador){
            return WWWBASE.'PCU/fotos/'.$nombreImagenPatrocinador;
        }
        else{
            return WWWBASE.'PCU/fotos/SinFoto.gif';
        }
    }

    function obtenerUrlPatrocinador(){
        return $this[self::COLUMNA_urlPatrocinador];
    }

    static function obtenerIds($idCampo = "", $desactivado=0, $idDeporte=""){

        $array_idsPistas = array();

        $array_Pistas = self::obtenerTodos($idCampo, $desactivado, $idDeporte);
        foreach ($array_Pistas as $Pista){
            $array_idsPistas[] = $Pista->obtenerId();
        }

        return $array_idsPistas;

    }

    static function obtenerTodos($idCampo = "", $desactivado=0, $idDeporte=""){
        global $bd;

        $where["AND"][self::COLUMNA_desactivado] = $desactivado;

        if (!empty($idCampo)){
            $where["AND"][self::COLUMNA_idCampo] = $idCampo;
        }

        if (!empty($idDeporte)){
            $where["AND"][self::COLUMNA_idDeporte] = $idDeporte;
        }

        $where["ORDER"] = self::COLUMNA_id." ASC";

        $ids =  $bd->select(self::NOMBRE_TABLA, self::COLUMNA_id, $where);

        $array = [];
        foreach ($ids as $id) {
            $array[] = new Pista($id);
        }
        return $array;
    }

    static function obtenerIdsPistasDelIdCampo($idCampo){
        global $bd;

        return $bd->select(self::NOMBRE_TABLA, self::COLUMNA_id, array(self::COLUMNA_idCampo => $idCampo));
    }

    static function imprimirTodasLasPistas($formato = "", $idCampo, $fechaHoy="", $opcionSeleccionada="", $disabled = false, $multiple=false, $onchange="", $idDeporte=""){

        if ($disabled){
            $disabled = "disabled";
        }
        else{
            $disabled = "";
        }

        if ($multiple){
            $multiple = "multiple";
        }
        else{
            $multiple = "";
        }

        if ($onchange != ""){
            $onchange = "onchange='$onchange'";
        }

        switch ($formato){


            case self::IMPRIMIRTODASLASPISTAS_FORMATO_LISTADO:
                $array_Pistas = self::obtenerTodos($idCampo, false, $idDeporte);

                echo "<ul class='list-group list-group-flush text-center'>";
                foreach ($array_Pistas as $Pista){
                    $nombre = $Pista->obtenerNombre();
                    echo "<li class='list-group-item'>$nombre</li>";
                }
                echo "</ul>";
                break;



            case self::IMPRIMIRTODASLASPISTAS_FORMATO_BOTONES:
                $array_Pistas = self::obtenerTodos($idCampo, false, $idDeporte);

                echo '
                    <div class="row">
                        <div id="botonesPistas" class="btn-group btn-group-lg col-md-12" role="group">
                    ';
                foreach ($array_Pistas as $Pista){

                    $id = $Pista->obtenerId();
                    $nombre = $Pista->obtenerNombre();

                    ?>
                    <button type="button" class="btn btn-primary" value="<?php echo $id;?>"><?php echo $nombre;?></button>
                    <?php
                }


                echo "
                        </div>
                    </div>
                    ";
                break;


            case self::IMPRIMIRTODASLASPISTAS_FORMATO_CABECERATABLA:

                $array_colores = array("blue","green","purple","darkorange");
                $array_Pistas = self::obtenerTodos($idCampo, false, $idDeporte);

                echo "
                    <thead id='contenedor_botonesPistas'>
                        <tr>
                            <th class='sticky-top ocultarMovil columna_hora' style='width: 70px; background:white;'>
                               <button class='btn btn-primary px-3' onclick='onclick_limpiarCacheTablaReservas(1);' title='".Traductor::traducir("Limpiar caché de la tabla de reservas")."'><i class='fas fa-redo pr-2' aria-hidden='true'></i></button>
                            </th>
                    ";

                $i = 0;
                foreach ($array_Pistas as $Pista){
                    $urlPatrocinador = $Pista->obtenerUrlPatrocinador();
                    $urlImagenPatrocinador = $Pista->obtenerUrlImagenPatrocinador();

                    if (empty($Pista->obtenerColorResaltado())){
                        $colorResaltado = $array_colores[$i];
                        $i++;

                        if ($i >= sizeof($array_colores)){
                            $i = 0;
                        }
                    }
                    else{
                        $colorResaltado = $Pista->obtenerColorResaltado();
                    }

                    $class_cabeceraPista= "";
                    if (!empty($urlPatrocinador)){
                        $onclick = "onclick=\"window.open('$urlPatrocinador','_blank')\";";
                        $class_cabeceraPista = "urlPatrocinador";
                    }

                    $img_cabeceraPista = "";
                    if (!empty($Pista->obtenerNombreImagenPatrocinador())){
                        $urlImagenPatrocinador = $Pista->obtenerUrlImagenPatrocinador();
                        $img_cabeceraPista = "<img src='$urlImagenPatrocinador'/>";
                        $class_cabeceraPista .= " imagenPatrocinador";
                    }
                    ?>
                    <th class="sticky-top th-lg columna_pista th_columna cabeceraPista <?php echo $class_cabeceraPista; ?>" <?php echo $onclick; ?>
                        scope="col"
                        style=" background: <?php echo $colorResaltado; ?>; color: white; text-align: center;">
                        <div class="contenedor_celdaPista">
                            <div style="width: 100%"><?php echo $Pista->obtenerNombre(); ?></div>
                            <div><?php echo $img_cabeceraPista; ?></div>
                        </div>
                    </th>
                    <?php
                }


                echo "
                        </tr>
                    </thead>
                    ";
                break;

            case self::IMPRIMIRTODASLASPISTAS_FORMATO_SELECTOR:
                $array_Pistas = self::obtenerTodos($idCampo, false, $idDeporte);

                echo "<select id='idPista' name='idsPista[]' $disabled $multiple $onchange>";
                foreach ($array_Pistas as $Pista){
                    $id = $Pista->obtenerId();
                    $nombre = $Pista->obtenerNombre();
                    $nombreDeporte = $Pista->obtenerDeporte()->obtenerNombre();


                    if ($id == $opcionSeleccionada){
                        $selected = "selected";
                    }
                    else{
                        $selected = "";
                    }
                    echo "<option value='$id' $selected>$nombre | [$nombreDeporte]</option>";
                }
                echo "</select>";
                break;

            case self::IMPRIMIRTODASLASPISTAS_FORMATO_CHECKBOX:
                $array_Pistas = self::obtenerTodos($idCampo, false, $idDeporte);

                foreach ($array_Pistas as $Pista){
                    $id = $Pista->obtenerId();
                    $nombre = $Pista->obtenerNombre();

                    if ($id == $opcionSeleccionada){
                        $selected = "selected";
                    }
                    else{
                        $selected = "";
                    }

                    echo "<input id='modal_copiarHorario_idPista_$id' idPista='$id' class='boton_checkbox_idPista' type='checkbox' data-toggle='toggle' data-on='$nombre' data-off='$nombre' data-width='100%'>";
                }

                break;


            case self::IMPRIMIRTODASLASPISTAS_FORMATO_LISTADO_BOTONES:
                $array_Pistas = self::obtenerTodos($idCampo);
                $array_PistasDesactivadas = self::obtenerTodos($idCampo,true, $idDeporte);

                echo "<div id='botonesPistas' class='btn-group-vertical d-flex'>";
                echo "<button type='button' class='btn btn-outline-primary btn-lg active mt-1' value='0'>".Traductor::traducir("AÑADIR PISTA")."</button><hr/>";

                foreach ($array_Pistas as $Pista){
                    $id = $Pista->obtenerId();
                    $nombre = $Pista->obtenerNombre();
                    echo "<button type='button' class='btn btn-outline-primary btn-lg mt-1' value='$id'>$nombre</button>";
                }
                foreach ($array_PistasDesactivadas as $Pista){
                    $id = $Pista->obtenerId();
                    $nombre = $Pista->obtenerNombre();
                    echo "<button type='button' class='btn btn-outline-secondary btn-lg mt-1' value='$id'>$nombre</button>";
                }
                echo "</div>";
                break;


            case self::IMPRIMIRTODASLASPISTAS_FORMATO_LISTADO_BOTONES_HORARIO:
                $array_Pistas = self::obtenerTodos($idCampo, false, $idDeporte);
                $array_PistasDesactivadas = self::obtenerTodos($idCampo,true, $idDeporte);

                echo "<div id='botonesPistas' class='btn-group-vertical d-flex'>";

                foreach ($array_Pistas as $Pista){
                    $id = $Pista->obtenerId();
                    $nombre = $Pista->obtenerNombre();
                    echo "<button type='button' class='btn btn-outline-primary btn-lg mt-1' value='$id'>$nombre</button>";
                }
                foreach ($array_PistasDesactivadas as $Pista){
                    $id = $Pista->obtenerId();
                    $nombre = $Pista->obtenerNombre();
                    echo "<button type='button' class='btn btn-outline-secondary btn-lg mt-1' value='$id'>$nombre</button>";
                }
                echo "</div>";
                break;


            default:
                echo Traductor::traducir("Indica un formato válido.");
                return;
        }



    }


    static function campoTienePistas($idCampo){
        global $bd;

        $numeroPistas = $bd->count(self::NOMBRE_TABLA, array("idCampo" => $idCampo));

        if ($numeroPistas > 0){
            return true;
        }
        else{
            return false;
        }

    }

    static function obtenerPistasDisponibles($idPista, $idCampo, $fechaMYSQL, $hora){

        $Pista = new Pista($idPista);
        $array_Pistas = $Pista->obtenerTodos($idCampo);
        $array_PistasDisponibles = array();

        foreach ($array_Pistas as $Pista){

            //$CacheTablaReserva = CacheTablaReserva::obtenerCacheTablaReserva($idCampo, $Pista->obtenerId(), $fechaMYSQL, $hora);


            if ($Pista->esPistaLibreRedis($fechaMYSQL, $hora)){
                $array_PistasDisponibles[] = $Pista;
            }

        }

        return $array_PistasDisponibles;
    }

    static function obtenerPistas($idCampo, $idClub=0){

        if ($idCampo > 0){
            $array_Pistas = self::obtenerTodos($idCampo);
        }
        else{
            $Club = new Club($idClub);
            $array_Campos = $Club->obtenerCampos();

            $array_Pistas = array();
            foreach ($array_Campos as $Campo){
                $array_Pistas = array_merge($array_Pistas, self::obtenerTodos($Campo->obtenerId()));
            }
        }

        return $array_Pistas;
    }

    static function imprimirSelectorPistas($idCampo, $idClub=0){


        $array_Pistas = self::obtenerPistas($idCampo, $idClub);


        echo "<select id='selector_idPistas' class='form-control' onchange='onchange_selectorPistas()'>";
        echo "<option value='-1'>".Traductor::traducir("TODAS")."</option>";
        foreach ($array_Pistas as $Pista){
            $id = $Pista->obtenerId();
            $nombre = $Pista->obtenerNombre();

            echo "<option value='$id'>$nombre</option>";
        }
        echo "</select>";
    }

    function imprimirFormulario($idCampo, $idioma){


        $tipoCubierta = $this->obtenerTipoCubierta();
        switch ($tipoCubierta){
            case "cubierta":
                $checked_tipoCubierta_cubierta = "checked";
                break;

            case "semicubierta":
                $checked_tipoCubierta_semicubierta = "checked";
                break;

            case "descubierta":
                $checked_tipoCubierta_descubierta = "checked";
                break;

            default:
                $checked_tipoCubierta_cubierta = "checked";
                break;
        }

        $tipoPared = $this->obtenerTipoPared();
        switch ($tipoPared){
            case "cristal":
                $checked_tipoPared_cristal = "checked";
                break;

            case "muro":
                $checked_tipoPared_muro = "checked";
                break;


            default:
                $checked_tipoPared_cristal = "checked";
                break;
        }

        $tipoLocalizacion = $this->obtenerTipoLocalizacion();
        switch ($tipoLocalizacion){
            case "interior":
                $checked_tipoLocalizacion_interior = "checked";
                break;

            case "exterior":
                $checked_tipoLocalizacion_exterior = "checked";
                break;


            default:
                $checked_tipoLocalizacion_interior = "checked";
                break;
        }

        if ($this->esPermitido2Jugadores()){
            $checked_permitido2Jugadores = "checked";
        }

        if ($this->esPermitido3Jugadores()){
            $checked_permitido3Jugadores = "checked";
        }

        if ($this->esPermitido4Jugadores()){
            $checked_permitido4Jugadores = "checked";
        }

        $informacionAdicional = $this->obtenerInformacionAdicional();

        $urlImagen = $this->obtenerUrlImagen();

        if ($this->obtenerId() > 0){

            if ($this->esPistaDesactivada()){
                $style_botonDesactivar = "display:none";
                $style_botonActivar = "display:block";
            }
            else{
                $style_botonDesactivar = "display:block";
                $style_botonActivar = "display:none";
            }

        }
        else{
            $style_botonDesactivar = "display:none";
            $style_botonActivar = "display:none";
        }


        ?>


        <div class="col-md-12 row">

            <div class="col-md-12">
                <h5 for="firstName" class="text-muted cabecera"><?php echo Traductor::traducir("Pista", false, $idioma);?></h5>
            </div>


            <div class="col-md-6 row">

                <div class="col-md-12 mb-3">
                    <h5 for="firstName" class="text-muted subcabecera"><?php echo Traductor::traducir("Nombre o Número de Pista", false, $idioma);?></h5>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="<?php echo Traductor::traducir("Indica el Nombre o Número de Pista",false, $idioma);?>" value="<?php echo $this->obtenerNombre();?>" required>
                </div>

                <div class="col-md-12 mb-3">
                    <h5 for="firstName" class="text-muted subcabecera"><?php echo Traductor::traducir("Características", false, $idioma);?></h5>
                </div>



                <div class="col-md-6">
                    <label class=""><?php echo Traductor::traducir("Tipo Deporte",false, $idioma);?></label>
                    <select class="form-control" id="<?php echo self::COLUMNA_idDeporte;?>" name="<?php echo self::COLUMNA_idDeporte;?>" onchange="onchange_selectorIdDeporte(this.id);">
                        <?php
                        $array_idsDeportes = Deporte::obtenerIds();
                        foreach ($array_idsDeportes as $idDeporte){
                            $Deporte = new Deporte($idDeporte);
                            $nombreDeporte = $Deporte->obtenerNombre();

                            $idDeportePista = $this->obtenerDeporte()->obtenerId();
                            if (empty($idDeportePista)){
                                $idDeportePista = Deporte::ID_padel;
                                Log::v(__FUNCTION__, "ID Deporte: $idDeportePista", true);
                            }


                            ?>
                            <option value='<?php echo $idDeporte;?>' <?php imprimirSelectedOpcionSelector($idDeporte, $idDeportePista);?>><?php echo $nombreDeporte;?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>


                <div class="col-md-6">
                    <label class=""><?php echo Traductor::traducir("Nº Máximo Jugadores",false, $idioma);?></label>
                    <input type="number" class="form-control" id="<?php echo self::COLUMNA_numeroJugadoresMaximo;?>" name="<?php echo self::COLUMNA_numeroJugadoresMaximo;?>" min="<?php echo $this->obtenerNumeroJugadoresMinimoPorsiblePorTipoDeporte();?>" max="<?php echo $this->obtenerNumeroJugadoresMaximoPorsiblePorTipoDeporte();?>" value="<?php echo $this->obtenerNumeroJugadoresMaximo();?>" required>
                </div>


                <div class="col-md-12 mb-3" style="margin-top: 15px">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input id="tipoCubierta_cubierta" name="tipoCubierta" type="radio" class="custom-control-input" value="cubierta" <?php echo $checked_tipoCubierta_cubierta;?>>
                        <label class="custom-control-label" for="tipoCubierta_cubierta"><?php echo Traductor::traducir("Cubierta", false, $idioma);?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input id="tipoCubierta_semicubierta" name="tipoCubierta" type="radio" class="custom-control-input" value="semicubierta" <?php echo $checked_tipoCubierta_semicubierta;?>>
                        <label class="custom-control-label" for="tipoCubierta_semicubierta"><?php echo Traductor::traducir("Semicubierta",false, $idioma);?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input id="tipoCubierta_descubierta" name="tipoCubierta" type="radio" class="custom-control-input" value="descubierta" <?php echo $checked_tipoCubierta_descubierta;?>>
                        <label class="custom-control-label" for="tipoCubierta_descubierta"><?php echo Traductor::traducir("Descubierta", false, $idioma);?></label>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <!--<h5 for="firstName" class="text-muted subcabecera">Tipo de Paredes</h5>-->
                    <div class="custom-control custom-radio custom-control-inline">
                        <input id="tipoPared_cristal" name="tipoPared" type="radio" class="custom-control-input" value="cristal" <?php echo $checked_tipoPared_cristal;?>>
                        <label class="custom-control-label" for="tipoPared_cristal"><?php echo Traductor::traducir("Cristal", false, $idioma);?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input id="tipoPared_muro" name="tipoPared" type="radio" class="custom-control-input" value="muro" <?php echo $checked_tipoPared_muro;?>>
                        <label class="custom-control-label" for="tipoPared_muro"><?php echo Traductor::traducir("Muro", false, $idioma);?></label>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <!--<h5 for="firstName" class="text-muted subcabecera">Tipo de Lozalización</h5>-->
                    <div class="custom-control custom-radio custom-control-inline">
                        <input id="tipoLocalizacion_interior" name="tipoLocalizacion" type="radio" class="custom-control-input" value="interior" <?php echo $checked_tipoLocalizacion_interior;?>>
                        <label class="custom-control-label" for="tipoLocalizacion_interior"><?php echo Traductor::traducir("Interior", false, $idioma);?></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input id="tipoLocalizacion_exterior" name="tipoLocalizacion" type="radio" class="custom-control-input" value="exterior" <?php echo $checked_tipoLocalizacion_exterior;?>>
                        <label class="custom-control-label" for="tipoLocalizacion_exterior"><?php echo Traductor::traducir("Exterior", false, $idioma);?></label>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <!--<h5 for="firstName" class="text-muted subcabecera">Nº Jugadores</h5>-->
                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="permitir2Jugadores" name="permitir2Jugadores" value="1" <?php echo $checked_permitido2Jugadores;?>>
                        <label class="custom-control-label" for="permitir2Jugadores">2 <?php echo Traductor::traducir("Jugadores", false, $idioma);?></label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="permitir3Jugadores" name="permitir3Jugadores" value="1" <?php echo $checked_permitido3Jugadores;?>>
                        <label class="custom-control-label" for="permitir3Jugadores">3 <?php echo Traductor::traducir("Jugadores", false, $idioma);?></label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" id="permitir4Jugadores" name="permitir4Jugadores" value="1" <?php echo $checked_permitido4Jugadores;?>>
                        <label class="custom-control-label" for="permitir4Jugadores">4 <?php echo Traductor::traducir("Jugadores", false, $idioma);?></label>
                    </div>
                </div>

            </div>

            <div class="col-md-6 row">

                <div class="col-md-12">
                    <label class=""><?php echo Traductor::traducir("Añadir Imagen",false, $idioma);?></label>
                    <div class="col-md-12 imgUp">
                        <div class="imagePreview">
                            <img id="vistaPreviaImagenPista" src="<?php echo $this->obtenerUrlImagen();?>"/>
                        </div>
                        <label class="btn btn-primary">
                            <?php echo Traductor::traducir("Seleccionar", false, $idioma);?><input type="file" onchange="onchange_inputFileImagenPista();" name="" id="" class="uploadFile img" value="<?php echo Traductor::traducir("Subir Imagen", false, $idioma);?>" accept='image/*' style="width: 0px;height: 0px;overflow: hidden;">
                        </label>
                        <div class="btn btn-danger col-md-1" style="color: white; position: absolute; top: 0px; right: 0px;" onclick="onclick_eliminarImagenPista();">X</div>
                        <input type="hidden" id="<?php echo $this::COLUMNA_nombreImagen;?>" name="<?php echo $this::COLUMNA_nombreImagen;?>" value="<?php echo $this->obtenerNombreImagen();?>"/>

                    </div>

                <div class="col-md-12 mb-3">
                    <h5 for="firstName" class="text-muted subcabecera"><?php echo Traductor::traducir("Información Adicional", false, $idioma);?></h5>
                    <textarea  class="form-control" name="informacionAdicional"><?php echo $informacionAdicional;?></textarea>
                </div>

            </div>

        </div>


        <div class="col-md-12 row py-5">

            <div class="col-md-12">
                <h5 for="firstName" class="text-muted cabecera"><?php echo Traductor::traducir("Patrocinadores", false, $idioma);?></h5>
            </div>


            <div class="col-md-8 row">



                <div class="col-md-2 form-group">
                    <label class=""><?php echo Traductor::traducir("Color Resaltado",false, $idioma);?></label>
                    <input type="color" class="form-control" id="<?php echo self::COLUMNA_colorResaltado;?>" name="<?php echo self::COLUMNA_colorResaltado;?>" value="<?php echo $this->obtenerColorResaltado();?>">
                </div>

                <div class="col-md-10 form-group">
                    <label class=""><?php echo Traductor::traducir("Url Patrocinador",false, $idioma);?></label>
                    <input type="url" class="form-control" id="<?php echo self::COLUMNA_urlPatrocinador;?>" name="<?php echo self::COLUMNA_urlPatrocinador;?>" value="<?php echo $this->obtenerUrlPatrocinador();?>">
                </div>

            </div>

            <div class="col-md-4 row">
                <label class=""><?php echo Traductor::traducir("Logotipo",false, $idioma);?></label>
                <div class="col-md-12 imgUp">
                <div class="imagePreview">
                    <img id="vistaPreviaImagenPatrocinador" src="<?php echo $this->obtenerUrlImagenPatrocinador();?>"/>
                </div>
                    <label class="btn btn-primary">
                        <?php echo Traductor::traducir("Seleccionar", false, $idioma);?><input type="file" onchange="onchange_inputFileLogotipoPatrocinador();" name="" id="" class="uploadFile img" value="<?php echo Traductor::traducir("Subir Logotipo", false, $idioma);?>" accept='image/*' style="width: 0px;height: 0px;overflow: hidden;">
                    </label>
                    <div class="btn btn-danger col-md-1" style="color: white; position: absolute; top: 0px; right: 0px;" onclick="onclick_eliminarLogotipoPatrocinador();">X</div>
                    <input type="hidden" id="<?php echo $this::COLUMNA_nombreImagenPatrocinador;?>" name="<?php echo $this::COLUMNA_nombreImagenPatrocinador;?>" value="<?php echo $this->obtenerNombreImagenPatrocinador();?>"/>
            </div>
        </div>

        <div class="col-md-12">
            <hr class="mb-4">
            <button id="botonGuardar" class="botonGuardar btn btn-primary btn-lg btn-block" type="submit"><?php echo Traductor::traducir("Guardar", false, $idioma);?></button>
            <br/>
            <button id="botonDesactivar" class="botonEliminar btn btn-outline-danger btn-lg btn-block" style="<?php echo $style_botonDesactivar; ?>" type="button" onclick="onclick_desactivarPista()"><?php echo Traductor::traducir("Desactivar", false, $idioma);?></button>
            <button id="botonDesactivar" class="botonEliminar btn btn-outline-success btn-lg btn-block" style="<?php echo $style_botonActivar; ?>" type="button" onclick="onclick_activarPista()"><?php echo Traductor::traducir("Activar", false, $idioma);?></button>

            <?php
            if (!$this->existenReservasEnPista()){
                ?>
                    <button id="botonEliminar" class="botonEliminar btn btn-danger btn-lg btn-block" style="<?php echo $style_botonActivar; ?>" type="button" onclick="onclick_eliminarPista()"><?php echo Traductor::traducir("Eliminar", false, $idioma);?></button>
                <?php
            }
            ?>


            <input type="hidden" id="idPista" name="id" value="<?php echo $this->obtenerId();?>">
            <input type="hidden" id="idCampo" name="idCampo" value="<?php echo $idCampo;?>">
            <input type="hidden" name="op" value="guardar"/>
        </div>


        <?php

    }



    function obtenerHorariosPistaDisponibles($fechaMYSQL, $hora, $ignorarHoraPistaBloqueada=false, $devolverPrimeroSolo=false){
        return HorarioPista::obtenerHorariosPistaDisponibles($this["id"], $fechaMYSQL, $hora, $ignorarHoraPistaBloqueada, $devolverPrimeroSolo);
    }


    function esPistaDisponibleParaReservarRedis($fechaMYSQL, $hora, $actualizarRedis=false){
        global $Redis;
        $idPista = $this->obtenerId();
        // $keyRedis = WWWBASE."esPistaDisponibleParaReservarRedis($idPista, $fechaMYSQL, $hora)";
        $keyRedis = 'r_es_pista_disponible_reserva_pista_fecha_hora_'.$idPista.'_'.$fechaMYSQL.'_'.$hora;
        Log::v(__FUNCTION__, $keyRedis, false);


        if ($Redis->exists($keyRedis) && REDIS_RESERVAS && !$actualizarRedis){
            Log::v(__FUNCTION__, "Desde Caché Redis", false);
            return unserialize($Redis->get($keyRedis));
        }

        $esPistaReservada =  $this->esPistaDisponibleParaReservar($fechaMYSQL, $hora, true);
        $Redis->set($keyRedis, serialize($esPistaReservada));
        return $esPistaReservada;
    }

    function esPistaDisponibleParaReservar($fechaMYSQL, $hora, $conRedis=false){

        $fechaActual = strtotime(date("d-m-Y H:i:00",time()));
        $fechaReservarPista = strtotime("$fechaMYSQL $hora");

        if($fechaReservarPista > $fechaActual)
        {
            Log::v(__FUNCTION__, "FECHA RESERVA PISTA ($fechaMYSQL) SUPERIOR AL ACTUAL");
            if (ReservaPista::esPistaSinReservar($this->obtenerId(),$fechaMYSQL, $hora, "", $conRedis)){
                return true;
            }
        }

        return false;

    }



    function esPistaLibreRedis($fechaMYSQL, $hora, $actualizarRedis=false){
        global $Redis;
        $hora = date('H:i:s', strtotime($hora));
        $idPista = $this->obtenerId();
        // $keyRedis = WWWBASE."esPistaLibreRedis($idPista, $fechaMYSQL, $hora)";
        $keyRedis = 'r_es_pista_libre_redis_fecha_hora_'.$idPista.'_'.$fechaMYSQL.'_'.$hora;
        Log::v(__FUNCTION__, $keyRedis, false);


        if ($Redis->exists($keyRedis) && REDIS_RESERVAS && !$actualizarRedis){
            Log::v(__FUNCTION__, "Desde Caché Redis", false);
            return unserialize($Redis->get($keyRedis));
        }

        $esPistaReservada =  $this->esPistaLibre($fechaMYSQL, $hora);
        $Redis->set($keyRedis, serialize($esPistaReservada));
        return $esPistaReservada;
    }

    function esPistaLibre($fechaMYSQL, $hora){

        if (count($this->obtenerHorariosPistaDisponibles($fechaMYSQL, $hora, false, true)) > 0){
            Log::v(__FUNCTION__, "Pista LIBRE: ID PISTA:".self::obtenerNombre()." | fechaMYSQL:$fechaMYSQL | hora:$hora");
            return true;
        }
        else{
            return false;
        }
    }

    function esPistaReservadaRedis($fechaMYSQL, $hora, $idReservaIngnorar="", $actualizarRedis=true){
        global $Redis;
        $hora = date('H:i:s', strtotime($hora));

        $idPista = $this->obtenerId();
        $keyRedis = WWWBASE."esPistaReservadaRedis($idPista, $fechaMYSQL, $hora, $idReservaIngnorar)";
        Log::v(__FUNCTION__, $keyRedis, false);

        if ($Redis->exists($keyRedis) && REDIS_RESERVAS && !$actualizarRedis){
            Log::v(__FUNCTION__, "Desde Caché Redis", false);
            return unserialize($Redis->get($keyRedis));
        }


        $resultado =  $this->esPistaReservada($fechaMYSQL, $hora, $idReservaIngnorar);
        $Redis->set($keyRedis, serialize($resultado));
        return $resultado;
    }



    function esPistaReservada($fechaMYSQL, $hora, $idReservaIngnorar=""){

        if (!$this->existenReservasEnPista($fechaMYSQL, $idReservaIngnorar)){
            return false;
        }

        $ReservaPista = $this->obtenerReservaPista($fechaMYSQL, $hora, $idReservaIngnorar, true);
        if ($ReservaPista == null){
            return false;
        }
        else{
            return true;
        }

    }

    function obtenerReservaPistaRedis($fechaMYSQL, $hora, $idReservaIngnorar="", $actualizarRedis=false){
        global $Redis;
        $hora = date('H:i:s', strtotime($hora));
        $idPista = $this->obtenerId();
        $keyRedis = WWWBASE."obtenerReservaPistaRedis($idPista, $fechaMYSQL, $hora, $idReservaIngnorar)";
        Log::v(__FUNCTION__, $keyRedis, false);


        if ($Redis->exists($keyRedis) && REDIS_RESERVAS && !$actualizarRedis){
            Log::v(__FUNCTION__, "Desde Caché Redis", false);
            return unserialize($Redis->get($keyRedis));
        }

        $respuesta =  $this->obtenerReservaPista($fechaMYSQL, $hora, $idReservaIngnorar);
        $Redis->set($keyRedis, serialize($respuesta));
        return $respuesta;
    }

    function obtenerReservaPista($fechaMYSQL, $hora, $idReservaIngnorar="", $devolverPrimeroSolo=false){
        global $bd;
        Log::v(__FUNCTION__, "obtenerReservaPista", false);


        $idPista = $this["id"];

        if ($idReservaIngnorar > 0){
            $condicion_idReservaIgnorar = " AND id != $idReservaIngnorar ";
        }


        $sql_encontrarReservasEnElMismoDia = "SELECT id FROM reservas_pista WHERE idPista = $idPista AND fechaReserva = DATE('$fechaMYSQL') AND (horaInicioReserva <= TIME('$hora') AND IF(horaFinReserva = '00:00:00', '23:59:00', horaFinReserva) > TIME('$hora')) $condicion_idReservaIgnorar";
        $filasReservasEnElMismoDia = $bd->query($sql_encontrarReservasEnElMismoDia)->fetchAll();

        $array_idReservaPistas = array();
        foreach ($filasReservasEnElMismoDia as $filaReservasEnElMismoDia){
            $idReservaPista = $filaReservasEnElMismoDia["id"];
            $array_idReservaPistas[] = $idReservaPista;
            Log::v(__FUNCTION__, "Reserva encontrada: $idReservaPista", false);
            if ($devolverPrimeroSolo){
                return  new ReservaPista($idReservaPista);
            }
        }


        $horaFinMaximoPista = $this->obtenerHorarioFinMaximoPista($fechaMYSQL);
        if (strtotime($horaFinMaximoPista) < strtotime("07:00") && strtotime($horaFinMaximoPista) >= strtotime("23:59")){
            Log::v(__FUNCTION__, "Comprobar en dos días", true);

            $sql_encontrarReservasEnDosDias = "
        SELECT * FROM reservas_pista 
            WHERE 
                idPista = $idPista
            AND
                fechaReserva = DATE('$fechaMYSQL')
            AND 
                (horaInicioReserva > horaFinReserva)
            AND
                (horaFinReserva > TIME('$hora') OR horaInicioReserva <= TIME('$hora')) $condicion_idReservaIgnorar
        ";
            $filasReservasEnDosDias = $bd->query($sql_encontrarReservasEnDosDias)->fetchAll();

            foreach ($filasReservasEnDosDias as $filaReservasEnDosDias){
                $idReservaPista = $filaReservasEnDosDias["id"];

                $bool_yaExisteIdReserva = false;
                foreach ($array_idReservaPistas as $idReservaPistaEnElMismoDia){

                    if ($idReservaPistaEnElMismoDia == $idReservaPista){
                        $bool_yaExisteIdReserva = true;
                    }
                }

                if ($bool_yaExisteIdReserva == false){
                    $array_idReservaPistas[] = $idReservaPista;

                    if ($devolverPrimeroSolo){
                        return  new ReservaPista($idReservaPista);
                    }
                }

            }
        }





        if (count($array_idReservaPistas) > 0){
            return new ReservaPista($array_idReservaPistas[0]);
        }
        else{
            return null;
        }
    }


    function existenReservasEnPista($fechaMYSQL="", $idReservaIgnorar=""){
        return ReservaPista::existenReservasEnPista($this->obtenerId(), $fechaMYSQL, $idReservaIgnorar);
    }

    function obtenerNumeroDeReservasEnPistaRedis($fechaMYSQL, $actualizarRedis=false){
        global $Redis;
        $idPista = $this->obtenerId();
        $keyRedis = WWWBASE."obtenerNumeroDeReservasEnPistaRedis($idPista, $fechaMYSQL)";
        Log::v(__FUNCTION__, $keyRedis, false);


        if ($Redis->exists($keyRedis) && REDIS_RESERVAS && !$actualizarRedis){
            Log::v(__FUNCTION__, "Desde Caché Redis", false);
            return unserialize($Redis->get($keyRedis));
        }


        $resultado =  $this->obtenerNumeroDeReservasEnPista($fechaMYSQL);
        $Redis->set($keyRedis, serialize($resultado));
        return $resultado;
    }

    function obtenerNumeroDeReservasEnPista($fechaMYSQL){
        return ReservaPista::obtenerNumeroDeReservasEnPista($this->obtenerId(), $fechaMYSQL);
    }

    function esPistaSinReservar($fechaMYSQL, $horaInicio, $idReservaIngnorar=""){
        return ReservaPista::esPistaSinReservar($this->obtenerId(), $fechaMYSQL, $horaInicio, $idReservaIngnorar);
    }





    function esPistaReservadaEnTramo ($fechaMYSQL, $horaInicio, $horaFin, $idReservaPistaIgnorar = 0, $conRedis){

        global $bd;


        $idPista = $this["id"];
        $Pista = new Pista($idPista);

        $horaFin = anadirRestarMinutosAHora($horaFin, -30);
        Log::v(__FUNCTION__, "Hora para calcular: $horaFin", false);

        if ($conRedis){
            $esPistaReservadaHoraInicio = $Pista->esPistaReservadaRedis($fechaMYSQL, $horaInicio, $idReservaPistaIgnorar);
            $esPistaReservadaHoraFin = $Pista->esPistaReservadaRedis($fechaMYSQL, $horaFin, $idReservaPistaIgnorar);
        }
        else{
            $esPistaReservadaHoraInicio = $Pista->esPistaReservada($fechaMYSQL, $horaInicio, $idReservaPistaIgnorar);
            $esPistaReservadaHoraFin = $Pista->esPistaReservada($fechaMYSQL, $horaFin, $idReservaPistaIgnorar);
        }



        /*
        $consulta = "SELECT id FROM reservas_pista WHERE 
                                idPista = $idPista AND fechaReserva = DATE('$fechaMYSQL') 
                                AND
                                (
                                    (TIME('$horaInicio') >= horaInicioReserva AND TIME('$horaInicio') < IF(horaFinReserva = '00:00:00', '23:59:00', horaFinReserva))
                                    OR
                                    (TIME('$horaFin') > horaInicioReserva AND TIME('$horaFin') < IF(horaFinReserva = '00:00:00', '23:59:00', horaFinReserva))
                                    OR
                                    (TIME('$horaInicio') <= horaInicioReserva AND TIME('$horaFin') >= IF(horaFinReserva = '00:00:00', '23:59:00', horaFinReserva))
                                )
                                $consultar_igonorarIdReservaPista";

        //echo $consulta;

        $filas = $bd->query($consulta)->fetchAll();
        */

        /*
        $filas = $bd->query("SELECT id FROM reservas_pista WHERE
                                idPista = $idPista AND fechaReserva = DATE('$fechaMYSQL')
                                AND
                                (
                                    (TIME('$horaInicio') >= horaInicioReserva AND TIME('$horaInicio') < horaFinReserva)
                                    OR
                                    (TIME('$horaFin') >= horaInicioReserva AND TIME('$horaFin') < horaFinReserva)
                                    OR
                                    (TIME('$horaInicio') <= horaInicioReserva AND TIME('$horaFin') >= horaFinReserva)
                                )
                                ")->fetchAll();
        */
        if ($esPistaReservadaHoraInicio || $esPistaReservadaHoraFin){
            Log::v(__FUNCTION__,"Pista: ".self::obtenerNombre()." | fechaMYSQL:$fechaMYSQL | horaInicio:$horaInicio | horaFin:$horaFin -> Pista Reservada en tramo");
            return true;
        }
        else{
            return false;
        }
    }

    function obtenerTiemposReservaDisponibles($fechaMYSQL, $hora, $ignorarHoraPistaBloqueada=false){

        $array_TiemposReservaDisponible = array();
        $array_HorariosPista = self::obtenerHorariosPistaDisponibles($fechaMYSQL, $hora, $ignorarHoraPistaBloqueada);

        foreach ($array_HorariosPista as $HorarioPista){
            $array_TiemposReserva = $HorarioPista->obtenerTiemposReservaDisponibles($hora);
            $array_TiemposReservaDisponible = array_merge($array_TiemposReservaDisponible, $array_TiemposReserva);
        }

        return $array_TiemposReservaDisponible;

    }

    function obtenerHorarioInicioMinimoPista($fechaMYSQL){
        $Campo = $this->obtenerCampo();

        return HorarioPista::obtenerHoraInicioMinimoCampoDisponible($Campo->obtenerId(), $fechaMYSQL);
    }

    function obtenerHorarioInicioMinimoPistaPorDiaSemana($diaSemana){
        return HorarioPista::obtenerHoraInicioMinimoPistaPorDiaSemana($this->obtenerId(), $diaSemana);
    }

    function obtenerHorarioFinMaximoPistaPorDiaSemana($diaSemana){
        return HorarioPista::obtenerHoraFinMaximoPistaPorDiaSemana($this->obtenerId(), $diaSemana);
    }

    function obtenerHorarioFinMaximoPista($fechaMYSQL){
        $Campo = $this->obtenerCampo();

        return HorarioPista::obtenerHoraFinMaximoCampoDisponible($Campo->obtenerId(), $fechaMYSQL);
    }

    function obtenerId(){
        return $this["id"];
    }

    function obtenerNombre(){

        return $this["nombre"];
    }

    function obtenerCampo(){
        return new Campo($this[self::COLUMNA_idCampo]);
    }

    function obtenerDeporte(){
        return new Deporte($this[self::COLUMNA_idDeporte]);
    }

    function obtenerNumeroJugadoresMaximo(){

        if ($this->existe()){
            if($this[self::COLUMNA_numeroJugadoresMaximo] > 0){
                return $this[self::COLUMNA_numeroJugadoresMaximo];
            }
            else{
                return self::PORDEFECTO_numeroJugadoresMaximo;
            }
        }
        else{
            return self::PORDEFECTO_numeroJugadoresMaximo;
        }
    }

    function obtenerNumeroJugadoresMinimoPorsiblePorTipoDeporte(){
        if ($this->existe()){
            return $this->obtenerDeporte()->obtenerNumeroMinimoJugadores();
        }
        else{
            return (new Deporte(self::PORDEFECTO_idDeporte))->obtenerNumeroMinimoJugadores();
        }
    }

    function obtenerNumeroJugadoresMaximoPorsiblePorTipoDeporte(){
        if ($this->existe()){
            return $this->obtenerDeporte()->obtenerNumeroMaximoJugadores();
        }
        else{
            return (new Deporte(self::PORDEFECTO_idDeporte))->obtenerNumeroMaximoJugadores();
        }
    }

    function obtenerTipoCubierta(){
        return $this["tipoCubierta"];
    }

    function obtenerTipoPared(){
        return $this["tipoPared"];
    }

    function obtenerTipoLocalizacion(){
        return $this["tipoLocalizacion"];
    }

    function obtenerInformacionAdicional(){
        return $this["informacionAdicional"];
    }

    function esPermitido2Jugadores(){
        $permitido2Jugadores = $this[self::COLUMNA_PERMITIR2JUGADORES];

        if ($permitido2Jugadores == 1){
            return true;
        }
        else{
            return false;
        }
    }

    function esPermitido3Jugadores(){
        $permitido3Jugadores = $this[self::COLUMNA_PERMITIR3JUGADORES];

        if ($permitido3Jugadores == 1){
            return true;
        }
        else{
            return false;
        }
    }

    function esPermitido4Jugadores(){
        $permitido4Jugadores = $this[self::COLUMNA_PERMITIR4JUGADORES];

        if ($permitido4Jugadores == 1 || $permitido4Jugadores == ""){
            return true;
        }
        else{
            return false;
        }
    }

    function esPermitidoSolo4Jugadores(){

        if ($this->esPermitido2Jugadores() || $this->esPermitido3Jugadores()){
            return false;
        }
        else{
            if ($this->esPermitido4Jugadores()){
                return true;
            }
            else{
                return false;
            }

        }
    }


    function obtenerNombreImagen(){
        return $this[self::COLUMNA_nombreImagen];
    }

    function obtenerUrlImagen(){
        $nombreImagenPatrocinador = $this->obtenerNombreImagen();

        if ($nombreImagenPatrocinador){
            return WWWBASE.'PCU/fotos/'.$nombreImagenPatrocinador;
        }
        else{
            return WWWBASE.'PCU/fotos/SinFoto.gif';
        }
    }

    function imprimirSelectorNumeroJugadoresPermitidos($id, $name, $onchange){

        echo "<select id='$id' name='$name' onchange='$onchange'>";


        if ($this->esPermitido2Jugadores()){
            echo "<option value='2' selected>2 ".Traductor::traducir("Jugadores")."</option>";
        }

        if ($this->esPermitido3Jugadores()){
            echo "<option value='3' selected>3 ".Traductor::traducir("Jugadores")."</option>";
        }

        if ($this->esPermitido4Jugadores()){
            echo "<option value='4' selected>4 ".Traductor::traducir("Jugadores")."</option>";
        }

        echo "</select>";
    }

    function obtenerNumeroJugadoresPermitidos(){
        if ($this->esPermitido4Jugadores()){
            return 4;
        }

        if ($this->esPermitido3Jugadores()){
            return 3;
        }

        if ($this->esPermitido2Jugadores()){
            return 2;
        }
    }

    function esPistaDesactivada(){
        $desactivado = $this["desactivado"];

        if ($desactivado == 1){
            return true;
        }
        else{
            return false;
        }
    }

    function existeAlgunHorarioPistaParaLaPistaYDiaSemana($diaSemana){
        return HorarioPista::existeAlgunHorarioPistaParaLaPistaYDiaSemana($this->obtenerId(), $diaSemana);
    }

    function existe(){

        if ($this->obtenerId() > 0){
            return true;
        }

        return false;
    }



}