<?php
?>
<script type="text/javascript">


//JMAM: Carga el buscador de direcciones de Google
$(function () {	
	$('#location').geocomplete({
		details: '.geo-details',
		detailsAttribute: 'data-geo'
	});
			
});

	
/**
 * @author JMAM
 * 
 * Comprueba si la direcci?n se ha recibido correctamente
 */	
function onchangeDireccion(){
	
	//JMAM: Obtiene los datos de la direcci?n obtenidos a trav?s de Google
	var localidad = document.getElementById('Localidad').value;
	var provincia = document.getElementById('Provincia').value;
	var pais = document.getElementById('Pais').value;
	
	//JMAM: Reseta los valores placholder de los campos donde se obtiene los datos de las direcci?n
	document.getElementById('Localidad').placeholder = "No se ha podido identificar la localidad";
	document.getElementById('Provincia').placeholder = "No se ha podido identificar la provincia";
	document.getElementById('Pais').placeholder = "No se ha podido identificar el pa?s";
	
	//JMAM: Depuraci?n
	console.log("Ha cambiado la direcci?n: LOCALIDAD->"+localidad+" Provincia->"+provincia+" Pais->"+pais);
	
	

	
	//JMAM: Comprueba si se ha obtenido la localidad
	if (localidad == ""){
		//JMAM: NO se ha obtenido la localidad
		
		//JMAM: Indica el mensaje de advertencia al usuario
		document.getElementById('Localidad').placeholder = "No se ha podido identificar la localidad";
	}
	
	//JMAM: Comprueba si se ha obtenido la provincia
	if (provincia == ""){
		//JMAM: NO se ha obtenido la provincia
		
		//JMAM: Comprueba si la localidad existe
		if (localidad != ""){
			//JMAM: La localidad existe
			
			//JMAM: Se le asigna a la provincia la localidad
			document.getElementById('Provincia').value = localidad;
			
		}
		else{
			//JMAM: La localdiad no existe
			
			//JMAM: Indica el mensaje de advertencia al usuario
			document.getElementById('Provincia').placeholder = "No se ha podido identificar la provincia";
		}
		
		
	}
	
	//JMAM: Comprueba si se ha obtenido el pais
	if (pais == ""){
		//JMAM: NO se ha obtenido el pa?s
		
		//JMAM: Indica el mensaje de advertencia al usuario
		document.getElementById('Pais').placeholder = "No se ha podido identificar el pa?s";
	}
	
	
	
}

	
	
</script>
	
<?php
?>	
