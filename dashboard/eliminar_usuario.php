<?php
session_start();
if ($_SESSION['rol'] != 1) {

    header('location: ./');
}
include "../bd/conn.php";
if (!empty($_POST)) {

    if ($_POST['codigo'] == 1) {

        header('location: listar_usuarios.php');
        mysqli_close($conn);
        exit;
    }
    $codigo = $_POST['codigo'];

    //$query_delete = mysqli_query($conn, "DELETE FROM usuarios WHERE codigo = $codigo");
    $query_delete = mysqli_query($conn, "UPDATE usuarios SET estatus = 0 WHERE codigo = $codigo");
    mysqli_close($conn);
    if ($query_delete) {

        header('location: listar_usuarios.php');
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['codigo']) || $_REQUEST['codigo'] == 1) {

    header('location: listar_usuarios.php');
    mysqli_close($conn);
} else {

    $codigo = $_REQUEST['codigo'];
    $query = mysqli_query($conn, "SELECT u.nombre, u.usuario, r.rol 
                                            FROM usuarios u 
                                            INNER JOIN rol r ON u.rol = r.id_rol 
                                            WHERE codigo = $codigo");
    mysqli_close($conn);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($row = mysqli_fetch_array($query)) {

            $nombre = $row['nombre'];
            $usuario = $row['usuario'];
            $rol = $row['rol'];
        }
    } else {

        header('location: listar_usuarios.php');
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Eliminar Usuario</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="container-delete">
            <div class="data-delete">
                <h2>¿Está seguro de eliminar el siguiente usuario?</h2><br>
                <div class="row-delete animate__animated animate__fadeInUp">
                    <div class="datos-delete">
                        <img src="img/usuario.png" alt="">
                        <p>Nombre: <span><?php echo $nombre; ?></span></p>
                        <p>Usuario: <span><?php echo $usuario; ?></span></p>
                        <p>Tipo Usuario: <span><?php echo $rol; ?></span></p>

                        <form action="" method="POST">
                            <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
                            <input type="submit" value="Aceptar" class="btn-ok">
                            <a href="listar_usuarios.php" class="btn-cancel">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>