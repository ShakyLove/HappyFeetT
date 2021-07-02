<?php

    session_start();
    if($_SESSION['rol'] != 1){

        header('location: ./');
    }
    include "../bd/conn.php";

    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])){

            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            $codigo = $_POST['codigo'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $usuario = $_POST['usuario'];
            $contraseña = md5($_POST['contraseña']);
            $rol = $_POST['rol'];

            $query = mysqli_query($conn, "SELECT * FROM usuarios 
                                                    WHERE (usuario = '$usuario' AND codigo != $codigo) 
                                                    OR (correo = '$correo' AND codigo != $codigo )");
            $resultado = mysqli_fetch_array($query);

            if($resultado > 0){

                $alert = '<p class="msg_error">El correo o el usuario ya existe</p>';
            }else{

                if(empty($_POST['contraseña'])){

                    $sql_edit = mysqli_query($conn, "UPDATE usuarios 
                                                        SET nombre = '$nombre', correo = '$correo', usuario = '$usuario', rol = '$rol' 
                                                        WHERE codigo = $codigo ");

                }else{
                    
                    $sql_edit = mysqli_query($conn, "UPDATE usuarios 
                                                        SET nombre = '$nombre', correo = '$correo', usuario = '$usuario', contraseña = '$contraseña', rol = '$rol' 
                                                        WHERE codigo = $codigo ");
                }
                if($sql_edit){
                    
                    $alert = '<p class="msg_save">Usuario actualizado correctamente</p>';
                }else{

                    $alert = '<p class="msg_error">Error al actualizar el usuario</p>';
                }
            }
        }
    }
    //Mostrar datos 
    if(empty($_REQUEST['codigo'])){

        header('location: listar_usuarios.php');
        mysqli_close($conn);
    }
    $codigo_user = $_REQUEST['codigo'];

    $sql = mysqli_query($conn, "SELECT u.codigo, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol 
                                FROM usuarios u 
                                INNER JOIN rol r 
                                ON u.rol = r.id_rol
                                WHERE codigo = $codigo_user");
    mysqli_close($conn);
    $result_sql = mysqli_num_rows($sql);

    if($result_sql == 0){

        header('location: listar_usuarios.php');
    }else{
        $option = '';
        while($row = mysqli_fetch_array($sql)){

            $codigo = $row['codigo'];
            $nombre = $row['nombre'];
            $correo = $row['correo'];
            $usuario = $row['usuario'];
            $idrol = $row['idrol'];
            $rol = $row['rol'];

            if($idrol == 1){
                $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
            }else if($idrol == 2){
                $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
            }

        }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Usuario</title>
</head>
<body>
	<?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-user-edit"></i> Actualizar Usuario</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>
            <form action="" method="POST">
                <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" placeholder="Nombre completo" id="nombre" value="<?php echo $nombre; ?>">

                <label for="correo">Correo Electronico</label>
                <input type="email" name="correo" placeholder="Correo Electronico" id="correo" value="<?php echo $correo; ?>">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" placeholder="Nombre de usuario" id="usuario" value="<?php echo $usuario; ?>">

                <label for="contraseña">Contraseña</label>
                <input type="password" name="contraseña" placeholder="Contraseña" id="contraseña">

                <label for="rol">Tipo Usuario</label>
                <select name="rol" id="rol" class="notItemOne">
                <?php 
                    echo $option;
                ?>
                    <option value="1">Administrador</option>
                    <option value="2">Empleado</option>
                </select>

                <input type="submit" class="btn-save" value="Actualizar Usuario">
                <a href="listar_usuarios.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: #df4759; display: inline-block; text-align: center;">Cancelar</a>
            </form>
        </div>
    </section>
</body>
</html>