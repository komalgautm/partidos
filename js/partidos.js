function onclick_mostrarMensajeCortoOCompleto(id){
    var mostrandoMensajeCorto = document.getElementById("mostrandoMensajeCorto_"+id).value;

    var mostrarMensajeCorto = 1;
    if (mostrandoMensajeCorto == 1){
        mostrarMensajeCorto = 0;
    }

    document.getElementById("boton_mostrarMensaje_"+id).value = Traductor.traducir("Cargando...");

    var url = "modulo_suscripciones/config/clases/mensaje/procesar.php";
    var datos = new Object();
    datos.op="obtenerMensaje";
    datos.id = id;
    datos.mostrarMensajeCorto = mostrarMensajeCorto
    jQuery.ajax({
        data: datos,
        url:   url,
        type:  "post",
        success:  function (response) {
            document.getElementById("mensaje_"+id).innerHTML = response;
            document.getElementById("mostrandoMensajeCorto_"+id).value = mostrarMensajeCorto;

            switch (mostrarMensajeCorto){

                case 0:
                    document.getElementById("boton_mostrarMensaje_"+id).value = Traductor.traducir("Ocultar mensaje");
                    break;

                case 1:
                    document.getElementById("boton_mostrarMensaje_"+id).value = Traductor.traducir("Ver mensaje completo");
                    break;
            }
        }
    });

}

function onclick_abrirModalListadoJugadorPartido(idPartido, descripcion){
    mostrarOcultarModalIframe(true, "modulo_modales/listadoJugadoresPartido.php?idPartido="+idPartido, false, false, Traductor.traducir("Participantes")+": "+descripcion);
}

function obtenerMasPartidos(url){

    if (document.getElementById("moreload").style.display !== "none"){
        document.getElementById("moreload").style.display = "none";

        console.log("[obtenerMasPartidos] Url"+url);
        Toast.progress(Traductor.traducir("Obteniendo más partidos..."));
        jQuery.ajax({
            url:   url,
            type:  "post",
            success:  function (response) {
                $('ul:last').append(Utf8.decode(response));
                Toast.success(Traductor.traducir("Partidos obtenidos"));
                document.getElementById("moreload").style.display = "block";
                pgn++;
            }
        });
    }
    else{
        console.log("[obtenerMasPartidos]: No se carga más, esperando a obtener partidos de la petición previa");
    }

}