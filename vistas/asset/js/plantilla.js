function valida(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

$(document).ready(function(){

   /* var config = {
    apiKey: "AIzaSyAuYs76kTA7rezMcDQU6uw331nNL6VC_yo",
    authDomain: "buscalab-12be2.firebaseapp.com",
    databaseURL: "https://buscalab-12be2.firebaseio.com",
    storageBucket: "buscalab-12be2.appspot.com",
    messagingSenderId: "720124466116"
    };
    //firebase.initializeApp(config);
  // Retrieve Firebase Messaging object.

  var messaging = firebase.messaging();

  messaging.requestPermission()
  .then(function() {
    console.log('Se han aceptado las notificacion');
    return messaging.getToken();
  })*/

  
  // Add the public key generated from the console here.
  //messaging.usePublicVapidKey("BA7TMIDWE_VxhGZjxQJoKZybaun5R4hkf--nXvIHmJUSnsW0tA2j6z5yisEt0XRbF1E-2doJYejjUPS3UlgL-Z8");

  
 /* firebase.messaging().requestPermission()
  .then(function(t) {
   console.log('Recibido permiso.',);
   // En el parámetro "token" tienes el código para poder enviar las notificacion
    console.log(firebase.messaging()) ;

  })
  .catch(function(err) {
   console.log('No se ha obtenido permiso', err);
  });*/

  String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ""); };

  var listaTexto = ["Clic aquí y busca lo que necesitas como...","corona de metal porcelana","incrustación de ceromero","placas hawley","discilicato","prostodoncia total"]  
  setTimeout(maquinaLista(".buscar.typer",40,listaTexto,0),1000);

  function maquinaLista(contenedor, intervalo, listaTexto, indice){      
    if (indice == listaTexto.length){
        indice = 0;
    }
    
    maquina2(contenedor, listaTexto[indice], intervalo, listaTexto, indice);
  }

  function maquina2(contenedor, texto, intervalo, listaTexto, indiceLista){
    var indiceTexto = 0;
    var finalTexto = false;
    timer = setInterval( function(){			  
      if (indiceTexto == texto.length && finalTexto == false){

      	setTimeout(function(){finalTexto = true;},800);

      }
    		
      if (finalTexto == false) indiceTexto++
      else indiceTexto--;

      mostrarEliminarTexto(contenedor, texto, indiceTexto, finalTexto);

      if (finalTexto == true && indiceTexto == 0){
      	clearInterval(timer);
        maquinaLista(contenedor, intervalo, listaTexto, indiceLista+1);
      }		
    },intervalo)
  }	 

  function mostrarEliminarTexto(contenedor, texto, i, finalTexto){

    if (finalTexto){
      
      /*var textoContenedor = $("."+contenedor+".parpadea").html();
      console.log(textoContenedor);
      var textoMaquina = texto.substr(0,i--);

      var nuevoTexto = nuevoTexto += textoMaquina;
      nuevoTexto += textoContenedor; */

      $(contenedor).val(texto.substr(0,i--) + "|"); 
      

    } else{

      /*var textoContenedor = $(".parpadea").html();
      console.log(textoContenedor);
      var textoMaquina = texto.substr(0,i++);

      var nuevoTexto = nuevoTexto += textoMaquina;
      nuevoTexto += textoContenedor; */

      $(contenedor).val(texto.substr(0,i++) + "|");

    }

  }



});


$(".buscar").click(function(){

  /*if($(this).hasClass("typer")){

    $(this).val("");
    $(this).removeClass("typer");
  
  }*/


});

function mostrarModalIniciarSesion(){

  $("#modalIniciarSesion").modal("show");
  
}

function mostrarModalRegistro(){

  $("#modalRegistro").modal('show');
  
}


$(".inicioSesion").click(mostrarModalIniciarSesion);
$(".inicioSesionLateral").click(mostrarModalIniciarSesion);

$(".inicioSesionLateral").click(closeNav);

$(".registro").click(mostrarModalRegistro);
$(".registroLateral").click(mostrarModalRegistro);

$(".registroLateral").click(closeNav);

$(".modalRegistro").click(function(e){

  e.preventDefault();

  mostrarModalIniciarSesion();

});

/*CÓDIGO PARA PODER REALIZAR LA INTERACCIÓN DE LA BUSQUEDA*/

var rutaBuscador = url + "resultados";

$('.busqueda input').each(function() {
 var elem = $(this);

 // Save current value of element
 elem.data('oldVal', elem.val());

 // Look for changes in the value
 elem.bind("propertychange change click keyup input paste", function(event){
    // If value has changed...
    if (elem.data('oldVal') != elem.val()) {
     // Updated stored value
     elem.data('oldVal', elem.val());

     //// DO action 
     var busqueda = $(this).val().trim();

      //busqueda = busqueda.replace(/[áéíóúÁÉÍÓÚ ]/g, "-");

      var expression = /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]*$/;

      if(!expression.test(busqueda)){

        $(this).val("");

      } else {

        var evaluarBusqueda = busqueda.replace(/[áéíóúÁÉÍÓÚ ]/g, "-");

        //var rutaBuscador = $(".busqueda a").attr("href");

        if($(this).val() != ""){

          $(".busqueda a").attr("href", rutaBuscador+"/"+evaluarBusqueda);

        }

      }
       console.log(evaluarBusqueda);

     //// end action
   }
 });
});

