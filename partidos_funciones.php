<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR);

    include ("ligagolf_PCU.php");
    include ("funciones.php");

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
    if 	($_SESSION['S_Version']=='Movil'){
                echo "<div class='caja-texto'>";
            }

    nivel_min, nivel_max, favoritos, invitacion
    */

    ////////////////////////////////////////////////


    //Funcion que gestiona la correcion de resultados para version movil, llamada desde misresultadosPARACORREGIR.
    function corregir_resultados_movil()
    {
        mini_cabecera_html();
		Mini_TITULO_CSS ('box1','Corregir','Resultados','','SI');//    TITULO_CSS_FIN ();
        
        ///Resultado concretos PARTIDOS
        echo "<ul  id='navtabs' style='text-align:center'>
                <li><a style='width:32%' href='./partidos.php?menu=misresultadosPARACORREGIR' title='Corregir resultados'  id='current'>Corregir</a></li>
				<li><a style='width:32%' href='./jugadores.php?menu=resultados&id=".$_SESSION['S_id_usuario']."' title='Mis resultados'>".Traductor::traducir("Mis result.")."</a></li>
				<li><a style='width:32%' href='./jugadores.php?menu=ResultadosJugador' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Res. Jugador")."</a></li>
            </ul>";


        echo ("<div class='filtrocontainer2' style='margin-top:0px'><center>Los resultados pueden corregirse en las 24 horas posteriores a su celebraci&oacute;n s&oacute;lo por el jugador que haya grabado ese resultado</center></div>");

							
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
            else {echo ("<tr bgcolor='#FFFFFF'>");$clase='fila2';$colortoca=1;};

            //if (1!='') {echo ("<td class=$clase align=center><p class='dato'><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p></td>");} else {echo ("<td class=$clase> <br> </td>");};

            //Datos del Partido
            echo ("<td class=$clase align=center>");

            if ($P_id_campo) echo("<p class='dato' align=center>".devuelve_un_campo ("campos",2,"id",$P_id_campo)."<br>$P_TipoPuntuacion<br><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p>");
            else echo("<p class='dato' align=center>$P_Otro_Campo<br>$P_TipoPuntuacion<br><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p>");

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
//Devuelve verdadero o falso si el jugador de la sesiï¿œn puede apuntarse al partido,
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

//Devuelve verdadero o falso si el jugador de la sesiï¿œn puede apuntarse al partido,
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
         return true; //Si el sexo de los dos primeros jugadores es distinto, el jugador 3 puede apuntarse y dependerï¿œ sï¿œlo del cuarto.

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
    global $bd;

    $query_mixto="INSERT INTO partidos_mixtos VALUES('$id_partido')";

    $bd->query($query_mixto);
}

function agrega_registro_parejas($id_creador,$id_invitado,$id_partido,$confirmado='N')
{
    global $bd;

         if($id_creador=='' or $id_invitado=='')
            die("Error partidos-288: No han sido especificados o el creador o el invitado, avise a soporte");

         //$id_partido=$LastID;
         $queryParejas="INSERT INTO partidos_pendientes_pareja(id_jugador_creador,id_partido,id_jugador_invitado,confirmado) VALUES($id_creador,$id_partido,$id_invitado,'$confirmado')";
            $bd->query($queryParejas);

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
           $mensaje=Traductor::traducir("Estas invitado a jugar como pareja de ").$creador.Traductor::traducir(" en un partido en ").$campo.Traductor::traducir("el").$fecha.Traductor::traducir("a las").$hora.Traductor::traducir("Recuerda que ningún jugador verá este partido hasta que no te apuntes antes de 24 horas a partir de la recepcion de este mensaje...").$creador.Traductor::traducir(" para que lo anule o modifique) ");
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
           $bd->query($queryMensaje);
         }

}
//////////////fin FUNCIONES PARA LA GESTION DE SEXOS EN PARTIDOS DE SEXO DEFINIDO Y MIXTOS////////////////////////////////







///////////////////////////////////////////AVISOS PARTIDOS ABIERTOS///////////////////////////////////////////////////////          //Seleccion de sustituto, con el id del partido y del jugador en caso de true.
///////////////////////////////////////////AVISOS PARTIDOS ABIERTOS///////////////////////////////////////////////////////          //Seleccion de sustituto, con el id del partido y del jugador en caso de true.

//OJO: Esta funcion esta en gestion_aviso_partido_abierto.php repetida para el tema de JQuery de avisos abiertos
function gestion_aviso_partido_abierto($id_partido_abierto, $id_Jugador1, $id_Jugador2, $parejas,$id_Liga,$nivel_min,$nivel_max,$Fecha, $Hora,$favoritos,$para_sustituir=false,$id_del_partido='',$id_Jugador_busca_sustituto='')
{
  //NUEVO 29/06/14: Aviso de nuevo partido abierto////////////
  //echo "Inicio: ".time()."<br>";

  include("classes/general_class.php");
  include("classes/database_class.php");
  include("classes/jugador_class.php");
  include("classes/avisos_class.php");

  //JMAM: Indica si puede enviar PUSH
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

  $query_jugadores_posibles="SELECT id FROM jugadores
		  WHERE Estado='ACTIVO' AND id IN (SELECT id_Jugador FROM juegalaliga WHERE id_Liga=$id_Liga and estado='ACTIVO')
		  $query_nivel $query_favoritos $query_excluye_propio_jugador";

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
        $titulo_push=Traductor::traducir("Busco sustituto. Apúntate");
        $fecha_p=devuelve_un_campo('partidos',2,'id',$id_del_partido);
        $hora_p=devuelve_un_campo('partidos',3,'id',$id_del_partido);
        $nivel_min_p=devuelve_un_campo('partidos',18,'id',$id_del_partido);
        $nivel_max_p=devuelve_un_campo('partidos',19,'id',$id_del_partido);

  }else{
        $titulo_push=Traductor::traducir("Nuevo partido. Apúntate");
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

     //estos valores por defecto los pongo intercambiados (realmente min serï¿œa 18 y max 1), pero como los desplegables se llaman al contrario
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
         }else //No hay campo, es que hay un "otros" en el campo, obtengo la provincia a travï¿œs del municipio
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
         }else //No hay campo, es que hay un "otros" en el campo, obtengo la provincia a travï¿œs del municipio
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
		$jugadores_a_avisar[]=$id; //Si pasa todos los filtros sin ponerse false, aï¿œade al array de los que hay que avisar.
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


function alta_partidos ($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Extra3,$parejas='N',$id_creador='',$id_invitado='',$es_mixto='N',$es_amistoso='N',$Localidad, $reservarPista="", $idPista, $idTiempoReserva, $partidoCompleto, $numeroJugadores, $repartirImporte, $importeReserva, $aplazarPago, $tipoPagoJugadorReserva, $numeroPedido, $idTarjeta)
{
    global $bd;
    //echo "ID PISTA: $idPista";
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//JMAM: Comprueba que se ha indicado el Campo Hora
if ($Hora == ""){
    echo Traductor::traducir("Vuelve a intentarlo. Si el problema persiste, contacta con nosotros a organizacion@radicalpadel.com");
    return "error";
}

//Nuevo jm 13-jul-15: Si hay una liga con el nombre "open de verano" en estado activo, se pone a true esta variable para activar
//mas abajo la opcion de fecha de fin de liga y apertura d partidos.
$aData=mysql_fetch_array(mysql_query("select count(*) as res from liga where Nombre like '%Open de Verano%' and Estado='ACTIVA'"));
$hay_open_abierto=$aData[0];

//die("hay open abierto es $hay_open_abierto");

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



     $headers =  'MIME-Version: 1.0' . "\r\n";
     $headers .= 'From: Radical Padel <organizacion@radicalpadel.com>' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
     mail($mail_campo,Traductor::traducir("email_alta_nuevo_partido_para_club_titulo"), $email_texto, $headers);

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
$Extra3=$Localidad;

}

	$CUANTOS_PARTIDOS=Tiene_Partido_Sin_Resultado ($Fecha,$_SESSION['S_id_usuario'],$Hora);
 if ($CUANTOS_PARTIDOS>=1)
   {
 	$Mensaje='ERROR\\n\\n'.Traductor::traducir("Ya tienes ").' '.$CUANTOS_PARTIDOS.' '.Traductor::traducir("partido el día").' '.cambiaf_a_normal($Fecha).' \\n'.Traductor::traducir("No puedes crear otro partido ese mismo día con una diferencia menor a 1.30 horas.");
 	 echo "<script>alert('$Mensaje');</script>";
 	 return (0);
   }
 else if($Fecha==$fechahoy && $hora_para_crear_partido<=$ahora/*$horas[0]<=date("H")*/)
   {
	$Mensaje='ERROR\\n\\n'.Traductor::traducir("No puedes crear un partido para hoy a una hora anterior a la actual");
 	 echo "<script>alert('$Mensaje');</script>";
 	 return (0);


 }
//DESCOMENTAR PARA EL OPEN
//Nuevo jm 13-ju-15: metemos aqui el control de open abierto, y no hay que volver a comentar/descomentar cada temporada.
 else if($Fecha>$fechafinliga and $hay_open_abierto)
{


 	$Mensaje='ERROR\\n\\n'.Traductor::traducir("Para crear un partido con fecha posterior al fin del Open").' ('.cambiaf_a_normal($fechafinliga).')\\n'.Traductor::traducir("debes hacerlo desde la liga de tu zona");
 	 echo "<script>alert('$Mensaje');</script>";
 	 return (0);
  }
/////////////////////////////////////////

 else{
        ///Comprobamos este error antes de insertar ningï¿œn registro
       if($parejas=='S')   //Nuevo, si es partido por parejas agrega el registro correspondiente.
         if($id_creador=='' or $id_invitado=='')
         {
           echo "<script>alert('".ERROR_DEBE_ELEGIR_PAREJA."');</script>";
     	   return (0);
         }

        //JMAM: Comprueba si existe ya una reserva
        if ($reservarPista == 1){
            $horaFinReserva = (new TiempoReserva($idTiempoReserva))->obtenerHoraFinTiempoReserva($Hora);
            if (CacheTablaReserva::esPistaReservadaEnTramo($idPista, $Fecha, $Hora, $horaFinReserva)){
                mini_cabecera_html(0,'',true);
  				Mini_TITULO_CSS ('box1',Traductor::traducir("Crear un"),Traductor::traducir("Partido"),'','SI');//
  				echo "<br/>";
                echo ('<center>'.Traductor::traducir("¡Lo sentimos!, otro usuario acaba de ocupar el lugar de tu Reserva").'.<br><br><a class="superboton boton_principal" target="_parent" href="'.WWWBASE.'partidos_funciones.php?menu=alta">'.Traductor::traducir("Volver a Intentarlo").'</a></center>');
                die();
            }
        }

     //$query = "INSERT INTO partidos VALUES ('$id','$id_Liga','$Fecha','$Hora','$TipoPuntuacion','$id_Campo','$Otro_Campo','$id_Jugador1','$id_Jugador2','$id_Jugador3','$id_Jugador4','$Puntos_J1','$Puntos_J2','$Puntos_J3','$Puntos_J4','$id_Jugador_ApuntaResult','$Fecha_Result','$Observaciones','$nivel_min','$nivel_max','$favoritos','$invitacion','$Extra1','$Extra2','$Extra3','0')";

        $Partido = new Partido();
        $Partido[Partido::COLUMNA_idLiga] = $id_Liga;
        $Partido[Partido::COLUMNA_fecha] = $Fecha;
        $Partido[Partido::COLUMNA_hora] = $Hora;
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

        $Partido = new Partido($LastID);
        $Partido->actualizarPartidosApuntadoParaLosJugadoresDelPartido();


	 global $id_partido_creado;
	 $id_partido_creado=$LastID;//Para aviso partidos abiertos por JQ


        $PartidoJugador = new PartidoJugador();
        $PartidoJugador[PartidoJugador::COLUMNA_idPartido] = $LastID;
        $PartidoJugador[PartidoJugador::COLUMNA_idJugador] = $id_Jugador1;
        $PartidoJugador[PartidoJugador::COLUMNA_numeroJugador] = 1;
        $PartidoJugador[PartidoJugador::COLUMNA_tipoJugador] = PartidoJugador::TIPOJUGADOR_interno;
        $PartidoJugador->guardar();



	 //JMAM: INICIO Realizar Reserva Pista (si procede) ////////////////////////////////////////////////////////////////



     if ($reservarPista == 1){

         $idReservaPista = ReservaPista::realizarReservaPista(
                                $idPartido = $id_partido_creado,
                                $idPista = $idPista,
                                $horaInicio = $Hora,
                                $Fecha,
                                $idTiempoReserva = $idTiempoReserva,
                                $idJugadorReserva = $id_Jugador1,
                                $numeroJugadores = $numeroJugadores,
                                $repartirImporte = $repartirImporte,
                                $importeReserva = $importeReserva,
                                $aplazarPago,
                                $tipoPagoJugadorReserva = $tipoPagoJugadorReserva,
                                $partidoCompleto = $partidoCompleto,
                                $reservaRealizadaPorClub = 0,
                                $numeroPedido = $numeroPedido

                        );




         $arrayJugadoresAdministradores = Juegalaliga::obtenerJugadoresAdministradores($id_Liga);
         foreach ($arrayJugadoresAdministradores as $JugadorAdministrador){

             $idJugador = $JugadorAdministrador->obtenerId();

             $Pista = new Pista($idPista);
             $nombrePista = $Pista->obtenerNombre();

             $array_hora = explode(":",$Hora);
             $hora = $array_hora[0];
             $minutos = $array_hora[1];

             $horaReserva = "$hora:$minutos";

             $dia = getDiaEvento($Fecha, false);
             $fechaNormal = cambiaf_a_normal2($Fecha);

             $JugadorReserva = new Jugador($id_Jugador1);
             $nombreJugador = $JugadorReserva->obtenerNombre(true);
             $telefonoJugador = $JugadorReserva->obtenerTelefono(true);


             $mensaje = "$nombreJugador (tlf $telefonoJugador) ".Traductor::traducir("ha reservado", false, $JugadorAdministrador->obtenerIdioma())." $nombrePista ".Traductor::traducir($dia,false, $JugadorAdministrador->obtenerIdioma())." $fechaNormal ".Traductor::traducir("a las", false, $JugadorAdministrador->obtenerIdioma())." ".$horaReserva."h";

             Notificacion::enviarNotificacion($idJugador, Traductor::traducir("NUEVA RESERVA", false, $JugadorAdministrador->obtenerIdioma()), $mensaje, Notificacion::TIPO_NOTIFICACION_verReservasAdmin, $id_Liga, $idReservaPista, Notificacion::ICONO_alarma);
         }


         /*
         $ReservaPista = new ReservaPista($idReservaPista);
         if (!$ReservaPista->comprobarIntegridadReserva(false, false, false)){
             //echo Traductor::traducir("Se ha producido un error en el tratamiento de los datos de la reserva, ponte en contacto con administración si el error persiste");

             $asunto = "Reserva Incorrecta: PROGRAMA - NUEVA RESERVA";
             $ReservaPista->comprobarIntegridadReserva(true, false, true, $asunto);

             return "ERROR_AL_COMPROBAR_INTEGRIDAD_RESERVA";
         }*/
     }
     else{
         if ($Partido->obtenerCampo()->obtenerConfiguracionReservaPistas()->esMostrarPartidosEnTablaReservas()){
             $Partido->generarReservaPistaParaMostrarPartidoEnTablaReservas();
         }

     }

	 //JMAM: FIN Realizar Reserva Pista (si procede) ////////////////////////////////////////////////////////////////





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


        Juegalaliga::actualizarNumeroPartidosApuntadoJugadorEnLiga($id_Jugador1, $id_Liga);
        Juegalaliga::actualizarNumeroPartidosJugadosJugadorEnLiga($id_Jugador1, $id_Liga);



	  $sqlr = mysql_query("SELECT * FROM jugadores WHERE id=".$id_Jugador1);
$dtsr = mysql_fetch_array($sqlr);
if($dtsr['tiporecordatorio']>0){
$idp = $LastID;
$tiempor = calculaTiempo($idp, $dtsr['tiporecordatorio'], ''.$dtsr['horarecordatorio'].'');
$bd->query("INSERT INTO recordatorios (id_jugador, id_partido, id_liga, tiempo, estado) VALUES (".$id_Jugador1.",".$idp.",".$id_Liga.",".$tiempor.",0)");
$tiempor = calculaTiempo($idp, $dtsr['tiporecordatorio'], ''.$dtsr['horarecordatorio'].'', true);
return $tiempor;
}else{
//if($_SESSION['S_LIGA_ACTUAL']==27){

return '1';
//}else{
  //   return ($OK);
	// }
 }}
};




////////////////////////////////////////////////
function baja_partidos ($id)
{


        //JMAM: Elimina la Reserva del Partido, si existiera
          $Partido = new Partido($id);
          if($Partido->esReservaPistaPartido()){
              //JMAM: Guarda el Partido que se va a eliminar
              $Partido->guardar(null, PartidoEliminado::TABLA_nombre);

              $ReservaPista = $Partido->obtenerReservaPistaPartido();
              $idReservaPista = $ReservaPista->obtenerId();

              //JMAM: Devuelve el Pago a Todos Los Jugadores
              $ReservaPista->devolverPagoATodosLosJugadores();


              //JMAM: INICIO Notificación a Administradores ////////////////////////////////////////////////////////////
              $arrayJugadoresAdministradores = Juegalaliga::obtenerJugadoresAdministradores($_SESSION['S_LIGA_ACTUAL']);
             foreach ($arrayJugadoresAdministradores as $JugadorAdministrador){

                 $idJugador = $JugadorAdministrador->obtenerId();

                 $Pista = new Pista($ReservaPista->obtenerIdPista());
                 $nombrePista = $Pista->obtenerNombre();

                 $horaReserva = $ReservaPista->obtenerHoraInicioReserva(true);
                 $fechaReserva = $ReservaPista->obtenerFechaReserva();

                 $dia = getDiaEvento($fechaReserva, false);
                 $fechaNormal = cambiaf_a_normal2($fechaReserva);

                 $JugadorReserva = $ReservaPista->obtenerJugador1();
                 $nombreJugador = $JugadorReserva->obtenerNombre(true);
                 $telefonoJugador = $JugadorReserva->obtenerTelefono(true);

                 $mensaje = "$nombreJugador (tlf $telefonoJugador) ".Traductor::traducir("ha cancelado", false, $JugadorAdministrador->obtenerIdioma())." $nombrePista ".Traductor::traducir($dia,false, $JugadorAdministrador->obtenerIdioma())." $fechaNormal ".Traductor::traducir("a las", false, $JugadorAdministrador->obtenerIdioma())." ".$horaReserva."h";

                 Notificacion::enviarNotificacion($idJugador, Traductor::traducir("RESERVA CANCELADA", false, $JugadorAdministrador->obtenerIdioma()), $mensaje, Notificacion::TIPO_NOTIFICACION_verReservasAdmin, $_SESSION['S_LIGA_ACTUAL'], $idReservaPista, Notificacion::ICONO_rojo);
             }
             //JMAM: FIN Notificación a Administradores //////////////////////////////////////////////7777//////////////


              //JMAM: Guarda la Reserva a borrar en la tabla borrados y la elimina
              $ReservaPista->guardar(null, ReservaPistaEliminado::TABLA_nombre);
              $ReservaPista->eliminar();
          }

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
    $Partido->actualizarPartidosApuntadoParaLosJugadoresDelPartido();
    $Partido->eliminarTodosLosJugadoresDelPartido();
	Registrar_Actividad ('8', $id);

};


////////////////////////////////////////////////

function modifica_partidos ($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Extra3,$parejas='N',$id_creador='',$id_invitado='',$es_mixto='N',$es_amistoso='N',$Localidad, $reservarPista="", $idPista, $idTiempoReserva, $partidoCompleto, $numeroJugadores, $repartirImporte, $importeReserva, $aplazarPago, $tipoPagoJugadorReserva, $numeroPedido, $idTarjeta)

{
global $bd;

//JMAM: Comprueba que se ha indicado el Campo Hora
if ($Hora == ""){
    echo Traductor::traducir("Vuelve a intentarlo. Si el problema persiste, contacta con nosotros a organizacion@radicalpadel.com");
    return "error";
}


	$idp = $id;
$fechafinliga = devuelve_un_campo('liga',4,'id',$id_Liga);
if($id_Campo>0){
$Extra3=devuelve_un_campo('campos',10,'id',$id_Campo);
}else{
$Extra3=$Localidad;

}
  $Fecha=cambiaf_a_mysql($Fecha);
  $Fecha_Result=cambiaf_a_mysql($Fecha_Result);

  //if ($Fecha!=devuelve_un_campo('partidos',2,'id',$id)) $CUANTOS_PARTIDOS=Tiene_Partido_Sin_Resultado ($Fecha,$_SESSION['S_id_usuario']);

 $CUANTOS_PARTIDOS=Tiene_Partido_Sin_Resultado_modpartidos ($Fecha,$_SESSION['S_id_usuario'],$Hora,$id);

 if ($CUANTOS_PARTIDOS>=1)
 {
 	$Mensaje='ERROR\\n\\n'.Traductor::traducir("Ya tienes ").' '.$CUANTOS_PARTIDOS.' '.Traductor::traducir("partido el día").' '.cambiaf_a_normal($Fecha).' \\n'.Traductor::traducir("No puedes modificar el partido a ese día y esa hora ya que no cuenta con al menos 1.30 horas de diferencia con los otros partidos que tienes.");
 	 echo "<script>alert('$Mensaje');</script>";
 	 return (0);
 }
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
        $Partido[Partido::COLUMNA_fecha] = $Fecha;
        $Partido[Partido::COLUMNA_hora] = $Hora;
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
        //$Partido[Partido::COLUMNA_idsJugadoresBuscandoSustituto] = $Extra1;
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


    $Partido = new Partido($LastID);
    $Partido->actualizarPartidosApuntadoParaLosJugadoresDelPartido();
	Registrar_Actividad ('3',$LastID);
if(!$id_Jugador_ApuntaResult>0){
	  $sqlr = mysql_query("SELECT * FROM jugadores WHERE id=".$id_Jugador1);
$dtsr = mysql_fetch_array($sqlr);
if($dtsr['tiporecordatorio']>0){
mysql_query("DELETE FROM recordatorios WHERE id_partido=".$idp." AND id_jugador=".$id_Jugador1);
$tiempor = calculaTiempo($idp, $dtsr['tiporecordatorio'], ''.$dtsr['horarecordatorio'].'');
$bd->query("INSERT INTO recordatorios (id_jugador, id_partido, id_liga, tiempo, estado) VALUES (".$id_Jugador1.",".$idp.",".$id_Liga.",".$tiempor.",0)");

}

    //JMAM: Comprueba si se ha modificar el partido
    if ($OK){
        //JMAM: Modificar Reserva
        $Partido = new Partido($idp);
        if ($Partido->esReservaPistaPartido()){
            ReservaPista::modificarReservaPista(
                                    $idPartido = $idp,
                                    $idPista = $idPista,
                                    $horaInicio = $Hora,
                                    $Fecha,
                                    $idTiempoReserva = $idTiempoReserva,
                                    $partidoCompleto = $partidoCompleto,
                                    $id_Jugador1,
                                    $importeReserva,
                                    $tipoPagoJugadorReserva
                            );

            /*
            $ReservaPista = $Partido->obtenerReservaPistaPartido();
            if (!$ReservaPista->comprobarIntegridadReserva(false, false, false)){
                //echo Traductor::traducir("Se ha producido un error en el tratamiento de los datos de la reserva, ponte en contacto con administración si el error persiste");

                $asunto = "Reserva Incorrecta: PROGRAMA - MODIFICACIÓN RESERVA";
               $ReservaPista->comprobarIntegridadReserva(true, false, true, $asunto);
               return "ERROR_AL_COMPROBAR_INTEGRIDAD_RESERVA";

            }
            */
        }
        else if ($Partido->obtenerCampo()->activadoModuloReserva() && $Partido->puedeJugadorOrganizarRealizarReservaPistaYa()){
            //JMAM: INICIO Realizar Reserva Pista (si procede) ////////////////////////////////////////////////////////////////

            if ($repartirImporte){
                $importeReserva = $importeReserva * $numeroJugadores;
            }

          $idReservaPista = ReservaPista::realizarReservaPista(
                                $idPartido = $idp,
                                $idPista = $idPista,
                                $horaInicio = $Hora,
                                $Fecha,
                                $idTiempoReserva = $idTiempoReserva,
                                $idJugadorReserva = $id_Jugador1,
                                $numeroJugadores = $numeroJugadores,
                                $repartirImporte = $repartirImporte,                       //JMAM: El valor del importe de la reserv
                                $importeReserva = $importeReserva,
                                $aplazarPago,
                                $tipoPagoJugadorReserva = $tipoPagoJugadorReserva,
                                $partidoCompleto = $partidoCompleto,
                                $reservaRealizadaPorClub = 0,
                                $numeroPedido = $numeroPedido

                        );


         $arrayJugadoresAdministradores = Juegalaliga::obtenerJugadoresAdministradores($id_Liga);
         foreach ($arrayJugadoresAdministradores as $JugadorAdministrador){

             $idJugador = $JugadorAdministrador->obtenerId();

             $Pista = new Pista($idPista);
             $nombrePista = $Pista->obtenerNombre();

             $array_hora = explode(":",$Hora);
             $hora = $array_hora[0];
             $minutos = $array_hora[1];

             $horaReserva = "$hora:$minutos";

             $dia = getDiaEvento($Fecha, false);
             $fechaNormal = cambiaf_a_normal2($Fecha);

             $JugadorReserva = new Jugador($id_Jugador1);
             $nombreJugador = $JugadorReserva->obtenerNombre(true);
             $telefonoJugador = $JugadorReserva->obtenerTelefono(true);


             $mensaje = "$nombreJugador ".Traductor::traducir("ha reservado", false, $JugadorAdministrador->obtenerIdioma())." $nombrePista ".Traductor::traducir($dia,false, $JugadorAdministrador->obtenerIdioma())." $fechaNormal ".Traductor::traducir("a las", false, $JugadorAdministrador->obtenerIdioma())." ".$horaReserva."h";

             Notificacion::enviarNotificacion($idJugador, Traductor::traducir("NUEVA RESERVA", false, $JugadorAdministrador->obtenerIdioma()), $mensaje, Notificacion::TIPO_NOTIFICACION_verReservasAdmin, $id_Liga, $idReservaPista, Notificacion::ICONO_alarma);
         }


         /*
         $ReservaPista = new ReservaPista($idReservaPista);
         if (!$ReservaPista->comprobarIntegridadReserva(false, false, false)){
             //echo Traductor::traducir("Se ha producido un error en el tratamiento de los datos de la reserva, ponte en contacto con administración si el error persiste");

             $asunto = "Reserva Incorrecta: PROGRAMA - NUEVA RESERVA";
             $ReservaPista->comprobarIntegridadReserva(true, false, true, $asunto);
             return "ERROR_AL_COMPROBAR_INTEGRIDAD_RESERVA";

         }
         */
	        //JMAM: FIN Realizar Reserva Pista (si procede) ////////////////////////////////////////////////////////////////
        }
        else{
            //JMAM: El partido no tiene reserva pista
             if ($Partido->obtenerCampo()->obtenerConfiguracionReservaPistas()->esMostrarPartidosEnTablaReservas()){
                Log::v(__FUNCTION__, "Activado Mostrar Partidos En Tabla de Reservas", true);
                echo $Partido->generarReservaPistaParaMostrarPartidoEnTablaReservas();
            }
        }
    }

}


/*
    if ($OK && $id_Jugador_ApuntaResult > 0){
        $Partido = new Partido($id);
        $Partido->archivarPartido();
    }
*/

     return ($OK);
 }
};

function botones ($numero,$parecido)

{

//echo ("<table>");
//echo ("<tr>");
//echo ("<td>");

echo "<a href='$PHP_SELF?menu=listar&texto=$parecido'><img src='./images/flechatopeiz.gif' border='0' width='20' alt='INICIO partidos'></a>";

//echo ("</td>");


if ($numero>0){
  //        echo ("<td>");

          echo "<a href='$PHP_SELF?menu=listar&texto=$parecido&hoja=".($numero-10)."'><img src='./images/flechaiz.gif' border='0' width='20' alt='ANTERIOR Pï¿œGINA partidos'></a>";

  //        echo ("</td>");
};

//echo ("<td>");

echo "<a href='$PHP_SELF?menu=listar&texto=$parecido&hoja=".($numero+10)."'><img src='./images/flechader.gif' border='0' width='20' alt='SIGUIENTE Página partidos'></a>";

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

function listar_partidos ($parecido,$hoja,$ARR,$PAG,$order,$where,$Exportar,$MiAgenda='',$Resultados=''){

        Log::v(__FUNCTION__, "En partidos_funciones.php", true);

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
$Fecha_Limite= date("Y-m-d", strtotime("$Hoy + 4 days"));//Para Cancelaciï¿œn de participaciï¿œn en partidos y Cancelaciï¿œn de busq de sustituto
$Fecha_Limite2= date("Y-m-d", strtotime("$Hoy + 2 days"));//Para Cancelar un partido
$Fecha_Limite5= date("Y-m-d", strtotime("$Hoy + 5 days"));//Organizador del partido
$YO=$_SESSION['S_id_usuario'];
$Manana=strtotime("$Hoy + 1 days");
$Vs=0;$Vc=0;$P9=0;$P18=0;

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
							<div id='contentwrap' class='contenedor_principal'>
							</div>
							";
				}else{
					if($_SESSION['S_TipoDeLiga']!='QUEDADA' && Sesion::obtenerDeporte()->obtenerId() == Deporte::ID_padel){

                       $textoBoton_menuAbrirPartido = obtenerTextoBontonMenuSuperiorAbrirPartido();


					echo"
							<ul id='navtabs'  style='text-align:center'>
							   <li><a style='width:32%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li>
							   <li><a style='width:32%' href='./partidos.php' title='Partidos' $PARTIDOSCURRENT>".Traductor::traducir("Partidos")."</a></li>
							   <li><a style='width:32%' href='./partidos.php?menu=alta' title='Nuevo'>".$textoBoton_menuAbrirPartido."</a></li>
							</ul>
							<div id='contentwrap' class='contenedor_principal'>
							</div>
							";
						}else{
						echo"
							<ul id='navtabs'  style='text-align:center'>
							   <li><a style='width:48%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li> 
							   <li><a style='width:48%' href='./partidos.php' title='Partidos' $PARTIDOSCURRENT>".Traductor::traducir("Partidos")."</a></li>
							</ul>
							
							<div id='contentwrap' class='contenedor_principal'>
							</div>
							";

						}
							}
				}


}

/////////////LA CONSULTA

