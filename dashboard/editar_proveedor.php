<?php
    session_start();
    include "../bd/conn.php";

    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])){

            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            $nit_prov = $_POST['nit_proveedor'];
            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            $sql_edit = mysqli_query($conn, "UPDATE proveedor 
                                                SET proveedor = '$proveedor', contacto = '$contacto', telefono = '$telefono', direccion = '$direccion'
                                                WHERE nit_proveedor = $nit_prov ");
                
                if($sql_edit){
                    
                    $alert = '<p class="msg_save">Proveedor actualizado correctamente</p>';
                }else{

                    $alert = '<p class="msg_error">Error al actualizar el proveedor</p>';
                }
            }
        }

    //mostrar datos 
    if(empty($_REQUEST['nit'])){

        header('location: listar_proveedor.php');

    }
    $nit_proveedor = $_REQUEST['nit'];

    $query = mysqli_query($conn, "SELECT * FROM proveedor WHERE nit_proveedor = $nit_proveedor");

    $result_sql = mysqli_num_rows($query);

    if($result_sql == 0){

        header('location: listar_proveedor.php');
    }else{
        while($row = mysqli_fetch_array($query)){

            $nit = $row['nit_proveedor'];
            $proveedor = $row['proveedor'];
            $contacto = $row['contacto'];
            $telefono = $row['telefono'];
            $direccion = $row['direccion'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Proveedor</title>
</head>
<body>
	<?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-people-carry"></i> Actualizar Proveedor</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>
            <form action="" method="POST">
                <input type="hidden" name="nit_proveedor" value="<?php echo $nit; ?>">
                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" placeholder="Nombre del proveedor" id="proveedor" value="<?php echo $proveedor; ?>">

                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" placeholder="Nombre completo del contacto" id="contacto" value="<?php echo $contacto; ?>">

                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" placeholder="Numero de telefono" id="telefono" value="<?php echo $telefono; ?>">

                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" placeholder="Direccion completa" id="direccion" value="<?php echo $direccion; ?>">

                <input type="submit" class="btn-save" value="Actualizar Proveedor">
                <a href="listar_proveedor.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: #df4759; display: inline-block; text-align: center;">Cancelar</a>
            </form>
        </div>
    </section>
</body>
</html>