$(window).scroll(function() {

    if($( window ).width() >= 991){

        posicionarMenu();
        
    }

});

function posicionarMenu() {
    var altura_del_header = $('.cabezotePrincipal').outerHeight(true);
    var altura_del_menu = $('.resumenOrdenes').outerHeight(true);

    if ($(window).scrollTop() >= altura_del_header){
        ////console.log(altura_del_header);
        $('.resumenOrdenes').addClass('fixed');
        //$('.wrapper').css('margin-top', (altura_del_menu) + 'px');
    } else {
        $('.resumenOrdenes').removeClass('fixed');
        //$('.wrapper').css('margin-top', '0');
    }
}

$(".btnVerLaboratorios").click(function(){

    window.location.href = url+"resultados/corona";

})

/*SCRIPT PARA HACER FUNCIONAR LOS BOTONES DE EDITAR Y ELIMINAR DEL CARRITO*/

$(".btnEliminarOrdenCarrito").click(function(){

    var idBox = $(this).attr("data-id");

    var datos = {
        "idBox":idBox
    }

    Swal({
        title: '¿Deseas eliminar la orden?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#a82c00',
        cancelButtonColor: '#a5a5a5',
        confirmButtonText: 'Si, eliminalo <i class="fas fa-check-circle"></i>',
        cancelButtonText: 'Cancelar'
    }).then((result) =>{

        if(result.value){

            $.ajax({
                data:datos,
                type:"POST",
                url:url+"vistas/asset/ajax/eliminarOrdenCarrito.ajax.php",
                context:this,
                success: function(respuesta){
                    if(!respuesta){ //SÍ REGRESO FALSE, SIGNIFICA QUE NO LO PUDO ELIMINAR CORRECTAMENTE
                        Swal({
                            title: 'Ocurrió un error',
                            text: "Inténtalo de nuevo",
                            type: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#a82c00',
                            cancelButtonColor: '#a5a5a5',
                            confirmButtonText: 'Si, eliminalo <i class="fas fa-check-circle"></i>',
                            cancelButtonText: 'Cancelar'
                        })
                    } else {
                        $(".elementoBox[data-id='"+idBox+"']").addClass("hidden");
                        $(".separadorBox[data-id='"+idBox+"']").addClass("hidden");

                        Swal({
                            title: 'Orden eliminada',
                            text: "",
                            type: 'success',
                            timer:1000
                        });
                    }
                }
            })

        }

    })

})

/*SCRIPT PARA EL BOTON DE EDITAR Y PODER CAMBIAR LOS DATOS DE LA ORDEN*/

datosOrdenBox = '';
idBox = '';
colorimetria = ''; //PARA VERIFICAR EN LAS DEMÁS FUNCIONES SÍ SE TRATA DE UN TRABAJO DE ORTODONCIA O DE PROTESIS
porcientoInicialTrabajo = '';
precioTrabajo = '';

$(".btnEditarOrdenCarrito").click(function(){

    idBox = $(this).attr("data-id");

    datosOrdenBox = {
        "idBox":idBox
    }

    $("#modalEditarOrden").modal("show");

});

