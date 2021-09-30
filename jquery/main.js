$(document).ready(function () {
  let boton = document.getElementById("enviar");

  //Cambio de contraseña
  boton.addEventListener("click", function (e) {
    e.preventDefault();
    usuario = document.getElementById("usuario").value;
    pass = document.getElementById("pass").value;

    if (pass == "" || usuario == "") {
      Swal.fire({
        title: "Digite los campos",
        icon: "error",
        confirmButtonText: `Aceptar`,
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
        }
      });
    }

    //variable para ejecutar la accion
    var action = 1;
    $.ajax({
      url: "bd/login/fullLogin.php",
      type: "POST",
      async: true,
      data: { action: action, usuario: usuario, pass: pass },

      success: function (response) {
        if (response != "error") {
          if (response == "ok") {
            Swal.fire({
              title: "Se ha realizado el cambio de contraseña",
              icon: "success",
              confirmButtonText: `Aceptar`,
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                var url = "login.php";
                $(location).attr("href", url);
              }
            });
          }
        }
      },
      error: function (error) {},
    });
  });
  //Registro de usuarios
});