if ($_SQL=='')
{
$cuantosmostrar = 50;
      //   $q="SELECT * FROM partidos WHERE ((Fecha>=CURDATE() AND Fecha_Result='0000-00-00') OR ((id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." ) AND Fecha>=CURDATE()-1)) AND ((favoritos=0)OR(favoritos=1 AND (SELECT COUNT(id) FROM noesfavorito WHERE (partidos.id_Jugador1>0 AND partidos.id_Jugador1<>".$_SESSION['S_id_usuario']." AND id_Jugador=partidos.id_Jugador1 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") OR (partidos.id_Jugador2>0 AND partidos.id_Jugador2<>".$_SESSION['S_id_usuario']." AND id_Jugador=partidos.id_Jugador2 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") OR (partidos.id_Jugador3>0 AND partidos.id_Jugador3<>".$_SESSION['S_id_usuario']." AND id_Jugador=partidos.id_Jugador3 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") OR (partidos.id_Jugador4>0 AND partidos.id_Jugador4<>".$_SESSION['S_id_usuario']." AND id_Jugador=partidos.id_Jugador4 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario']."))<1)) AND ";  if (!$MiAgenda) $q.="id_Liga=".$_SESSION['S_LIGA_ACTUAL'];
      $q="SELECT * FROM partidos

          WHERE
               (
                    (Fecha>=CURDATE() AND Fecha_Result='0000-00-00')

                    OR
                     (
                         (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario'].")
                         AND Fecha>=CURDATE()-10 AND Fecha_Result='0000-00-00'
                     )
                ) AND
                    (
                      (favoritos=0)
                       OR
                        (
                           (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." )
                         )
                      OR(
                           favoritos=1 AND (
                                             SELECT COUNT(*) FROM noesfavorito WHERE
                                                                                 (id_Jugador=partidos.id_Jugador1 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].")
                                                                                 OR (id_Jugador=partidos.id_Jugador2 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].")
                                                                                 OR (id_Jugador=partidos.id_Jugador3 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].")
                                                                                 OR (id_Jugador=partidos.id_Jugador4 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].")
                                            )<1
                        )
                    ) AND ";

       //  $q="SELECT * FROM partidos WHERE ((Fecha>=CURDATE() AND Fecha_Result='0000-00-00') OR ((id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." ) AND Fecha>=CURDATE()-1 AND Fecha_Result='0000-00-00')) AND ((favoritos=0)OR(favoritos=1 AND (SELECT COUNT(*) FROM noesfavorito WHERE (id_Jugador=partidos.id_Jugador1 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") AND (id_Jugador=partidos.id_Jugador2 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") AND (id_Jugador=partidos.id_Jugador3 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario'].") AND (id_Jugador=partidos.id_Jugador4 AND id_Jugador_NO_Favorito=".$_SESSION['S_id_usuario']."))<1)) AND ";
      //   $q="SELECT * FROM partidos WHERE ((Fecha>=CURDATE() AND Fecha_Result='0000-00-00') OR ((id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." ) AND Fecha>=CURDATE()-1)) AND ";
		 if (!$MiAgenda) $q.="id_Liga=".$_SESSION['S_LIGA_ACTUAL'];


         if ($where!="")  { $q.="  and ".$where;  }
         else if ($parecido!="")
         {
          $coma="'"; $q.=" and ";
          $q.="id like $coma%$parecido%$coma OR id_Liga like $coma%$parecido%$coma OR Fecha like $coma%$parecido%$coma OR Hora like $coma%$parecido%$coma OR TipoPuntuacion like $coma%$parecido%$coma OR id_Campo like $coma%$parecido%$coma OR Otro_Campo like $coma%$parecido%$coma OR id_Jugador1 like $coma%$parecido%$coma OR id_Jugador2 like $coma%$parecido%$coma OR id_Jugador3 like $coma%$parecido%$coma OR id_Jugador4 like $coma%$parecido%$coma OR Puntos_J1 like $coma%$parecido%$coma OR Puntos_J2 like $coma%$parecido%$coma OR Puntos_J3 like $coma%$parecido%$coma OR Puntos_J4 like $coma%$parecido%$coma OR id_Jugador_ApuntaResult like $coma%$parecido%$coma OR Fecha_Result like $coma%$parecido%$coma OR Observaciones like $coma%$parecido%$coma OR Extra1 like $coma%$parecido%$coma OR Extra2 like $coma%$parecido%$coma OR Extra3 like $coma%$parecido%$coma";
         }
         if ($MiAgenda)	{ $q.=" 1 and (id_Jugador1=".$_SESSION['S_id_usuario']." OR id_Jugador2=".$_SESSION['S_id_usuario']." OR id_Jugador3=".$_SESSION['S_id_usuario']." OR id_Jugador4=".$_SESSION['S_id_usuario']." )";}

         //Nuevo jm 31/08 13:15, agrego que en MI AGENDA salgan los partidos pendientes de confirmar como pareja  a los que estï¿œ invitado
         //a travï¿œs de una consulta union. Optimizo la consulta union para que no tarde.
         if ($MiAgenda)	{

            $q.=" union (select * from partidos where Fecha>=CURDATE() AND Fecha_Result='0000-00-00' AND id IN (SELECT id_partido FROM partidos_pendientes_pareja WHERE confirmado='N' and id_jugador_invitado=".$_SESSION['S_id_usuario'].") )";
            //echo $q;
         }else

         //NUEVO: INTRODUCIMOS Lï¿œGICA DE PAREJAS
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
		 if(!isset($_GET['filtrorapido']) && !isset($_GET['filtrolocalidad']) && !isset($_GET['filtrofecha'])){
     if(!isset($_GET['pg'])){
		 $pags = 0;
		 }else{
		 $pags = $_GET['pg']; echo"PAG=$pags";
		 }
		 $qrx = $q;
        $q.= " LIMIT ".($pags*$cuantosmostrar).",".$cuantosmostrar;
		}
}

else
{
    $q=$_SQL;
}

         $si=mysql_query($q); $total_rows=mysql_num_rows ($si);
         $q.=" ".$order;
         //if ($PAG=='on') $q.=" LIMIT $hoja,10";


//echo $q;

         $si=mysql_query($q);


//echo"<br><br>$total_rows resultados";

if ($total_rows) //Si hay resultados
{

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
if(!$MiAgenda && $_GET['menu']!='misresultadospendientes'){
//if 	($_SESSION['S_Version']=='Movil') echo"<br>";
$totalpags = mysql_num_rows(mysql_query($qrx));
$tpart = $totalpags;
$totalpags = ceil($totalpags/$cuantosmostrar);
echo"
<div style='text-align:center' class='align-center filtrocontainer2'>
<script>
var pgn=0;
var totalpg=".$totalpags.";
</script>
";
$urlanidada = '?1';
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


<a href="partidos.php" class="botonancho optselected" style="width: 31%; display: inline-block; margin-left: 2%;"><?php echo Traductor::traducir("Filtro Rápido");?></a>
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
$styleactivado = 'background:#5FB33A;color:white';
}else{
$textfecha = 'Fecha';
$styleactivado = '';
}
?>


<input style="display:inline-block;<?=$styleactivado?>;text-align:center" readonly class=" botonancho-4 botonancho-5 fechac CP_fechapartido" id="fecha" type="text" size="12" value="Fecha"<?=$textfecha?>" onchange="actualizarSelectorHoras()"/>


<?php
}else{
	if(isset($_GET['filtrofecha'])){
$textfecha = $_GET['filtrofecha'];
}else{
$textfecha = date("d/m/Y", time());
}
	?>
	<input class="fechac botonancho" id="fecha" type="text" name='Fecha' size="" value="<?=$textfecha?>" onchange="actualizarSelectorHoras()"/>


	<!--<input class='textbox fechac' type='text' $sololectura name='Fecha' value='".cambiaf_a_normal($ant_Fecha)."' " .
				"id='Fec1' size='15' maxlength='10' $EsPuntuacion>-->

	<!--<input style="display:inline-block;width:25%;;margin:5px 5px;text-align:center" readonly="readonly" class="fechac botonancho" id="fecha" size="12" value="Fecha" type="text">-->

	<?php


/*echo ("	<script>function cargacalendario(){");
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

		 </script>");*/
}

 if($_SESSION['S_LIGA_ACTUAL']==45 || $_SESSION['S_LIGA_ACTUAL']==27 || 1==1){
if 	($_SESSION['S_Version']=='Movil'){
	if(isset($_GET['filtrolocalidad']) && $_GET['filtrolocalidad']!=1){
		$styleactivado2 = 'background:#5FB33A;color:white';
	}else{
		$styleactivado2 = '';
	}
	?>
<a class="botonancho botonancho-5 CP_selectlocalidad"><select class="botonancho-2" style="<?=$styleactivado2?>;width:100%;display:inline;border:0px;z-index:150;position:relative; margin: 0px" onchange="document.location='<?=$urlanidada?>&filtrolocalidad='+this.value">
	<? }else{ ?>
<span ><select style="margin-left:10px;width:100px;" onchange="document.location='<?=$urlanidada?>&filtrolocalidad='+this.value">
	<? } ?>
<option value="1"><?=Traductor::traducir("Localidad")?></option>
<?
$fl = mysql_query("SELECT DISTINCT(Extra3) FROM partidos WHERE Fecha>=CURDATE() AND id_Liga=".$_SESSION['S_LIGA_ACTUAL']." ORDER BY Extra3;");
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
              	if 	($_SESSION['S_Version']!='Movil' && !isset($_GET['pg']))
				{

	              // echo "";
				////comenzamos a mostrar los nombres (todo en una fila)
			  //echo ("<td width='15' class='cabecera'><p class='textablacabecera'>Fecha</p></td>");
  			echo ("<td width='30' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("Hora")."</p></td>");
					 //	echo ("<td width='15' class='cabecera'><p class='textablacabecera'>Tipo</p></td>");
  			echo ("<td width='135' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("Pista")."</p></td>");
  			echo ("<td width='75' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("JUG")." 1</p></td>");
       			if (!$Resultados) echo ("<td width='20' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("Inf")."</p></td>");
  			echo ("<td width='75' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("JUG")." 2</p></td>");
  			echo ("<td width='75' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("JUG")." 3</p></td>");
  			echo ("<td width='75' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("JUG")." 4</p></td>");
  			echo ("<td width='30' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("NIV")."</p></td>");
  			echo ("<td width='25' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("FAV")."</p></td>");
  			echo ("<td width='55' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("OPCIONES")."</p></td>");



///////fin de mostrado de nombres


echo ("</td>");
echo ("</tr>");

				}

$FechaAnterior=0;

$colortoca=0;

//////////////fin de la fila de nombres y botï¿œn de alta


$MiHCP=devuelve_un_campo("jugadores",35,"id",$YO);$MiHCP=floor($MiHCP/100)+1;
$fechafinliga = devuelve_un_campo('liga',4,'id',$_SESSION['S_LIGA_ACTUAL']);
$fechainicioliga = devuelve_un_campo('liga',3,'id',$_SESSION['S_LIGA_ACTUAL']);
                    while (list($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Extra3,$tienecreditos) = mysql_fetch_array($si))

                   {
                   	$id_PART=$id;
/////CONTROLES DE PARTIDO
//"ORGANIZO=$Soy_el_organizador<br><3Dias:$Faltan_menos_de_3_dias<br>Partido_Pasado:$Partido_Pasado<br>Abierto:$Partido_Abierto<br>Vacio:$Partido_Vacio<br>$Libre:$Partido_Libre<br>YO:$YO_Juego<br>FaltaResul:$FaltaResultado";

//

if ($id_Jugador1==$YO) {$Soy_el_organizador='1';} else {$Soy_el_organizador='0';}
if (strtotime("$Fecha_Limite")>strtotime("$Fecha")) $Faltan_menos_de_3_dias='1'; else $Faltan_menos_de_3_dias='0';
if (strtotime("$Fecha_Limite2")>strtotime("$Fecha")) $Faltan_menos_de_2_dias='1'; else $Faltan_menos_de_2_dias='0';
if (strtotime("$Fecha_Limite5")>strtotime("$Fecha")) $Faltan_menos_de_5_dias='1'; else $Faltan_menos_de_5_dias='0';

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

if (($MiHCP<$nivel_min)||($MiHCP>$nivel_max)) $Partido_Abierto='0';


if (($id_Jugador2==$YO)||($id_Jugador3==$YO)||($id_Jugador4==$YO)||($id_Jugador1==$YO)) $YO_Juego='1'; else $YO_Juego='0';



if (strtotime("$Fecha")>(strtotime("$Hoy - 2 days"))) $Pasaronmenosde3Dias=1; else $Pasaronmenosde3Dias=0;
if (strtotime("$Fecha")>(strtotime("$Hoy + 1 days"))) $falta1dia=0; else $falta1dia=1;
//PArtido pasado pero pendiente de resultado por mi parte
$PendienteResultado=0;
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
			else {echo "<ul><li class='post'> <span class='content'><span class='category'>";$TamMovil=" style='font-size:1.6em; '";}

			echo"<font color=brown $TamMovil>".Traductor::traducir("PENDIENTE RESULTADO")." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>";

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
$fechado=true;
}
    if ($FechaAnterior!=$Fecha)
    {
///////////////////////////ff/////////////////////////////////////////////////////////////////////////
/////////////////////////////////// ADAPTACION MOVIL
////////////////////////////////////////////////////////////////////////////////////////////////////

    	    	if 	($_SESSION['S_Version']!='Movil') {$ANT1="<tr><td colspan=13><br>"; $FIN1="</td></tr>";}
    	    	else {$ANT1="</div><ul style='margin-top:0px'><li class='post'> <span class='content' style=' '><span class='category cabecera' style=''>";$FIN1="</span></span></li></ul>";}
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

		if (strtotime($Fecha)==strtotime($Hoy))
    	{
    	echo"$ANT1 $div_verde<b><font color=$color1>".Traductor::traducir("HOY")." ".dia_semana($Fecha)." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
    	//cabecera ();
    	}
    	else if (strtotime($Fecha)==$Manana)
    	{
    	echo"$ANT1 $div_verde<b><font color=$color2>".Traductor::traducir("MAÑANA")." ".dia_semana($Fecha)." </font><font size=-1>(".cambiaf_a_normal($Fecha).")</font></b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
    	//cabecera ();
    	}
    	else
    	{
    	echo"$ANT1 $div_verde<b>".dia_semana($Fecha)." ".cambiaf_a_normal($Fecha)."</b>$fin_div_verde $FIN1"; $FechaAnterior=$Fecha;
    	//cabecera ();
    	}


    };
////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// ADAPTACION MOVIL
////////////////////////////////////////////////////////////////////////////////////////////////////


    	    	if 	($_SESSION['S_Version']!='Movil') {$ANT2="<tr bgcolor='#FFFFFF' height=\"41\">"; $ANT3="<tr bgcolor='#FFFFFF' height=\"41\">"; $ANT_="<tr bgcolor='lightpink' height=\"41\">"; $FIN1="</td></tr>";
				$clasem ='';
				}
    	    	else {
    	    		$ANT2="<ul style='background-color: #f5f5f5;'>";
    	    		$ANT3="<ul style='background-color:#fff;'>";
					$ANT_="<ul style='background-color:lightpink;'>";
    	    		$FIN1="</span></li>";
					$clasem ='m';
					}

/////////Modificado para poner rojo claro los partidos cancelados
if (Estado_Cancelac($id_PART)=='S') {echo ("$ANT_");$clase=''.$clasem;}
else
{
if ($colortoca!=0){echo ("$ANT2");$clase='fila'.$clasem;$colortoca=0;}
else {echo ("$ANT3");$clase='fila2';$colortoca=1;};
}


//echo"$id_PART=".Estado_Cancelac($id_PART)."<br>";

$titulo1='';$titulo2='';$titulo3='';$titulo4='';

////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// ADAPTACION MOVIL
////////////////////////////////////////////////////////////////////////////////////////////////////

				if 	($_SESSION['S_Version']!='Movil')
				{
					$ANT4="<td class=$clase width=30><p class='dato' align=center>";
					$ANT5="<td class=$clase width=135><p class='dato' align=center>";
					$ANT6="<p class='dato' align=center><font color=grey>";
					$FIN6="</font>";
					$FIN4="</p></td>";
				}
    	    	else {
    	    		$ANT4="<li class='post caja-texto' style=''> <span class='content'><span class='category' style=';'>";
    	    		$FIN1="</span>";
    	    		$ANT6="<span class='category liga2' style=''>";
    	    		$FIN6="</span>";
    	    		}

      echo ("$ANT4<b>$Hora</b>$FIN4");
		//////// INICIO NIVEL Y FAVORITOS VERSION MOVIL
if 	($_SESSION['S_Version']=='Movil')
{
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


		//if($favoritos){ echo ("<img src='./images/fav.png' title='FAV' />"); }
		$ANT9Nivela="<span style='font-size:0.7em;display:block'>".Traductor::traducir("Nivel")." $nivel_max ".Traductor::traducir("a")." $nivel_min</span>";
     		$ANT9Nivelb="<span style='font-size:0.7em;display:block'>".Traductor::traducir("Nivel Mínimo")." $nivel_min </span>";
   	   		$ANT9Nivelc="<span style='font-size:0.7em;display:block'>".Traductor::traducir("Nivel Máximo")." $nivel_max </span>";
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
}

////// FIN FAVORITOS Y NIVEL VERSION MOVIL
		//echo"$Partido_Cancelado_o_Cancelandose - $id_de_cancelacion<br>";
	  if ($MiAgenda) $NombreLiga="Liga: ".devuelve_un_campo ("liga",1,"id",$id_Liga)."<br>"; else $NombreLiga='';


      if ($id_Campo)
      {
	  	$TIENE_CONVENIO=devuelve_un_campo ("campos",4,"id",$id_Campo);
	  	if ($TIENE_CONVENIO=='S') echo  "$ANT5 $NombreLiga<a style='display:inline-block;padding:0px' href=\"javascript:Boxy.load('fichacampos.php?id=".$id_Campo."', {title:  '".devuelve_un_campo ("campos",2,"id",$id_Campo)."'});\"><font style='font-weight: bold; color: #1469CD; font-size: ".($_SESSION['S_Version']!='Movil'?'12px':'17px').";'>".devuelve_un_campo ("campos",2,"id",$id_Campo)."</font></a>";
	  	else echo  "$ANT5 $NombreLiga<a style='display:inline-block;padding:0px' href=\"javascript:Boxy.load('fichacampos.php?id=".$id_Campo."', {title:  '".devuelve_un_campo ("campos",2,"id",$id_Campo)."'});\"><font style='color:#004F0C;font-size:".($_SESSION['S_Version']!='Movil'?'12px':'18px')."'>".devuelve_un_campo ("campos",2,"id",$id_Campo)."</font></a>";
      }
      else //OTRO CAMPO
      {
      	$TIENE_CONVENIO='N';
	  	echo  "$ANT5 $NombreLiga<font style='color:gray;font-weight:bold;font-size:".($_SESSION['S_Version']!='Movil'?'12px':'18px')."'>$Otro_Campo</font>";
      }

if($id_Campo>0){
      echo  "$ANT6 ".devuelve_un_campo ("campos",10,"id",$id_Campo)." $FIN6";
}else{
	echo  "$ANT6 ".$Extra3." $FIN6";
}
//      	if ($TipoPuntuacion=='STABLEFORD 9-Hoyos') echo "<br>STB. <b>9</b> Hoyos";
//		if ($TipoPuntuacion=='STABLEFORD 18-Hoyos') echo "<br>STB. <b>18</b> Hoyos";

      //$Recorrido=devuelve_un_campo ("campos",36,"id",$id_Campo);
      if ($TIENE_CONVENIO=='S')
      	{

      		if 	($_SESSION['S_Version']!='Movil') {echo " <a href=\"javascript:Boxy.load('fichacampos.php?id=".$id_Campo."', {title:  '".devuelve_un_campo ("campos",2,"id",$id_Campo)."'});\"><img src='./images/notice-icon.png' title='Convenio: ".devuelve_un_campo ("campos",6,"id",$id_Campo)."'></a>";}
      		//else echo "<span class=metadata style='font-size:0.5em;'>Convenio: ".devuelve_un_campo ("campos",6,"id",$id_Campo)."</span>";
      	}

		echo ("$FIN4");


      //echo("<p class='dato'>Par:$ParCampo<br>$Vs,$Vc,$P9,$P18</p></td>");

      if (!$Resultados)
      {
   	   	//echo ("<td class=$clase width=75 title='HJola'><p class='dato' align=center valign=center>".minificha_jugador ($id,$id_Jugador1,$Soy_el_organizador,$Partido_Abierto,'',$YO_Juego)." ");
   	   	if ($id_Jugador1)
   	   		{//calculo del HcJ
   	   			$Hex=devuelve_un_campo("jugadores",35,"id",$id_Jugador1);$HcJ=$Hex;
   	   			//$HcJ=$Hex * $Vs / 113 + ( $Vc - $ParCampo);
   	   			//$HcJ=(round($HcJ*10)/10);
   	   			$titulo1=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";
				//echo"SOLICITO CANCELACIï¿œN";
   	   		}
				if ($id_Jugador2)
			{//calculo del HcJ
   	   			$Hex=devuelve_un_campo("jugadores",35,"id",$id_Jugador2);$HcJ=$Hex;
   	   			//$HcJ=$Hex * $Vs / 113 + ( $Vc - $ParCampo);
   	   			//$HcJ=(round($HcJ*10)/10);
   	   			$titulo2=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";
   	   		}

			if ($id_Jugador3)
			{//calculo del HcJ
   	   			$Hex=devuelve_un_campo("jugadores",35,"id",$id_Jugador3);$HcJ=$Hex;
   	   			//$HcJ=$Hex * $Vs / 113 + ( $Vc - $ParCampo);
   	   			//$HcJ=(round($HcJ*10)/10);
   	   			$titulo3=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";
   	   		}

			if ($id_Jugador4)
			{//calculo del HcJ
   	   			$Hex=devuelve_un_campo("jugadores",35,"id",$id_Jugador4);$HcJ=$Hex;
   	   			//$HcJ=$Hex * $Vs / 113 + ( $Vc - $ParCampo);
   	   			//$HcJ=(round($HcJ*10)/10);
   	   			$titulo4=Traductor::traducir("Nivel")." ".(floor($HcJ/100)+1)." (".Traduce_Nivel ($HcJ).")";
   	   		}
////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// ADAPTACION MOVIL
////////////////////////////////////////////////////////////////////////////////////////////////////

   	   	if 	($_SESSION['S_Version']!='Movil')
   	   	{
   	   		$ANT7a="<td class=$clase width=75 title='$titulo1'><p class='dato' align=center valign=center>";
   	   		$ANT7b="<td class=$clase width=75 title='$titulo2'><p class='dato' align=center valign=center>";
   	   		$ANT7c="<td class=$clase width=75 title='$titulo3'><p class='dato' align=center valign=center>";
			$ANT7d="<td class=$clase width=75 title='$titulo4'><p class='dato' align=center valign=center>";
   	   		$ANT8="<tr bgcolor='#FFFFFF' height=\"41\">";
   	   		$FIN7="</td>";
   	   		}
    	else {
    		//$ANT7a="<span>";$ANT7b="<span>";$ANT7c="<span>";$ANT7d="<span>";
    		//$ANT7="<li class='post'> <span class='title'><span class='metadata'>";
    		//$ANT8="<li class='post'> <span class='content'><span class='metadata'>";$FIN7="</span>";
			$ANT7a="<td class=$clase width=75 title='$titulo1'>";
   	   		$ANT7b="<td class=$clase width=75 title='$titulo2'>";
   	   		$ANT7c="<td class=$clase width=75 title='$titulo3'>";
			$ANT7d="<td class=$clase width=75 title='$titulo4'>";
   	   		$ANT8="<tr bgcolor='#FFFFFF' height=\"41\">";
   	   		$FIN7="</td>";
			echo '<table width="100%">';
			}
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////GESTION BOTON JUGADOR 1
		echo ("$ANT7a".minificha_jugador ($id,$id_Jugador1,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'1')." ");
   	   	echo("$FIN7");
   	   	//echo ("<td><div class=note><p class='typo-icon' align=center>".minificha_jugador ($id,$id_Jugador1,$Soy_el_organizador,$Partido_Abierto,$YO_Juego)."</p></div></td>");



///////////////////////////////////////////GESTION BOTON JUGADOR 2
   //OBtenemos los jugadores para mostrar o no el boton de apuntarse controlando si el jugador de ese boton ya esta asignado.

   $sqlr = mysql_query("SELECT id_Jugador2,id_Jugador3,id_Jugador4 FROM partidos WHERE id=$id") or die("Error partidos-1297: Error al obtener datos de unisexo. Consulte a soporte.");
   $dtsr = mysql_fetch_array($sqlr);
   $id_jugador_2=$dtsr['id_Jugador2'];
   $id_jugador_3=$dtsr['id_Jugador3'];
   $id_jugador_4=$dtsr['id_Jugador4'];

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
      if 	($_SESSION['S_Version']=='Movil')
         $style_mvl="float:none;height:auto;margin:0 0 0 0em;";
      $boton_popup_amistosos='
      <a style="display:block" href="javascript:Boxy.load(\'popup_amistosos.php?resultados='.$numero_jugados.'&boxy=1\', {title:  \'Informacion\'});">
      <img style="'.$style_mvl.'" src="./images/images_j/meapuntoOFF.png" onmouseover="this.src=\'./images/images_j/meapuntoON.png\';" onmouseout="this.src=\'./images/images_j/meapuntoOFF.png\';" border="0" width="66" \'="">
      </a>';
   }

   //En cada botï¿œn, entra a hacer lo que hacï¿œa antes si no es amistoso, o si lo es y se el usuario puede crear/jugar en amistosos.
   //En caso de que se no dï¿œ esa situaciï¿œn, no muestra la minificha.
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
               $inicio_resalta_pareja="<div><center><img $EstiloMovil src='./images/images_j/pareja_espera.png'>";


           $fin_resalta_pareja="</center></div>";

           if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_jugador_2!=0) //Si el jugador 2 ya esta establecido muestro el boton porque lo que se va a mostrar es la fichita de el.
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
           if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_jugador_2!=0)
              $minifichaj2=minificha_jugador($id,$id_Jugador2,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'2','S',$partido_es_mixto); //Le pasamos "S" para que muestre la imagen diferente de apuntarse para la pareja
           else
              $minifichaj2=''; //No por mixto o sexo2

           echo ("$ANT7b  ".$minifichaj2." ");
        }

        //No se ha entrado por ninguno de los dos anteriores casos
        if($resultado<=0 and $resultado2<=0 )
        {

                 if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_jugador_2!=0)
                    $minifichaj2=minificha_jugador ($id,$id_Jugador2,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'2','N',$partido_es_mixto);
                 else
                    $minifichaj2=''; //No por mixto o sexo3

           echo ("$ANT7b  ".$minifichaj2." ");
      	}
        ///////////////////////////////////////////

      	//echo ("$ANT7b $inicio_resalta_pareja ".minificha_jugador ($id,$id_Jugador2,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'2')." $fin_resalta_pareja");
        echo("$FIN7");

   }else{ //fin amistosos
      if($_SESSION['S_Version']=='Movil'){
         $spani='<span style="font-size:9px;">';
         $spanf='</span>';
      }
      echo ("$ANT7b $spani $boton_popup_amistosos $spanf $FIN7");
   }
///////////////////////////////////////////GESTION BOTON JUGADOR 3

   if($partido_amistoso=='N' OR ($partido_amistoso=='S' and $puede_crear_amistosos=='S' ))
   {
        if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_jugador_3!=0)
           $minifichaj3=minificha_jugador ($id,$id_Jugador3,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'3','N',$partido_es_mixto);
        else
           $minifichaj3=''; //No por mixto o sexo

      	echo ("$ANT7c ".$minifichaj3." ");
   	   	echo("$FIN7");
    }else { //fin amistosos
      if($_SESSION['S_Version']=='Movil'){
         $spani='<span style="font-size:9px;">';
         $spanf='</span>';
      }
      echo ("$ANT7c $spani $boton_popup_amistosos $spanf $FIN7");
   }
///////////////////////////////////////////GESTION BOTON JUGADOR 4

    if($partido_amistoso=='N' OR ($partido_amistoso=='S' and $puede_crear_amistosos=='S' ))
    {
        if(evalua_unisexo_mixto($_SESSION['S_id_usuario'],$id) or $id_jugador_4!=0)
           $minifichaj4=minificha_jugador ($id,$id_Jugador4,$Soy_el_organizador,$Partido_Abierto,$YO_Juego,'',$Extra1,1,'4','N',$partido_es_mixto);
        else
           $minifichaj4=''; //No por mixto o sexo

      	echo ("$ANT7d ".$minifichaj4." ");
   	   	echo("$FIN7");


   	}else{ //fin amistosos
      if($_SESSION['S_Version']=='Movil'){
         $spani='<span style="font-size:9px;">';
         $spanf='</span>';
      }
      echo ("$ANT7d $spani $boton_popup_amistosos $spanf $FIN7");
   }



		if 	($_SESSION['S_Version']=='Movil')
   	   	{
echo '</table>';
}
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

if 	($_SESSION['S_Version']!='Movil')
{
	  $generopartido = $Extra2;
	  $pluralgenero = '';
	  	if 	($_SESSION['S_Version']=='Movil')
   	   	{
		$EstiloMovil=" style='float:none;height:auto;width:20px;margin:0' ";
		}
		$tcred= '';


if($generopartido=='Hombre'){
$pluralgenero="<img ".($_SESSION['S_Version']=='Movil'?'width="32px"':'width="16px"')." $EstiloMovil src=\"./images/male-icon.png\" title=\"".Traductor::traducir("Partido solo masculino")."\">";
}
if($generopartido=='Mujer'){
$pluralgenero="<img ".($_SESSION['S_Version']=='Movil'?'width="32px"':'width="16px"')." $EstiloMovil src=\"./images/female-icon.png\" title=\"".Traductor::traducir("Partido solo femenino")."\">";
}
   	   	if 	($_SESSION['S_Version']!='Movil')
   	   	{
   	   		// condicion de sergio para mostrar moneda cuando el partido tenga credito
   	   		 			if($tienecreditos==1){
			$tcred= '<br><img src="./images/monedagirando.gif" title="Partido de creditos" style="width:15px" />';
		}

   	   		$ANT9Nivela="<td class=$clase  width=30 title='".Traductor::traducir("de")." $nivel_max ".Nivel_en_letras ($nivel_max)." ".Traductor::traducir("a")." $nivel_min ".Nivel_en_letras ($nivel_min)."'><p class='dato' align=center>$nivel_max<br>a $nivel_min".$tcred."</p></td>";
   	   		$ANT9Nivelb="<td class=$clase  width=30><p class='dato' align=center title='$nivel_min ".Nivel_en_letras ($nivel_min)."'>$nivel_min</p></td>";
   	   		$ANT9Nivelc="<td class=$clase  width=30><p class='dato' align=center title='$nivel_max ".Nivel_en_letras ($nivel_max)."'>a $nivel_max</p></td>";
			$ANT7d="<td class=$clase width=75 title='$titulo4'><p class='dato' align=center valign=center>";
   	   		$ANT9SinNivel="<td class=$clase  width=25><p class='dato' align=center>-".$tcred."</p></td>";
   	   		$FIN7="</td>";
   	   		$EstiloMovil="";
   	   		}
    	else {

//Nuevo: icono de mixto para moviles
$sqlr = mysql_query("SELECT count(*) as res from partidos_mixtos WHERE id=".$id_PART) or die('Error partidos-921: Consulta erronea, consulte con soporte.');
$dtsr = mysql_fetch_array($sqlr);
$espartidomixto=intval($dtsr['res']);
if($espartidomixto>0)
  $partidomixtoimg="<img $EstiloMovil title='Es partido mixto' src='images/mixto.png' alt='Este partido es mixto'>";
else
  $partidomixtoimg='';

		//if($favoritos){ echo ("<img src='./images/fav.png' title='FAV' />"); }
		$ANT9Nivela="de $nivel_max ".Nivel_en_letras ($nivel_max)." a $nivel_min ".Nivel_en_letras ($nivel_min)."</span>";
     		$ANT9Nivelb="Mï¿œnimo $nivel_min ".Nivel_en_letras ($nivel_min)."</span>";
   	   		$ANT9Nivelc="Mï¿œximo $nivel_max ".Nivel_en_letras ($nivel_max)."</span>";
   	   		$ANT9SinNivel='';
   	   		$EstiloMovil=" style='float:none;height:auto;width:20px;margin:0 0 0 0.4em;' ";
			 if ($favoritos)       echo ("<span class='category'><font color=red> <img ".($_SESSION['S_Version']=='Movil'?'width="32px"':'width="16px"')." src='./images/fav.png' title='".Traductor::traducir("SOLO FAVORITOS")."' $EstiloMovil/>  $pluralgenero</font>");
	  else echo ("<span class='category'><font color=red>$pluralgenero</font>");

            echo $partidomixtoimg;

    		}
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

      if (($nivel_min)||($nivel_max!=1899))
      {
      	 if (($nivel_min)&&($nivel_max!=99)) echo ("$ANT9Nivela");
      	 else if ($nivel_min) echo ("$ANT9Nivelb");
      	 else if ($nivel_max!=99) echo ("");
      }
      else  echo ("</span>$ANT9SinNivel");

}


////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// ADAPTACION MOVIL
////////////////////////////////////////////////////////////////////////////////////////////////////

if 	($_SESSION['S_Version']!='Movil')
{

$sqlr = mysql_query("SELECT count(*) as res from partidos_mixtos WHERE id=".$id_PART) or die('Error partidos-921: Consulta erronea, consulte con soporte.');
$dtsr = mysql_fetch_array($sqlr);
$espartidomixto=intval($dtsr['res']);
if($espartidomixto>0)
  $partidomixtoimg="<img width='16px' title='Es partido mixto' src='images/mixto.png' alt='Este partido es mixto'>";
else
  $partidomixtoimg='';


      if ($favoritos)       echo ("<td class=$clase  width=25><p class='dato' align=center>".$pluralgenero.$partidomixtoimg."<img ".($_SESSION['S_Version']=='Movil'?'width="32px"':'width="16px"')." src='./images/fav.png' title='".Traductor::traducir("SOLO FAVORITOS")."' /></p></td>");
      else echo ("<td class=$clase  width=25><p class='dato' align=center>".$pluralgenero.$partidomixtoimg."</p></td>");
}
else
{


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
	        if ($Observaciones or $amistoso or $resultado3) echo"<span class='metadata2' style='float:none;''>".($resultado3>0?'<img style="width:20px;height:20px;float:left;" '.$EstiloMovil.' src="images//boton%20pareja.png">&nbsp;'.Traductor::traducir("Los jugadores 1 y 2 van juntos como pareja."):'').($amistoso?'<img style="width:20px;height:18px;float:left;" '.$EstiloMovil.' src="images/Amistoso_sin_ranking.png"> '.Traductor::traducir("Partido Amistoso. No puntuable"):'').($Observaciones?'<img src="./images/icon-notes.png" '.$EstiloMovil.' ">&nbsp;'.htmlentities($Observaciones):'')."</span>";


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
}


////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// BOTONES
////////////////////////////////////////////////////////////////////////////////////////////////////
/////////Modificado para poner rojo claro los partidos cancelados
if (Estado_Cancelac($id_PART)=='S') $Mostraralgunboton=0;
else $Mostraralgunboton=1;

if (Estado_Cancelac($id_PART)=='N')  $PartidoCancelandose=1;
else $PartidoCancelandose=0;

if 	($_SESSION['S_Version']!='Movil')
{
echo ("<td class=$clase colspan=2>");
/////////tabla para contener a los botones de editar y borrar

echo ("<table border=0>");
echo ("<tr><td  width=55>");
$FINBotones="</td><td>";
$DIVMOVIL="";
	$EstiloMovil="";$EstiloA ='';
}
else
{
	$FINBotones="";
	$DIVMOVIL="<div >";
	$EstiloMovil="class='botonancho-3 botontrans CP_iconsjota' style='padding: 10px;float:none;height:20px;width:20px;margin:0 0 0 10px;display:inline-block'; ";
	$EstiloA=" style='display:inline;float:center;' ";
	$EstiloTamanoImg=" style='height:20px;width:20px;margin:0px' ";
	echo"<center><div style='margin: 0;width:auto; vertical-align=middle;' valign=center>";

}


if (($Soy_el_organizador)&&($FaltaResultado)&&(!$Partido_Pasado) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']) && $Mostraralgunboton && $_SESSION['S_TipoDeLiga']!='QUEDADA')
{
echo ("<a $EstiloMovil href='$PHP_SELF?menu=modificar&id=$id'><img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/editar.png' border='0' title='".Traductor::traducir("EDITAR PARTIDO")."' $EstiloTamanoImg></a>$FINBotones");
// LINEA COMENTADA POR DIAS DE LLUVIA
if($Partido_Libre || !$falta1dia){
echo ("<a $EstiloMovil href='$PHP_SELF?menu=confirmar&id=$id&id_jugadores=$id_Jugador1;$id_Jugador2;$id_Jugador3;$id_Jugador4''><img src='./images/images_j/borrar.png' ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')."  border='0' title='".Traductor::traducir("BORRAR PARTIDO")."' $EstiloTamanoImg></a>$FINBotones");
}

}

if ((!$Soy_el_organizador) && ($Partido_Abierto) && ($Partido_Libre) && (!$YO_Juego) && ($FaltaResultado) && $Mostraralgunboton)
{
}



if (    (!$Partido_Vacio) && ($YO_Juego) && ($Fecha>(date("Y-m-d", strtotime("$Hoy - 10 days")))) && (time()>=strtotime("$Fecha $Hora")) && $Mostraralgunboton)
{
	if (!$Resultados)
	{
		//CONTROL PARA SOLO PERMITIR RESULTADOS ENTRE LA FECHA_INI y la FECHA_FIN
		// y NO $Partido_Libre es decir partido completo 4 jugadores
		if ((!$Partido_Libre)&&(($Fecha<=devuelve_un_campo('liga',4,'id',$_SESSION['S_LIGA_ACTUAL'])) && ($Fecha>=devuelve_un_campo('liga',3,'id',$_SESSION['S_LIGA_ACTUAL']))) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']))
		{
      if 	($_SESSION['S_Version']!='Movil')
		    echo ("<a $EstiloMovil  href='$PHP_SELF?menu=resultado&id=$id'><img  src='./images/images_j/resultado.png'   border='0'title='".Traductor::traducir("GRABAR RESULTADO")."' $EstiloTamanoImg></a>$FINBotones");
		else
		    echo ("<a $EstiloMovil  href='$PHP_SELF?menu=resultado&id=$id'><img src='./images/images_j/resultado.png' border='0'title='".Traductor::traducir("GRABAR RESULTADO")."' $EstiloTamanoImg></a>$FINBotones");
		}
//echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
	}
	else
	{
		if ( $_SESSION['S_id_usuario']==$id_Jugador_ApuntaResult && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']))
		echo ("<a  $EstiloMovil href='$PHP_SELF?menu=resultado&id=$id'><img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/editar.gif' border='0' title='".Traductor::traducir("CORREGIR RESULTADO")."' $EstiloTamanoImg></a>");


		echo("$FINBotones");
	}
}

if (!($Faltan_menos_de_3_dias)&&($YO_Juego) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL'])&& $id_Jugador1!=$_SESSION['S_id_usuario']  && $Mostraralgunboton)
{

	if(mostrar_confirmacion_boxy())
	{
		include("alerts_android.php");

		echo ("<a onclick=\"return confirmacion('".Traductor::traducir("Cancelar participacion")."','".Traductor::traducir("¿Seguro que deseas cancelar tu participacion?")."',1,'partidos.php?menu=cancelar_participacion&id=".$id."');\"   $EstiloMovil href='$PHP_SELF?menu=cancelar_participacion&id=$id' ><img src='./images/images_j/borrarse.png' border='0' title='".Traductor::traducir("CANCELAR PARTICIPACION")."' $EstiloTamanoImg></a>$FINBotones");

	}
	else
	{
		echo ("<a $EstiloMovil href='$PHP_SELF?menu=cancelar_participacion&id=$id' onclick=\"return confirm('".Traductor::traducir("¿Seguro que deseas cancelar tu participacion?")."')\"><img src='./images/images_j/borrarse.png' border='0' title='".Traductor::traducir("CANCELAR PARTICIPACION")."' $EstiloTamanoImg></a>$FINBotones");

	}



//echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////NUEVO DE JORGE PARA CANCELACION DE PARTIDOS PREVIA CONFIRMACION DE LOS PARTICIPANTES
if (!$Mostraralgunboton) echo"<center>".Traductor::traducir("PARTIDO CANCELADO")."</center>";
//else echo"-$PartidoCancelandose-$Mostraralgunboton-$Faltan_menos_de_3_dias-$Soy_el_organizador";

if (($Faltan_menos_de_2_dias)&&($Soy_el_organizador)&& !($Partido_Libre) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL'])  && $Mostraralgunboton  && !$PartidoCancelandose && $_SESSION['S_TipoDeLiga']!='QUEDADA')
{
echo ("<a $EstiloMovil href='$PHP_SELF?menu=iniciar_proceso_cancelacion&id=$id&id_jugadores=$id_Jugador1;$id_Jugador2;$id_Jugador3;$id_Jugador4''>
         <img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/borrar.png' border='0' title='".Traductor::traducir("SOLICITAR CANCELACIÓN")."' $EstiloTamanoImg></a>$FINBotones");
//echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
}
////AHORA EL CANCELAR EL PROCESO DE CANCELACION
if (($Faltan_menos_de_2_dias)&&($Soy_el_organizador)&& !($Partido_Libre) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL'])  && $Mostraralgunboton  && $PartidoCancelandose && $_SESSION['S_TipoDeLiga']!='QUEDADA')
{
echo ("<a $EstiloMovil onclick=\"return confirm('".Traductor::traducir("OJO! Se cancelarán las confirmaciones recibidas, y el partido volverá a estar activo normalmente.")."');\" href='$PHP_SELF?menu=cancelar_proceso_cancelacion&id_partido=$id&id_jugadores=$id_Jugador1;$id_Jugador2;$id_Jugador3;$id_Jugador4''>
         <img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/borrar.png' border='0' title='".Traductor::traducir("SUSPENDER CANCELACIÓN")."' $EstiloTamanoImg></a>$FINBotones");
//echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
}



if ((($Faltan_menos_de_3_dias)&&($YO_Juego) || ($Faltan_menos_de_5_dias && $Soy_el_organizador && $_SESSION['S_TipoDeLiga']!='QUEDADA')) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL']) && $Mostraralgunboton  && !$PartidoCancelandose)
{
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
			echo ("<a $EstiloMovil onclick=\"return confirm('".Traductor::traducir("CANCELAR BUSQUEDA DE SUSTITUTO//Si aceptas cancelarás la busqueda de sustituto.")."');\" href=\"partidos.php?menu=CancelarBuscarSustituto&datos=$id,$YO\" title='".Traductor::traducir("CANCELAR BUSQUEDA")."'>" .
			"<img src='./images/images_j/boton_cancel_sustituto.png' border='0' width=16 title='".Traductor::traducir("CANCELAR BUSQUEDA")."' $EstiloTamanoImg></a>$FINBotones");

		}


	}
	else{

		if(mostrar_confirmacion_boxy())
		{
			include("alerts_android.php");

			$id_boton='buscar_sustituto_'.$id.$YO;

			echo ("<a id='$id_boton' $EstiloMovil onclick=\"return confirmacion('".Traductor::traducir("BUSCAR SUSTITUTO")."','".Traductor::traducir("RECUERDA//Recibirás un mensaje en tu móvil cuando te sustituyan/Si nadie te sustituye sigues comprometido a asistir al partido.//Al pulsar en ACEPTAR se generará un mensaje automático buscando sustituto")."',1,'partidos.php?menu=buscar_sustituto&id=".$id."','$id_boton');\"  href='$PHP_SELF?menu=buscar_sustituto&id=$id'>" .
			"<img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/boton_sustituto.png' border='0' title='".Traductor::traducir("BUSCAR SUSTITUTO")."' $EstiloTamanoImg></a>$FINBotones");



		}
		else
		{
			echo ("<a  $EstiloMovil onclick=\"return confirm('".Traductor::traducir("RECUERDA//Recibirás un mensaje en tu móvil cuando te sustituyan/Si nadie te sustituye sigues comprometido a asistir al partido.//Al pulsar en ACEPTAR se generará un mensaje automático buscando sustituto")."');\" href='$PHP_SELF?menu=buscar_sustituto&id=$id'>" .
			"<img ".($_SESSION['S_Version']!='Movil'?'width=\'20px\'':'')." src='./images/images_j/boton_sustituto.png' border='0' title='".Traductor::traducir("BUSCAR SUSTITUTO")."' $EstiloTamanoImg></a>$FINBotones");

		}


	}
//echo ("<a href='$PHP_SELF?menu=confirmar&id=$id'><img src='./images/borrar.gif' border='1' width='20' alt='BORRAR PARTIDO'></a>");
}

echo ("$FIN1");

if (($YO_Juego) && ($id_Liga==$_SESSION['S_LIGA_ACTUAL'])  && $Mostraralgunboton)
{



if 	($_SESSION['S_Version']!='Movil')	echo"<tr><td colspan=5>";
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

if 	($_SESSION['S_Version']!='Movil')
	{
	echo ("</td>");
	echo ("</tr>");
	}
	//else echo"</div>";
}
//else echo"</div>";
//echo "<font size=-2><3Dias:$PArtidoMENORDE3DIAS<br>ORGANIZO=$Soy_el_organizador<br><3Dias:$Faltan_menos_de_3_dias<br>Partido_Pasado:$Partido_Pasado<br>Abierto:$Partido_Abierto<br>Vacio:$Partido_Vacio<br>Libre:$Partido_Libre<br>YO:$YO_Juego<br>FaltaResul:$FaltaResultado</font>";
if 	($_SESSION['S_Version']!='Movil')
	{
	echo ("</table>");
	////////////fin de la tabla que contiene los botones de editar y borrar

	///////////fin de fila (del bucle)
	echo ("</td>");
	echo ("</tr>");
	}
else echo"</div></center></li></div>";
  }//fin del mostrar
else{
$nomuestra++;

}
if 	($_SESSION['S_Version']=='Movil') echo"</ul>";
 }//del WHILE
                 //echo "<br><br><br>";

//if 	($_SESSION['S_Version']=='Movil') echo "</div>";

          }//del if si
          else {
              /////////////no hay nada encontrado
              echo ("<tr><td>");
          echo "busqueda no realizada <br><br>";
          echo ("</td></tr>");
          }



////////////fin de la segunda tabla de listar
if(!isset($_GET['pg'])){

if 	($_SESSION['S_Version']!='Movil'){ echo ("</table><br>");

echo ("<!--<button onclick='loadmore()'>Cargar mas</button><br>-->");
if(!isset($_GET['filtrorapido']) && !isset($_GET['filtrolocalidad']) && !isset($_GET['filtrofecha']) && !$MiAgenda && !$Resultados){
echo "<center><button id='moreload' class='superboton' onclick='loadmore()'>Ver mï¿œs partidos</button><img id='loading' src='loading.gif' style='display:none'></center>";

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

var \$win   = \$(window);
    \$win.scroll(function () {
        if((\$win.height() + \$win.scrollTop()) >= \$(document).height()){
          loadmore();
        }
       
    });
</script>";

}
} else {echo"</ul>";
if(!isset($_GET['filtrorapido']) && !isset($_GET['filtrolocalidad']) && !isset($_GET['filtrofecha'])){
echo "<center><button id='moreload' class='superboton' onclick='loadmore()'>".Traductor::traducir("Ver más partidos")."</button><img id='loading' src='loading.gif' style='display:none'></center>";

echo"
<script>
function loadmore(){
pgn++;
if(pgn<totalpg){
\$('#loading').show(0);
\$.get('partidos.php?pg='+pgn, function(data) {
\$('ul:last').append(data);
\$('#loading').hide(0);

});

}else{
\$('#moreload').hide(0);
return false;
}
}
var \$win   = \$(window);

    \$win.scroll(function () {
        if((\$win.height() + \$win.scrollTop()) >= \$(document).height()){
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
//	echo"<br><br>$q";
	}
if 	($_SESSION['S_Version']!='Movil'){
if(!isset($_GET['pg'])){
echo "<span style='color:grey;font-size:10px'> ".Traductor::traducir("Mostrados").": ";
if(!isset($_GET['filtrorapido']) && !isset($_GET['filtrolocalidad']) && !isset($_GET['filtrofecha'])){
echo"$tpart";
}else{
echo"$ContadorDePartidosMostrador";
}

echo"#$nomuestra#";
echo " ".Traductor::traducir("Partidos")."</span>";
}}
};//////FIN DE LISTAR TABLA





////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////

function formulario ($op,$id)
{
	global $PAREJA;
	global $RESULTADO;
	$PHP_SELF = $_SERVER['PHP_SELF'];

    require_once "alerts_android.php";
	require_once "partidos_funciones.js.php";
	echo modalIframe("", Traductor::traducir("Reservar Pista"), false, false);


	if 	($_SESSION['S_Version']=='Movil')
				{
					$SaltodeLinea='<br>';
					$anchoMovil=" size='10' ";

					if ($op!="puntuacion")
					{
					 	$CURRENT="id='current'";


                         //JMAM: Determina el texto del menú de "Abrir Partido"

                        $Liga = new Liga($_SESSION["S_LIGA_ACTUAL"]);
                        $array_idsClubs = $Liga->obtenerIdsClubs();
                        $textoBoton_menuAbrirPartido = obtenerTextoBontonMenuSuperiorAbrirPartido();


                        if (Sesion::obtenerDeporte()->obtenerId() == Deporte::ID_padel){
                            echo"
							<ul id='navtabs' style='text-align:center'>
							   <li><a style='width:32%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li>
							   <li><a style='width:32%' href='./partidos.php' title='Partidos' $PARTIDOSCURRENT>".Traductor::traducir("Partidos")."</a></li>
							   <li><a style='width:32%' href='./partidos.php?menu=alta' title='Nuevo' $CURRENT>".$textoBoton_menuAbrirPartido."</a></li>
							</ul>";
                        }
                        else{
                            echo"
							<ul id='navtabs' style='text-align:center'>
							   <li><a style='width:48%' href='./partidos.php?menu=miagenda' title='Agenda' $AGENDACURRENT>".Traductor::traducir("Mi Agenda")."</a></li>
							   <li><a style='width:48%' href='./partidos.php?menu=alta' title='Nuevo' $CURRENT>".$textoBoton_menuAbrirPartido."</a></li>
							</ul>";
                        }

                        echo "
							<div id='contentwrap' class='contenedor_principal'>
						
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
///ADEMAS CONTROLO QUE LA FECHA ESTï¿œ EN EL RANGO
$Hoy=date('Y-m-d');  //jm 241016 donde pone -7 era -3 // 221216 vuelvo a poner -3
if (strtotime("$ant_Fecha")>(strtotime("$Hoy - 3 days"))) $Pasaronmenosde3Dias=1; else $Pasaronmenosde3Dias=0;

$JugadoresdelPartido= array ($ant_id_Jugador1,$ant_id_Jugador2,$ant_id_Jugador3,$ant_id_Jugador4);
if (((in_array ($_SESSION['S_id_usuario'],$JugadoresdelPartido))&&($Pasaronmenosde3Dias))||($op=='alta'))
{



if ($op!="puntuacion")
{
    echo("<div align='center'><form id='formulario_abrirPartido' style='max-width: 500px;' enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'><div class='filtrocontainer caja-partido-1 CP_cont' style='background:#eee;width: 65%; padding-right: 5px; padding-left: 3px; '><TABLE BORDER=0  CELLPADDING=0>");


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




//echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Hora:</b></TD>");
//echo ("<TD ALIGN=LEFT VALIGN=TOP><input class=textbox type='text' name='Hora' value='".htmlentities($ant_Hora)."' size='5' maxlength='5'></TD></tr>");
if 	($_SESSION['S_Version']!='Movil' ){
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



$Jugador = new Jugador($_SESSION['S_id_usuario']);
$Partido = new Partido($id);

//JMAM: Si el Partido está Libre, si falta más de 1 día o si es una Reserva de Pista, mostrar el botón borrar partido
    if ($Partido->esReservaPistaPartido()){
        include("alerts_android.php");
        $ReservaPista = $Partido->obtenerReservaPistaPartido();

        if ($Jugador->puedeCancelarLaReserva($ReservaPista) == false){
            //JMAM: Jugador no puede cancelar

            //$emailContactoClub = $Club = $Partido->obtenerCampo()->obtenerClub()->obtenerEmailContacto();
            $horasAntesCancelar = $Jugador->obtenerHorasAntesCancelar($ReservaPista);

            $div_impedirModificaciones = "<div onclick=\"return confirmacion('No puedes Modicar el Partido','No puedes modificar el Campo, Fecha, Hora, y Pista y Duración del partido con menos de $horasAntesCancelar horas de antelación, ponte en contacto con el administrador para solicitar la cancelación.',1,'avisame.php?TIPO=DESDEDENTRO&Version=Movil');\" style='height: 207px;width: 100%; position: absolute; left: 0px; background: #00000030; z-index: 1;'></div>";

        }
    }

echo"</table></div>$div_impedirModificaciones<div class='filtrocontainer CP_cont contenedor_selector_partidos' style=''><TABLE BORDER=0 WIDTH='auto' style='white-space:100%' CELLPADDING=0>";

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
  var sel = document.getElementById( 'id_campo' );
  //var textOculto1 = document.getElementById( 'textOculto1' );
  var textOculto2 = document.getElementById( 'textOculto2' );
  var textlocalidad = document.getElementById( 'textlocalidad' );
  var Localidad = document.getElementById( 'Localidad' );

  //JMAM: Comprueba si se ha seleccionado el campo OTRO (id = 0)
  if( sel.value == 0){
        
    console.log('Carga Opciones OTRO CAMPO');
    //textOculto1.style.display = '';
    textOculto2.style.display = 'block';
    textlocalidad.style.display = '';
    Localidad.style.display = 'block';
	document.getElementById( 'fila1ocul' ).style.display='';
	document.getElementById( 'fila2ocul' ).style.display='none';
	document.getElementById( 'fila4ocul' ).style.display='';
  }
  else
  {
    //textOculto1.style.display = 'none';
    textOculto2.style.display = 'none';
    textlocalidad.style.display = 'none';
	Localidad.style.display = 'none';
    document.getElementById( 'fila4ocul' ).style.display='none';
    ";

if($_SESSION['S_Version']=='Movil'){
	echo "
document.getElementById( 'fila1ocul' ).style.display='none';
document.getElementById( 'fila2ocul' ).style.display='none';
document.getElementById( 'fila4ocul' ).style.display='none';

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
	echo ("<tr>");
	}


//rellena_select ($tabla,$nombre,$campo,$valor,$ant_valor,$otro,$campo2='',$campo3='',$todos='',$order='',$disabled='')

        $where_camposPorDeporte = "";
        if (Sesion::obtenerDeporte()->obtenerId() != Deporte::ID_padel){
            $where_camposPorDeporte = "AND id IN (SELECT ".Pista::COLUMNA_idCampo." FROM ".Pista::NOMBRE_TABLA." WHERE ".Pista::COLUMNA_idDeporte."=".Sesion::obtenerDeporte()->obtenerId().")";
        }

        $q2="SELECT id, Nombre, Extra2, Convenio, Localidad, Foto_CAMPO, Extra2, (SELECT COUNT(*) FROM `partidos` AS pa WHERE pa.id_Campo = ca.id AND pa.id_liga = ".$_SESSION['S_LIGA_SELECCIONADA'].") AS numero_partidos FROM campos AS ca WHERE id IN (SELECT id_campo FROM camposporligas WHERE id_liga=".$_SESSION['S_LIGA_SELECCIONADA'].") $where_camposPorDeporte AND ".Campo::COLUMNA_mostrarEnFiltroSelectorCamposAbrirPartido."=1 ORDER BY Extra2 desc, Convenio desc, numero_partidos desc, Localidad asc, Nombre asc";
        //echo $q2;
        Log::v(__FUNCTION__, $q2, true);
	    $si2=mysql_query ($q2);
        $NumeroDeCampos= mysql_num_rows ($si2);


        //JMAM: Comprueba que el número de campos sea mayor a 0 para saber si es necesario mostrar el desplegable
            if ($NumeroDeCampos > 0){
            //JMAM: Número de campos mayor a 0

            //JMAM: Se muestra el desplegable


            ?>
                <td align="left" valign="top">

                    <div align="center">
                        <dl id="sample" class="dropdown">
                            <dt>
                                <?php

                                    if ($ant_id_Campo != "" && $ant_id_Campo != 0){

                                        $sql_datosCampo = "SELECT Nombre, Localidad FROM campos WHERE id=$ant_id_Campo";
                                        $resultados_datosCampo=mysql_query ($sql_datosCampo);

                                        //JMAM: Obtiene la localidad de la liga
                                        $localidad = mysql_result($resultados_datosCampo,0,"Localidad");
                                        //JMAM: Obtiene el nombre de la liga
                                        $campo = mysql_result($resultados_datosCampo,0,"Nombre");

                                         //JMAM: Compone el nombre de la liga que aparece en el selector
                                        if ($localidad != ""){
                                            //JMAM: La liga tiene una localidad
                                            $nombreCampo = $localidad." - ".$campo;
                                        }
                                        else{
                                            //JMAM: La liga no tiene localidad
                                            $nombreCampo = $campo;
                                        }

                                        $placeholder_selectCampo = $nombreCampo;
                                    }
                                    else if ($ant_id_Campo == 0 && $ant_Extra3 != ""){
                                        $placeholder_selectCampo = "OTROS";
                                    }
                                    else{

                                        $placeholder_selectCampo = Traductor::traducir("Selecciona una pista...");
                                    }

                                 ?>
                                <a href="#"><span class="selec_club_in"><?php echo $placeholder_selectCampo ?></span></a>
                            </dt>
                            <dd id="desplegable_selector_campo">
                                <ul>
                                <?php
                                    for ($i=0;$i<mysql_num_rows($si2);$i++){

                                        //JMAM: Obtiene el id del campo
                                        $id_campo = mysql_result($si2,$i,"id");

                                        $Campo = new Campo($id_campo);

                                        //JMAM: Comprueba si es el único campo que existe en la liga
                                        if (mysql_num_rows($si2) == 1){
                                            //JMAM: Es el único campo que existe

                                            //JMAM: Indica el id del campo seleccionado
                                            $ant_id_Campo = $id_campo;
                                        }

                                        //JMAM: Obtiene la localidad de la liga
                                        $localidad = mysql_result($si2,$i,"Localidad");

                                        //JMAM: Obtiene el nombre del campo
                                        $campo = mysql_result($si2,$i,"Nombre");

                                        //JMAM: Compone el nombre de la liga que aparece en el selector
                                        if ($localidad != ""){
                                            //JMAM: La liga tiene una localidad
                                            //$nombreCampo = $localidad." - ".$campo;

                                            $nombreCampo = $campo;
                                        }
                                        else{
                                            //JMAM: La liga no tiene localidad
                                            $nombreCampo = $campo;
                                        }


                                        //JMAM: Obtiene si la liga tiene convenio
                                        $convenio = mysql_result($si2,$i,"Convenio");

                                       $urlLogo = $Campo->obtenerImagen();




                                        //JMAM: Comprueba si la liga tiene convenio (liga con convenio se muestra con imagen)
                                        if ($convenio == "S"){
                                            //JMAM: Liga con convenio

                                            //JMAM: Se muestra la liga con imagen


                                            $Campo = new Campo($id_campo);
                                            $esPermitidoMostrarSelectorPistaFormatoTabla = $Campo->obtenerConfiguracionReservaPistas()->esPermitidoMostrarSelectorPistaFormatoTabla();

                                            if ($Campo->activadoModuloReserva()){
                                                $iconoSecundario = "icono_campo_moduloreservaactivado.png";
                                            }
                                            else{
                                                $iconoSecundario = "icono_telefono.png";
                                            }

                                            echo '
                                                <li>
                                                  <div class="mas_opciones_club_convenio" onclick=\'javascript:Boxy.load("fichacampos.php?id='.$id_campo.'", {title:  "'.$nombreCampo.'"});\'><img width="25px" src="./images/images_j/'.$iconoSecundario.'" border="0" title="'.$nombreCampo.'"></div>
                                                  <a href="#">
                                                        <div class="img_club_select"><img src="'.$urlLogo.'" width="225" height="225" alt=""/></div>
                                                        <span class="nombre_campo" id="nombreCampo">'.$nombreCampo.'</span>
                                                       <span>'.$campo.'</span>
                                                        <div class="localidad">'.$localidad.'</div>
                                                        <id class="id_campo" style="display: none;">'.$id_campo.'</id>
                                                        <esPermitidoMostrarSelectorPistaFormatoTabla  style="display: none;">'.$esPermitidoMostrarSelectorPistaFormatoTabla.'</esPermitidoMostrarSelectorPistaFormatoTabla>
                                                  </a>
                                                </li>
                                            ';

                                        }
                                        else{
                                            //JMAM: Liga sin convenio

                                            //JMAM: Se muestra la liga sin imagen


                                            echo '
                                                <li>
                                                  <a href="#">
                                                        <span class="clubsinlogo">'.$nombreCampo.'</span>
                                                        <id class="id_campo" style="display: none;">'.$id_campo.'</id>
                                                        <esPermitidoMostrarSelectorPistaFormatoTabla  style="display: none;">'.$esPermitidoMostrarSelectorPistaFormatoTabla.'</esPermitidoMostrarSelectorPistaFormatoTabla>

                                                  </a>
                                                </li>
                                            ';
                                        }

                                    }



                                    //JMAM: Obtiene el nombre de la liga actual
                                    $nombreLigaActual = devuelve_un_campo ('liga',1,'id',$_SESSION['S_LIGA_ACTUAL']);
                                    $tipoLiga = devuelve_un_campo("liga",29,"id",$_SESSION['S_LIGA_ACTUAL']);

                                    //JMAM: Comprueba si NO es una liga privada
                                    if ($tipoLiga != "PRIVADACONAVISO" && $tipoLiga != "PRIVADA" && $Liga->esPermitidoMostrarOtrosCampos()){
                                        //JMAM: NO es una liga privada

                                        //JMAM Muestra laopción de elegir otro campo
                                         echo '
                                            <!--JMAM: Selección campo OTROS -->
                                            <li>
                                                <a href="#">
                                                    <span class="clubsinlogo">'.Traductor::traducir("OTRO").'</span>
                                                     <id class="id_campo" style="display: none;">0</id>
                                                 </a>
                                             </li>
                                         ';
                                    }

                                ?>



                            </dd>
                        </dl>
                    </div>

                    <input type="hidden" id="id_campo" name="id_Campo" value="<?php echo $ant_id_Campo; ?>"/>

                    <?php

                    if ($ant_id_Campo > 0){
                        $Campo = new Campo($ant_id_Campo);
                        $esPermitidoMostrarSelectorPistaFormatoTabla = $Campo->obtenerConfiguracionReservaPistas()->esPermitidoMostrarSelectorPistaFormatoTabla();
                    }

                    ?>

                    <input type="hidden" id="esPermitidoMostrarSelectorPistaFormatoTabla" name="esPermitidoMostrarSelectorPistaFormatoTabla" value="<?php echo $esPermitidoMostrarSelectorPistaFormatoTabla;?>"/>


                 </td>

                <!--script selector clubs-->
                <script src="librerias/select_clubs/select_clubs.js<?php echo CacheJSyCSS::obtenerVersionJs();?>"></script>
            <?

            if ($NumeroDeCampos == 1){
                ?>

                <script type="text/javascript">

                    console.log("Sólo existe un campo, se selecciona el único campo disponible");
                    var campoSeleccionado = $("#desplegable_selector_campo ul li a").html();
                    $(".selec_club_in").html(campoSeleccionado);

                </script>

                <?php
            }
        }
        else{

        }





        /*
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

        //JMAM: Obtiene el nombre de la liga actual
        $nombreLigaActual = devuelve_un_campo ('liga',1,'id',$_SESSION['S_LIGA_ACTUAL']);

        //JMAM: Comprueba si NO es la liga "Skpadelkos"
        if (strpos($nombreLigaActual, "Skpadelkos") === false){
            //JMAM: NO la liga "Skpadelkos"

            //JMAM Muestra laopción de elegir otro campo
            echo"<OPTION VALUE='OTRO'>".Traductor::traducir("OTRO")."";
        }


if 	($_SESSION['S_Version']!='Movil')
	{
       echo ("</SELECT></td></TR>");
}else{
	 echo ("</SELECT></span></td></TR>");
}

        */
//echo "<tr><td colspan=2><div id='FORM_id_Campo_errorloc' class='innerError''></div></TD></TR>";
if 	($_SESSION['S_Version']!='Movil')
	{
echo "<tr><td colspan=2>";
echo '<table>';
echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g id=\"textOculto1\" style=\"display: none;\"><b>".Traductor::traducir("Lugar").":</b></TD>");
echo ("<TD ALIGN=LEFT VALIGN=TOP ><input class=textbox type='text' name='Otro_Campo' value='".htmlentities($ant_Otro_Campo)."' size='25' maxlength='25' id=\"textOculto2\" style=\"display: none;\">");

echo ("<div id='errorotrapista' class='innerError'></div></TD></tr>");


echo "
	<tr id='fila4ocul' style='display:none'>
		<td ALIGN=RIGHT VALIGN=TOP class=texttitular_g>
			<b>".Traductor::traducir("Localidad").":</b>
		</td>
		<td ALIGN=LEFT VALIGN=TOP>
			<input type='text' name='location' id='location' class='location textbox' value='' placeholder='Ejm: ".Traductor::traducir("Localidad")." ".Traductor::traducir("Pais")."' autocomplete='off'>
		</td>
	</tr>
";

echo ("<tr ><TD ALIGN=RIGHT VALIGN=TOP id='textlocalidad'  style=\"display: none;\" class=texttitular_g><b>".Traductor::traducir("Localidad").":</b></TD>");
echo ("<TD ALIGN=LEFT VALIGN=TOP>");//"<input class=textbox type='text' name='Provincia' id='Provincia' value='".htmlentities($ant_Provincia)."' size='75' maxlength='150'></TD></tr>");
echo"<div class='geo-details'><input  style='background-color: whitesmoke; text-align:left; display:none' type='text' data-geo='locality' value='$ant_Extra3' name='Localidad'  id='Localidad' class='location botonancho ancho200' readonly></div>";
echo "<TR><TD colspan='2;' style='color:red;font-weight:bold'><div id='FORM_Localidad_errorloc' class='innerError'></div></TD></TR>";
}else{
echo ("<tr id='fila1ocul' style='display:none'>");
echo ("<TD ALIGN=LEFT  ><input class='botonancho ancho200 input_otroCampo' type='text' name='Otro_Campo' value='".htmlentities($ant_Otro_Campo)."'  maxlength='25' id=\"textOculto2\" style=\"display: none;\" placeholder='".Traductor::traducir("Lugar")."'>");
echo ("</td></tr>");
echo ("<tr><td colspan=2; style='color:red;font-weight:bold'><div id='errorotrapista' class='innerError'></div></TD></tr>");

echo "
	<tr id='fila4ocul' style='display:none;'>
		<td ALIGN=LEFT>
			<input style='text-align:left; margin-bottom: 20px;' type='text' name='location' id='location' class='location botonancho ancho200 input_otroCampoLocalidad' value='$ant_Extra3' placeholder='".Traductor::traducir("Localidad")."' autocomplete='off'>
		</td>
	</tr>
";

}

 if 	($_SESSION['S_Version']=='Movil')
	{
 echo ("</div></td></tr>");
echo ("<tr><td colspan=2; style='color:red;font-weight:bold'>");
}
//"<input class=textbox type='text' name='Localidad' value='".htmlentities($ant_Localidad)."' size='75' maxlength='150'></TD></tr>");
if 	($_SESSION['S_Version']!='Movil'){
echo ("<tr><TD ALIGN=RIGHT VALIGN=TOP id='textlocalidad'  style=\"display: none;\" class=texttitular_g><b>".Traductor::traducir("Localidad").":</b></TD>");
echo ("<TD ALIGN=LEFT VALIGN=TOP>");
echo"<select name='Localidad' id='Localidad' size='1' style=\"display: none;\">";
}else{
echo ("<tr id='fila2ocul' style='display:none;'><TD ALIGN=RIGHT id='textlocalidad'  style=\"display: none;\" class=texttitular_g><b><label for='Localidad'>Loc.</label></b></TD>");
echo ("<TD ALIGN=LEFT >");
echo "<div style='display:inline-block;' class='geo-details'>";
echo"<input  style='background-color: whitesmoke; text-align:left' type='text' data-geo='locality' value='$ant_Extra3' name='Localidad'  id='Localidad' class='location botonancho ancho200' readonly>";
echo "<TR><TD colspan='2;' style='color:red;font-weight:bold'><div id='FORM_Localidad_errorloc' class='innerError'></div></TD></TR>";
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


		echo ("<tr>");
		echo ("<TD ALIGN=CENTERT VALIGN=TOP style='display:inline-flex; width:100%; padding-bottom:25px;'><input class='botonancho fechac' style='margin-right: 10px;width: 40%; text-align: center' $sololectura type='text' name='Fecha' id='fecha' value='".cambiaf_a_normal($ant_Fecha)."' " .
				"id='Fec1' size='15' maxlength='10' $EsPuntuacion onclick='onclick_selectorFecha();' onchange='onchange_selectorFecha()'>
<span class='botonancho' style='display:inline-block; text-align: center' id='contenedor_selectorHoras'>
<select id='Hora' name='Hora' class='botonancho-nofloat horas' style='display:inline;padding:0px;  padding-left: 0px !important; border:0px; width: 100% !important; text-align-last:center;'>
    ");

		if ($ant_Hora != ""){
		       echo "<option disabled selected value='$ant_Hora'>$ant_Hora</option>";
		}
		else{
		     echo "<option disabled selected value=''>hh:mm</option>";
		}
echo ("		
</select>		
</span>


</TD></tr>");
		echo "<tr><td colspan=2><div id='FORM_Fecha_errorloc' class='innerError'></div>";
			?>
			    <script type="text/javascript">
                    function cargacalendario(){$('.fechac').Zebra_DatePicker({
                        direction: true,
                        readonly_element:true
                        });
                        $('.Zebra_DatePicker_Icon').css({'display':'none'});
                        }
                </script>


			<?php
		/*echo ("	<script>function cargacalendario(){");
			if(!$hayotrojugador || true){
		echo ("$('.fechac').Zebra_DatePicker({
		direction: true,
		readonly_element:true"
		);
		}
		echo ("
		});
		$('.Zebra_DatePicker_Icon').css({'display':'none'});
		}

		 </script>";*/


		echo ("</TD></tr>");



}


//JMAM: Selector Módulo de Reservas

?>
<link rel="stylesheet" href="<?php echo WWWBASE;?>modulo_reservas/css/partidos_funciones.css<?php echo CacheJSyCSS::obtenerVersionCss();?>"/>
<script type="text/javascript" src="<?php echo WWWBASE;?>modulo_reservas/js/partidos_funciones.js<?php echo CacheJSyCSS::obtenerVersionJs();?>"></script>
<tr style="margin-bottom:25px" class="moduloReserva">
    <td>
        <div id="contenedor_selectorPistaYTiemposReserva" class="contenedor_selectorPistaYTiemposReserva">
          <div id="contenedor_selectorPistas" class="contenedor_selectorPistas"></div>
          <div id="contenedor_selectorTiemposReserva" class="contenedor_selectorTiemposReserva"></div>
        </div>
    </td>


</tr>

<?php


 if 	($_SESSION['S_Version']=='Movil')
	{
 echo ("</div></td></tr>");
echo ("<tr><td colspan=2; style='color:red;font-weight:bold'>");
}

echo '</td></tr>';
if 	($_SESSION['S_Version']!='Movil'){
echo '</table>';
}else{
	echo "</table></div><div class='filtrocontainer CP_cont CP_cont_100' id='contenedor_filtroAbrirPartido1' style='background:#eee;margin-bottom:10px'><TABLE BORDER=0 WIDTH='auto' style='width:100%' CELLPADDING=0>";
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
echo "<tr><td class=texttitular_g colspan=2 style='padding-left: 8px; padding-right: 9px;'>";
echo "<table style='display:none' width='100%'><tr><td align='center' width='33%'><label for='FiltroHCP'>".Traductor::traducir("Nivel")."</label><br><input type=checkbox name=FiltroHCP value=1 onclick=\"verifica2();\" id='FiltroHCP' ".checked($ant_nivel_min,$ant_nivel_max)." > ";
echo "</td><td align='center' width='33%'><label for='favoritos'>".Traductor::traducir("FAVORITOS")."</label><br>";
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

		<a  class="botonancho botonancho-nuevo-seleccion boton_selectorTipoPartido <?php if($ant_nivel_min) echo "optselected";?>" id="FiltroHCPbtn" onclick="javascript:seleccionacheck('FiltroHCP')"><?=Traductor::traducir("Nivel")?></a>
		<div class="grup-selector-separador"></div>



        <style type="text/css">
        .botonancho.optselected#favoritosbtn {
            color: #1469CD !important;
        }
        </style>

		<a  class="botonancho botonancho-nuevo-seleccion boton_selectorTipoPartido <?php if($ant_favoritos) echo "optselected";?>" id="favoritosbtn" onclick="javascript:seleccionacheck('favoritos')"><?=Traductor::traducir("FAVORITOS")?></a>
		 <div class="grup-selector-separador"></div>


		<a class="botonancho botonancho-nuevo-seleccion boton_selectorTipoPartido <?php if($ant_Extra2) echo "optselected";?>" id="sexobtn" onclick="javascript:seleccionacheck('sexo')"><?=$textsexo?></a>
		<div class="grup-selector-separador"></div>
    	<!--Nuevo: mixtos y parejas para moviles-->


	<div>

    <?php

	$mixtos_activos=devuelve_un_campo("liga",50,"id",$_SESSION['S_LIGA_ACTUAL']);

	//echo " id $ant_id mix $mixtos_activos";

	if($mixtos_activos!='NO'){


	?>

	<a  class="botonancho botonancho-nuevo-seleccion boton_selectorTipoPartido <?php if($ant_es_mixto) echo "optselected";?>" id="FiltroMixtobtn" onclick="javascript:seleccionacheck('FiltroMixto')">Mixto</a>
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
echo ("<tr style='text-align: center'><TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g id=text_nivel_min  style=\"display: none; \">");
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

 //JMAM: Muestra/oculta ranking, seg?n configuraci? de la liga
 $QUELIGA=$_SESSION['S_LIGA_ACTUAL'];
 $mostrar_ranking = devuelve_un_campo ('liga',83,'id',$QUELIGA);
 if ($mostrar_ranking == "0"){
    //JMAM: Oculta ranking

  if($MiHCP<=18)
    echo "
     <option value='18' ".igual('18',$ant_nivel_max)." >".Traductor::traducir("Básico");

   if($MiHCP<=15)
     echo "
     <option value='15' ".igual('15',$ant_nivel_max)." >".Traductor::traducir("Medio")."-";

   if($MiHCP<=12)
     echo "
     <option value='12' ".igual('12',$ant_nivel_max)." >".Traductor::traducir("Medio");

   if($MiHCP<=9)
     echo "
     <option value='09' ".igual('09',$ant_nivel_max)." >".Traductor::traducir("Medio")."+";

   if($MiHCP<=6)
     echo "
     <option value='06' ".igual('06',$ant_nivel_max)." >".Traductor::traducir("Radical");

   if($MiHCP<=3)
     echo "
     <option value='03' ".igual('03',$ant_nivel_max)." >".Traductor::traducir("Radical Pro");


 }
 else{

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


 }


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


  $mostrar_ranking = devuelve_un_campo ('liga',83,'id',$QUELIGA);
 if ($mostrar_ranking == "0"){
    //JMAM: Oculta ranking

  if($MiHCP>=1)
     echo "
     <option value='01'".igual('01',$ant_nivel_min)." selected>".Traductor::traducir("Radical Pro");

  if($MiHCP>=4)
     echo "
     <option value='04'".igual('04',$ant_nivel_min).">".Traductor::traducir("Radical");

  if($MiHCP>=7)
     echo "
     <option value='07'".igual('07',$ant_nivel_min).">".Traductor::traducir("Medio")."+";


  if($MiHCP>=10)
     echo "
     <option value='10'".igual('10',$ant_nivel_min).">".Traductor::traducir("Medio");

  if($MiHCP>=13)
     echo "
     <option value='13'".igual('13',$ant_nivel_min).">".Traductor::traducir("Medio")."-";

  if($MiHCP>=16)
     echo "
     <option value='16'".igual('16',$ant_nivel_min).">".Traductor::traducir("Básico");

 }
 else{
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
 }



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

   //Gestiï¿œn de la pareja a traves de auto relleno de jquery

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

<style type="text/css">
    ul.token-input-list{
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
</style>

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

<table width='100%' style="padding-right: 12px; padding-left: 6px;">
<tr>
<td align='center' style="width: 45% !important;">
<a <?php echo $disabled_mvl; ?> class="botonancho botonachatado opt-no-selected <?php if(!$ant_es_pareja) echo "optselected";?>" id="sorteobtn" onclick="javascript:seleccionacheckparejas(false)"><?=Traductor::traducir("Por sorteo")?></a>
</td>
<td width="5%"></td>
<td align='center' width="45%">
<a <?php echo $disabled_mvl; ?> class="botonancho botonachatado opt-no-selected <?php if($ant_es_pareja) echo "optselected";?>" id="conparejabtn" onclick="javascript:seleccionacheckparejas(true)"><?=Traductor::traducir("Con pareja")?></a>
</td>
 </tr>
</table>

<?php } ?>


<?php




   //Gestiï¿œn de la pareja a traves de auto relleno de jquery PARA MOVILES
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
   hintText: \"".Traductor::traducir("Elegir pareja. Recibirá un email de aviso")."\",
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
    document.getElementById("token-input-txtPareja").placeholder = "<?php echo Traductor::traducir("Buscar pareja...") ?>";

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

}//si es puntuaciï¿œn
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

    $Jugador1 = new Jugador($ant_id_Jugador1);
    $Jugador2 = new Jugador($ant_id_Jugador2);
    $Jugador3 = new Jugador($ant_id_Jugador3);
    $Jugador4 = new Jugador($ant_id_Jugador4);

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

    if ($_SESSION['S_id_usuario']!=$ant_id_Jugador1) echo"  <option value='$ant_id_Jugador1'>".$Jugador1->obtenerNombre();
    if ($_SESSION['S_id_usuario']!=$ant_id_Jugador2) echo"  <option value='$ant_id_Jugador2'>".$Jugador2->obtenerNombre();
    if ($_SESSION['S_id_usuario']!=$ant_id_Jugador3) echo"  <option value='$ant_id_Jugador3'>".$Jugador3->obtenerNombre();
 	if ($_SESSION['S_id_usuario']!=$ant_id_Jugador4) echo"  <option value='$ant_id_Jugador4'>".$Jugador4->obtenerNombre();

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
	echo("<TABLE BORDER=0 WIDTH='100%' CELLPADDING=5 style='background:white; padding-bottom: 20px'><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
	echo"<tr><td colspan=2><div style='text-align:center' class='caja-texto'>".Traductor::traducir("En primer lugar selecciona a TU PAREJA en el partido")."</div></td></tr>";

echo"<tr><td align=center colspan=2><div  class='caja-texto'><table border=0 >";
	echo"<tr>";
    echo"<td class='' colspan=2><p class='dato' style='font-size:1.1em' align='center'>".devuelve_un_campo ("campos",2,"id",$ant_id_Campo)."<br>$ant_TipoPuntuacion</p></td>";
	echo"<td width=90 class='' colspan=2><p class='dato' style='font-size:1.1em' align='center'><b>".cambiaf_a_normal($ant_Fecha)."<br>$ant_Hora</b></p></td>";

	echo"</tr><td colspan=4>&nbsp;</td><tr style='font-size:1.5em;line-height:0.6em'>";

	echo"<td class='botonancho-3 seleccion-pareja'><p class='dato' align='center' >".minificha_jugador($id,$ant_id_Jugador1,1,1,0,false)."<p></p></td>";
	echo"<td class='botonancho-3 seleccion-pareja'><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador2,1,1,0,false)."<p></p></td>";
	if ($ant_id_Jugador3) echo"	<td class='botonancho-3 seleccion-pareja'><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador3,1,1,0,false)."<p></p></td>";
	if ($ant_id_Jugador4) echo"<td class='botonancho-3 seleccion-pareja' style='border-right: none;'><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,false)."<p></p></td>";
    echo"</tr><tr><td colspan=4>&nbsp;</td></tr>";
    echo"<tr> <td class='' colspan=4 align=center><p class='dato' align='center'>".Traductor::traducir("Selecciona al jugador con el que has jugado de pareja")."<br></p>";
    echo "<span class='botonancho-3 selectGrabarResultados' style='display: inline-block;padding: 0px;'>";
    echo"<SELECT style='margin: 0px; border: 0px none; width: 100%' name=PAREJA class='botonancho'>";




    if ($_SESSION['S_id_usuario']!=$ant_id_Jugador1) echo"  <option value='$ant_id_Jugador1'>".$Jugador1->obtenerNombre();
    if ($_SESSION['S_id_usuario']!=$ant_id_Jugador2) echo"  <option value='$ant_id_Jugador2'>".$Jugador2->obtenerNombre();
    if ($_SESSION['S_id_usuario']!=$ant_id_Jugador3) echo"  <option value='$ant_id_Jugador3'>".$Jugador3->obtenerNombre();
 	if ($_SESSION['S_id_usuario']!=$ant_id_Jugador4) echo"  <option value='$ant_id_Jugador4'>".$Jugador4->obtenerNombre();

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

echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP style='padding: 0 20px;'><p style='text-align: center'> <input type='submit' class='superboton botonContinuarGrabarResultados' style=' width:50%' value='".Traductor::traducir("CONTINUAR")."'></p>");
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
echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP><p style='text-align: center;'> <input type='submit' class='botonContinuarGrabarResultados' value='".Traductor::traducir("CONTINUAR")."'></p>");

echo ("<input type=hidden name=menu value='resultado'>");
echo ("<input type=hidden name=PAREJA value='$PAREJA'>");
echo ("<input type=hidden name=id menu value='$id'>");
echo ("<input type=hidden name=ESRESULTADO value='1'>");

   // echo"</tr>";

//echo"</td></tr></table><br><br></td></tr>";
	}
	else ///////////////////VERSION MOVIL
		{
	echo("<TABLE BORDER=0 WIDTH='100%' CELLPADDING=5 style='background:white; padding-bottom: 20px'><form enctype='multipart/form-data' action='$PHP_SELF' method='post' id='FORM' name='FORM'>");
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
	echo"<td class='botonancho-3 seleccion-pareja' style='border-right: none' ><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,false)."<p></p></td>";
    echo"</tr><tr><td colspan=4>&nbsp;</td></tr>";
    echo"<tr> <td class='' style='font-size:1.6em;line-height:0.6em' colspan=4><p class='dato' align='center'>".Traductor::traducir("Has jugado de pareja con").":<br></p>";
    echo "".minificha_jugador($id,$PAREJA,1,1,0,false);
        echo"</td>";

    echo"</tr><tr><td colspan=4>&nbsp;</td></tr>";
    echo"<tr> <td class='filtrocontainer2'  colspan=4 align='center'><p class='dato' >".Traductor::traducir("El resultado obtenido ha sido").":<br></p>";
    //echo"<td class='fila' width=440 colspan=4>";
    echo"<span style='display:inline-block;padding:0px' class='botonancho-3 selectGrabarResultados'><SELECT style='margin:0; width: 100%;'  class='botonancho' name=RESULTADO>";
echo"  <option value='GANADORES'>".Traductor::traducir("GANADORES")."";
echo"  <option value='EMPATE'>".Traductor::traducir("EMPATE")."";
echo"  <option value='PERDEDORES'>".Traductor::traducir("PERDEDORES")."";

	echo"</SELECT></span>";
    echo"</td>";
    echo"</tr>";

    echo"</td></tr></table></div></td></tr>";
echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP style='padding: 0 20px;'><p style='text-align: center'> <input type='submit' class='superboton botonContinuarGrabarResultados' style='width:50%' value='".Traductor::traducir("CONTINUAR")."'></p>");
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
	echo"<td class='fila'  colspan=2><p class='dato' align='center'>Dï¿œa: <b>".cambiaf_a_normal($ant_Fecha)."</b> hora: <b> $ant_Hora</b></p></td></tr><td colspan=4>&nbsp;</td>";

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
	echo("<TABLE BORDER=0 WIDTH='100%' CELLPADDING=5 style='background: white; padding-bottom: 20px'><form id='formulario_guardarResultados' enctype='multipart/form-data' action='$PHP_SELF' method='post'>");
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
				echo"<td class='botonancho-3 seleccion-pareja' style='border-right: none' width=25% align=center><p class='dato' align='center'>".minificha_jugador($id,$ant_id_Jugador4,1,1,0,false)."<p></p></td>";
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
echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP><> <input type='submit' value='CONFIRMAR RESULTADO'></p></TD>");
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

if(puede_crear_amistosos() && LigaDentroDePeriodo()) //Comprobacion de si el usuario logado puede crear amistosos segï¿œn su nivel.
{


?>
<tr>
<tr>
<TD ALIGN=RIGHT VALIGN=TOP class=texttitular_g><b>Partido amistoso </b></TD>
<?
	/*
		nuevo jm 27-ago-15:
		ismael: veo que se puede cambiar la hora...., asÃ­ que habrÃ¡ que bloquear ese check el mismo dÃ­a del partido
		(no tras la hora de comienzo). Como el dÃ­a del partido ya estÃ¡ tambiÃ©n bloqueado (el mismo dÃ­a
		no se puede cambiar la fecha), pues queda resuelto asÃ­ las posibles trampas
	*/

	$hora_partido=strtotime(devuelve_un_campo ("partidos",3,"id",$id));
	$hora_actual=strtotime(date("H:i:s"));
	$fecha_partido=strtotime(devuelve_un_campo ("partidos",2,"id",$id));
	$fecha_actual=strtotime(date("Y-m-d"));

	//echo "$fecha_actual $fecha_partido $hora_actual $hora_partido";
	$locked='';
	if($fecha_actual>=$fecha_partido and $hora_actual>=$hora_partido and $fecha_partido!='' and $hora_partido!='')
	   $locked='disabled=disabled';


	echo "<td align=left valign=top> <input $locked type=checkbox name='FiltroAmistosos' $anchoMovil value=1 id='FiltroAmistosos'  ".($ant_es_amistoso?'checked=checked':'')." ><span style='font-size:1em;margin-left:10px;'>(cuenta para Clasificaci&oacute;n pero no para Rankings)</span></td>";
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
				echo ("</table></div><div class='filtrocontainer CP_cont' id='contenedor_filtroAbrirPartido2' style='background:#eee;margin-bottom:10px; margin-top: -10px'><TABLE BORDER=0 WIDTH='auto' style='width:100%' CELLPADDING=0>");


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

if(puede_crear_amistosos()  && LigaDentroDePeriodo()) //Comprobacion de si el usuario logado puede crear amistosos segï¿œn su nivel.
{


?>
<table width='100%' style="padding-left: 8px;padding-right: 9px;">
<tr>
<td width='20%' style="display: none">
<b class="texttitular_g"><?php echo Traductor::traducir("Tipo") ?></b>
<?

echo "<input type=checkbox style='display:none;' name='FiltroAmistosos' value=1 id='FiltroAmistosos'  ".($ant_es_amistoso?'checked=checked':'')." >";
?>
</td>
<td align='center' width="45%">
<a class="botonancho opt-no-selected <?php if(!$ant_es_amistoso) echo "optselected";?>" id="torneobtn" onclick="javascript:seleccionacheckamistosos(true)"><?php echo Traductor::traducir("Torneo") ?></a>
</td>
<td width="5%"></td>
<td align='center' width="45%">
<a class="botonancho opt-no-selected <?php if($ant_es_amistoso) echo "optselected";?>" id="amistosobtn" onclick="javascript:seleccionacheckamistosos(false)"><?php echo Traductor::traducir("Amistoso") ?></a>
</td>
 </tr>

<?
}//Fin de comprobacion de si puede crear amistosos Y SECCION DE AMISTOSOS PARA VERSION MOVIL
///////////////////////////////////



					echo ("<tr ><TD ALIGN=left colspan=4 style='width:100%' class=texttitular_g></TD></tr>");
					echo ("<tr ><TD colspan=5 ALIGN=LEFT VALIGN=TOP><textarea class='botonancho-100 CP_observaciones' type='text' name='Observaciones' placeholder='".Traductor::traducir("Escriba sus notas...")."' style=' maxlength='150'>".htmlentities($ant_Observaciones)."</textarea></TD></tr>");

					echo "</table>";
					echo '<div style="text-align:left;padding-top:10px;padding-left:15px;font-size:0.8em;">'.Traductor::traducir("Como organizador del partido, te responsabilizas de:").'</div>';
					echo "</div><TABLE BORDER=0 WIDTH='auto' style='width:100%;' CELLPADDING=0>";
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
		echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP colspan=2><p> <input class='botonGuardar' type='submit' value='".Traductor::traducir("GUARDAR RESULTADO")."'></p></TD>");
	}
else  echo ("<TR><TD ALIGN=center VALIGN=TOP colspan=0 style='padding:0 20px'><p><input style='width: 80%' type='submit' id='botonGuardar' class='superboton botonContinuarGrabarResultados' value='".Traductor::traducir("GUARDAR RESULTADO")."'></p>");


echo ("<input type=hidden name=menu value='modificarya'>");
echo ("<input type=hidden name=id menu value='$id'>");
echo ("<input type=hidden name=ESRESULTADO value='1'>");
//$_SESSION['S_LIGA_ACTUAL']
echo ("<input type=hidden name=id_Liga value='".$_SESSION['S_LIGA_ACTUAL']."'>");
//id_Jugador1 $_SESSION['S_usuario']
echo ("<input type=hidden name=Fecha value='".cambiaf_a_normal($ant_Fecha)."'>");
echo ("<input type=hidden name=Hora value='$Hora[0]:$Hora[1]'>");
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

cargarScriptsGlobales();
cargarEstilosGlobales();
Traductor::cargarTraduccionesJavaScript();
?>
    <script type="text/javascript">


        $(function () {
            $("#formulario_guardarResultados").on("submit", function (e) {
                console.log("formulario_guardarResultados onclick");
                Toast.info(Traductor.traducir("Grabando resultados..."));
                if (!e.isDefaultPrevented()) {
                    var botonGuardar = document.getElementById("botonGuardar");
                    $.ajax({
                        type: $(this).attr("method"),
                        url: $(this).attr("action"),
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            botonGuardar.disabled = true;
                        },
                        complete: function (data) {
                        },
                        success: function (data) {
                            Toast.success(Traductor.traducir("Resultados guardados")+".");
                            botonGuardar.disabled = false;
                            window.location.href = "<?php echo WWWBASE;?>jugadores.php?menu=resultados";
                        },
                        error: function (data) {
                            Toast.error(Traductor.traducir("Problemas al tratar de enviar el Formulario")+".");
                        }
                    });

                    return true;
                }
            });
        });
    </script>

<?php
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
".Traductor::traducir("Como organizador del partido, te responsabilizas de:")."
</td>
</tr>

</table>
</td></tr>
";

if ($op=='alta') $BotonFORM=Traductor::traducir("ALTA");
else $BotonFORM=Traductor::traducir("MODIFICAR");
if 	($_SESSION['S_Version']!='Movil')
	{
echo ("<TR><TD ALIGN=RIGHT VALIGN=TOP><p> <input type='submit' value='$BotonFORM'></p></TD>");
}else{


     if(!Juegalaliga::esJugadorActivoEnLiga($_SESSION['S_id_usuario'], $_SESSION['S_LIGA_ACTUAL']) || (Sesion::obtenerJugador()->esFinalizadaInscripcionJugadorEnLigaDelClub($Liga->obtenerId()) && !$Liga->esSuscripcion()))
     {
           $checkbox_marcarPartidoCompleto_onclick = "onclick_invitarApuntarseALiga(this);";
           $checkbox_marcarPartidoCompleto_checked = "checked";
     }
     else{
         $mensajeApunarseAPartido = Sesion::obtenerJugador()->puedeAccederPartido(Sesion::obtenerLiga()->obtenerId(), $ant_Fecha, false, false);

         if (!empty($mensajeApunarseAPartido) && Sesion::obtenerLiga()->esSuscripcion()){
             $checkbox_marcarPartidoCompleto_onclick = "onclick_mostrarModalPlanesSuscripcionAlDesmarcarPartidoCompleto(this, ".Sesion::obtenerLiga()->obtenerId().", \"$mensajeApunarseAPartido\")";
             $checkbox_marcarPartidoCompleto_checked = "checked";
         }
         else{
             $checkbox_marcarPartidoCompleto_onclick = "";
             $checkbox_marcarPartidoCompleto_checked = "";
         }
     }

 if ($op == "alta"){
     $texto_botonReservar = Traductor::traducir("RESERVAR");
 }
 else{
     $texto_botonReservar = Traductor::traducir("MODIFICAR");
 }

echo ("<TR class='moduloReserva' id='contenedor_filtroAbrirPartido3'><TD ALIGN=CENTER VALIGN=TOP colspan='2' class=''><input style='' type='checkbox' name='partidoCompleto' id='partidoCompleto' value='1' class='checkbox_partidoCompleto' $checkbox_marcarPartidoCompleto_checked onclick='$checkbox_marcarPartidoCompleto_onclick'><label>".Traductor::traducir("Marcar como Partido Completo")."</label>");
echo ("<TR class='moduloReserva'><TD ALIGN=CENTER VALIGN=TOP colspan='2' class='CP_botonalta botonReservarPista' ><input style=';' type='button' value='$texto_botonReservar' class='superboton superboton-alta' onclick='onclick_mostrarModalPagarPista();'>&nbsp;&nbsp;&nbsp;");
echo ("<TR class='noModuloReserva'><TD ALIGN=CENTER VALIGN=TOP colspan='2' class='CP_botonalta' ><div id='contenedor_botonAlta'><div id='contenedor_informacionAdicional' class='contenedor_informacionAdicional'></div><input id='boton_altaPartido' style='' type='submit' value='$BotonFORM' class='superboton superboton-alta'>&nbsp;&nbsp;&nbsp;</div>");
}
echo ("<input type=hidden name=menu value='$op"."ya"."'>");
echo ("<input type=hidden name=id menu id='idPartido' value='$id'>");
//$_SESSION['S_LIGA_ACTUAL']
echo ("<input type=hidden name=id_Liga id='idLiga' value='".$_SESSION['S_LIGA_ACTUAL']."'>");
//id_Jugador1 $_SESSION['S_usuario']
echo ("<input type=hidden name=id_Jugador1 id='idJugador1' value='".$_SESSION['S_id_usuario']."'>");
echo ("<input type=hidden name=id_Jugador2 value='$ant_id_Jugador2'>");
echo ("<input type=hidden name=id_Jugador3 value='$ant_id_Jugador3'>");
echo ("<input type=hidden name=id_Jugador4 value='$ant_id_Jugador4'>");

$Partido = new Partido($id);
Log::v(__FUNCTION__, "Id Partido: $id", true);
if ($Partido->existe()){
    $idReservaPista = $Partido->obtenerReservaPistaPartido()->obtenerId();
}
$idPista = $Partido->obtenerReservaPistaPartido()->obtenerPista()->obtenerId();
echo "<input type='hidden' name='idReservaPista_reserva' id='idReservaPista_reserva' value='$idReservaPista'/>";
echo "<input type='hidden' name='idPista_reserva' id='idPista_reserva' value='$idPista'/>";

?>


<input type="hidden" name="reservarPista" id="reservarPista" value=""/>
<input type="hidden" name="numeroJugadores" id="numeroJugadores" value=""/>
<input type="hidden" name="repartirImporte" id="repartirImporte" value=""/>
<input type="hidden" name="importeReserva" id="importeReserva" value=""/>
<input type="hidden" name="importePagar" id="importePagar" value=""/>
<input type="hidden" name="tipoPagoJugadorReserva" id="tipoPagoJugadorReserva" value=""/>
<input type="hidden" name="aplazarPago" id="aplazarPago" value=""/>
<input type="hidden" name="numeroPedido" id="numeroPedido" value=""/>
<input type="hidden" name="idTarjeta" id="idTarjeta" value=""/>
<?php
}

echo ("</form>");

if (Sesion::obtenerDeporte()->obtenerId() != Deporte::ID_padel){
    ?>
        <style type="text/css">
            #contenedor_filtroAbrirPartido1, #contenedor_filtroAbrirPartido2, #contenedor_filtroAbrirPartido3{
                display: none;
            }
        </style>
    <?php
}


$Fecha_INI=devuelve_un_campo('liga',3,'id',$_SESSION['S_LIGA_ACTUAL']);
$Fecha_FIN=devuelve_un_campo('liga',4,'id',$_SESSION['S_LIGA_ACTUAL']);
$Fecha_INI=cambiaf_a_normal($Fecha_INI);
$Fecha_FIN=cambiaf_a_normal($Fecha_FIN);
$NivelJugAc=devuelve_un_campo('jugadores',36,'id',$_SESSION['S_id_usuario']);



$eSoloUnCampoConModuloActivado = esUnUnicoCampoEnElSelctorAbrirPartidoConModuloReservaActivado();

if ($eSoloUnCampoConModuloActivado && $esPermitidoMostrarSelectorPistaFormatoTabla && $op=='alta'){
        $abrirSelectorPistaSiProcede = "onclick_selectorHora()";
    }

?>

<script type="text/javascript">

$(function() {
    <?php echo $abrirSelectorPistaSiProcede;?>
});


function compruebaPista(){

if (document.getElementById("id_campo").value == ""){
    document.getElementById("desplegable_selector_campo").style.display = "block";
    return false;
}

//JMAM: Se ha seleccionado el Campo Otros
if(document.getElementById('id_campo').value=='0'){
if(document.getElementById('textOculto2').value==''){
document.getElementById('errorotrapista').innerHTML='<?=Traductor::traducir("Debes indicar el nombre del campo para el partido")?>';
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



 frmvalidator.addValidation('id_campo','dontselect=selecc','<?=Traductor::traducir("Debes seleccionar un campo para el partido")?>');
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





////BOTON
echo ("<a href='partidos.php?menu=miagenda'> <p style='text-align: center'><input type='button' class='superboton superboton-cancelar botonCancelarGrabarResultados' value='".Traductor::traducir("CANCELAR")."'></p></a>");


echo ("</TR></table><br></div>");
}
else
{
	echo "<br><br><h2>".Traductor::traducir("No tienes permisos para ver este partido")."</h2>";
}


?>
<style type="text/css">
    .NW_bg_footer{
        display: none;
    }
</style>
<?php


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

         $Jugador = new Jugador($_SESSION['S_id_usuario']);
         Log::v(__FUNCTION__, "Nivel Jugador: ".$Jugador->obtenerNivelInicial(), true);
         if($Jugador->esPerfilJugadorIncompleto()){
             header("Location: jugadormodificadatoselmismo.php");
             return;
         }


     mini_cabecera_html(0,'',true);
     echo"<SCRIPT src='gen_validatorv4.js'></SCRIPT>";
     Traductor::cargarTraduccionesJavaScript();
     ?>
     <?php

     /*
     cargarScriptsGlobales();
     cargarEstilosGlobales();
     */


		Mini_TITULO_CSS ('box1',Traductor::traducir("Crear un"),Traductor::traducir("Partido"),'','SI');//    TITULO_CSS_FIN ();

    	//TITULO_CSS_FIN ();

    	//JMAM: Obtiene el tipo de liga
		$tipoLiga = devuelve_un_campo('liga', 29, 'id', $_SESSION['S_LIGA_ACTUAL']);


        //echo "TIPO LIGA: $tipoLiga  ADMINISTRADOR:".$_SESSION['es_administrador'];
		//JMAM: Comprueba si es torneo
		if ($tipoLiga == "QUEDADA" && !$_SESSION['es_administrador']) {

		//JMAM: No tiene permisos, se le muestra un mensaje de advertencia y se interrumpe el proceso
		echo "
			</br>
			</br>
				<p align='center' style='font-weight: bold;'>Est&aacute;s en un TORNEO. S&oacute;lo el Club puede crear o modificar partidas y grabar o corregir resultados.</p>
			</br>
			</br>
			";
			breaK;
		}
		else{
			formulario (alta,"null");
		}
		PieMovil (1);
     break;





     case 'altaya':

         //echo "ID PISTA: $idPista";
          ?>
         <link rel="stylesheet" type="text/css" href="./estilos_j.css<?php echo CacheJSyCSS::obtenerVersionCss();?>">
            <?php
            $Jugador = new Jugador($_SESSION["S_id_usuario"]);
            if ($Jugador->esModoOscuroActivado()){
                echo "<link rel='stylesheet' type='text/css' href='./estilos_modo_oscuro.css".CacheJSyCSS::obtenerVersionCSS()."'>";
            }
            ?>
        <?php

	 		if(!jugador_activo_liga($id_Jugador1,$_SESSION['S_LIGA_ACTUAL']) && $reservarPista != 1 && Sesion::obtenerJugador()->obtenerRolJugadorEnLiga(Sesion::obtenerLiga()->obtenerId()) != Juegalaliga::ROL_6)
			{
                VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_noTienesPermisosParaCrearPartidos);
                die();
			}

	 		if($_SESSION['S_ROL']==4 && $reservarPista != 1){
                 VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_noTienesPermisosParaCrearPartidos);
                 die();
			}

             $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
             if(Sesion::obtenerJugador()->esFinalizadaInscripcionJugadorEnLigaDelClub($Liga->obtenerId()) && !$Liga->esSuscripcion() && $reservarPista != 1){
			 mini_cabecera_html(0,'',true);
  				Mini_TITULO_CSS ('box1',Traductor::traducir("Crear un"),Traductor::traducir("Partido"),'','SI');//
                echo ('<center>'.Traductor::traducir("El Club ha indicado que ha finalizado tu Inscripción, contacta con el club para más información").'<br><br><a class="superboton boton_principal" target="_parent" href="apuntarse.php?menu=confirmardatosFORM&id_LIGA=' . $_SESSION['S_LIGA_ACTUAL'] . '">'.Traductor::traducir("Aquí").'</a></center>');
			die();
			}

             if ($Liga->tieneAlgunPackContratadoDisponibleTotalesEnLaLiga() == false && $Liga->esSuscripcion() == false){
                mini_cabecera_html(0,'',true);
  				Mini_TITULO_CSS ('box1',Traductor::traducir("Crear un"),Traductor::traducir("Partido"),'','SI');//
  				echo "<br/>";
                echo ('<center>'.Traductor::traducir("La opción de abrir partido no está operativa temporalmente").'.<br><br><a class="superboton boton_principal" target="_parent" href="'.WWWBASE.'avisame.php?TIPO=DESDEDENTRO&Version=Movil">'.Traductor::traducir("Contactar con el Club").'</a></center>');
                return;
             }

	 		$Jugador = new Jugador($_SESSION["S_id_usuario"]);



	 		if ($Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], cambiaf_a_mysql($Fecha), false, $partidoCompleto) != ""){

	 		    $respuesta = $Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], cambiaf_a_mysql($Fecha), false, $partidoCompleto);

	 		    mini_cabecera_html(0,'',true);
  				Mini_TITULO_CSS ('box1',Traductor::traducir("Crear un"),Traductor::traducir("Partido"),'','SI');

  				echo "
  				    <style type='text/css'>
  				        .contenedor_mensaje_error_crear_partido{
  				            text-align: center;
  				            margin: 10px;
  				        }
  				        
  				        .contenedor_mensaje_error_crear_partido .boton_ver_ventajas{
  				            border: 1px solid #0f4585;
                            color: #fff;
                            background: #1469CD;
                            padding: 8px 23px;
                            border-radius: 50px;
                            cursor: pointer;
  				        }
  				        .contenedor_mensaje_error_crear_partido .boton_ver_ventajas:hover{
  				            background: white;
                            color: #1469CD;
  				        }
  				        
  				        .bloqueado_partidos_gratis{
  				            background-color: red;
  				            color:white;
  				            padding: 10px;
  				            text-align: center;
  				        }
  				        
  				        .jugador_sin_suscripcion{
  				            background-color: red;
  				            color:white;
  				            padding: 10px;
  				            text-align: center;
  				        }
  				        
  				    </style>
  				";

  				modalIframe('',Traductor::traducir("Ver Ventajas"), false, true);

                //echo "Respuesta: $respuesta";
  				switch ($respuesta){

  				    case "JUGADOR_NO_TIENE_SUSCRIPCION":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_jugadorNoTieneSuscripcion);

                          /*
  				         echo "<div class='jugador_sin_suscripcion'>".Traductor::traducir("¡Esta liga es sólo para Jugadores con Suscripción!, elige un Plan para Continuar")."</div>
                            <script type='text/javascript'>
                                mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\");
                            </script>
                        ";*/
                        die();
  				        break;

  				    case "BLOQUEADO_GRATIS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_bloqueadoGratis);

                          /*
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $search = array("%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
                        $replace = array($Plan1["nombre"], $Plan3["nombre"]);
                        $respuesta = str_replace($search,$replace,Traductor::traducir("Tu usuario no puede jugar partidos gratuitos en Rádical Pádel. Dicha limitación..."));
  				        echo "<div class='bloqueado_partidos_gratis'>".$respuesta."</div>
                            <script type='text/javascript'>
                                mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."&mostrarBloqueadoGratis=1\");
                            </script>
                        ";
                          */
                        die();
  				        break;


  				    case "LIGA_NO_PERMITE_CREAR_PARTIDOS_GRATIS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_ligaNoPermiteCrearPartidosGratis);

                          /*
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $search = array("%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
                        $replace = array($Plan1["nombre"], $Plan3["nombre"]);
                        $respuesta = str_replace($search,$replace,Traductor::traducir("Tu suscripción GRATUITA NO te permite crear partidos en esta liga..."));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;


  				     case "NUMERO_PARTIDOS_EXCEDIDOS_POR_MES_GLOBALMENTE":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidosPorMesGlobalmente);

                           /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array($Liga->obtenerPartidosMensualesPlanGratis(), $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("Tu Suscripción GRATUITA permite apuntarte hasta %NUMERO_PARTIDOS_MENSUALES_LIGA% partidos al mes (uno cada %DIAS_DIFERENCIA_ENTRE_PARTIDOS% días), pero no crearlos"));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                           */
                        die();
  				        break;

  				    case "NUMERO_PARTIDOS_EXCEDIDOS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidos);

                          /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array($Liga->obtenerPartidosMensualesPlanGratis(), $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("Tu Suscripción GRATUITA permite apuntarte hasta %NUMERO_PARTIDOS_MENSUALES_LIGA% partidos al mes (uno cada %DIAS_DIFERENCIA_ENTRE_PARTIDOS% días), pero no crearlos"));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;

  				    case "EXCEDIDO_PARTIDOS_ENTRE_FECHAS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidosEntreFechas);

                          /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array($Liga->obtenerPartidosMensualesPlanGratis(), $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("Tu Suscripción GRATUITA permite apuntarte hasta %NUMERO_PARTIDOS_MENSUALES_LIGA% partidos al mes (uno cada %DIAS_DIFERENCIA_ENTRE_PARTIDOS% días), pero no crearlos"));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;
  				}




	 		}

          //$Hora=$Hora.":".$Minutos;
          if ($FiltroHCP!=1) {$nivel_min=0;$nivel_max=0;}
		 if($limsexo==1){
			$Extra2 = $_SESSION['sexo'];
			}
		  //Nuevo: Parejas
          $id_creador='';
          $id_invitado='';
          $Parejas='';

          if($FiltroParejas){
              $Parejas='S';
              $id_creador=$id_Jugador1;
              $id_invitado=$txtPareja;
              //echo "<script>alert('con pareja. Invitado:$id_invitado, creador $id_creador');</script>";
          }else{
              $Parejas='N';
              $id_creador='';
              $id_invitado='';
              //echo "<script>alert('sin pareja');</script>";
           }

           //Nuevo: partidos mixtos
           $es_mixto='N';
           if($FiltroMixto)
              $es_mixto='S';

			//Nuevo: partidos amistosos
			$es_amistoso='N';
			if($FiltroAmistosos)
              $es_amistoso='S';

           $OK=alta_partidos ($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,'0','0','0','0',$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Localidad,$Parejas,$id_creador,$id_invitado,$es_mixto,$es_amistoso, $Localidad, $reservarPista, $idPista, $idTiempoReserva, $partidoCompleto, $numeroJugadores, $repartirImporte, $importeReserva, $aplazarPago, $tipoPagoJugadorReserva, $numeroPedido, $idTarjeta);
           //echo "<center>ALTA REALIZADA<br><br><a href='$php_self?menu=listar'><font color='black'>LISTAR partidas</font></a></center>";


          if ($OK === "ERROR_AL_COMPROBAR_INTEGRIDAD_RESERVA"){
             mini_cabecera_html();
          	    Mini_TITULO_CSS ('box1','CREAR ','PARTIDO','','SI');

	            echo "<div class='filtrocontainer2'>";
          	    echo "<div style='background: red; color: white; padding: 10px; margin: 5px; font-weight: bolder'><center>".Traductor::traducir("No se ha creado la Reserva, comprueba disponibilidad y vuelve a intentarlo.")."</center></div>";
                echo "<center><a class='botonancho-3 CP_listarpartidos' href='partidos.php?menu=listar'><font>".Traductor::traducir("LISTAR PARTIDOS")."</font></a></center>";
	            echo "</div>";
          }
          else if($OK){

		  mini_cabecera_html();
		   Mini_TITULO_CSS ('box1','CREAR ','PARTIDO','','SI');//    TITULO_CSS_FIN ();
if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='caja-texto'>";
}
		  echo "<br><br>";

            global $id_partido_creado;
			$titulo_push=Traductor::traducir("Nuevo partido. Apúntate");
	        $fecha_p=$FechaSQL;
	        $hora_p=$Hora;
	        $nivel_min_p=$nivel_min;
	        $nivel_max_p=$nivel_max;


		  $date = new DateTime($fecha_p);

		  $dia_p=$date->format('d');
		  $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
		  $dia_sem = $dias[date('N', strtotime($fecha_p))];
		  //$dia_sem=strftime('%A',strtotime($fecha_p));

		  $idp_usar= $id_partido_creado;

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
			$urlCompartirWhatsapp = "*$titulo_push*\\r\\n$mensaje_push\\r\\n\\r\\n$urlCompartirPartido";

            $Liga = new Liga($_SESSION["S_LIGA_ACTUAL"]);


            $Partido = new Partido($id_partido_creado);
            $enlaceCompartirPartidoWhatsapp = $Partido->obtenerUrlEnlaceCompartirPorWhatsApp();

            echo "
                <script type='text/javascript'>
			 	 	function compartirPartidoWhatsapp(){
			 	 		var url = '$enlaceCompartirPartidoWhatsapp';
			 	 		//url = window.encodeURIComponent(url);
			 	 		
			 	 		if (typeof Android !== 'undefined'){
			 	 			window.open(url,'_blank');
			 	 		}
			 	 		else{
			 	 			window.open(url,'_blank');
			 	 		
			 	 		}
					 	
				   }
		  		 </script>
            
            ";



            if($Liga->esSuscripcion()){

                //JMAM: Comprueba si el Jugador adquiere bonificación por número de partidos

                ?>

                <div class="NW_BG_white">
                    <div class="NW_NW_altarealizada_cont">
                        <h2 class="NW_altarealizada">
                            <?php echo Traductor::traducir("ALTA REALIZADA"); ?>
                        </h2>
                        <?php
                        if ($Partido->esPartidoPreReservaDePista()){

                            $fechaPuedeReservarPista = formatearFecha($Partido->obtenerFechaMYSQLQueSePuedeReservarPista());

                            $texto = Traductor::traducir("Partido creado sin reserva de pista.<br/>Recibirás un aviso para efectuar la reserva a partir del<br/>%FECHA_SE_PUEDE_HACER_RESERVA%");
                            $texto = str_replace("%FECHA_SE_PUEDE_HACER_RESERVA%", $fechaPuedeReservarPista, $texto);

                            echo "<br/><div style='padding: 15px;;font-weight: bold; color: red; border: solid 2px red;'>".$texto."</div>";
                        }

                          $FechaSQL=cambiaf_a_mysql($Fecha);
                          if(Tiene_Partido($FechaSQL,$id_Jugador1)>1)
                             echo "<br><br><font color='red'>".Traductor::traducir("Recuerda que este día tienes más de un partido para jugar")."</font><br><br>";
                          ///Fin nuevo

                          if($es_amistoso=='S')
                             echo "<center><span style='padding-top:15px;font-size:0.9em;font-weight:bold; display:none'>AMISTOSO: Contabiliza para Clasificaci&oacute;n pero no para Ranking.</span></center>";

                          if($Parejas=='S')
                             echo Traductor::traducir("CON PAREJA: El partido solo sera visible para ti y tu pareja, hasta que ésta se inscriba en el partido...");



                          if ($OK>0){
                            echo "<br><br>";
                                if($OK==1){
                                     echo Traductor::traducir("Recordatorios desactivados");
                                     ?>
                                     <button class='superboton' style="margin-left:10px" onclick="document.location='recordatorios.php'"><?=Traductor::traducir("ACTIVAR")?></button>
                                     <?
                                }else{

                                    $textoRecordatorio = "<strong>".Traductor::traducir("Recordatorio Creado").' '.date('d/m/y',$OK).' '.Traductor::traducir("a las").' '.date('H:i',$OK).' ';
                                    $datr =mysql_fetch_array(mysql_query("SELECT * FROM recordatorios WHERE id_jugador=".$_SESSION['S_id_usuario'].' AND tiempo='.$OK.' ORDER BY id DESC'));
                                    ?>

                                     <div class="NW_text_alta">
                                        <?php echo $textoRecordatorio; ?>
                                        <br>
                                        <a onclick="document.location='recordatorios.php?idr=<?=$datr['id']?>'"><?php echo Traductor::traducir("MODIFICAR");?></a>
                                    </div>

                                    <?php
                                 }

                          }

                          $Jugador = new Jugador($_SESSION["S_id_usuario"]);
                          $enlaceRecomendacionWhatsApp = $Jugador->obtenerEnlaceRecomendacionWhatsApp($_SESSION["S_LIGA_ACTUAL"]);
                         ?>

                        <div class="NW_invitaamigos">
                            <h3><?php echo Traductor::traducir("INVITA A TUS AMIGOS"); ?></h3><br>

                            <div class="NW_enlacescompartir">
                                <a href="<?php echo $Partido->obtenerUrlEnlaceCompartirPorWhatsApp();?>" target="_blank">
                                    <?php echo Traductor::traducir("Para que se apunten al partido (Si ya están inscritos en Radical Padel)");?>
                                </a>

                                <a href="<?php echo $enlaceRecomendacionWhatsApp; ?>">
                                   <?php echo Traductor::traducir("Para que se inscriban y se apunten GRATIS (Enviará tu enlace de recomendación y obtendrás bonificaciones)");?>
                                </a>
                            </div>

                            <?php

                            if ($tipoPagoJugadorReserva == ReservaPista::TIPOPAGOJUGADORRESERVA_TPV){

                                          ReservaPista::generarFormularioInvisiblePagoReservaPista($id_Jugador1,$idTarjeta,$importePagar,$numeroPedido);

                                          ?>
                                                <div style="color:red; text-decoration:underline; font-weight: bold; margin:25px">Realiza el Pago para Confirmar la Reserva de la Pista</div>
                                                <script type="text/javascript">

                                                    onclick_realizarPagoReserva();

                                                    function onclick_realizarPagoReserva(){
                                                        document.getElementById("formulario_realizarPago").submit();
                                                    }

                                                </script>
                                          <?php
                                      }
                            ?>

                            <a href='partidos.php?menu=miagenda' class="NW_listarpartidos"><?php echo Traductor::traducir("Ir a mi Agenda"); ?></a>

                        </div>

                    </div>
                </div>

                <?php
            }
            else{


                echo "<center><span style='font-size:1.2;font-weight:bold;' class='CP_titulo_alta'>".Traductor::traducir("ALTA REALIZADA")."</span><br>";

                if ($Partido->esPartidoPreReservaDePista()){

                    $fechaPuedeReservarPista = formatearFecha($Partido->obtenerFechaMYSQLQueSePuedeReservarPista());

                    $texto = Traductor::traducir("Partido creado sin reserva de pista.<br/>Recibirás un aviso para efectuar la reserva a partir del<br/>%FECHA_SE_PUEDE_HACER_RESERVA%");
                    $texto = str_replace("%FECHA_SE_PUEDE_HACER_RESERVA%", $fechaPuedeReservarPista, $texto);

                    echo "<br/><div style='padding: 15px;;font-weight: bold; color: red; border: solid 2px red;'>".$texto."</div>";
                }

		  //Nuevo: complemento el mensaje si tiene mas partidos ese dia
          $FechaSQL=cambiaf_a_mysql($Fecha);
		  if(Tiene_Partido($FechaSQL,$id_Jugador1)>1)
             echo "<br><br><font color='red'>".Traductor::traducir("Recuerda que este día tienes más de un partido para jugar")."</font><br><br>";
          ///Fin nuevo

          if($es_amistoso=='S')
             echo "<center><span style='padding-top:15px;font-size:0.9em;font-weight:bold; display:none'>AMISTOSO: Contabiliza para Clasificaci&oacute;n pero no para Ranking.</span></center>";

		  if($Parejas=='S')
             echo Traductor::traducir("CON PAREJA:El partido solo sera visible para ti y tu pareja, hasta que ésta se inscriba en el partido...");

		  echo "";
		  if ($OK>0){
		  echo "<br><br>";
				 if($OK==1){
				 echo Traductor::traducir("Recordatorios desactivados");
				 ?>
				 <button class='superboton' style="margin-left:10px" onclick="document.location='recordatorios.php'"><?=Traductor::traducir("ACTIVAR")?></button>
				 <?
				 }else{
				 echo "<strong>".Traductor::traducir("Recordatorio Creado")."</strong><br/>".Traductor::traducir("Se enviará el día").' '.date('d/m/y',$OK).' '.Traductor::traducir("a las").' '.date('H:i',$OK).' ';
				 $datr =mysql_fetch_array(mysql_query("SELECT * FROM recordatorios WHERE id_jugador=".$_SESSION['S_id_usuario'].' AND tiempo='.$OK.' ORDER BY id DESC'));
				 ?><br>
				 <button class='superboton' style="margin-left:10px" onclick="document.location='recordatorios.php?idr=<?=$datr['id']?>'"><?=Traductor::traducir("MODIFICAR")?></button>
				 <?
				 }

          }

		  echo "<br><br>";

		  if ($_SESSION['S_Version']=='Movil'){
              $urlCompartirWhatsapp = $Partido->obtenerUrlEnlaceCompartirPorWhatsApp();
			//JMAM: Compartir partido en Whatsapp
		  	echo "
		  		 <a href='$urlCompartirWhatsapp' target='_blank'>
		  		 <div class='compartir_whatsapp'>
		  			<div>".Traductor::traducir("AVISAR EN MI GRUPO DE AMIGOS PARA QUE SE APUNTEN AL PARTIDO")."</div>
		  		  </div>
		  		 </a>
		  		 
		  		 
		  		 
				 <style>
				 	.compartir_whatsapp{
				 		text-align: center;
						margin: 20px;
						padding: 20px;
						background: #50b154;
						color: white;
						padding-top: 15px;
						padding-bottom: 10px;
						border-radius: 10px;
						 -webkit-transition: all 0.2s ease;
					    -moz-transition: all 0.2s ease;
					    -o-transition: all 0.2s ease;
					    -ms-transition: all 0.2s ease;
					    transition: all 0.2s ease;
				 	}
				 	
				 	.compartir_whatsapp:hover{
				 		background: #39773c;
				 	}
				 	
				 	.compartir_whatsapp div{
				 		    font-weight: bold;
                            background: url('../images/ico_whatsapp.png');
                            background-position: left;
                            background-repeat: no-repeat;
                            background-size: 28px 28px;
                            padding-left: 35px;
                            min-height: 25px;
                            padding-top: 5px;
				 	}
				 	
				 	.compartir_whatsapp img{
				 		margin-top: 10px;	
				 	}
				 </style>
		  		";
		  	}


		  if ($tipoPagoJugadorReserva == ReservaPista::TIPOPAGOJUGADORRESERVA_TPV){

		      echo ReservaPista::generarFormularioInvisiblePagoReservaPista($id_Jugador1,$idTarjeta,$importeReserva,$numeroPedido);

		      ?>
		            <div style="color:red; text-decoration:underline; font-weight: bold">Realiza el Pago para Confirmar la Reserva de la Pista</div>
                    <script type="text/javascript">

                        onclick_realizarPagoReserva();

                        function onclick_realizarPagoReserva(){
                            document.getElementById("formulario_realizarPago").submit();
                        }

                    </script>
              <?php
		  }


		  echo"<a class='botonancho-3 CP_listarpartidos' href='partidos.php?menu=miagenda'>".Traductor::traducir("Ir a Mi Agenda")."</font></a></center>";

            }


		  if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
          global $id_partido_creado;


		  if($Parejas!='S'){

		      //JMAM: Envío de push en segundo plano
		      $cron = "false";
		      $id_partido = "$id_partido_creado";
		      $orden = system("php5.6 gestion_aviso_partido_abierto_system.php $cron $id_partido >>gestion_aviso_partido_abierto_out.txt 2>>gestion_aviso_partido_abierto_error.txt &" );
		      //var_dump($orden);

/*
			  echo '
			   <script  type="text/javascript">


					$.ajax({
						url: "gestion_aviso_partido_abierto.php?cron=false&id_partido='.$id_partido_creado.'",
						type: "POST",
						async: true,
						error: function(req, err){ console.log("my message" + err); }

					});

				</script>
				';*/
				//echo "Envio jq $id_partido_creado";
		  }




		  }
		  else {
                mini_cabecera_html();
          	    Mini_TITULO_CSS ('box1','CREAR ','PARTIDO','','SI');

	            echo "<div class='filtrocontainer2'>";
          	    echo "<br><br><center>".Traductor::traducir("NO SE HA CREADO EL PARTIDO")."<br><a class='botonancho-3 CP_listarpartidos' href='partidos.php?menu=listar'><font>".Traductor::traducir("LISTAR PARTIDOS")."</font></a></center>";
	            echo "</div>";


          }
     break;






     case 'modificar':
         Traductor::cargarTraduccionesJavaScript();
         cargarScriptsGlobales();
         cargarEstilosGlobales();
     mini_cabecera_html(0,'',true);
     echo"<SCRIPT src='gen_validatorv4.js'></SCRIPT>";
		Mini_TITULO_CSS ('box1',Traductor::traducir("Modificar un"),Traductor::traducir("Partido"),'','SI');//    TITULO_CSS_FIN ();
          formulario (modificar,$id);
	//	TITULO_CSS_FIN ();
     break;



     case 'modificarya':


         /*
     		if(!jugador_activo_liga(Sesion::obtenerJugador()->obtenerId(),$_SESSION['S_LIGA_ACTUAL']))
			{
                Log::v(__FUNCTION__, "Jugador no está acitvo en la liga: ID LIGA: (".$_SESSION['S_LIGA_ACTUAL'].") | JUGADOR: $id_Jugador1", true);
				if($_SESSION['S_Version'] != 'Movil')
                Mini_TITULO_CSS('box1', Traductor::traducir("Crear un"), Traductor::traducir("Mensaje"), 'nada');
            	echo ('<center>Tu Acceso actual no tiene permisos suficientes para modificar partidos en esta Liga.<br><br><b>ï¿œQuieres activar esta opcion y beneficiarte de las ventajas de pertenecer a R&aacute;dical Padel?</b><br>No esperes m&aacute;s, apuntate en esta liga y activa tu acceso pinchando<br><br><a target="_parent" href="apuntarse.php?menu=confirmardatosFORM&id_LIGA=' . $_SESSION['S_LIGA_ACTUAL'] . '">AQU&Iacute;</a></center>');
            	die();

			}
         */

         /*

	 		if($_SESSION['S_ROL']==4){
			 mini_cabecera_html(0,'',true);
			 Mini_TITULO_CSS ('box1',Traductor::traducir("Modificar un"),Traductor::traducir("Partido"),'','SI');
			die ('No tienes permisos suficientes para realizar esta acci&oacute;n');
			}
        */

	 		$nivelAnteriorJugador = (new Jugador($_SESSION['S_id_usuario']))->obtenerNivel(true);

            //Nuevo: Variables para la modificacion de mixtos y parejas
            //Parejas
            $id_creador='';
            $id_invitado='';
            $Parejas='';

            if($FiltroParejas){

              $Parejas='S';
              $id_creador=$id_Jugador1;
              $id_invitado=$txtPareja;

              //die("creador $id_creador invitado $txtPareja");
              //echo "<script>alert('con pareja. Invitado:$id_invitado, creador $id_creador');</script>";
            }else{
              $Parejas='N';
              $id_creador='';
              $id_invitado='';

              //echo "<script>alert('sin pareja');</script>";
             }

             //Nuevo: partidos mixtos
             $es_mixto='N';
             if($FiltroMixto)
                $es_mixto='S';

            /////////////////////////

			//Nuevo: partidos amistosos: modificacion.
			$es_amistoso='N';
			if($FiltroAmistosos)
              $es_amistoso='S';
			//////////////////////


     		//$Hora=$Hora.":".$Minutos;
     		if ($FiltroHCP!=1) {$nivel_min=0;$nivel_max=0;}
    		if ($ESRESULTADO)
    		{     mini_cabecera_html(0,'',true);
    			 //echo "ES RESULTADO";
    			 //Calcular quiï¿œn gana empata pierde, y cuantos puntos lleva aparejado
					PUNTUA ($id,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$Fecha,$Hora,$RESULTADO);
    			 //Dar de alta los puntos en "resultados"
				Mini_TITULO_CSS ('box1','INTRODUCCI&Oacute;N','DE RESULTADO','','SI');//    TITULO_CSS_FIN ();
    			 //ACTUALIZAR (RECALCULAR) los puntos y contrincantes en "puntos" SOLO DE LOS JUGADORES IMPLICADOS
				if($limsexo==1){
					$Extra2 = $_SESSION['sexo'];
				}else{
					$Extra2 = '';
				}
                                                                                                                                                                                                                                                                                                                                 //Agrego estos registros
				modifica_partidos ($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Localidad,$Parejas,$id_creador,$id_invitado,$es_mixto,$es_amistoso, $Localidad,$reservarPista="", $idPista, $idTiempoReserva, $partidoCompleto, $numeroJugadores, $repartirImporte, $importePagar, $aplazarPago, $tipoPagoJugadorReserva, $numeroPedido, $idTarjeta);


               			    //JMAM: Notificación a Jugadores de que el resultado del partido ha sido introducido
    			    $arrayJugadoresPartido = (new Partido($id))->obtenerJugadoresInscritos($_SESSION['S_id_usuario']);
                     foreach ($arrayJugadoresPartido as $Jugador){

                         $idJugador = $Jugador->obtenerId();

                         $JugadorReserva = new Jugador($id_Jugador_ApuntaResult);
                         $nombreJugador = $JugadorReserva->obtenerNombre(true);
                         $telefonoJugador = $JugadorReserva->obtenerTelefono(true);

                         $asunto = Traductor::traducir("RESULTADO DE TU PARTIDO", false, $Jugador->obtenerIdioma());
                         $mensaje = "$nombreJugador ".Traductor::traducir("ha grabado el resultado del partido que acabas de jugar, comprueba tu ranking", false, $Jugador->obtenerIdioma());

                         Notificacion::enviarNotificacion($idJugador, $asunto, $mensaje, Notificacion::TIPO_NOTIFICACION_verResultados, $id_Liga);

                         //JMAM: Enviar Notificación de Comparativa a los Jugadores con los que ha jugado
                         $Jugador->enviarNotificacionDeComparativa($id_Liga);
                     }


                     //JMAM: Notificación a Jugadores de que el usuario ha subido de nivel si corresponde
                     $nivelActualJugador = (new Jugador($id_Jugador_ApuntaResult))->obtenerNivel(true);

                     //echo "NIVEL ACTUAL: $nivelActualJugador NIVEL ANTERIOR: $nivelAnteriorJugador";
                     if ($nivelActualJugador < $nivelAnteriorJugador){

                         $JugadorReserva = new Jugador($id_Jugador_ApuntaResult);
                         $arrayJugadoresConLosQueHaJugado = $JugadorReserva->obtenerJugadoresConLosQueHaJugado($id_Liga);

                         //print_r($arrayJugadoresConLosQueHaJugado);

                         foreach ($arrayJugadoresConLosQueHaJugado as $Jugador){

                             $idJugador = $Jugador->obtenerId();

                             $idJugadorReserva = $JugadorReserva->obtenerId();
                             $nombreJugador = $JugadorReserva->obtenerNombre(true);
                             $telefonoJugador = $JugadorReserva->obtenerTelefono(true);

                             $asunto = Traductor::traducir("SUBIDA RANKING", false, $Jugador->obtenerIdioma());
                             $mensaje = "$nombreJugador ".Traductor::traducir("ha subido de nivel, mira su Ranking y felicítalo", false, $Jugador->obtenerIdioma());

                             Notificacion::enviarNotificacion($idJugador, $asunto, $mensaje, Notificacion::TIPO_NOTIFICACION_verResultados, $id_Liga, $idJugadorReserva);

                         }


                     }



	echo "<div class='filtrocontainer2'>";



    			echo "</br><center style='margin-bottom: 40px'>".Traductor::traducir("RESULTADO INTRODUCIDO")."<br><br><a class='superboton listarPartidosGrabarResultados' href='partidos.php?menu=misresultados'>".Traductor::traducir("LISTAR MENSAJES")."</a><br/></center>";


    			 //Nuevo JM 23-feb-2015: llamamos por jquery para actualizar la cache de la clasificacion:
    			 echo "
     	<script type='text/javascript'>
   			 $.post( 
  		  'cacheClasificacionPARTIDO.php', // location of your php script
  			  { token: '545adgsWds', id_partido: $id }, // any data you want to send to the script
 			   function( data ){  // a function to deal with the returned information

  		      //alert('executed'); 

 			   });
		</script>
    		 ";
				////////////////////////////

		if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
			//	TITULO_CSS_FIN ();
				Registrar_Actividad (55,$id);


    		}
	     	else
	     	{
			if($limsexo==1){
					$Extra2 = $_SESSION['sexo'];
				}else{
					$Extra2 = '';
				}

          $OK=modifica_partidos ($id,$id_Liga,$Fecha,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,'0','0','0','0',$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1,$Extra2,$Localidad,$Parejas,$id_creador,$id_invitado,$es_mixto,$es_amistoso, $Localidad, $reservarPista="", $idPista, $idTiempoReserva, $partidoCompleto, $numeroJugadores, $repartirImporte, $importePagar, $aplazarPago, $tipoPagoJugadorReserva, $numeroPedido, $idTarjeta);
            Log::v(__FUNCTION__, "¿Modificar Partidos?: $OK", true);
            if ($OK === "ERROR_AL_COMPROBAR_INTEGRIDAD_RESERVA"){
                Log::v(__FUNCTION__, "Entra en pantalla error partido", true);
             mini_cabecera_html();
          	    Mini_TITULO_CSS ('box1','CREAR ','PARTIDO','','SI');

	            echo "<div class='filtrocontainer2'>";
          	    echo "<div style='background: red; color: white; padding: 10px; margin: 5px; font-weight: bolder'><center>".Traductor::traducir("No se ha creado la Reserva, comprueba disponibilidad y vuelve a intentarlo.")."</center></div>";
                echo "<center><a class='botonancho-3 CP_listarpartidos' href='partidos.php?menu=listar'><font>".Traductor::traducir("LISTAR PARTIDOS")."</font></a></center>";
	            echo "</div>";
          }else if($OK){
                header("Location: partidos.php");
			}
          else {     mini_cabecera_html();
          	if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='filtrocontainer2'>";
}
          	echo "<br><br><center>".Traductor::traducir("NO SE HA MODIFICADO EL PARTIDO")."<br><a href='partidos.php?menu=listar' class='botonancho-3'><font color='black'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";}

if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
			}
     break;
//////////////////////////////////////////////////////
//////////CANCELACIONES jorge 19/02/2013    //////////
///////////////////////////////////////////////////////


	 //iniciar_proceso_cancelacion Aï¿œADIDO POR JORGE PARA LA CANCELACION
	 case 'iniciar_proceso_cancelacion':
     mini_cabecera_html();Mini_TITULO_CSS ('box1',Traductor::traducir("CANCELACIÓN"),Traductor::traducir("DE PARTIDO"),'','SI');//    TITULO_CSS_FIN ();
     if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='filtrocontainer2'>";
	echo "<div class='filtrocontainer2' style='text-align:center'>";
}
     RECUADRO_CSS (Traductor::traducir("Faltan menos de 2 días y el partido está completo.<br>Debes buscar sustituto o Enviar Solicitud de cancelación al resto de jugadores."));
if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
              echo "<br><center>

          ".Traductor::traducir("¿Seguro que quieres iniciar el procedimiento para cancelar el partido?<br><br> <b>En caso de continuar, el partido sólo se cancelará si confirman los 4 jugadores<b/>")."";

                    echo "<br><br><table border='0'>";
                    echo ("<tr>");
                    echo("<TD><form action='$PHP_SELF' method='get'>");
                    ////BOTON
                    echo ("<TD ALIGN=RIGHT VALIGN=TOP><p> <input type='submit' class='superboton' value='".Traductor::traducir("SOLICITAR CANCELACIÓN")."'></p></TD>");
                    echo ("<input type=hidden name=id_Jugadores value=$id_jugadores>");
                    echo ("<input type=hidden name=menu value='proceso_cancelacion'>");
                    echo ("<input type=hidden name=id value='$id'>");
                    echo ("</form></TD>");
                    echo("<TD><form action='$PHP_SELF' method='get'>");
                    ////BOTON
                    echo ("<TD ALIGN=RIGHT VALIGN=TOP><p> <input type='submit' class='superboton' value='".Traductor::traducir("NO")."'></p><br><br></TD>");
                    echo ("<input type=hidden name=menu value='listar'>");
                    echo ("</form></TD>");
                    echo ("</tr>");
                    echo "</table></center>";
                    if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
              //      TITULO_CSS_FIN ();

                    //$id_liga,$Fecha_INI,$Fecha_FIN,$id_Jugador_ORIGEN,$id_Jugador_DESTINO[$i],$Titulo,$Mensaje,$Importancia,$Extra1,$Extra2
     break;

	 case 'proceso_cancelacion':

          ///MANDAR UN MENSAJE A LOS JUGADORES DEL PARTIDO

          	$Hoy=date('Y-m-d');
			date_default_timezone_set('Europe/Madrid');
			$hora=localtime();
			if ($hora[2]<10) $hora[2]="0".$hora[2];
			if ($hora[1]<10) $hora[1]="0".$hora[1];
			$horaUNIDA =( $hora[2] . ":" . $hora[1] );
			$Extra1=$horaUNIDA;
			$id_jugador=$_SESSION['S_id_usuario'];
			$NAME=devuelve_un_campo('jugadores','6','id',$id_jugador)." ".devuelve_un_campo('jugadores','7','id',$id_jugador);

			$si=mysql_query("SELECT * FROM partidos WHERE id=$id");
			list($id_P,$id_Liga,$Fecha_FIN,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1P,$Extra2P,$Extra3P) = mysql_fetch_array($si);

			//$botonCancelarPartidoJugador.= "<a href=\"partidos.php?menu=aceptar_cancelacion&datos=$id_P,$id_Jugador1\"><img src=\"./images/interrogante_bocadillo.png\" border=\"0\" title=\"".Traductor::traducir("ACEPTA la CANCELACION")."\"></a>";

			$botonCancelarPartidoJugador = "<br/><br/><div class=\"superboton\" style=\"text-align: center;\" onclick=\"javascript:location.href=\'partidos.php?menu=aceptar_cancelacion&datos=$id_P,$id_Jugador1\'\">".Traductor::traducir("ACEPTA la CANCELACION")."</div>";


			if ($id_Campo) $Mensaje="$NAME, ".Traductor::traducir("como organizador del partido del")." ".cambiaf_a_normal($Fecha_FIN)." ".Traductor::traducir("en")." ".devuelve_un_campo('campos',2,'id',$id_Campo)." ".Traductor::traducir("a las")." $Hora, ".Traductor::traducir("ha solicitado la cancelación del mismo").$botonCancelarPartidoJugador."";
			else $Mensaje="$NAME, ".Traductor::traducir("como organizador del partido del")." ".cambiaf_a_normal($Fecha_FIN)." ".Traductor::traducir("en")." $Otro_Campo ".Traductor::traducir("a las")." $Hora, ".Traductor::traducir("ha solicitado la cancelación del mismo").$botonCancelarPartidoJugador."";
			$LIGA=$_SESSION['S_LIGA_ACTUAL'];

    $id_Jugador_DESTINO=explode(';',$id_Jugadores);
   for ($i=0;$i<count($id_Jugador_DESTINO);$i++)
       {
         if (($id_Jugador_DESTINO[$i])&&($id_Jugador_DESTINO[$i]!=$_SESSION['S_id_usuario']))
         {

       			$Email1=devuelve_un_campo('jugadores',18,'id',$id_Jugador_DESTINO[$i]);
		       //		mandar mensaje

       			$mensaje = "<head><title>".Traductor::traducir("Mensaje privado en Rádical Padel")."</title></head><body><br>".Traductor::traducir("SOLICITUD DE CANCELACION")."<br>".Traductor::traducir("Tienes un mensaje privado del jugador")." <b>$NAME</b> " .
       					"".Traductor::traducir("referente a la cancelación de un partido en la liga")." ".devuelve_un_campo('liga',1,'id',$LIGA)." <br>";
       			$mensaje.= " <a href=\"http://www.radicalpadel.com\">".Traductor::traducir("IR A LA WEB PARA LEERLO")."</a><br><br>";
       			$mensaje.= "<br>".Traductor::traducir("Un saludo. <br><br>Organización Rádical Padel")."<br><br><center><img src=\"http://www.radicalpadel.com/PROGRAMA/images/logo.jpg\"></center>";
       			$mensaje.="</body>";


                //Nuevo: gestion del push
                $NM=devuelve_un_campo('jugadores',6,'id',$id_jugador);
                $AP=devuelve_un_campo('jugadores',7,'id',$id_jugador);
                $nombre_liga=devuelve_un_campo('liga',1,'id',$LIGA);
                $asunto_push="$NM $AP";
                $mensaje_push="Referente a la cancelacion de un partido en la liga $nombre_liga";

               if(!MensajePUSH ($id_Jugador_DESTINO[$i],(Componer_Notificacion($id_Jugador_DESTINO[$i],$asunto_push,$mensaje_push,'2',$id_Liga,$id_P))))
                {

         			if(!mail($Email1, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
         			{$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';}
                }
			$F_Desaparece = date('Y-m-d', strtotime('+2 days'));


			if(checkMensajesRepetidos($_SESSION['S_id_usuario'],$id_Jugador_DESTINO[$i],$Hoy,$Extra1,$Mensaje))
			   die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");



			//	       ('','$LIGA','$Hoy','$Fecha_FIN','$id_Jugador'                  ,'0'                          ,'BUSCO  SUSTITUTO'   ,'$Mensaje','$Importancia','$Hora1','$id_Partido,$id_Jugador','$F_Desaparece','$Respuesta_id','$MeGusta','$Extra2','$Extra3','$Extra4','$Extra5','$Extra6')";
			$query = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','".$_SESSION['S_id_usuario']."','".$id_Jugador_DESTINO[$i]."','PROCESO CANCELACION','$Mensaje','$Importancia','$Extra1','$id,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','','')";
			mysql_query($query);

			//echo "$query";
         }
	}
			$query_cancelacion_ANT="DELETE FROM partidos_cancelacion WHERE id_partido='$id'";
			mysql_query($query_cancelacion_ANT);

		$ahora = date("Y-m-d H:i:s"); // 2001-03-10 17:16:18 (el formato DATETIME de MySQL)
			$query_cancelacion="INSERT INTO partidos_cancelacion VALUES ('','$id','N','".$_SESSION['S_id_usuario']."','$ahora','','','','','','','')";
			mysql_query($query_cancelacion);

			//baja_partidos ($id);
			//echo "<br>$query<br><br>$query_cancelacion<br><br>";

			mini_cabecera_html();Mini_TITULO_CSS ('box1',Traductor::traducir("CANCELACIÓN"),Traductor::traducir("DE PARTIDO"),'','SI');//    TITULO_CSS_FIN ();
			if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='filtrocontainer2'>";
	echo "<div class='filtrocontainer2' style='text-align:center'>";
}
			RECUADRO_CSS (Traductor::traducir("Se ha enviado solicitud de cancelación del partido<br>"));
			if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
          echo "<center>".Traductor::traducir("Los participantes recibirán un mensaje para confirmar la cancelación, no obstante, si lo deseas puedes avisarles telefónicamente para que confirmen la cancelación")."<br><br><a class='botonancho-3' href='partidos.php?menu=listar'><font color='black'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";
		  if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
			TITULO_CSS_FIN ();
          //header("Location: $PHP_SELF?menu=listar");

     break;

	 case 'aceptar_cancelacion':
	 //$datos tiene el id del partido y el id del jugador a sustituir

			$separar = explode(',',$datos);
			$id_partido=$separar[0];
			$id_Jugador_Q_CANCELA=$separar[1];

          	$Hoy=date('Y-m-d');
			date_default_timezone_set('Europe/Madrid');
			$hora=localtime();
			if ($hora[2]<10) $hora[2]="0".$hora[2];
			if ($hora[1]<10) $hora[1]="0".$hora[1];
			$horaUNIDA =( $hora[2] . ":" . $hora[1] );
			$Extra1=$horaUNIDA;
			$id_Jugador=$_SESSION['S_id_usuario'];

			$NAME=devuelve_un_campo('jugadores','6','id',$id_Jugador)." ".devuelve_un_campo('jugadores','7','id',$id_Jugador);

			$si=mysql_query("SELECT * FROM partidos WHERE id=$id_partido");
			list($id_P,$id_Liga,$Fecha_FIN,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1P,$Extra2P,$Extra3P) = mysql_fetch_array($si);

			if ($id_Campo) $Mensaje="$NAME, ".Traductor::traducir("ha aceptado tu solicitud de cancelacion para el partido")." ".cambiaf_a_normal($Fecha_FIN)." ".Traductor::traducir("en")." ".devuelve_un_campo('campos',2,'id',$id_Campo)." ".Traductor::traducir("a las")." $Hora, ".Traductor::traducir("Te recordamos que el partido sólo se cancelará si confirman los 4 jugadores")."";
			else $Mensaje="$NAME, ".Traductor::traducir("ha aceptado tu solicitud de cancelacion para el partido")." ".cambiaf_a_normal($Fecha_FIN)." ".Traductor::traducir("en")." $Otro_Campo ".Traductor::traducir("a las")." $Hora, ".Traductor::traducir("Te recordamos que el partido sólo se cancelará si confirman los 4 jugadores")."";
			$LIGA=$_SESSION['S_LIGA_ACTUAL'];



		  $SQL_Cancelar="SELECT * FROM partidos_cancelacion WHERE id_partido=$id_partido";
		  $SI=mysql_query ($SQL_Cancelar);
		  $PARTIDO_CANCELADO=0;

		  $ahora = date("Y-m-d H:i:s"); // 2001-03-10 17:16:18 (el formato DATETIME de MySQL)

		   while (list($id_cancel,$id_partido_,$cancelado,$id_J1,$FyH_J1,$id_J2,$FyH_J2,$id_J3,$FyH_J3,$id_J4,$FyH_J4,$Extra1_) = mysql_fetch_array($SI))
		  {
		  $Email1=devuelve_un_campo('jugadores',18,'id',$id_J1);
		  $Solicitante_de_cancelacion=$id_J1;

		  if (!$id_J2)
			{
				mysql_query ("UPDATE partidos_cancelacion SET id_J2=$id_Jugador, FyH_J2='$ahora'  WHERE id=$id_cancel");
			}
		   else if (!$id_J3)
			{
				mysql_query ("UPDATE partidos_cancelacion SET id_J3=$id_Jugador, FyH_J3='$ahora'  WHERE id=$id_cancel");
			}
			else if (!$id_J4)
			{
				mysql_query ("UPDATE partidos_cancelacion SET id_J4=$id_Jugador, cancelado='S', FyH_J4='$ahora'   WHERE id=$id_cancel");
				$PARTIDO_CANCELADO=1;
			}

		  }
		  ///MANDAR UN MENSAJE AL CREADOR DEL PARTIDO
		  if (1) //Envio de mensaje de confirmaciï¿œn al que ha solicitado la confirmaciï¿œn
		           {

		       //		mandar mensaje

       			$mensaje = "<head><title>".Traductor::traducir("Mensaje privado en Rádical Padel")."</title></head><body><br>".Traductor::traducir("SOLICITUD DE CANCELACION")."<br>".Traductor::traducir("Tienes un mensaje privado del jugador")." <b>$NAME</b> " .
       					"".Traductor::traducir("referente a la cancelación de un partido en la liga")." ".devuelve_un_campo('liga',1,'id',$LIGA)." <br>";
       			$mensaje.= " <a href=\"http://www.radicalpadel.com\">".Traductor::traducir("IR A LA WEB PARA LEERLO")."</a><br><br>";
       			$mensaje.= "<br>".Traductor::traducir("Un saludo. <br><br>Organización Rádical Padel")."<br><br><center><img src=\"http://www.radicalpadel.com/PROGRAMA/images/logo.jpg\"></center>";
       			$mensaje.="</body>";

                //Nuevo: gestion del push
                $NM=devuelve_un_campo('jugadores',6,'id',$id_Jugador);
                $AP=devuelve_un_campo('jugadores',7,'id',$id_Jugador);
                $asunto_push="$NM $AP";
                $mensaje_push="Acepta la cancelacion provisionalmente pendiente del resto de jugadores";
                $id_creador=devuelve_un_campo('partidos',7,'id',$id_partido);

                if(!MensajePUSH ($id_creador,(Componer_Notificacion($id_creador,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
      			   if(!mail($Email1, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }


			$F_Desaparece = date('Y-m-d', strtotime('+2 days'));
										//	       ('','$LIGA','$Hoy','$Fecha_FIN','$id_Jugador'                  ,'0'                          ,'BUSCO  SUSTITUTO'   ,'$Mensaje','$Importancia','$Hora1','$id_Partido,$id_Jugador','$F_Desaparece','$Respuesta_id','$MeGusta','$Extra2','$Extra3','$Extra4','$Extra5','$Extra6')";


			if(checkMensajesRepetidos($_SESSION['S_id_usuario'],$Solicitante_de_cancelacion,$Hoy,$Extra1,$Mensaje))
			   die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");


			$query = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','".$_SESSION['S_id_usuario']."','".$Solicitante_de_cancelacion."','".Traductor::traducir("ACEPTA la CANCELACION")."','$Mensaje','$Importancia','$Extra1','$id,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		    mysql_query($query);
			//echo "$query";
         }
		 //////BORRAR MENSAJE DE ORIGEN DE LA CANCELACION
		 $Query_Borrar_MSG="DELETE FROM mensajes2 WHERE Titulo='PROCESO CANCELACION' AND id_Jugador_DESTINO='".$_SESSION['S_id_usuario']."' AND Extra1='".$id_partido.",".$Solicitante_de_cancelacion."'";
		 mysql_query($Query_Borrar_MSG);



		  mini_cabecera_html();Mini_TITULO_CSS ('box1',Traductor::traducir("CANCELACIÓN"),Traductor::traducir("DE PARTIDO"),'','SI');//    TITULO_CSS_FIN ();
			//echo " Sustituir ($id_partido,$id_Jugador_A_SUST,$id_Jugador)";
    if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='filtrocontainer2'>";
}
          echo "<center>";
		  if ($PARTIDO_CANCELADO)
		  {
		  $SQL_Cancelar="SELECT * FROM partidos_cancelacion WHERE id_partido=$id_partido";
		  $SI=mysql_query ($SQL_Cancelar);
		  list($id_cancel,$id_partido_,$cancelado,$id_J1,$FyH_J1,$id_J2,$FyH_J2,$id_J3,$FyH_J3,$id_J4,$FyH_J4,$Extra1_) = mysql_fetch_array($SI);

			echo "".Traductor::traducir("CANCELACION COMPLETADA").",<br> ".Traductor::traducir("TODOS LOS JUGADORES HAN CONFIRMADO LA CANCELACION.")."";
			//echo"parametros:$id_cancel,$id_partido_,$cancelado,$id_J1,$FyH_J1,$id_J2,$FyH_J2,$id_J3,$FyH_J3,$id_J4,$FyH_J4,$Extra1";
			////////////////ENVIO DE MENSAJE A LOS PARTICIPANTES DE LA CANCELACION DEL PARTIDO

			$Email1=devuelve_un_campo('jugadores',18,'id',$id_J1);
			$Email2=devuelve_un_campo('jugadores',18,'id',$id_J2);
			$Email3=devuelve_un_campo('jugadores',18,'id',$id_J3);
			$Email4=devuelve_un_campo('jugadores',18,'id',$id_J4);
			//		mandar mensaje
			$NAME=devuelve_un_campo('jugadores','6','id',$id_J1)." ".devuelve_un_campo('jugadores','7','id',$id_J1);

       			$mensaje = "<head><title>".Traductor::traducir("Mensaje privado en Rádical Padel")."</title></head><body><br>".Traductor::traducir("PARTIDO CANCELADO")."<br>".Traductor::traducir("Tienes un mensaje privado del jugador")." <b>$NAME</b> " .
       					"".Traductor::traducir("referente a la cancelación de un partido en la liga")." ".devuelve_un_campo('liga',1,'id',$LIGA)." <br>";
       			$mensaje.= " <a href=\"http://www.radicalpadel.com\">".Traductor::traducir("IR A LA WEB PARA LEERLO")."</a><br><br>";
       			$mensaje.= "<br>".Traductor::traducir("Un saludo. <br><br>Organización Rádical Padel")."<br><br><center><img src=\"http://www.radicalpadel.com/PROGRAMA/images/logo.jpg\"></center>";
       			$mensaje.="</body>";


                //Nuevo: gestion del push
                $NM=devuelve_un_campo('jugadores',6,'id',$id_J1);
                $AP=devuelve_un_campo('jugadores',7,'id',$id_J1);

                $fecha_p=devuelve_un_campo('partidos',2,'id',$id_partido);
                $hora_p=devuelve_un_campo('partidos',3,'id',$id_partido);
                $id_campo=devuelve_un_campo('partidos',5,'id',$id_partido);
                $nombre_campo=devuelve_un_campo('campos',2,'id',$id_campo);

                if($nombre_campo!="")
                   $nombre_campo="en $nombre_campo";

                $asunto_push="Partido como pareja";
                $dia_evento=getDiaEvento($fecha_p);

                $asunto_push="$NM $AP";
                $mensaje_push="Confirmada la cancelacion del partido de $dia_evento a las $hora_p $nombre_campo";

                if(!MensajePUSH ($id_J1,(Componer_Notificacion($id_J1,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
                   if(!mail($Email1, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }

                if(!MensajePUSH ($id_J2,(Componer_Notificacion($id_J2,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
                   if(!mail($Email2, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }

                if(!MensajePUSH ($id_J3,(Componer_Notificacion($id_J3,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
                   if(!mail($Email3, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }

                if(!MensajePUSH ($id_J4,(Componer_Notificacion($id_J4,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
                   if(!mail($Email4, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }

				$F_Desaparece = date('Y-m-d', strtotime('+2 days'));
				$Mensaje=Traductor::traducir("EL PROCESO DE CANCELACION DEL PARTIDO HA CONCLUIDO CON LA ACEPTACION POR PARTE DE TODOS LOS JUGADORES, EL PARTIDO ESTÁ CANCELADO.");


				if(checkMensajesRepetidos($id_J1,$id_J1,$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");
				if(checkMensajesRepetidos($id_J1,$id_J2,$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");
				if(checkMensajesRepetidos($id_J1,$id_J3,$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");
				if(checkMensajesRepetidos($id_J1,$id_J4,$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");


			$querya = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','$id_J1','$id_J1','".Traductor::traducir("PARTIDO CANCELADO")."','$Mensaje','$Importancia','$Extra1','$id,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		  		mysql_query($querya);
			$queryb = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','$id_J1','$id_J2','".Traductor::traducir("PARTIDO CANCELADO")."','$Mensaje','$Importancia','$Extra1','$id,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		  		mysql_query($queryb);
			$queryc = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','$id_J1','$id_J3','".Traductor::traducir("PARTIDO CANCELADO")."','$Mensaje','$Importancia','$Extra1','$id,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		  		mysql_query($queryc);
			$queryd = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','$id_J1','$id_J4','".Traductor::traducir("PARTIDO CANCELADO")."','$Mensaje','$Importancia','$Extra1','$id,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		  		mysql_query($queryd);

					//echo"$querya<br>$queryb<br>$queryc<br>$queryd";
			///Fin del proceso de mandar mensajes
		  		$datos_j1 = mysql_fetch_array(mysql_query("SELECT * FROM jugadores WHERE id=".$id_J1));
		  		$datos_partido = mysql_fetch_array(mysql_query("SELECT * FROM partidos WHERE id=".$id_partido_));
		  		$datos_campo = mysql_fetch_array(mysql_query("SELECT * FROM campos WHERE id=".$datos_partido['id_Campo']));
		  		if($datos_campo['Convenio']=='S'){
		  			$fechapartido = explode('-',$datos_partido['Fecha']);
		  			$datos_partido['Fecha'] = $fechapartido[2].'/'.$fechapartido[1].'/'.$fechapartido[0];
		  		$mensajeaviso = Traductor::traducir("Hola: os informamos que el jugador que figuraba en la aplicación Rádical Padel como organizador de un partido en vuestro club el...");
		  		$mensajeaviso = str_replace('%NOMBRE_JUG%', $datos_j1['Nombre'].' '.$datos_j1['Apellidos'], $mensajeaviso);
		  		$mensajeaviso = str_replace('%TLF_JUG%', $datos_j1['Movil'], $mensajeaviso);
		  		$mensajeaviso = str_replace('%HORA%', $datos_partido['Hora'], $mensajeaviso);
		  		$mensajeaviso = str_replace('%FECHA%', $datos_partido['Fecha'], $mensajeaviso);
				$emailclub = $datos_campo['email'];
			if($emailclub!='')
			mail($emailclub, Traductor::traducir("Aviso de comprobacion de reserva"),$mensajeaviso,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com");
      		}

		  }
     	  else
			echo Traductor::traducir("Tu conformidad ha quedado registrada. Si todos los jugadores estén conformes recibirás un mensaje y el partido aparecerá en rojo dándose por cancelado...");
          //echo"Ya puede ver su nombre en el partido<br>Se ha enviado un SMS al jugador para avisarle de que ha sido sustituido";
          echo"<br><br><a href='partidos.php?menu=listar'><font color='black'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";
          //header("Location: $PHP_SELF?menu=listar");
          if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
			TITULO_CSS_FIN ();

	 break;

	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 case 'cancelar_proceso_cancelacion':
	 	  $SQL_Cancelar="SELECT * FROM partidos_cancelacion WHERE id_partido=$id_partido";
		  $SI=mysql_query ($SQL_Cancelar);
		  $PARTIDO_CANCELADO=0;

		  		  			$LIGA=$_SESSION['S_LIGA_ACTUAL'];

          	$Hoy=date('Y-m-d');
			date_default_timezone_set('Europe/Madrid');
			$hora=localtime();
			if ($hora[2]<10) $hora[2]="0".$hora[2];
			if ($hora[1]<10) $hora[1]="0".$hora[1];
			$horaUNIDA =( $hora[2] . ":" . $hora[1] );
			$Extra1=$horaUNIDA;

		  $ahora = date("Y-m-d H:i:s"); // 2001-03-10 17:16:18 (el formato DATETIME de MySQL)

		   while (list($id_cancel,$id_partido_,$cancelado,$id_J1,$FyH_J1,$id_J2,$FyH_J2,$id_J3,$FyH_J3,$id_J4,$FyH_J4,$Extra1_) = mysql_fetch_array($SI))
		  {
		  if ($cancelado=='N')
		  {
		      $id_Jugador_DESTINO=explode(';',$id_jugadores);
				/// Primero se borran los mensajes
					 //////BORRAR MENSAJE DE ORIGEN DE LA CANCELACION
		 $Query_Borrar_MSGa="DELETE FROM mensajes2 WHERE Titulo='PROCESO CANCELACION' AND id_Jugador_DESTINO='".$id_Jugador_DESTINO[1]."' AND Extra1='".$id_partido.",".$id_J1."'";
		 mysql_query($Query_Borrar_MSGa);
		 $Query_Borrar_MSGb="DELETE FROM mensajes2 WHERE Titulo='PROCESO CANCELACION' AND id_Jugador_DESTINO='".$id_Jugador_DESTINO[2]."' AND Extra1='".$id_partido.",".$id_J1."'";
		 mysql_query($Query_Borrar_MSGb);
		 $Query_Borrar_MSGc="DELETE FROM mensajes2 WHERE Titulo='PROCESO CANCELACION' AND id_Jugador_DESTINO='".$id_Jugador_DESTINO[3]."' AND Extra1='".$id_partido.",".$id_J1."'";
		 mysql_query($Query_Borrar_MSGc);

	 //echo $Query_Borrar_MSGa."<br>".$Query_Borrar_MSGb."<br>".$Query_Borrar_MSGc."<br>";

	 ///Segundo se envï¿œan mensajes a los que habï¿œan cancelado
	 			//		mandar mensaje
				$NAME=devuelve_un_campo('jugadores','6','id',$id_J1)." ".devuelve_un_campo('jugadores','7','id',$id_J1);

       			$mensaje = "<head><title>".Traductor::traducir("Mensaje privado en Rádical Padel")."</title></head><body><br>".Traductor::traducir("SOLICITUD DE CANCELACION SUSPENDIDA")."<br>".Traductor::traducir("Tienes un mensaje privado del jugador")." <b>$NAME</b> " .
       					"".Traductor::traducir("referente a la cancelación de un partido en la liga")." ".devuelve_un_campo('liga',1,'id',$LIGA)." <br>";
       			$mensaje.= " <a href=\"http://www.radicalpadel.com\">".Traductor::traducir("IR A LA WEB PARA LEERLO")."</a><br><br>";
       			$mensaje.= "<br>".Traductor::traducir("Un saludo. <br><br>Organización Rádical Padel")."<br><br><center><img src=\"http://www.radicalpadel.com/PROGRAMA/images/logo.jpg\"></center>";
       			$mensaje.="</body>";

                //Nuevo: gestion del push
                $NM=devuelve_un_campo('jugadores',6,'id',$id_J1);
                $AP=devuelve_un_campo('jugadores',7,'id',$id_J1);
                $fecha_p=devuelve_un_campo('partidos',2,'id',$id_partido);
                $hora_p=devuelve_un_campo('partidos',3,'id',$id_partido);
                $asunto_push="Partido como pareja";
                $dia_evento=getDiaEvento($fecha_p);
                $asunto_push="$NM $AP";


                $mensaje_push="El partido de $dia_evento a las $hora_p se juega, no se suspende.";

                if(!MensajePUSH ($id_J1,(Componer_Notificacion($id_J1,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
                   if(!mail($Email1, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }
                if(!MensajePUSH ($id_J2,(Componer_Notificacion($id_J2,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
                   if(!mail($Email2, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }
                if(!MensajePUSH ($id_J3,(Componer_Notificacion($id_J3,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
                   if(!mail($Email3, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }
                if(!MensajePUSH ($id_J4,(Componer_Notificacion($id_J4,$asunto_push,$mensaje_push,'2',$LIGA,$id_partido))))
                {
                   if(!mail($Email4, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}
                }
				$F_Desaparece = date('Y-m-d', strtotime('+2 days'));

				$Mensaje=Traductor::traducir("EL PARTIDO VUELVE A SU ESTADO INICIAL, NO SE SUSPENDE");

				if(checkMensajesRepetidos($id_J1,$id_J1,$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");
				if(checkMensajesRepetidos($id_J1,$id_J2,$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");
				if(checkMensajesRepetidos($id_J1,$id_J3,$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");
				if(checkMensajesRepetidos($id_J1,$id_J4,$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");

			$query = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','$id_J1','$id_J1','".Traductor::traducir("SOLICITUD DE CANCELACION SUSPENDIDA")."','$Mensaje','$Importancia','$Extra1','$id_partido_,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		  		mysql_query($query);
			if ($id_J2)
			{
			$query = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','$id_J1','$id_J2','".Traductor::traducir("SOLICITUD DE CANCELACION SUSPENDIDA")."','$Mensaje','$Importancia','$Extra1','$id_partido_,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		  		mysql_query($query);
			}
			if ($id_J3)
			{
			$query = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','$id_J1','$id_J3','".Traductor::traducir("SOLICITUD DE CANCELACION SUSPENDIDA")."','$Mensaje','$Importancia','$Extra1','$id_partido_,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		  		mysql_query($query);
			}
			if ($id_J4)
			{
			$query = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','$id_J1','$id_J4','".Traductor::traducir("SOLICITUD DE CANCELACION SUSPENDIDA")."','$Mensaje','$Importancia','$Extra1','$id_partido_,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		  		mysql_query($query);
			}

			///Fin del proceso de mandar mensajes

			///Tercero se borra la lï¿œnea en partidos_cancelar
						$query_cancelacion_ANT="DELETE FROM partidos_cancelacion WHERE id_partido='$id_partido'";
			mysql_query($query_cancelacion_ANT);

			mini_cabecera_html();Mini_TITULO_CSS ('box1',Traductor::traducir("CANCELACIÓN"),Traductor::traducir("DE PARTIDO"),'','SI');//    TITULO_CSS_FIN ();
			if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='filtrocontainer2'>";
}
			echo "<center>".Traductor::traducir("EL PROCESO DE CANCELACION SE HA SUSPENDIDO")."</center>";
			if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
			TITULO_CSS_FIN ();

			}
			else ///El partido ya esta cancelado
			{
			mini_cabecera_html();Mini_TITULO_CSS ('box1',Traductor::traducir("CANCELACIÓN"),Traductor::traducir("DE PARTIDO"),'','SI');//    TITULO_CSS_FIN ();
			RECUADRO_CSS (Traductor::traducir("ERROR. El partido ya estaba cancelado, no se puede suspender el proceso de cancelación una vez que todos los jugadores han confirmado la cancelación"));
			}

	 	 }//del while
		 //en caso contrario no se puede


	 break;


     case 'confirmar':
     mini_cabecera_html();Mini_TITULO_CSS ('box1',Traductor::traducir("CANCELACIÓN"),Traductor::traducir("DE PARTIDO"),'','SI');//    TITULO_CSS_FIN ();
       if 	($_SESSION['S_Version']=='Movil')
     echo "<div class='filtrocontainer2' style='text-align:center'>";
     RECUADRO_CSS (Traductor::traducir("Estás cancelando un partido"),'','','red');
      if 	($_SESSION['S_Version']=='Movil')
     echo "</div>";
             if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='filtrocontainer2'>";
}
              echo "<br> <center>

          ".Traductor::traducir("¿Seguro que quieres borrar el partido?")."";

                    echo "<br><br><table border='0'>";
                    echo ("<tr>");
                    echo("<TD><form action='$PHP_SELF' method='get'>");
                    ////BOTON
                    echo ("<TD ALIGN=RIGHT VALIGN=TOP><p> <input type='submit' class='superboton' value='".Traductor::traducir("SI")."'></p></TD>");
                    echo ("<input type=hidden name=id_Jugadores value=$id_jugadores>");
                    echo ("<input type=hidden name=menu value='borrar'>");
                    echo ("<input type=hidden name=id value='$id'>");
                    echo ("</form></TD>");
                    echo("<TD><form action='$PHP_SELF' method='get'>");
                    ////BOTON
                    echo ("<TD ALIGN=RIGHT VALIGN=TOP><p> <input type='submit' class='superboton' value='".Traductor::traducir("NO")."'></p><br><br></TD>");
                    echo ("<input type=hidden name=menu value='listar'>");
                    echo ("</form></TD>");
                    echo ("</tr>");
                    echo "</table></center>";
              //      TITULO_CSS_FIN ();
if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
                    //$id_liga,$Fecha_INI,$Fecha_FIN,$id_Jugador_ORIGEN,$id_Jugador_DESTINO[$i],$Titulo,$Mensaje,$Importancia,$Extra1,$Extra2
     break;


     case 'borrar':

          ///MANDAR UN MENSAJE A LOS JUGADORES DEL PARTIDO

          	$Hoy=date('Y-m-d');
			date_default_timezone_set('Europe/Madrid');
			$hora=localtime();
			if ($hora[2]<10) $hora[2]="0".$hora[2];
			if ($hora[1]<10) $hora[1]="0".$hora[1];
			$horaUNIDA =( $hora[2] . ":" . $hora[1] );
			$Extra1=$horaUNIDA;
			$id_jugador=$_SESSION['S_id_usuario'];
			$NAME=devuelve_un_campo('jugadores','6','id',$id_jugador)." ".devuelve_un_campo('jugadores','7','id',$id_jugador);

			$si=mysql_query("SELECT * FROM partidos WHERE id=$id");
			list($id_P,$id_Liga,$Fecha_FIN,$Hora,$TipoPuntuacion,$id_Campo,$Otro_Campo,$id_Jugador1,$id_Jugador2,$id_Jugador3,$id_Jugador4,$Puntos_J1,$Puntos_J2,$Puntos_J3,$Puntos_J4,$id_Jugador_ApuntaResult,$Fecha_Result,$Observaciones,$nivel_min,$nivel_max,$favoritos,$invitacion,$Extra1P,$Extra2P,$Extra3P) = mysql_fetch_array($si);

			if ($id_Campo) $Mensaje="$NAME, ".Traductor::traducir("como organizador del partido del")." ".cambiaf_a_normal($Fecha_FIN)." ".Traductor::traducir("en")." ".devuelve_un_campo('campos',2,'id',$id_Campo)." ".Traductor::traducir("a las")." $Hora, ".Traductor::traducir("lo ha CANCELADO")."";
			else $Mensaje="$NAME, ".Traductor::traducir("como organizador del partido del")." ".cambiaf_a_normal($Fecha_FIN)." ".Traductor::traducir("en")." $Otro_Campo ".Traductor::traducir("a las")." $Hora, ".Traductor::traducir("lo ha CANCELADO")."";
			$LIGA=$_SESSION['S_LIGA_ACTUAL'];

    $id_Jugador_DESTINO=explode(';',$id_Jugadores);
   for ($i=0;$i<count($id_Jugador_DESTINO);$i++)
       {
         if (($id_Jugador_DESTINO[$i])&&($id_Jugador_DESTINO[$i]!=$_SESSION['S_id_usuario']))
         {

       			$Email1=devuelve_un_campo('jugadores',18,'id',$id_Jugador_DESTINO[$i]);
		       //		mandar mensaje

       			$mensaje = "<head><title>".Traductor::traducir("Mensaje privado en Rádical Padel")."</title></head><body><br>".Traductor::traducir("PARTIDO CANCELADO")."<br>".Traductor::traducir("Tienes un mensaje privado del jugador")." <b>$NAME</b> " .
       					"".Traductor::traducir("referente a la cancelación de un partido en la liga")." ".devuelve_un_campo('liga',1,'id',$LIGA)." <br>";
       			$mensaje.= " <a href=\"http://www.radicalpadel.com\">".Traductor::traducir("IR A LA WEB PARA LEERLO")."</a><br><br>";
       			$mensaje.= "<br>".Traductor::traducir("Un saludo. <br><br>Organización Rádical Padel")."<br><br><center><img src=\"http://www.radicalpadel.com/PROGRAMA/images/logo.jpg\"></center>";
       			$mensaje.="</body>";

                //Nuevo: gestion del push
                $id_creadorpa=devuelve_un_campo('partidos',7,'id',$id);
                $NMpa=devuelve_un_campo('jugadores',6,'id',$id_creadorpa);
                $APpa=devuelve_un_campo('jugadores',7,'id',$id_creadorpa);
                $fecha_pa=devuelve_un_campo('partidos',2,'id',$id);
                $hora_pa=devuelve_un_campo('partidos',3,'id',$id);
                $dia_eventopa=getDiaEvento($fecha_pa);

                $asunto_pushpa="$NMpa $APpa";
                $mensaje_pushpa="Cancelado el partido de $dia_eventopa a las $hora_pa h";

                if(!MensajePUSH ($id_Jugador_DESTINO[$i],(Componer_Notificacion($id_Jugador_DESTINO[$i],$asunto_pushpa,$mensaje_pushpa,'2',$LIGA,$id_partido))))
                {
      			   if(!mail($Email1, Traductor::traducir("Mensaje privado en Rádical Padel"),$mensaje,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com"))
      			   {       			$Mensaje=$Mensaje.'<br> (".Traductor::traducir("Error al enviar el correo, el jugador no lo ha recibido").")';      			}

      			   //echo "Notificacion Enviada";
                }
                $F_Desaparece = date('Y-m-d', strtotime('+2 days'));

				if(checkMensajesRepetidos($_SESSION['S_id_usuario'],$id_Jugador_DESTINO[$i],$Hoy,$Extra1,$Mensaje))
					die("Mensajes.php: No se puede mandar otro mensaje desde un jugador origen a un jugador destino dentro del mismo minuto en que se genero el ultimo mensaje enviado");

			    $query = "INSERT INTO mensajes2 VALUES ('','$LIGA','$Hoy','$Fecha_FIN','".$_SESSION['S_id_usuario']."','".$id_Jugador_DESTINO[$i]."','PARTIDO CANCELADO','$Mensaje','$Importancia','$Extra1','$id,".$_SESSION['S_id_usuario']."','$F_Desaparece','','','','','','','')";
		        mysql_query($query);
			//echo "$query";
         }
       }

	  		$datos_j1 = mysql_fetch_array(mysql_query("SELECT * FROM jugadores WHERE id=".$id_jugador));
		  		$datos_partido = mysql_fetch_array(mysql_query("SELECT * FROM partidos WHERE id=".$id));
		  		$datos_campo = mysql_fetch_array(mysql_query("SELECT * FROM campos WHERE id=".$datos_partido['id_Campo']));
		  		if($datos_campo['Convenio']=='S'){
		  			$fechapartido = explode('-',$datos_partido['Fecha']);
		  			$datos_partido['Fecha'] = $fechapartido[2].'/'.$fechapartido[1].'/'.$fechapartido[0];
		  		$mensajeaviso = Traductor::traducir("Hola: os informamos que el jugador que figuraba en la aplicación Rádical Padel como organizador de un partido en vuestro club el...");
		  		$mensajeaviso = str_replace('%NOMBRE_JUG%', $datos_j1['Nombre'].' '.$datos_j1['Apellidos'], $mensajeaviso);
		  		$mensajeaviso = str_replace('%TLF_JUG%', $datos_j1['Movil'], $mensajeaviso);
		  		$mensajeaviso = str_replace('%HORA%', $datos_partido['Hora'], $mensajeaviso);
		  		$mensajeaviso = str_replace('%FECHA%', $datos_partido['Fecha'], $mensajeaviso);
				$emailclub = $datos_campo['email'];
			if($emailclub!='')
			mail($emailclub, Traductor::traducir("Aviso de comprobacion de reserva"),$mensajeaviso,"FROM: organizacion@radicalpadel.com\nContent-type: text/html\r\n","-forganizacion@radicalpadel.com");
      		}


     baja_partidos ($id);
     //Nuevo: Eliminamos posibles invitaciones de pareja en la tabla correspondiente
     mysql_query("DELETE FROM partidos_pendientes_pareja WHERE id_partido=$id AND id_jugador_creador='".$_SESSION['S_id_usuario']."' LIMIT 1") or die("Error partidos-3693: Error al eliminar registro de la base de datos, contacte con soporte.");
     mysql_query("DELETE FROM partidos_mixtos WHERE id=$id LIMIT 1") or die("Error partidos-3694: Error al eliminar registro de la base de datos, contacte con soporte.");
     /////////////////////////
          //echo "<center>BAJA REALIZADA<br><br><a href='$php_self?menu=listar'><font color='red'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";


          header("Location: partidos.php?menu=miagenda");

     break;

     case 'miagenda':
     mini_cabecera_html();
          //opciones($registrado);
         Mini_TITULO_CSS ('box1',Traductor::traducir("Mi agenda de"),Traductor::traducir("Partidos"),'','SI');//    TITULO_CSS_FIN ();
          			$_NOMBRECONSULTA="Mi Agenda de Partidos";
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
	mini_cabecera_html();
	if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='filtrocontainer2'>";
}
          echo "<br><br><center>

          &iquest;Seguro que quiere apuntarse al partido $id?";

                    echo "<br><br><table border='1'>";
                    echo ("<tr>");
                    echo("<TD><form action='$PHP_SELF' method='get'>");
                    ////BOTON
                    echo ("<TD ALIGN=RIGHT VALIGN=TOP><p> <input type='submit' class='superboton' value='".Traductor::traducir("SI")."'></p></TD>");
                    echo ("<input type=hidden name=menu value='borrar'>");
                    echo ("<input type=hidden name=id value='$id'>");
                    echo ("</form></TD>");
                    echo("<TD><form action='$PHP_SELF' method='get'>");
                    ////BOTON
                    echo ("<TD ALIGN=RIGHT VALIGN=TOP><p> <input type='submit' class='superboton' value='".Traductor::traducir("NO")."'></p></TD>");
                    echo ("<input type=hidden name=menu value='listar'>");
                    echo ("</form></TD>");
                    echo ("</tr>");
                    echo "</table></center>";
                    if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}

     break;



     case 'apuntarseya':
         $Partido = new Partido($id);

         $Jugador = new Jugador($_SESSION['S_id_usuario']);
         Log::v(__FUNCTION__, "Nivel Jugador: ".$Jugador->obtenerNivelInicial(), true);
         if($Jugador->esPerfilJugadorIncompleto()){
             header("Location: jugadormodificadatoselmismo.php");
             return;
         }

         $tituloSecundario_cabecera = Traductor::traducir("a Partido");
         if (Sesion::obtenerDeporte()->obtenerId() != Deporte::ID_padel){
             $tituloSecundario_cabecera = Traductor::traducir("al")." ".Sesion::obtenerDeporte()->obtenerNombre();
         }

         ?>
         <link rel="stylesheet" type="text/css" href="./estilos_j.css<?php echo CacheJSyCSS::obtenerVersionCss();?>">
            <?php
            $Jugador = new Jugador($_SESSION["S_id_usuario"]);
            if ($Jugador->esModoOscuroActivado()){
                echo "<link rel='stylesheet' type='text/css' href='./estilos_modo_oscuro.css".CacheJSyCSS::obtenerVersionCSS()."'>";
            }
            ?>
        <?php


		 	if(!jugador_activo_liga($_SESSION['S_id_usuario'],$_SESSION['S_LIGA_ACTUAL']) && Sesion::obtenerJugador()->obtenerRolJugadorEnLiga(Sesion::obtenerLiga()->obtenerId()) != Juegalaliga::ROL_6 && Sesion::obtenerLiga()->esSuscripcion() == false)
			{
                VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_noTienesPermisosParaApuntarteAPartidos);
                die();

			}

		 	if($_SESSION['S_ROL']==4 && Sesion::obtenerLiga()->esSuscripcion() == false){
				VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_noTienesPermisosParaApuntarteAPartidos);
                die();
			}


		 	$Jugador = new Jugador($_SESSION["S_id_usuario"]);
		 	$fecha=mysql_fetch_array(mysql_query("SELECT Fecha FROM partidos WHERE id=$id"));
		 	$Fecha=$fecha['Fecha'];

	 		if ($Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], $Fecha, true) != ""){
	 		    $respuesta = $Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], $Fecha, true);


                  if ($respuesta == "JUGADOR_NO_TIENE_SUSCRIPCION"){
                        $PlanSuscripcion = $Jugador->obtenerPlanSuscripcionMayor("", "ALTA");
                      if ($PlanSuscripcion->esDePago()){
                         realizarInscripcionConPlanActual(Sesion::obtenerLiga()->obtenerId(), $Jugador->obtenerId());
                        $respuesta = $Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], $Fecha, true);
                      }
                    }

                if (!empty($respuesta)){
                     mini_cabecera_html();
                     Mini_TITULO_CSS ('box1',Traductor::traducir("APUNTARSE"),$tituloSecundario_cabecera);//    TITULO_CSS_FIN ();
                }


  				echo "
  				    <style type='text/css'>
  				        .contenedor_mensaje_error_crear_partido{
  				            text-align: center;
  				            margin: 10px;
  				        }
  				        
  				        .contenedor_mensaje_error_crear_partido .boton_ver_ventajas{
  				            border: 1px solid #0f4585;
                            color: #fff;
                            background: #1469CD;
                            padding: 8px 23px;
                            border-radius: 50px;
                            cursor: pointer;
  				        }
  				        .contenedor_mensaje_error_crear_partido .boton_ver_ventajas:hover{
  				            background: white;
                            color: #1469CD;
  				        }
  				        
  				        .bloqueado_partidos_gratis{
  				            background-color: red;
  				            color:white;
  				            padding: 10px;
  				            text-align: center;
  				        }
  				        
  				        .jugador_sin_suscripcion{
  				            background-color: red;
  				            color:white;
  				            padding: 10px;
  				            text-align: center;
  				        }
  				        
  				    </style>
  				";

  				modalIframe('',Traductor::traducir("Ver Ventajas"), false, true);




                //echo "Respuesta: $respuesta";
  				switch ($respuesta){


  				    case "JUGADOR_NO_TIENE_SUSCRIPCION":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_jugadorNoTieneSuscripcion);
                            die();
  				        break;

  				    case "BLOQUEADO_GRATIS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_bloqueadoGratis);

                        die();
  				        break;

  				    case "NUMERO_PARTIDOS_EXCEDIDOS_POR_MES_GLOBALMENTE":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidosPorMesGlobalmente);
                        die();
  				        break;

  				    case "NUMERO_PARTIDOS_EXCEDIDOS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidos);
                        die();
  				        break;

  				    case "EXCEDIDO_PARTIDOS_ENTRE_FECHAS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidosEntreFechas);
                        die();
  				        break;
  				}




	 		}

          $id_Jugador=$_SESSION['S_id_usuario'];
          $ERRO=Apuntar_a_partido ($id,$id_Jugador);


          //JMAM: INICO Añade el Jugador en la Reserva si Procede //////////////////////////////////////////////////////

          if(empty($idReservaPista) && $Partido->esReservaPistaPartido()){
              $idReservaPista = $Partido->obtenerReservaPistaPartido()->obtenerId();
          }

          //JMAM: Comprueba si se tiene que apuntar a una Reserva de Pista
          if ($idReservaPista > 0 && $ERRO > 0){
              //JMAM: Se tiene que apuntar a la Reserva de Pista
              $ReservaPista =new ReservaPista($idReservaPista);
              $ReservaPista->apuntarJugador($idJugador = $id_Jugador, $precio, $tipoPagoJugadorReserva, $pagoAplazado);

              //JMAM Notificación a Administradores si Partido Completo
                if ($ReservaPista->esPartidoCompletoPorJugadores()){

                    $arrayJugadoresAdministradores = Juegalaliga::obtenerJugadoresAdministradores($ReservaPista->obtenerLiga()->obtenerId());
                     foreach ($arrayJugadoresAdministradores as $JugadorAdministrador){

                         $idJugador = $JugadorAdministrador->obtenerId();
                         $nombreJugador = $JugadorAdministrador->obtenerNombre(true);
                         $telefonoJugador = $JugadorAdministrador->obtenerTelefono(true);

                         $Pista = $ReservaPista->obtenerPista();
                         $nombrePista = $Pista->obtenerNombre();

                         $fechaReservaPista = $ReservaPista->obtenerFechaReserva();
                         $horaReservaPista = $ReservaPista->obtenerHoraInicioReserva(true);

                         $dia = getDiaEvento($fechaReservaPista, false);
                         $fechaNormal = cambiaf_a_normal2($fechaReservaPista);

                         $asunto = Traductor::traducir("RESERVA COMPLETADA", false, $JugadorAdministrador->obtenerIdioma());
                         $mensaje = Traductor::traducir("Completado el partido organizado en", false, $JugadorAdministrador->obtenerIdioma())." $nombrePista ".Traductor::traducir($dia,false, $JugadorAdministrador->obtenerIdioma())." $fechaNormal ".Traductor::traducir("a las", false, $JugadorAdministrador->obtenerIdioma())." ".$horaReservaPista."h";

                         Notificacion::enviarNotificacion($idJugador, $asunto, $mensaje, Notificacion::TIPO_NOTIFICACION_verReservasAdmin, $ReservaPista->obtenerLiga()->obtenerId(), $ReservaPista->obtenerId(), Notificacion::ICONO_sustitucion);
                     }
                }

          }

          //JMAM: FIN Añade el Jugador en la Reserva si Procede ////////////////////////////////////////////////////////


          //Nuevo: avisa de partido abierto cuando se apunta la pareja, en partidos de parejas.
   		  //OJO: Hay que hacerlo antes de poner a S la confirmacion, para que el filtro de confirmado=n sirva.
          $sqlr = mysql_query("SELECT count(*) as res from partidos_pendientes_pareja WHERE confirmado='N' and id_partido=".$id) or die('Error partidos-1060: Consulta erronea, consulte con soporte.');
          $dtsr = mysql_fetch_array($sqlr);
          $part_parejas=intval($dtsr['res']);

		  if($part_parejas>0)
		  {
            $sql_par="SELECT id_Jugador1, Fecha, Hora, nivel_min, nivel_max, id_Liga,favoritos FROM partidos WHERE id=$id";
            $datos_par=mysql_fetch_array(mysql_query($sql_par));
            $p_j1=$datos_par['id_Jugador1'];
            $p_j2=$id_Jugador; //El que se esta apuntando ahora.
            $p_id_Liga=$datos_par['id_Liga'];
            $p_nivel_min=$datos_par['nivel_min'];
            $p_nivel_max=$datos_par['nivel_max'];
            $p_Fecha=$datos_par['Fecha'];
            $p_Hora=$datos_par['Hora'];
            $p_favoritos=$datos_par['favoritos'];
			gestion_aviso_partido_abierto($id,$p_j1, $p_j2, 'S',$p_id_Liga,$p_nivel_min,$p_nivel_max,$p_Fecha,$p_Hora,$p_favoritos);
			depurar_funcion_avisos("Aviso de partidos por pareja");
		  }
		  else
			depurar_funcion_avisos("No se ejecuta aviso al apuntarse el jugador por no ser partido parejas ya que se avisa al abrir el partido, o porque es el j3 o j4");
		  ////////////////////////////////


          //Nuevo: Actualizamos la tabla correspondiente por si habia una invitacion de pareja pendiente ponerla como confirmada
          $queryParejas="UPDATE partidos_pendientes_pareja SET Confirmado='S' WHERE Confirmado='N' AND id_partido=$id AND id_jugador_invitado=$id_Jugador";
          mysql_query($queryParejas) or die("Error partidos-3726: Error al insertar registro en base de datos, avise a soporte");
          //////////////////////////////////////

          mini_cabecera_html();
               	 Mini_TITULO_CSS ('box1',Traductor::traducir("APUNTARSE"),$tituloSecundario_cabecera);//    TITULO_CSS_FIN ();

               	 if ($ERRO > 0){

               	        //JMAM: Usuario Apuntado al Partido

               	        //JMAM: Enviar Push de Jugador Apuntado a todos los jugadores del Partido
               	        $Partido = new Partido($id);
               	        $Jugador = new Jugador($id_Jugador);

                        $Partido->enviarNotificacionJugadorApuntadoAPartidoJugadoresInscritos($id_Jugador);

                        /*
               	        $nombreJugador = $Jugador->obtenerNombre(true);

               	        $asunto = Traductor::traducir("Sobre tu Partido", false, $Jugador->obtenerIdioma())." ".$Partido->obtenerDiaHoraClubATexto(true, false, false, $Jugador->obtenerIdioma());
               	        $mensaje = $nombreJugador." ".Traductor::traducir("se ha apuntado a tu partido", false, $Jugador->obtenerIdioma())." ".$Partido->obtenerDiaHoraClubATexto();
               	        $Partido->enviarNotificacionAJugadoresInscritos($asunto, $mensaje, $id_Jugador);
                        */






               	        Registrar_Actividad ('4',$id);

               	        $titulo_push=Traductor::traducir("Apúntate al partido");
               	        $sql_par="SELECT id_Jugador1, Fecha, Hora, nivel_min, nivel_max, id_Liga,favoritos FROM partidos WHERE id=$id";
                        $datos_par=mysql_fetch_array(mysql_query($sql_par));
                        $fecha_p=$datos_par['Fecha'];;
                        $hora_p=$datos_par['Hora'];;
                        $nivel_min_p=$datos_par['nivel_min'];;
                        $nivel_max_p=$datos_par['nivel_max'];;

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
                        $urlCompartirWhatsapp = "*$titulo_push*\\r\\n$mensaje_push\\r\\n\\r\\n$urlCompartirPartido";

                        echo "
                            <script type='text/javascript'>
                                function compartirPartidoWhatsapp(){
                                    var url = '$urlCompartirWhatsapp';
                                    url = window.encodeURIComponent(url);
                                    
                                    if (typeof Android !== 'undefined'){
                                        window.open('whatsapp://send?text='+url,'_blank');
                                    }
                                    else{
                                        window.open('whatsapp://send?text='+url,'_blank');
                                    
                                    }
                                    
                               }
                             </script>
                        
                        ";

               	     ?>


               	     <div class="NW_BG_white">
                        <div class="NW_NW_altarealizada_cont">
                            <h2 class="NW_altarealizada">
                                <?php echo Traductor::traducir("INSCRIPCIÓN REALIZADA"); ?>
                            </h2>
                            <?php
                                 //Nuevo: complemento el mensaje si tiene mas partidos ese dia
                                 $fecha=mysql_fetch_array(mysql_query("SELECT Fecha FROM partidos WHERE id=$id"));
                                 $FechaSQL=$fecha['Fecha'];
                                 if(Tiene_Partido($FechaSQL,$id_Jugador)>1)
                                    echo "<br><br><font class='CP_alerta'>".Traductor::traducir("Recuerda que este día tienes más de un partido para jugar")."</font>";
                                 ///Fin nuevo
                            if($ERRO==1)
                             {
                                echo Traductor::traducir("Recordatorios desactivados");
                                 ?>
                                    <button class='superboton' style="margin-left:10px" onclick="document.location='recordatorios.php'"><?=Traductor::traducir("ACTIVAR")?></button>
                                <?php
                             }
                             else{
                                $datr =mysql_fetch_array(mysql_query("SELECT * FROM recordatorios WHERE id_jugador=".$id_Jugador.' AND id_partido='.$id));
                             ?>
                                <div class="NW_text_alta">
                                <?php echo "<strong>".Traductor::traducir("Recordatorio Creado")."</strong><br/>".Traductor::traducir("Se enviará el día").' '.date('d/m/y',$ERRO).' '.Traductor::traducir("a las").' '.date('h:i',$ERRO).' ';?>
                                <br>
                                <a href="recordatorios.php?idr=<?=$datr['id']?>"><?php echo Traductor::traducir("MODIFICAR"); ?></a>
                            </div>

                             <?php
                             }

                              $Jugador = new Jugador($_SESSION["S_id_usuario"]);
                              $enlaceRecomendacionWhatsApp = $Jugador->obtenerEnlaceRecomendacionWhatsApp($_SESSION["S_LIGA_ACTUAL"]);

                             ?>

                            <div class="NW_invitaamigos">
                                <h3><?php echo Traductor::traducir("INVITA A TUS AMIGOS"); ?></h3><br>

                                <div class="NW_enlacescompartir">
                                    <a onclick='compartirPartidoWhatsapp()'>
                                        <?php echo Traductor::traducir("Para que se apunten al partido (Si ya están inscritos en Radical Padel)");?>
                                    </a>

                                    <a href="<?php echo $enlaceRecomendacionWhatsApp; ?>">
                                       <?php echo Traductor::traducir("Para que se inscriban y se apunten GRATIS (Enviará tu enlace de recomendación y obtendrás bonificaciones)");?>
                                    </a>
                                </div>

                                <?php

                                     if ($tipoPagoJugadorReserva == ReservaPista::TIPOPAGOJUGADORRESERVA_TPV){

                                         $ReservaPista = new ReservaPista($idReservaPista);
                                          echo ReservaPista::generarFormularioInvisiblePagoReservaPista($id_Jugador,$idTarjeta,$precio,$ReservaPista->obtenerNumeroPedido(), 1);

                                          ?>
                                                <div style="color:red; text-decoration:underline; font-weight: bold">Realiza el Pago para Confirmar la Reserva de la Pista</div>
                                                <script type="text/javascript">

                                                    onclick_realizarPagoReserva();

                                                    function onclick_realizarPagoReserva(){
                                                        document.getElementById("formulario_realizarPago").submit();
                                                    }

                                                </script>
                                          <?php
                                      }
                                ?>

                                <a href="partidos.php?menu=miagenda" class="NW_listarpartidos"><?php echo Traductor::traducir("Ir a mi Agenda"); ?></a>

                            </div>

                        </div>
                    </div>

               	     <?php
               	 }
               	 else{
                     VerMensaje::mostrarTipoVerMensaje($ERRO);
               	     echo "<center>".Traductor::traducir("Error al apuntarse")."<br>$ERRO<br><br><a class='botonancho-3 CP_listarpartidos' href='partidos.php?menu=miagenda'><font>".Traductor::traducir("Ir a Mi Agenda")."</font></a></center>";
               	 }
               	 ?>



               	 <?php

			//	 if($_SESSION['S_LIGA_ACTUAL']==27){
			/*
				 if ($ERRO>0)
				 {
				 	Registrar_Actividad ('4',$id);
				 echo "<center><div class='CP_titulo_alta'><br>".Traductor::traducir("INSCRIPCIÓN REALIZADA")."</div><br><br>";

				 //Nuevo: complemento el mensaje si tiene mas partidos ese dia
				 $fecha=mysql_fetch_array(mysql_query("SELECT Fecha FROM partidos WHERE id=$id"));
                 $FechaSQL=$fecha['Fecha'];
	        	 if(Tiene_Partido($FechaSQL,$id_Jugador)>1)
                    echo "<br><br><font class='CP_alerta'>Recuerda que este d&iacute;a tiene m&aacute;s de un partido para jugar.</font>";
                 ///Fin nuevo

				 if($ERRO==1)
				 {
				 echo Traductor::traducir("Recordatorios desactivados");
				 ?>
				 <button class='superboton' style="margin-left:10px" onclick="document.location='recordatorios.php'"><?=Traductor::traducir("ACTIVAR")?></button>
				 <?
				 }else{
				 echo Traductor::traducir("<strong>Recordatorio Creado.</strong></br> Se enviar&aacute; el día").' '.date('d/m/y',$ERRO).' '.Traductor::traducir("a las").' '.date('h:i',$ERRO).' ';
				 $datr =mysql_fetch_array(mysql_query("SELECT * FROM recordatorios WHERE id_jugador=".$id_Jugador.' AND id_partido='.$id));
				 ?>
				 <button class='superboton' style="margin-left:10px" onclick="document.location='recordatorios.php?idr=<?=$datr['id']?>'"><?=Traductor::traducir("MODIFICAR")?></button>
				 <?
				 }
				 echo "<br><br><a href='$php_self?menu=miagenda' class='botonancho-3 CP_listarpartidos'><font>".Traductor::traducir("Mi Agenda")."</font></a></center>";
          }else echo "<center>ERROR AL APUNTARSE A PARTIDO<br>$ERRO<br><br><a class='botonancho-3 CP_listarpartidos' href='partidos.php?menu=listar'><font>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";*/

if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}

     break;
//Cancelar_participacion ($id_partido,$Jugador)

     case 'cancelar_participacion':

          $id_Jugador=$_SESSION['S_id_usuario'];
          Cancelar_participacion ($id,$id_Jugador);

        	Registrar_Actividad ('5',$id);

          //echo "<center>MODIFICACION REALIZADA<br><br><a href='$php_self?menu=listar'><font color='red'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";
          header("Location: partidos.php?menu=miagenda");

     break;

    /* nuevo jm 20-jul-15: metemos esta rama por jquery + abajo


    case 'buscar_sustituto':

          $id_Jugador=$_SESSION['S_id_usuario'];

          //Nuevo: avisa de partido abierto cuando se busca un sustituto.

	        $sql_par="SELECT id_Jugador1, Fecha, Hora, nivel_min, nivel_max, id_Liga,favoritos FROM partidos WHERE id=$id";
            $datos_par=mysql_fetch_array(mysql_query($sql_par));
            $p_j1='';//$id_Jugador; //El que se esta apuntando ahora.
            $p_j2='';
            $p_id_Liga=$datos_par['id_Liga'];
            $p_nivel_min=$datos_par['nivel_min'];
            $p_nivel_max=$datos_par['nivel_max'];
            $p_Fecha=$datos_par['Fecha'];
            $p_Hora=$datos_par['Hora'];
            $p_favoritos=$datos_par['favoritos'];                                                                              //Decimos que gestione como sustituto, y pasamos el id del partido, y el jugador que busca sustituto


			gestion_aviso_partido_abierto(0,$p_j1, $p_j2, 'N',$p_id_Liga,$p_nivel_min,$p_nivel_max,$p_Fecha,$p_Hora,$p_favoritos,true,$id,$id_Jugador);
            //die("Aviso de partidos por busqueda de sustituto<br>");
            //depurar_funcion_avisos("Aviso de partidos por busqueda de sustituto<br>");
		    ////////////////////////////////


          Buscar_Sustituto ($id,$id_Jugador);

        	Registrar_Actividad ('6',$id);

          //echo "<center>Se ha generado un mensaje solicitando sustituto<br><br><a href='$php_self?menu=listar'><font color='red'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";
          header("Location: $PHP_SELF?menu=listar");*/



         case 'buscar_sustituto':

          $id_Jugador=$_SESSION['S_id_usuario'];

          //Nuevo: avisa de partido abierto cuando se busca un sustituto.

	        $sql_par="SELECT id_Jugador1, Fecha, Hora, nivel_min, nivel_max, id_Liga,favoritos FROM partidos WHERE id=$id";
            $datos_par=mysql_fetch_array(mysql_query($sql_par));
            $p_j1='';//$id_Jugador; //El que se esta apuntando ahora.
            $p_j2='';
            $p_id_Liga=$datos_par['id_Liga'];
            $p_nivel_min=$datos_par['nivel_min'];
            $p_nivel_max=$datos_par['nivel_max'];
            $p_Fecha=$datos_par['Fecha'];
            $p_Hora=$datos_par['Hora'];
            $p_favoritos=$datos_par['favoritos'];


            mini_cabecera_html();
		    //Mini_TITULO_CSS ('box1','CREAR ','PARTIDO','','SI');

            																													//Decimos que gestione como sustituto, y pasamos el id del partido, y el jugador que busca sustituto
			//gestion_aviso_partido_abierto(0,$p_j1, $p_j2, 'N',$p_id_Liga,$p_nivel_min,$p_nivel_max,$p_Fecha,$p_Hora,$p_favoritos,true,$id,$id_Jugador);
            //Nuevo jm 20-jul-15: Avisos de sustituciones lo hacemos en jquery en gestion_aviso_partido_abierto

            /*echo '
			   <script  type="text/javascript">


					$.ajax({
						url: "gestion_aviso_partido_abierto.php?cron=false&id_partido=0&id_del_partido='.$id.'&id_Jugador_busca_sustituto='.$id_Jugador.'&a_sustituir=true",
						type: "GET",
						async:   true,
						error: function(req, err){ console.log("my message" + err); }

					});

				</script>
				';*/


                //JMAM: Envío de push en segundo plano
		        $cron = "false";
		        $id_partido = "$id";
		        $id_del_partido = "$id";
		        $id_Jugador_busca_sustituto = "$id_Jugador";
		        $a_sustituir = "true";

		        $orden = system("php5.6 gestion_aviso_partido_abierto_system.php $cron $id_del_partido $id_Jugador_busca_sustituto $a_sustituir >>gestion_aviso_partido_abierto_out.txt 2>>gestion_aviso_partido_abierto_error.txt &" );
                Log::v(__FUNCTION__, "ORDEN: $orden | CRON: $cron | ID Partido: $id_partido | ID del Partido: $id_del_partido | Id Jugador Busca Sustituto: $id_Jugador_busca_sustituto | A Sustituir: $a_sustituir", true);
                
		        /*
				 echo '
			   <script  type="text/javascript">
					$.get("gestion_aviso_partido_abierto.php?cron=false&id_partido=0&id_del_partido='.$id.'&id_Jugador_busca_sustituto='.$id_Jugador.'&a_sustituir=true");
				</script>
				';
		        */

				$url='http://www.radicalpadel.com/PROGRAMA/gestion_aviso_partido_abierto.php?cron=false&id_partido=0&id_del_partido='.$id.'&id_Jugador_busca_sustituto='.$id_Jugador.'&a_sustituir=true';

				/*$h=curl_init($url);
				curl_exec($h);*/

				/*$curl = curl_init();
				curl_setopt_array($curl, array(
						CURLOPT_CUSTOMREQUEST => 'GET',
    					CURLOPT_HTTPGET=> true,
    					CURLOPT_TIMEOUT=>9999,
    					CURLOPT_RETURNTRANSFER => false,
    					CURLOPT_FOLLOWLOCATION=>true,
    					CURLOPT_URL => 'http://www.radicalpadel.com/PROGRAMA/gestion_aviso_partido_abierto.php?cron=false&id_partido=0&id_del_partido='.$id.'&id_Jugador_busca_sustituto='.$id_Jugador.'&a_sustituir=true'
				));

				$result = curl_exec($curl);
				if(!$result)
					die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
				curl_close($curl);*/



				//echo '<iframe width="10px" height="10px" src="http://www.radicalpadel.com/PROGRAMA/gestion_aviso_partido_abierto.php?cron=false&id_partido=0&id_del_partido='.$id.'&id_Jugador_busca_sustituto='.$id_Jugador.'&a_sustituir=true"></iframe>';


				/*require_once('parallelcurl.php');
				$max_requests = 10;
				$curl_options = array(
				    CURLOPT_SSL_VERIFYPEER => FALSE,
				    CURLOPT_SSL_VERIFYHOST => FALSE,
			    	CURLOPT_USERAGENT, 'Parallel Curl test script',
				);

				$parallel_curl = new ParallelCurl($max_requests, $curl_options);


    			$search = '';
    			$search_url = 'http://www.radicalpadel.com/PROGRAMA/gestion_aviso_partido_abierto.php?cron=false&id_partido=0&id_del_partido='.$id.'&id_Jugador_busca_sustituto='.$id_Jugador.'&a_sustituir=true';
    			$parallel_curl->startRequest($search_url, 'on_request_done', $search);
				*/

				// This should be called when you need to wait for the requests to finish.
				// This will automatically run on destruct of the ParallelCurl object, so the next line is optional.
				//$parallel_curl->finishAllRequests();


//            die("gestion_aviso_partido_abierto.php?cron=false&id_partido=0&id_del_partido=$id&id_Jugador_busca_sustituto=$id_Jugador&a_sustituir=true");
            //depurar_funcion_avisos("Aviso de partidos por busqueda de sustituto<br>");
		    ////////////////////////////////


          Buscar_Sustituto ($id,$id_Jugador);

          Registrar_Actividad ('6',$id);

          if 	($_SESSION['S_Version']=='Movil'){
			echo "<div class='caja-texto'>";
		 }

		  echo "<br><br>";


          echo "<center>".Traductor::traducir("Se ha enviado un mensaje a todos los jugadores de la liga solicitando un sustituto")."</a></center>";
          echo"<br><br><center><a class='superboton' style='color:#2c69cd; font-weight: bolder' href='partidos.php?menu=miagenda'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";
          echo "<br><br>";
          //header("Location: $PHP_SELF?menu=listar");

		 if 	($_SESSION['S_Version']=='Movil'){
			echo "</div>";
		 }

     break;

     case 'sustituir':
			//$datos tiene el id del partido y el id del jugador a sustituir
			$separar = explode(',',$datos);
			$id_partido=$separar[0];
			$id_Jugador_A_SUST=$separar[1];
          $id_Jugador=$_SESSION['S_id_usuario'];


          	$Jugador = new Jugador($_SESSION["S_id_usuario"]);
		 	$fecha=mysql_fetch_array(mysql_query("SELECT Fecha FROM partidos WHERE id=$id_partido"));
		 	$Fecha=$fecha['Fecha'];

	 		if ($Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], $Fecha, true) != ""){
	 		    $respuesta = $Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], $Fecha, true);

	 		    mini_cabecera_html();
	 		    Mini_TITULO_CSS ('box1',Traductor::traducir("APUNTARSE"),Traductor::traducir("a Partido"));//    TITULO_CSS_FIN ();

  				echo "
  				    <style type='text/css'>
  				        .contenedor_mensaje_error_crear_partido{
  				            text-align: center;
  				            margin: 10px;
  				        }
  				        
  				        .contenedor_mensaje_error_crear_partido .boton_ver_ventajas{
  				            border: 1px solid #0f4585;
                            color: #fff;
                            background: #1469CD;
                            padding: 8px 23px;
                            border-radius: 50px;
                            cursor: pointer;
  				        }
  				        .contenedor_mensaje_error_crear_partido .boton_ver_ventajas:hover{
  				            background: white;
                            color: #1469CD;
  				        }
  				        
  				        .bloqueado_partidos_gratis{
  				            background-color: red;
  				            color:white;
  				            padding: 10px;
  				            text-align: center;
  				        }
  				        
  				        .jugador_sin_suscripcion{
  				            background-color: red;
  				            color:white;
  				            padding: 10px;
  				            text-align: center;
  				        }
  				        
  				    </style>
  				";

  				modalIframe('',Traductor::traducir("Ver Ventajas"), false, true);

                //echo "Respuesta: $respuesta";
  				switch ($respuesta){


  				    case "JUGADOR_NO_TIENE_SUSCRIPCION":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_jugadorNoTieneSuscripcion);

                          /*
  				         echo "<div class='jugador_sin_suscripcion'>".Traductor::traducir("¡Esta liga es sólo para Jugadores con Suscripción!, elige un Plan para Continuar")."</div>
                            <script type='text/javascript'>
                                mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\");
                            </script>
                        ";
                          */
                        die();
  				        break;

  				    case "BLOQUEADO_GRATIS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_bloqueadoGratis);

                          /*
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $search = array("%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
                        $replace = array($Plan1["nombre"], $Plan3["nombre"]);
                        $respuesta = str_replace($search,$replace,Traductor::traducir("Tu usuario no puede jugar partidos gratuitos en Rádical Pádel. Dicha limitación..."));
  				        echo "<div class='bloqueado_partidos_gratis'>".$respuesta."</div>
                            <script type='text/javascript'>
                                mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."&mostrarBloqueadoGratis=1\");
                            </script>
                        ";
                          */
                        die();
  				        break;

  				    /*case "LIGA_NO_PERMITE_CREAR_PARTIDOS_GRATIS":

  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $search = array("%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
                        $replace = array($Plan1["nombre"], $Plan3["nombre"]);
                        $respuesta = str_replace($search,$replace,Traductor::traducir("Tu suscripción GRATUITA NO te permite crear partidos en esta liga..."));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>

                              </div>";
                        die();
  				        break;*/

  				    case "NUMERO_PARTIDOS_EXCEDIDOS_POR_MES_GLOBALMENTE":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidosPorMesGlobalmente);

                          /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array("6", $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("hasta x partidos al mes (uno cada X días), pero no crearlos"));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;

  				    case "NUMERO_PARTIDOS_EXCEDIDOS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidos);

                          /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array($Liga->obtenerPartidosMensualesPlanGratis(), $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("hasta x partidos al mes (uno cada X días), pero no crearlos"));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;

  				    case "EXCEDIDO_PARTIDOS_ENTRE_FECHAS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidosEntreFechas);

                          /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array($Liga->obtenerPartidosMensualesPlanGratis(), $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("Tu suscripción GRATUITA sólo te permite apuntarte hasta X partidos al mes..."));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;
  				}




	 		}

          $MENS=Sustituir ($id_partido,$id_Jugador_A_SUST,$id_Jugador);
     		mini_cabecera_html();
			//echo " Sustituir ($id_partido,$id_Jugador_A_SUST,$id_Jugador)";
			if 	($_SESSION['S_Version']=='Movil'){
	echo "<div class='filtrocontainer2'>";
}
          echo "<center>";
          echo $MENS;
          //echo"Ya puede ver su nombre en el partido<br>Se ha enviado un SMS al jugador para avisarle de que ha sido sustituido";
          echo"<br><br><a href='partidos.php?menu=listar' class='botonancho-3'><font color='black'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";
          if 	($_SESSION['S_Version']=='Movil'){
	echo "</div>";
}
          //header("Location: $PHP_SELF?menu=listar");

     break;

	     case 'CancelarBuscarSustituto':
			//$datos tiene el id del partido y el id del jugador a sustituir
			$separar = explode(',',$datos);
			$id_partido=$separar[0];
			$id_Jugador_A_SUST=$separar[1];
          $id_Jugador=$_SESSION['S_id_usuario'];




          	$Jugador = new Jugador($_SESSION["S_id_usuario"]);
		 	$fecha=mysql_fetch_array(mysql_query("SELECT Fecha FROM partidos WHERE id=$id_partido"));
		 	$Fecha=$fecha['Fecha'];

	 		if ($Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], $Fecha, true) != ""){
	 		    $respuesta = $Jugador->puedeAccederPartido($_SESSION['S_LIGA_ACTUAL'], $Fecha, true);

	 		    mini_cabecera_html();
	 		    Mini_TITULO_CSS ('box1',Traductor::traducir("APUNTARSE"),Traductor::traducir("a Partido"));//    TITULO_CSS_FIN ();

  				echo "
  				    <style type='text/css'>
  				        .contenedor_mensaje_error_crear_partido{
  				            text-align: center;
  				            margin: 10px;
  				        }
  				        
  				        .contenedor_mensaje_error_crear_partido .boton_ver_ventajas{
  				            border: 1px solid #0f4585;
                            color: #fff;
                            background: #1469CD;
                            padding: 8px 23px;
                            border-radius: 50px;
                            cursor: pointer;
  				        }
  				        .contenedor_mensaje_error_crear_partido .boton_ver_ventajas:hover{
  				            background: white;
                            color: #1469CD;
  				        }
  				        
  				        .bloqueado_partidos_gratis{
  				            background-color: red;
  				            color:white;
  				            padding: 10px;
  				            text-align: center;
  				        }
  				        
  				        .jugador_sin_suscripcion{
  				            background-color: red;
  				            color:white;
  				            padding: 10px;
  				            text-align: center;
  				        }
  				        
  				    </style>
  				";

  				modalIframe('',Traductor::traducir("Ver Ventajas"), false, true);

                //echo "Respuesta: $respuesta";
  				switch ($respuesta){


  				    case "JUGADOR_NO_TIENE_SUSCRIPCION":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_jugadorNoTieneSuscripcion);

                          /*
  				         echo "<div class='jugador_sin_suscripcion'>".Traductor::traducir("¡Esta liga es sólo para Jugadores con Suscripción!, elige un Plan para Continuar")."</div>
                            <script type='text/javascript'>
                                mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\");
                            </script>
                        ";
                          */
                        die();
  				        break;

  				    case "BLOQUEADO_GRATIS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_bloqueadoGratis);

                          /*
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $search = array("%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
                        $replace = array($Plan1["nombre"], $Plan3["nombre"]);
                        $respuesta = str_replace($search,$replace,Traductor::traducir("Tu usuario no puede jugar partidos gratuitos en Rádical Pádel. Dicha limitación..."));
  				        echo "<div class='bloqueado_partidos_gratis'>".$respuesta."</div>
                            <script type='text/javascript'>
                                mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."&mostrarBloqueadoGratis=1\");
                            </script>
                        ";
                          */
                        die();
  				        break;

  				    /*case "LIGA_NO_PERMITE_CREAR_PARTIDOS_GRATIS":

  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $search = array("%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
                        $replace = array($Plan1["nombre"], $Plan3["nombre"]);
                        $respuesta = str_replace($search,$replace,Traductor::traducir("Tu suscripción GRATUITA NO te permite crear partidos en esta liga..."));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>

                              </div>";
                        die();
  				        break;*/

  				    case "NUMERO_PARTIDOS_EXCEDIDOS_POR_MES_GLOBALMENTE":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidosPorMesGlobalmente);

                          /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array("6", $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("hasta x partidos al mes (uno cada X días), pero no crearlos"));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;

  				    case "NUMERO_PARTIDOS_EXCEDIDOS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidos);

                          /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array($Liga->obtenerPartidosMensualesPlanGratis(), $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("hasta x partidos al mes (uno cada X días), pero no crearlos"));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;

  				    case "EXCEDIDO_PARTIDOS_ENTRE_FECHAS":
                          VerMensaje::mostrarTipoVerMensaje(VerMensaje::TIPO_VER_MENSAJE_numeroPartidosExcedidosEntreFechas);

                          /*
  				        $Liga = new Liga($_SESSION['S_LIGA_ACTUAL']);
  				        $Plan1 = new Plan(1);
  				        $Plan3 = new Plan(3);

  				        $searh = array("%NUMERO_PARTIDOS_MENSUALES_LIGA%", "%DIAS_DIFERENCIA_ENTRE_PARTIDOS%", "%NOMBRE_PLAN1%", "%NOMBRE_PLAN3%");
  				        $replace = array($Liga->obtenerPartidosMensualesPlanGratis(), $Liga->obtenerDiasDiferenciaEntrePartidosPlanGratis(), $Plan1["nombre"], $Plan3["nombre"]);
  				        $respuesta = str_replace($searh,$replace, Traductor::traducir("Tu suscripción GRATUITA sólo te permite apuntarte hasta X partidos al mes..."));
  				        echo "<div class='contenedor_mensaje_error_crear_partido'>
                                    <div>$respuesta</div>
                                    <br/>
                                    <br/>
                                    <div class='boton_ver_ventajas' onclick='mostrarOcultarModalIframe(true,\"modulo_suscripciones/planes.php?idLiga=".$_SESSION['S_LIGA_ACTUAL']."\")'>".Traductor::traducir("Ver Ventajas")."</div>
                                    
                              </div>";
                          */
                        die();
  				        break;
  				}




	 		}





           CANCELAR_Buscar_Sustituto ($id_partido,$id_Jugador);

          //echo "<center>Ha dejado de buscar sustituto<br><br><a href='$php_self?menu=listar'><font color='red'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";
          header("Location: partidos.php?menu=listar");

     break;

     case 'resultado':
     mini_cabecera_html();
     	 Mini_TITULO_CSS ('box1',Traductor::traducir("GRABAR"),Traductor::traducir("resultado"),'','SI');//    TITULO_CSS_FIN ();
          formulario (puntuacion,$id);
	//	 TITULO_CSS_FIN ();
			//echo "<center><br>Formulario para meter resultado<br>";
			//echo "<br>BLOQUEADO<br>Hasta activaciï¿œn de mensajes de usuario</center>";

          //$id_Jugador=$_SESSION['S_id_usuario'];
          //Apuntar_a_partido ($id,$id_Jugador);

          //echo "<center>MODIFICACION REALIZADA<br><br><a href='$php_self?menu=listar'><font color='red'>".Traductor::traducir("LISTAR MENSAJES")."</font></a></center>";
          //header("Location: $PHP_SELF?menu=listar");

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
							<div id='contentwrap' class='contendor_principal'>
							";
	        echo "<div class='filtrocontainer2' style='text-align:center'>
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

        echo '<input style="display:inline-block;" readonly class="fechac botonancho optselected" id="fecha" type="text" value="'.$fechavalue.'" onchange="actualizarSelectorHoras()"/>';

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

//			$_SQL="SELECT * FROM partidos WHERE id_Liga=".$_SESSION['S_LIGA_ACTUAL']." AND Fecha='$fecha' AND id_Jugador_ApuntaResult<>'0'";
 //        $_NOMBRECONSULTA="<center>".Traductor::traducir("RESULTADOS del día ")."".cambiaf_a_normal($fecha)."</center>";
         //$_Subtitulo="<center>Los resultados que se pueden corregir tienen el botï¿œn <img src='./images/editar.gif' width='30' border=1>";
         //echo "$_SQL";

  //        listar_partidos ($texto,0,$ARR,$pag,$order,$where,$Exportar,'','1');


		  //////////////////////////////////
		  //////////////////////////////////////////
		  ///////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///Resultado concretos PARTIDOS
if 	($_SESSION['S_Version']!='Movil') echo "<table width='600' border =0><tr>";
else  echo "<div class='filtrocontainer2' style='margin-top:0px'> <table width='100%' border =0><tr>";


echo"<tr><td colspan=3><b><br>".Traductor::traducir("RESULTADOS del día ")."".cambiaf_a_normal($fecha)."</b></td></tr>";

// echo ("<td width='13%' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("Fecha")."</td>");
 echo ("<td width='26%' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("Partido")."</td>");
 //echo ("<td width='7%' class='cabecera'><p class='textablacabecera'>Puntos</td>");

if 	($_SESSION['S_Version']!='Movil')  echo ("<td width='51%' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("POS")." &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   " .
 		"".Traductor::traducir("Jugadores")." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  |  +-".Traductor::traducir("NIVEL")."  |   ".Traductor::traducir("PUNT")."</td>");
else echo ("<td width='51%' class='cabecera'><p class='textablacabecera'>".Traductor::traducir("POS")." &nbsp;&nbsp;|&nbsp;" .
 		"".Traductor::traducir("Jugadores")." &nbsp; |  +-".Traductor::traducir("NIVEL")."  |   ".Traductor::traducir("PUNT")."</td>");
// echo ("<td width='9%' class='cabecera'><p class='textablacabecera'>+-Hcp</td>");

 echo"</tr><tr><td>";

$arrapar = array();
$q="SELECT P.id,P.Fecha,P.Hora,P.TipoPuntuacion,P.id_campo,P.Otro_Campo,P.id_Jugador1,P.Puntos_J1,P.id_Jugador2,P.Puntos_J2," .
		"P.id_Jugador3,P.Puntos_J3,P.id_Jugador4,P.Puntos_J4,R.PUNTOS,R.id_jug2,R.id_jug3,R.id_jug4,R.FechaResult,R.HoraResult," .
		"R.Extra1,R.Extra2,R.Extra3,P.id_Jugador_ApuntaResult
FROM  partidosArchivados AS P, resultados AS R
WHERE P.Fecha='".$fecha."' AND P.id_liga=".$_SESSION['S_LIGA_ACTUAL']." AND P.id=R.id_partido ORDER BY P.Hora asc";

Log::v(__FUNCTION__, $q, true);
$si=mysql_query($q);

 while (list($id_partido,$P_Fecha,$P_Hora,$P_TipoPuntuacion,$P_id_campo,$P_Otro_Campo,$P_id_Jugador1,$P_Puntos_J1,$P_id_Jugador2,$P_Puntos_J2,$P_id_Jugador3,$P_Puntos_J3,$P_id_Jugador4,$P_Puntos_J4,$PUNTOS,$id_jug2,$id_jug3,$id_jug4,$FechaResult,$HoraResult,$Extra1,$Extra2,$Extra3,$QuienApunta) = mysql_fetch_array($si))
                   {
if(in_array($id_partido,$arrapar)){
continue;
}else{
$arrapar[] = $id_partido;
}
////////////nueva fila
if ($colortoca!=0){echo ("<tr bgcolor='#FFFFFF'>");$clase='fila';$colortoca=0;}
else {echo ("<tr bgcolor='#FFFFFF'>");$clase='fila2';$colortoca=1;};

//if (1!='') {echo ("<td class=$clase align=center><p class='dato'><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p></td>");} else {echo ("<td class=$clase> <br> </td>");};

//Datos del Partido
echo ("<td class=$clase align=center>");
if ($P_id_campo) echo("<p class='dato' align=center>".devuelve_un_campo ("campos",2,"id",$P_id_campo)."<br>$P_TipoPuntuacion<br><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p>");
else echo("<p class='dato' align=center>$P_Otro_Campo<br>$P_TipoPuntuacion<br><b>".cambiaf_a_normal($P_Fecha)."</b><br>$P_Hora</p>");
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

   echo "<br>tmp " . round($tiempo_fin - $tiempo_inicio, 5);
   }
     break;



};//fin switch

//$CUANTOS=Tiene_Partido ('2011-07-21',$_SESSION['S_id_usuario']);
//echo "<script>alert('El jugador ".$_SESSION['S_id_usuario']." tiene $CUANTOS PARTIDOS el dï¿œa 2011-07-21')</script>";
if(!isset($_GET['pg'])){
echo"</td></tr></table>";

        pie_html();

}




    // This function gets called back for each request that completes
    function on_request_done($content, $url, $ch, $search) {
        
    /* $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
        if ($httpcode !== 200) {
            print "Fetch error $httpcode for '$url'\n";
            return;
        }

        $responseobject = json_decode($content, true);
        if (empty($responseobject['responseData']['results'])) {
            print "No results found for '$search'\n";
            return;
        }

        print "********\n";
        print "$search:\n";
        print "********\n";

        $allresponseresults = $responseobject['responseData']['results'];
        foreach ($allresponseresults as $responseresult) {
            $title = $responseresult['title'];
            print "$title\n";
        }*/
    }


?>