$("#modalEditarOrden").on("shown.bs.modal",function(){
    
    $.ajax({
        type:"POST",
        data:datosOrdenBox,
        url:url+"vistas/asset/ajax/obtenerDatosOrdenCaja.ajax.php",
        context:this,
        beforeSend: function(){
            $(".cargandoOrden").html('<div class="text-center"><img width="120px;" src="'+url+'vistas/asset/images/cargando.gif" alt="cargando..." /><br /><p class="bold text-muted">Cargando...</p></div>');
        },
        success:function(respuesta){

            ////console.log(respuesta);

            $(".cargandoOrden").addClass("hidden");
            $(".datosGeneralesOrden").removeClass("hidden");
            $(".pagoInicialOrden").prop("disabled",false);

            var datosOrden = JSON.parse(respuesta);

            ////console.log(datosOrden);

            colorimetria = datosOrden['colorimetria'];
            porcientoInicialTrabajo = (colorimetria != null) ? datosOrden['trabajo']['porciento_inicial'] : datosOrden['trabajo']['porcentaje'];
            precioTrabajo = datosOrden['trabajo']['precio'];
            var nombrePaciente = datosOrden['paciente'];
            var pagoInicial = datosOrden['porcentajePagar'];
            var tipo = datosOrden['tipo'];
            var fechaRecepcion = datosOrden['fecha_rec'];
            var direccionRecepcion = datosOrden['recepcion'];
            var fechaEntrega = datosOrden['fecha_ent'];
            var direccionEntrega = datosOrden['entrega'];
            var estadoTrabajo = datosOrden['estado'];
            var dientes = datosOrden['dientes'];
            var descripcion = datosOrden['specs'];
            var imagenes = datosOrden['imagenes'];
            var nombreTrabajo = datosOrden['trabajo']['nomb'];
            var organosDentarios = dientes.split(",");

            tiempoEntrega = datosOrden['trabajo']['dias_entrega'];
            tiempoEntregaUrgente = datosOrden['trabajoUrgente']['dias_terminado'];
            precioTotal = precioTrabajo;
            precioTotalUrgente = datosOrden['trabajoUrgente']['precio'];


            /*COLOCAMOS EL TRABAJO Y EL PACIENTE QUE SE EDITARÁ*/
            $(".nombreTrabajoModal").html(nombreTrabajo.charAt(0).toUpperCase()+nombreTrabajo.slice(1));
            $(".nombrePacienteModal").html(nombrePaciente.charAt(0).toUpperCase()+nombrePaciente.slice(1));

            /*COLOCAMOS LA INFORMACIÓN EXISTENTE EN EL MODAL DE EDITAR*/

            //COLOCAMOS EL NOMBRE DEL PACIENTE
            $(".nombrePacienteOrden").val(nombrePaciente);

            //ALGORITMO PARA CALCULAR LAS CANTIDADES DE PAGO DEL TRABAJO
            var optionsPagoInicial = "";
            var porciento = porcientoInicialTrabajo*10;
            while(porciento<=10){

                let precioTotal = precioTrabajo;
                let precioParcial = (precioTotal * porciento) / 10 ;
                let porcientoTotal = porciento*10;


                if(porciento == pagoInicial*10){
                    optionsPagoInicial += '<option value="'+(porciento/10)+'" selected>$'+precioParcial+' por unidad ('+porcientoTotal+'%)</option>';                                             
                } else {
                    optionsPagoInicial += '<option value="'+(porciento/10)+'">$'+precioParcial+' por unidad ('+porcientoTotal+'%)</option>';
                }


                if( (++porciento) > 10 ){

                    --porciento;
                    porciento = porciento + 0.5;

                }
            }

            
            

            //ESPECIFICAR LAS CANTIDADES DE PAGO INICIALES DEL TRABAJO
            $(".pagoInicialOrden").html(optionsPagoInicial);

            //MARCAR SÍ ES ORDINARIO O URGENTE EL TRABAJO
            if(tipo == "ordinario"){
                $("#tipoOrdenOrdinario").prop("checked",true);
                $("#tipoOrdenUrgente").prop("checked",false);
            } else if(tipo == "urgente"){
                $("#tipoOrdenUrgente").prop("checked",true);
                $("#tipoOrdenOrdinario").prop("checked",false);
            }

            //ASIGNAR LA FECHA Y DIRECCIÓN DE RECEPCIÓN Y ENTREGA
            $(".fechaRecepcionOrden").val(fechaRecepcion);
            $(".direccionRecepcionOrden option[value='"+direccionRecepcion+"']").prop("selected",true);

            $(".fechaEntregaOrden").val(fechaEntrega);
            $(".direccionEntregaOrden option[value='"+direccionEntrega+"']").prop("selected",true);

            //ESPECIFICAR EL ESTADO DEL TRABAJO, SI ES A PRUEBA O TERMINADO
            if(estadoTrabajo == "2"){
                $("#prueba").prop("checked",true);
                $("#terminado").prop("checked",false);
                $(".pagoInicialOrden").prop("disabled",false);
            } else if(estadoTrabajo == "1"){
                $("#terminado").prop("checked",true);
                $("#prueba").prop("checked",false);
                $(".pagoInicialOrden").val(1);
                $(".pagoInicialOrden").prop("disabled",true);
            }

            //OCULTAMOS LA COLORIMETRIA EN CASO DE QUE NO SE UTILICE
            if(colorimetria != null){
                $(".colorimetriaOrden").parent().removeClass("hidden");
                $(".colorimetriaOrden option[value='"+colorimetria+"']").prop("selected",true);
            } else {
                $(".colorimetriaOrden").parent().addClass("hidden");
            }

            //MOSTRAMOS LOS DATOS DE EL ODONTOGRAMA, Y LAS ESPECIFICACIONES
            $(".descripcionOrden").val(descripcion);

            if(colorimetria == null){ $(".odontogramaGeneral").addClass("hidden"); } else { $(".odontogramaGeneral").removeClass("hidden"); }

            for(let i = 11; i <= 48; i++)
                $(".dientesOdontoOrden li[data-num='"+i+"']").removeClass("selected"); 

            for(let i = 0; i < organosDentarios.length; i++)
                $(".dientesOdontoOrden li[data-num='"+organosDentarios[i]+"']").addClass("selected");

            $(".precioTotal").html("$ "+(organosDentarios.length*precioTrabajo));

            //MOSTRAMOS LAS IMAGENES QUE EL USUARIO A SUBIDO A LA ORDEN
            var mostrarImagenes = '';

            for(let i = 0; i< imagenes.length; i++)
                mostrarImagenes += 
                '<div class="col-lg-4 col-md-6 col-sm-12 col-12">'+
                    '<img src="'+url+'vistas/asset/images/ordenes/'+imagenes[i]["nombre"]+'" alt="'+imagenes[i]["nombre"]+'" class="previsualizacionImagenOrden">'+
                    '<button class="btn btn-danger btnBorrarImagenOrden btn-sm" data-id="'+imagenes[i]["id_archivo"]+'"><i class="far fa-trash-alt"></i></button>'+
                '</div>';
            
            $("#respuestaSubidaImagen").html(mostrarImagenes);

            //MOSTRAMOS EL TIPO DE ORDEN, SI SOLO HAY ORDINARIOS O TAMBIÉN HAY URGENTES

            if(!datosOrden['trabajoUrgente']){
                $("#tipoOrdenUrgente").parent().addClass("hidden");
            } else {
                $("#tipoOrdenUrgente").parent().removeClass("hidden");
            }

        }
    });

});

