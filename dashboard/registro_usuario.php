<?php
    session_start();
    if($_SESSION['rol'] != 1){

        header('location: ./');
    }
    include "../bd/conn.php";

    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['contraseña']) || empty($_POST['rol'])){

            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $usuario = $_POST['usuario'];
            $contraseña = md5($_POST['contraseña']);
            $rol = $_POST['rol'];

            $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario = '$usuario' OR correo = '$correo'");
            $resultado = mysqli_fetch_array($query);

            if($resultado > 0){

                $alert = '<p class="msg_error">El correo o el usuario ya existe</p>';
            }else{

                $query_insert = mysqli_query($conn, "INSERT INTO usuarios(nombre, correo, usuario, contraseña, rol) VALUES('$nombre', '$correo', '$usuario', '$contraseña', '$rol')");

                if($query_insert){
                    $alert = '<p class="msg_save">Usuario creado correctamente</p>';
                }else{

                    $alert = '<p class="msg_error">Error al crear el usuario</p>';
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
            <h1>Registro usuario</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>
            <form action="" method="POST">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" placeholder="Nombre completo" id="nombre">

                <label for="correo">Correo Electronico</label>
                <input type="email" name="correo" placeholder="Correo Electronico" id="correo">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" placeholder="Nombre de usuario" id="usuario">

                <label for="contraseña">Contraseña</label>
                <input type="password" name="contraseña" placeholder="Contraseña" id="contraseña">

                <label for="rol">Tipo Usuario</label>
                <select name="rol" id="rol">
                    <option value="1">Administrador</option>
                    <option value="2">Empleado</option>
                </select>

                <input type="submit" class="btn-save" value="Crear Usuario">
            </form>
        </div>
    </section>
</body>
</html>