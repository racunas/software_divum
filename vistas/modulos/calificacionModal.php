<div class="modal fade" id="modalOpinion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-body" id="cuerpoModalCalificacion">
        
        <div class="row">
          <div class="col-12">
            <div class="txtNaranja txtBold txtGrande"><center>¡Gracias por usar Buscalab!</center></div>
          </div>
        </div>

        <div class="row">
          
          <div class="col-12">
            <div class="text-muted txtBold separacionGrande">
              <center>Para seguir ofreciendo una buena funcionalidad, requerimos que califiques el servicio de:</center>
            </div>

            <div class="separacionPequeña">
              <div class="txtAzul txtBold txtGrande">
                <center class="nombreLaboratorio colorBuscalab bold"><?php echo ucfirst($resumenOrden['nombre']); ?></center>
              </div>
            </div>

          </div>

        </div>

        <div class="error"></div>

        <hr>

        <div class="row separacionPequeña">
          <div class="col-12">
            <div class="text-muted txtBold separacionGrande text-center">
              Precio
            </div>
          </div>
          <div class="col-12" id="iconosCalif1">
            <i class="far fa-grin-stars mx-3 muyBien" id="5"></i>

            <i class="far fa-smile bien mx-3" id="4"></i>

            <i class="far fa-meh neutral mx-3" id="3"></i>

            <i class="far fa-frown regular mx-3" id="2"></i>

            <i class="far fa-angry mal mx-3" id="1"></i>
          </div>
        </div>

        <hr>

        <div class="row separacionPequeña">
          <div class="col-12">
            <div class="text-muted txtBold separacionGrande text-center">
              Tiempo de entrega
            </div>
          </div>

          <div class="col-12" id="iconosCalif2">
            <i class="far fa-grin-stars mx-3 muyBien" id="5"></i>

            <i class="far fa-smile bien mx-3" id="4"></i>

            <i class="far fa-meh neutral mx-3" id="3"></i>

            <i class="far fa-frown regular mx-3" id="2"></i>

            <i class="far fa-angry mal mx-3" id="1"></i>
          </div>
        </div>

        <hr>

        <div class="row separacionPequeña">
          <div class="col-12">
            <div class="text-muted txtBold separacionGrande text-center">
              Calidad de trabajo
            </div>
          </div>

          <div class="col-12" id="iconosCalif3">
            <i class="far fa-grin-stars mx-3 muyBien" id="5"></i>

            <i class="far fa-smile bien mx-3" id="4"></i>

            <i class="far fa-meh neutral mx-3" id="3"></i>

            <i class="far fa-frown regular mx-3" id="2"></i>

            <i class="far fa-angry mal mx-3" id="1"></i>
          </div>
        </div>

        <hr>

        <div class="error2"></div>

        <div class="row separacionPequeña">
          <div class="col-12">
            <textarea class="form-control" name="opinion" id="opinion" cols="5" rows="5" placeholder="Introduce una opinión..."></textarea>
          </div>
        </div>

        <div class="aprobado"></div>
        
      </div>

      <div class="modal-footer">
        <div class="btn-group">
          <input type="hidden" class="idOrden" value="<?php echo $idOrd; ?>">
          <input type="hidden" class="idTrabajo" value="<?php echo $idTrabajo."_".$tipoTrabajo; ?>">
          <button class="btn btnBuscalab txtBlanco mx-2" id="enviarOpinion">Enviar opinión</button>
          <button class="btn btn-outline-secondary mx-2" data-dismiss="modal">Cancelar</button>
        </div>
      </div>

    </div>

  </div>
</div>