$(".dienteOrden").click(function(){

    var numDiente = $(this).attr("data-num");
    var numSeleccionDientes = 0;

    if( $(this).hasClass("selected") ){

        //SÍ ESTÁ SELECCIONADO, LO DESELECCIONAMOS

        $(this).removeClass("selected");

    } else {

        $(this).addClass("selected");
        
    }

    for (var i = 11; i <= 48; i++) {


        if($(".dienteOrden[data-num='"+i+"']").hasClass("selected")){

            numSeleccionDientes++;
        }

    }

    if(numSeleccionDientes > 0){

        var totalPrecioOrden = ($("input[name='tipoOrdenModal']:checked").val() == 'urgente' ) ? numSeleccionDientes * precioTotalUrgente : numSeleccionDientes * precioTotal;

        //var totalPrecioOrden = numSeleccionDientes * precioTotal;

        $(".precioTotal").html("$ "+totalPrecioOrden);
        
    }

});

$("#respuestaSubidaImagen").on("click",".btnBorrarImagenOrden",function(e){

    e.preventDefault();

    var imageElement = $(this);
    var idImagen = imageElement.attr("data-id");

    if(idImagen == "temp"){ //SÍ ES IMAGEN AGREGADA POR EL INPUT

        imageElement.parent().remove();
        $("#nuevaOrdenImg").val(null);

    } else { //SÍ ES IMAGEN MOSTRADA DESDE LA BASE DE DATOS

        swal({
            title: 'Estás seguro?',
            text: 'Eliminarás la imagen de esta orden',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#9ac76d',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminalo',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if(result.value){

                var datos = {
                    "idBox":idBox,
                    "idImagen":idImagen
                }

                $.ajax({
                    data:datos,
                    type:"POST",
                    url:url+"vistas/asset/ajax/eliminarImagenOrden.ajax.php",
                    context:this,
                    success:function(respuesta){

                        //console.log(respuesta);

                        if(respuesta){

                            imageElement.parent().remove();
                        
                        }
                           
                    }
                })

            }

        })




    }

});

function calcularFechaEntregaOrden(){

    var tipoOrden = $("input[name='tipoOrdenModal']:checked").val();
    var hoy = new Date( $(".fechaRecepcionOrden").val() );
    var i=0;

    ////console.log(tipoOrden);

    if(tipoOrden == 'urgente'){

        //console.log("urgente:" +tiempoEntregaUrgente);

        while (i<=tiempoEntregaUrgente) {
          hoy.setTime(hoy.getTime()+24*60*60*1000); // añadimos 1 día
          if (hoy.getDay() != 6 && hoy.getDay() != 0)
            i++;  
        }

    } else if(tipoOrden == 'ordinario'){

        while (i<=tiempoEntrega) {
          hoy.setTime(hoy.getTime()+24*60*60*1000); // añadimos 1 día
          if (hoy.getDay() != 6 && hoy.getDay() != 0)
            i++;  
        }
        
    }
    
    mes = hoy.getMonth()+1;
    if (mes<10) mes = '0'+mes;
    fecha = hoy.getFullYear()+ '-' + mes + '-' + hoy.getDate();


    if(fecha != "NaN-NaN-NaN"){
        $(".fechaEntregaOrden").val(fecha);
    }

}

