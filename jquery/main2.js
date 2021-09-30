$(document).ready(function () {
  let boton = document.getElementById("enviar");

  boton.addEventListener("click", function (e) {
    e.preventDefault();

    nombre = document.getElementById("nombre").value;
    correo = document.getElementById("correo").value;
    user = document.getElementById("Usuario").value;
    contra = document.getElementById("contraseña").value;

    if (nombre == "" || correo == "" || user == "" || contra == "") {
      Swal.fire({
        title: "Digite los campos",
        icon: "error",
        confirmButtonText: `Aceptar`,
      });
    }

    if ($.isNumeric(nombre)) {
      console.log('cont numeros')
      document.getElementById("mensaje").innerHTML = "No se permiten números en este campo";
    }else if(nombre.length <= 5){
      document.getElementById("mensaje").innerHTML = "El nombre debe contener más de 5 caracteres";
    }

    var action = 2;
    $.ajax({
      url: "bd/login/fullLogin.php",
      type: "POST",
      async: true,
      data: { action: action, nombre: nombre, correo: correo, user: user, contra: contra },

      success: function (response) {
        if (response != "error") {
          if (response == "ok") {
            Swal.fire({
              title: "Cuenta creada con éxito",
              icon: "success",
              confirmButtonText: `Aceptar`,
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                var url = "login.php";
                $(location).attr("href", url);
              }
            });
          }else if(response == "existe"){
            Swal.fire({
              title: "Error al crear la cuenta",
              text: "El usuario o correo ya se encuentran registrados",
              icon: "error",
              confirmButtonText: `Aceptar`,
            });
          }
        }
      },
      error: function (error) {},
    });
  });
});
