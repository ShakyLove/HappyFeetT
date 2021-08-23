<?php
session_start();
if ($_SESSION['rol'] != 1) {

    header('location: ./');
}
include "../bd/conn.php";

if (!empty($_POST)) {

    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['contraseña']) || empty($_POST['rol'])) {

        $alert = 1;
    } elseif (is_numeric($_POST['nombre'])) {

        $alert = 'nombre';
    } else {

        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $usuario = $_POST['usuario'];
        $contraseña = md5($_POST['contraseña']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario = '$usuario' OR correo = '$correo'");
        $resultado = mysqli_fetch_array($query);

        if ($resultado > 0) {

            $alert = 2;
        } else {

            $query_insert = mysqli_query($conn, "INSERT INTO usuarios(nombre, correo, usuario, contraseña, rol) VALUES('$nombre', '$correo', '$usuario', '$contraseña', '$rol')");

            if ($query_insert) {
                $alert = 3;
            } else {

                $alert = 4;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Registro de Usuarios</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-user-plus"></i> Registro usuario</h1>
            <?php isset($alert) ? $alert : ''; ?>
            <hr>
            <div class="alert"></div>
            <div class="forms">
                <form action="" method="POST">
                    <input type="hidden" id="valor_form" value="<?php echo $alert; ?>">
                    <div class="label">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" placeholder="Nombre completo" id="nombre">
                    </div>

                    <div class="label">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" name="correo" placeholder="Correo Electrónico" id="correo">
                    </div>

                    <div class="label">
                        <label for="usuario">Usuario</label>
                        <input type="text" name="usuario" placeholder="Nombre de usuario" id="usuario">
                    </div>

                    <div class="label">
                        <label for="contraseña">Contraseña</label>
                        <input type="password" name="contraseña" placeholder="Contraseña" id="contraseña">
                    </div>

                    <div class="label">
                        <label for="rol">Tipo Usuario</label>
                        <select name="rol" id="rol">
                            <option value="1">Administrador</option>
                            <option value="2">Empleado</option>
                        </select>
                    </div>

                    <input type="submit" class="btn-save" value="Crear Usuario">
                    <a href="listar_usuarios.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                    border-radius: 5px; background: black; color:white; display: inline-block; text-align: center;">Cancelar</a>
                </form>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        let ubicacionPrincipal = window.pageYOffset;
        window.onscroll = function Scroll() {
            let desplazamiento = window.pageYOffset;
            if (desplazamiento == 0) {
                document.getElementById('navegacion').style.display = 'block';
                document.getElementById('header').style.background = 'initial';
            } else {
                document.getElementById('navegacion').style.display = 'none';
                document.getElementById('header').style.background = 'white';
            }
            ubicacionPrincipal = desplazamiento;
        }

        valor = $('#valor_form').val();
        if (valor == 1) {

            Swal.fire({
                title: 'Todos los campos son obligatorios',
                icon: 'error',
                confirmButtonText: `Aceptar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    $('#valor_form').val('0');

                }
            });
        } else {
            if (valor == 3) {

                Swal.fire({
                    title: 'Usuarios registrado con éxito',
                    icon: 'success',
                    confirmButtonText: `Aceptar`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {

                        var url = 'listar_usuarios.php';
                        $(location).attr('href', url);
                    }
                });
            } else {
                if (valor == 'nombre') {

                    Swal.fire({
                        title: 'Error en el registro',
                        icon: 'error',
                        text: 'No se permiten números en el campo "Nombre"',
                        confirmButtonText: `Aceptar`,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {

                            $('#valor_form').val('0');

                        }
                    });
                }
            }
        }
    </script>
</body>

</html>