$(".fechaRecepcionOrden").change(calcularFechaEntregaOrden);

$(".fechaEntregaOrden").click(function(){

    $('.datepicker').datepicker("show");

});

/********************************************************************************/

/*VERIFICAR QUE ES TRABAJO URGENTE U ORDINARIO, PARA MODIFICAR LOS PRECIOS DE LA PREORDEN*/

function verificarTipoOrdenModal(){

    var precio = "";

    ////console.log($(this).val());

    if( $(this).val() == 'urgente' ){

        ////console.log("urgentes: "+precio+" "+tiempoEntrega);
        precio = precioTotalUrgente;
        calcularFechaEntregaOrden();

    } else if( $(this).val() == 'ordinario' ){

        ////console.log("ordinario: "+precio+" "+tiempoEntrega);
        precio = precioTrabajo;
        calcularFechaEntregaOrden();

    }

    var porcientoLaboratorio = porcientoInicialTrabajo;
    var porciento = porcientoLaboratorio * 10;
    var precioTotal, precioParcial, porcientoTotal, optionsPrecios = '';
    //$min = $porciento * 10;

    if(porciento == 10){
        
        //IMPRIMIMOS QUE ES 100% CUANDO SEA TRABAJO URGENTE O PIDA EL 100%
        /*$('#mySelect').append($('<option>', {
            value: 1,
            text: 'My option'
        }));*/
        optionsPrecios +=  '<option value="1" selected>$'+precio+' (100%)</option>';

    } else {

        var porcientoSelect = porciento;

        while(porciento<=10){

            precioTotal = precio;
            precioParcial = (precioTotal * porciento) / 10 ;
            porcientoTotal = porciento*10;

            var seleccionarTodoElPago = (porciento == 10) ? 'selected' : '';
            
            optionsPrecios += '<option value="'+(porciento/10)+'" '+seleccionarTodoElPago+'>$'+precioParcial+' por unidad ('+porcientoTotal+'%)</option>';
        

            if( (++porciento) > 10 ){

                --porciento;
                porciento = porciento + 0.5;

            }

        }

    }


    //$(".precioTipoTrabajo").html(precio);
    //$(".diasEntrega").html(tiempoEntrega);
    $(".pagoInicialOrden").html(optionsPrecios);

    ////console.log(optionsPrecios);

}

$("input[name='tipoOrdenModal']").click(verificarTipoOrdenModal);


/*******************************************************************/

$("input[name='estadoTrabajoOrden']").change(function(){

    if( $(this).val() == 1 ){

        $(".pagoInicialOrden").val('1');
        $(".pagoInicialOrden").prop("disabled",true);

    } else {

        $(".pagoInicialOrden").prop("disabled",false);

    }
    
});



//METODO PARA OBTENER TODOS LOS DATOS QUE EDITO EL USUARIO DE LA ORDEN Y ACTUALIZARLOS EN LA BD DESDE AJAX

