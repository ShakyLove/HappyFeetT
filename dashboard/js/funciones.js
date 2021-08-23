$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
        var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
            }else{
                alert("No selecciono foto");
                $("#img").remove();
            }              
    });

    $('.delPhoto').click(function(){
    $('#foto').val('');
    $(".delPhoto").addClass('notBlock');
    $("#img").remove();

    if($("#foto_actual") && $("foto_remove")){
        $("#foto_remove").val('img_producto.png');
    }

    });

    // Buscar producto
    $('#txt_cod_producto').keyup(function(e){
        e.preventDefault();

        var producto = $(this).val();
        var action = 'infoProducto';

        if(producto != ''){

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action, producto:producto},
    
                success: function(response){

                    if(response != 'error'){

                        var info = JSON.parse(response);
                        $('#txt_descripcion').html(info.descripcion);
                        $('#txt_existencia').html(info.existencia);
                        $('#txt_cant_producto').val('1');
                        $('#txt_precio').html(info.precio);
                        $('#txt_precio_total').html(info.precio);

                        //Activar Cantidad
                        $('#txt_cant_producto').removeAttr('disabled');

                        //Mostrar boton Agregar
                        $('#add_product_venta').slideDown();
                    }else{

                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0.00');
                        $('#txt_precio_total').html('0.00');

                        //Bloquear cantidad
                        $('#txt_cant_producto').attr('disabled','disabled');

                        //Ocultar boton agregar
                        $('#add_product_venta').slideUp();
                    }
                },
                error: function(error){
    
                }
            });
        }
    });

    //Validar cantidad del producto antes de agregar
    $('#txt_cant_producto').keyup(function(e){
        e.preventDefault();
        
        var precio_total = $(this).val() * $('#txt_precio').html();
        var existencia = parseInt($('#txt_existencia').html());
        $('#txt_precio_total').html(precio_total);

        //Ocultar el boton agregar si la cantidad es menor que 1
        if(($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia)){
            $('#add_product_venta').slideUp();
        }else{
            $('#add_product_venta').slideDown();
        }
    });

    //Agreagr producto al detalle 
    $('#add_product_venta').click(function(e){
        e.preventDefault();

        if($('#txt_cant_producto').val() > 0){

            var codproducto = $('#txt_cod_producto').val();
            var cantidad = $('#txt_cant_producto').val();
            var action = 'addProductoDetalle';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action, producto:codproducto, cantidad:cantidad},
    
                success: function(response){

                    if(response != 'error'){

                        var info = JSON.parse(response);
                        $('#detalle_venta').html(info.detalle);
                        $('#detalle_totales').html(info.totales);

                        $('#txt_cod_producto').val('');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0.00');
                        $('#txt_precio_total').html('0.00');

                        //Bloquear cantidad
                        $('#txt_cant_producto').attr('disabled','disabled');

                        //Ocultar boton agregar
                        $('#add_product_venta').slideUp();
                    }else{
                        console.log('no data');
                    }
                    viewProcesar();
                },
                error: function(error){
    
                }
            });
        }
    });

    //Anular venta
    $('#btn_anular_venta').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_venta tr').length;
        if(rows > 0){

            var action = 'anularVenta';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action},
        
                success: function(response){

                    if(response != 'error'){

                        Swal.fire({
                            title: 'La venta ha sido anulada',
                            icon: 'error',
                            confirmButtonText: `Aceptar`,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                
                                
                                location.reload();
                            } 
                        });
                    }
                },
                error: function(error){
        
                }
            });
        }
    });

    //Procesar venta
    $('#btn_facturar_venta').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_venta tr').length;
        if(rows > 0){

            var action = 'procesarVenta';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action},
        
                success: function(response){

                    if(response != 'error'){
                        
                        Swal.fire({
                            title: 'Venta procesada con éxito',
                            icon: 'success',
                            confirmButtonText: `Aceptar`,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                
                                var url = 'listar_salidas.php';
                                $(location).attr('href',url);
                            } 
                        });
                    }else{
                        console.log('no data');
                    }
                },
                error: function(error){
        
                }
            });
        }
    });

    //$('.ver_modal').click(function(e){
      //  e.preventDefault();
        //$('.modal').fadeIn();
    //});


});//END READY-------------------------------------------------

function mostrarAlerta(titulo, texto, tipo){
    Swal.fire(
        titulo,
        texto,
        tipo
    );
}

function del_product_detalle(correlativo){
    var action = 'delProductoDetalle';
    var id_detalle = correlativo;

    $.ajax({
        url: 'ajax.php',
        type: "POST",
        async: true,
        data: {action:action, id_detalle:id_detalle},

        success: function(response){

            if(response != 'error'){

                var info = JSON.parse(response);
                    $('#detalle_venta').html(info.detalle);
                    $('#detalle_totales').html(info.totales);

                    $('#txt_cod_producto').val('');
                    $('#txt_descripcion').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');

                    //Bloquear cantidad
                    $('#txt_cant_producto').attr('disabled','disabled');

                    //Ocultar boton agregar
                    $('#add_product_venta').slideUp();
            }else{
                $('#detalle_venta').html('');
                $('#detalle_totales').html('');
            }
            viewProcesar();
        },
        error: function(error){

        }
    });
}

//Mostrar ocultar boton procesar
function viewProcesar(){
    if($('#detalle_venta tr').length > 0){
        
        $('#btn_facturar_venta').show();
    }else{
        $('#btn_facturar_venta').hide();
    }
}

function searchForDetalle(id){
    var action = 'searchForDetalle';
    var user = id;

    $.ajax({
        url: 'ajax.php',
        type: "POST",
        async: true,
        data: {action:action, user:user},

        success: function(response){

            if(response != 'error'){

                var info = JSON.parse(response);
                $('#detalle_venta').html(info.detalle);
                $('#detalle_totales').html(info.totales);

            }else{
                console.log('no data');
            }
            viewProcesar();
        },
        error: function(error){

        }
    });
}

function closeModal(){
    $('.modal').fadeOut();
}
function openModal(){
    $('.modal').fadeIn();
}