/*CODIGO PARA QUE REACCIONE AL ENTER EL BUSCADOR*/

$(".busqueda input").focus(function(){

  $(document).keyup(function(e){

    e.preventDefault();

    if(e.keyCode == 13 && ( $(".busqueda input").val().length > 0 || $(".buscadorMovil .busqueda input").val().length > 0 )){

      var rutaBuscador2 = $(".busqueda a").attr("href");

      window.location.href = rutaBuscador2;

    }

  });

});

/*EVITAR QUE EL BUSCADOR ENVIE DATOS VACÍOS*/

$(".busqueda a").click(function(e){

  if( ( $(".busqueda input").hasClass("typer") && $(".buscadorMovil .busqueda input").hasClass("typer") ) || 
      ( $(".busqueda input").val().length == 0 && $(".buscadorMovil .busqueda input").val().length == 0 )  ){

    e.preventDefault();

  } else {
   
    var rutaBuscador2 = $(".busqueda a").attr("href");

    window.location.href = rutaBuscador2;
  
  }

})

/*SCRIPT PARA EL PANEL DERECHO MENU*/

function openNav() {
    document.getElementById("mySidenav").style.width = "200px";
    //document.getElementById("main").style.marginRight = "210px";
    $("#mySidenav").addClass("active");
    document.body.style.backgroundColor = "rgba(0,0,0,0)";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    //document.getElementById("main").style.marginRight= "0";
    $("#mySidenav").removeClass("active");
    document.body.style.backgroundColor = "white";
}

function mostrarBuscador(){

  $(".buscadorMovil").fadeIn();
  $(".buscadorMovil").addClass("show");

}

function ocultarBuscador(){

  $(".buscadorMovil").fadeOut();
  $(".buscadorMovil").removeClass("show");

}


$(".menuDispositivoMovil .fa-bars").click(openNav);

$(".menuDispositivoMovil .fa-search-location").click(function(){

  if($(".buscadorMovil").hasClass("show")){
    ocultarBuscador();
  } else {
    mostrarBuscador();
  }

});

//$(".cerrarMenuLateral").click(closeNav);

//FUNCION DE CERRAR SESION

$(document).on("click",".cerrarSesion",function(){

  $.ajax({
    url:url+"vistas/asset/ajax/cerrarSesion.ajax.php",
    success: function(respuesta){

      if(respuesta == 1){

        window.location.href = url;

      }

    }
  })

})

//////////////////////////////

//EFECTOS DE SCROLL PARA LAS ANIMACIONES

$(window).scroll(function(event) {
  var scrollTop = $(window).scrollTop();
  //console.log("Vertical "+scrollTop);

  var image = "";

  if(scrollTop >= 1323 && scrollTop <= 1423){
    var image = $(".paso1").attr("data-gif");
    $(".paso1").attr("src",image);

    //console.log("paso 1:"+image);
  }

  if(scrollTop >= 1837 && scrollTop <= 1937){
    var image = $(".paso2").attr("data-gif");
    $(".paso2").attr("src",image);
  }

  if(scrollTop >= 2311 && scrollTop <= 2411){
    var image = $(".paso3").attr("data-gif");
    $(".paso3").attr("src",image);
  }


});

function repetirGif(){

  var image = $(this).attr("data-gif");

  $(this).attr("src",image);

}

$(".paso1").click(repetirGif);
$(".paso2").click(repetirGif);
$(".paso3").click(repetirGif);

////////////////////////////////////////

///FUNCION PARA CAMBIAR EL TITLE DE LAS PAGINAS

$(window).ready(function(){

  var title = $("#title").val();

  $("title").html(title);

})

/////////////////////////////////////

//FUNCION PARA SELECCIONAR EL REGISTRO COMO TECNICO DENTAL

$(".crearCuentaTecnico").click(function(){

  $("#tecnico").prop("checked",true);

  $("#modalRegistro").modal('show');

})

$(".crearCuentaDentista").click(function(){

  $("#dentista").prop("checked",true);

  $("#modalRegistro").modal('show');

})

//SUGERENCIAS DE BUSQUEDA////////////////////7
/////////////////////////////////////////////7

var sugerencias = '';

var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};

$(document).ready(function(){

  $.ajax({
    type:"POST",
    url:url+"vistas/asset/ajax/sugerencias.ajax.php",
    context:this,
    success:function(respuesta){

      sugerencias = JSON.parse(respuesta);

      sugerencias.sort();

      //console.log(sugerencias);

      $('.buscar').typeahead({
        hint: false,
        highlight: true,
        minLength: 1
      },
      {
        name: 'sugerencias',
        source: substringMatcher(sugerencias),
        limit: 15,
        display:""
      });

      $('.buscarCabezote').typeahead({
        hint: false,
        highlight: true,
        minLength: 1
      },
      {
        name: 'sugerencias',
        source: substringMatcher(sugerencias),
        limit: 15,
        display:""
      });


    }

  })


})