function actualizarDatosOrdenPendiente(e){

    var formData; //VARIABLE PARA INSTANCIAR EL FORMULARIO A MANDAR POR AJAX

    var nombrePaciente = $(".nombrePacienteOrden").val();
    var pagoInicial = $(".pagoInicialOrden").val();
    var tipoOrden = $("input[name='tipoOrdenModal']:checked").val();
    var fechaRecepcion = $(".fechaRecepcionOrden").val();
    var direccionRecepcion = $(".direccionRecepcionOrden").val();
    var fechaEntrega = $(".fechaEntregaOrden").val();
    var direccionEntrega = $(".direccionEntregaOrden").val();
    var estadoOrden = $("input[name='estadoTrabajoOrden']:checked").val();
    var descripcionOrden = $(".descripcionOrden").val();

    /*ESTOS DATOS PUEDEN VARIAR DEPENDIENDO SÍ ES UN TRABAJO DE PROTESIS O DE ORTODONCIA*/

    var colorimetria = ($("#colorimetria").parent().hasClass("hidden")) ? null : $("#colorimetria").val();
    
    var dientesSeleccionados = '';
    var numSeleccionDientes = 0;

    if(colorimetria != null){ //REALIZAMOS EL PROCEDIMIENTO EN CASO DE QUE SEA UNA PROTESIS

        for (var i = 11; i <= 48; i++) {


            if($(".dienteOrden[data-num='"+i+"']").hasClass("selected")){

                numSeleccionDientes++;

                ////console.log($(".diente[data-num='"+i+"']").attr("data-num"));

                dientesSeleccionados += $(".dienteOrden[data-num='"+i+"']").attr("data-num")+",";

            }

        }

    }

    /***********************************************************************************/

    if( nombrePaciente.length >= 1 && 
        pagoInicial.length >= 1 && 
        tipoOrden.length >= 1 && 
        fechaRecepcion.length >= 1 && 
        direccionRecepcion.length >= 1 && 
        fechaEntrega.length >= 1 && 
        direccionEntrega.length >= 1 && 
        estadoOrden.length >= 1 && 
        descripcionOrden.length >= 1 &&
            (   
                (colorimetria == null && dientesSeleccionados.length < 1) || 
                (colorimetria != null && dientesSeleccionados.length >= 1) 
            ) 
        ){


        //VERIFICAMOS SÍ AGREGÓ UNA NUEVA IMAGEN O NO
        var imagenes = document.getElementById('nuevaOrdenImg').files;
        if(imagenes.length >= 1){ //SÍ EXISTE ALGUNAS IMAGENES, LAS AGREGAMOS AL FORMDATA

            if(imagenes.length <= 6){ //SÍ SON MENOS DE 6 IMAGENES, LAS AGREGAMOS

                formData = new FormData($("#subidaImagenPreOrden")[0]);

            } else {

                $("#respuestaSubidaImagen").append('<p class="text-danger">No puedes subir mas de 6 imágenes</p>');

            }

        } else {

            formData = new FormData();

        }

        formData.append("nombrePaciente",nombrePaciente);
        formData.append("pagoInicial",pagoInicial);
        formData.append("tipoOrden",tipoOrden);
        formData.append("fechaRecepcion",fechaRecepcion);
        formData.append("direccionRecepcion",direccionRecepcion);
        formData.append("fechaEntrega",fechaEntrega);
        formData.append("direccionEntrega",direccionEntrega);
        formData.append("estadoOrden",estadoOrden);
        formData.append("descripcionOrden",descripcionOrden);
        formData.append("colorimetria",colorimetria);
        formData.append("dientesSeleccionados",dientesSeleccionados);
        formData.append("numSeleccionDientes",numSeleccionDientes);
        formData.append("idBox",idBox);

        /*for (let pair of formData.entries()) {
            //console.log(pair[0]+ ', ' + pair[1]); 
        }*/

        $.ajax({
            data:formData,
            type:"POST",
            contentType: false,
            processData: false,
            url:url+"vistas/asset/ajax/actualizarOrdenPendiente.ajax.php",
            context:this,
            success:function(respuesta){

                //console.log(respuesta);

                if(!respuesta){
                    //AQUI MOSTRAMOS QUE HUBO UN ERROR

                    Swal({
                        title: 'Ocurrió un error interno',
                        text: "Comunícalo a Buscalab",
                        type: 'error',
                        timer:2000
                    });

                } else {

                    Swal({
                        title: 'Pre-orden actualizada',
                        text: "Envía YA tu orden al laboratorio",
                        type: 'success',
                        timer:1500
                    });

                }

            }
        })

    } else {

        Swal({
            title: 'No puedes dejar ningún campo vacío',
            text: "Recuerda llenar todos los campos y el odontograma (las imágenes son opcionales)",
            type: 'error'
        });        

    }


}

$(".btnGuardarEdicionOrdenCarrito").click(function(e){
    actualizarDatosOrdenPendiente(e);
});


$(".btnMostrarOdontogramaEditarOrden").click(function(){

    $(".datosGeneralesOrden").addClass("hidden");
    $(".odontogramaOrden").removeClass("hidden");

});

$(".regresarADetallesGenerales").click(function(){

    $(".datosGeneralesOrden").removeClass("hidden");
    $(".odontogramaOrden").addClass("hidden");

})

$("#modalEditarOrden").on("hidden.bs.modal",function(){
    
    $(".cargandoOrden").removeClass("hidden");
    $(".datosGeneralesOrden").addClass("hidden");
    $(".odontogramaOrden").addClass("hidden");

});

