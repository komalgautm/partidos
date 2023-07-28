<?php
    //Variables de Error
    error_reporting(E_ALL);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    ini_set("display_errors", 1);

    $tiempoInicial = microtime(true);

    include ("ligagolf_PCU.php");
    include ("funciones.php");

    //echo "ID LIGA: ".$_SESSION["S_LIGA_ACTUAL"]."- ".$_SESSION["S_LIGA_SELECCIONADA"];

    //JMAM: Comprueba si se recibe el ID de la Liga Seleccionada
    if (empty($_SESSION["S_LIGA_SELECCIONADA"])){
        //JMAM: NO se recibe ID de la liga seleccionada

        //JMAM: Se le asigna el ID de la liga actual
        $_SESSION["S_LIGA_SELECCIONADA"] = $_SESSION["S_LIGA_ACTUAL"];
    }

    $id_cuadro=0;

    //Nuevo JM 19/Feb/15: redireccion si no hay foto

    if($_GET['menu']=='miagenda')
        redirect_si_no_foto();

    ////////////////////////////////////////////////
    $id_partido_creado=0; //Para envio de push de alta de partidos por JQ
    /*
    ALTER TABLE `partidos` ADD `nivel_min` INT( 2 ) UNSIGNED NOT NULL AFTER `Observaciones` ,
    ADD `nivel_max` INT( 2 ) UNSIGNED NOT NULL AFTER `nivel_min` ,
    ADD `favoritos` VARCHAR( 1 ) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL AFTER `nivel_max` ,
    ADD `invitacion` VARCHAR( 1 ) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL AFTER `favoritos` ;

    nivel_min, nivel_max, favoritos, invitacion
    */
    ////////////////////////////////////////////////


    //Funcion que gestiona la correcion de resultados para version movil, llamada desde misresultadosPARACORREGIR.
    function corregir_resultados_movil()
    {


        mini_cabecera_html();
        Mini_TITULO_CSS ('box1','Corregir','Resultados','','SI');//    TITULO_CSS_FIN ();



    ///Resultado concretos PARTIDOS


        echo "							<ul  id='navtabs' style='text-align:center'>
                                <li><a style='width:32%' href='./partidos.php?menu=misresultadosPARACORREGIR' title='Corregir resultados'  id='current'>Corregir</a></li>
                                <li><a style='width:32%' href='./jugadores.php?menu=resultados&id=".$_SESSION['S_id_usuario']."' title='Mis resultados'>".Traductor::traducir("Mis result.")."</a></li>
                                <li><a style='width:32%' href='./jugadores.php?menu=ResultadosJugador' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Res. Jugador")."</a></li>

                                </ul>

                                ";


        echo ("<div id='filtrocontainer2' class='filtrocontainer2' style='margin-top:0px'><center>Los resultados pueden corregirse en las 24 horas posteriores a su celebraci&oacute;n s&oacute;lo por el jugador que haya grabado ese resultado</center></div>");


        echo "<div class='filtrocontainer2' style='margin-top:0px'> <table width='100%' border =0><tr>";


        $Hoy=date('Y-m-d');
        $Hoymenos2= date("Y-m-d", strtotime("$Hoy -2 days"));

        $arrapar = array();
        $q="SELECT P.id,P.Fecha,P.Hora,P.TipoPuntuacion,P.id_campo,P.Otro_Campo,P.id_Jugador1,P.Puntos_J1,P.id_Jugador2,P.Puntos_J2," .
            "P.id_Jugador3,P.Puntos_J3,P.id_Jugador4,P.Puntos_J4,R.PUNTOS,R.id_jug2,R.id_jug3,R.id_jug4,R.FechaResult,R.HoraResult," .
            "R.Extra1,R.Extra2,R.Extra3,P.id_Jugador_ApuntaResult
    FROM  partidos AS P, resultados AS R
    WHERE P.Fecha<='$Hoy' AND P.Fecha>='$Hoymenos2' AND id_Jugador_ApuntaResult=".$_SESSION['S_id_usuario']." ".
            " and (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." " .
            "OR id_Jugador4=".$_SESSION['S_id_usuario']." )
    AND P.id_liga=".$_SESSION['S_LIGA_ACTUAL']." AND P.id=R.id_partido ORDER BY P.Fecha asc";


        $si=mysql_query($q);
        $count=mysql_num_rows($si);

        if($count>0)
        {
            echo ("<td width='26%' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("Partido")."</td>");


            if 	($_SESSION['S_Version']!='Movil')  echo ("<td width='51%' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("POS")." &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   " .
                "".Traductor::traducir("Jugadores")." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  |  +-".Traductor::traducir("NIVEL")."  |   ".Traductor::traducir("PUNT")."</td>");
            else echo ("<td width='51%' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("POS")." &nbsp;&nbsp;|&nbsp;" .
                "".Traductor::traducir("Jugadores")." &nbsp; |  +-".Traductor::traducir("NIVEL")."  |   ".Traductor::traducir("PUNT")."</td>");
    // echo ("<td width='9%' class='cabecera'><p class='textablacabecera'>+-Hcp</td>");

            echo"</tr><tr><td>";
        }else
        {
            echo "No hay partidos que puedas editar en este momento";
        }



        while (list($id_partido,$P_Fecha,$P_Hora,$P_TipoPuntuacion,$P_id_campo,$P_Otro_Campo,$P_id_Jugador1,$P_Puntos_J1,$P_id_Jugador2,$P_Puntos_J2,$P_id_Jugador3,$P_Puntos_J3,$P_id_Jugador4,$P_Puntos_J4,$PUNTOS,$id_jug2,$id_jug3,$id_jug4,$FechaResult,$HoraResult,$Extra1,$Extra2,$Extra3,$QuienApunta) = mysql_fetch_array($si))
        {
            if(in_array($id_partido,$arrapar)){
                continue;
            }else{
                $arrapar[] = $id_partido;
            }
    ////////////nueva fila
            if ($colortoca!=0){echo ("<tr bgcolor='#FFFFFF'>");$clase='fila';$colortoca=0;}
            else {echo ("<tr bgcolor='#FFFFFF'>");$clase='fila2 fila_listadoPartidos';$colortoca=1;};

    //if (1!='') {echo ("<td class=$clase align=center><p class='dato'><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p></td>");} else {echo ("<td class=$clase> <br> </td>");};

    //Datos del Partido
            echo ("<td class=$clase align=center>");

            if ($P_id_campo) echo("<p class='dato' align=center>".devuelve_un_campo ("campos",2,"id",$P_id_campo)."<br>$P_TipoPuntuacion<br><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p>");
            else echo("<p class='dato' align=center>$P_Otro_Campo<br>$P_TipoPuntuacion<br><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p>

    ");

            echo "<a class='botonancho botontrans' style='float:none;height:20px;width:20px;margin:0 0 0 10px;display:inline-block' href='partidos.php?menu=resultado&id=$id_partido'><img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'style=\'height:20px;width:20px;margin:0px\'')."  src='./images/images_j/editar.png'></a>";
            echo("</td>");




    //if (1!='') {echo ("<td class=$clase><p class='dato'>".htmlentities($id_liga)."</p></td>");} else {echo ("<td class=$clase> <br> </td>");};
    //if (1!='') {echo ("<td class=$clase><p class='dato'>".devuelve_un_campo('jugadores','6','id',$id_jugador)." ".devuelve_un_campo('jugadores','7','id',$id_jugador)."</p></td>");} else {echo ("<td class=$clase> <br> </td>");};

    //if (1!='') {echo ("<td class=$clase align=center><p class='dato'><font size=+2><b>".htmlentities($PUNTOS)."</B></font></p></td>");} else {echo ("<td class=$clase> <br> </td>");};

    //Datos de Jugadores
            echo ("<td class=$clase align=center>");

            if 	($_SESSION['S_Version']!='Movil') echo "<table border=0 width=320 class='dato'>";
            else echo "<table border=0 width=100% class='dato'>";


            $JUG= array ($P_id_Jugador1, $P_id_Jugador2, $P_id_Jugador3, $P_id_Jugador4 );
            $PUNT= array ($P_Puntos_J1, $P_Puntos_J2, $P_Puntos_J3, $P_Puntos_J4 );

            $JUG= array ($P_id_Jugador1=>$P_Puntos_J1, $P_id_Jugador2=>$P_Puntos_J2, $P_id_Jugador3=>$P_Puntos_J3, $P_id_Jugador4 =>$P_Puntos_J4);
            asort($JUG);
    //$JUG[$INDICES[0]] $PUNT[$INDICES[0]]
            $posi=1;

            foreach ($JUG as $key => $val)
            {

                if ($key)
                {
                    if ($key==$id) $color='blue'; else $color='gray';

                    echo("<tr>");
                    echo("<td  width=30><font color=$color><b>".$val.":</b>");
                    if ($key==$QuienApunta)	echo"<img border=0 height=11 title='".Traductor::traducir("Jugador que introdujo el resultado")."' src='./images/miniuser.gif'>";
                    echo"</td>";

                    echo "<td align=center  width=200><a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$key&mostrarTLF=$YaEstoy&boxy=1',{title:'".devuelve_un_campo ("jugadores",6,"id",$key)." ".devuelve_un_campo ("jugadores",7,"id",$key)."'});\"><font color=$color><b>" .
                        "".devuelve_un_campo ("jugadores",6,"id",$key)." ".devuelve_un_campo ("jugadores",7,"id",$key)."</b></font></a></td>" ;
                    //"<td align=right  width=30><font color=$color><b>".$val."</font></b></td>";
                    //else echo "<td align=center>".devuelve_un_campo ("jugadores",6,"id",$key)." ".devuelve_un_campo ("jugadores",7,"id",$key)."</td><td align=right> ".$val."</td>";
                    $posi++;
                    echo"</font></td>";
                    echo "<td  width=40 align=center><font color=$color>".devuelve_un_campo('resultados',10,'id_partido',$id_partido." AND id_jugador =".$key)."</font></td>";
                    echo("<td align=right width=20><font color=$color>".devuelve_un_campo('resultados',4,'id_partido',$id_partido." AND id_jugador =".$key)."</font></td>");
                    echo "</tr>";
                }

            }


            if ($posi==3) echo"<tr><td colspan=3 align=center>----</td></tr><tr><td colspan=3 align=center>----</td></tr>";
            if ($posi==4) echo"<tr><td colspan=3 align=center>----</td></tr>";


            echo"</table>";
            echo("</td>");



    //if (1!='') {echo ("<td class=$clase align=center><p class='dato'><font size=+1>".htmlentities($Extra1)."</font></p></td>");} else {echo ("<td class=$clase> <br> </td>");};
    //if (1!='') {echo ("<td class=$clase><p class='dato'>".htmlentities($Extra2)."</p></td>");} else {echo ("<td class=$clase> <br> </td>");};



    ///////////fin de fila (del bucle)
            echo ("</td>");
            echo ("</tr>");
        }
        echo "</table>";


        if 	($_SESSION['S_Version']=='Movil'){
            echo '</div>';

    //PieMovil (1);

        }




        TITULO_CSS_FIN ();

    }

    //////////////FUNCIONES PARA LA GESTION DE SEXOS EN PARTIDOS DE SEXO DEFINIDO Y MIXTOS////////////////////////////////

    //Para que no pueda borrarse una pareja una vez esta ha confirmado la pareja. Esta variable se usa para pc y movil.
    function posible_modificar_pareja($id_partido,$ant_es_pareja)
    {

        $posible=false;
        if($ant_es_pareja)
        {
            $sqlr = mysql_query("SELECT id_Jugador2 FROM partidos WHERE id=$id_partido") or die("Error partidos-2748: Error al obtener datos de edicion de partidos. Consulte a soporte.");
            $dtsr = mysql_fetch_array($sqlr);
            $id_jugador_2=$dtsr['id_Jugador2'];

            $posible=($id_jugador_2>0);
        }

        return !$posible;
    }

    //Devuelve True o False dependienod de si el partido pasado es de sexo definido
    function es_unisexo($id_partido)
    {
        $sqlr = mysql_query("SELECT Extra2 FROM partidos WHERE id=$id_partido") or die("Error partidos-23: Error al obtener datos de unisexo. Consulte a soporte.");
        $dtsr = mysql_fetch_array($sqlr);
        $sexo=$dtsr['Extra2'];
        if($sexo=='')
            return false;
        else
        {
            return true;
        }
    }

    //Devuelve True o False dependienod de si el partido pasado esta definido como mixto
    function es_mixto($id_partido)
    {

        $sqlr = mysql_query("SELECT count(*) as cnt FROM partidos_mixtos WHERE id=$id_partido") or die("Error partidos-36: Error al obtener datos de mixto. Consulte a soporte.");

        $dtsr = mysql_fetch_array($sqlr);
        $cnt=$dtsr['cnt'];

        if($cnt>0){

            return true;
        }
        else
            return false;

    }
    //Devuelve verdadero o falso en base a si se muestra o no el boton de apuntarse dependiendo de los parametros de entrada
    function evalua_unisexo_mixto($id_jugador_sesion,$id_partido)
    {
        $es_mixto=es_mixto($id_partido);
        $es_unisexo=es_unisexo($id_partido);


        $sqlr = mysql_query("SELECT id_Jugador1,id_Jugador2,id_Jugador3,id_Jugador4 FROM partidos WHERE id=$id_partido") or die("Error partidos-58: Error al obtener datos de unisexo. Consulte a soporte.");
        $dtsr = mysql_fetch_array($sqlr);
        $id_jugador_1=$dtsr['id_Jugador1'];
        $id_jugador_2=$dtsr['id_Jugador2'];
        $id_jugador_3=$dtsr['id_Jugador3'];
        $id_jugador_4=$dtsr['id_Jugador4'];

        //Si soy yo mismo el que esta ya como jugador, desactivamos esta funcion devolviendo true y dejando paso a minificha_jugador.
        if(in_array($id_jugador_sesion,array($id_jugador_1,$id_jugador_2,$id_jugador_3,$id_jugador_4)))
            return true;

        if($es_mixto and $es_unisexo)
        {
            //No hay control para cuando hay 1 jugador apuntado

            //Hay dos jugadores apuntados

            if($id_jugador_1!=0 and $id_jugador_2!=0 and $id_jugador_3==0 and $id_jugador_4==0)
            {
                $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_1") or die("Error partidos-79: Error al obtener datos de mixto. Consulte a soporte.");;
                $dtsr = mysql_fetch_array($sqlr);
                $sexo_jugador_1=$dtsr['Sexo'];

                $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_2") or die("Error partidos-83: Error al obtener datos de mixto. Consulte a soporte.");;
                $dtsr = mysql_fetch_array($sqlr);
                $sexo_jugador_2=$dtsr['Sexo'];
                if($sexo_jugador_1!=$sexo_jugador_2)
                    return mixto($id_jugador_sesion,$id_jugador_1,$id_jugador_2,$id_jugador_3,$id_jugador_4);
                else
                    return true;
            }

            //Hay tres jugadores apuntados
            if($id_jugador_1!=0 and $id_jugador_2!=0 and $id_jugador_3!=0 and $id_jugador_4==0)
            {
                $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_1") or die("Error partidos-95: Error al obtener datos de mixto. Consulte a soporte.");;
                $dtsr = mysql_fetch_array($sqlr);
                $sexo_jugador_1=$dtsr['Sexo'];

                $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_2") or die("Error partidos-99: Error al obtener datos de mixto. Consulte a soporte.");;
                $dtsr = mysql_fetch_array($sqlr);
                $sexo_jugador_2=$dtsr['Sexo'];

                $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_3") or die("Error partidos-103: Error al obtener datos de mixto. Consulte a soporte.");;
                $dtsr = mysql_fetch_array($sqlr);
                $sexo_jugador_3=$dtsr['Sexo'];

                if($sexo_jugador_1!=$sexo_jugador_2 or $sexo_jugador_1!=$sexo_jugador_3 or $sexo_jugador_2!=$sexo_jugador_3)
                    return mixto($id_jugador_sesion,$id_jugador_1,$id_jugador_2,$id_jugador_3,$id_jugador_4);
                else
                    return unisexo($id_jugador_sesion,$id_partido);

            }
        }

        if($es_unisexo and !$es_mixto)
            return unisexo($id_jugador_sesion,$id_partido);

        if(!$es_unisexo and $es_mixto)
            return mixto($id_jugador_sesion,$id_jugador_1,$id_jugador_2,$id_jugador_3,$id_jugador_4);

        return true; //por defecto entonces permitimos el apuntarse.
    }

    //Devuelve verdadero o falso si el jugador de la sesión puede apuntarse al partido,
    //partiendo de que es un partido de sexo definido.
    function unisexo($id_jugador_sesion,$id_partido)
    {
        $sqlr = mysql_query("SELECT id_Jugador1 FROM partidos WHERE id=$id_partido") or die("Error partidos-127: Error al obtener datos de unisexo. Consulte a soporte.");
        $dtsr = mysql_fetch_array($sqlr);
        $id_jugador_1=$dtsr['id_Jugador1'];

        $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_sesion") or die("Error partidos-133: Error al obtener datos de unisexo. Consulte a soporte.");
        $dtsr = mysql_fetch_array($sqlr);
        $sexo_jugador_sesion=$dtsr['Sexo'];

        $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_1") or die("Error partidos-137: Error al obtener datos de unisexo. Consulte a soporte.");;
        $dtsr = mysql_fetch_array($sqlr);
        $sexo_jugador_1=$dtsr['Sexo'];

    //   die("unisexo $id_partido: sexo jsesion($id_jugador_sesion) es $sexo_jugador_sesion y sexo j1($id_jugador_1) es $sexo_jugador_1");

        if($sexo_jugador_sesion==$sexo_jugador_1)
            return true;
        else
        {
    //      die("$id_partido no unisexo");
            return false;
        }
    }

    //Devuelve verdadero o falso si el jugador de la sesión puede apuntarse al partido,
    //partiendo de que es un partido mixto definido.
    function mixto($id_jugador_sesion,$id_jugador_1,$id_jugador_2,$id_jugador_3,$id_jugador_4)
    {
        /*   $sqlr = mysql_query("SELECT id_Jugador1,id_Jugador2,id_Jugador3,id_Jugador4 FROM partidos WHERE id=$id_partido") or die("Error partidos-48: Error al obtener datos de unisexo. Consulte a soporte.");
    $dtsr = mysql_fetch_array($sqlr);
    $id_jugador_1=$dtsr['id_Jugador1'];
    $id_jugador_2=$dtsr['id_Jugador2'];
    $id_jugador_3=$dtsr['id_Jugador3'];
    $id_jugador_4=$dtsr['id_Jugador4'];

    */

        $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_1") or die("Error partidos-161: Error al obtener datos de mixto. Consulte a soporte.");;
        $dtsr = mysql_fetch_array($sqlr);
        $sexo_jugador_1=$dtsr['Sexo'];


        $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_2") or die("Error partidos-165: Error al obtener datos de mixto. Consulte a soporte.");;
        $dtsr = mysql_fetch_array($sqlr);
        $sexo_jugador_2=$dtsr['Sexo'];

        $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_3") or die("Error partidos-169: Error al obtener datos de mixto. Consulte a soporte.");;
        $dtsr = mysql_fetch_array($sqlr);
        $sexo_jugador_3=$dtsr['Sexo'];

        $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_4") or die("Error partidos-173: Error al obtener datos de mixto. Consulte a soporte.");;
        $dtsr = mysql_fetch_array($sqlr);
        $sexo_jugador_4=$dtsr['Sexo'];


        $sqlr = mysql_query("SELECT Sexo FROM jugadores WHERE id=$id_jugador_sesion") or die("Error partidos-178: Error al obtener datos de mixto. Consulte a soporte.");
        $dtsr = mysql_fetch_array($sqlr);
        $sexo_jugador_sesion=$dtsr['Sexo'];

        //die("sexo j1: $sexo_jugador_1 sexo j2: $sexo_jugador_2 sexo j3: $sexo_jugador_3 sexo j4: $sexo_jugador_4 sexo sesion $sexo_jugador_sesion");
        //El Jugador1 es el creador y no necesita control.

        //El Jugador2 no necesita control, puede ser de cualquier sexo.

        //Control cuando el Jugador3 se apunta:

        if($id_jugador_1!=0 and $id_jugador_2!=0 and $id_jugador_3==0 and $id_jugador_4==0)
        {
            if($sexo_jugador_1==$sexo_jugador_2)
                if($sexo_jugador_sesion!=$sexo_jugador_1)
                    return true;
                else
                    return false;
            else
                return true; //Si el sexo de los dos primeros jugadores es distinto, el jugador 3 puede apuntarse y dependerá solo del cuarto.

        }

        //Control cuando el Jugador4 se apunta:
        if($id_jugador_1!=0 and $id_jugador_2!=0 and $id_jugador_3!=0 and $id_jugador_4==0)
        {

            if($sexo_jugador_1==$sexo_jugador_2 or $sexo_jugador_1==$sexo_jugador_3)
            {
                if($sexo_jugador_sesion!=$sexo_jugador_1)
                    return true;
                else
                    return false;
            }
            elseif($sexo_jugador_2==$sexo_jugador_3)
            {
                if($sexo_jugador_sesion==$sexo_jugador_1)
                    return true;
                else
                    return false;

            }else
                return false;
        }
        //A falta de control, se deja apuntarse.
        return true;
    }

    function agrega_registro_mixto($id_partido)
    {
        $query_mixto="INSERT INTO partidos_mixtos VALUES('$id_partido')";
        mysql_query($query_mixto) or die("Error partidos-62: Error al insertar el partido mixto en la base de datos, contacte con soporte.");
    }

    function agrega_registro_parejas($id_creador,$id_invitado,$id_partido,$confirmado='N')
    {

        if($id_creador=='' or $id_invitado=='')
            die("Error partidos-288: No han sido especificados o el creador o el invitado, avise a soporte");

        //$id_partido=$LastID;
        $queryParejas="INSERT INTO partidos_pendientes_pareja(id_jugador_creador,id_partido,id_jugador_invitado,confirmado) VALUES($id_creador,$id_partido,$id_invitado,'$confirmado')";
        $OK=mysql_query($queryParejas) or die("Error partidos-292: Error al insertar registro en base de datos, avise a soporte");

        if($confirmado=='N')
        {
            //Enviamos mail de invitacion solo si el partido no estaba ya confirmado. Si lo estaba, es que esto es una modificacion y no debe enviar mail
            $sqlr = mysql_query("SELECT Nombre,Email1 FROM jugadores WHERE id=".$id_invitado);
            $dtsr = mysql_fetch_array($sqlr);
            $mail=$dtsr['Email1'];
            $invitado=$dtsr['Nombre'];

            $sqlr = mysql_query("SELECT Nombre FROM jugadores WHERE id=".$id_creador);
            $dtsr = mysql_fetch_array($sqlr);
            $creador=$dtsr['Nombre'];

            $sqlr = mysql_query("SELECT p.Fecha as Fecha, p.Hora as Hora,c.Nombre as Nombre,id_Liga as id_liga FROM partidos p left join campos c on p.id_campo=c.id WHERE p.id=".$id_partido);
            $dtsr = mysql_fetch_array($sqlr);
            $fecha=date('d-m-Y',strtotime($dtsr['Fecha']));
            $hora=$dtsr['Hora'];
            $campo=$dtsr['Nombre'];

            $titulo=Traductor::traducir("Invitación como pareja a un partido");
            $mensaje=Traductor::traducir("Estas invitado a jugar como pareja de ").$creador.Traductor::traducir(" en un partido en ").$campo.Traductor::traducir(" el ").$fecha.Traductor::traducir(" a las ").$hora.Traductor::traducir(" <br><br>Recuerda que ningún jugador verá este partido hasta que no te apuntes antes de 24 horas a partir de la recepcion de este mensaje. <br><br>Puedes apuntarte accediendo a PARTIDOS EN CURSO. <br>(Si no vas a asistir, avisa a ").$creador.Traductor::traducir(" para que lo anule o modifique) ");
            if($mail!=''){
                //cambiar esto en produccion por $mail

                //Nuevo: gestion del push
                $NM=devuelve_un_campo('jugadores',6,'id',$id_creador);
                $AP=devuelve_un_campo('jugadores',7,'id',$id_creador);
                $tlf=devuelve_un_campo('jugadores',10,'id',$id_Jugador);
                $fecha_p=devuelve_un_campo('partidos',2,'id',$id_partido);
                $hora_p=devuelve_un_campo('partidos',3,'id',$id_partido);
                $date = new DateTime($fecha_p);
                $dia_p=$date->format('d');
                $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
                $dia_sem = $dias[date('N', strtotime($fecha_p))];
                $id_campo=devuelve_un_campo('partidos',5,'id',$id_partido);
                $nombre_campo=devuelve_un_campo('campos',2,'id',$id_campo);

                $asunto_push="Partido como pareja";
                $dia_evento=getDiaEvento($fecha_p);
                $mensaje_push="Apuntate para jugar como pareja de $NM $AP $dia_evento a las $hora_p h en $nombre_campo";
                if(!MensajePUSH ($id_invitado,(Componer_Notificacion($id_invitado,$asunto_push,"\"".$mensaje_push."\"",'6',$dtsr['id_liga'],$id_partido))))
                    mail($mail, $titulo,$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com");
            }

            //Agregamos un mensaje privado interno
            $fechaini=date("Y-m-d");
            $fechafin=date('Y-m-d', strtotime('+1 month'));
            $hora=date("H").':'.date("i");

            $sqlr = mysql_query("SELECT id_liga FROM partidos p WHERE id=".$id_partido);
            $dtsr = mysql_fetch_array($sqlr);
            $id_Liga=$dtsr['id_liga'];

            $mensaje=str_replace("'","\"\"",$mensaje);
            $mensaje=str_replace("<br>","\n",$mensaje);
            $mensaje=str_replace("<b>","",$mensaje);
            $mensaje=str_replace("</b>","",$mensaje);
            $titulo=str_replace("'","\"\"",$titulo);


            if(checkMensajesRepetidos($id_creador,$id_invitado,$fechaini,$hora,$mensaje))
                die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");


            $queryMensaje="INSERT INTO mensajes2(id_liga,id_jugador_origen,id_jugador_destino,Titulo,Mensaje,Hora,Fecha_INI,Fecha_FIN,F_Desaparece)
                            VALUES('$id_Liga','$id_creador','$id_invitado','$titulo','$mensaje','$hora','$fechaini','$fechafin','$fechafin')";
            $OK=mysql_query($queryMensaje) or die("Error partidos-79: Error al insertar registro en base de datos, avise a soporte. $queryMensaje");
        }

    }
    //////////////fin FUNCIONES PARA LA GESTION DE SEXOS EN PARTIDOS DE SEXO DEFINIDO Y MIXTOS////////////////////////////////


    ///////////////////////////////////////////AVISOS PARTIDOS ABIERTOS///////////////////////////////////////////////////////          //Seleccion de sustituto, con el id del partido y del jugador en caso de true.
    ///////////////////////////////////////////AVISOS PARTIDOS ABIERTOS///////////////////////////////////////////////////////          //Seleccion de sustituto, con el id del partido y del jugador en caso de true.

    //OJO: Esta funcion esta en gestion_aviso_partido_abierto.php repetida para el tema de JQuery de avisos abiertos
    //ESTA FUNCION NO SE USA Y ESTA OBSOLETA.
    function gestion_aviso_partido_abierto($id_partido_abierto, $id_Jugador1, $id_Jugador2, $parejas,$id_Liga,$nivel_min,$nivel_max,$Fecha, $Hora,$favoritos,$para_sustituir=false,$id_del_partido='',$id_Jugador_busca_sustituto='')
    {
        //NUEVO 29/06/14: Aviso de nuevo partido abierto////////////
        //echo "Inicio: ".time()."<br>";

        include("classes/general_class.php");
        include("classes/database_class.php");
        include("classes/jugador_class.php");
        include("classes/avisos_class.php");


        //JMAM: Indica si puede enviar el PUSH
        $puedeEnviarPush = true;

        //Comprobamos que el partido cumple o no las condiciones para realizar el envio del email.

        //Primera seleccion previa, jugadores activos, con el nivel entre el minimo y el maximo, y que jueguen en esta liga
        $query_favoritos="";
        if($favoritos!=''){  //Partido donde se han marcado favoritos

            if($para_sustituir==false) //No es la logica para sustitutos, sino para nuevos partidos abiertos o partidos por parejas
            {
                $query_favoritos=" AND id NOT IN (SELECT id_Jugador_NO_Favorito FROM noesfavorito WHERE id_Jugador=$id_Jugador1) ";
                if($parejas=='S') //Si es un partido por parejas, que el jugador factible no sea no favorito del jugador 2 tambien.
                    $query_favoritos.=" AND id NOT IN (SELECT id_Jugador_NO_Favorito FROM noesfavorito WHERE id_Jugador=$id_Jugador2) ";
            }else //Para sustitutos, el jugador evaluado debe ser favorito de todos los que esten apuntados al partido
            {
                $idj1=devuelve_un_campo('partidos',7,'id',$id_del_partido);
                $idj2=devuelve_un_campo('partidos',8,'id',$id_del_partido);
                $idj3=devuelve_un_campo('partidos',9,'id',$id_del_partido);
                $idj4=devuelve_un_campo('partidos',10,'id',$id_del_partido);

                if(intval($idj1)>0)
                    $query_favoritos.=" AND id NOT IN (SELECT id_Jugador_NO_Favorito FROM noesfavorito WHERE id_Jugador=$idj1)";
                if(intval($idj2)>0)
                    $query_favoritos.=" AND id NOT IN (SELECT id_Jugador_NO_Favorito FROM noesfavorito WHERE id_Jugador=$idj2)";
                if(intval($idj3)>0)
                    $query_favoritos.=" AND id NOT IN (SELECT id_Jugador_NO_Favorito FROM noesfavorito WHERE id_Jugador=$idj3)";
                if(intval($idj4)>0)
                    $query_favoritos.=" AND id NOT IN (SELECT id_Jugador_NO_Favorito FROM noesfavorito WHERE id_Jugador=$idj4)";

                depurar_funcion_avisos("sustituir en favoritos aplicado<br>");
            }
        }

        $query_excluye_propio_jugador="";
        if($para_sustituir==false)
            $query_excluye_propio_jugador="AND id!=$id_Jugador1";
        else
            $query_excluye_propio_jugador="AND id!=$id_Jugador_busca_sustituto";


        $query_nivel="";
        if($nivel_min!='' and $nivel_max!='')
            $query_nivel=" AND Golf_HExacto BETWEEN ".($nivel_min*100)." AND ".($nivel_max*100)." ";

        /*$query_jugadores_posibles="SELECT id FROM jugadores
            WHERE Estado='ACTIVO' AND id IN (SELECT id_Jugador FROM juegalaliga WHERE id_Liga=$id_Liga and estado='ACTIVO')
            $query_nivel $query_favoritos $query_excluye_propio_jugador";*/

        $hoy=date("Y-m-d");
        $query_jugadores_posibles="SELECT id FROM jugadores
            WHERE 
                    (
                        Estado='ACTIVO' AND id IN (SELECT id_Jugador FROM juegalaliga WHERE id_Liga=$id_Liga and estado='ACTIVO')
                        $query_nivel $query_favoritos $query_excluye_propio_jugador
                    )
                    OR
                    (
                        Estado='ACTIVO' AND id IN (SELECT id_Jugador FROM juegalaliga WHERE id_Liga=$id_Liga and estado!='ACTIVO')
                        $query_nivel $query_favoritos $query_excluye_propio_jugador AND id NOT IN(SELECT id_jugador FROM control_avisos_partidos_abiertos WHERE fecha='$hoy')
                    )";

        //JM 9-jun-15: Para contabilizar como enviado y no enviarlo en la siguiente query al jugador.
        mysql_query("INSERT INTO control_avisos_partidos_abiertos(id_jugador,fecha) VALUES($id_Jugador1,'$hoy')");

        depurar_funcion_avisos($query_jugadores_posibles.'<br>');

        $aJugadores=mysql_query($query_jugadores_posibles);

        //echo "Query factibles: ".time()."<br>";
        //Recorremos cada jugador factible, cargamos sus configuracion de condiciones guardada, y vemos si este partido las cumple
        //para enviarles o no el aviso.

        //IDs de los jugadores a avisar finalmente, que cumplan el filtro.
        $jugadores_a_avisar=array();
        $numero=mysql_num_rows($aJugadores);
        depurar_funcion_avisos("hay ".$numero." jugadores a evaluar inicialmente<br>");

        $nombre_de_liga=trim(devuelve_un_campo('liga',1,'id',$id_Liga));
        depurar_funcion_avisos('#'.$nombre_de_liga.'#<br>');



        if($para_sustituir==true){
            $titulo_push= Traductor::traducir("Busco sustituto. Apúntate");
            $fecha_p=devuelve_un_campo('partidos',2,'id',$id_del_partido);
            $hora_p=devuelve_un_campo('partidos',3,'id',$id_del_partido);
            $nivel_min_p=devuelve_un_campo('partidos',18,'id',$id_del_partido);
            $nivel_max_p=devuelve_un_campo('partidos',19,'id',$id_del_partido);

        }else{

            $titulo_push= Traductor::traducir("Nuevo partido. Apúntate");
            $fecha_p=$Fecha;
            $hora_p=$Hora;
            $nivel_min_p=$nivel_min;
            $nivel_max_p=$nivel_max;

            $Partido = new Partido($id_del_partido);
            if ($Partido->esReservaPistaPartido()){
                $ReservaPista = $Partido->obtenerReservaPistaPartido();

                if(!$ReservaPista->esPartidoPublico() || $ReservaPista->esPartidoCompleto()){
                    $puedeEnviarPush = false;
                    depurar_funcion_avisos("Partido: $id_del_partido es Cerrado, no se notifica cuando se abre el Partido<br>");
                }
            }

        }
        $date = new DateTime($fecha_p);
        $dia_p=$date->format('d');
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $dia_sem = $dias[date('N', strtotime($fecha_p))];
        //$dia_sem=strftime('%A',strtotime($fecha_p));

        $idp_usar=($para_sustituir?$id_del_partido:$id_partido_abierto);

        $id_campo=devuelve_un_campo('partidos',5,'id',$idp_usar);
        if($id_campo>0)
            $nombre_campo=devuelve_un_campo('campos',2,'id',$id_campo);
        else
            $nombre_campo=devuelve_un_campo('partidos',6,'id',$idp_usar);

        $dia_evento=ucfirst(getDiaEvento($fecha_p));

        $mensaje_push=$dia_evento.' a las '.$hora_p.' h en '.$nombre_campo;
        if(intval($nivel_min)>0 and intval($nivel_max)>0)
            $mensaje_push.=". Nivel $nivel_max al $nivel_min";
        else
            $mensaje_push.=". Nivel 18 al 1";

        //echo "hola1";

        while($Jugador=mysql_fetch_array($aJugadores))
        {
            $id=$Jugador['id'];

            if(!DameDeviceIDs ($id))
                continue;

            $aj=new aviso_jugador_class(1,$id);
            $condiciones=&$aj->condiciones_aviso->getFields();

            if($condiciones['nivel_maximo']->value=='') //Por ejemplo este campo vacio significa que no hay ningun campo para este user en la bd
            {
                depurar_funcion_avisos($id.' no tiene configuracion, se salta<br>');
                continue;
            }



            $cumple=true; //A priori cumple, a no ser que no cumpla una de las siguientes condiciones:
            /*if($id==51)
        {
            depurar_funcion_avisos('COND.'.var_dump($condiciones));
            depurar_funcion_avisos('AVISO JUG.'.var_dump($aj));
        } */

            $Fecha_partido=strtotime($Fecha);
            $Hora_partido=strtotime($Hora);

            $dia_semana_partido=date("N",$Fecha_partido); //De 1 a 7

            $horaInicio="";
            $horaFin="";
            $dia_semana_inactivo="";
            if($dia_semana_partido==1) //Lunes
            {
                $horaInicio=strtotime($condiciones['lunesdesde']->value);
                $horaFin=strtotime($condiciones['luneshasta']->value);
                $dia_semana_inactivo=intval($condiciones['lunesdesde']->inactive);
            }
            elseif($dia_semana_partido==2)
            {
                $horaInicio=strtotime($condiciones['martesdesde']->value);
                $horaFin=strtotime($condiciones['marteshasta']->value);
                $dia_semana_inactivo=intval($condiciones['martesdesde']->inactive);
            }
            elseif($dia_semana_partido==3)
            {
                $horaInicio=strtotime($condiciones['miercolesdesde']->value);
                $horaFin=strtotime($condiciones['miercoleshasta']->value);
                $dia_semana_inactivo=intval($condiciones['miercolesdesde']->inactive);
            }
            elseif($dia_semana_partido==4)
            {
                $horaInicio=strtotime($condiciones['juevesdesde']->value);
                $horaFin=strtotime($condiciones['jueveshasta']->value);
                $dia_semana_inactivo=intval($condiciones['juevesdesde']->inactive);
            }
            elseif($dia_semana_partido==5)
            {
                $horaInicio=strtotime($condiciones['viernesdesde']->value);
                $horaFin=strtotime($condiciones['vierneshasta']->value);
                $dia_semana_inactivo=intval($condiciones['viernesdesde']->inactive);
            }
            elseif($dia_semana_partido==6)
            {
                $horaInicio=strtotime($condiciones['sabadodesde']->value);
                $horaFin=strtotime($condiciones['sabadohasta']->value);
                $dia_semana_inactivo=intval($condiciones['sabadodesde']->inactive);
            }
            elseif($dia_semana_partido==7) //Domingo
            {
                $horaInicio=strtotime($condiciones['domingodesde']->value);
                $horaFin=strtotime($condiciones['domingohasta']->value);
                $dia_semana_inactivo=intval($condiciones['domingodesde']->inactive);
            }else
                die("Error 456 avisos partidos abiertos: Dia semana partido no valido, consulte con soporte tecnico");

            //Si no estan definidos, por defecto es de 7 a 23 horas.
            if($horaInicio=='')
                $horaInicio=strtotime('07:00');
            if($horaFin=='')
                $horaFin=strtotime('23:00');
            if($dia_semana_inactivo=='')
            {
                $dia_semana_inactivo=0;
                //$strr=" se toma por defecto";
            }
            //depurar_funcion_avisos("dia inactivo esta a $dia_semana_inactivo para jugador $id $strr<br>");

            if(! ($Hora_partido>=$horaInicio AND $Hora_partido<=$horaFin and $dia_semana_inactivo==0))  //El partido no esta entre hinicio y hfin definidas por el usuario para ese dia de la semana, y el dia esta activo
            {
                $cumple=false;
                depurar_funcion_avisos("Hora $Hora  hace que no cumpla con ".(date("H:i",$horaInicio))." y ".(date("H:i",$horaFin))." dia_semana_inactivo es $dia_semana_inactivo<br>");
                continue;
            }

            if(Tiene_Partido_Sin_Resultado_modpartidos($Fecha,$id,$Hora,$id_del_partido)>0)
            {
                $cumple=false;
                depurar_funcion_avisos("El jugador $id para $Fecha a las $Hora +-1.30 ya tiene partido, se excluye <br>");
                continue;
            }


            $nivel_min_partido=intval($nivel_min);
            $nivel_max_partido=intval($nivel_max);

            //depurar_funcion_avisos("nivel min partido es $nivel_min_partido y max es $nivel_max_partido <br>");

            //estos valores por defecto los pongo intercambiados (realmente min sería 18 y max 1), pero como los desplegables se llaman al contrario
            //en la interfaz de creacion de partidos hay que hacerlo asi.
            if($nivel_min_partido==0)
                $nivel_min_partido=1;

            if($nivel_max_partido==0)
                $nivel_max_partido=18;

            if($id==8416)
            {
                depurar_funcion_avisos("nivel min partido es $nivel_min_partido y max es $nivel_max_partido <br>");
                depurar_funcion_avisos("nivel min configurado es ".($condiciones['nivel_minimo']->value)." y max es ".($condiciones['nivel_maximo']->value)." <br>");
                depurar_funcion_avisos("Min partido $nivel_max_partido debe ser menor o igual a condiciones minimo ".$condiciones['nivel_minimo']->value." para el usuario $id<br>");
                depurar_funcion_avisos("Max partido $nivel_min_partido debe ser mayor o igual a condiciones maximo ".$condiciones['nivel_maximo']->value." para el usuario $id<br>");
            }

            if(!(intval($nivel_max_partido)<=intval($condiciones['nivel_minimo']->value)) and $condiciones['nivel_minimo']->value!='' and $nivel_max_partido!=0) //Si vacio (no definido), validamos para que avise para cualquier nivel.
            {
                $cumple=false;
                depurar_funcion_avisos("Nivel min $nivel_min_partido hace que no cumpla con nivel ".$condiciones['nivel_minimo']->value."<br>");
                continue;
            }

            if(!(intval($nivel_min_partido)>=intval($condiciones['nivel_maximo']->value)) and $condiciones['nivel_maximo']->value!='' and $nivel_min_partido!=0) //Si vacio (no definido), validamos para que avise para cualquier nivel.
            {
                $cumple=false;
                depurar_funcion_avisos("Nivel max $nivel_max_partido hace que no cumpla con nivel ".$condiciones['nivel_maximo']->value."<br>");
                continue;
            }

            if($condiciones['sexo']->value!='cualquiera' and $condiciones['sexo']->value!='') //Si no esta definido, lo validamos por defecto para que avise para cualquier sexo.
            {
                if($para_sustituir==false)
                    $sexo_jugador_creador_partido=devuelve_un_campo('jugadores',9,'id',$id_Jugador1);
                else{ //Si es un aviso por sustituto, hay que comparar el sexo del jugador evaluado CON EL QUE SE VA A SUSTITUIR
                    $sexo_jugador_creador_partido=devuelve_un_campo('jugadores',9,'id',$id_Jugador_busca_sustituto);//AQUI REALMENTE SE PASA EL ID DEL QUE BUSCA SUSTITUTO PARA QUE COINCIDA SU SEXO
                    depurar_funcion_avisos("sustituir en sexo aplicado, el id del que busca sustituto es $id_Jugador_busca_sustituto <br>");
                }
                $sexo_jugador_evaluado=devuelve_un_campo('jugadores',9,'id',$id);

                if($sexo_jugador_creador_partido!=$sexo_jugador_evaluado)
                {
                    $cumple=false;

                    if($para_sustituir)
                        depurar_funcion_avisos("Sexo (sustituir=$para_sustituir) no cumple, jugador $id es $sexo_jugador_evaluado y el que busca sustituto ($id_Jugador_busca_sustituto) es $sexo_jugador_creador_partido <br>");
                    continue;
                }else
                {
                    if($para_sustituir)
                        depurar_funcion_avisos("Sexo (sustituir=$para_sustituir) SI cumple, jugador $id es $sexo_jugador_evaluado y el que busca sustituto ($id_Jugador_busca_sustituto) es $sexo_jugador_creador_partido <br>");
                }
            }
            //OPEN DE VERANO//

            if($nombre_de_liga=='Open de Verano') //ojo, tener esto en cuenta si cambia el nombre (may-minusc y espacios principio y final no afecta)
            {

                $provincia_residencia_jugador=devuelve_un_campo('jugadores',15,'id',$id); //En texto
                //depurar_funcion_avisos('$provincia_residencia_jugador'.$provincia_residencia_jugador.'<br>');

                $id_campo_partido=devuelve_un_campo('partidos',5,'id',($para_sustituir?$id_del_partido:$id_partido_abierto));
                //depurar_funcion_avisos('$id_campo_partido'.$id_campo_partido.'<br>');

                if($id_campo_partido>0)
                {
                    $provincia_partido = devuelve_un_campo('campos',9,'id',$id_campo_partido);
                    depurar_funcion_avisos('provincia_partido:'.$provincia_partido);
                }else //No hay campo, es que hay un "otros" en el campo, obtengo la provincia a través del municipio
                {
                    $municipio=devuelve_un_campo('partidos',24,'id',($para_sustituir?$id_del_partido:$id_partido_abierto));
                    $provincia_id=devuelve_un_campo('municipios',1,'municipio',$municipio);
                    $provincia_partido=devuelve_un_campo('provincias',1,'id',$provincia_id);
                }

                //$provincia_partido = devuelve_un_campo('campos',9,'id',$id_campo_partido); //En texto
                //depurar_funcion_avisos('$provincia_partido'.$provincia_partido.'<br>');

                $provincia_configuracion = devuelve_un_campo('provincias',1,'id',$condiciones['provincia']->value);
                //depurar_funcion_avisos('$provincia_configuracion'.$provincia_configuracion.'<br>');

                if($condiciones['provincia']->value=='') //No tiene nada definido
                {
                    if($provincia_residencia_jugador!=$provincia_partido)
                    {
                        $cumple=false;
                        depurar_funcion_avisos("Open de Verano: id $id sin provincia defininida, prov. jugador y prov. partido no coincide".'<br>');
                        continue;
                    }//else
                    //depurar_funcion_avisos("Open de Verano: id $id sin provincia defininida, prov. jugador y prov. partido coincide".'<br>');

                }elseif($condiciones['provincia']->value!='todas') //Tiene provincia definida, porque si no es vacio ni es todas, es una provincia determinada.
                {
                    if($provincia_configuracion!=$provincia_partido)
                    {
                        $cumple=false;
                        continue;
                        //depurar_funcion_avisos("Open de Verano: id $id con provincia defininida, provincia configurada  prov. partido no coincide".'<br>');
                    }//else
                    //depurar_funcion_avisos("Open de Verano: id $id con provincia defininida, provincia configurada  prov. partido coincide".'<br>');

                }
                //En el caso de que sea 'todas', no discrimina provincia.
                //FIN OPEN DE VERANO//
                //Gestion de la provincia en ligas que no son el open de verano
            }elseif($condiciones['provincia']->value!='todas' and $condiciones['provincia']->value!='') //Si fuera '', entonces validamos para que avise para cualquier provincia. Que sea de la liga ya se filtra antes.
            {
                depurar_funcion_avisos('Provincia en configuracion: '.$condiciones['provincia']->value);


                $id_campo_partido=devuelve_un_campo('partidos',5,'id',($para_sustituir?$id_del_partido:$id_partido_abierto));
                depurar_funcion_avisos('campo partido:'.$id_campo_partido);

                if($id_campo_partido>0)
                {
                    $provincia_partido = devuelve_un_campo('campos',9,'id',$id_campo_partido);
                    depurar_funcion_avisos('provincia_partido:'.$provincia_partido);
                }else //No hay campo, es que hay un "otros" en el campo, obtengo la provincia a través del municipio
                {
                    $municipio=devuelve_un_campo('partidos',24,'id',($para_sustituir?$id_del_partido:$id_partido_abierto));
                    $provincia_id=devuelve_un_campo('municipios',1,'municipio',$municipio);
                    $provincia_partido=devuelve_un_campo('provincias',1,'id',$provincia_id);
                }

                $provincia_configuracion = devuelve_un_campo('provincias',1,'id',$condiciones['provincia']->value);
                depurar_funcion_avisos('provincia_configuracion:'.$provincia_configuracion);

                if($provincia_configuracion!= $provincia_partido and $provincia_partido!='')//Por si hubiera ligas sin provincia entonces no se tienen en cuenta
                {
                    $cumple=false;
                    depurar_funcion_avisos("Provincia $provincia_partido hace que no cumpla con ".$condiciones['provincia']->value." ".$id."<br>");
                    continue;
                }
            }

            if($cumple && $puedeEnviarPush){
                $jugadores_a_avisar[]=$id; //Si pasa todos los filtros sin ponerse false, añade al array de los que hay que avisar.
                MensajePUSH ($id,(Componer_Notificacion($id,$titulo_push,$mensaje_push,'4',$id_Liga,($para_sustituir?$id_del_partido:$id_partido_abierto))));
            }
        }
        //echo "Fin envio push: ".time()."<br>";
        $contar_jug_en=count($jugadores_a_avisar);
        depurar_funcion_avisos("Se manda push a $contar_jug_en jugadores<br>");

    }

    function depurar_funcion_avisos($mensaje)
    {
        //if(in_array($_SESSION['S_id_usuario'],array('8416','50','51','52','224','7004')))
        //   echo $mensaje;
    }
    ///////////////////////////////////////////FIN AVISOS PARTIDOS ABIERTOS///////////////////////////////////////////////////////

    function alta_partidos ($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Extra3,$parejas='N',$id_creador='',$id_invitado='',$es_mixto='N',$es_amistoso='N')
    {

        //Nuevo JM 19/02/15: envio de mail al del campo en caso de que lo tenga activo en el PCU
        $enviar_mail_partido=devuelve_un_campo('campos',35,'id',$id_Campo);
        if($enviar_mail_partido=='S')
        {
            $mail_campo=devuelve_un_campo('campos',16,'id',$id_Campo);
            $nombre_jugador=devuelve_un_campo('jugadores',1,'id',$id_Jugador1);
            $telefono_jugador=devuelve_un_campo('jugadores',10,'id',$id_Jugador1);

            $search = array("%NOMBRE_JUGADOR%", "%TELEFONO_JUGADOR%", "%FECHA_PARTIDO%", "%HORA_PARTIDO%");
            $replace = array($nombre_jugador, $telefono_jugador, $Fecha, $Hora);

            $email_texto = str_replace($search, $replace, Traductor::traducir("email_alta_nuevo_partido_para_club_mensaje"));

            //$mail_campo='info@intelsys.es';
            mail($mail_campo,Traductor::traducir("email_alta_nuevo_partido_para_club_titulo"), $email_texto);
        }
        ///

        $fechahoy = date("d/m/Y");
        $fechafinliga = devuelve_un_campo('liga',4,'id',$id_Liga);
        $Fecha=cambiaf_a_mysql($Fecha);
        $fechahoy=cambiaf_a_mysql($fechahoy);
        $Fecha_Result=cambiaf_a_mysql($Fecha_Result);
        $horas = explode(':', $Hora);

        $hora_para_crear_partido=strtotime($Hora);
        $ahora=time();

        if($id_Campo>0){
            $Extra3=devuelve_un_campo('campos',10,'id',$id_Campo);
        }else{
            $Extra3=devuelve_un_campo('municipios',2,'id',$Extra3);

        }

        $CUANTOS_PARTIDOS=Tiene_Partido_Sin_Resultado ($Fecha,$_SESSION['S_id_usuario'],$Hora);
        if ($CUANTOS_PARTIDOS>=1)
        {
            $Mensaje='ERROR\\n\\n'.Traductor::traducir("Ya tienes ").' '.$CUANTOS_PARTIDOS.' '.Traductor::traducir("partido el día").' '.cambiaf_a_normal($Fecha).' \\n'.Traductor::traducir("No puedes crear otro partido ese mismo día con una diferencia menor a 1.30 horas.").' o bien quedan menos de 10 dias para que acabe esta liga';
            echo "<script>alert('$Mensaje');</script>";
            return (0);
        }
        else if($Fecha==$fechahoy && $hora_para_crear_partido<=$ahora/*$horas[0]<=date("H")*/)
        {
            $Mensaje='ERROR\\n\\n'.Traductor::traducir("No puedes crear un partido para hoy a una hora anterior a la actual");
            echo "<script>alert('$Mensaje');</script>";
            return (0);


        }
        //DESCOMENTAR PARA EL OPEN OJO ESTA FUNCION NO TIENE EFECTO, USAR LA DE PARTIDOS_FUNCIONES

        else if($Fecha>$fechafinliga )
        {
            $Mensaje='ERROR\\n\\n'.Traductor::traducir("Para crear un partido con fecha posterior al fin del Open").' ('.cambiaf_a_normal($fechafinliga).')\\n'.Traductor::traducir("debes hacerlo desde la liga de tu zona");
            echo "<script>alert('$Mensaje');</script>";
            return (0);
        }
        /////////////////////////////////////////

        else{
            ///Comprobamos este error antes de insertar ningún registro
            if($parejas=='S')   //Nuevo, si es partido por parejas agrega el registro correspondiente.
                if($id_creador=='' or $id_invitado=='')
                {
                    echo "<script>alert('".ERROR_DEBE_ELEGIR_PAREJA."');</script>";
                    return (0);
                }

            //$query = "INSERT INTO partidos VALUES ('$id','$id_Liga','$Fecha','$Hora','$TipoPuntuacion','$id_Campo','$Otro_Campo','$id_Jugador1','$id_Jugador2','$id_Jugador3','$id_Jugador4','$Puntos_J1','$Puntos_J2','$Puntos_J3','$Puntos_J4','$id_Jugador_ApuntaResult','$Fecha_Result','$Observaciones','$nivel_min','$nivel_max','$favoritos','$invitacion','$Extra1','$Extra2','$Extra3','0')";

            $Partido = new Partido();
            $Partido[Partido::COLUMNA_idLiga] = $id_Liga;
            $Partido[Partido::COLUMNA_hora] = $Hora;
            $Partido[Partido::COLUMNA_fecha] = $Fecha;
            $Partido[Partido::COLUMNA_tipoPuntuacion] = $TipoPuntuacion;
            $Partido[Partido::COLUMNA_idCampo] = $id_Campo;
            $Partido[Partido::COLUMNA_nombreOtroCampo] = $Otro_Campo;
            $Partido[Partido::COLUMNA_idJugador1] = $id_Jugador1;
            $Partido[Partido::COLUMNA_idJugador2] = $id_Jugador2;
            $Partido[Partido::COLUMNA_idJugador3] = $id_Jugador3;
            $Partido[Partido::COLUMNA_idJugador4] = $id_Jugador4;
            $Partido[Partido::COLUMNA_parejaJugador1] = $Puntos_J1;
            $Partido[Partido::COLUMNA_parejaJugador2] = $Puntos_J2;
            $Partido[Partido::COLUMNA_parejaJugador3] = $Puntos_J3;
            $Partido[Partido::COLUMNA_parejaJugador4] = $Puntos_J4;
            $Partido[Partido::COLUMNA_idJugadorApuntaResultado] = $id_Jugador_ApuntaResult;
            $Partido[Partido::COLUMNA_fechaApuntaResultado] = $Fecha_Result;
            $Partido[Partido::COLUMNA_observaciones] = $Observaciones;
            $Partido[Partido::COLUMNA_nivelMininimo] = $nivel_min;
            $Partido[Partido::COLUMNA_nivelMaximo] = $nivel_max;
            $Partido[Partido::COLUMNA_favoritos] = $favoritos;
            $Partido[Partido::COLUMNA_notasDelClub] = $invitacion;
            $Partido[Partido::COLUMNA_idsJugadoresBuscandoSustituto] = $Extra1;
            $Partido[Partido::COLUMNA_sexo] = $Extra2;
            $Partido[Partido::COLUMNA_zonaGeografica] = $Extra3;
            $Partido[Partido::COLUMNA_tieneCredito] = 0;
            $LastID = $Partido->guardar();

            Registrar_Actividad ('3',$LastID);

            global $id_partido_creado;
            $id_partido_creado=$LastID;//Para aviso partidos abiertos por JQ

            if($es_mixto=='S'){
                agrega_registro_mixto($LastID);
            }

            if($parejas=='S')   //Nuevo, si es partido por parejas agrega el registro correspondiente.
                agrega_registro_parejas($id_creador,$id_invitado,$LastID);

            //Nuevo: Aviso de partidos abiertos al abrir un partido que no es parejas (partidos por parejas se avisan al apuntarse la pareja)
            //Nuevo JM 22/01/15: lo hacemos por jquery abajo en la llamada
            //if($parejas!='S')
            //  gestion_aviso_partido_abierto($LastID,$id_Jugador1, $id_Jugador2, 'N',$id_Liga,$nivel_min,$nivel_max,$Fecha, $Hora,$favoritos);


            //Nuevo: si es partido amistoso, agregamos el registro en la BD.
            //die('es amistoso: '.$es_amistoso);
            if($es_amistoso!='N'){
                agrega_partido_amistoso($LastID);
                //die("agregado amistoso");
            }

            $sqlr = mysql_query("SELECT * FROM jugadores WHERE id=".$id_Jugador1);
            $dtsr = mysql_fetch_array($sqlr);
            if($dtsr['tiporecordatorio']>0){
                $idp = $LastID;
                $tiempor = calculaTiempo($idp, $dtsr['tiporecordatorio'], ''.$dtsr['horarecordatorio'].'');
                mysql_query("INSERT INTO recordatorios (id_jugador, id_partido, id_liga, tiempo, estado) VALUES (".$id_Jugador1.",".$idp.",".$id_Liga.",".$tiempor.",0)");
                return $tiempor;
            }else{
                //if($_SESSION['S_LIGA_ACTUAL']==27){
                Juegalaliga::actualizarNumeroPartidosApuntadoJugadorEnLiga($id_Jugador1, $id_Liga);
                Juegalaliga::actualizarNumeroPartidosJugadosJugadorEnLiga($id_Jugador1, $id_Liga);
                return '1';
                //}else{
                //   return ($OK);
                // }
            }}
    };

    ////////////////////////////////////////////////
    function baja_partidos ($id)
    {
        $q="DELETE FROM partidos WHERE id=$id";
        $qr="DELETE FROM recordatorios WHERE id_partido=$id";

        //agregamos borrado en la tabla de amistosos
        $qa="DELETE FROM partidos_amistosos WHERE id=$id limit 1";
        mysql_query($qa);
        ////////////////////
        mysql_query($q);
        mysql_query($qr);

        //Borramos las notificaciones del partido en cuestion, las de partidos abiertos
        Desnotificar(4,$id,$_SESSION['S_LIGA_ACTUAL']);

        Registrar_Actividad ('8');

    };

    ////////////////////////////////////////////////

    function modifica_partidos ($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Extra3,$parejas='N',$id_creador='',$id_invitado='',$es_mixto='N',$es_amistoso='N')
    {
        $idp = $id;
        $fechafinliga = devuelve_un_campo('liga',4,'id',$id_Liga);
        if($id_Campo>0){
            $Extra3=devuelve_un_campo('campos',10,'id',$id_Campo);
        }else{
            $Extra3=devuelve_un_campo('municipios',2,'id',$Extra3);
        }
        $Fecha=cambiaf_a_mysql($Fecha);
        $Fecha_Result=cambiaf_a_mysql($Fecha_Result);
        //if ($Fecha!=devuelve_un_campo('partidos',2,'id',$id)) $CUANTOS_PARTIDOS=Tiene_Partido_Sin_Resultado ($Fecha,$_SESSION['S_id_usuario']);
        $CUANTOS_PARTIDOS=Tiene_Partido_Sin_Resultado_modpartidos ($Fecha,$_SESSION['S_id_usuario'],$Hora,$id);

        if ($CUANTOS_PARTIDOS>=1)
        {
            $Mensaje='ERROR\\n\\n'.Traductor::traducir("Ya tienes ").' '.$CUANTOS_PARTIDOS.' '.Traductor::traducir("partido el día").' '.cambiaf_a_normal($Fecha).' \\n'.Traductor::traducir("No puedes crear otro partido ese mismo día con una diferencia menor a 1.30 horas.");
            echo "<script>alert('$Mensaje');</script>";
            return (0);
        }


        //Jm 24/10/14 desactivamos este control para ver que pasa
        /*$anterior_amistoso=partido_es_amistoso($id);

    if ( (($anterior_amistoso==true and $es_amistoso=='N') or ($anterior_amistoso==false and $es_amistoso=='S')) and $id_Jugador2>0)
    {
        $Mensaje='ERROR\\n\\n No se puede modificar el tipo de partido (Torneo/Amistoso) una vez se ha apuntado el segundo jugador.';
        echo "<script>alert('$Mensaje');</script>";
        return (0);
    }*/



        /*
    // Condicion para partidos despues de  la fecha de fin de liga (se usa en el OPEN)
    else if($Fecha>$fechafinliga ){
    $Mensaje='ERROR\\n\\n'.Traductor::traducir("Para crear un partido con fecha posterior al fin del Open").' ('.cambiaf_a_normal($fechafinliga).')\\n'.Traductor::traducir("Para crear un partido con fecha posterior al fin del Open");
        echo "<script>alert('$Mensaje');</script>";
        return (0);

    }*/
        else
        {


    //Nuevo gestion de parejas: Borramos el partido por parejas, y si ha entrado como parejas o no (con una nueva o no), se vuelve a introducir.

    //Obtenemos si estaba confirmado o no
            $sqlr = mysql_query("SELECT confirmado FROM partidos_pendientes_pareja WHERE id_partido=$id") or die("Error partidos-448: Error al obtener datos de edicion de partidos. Consulte a soporte.");
            $dtsr = mysql_fetch_array($sqlr);
            $confirmado=$dtsr['confirmado'];
            if($confirmado=='') //Nuevo jm 20/10/14, para evitar fallo si creo partido normal y luego lo modifico a tipo pareja, donde no existia antes registro en la tabla.
                $confirmado='N';

            mysql_query("DELETE FROM partidos_pendientes_pareja WHERE id_partido=$id LIMIT 1") or die("Error partidos-413: Error al borrar registro, avise a soporte.");
            if($parejas=='S')
            {
                if($id_creador=='' or $id_invitado=='')
                {
                    echo "<script>alert('".ERROR_DEBE_ELEGIR_PAREJA."');</script>";
                    return (0);
                }


                agrega_registro_parejas($id_creador,$id_invitado,$id,$confirmado);
            }
            //$OK=mysql_query("update partidos set id_Liga='$id_Liga', Fecha='$Fecha', Hora='$Hora', TipoPuntuacion='$TipoPuntuacion', id_Campo='$id_Campo', Otro_Campo='$Otro_Campo', id_Jugador1='$id_Jugador1', id_Jugador2='$id_Jugador2', id_Jugador3='$id_Jugador3', id_Jugador4='$id_Jugador4', Puntos_J1='$Puntos_J1', Puntos_J2='$Puntos_J2', Puntos_J3='$Puntos_J3', Puntos_J4='$Puntos_J4', id_Jugador_ApuntaResult='$id_Jugador_ApuntaResult', Fecha_Result='$Fecha_Result', Observaciones='$Observaciones', nivel_min='$nivel_min', nivel_max='$nivel_max', favoritos='$favoritos', invitacion='$invitacion', Extra2='$Extra2', Extra3='$Extra3' where (id='$id')");


            $Partido = new Partido($id);
            $Partido[Partido::COLUMNA_idLiga] = $id_Liga;
            $Partido[Partido::COLUMNA_hora] = $Hora;
            $Partido[Partido::COLUMNA_fecha] = $Fecha;
            $Partido[Partido::COLUMNA_tipoPuntuacion] = $TipoPuntuacion;
            $Partido[Partido::COLUMNA_idCampo] = $id_Campo;
            $Partido[Partido::COLUMNA_nombreOtroCampo] = $Otro_Campo;
            $Partido[Partido::COLUMNA_idJugador1] = $id_Jugador1;
            $Partido[Partido::COLUMNA_idJugador2] = $id_Jugador2;
            $Partido[Partido::COLUMNA_idJugador3] = $id_Jugador3;
            $Partido[Partido::COLUMNA_idJugador4] = $id_Jugador4;
            $Partido[Partido::COLUMNA_parejaJugador1] = $Puntos_J1;
            $Partido[Partido::COLUMNA_parejaJugador2] = $Puntos_J2;
            $Partido[Partido::COLUMNA_parejaJugador3] = $Puntos_J3;
            $Partido[Partido::COLUMNA_parejaJugador4] = $Puntos_J4;
            $Partido[Partido::COLUMNA_idJugadorApuntaResultado] = $id_Jugador_ApuntaResult;
            $Partido[Partido::COLUMNA_fechaApuntaResultado] = $Fecha_Result;
            $Partido[Partido::COLUMNA_observaciones] = $Observaciones;
            $Partido[Partido::COLUMNA_nivelMininimo] = $nivel_min;
            $Partido[Partido::COLUMNA_nivelMaximo] = $nivel_max;
            $Partido[Partido::COLUMNA_favoritos] = $favoritos;
            $Partido[Partido::COLUMNA_notasDelClub] = $invitacion;
            $Partido[Partido::COLUMNA_idsJugadoresBuscandoSustituto] = $Extra1;
            $Partido[Partido::COLUMNA_sexo] = $Extra2;
            $Partido[Partido::COLUMNA_zonaGeografica] = $Extra3;
            $Partido->guardar();

            $OK = true;

    //Nuevo gestion de mixtos: Borra, y si ha entrado con S, vuelve a activar. Si no, se queda borrado.
            mysql_query("DELETE FROM partidos_mixtos WHERE id=$id LIMIT 1") or die("Error partidos-422: Error al borrar registro, avise a soporte.");
            if($es_mixto=='S')
                agrega_registro_mixto($id);

    //Nuevo gestion de amistosos: Borra, y si ha entrado con S, vuelve a agregar. Si no, se queda borrado.
            elimina_partido_amistoso($id);
            if($es_amistoso=='S')
                agrega_partido_amistoso($id);


            Registrar_Actividad ('3',$LastID);
            if(!$id_Jugador_ApuntaResult>0){
                $sqlr = mysql_query("SELECT * FROM jugadores WHERE id=".$id_Jugador1);
                $dtsr = mysql_fetch_array($sqlr);
                if($dtsr['tiporecordatorio']>0){
                    mysql_query("DELETE FROM recordatorios WHERE id_partido=".$idp." AND id_jugador=".$id_Jugador1);
                    $tiempor = calculaTiempo($idp, $dtsr['tiporecordatorio'], ''.$dtsr['horarecordatorio'].'');
                    mysql_query("INSERT INTO recordatorios (id_jugador, id_partido, id_liga, tiempo, estado) VALUES (".$id_Jugador1.",".$idp.",".$id_Liga.",".$tiempor.",0)");

                }
            }
            return ($OK);
        }
    };

    function botones ($numero,$parecido)
    {
        //echo ("<table>"); //echo ("<tr>"); //echo ("<td>");
        echo "<a href='$PHP_SELF?menu=listar&texto=$parecido'><img src='./images/flechatopeiz.gif' border='0' width='20' alt='INICIO partidos'></a>";
        //echo ("</td>");
        if ($numero>0){
            //        echo ("<td>");
            echo "<a href='$PHP_SELF?menu=listar&texto=$parecido&hoja=".($numero-10)."'><img src='./images/flechaiz.gif' border='0' width='20' alt='ANTERIOR PÁGINA partidos'></a>";
            //        echo ("</td>");
        };
        //echo ("<td>");
        echo "<a href='$PHP_SELF?menu=listar&texto=$parecido&hoja=".($numero+10)."'><img src='./images/flechader.gif' border='0' width='20' alt='SIGUIENTE PÁGINA partidos'></a>";
        //echo ("</td>");
        //echo ("</tr>");
        //echo ("</table>");
    };

    ////////////////////////////////////////////////

    function consulta_partidos ($id)
    {
        $i = mysql_query("SELECT * FROM partidos WHERE id = $id");
        return $i;
    };

    ////////////////////////////////////////////////

    ////////////////////////////////////////////////
    function hay_evento($Fecha)
    {
        $sql_eventos2="SELECT * FROM mensajes2 WHERE Importancia='eventoferta' and '$Fecha' >=Fecha_INI AND  '$Fecha' <= Fecha_FIN and id_liga=".$_SESSION['S_LIGA_ACTUAL'];
        //echo $sql_eventos2;

        $roweventos2=mysql_query($sql_eventos2);
        return mysql_num_rows($roweventos2);
    }

    function gestion_eventos_intercalados($MiAgenda,$fecha_anterior,$Fecha,$hora_anterior,$Hora,$ultimo)
    {
        Log::v(__FUNCTION__, "Fecha Anterior: $fecha_anterior", true);

        global $id_cuadro;

        if ($MiAgenda)
        {

            if($ultimo)
            {

                $Jugador = new Jugador($_SESSION['S_id_usuario']);
                $PlanJugador = $Jugador->obtenerPlanSuscripcion($_SESSION['S_LIGA_ACTUAL']);
                $idPlanJugador = $PlanJugador['id'];

                if ($PlanJugador->esDePago()){
                    $idPlan_visibilida_por_tipo = "SI_PAGO";
                }
                else{
                    $idPlan_visibilida_por_tipo = "NO_PAGO";
                }

                $sql_eventos2="SELECT * FROM mensajes2 WHERE Importancia='eventoferta' and '$fecha_anterior' >=Fecha_INI AND  '$fecha_anterior' <= Fecha_FIN AND time_format(Hora, '%H:%i') >='$hora_anterior' and id_liga=".$_SESSION['S_LIGA_ACTUAL']." AND (idPlan_visibilidad = '$idPlanJugador' OR idPlan_visibilidad = '$idPlan_visibilida_por_tipo' OR idPlan_visibilidad = 'TODOS' OR idPlan_visibilidad = '')";
                //echo "$fecha_anterior y $Fecha";
                //echo $sql_eventos2." eee<br>";

                $roweventos2=mysql_query($sql_eventos2);
                $eventos2="";
                while($row2=mysql_fetch_array($roweventos2))
                {

                    $mensaje = $row2['Mensaje'];
                    //JMAM: Fuerza HTTPS en las URLS
                    $mensaje = str_replace("http:", "https:", $mensaje);

                    mostrarEvento($row2['id']);

                    /*
                    $inicioX2="<li id='expandable$id_cuadro' class='expandable post caja-texto minusculas'  ><span class='article-containerexpandable$id_cuadro content'><span class='category' style='font-size: small;'>";
                    $cierreX2="</span></span></li>";
                    echo "$inicioX2 <font color='green'><b>".$row2['Hora'].'</b></font> - <font color="#B21010">'.$row2['Titulo']."</font> <br> <span class='minusculas'>".$mensaje."</span> $cierreX2 ";
                    $id_cuadro++;
                    */
                }

                return;

            }



            if($fecha_anterior!=$Fecha)
            {
                $hora_anterior='';
            }

            $hant="";
            if($hora_anterior!='')
                $hant=" and time_format(Hora, '%H:%i')>='$hora_anterior' ";

            $h="";
            if($Hora!='')
                $h=" AND time_format(Hora, '%H:%i') <'$Hora' ";

            $Jugador = new Jugador($_SESSION['S_id_usuario']);
            $PlanJugador = $Jugador->obtenerPlanSuscripcion($_SESSION['S_LIGA_ACTUAL']);
            $idPlanJugador = $PlanJugador['id'];

            if ($PlanJugador->esDePago()){
                $idPlan_visibilida_por_tipo = "SI_PAGO";
            }
            else{
                $idPlan_visibilida_por_tipo = "NO_PAGO";
            }

            $sql_eventos="SELECT * FROM mensajes2 WHERE Importancia='eventoferta' and '$Fecha' >=Fecha_INI AND '$Fecha' <= Fecha_FIN $hant $h and id_liga=".$_SESSION['S_LIGA_ACTUAL']." AND (idPlan_visibilidad = '$idPlanJugador' OR idPlan_visibilidad = '$idPlan_visibilida_por_tipo' OR idPlan_visibilidad = 'TODOS' OR idPlan_visibilidad = '')";

            //echo $sql_eventos;
            $roweventos=mysql_query($sql_eventos);
            $eventos="";
            while($row=mysql_fetch_array($roweventos))
            {
                $mensaje = $row['Mensaje'];
                //JMAM: Fuerza HTTPS en las URLS
                $mensaje = str_replace("http:", "https:", $mensaje);


                mostrarEvento($row['id']);


                /*
                echo "<li id='expandable$id_cuadro' class='expandable post caja-texto' style='list-style:none; border:none !important;'><span class='article-containerexpandable$id_cuadro content'><span class='category' style='font-size: small;'>";

                Interfaz::mostrarTituloSeparador("<img src='images/icon_nota.png' style='width: 20px; height: 20px; float:left; padding-right: 5px'>".$row['Titulo'], $row['Hora']);
                echo "<span class='minusculas'>".$mensaje."</span>";
                echo "</span></span></li>";
                */


                //echo "$inicioX <font color='#a9a9a9'><b>".$row['Hora'].'</b></font> - <font color="#2f4f4f">'.$row['Titulo']."</font> <br> <span class='minusculas'>".$mensaje."</span> $cierreX ";

                $id_cuadro++;
            }

        }

    }
    ///////////////////////
    ///
    ///

    function mostrarEvento($id){
        Log::v(__FUNCTION__, "ID MENSAJE: $id", true);
        $Mensaje = new Mensaje($id);

        $titulo = $Mensaje->obtenerTitulo();
        $mensajeAMostrar = $Mensaje->obtenerMensaje();
        $hora = $Mensaje->obtenerHora();
        $contenedor_botonesVisibilidad_style_display = "none";
        if (strlen($mensajeAMostrar) > 200){
            $contenedor_botonesVisibilidad_style_display = "block";
            $mensajeAMostrar = $Mensaje->obtenerMensaje(true);
        }

        ?>
        <div class="contenedorPrincipal_evento">
            <input type="hidden" id="mostrandoMensajeCorto_<?php echo $id;?>" value="1">
            <div class="contenedor_titulo">
                <?php Interfaz::mostrarTituloSeparador("<img src='images/icon_nota.png' style='width: 20px; height: 20px; float:left; padding-right: 5px'>".$titulo, $hora);?>
            </div>
            <div class="contenedor_mensaje">
                <div id="mensaje_<?php echo $id;?>"><?php echo $mensajeAMostrar;?></div>
            </div>
            <div class="contenedor_botonesVisibilidad" style="display:<?php echo $contenedor_botonesVisibilidad_style_display;?>">
                <?php echo Interfaz::mostrarBoton(Traductor::traducir("Ver mensaje completo"), "onclick_mostrarMensajeCortoOCompleto($id);", "boton_mostrarMensaje_$id");?>
            </div>
        </div>
        <?php
    }

    function listar_partidos ($parecido,$hoja,$ARR,$PAG,$order,$where,$Exportar,$MiAgenda='',$Resultados='')
    {
        Log::v(__FUNCTION__, "En partidos.php", true);
        if ($_GET['menu'] != "miagenda"){
            $whereIdDeporte =" AND ".Partido::COLUMNA_id." NOT IN (SELECT ".ReservaPista::COLUMNA_idPartido." FROM ".ReservaPista::TABLA_nombre." WHERE ".ReservaPista::COLUMNA_idPista." IN (SELECT ".Pista::COLUMNA_id." FROM ".Pista::NOMBRE_TABLA." WHERE ".Pista::COLUMNA_idDeporte." != ".Sesion::obtenerDeporte()->obtenerId()."))";
        }

        $tiempo_inicial = microtime(true);
        if(isset($_GET['pg'])){
            header('Content-Type: text/html; charset=iso-8859-1');
            $fechado=false;
        }
        $numero=$hoja;
        global $_SQL;
        global $_NOMBRECONSULTA;
        global $_Subtitulo;

        $ContadorDePartidosMostrador=0;
        $nomuestra=0;

        $buscador=0;
        $Hoy=date('Y-m-d');
        $Fecha_Limite= date("Y-m-d", strtotime("$Hoy + 4 days"));//Para Cancelación de participación en partidos y Cancelación de busq de sustituto
        $Fecha_Limite2= date("Y-m-d", strtotime("$Hoy + 2 days"));//Para Cancelar un partido
        $Fecha_Limite5= date("Y-m-d", strtotime("$Hoy + 5 days"));//Organizador del partido
        $YO=$_SESSION['S_id_usuario'];
        $Mañana=strtotime("$Hoy + 1 days");
        $Vs=0;$Vc=0;$P9=0;$P18=0;

        ?>
        <script type="text/javascript" src="js/partidos.js<?php echo CacheJSyCSS::obtenerVersionJs();?>"></script>
        <link rel="stylesheet" href="css/partidos/partidos.css<?php echo CacheJSyCSS::obtenerVersionCss();?>"/>
        <?php

        Traductor::cargarTraduccionesJavaScript();
        cargarEstilosGlobales();
        cargarScriptsGlobales();

        if ($_NOMBRECONSULTA=="Mi Agenda de Partidos") $AGENDACURRENT=" id='current'";
        else $PARTIDOSCURRENT=" id='current'";
        if(!isset($_GET['pg'])){
            if 	($_SESSION['S_Version']=='Movil')
            {
                if($_GET['menu']=='misresultadospendientes'){
                    echo"
                                    <ul class='align-center' id='navtabs' style='text-align:center'>
                                    <li><a style='width:32%' href='./partidos.php?menu=misresultadospendientes' title='Resultados pendientes'  id='current'>".Traductor::traducir("Pendientes")."</a></li>
                                    <li><a style='width:32%' href='./jugadores.php?menu=resultados&id=".$_SESSION['S_id_usuario']."' title='Mis resultados'>".Traductor::traducir("Mis result.")."</a></li>
                                    <li><a style='width:32%' href='./jugadores.php?menu=ResultadosJugador' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Res. Jugador")."</a></li>

                                    </ul>
                                    <div id='contentwrap'>
                                    ";
                }else{
                    if($_SESSION['S_TipoDeLiga']!='QUEDADA'){

                        $textoBoton_menuAbrirPartido = obtenerTextoBontonMenuSuperiorAbrirPartido();


                        if (Sesion::obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                            echo"
                                    <ul id='navtabs'  style='text-align:center'>
                                    <li><a style='width:32%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li>
                                    <li><a style='width:32%' href='./partidos.php' title='Partidos' $PARTIDOSCURRENT>".Traductor::traducir("Partidos")."</a></li>
                                    <li><a style='width:32%' href='./partidos_funciones.php?menu=alta' title='Nuevo'>".$textoBoton_menuAbrirPartido."</a></li>
                                    </ul>";
                        }
                        else{
                            echo"
                                    <ul id='navtabs'  style='text-align:center'>
                                    <li><a style='width:48%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li>
                                    <li><a style='width:48%' href='./partidos_funciones.php?menu=alta' title='Nuevo'>".$textoBoton_menuAbrirPartido."</a></li>
                                    </ul>";
                        }

                        echo "<div id='contentwrap'>
                                    ";
                    }else{
                        echo"
                                    <ul id='navtabs'  style='text-align:center'>
                                    <li><a style='width:48%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li>
                                    <li><a style='width:48%' href='./partidos.php' title='Partidos' $PARTIDOSCURRENT>".Traductor::traducir("Partidos")."</a></li>
                                    </ul>
                                    
                                    <div id='contentwrap'>
                                    ";

                    }
                }
            }


        }
        /////////////LA CONSULTA

        if ($_SQL=='')
        {
            $cuantosmostrar = 40;
            //   $q="SELECT * FROM partidos WHERE ((Fecha>=CURDATE() AND Fecha_Result='0000-00-00') OR ((id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." ) AND Fecha>=CURDATE()-1)) AND ((favoritos=0)OR(favoritos=1 AND (SELECT COUNT(id) FROM noesfavorito WHERE (partidos.id_Jugador1>0 AND partidos.id_Jugador1<>".$_SESSION['S_id_usuario']." AND id_Jugador=partidos.id_Jugador1 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") OR (partidos.id_Jugador2>0 AND partidos.id_Jugador2<>".$_SESSION['S_id_usuario']." AND id_Jugador=partidos.id_Jugador2 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") OR (partidos.id_Jugador3>0 AND partidos.id_Jugador3<>".$_SESSION['S_id_usuario']." AND id_Jugador=partidos.id_Jugador3 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") OR (partidos.id_Jugador4>0 AND partidos.id_Jugador4<>".$_SESSION['S_id_usuario']." AND id_Jugador=partidos.id_Jugador4 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario']."))<1)) AND ";  if (!$MiAgenda) $q.="id_Liga=".$_SESSION['S_LIGA_ACTUAL'];

        //241016: en -10 ponia -1

            if ($_GET['menu'] != "miagenda"){
                $condicionandoPartidosPublicadosYNoCompletos = "partidos.id NOT IN (SELECT idPartido FROM reservas_pista WHERE partidoPublico = 0 OR (partidoCompleto = 1 AND (idJugador1!=".$_SESSION['S_id_usuario']." AND idJugador2!=".$_SESSION['S_id_usuario']." AND idJugador3!=".$_SESSION['S_id_usuario']." AND idJugador4!=".$_SESSION['S_id_usuario']."))) AND";
            }

            $whereIdJugador = " ".Partido::COLUMNA_id." IN (SELECT ".PartidoJugador::COLUMNA_idPartido." FROM ".PartidoJugador::TABLA_nombre." WHERE ".PartidoJugador::COLUMNA_idJugador."=".Sesion::obtenerJugador()->obtenerId().")";

            $whereFiltroFecha = "";
            if (isset($_GET['filtrofecha'])){
                $filtroFecha = Fecha::fechaNormalAMYSQL($_GET['filtrofecha']);

                $whereFiltroFecha = " ".Partido::TABLA_NOMBRE.".".Partido::COLUMNA_fecha. " = '$filtroFecha' AND ";
            }

            $q="SELECT * FROM partidos

                WHERE
                        $whereFiltroFecha
                        $condicionandoPartidosPublicadosYNoCompletos
                    (
                            (Fecha>=date_sub(CURDATE(), INTERVAL 1 DAY) AND Fecha_Result='0000-00-00')

                            OR
                            (
                                $whereIdJugador
                                AND Fecha>=date_sub(CURDATE(), INTERVAL 1 DAY) AND Fecha_Result='0000-00-00'
                            )
                        ) AND
                            (
                            (favoritos=0)
                            OR
                                (
                                $whereIdJugador
                                )
                            OR(
                                favoritos=1 AND (
                                                    SELECT COUNT(*) FROM noesfavorito WHERE
                                                                                        (id_Jugador=partidos.id_Jugador1 AND id_Jugador_NO_Favorito=" . $_SESSION['S_id_usuario'] . ")
                                                                                        OR (id_Jugador=partidos.id_Jugador2 AND id_Jugador_NO_Favorito=" . $_SESSION['S_id_usuario'] . ")
                                                                                        OR (id_Jugador=partidos.id_Jugador3 AND id_Jugador_NO_Favorito=" . $_SESSION['S_id_usuario'] . ")
                                                                                        OR (id_Jugador=partidos.id_Jugador4 AND id_Jugador_NO_Favorito=" . $_SESSION['S_id_usuario'] . ")
                                                    )<1
                                )
                            ) ".$whereIdDeporte." AND ";

            //  $q="SELECT * FROM partidos WHERE ((Fecha>=CURDATE() AND Fecha_Result='0000-00-00') OR ((id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." ) AND Fecha>=CURDATE()-1 AND Fecha_Result='0000-00-00')) AND ((favoritos=0)OR(favoritos=1 AND (SELECT COUNT(*) FROM noesfavorito WHERE (id_Jugador=partidos.id_Jugador1 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") AND (id_Jugador=partidos.id_Jugador2 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") AND (id_Jugador=partidos.id_Jugador3 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") AND (id_Jugador=partidos.id_Jugador4 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario']."))<1)) AND ";
            //   $q="SELECT * FROM partidos WHERE ((Fecha>=CURDATE() AND Fecha_Result='0000-00-00') OR ((id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." ) AND Fecha>=CURDATE()-1)) AND ";


            $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
            if ($Liga->esPermitidoMostrarPartidosDeLaLigaPadreYAlias()){
                Log::v(__FUNCTION__, "Mostrar Partidos de la Liga Padre y Alias ", true);

                $array_idsLigasGrupoDeLiga[] = $Liga->obtenerId();

                if($Liga->estaLigaEsAlias()){
                    $array_idsLigasGrupoDeLiga[]  = $Liga->obtenerLigaPadre()->obtenerId();
                }
                else{
                    $array_idsLigaAlias = $Liga->obtenerIdsLigaAliasLiga();
                    if (!empty($idsLigaAlias)){
                        //$array_idsLigasGrupoDeLiga[] = $idLigaAlias;
                        $array_idsLigasGrupoDeLiga = array_merge($array_idsLigasGrupoDeLiga, $array_idsLigaAlias);
                    }
                }

            }
            else{
                $array_idsLigasGrupoDeLiga = $Liga->obtenerIdsLigasPertenecerAlGrupoDeEstaLiga();

            }

            Log::v(__FUNCTION__, "Mostrar Partidos de Ligas: ".print_r($array_idsLigasGrupoDeLiga, true), true);
            $implode_idsLigasGrupoDeLiga = implode(",",$array_idsLigasGrupoDeLiga);


            if ($Liga->esPermitidoMostrarOtrosCampos()){
                $filtro_otrosCampos = " OR Otro_Campo != ''";
            }




            $array_idsClubs = $Liga->obtenerIdsClubs();
            if (count($array_idsClubs) == 1){
                $Club = new Club($array_idsClubs[0]);
                if ($Club->esModuloReservaActivadoParaAlgunCampo()){
                    $filtro_otrosCampos = "";
                }
            }

            if (!$MiAgenda) $q.="id_Liga IN ($implode_idsLigasGrupoDeLiga) AND (id_Campo IN (SELECT id_campo FROM camposporligas WHERE id_liga = ".$_SESSION['S_LIGA_SELECCIONADA'].") $filtro_otrosCampos)";

            //if (!$MiAgenda) $q.="id_Liga".$_SESSION['S_LIGA_ACTUAL']." AND (id_Campo IN (SELECT id_campo FROM camposporligas WHERE id_liga = ".$_SESSION['S_LIGA_SELECCIONADA'].") OR Otro_Campo != '')";


            if ($where!="")  { $q.="  and ".$where;  }
            else if ($parecido!="")
            {
                $coma="'"; $q.=" and ";
                $q.="id like $coma%$parecido%$coma OR id_Liga like $coma%$parecido%$coma OR Fecha like $coma%$parecido%$coma OR Hora like $coma%$parecido%$coma OR TipoPuntuacion like $coma%$parecido%$coma OR id_Campo like $coma%$parecido%$coma OR Otro_Campo like $coma%$parecido%$coma OR id_Jugador1 like $coma%$parecido%$coma OR id_Jugador2 like $coma%$parecido%$coma OR id_Jugador3 like $coma%$parecido%$coma OR id_Jugador4 like $coma%$parecido%$coma OR Puntos_J1 like $coma%$parecido%$coma OR Puntos_J2 like $coma%$parecido%$coma OR Puntos_J3 like $coma%$parecido%$coma OR Puntos_J4 like $coma%$parecido%$coma OR id_Jugador_ApuntaResult like $coma%$parecido%$coma OR Fecha_Result like $coma%$parecido%$coma OR Observaciones like $coma%$parecido%$coma OR Extra1 like $coma%$parecido%$coma OR Extra2 like $coma%$parecido%$coma OR Extra3 like $coma%$parecido%$coma";
            }
            if ($MiAgenda)	{ $q.=" 1 and $whereIdJugador";}

            //Nuevo jm 31/08 13:15, agrego que en MI AGENDA salgan los partidos pendientes de confirmar como pareja  a los que está invitado
            //a través de una consulta union. Optimizo la consulta union para que no tarde.
            if ($MiAgenda)	{

                $q.=" union (select * from partidos where Fecha>=date_sub(CURDATE(), INTERVAL 1 DAY) AND Fecha_Result='0000-00-00' AND id IN (SELECT id_partido FROM partidos_pendientes_pareja WHERE confirmado='N' and id_jugador_invitado=".$_SESSION['S_id_usuario'].") )";
                //echo $q;
            }else

                //NUEVO: INTRODUCIMOS LÓGICA DE PAREJAS
                //Partidos que aunque estan dados de alta, estan por confirmar pareja en la tabla correspondiente.

                //"A mi me salen siempre mis partidos creados para invitar, al resto no les salen, excepto a quien invito que si le sale
                //incluso si no esta confirmado para que este entre y se inscriba"

                $q.="  and partidos.id not in (
                                                select id_partido
                                                FROM partidos_pendientes_pareja
                                                where confirmado!='S'
                                                        AND id_jugador_creador!=".$_SESSION['S_id_usuario']."
                                                        AND id_jugador_invitado!=".$_SESSION['S_id_usuario']."
                                                        AND id_jugador_invitado != partidos.id_Jugador2
                                            )  ";
            //////////////////////////////////OR id_jugador_invitado !=".$_SESSION['S_id_usuario']."///



            $q.= " ORDER BY Fecha asc , Hora asc, id asc";
            //if(!isset($_GET['filtrorapido']) && !isset($_GET['filtrolocalidad']) && !isset($_GET['filtrofecha'])){

            if(!isset($_GET['filtrolocalidad']) && !isset($_GET['filtrofecha'])){
                if(!isset($_GET['pg'])){
                    $pags = 0;
                }else{
                    $pags = $_GET['pg']; echo"PAG=$pags";
                }
                $qrx = $q;
                $q.= " LIMIT ".($pags*$cuantosmostrar).",".$cuantosmostrar;
                //echo $qrx;
            }
        }
        else
        {
            $q=$_SQL;
        }

        $tiempoInicialConsulta = microtime(true);
        //echo $q;
        $si=mysql_query($q); $total_rows=mysql_num_rows ($si);
        $q.=" ".$order;
        //if ($PAG=='on') $q.=" LIMIT $hoja,10";
        //echo $q;
        Log::v(__FUNCTION__, "Consulta: $q", true);

        $si=mysql_query($q);
        $tiempoFinalConsulta = microtime(true);
        $tiempoEjecucionConsulta = $tiempoFinalConsulta - $tiempoInicialConsulta;
        Log::v(__FUNCTION__, "Tiempo ejecución Consulta: $tiempoEjecucionConsulta", true);
        //return;

        //JMAM: Comprueba bloqueo a listado de partidos a visitantes
        if (!$MiAgenda && !Sesion::obtenerLiga()->esPermitidoVerListadoPartidosAVisitantes() && !Sesion::obtenerLiga()->esJugadorActivoEnLiga(Sesion::obtenerJugador()->obtenerId())){
            VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_noTienesPermisosParaVerListadoPartidos);
            die();
        }

        //JMAM: Comprueba bloqueo a listado de partidos a jugadores
        if (!$MiAgenda && !Sesion::obtenerLiga()->obtenerJuegalaliga(Sesion::obtenerJugador()->obtenerId())->esPermitidoVerListadoDePartidos()){
            VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_noTienesPermisosParaVerListadoPartidosBloqueadoPorClub);
            die();
        }

        //echo"<br><br>$total_rows resultados";
        if ($total_rows) //Si hay resultados
        {
            //echo "Resultados: $total_rows | PG: ".$_GET['pg'];
            if(!isset($_GET['pg'])){
                echo"<center>";
                if 	($_SESSION['S_Version']=='Movil' && $_Subtitulo!='')
                    echo "<div class='filtrocontainer2'>";
                echo $_Subtitulo;
                echo"</center>";
                if 	($_SESSION['S_Version']=='Movil' && $_Subtitulo!='')
                    echo "</div>";
            }
            //////////////////////

            ///////////otra tabla  para el resto
            if (!$Resultados) {
                if(!isset($_GET['pg'])){
                    $totalpags = mysql_num_rows(mysql_query($qrx));
                    $tpart = $totalpags;
                    $totalpags = ceil($totalpags/$cuantosmostrar);
                    ?>
                    <script>
                        var pgn=0;
                        var totalpg= <?php echo $totalpags;?>
                    </script>
                    <?php

                    if(!$MiAgenda && $_GET['menu']!='misresultadospendientes'){
                        echo"<div style='text-align:center' id='filtrocontainer2' class='align-center filtrocontainer2'>";
                        $urlanidada = '?';
                        if(isset($_GET['filtrorapido'])){
                            $urlanidada .= '&filtrorapido=1';
                        }
                        if(isset($_GET['filtrolocalidad'])){
                            $urlanidada .= '&filtrolocalidad='.$_GET['filtrolocalidad'];
                        }
                        if(isset($_GET['filtrofecha'])){
                            $urlanidada .= '&filtrofecha='.$_GET['filtrofecha'];
                        }

                        if(isset($_GET['filtrorapido'])){
                            if 	($_SESSION['S_Version']=='Movil'){
                                ?>


                                <a href="partidos.php" class="botonancho optselected CP_filtrorapido" style="width: 31%; display: inline-block; margin-left: 2%;"><?php echo Traductor::traducir("Filtro Rápido");?></a>
                            <?}else{?>
                                <button style="" onclick="document.location='partidos.php'"><?php echo Traductor::traducir("DESACTIVAR")." ".Traductor::traducir("Filtro Rápido");?></button>
                                <?
                            }
                        }else{
                            if 	($_SESSION['S_Version']=='Movil'){
                                ?>

                                <a href="partidos.php<?=$urlanidada?>&filtrorapido=1" class="botonancho botonancho-2 CP_filtrorapido" style="display: inline-block; margin-left: 2.5%;"><?php echo Traductor::traducir("Filtro Rápido");?></a>
                            <?}else{?>
                                <button style="" onclick="document.location='<?=$urlanidada?>&filtrorapido=1'"><?php echo Traductor::traducir("ACTIVAR")." ".Traductor::traducir("Filtro Rápido");?></button>
                                <?php
                            }
                        }


                        if 	($_SESSION['S_Version']=='Movil'){
                            if(isset($_GET['filtrofecha'])){
                                $textfecha = $_GET['filtrofecha'];
                                $styleactivado = 'border-bottom: 1px solid #1469CD;color: #1469CD';
                            }else{
                                $textfecha = Traductor::traducir("Fecha");
                                $styleactivado = '';
                            }
                            ?>


                            <input style="display:inline-block;<?=$styleactivado?>;text-align:center" readonly class=" botonancho-4 botonancho-5 fechac CP_fechapartido" id="fecha" type="text" size="12" value="<? echo $textfecha ?>"/>


                            <?php
                        }else{
                            if(isset($_GET['filtrofecha'])){
                                $textfecha = $_GET['filtrofecha'];
                            }else{
                                $textfecha = date("d/m/Y", time());
                            }
                            ?>
                            <input class="fechac botonancho" id="fecha" type="text" name='Fecha' size="" value="<? echo $textfecha?>"/>



                            <?php



                        }

                        if($_SESSION['S_LIGA_ACTUAL']==45 || $_SESSION['S_LIGA_ACTUAL']==27 || 1==1){
                        if 	($_SESSION['S_Version']=='Movil'){
                            if(isset($_GET['filtrolocalidad']) && $_GET['filtrolocalidad']!=1){
                                $styleactivado2 = 'border-bottom: 1px solid #1469CD;color: #1469CD';
                            }else{
                                $styleactivado2 = '';
                            }
                            ?>
                            <a class="botonancho botonancho-5 CP_selectlocalidad"><select class="botonancho-2" style="<?=$styleactivado2?>;width:100%;display:inline;border:0px;z-index:150;position:relative; margin: 0px; margin-top: -15px" onchange="document.location='<?=$urlanidada?>&filtrolocalidad='+this.value">
                                    <? }else{ ?>
                                    <span ><select style="margin-left:10px;width:100px;" onchange="document.location='<?=$urlanidada?>&filtrolocalidad='+this.value">
                <? } ?>
            <option value="1"><?=Traductor::traducir("Localidad")?></option>
            <?

            $fl = mysql_query("SELECT DISTINCT(Extra3) FROM partidos WHERE Fecha>=date_sub(NOW(), INTERVAL 1 DAY) AND id_Liga=".$_SESSION['S_LIGA_ACTUAL']." ORDER BY Extra3;");
            while($ofl=mysql_fetch_array($fl)){
                if($ofl['Extra3']!=''){
                    ?>
                    <option value="<?=$ofl['Extra3']?>" <?if(isset($_GET['filtrolocalidad']) && $_GET['filtrolocalidad']==$ofl['Extra3']){ echo 'selected';}?>><?=$ofl['Extra3']?></option>
                    <?
                }}

            ?>
            </select></a></div>
                            <script>
                                function cargacalendario(){
                                    $('input.fechac').Zebra_DatePicker({
                                        direction: true,
                                        <?
                                        //if 	($_SESSION['S_Version']=='Movil'){
                                        ?>
                                        readonly_element:true,
                                        <?//} ?>
                                        onSelect: function(){
                                            document.location='<?=$urlanidada?>&filtrofecha='+$('.fechac').val();
                                        }

                                    });
                                    <?
                                    //if 	($_SESSION['S_Version']=='Movil')
                                    //		{
                                    ?>
                                    $('button.Zebra_DatePicker_Icon').css("background","transparent");
            //$('.Zebra_DatePicker_Icon').css('');
                                    <? //} ?>
                                }
                            </script>
                            <?
                        }
                        if 	($_SESSION['S_Version']!='Movil')
                        {

                            //	echo ("<br>");

                        }
                    }

                    if 	($_SESSION['S_Version']!='Movil')
                    {

                        echo ("<table WIDTH=600 id='tpartidos' valign=top align=center border=0 cellpadding=1 rowspacing=2 cellspacing=1  style='margin-left: -7px;'>");
                        echo ("<tr bgcolor='#669999'>");

                    }
                }
            }
            if ($Resultados) {
                if 	($_SESSION['S_Version']!='Movil')
                {
                    echo ("<table WIDTH=600 id='tpartidos' valign=top align=center border=0 cellpadding=1 rowspacing=2 cellspacing=1  style='margin-left: -7px;'>");
                    echo ("<tr bgcolor='#669999'>");
                }
            }
            if ($si)
            {
                $FechaAnterior=0;
                $colortoca=0;
                //////////////fin de la fila de nombres y botón de alta
                $MiHCP=devuelve_un_campo("jugadores",35,"id",$YO);$MiHCP=floor($MiHCP/100)+1;
                $fechafinliga = devuelve_un_campo('liga',4,'id',$_SESSION['S_LIGA_ACTUAL']);
                $fechainicioliga = devuelve_un_campo('liga',3,'id',$_SESSION['S_LIGA_ACTUAL']);
                $Jugador = new Jugador($YO);
                $tiempoInicialWhile = microtime(true);

                while (list($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Extra3,$tienecreditos) = mysql_fetch_array($si))
                {
                    Log::v(__FUNCTION__, "ID Partido: $id", true);
                    $tiempoInicialBucle = microtime(true);

                    $Partido = new Partido($id);
                    $LigaPartido = new Liga($id_Liga);
                    $CampoPartido = new Campo($id_Campo);
                    $ClubPartido = $CampoPartido->obtenerClub();
                    $Jugador1Partido = new Jugador($id_Jugador1);
                    $Jugador2Partido = new Jugador($id_Jugador2);
                    $Jugador3Partido = new Jugador($id_Jugador3);
                    $Jugador4Partido = new Jugador($id_Jugador4);
                    $esReservaPistaPartido = $Partido->esReservaPistaPartido();
                    if ($esReservaPistaPartido){
                        $ReservaPistaPartido = $Partido->obtenerReservaPistaPartido();
                        $esPartidoReservaPistaCompletoPartido = $ReservaPistaPartido->esPartidoCompleto();
                    }
                    $id_PART=$id;
                    /////CONTROLES DE PARTIDO
                    //"ORGANIZO=$Soy_el_organizador<br><3Dias:$Faltan_menos_de_3_dias<br>Partido_Pasado:$Partido_Pasado<br>Abierto:$Partido_Abierto<br>Vacio:$Partido_Vacio<br>$Libre:$Partido_Libre<br>YO:$YO_Juego<br>FaltaResul:$FaltaResultado";

                    //

                    if ($id_Jugador1==$YO) {$Soy_el_organizador='1';} else {$Soy_el_organizador='0';}
                    if (strtotime("$Fecha_Limite")>strtotime("$Fecha")) $Faltan_menos_de_3_dias='1'; else $Faltan_menos_de_3_dias='0';
                    if (strtotime("$Fecha_Limite2")>strtotime("$Fecha")) $Faltan_menos_de_2_dias='1'; else $Faltan_menos_de_2_dias='0';
                    if (strtotime("$Fecha_Limite5")>strtotime("$Fecha")) $Faltan_menos_de_5_dias='1'; else $Faltan_menos_de_5_dias='0';

                    //echo "<br/>HOY: $Hoy $Fecha";
                    if (strtotime("$Hoy")>strtotime("$Fecha")) $Partido_Pasado='1'; else $Partido_Pasado='0';

                    if (($Puntos_J1=='0')&&($Puntos_J2=='0')) $FaltaResultado=1; else $FaltaResultado=0;
                    if (($FaltaResultado)&&(strtotime("$Hoy")<= strtotime("$Fecha"))) $Partido_Abierto='1'; else $Partido_Abierto='0';
                    if (($id_Jugador2=='0')&&($id_Jugador3=='0')&&($id_Jugador4=='0')) $Partido_Vacio='1'; else $Partido_Vacio='0';
                    if (($id_Jugador4=='0')) $Partido_Libre='1'; else $Partido_Libre='0';

                    $Sigue=mysql_query("SELECT id,cancelado FROM partidos_cancelacion WHERE id_partido=$id_PART");
                    $Partido_Cancelado_o_Cancelandose=mysql_result($Sigue,0,1);
                    $id_de_cancelacion=mysql_result($Sigue,0,0);

                    //if ( (No_Es_favorito ($_SESSION['S_id_usuario'],$id_Jugador1))+(No_Es_favorito ($_SESSION['S_id_usuario'],$id_Jugador2))+(No_Es_favorito ($_SESSION['S_id_usuario'],$id_Jugador3))+(No_Es_favorito ($_SESSION['S_id_usuario'],$id_Jugador4)) ) $NoSoyFavorito=1; else $NoSoyFavorito=0;
                    //if ( (No_Es_favorito_Partido ($_SESSION['S_id_usuario'],$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4)) ) $NoSoyFavorito=1; else $NoSoyFavorito=0;
                    //if (($_SESSION['S_id_usuario']==$id_Jugador1)||($_SESSION['S_id_usuario']==$id_Jugador2)||($_SESSION['S_id_usuario']==$id_Jugador3)||($_SESSION['S_id_usuario']==$id_Jugador4) )$NoSoyFavorito=0;

                    //FILTRO DE NIVEL $Partido_Abierto='0'; $YO

                    if (($nivel_max=='')||($nivel_max=='0')) $nivel_max='1899';

                    if (($MiHCP<$nivel_min)||($MiHCP>$nivel_max)){
                        $Partido_Abierto='0';

                        Log::v(__FUNCTION__, "No cumple el nivel -> MI HCP: $MiHCP | Nivel mínimo: $nivel_min | Nivel Máximo: $nivel_max", true);
                    }

                    if (($id_Jugador2==$YO)||($id_Jugador3==$YO)||($id_Jugador4==$YO)||($id_Jugador1==$YO)) $YO_Juego='1'; else $YO_Juego='0';

                    //241016: en -6days ponia -3days
                    //JMAM: Número de días máximo en el que se ve el partido pendiente de grabar resultado en la pantalla de partidos en curso y mi agenda
                    if (strtotime("$Fecha")>(strtotime("$Hoy - 2 days"))) $Pasaronmenosde3Dias=1; else $Pasaronmenosde3Dias=0;
                    if (strtotime("$Fecha")>(strtotime("$Hoy + 1 days"))) $falta1dia=0; else $falta1dia=1;
                    //PArtido pasado pero pendiente de resultado por mi parte
                    $PendienteResultado=1;
                    if ( ($Fecha<$Hoy)&&($YO_Juego)&& ($FaltaResultado)&&(!$Partido_Vacio)&& Estado_Cancelac($id_PART)!='S' && !$Partido_Libre ) $PendienteResultado=1; else $PendienteResultado=0;

                    ////////////nueva fila
                    //$Hoy=date('Y-m-j');
                    //$Fecha_Limite= date("Y-m-d", strtotime("$Hoy + 3 days"));


                    //SIEMPRE FALTA EL TEMA FAVORITOS
                    //  Tema favoritos... para ocultar lo que no sea favorito


                    ////PARTIDOS EN CURSO
                    // Fecha >= Hoy
                    // Y los pendientes de resultado

                    ////PARTIDOS MI AGENDA
                    // Fecha >= Hoy
                    // Yo Juego
                    // Y los pendientes de resultado


                    ////INTRODUCIR RESULTADO
                    // Fecha <= Hoy
                    // Yo Juego
                    // Falta resultado

                    ////MIS RESULTADOS
                    // Yo Juego
                    // No Falta Resultado

                    ////CORREGIR RESULTADOS
                    // Yo Juego
                    // No Falta Resultado
                    // Fecha del partido => Hoy - 3 dias

                    ////Los pendientes de resutado son:
                    // Fecha del partido => Hoy - 3 dias

                if (!$Resultados)
                {
                    $Mostrar=1;
                //echo "Fecha:".strtotime("$Fecha").">=".strtotime("$Hoy - 3 days")."<br> ";

                    if ($Partido_Pasado) $Mostrar=0;

                    if (!$FaltaResultado)  $Mostrar=0;

                    if (($Partido_Pasado)&&($PendienteResultado)&&($Pasaronmenosde3Dias))
                    {
                        if(($Fecha<=$fechafinliga) && ($Fecha>=$fechainicioliga)){
                ////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////// ADAPTACION MOVIL
                ////////////////////////////////////////////////////////////////////////////////////////////////////

                            if 	($_SESSION['S_Version']!='Movil') echo"<tr><td colspan=13><br><b>";
                            else {echo "<ul><li class='post'> <span class='content'><span class='category'>";$TamMovil=" style='font-size:15px; font-weight:600; '";}

                            echo"<font color=red $TamMovil>".Traductor::traducir("PENDIENTE RESULTADO")." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>";

                            if 	($_SESSION['S_Version']!='Movil') echo "</td></tr>";
                            else echo"</span></span></li></ul>";
                            $FechaAnterior=$Fecha;
                            $Mostrar=1;
                        }

                    }





                if ($Fecha>=$Hoy) $Mostrar=1;

                if (($Fecha==$Hoy)&&(!$FaltaResultado)) $Mostrar=0;
            }///if op!=resultados
            else
            {
                if (($Fecha<=$Hoy)&&(!$FaltaResultado)) $Mostrar=1;
                else  $Mostrar=0;

            }
            if(Estado_Cancelac($id_PART)=='S' && !$YO_Juego) $Mostrar=0;
            if(isset($_GET['filtrolocalidad']) && $_GET['filtrolocalidad']!=1){

                if(strtolower($Extra3)!=strtolower($_GET['filtrolocalidad'])){
                    $Mostrar=0;
            //die("filtro localidad puesto");
                }
            }

            if ($NoSoyFavorito && $favoritos)  $Mostrar=0;
            if($Mostrar==1){
                if(isset($_GET['filtrorapido']) && $Partido_Abierto==0){
                    $Mostrar=0;
                }
            //Nuevo, comento esto para tener en cuenta lo de la hora y media antes y despues para poder crear un partido.
            //$fechaconpartido = mysql_num_rows(mysql_query("SELECT id FROM partidos WHERE (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario'].") AND Fecha='".$Fecha."' AND id_Liga='".$id_Liga."'"));
                $fechaconpartido = Tiene_Partido_Sin_Resultado ($Fecha,$_SESSION['S_id_usuario'],$Hora,$id_PART);

                if(isset($_GET['filtrorapido']) && $fechaconpartido>=1 && $id_Jugador1!=$_SESSION['S_id_usuario'] && $id_Jugador2!=$_SESSION['S_id_usuario'] && $id_Jugador3!=$_SESSION['S_id_usuario'] && $id_Jugador4!=$_SESSION['S_id_usuario']){
                    $Mostrar = 0;
                }

                if(isset($_GET['filtrorapido'])){



                    //$Partido = new Partido($id);

                    if ($esReservaPistaPartido){
                        if ($esPartidoReservaPistaCompletoPartido){
                            //echo "Partido Completo";
                            $Mostrar = 0;
                        }
                    }



                }

                $generopartido = $Extra2;

                ////Nuevo: aqui ponemos que si es mixto se muestre o no.
                if($_GET['filtrorapido']){

                    if(!evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id_PART)){
                        $Mostrar=0;
                        //echo 'rechazado '.$id_PART;
                    }else{
                        //echo 'aceptado '.$id_PART;
                    }

                    if(es_mixto($id_PART)) //Si el partido es mixto
                    {
                        if(Partido_Con_Sustituto($id_PART)) //Y algun jugador busca sustituto
                            $Mostrar=1; //Muestra el partido en el filtro rapido
                    }


                    //Nuevo 17/10/14 gestion del filtro rapido y partidos amistosos

                    $partido_amistoso='N';
                    if(partido_es_amistoso($id))
                        $partido_amistoso='S';
                    $puede_crear_amistosos='N';
                    if(puede_crear_amistosos())
                        $puede_crear_amistosos='S';
                    if(! ($partido_amistoso=='N' OR ($partido_amistoso=='S' and $puede_crear_amistosos=='S' )))
                        $Mostrar=0;
                }
                /*
            Esto ya se controla arriba
            if($_GET['filtrorapido'] && $generopartido!='' && $_SESSION['sexo']!=$generopartido){
            $Mostrar = 0;
            }*/



                //$Mostrar=false;

                if(isset($_GET['filtrofecha']) && cambiaf_a_normal($Fecha)!=$_GET['filtrofecha']) $Mostrar=0;
                if($Mostrar==1 && isset($_GET['filtrorapido']) && $id_Jugador4!=0){ //Si el partido esta completo...
                    if($id_Jugador1!=$_SESSION['S_id_usuario'] && $id_Jugador2!=$_SESSION['S_id_usuario'] && $id_Jugador3!=$_SESSION['S_id_usuario'] && $id_Jugador4!=$_SESSION['S_id_usuario'] && $Extra1==''){ //Si NO eres uno de los jugadores y no hay sustitutos
                        $Mostrar = 0;
                    }else{ // SI eres uno de los jugadores o se busca sustituto
                        $Mostrar = 1;

                    }
                }
                if(($Partido_Pasado)&&($PendienteResultado)&&($Pasaronmenosde3Dias)){
                    if(($Fecha<=$fechafinliga) && ($Fecha>=$fechainicioliga)){
                        $Mostrar=1;
                    }else{
                        $Mostrar=0;
                    }
                }
            }


            //echo" $Mostrar";
            //$Mostrar=1;
            //echo"($FechaAnterior!=$Fecha)";

            if ($Mostrar)
            {
            $ContadorDePartidosMostrador++;
            ValoresCampo($id_Campo,$Vs,$Vc,$P9,$P18);
            /*
            if ($TipoPuntuacion=="STABLEFORD 9-Hoyos") $ParCampo=$P9;
            else $ParCampo=$P18;
            */
            //else ECHO "ERROR";
            $ParCampo=$P18;

            //echo $Fecha." ".$Hoy.'<br>'  ;


            if(isset($_GET['pg']) && !$fechado){
                $panterior = mysql_fetch_array(mysql_query($qrx." LIMIT ".(($_GET['pg']*$cuantosmostrar)-1).",1"));
                $FechaAnterior = $panterior['Fecha'];
                $FechaAnteriorMasUno=strtotime($FechaAnterior. ' +1 day');

                $fechado=true;
            }
            //echo $qrx;
            //echo "FAMU es $FechaAnteriorMasUno FA es $FechaAnterior F es $Fecha<br>";


            if (empty($FechaAnterior)){

                $FechaAnterior = Fecha::restarDiasAFecha(date("Y-m-d"), 1);
                $FechaAnteriorMasUno = date("Y-m-d");
            }

            Log::v(__FUNCTION__, "Fecha Anterior: $FechaAnterior | Fecha: $Fecha", true);
            if ($FechaAnterior!=$Fecha)
            {


                gestion_eventos_intercalados($MiAgenda,$FechaAnteriorMasUno,$Fecha,$hora_anterior,$Hora,true); //Pasamos true para ultima fecha

                ///////////////////////////ff/////////////////////////////////////////////////////////////////////////
                /////////////////////////////////// ADAPTACION MOVIL
                ////////////////////////////////////////////////////////////////////////////////////////////////////

                $topSticky_listadoPartidos = "80px";
                if ($_GET['menu']=='miagenda'){
                    $topSticky_listadoPartidos = "38px";
                }


                if 	($_SESSION['S_Version']!='Movil') {$ANT1="<tr><td colspan=13><br>"; $FIN1="</td></tr>";}
                else {$ANT1="<div id='listadoPartidos' style='margin-top:0px; top:".$topSticky_listadoPartidos."'><div class='post'> <div class='content' style=' '><div class='category cabecera'>";$FIN1="</div></div></div></div>";}
                ////////////////////////////////////////////////////////////////////////////////////////////////////
                ////////////////////////////////////////////////////////////////////////////////////////////////////
                if 	($_SESSION['S_Version']!='Movil'){
                    $color1 = 'white';
                    $color2 = 'white';
                }else{
                    $color1 = 'white';
                    $color2 = 'white';
                }

                $div_verde='';
                $fin_div_verde='';
                if 	($_SESSION['S_Version']!='Movil'){
                    $div_verde='<div style="color: rgb(255, 255, 255); background: none repeat scroll 0% 0% rgb(95, 179, 58); margin-top: 10px; text-align: left; padding: 3px 3px 3px 16px;">';
                    $fin_div_verde='</div>';
                }

                /////GESTIONAMOS LOS SALTOS ENTRE DIAS PARA MOSTRAR EL EVENTO ENTRE MEDIO AUNQUE NO HAYA PARTIDOS
                $FechaAnteriorMasUno=strtotime($FechaAnterior. ' +1 day');

                $cont=0;
                while($FechaAnteriorMasUno<strtotime($Fecha) and $cont<=200 and $FechaAnterior!='')
                {

                    $FechaAnteriorMasUno=date('Y-m-d',$FechaAnteriorMasUno);

                    //echo $FechaAnteriorMasUno;
                    if(hay_evento($FechaAnteriorMasUno))
                    {
                        echo"$ANT1 $div_verde<b>".dia_semana($FechaAnteriorMasUno)." ".cambiaf_a_normal($FechaAnteriorMasUno)."</b>$fin_div_verde $FIN1";
                        gestion_eventos_intercalados($MiAgenda,$Fecha,$FechaAnteriorMasUno,$hora_anterior,"",false); //Para antes del primer evento que salga despues de esta cabecera

                    }

                    //echo "Salto Fecha $FechaAnteriorMasUno $Fecha<br>";
                    $FechaAnteriorMasUno=strtotime($FechaAnteriorMasUno. ' +1 day');
                    $cont++;
                }
                ///////////////////////////////////////////////////
                if (strtotime($Fecha)==strtotime($Hoy))
                {
                    echo"$ANT1 $div_verde<b><font color=$color1>".Traductor::traducir("HOY")." ".dia_semana($Fecha)." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
                    //cabecera ();
                }
                else if (strtotime($Fecha)==$Mañana)
                {
                    echo"$ANT1 $div_verde<b><font color=$color2>".Traductor::traducir("MAÑANA")." ".dia_semana($Fecha)." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
                    //cabecera ();
                }
                else
                {
                    echo"$ANT1 $div_verde<b>".dia_semana($Fecha)." ".cambiaf_a_normal($Fecha)."</b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
                    //cabecera ();
                }


                gestion_eventos_intercalados($MiAgenda,$Fecha,$Fecha,$hora_anterior,$Hora,true); //Para antes del primer evento que salga despues de esta cabecera

            }else

                gestion_eventos_intercalados($MiAgenda,$FechaAnterior,$Fecha,$hora_anterior,$Hora,false);
            ////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////// ADAPTACION MOVIL
            ////////////////////////////////////////////////////////////////////////////////////////////////////

            echo "<div>";
            if 	($_SESSION['S_Version']!='Movil') {$ANT2="<tr bgcolor='#FFFFFF' height=\"41\">"; $ANT3="<tr bgcolor='#FFFFFF' height=\"41\">"; $ANT_="<tr bgcolor='lightpink' height=\"41\">"; $FIN1="</td></tr>";
                $clasem ='';
            }
            else {
                $ANT2="<ul style=''>";
                $ANT3="<ul style=''>";
                $ANT_="<ul style='background-color:lightpink;'>";
                $FIN1="</span></li>";
                $clasem ='m';
            }

            /////////Modificado para poner rojo claro los partidos cancelados
            if (Estado_Cancelac($id_PART)=='S') {echo ("$ANT_");$clase=''.$clasem;}
            else
            {
                if ($colortoca!=0){echo ("$ANT2");$clase='fila'.$clasem;$colortoca=0;}
                else {echo ("$ANT3");$clase='fila2 fila_listadoPartidos';$colortoca=1;};
            }



            //echo"$id_PART=".Estado_Cancelac($id_PART)."<br>";

            $titulo1='';$titulo2='';$titulo3='';$titulo4='';

            ////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////// ADAPTACION MOVIL
            ////////////////////////////////////////////////////////////////////////////////////////////////////


            $ANT4="<li class='post caja-texto' style=''> <span class='content' style='display: grid'><span class='category' style=''>";
            $FIN1="</span>";
            $ANT6="<span class='category liga2 CP_loc_partido' style=''>";
            $FIN6="</span>";

            echo ("$ANT4<b class='CP_horapartido'>$Hora</b>$FIN4");
            //////// INICIO NIVEL Y FAVORITOS VERSION MOVIL
            $generopartido = devuelve_un_campo('partidos',23,'id',$id_PART);
            $pluralgenero = '';
            echo '<span style="float:right;text-align:right">';
            $EstiloMovil=" style='float:none;height:auto;width:20px;margin:0;vertical-align: middle;' ";
            $pluralgenero='';
            if($generopartido=='Hombre'){
                $pluralgenero="<img $EstiloMovil ".($_SESSION['S_Version']=='Movil'?'width="32px"':'width="16px"')." src=\"./images/male-icon.png\" title=\"".Traductor::traducir("Partido solo masculino")."\">";
            }
            if($generopartido=='Mujer'){
                $pluralgenero="<img $EstiloMovil ".($_SESSION['S_Version']=='Movil'?'width="32px"':'width="16px"')." src=\"./images/female-icon.png\" title=\"".Traductor::traducir("Partido solo femenino")."\">";
            }
            //Nuevo: icono de mixto para moviles
            $sqlr = mysql_query("SELECT count(*) as res from partidos_mixtos WHERE id=".$id_PART) or die('Error partidos-921: Consulta erronea, consulte con soporte.');
            $dtsr = mysql_fetch_array($sqlr);
            $espartidomixto=intval($dtsr['res']);
            if($espartidomixto>0)
                $partidomixtoimg="<img $EstiloMovil title='Es partido mixto' src='images/mixto.png' alt='Este partido es mixto'>";
            else
                $partidomixtoimg='';

            if (Sesion::obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                if ($mostrar_ranking == "0"){
                    $ANT9Nivela="<span style='' class='CP_nivelpart'>".Traductor::traducir("Nivel")." ".Nivel_en_letras ($nivel_max, '1')." ".Traductor::traducir("a")." ".Nivel_en_letras ($nivel_min,'1')."</span>";
                    $ANT9Nivelb="<span style='' class='CP_nivelpart'>".Traductor::traducir("Nivel mínimo")." ".Nivel_en_letras ($nivel_min, '1')." </span>";
                    $ANT9Nivelc="<span style='' class='CP_nivelpart'>".Traductor::traducir("Nivel Máximo")." ".Nivel_en_letras ($nivel_max, '1')." </span>";
                }
                else{
                    $ANT9Nivela="<span style='' class='CP_nivelpart'>".Traductor::traducir("Nivel")." $nivel_max ".Traductor::traducir("a")." $nivel_min</span>";
                    $ANT9Nivelb="<span style='' class='CP_nivelpart'>".Traductor::traducir("Nivel mínimo")." $nivel_min </span>";
                    $ANT9Nivelc="<span style='' class='CP_nivelpart'>".Traductor::traducir("Nivel Máximo")." $nivel_max </span>";
                }
            }
            //if($favoritos){ echo ("<img src='./images/fav.png' title='FAV' />"); }

            $ANT9Nivelb="<span style='' class='CP_nivelpart'>".Traductor::traducir("Nivel mínimo")." $nivel_min </span>";
            $ANT9Nivelc="<span style='' class='CP_nivelpart'>".Traductor::traducir("Nivel Máximo")." $nivel_max </span>";
            $ANT9SinNivel='';
            $EstiloMovil=" style='float:none;height:auto;width:20px;margin:0 0 0 0.4em;vertical-align: middle;' ";
            // condicion de sergio para mostrar moneda cuando el partido tenga credito
            if($tienecreditos==1){
                echo "<img src='./images/monedagirando.gif' title='Partido de creditos' $EstiloMovil />";
            }
            if ($favoritos)       echo ("<img ".($_SESSION['S_Version']=='Movil'?'width="32px"':'width="16px"')." src='./images/fav.png' title='".Traductor::traducir("SOLO FAVORITOS")."' $EstiloMovil/>  $pluralgenero");
            else if($pluralgenero!=''){
                echo ("$pluralgenero");
            }else{
                echo '';
            }
            echo $partidomixtoimg;
            ////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////
            echo '';
            if (($nivel_min)||($nivel_max!=1899))
            {
                if (($nivel_min)&&($nivel_max!=99)) echo ("$ANT9Nivela");
                else if ($nivel_min) echo ("$ANT9Nivelb");
                else if ($nivel_max!=99) echo ("");
            }
            else  echo ("$ANT9SinNivel");
            echo '<span style="clear:right"></span></span>';

            ////// FIN FAVORITOS Y NIVEL VERSION MOVIL
            //echo"$Partido_Cancelado_o_Cancelandose - $id_de_cancelacion<br>";

            //if ($MiAgenda) $NombreLiga="Liga: ".devuelve_un_campo ("liga",1,"id",$id_Liga)."<br>"; else $NombreLiga='';
            if ($MiAgenda) $NombreLiga=Traductor::traducir("Liga").": ".$LigaPartido->obtenerNombre()."<br>"; else $NombreLiga='';


            if ($Partido->esReservaPistaPartido()){
                if ($Partido->obtenerDeporte()->obtenerId() != Deporte::ID_padel){
                    $NombreLiga = "";
                }
            }


            if ($id_Campo)
            {


                //$TIENE_CONVENIO=devuelve_un_campo ("campos",4,"id",$id_Campo);
                $TIENE_CONVENIO = $CampoPartido[Campo::COLUMNA_tieneConvenio];



                if ($TIENE_CONVENIO=='S') echo  "$ANT5 $NombreLiga<a style='display:inline-block;padding:0px' class='CP_nombreliga_vip' href=\"javascript:Boxy.load('fichacampos.php?id=".$id_Campo."', {title:  '".devuelve_un_campo ("campos",2,"id",$id_Campo)."'});\"><font style='font-weight: bold; color: #1469CD; font-size: ".($_SESSION['S_Version']!='Movil'?'12px':'17px').";'>".devuelve_un_campo ("campos",2,"id",$id_Campo)."</font></a>";
                else echo  "$ANT5 $NombreLiga<a style='display:inline-block;padding:0px' class='CP_nombreliga' href=\"javascript:Boxy.load('fichacampos.php?id=".$id_Campo."', {title:  '".devuelve_un_campo ("campos",2,"id",$id_Campo)."'});\"><font style='color:#004F0C;font-size:".($_SESSION['S_Version']!='Movil'?'12px':'18px')."'>".devuelve_un_campo ("campos",2,"id",$id_Campo)."</font></a>";
            }
            else //OTRO CAMPO
            {
                $TIENE_CONVENIO='N';
                echo  "$ANT5 $NombreLiga<font style='color:gray;font-weight:bold;font-size:".($_SESSION['S_Version']!='Movil'?'12px':'18px')."'>$Otro_Campo</font>";
            }

            if($id_Campo>0){
                //echo  "$ANT6 ".devuelve_un_campo ("campos",10,"id",$id_Campo)." $FIN6";
                echo  "$ANT6 ".$CampoPartido->obtenerLocalidad()." $FIN6";

            }else{
                echo  "$ANT6 ".$Extra3." $FIN6";
            }
            //      	if ($TipoPuntuacion=='STABLEFORD 9-Hoyos') echo "<br>STB. <b>9</b> Hoyos";
            //		if ($TipoPuntuacion=='STABLEFORD 18-Hoyos') echo "<br>STB. <b>18</b> Hoyos";


            //$Recorrido=devuelve_un_campo ("campos",36,"id",$id_Campo);
            if ($TIENE_CONVENIO=='S')
            {

                if 	($_SESSION['S_Version']!='Movil') {echo " <a href=\"javascript:Boxy.load('fichacampos.php?id=".$id_Campo."', {title:  '".$CampoPartido->obtenerNombre()."'});\"><img src='./images/notice-icon.png' title='Convenio: ".$TIENE_CONVENIO."'></a>";}
                //else echo "<span class=metadata style='font-size:0.5em;'>Convenio: ".devuelve_un_campo ("campos",6,"id",$id_Campo)."</span>";
            }

            echo ("$FIN4");


            //echo("<p class='dato'>Par:$ParCampo<br>$Vs,$Vc,$P9,$P18</p></td>");

            //JMAM: Indentifica si se debe de mostrar el ranking
            $QUELIGA = $_SESSION['S_LIGA_ACTUAL'];
            $mostrar_ranking = devuelve_un_campo ('liga',83,'id',$QUELIGA);



            if (!$Resultados)
            {
            //echo ("<td class=$clase width=75 title='HJola'><p class='dato' align=center valign=center>".minificha_jugador ($id,$id_Jugador1,$Soy_el_organizador,$Partido_Abierto,'',$YO_Juego)." ");
            if ($id_Jugador1)
            {//calculo del HcJ
                //$Hex=devuelve_un_campo("jugadores",35,"id",$id_Jugador1);


                $HcJ = $Jugador1Partido->obtenerNivel();
                //$HcJ=$Hex * $Vs / 113 + ( $Vc - $ParCampo);
                //$HcJ=(round($HcJ*10)/10);
                //$titulo1=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";

                //JMAM: Identifica la visualización del nivel
                if ($mostrar_ranking == "0"){
                    $titulo1=Traductor::traducir("Nivel")." ".Traduce_Nivel ($HcJ);
                }
                else{
                    $titulo1=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";
                }

                //echo"SOLICITO CANCELACIÓN";
            }
            if ($id_Jugador2)
            {//calculo del HcJ
                //$Hex=devuelve_un_campo("jugadores",35,"id",$id_Jugador2);$HcJ=$Hex;
                //$HcJ=$Hex * $Vs / 113 + ( $Vc - $ParCampo);
                //$HcJ=(round($HcJ*10)/10);
                //$titulo2=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";

                $HcJ = $Jugador2Partido->obtenerNivel();

                //JMAM: Identifica la visualización del nivel
                if ($mostrar_ranking == "0"){
                    $titulo2=Traductor::traducir("Nivel")." ".Traduce_Nivel ($HcJ);
                }
                else{
                    $titulo2=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";
                }
            }



            if ($id_Jugador3)
            {//calculo del HcJ
                //$Hex=devuelve_un_campo("jugadores",35,"id",$id_Jugador3);$HcJ=$Hex;
                //$HcJ=$Hex * $Vs / 113 + ( $Vc - $ParCampo);
                //$HcJ=(round($HcJ*10)/10);
                //$titulo3=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";

                $HcJ = $Jugador3Partido->obtenerNivel();

                //JMAM: Identifica la visualización del nivel
                if ($mostrar_ranking == "0"){
                    $titulo3=Traductor::traducir("Nivel")." ".Traduce_Nivel ($HcJ);
                }
                else{
                    $titulo3=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";
                }
            }

            if ($id_Jugador4)
            {//calculo del HcJ
                //$Hex=devuelve_un_campo("jugadores",35,"id",$id_Jugador4);$HcJ=$Hex;
                //$HcJ=$Hex * $Vs / 113 + ( $Vc - $ParCampo);
                //$HcJ=(round($HcJ*10)/10);
                //$titulo4=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";

                $HcJ = $Jugador4Partido->obtenerNivel();

                //JMAM: Identifica la visualización del nivel
                if ($mostrar_ranking == "0"){
                    $titulo4=Traductor::traducir("Nivel")." ".Traduce_Nivel ($HcJ);
                }
                else{
                    $titulo4=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";
                }

            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////// ADAPTACION MOVIL
            ////////////////////////////////////////////////////////////////////////////////////////////////////

            //$ANT7a="<span>";$ANT7b="<span>";$ANT7c="<span>";$ANT7d="<span>";
            //$ANT7="<li class='post'> <span class='title'><span class='metadata'>";
            //$ANT8="<li class='post'> <span class='content'><span class='metadata'>";$FIN7="</span>";
            $ANT7a="<td class=$clase width=100 title='$titulo1'>";
            $ANT7b="<td class=$clase width=100 title='$titulo2'>";
            $ANT7c="<td class=$clase width=100 title='$titulo3'>";
            $ANT7d="<td class=$clase width=100 title='$titulo4'>";
            $ANT8="<tr bgcolor='#FFFFFF' height=\"41\">";
            $FIN7="</td>";
            echo '<table width="100%">';
            ////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////




            if ($Partido->obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                ///////////////////////////////////////////GESTION BOTON JUGADOR 1
                Log::v(__FUNCTION__, "Botón Jugador 1", false);
                echo ("$ANT7a".minificha_jugador ($id,$id_Jugador1,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'1')." ");
                echo("$FIN7");
                //echo ("<td><div class=note><p class='typo-icon' align=center>".minificha_jugador ($id,$id_Jugador1,$Soy_el_organizador,$Partido_Abierto,$YO_Juego)."</p></div></td>");



            ///////////////////////////////////////////GESTION BOTON JUGADOR 2
                //OBtenemos los jugadores para mostrar o no el boton de apuntarse controlando si el jugador de ese boton ya esta asignado.
                /*
                $sqlr = mysql_query("SELECT id_Jugador2,id_Jugador3,id_Jugador4 FROM partidos WHERE id=$id") or die("Error partidos-1297: Error al obtener datos de unisexo. Consulte a soporte.");
                $dtsr = mysql_fetch_array($sqlr);


                $id_jugador_2=$dtsr['id_Jugador2'];
                $id_jugador_3=$dtsr['id_Jugador3'];
                $id_jugador_4=$dtsr['id_Jugador4'];
                */
                Log::v(__FUNCTION__, "Botón Jugador 2", false);



                if(es_mixto($id))
                    $partido_es_mixto=='S';
                else
                    $partido_es_mixto=='N';

                $partido_amistoso='N';
                if(partido_es_amistoso($id))
                    $partido_amistoso='S';

                $puede_crear_amistosos='N';
                if(puede_crear_amistosos())
                    $puede_crear_amistosos='S';

                $boton_popup_amistosos='';
                if(!ya_apuntado_a_partido($id))
                {
                    $numero_jugados=puede_crear_amistosos('',true);

                    $style_mvl='';
                    $style_mvl="float:none;height:auto;margin:0 0 0 0em;";

                    $boton_popup_amistosos='
                <a style="display:block" href="javascript:Boxy.load(\'popup_amistosos.php?resultados='.$numero_jugados.'&boxy=1\', {title:  \'Informacion\'});">
                <img style="'.$style_mvl.'" src="./images/images_j/meapuntoOFF.png" onmouseover="this.src=\'./images/images_j/meapuntoON.png\';" onmouseout="this.src=\'./images/images_j/meapuntoOFF.png\';" border="0" width="66" \'="">
                </a>';
                }

                //En cada botón, entra a hacer lo que hacía antes si no es amistoso, o si lo es y se el usuario puede crear/jugar en amistosos.
                //En caso de que se no de esa situación, no muestra la minificha.
                // echo "id partido $id: $partido_amistoso es amistoso y $puede_crear_amistosos puede crear amistosos";




                if($partido_amistoso=='N' OR ($partido_amistoso=='S' and $puede_crear_amistosos=='S' ))
                {
                    //Nuevo: comprobamos que sea un partido por parejas y QUE NO ESTE CONFIRMADO AUN para mostrar la imagen correcta:
                    //$sqlr = mysql_query("SELECT count(*) as res from partidos_pendientes_pareja WHERE confirmado='N' and id_partido=".$id." and (id_jugador_invitado=".$YO." or id_jugador_creador=".$YO.")") or die('Error partidos-1056: Consulta erronea, consulte con soporte.');
                    $sqlr = mysql_query("SELECT count(*) as res from partidos_pendientes_pareja WHERE confirmado='N' and id_partido=".$id." and id_jugador_creador=".$YO) or die('Error partidos-1060: Consulta erronea, consulte con soporte.');
                    $dtsr = mysql_fetch_array($sqlr);
                    $resultado=intval($dtsr['res']);

                    if($resultado>0)
                    {
                        $EstiloMovil=" style='float:none;height:auto;margin:0;vertical-align: middle;' ";

                        $inicio_resalta_pareja="<div><center><img $EstiloMovil src='images/images_j/pareja_espera.png'>";

                        $fin_resalta_pareja="</center></div>";

                        if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_Jugador2!=0) //Si el jugador 2 ya esta establecido muestro el boton porque lo que se va a mostrar es la fichita de el.
                            $minifichaj2=minificha_jugador ($id,$id_Jugador2,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'2','N',$partido_es_mixto);
                        else
                            $minifichaj2=''; //No por mixto o sexo1

                        echo ("$ANT7b $inicio_resalta_pareja ".$minifichaj2." $fin_resalta_pareja");
                    }
                    //Comprobamos que sea un partido por parejas y que SEA EL INVITADO para mostrar la imagen correcta para que se apunte.

                    $sqlr = mysql_query("SELECT count(*) as res from partidos_pendientes_pareja WHERE confirmado='N' and id_partido=".$id." and id_jugador_invitado=".$YO) or die('Error partidos-1093: Consulta erronea, consulte con soporte.');
                    $dtsr = mysql_fetch_array($sqlr);
                    $resultado2=intval($dtsr['res']);

                    if($resultado2>0)
                    {
                        if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_Jugador2!=0)
                            $minifichaj2=minificha_jugador($id,$id_Jugador2,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'2','S',$partido_es_mixto); //Le pasamos "S" para que muestre la imagen diferente de apuntarse para la pareja
                        else
                            $minifichaj2=''; //No por mixto o sexo2

                        echo ("$ANT7b  ".$minifichaj2." ");
                    }

                    //No se ha entrado por ninguno de los dos anteriores casos
                    if($resultado<=0 and $resultado2<=0 )
                    {
                        if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_Jugador2!=0)
                            $minifichaj2=minificha_jugador ($id,$id_Jugador2,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'2','N',$partido_es_mixto);
                        else
                            $minifichaj2=''; //No por mixto o sexo3

                        echo ("$ANT7b  ".$minifichaj2." ");
                    }
                    ///////////////////////////////////////////

                    //echo ("$ANT7b $inicio_resalta_pareja ".minificha_jugador ($id,$id_Jugador2,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'2')." $fin_resalta_pareja");
                    echo("$FIN7");

                }else{ //fin amistosos
                    $spani='<span style="font-size:9px;">';
                    $spanf='</span>';
                    echo ("$ANT7b $spani $boton_popup_amistosos $spanf $FIN7");
                }
            ///////////////////////////////////////////GESTION BOTON JUGADOR 3
                Log::v(__FUNCTION__, "Botón Jugador 3", false);

                if($partido_amistoso=='N' OR ($partido_amistoso=='S' and $puede_crear_amistosos=='S' ))
                {
                    if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_Jugador3!=0)
                        $minifichaj3=minificha_jugador ($id,$id_Jugador3,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'3','N',$partido_es_mixto);
                    else
                        $minifichaj3=''; //No por mixto o sexo

                    echo ("$ANT7c ".$minifichaj3." ");
                    echo("$FIN7");
                }else { //fin amistosos
                    $spani='<span style="font-size:9px;">';
                    $spanf='</span>';
                    echo ("$ANT7c $spani $boton_popup_amistosos $spanf $FIN7");
                }
            ///////////////////////////////////////////GESTION BOTON JUGADOR 4
                Log::v(__FUNCTION__, "Botón Jugador 4", false);

                if($partido_amistoso=='N' OR ($partido_amistoso=='S' and $puede_crear_amistosos=='S' ))
                {
                    if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_Jugador4!=0)
                        $minifichaj4=minificha_jugador ($id,$id_Jugador4,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'4','N',$partido_es_mixto);
                    else
                        $minifichaj4=''; //No por mixto o sexo

                    echo ("$ANT7d ".$minifichaj4." ");
                    echo("$FIN7");


                }else{ //fin amistosos
                    $spani='<span style="font-size:9px;">';
                    $spanf='</span>';
                    echo ("$ANT7d $spani $boton_popup_amistosos $spanf $FIN7");
                }
            }
            else{
            //JMAM: NO es Pádel

            if ($Partido->existeJugadorApuntadoEnElPartido(Sesion::obtenerJugador()->obtenerId())){




            echo ("$ANT7a".minificha_jugador ($id,Sesion::obtenerJugador()->obtenerId(),$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'1')." ");
            echo("$FIN7");


            if($Partido->esReservaPistaPartido()){
                $descripcion = $Partido->obtenerReservaPistaPartido()->obtenerDescripcion();

                if(empty($descripcion)){
                    $array_idsPartidoJugadores = $Partido->obtenerIdsPartidoJugadores();
                    foreach ($array_idsPartidoJugadores as $idPartidoJugador){
                        $PartidoJugador = new PartidoJugador($idPartidoJugador);
                        if ($PartidoJugador->obtenerJugador()->obtenerId() != Sesion::obtenerJugador()->obtenerId()){
                            if (empty($descripcion)){
                                $descripcion = $PartidoJugador->obtenerJugador()->obtenerNombre(true);
                            }
                            else{
                                $descripcion .= ", ".$PartidoJugador->obtenerJugador()->obtenerNombre();
                            }
                        }

                    }
                }

                $numeroJugadores_sinContarOrganizador = $Partido->obtenerReservaPistaPartido()->obtenerNumeroJugadores() - 1;
                $texto_numeroJugadores = "+".$numeroJugadores_sinContarOrganizador;
            }

            $descripcion = $Partido->obtenerReservaPistaPartido()->obtenerDescripcion();
            ?>
            <td class='fila2' width=300>
                <div class='contenedor_descripcionReservaGimnasio' onclick="onclick_abrirModalListadoJugadorPartido(<?php echo  $id;?>, '<?php echo $descripcion;?>')">
                    <div class='descripcion'><?php echo $descripcion;?></div>
                    <div class='informacionNumeroJugadores'><?php echo $texto_numeroJugadores;?></div>
                </div>
                <?php
                echo "
                    $FIN7
                    ";

                }
                else{
                    echo "Error, no estás apuntando a este partido";
                }


                }




                echo '</table>';
                }

                //////////////////FALTA VERSION MOVIL

                else //ES RESULTADOS
                {
                    //J1
                    echo ("<td class=$clase  width=75>");
                    if ($id_Jugador1) echo("<p class='dato' align=center valign=center>".minificha_jugador ($id,$id_Jugador1,$Soy_el_organizador,$Partido_Abierto,$YO_Juego)."</p>");
                    if ($Puntos_J1) echo("<center><div>$Puntos_J1</div>".devuelve_un_campo('resultados',4,'id_partido',$id_PART,'id_jugador',$id_Jugador1)."</center>");
                    echo("</td>");
                    //J2
                    echo ("<td class=$clase  width=75>");
                    if ($id_Jugador2) echo("<p class='dato' align=center valign=center>".minificha_jugador ($id,$id_Jugador2,$Soy_el_organizador,$Partido_Abierto,$YO_Juego)."</p>");
                    if ($Puntos_J2) echo("<center><div><b>$Puntos_J2</b></div>".devuelve_un_campo('resultados',4,'id_partido',$id_PART,'id_jugador',$id_Jugador2)."</center>");
                    echo("</td>");
                    //J3
                    echo ("<td class=$clase width=75>");
                    if ($id_Jugador3) echo("<p class='dato' align=center valign=center>".minificha_jugador ($id,$id_Jugador3,$Soy_el_organizador,$Partido_Abierto,$YO_Juego)."</p>");
                    if (($id_Jugador3)&&($Puntos_J3)) echo("<center><div>$Puntos_J3</div>".devuelve_un_campo('resultados',4,'id_partido',$id_PART,'id_jugador',$id_Jugador3)."</center>");
                    echo("</td>");
                    //J4
                    echo ("<td class=$clase width=75>");
                    if ($id_Jugador4) echo("<p class='dato' align=center valign=center>".minificha_jugador ($id,$id_Jugador4,$Soy_el_organizador,$Partido_Abierto,$YO_Juego)."</p>");
                    if (($id_Jugador4)&&($Puntos_J4)) echo("<center><div>$Puntos_J4</div>".devuelve_un_campo('resultados',4,'id_partido',$id_PART,'id_jugador',$id_Jugador4)."</center>");
                    echo("</td>");

                }
                //NIVEL

                ////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////// ADAPTACION MOVIL
                ////////////////////////////////////////////////////////////////////////////////////////////////////
                //FAVORITOS



                ////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////// ADAPTACION MOVIL
                ////////////////////////////////////////////////////////////////////////////////////////////////////



                //Nuevo: Comprobamos si es un partido de parejas que ya esta confirmado con las dos parejas, para mostrarle los iconos correspondientes
                $sqlr = mysql_query("SELECT count(*) as res from partidos_pendientes_pareja WHERE confirmado='S' and id_partido=".$id) or die('Error partidos-1060: Consulta erronea, consulte con soporte.');
                $dtsr = mysql_fetch_array($sqlr);
                $resultado3=intval($dtsr['res']);
                //if($resultado3>0)
                //$Observaciones=$Observaciones.' ';
                //$Observaciones=$Observaciones.Traductor::traducir("Los jugadores 1 y 2 van juntos como pareja.");

                //Nuevo: Comprobamos si es un partido amistoso, para mostrarle los iconos correspondientes
                $amistoso=false;
                if(partido_es_amistoso($id)){
                    $amistoso=true;
                    //$Observaciones=$Observaciones.' ';
                }

                echo"</span>";
                $EstiloMovil=" style='float:none;height:auto;width:10px;margin:0 0 0 0.4em;' ";

                //movil



                //$Partido = new Partido($id);

                $informacionReservaPago = "";
                $informacionReservaPista = "";
                if($esReservaPistaPartido){

                    $ReservaPista = $ReservaPistaPartido;
                    $nombrePista = $ReservaPista->obtenerPista()->obtenerNombre();
                    $duracion = $ReservaPista->obtenerDuracion(true);


                    if ($Soy_el_organizador){
                        /*
                        if ($ReservaPista->esPagadoJugador1() == false && $ReservaPista->obtenerTipoPagoJugadorReserva() == ReservaPista::TIPOPAGOJUGADORRESERVA_TPV){
                            $informacionReservaPago = "
                                <a href='$PHP_SELF?menu=modificar&id=$id&pagarReservaPista=1'>
                                    <div class='boton_reservaPendientePagoOrganizador'>Pendiente de Pago</div>
                                </a>";

                        }
                        */
                    }
                    else{
                        /*
                        if ($Partido->esJugadorApuntadoAPartido($_SESSION['S_id_usuario']) == true && $ReservaPista->esJugadorPagadoReserva($_SESSION['S_id_usuario']) == false){

                            $informacionReservaPago = "
                                <a href=''>
                                    <div class='boton_reservaPendientePagoJugador'>Pendiente de Pago</div>
                                </a>";

                        }
                        */
                    }

                    $idTipoReserva = $ReservaPista->obtenerIdTipoReserva();

                    if ($idTipoReserva != TipoReserva::ID_TIPORESERVA_SIN_RESERVA){
                        if ($idTipoReserva == TipoReserva::ID_TIPORESERVA_CLASES){
                            $informacionReservaPista .= "[".Traductor::traducir("CLASES")."]";
                        }

                        if (!empty($ReservaPista->obtenerPista()->obtenerUrlPatrocinador())){
                            $urlPatrocinador = $ReservaPista->obtenerPista()->obtenerUrlPatrocinador();
                            $informacionReservaPista .= " <a href='$urlPatrocinador' target='_blank' style='text-decoration: underline;'><b style='color: green; font-weight: 900'>$nombrePista</b></a>";
                        }
                        else{
                            $informacionReservaPista .= " <b style='color: green'>$nombrePista</b>";
                        }
                        if ($duracion != 90){
                            $informacionReservaPista .= " - $duracion ".Traductor::traducir("minutos");
                        }
                        else{
                            Log::v(__FUNCTION__, "Duración de 90 minutos", true);
                        }

                        if ($ReservaPista->obtenerClub()->esActivadoAutomatizacionPuertas() && $MiAgenda){
                            $informacionReservaPista .= " - <b>".Traductor::traducir("Pin Acceso").": ".$ReservaPista->obtenerNumeroPinAcceso()."</b>";
                        }

                        if ($ReservaPista->esPartidoCompleto()){
                            $informacionReservaPista .= " - <b style='color: red'>".Traductor::traducir("Partido Completo")."</b>";
                        }
                    }


                }
                /*
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
                */


                //$Partido = new Partido($id);
                //$Jugador = new Jugador($_SESSION["S_id_usuario"]);


                $idPartido = $id;
                Log::v(__FUNCTION__, "ID PARTIDO: $idPartido", true);

                $Campo = $CampoPartido;
                $Club = $ClubPartido;
                if ($Club->puedeAdministrarAbrirPartidoDesdePanelReservar($_SESSION['S_LIGA_ACTUAL'])){
                    if ($Campo->activadoModuloReserva() && $Partido->esPartidoPreReservaDePista()){

                        //print_r($Partido);

                        if ($Jugador->obtenerId() == $Partido->obtenerJugadorOrganizador()->obtenerId()){
                            if ($Partido->puedeJugadorOrganizarRealizarReservaPistaYa()){

                                if ($LigaPartido->obtenerId() == $_SESSION["S_LIGA_ACTUAL"]){
                                    $informacionReservaPista .= "<a class='boton_infoReservarPistaAhora' href='".WWWBASE."partidos_funciones.php?menu=modificar&id=$id' target='_blank'>".Traductor::Traducir("¡RESERVA PISTA AHORA!")."</a>";
                                }
                                else{
                                    $nombreLigaPartido = $LigaPartido->obtenerNombre(true);
                                    $urlEditarPartido = $Partido->obtenerEnlaceEditarPartido();
                                    $informacionReservaPista .= "<a class='boton_infoReservarPistaAhora' href='$urlEditarPartido' target='_blank'>".Traductor::Traducir("RESERVA PISTA EN LA LIGA").": $nombreLigaPartido</a>";
                                }
                            }
                            else{
                                $fechaPuedeReservarPista = formatearFecha($Partido->obtenerFechaMYSQLQueSePuedeReservarPista(), false, false, true);
                                $informacionReservaPista .= " - <b style='color: red'>".Traductor::Traducir("Pendiente reservar a partir del").": <u> $fechaPuedeReservarPista</u></b>";
                            }
                        }
                        else{
                            if ($Partido->puedeJugadorOrganizarRealizarReservaPistaYa()){
                                $informacionReservaPista .= " - <b style='color: red'>".Traductor::Traducir("Pista Pendiente de Reservar")."</b>";
                            }
                            else{
                                $fechaPuedeReservarPista = formatearFecha($Partido->obtenerFechaMYSQLQueSePuedeReservarPista(), false, false, true);
                                $informacionReservaPista .= " - <b style='color: red'>".Traductor::Traducir("Pendiente reservar a partir del").": <u> $fechaPuedeReservarPista</u></b>";
                            }
                        }

                    }
                }



                if ($informacionReservaPago or $informacionReservaPista or $Observaciones or $amistoso or $resultado3){
                    Log::v(__FUNCTION__,"ID PARTIDO: $idPartido", true);

                    $iconoPista = "icon_pista.png";
                    switch ($Partido->obtenerDeporte()->obtenerId()){
                        case Deporte::ID_gimanasio:
                            $iconoPista = "icon_gimnasio.png";
                            break;

                        default:
                            break;
                    }

                    echo"<span class='metadata2' style='float:none; margin-bottom: 2px; width: fit-content; display: flow-root;'>".$informacionReservaPago.($informacionReservaPista?'<div style="display: flex"><img src="./images/'.$iconoPista.'" style="width: 30px; height: auto; max-height:15px; object-fit: contain; float: left" ">&nbsp;'.$informacionReservaPista.'</div>':'').($resultado3>0?'<img style="width:20px;height:20px;float:left;" '.$EstiloMovil.' src="images//boton%20pareja.png">&nbsp;'.Traductor::traducir("Los jugadores 1 y 2 van juntos como pareja."):'').($amistoso?'<img style="width:20px;height:18px;float:left;" '.$EstiloMovil.' src="images/Amistoso_sin_ranking.png"> '.Traductor::traducir("Partido Amistoso. No puntuable").'':'').($Observaciones?'<img src="./images/icon-notes.png" '.$EstiloMovil.' ">&nbsp;'.htmlentities($Observaciones):'')."</span>";
                }


                //Nuevo: Comprobamos si es un partido de parejas que ya esta confirmado con las dos parejas, para mostrarle los iconos correspondientes
                /*$sqlr = mysql_query("SELECT count(*) as res from partidos_pendientes_pareja WHERE confirmado='S' and id_partido=".$id) or die('Error partidos-1060: Consulta erronea, consulte con soporte.');
            $dtsr = mysql_fetch_array($sqlr);
            $resultado3=intval($dtsr['res']);
            if($resultado3>0)
            $Observaciones=$Observaciones.Traductor::traducir("Los jugadores 1 y 2 van juntos como pareja.");
            */
                //Nuevo: Comprobamos si es un partido amistoso, para mostrarle los iconos correspondientes
                /*if(partido_es_amistoso($id))
            $Observaciones=$Observaciones.' Partido Amistoso. No puntua para Ranking.';
            */
                /*echo"</span>";
            $EstiloMovil=" style='float:none;height:auto;width:10px;margin:0 0 0 0.4em;' ";


            if ($Observaciones) echo"<span class='metadata' style='float:none;font-size:0.5em;''>".($resultado3>0?'<img style="width:10px;height:10px;float:left;" src="images/pareja.gif"'.$EstiloMovil.'>':'')."<img src='./images/icon-notes.png' $EstiloMovil> ".htmlentities($Observaciones)."</span>";
            */




                ////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////// BOTONES
                ////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////Modificado para poner rojo claro los partidos cancelados
                ///
                ///
                ///

                if ($Partido->obtenerDeporte()->obtenerId() != Deporte::ID_padel){

                    $EstiloMovil="class='botonancho-3 botontrans CP_iconsjota' style='padding: 10px;float:none;height:20px;width:20px;margin:0 0 0 10px;display:inline-block'; ";
                    $EstiloA=" style='display:inline;float:center;' ";
                    $EstiloTamanoImg=" style='height:20px;width:20px;margin:0px;' ";

                    echo "<tr><td colspan='4'><center><div style='display:inline-flex; text-align: center; margin-top: 15px'>";
                    Log::v(__FUNCTION__, "Yo Juego", false);


                    if(mostrar_confirmacion_boxy())
                    {
                        include("alerts_android.php");

                        echo ("<a onclick=\"return confirmacion('".Traductor::traducir("Cancelar participacion")."','".Traductor::traducir("¿Seguro que deseas cancelar tu participacion?")."',1,'partidos_funciones.php?menu=cancelar_participacion&id=".$id."');\"   $EstiloMovil href='partidos_funciones.php?menu=cancelar_participacion&id=$id' ><img src='".Sesion::obtenerDeporte()->obtenerUrlIconoEliminarJugador()."' border='0' title='".Traductor::traducir("CANCELAR PARTICIPACION")."' $EstiloTamanoImg></a>$FINBotones");

                    }
                    else
                    {
                        echo ("<a $EstiloMovil href='partidos_funciones.php?menu=cancelar_participacion&id=$id' onclick=\"return confirm('".Traductor::traducir("¿Seguro que deseas cancelar tu participacion?")."')\"><img src='".Sesion::obtenerDeporte()->obtenerUrlIconoEliminarJugador()."' border='0' title='".Traductor::traducir("CANCELAR PARTICIPACION")."' $EstiloTamanoImg></a>$FINBotones");

                    }

                    $datorx = mysql_fetch_array(mysql_query("SELECT tiporecordatorio FROM jugadores WHERE id=".$_SESSION['S_id_usuario']));
                    if($datorx['tiporecordatorio']>0){
                        $dator = mysql_fetch_array(mysql_query("SELECT * FROM recordatorios WHERE id_partido=".$id_PART." AND id_jugador=".$_SESSION['S_id_usuario']));
                        if($dator['tiempo']>0){
                            if($dator['estado']==1){
                                $pasadofuturo = Traductor::traducir("enviado");
                                $env = 'env';
                            }else{
                                $pasadofuturo = Traductor::traducir("programado para");
                                $env = '';
                            }
                            ?>
                            <a <?=$EstiloMovil?> href="recordatorios.php?idr=<?=$dator['id']?>"><img src="./images/images_j/clock<?=$env?>.png"
                                                                                                    title="<?php echo Traductor::traducir("Recordatorio")." ".$pasadofuturo." ".Traductor::traducir("el")." ".date('d/m/y',$dator['tiempo'])." ".Traductor::traducir("a las")." ".date('H:i',$dator['tiempo'])?> &#13;[<?php echo Traductor::traducir("Click para editar"); ?>]" <?=$EstiloTamanoImg?>></a>
                            <?
                        }else{
                            ?>
                            <img src="./images/images_j/clock.png" title="<?=Traductor::traducir("No tienes recordatorio para este partido")?>" <?=$EstiloTamanoImg?>></a>
                            <?
                        }
                    }else{
                        ?>
                        <a <?=$EstiloMovil?> href="recordatorios.php"><img src="./images/images_j/clockdes.png" title="<?= Traductor::traducir("No tienes los recordatorios activados")?> &#13;[<?=Traductor::traducir("Click para activarlos")?>]" <?=$EstiloTamanoImg?>></a>
                        <?
                    }

            //echo ("<a href=\"javascript:popWindow('tarjetas.php?id=$id',800,330);\"><img src='./images/Tarjeta.gif' border='1' width='20' title='SACAR TARJETA DE PARTIDA'></a></td><td>");
            //echo "<a href=\"javascript:popWindow('tarjetas.php?id=$id',500,330);\"><img src='./images/editar.gif' border='1' width='20' title='Ficha de $Nombre'></a>";


                    //else echo"</div>";
                    echo("</div></center></td></tr>");
                }


                if (Estado_Cancelac($id_PART)=='S' || $Partido->obtenerDeporte()->obtenerId() != Deporte::ID_padel) $Mostraralgunboton=0;
                else $Mostraralgunboton=1;

                if (Estado_Cancelac($id_PART)=='N')  $PartidoCancelandose=1;
                else $PartidoCancelandose=0;

                $FINBotones="";
                $DIVMOVIL="<div >";
                $EstiloMovil="class='botonancho-3 botontrans CP_iconsjota' style='padding: 10px;float:none;height:20px;width:20px;margin:0 0 0 10px;display:inline-block'; ";
                $EstiloA=" style='display:inline;float:center;' ";
                $EstiloTamanoImg=" style='height:20px;width:20px;margin:0px' ";
                echo"<center><div style='margin: 0;width:auto; vertical-align=middle;' valign=center>";

                if ($Partido->obtenerTiempoFaltaParaInicioPartidoEnMinutos() <= 10 && $Partido->obtenerNumeroJugadores(true) == 4 && $_GET['menu']=="miagenda"){
                    ?>
                    <a <?php echo $EstiloMovil;?> href="<?php echo WWWBASE_RAIZ;?>sorteador/index.php?TIPO=DESDEDENTRO&idPartido=<?php echo $Partido->obtenerId();?>" target='_blank'><img src='./images/images_j/dado.png' title='<?php echo Traductor::traducir("Sortear Parejas");?>'<?php echo $EstiloTamanoImg;?>></a>
                    <?php

                }
                else{

                    //JMAM: START Botón Whatsapp ////////////////////////////////////////////////////////////////
                    //echo $_SESSION['S_Version'];
                    //JMAM: Comprueba si es al versión móvil
                    //JMAM: Es la versión móvil

                    $titulo_push=Traductor::traducir("Apúntate al partido");
                    $fecha_p=$Fecha;
                    $hora_p=$Hora;
                    $nivel_min_p=$nivel_min;
                    $nivel_max_p=$nivel_max;

                    $date = new DateTime($fecha_p);

                    $dia_p=$date->format('d');
                    $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
                    $dia_sem = $dias[date('N', strtotime($fecha_p))];
                    //$dia_sem=strftime('%A',strtotime($fecha_p));

                    $idp_usar= $id;

                    $id_campo=devuelve_un_campo('partidos',5,'id',$idp_usar);
                    if($id_campo>0)
                        $nombre_campo=devuelve_un_campo('campos',2,'id',$id_campo);
                    else
                        $nombre_campo=devuelve_un_campo('partidos',6,'id',$idp_usar);

                    $dia_evento=ucfirst(getDiaEvento($fecha_p));

                    $mensaje_push=$dia_evento.' a las '.$hora_p.' h en '.$nombre_campo;
                    if(intval($nivel_min)>0 and intval($nivel_max)>0)
                        $mensaje_push.=". Nivel $nivel_max al $nivel_min";
                    else
                        $mensaje_push.=". Nivel 18 al 1";

                    $idLiga = $_SESSION['S_LIGA_ACTUAL'];
                    $fechaCodificada = cambiaf_a_normal($Fecha);

                    $parametrosCodificados = base64_encode("0,$idLiga,$fechaCodificada");

                    $urlCompartirPartido = WWWBASE_RAIZ."app?cd=$parametrosCodificados";

                    //JMAM: URL a compartir
                    $urlCompartirWhatsapp = $Partido->obtenerUrlEnlaceCompartirPorWhatsApp();


                    Log::v(__FUNCTION__,"¿ES ORGANIZADOR?: $Soy_el_organizador | ¿Falta Resultado?: $FaltaResultado | ¿Partido Pasado?: $Partido_Pasado | ¿Mostrar Botón? $Mostraralgunboton",false);

                    if (($Soy_el_organizador)&&($FaltaResultado)&&(!$Partido_Pasado) && ($id_Liga==$_SESSION[
                            'S_LIGA_ACTUAL']) && $Mostraralgunboton && $_SESSION['S_TipoDeLiga']!='QUEDADA'){

                        echo "
                            <input type='hidden' id='urlCompartirWhatsapp_$id' value='$urlCompartirWhatsapp'/>
                            <a $EstiloMovil href='$urlCompartirWhatsapp' target='_blank'><img src='./images/images_j/ico_whatsapp.png' title='".Traductor::traducir("Avisar en mi grupo de amigos para que se apunten al partido")."' $EstiloTamanoImg></a>
                            ";

                    }
                    else if ((!$Soy_el_organizador)&&($FaltaResultado)&&(!$Partido_Pasado) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']) && $Mostraralgunboton && $_SESSION['S_TipoDeLiga']!='QUEDADA'){

                        if (ya_apuntado_a_partido($id)){
                            echo "
                                <input type='hidden' id='urlCompartirWhatsapp_$id' value='$urlCompartirWhatsapp'/>
                                <a $EstiloMovil href='$urlCompartirWhatsapp' target='_blank'><img src='./images/images_j/ico_whatsapp.png' title='".Traductor::traducir("Avisar en mi grupo de amigos para que se apunten al partido")."' $EstiloTamanoImg></a>
                                ";
                        }
                    }

                    //JMAM: END Botón Whatsapp ////////////////////////////////////////////////////////////////


                }




                if (($Soy_el_organizador)&&($FaltaResultado)&&(!$Partido_Pasado) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']) && $Mostraralgunboton && $_SESSION['S_TipoDeLiga']!='QUEDADA')
                {
                    echo ("<a $EstiloMovil href='partidos_funciones.php?menu=modificar&id=$id'><img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/editar.png' border='0' title='".Traductor::traducir("EDITAR PARTIDO")."' $EstiloTamanoImg></a>$FINBotones");
            // LINEA COMENTADA POR DIAS DE LLUVIA


                    /*
                    $Jugador = new Jugador($_SESSION['S_id_usuario']);
                    $Partido = new Partido($id);
                    */

                    if($Partido_Libre || !$falta1dia){

                        $botonCancelarPartido = ("<a $EstiloMovil href='$PHP_SELF?menu=confirmar&id=$id&id_jugadores=$id_Jugador1;$id_Jugador2;$id_Jugador3;$id_Jugador4''><img src='./images/images_j/borrar.png' ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')."  border='0' title='".Traductor::traducir("BORRAR PARTIDO")."' $EstiloTamanoImg></a>$FINBotones");


                        //JMAM: Si el Partido está Libre, si falta más de 1 día o si es una Reserva de Pista, mostrar el botón borrar partido
                        if ($esReservaPistaPartido){
                            $ReservaPista = $ReservaPistaPartido;

                            if ($Jugador->puedeCancelarLaReserva($ReservaPista) == false){
                                //JMAM: Jugador no puede cancelar

                                //$emailContactoClub = $Club = $Partido->obtenerCampo()->obtenerClub()->obtenerEmailContacto();
                                $horasAntesCancelar = $Jugador->obtenerHorasAntesCancelar($ReservaPista);


                                $titulo_modalCancelarPartido = Traductor::traducir("MODAL_CANCELAR_PARTIDO_JUGADOR_TITULO");
                                $mensaje_modalCancelarPartido = str_replace("%HORAS_ANTELACION%", $horasAntesCancelar, Traductor::traducir("MODAL_CANCELAR_PARTIDO_JUGADOR_MENSAJE"));


                                $estiloBotonCancelarPartido = "style='height:20px;width:20px;filter: grayscale(1);margin:0px;'";
                                $botonCancelarPartido = "<a onclick=\"return confirmacion('$titulo_modalCancelarPartido','$mensaje_modalCancelarPartido',1,'');\" $EstiloMovil><img src='./images/images_j/borrar.png' ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')."  border='0' title='".Traductor::traducir("BORRAR PARTIDO")."' $estiloBotonCancelarPartido></a>$FINBotones";

                            }
                        }


                        echo $botonCancelarPartido;

                    }

                }


                if ((!$Soy_el_organizador) && ($Partido_Abierto) && ($Partido_Libre) && (!$YO_Juego) && ($FaltaResultado) && $Mostraralgunboton)
                {
                    Log::v(__FUNCTION__, "No se muestra nada", true);
                }
                else {
                    Log::v(__FUNCTION__, "Se muestra algo", true);


                    if (    (!$Partido_Vacio) && ($YO_Juego) && ($Fecha>(date("Y-m-d", strtotime("$Hoy - 10 days")))) && (time()>=strtotime("$Fecha $Hora")) && $Mostraralgunboton)
                        if (!$Resultados)
                        {


                            //CONTROL PARA SOLO PERMITIR RESULTADOS ENTRE LA FECHA_INI y la FECHA_FIN
                            // y NO $Partido_Libre es decir partido completo 4 jugadores

                            $Liga = new Liga($id_Liga);
                            $fechaInicio = $Liga->obtenerFechaInicio();
                            $fechaFin = $Liga->obtenerFechaFin();


                            Log::v(__FUNCTION__, "ID LIGA Partido: $id_Liga | ID PARTIDO: $id | FECHA: $Fecha | FECHA INICIO: $fechaInicio | FECHA FIN: $fechaFin | PARTIDO LIBRE: $Partido_Libre PARTIDO VACIO: $Partido_Vacio | JUEGO: $YO_Juego | MOSTRAR BOTÓN:$Mostraralgunboton | ID LIGA: $id_Liga | ID LIGA SESIÓN: ".$_SESSION['S_LIGA_ACTUAL'], true);

                            if ((!$Partido_Libre) && (($Fecha<=date("Y-m-d",strtotime($fechaFin))) && ($Fecha>=date("Y-m-d", strtotime($fechaInicio)))) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']))
                            {


                                /*nuevo jm 27-ago-15
            Dicho de otra forma ms sencilla: Si estamos en los ltimos 10 das de liga y el partido es amistoso,
            no debe salir el "icono" que nos permite meter el resultado (ni en mvil ni en web) por lo que lgicamente
            ya no se puede meter resultado
            */
                                $fechahoy = date("Y-m-d");
                                //$fechafinliga = devuelve_un_campo('liga',4,'id',$id_Liga);
                                $fechafinliga = $LigaPartido->obtenerFechaFin();

                                $dias= (strtotime($fechahoy)-strtotime($fechafinliga))/86400;
                                $dias = abs($dias); $dias = floor($dias);
                                //echo $dias;

                                /*
                                //JMAM: Número de días máximo en el que se ve el partido pendiente de grabar resultado en la pantalla de partidos en curso y mi agenda
                                $quedan_10_dias_liga=($dias<=5);
                                $este_partido_es_amistoso=partido_es_amistoso($id);
                                $poner_boton_resultado=true;

                                if($este_partido_es_amistoso && $quedan_10_dias_liga)
                                    $poner_boton_resultado=false;
                                */

                                Log::v(__FUNCTION__, "¿Poner botón Mis Resultados?: $poner_boton_resultado", true);


                                if(R)
                                {
                                    echo ("<a $EstiloMovil  href='$PHP_SELF?menu=resultado&id=$id'><img src='./images/images_j/resultado.png' border='0'title='".Traductor::traducir("GRABAR RESULTADO")."' $EstiloTamanoImg></a>$FINBotones");

                                }
                            }

            //echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
                        }
                        else
                        {
                            if ( $_SESSION['S_id_usuario']==$id_Jugador_ApuntaResult && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']))
                                echo ("<a  $EstiloMovil href='$PHP_SELF?menu=resultado&id=$id'><img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/editar.png' border='0' title='".Traductor::traducir("CORREGIR RESULTADO")."' $EstiloTamanoImg></a>");



                            echo("$FINBotones");
                        }
                }

                if (!($Faltan_menos_de_3_dias)&&($YO_Juego) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL'])&& $id_Jugador1!=$_SESSION['S_id_usuario']  && $Mostraralgunboton)
                {
                    Log::v(__FUNCTION__, "NO Falta menos de 3 días", true);

                    if(mostrar_confirmacion_boxy())
                    {
                        include("alerts_android.php");

                        echo ("<a onclick=\"return confirmacion('".Traductor::traducir("Cancelar participacion")."','".Traductor::traducir("¿Seguro que deseas cancelar tu participacion?")."',1,'partidos_funciones.php?menu=cancelar_participacion&id=".$id."');\"   $EstiloMovil href='partidos_funciones.php?menu=cancelar_participacion&id=$id' ><img src='./images/images_j/borrarse.png' border='0' title='".Traductor::traducir("CANCELAR PARTICIPACION")."' $EstiloTamanoImg></a>$FINBotones");

                    }
                    else
                    {
                        echo ("<a $EstiloMovil href='partidos_funciones.php?menu=cancelar_participacion&id=$id' onclick=\"return confirm('".Traductor::traducir("¿Seguro que deseas cancelar tu participacion?")."')\"><img src='./images/images_j/borrarse.png' border='0' title='".Traductor::traducir("CANCELAR PARTICIPACION")."' $EstiloTamanoImg></a>$FINBotones");

                    }



            //echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
                }
                //////////////////////////////////////////////////////////////////////////////////////////////////////
                //////////////////NUEVO DE JORGE PARA CANCELACION DE PARTIDOS PREVIA CONFIRMACION DE LOS PARTICIPANTES
                ///
                if (!$Mostraralgunboton){

                    if ($Partido->obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                        echo"<center>".Traductor::traducir("PARTIDO CANCELADO")."</center>";
                    }
                    else{
                        //echo"<center>".Sesion::obtenerDeporte()->obtenerNombre()."</center>";
                    }
                }
                //else echo"-$PartidoCancelandose-$Mostraralgunboton-$Faltan_menos_de_3_dias-$Soy_el_organizador";

                if (($Faltan_menos_de_2_dias)&&($Soy_el_organizador)&& !($Partido_Libre) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL'])  && $Mostraralgunboton  && !$PartidoCancelandose && $_SESSION['S_TipoDeLiga']!='QUEDADA')
                {

                    $botonInciarProcesoCancelacion = ("<a $EstiloMovil href='$PHP_SELF?menu=iniciar_proceso_cancelacion&id=$id&id_jugadores=$id_Jugador1;$id_Jugador2;$id_Jugador3;$id_Jugador4''><img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/borrar.png' border='0' title='".Traductor::traducir("SOLICITAR CANCELACIÓN")."' $EstiloTamanoImg></a>$FINBotones");

                    /*
                    $Jugador = new Jugador($_SESSION['S_id_usuario']);
                    $Partido = new Partido($id);
                    */
                    //JMAM: Si el Partido está Libre, si falta más de 1 día o si es una Reserva de Pista, mostrar el botón borrar partido
                    if ($esReservaPistaPartido){
                        $ReservaPista = $ReservaPistaPartido;

                        if ($Jugador->puedeCancelarLaReserva($ReservaPista) == false){
                            //JMAM: Jugador no puede cancelar

                            //$emailContactoClub = $Club = $Partido->obtenerCampo()->obtenerClub()->obtenerEmailContacto();
                            $horasAntesCancelar = $Jugador->obtenerHorasAntesCancelar($ReservaPista);

                            $titulo_modalCancelarPartido = Traductor::traducir("MODAL_CANCELAR_PARTIDO_JUGADOR_TITULO");
                            $mensaje_modalCancelarPartido = str_replace("%HORAS_ANTELACION%", $horasAntesCancelar, Traductor::traducir("MODAL_CANCELAR_PARTIDO_JUGADOR_MENSAJE"));

                            $estiloBotonIniciarProcesoCancelacion = "style='height:20px;width:20px;filter: grayscale(1);margin:0px;'";
                            $botonInciarProcesoCancelacion = "<a onclick=\"return confirmacion('$titulo_modalCancelarPartido','$mensaje_modalCancelarPartido',1,'');\" $EstiloMovil><img src='./images/images_j/borrar.png' ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')."  border='0' title='".Traductor::traducir("BORRAR PARTIDO")."' $estiloBotonIniciarProcesoCancelacion></a>$FINBotones";

                        }
                    }


                    echo $botonInciarProcesoCancelacion;



            //echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
                }
                ////AHORA EL CANCELAR EL PROCESO DE CANCELACION
                if (($Faltan_menos_de_2_dias)&&($Soy_el_organizador)&& !($Partido_Libre) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL'])  && $Mostraralgunboton  && $PartidoCancelandose && $_SESSION['S_TipoDeLiga']!='QUEDADA')
                {
                    Log::v(__FUNCTION__, "Falta menos de 2 días", false);
                    echo ("<a $EstiloMovil onclick=\"return confirm('".Traductor::traducir("OJO! Se cancelarán las confirmaciones recibidas, y el partido volverá a estar activo normalmente.")."');\" href='$PHP_SELF?menu=cancelar_proceso_cancelacion&id=$id&id_jugadores=$id_Jugador1;$id_Jugador2;$id_Jugador3;$id_Jugador4''>
                    <img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/borrar.png' border='0' title='".Traductor::traducir("SUSPENDER CANCELACIÓN")."' $EstiloTamanoImg></a>$FINBotones");
            //echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
                }



                if ((($Faltan_menos_de_3_dias)&&($YO_Juego) || ($Faltan_menos_de_5_dias && $Soy_el_organizador && $_SESSION['S_TipoDeLiga']!='QUEDADA')) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']) && $Mostraralgunboton  && !$PartidoCancelandose)
                {
                    Log::v(__FUNCTION__, "Falta menos de 3 días", false);
                    $LosQueBuscanSustitutos=explode(',',$Extra1);

                    if (in_array($YO,$LosQueBuscanSustitutos))
                    {

                        if(mostrar_confirmacion_boxy())
                        {
                            include("alerts_android.php");

                            echo ("<a $EstiloMovil onclick=\"return confirmacion('".Traductor::traducir("CANCELAR BUSQUEDA")."','".Traductor::traducir("CANCELAR BUSQUEDA DE SUSTITUTO//Si aceptas cancelarás la busqueda de sustituto.")."',1,'partidos.php?menu=CancelarBuscarSustituto&datos=".$id.",$YO');\" href=\"partidos.php?menu=CancelarBuscarSustituto&datos=$id,$YO\" title='".Traductor::traducir("CANCELAR BUSQUEDA")."'>" .
                                "<img src='./images/images_j/boton_cancel_sustituto.png' border='0' width=16 title='".Traductor::traducir("CANCELAR BUSQUEDA")."' $EstiloTamanoImg></a>$FINBotones");

                        }
                        else
                        {
                            include("alerts_android.php");
                            echo ("<a $EstiloMovil onclick=\"return confirmacion('".Traductor::traducir("CANCELAR BUSQUEDA")."','".Traductor::traducir("CANCELAR BUSQUEDA DE SUSTITUTO//Si aceptas cancelarás la busqueda de sustituto.")."',1,'partidos.php?menu=CancelarBuscarSustituto&datos=".$id.",$YO');\" href=\"partidos.php?menu=CancelarBuscarSustituto&datos=$id,$YO\" title='".Traductor::traducir("CANCELAR BUSQUEDA")."'>" .
                                //echo ("<a $EstiloMovil onclick=\"return confirm('".Traductor::traducir("CANCELAR BUSQUEDA DE SUSTITUTO//Si aceptas cancelarás la busqueda de sustituto.")."');\" href=\"partidos.php?menu=CancelarBuscarSustituto&datos=$id,$YO\" title='".Traductor::traducir("CANCELAR BUSQUEDA")."'>" .
                                "<img src='./images/images_j/boton_cancel_sustituto.png' border='0' width=16 title='".Traductor::traducir("CANCELAR BUSQUEDA")."' $EstiloTamanoImg></a>$FINBotones");

                        }


                    }
                    else{

                        $estilo_botonBuscarSustituto = "style='height:20px;width:20px;margin:0px;'";
                        $enlace_botonBuscarSustituto = "onclick=\"return confirmacion('".Traductor::traducir("BUSCAR SUSTITUTO")."','".Traductor::traducir("RECUERDA<br/>Recibirás un mensaje en tu móvil cuando te sustituyan<br/>Si nadie te sustituye sigues comprometido a asistir al partido.<br/><br/>Al pulsar en ACEPTAR se generará un mensaje automático buscando sustituto")."',1,'partidos.php?menu=buscar_sustituto&id=".$id."')\"";
                        $href_botonBuscarSustituto = "";


                        //$Jugador = new Jugador($YO);

                        if (!$Jugador->puedePedirSustitucionParaLiga($_SESSION['S_LIGA_ACTUAL'])){

                            $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
                            $numeroSustitucionesJugadorLiga = $Liga->obtenerNumeroSustitucionesPorJugador();

                            $titulo_modalBusquedaSutituto = Traductor::traducir("Búsqueda de sustituto");
                            $mensaje_modalBuqedaSustituto = Traductor::traducir("No puedes pedir más sustituciones, has superado el número máximo permitido");

                            $estilo_botonBuscarSustituto = "style='height:20px;width:20px;filter: grayscale(1);margin:0px;'";
                            $enlace_botonBuscarSustituto = "onclick=\"return confirmacion('$titulo_modalBusquedaSutituto','$mensaje_modalBuqedaSustituto: $numeroSustitucionesJugadorLiga',1,'')\"";
                            $href_botonBuscarSustituto = "";
                        }


                        if(mostrar_confirmacion_boxy())
                        {
                            include("alerts_android.php");

                            $id_boton='buscar_sustituto_'.$id.$YO;

                            echo ("<a id='$id_boton' $EstiloMovil $enlace_botonBuscarSustituto  $href_botonBuscarSustituto>" .
                                "<img $estilo_botonBuscarSustituto src='./images/images_j/boton_sustituto.png' border='0' title='".Traductor::traducir("BUSCAR SUSTITUTO")."' $EstiloTamanoImg></a>$FINBotones");



                        }
                        else
                        {
                            echo ("<a  $EstiloMovil onclick=\"return confirm('".Traductor::traducir("RECUERDA<br/>Recibirás un mensaje en tu móvil cuando te sustituyan<br/>Si nadie te sustituye sigues comprometido a asistir al partido.<br/><br/>Al pulsar en ACEPTAR se generará un mensaje automático buscando sustituto")."');\" href='$PHP_SELF?menu=buscar_sustituto&id=$id'>" .
                                "<img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/boton_sustituto.png' border='0' title='".Traductor::traducir("BUSCAR SUSTITUTO")."' $EstiloTamanoImg></a>$FINBotones");

                        }


                    }
            //echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
                }
                echo ("$FIN1");
                if (($YO_Juego) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL'])  && $Mostraralgunboton)
                {
                    Log::v(__FUNCTION__, "Yo Juego", true);
                    //echo ("<a href='tarjetas.php?id=$id&imprimir=1' target='_BLANK'><img src='./images/Tarjeta.gif' border='1' width='16' title='SACAR TARJETA DE PARTIDO'></a> ");

                    if ($id_Campo) echo "<a $EstiloMovil href='mensajes.php?menu=deunaPartida&Titulo=Partido%20".devuelve_un_campo ("campos",2,"id",$id_Campo)." del ".cambiaf_a_normal($Fecha)."&id=0&id_jugadores=$id_Jugador1;$id_Jugador2;$id_Jugador3;$id_Jugador4'><img src='./images/images_j/mensaje.png' border='1' width='20' title='".Traductor::traducir("Enviar un Mensaje a los jugadores del partido")."' $EstiloTamanoImg></a>";
                    else  echo "<a $EstiloMovil href='mensajes.php?menu=deunaPartida&Titulo=Partido%20$Otro_Campo%20del%20".cambiaf_a_normal($Fecha)."&id=0&id_jugadores=$id_Jugador1;$id_Jugador2;$id_Jugador3;$id_Jugador4'><img src='./images/images_j/mensaje.png' border='1' width='20' title='".Traductor::traducir("Enviar un Mensaje a los jugadores del partido")."' $EstiloTamanoImg></a>";
                    $datorx = mysql_fetch_array(mysql_query("SELECT tiporecordatorio FROM jugadores WHERE id=".$_SESSION['S_id_usuario']));
                    if($datorx['tiporecordatorio']>0){
                        $dator = mysql_fetch_array(mysql_query("SELECT * FROM recordatorios WHERE id_partido=".$id_PART." AND id_jugador=".$_SESSION['S_id_usuario']));
                        if($dator['tiempo']>0){
                            if($dator['estado']==1){
                                $pasadofuturo = Traductor::traducir("enviado");
                                $env = 'env';
                            }else{
                                $pasadofuturo = Traductor::traducir("programado para");
                                $env = '';
                            }
                            ?>
                            <a <?=$EstiloMovil?> href="recordatorios.php?idr=<?=$dator['id']?>"><img src="./images/images_j/clock<?=$env?>.png"
                                title="<?php echo Traductor::traducir("Recordatorio")." ".$pasadofuturo." ".Traductor::traducir("el")." ".date('d/m/y',$dator['tiempo'])." ".Traductor::traducir("a las")." ".date('H:i',$dator['tiempo'])?> &#13;[<?php echo Traductor::traducir("Click para editar"); ?>]" <?=$EstiloTamanoImg?>></a>
                            <?
                        }else{
                            ?>
                            <img src="./images/images_j/clock.png" title="<?=Traductor::traducir("No tienes recordatorio para este partido")?>" <?=$EstiloTamanoImg?>></a>
                            <?
                        }
                    }else{
                        ?>
                        <a <?=$EstiloMovil?> href="recordatorios.php"><img src="./images/images_j/clockdes.png" title="<?= Traductor::traducir("No tienes los recordatorios activados")?> &#13;[<?=Traductor::traducir("Click para activarlos")?>]" <?=$EstiloTamanoImg?>></a>
                        <?
                    }

                    //echo ("<a href=\"javascript:popWindow('tarjetas.php?id=$id',800,330);\"><img src='./images/Tarjeta.gif' border='1' width='20' title='SACAR TARJETA DE PARTIDA'></a></td><td>");
                    //echo "<a href=\"javascript:popWindow('tarjetas.php?id=$id',500,330);\"><img src='./images/editar.gif' border='1' width='20' title='Ficha de $Nombre'></a>";
                    //else echo"</div>";
                }
                //else echo"</div>";
                Log::v(__FUNCTION__, "<3Dias:$PArtidoMENORDE3DIAS | ORGANIZO=$Soy_el_organizador | <3Dias:$Faltan_menos_de_3_dias | Partido_Pasado:$Partido_Pasado | Abierto:$Partido_Abierto | Vacio:$Partido_Vacio | Libre:$Partido_Libre | YO:$YO_Juego | FaltaResul:$FaltaResultado",false);
                echo"</div></center></li></div>";
                }//fin del mostrar
                else{
                    $nomuestra++;
                }
                //Para mostrar eventos
                $fecha_anterior=$Fecha;
                $hora_anterior=$Hora;
                echo"</ul>";

                $tiempoFinalBucle = microtime(true);
                $tiempoEjecucionBucle = $tiempoFinalBucle - $tiempoInicialBucle;
                Log::v(__FUNCTION__, "Tiempo ejecución Fila: $tiempoEjecucionBucle", true);

                }//del WHILE
                $tiempoFinalWhile = microtime(true);
                $tiempoEjecucionWhile = $tiempoFinalWhile - $tiempoInicialWhile;
                Log::v(__FUNCTION__, "Tiempo ejecución Bucle: $tiempoEjecucionWhile", true);
                //JMAM: Muestra los eventos (en caso de que esté en MI Agenda) futuros después del último partido
                if ($MiAgenda){
                    mostrarTodosMensajesEventos(date("Y-m-d",strtotime("$fecha_anterior + 1 days")));
                }
                //echo "<br><br><br>";
                //if 	($_SESSION['S_Version']=='Movil') echo "</div>";
            }//del if si
            else {
                /////////////no hay nada encontrado
                echo ("<tr><td>");
                echo "busqueda no realizada <br><br>";
                echo ("</td></tr>");
            }
            echo '
                <style>.more-link{/*margin-top:-50px !important;*/color: green;}</style>
                <script src="./librerias/expander/jquery.expander.js"></script>
                <script>
                    $(".expandable").expander({
                    slicePoint: 400,
                    widow: 2,
                    preserveWords: true,
                    expandText: "Ver el mensaje completo",
                    expandPrefix: "&hellip; ",
                    expandEffect: "show",
                    collapseTimer: 100000,
                    userCollapseText: "Volver a ocultar",
                    expandEffect: "slideDown",
                    expandSpeed: 250,
                    collapseEffect: "slideUp",
                    collapseSpeed: 200,
                    });
                </script>
                <script type="text/javascript">
                    function compartirPartidoWhatsapp(id){
                        var url = document.getElementById("urlCompartirWhatsapp_"+id).value;
                        url = window.encodeURIComponent(url);
                        
                        if (typeof Android !== "undefined"){
                        window.open("whatsapp://send?text="+url,"_blank");
                        }
                        else{
                        window.open("whatsapp://send?text="+url,"_blank");
                        }
                    
                    }
                </script>';
                ////////////fin de la segunda tabla de listar
                if(!isset($_GET['pg'])){
                    if 	($_SESSION['S_Version']!='Movil'){ echo ("</table><br>");
                        echo ("<!--<button onclick='loadmore()'>Cargar mas</button><br>-->");
                        if(!isset($_GET['filtrolocalidad']) && !isset($_GET['filtrofecha']) && !$MiAgenda && !$Resultados){
                            echo "<center><button id='moreload' class='superboton' onclick='loadmore()'>Ver mas partidos</button><img id='loading' src='loading.gif' style='display:none'></center>";
                            echo"
                                <script>
                                    function loadmore(){
                                    pgn++;
                                    if(pgn<totalpg){
                                    \$('#loading').show(0);
                                    \$.get('partidos.php?pg='+pgn, function(data) {
                                    \$('#tpartidos > tbody:last').append(data);
                                    \$('#loading').hide(0);
                                    });
                                    }else{
                                    \$('#moreload').hide(0);
                                    return false;
                                    }
                                    }
                                </script>";
                        }
                    } else {echo"</ul>";
                        if(!isset($_GET['filtrolocalidad']) && !isset($_GET['filtrofecha'])){
                            echo "<center><button id='moreload' class='superboton' onclick='loadmore()'>".Traductor::traducir("Ver más partidos")."</button><img id='loading' src='loading.gif' style='display:none'></center>";



                            ?>
                            <div class="contenedor_cargando" id="contenedor_cargando" style="display: none">
                                <img src="<?php echo WWWBASE;?>images/logotipo_circular.png"/>
                                <span></span>
                            </div>
                            <?php
                            echo"
            <script>
            ";
                            ?>

                            /**

                            *

                            *  UTF-8 data encode / decode

                            *  http://www.webtoolkit.info/

                            *

                            **/




                            var Utf8 = {




                            // public method for url encoding


                            encode : function (string) {


                            string = string.replace(/\r\n/g,"\n");


                            var utftext = "";




                            for (var n = 0; n < string.length; n++) {




                            var c = string.charCodeAt(n);




                            if (c < 128) {


                            utftext += String.fromCharCode(c);


                            }


                            else if((c > 127) && (c < 2048)) {


                            utftext += String.fromCharCode((c >> 6) | 192);


                            utftext += String.fromCharCode((c & 63) | 128);


                            }


                            else {


                            utftext += String.fromCharCode((c >> 12) | 224);


                            utftext += String.fromCharCode(((c >> 6) & 63) | 128);


                            utftext += String.fromCharCode((c & 63) | 128);


                            }




                            }




                            return utftext;


                            },




                            // public method for url decoding


                            decode : function (utftext) {


                            var string = "";


                            var i = 0;


                            var c = c1 = c2 = 0;




                            while ( i < utftext.length ) {




                            c = utftext.charCodeAt(i);




                            if (c < 128) {


                            string += String.fromCharCode(c);


                            i++;


                            }


                            else if((c > 191) && (c < 224)) {


                            c2 = utftext.charCodeAt(i+1);


                            string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));


                            i += 2;


                            }


                            else {


                            c2 = utftext.charCodeAt(i+1);


                            c3 = utftext.charCodeAt(i+2);


                            string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));


                            i += 3;


                            }




                            }




                            return string;


                            }



                            }
                            <?php

                            $urlCargaAsincrona="&menu=".$_GET["menu"];

                            if(isset($_GET['filtrorapido'])){
                                $urlCargaAsincrona = "&filtrorapido=1";
                            }

                            echo "
            function loadmore(){
            console.log('loadmore: Página: '+pgn+' Total Páginas: '+totalpg);

            if(pgn<totalpg){
            var paginaACargar = pgn + 1;
            obtenerMasPartidos('partidos.php?pg='+paginaACargar+'$urlCargaAsincrona');
            }else{
            \$('#moreload').hide(0);
            return false;
            }
            }
            var \$win   = \$(window);

                \$win.scroll(function () {
                    if((\$win.height() + \$win.scrollTop()) >= \$(document).height() - 50){
                    console.log('Cargar más resultados');
                    loadmore();
                    }
                
                });
            </script>
            ";
                        }
                        PieMovil (1);
                    }
                }
        }
        else
        {//No hay partidos para mostrar
            echo"<tr><td colspan=10><center>".Traductor::traducir("No hay partidos para mostrar")."</center></td></tr>";

            echo mostrarTodosMensajesEventos();


    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////// ADAPTACION MOVIL
    ////////////////////////////////////////////////////////////////////////////////////////////////////

            echo "<div>";
            if 	($_SESSION['S_Version']!='Movil') {$ANT2="<tr bgcolor='#FFFFFF' height=\"41\">"; $ANT3="<tr bgcolor='#FFFFFF' height=\"41\">"; $ANT_="<tr bgcolor='lightpink' height=\"41\">"; $FIN1="</td></tr>";
                $clasem ='';
            }
            else {
                $ANT2="<ul style=''>";
                $ANT3="<ul style=''>";
                $ANT_="<ul style='background-color:lightpink;'>";
                $FIN1="</span></li>";
                $clasem ='m';
            }



    //	echo"<br><br>$q";
        }
        $tiempo_final = microtime(true);
        $tiempo = $tiempo_final - $tiempo_inicial;

        Log::v(__FUNCTION__, "Tiempo ejecución: $tiempo", true);
    };//////FIN DE LISTAR TABLA

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function formulario ($op,$id)
    {
        global $PAREJA;
        global $RESULTADO;

    if 	($_SESSION['S_Version']=='Movil')
    {
        $SaltodeLinea='<br>';
        $anchoMovil=" size='10' ";

        if ($op!="puntuacion")
        {
            $CURRENT="id='current'";

            if (Sesion::obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                echo"
							<ul id='navtabs' style='text-align:center'>
							   <li><a style='width:32%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li>
							   <li><a style='width:32%' href='./partidos.php' title='Partidos' $PARTIDOSCURRENT>".Traductor::traducir("Partidos")."</a></li>
							   <li><a style='width:32%' href='./partidos.php?menu=alta' title='Nuevo' $CURRENT>".Traductor::traducir("Nuevo")."</a></li>
							</ul>";
            }
            else{
                echo"
							<ul id='navtabs' style='text-align:center'>
							   <li><a style='width:48%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li>
							   <li><a style='width:48%' href='./partidos.php?menu=alta' title='Nuevo' $CURRENT>".Traductor::traducir("Nuevo")."</a></li>
							</ul>";
            }
            echo"
							<div id='contentwrap'>
						
							";
        }
    }

    if ($op=="puntuacion") $EsPuntuacion="disabled";
    else
    {
        $EsPuntuacion="";
        echo"

<script language=\"JavaScript\" type=\"text/javascript\">
// creando objeto XMLHttpRequest de Ajax
var obXHR;
try {
	obXHR=new XMLHttpRequest();
} catch(err) {
	try {
		obXHR=new ActiveXObject(\"Msxml2.XMLHTTP\");
	} catch(err) {
		try {
			obXHR=new ActiveXObject(\"Microsoft.XMLHTTP\");
		} catch(err) {
			obXHR=false;
		}
	}
}

function cargar2(url,obId) {
	var obCon = document.getElementById(obId);
	obXHR.open(\"GET\", url);
	obXHR.onreadystatechange = function() {
		if (obXHR.readyState == 4 && obXHR.status == 200) {
			obXML = obXHR.responseXML;
			obCod = obXML.getElementsByTagName(\"cantidad\");
			obCon.length=obCod.length;
				obCon.value=obCod[0].firstChild.nodeValue;
			}
	}
	obXHR.send(null);
}
</script>";
    }

    if (($op=="modificar")||($op=="puntuacion"))

    {

        if ($id!="")

        {

            $req=consulta_partidos ($id);

            list($ant_id,$ant_id_Liga,$ant_Fecha,$ant_Hora,$ant_TipoPuntuacion,$ant_id_Campo,$ant_Otro_Campo,$ant_id_Jugador1,$ant_id_Jugador2,$ant_id_Jugador3,$ant_id_Jugador4,$ant_Puntos_J1,$ant_Puntos_J2,$ant_Puntos_J3,$ant_Puntos_J4,$ant_id_Jugador_ApuntaResult,$ant_Fecha_Result,$ant_Observaciones,$ant_nivel_min,$ant_nivel_max,$ant_favoritos,$ant_invitacion,$ant_Extra1,$ant_Extra2,$ant_Extra3) = mysql_fetch_array($req);

            //Nuevo: recuperamos datos de parejas y mixto
            $id_logado=$_SESSION['S_id_usuario'];

            $sqlr = mysql_query("SELECT count(*) as cnt from partidos_mixtos WHERE id=".$id) or die("Error partidos-1867: Consulta erronea, avise a soporte.");
            $dtsr = mysql_fetch_array($sqlr);
            $ant_es_mixto=(intval($dtsr['cnt'])>0);

            $sqlr = mysql_query("SELECT count(*) as cnt from partidos_pendientes_pareja WHERE id_partido=$id and id_jugador_creador=$id_logado") or die('Error partidos-1871: Consulta erronea, consulte con soporte.');
            $dtsr = mysql_fetch_array($sqlr);
            $ant_es_pareja=(intval($dtsr['cnt'])>0);

            /******VEMOS SI EL PARTIDO ERA AMISTOSO O NO PARA MODIFICACIONES POSTERIORES. Con esta comprobacion cualquiera de los jugadores apuntados puede cambiar si es amistoso o no. Ver si es asi o solo el creador puede cambiar esta caracteristica.*/
            $sqlr = mysql_query("SELECT count(*) as cnt from partidos_amistosos WHERE id=$id ") or die('Error partidos-2633: Consulta erronea, consulte con soporte.');
            $dtsr = mysql_fetch_array($sqlr);
            $ant_es_amistoso=(intval($dtsr['cnt'])>0);
            /*******************/

            $ant_id_jugador_invitado='';
            $ant_nombre_jugador_invitado='';
            if($ant_es_pareja)
            {
                //Obtenemos los datos del partido por parejas
                $sqlr = mysql_query("SELECT id_jugador_invitado,confirmado from partidos_pendientes_pareja WHERE id_partido=$id and id_jugador_creador=$id_logado") or die('Error partidos-1879: Consulta erronea, consulte con soporte.');
                $dtsr = mysql_fetch_array($sqlr);
                $ant_id_jugador_invitado=$dtsr['id_jugador_invitado'];
                $ant_confirmado=$dtsr['confirmado'];

                $sqlr = mysql_query("SELECT Nombre, Apellidos from jugadores WHERE id=$ant_id_jugador_invitado") or die('Error partidos-1897: Consulta erronea, consulte con soporte.');
                $dtsr = mysql_fetch_array($sqlr);
                $ant_nombre_jugador_invitado=$dtsr['Nombre'].' '.$dtsr['Apellidos'];
//          die($ant_id_jugador_invitado);
            }


        }

        else echo("Consulta mal realizada");


        $Hora=explode(":",$ant_Hora);

    }
    else {$ant_Fecha=date('Y-m-d');$Hora[0]='09';}

    ///CONTROLO QUE EL JUGADOR ESTA EN EL PARTIDO, PARA VERLO O MODIFICARLO
    ///ADEMAS CONTROLO QUE LA FECHA ESTÁ EN EL RANGO
    $Hoy=date('Y-m-d'); //jm 241016 donde pone -7 era -3 // 221216 vuelvo a poner -3
    if (strtotime("$ant_Fecha")>(strtotime("$Hoy - 3 days"))) $Pasaronmenosde3Dias=1; else $Pasaronmenosde3Dias=0;

    $JugadoresdelPartido= array ($ant_id_Jugador1,$ant_id_Jugador2,$ant_id_Jugador3,$ant_id_Jugador4);
    if (((in_array ($_SESSION['S_id_usuario'],$JugadoresdelPartido))&&($Pasaronmenosde3Dias))||($op=='alta'))
    {



    if ($op!="puntuacion")
    {
        if 	($_SESSION['S_Version']!='Movil'){
            echo("<div align='center'><TABLE BORDER=0 WIDTH='95%' CELLPADDING=5><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
            echo '<br>';
        }else{
            echo("<div align='center'><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'><div class='filtrocontainer caja-partido-1' style='background:#eee;'><TABLE BORDER=0  CELLPADDING=0>");
        }

        if($ant_id_Jugador2!=0){
            if ($ant_Fecha==date("Y-m-d")){
                $nocambiahora = 'disabled';
            }
            $hayotrojugador=true;
            $sololectura = 'readonly';
        }else{
            $hayotrojugador=false;
            $sololectura = '';
            $nocambiahora = '';
        }
        if 	($_SESSION['S_Version']!='Movil'){

            echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>".Traductor::traducir("Fecha").":</b></TD>");
            echo ("<TD ALIGN=LEFT VALIGN=TOP><input class='textbox fechac' type='text' $sololectura name='Fecha' value='".cambiaf_a_normal($ant_Fecha)."' " .
                "id='Fec1' size='15' maxlength='10' $EsPuntuacion>");
            echo ("	<script>function cargacalendario(){");
            if(!$hayotrojugador){
                echo ("$('.fechac').Zebra_DatePicker({
		direction: true,
		readonly_element:true"
                );
            }
            echo ("
		});
		$('.Zebra_DatePicker_Icon').css({'display':'none'});
		}

		 </script></TD></tr>");
            echo "<tr><td colspan=2><div id='FORM_Fecha_errorloc' class='innerError''></div></TD></TR>";
        }else{


            echo ("<tr><TD ALIGN=RIGHT style='' class=texttitular_g><b>".Traductor::traducir("Fecha")."&nbsp;</b></TD>");
            echo ("<TD ALIGN=LEFT VALIGN=TOP><input class='botonancho fechac' style='' $sololectura type='text' name='Fecha' value='".cambiaf_a_normal($ant_Fecha)."' " .
                "id='Fec1' size='15' maxlength='10' $EsPuntuacion></TD></tr>");
            echo "<tr><td colspan=2><div id='FORM_Fecha_errorloc' class='innerError'></div>";
            echo ("	<script>function cargacalendario(){");
            if(!$hayotrojugador){
                echo ("$('.fechac').Zebra_DatePicker({
		direction: true,
		readonly_element:true"
                );
            }
            echo ("
		});
		$('.Zebra_DatePicker_Icon').css({'display':'none'});
		}

		 </script></TD></tr>");

        }
//echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Hora:</b></TD>");
//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".htmlentities($ant_Hora)."' size='5' maxlength='5'></TD></tr>");
    if 	($_SESSION['S_Version']!='Movil'){
        echo"<tr><td align=center colspan=4><table border=0>";

        echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>".Traductor::traducir("Hora").":</b></TD>");
//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".$Hora[0]."' size='5' maxlength='5'></TD>");

        echo"
<TD ALIGN=LEFT VALIGN=TOP>
  <SELECT name='Hora' $EsPuntuacion $nocambiahora>
  <option value='07'".igual('07',$Hora[0]).">07
  <option value='08'".igual('08',$Hora[0]).">08
  <option value='09'".igual('09',$Hora[0]).">09
  <option value='10'".igual('10',$Hora[0]).">10
  <option value='11'".igual('11',$Hora[0]).">11
  <option value='12'".igual('12',$Hora[0]).">12
  <option value='13'".igual('13',$Hora[0]).">13
  <option value='14'".igual('14',$Hora[0]).">14
  <option value='15'".igual('15',$Hora[0]).">15
  <option value='16'".igual('16',$Hora[0]).">16
  <option value='17'".igual('17',$Hora[0]).">17
  <option value='18'".igual('18',$Hora[0]).">18
  <option value='19'".igual('19',$Hora[0]).">19
  <option value='20'".igual('20',$Hora[0]).">20
  <option value='21'".igual('21',$Hora[0]).">21
  <option value='22'".igual('22',$Hora[0]).">22
  <option value='23'".igual('23',$Hora[0]).">23
  </SELECT>

";
        if ($ant_Fecha==date("Y-m-d") && $ant_id_Jugador2!=0){
            ?><input type="hidden" value="<?=$Hora[0]?>" name="Hora"></input>
            <?
        }
        echo "</TD>";

        echo ("<TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b> &nbsp;&nbsp;".Traductor::traducir("Minutos").":</b></TD>");
//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".$Hora[1]."' size='5' maxlength='5'></TD></tr>");
        echo"
<TD ALIGN=LEFT VALIGN=TOP>
  <SELECT name='Minutos' $EsPuntuacion $nocambiahora>
  <option value='00'".igual('00',$Hora[1]).">00
  <option value='05'".igual('05',$Hora[1]).">05
  <option value='10'".igual('10',$Hora[1]).">10
  <option value='15'".igual('15',$Hora[1]).">15
  <option value='20'".igual('20',$Hora[1]).">20
  <option value='25'".igual('25',$Hora[1]).">25
  <option value='30'".igual('30',$Hora[1]).">30
  <option value='35'".igual('35',$Hora[1]).">35
  <option value='40'".igual('40',$Hora[1]).">40
  <option value='45'".igual('45',$Hora[1]).">45
  <option value='50'".igual('50',$Hora[1]).">50
  <option value='55'".igual('55',$Hora[1]).">55
  </SELECT>";
    if ($ant_Fecha==date("Y-m-d") && $ant_id_Jugador2!=0){
        ?><input type="hidden" value="<?=$Hora[1]?>" name="Minutos"></input>
    <?
    }
    echo "
</TD></tr>
";


    echo"</td></tr></table></td></tr>";

    }else{


        echo ("<tr><TD ALIGN=RIGHT class=texttitular_g><b><label for='Hora'>".Traductor::traducir("Hora")."&nbsp;</label></b></TD>");
//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".$Hora[0]."' size='5' maxlength='5'></TD>");
        echo"
<TD ALIGN=LEFT VALIGN=TOP>
<span class='botonancho ' style='display:inline-block;'>
  <SELECT name='Hora' class='botonancho-nofloat horas' style='display:inline;padding:0px;border:0px' id='Hora' $EsPuntuacion>
  <option value='07'".igual('07',$Hora[0]).">07
  <option value='08'".igual('08',$Hora[0]).">08
  <option value='09'".igual('09',$Hora[0]).">09
  <option value='10'".igual('10',$Hora[0]).">10
  <option value='11'".igual('11',$Hora[0]).">11
  <option value='12'".igual('12',$Hora[0]).">12
  <option value='13'".igual('13',$Hora[0]).">13
  <option value='14'".igual('14',$Hora[0]).">14
  <option value='15'".igual('15',$Hora[0]).">15
  <option value='16'".igual('16',$Hora[0]).">16
  <option value='17'".igual('17',$Hora[0]).">17
  <option value='18'".igual('18',$Hora[0]).">18
  <option value='19'".igual('19',$Hora[0]).">19
  <option value='20'".igual('20',$Hora[0]).">20
  <option value='21'".igual('21',$Hora[0]).">21
  <option value='22'".igual('22',$Hora[0]).">22
  <option value='23'".igual('23',$Hora[0]).">23
  </SELECT>
</span>
";



//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".$Hora[1]."' size='5' maxlength='5'></TD></tr>");
        echo"
<span class='botonancho ' style='display:inline-block;'>
<SELECT name='Minutos' id='Minutos' class='botonancho minutos' style='display:inline;' $EsPuntuacion>
  <option value='00'".igual('00',$Hora[1]).">00
  <option value='05'".igual('05',$Hora[1]).">05
  <option value='10'".igual('10',$Hora[1]).">10
  <option value='15'".igual('15',$Hora[1]).">15
  <option value='20'".igual('20',$Hora[1]).">20
  <option value='25'".igual('25',$Hora[1]).">25
  <option value='30'".igual('30',$Hora[1]).">30
  <option value='35'".igual('35',$Hora[1]).">35
  <option value='40'".igual('40',$Hora[1]).">40
  <option value='45'".igual('45',$Hora[1]).">45
  <option value='50'".igual('50',$Hora[1]).">50
  <option value='55'".igual('55',$Hora[1]).">55
  </SELECT>
</span>
";


        echo"</td></tr></table></div><div class='filtrocontainer' style='background:#eee;margin-bottom:10px'><TABLE BORDER=0 WIDTH='auto' style='white-space:100%' CELLPADDING=0>";

    }

    /*
echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Tipo de Partido:</b></TD>");
//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='TipoPuntuacion' value='".htmlentities($ant_TipoPuntuacion)."' size='75' maxlength='100'></TD></tr>");

echo"
<TD ALIGN=LEFT VALIGN=TOP>
  <SELECT name='TipoPuntuacion'>
  <option value='STABLEFORD 9-Hoyos'".igual('STABLEFORD 9-Hoyos',$ant_TipoPuntuacion).">STABLEFORD 9-Hoyos
  <option value='STABLEFORD 18-Hoyos'".igual('STABLEFORD 18-Hoyos',$ant_TipoPuntuacion).">STABLEFORD 18-Hoyos
  </SELECT>
</TD></tr>
";
*/
    echo "<script>
function verifica()
{
  var sel = document.getElementById( 'sel' );
  var textOculto1 = document.getElementById( 'textOculto1' );
  var textOculto2 = document.getElementById( 'textOculto2' );
  var textprovincia = document.getElementById( 'textprovincia' );
  var textlocalidad = document.getElementById( 'textlocalidad' );
  var Localidad = document.getElementById( 'Localidad' );
  var Provincia = document.getElementById( 'Provincia' );

  if( sel.options[ sel.selectedIndex ].value == 'OTRO' )
  {
    ";
    if($_SESSION['S_Version']=='Movil'){
        echo "
	textOculto1.style.display = '';
    textOculto2.style.display = 'block';
   textprovincia.style.display = '';
    textlocalidad.style.display = '';
    Provincia.style.display = 'block';
    Localidad.style.display = 'block';
document.getElementById( 'fila1ocul' ).style.display='';
document.getElementById( 'fila2ocul' ).style.display='';
document.getElementById( 'fila3ocul' ).style.display='';

	";
    }else{
        echo "
textOculto1.style.display = 'block';
    textOculto2.style.display = 'block';
   textprovincia.style.display = 'block';
    textlocalidad.style.display = 'block';
    Provincia.style.display = 'block';
    Localidad.style.display = 'block';

	";
    }

    echo "
  }
  else
  {
    textOculto1.style.display = 'none';
    textOculto2.style.display = 'none';
   textprovincia.style.display = 'none';
    textlocalidad.style.display = 'none';
    Provincia.style.display = 'none';
    Localidad.style.display = 'none';";
    if($_SESSION['S_Version']=='Movil'){
        echo "
document.getElementById( 'fila1ocul' ).style.display='none';
document.getElementById( 'fila2ocul' ).style.display='none';
document.getElementById( 'fila3ocul' ).style.display='none';

	";
    }

    echo "
  }
}

function verifica2()
{
  var sel = document.getElementById( 'FiltroHCP' );
	var sel2 = document.getElementById( 'favoritos' );
	var sel3 = document.getElementById( 'sexo' );
	var Oculto1 = document.getElementById( 'nivel_min' );
  var Oculto2 = document.getElementById( 'nivel_max' );
  var textOculto1 = document.getElementById( 'text_nivel_min' );
  var textOculto2 = document.getElementById( 'text_nivel_max' );
  var textOculto3 = document.getElementById( 'text_cantidad' );

  if( sel.checked )
  {
    textOculto1.style.display = 'inline';
    textOculto2.style.display = 'inline';
    Oculto1.style.display = 'inline';
    Oculto2.style.display = 'inline';
    		  //cargar('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&yo=".$_SESSION['S_id_usuario']."&max='+nivel_max.value+'&min='+nivel_min.value+'&genero=".$_SESSION['sexo']."&sexo='+limsexo.checked+'&fav='+favoritos.checked,'CANT');
  }
  else
  {
    textOculto1.style.display = 'none';
    textOculto2.style.display = 'none';
    Oculto1.style.display = 'none';
    Oculto2.style.display = 'none';
	document.getElementById('nivel_max').value='18';
document.getElementById('nivel_min').value='01';
  }

   if( sel2.checked || sel.checked || sel3.checked )
  {
    textOculto3.style.display = 'block';
    		  cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&yo=".$_SESSION['S_id_usuario']."&max='+nivel_max.value+'&min='+nivel_min.value+'&genero=".$_SESSION['sexo']."&sexo='+sexo.checked+'&fav='+favoritos.checked,'CANT');
  }
  else
  {
    textOculto3.style.display = 'none';
  }

}

 </script>
 ";

    //	echo"<tr><td colspan=2><br><center>Hola</center></td></tr>";


    if 	($_SESSION['S_Version']!='Movil')
    {
        echo ("<tr><TD ALIGN=right VALIGN=TOP class=texttitular_g><br><b>".Traductor::traducir("Pista").":</b></td>");
        echo ("<td style='font-size:80%;color:green;font-weight:bold;'><br>".Traductor::traducir("VERDE=con convenio").",<span style='color:gray;font-weight:normal;'> ".Traductor::traducir("GRIS=sin convenio")."</span></TD>");
        echo("</tr>");
    }
    else{
//	echo ("<tr><td colspan='2'><hr></td></tr>");
        echo ("<tr><TD ALIGN=RIGHT style='' class=texttitular_g><b><label for='sel'>".Traductor::traducir("Pista")."</label></b></td>");
    }


    //rellena_select ($tabla,$nombre,$campo,$valor,$ant_valor,$otro,$campo2='',$campo3='',$todos='',$order='',$disabled='')
    // rellena_select('campos','id_Campo','Nombre','id',$ant_id_Campo,'OTRO','Extra2','','',' Nombre asc');



    $q2="SELECT id,Nombre, Extra2, Convenio, Localidad FROM campos WHERE id IN (SELECT id_campo FROM camposporligas WHERE id_liga=".$_SESSION['S_LIGA_ACTUAL'].") ORDER BY Localidad asc, Convenio desc, Nombre asc";
    $si2=mysql_query ($q2);
    $NumeroDeCampos= mysql_num_rows ($si2);

    if 	($_SESSION['S_Version']!='Movil')
    {
        echo "<tr><td></td><TD ALIGN=left VALIGN=TOP>";
        echo "<SELECT name='id_Campo' SINGLE id='sel' style='width:190px' onchange=\"verifica();\">\n";
    }else{
        echo "<TD ALIGN=left>
		";
        echo "<span class='botonancho ancho200' style='display:inline-block;'>";
        echo "<SELECT name='id_Campo' SINGLE id='sel' class='botonancho-2 caja-partido-3' style='text-align:left' onchange=\"verifica();\">\n";
    }


    if ($NumeroDeCampos>1) echo "<option value='selecc'>".Traductor::traducir("Selecciona una pista...")."";

    for ($i=0;$i<mysql_num_rows($si2);$i++)
    {
        if (mysql_result($si2,$i,"Localidad")) $LOCAL=mysql_result($si2,$i,"Localidad")."- ";

        echo"<OPTION VALUE=\"".mysql_result($si2,$i,"id")."\"";
        if ($ant_id_Campo==mysql_result($si2,$i,"id")) {echo " selected ";}
        if (mysql_result($si2,$i,"Convenio")=='S') {echo " style='color:green;font-weight:bold;' ";}	else {echo " style='color:gray;font-weight:normal;' ";}
        echo">$LOCAL".mysql_result($si2,$i,"Nombre");


    }


    echo"<OPTION VALUE='OTRO'>".Traductor::traducir("OTRO")."";
    if 	($_SESSION['S_Version']!='Movil')
    {
        echo ("</SELECT></td></TR>");
    }else{
        echo ("</SELECT></span></td></TR>");
    }
    echo "<tr><td colspan=2><div id='FORM_id_Campo_errorloc' class='innerError''></div></TD></TR>";
    if 	($_SESSION['S_Version']!='Movil')
    {
        echo "<tr><td colspan=2>";
        echo '<table>';
        echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g id=\"textOculto1\" style=\"display: none;\"><b>".Traductor::traducir("Lugar").":</b></TD>");
        echo ("<TD ALIGN=LEFT VALIGN=TOP ><input class=textbox type='text' name='Otro_Campo' value='".htmlentities($ant_Otro_Campo)."' size='25' maxlength='25' id=\"textOculto2\" style=\"display: none;\">");

        echo ("<div id='errorotrapista' class='innerError'></div></TD></tr>");
        echo ("<tr ><TD ALIGN=RIGHT VALIGN=TOP id='textprovincia'  style=\"display: none;\" class=texttitular_g><b>".Traductor::traducir("Provincia").":</b></TD>");
        echo ("<TD ALIGN=LEFT VALIGN=TOP>");//"<input class=textbox type='text' name='Provincia' id='Provincia' value='".htmlentities($ant_Provincia)."' size='75' maxlength='150'></TD></tr>");
        echo"<select name='Provincia' id='Provincia' style=\"display: none;\" size='1' onchange=\"cargar('XMLmunicipios.php?provincia='+this.value,'Localidad')\" onload=\"cargar('XMLmunicipios.php?provincia=1','Localidad')\">";
    }else{
        echo ("<tr id='fila1ocul' style='display:none'><TD ALIGN=RIGHT class=texttitular_g id=\"textOculto1\" style=\"display: none;\"><b><label for='textOculto2'>Lugar</label></b></TD>");
        echo ("<TD ALIGN=LEFT  ><input class='botonancho ancho200' type='text' name='Otro_Campo' value='".htmlentities($ant_Otro_Campo)."'  maxlength='25' id=\"textOculto2\" style=\"display: none;\">");
        echo ("</td></tr>");
        echo ("<tr><td colspan=2; style='color:red;font-weight:bold'><div id='errorotrapista' class='innerError'></div></TD></tr>");
        echo ("<tr id='fila2ocul' style='display:none'><TD ALIGN=RIGHT id='textprovincia'  style=\"display: none;\" class=texttitular_g><b><label for='Provincia'>Prov.</label></b></TD>");
        echo ("<TD ALIGN=LEFT>");//"<input class=textbox type='text' name='Provincia' id='Provincia' value='".htmlentities($ant_Provincia)."' size='75' maxlength='150'></TD></tr>");
        echo "<span class='botonancho ancho200' style='display:inline-block;'>";
        echo"<select name='Provincia' id='Provincia' class='botonancho' style=\"display: none;\" size='1' onchange=\"cargar('XMLmunicipios.php?provincia='+this.value,'Localidad')\" onload=\"cargar('XMLmunicipios.php?provincia=1','Localidad')\">";


    }
    $sqlx="SELECT * FROM provincias";
    $qur = mysql_query($sqlx);
    ?>
        <option value="000"><?=Traductor::traducir("Selecciona una provincia")?></option>
    <?
    while($prov = mysql_fetch_array($qur)){
    ?>

        <option value="<?=$prov['id']?>" <?if($prov['provincia']==$ant_Provincia){ echo 'selected';}?>><?=$prov['provincia']?></option>
    <?
    }
    echo "</select>";
    if 	($_SESSION['S_Version']=='Movil')
    {
        echo ("</span></td></tr>");
        echo ("<tr><td colspan=2; style='color:red;font-weight:bold'>");
    }
    echo "<div id='FORM_Provincia_errorloc' class='innerError'></div></TD></TR>";

    //"<input class=textbox type='text' name='Localidad' value='".htmlentities($ant_Localidad)."' size='75' maxlength='150'></TD></tr>");
    if 	($_SESSION['S_Version']!='Movil'){
        echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP id='textlocalidad'  style=\"display: none;\" class=texttitular_g><b>".Traductor::traducir("Localidad").":</b></TD>");
        echo ("<TD ALIGN=LEFT VALIGN=TOP>");
        echo"<select name='Localidad' id='Localidad' size='1' style=\"display: none;\">";
    }else{
        echo ("<tr id='fila3ocul' style='display:none'><TD ALIGN=RIGHT id='textlocalidad'  style=\"display: none;\" class=texttitular_g><b><label for='Localidad'>Local.</label></b></TD>");
        echo ("<TD ALIGN=LEFT >");
        echo "<span class='botonancho ancho200' style='display:inline-block;'>";
        echo"<select name='Localidad' id='Localidad' class='botonancho' size='1' style=\"display: none;\">";
    }
    $sqlx="SELECT municipios.id as id, municipio FROM municipios,provincias WHERE municipios.provincia=provincias.id AND provincias.provincia='".$ant_Provincia."'";
    $qur = mysql_query($sqlx);
    while($prov = mysql_fetch_array($qur)){
    ?>

        <option value="<?=$prov['id']?>" <?if($prov['municipio']==$ant_Localidad){ echo 'selected';}?>><?=$prov['municipio']?></option>
    <?}

    echo "</select>";
    if 	($_SESSION['S_Version']=='Movil')
    {
        echo ("</span></td></tr>");
        echo ("<tr><td colspan=2; style='color:red;font-weight:bold'>");
    }
    echo "<div id='FORM_Localidad_errorloc' class='innerError'></div></TD></TR>";
    echo '</td></tr>';
    if 	($_SESSION['S_Version']!='Movil'){
        echo '</table>';
    }else{
        echo "</table></div><div class='filtrocontainer' style='background:#eee;margin-bottom:10px'><TABLE BORDER=0 WIDTH='auto' style='width:100%' CELLPADDING=0>";
    }
    function checked($min,$max)
    {
        if ( (($min!='0')||($max!='0')) && (($min!='')&&($max!='')) ) return "checked";

    }

    function checked2($genero){
        if($genero==$_SESSION['sexo']){
            return 'checked';
        }
    }
    if 	($_SESSION['S_Version']!='Movil'){
        echo "<tr><td class=texttitular_g colspan=3>

   <input type=checkbox name=FiltroHCP value=1 onclick=\"verifica2();\" id='FiltroHCP' ".checked($ant_nivel_min,$ant_nivel_max)." > ".Traductor::traducir("Filtrar por nivel")."";

        echo " &nbsp;&nbsp;&nbsp;$SaltodeLinea<input type=checkbox name=favoritos value=1 onclick=\"verifica2();\" id='favoritos' ".checked($ant_favoritos,'99').
            " onchange=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&max='+nivel_max.value+'&min='+nivel_min.value+'&yo=".$_SESSION['S_id_usuario']."&genero=".$_SESSION['sexo']."&sexo='+sexo.checked+'&fav='+this.checked,'CANT')\"> ".Traductor::traducir("Solo para Favoritos")."";
        echo " &nbsp;&nbsp;$SaltodeLinea<input type=checkbox ".checked2($ant_Extra2)." name=limsexo value=1 onclick=\"verifica2();\" id='sexo' onchange=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&max='+nivel_max.value+'&min='+nivel_min.value+'&yo=".$_SESSION['S_id_usuario']."&genero=".$_SESSION['sexo']."&sexo='+this.checked+'&fav='+favoritos.checked,'CANT')\"> ".Traductor::traducir("")." ";

        if($_SESSION['sexo']=='Hombre'){
            echo Traductor::traducir("Masculino");
        }
        if($_SESSION['sexo']=='Mujer'){
            echo Traductor::traducir("Femenino");
        }

//INICIO FILTRO POR PAREJAS Y MIXTO
        //Funciones JS
        echo "<script type='text/javascript'>
   function GestionPareja() //Muestra o no el div de seleccion de pareja dependiendo de la seleccion del check
   {
     var divPareja = document.getElementById( 'pareja' );
     var chkboxPareja = document.getElementById( 'FiltroParejas' );
     if( chkboxPareja.checked )
     {
       divPareja.style.display = 'inline';
     }
     else
     {
       divPareja.style.display = 'none';
     }

   }


   </script>";

        //FIN fUNCIONES JS
        $mixtos_activos=devuelve_un_campo("liga",50,"id",$_SESSION['S_LIGA_ACTUAL']);

        //echo " id $ant_id mix $mixtos_activos";

        if($mixtos_activos!='NO')
            echo " &nbsp;&nbsp; <input type=checkbox name=FiltroMixto value=1 onclick=\"verifica2();\" id='FiltroMixto' ".($ant_es_mixto?'checked=checked':'')." > ".Traductor::traducir("Mixto")."";


        echo "</td></tr>";
    }else{       //Version movil
    //echo ("<tr><td colspan='2'><hr></td></tr>");
    echo "<tr><td class=texttitular_g colspan=2>";
    echo "<table style='display:none' width='100%'><tr><td align='center' width='33%'><label for='FiltroHCP'>".Traductor::traducir("Nivel")."</label><br><input type=checkbox name=FiltroHCP value=1 onclick=\"verifica2();\" id='FiltroHCP' ".checked($ant_nivel_min,$ant_nivel_max)." > ";
    echo "</td><td align='center' width='33%'><label for='favoritos'>".ucfirst(strtolower(Traductor::traducir("FAVORITOS")))."</label><br>";
    echo "<input type=checkbox name=favoritos value=1 onclick=\"verifica2();\" id='favoritos' ".checked($ant_favoritos,'99')." onchange=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&max='+nivel_max.value+'&min='+nivel_min.value+'&yo=".$_SESSION['S_id_usuario']."&genero=".$_SESSION['sexo']."&sexo='+sexo.checked+'&fav='+this.checked,'CANT')\"> ";

    echo "<input type=checkbox name=FiltroMixto value=1 onclick=\"verifica2();\" id='FiltroMixto' ".($ant_es_mixto?'checked=checked':'')." > ".Traductor::traducir("Mixto")."";
    echo "<input type=checkbox name=FiltroParejas value=1  onclick=\"GestionParejaMoviles();\" id='FiltroParejas'  ".($ant_es_pareja?'checked=checked':'')." > ".Traductor::traducir("Marcar, solo si quieres elegir una pareja")."";

    echo "</td><td align='center' width='33%'><label for='sexo'>";
    if($_SESSION['sexo']=='Hombre'){
        echo ucfirst(Traductor::traducir("Masculino"));
        $textsexo = ucfirst(Traductor::traducir("Masculino"));
    }
    if($_SESSION['sexo']=='Mujer'){
        echo ucfirst(Traductor::traducir("Femenino"));
        $textsexo = ucfirst(Traductor::traducir("Femenino"));
    }
    echo "</label><br>";
    echo "<input type=checkbox ".checked2($ant_Extra2)." name=limsexo value=1 onclick=\"verifica2();\" id='sexo' onchange=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&max='+nivel_max.value+'&min='+nivel_min.value+'&yo=".$_SESSION['S_id_usuario']."&genero=".$_SESSION['sexo']."&sexo='+this.checked+'&fav='+favoritos.checked,'CANT')\"> ";
    echo "</td></tr></table>";
    ?>


        <div class="grup-selector">
            <div >
                <a  class="botonancho botonancho-nuevo-seleccion <?php if($ant_nivel_min) echo "optselected";?>" id="FiltroHCPbtn" onclick="javascript:seleccionacheck('FiltroHCP')"><?=Traductor::traducir("Nivel")?></a>
            </div>
            <div >
                <a  class="botonancho botonancho-nuevo-seleccion <?php if($ant_favoritos) echo "optselected";?>" id="favoritosbtn" onclick="javascript:seleccionacheck('favoritos')"><?=ucfirst(strtolower(Traductor::traducir("FAVORITOS")))?></a>
            </div>
            <a class="botonancho botonancho-nuevo-seleccion <?php if($ant_Extra2) echo "optselected";?>" id="sexobtn" onclick="javascript:seleccionacheck('sexo')"><?=$textsexo?></a>
            <!--Nuevo: mixtos y parejas para moviles-->
        </div>

        <div>

            <?php

            $mixtos_activos=devuelve_un_campo("liga",50,"id",$_SESSION['S_LIGA_ACTUAL']);

            //echo " id $ant_id mix $mixtos_activos";

            if($mixtos_activos!='NO'){


                ?>

                <a  class="botonancho botonancho-nuevo-seleccion<?php if($ant_es_mixto) echo "optselected";?>" id="FiltroMixtobtn" onclick="javascript:seleccionacheck('FiltroMixto')">Mixto</a>
            <?php } ?>


            <?php
            //INICIO FILTRO POR PAREJAS Y MIXTO para moviles

            //Funciones JS
            echo "<script type='text/javascript'>
       function GestionPareja() //Muestra o no el div de seleccion de pareja dependiendo de la seleccion del check
       {
         var divPareja = document.getElementById( 'pareja' );
         var chkboxPareja = document.getElementById( 'FiltroParejas' );
         if( chkboxPareja.checked )
         {
           divPareja.style.display = 'inline';
         }
         else
         {
           divPareja.style.display = 'none';
         }
    
       }
    
    
       </script>";

            //FIN fUNCIONES JS



            ?>


        </div>




        </div>



    <?
    echo"</td></tr>";
    }

    if 	($_SESSION['S_Version']!='Movil'){
        echo"<tr><td align=center colspan=4><table border=0>";
        echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g id=text_nivel_min  style=\"display: none;\"><b>".Traductor::traducir("Mínimo").": </b></TD>");
//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".$Hora[0]."' size='5' maxlength='5'></TD>");
        echo"<TD ALIGN=LEFT VALIGN=TOP>";
        $classmovil = '';
    }else{
        echo"<tr><td align=center colspan=2><table border=0>";
        echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g id=text_nivel_min  style=\"display: none; \">");
//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".$Hora[0]."' size='5' maxlength='5'></TD>");
        $classmovil = "class='botonancho-3 boton-peque'";
    }


    echo "<SELECT ".$classmovil." name='nivel_max' $EsPuntuacion id='nivel_max' style=\"\" " .
        "onchange=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&min='+nivel_min.value+'&max='+this.value+'&yo=".$_SESSION['S_id_usuario']."&genero=".$_SESSION['sexo']."&sexo='+limsexo.checked+'&fav='+favoritos.checked,'CANT')\" " .
        "onload=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&min='+nivel_min.value+'&max='+this.value+'&yo=".$_SESSION['S_id_usuario']."&genero=".$_SESSION['sexo']."&sexo='+limsexo.checked+'&fav='+favoritos.checked,'CANT')\">
";

    $MiHCP=devuelve_un_campo("jugadores",35,"id",$_SESSION['S_id_usuario']);
    $MiHCP=floor($MiHCP/100)+1;

    //die("mihcp ".$MiHCP);

    if($MiHCP<=18)
        echo "
     <option value='18' ".igual('18',$ant_nivel_max)." >18 ".Traductor::traducir("Básico");

    if($MiHCP<=17)
        echo"
     <option  value='17' ".igual('17',$ant_nivel_max)." >17 ".Traductor::traducir("Básico");

    if($MiHCP<=16)
        echo "
     <option value='16' ".igual('16',$ant_nivel_max)." >16 ".Traductor::traducir("Básico");

    if($MiHCP<=15)
        echo "
     <option value='15' ".igual('15',$ant_nivel_max)." >15 ".Traductor::traducir("Medio")."-";

    if($MiHCP<=14)
        echo "
     <option value='14' ".igual('14',$ant_nivel_max)." >14 ".Traductor::traducir("Medio")."-";

    if($MiHCP<=13)
        echo "
     <option value='13' ".igual('13',$ant_nivel_max)." >13 ".Traductor::traducir("Medio")."-";

    if($MiHCP<=12)
        echo "
     <option value='12' ".igual('12',$ant_nivel_max)." >12 ".Traductor::traducir("Medio");

    if($MiHCP<=11)
        echo "
     <option value='11' ".igual('11',$ant_nivel_max)." >11 ".Traductor::traducir("Medio");

    if($MiHCP<=10)
        echo "
     <option value='10' ".igual('10',$ant_nivel_max)." >10 ".Traductor::traducir("Medio");

    if($MiHCP<=9)
        echo "
     <option value='09' ".igual('09',$ant_nivel_max)." >09 ".Traductor::traducir("Medio")."+";

    if($MiHCP<=8)
        echo "
     <option value='08' ".igual('08',$ant_nivel_max)." >08 ".Traductor::traducir("Medio")."+";

    if($MiHCP<=7)
        echo "
     <option value='07' ".igual('07',$ant_nivel_max)." >07 ".Traductor::traducir("Medio")."+";

    if($MiHCP<=6)
        echo "
     <option value='06' ".igual('06',$ant_nivel_max)." >06 ".Traductor::traducir("Radical");

    if($MiHCP<=5)
        echo "
     <option value='05' ".igual('05',$ant_nivel_max)." >05 ".Traductor::traducir("Radical");

    if($MiHCP<=4)
        echo "
     <option value='04' ".igual('04',$ant_nivel_max)." >04 ".Traductor::traducir("Radical");

    if($MiHCP<=3)
        echo "
     <option value='03' ".igual('03',$ant_nivel_max)." >03 ".Traductor::traducir("Radical Pro");

    if($MiHCP<=2)
        echo "
     <option value='02' ".igual('02',$ant_nivel_max)." >02 ".Traductor::traducir("Radical Pro");

    if($MiHCP<=1)
        echo "
     <option value='01' ".igual('01',$ant_nivel_max)." >01 ".Traductor::traducir("Radical Pro");

    echo "

  </SELECT>

";

    /*  echo "<SELECT ".$classmovil." name='nivel_max' $EsPuntuacion id='nivel_max' style=\"\" " .
  		"onchange=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&min='+nivel_min.value+'&max='+this.value+'&yo=".$_SESSION['S_id_usuario']."&genero=".$_SESSION['sexo']."&sexo='+limsexo.checked+'&fav='+favoritos.checked,'CANT')\" " .
  		  "onload=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&min='+nivel_min.value+'&max='+this.value+'&yo=".$_SESSION['S_id_usuario']."&genero=".$_SESSION['sexo']."&sexo='+limsexo.checked+'&fav='+favoritos.checked,'CANT')\">

  <option value='18'".igual('18',$ant_nivel_max)." selected>18 ".Traductor::traducir("Básico")."
  <option value='17'".igual('17',$ant_nivel_max).">17 ".Traductor::traducir("Básico")."
  <option value='16'".igual('16',$ant_nivel_max).">16 ".Traductor::traducir("Básico")."
  <option value='15'".igual('15',$ant_nivel_max).">15 ".Traductor::traducir("Medio")."-
  <option value='14'".igual('14',$ant_nivel_max).">14 ".Traductor::traducir("Medio")."-
  <option value='13'".igual('13',$ant_nivel_max).">13 ".Traductor::traducir("Medio")."-
  <option value='12'".igual('12',$ant_nivel_max).">12 ".Traductor::traducir("Medio")."
  <option value='11'".igual('11',$ant_nivel_max).">11 ".Traductor::traducir("Medio")."
  <option value='10'".igual('10',$ant_nivel_max).">10 ".Traductor::traducir("Medio")."
  <option value='09'".igual('09',$ant_nivel_max).">09 ".Traductor::traducir("Medio")."+
  <option value='08'".igual('08',$ant_nivel_max).">08 ".Traductor::traducir("Medio")."+
  <option value='07'".igual('07',$ant_nivel_max).">07 ".Traductor::traducir("Medio")."+
  <option value='06'".igual('06',$ant_nivel_max).">06 ".Traductor::traducir("Radical")."
  <option value='05'".igual('05',$ant_nivel_max).">05 ".Traductor::traducir("Radical")."
  <option value='04'".igual('04',$ant_nivel_max).">04 ".Traductor::traducir("Radical")."
  <option value='03'".igual('03',$ant_nivel_max).">03 ".Traductor::traducir("Radical Pro")."
  <option value='02'".igual('02',$ant_nivel_max).">02 ".Traductor::traducir("Radical Pro")."
  <option value='01'".igual('01',$ant_nivel_max).">01 ".Traductor::traducir("Radical Pro")."

  </SELECT>

";  */
    if 	($_SESSION['S_Version']!='Movil'){
        echo "</td>";
        echo ("<TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g  id=text_nivel_max  style=\"display: none;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;".Traductor::traducir("Máximo").":</b></TD>");
        echo "<TD ALIGN=LEFT VALIGN=TOP>";
    }else{
        echo ("</td><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g  id=text_nivel_max  style=\"display: none;\"><b>&nbsp;a </b>");

    }
    //echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".$Hora[1]."' size='5' maxlength='5'></TD></tr>");


    echo"

  <SELECT ".$classmovil." name='nivel_min' $EsPuntuacion id='nivel_min' style=\"\" " .
        "onchange=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&max='+nivel_max.value+'&min='+this.value+'&yo=".$_SESSION['S_id_usuario']."&fav='+favoritos.checked,'CANT')\" " .
        "onload=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&max='+nivel_max.value+'&min='+this.value+'&yo=".$_SESSION['S_id_usuario']."&fav='+favoritos.checked,'CANT')\">
  ";

    if($MiHCP>=1)
        echo "
     <option value='01'".igual('01',$ant_nivel_min)." selected>01 ".Traductor::traducir("Radical Pro");
    if($MiHCP>=2)
        echo "
     <option value='02'".igual('02',$ant_nivel_min).">02 ".Traductor::traducir("Radical Pro");
    if($MiHCP>=3)
        echo "
     <option value='03'".igual('03',$ant_nivel_min).">03 ".Traductor::traducir("Radical Pro");
    if($MiHCP>=4)
        echo "
     <option value='04'".igual('04',$ant_nivel_min).">04 ".Traductor::traducir("Radical");
    if($MiHCP>=5)
        echo "
     <option value='05'".igual('05',$ant_nivel_min).">05 ".Traductor::traducir("Radical");
    if($MiHCP>=6)
        echo "
     <option value='06'".igual('06',$ant_nivel_min).">06 ".Traductor::traducir("Radical");
    if($MiHCP>=7)
        echo "
     <option value='07'".igual('07',$ant_nivel_min).">07 ".Traductor::traducir("Medio")."+";

    if($MiHCP>=8)
        echo "
     <option value='08'".igual('08',$ant_nivel_min).">08 ".Traductor::traducir("Medio")."+";
    if($MiHCP>=9)
        echo "
     <option value='09'".igual('09',$ant_nivel_min).">09 ".Traductor::traducir("Medio")."+";
    if($MiHCP>=10)
        echo "
     <option value='10'".igual('10',$ant_nivel_min).">10 ".Traductor::traducir("Medio");
    if($MiHCP>=11)
        echo "
     <option value='11'".igual('11',$ant_nivel_min).">11 ".Traductor::traducir("Medio");
    if($MiHCP>=12)
        echo "
     <option value='12'".igual('12',$ant_nivel_min).">12 ".Traductor::traducir("Medio");
    if($MiHCP>=13)
        echo "
     <option value='13'".igual('13',$ant_nivel_min).">13 ".Traductor::traducir("Medio")."-";
    if($MiHCP>=14)
        echo "
     <option value='14'".igual('14',$ant_nivel_min).">14 ".Traductor::traducir("Medio")."-";
    if($MiHCP>=15)
        echo "
     <option value='15'".igual('15',$ant_nivel_min).">15 ".Traductor::traducir("Medio")."-";
    if($MiHCP>=16)
        echo "
     <option value='16'".igual('16',$ant_nivel_min).">16 ".Traductor::traducir("Básico");
    if($MiHCP>=17)
        echo "
     <option value='17'".igual('17',$ant_nivel_min).">17 ".Traductor::traducir("Básico");
    if($MiHCP>=18)
        echo "
     <option value='18'".igual('18',$ant_nivel_min).">18 ".Traductor::traducir("Básico");

    echo "
  </SELECT>
</TD></tr>
";

    /*echo"

  <SELECT ".$classmovil." name='nivel_min' $EsPuntuacion id='nivel_min' style=\"\" " .
  		"onchange=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&max='+nivel_max.value+'&min='+this.value+'&yo=".$_SESSION['S_id_usuario']."&fav='+favoritos.checked,'CANT')\" " .
  	      "onload=\"cargar2('XMLjugadores.php?liga=".$_SESSION['S_LIGA_ACTUAL']."&max='+nivel_max.value+'&min='+this.value+'&yo=".$_SESSION['S_id_usuario']."&fav='+favoritos.checked,'CANT')\">

  <option value='01'".igual('01',$ant_nivel_min)." selected>01 ".Traductor::traducir("Radical Pro")."
  <option value='02'".igual('02',$ant_nivel_min).">02 ".Traductor::traducir("Radical Pro")."
  <option value='03'".igual('03',$ant_nivel_min).">03 ".Traductor::traducir("Radical Pro")."
  <option value='04'".igual('04',$ant_nivel_min).">04 ".Traductor::traducir("Radical")."
  <option value='05'".igual('05',$ant_nivel_min).">05 ".Traductor::traducir("Radical")."
  <option value='06'".igual('06',$ant_nivel_min).">06 ".Traductor::traducir("Radical")."
  <option value='07'".igual('07',$ant_nivel_min).">07 ".Traductor::traducir("Medio")."+
  <option value='08'".igual('08',$ant_nivel_min).">08 ".Traductor::traducir("Medio")."+
  <option value='09'".igual('09',$ant_nivel_min).">09 ".Traductor::traducir("Medio")."+
  <option value='10'".igual('10',$ant_nivel_min).">10 ".Traductor::traducir("Medio")."
  <option value='11'".igual('11',$ant_nivel_min).">11 ".Traductor::traducir("Medio")."
  <option value='12'".igual('12',$ant_nivel_min).">12 ".Traductor::traducir("Medio")."
  <option value='13'".igual('13',$ant_nivel_min).">13 ".Traductor::traducir("Medio")."-
  <option value='14'".igual('14',$ant_nivel_min).">14 ".Traductor::traducir("Medio")."-
  <option value='15'".igual('15',$ant_nivel_min).">15 ".Traductor::traducir("Medio")."-
  <option value='16'".igual('16',$ant_nivel_min).">16 ".Traductor::traducir("Básico")."
  <option value='17'".igual('17',$ant_nivel_min).">17 ".Traductor::traducir("Básico")."
  <option value='18'".igual('18',$ant_nivel_min).">18 ".Traductor::traducir("Básico")."
  </SELECT>
</TD></tr>
";  */





    if 	($_SESSION['S_Version']!='Movil'){
        echo "</table><table><tr ><td align='center' id='text_cantidad'  style=\"display: none;\" colspan='4'>".Traductor::traducir("Se pueden apuntar")." <input name='CANT' id=CANT value=99 size='2' maxlength='3' readonly disabled>&nbsp;&nbsp; ".Traductor::traducir("jugadores")."</td></tr>";
        echo "<tr><td colspan=2><div id='FORM_CANT_errorloc' class='innerError'></div><div id='errornivel' class='innerError'></div><div id='errorfavs' class='innerError'></div></TD></TR>";
        echo"</table><br>";

//Si la pareja ya esta apuntada no se puede modificar check
        $disabled='';
        if(!posible_modificar_pareja($id,$ant_es_pareja))
            $disabled='onclick="javascript: return false;"';

//Agrego controles de parejas version web
        echo '<div style="text-align:center;margin-top:-40px;">';

//Nuevo 23-5-14, para mostrar o no dependiendo de si la liga esta activa o no para seleccion de parejas
        $parejas_habilitadas = devuelve_un_campo('liga',49,'id',$_SESSION['S_LIGA_ACTUAL']);
        if($parejas_habilitadas!='N')
            echo " <br/><br/> <input type=checkbox name=FiltroParejas value=1 $disabled onclick=\"GestionPareja();\" id='FiltroParejas' ".($ant_es_pareja?'checked=checked':'')." > <span style='font-size:14px;font-weight:bold;color:#009100;'>".Traductor::traducir("Marcar, solo si quieres elegir una pareja")."</span>";

        echo "</div>";

        //Gestión de la pareja a traves de auto relleno de jquery

        if(posible_modificar_pareja($id,$ant_es_pareja)){


            echo '
    <br/><br/>
    <script type="text/javascript" src="/js/jquery.tokeninput.js"></script>
    <link rel="stylesheet" href="/css/token-input.css" type="text/css" />

    <div id="pareja" style="'.($ant_es_pareja?'':'display:none;').'padding:5px;">

        <label for="txtPareja">'.Traductor::traducir("Seleccione a su pareja. Hasta que ésta no haya confirmado su asistencia el<br>partido no estará visible para el resto.").'</label><input type="text" id="txtPareja" name="txtPareja">
    </div>';
        }
        else
        {

            $sqlr = mysql_query("SELECT id_Jugador2 FROM partidos WHERE id=$id") or die("Error partidos-2679: Error al obtener datos de edicion de partidos. Consulte a soporte.");
            $dtsr = mysql_fetch_array($sqlr);
            $id_jugador_2=$dtsr['id_Jugador2'];

            $sqlr = mysql_query("SELECT Nombre, Apellidos FROM jugadores WHERE id=$id_jugador_2") or die("Error partidos-2683: Error al obtener datos de edicion de partidos. Consulte a soporte.");
            $dtsr = mysql_fetch_array($sqlr);
            $jugador_2=$dtsr['Nombre'].' '.$dtsr['Apellidos'];

            echo "<center><h3>".Traductor::traducir("Pareja confirmada").": $jugador_2 </h3></center>";
            echo "<input type='hidden' id='txtPareja' name='txtPareja' value='$ant_id_jugador_invitado'>"; //Para que al modificar se pase la pareja aunque no se modifique.
        }

//   echo $ant_id_jugador_invitado.' '.$ant_nombre_jugador_invitado;
        $nofavoritos="AND id NOT IN (SELECT id_Jugador_NO_Favorito FROM noesfavorito WHERE id_Jugador=".$_SESSION['S_id_usuario'].")";

        echo '<script>

    $(document).ready(function() {


      $("#txtPareja").tokenInput(';

        $sql = "SELECT id, Nombre, Apellidos, Foto, Golf_Hexacto FROM jugadores WHERE id IN (SELECT id_Jugador FROM juegalaliga WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND Estado='ACTIVO' ) AND id!=".$_SESSION['S_id_usuario']." $nofavoritos ";
        $sqr = mysql_query($sql);

        $rows = array();
        $r = "[";

        while($d = mysql_fetch_array($sqr)) {
            $txtnivel = $d['Golf_Hexacto']." (".(floor($d['Golf_Hexacto']/100)+1)." ".Traduce_Nivel($d['Golf_Hexacto']).")";
            if($d['Foto']=='')
                $d['Foto']='SinFoto.gif';
            $r .= "{id: ".$d['id'].", 'nombre':'".trim($d['Nombre'])." ".trim($d['Apellidos'])."', 'foto':'/PROGRAMA/PCU/fotos/".$d['Foto']."', 'nivel':'".$txtnivel."'},";

        }
        $r = substr($r, 0, -1);
        $r .= "]";
        echo $r;

        echo ", {
";

//Pre-rellenamos el buscador con la pareja que ya estaba
        if($ant_es_pareja)
        {
            echo 'prePopulate: [{id:'.$ant_id_jugador_invitado.' ,nombre:"'.$ant_nombre_jugador_invitado.'"}],';

        }

//if(posible_modificar_pareja($id,$ant_es_pareja)) //Para que no pueda modificarse un partido por parejas con pareja apuntada
//   echo 'disabled:true,';


        echo "
 minChars: 3,
 tokenDelimiter: \"||\",
 preventDuplicates: true,
   hintText: \"".Traductor::traducir("Busque a su pareja dentro de la liga. Solo es posible establecer una.")."\",
                noResultsText: \"".Traductor::traducir("No hay ninguna coincidencia")."\",
                searchingText: \"".Traductor::traducir("Buscando...")."\",
                tokenLimit: 1,
              propertyToSearch: \"nombre\",
              resultsFormatter: function(item){ return \"<li>\" + \"<img src='\" + item.foto + \"' title='\" + item.nombre + \"' height='25px' width=\'25px\' style=\'float:left\' />\" + \"<div style=\'float:left;width:300px; padding-left: 10px;\'><div style=\'line-height:10px\' class=\'full_name\'> \" + item.nombre + \"</div><div style=\'color:#aaa;font-size:11px;padding:0px;margin:0px;line-height:15px\'>Nivel: \"+item.nivel+\"</div></div><div style=\'clear:both\'></div></li>\" },
              tokenFormatter: function(item) { return \"<li><p>\" + item.nombre + \"</p></li>\" }
          });
        });

   </script>";

//FIN FILTRO POR PAREJAS Y MIXTO






    }else{
    echo "<tr ><td class='texttitular_g'  align='center' id='text_cantidad'  style=\"display: none;\" colspan='2'>".Traductor::traducir("Se pueden apuntar")." <input name='CANT' id=CANT value=99 style='width: 30px; height: 27px; border: medium none; background-color: rgb(238, 238, 238);' maxlength='3' readonly disabled>&nbsp;".Traductor::traducir("jugadores")."</td></tr>";
    echo "<tr><td colspan=2><div id='FORM_CANT_errorloc' class='innerError'></div><div id='errornivel' class='innerError'></div><div id='errorfavs' class='innerError'></div></TD></TR>";
    echo"</table>";
    ?>

        <!--Controles de Parejas-->

        <?php

//Si la pareja ya esta apuntada no se puede modificar check
        $disabled_mvl='';
        if(!posible_modificar_pareja($id,$ant_es_pareja))
            $disabled_mvl='style="pointer-events: none;"';

        ?>

        <?php

        $parejas_habilitadas = devuelve_un_campo('liga',49,'id',$_SESSION['S_LIGA_ACTUAL']);
    if($parejas_habilitadas!='N')
    {
        ?>

        <table width='100%'>
            <tr>
                <td width='15%'>&nbsp;</td>
                <td align='center' width='35%'>
                    <a <?php echo $disabled_mvl; ?> class="botonancho opt-no-selected <?php if(!$ant_es_pareja) echo "optselected";?>" id="sorteobtn" onclick="javascript:seleccionacheckparejas(false)"><?=Traductor::traducir("Por sorteo")?></a>
                </td>
                <td width='5%'>&nbsp;</td>
                <td align='center' width='35%'>
                    <a <?php echo $disabled_mvl; ?> class="botonancho opt-no-selected  <?php if($ant_es_pareja) echo "optselected";?>" id="conparejabtn" onclick="javascript:seleccionacheckparejas(true)"><?=Traductor::traducir("Con pareja")?></a>
                </td>
                <td width='10%'>&nbsp;</td>

            </tr>
        </table>

    <?php } ?>


    <?php




    //Gestion de la pareja a traves de auto relleno de jquery PARA MOVILES
    if(posible_modificar_pareja($id,$ant_es_pareja)){
        echo '
     <script type="text/javascript" src="/js/jquery.tokeninput.js"></script>
     <link rel="stylesheet" href="/css/token-input.css" type="text/css" />

     <div id="parejaMoviles" style="'.($ant_es_pareja?'':'display:none;').'padding:15px;">
       <input type="text" id="txtPareja" name="txtPareja">
     </div>';
    }
    else
    {
        $sqlr = mysql_query("SELECT id_Jugador2 FROM partidos WHERE id=$id") or die("Error partidos-2679: Error al obtener datos de edicion de partidos. Consulte a soporte.");
        $dtsr = mysql_fetch_array($sqlr);
        $id_jugador_2=$dtsr['id_Jugador2'];

        $sqlr = mysql_query("SELECT Nombre, Apellidos FROM jugadores WHERE id=$id_jugador_2") or die("Error partidos-2683: Error al obtener datos de edicion de partidos. Consulte a soporte.");
        $dtsr = mysql_fetch_array($sqlr);
        $jugador_2=$dtsr['Nombre'].' '.$dtsr['Apellidos'];

        echo "<center><h3>".Traductor::traducir("Pareja confirmada").": $jugador_2 </h3></center>";
        echo "<input type='hidden' id='txtPareja' name='txtPareja' value='$ant_id_jugador_invitado'>"; //Para que al modificar se pase la pareja aunque no se modifique.
    }

    $nofavoritos="AND id NOT IN (SELECT id_Jugador_NO_Favorito FROM noesfavorito WHERE id_Jugador=".$_SESSION['S_id_usuario'].")";

    echo '<script type="text/javascript">

    $(document).ready(function() {


      $("#txtPareja").tokenInput(';

    $sql = "SELECT id, Nombre, Apellidos, Foto, Golf_Hexacto FROM jugadores WHERE id IN (SELECT id_Jugador FROM juegalaliga WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND Estado='ACTIVO' ) AND id!=".$_SESSION['S_id_usuario']." $nofavoritos ";
    $sqr = mysql_query($sql);

    $rows = array();
    $r = "[";

    while($d = mysql_fetch_array($sqr)) {
        $txtnivel = $d['Golf_Hexacto']." (".(floor($d['Golf_Hexacto']/100)+1)." ".Traduce_Nivel($d['Golf_Hexacto']).")";
        if($d['Foto']=='')
            $d['Foto']='SinFoto.gif';
        $r .= "{id: ".$d['id'].", 'nombre':'".trim($d['Nombre'])." ".trim($d['Apellidos'])."', 'foto':'/PROGRAMA/PCU/fotos/".$d['Foto']."', 'nivel':'".$txtnivel."'},";

    }
    $r = substr($r, 0, -1);
    $r .= "]";
    echo $r;

    echo ", {
";

    //Pre-rellenamos el buscador con la pareja que ya estaba
    if($ant_es_pareja)
    {
        echo 'prePopulate: [{id:'.$ant_id_jugador_invitado.' ,nombre:"'.$ant_nombre_jugador_invitado.'"}],';
    }

    //if(!posible_modificar_pareja($id,$ant_es_pareja)) //Para que no pueda modificarse un partido por parejas con pareja apuntada
    //   echo 'disabled:true,';

    echo "
 minChars: 3,
 tokenDelimiter: \"||\",
 preventDuplicates: true,
   hintText: \"".Traductor::traducir("Elegir pareja. Recibirá un mensaje de aviso")."\",
                noResultsText: \"".Traductor::traducir("No hay ninguna coincidencia")."\",
                searchingText: \"".Traductor::traducir("Buscando...")."\",
                tokenLimit: 1,
              propertyToSearch: \"nombre\",
              resultsFormatter: function(item){ return \"<li>\" + \"<img src='\" + item.foto + \"' title='\" + item.nombre + \"' height='25px' width=\'25px\' style=\'float:left\' />\" + \"<div style=\'float:left;width:250px; padding-left: 10px;\'><div style=\'line-height:10px\' class=\'full_name\'> \" + item.nombre + \"</div><div style=\'color:#aaa;font-size:11px;padding:0px;margin:0px;line-height:15px\'>Nivel: \"+item.nivel+\"</div></div><div style=\'clear:both\'></div></li>\" },
              tokenFormatter: function(item) { return \"<li><p>\" + item.nombre + \"</p></li>\" }
          });
        });

   </script>";

    //FIN FILTRO POR PAREJAS PARA MOVILES


    ?>


    <?php
    //Funciones JS
    echo "<script type='text/javascript'>
   function GestionParejaMoviles(parejas) //Muestra o no el div de seleccion de pareja dependiendo de la seleccion del check
   {
     var divPareja = document.getElementById( 'parejaMoviles' );
     var chkboxPareja = document.getElementById( 'conparejabtn' );
     if( parejas )
     {
       divPareja.style.display = 'inline';
     }
     else
     {
       divPareja.style.display = 'none';
     }

   }


   </script>";

    //FIN fUNCIONES JS
    ?>

        <!-- hace el seleccionable alternado de parejas y sorteo-->
        <script>
            function seleccionacheckparejas(parejas)
            {

                $('#FiltroParejas').attr('checked', !$('#FiltroParejas').attr('checked'));
                $('#conparejabtn').toggleClass('optselected',parejas);
                $('#sorteobtn').toggleClass('optselected',!parejas);
                GestionParejaMoviles(parejas);

            }
        </script>

        <script>
            function seleccionacheck(namecheck){

                $('#'+namecheck).attr('checked', !$('#'+namecheck).attr('checked'));

                $('#'+namecheck+'btn').toggleClass('optselected');
                verifica2();

            }
        </script>
        <?
    }
        if ($op=="modificar") echo "<p class=texttitular style='color:gray;'>".Traductor::traducir("Las nuevas restricciones sólo serán efectivas para los jugadores que quieran apuntarse, no para los que ya estén apuntados")."</p>";

        echo"</td></tr>";

        echo "<script>verifica2();</script>";

//PieMovil (1);

    }//si es puntuación
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////              PUNTUACION                   ///////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
    else
    {


//// SI NO TIENE $PAREJA
        if (!$PAREJA)
        {
            if 	($_SESSION['S_Version']!='Movil')
            {
                echo("<div align='center'><TABLE BORDER=0 WIDTH='95%' CELLPADDING=5><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
                echo"<tr><td colspan=4>".Traductor::traducir("En primer lugar selecciona a TU PAREJA en el partido")."</td></tr>";

                echo"<tr><td align=center colspan=4><table border=0>";
                echo"<tr bgcolor='#FFFFFF'>";
                echo"<td class='fila' width=90><p class='dato' align='center'>".devuelve_un_campo ("campos",2,"id",$ant_id_Campo)."<br>$ant_TipoPuntuacion</p></td>";
                echo"<td width=90 class='fila'><p class='dato' align='center'><b>".cambiaf_a_normal($ant_Fecha)."<br>$ant_Hora</b></p></td>";

                echo"<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,1)."<p></p></td>";
                echo"<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,1)."<p></p></td>";
                if ($ant_id_Jugador3) echo"	<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,1)."<p></p></td>";
                if ($ant_id_Jugador4) echo"<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,1)."<p></p></td>";
                echo"</tr>";
                echo"<tr bgcolor='#FFFFFF'> <td class='fila' colspan=2><p class='dato' align='center'>".Traductor::traducir("Selecciona al jugador con el que has jugado de pareja")."<br></p></td>";
                echo"<td class='fila' width=440 colspan=4>";
                echo"<SELECT name=PAREJA>";

                if ($_SESSION['S_id_usuario']!=$ant_id_Jugador1) echo"  <option value='$ant_id_Jugador1'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,1);
                if ($_SESSION['S_id_usuario']!=$ant_id_Jugador2) echo"  <option value='$ant_id_Jugador2'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,1);
                if ($_SESSION['S_id_usuario']!=$ant_id_Jugador3) echo"  <option value='$ant_id_Jugador3'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,1);
                if ($_SESSION['S_id_usuario']!=$ant_id_Jugador4) echo"  <option value='$ant_id_Jugador4'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,1);

                echo"</SELECT>";


                echo"</td>";


                /*
        echo"<td class='fila' width=110><p class='dato' align='center'><input class=textbox type='text' name='Puntos_J1' value='$ant_Puntos_J1' size='4' maxlength='2'><p></p></td>";
        echo"<td class='fila' width=110><p class='dato' align='center'><input class=textbox type='text' name='Puntos_J2' value='$ant_Puntos_J2' size='4' maxlength='2'><p></p></td>";
        if ($ant_id_Jugador3) echo"	<td class='fila' width=110><p class='dato' align='center'><input class=textbox type='text' name='Puntos_J3' value='$ant_Puntos_J3' size='4' maxlength='2'><p></p></td>";
        if ($ant_id_Jugador4) echo"<td class='fila' width=110><p class='dato' align='center'><input class=textbox type='text' name='Puntos_J4' value='$ant_Puntos_J4' size='4' maxlength='2'><p></p></td>";
        */
                echo"</tr>";

                echo"</td></tr></table><br><br></td></tr>";
            }//////////////////////////////


            else///////////////////////// VERSION MOVIL
            {
                echo("<TABLE BORDER=0 WIDTH='100%' CELLPADDING=5><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
                echo"<tr><td colspan=2><div style='text-align:center' class='caja-texto'>".Traductor::traducir("En primer lugar selecciona a TU PAREJA en el partido")."</div></td></tr>";

                echo"<tr><td align=center colspan=2><div  class='caja-texto'><table border=0 >";
                echo"<tr>";
                echo"<td class='' colspan=2><p class='dato' style='font-size:1.1em' align='center'>".devuelve_un_campo ("campos",2,"id",$ant_id_Campo)."<br>$ant_TipoPuntuacion</p></td>";
                echo"<td width=90 class='' colspan=2><p class='dato' style='font-size:1.1em' align='center'><b>".cambiaf_a_normal($ant_Fecha)."<br>$ant_Hora</b></p></td>";

                echo"</tr><td colspan=4>&nbsp;</td><tr style='font-size:1.5em;line-height:0.6em'>";

                echo"<td class='botonancho-3 seleccion-pareja'><p class='dato' align='center' >".minificha_jugador($id,$ant_id_Jugador1,1,1,0,false)."<p></p></td>";
                echo"<td class='botonancho-3 seleccion-pareja'><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,false)."<p></p></td>";
                if ($ant_id_Jugador3) echo"	<td class='botonancho-3 seleccion-pareja'><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,false)."<p></p></td>";
                if ($ant_id_Jugador4) echo"<td class='botonancho-3 seleccion-pareja'><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,false)."<p></p></td>";
                echo"</tr><tr><td colspan=4>&nbsp;</td></tr>";
                echo"<tr> <td class='' colspan=4 align=center><p class='dato' align='center'>".Traductor::traducir("Selecciona al jugador con el que has jugado de pareja")."<br></p>";
                echo "<span class='botonancho-3' style='display: inline-block;padding: 0px;'>";
                echo"<SELECT style='margin: 0px; border: 0px none;' name=PAREJA class='botonancho'>";

                if ($_SESSION['S_id_usuario']!=$ant_id_Jugador1) echo"  <option value='$ant_id_Jugador1'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,false);
                if ($_SESSION['S_id_usuario']!=$ant_id_Jugador2) echo"  <option value='$ant_id_Jugador2'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,false);
                if ($_SESSION['S_id_usuario']!=$ant_id_Jugador3) echo"  <option value='$ant_id_Jugador3'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,false);
                if ($_SESSION['S_id_usuario']!=$ant_id_Jugador4) echo"  <option value='$ant_id_Jugador4'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,false);

                echo"</SELECT></span>";


                echo"</td>";


                /*
        echo"<td class='fila' width=110><p class='dato' align='center'><input class=textbox type='text' name='Puntos_J1' value='$ant_Puntos_J1' size='4' maxlength='2'><p></p></td>";
        echo"<td class='fila' width=110><p class='dato' align='center'><input class=textbox type='text' name='Puntos_J2' value='$ant_Puntos_J2' size='4' maxlength='2'><p></p></td>";
        if ($ant_id_Jugador3) echo"	<td class='fila' width=110><p class='dato' align='center'><input class=textbox type='text' name='Puntos_J3' value='$ant_Puntos_J3' size='4' maxlength='2'><p></p></td>";
        if ($ant_id_Jugador4) echo"<td class='fila' width=110><p class='dato' align='center'><input class=textbox type='text' name='Puntos_J4' value='$ant_Puntos_J4' size='4' maxlength='2'><p></p></td>";
        */
                echo"</tr>";

                echo"</td></tr></table><br></div></td></tr>";
            }

            echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP style='padding: 0 20px;'><p ID=ift> <input type='submit' class='superboton' style='float:left; width:64%' value='".Traductor::traducir("CONTINUAR")."'></p>");
            if 	($_SESSION['S_Version']!='Movil')
                echo "</td>";
            echo ("<input type=hidden name=menu value='resultado'>");
            echo ("<input type=hidden name=id menu value='$id'>");
            echo ("<input type=hidden name=ESRESULTADO value='1'>");

//PieMovil ();

        }
        else if (!$RESULTADO)
        {
            if 	($_SESSION['S_Version']!='Movil')
            {
                echo("<div align='center'><TABLE BORDER=0 WIDTH='95%' CELLPADDING=5><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
                echo"<tr><td colspan=4>".Traductor::traducir("Ahora indica si habéis ganado, empatado o perdido el partido")."</td></tr>";

                echo"<tr><td align=center colspan=4><table border=0>";
                echo"<tr bgcolor='#FFFFFF'>";
                echo"<td class='fila' width=90><p class='dato' align='center'>".devuelve_un_campo ("campos",2,"id",$ant_id_Campo)."<br>$ant_TipoPuntuacion</p></td>";
                echo"<td width=90 class='fila'><p class='dato' align='center'><b>".cambiaf_a_normal($ant_Fecha)."<br>$ant_Hora</b></p></td>";

                echo"<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,1)."<p></p></td>";
                echo"<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,1)."<p></p></td>";
                if ($ant_id_Jugador3) echo"	<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,1)."<p></p></td>";
                if ($ant_id_Jugador4) echo"<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,1)."<p></p></td>";
                echo"</tr>";
                echo"<tr bgcolor='#FFFFFF'> <td class='fila' colspan=2><p class='dato' align='center'>".Traductor::traducir("Has jugado de pareja con").":<br></p></td>";
                echo"<td class='fila' width=440 colspan=4> ".minificha_jugador($id,$PAREJA,1,1,0,1);
                echo"</td>";

                echo"</tr>";
                echo"<tr bgcolor='#FFFFFF'> <td class='fila' colspan=2><p class='dato' align='center'>".Traductor::traducir("El resultado obtenido ha sido").":<br></p></td>";
                echo"<td class='fila' width=440 colspan=4>";
                echo"<SELECT name=RESULTADO>";
                echo"  <option value='GANADORES'>".Traductor::traducir("GANADORES")."";
                echo"  <option value='EMPATE'>".Traductor::traducir("EMPATE")."";
                echo"  <option value='PERDEDORES'>".Traductor::traducir("PERDEDORES")."";

                echo"</SELECT>";
                echo"</td>";
                echo"</tr>";

                echo"</td></tr></table><br><br></td></tr>";
                echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP><p ID=ift> <input type='submit' value='".Traductor::traducir("CONTINUAR")."'></p>");

                echo ("<input type=hidden name=menu value='resultado'>");
                echo ("<input type=hidden name=PAREJA value='$PAREJA'>");
                echo ("<input type=hidden name=id menu value='$id'>");
                echo ("<input type=hidden name=ESRESULTADO value='1'>");

                // echo"</tr>";

//echo"</td></tr></table><br><br></td></tr>";
            }
            else ///////////////////VERSION MOVIL
            {
                echo("<TABLE BORDER=0 WIDTH='100%' CELLPADDING=5><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
                echo"<tr><td colspan=4><div style='text-align:center' class='caja-texto'>".Traductor::traducir("Ahora indica si habéis ganado, empatado o perdido el partido")."</div></td></tr>";

                echo"<tr><td align=center colspan=4><div class='caja-texto'><table border=0 > ";
                echo"<tr bgcolor=''>";
                echo"<td class='' colspan=2><p class='dato'  style='font-size:1.1em' align='center'>".devuelve_un_campo ("campos",2,"id",$ant_id_Campo)."<br>$ant_TipoPuntuacion</p></td>";
                echo"<td width=90 class='' colspan=2><p class='dato'  style='font-size:1.1em' align='center'><b>".cambiaf_a_normal($ant_Fecha)."<br>$ant_Hora</b></p></td>";

                echo"</tr><td colspan=4>&nbsp;</td><tr style='font-size:1.5em;line-height:0.6em'>";

                echo"<td class='botonancho-3 seleccion-pareja'  ><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,false)."<p></p></td>";
                echo"<td class='botonancho-3 seleccion-pareja'  ><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,false)."<p></p></td>";
                if ($ant_id_Jugador3)
                    echo"	<td class='botonancho-3 seleccion-pareja' ><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,false)."<p></p></td>";
                if ($ant_id_Jugador4)
                    echo"<td class='botonancho-3 seleccion-pareja' ><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,false)."<p></p></td>";
                echo"</tr><tr><td colspan=4>&nbsp;</td></tr>";
                echo"<tr> <td class='' style='font-size:1.6em;line-height:0.6em' colspan=4><p class='dato' align='center'>".Traductor::traducir("Has jugado de pareja con").":<br></p>";
                echo "".minificha_jugador($id,$PAREJA,1,1,0,false);
                echo"</td>";

                echo"</tr><tr><td colspan=4>&nbsp;</td></tr>";
                echo"<tr> <td class='filtrocontainer2'  colspan=4 align='center'><p class='dato' >".Traductor::traducir("El resultado obtenido ha sido").":<br></p>";
                //echo"<td class='fila' width=440 colspan=4>";
                echo"<span style='display:inline-block;padding:0px' class='botonancho-3'><SELECT style='margin:0'  class='botonancho' name=RESULTADO>";
                echo"  <option value='GANADORES'>".Traductor::traducir("GANADORES")."";
                echo"  <option value='EMPATE'>".Traductor::traducir("EMPATE")."";
                echo"  <option value='PERDEDORES'>".Traductor::traducir("PERDEDORES")."";

                echo"</SELECT></span>";
                echo"</td>";
                echo"</tr>";

                echo"</td></tr></table></div></td></tr>";
                echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP style='padding: 0 20px;'><p ID=ift> <input type='submit' class='superboton' style='float: left;width:64%' value='".Traductor::traducir("CONTINUAR")."'></p>");
                if 	($_SESSION['S_Version']!='Movil')
                    echo "</td>";
                echo ("<input type=hidden name=menu value='resultado'>");
                echo ("<input type=hidden name=PAREJA value='$PAREJA'>");
                echo ("<input type=hidden name=id menu value='$id'>");
                echo ("<input type=hidden name=ESRESULTADO value='1'>");

                // echo"</tr>";

//echo"</td></tr></table><br><br></td></tr>";
            }

        }
        else if (($RESULTADO)&&($PAREJA))
        {
            if 	($_SESSION['S_Version']!='Movil')
            {
                echo("<div align='center'><TABLE BORDER=0 WIDTH='95%' CELLPADDING=5><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
                echo"<tr><td colspan=4>".Traductor::traducir("Confirma los datos introducidos")."</td></tr>";
                echo"<tr><td align=center colspan=4><table border=0>";
                echo"<tr bgcolor='#FFFFFF'>";
                echo"<td class='fila' colspan=2><p class='dato' align='center'>".devuelve_un_campo ("campos",2,"id",$ant_id_Campo)."<br>$ant_TipoPuntuacion</p></td>";
                echo"<td class='fila'  colspan=2><p class='dato' align='center'>Día: <b>".cambiaf_a_normal($ant_Fecha)."</b> hora: <b> $ant_Hora</b></p></td></tr><td colspan=4>&nbsp;</td>";

                echo"<tr class=dato>";
                echo"<td colspan=2 align=center><br><b>".Traductor::traducir("PAREJA")." A</B></td>";echo"<td colspan=2 align=center><br><b>".Traductor::traducir("PAREJA")." B</B></td>";
                echo"</tr>";

                echo"<tr>";

                //echo"<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,1)."<p></p></td>";


                echo"<td class='fila' width=110 align=center><p class='dato' align='center'>".minificha_jugador($id,$_SESSION['S_id_usuario'],1,1,0,1)."<p></p></td>";
                //echo ("<input type=hidden name=PAREJA value='$PAREJA'>");
                echo"<td class='fila' width=110 align=center><p class='dato' align='center'>".minificha_jugador($id,$PAREJA,1,1,0,1)."<p></p></td>";

                if (($ant_id_Jugador1!=$PAREJA)&&($ant_id_Jugador1!=$_SESSION['S_id_usuario']))
                {
                    echo"<td class='fila' width=110 align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,1)."<p></p></td>";
                    echo ("<input type=hidden name=Puntos_J1 value='B'>");
                }
                else echo ("<input type=hidden name=Puntos_J1 value='A'>");

                if (($ant_id_Jugador2!=$PAREJA)&&($ant_id_Jugador2!=$_SESSION['S_id_usuario']))
                {
                    echo"<td class='fila' width=110 align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,1)."<p></p></td>";
                    echo ("<input type=hidden name=Puntos_J2 value='B'>");

                }
                else echo ("<input type=hidden name=Puntos_J2 value='A'>");
                if (($ant_id_Jugador3!=$PAREJA)&&($ant_id_Jugador3!=$_SESSION['S_id_usuario']))
                {
                    echo"<td class='fila' width=110 align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,1)."<p></p></td>";
                    echo ("<input type=hidden name=Puntos_J3 value='B'>");
                }
                else echo ("<input type=hidden name=Puntos_J3 value='A'>");
                if (($ant_id_Jugador4!=$PAREJA)&&($ant_id_Jugador4!=$_SESSION['S_id_usuario']))
                {
                    echo"<td class='fila' width=110 align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,1)."<p></p></td>";
                    echo ("<input type=hidden name=Puntos_J4 value='B'>");
                }
                else echo ("<input type=hidden name=Puntos_J4 value='A'>");
                echo"</tr>";


                echo"<tr class=dato>";
                if ($RESULTADO=='GANADORES') {echo"<td colspan=2 align=center style='bgcolor:green;'><b>".Traductor::traducir("GANADORES")."</B></td>";echo"<td colspan=2 align=center><b>".Traductor::traducir("PERDEDORES")."</B></td>";}
                else if ($RESULTADO=='PERDEDORES') {echo"<td colspan=2 align=center style='bgcolor:green;'><b>".Traductor::traducir("PERDEDORES")."</B></td>";echo"<td colspan=2 align=center><b>".Traductor::traducir("GANADORES")."</B></td>";}
                else echo"<td colspan=4 align=center><b>".Traductor::traducir("EMPATE")."</B></td>";

                echo"</tr>";
            }
            else ////////////////////VERSION MOVIL
            {
                echo("<TABLE BORDER=0 WIDTH='100%' CELLPADDING=5><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
                echo"<tr><td colspan=2><div style='text-align:center' class='caja-texto'>".Traductor::traducir("Confirma los datos introducidos")."</div></td></tr>";
                echo"<tr><td align=center colspan=2><div class='caja-texto'><table border=0 WIDTH='95%' cellspacing=0 >";
                echo"<tr>";
                echo"<td class='' colspan=2><p class='dato'  style='font-size:1.1em' align='center'>".devuelve_un_campo ("campos",2,"id",$ant_id_Campo)."<br>$ant_TipoPuntuacion</p></td>";
                echo"<td class=''  colspan=2><p class='dato'  style='font-size:1.1em' align='center'><b>".cambiaf_a_normal($ant_Fecha)."</b><br><b> $ant_Hora</b></p></td></tr>";

                echo"<tr class=dato>";
                echo"<td colspan=2 align=center style='border-right:1px solid grey'><br><b>".Traductor::traducir("PAREJA")." A</B></td>";echo"<td colspan=2 align=center><br><b>".Traductor::traducir("PAREJA")." B</B></td>";
                echo"</tr>";
                echo ("<tr><td colspan=2 style='border-right:1px solid grey'>&nbsp;</td><td colspan=2>&nbsp;</td></tr>");
                echo"<tr style='font-size:1.5em;line-height:0.6em'>";

                //echo"<td class='fila' width=110><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,1)."<p></p></td>";


                echo"<td class='botonancho-3 seleccion-pareja'  width=25% align=center><p class='dato' align='center'>".minificha_jugador($id,$_SESSION['S_id_usuario'],1,1,0,false)."<p></p></td>";
                //echo ("<input type=hidden name=PAREJA value='$PAREJA'>");
                echo"<td class='botonancho-3 seleccion-pareja' style='border-right:1px solid grey' width=25% align=center><p class='dato' align='center'>".minificha_jugador($id,$PAREJA,1,1,0,false)."<p></p></td>";

                if (($ant_id_Jugador1!=$PAREJA)&&($ant_id_Jugador1!=$_SESSION['S_id_usuario']))
                {
                    echo"<td class='botonancho-3 seleccion-pareja' width=25% align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador1,1,1,0,false)."<p></p></td>";
                    echo ("<input type=hidden name=Puntos_J1 value='B'>");
                }
                else echo ("<input type=hidden name=Puntos_J1 value='A'>");

                if (($ant_id_Jugador2!=$PAREJA)&&($ant_id_Jugador2!=$_SESSION['S_id_usuario']))
                {
                    echo"<td class='botonancho-3 seleccion-pareja' width=25% align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,false)."<p></p></td>";
                    echo ("<input type=hidden name=Puntos_J2 value='B'>");

                }
                else echo ("<input type=hidden name=Puntos_J2 value='A'>");
                if (($ant_id_Jugador3!=$PAREJA)&&($ant_id_Jugador3!=$_SESSION['S_id_usuario']))
                {
                    echo"<td class='botonancho-3 seleccion-pareja'  width=25% align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,false)."<p></p></td>";
                    echo ("<input type=hidden name=Puntos_J3 value='B'>");
                }
                else echo ("<input type=hidden name=Puntos_J3 value='A'>");
                if (($ant_id_Jugador4!=$PAREJA)&&($ant_id_Jugador4!=$_SESSION['S_id_usuario']))
                {
                    echo"<td class='botonancho-3 seleccion-pareja' width=25% align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,false)."<p></p></td>";
                    echo ("<input type=hidden name=Puntos_J4 value='B'>");
                }
                else echo ("<input type=hidden name=Puntos_J4 value='A'>");
                echo"</tr>";

                echo ("<tr><td colspan=2 style='border-right:1px solid grey'>&nbsp;</td><td colspan=2>&nbsp;</td></tr>");
                echo"<tr class=dato>";
                if ($RESULTADO=='GANADORES') {echo"<td colspan=2 align=center style='border-right:1px solid grey'><b>".Traductor::traducir("GANADORES")."</B></td>";echo"<td colspan=2 align=center><b>".Traductor::traducir("PERDEDORES")."</B></td>";}
                else if ($RESULTADO=='PERDEDORES') {echo"<td colspan=2 align=center style=''><b>".Traductor::traducir("PERDEDORES")."</B></td>";echo"<td colspan=2 align=center><b>".Traductor::traducir("GANADORES")."</B></td>";}
                else echo"<td colspan=4 align=center><b>".Traductor::traducir("EMPATE")."</B></td>";

                echo"</tr>";
                echo"</table></div></td></tr>";
            }

            /*
        echo"<tr bgcolor='#FFFFFF'> <td class='fila' colspan=2><p class='dato' align='center'>Has jugado de pareja con:<br></p></td>";
        echo"<td class='fila' width=440 colspan=4> ".minificha_jugador($id,$PAREJA,1,1,0,1);
            echo"</td>";

        echo"</tr>";
        echo"<tr bgcolor='#FFFFFF'> <td class='fila' colspan=2><p class='dato' align='center'>El resultado obtenido ha sido:<br></p></td>";
        echo"<td class='fila' width=440 colspan=4>";
        echo"<SELECT name=RESULTADO>";
    echo"  <option value='GANADORES'>GANADORES";
    echo"  <option value='EMPATE'>EMPATE";
    echo"  <option value='PERDEDORES'>PERDEDORES";

        echo"</SELECT>";
        echo"</td>";
        echo"</tr>";

        echo"</td></tr></table><br><br></td></tr>";
    echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP><p ID=ift> <input type='submit' value='CONFIRMAR RESULTADO'></p></TD>");
    echo ("<input type=hidden name=menu value='resultado'>");
    echo ("<input type=hidden name=PAREJA value='$PAREJA'>");
    echo ("<input type=hidden name=id menu value='$id'>");
    echo ("<input type=hidden name=ESRESULTADO value='1'>");
    */
            // echo"</tr>";

//echo"</td></tr></table><br><br></td></tr>";

        }




        /*
    echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>id_Jugador1:</b></TD>");
     rellena_select('jugadores','id_Jugador1','Nombre','id',$ant_id_Jugador1,'');	// echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='id_Jugador1' value='".htmlentities($ant_id_Jugador1)."' size='10) ' maxlength='10) '></TD></tr>");
    echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>id_Jugador2:</b></TD>");
     rellena_select('jugadores','id_Jugador2','Nombre','id',$ant_id_Jugador2,'');	// echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='id_Jugador2' value='".htmlentities($ant_id_Jugador2)."' size='10) ' maxlength='10) '></TD></tr>");
    echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>id_Jugador3:</b></TD>");
     rellena_select('jugadores','id_Jugador3','Nombre','id',$ant_id_Jugador3,'');	// echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='id_Jugador3' value='".htmlentities($ant_id_Jugador3)."' size='10) ' maxlength='10) '></TD></tr>");
    echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>id_Jugador4:</b></TD>");
     rellena_select('jugadores','id_Jugador4','Nombre','id',$ant_id_Jugador4,'');	// echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='id_Jugador4' value='".htmlentities($ant_id_Jugador4)."' size='10) ' maxlength='10) '></TD></tr>");
    echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Puntos_J1:</b></TD>");
    echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Puntos_J1' value='".htmlentities($ant_Puntos_J1)."' size='11' maxlength='11'></TD></tr>");
    echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Puntos_J2:</b></TD>");
    echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Puntos_J2' value='".htmlentities($ant_Puntos_J2)."' size='11' maxlength='11'></TD></tr>");
    echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Puntos_J3:</b></TD>");
    echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Puntos_J3' value='".htmlentities($ant_Puntos_J3)."' size='11' maxlength='11'></TD></tr>");
    echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Puntos_J4:</b></TD>");
    echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Puntos_J4' value='".htmlentities($ant_Puntos_J4)."' size='11' maxlength='11'></TD></tr>");
    */



        if (0)
        {
            echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>id_Jugador_ApuntaResult:</b></TD>");
            rellena_select('jugadores','id_Jugador_ApuntaResult','Nombre','id',$ant_id_Jugador_ApuntaResult,'');	 //echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='id_Jugador_ApuntaResult' value='".htmlentities($ant_id_Jugador_ApuntaResult)."' size='10) ' maxlength='10) '></TD></tr>");

            echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Fecha_Result:</b></TD>");
            echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Fecha_Result' value='".cambiaf_a_normal($ant_Fecha_Result)."' id='Fec2' size='25' maxlength='25'><a href=\"javascript:NewCal('Fec2','ddmmyyyy',false,24)\"><img src='./images/cal.gif' width='16' height='16' border='0' alt='Mostrar calendario'></a></TD></tr>");
        }

    }
    if ($op!="puntuacion")
    {
    if 	($_SESSION['S_Version']!='Movil')
    {


/////////PARTIDOS AMISTOSOS ESCRITORIO//////////////

        if(puede_crear_amistosos() && LigaDentroDePeriodo()) //Comprobacion de si el usuario logado puede crear amistosos según su nivel.
        {


            ?>
            <tr>
            <tr>
                <TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Partido amistoso </b></TD>
                <?

                echo "<td align=left valign=top><input type=checkbox name='FiltroAmistosos' $anchoMovil value=1 id='FiltroAmistosos'  ".($ant_es_amistoso?'checked=checked':'')." ><span style='font-size:1em;margin-left:10px;display:none'>(cuenta para Clasificaci&oacute;n pero no para Rankings)</span></td>";
                ?>

            </tr>

            <?
        }//Fin de comprobacion de si puede crear amistosos Y FIN SECCION DE AMISTOSOS PARA VERSION ESCRITORIO
///////////////////////////////////





        echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>".Traductor::traducir("Observaciones").":</b></TD>");
        echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Observaciones' value='".htmlentities($ant_Observaciones)."' size='50' $anchoMovil maxlength='150'></TD></tr>");

    }
    else
    {
    echo ("</table></div><div class='filtrocontainer' style='background:#eee;margin-bottom:10px'><TABLE BORDER=0 WIDTH='auto' style='width:100%' CELLPADDING=0>");


    /////////PARTIDOS AMISTOSOS MOVIL//////////////
    ?>

    <!-- hace el seleccionable alternado de parejas y sorteo-->
    <script>
        function seleccionacheckamistosos(parejas)
        {

            $('#FiltroAmistosos').attr('checked', !$('#FiltroAmistosos').attr('checked'));
            $('#torneobtn').toggleClass('optselected',parejas);
            $('#amistosobtn').toggleClass('optselected',!parejas);
            //alert($('#FiltroAmistosos').attr('checked'));

        }
    </script>

    <?php

    if(puede_crear_amistosos()  && LigaDentroDePeriodo()) //Comprobacion de si el usuario logado puede crear amistosos según su nivel.
    {


    ?>
    <table width='100%'>
        <tr>
            <td width='15%'>
                <b class="texttitular_g">Tipo</b>
                <?

                echo "<input type=checkbox style='display:none;' name='FiltroAmistosos' value=1 id='FiltroAmistosos'  ".($ant_es_amistoso?'checked=checked':'')." >";
                ?>
            </td>
            <td align='center' width='35%'>
                <a class="botonancho opt-no-selected <?php if(!$ant_es_amistoso) echo "optselected";?>" id="torneobtn" onclick="javascript:seleccionacheckamistosos(true)">Torneo</a>
            </td>
            <td width='5%'>&nbsp;</td>
            <td align='center' width='35%'>
                <a class="botonancho opt-no-selected <?php if($ant_es_amistoso) echo "optselected";?>" id="amistosobtn" onclick="javascript:seleccionacheckamistosos(false)">Amistoso</a>
            </td>
            <td width='10%'>&nbsp;</td>

        </tr>

        <?
        }//Fin de comprobacion de si puede crear amistosos Y SECCION DE AMISTOSOS PARA VERSION MOVIL
        ///////////////////////////////////



        echo ("<tr ><TD ALIGN=left style='width:10%' class=texttitular_g><b>".Traductor::traducir("Notas")."&nbsp;</b></TD>");
        echo ("<TD colspan=4 ALIGN=LEFT VALIGN=TOP><input class=botonancho-100 type='text' name='Observaciones' value='".htmlentities($ant_Observaciones)."' style=' maxlength='150'></TD></tr>");

        echo "</table>";
        echo '<div style="text-align:left;padding-top:10px;padding-left:15px;font-size:0.8em;">'.LANG_RESPOSABILIDADES_ORGANIZADOR.'</div>';
        echo "</div><TABLE BORDER=0 WIDTH='auto' style='width:100%' CELLPADDING=0>";
        }
        }



        ////BOTON
        if ($op=="puntuacion")
        {
            if (($PAREJA)&&($RESULTADO))
            {
//echo"PAREJA=$PAREJA<br>";
                if 	($_SESSION['S_Version']!='Movil')
                {
                    echo"<tr><td colspan=4 align=center></td></tr>";
                    echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP colspan=2><p ID=ift> <input type='submit' value='".Traductor::traducir("GUARDAR RESULTADO")."'></p></TD>");
                }
                else  echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP colspan=0 style='padding:0 20px'><p ID=ift> <input type='submit' style='float:left; width:64%' class='superboton' value='".Traductor::traducir("GUARDAR RESULTADO")."'></p>");


                echo ("<input type=hidden name=menu value='modificarya'>");
                echo ("<input type=hidden name=id menu value='$id'>");
                echo ("<input type=hidden name=ESRESULTADO value='1'>");
//$_SESSION['S_LIGA_ACTUAL']
                echo ("<input type=hidden name=id_Liga value='".$_SESSION['S_LIGA_ACTUAL']."'>");
//id_Jugador1 $_SESSION['S_usuario']
                echo ("<input type=hidden name=Fecha value='".cambiaf_a_normal($ant_Fecha)."'>");
                echo ("<input type=hidden name=Hora value='$Hora[0]'>");
                echo ("<input type=hidden name=Minutos value='$Hora[1]'>");
                echo ("<input type=hidden name=TipoPuntuacion value='$ant_TipoPuntuacion'>");
                echo ("<input type=hidden name=id_Campo value='$ant_id_Campo'>");
                echo ("<input type=hidden name=Otro_Campo value='$ant_Otro_Campo'>");
                echo ("<input type=hidden name=PAREJA value='$PAREJA'>");
                echo ("<input type=hidden name=RESULTADO value='$RESULTADO'>");
                echo ("<input type=hidden name=id_Jugador1 value='$ant_id_Jugador1'>");
                echo ("<input type=hidden name=id_Jugador2 value='$ant_id_Jugador2'>");
                echo ("<input type=hidden name=id_Jugador3 value='$ant_id_Jugador3'>");
                echo ("<input type=hidden name=id_Jugador4 value='$ant_id_Jugador4'>");
                echo ("<input type=hidden name=id_Jugador_ApuntaResult value='".$_SESSION['S_id_usuario']."'>");
                echo ("<input type=hidden name=Fecha_Result value='".date('d/m/Y')."'>");
            }
        }
        else
        {
//echo"</div>";
//RECUADRO_CSS ('Hola que tal','','');
//echo"<div class=notaamrilla>asdfasdf</div>";

            if 	($_SESSION['S_Version']!='Movil')

                echo"<tr><td colspan=2 align=center>
<table class=texttitular style='color:gray;'>
<tr>
<td>
".LANG_RESPOSABILIDADES_ORGANIZADOR."
</td>
</tr>

</table>
</td></tr>
";

            if ($op=='alta') $BotonFORM=Traductor::traducir("ALTA");
            else $BotonFORM=Traductor::traducir("MODIFICAR");
            if 	($_SESSION['S_Version']!='Movil')
            {
                echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP><p ID=ift> <input type='submit' value='$BotonFORM'></p></TD>");
            }else{
                echo ("<TR><TD ALIGN=CENTER VALIGN=TOP colspan='2'><input style=';' type='submit' value='$BotonFORM' class='superboton superboton-alta'>&nbsp;&nbsp;&nbsp;");
            }
            echo ("<input type=hidden name=menu value='$op"."ya"."'>");
            echo ("<input type=hidden name=id menu value='$id'>");
//$_SESSION['S_LIGA_ACTUAL']
            echo ("<input type=hidden name=id_Liga value='".$_SESSION['S_LIGA_ACTUAL']."'>");
//id_Jugador1 $_SESSION['S_usuario']
            echo ("<input type=hidden name=id_Jugador1 value='".$_SESSION['S_id_usuario']."'>");
            echo ("<input type=hidden name=id_Jugador2 value='$ant_id_Jugador2'>");
            echo ("<input type=hidden name=id_Jugador3 value='$ant_id_Jugador3'>");
            echo ("<input type=hidden name=id_Jugador4 value='$ant_id_Jugador4'>");
        }

        echo ("</form>");






        $Fecha_INI=devuelve_un_campo('liga',3,'id',$_SESSION['S_LIGA_ACTUAL']);
        $Fecha_FIN=devuelve_un_campo('liga',4,'id',$_SESSION['S_LIGA_ACTUAL']);
        $Fecha_INI=cambiaf_a_normal($Fecha_INI);
        $Fecha_FIN=cambiaf_a_normal($Fecha_FIN);
        $NivelJugAc=devuelve_un_campo('jugadores',36,'id',$_SESSION['S_id_usuario']);
        ?>
        <script type="text/javascript">
            function compruebaPista(){
                if(document.getElementById('sel').value=='OTRO'){
                    if(document.getElementById('textOculto2').value==''){
                        document.getElementById('errorotrapista').innerHTML='<?=Traductor::traducir("Debes indicar el nombre del campo para el partido")?>';
                        return false;
                    }
                    if(document.getElementById('Provincia').value=='000'){
                        document.getElementById('FORM_Provincia_errorloc').innerHTML='<?=Traductor::traducir("Debes seleccionar la provincia del lugar del partido")?>';
                        return false;
                    }
                    if(document.getElementById('Localidad').value==''){
                        document.getElementById('FORM_Localidad_errorloc').innerHTML='<?=Traductor::traducir("Debes seleccionar el municipio del lugar del partido")?>';
                        return false;
                    }
                    document.getElementById('errorotrapista').innerHTML='';
                    document.getElementById('FORM_Localidad_errorloc').innerHTML='';
                    document.getElementById('FORM_Provincia_errorloc').innerHTML='';

                    return true;
                }
                return true;
            }

        function compruebaFyN(){
                if(!document.getElementById('favoritos').checked){
                    document.getElementById('errorfavs').innerHTML='';
                }
                if(!document.getElementById('FiltroHCP').checked){

                    document.getElementById('errornivel').innerHTML='';
                }
                if(document.getElementById('favoritos').checked && document.getElementById('CANT').value<<?=MIN_FAV($_SESSION['S_LIGA_ACTUAL'])?>){
                    document.getElementById('errorfavs').innerHTML='<?=Traductor::traducir("Para partido de favoritos, deben poder apuntarse más de")?> <?=MIN_FAV($_SESSION['S_LIGA_ACTUAL'])?> <?=Traductor::traducir("jugadores")?>';
                    return false;
                }
                if(document.getElementById('FiltroHCP').checked && ((document.getElementById('nivel_max').value)<<?=floor(($NivelJugAc-1)/100+1)?> || (document.getElementById('nivel_min').value)><?=floor(($NivelJugAc-1)/100+1)?>)){
                    document.getElementById('errornivel').innerHTML='<?=Traductor::traducir("Tu nivel")?> (<?=floor(($NivelJugAc-1)/100+1)?>) <?=Traductor::traducir("debe estar incluido en el tramo seleccionado.")?>';
                    return false;
                }
                document.getElementById('errorfavs').innerHTML='';
                document.getElementById('errornivel').innerHTML='';
                return true;

            }
            var frmvalidator = new Validator('FORM');


            frmvalidator.addValidation('Fecha','req','<?=Traductor::traducir("La Fecha es obligatorio rellenarla")?>');
            frmvalidator.addValidation('Fecha','minlen=8','<?=Traductor::traducir("La Fecha debe responder al formato DD/MM/AAAA")?>');
            frmvalidator.addValidation('Fecha','maxlen=10','<?=Traductor::traducir("La Fecha debe responder al formato DD/MM/AAAA")?>');



            frmvalidator.addValidation('id_Campo','dontselect=selecc','<?=Traductor::traducir("Debes seleccionar un campo para el partido")?>');
            frmvalidator.addValidation('CANT','gt=9','<?=Traductor::traducir("No existen suficientes jugadores que cumplan los requisitos para apuntarse a este partido (mínimo 10)")?>');
            frmvalidator.setAddnlValidationFunction(compruebaFyN);
            frmvalidator.setAddnlValidationFunction(compruebaPista);

            if (document.all){
                //alert(\"Estas usando un navegador de Microsoft\")
                frmvalidator.EnableMsgsTogether();
            }else{
                //alert(\"No estas usando un navegador de Microsoft\")
                frmvalidator.EnableOnPageErrorDisplay();
                frmvalidator.EnableMsgsTogether();
            }
            /*frmvalidator.clearAllValidations();*/
        </script>
        <?



        echo("<form action='$PHP_SELF' method='get' style='display: inline;'>");

        ////BOTON
        if 	($_SESSION['S_Version']!='Movil')
            echo ("<TD ALIGN=LEFT VALIGN=TOP colspan=2><p ID=ift> <input type='submit'  value='".Traductor::traducir("CANCELAR")."'></p></TD>");
        else echo ("<input type='submit' class='superboton superboton-cancelar' value='".Traductor::traducir("CANCELAR")."'>");

        echo ("<input type=hidden name=menu value='listar'>");

        echo ("</form>");

        echo ("</TR></table><br></div>");
        }
        else
        {
            echo "<br><br><h2>".Traductor::traducir("No tienes permisos para ver este partido")."</h2>";
        }


    }; //////////////fin del formulario


        conectardb();

        $Hoy=date('Y-m-d');
        $Hoymenos2= date("Y-m-d", strtotime("$Hoy -2 days"));
        $Hoymenos3= date("Y-m-d", strtotime("$Hoy -3 days"));


        /*
    $os = array("misresultadospendientes", "misresultadosPARACORREGIR", "resultadosporfecha", "resultadosporjugador");
    if (in_array($menu, $os)) menu_jugador("2");
    else menu_jugador("0");
    */
        //echo"<table width='100%'><tr><td>";

        //if (($menu!='altaya')&&($menu!='modificarya')&&($menu!='borrar')) cabecera_html();

        $_SQL=$_SESSION['Tpartidos'];
        $_NOMBRECONSULTA=$_SESSION['Npartidos'];

        $Exportar="S";

        ?>
        <script type="text/javascript">

            window.addEventListener("scroll", function(event) {
                var top = this.scrollY;
                var left =this.scrollX;

                if (top > 50){
                    document.getElementById("navtabs").classList.add("navtabs_fixed");
                    document.getElementById("filtrocontainer2").classList.add("filtrocontainer2_fixed");
                    document.getElementById("listadoPartidos").style.marginTop = "90px";
                }
                else{
                    document.getElementById("navtabs").classList.remove("navtabs_fixed");
                    document.getElementById("filtrocontainer2").classList.remove("filtrocontainer2_fixed");
                    document.getElementById("listadoPartidos").style.marginTop = "0px";
                }


            }, false);
        </script>


        <?php

        if 	($_SESSION['S_Version']=='Movil'){
            if(!isset($_GET['pg'])){
                echo"<body onload=\"setTimeout(function() {window.scrollTo(0, 1)}, 120)\">";}}

        switch ($menu)

        {

            case 'misresultadospendientes':
                mini_cabecera_html();
                Mini_TITULO_CSS ('box1',Traductor::traducir("Mis resultados"),Traductor::traducir("Pendientes"),'','SI');//    TITULO_CSS_FIN ();
                $_SQL="SELECT * FROM partidos WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND Fecha<='$Hoy'" .
                    " AND Fecha_Result='0000-00-00'" .
                    " AND Fecha>='$Hoymenos2' " .
                    " AND id_Jugador2 !='0' " .
                    "and (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." )  ORDER BY Fecha asc";
                //echo $_SQL;
                $_NOMBRECONSULTA="Mis resultados pendientes de introducir";
                $_Subtitulo="<center>".Traductor::traducir("Para introducir el resultado haz click en el botón")." <img src='./images/images_j/resultado.png' width='30' border=1>";


                listar_partidos ($texto,0,$ARR,$pag,$order,$where,$Exportar);
                // TITULO_CSS_FIN ();
                break;


            case 'misresultadosPARACORREGIR': //Solo utilizado para corregir resultados
                /* mini_cabecera_html();
              Mini_TITULO_CSS ('box1',Traductor::traducir("Listado de "),Traductor::traducir("resultados editables"),'','SI');//    TITULO_CSS_FIN ();
             $_SQL="SELECT * FROM partidos WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND Fecha<='$Hoy'  AND Fecha>='$Hoymenos2' AND id_Jugador_ApuntaResult<=".$_SESSION['S_id_usuario']." ".
                     " and (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." " .
                             "OR id_Jugador4=".$_SESSION['S_id_usuario']." ) ORDER BY Fecha asc";
             $_NOMBRECONSULTA="Corregir resultados";
             $_Subtitulo="<center>".Traductor::traducir("Sólo se pueden corregir los resultados introducidos por ti, mediante el botón ")."<img src='./images/editar.gif' width='20' border=1>";

              listar_partidos ($texto,0,$ARR,$pag,$order,$where,$Exportar,'',1);*/

            //JM 18/09/2014 introducimos el corregir resultado para version movil.
                if 	($_SESSION['S_Version']!='Movil')
                {
                    Mini_TITULO_CSS ('box1',Traductor::traducir("Listado de "),Traductor::traducir("resultados editables"),'','SI');//    TITULO_CSS_FIN ();
                    mini_cabecera_html();
                    $_SQL="SELECT * FROM partidos WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND Fecha<='$Hoy'  AND Fecha>='$Hoymenos2' AND id_Jugador_ApuntaResult<=".$_SESSION['S_id_usuario']." ".
                        " and (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." " .
                        "OR id_Jugador4=".$_SESSION['S_id_usuario']." ) ORDER BY Fecha asc";
                    $_NOMBRECONSULTA="Corregir resultados";
                    $_Subtitulo="<center>".Traductor::traducir("Sólo se pueden corregir los resultados introducidos por ti, mediante el botón ")."<img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'width=\'20px\'')." src='./images/images_j/editar.png'  border=1>";
                    listar_partidos ($texto,0,$ARR,$pag,$order,$where,$Exportar,'',1);
                }else{
                    corregir_resultados_movil();
                }
                break;

            case 'misresultadosORIGINAL':
                mini_cabecera_html();
                $_SQL="SELECT * FROM partidos WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND Fecha<='$Hoy'" .
                    " and (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." " .
                    "OR id_Jugador4=".$_SESSION['S_id_usuario']." ) ORDER BY Fecha asc";
                $_NOMBRECONSULTA="MIS RESULTADOS";$_Subtitulo="<center>".Traductor::traducir("Los resultados que se pueden corregir tienen el botón ")."<img src='./images/images_j/editar.png' ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'width=\'30px\'')."' border=1>";

                listar_partidos ($texto,0,$ARR,$pag,$order,$where,$Exportar,'',1);
                break;
            case 'listar':
                $tiempo_inicio = microtime(true);
                mini_cabecera_html(0,'',true);
                Mini_TITULO_CSS ('box1',Traductor::traducir(""),Traductor::traducir("Partidos en curso"),'','SI');//    TITULO_CSS_FIN ();
                if (isset($hoja)) listar_partidos ($texto,$hoja,$ARR,$pag,$order,$where,$Exportar);
                else listar_partidos ($texto,0,$ARR,$pag,$order,$where,$Exportar);
                //TITULO_CSS_FIN ();
                $tiempo_fin = microtime(true);
                echo "<br>tmp2 " . round($tiempo_fin - $tiempo_inicio, 5);
                break;

            case 'alta':
                echo"ERROR 1-1";
                header("Location: partidos_funciones.php?menu=alta");
                break;

            case 'altaya':
                echo"ERROR 2-1";
                break;

            case 'modificar':
                header("Location: partidos_funciones.php?menu=modificar&id=$id");
                break;



            case 'modificarya':
                echo"ERROR 4-1";
                break;

            //iniciar_proceso_cancelacion AÑADIDO POR JORGE PARA LA CANCELACION
            case 'iniciar_proceso_cancelacion':
                //echo"ERROR 5-1";
                header("Location: partidos_funciones.php?menu=iniciar_proceso_cancelacion&id=$id&id_jugadores=$id_jugadores");
                break;

            case 'proceso_cancelacion':
                echo"ERROR 6-1";
                break;

            case 'aceptar_cancelacion':
                //echo"ERROR 7-1";
                header("Location: partidos_funciones.php?menu=aceptar_cancelacion&id=$id&datos=$datos");
                break;

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            case 'cancelar_proceso_cancelacion':
                //echo"ERROR 8-1";
                header("Location: partidos_funciones.php?menu=cancelar_proceso_cancelacion&id_partido=$id&id_jugadores=$id_jugadores");
                break;

            case 'confirmar':
                //echo"ERROR 9-1";
                header("Location: partidos_funciones.php?menu=confirmar&id=$id");
                break;


            case 'borrar':
                header("Location: partidos_funciones.php?menu=borrar&id=$id");
                break;

            case 'miagenda':


                $_NOMBRECONSULTA="Mi Agenda de Partidos";
                if(!isset($_GET['pg'])){
                    mini_cabecera_html();
                    Mini_TITULO_CSS ('box1',Traductor::traducir("Mi agenda de"),Traductor::traducir("Partidos"),'','SI');//    TITULO_CSS_FIN ();
                }

                if (isset($hoja)) listar_partidos ($texto,$hoja,$ARR,$pag,$order,$where,$Exportar,"1");
                else listar_partidos ($texto,0,$ARR,'on',$order,$where,$Exportar,"1");
                // TITULO_CSS_FIN ();
                break;

            case 'miagendaSUSTI':
                mini_cabecera_html();
                //opciones($registrado);
                Mini_TITULO_CSS ('box1',Traductor::traducir("Mi agenda de"),Traductor::traducir("Partidos"),'','SI');//    TITULO_CSS_FIN ();
                $_NOMBRECONSULTA="Mi Agenda de Partidos";
                $_Subtitulo="<center>".Traductor::traducir("Para solicitar un sustituto haz click en el botón")." <img src='./images/boton sustituto.png' ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'width=\'30px\'')." border=1> ".Traductor::traducir("del partido")."";

                if (isset($hoja)) listar_partidos ($texto,$hoja,$ARR,$pag,$order,$where,$Exportar,"1");
                else listar_partidos ($texto,0,$ARR,'on',$order,$where,$Exportar,"1");
                // TITULO_CSS_FIN ();
                break;


            case 'apuntarse':
                //	echo"ERROR 11-1";
                header("Location: partidos_funciones.php?menu=apuntarse&id=$id");
                break;



            case 'apuntarseya':
                $Jugador = new Jugador($_SESSION['S_id_usuario']);
                Log::v(__FUNCTION__, "Nivel Jugador: ".$Jugador->obtenerNivelInicial(), true);
                if($Jugador->esPerfilJugadorIncompleto()){
                    header("Location: jugadormodificadatoselmismo.php");
                    return;
                }
                //echo"ERROR 12-1";
                header("Location: partidos_funciones.php?menu=apuntarseya&id=$id&idReservaPista=$idReservaPista&precio=$precio&idTarjeta=$idTarjeta&tipoPagoJugadorReserva=$tipoPagoJugadorReserva&pagoAplazado=$pagoAplazado");
                break;
                //Cancelar_participacion ($id_partido,$Jugador)

            case 'cancelar_participacion':
                //echo"ERROR 13-1";
                header("Location: partidos_funciones.php?menu=cancelar_participacion&id=$id");
                break;

            case 'buscar_sustituto':
                //echo"ERROR 14-1";
                header("Location: partidos_funciones.php?menu=buscar_sustituto&id=$id");
                break;

            case 'sustituir':
                //	echo"ERROR 15-1";
                header("Location: partidos_funciones.php?menu=sustituir&datos=$datos&id=$id&idReservaPista=$idReservaPista&precio=$precio&idTarjeta=$idTarjeta&tipoPagoJugadorReserva=$tipoPagoJugadorReserva");
                break;

            case 'CancelarBuscarSustituto':
                //echo"ERROR 16-1";
                header("Location: partidos_funciones.php?menu=CancelarBuscarSustituto&datos=$datos");
                break;

            case 'resultado':
                //echo"ERROR 17-1";
                header("Location: partidos_funciones.php?menu=resultado&id=$id");
                break;

            case 'resultadosporfecha':
                mini_cabecera_html();
                Mini_TITULO_CSS ('box1',Traductor::traducir("Listado de "),Traductor::traducir(" Resultados por fecha"),'','SI');//    TITULO_CSS_FIN ();
                if 	($_SESSION['S_Version']!='Movil'){
                    include ("./calendario/calendario.php");
                    echo"<br>";
                    echo"<div align=center style='overflow-x: scroll;width: 590px;'>";

                    //CARGO EL ARRAY DE PARTIDOS
                    //$arraydias=array ('2011-03-23'=>'2','2011-03-25'=>'1');

                    $FechasCONresultados=array();

                    $Rest=mysql_query ("SELECT distinct(Fecha) as FECHA,count(Fecha) as RESULT FROM `partidosArchivados` WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND `id_Jugador_ApuntaResult`<>'0' GROUP BY FECHA ORDER BY FECHA asc");
                    while (list($S_Fecha,$S_Resultados) = mysql_fetch_array($Rest))
                    {
                        $FechasCONresultados[$S_Fecha]=$S_Resultados;
                    }
                    //print_r ($FechasCONresultados);

                    $Fecha1=devuelve_un_campo('liga',3,'id',$_SESSION['S_LIGA_ACTUAL']);
                    $Fecha2=devuelve_un_campo('liga',4,'id',$_SESSION['S_LIGA_ACTUAL']);
                    //echo $Fecha1,$Fecha1;
                    mostrar_calendario($FechasCONresultados,$Fecha1,$Fecha2);
                    echo"</div>";
                    echo"</tr></table><table><tr>";
                }else{

                    echo"
							<ul id='navtabs' style='text-align:center'>
							   <li><a style='width:32%' href='./partidos.php?menu=misresultadospendientes' title='".Traductor::traducir("Pendientes")."'>".Traductor::traducir("Pendientes")."</a></li>
							   <li><a style='width:32%' href='./jugadores.php?menu=resultados&id=".$_SESSION['S_id_usuario']."' title='".Traductor::traducir("Mis result.")."' >".Traductor::traducir("Mis result.")."</a></li>
							   <li><a style='width:32%' href='./jugadores.php?menu=ResultadosJugador' title='Agenda' id='current'>".Traductor::traducir("Res. jugador")."</a></li>

							</ul>
							<div id='contentwrap'>
							";
                    echo "<div id='filtrocontainer2' class='filtrocontainer2' style='text-align:center'>
                        <script>

                        function busca(){
                        var name=prompt(\"Buscar\",\"\");

                        if (name!=null && name!=\"\")
                        {
                        window.location.href=\"jugadores.php?texto=\"+name+\"&busq=1&menu=ResultadosJugador\";
                        }
                        }
                        </script>";

                    $fechavalue = 'Fecha';
                    if(isset($_GET['fecha'])){
                        $fechav = explode('-', $_GET['fecha']);
                        $fechavalue = $fechav[2].'/'.$fechav[1].'/'.$fechav[0];
                    }

                    $boton_buscarPorRecha =  '<input readonly class="fechac botonancho" style="display: inline-block;height: 18px;text-align: center; width: 100px; font-size: 12px; color: #1469cd; font-weight: bolder;" id="fecha" type="text" value="'.$fechavalue.'"/>';
                    Interfaz::mostrarTituloSeparador(Traductor::traducir("Resultados"),$boton_buscarPorRecha);


                    echo "<script>
                        function cargacalendario(){
                        $(\"input.fechac\").Zebra_DatePicker({
                        direction: false,

                        readonly_element:true,

                        onSelect: function(){
                        ftext = $(\".fechac\").val();
                        fecha = ftext.split(\"/\");

                        document.location=\"partidos.php?menu=resultadosporfecha&fecha=\"+fecha[2]+\"-\"+fecha[1]+\"-\"+fecha[0];
                        }

                        });

                        $(\".Zebra_DatePicker_Icon\").css({\"visibility\":\"hidden\"});
                        //$(\".Zebra_DatePicker_Icon\").css({\"\"});

                        }
                        cargacalendario();
                        </script>";

                    echo "</div>";

                }
                if ($fecha) {
                    //echo "$fecha";

                    //$_SQL="SELECT * FROM partidos WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND Fecha='$fecha' AND id_Jugador_ApuntaResult<>'0'";
                        //        $_NOMBRECONSULTA="<center>".Traductor::traducir("RESULTADOS del día ")."".cambiaf_a_normal($fecha)."</center>";
                        //$_Subtitulo="<center>Los resultados que se pueden corregir tienen el botón <img src='./images/editar.gif' width='30' border=1>";
                        //echo "$_SQL";

                        //        listar_partidos ($texto,0,$ARR,$pag,$order,$where,$Exportar,'','1');


                        //////////////////////////////////
                        //////////////////////////////////////////
                        ///////////////////////////////////////////

                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///Resultado concretos PARTIDOS
                    if 	($_SESSION['S_Version']!='Movil') echo "<table width='600' border =0><tr>";
                    else  echo "<div id='filtrocontainer2' class='filtrocontainer2' style='margin-top:0px'> <table width='100%' border =0><tr>";



                // echo ("<td width='13%' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("Fecha")."</td>");


                    $arrapar = array();
                    $q="SELECT P.id,P.Fecha,P.Hora,P.TipoPuntuacion,P.id_campo,P.Otro_Campo,P.id_Jugador1,P.Puntos_J1,P.id_Jugador2,P.Puntos_J2," .
                        "P.id_Jugador3,P.Puntos_J3,P.id_Jugador4,P.Puntos_J4,R.PUNTOS,R.id_jug2,R.id_jug3,R.id_jug4,R.FechaResult,R.HoraResult," .
                        "R.Extra1,R.Extra2,R.Extra3,P.id_Jugador_ApuntaResult
                        FROM  partidosArchivados AS P, resultados AS R
                        WHERE P.Fecha='".$fecha."' AND P.id_liga=".$_SESSION['S_LIGA_ACTUAL']." AND P.id=R.id_partido GROUP BY R.id_partido ORDER BY P.Hora asc";

                    $si=mysql_query($q);

                    while (list($id_partido,$P_Fecha,$P_Hora,$P_TipoPuntuacion,$P_id_campo,$P_Otro_Campo,$P_id_Jugador1,$P_Puntos_J1,$P_id_Jugador2,$P_Puntos_J2,$P_id_Jugador3,$P_Puntos_J3,$P_id_Jugador4,$P_Puntos_J4,$PUNTOS,$id_jug2,$id_jug3,$id_jug4,$FechaResult,$HoraResult,$Extra1,$Extra2,$Extra3,$QuienApunta) = mysql_fetch_array($si))
                    {
                        Log::v(__FUNCTION__, "ID Partido: $id_partido", false);
                        $Partido = new Partido($id_partido);

                        $fechaPartido = Fecha::fechaMYSQLANormal($Partido->obtenerFecha());
                        $horaPartido = $Partido->obtenerHora();
                        $nombreCampo = $Partido->obtenerCampo()->obtenerNombre();

                        $array_idsJugadoresParejaA = [];
                        if ($P_Puntos_J1 == "A"){
                            $array_idsJugadoresParejaA[] = $P_id_Jugador1;
                        }
                        if ($P_Puntos_J2 == "A"){
                            $array_idsJugadoresParejaA[] = $P_id_Jugador2;
                        }
                        if ($P_Puntos_J3 == "A"){
                            $array_idsJugadoresParejaA[] = $P_id_Jugador3;
                        }
                        if ($P_Puntos_J4 == "A"){
                            $array_idsJugadoresParejaA[] = $P_id_Jugador4;
                        }

                        $array_idsJugadoresParejaB = [];
                        if ($P_Puntos_J1 == "B"){
                            $array_idsJugadoresParejaB[] = $P_id_Jugador1;
                        }
                        if ($P_Puntos_J2 == "B"){
                            $array_idsJugadoresParejaB[] = $P_id_Jugador2;
                        }
                        if ($P_Puntos_J3 == "B"){
                            $array_idsJugadoresParejaB[] = $P_id_Jugador3;
                        }
                        if ($P_Puntos_J4 == "B"){
                            $array_idsJugadoresParejaB[] = $P_id_Jugador4;
                        }

                        $maximoCaracteresNombreJugador = 8;

                        $Jugador1 = new Jugador($array_idsJugadoresParejaA[0]);
                        $idJugador1 = $Jugador1->obtenerId();
                        $nombreCompletoJugador1 = $Jugador1->obtenerNombre();
                        $nombreJugador1 = Interfaz::resaltarTextoSiEsJugadorIgualQueJugador($Jugador1->obtenerNombreSinApellidos($maximoCaracteresNombreJugador), $Jugador1->obtenerId(), $id);
                        $urlFotoPerfilJugador1 = $Jugador1->obtenerUrlFotoPerfil();
                        $puntosJugador1 = $Partido->obtenerPuntosJugador($Jugador1->obtenerId());
                        $rankingJugador1 = $Partido->obtenerRankingJugador($Jugador1->obtenerId());
                        $iconoApuntaResultadoJugador1 = "";
                        if ($Partido->obtenerJugadorApuntaResultado()->obtenerId() == $Jugador1->obtenerId()){
                            $iconoApuntaResultadoJugador1 = "<img class='icono_apuntaResultado' src='images/lapiz.png' title='".Traductor::traducir("Este Jugador a Apuntado el Resultado del Partido")."'/>";
                        }

                        $Jugador2 = new Jugador($array_idsJugadoresParejaA[1]);
                        $idJugador2 = $Jugador2->obtenerId();
                        $nombreCompletoJugador2 = $Jugador2->obtenerNombre();
                        $nombreJugador2 = Interfaz::resaltarTextoSiEsJugadorIgualQueJugador($Jugador2->obtenerNombreSinApellidos($maximoCaracteresNombreJugador), $Jugador2->obtenerId(), $id);
                        $urlFotoPerfilJugador2 = $Jugador2->obtenerUrlFotoPerfil();
                        $puntosJugador2 = $Partido->obtenerPuntosJugador($Jugador2->obtenerId());
                        $rankingJugador2 = $Partido->obtenerRankingJugador($Jugador2->obtenerId());
                        $iconoApuntaResultadoJugador2 = "";
                        if ($Partido->obtenerJugadorApuntaResultado()->obtenerId() == $Jugador2->obtenerId()){
                            $iconoApuntaResultadoJugador2 = "<img class='icono_apuntaResultado' src='images/lapiz.png' title='".Traductor::traducir("Este Jugador a Apuntado el Resultado del Partido")."'/>";
                        }


                        $Jugador3 = new Jugador($array_idsJugadoresParejaB[0]);
                        $idJugador3 = $Jugador3->obtenerId();
                        $nombreCompletoJugador3 = $Jugador3->obtenerNombre();
                        $nombreJugador3 = Interfaz::resaltarTextoSiEsJugadorIgualQueJugador($Jugador3->obtenerNombreSinApellidos($maximoCaracteresNombreJugador), $Jugador3->obtenerId(), $id);
                        $urlFotoPerfilJugador3 = $Jugador3->obtenerUrlFotoPerfil();
                        $puntosJugador3 = $Partido->obtenerPuntosJugador($Jugador3->obtenerId());
                        $rankingJugador3 = $Partido->obtenerRankingJugador($Jugador3->obtenerId());
                        $iconoApuntaResultadoJugador3 = "";
                        if ($Partido->obtenerJugadorApuntaResultado()->obtenerId() == $Jugador3->obtenerId()){
                            $iconoApuntaResultadoJugador3 = "<img class='icono_apuntaResultado' src='images/lapiz.png' title='".Traductor::traducir("Este Jugador a Apuntado el Resultado del Partido")."'/>";
                        }

                        $Jugador4 = new Jugador($array_idsJugadoresParejaB[1]);
                        $idJugador4 = $Jugador4->obtenerId();
                        $nombreCompletoJugador4 = $Jugador4->obtenerNombre();
                        $nombreJugador4 = Interfaz::resaltarTextoSiEsJugadorIgualQueJugador($Jugador4->obtenerNombreSinApellidos($maximoCaracteresNombreJugador), $Jugador4->obtenerId(), $id);
                        $urlFotoPerfilJugador4 = $Jugador4->obtenerUrlFotoPerfil();
                        $puntosJugador4 = $Partido->obtenerPuntosJugador($Jugador4->obtenerId());
                        $rankingJugador4 = $Partido->obtenerRankingJugador($Jugador4->obtenerId());
                        $iconoApuntaResultadoJugador4 = "";
                        if ($Partido->obtenerJugadorApuntaResultado()->obtenerId() == $Jugador4->obtenerId()){
                            $iconoApuntaResultadoJugador4 = "<img class='icono_apuntaResultado' src='images/lapiz.png' title='".Traductor::traducir("Este Jugador a Apuntado el Resultado del Partido")."'/>";
                        }

                        $icono_partidoAdmistoso = "";
                        if($Partido->esPartidoAmistoso()){
                            $icono_partidoAdmistoso = "<div class='icono_partidoAmistoso'><img  src='images/Amistoso_sin_ranking.png' title='".Traductor::traducir("Partido Amistoso")."'/></div>";
                        }

                        echo"
                        <div class='cont_partidos_jugadores'>
                            <div class='cont_fechaloc_jugadores'>
                                <div class='fecha_jugadores'>
                                    $fechaPartido <span class='hora_jugadores'>$horaPartido</span>
                                </div>
                                <div class='localizacion_jugadores'>
                                    $nombreCampo
                                </div>
                            </div>
                            <!--Contenedor jugadores-->
                            <div class='cont_jugadores_list'>
                            
                                <div class='cont_jugadores_list_izq'>
                                    <div class='cont_jugador_face'>
                                        <div class='jugador_face'>
                                            <a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$idJugador1&boxy=1', {title:  '$nombreCompletoJugador1'});\"><img src='$urlFotoPerfilJugador1'/>$iconoApuntaResultadoJugador1</a>
                                        </div>
                                        <div class='jugador_name'>
                                            <a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$idJugador1&boxy=1', {title:  '$nombreCompletoJugador1'});\">$nombreJugador1</a>
                                        </div>
                                        <div class='".obtenerColorRakingJugador($rankingJugador1)."'>
                                            $rankingJugador1
                                        </div>
                                    </div>
                                    <div class='cont_jugador_face'>
                                        <div class='jugador_face'>
                                            <a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$idJugador2&boxy=1', {title:  '$nombreCompletoJugador2'});\"><img src='$urlFotoPerfilJugador2'/>$iconoApuntaResultadoJugador2</a>
                                        </div>
                                        <div class='jugador_name'>
                                            <a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$idJugador2&boxy=1', {title:  '$nombreCompletoJugador2'});\">$nombreJugador2</a>
                                        </div>
                                        <div class='".obtenerColorRakingJugador($rankingJugador2)."'>
                                            $rankingJugador2
                                        </div>
                                    </div>
                                    <div class='cont_jugador_puntuacion' style='text-align: right'>
                                        $puntosJugador1
                                    </div>
                                </div>
                                <div style='border: solid 1px gray; height: 60px'></div>		
                                <div class='cont_jugadores_list_der'>
                                    <div class='cont_jugador_puntuacion' style='text-align: left;'>
                                        $puntosJugador3
                                    </div>
                                    <div class='cont_jugador_face'>
                                        <div class='jugador_face'>
                                            <a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$idJugador3&boxy=1', {title:  '$nombreCompletoJugador3'});\"><img src='$urlFotoPerfilJugador3'/>$iconoApuntaResultadoJugador3</a>
                                        </div>
                                        <div class='jugador_name'>
                                            <a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$idJugador3&boxy=1', {title:  '$nombreCompletoJugador3'});\">$nombreJugador3</a>
                                        </div>
                                        <div class='".obtenerColorRakingJugador($rankingJugador3)."'>
                                            $rankingJugador3
                                        </div>
                                    </div>
                                    <div class='cont_jugador_face'>
                                        <div class='jugador_face'>
                                            <a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$idJugador4&boxy=1', {title:  '$nombreCompletoJugador4'});\"><img src='$urlFotoPerfilJugador4'/>$iconoApuntaResultadoJugador4</a>
                                        </div>
                                        <div class='jugador_name'>
                                            <a href=\"javascript:Boxy.load('jugadores.php?menu=minimodificar&id=$idJugador4&boxy=1', {title:  '$nombreCompletoJugador4'});\">$nombreJugador4</a>
                                        </div>
                                        <div class='".obtenerColorRakingJugador($rankingJugador4)."'>
                                            $rankingJugador4
                                        </div>
                                    </div>
                                </div>
                            </div>
                            $icono_partidoAdmistoso
                        </div>
                        ";
                    }
                    echo "</table>";


                    if 	($_SESSION['S_Version']=='Movil'){
                        echo '</div>';

                        PieMovil (1);

                    }
                    //////////////////////////////////
                    ////////////////////////////////////
                    /////////////////////////////
                }
                else
                {echo "<br><center>".Traductor::traducir("Selecciona la fecha para ver los resultados")."</center>";}

                TITULO_CSS_FIN ();
                break;

            default:
                $tiempo_inicio = microtime(true);

                if(!isset($_GET['pg'])){
                    mini_cabecera_html(0,'',true);
                    //opciones($registrado);
                    Mini_TITULO_CSS ('box1',Traductor::traducir(""),Traductor::traducir("Partidos en curso"),'','SI');//    TITULO_CSS_FIN ();
                }

                if (isset($hoja)) listar_partidos ($texto,$hoja,$ARR,$pag,$order,$where,$Exportar);
                else listar_partidos ($texto,0,$ARR,'on',$order,$where,$Exportar);
                if(!isset($_GET['pg'])){
                    // TITULO_CSS_FIN ();

                    $tiempo_fin = microtime(true);

                    //echo "<br>Tiempo " . round($tiempo_fin - $tiempo_inicio, 5);
                }
                break;
        };//fin switch

        //$CUANTOS=Tiene_Partido ('2011-07-21',$_SESSION['S_id_usuario']);
        //echo "<script>alert('El jugador ".$_SESSION['S_id_usuario']." tiene $CUANTOS PARTIDOS el día 2011-07-21')</script>";
        if(!isset($_GET['pg'])){
            echo"</td></tr></table>";
            pie_html();
        }

        function mostrarTodosMensajesEventos($desdeFecha=""){

            if (empty($desdeFecha)){
                $Hoy=date('Y-m-d');
            }
            else{
                $Hoy = $desdeFecha;
            }

            $Hora = "";
            $manana=date("Y-m-d",strtotime("$Hoy + 1 days"));
            $FechaAnterior =$Hoy;
            $Fecha = $Hoy;


            for ($i=1; $i <=30; $i++){


                $topSticky_listadoPartidos = "80px";
                if ($_GET['menu']=='miagenda'){
                    $topSticky_listadoPartidos = "38px";
                }

                if ($FechaAnterior!=$Fecha)
                {


                    //gestion_eventos_intercalados($MiAgenda,$FechaAnterior,$Fecha,$hora_anterior,$Hora,true); //Pasamos true para ultima fecha

                    ///////////////////////////ff/////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////// ADAPTACION MOVIL
                    ////////////////////////////////////////////////////////////////////////////////////////////////////




                    if 	($_SESSION['S_Version']!='Movil') {$ANT1="<tr><td colspan=13><br>"; $FIN1="</td></tr>";}
                    else {$ANT1="<div id='listadoPartidos' style='margin-top:0px; top:".$topSticky_listadoPartidos."'><div class='post'> <div class='content' style=' '><div class='category cabecera'>";$FIN1="</div></div></div></div>";}



                    ////////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////////
                    if 	($_SESSION['S_Version']!='Movil'){
                        $color1 = 'white';
                        $color2 = 'white';
                    }else{
                        $color1 = 'white';
                        $color2 = 'white';
                    }

                    $div_verde='';
                    $fin_div_verde='';
                    if 	($_SESSION['S_Version']!='Movil'){
                        $div_verde='<div style="color: rgb(255, 255, 255); background: none repeat scroll 0% 0% rgb(95, 179, 58); margin-top: 10px; text-align: left; padding: 3px 3px 3px 16px;">';
                        $fin_div_verde='</div>';
                    }

                    /////GESTIONAMOS LOS SALTOS ENTRE DIAS PARA MOSTRAR EL EVENTO ENTRE MEDIO AUNQUE NO HAYA PARTIDOS
                    $FechaAnteriorMasUno=strtotime($FechaAnterior. ' +1 day');

                    $cont=0;
                    while($FechaAnteriorMasUno<strtotime($Fecha) and $cont<=200 and $FechaAnterior!='')
                    {

                        $FechaAnteriorMasUno=date('Y-m-d',$FechaAnteriorMasUno);

                        //echo $FechaAnteriorMasUno;
                        if(hay_evento($FechaAnteriorMasUno))
                        {
                            echo"$ANT1 $div_verde<b>".dia_semana($FechaAnteriorMasUno)." ".cambiaf_a_normal($FechaAnteriorMasUno)."</b>$fin_div_verde $FIN1";
                            echo gestion_eventos_intercalados(true,$Fecha,$FechaAnteriorMasUno,$hora_anterior,"",false); //Para antes del primer evento que salga despues de esta cabecera

                        }

                        //echo "Salto Fecha $FechaAnteriorMasUno $Fecha<br>";


                        $FechaAnteriorMasUno=strtotime($FechaAnteriorMasUno. ' +1 day');
                        $cont++;

                    }
                    ///////////////////////////////////////////////////


                    if(hay_evento($Fecha)){
                        if (strtotime($Fecha)==strtotime($Hoy))
                        {
                            echo"$ANT1 $div_verde<b><font color=$color1>".Traductor::traducir("HOY")." ".dia_semana($Fecha)." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
                            //cabecera ();
                        }
                        else if (strtotime($Fecha)==$manana)
                        {
                            echo"$ANT1 $div_verde<b><font color=$color2>".Traductor::traducir("MAÑANA")." ".dia_semana($Fecha)." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
                            //cabecera ();
                        }
                        else
                        {
                            echo"$ANT1 $div_verde<b>".dia_semana($Fecha)." ".cambiaf_a_normal($Fecha)."</b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
                            //cabecera ();
                        }
                    }



                    gestion_eventos_intercalados(true,$FechaAnterior,$Fecha,$hora_anterior,$Hora,false); //Para antes del primer evento que salga despues de esta cabecera

                }else{


                    if(hay_evento($Fecha)){
                        $ANT1="<div id='listadoPartidos' style='margin-top:0px; top:".$topSticky_listadoPartidos."'><div class='post'> <div class='content' style=' '><div class='category cabecera'>";$FIN1="</div></div></div></div>";

                        echo"$ANT1 $div_verde<b><font color=$color1>".Traductor::traducir("HOY")." ".dia_semana($Fecha)." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
                        gestion_eventos_intercalados(true,$FechaAnterior,$Fecha,$hora_anterior,$Hora,false);
                    }

                }



                $FechaAnterior = $Fecha;
                $Fecha = date("Y-m-d",strtotime("$Fecha + $i days"));
                //$i++;

            }
        }

        function obtenerColorRakingJugador($ranking){

            if ($ranking > 0){
                return "jugador_puntuacion_rojo";
            }
            else if ($ranking == 0){
                return "jugador_puntuacion_naranja";
            }
            else{
                return "jugador_puntuacion_verde";
            }
        }
        $tiempoFinal = microtime(true);
        $tiempoEjecucion = $tiempoFinal - $tiempoInicial;
        Log::v(__FUNCTION__, "Tiempo ejecución: $tiempoEjecucion", true);
?>
