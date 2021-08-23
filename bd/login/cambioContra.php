<?php 

    include("../conn.php");

    if(isset($_POST['change'])){

        if(empty($_POST['usuario'] || empty($_POST['contraseña']))){

            $_SESSION['message'] = 'Nombre de usuario incorrecto';
            $_SESSION['message_type'] = 'danger';
        }else{
            
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];

        $query = "UPDATE usuarios set contraseña = '$contraseña' WHERE usuario = '$usuario'";
        $resultado = mysqli_query($conn, $query);
        if(!$resultado){
            $_SESSION['message'] = 'Nombre de usuario incorrecto';
            $_SESSION['message_type'] = 'danger';
            header('location: ../../cambio.php');
        }
        $_SESSION['message'] = 'Cambio de contraseña exitoso';
        $_SESSION['message_type'] = 'secondary';
        header('location: ../../login.php');
    }
}


?>