<?php
session_start();

include "../bd/conn.php";
if (!empty($_POST)) {

    $cod_prod = $_POST['codigo_prod'];

    //$query_delete = mysqli_query($conn, "DELETE FROM usuarios WHERE codigo = $codigo");
    $query_delete = mysqli_query($conn, "UPDATE productos SET estatus = 0 WHERE codigo_prod = $cod_prod");
    mysqli_close($conn);
    if ($query_delete) {

        header('location: listar_proveedor.php');
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['cod'])) {

    header('location: listar_productos.php');
    mysqli_close($conn);
} else {

    $cod = $_REQUEST['cod'];
    $query = mysqli_query($conn, "SELECT p.codigo_prod, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto 
                                            FROM productos p INNER JOIN proveedor pr
                                            ON p.proveedor = pr.nit_proveedor   
                                            WHERE codigo_prod = $cod");
    mysqli_close($conn);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($row = mysqli_fetch_array($query)) {

            if ($row['foto'] != 'img_producto.png') {

                $foto = 'img/uploads/' . $row['foto'];
            } else {

                $foto = 'img/' . $row['foto'];
            }

            $descripcion = $row['descripcion'];
            $proveedor = $row['proveedor'];
        }
    } else {

        header('location: listar_productos.php');
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Eliminar Producto</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="container-delete">
            <div class="data-delete">
                <h2>¿Está seguro de eliminar el siguiente producto?</h2><br>
                <div class="row-delete animate__animated animate__fadeInUp">
                    <div class="datos-delete">
                        <img src="<?php echo $foto; ?>" alt="<?php echo $row['descripcion']; ?>" style="width: 150px;">
                        <p>Proveedor: <span><?php echo $proveedor; ?></span></p>
                        <p>Descripcion: <span><?php echo $descripcion; ?></span></p>

                        <form action="" method="POST">
                            <input type="hidden" name="codigo_prod" value="<?php echo $cod; ?>">
                            <input type="submit" value="Aceptar" class="btn-ok">
                            <a href="listar_productos.php" class="btn-cancel">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>