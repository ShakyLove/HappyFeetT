<?php
    session_start();
    include "../bd/conn.php";

    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])){

            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $usuario_id = $_SESSION['codigo'];

            $query_insert = mysqli_query($conn, "INSERT INTO proveedor(proveedor, contacto, telefono, direccion, usuario_id) 
                                                            VALUES('$proveedor', '$contacto', '$telefono', '$direccion', '$usuario_id')");

            if($query_insert){
                $alert = '<p class="msg_save">Proveedor guardado correctamente</p>';
            }else{

                $alert = '<p class="msg_error">Error al guardar el proveedor</p>';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro de Proveedor</title>
</head>
<body>
	<?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-people-carry"></i> Registro Proveedor</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>
            <form action="" method="POST">
                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" placeholder="Nombre del proveedor" id="proveedor">

                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" placeholder="Nombre completo del contacto" id="contacto">

                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" placeholder="Numero de telefono" id="telefono">

                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" placeholder="Direccion completa" id="direccion">

                <input type="submit" class="btn-save" value="Guardar Proveedor">
                <a href="listar_proveedor.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: #df4759; display: inline-block; text-align: center;">Cancelar</a>
            </form>
        </div>
    </section>
</body>
</html>