function removerClaseTyper(){
  
  if($(this).hasClass("typer")){

    $(this).val("");
    $(this).removeClass("typer");
    

  }

}

$(".buscar").click(removerClaseTyper);
$(".buscarCabezote").click(removerClaseTyper);


/**SCRIPT PARA PODER CAMBIAR LA CONTRASEÑA DEL USUARIO QUE SE LE OLVIDÓ*/

$(".btnCambiarNuevaContraseña").click(function(){

  var nuevaContraseña = $(".nuevaContraseña").val();
  var confirmarNuevaContraseña = $(".confirmarNuevaContraseña").val();
  var key = $(".key").val();

  if( nuevaContraseña.length >= 8 &&
      nuevaContraseña.length >= 8 ){

    if(nuevaContraseña == confirmarNuevaContraseña){

      var datos = {
        "nuevaContraseña":nuevaContraseña,
        "confirmarNuevaContraseña":confirmarNuevaContraseña,
        "key":key
      }

      $.ajax({

        data:datos,
        url:url+"vistas/asset/ajax/cambiarContraseña.ajax.php",
        type:"POST",
        success:function(respuesta){

          console.log(respuesta);

          if(respuesta == 'no cumple'){

            swal({
              type: 'error',
              title: 'Verifica tus contraseñas, minimo son 8 caracteres y tienen que ser iguales',
              confirmButtonText: 'Ok',
              confirmButtonColor: '#9ac76d'
            });


          } else if(respuesta) {

            swal({
              type: 'success',
              title: 'Contraseña modificada correctamente',
              confirmButtonText: 'Iniciar sesión',
              confirmButtonColor: '#9ac76d'
            }).then((result) =>{

              if(result.value){

                window.location.href = url+"?iniciarSesion=true";
                
              }

            });


          } else {
            window.location.reload();
          }

        }

      })

    } else {

      console.log("las contraseñas deben ser iguales");

      swal({
        type: 'error',
        title: 'Ambas contraseñas deben ser iguales',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#9ac76d'
      });

    }

  } else {

    console.log("minimo de 8 caracteres");

    swal({
      type: 'error',
      title: 'La contraseña debe tener mínimo 8 caracteres',
      confirmButtonText: 'Ok',
      confirmButtonColor: '#9ac76d'
    });

  }

})

/****************************/
/*SCRIPT PARA notificacion*/
/****************************/

var timestampNotificacion;

function ultimaNotificacion(){

  $.ajax({
    type:"GET",
    url:url+"vistas/asset/ajax/obtenerNumeroNotificacionesNuevas.ajax.php",
    success:function(respuesta){
      //console.log(respuesta);
      if(respuesta >= 1){
        $(".campananotificacion").html('<p class="numnotificacion">'+respuesta+'</p>');
      }
    }
  });

  $.ajax({
    type:"GET",
    url:url+"vistas/asset/ajax/ultimaNotificacion.ajax.php",
    success:function(respuesta){
      timestampNotificacion = respuesta;
      verificarNotificacionNueva();
    }
  });

}

function verificarNotificacionNueva(){

  var datos = {
    "timestampNotificacion":timestampNotificacion
  }

  /*console.log("timestamp actual: ");
  console.log(datos);*/

  $.ajax({
    data:datos,
    type:"POST",
    url:url+"vistas/asset/ajax/verificarNuevaNotificacion.ajax.php",
    success:function(respuesta){

      //console.log(respuesta);


      if(respuesta != 0){

        let datosRespuesta = JSON.parse(respuesta);

        let titulo = datosRespuesta['titulo'];
        let mensaje = datosRespuesta['mensaje'];
        let icono = datosRespuesta['icono'];
        let color = datosRespuesta['color'];
        let ruta = datosRespuesta['url'];
        let tipoNotificacion = datosRespuesta['tipo'];
        let paginaActual = $("#paginaActual").val();

        timestampNotificacion = datosRespuesta['fecha'];

        var numNotif = parseInt($(".campananotificacion p").html());

        console.log("notificacion: "+numNotif);

        if(numNotif >= 1){
          $(".campananotificacion p").html(numNotif + 1);
        } else {
          $(".campananotificacion").html('<p class="numnotificacion">1</p>');
        }

        //console.log(tipoNotificacion);
        //console.log(paginaActual);

        if( !(paginaActual == "orden" && tipoNotificacion == "nuevoMensaje") ){

          $.smallBox({
            position:3,
            title:titulo,
            content:mensaje,
            width:450,
            /*img:url+"vistas/asset/images/dentista.png",*/
            fa:icono,
            color:color,
            timeout:7500
          },function(action, button){
            //Do something here

            if(action == "touchClose"){

              window.location.href = ruta;

            }
            
          });

        }

      }


      setTimeout('verificarNotificacionNueva()',2000);
    }
  })


}

$(document).ready(ultimaNotificacion());


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}