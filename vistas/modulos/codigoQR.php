<?php
$respuesta = controladorOrden::ctrTodasLasOrdenes();


?>
<div class="container pt-5">

	<select name="ordenes" id="ordenes" class="form-control">
		<?php 

		foreach ($respuesta as $key => $value) {
				
			echo '<option value="'.$value['id_ord'].'">'.$value['id_ord'].'</option>';

		}

		 ?>
	</select>

	<button class="btn btnBuscalab float-right my-3 generarQr" name="submit">Generar QR</button>

</div>

<script>
		
	$(".generarQr").click(function(){

		var id = $("#ordenes").val();

		var datos = {

			"id":id

		}

		$.ajax({

			data:datos,
			type:"POST",
			url:url+"vistas/asset/ajax/generarQr.ajax.php",
			success:function(respuesta){

				console.log(respuesta);

			}

		})

	})

</script>