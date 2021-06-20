<?php

    include("../conn.php");

    if(isset($_POST['registrar'])){ 
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];
        $rol = $_POST['rol'];

        $query = "INSERT INTO usuarios(nombre, correo, usuario, contraseña, rol) VALUES('$nombre', '$correo', '$usuario', '$contraseña', '$rol')";
        $resultado = mysqli_query($conn, $query);
        if(!$resultado){
            $_SESSION['message'] = 'No se pudo crear la cuenta, intentelo de nuevo';
            $_SESSION['message_type'] = 'danger';
            header('location: ../../register.php');
        }
        $_SESSION['message'] = 'Cuenta creada con exito';
        $_SESSION['message_type'] = 'success';
        header('location: ../../login.php');
    }


?>