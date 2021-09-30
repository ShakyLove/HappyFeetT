<?php

session_start();
include "../conn.php";

if (!empty($_POST)) {

    $opcion = $_POST['action'];

    switch ($opcion) {

        case 1:

            $usuario = $_POST['usuario'];
            $pass = md5(mysqli_real_escape_string($conn, $_POST['pass']));

            $query_val = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario = '$usuario'");
            $result = mysqli_num_rows($query_val);

            if ($result > 0) {
                $query = mysqli_query($conn, "UPDATE usuarios SET contraseña = '$pass' WHERE usuario = '$usuario'");

                if ($query) {
                    echo 'ok';
                } else {
                    echo 'error';
                }
            } else {
                echo 'no';
            }
            break;

        case 2:

            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $usuario = $_POST['user'];
            $contra = md5(mysqli_real_escape_string($conn, $_POST['contra']));

            $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario = '$usuario' OR correo = '$correo'");
            $result = mysqli_num_rows($query);

            if($result > 0){
                echo 'existe';
            }else{

                $query_insert = mysqli_query($conn, "INSERT INTO usuarios(nombre, correo, usuario, contraseña, rol) VALUES('$nombre', '$correo', '$usuario', '$contra', 2)");

                if($query_insert){
                    echo 'ok';
                }else{
                    echo 'no';
                }
            }
            break;
        
        case 3: 

            $codigo = $_POST['codigo'];
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $id_usuario = $_SESSION['codigo'];

            $query_insert = mysqli_query($conn, "INSERT INTO entrada(cod_producto, cantidad,precio_entrada, usuario_id) VALUES('$codigo', '$cantidad', '$precio', '$id_usuario')");

            if($query_insert > 0){
                $query_update = mysqli_query($conn, "CALL actualizar_precio_producto($cantidad, $precio, $codigo)");

                if($query_update){
                    echo "ok";
                }else{
                    echo "error";
                }
            }else{
                echo "error no resultados";
            }

            break;
    }
} else {
    echo 'error no post';
}
