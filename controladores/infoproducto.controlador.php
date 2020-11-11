<?php 

class ctrInfoproducto{

	public  static function ctrOrdenesUrgentesMaximas($idUsuario){
		$respuesta = mdlInfoproducto::mdlOrdenesUrgentesMaximas($idUsuario);

		return $respuesta;
	}

	public static function ctrCalificaciones($idTrabajo,$tipoTrabajo){

		$respuesta = mdlInfoproducto::mdlCalificaciones($idTrabajo,$tipoTrabajo);

		return $respuesta;

	}

	public static function obtenerDatosInfo($laboratorio,$trabajo){

		$res = mdlInfoproducto::datosInfo($laboratorio,$trabajo);

		return $res;

	}

	public static function tipoTrabajo($laboratorio,$trabajo){

		$res = mdlInfoproducto::mdlTipoTrabajo($laboratorio,$trabajo);

		return $res;

	}

	public static function geolocalizar($direccion,$laboratorio){

		echo '<script>

			var mapa = new GMaps({
			  div: "#mapa",
			  lat: 19.4887694,
			  lng: -99.0945809
			});

			mapa.zoomOut(1);
			
			GMaps.geocode({
			  address: "'.$direccion.'",
			  callback: function(results, status) {
			    if (status == "OK") {
			      var latlng = results[0].geometry.location;
			      mapa.addMarker({
			        lat: latlng.lat(),
			        lng: latlng.lng(),
			        infoWindow: {
					  content: "<p>Laboratorio: '.$laboratorio.'</p>"
					}
			      });

			      mapa.setCenter({
			      	lat: latlng.lat(),
			        lng: latlng.lng()
			      });
			    }
			  }
			});

       	</script>';

	}

	public static function ctrColorimetria($idColor){

		$respuesta = mdlInfoproducto::mdlColorimetria($idColor);

		return $respuesta;

	}

}

 ?>