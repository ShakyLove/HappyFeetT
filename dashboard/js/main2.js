$(document).ready(function () {
  $("#saveForm").click(function (e) {
    e.preventDefault();
    cantidad = $("#cantidad").val();
    precio = $("#precio").val();
    codigo = $("#codigo").val();
    titulo = $("#titulo").html();

    if (cantidad == "" || precio == "") {
      e.preventDefault();
      Swal.fire({
        title: "Todos los campos son obligatorios",
        icon: "error",
        confirmButtonText: `Aceptar`,
      });
    } else if (cantidad < 10 || cantidad > 300) {
      $("#parrafo_cant").html("Cantidad no permitida");
    } else if (precio < 0 || !$.isNumeric(precio)) {
      $("#parrafo").html("No se permite este precio");
    }

    var action = 3;
    $.ajax({
      url: "../bd/login/fullLogin.php",
      type: "POST",
      async: true,
      data: {
        action: action,
        cantidad: cantidad,
        precio: precio,
        codigo: codigo,
      },

      success: function (response) {
        if (response != "error") {
          if (response == "ok") {
            mensaje = cantidad + " Unidades agregadas";
            console.log(titulo)
            Swal.fire({
              title: titulo,
              text: mensaje,
              icon: "success",
              confirmButtonText: `Aceptar`,
            }).then((result) => {
              if (result.isConfirmed) {
                var url = "listar_productos.php";
                $(location).attr("href", url);
              }
            });
          }
        }
      },
      error: function (error) {},
    });
  });
});
