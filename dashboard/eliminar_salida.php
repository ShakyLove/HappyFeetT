<?php
session_start();

include "../bd/conn.php";
if (!empty($_POST)) {

    $id_salida = $_POST['id_salida'];

    $query_sal = mysqli_query($conn, "CALL anular_factura($id_salida)");
    mysqli_close($conn);
    if ($query_sal) {

        header('location: listar_salidas.php');
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id_sal'])) {

    header('location: listar_salidas.php');
    mysqli_close($conn);
} else {

    $id_sal = $_REQUEST['id_sal'];
    $query = mysqli_query($conn, "SELECT id_salida, fecha_salida, FORMAT(total_salida, 0) as total_salida FROM salida WHERE id_salida = $id_sal");
    mysqli_close($conn);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($row = mysqli_fetch_array($query)) {

            $fecha_salida = $row['fecha_salida'];
            $total_salida = $row['total_salida'];
        }
    } else {

        header('location: listar_salidas.php');
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Anular Salida</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="container-delete">
            <div class="data-delete">
                <h2>¿Está seguro de anular la salida <strong style="color: black;">No. <?php echo $id_sal; ?></strong>?</h2><br>
                <div class="row-delete animate__animated animate__fadeInUp">
                    <div class="datos-delete">
                        <i class="fas fa-dolly fa-3x"></i>
                        <p>Generada el día: <span><?php echo $fecha_salida; ?></span></p>
                        <p>Con un costo de: <span><?php echo $total_salida; ?></span></p>

                        <form action="" method="POST">
                            <input type="hidden" name="id_salida" value="<?php echo $id_sal; ?>">
                            <input type="submit" value="Aceptar" class="btn-ok">
                            <a href="listar_salidas.php" class="btn-cancel">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>