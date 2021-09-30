<?php
session_start();

include "../bd/conn.php";
if (!empty($_POST)) {

    $nit_prov = $_POST['nit_proveedor'];

    //$query_delete = mysqli_query($conn, "DELETE FROM usuarios WHERE codigo = $codigo");
    $query_delete = mysqli_query($conn, "UPDATE proveedor SET estatus = 0 WHERE nit_proveedor = $nit_prov");
    mysqli_close($conn);
    if ($query_delete) {

        header('location: listar_proveedor.php');
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['nit'])) {

    header('location: listar_proveedor.php');
    mysqli_close($conn);
} else {

    $nit = $_REQUEST['nit'];
    $query = mysqli_query($conn, "SELECT * FROM proveedor WHERE nit_proveedor = $nit");
    mysqli_close($conn);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($row = mysqli_fetch_array($query)) {

            $proveedor = $row['proveedor'];
            $contacto = $row['contacto'];
            $telefono = $row['telefono'];
        }
    } else {

        header('location: listar_proveedor.php');
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Eliminar Proveedor</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="container-delete">
            <div class="data-delete">
                <h2>¿Está seguro de eliminar el siguiente proveedor?</h2><br>
                <div class="row-delete animate__animated animate__fadeInUp">
                    <div class="datos-delete">
                        <i class="fas fa-people-carry fa-3x"></i>
                        <p>Proveedor: <span><?php echo $proveedor; ?></span></p>
                        <p>Contacto: <span><?php echo $contacto; ?></span></p>
                        <p>Teléfono: <span><?php echo $telefono; ?></span></p>

                        <form action="" method="POST">
                            <input type="hidden" name="nit_proveedor" value="<?php echo $nit; ?>">
                            <input type="submit" value="Aceptar" class="btn-ok">
                            <a href="listar_proveedor.php" class="btn-cancel">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>