<script>

      $("#modalOpinion").on('hidden.bs.modal',function(){
        $("#iconosCalif1 .seleccionadoOpinion").css("font-size","25px");
        $("#iconosCalif2 .seleccionadoOpinion").css("font-size","25px");
        $("#iconosCalif3 .seleccionadoOpinion").css("font-size","25px");

        $("#iconosCalif1 .seleccionadoOpinion").removeClass("seleccionadoOpinion");
        $("#iconosCalif2 .seleccionadoOpinion").removeClass("seleccionadoOpinion");
        $("#iconosCalif3 .seleccionadoOpinion").removeClass("seleccionadoOpinion");

        $(".error").html("");
        $(".error2").html("");

        $("#opinion").val("");
      });

      $("#enviarOpinion").click(function(){
        if(!$("#iconosCalif1 .seleccionadoOpinion").attr("id") || !$("#iconosCalif2 .seleccionadoOpinion").attr("id") || !$("#iconosCalif3 .seleccionadoOpinion").attr("id")){
          $(".error").html('<div class="alert alert-danger">Selecciona una calificación para cada caso.</div>');
        } else {

          if($("#opinion").val() == ""){
            $(".error2").html('<div class="alert alert-danger">Introduce una opinión personal.</div>');
          } else {
            var calif1 = $("#iconosCalif1 .seleccionadoOpinion").attr("id");
            var calif2 = $("#iconosCalif2 .seleccionadoOpinion").attr("id");
            var calif3 = $("#iconosCalif3 .seleccionadoOpinion").attr("id");
            var textopinion = $("#opinion").val();
            var id = $(".idOrden").val();
            var infoTrabajo = $(".idTrabajo").val();

            var idTrabajo = infoTrabajo.split("_");

            var datos = {
              "calif1" : calif1,
              "calif2" : calif2,
              "calif3" : calif3,
              "textopinion" : textopinion,
              "id" : id,
              "idTrabajo" : idTrabajo[0],
              "tipo": idTrabajo[1]
            };

            $.ajax({
              data: datos,
              type : "post",
              url : url+"vistas/asset/ajax/agregarOpinion.ajax.php",
              success: function(respuesta){
                if(respuesta){

                  swal({
                    type: 'success',
                    title: 'Gracias por tus comentarios',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#ff9900'
                  }).then( (result) => {

                    if(result.value){

                      $("#modalOpinion").modal('hide');

                      var califTotal = (parseInt(calif1) + parseInt(calif2) + parseInt(calif3)) / 3;

                      var nuevoStatus = 'Calificación: '+califTotal.toFixed(1)+' de 5 <i class="fas fa-star ml-2"></i>';

                      $(".ordenSinCalificar").html(nuevoStatus);
                      $(".ordenSinCalificar").addClass("ordenCalificada");
                      $(".ordenSinCalificar").attr("data-status",null);
                      $(".ordenSinCalificar").removeClass("ordenSinCalificar");

                    }

                  })
                  
                } else {

                  swal({
                    type: 'error',
                    title: 'Ocurrió un error interno, inténtalo de nuevo',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#ff9900'
                  });
                }
                
              }
            });
          }
          
        }

      });

      // 25px el tamaño del ícono

      //ALGORITMO PARA QUE NO SE QUITE EL CONTENIDO AL TENER EL CLIC ENCIMA DEL ELEMENTO
      /*$('.muyBien').popover({
        title : '',
        content : '<div class="alert alert-danger">Hola pinche putita</div> <div class="separacionGrande"><div class="alert alert-success">Jódete hijin</div></div>',
        trigger : 'manual',
        placement : 'top',
        html : true
      }).on("mouseenter", function () {
          var _this = this;
          $(this).popover("show");
          $(this).siblings(".popover").on("mouseleave", function () {
              $(_this).popover('hide');
          });
      }).on("mouseleave", function () {
          var _this = this;
          setTimeout(function () {
              if (!$(".popover:hover").length) {
                  $(_this).popover("hide")
              }
          }, 100);
      });*/

      $("#opinion").on('keyup',function(){
        $(".error2").html("");
      });

      $(".muyBien").mouseenter(function(){
        //$(this).css("font-size","50px");
        $(this).popover({
          title : '',
          content : '<b>Muy bien</b>',
          trigger : 'hover',
          placement : 'top',
          html : true
        });
        $(this).popover('show');
      });

      $(".bien").mouseenter(function(){
        //$(this).css("font-size","50px");
        $(this).popover({
          title : '',
          content : '<b>Bien</b>',
          trigger : 'hover',
          placement : 'top',
          html : true
        });
        $(this).popover('show');
      });

      $(".neutral").mouseenter(function(){
        //$(this).css("font-size","50px");
        $(this).popover({
          title : '',
          content : '<b>Neutral</b>',
          trigger : 'hover',
          placement : 'top',
          html : true
        });
        $(this).popover('show');
      });

      $(".regular").mouseenter(function(){
        //$(this).css("font-size","50px");
        $(this).popover({
          title : '',
          content : '<b>Mal</b>',
          trigger : 'hover',
          placement : 'top',
          html : true
        });
        $(this).popover('show');
      });

      $(".mal").mouseenter(function(){
        //$(this).css("font-size","50px");
        $(this).popover({
          title : '',
          content : '<b>Muy mal</b>',
          trigger : 'hover',
          placement : 'top',
          html : true
        });
        $(this).popover('show');
      });

      
     /* $(".muyBien").mouseleave(function(){
        if(!$(this).hasClass('seleccionadoOpinion')){
          $(this).css("font-size","25px");
        }
      });

      $(".bien").mouseleave(function(){
        if(!$(this).hasClass('seleccionadoOpinion')){
          $(this).css("font-size","25px");
        }
      });

      $(".neutral").mouseleave(function(){
        if(!$(this).hasClass('seleccionadoOpinion')){
          $(this).css("font-size","25px");
        }
      });

      $(".regular").mouseleave(function(){
        if(!$(this).hasClass('seleccionadoOpinion')){
          $(this).css("font-size","25px");
        }
      });

      $(".mal").mouseleave(function(){
        if(!$(this).hasClass('seleccionadoOpinion')){
          $(this).css("font-size","25px");
        }
      });*/

      $(".muyBien").click(function(){
        $(".error").html("");
        $(this).addClass("seleccionadoOpinion");
        $(this).css("font-size","35px");

        $(this).next().removeClass("seleccionadoOpinion");
        $(this).next().css("font-size","25px");

        $(this).next().next().removeClass("seleccionadoOpinion");
        $(this).next().next().css("font-size","25px");

        $(this).next().next().next().removeClass("seleccionadoOpinion");
        $(this).next().next().next().css("font-size","25px");

        $(this).next().next().next().next().removeClass("seleccionadoOpinion");
        $(this).next().next().next().next().css("font-size","25px");
      });

      $(".bien").click(function(){
        $(".error").html("");
        $(this).prev().removeClass("seleccionadoOpinion");
        $(this).prev().css("font-size","25px");

        $(this).addClass("seleccionadoOpinion");
        $(this).css("font-size","35px");

        $(this).next().removeClass("seleccionadoOpinion");
        $(this).next().css("font-size","25px");

        $(this).next().next().removeClass("seleccionadoOpinion");
        $(this).next().next().css("font-size","25px");

        $(this).next().next().next().removeClass("seleccionadoOpinion");
        $(this).next().next().next().css("font-size","25px");

      });

      $(".neutral").click(function(){
        $(".error").html("");
        $(this).prev().prev().removeClass("seleccionadoOpinion");
        $(this).prev().prev().css("font-size","25px");

        $(this).prev().removeClass("seleccionadoOpinion");
        $(this).prev().css("font-size","25px");

        $(this).addClass("seleccionadoOpinion");
        $(this).css("font-size","35px");

        $(this).next().removeClass("seleccionadoOpinion");
        $(this).next().css("font-size","25px");

        $(this).next().next().removeClass("seleccionadoOpinion");
        $(this).next().next().css("font-size","25px");
      });

      $(".regular").click(function(){
        $(".error").html("");
        $(this).prev().prev().prev().removeClass("seleccionadoOpinion");
        $(this).prev().prev().prev().css("font-size","25px");

        $(this).prev().prev().removeClass("seleccionadoOpinion");
        $(this).prev().prev().css("font-size","25px");

        $(this).prev().removeClass("seleccionadoOpinion");
        $(this).prev().css("font-size","25px");

        $(this).addClass("seleccionadoOpinion");
        $(this).css("font-size","35px");

        $(this).next().removeClass("seleccionadoOpinion");
        $(this).next().css("font-size","25px");
      });

      $(".mal").click(function(){
        $(".error").html("");
        $(this).prev().prev().prev().prev().removeClass("seleccionadoOpinion");
        $(this).prev().prev().prev().prev().css("font-size","25px");

        $(this).prev().prev().prev().removeClass("seleccionadoOpinion");
        $(this).prev().prev().prev().css("font-size","25px");

        $(this).prev().prev().removeClass("seleccionadoOpinion");
        $(this).prev().prev().css("font-size","25px");

        $(this).prev().removeClass("seleccionadoOpinion");
        $(this).prev().css("font-size","25px");

        $(this).addClass("seleccionadoOpinion");
        $(this).css("font-size","35px");
      });
</script>