<?php
session_start();
include "../bd/conn.php";

//mostrar datos 
$id_sal = $_REQUEST['id_sal'];
if (empty($_REQUEST['id_sal'])) {

    header('location: listar_salidas.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Detalle de salida</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="tabla-usuario">
            <h1><i class="fas fa-people-carry"></i> Detalle de salida <?php echo $id_sal ?> </h1>
            <img style="position: absolute; width: 70%; margin-left: 15%;" src="img/inicio/anulado.png" alt="">
            <div class="table">
                <table>
                    <tr>
                        <th class="titulo-pro">ID</th>
                        <th class="titulo-pro">Producto</th>
                        <th class="titulo-pro">Proveedor</th>
                        <th class="titulo-pro">Foto</th>
                        <th class="titulo-pro">Cantidad</th>
                        <th class="titulo-pro">Precio producto</th>
                    </tr>
                    <?php


                    $query = mysqli_query($conn, "SELECT d.correlativo, d.id_salida, p.descripcion, pr.proveedor, p.foto, d.cantidad, FORMAT(d.precio_venta,0) AS precio_venta 
                                                                FROM ((detalle_salida d
                                                                INNER JOIN productos p ON d.id_producto = p.codigo_prod)
                                                                INNER JOIN proveedor pr ON p.proveedor = pr.nit_proveedor)
                                                                WHERE d.id_salida = $id_sal");

                    mysqli_close($conn);

                    $resltado = mysqli_num_rows($query);
                    if ($resltado == 0) {

                        header('location: listar_salidas.php');
                    } else {
                        while ($row = mysqli_fetch_array($query)) {

                            if ($row['foto'] != 'img_producto.png') {

                                $foto = 'img/uploads/' . $row['foto'];
                            } else {

                                $foto = 'img/' . $row['foto'];
                            }

                    ?>
                            <?php
                            $clase = 0;
                            if ($_SESSION['rol'] == 1) {
                                $clase = 1;
                            } else {
                                $clase = 1;
                            }
                            ?>
                            <tr class="rol-<?php echo $clase ?>">
                                <td class="titulo-pro"><?php echo $row['correlativo']; ?></td>
                                <td class="titulo-pro"><?php echo $row['descripcion']; ?></td>
                                <td class="titulo-pro"><?php echo $row['proveedor']; ?></td>
                                <td class="imagen-pro"><img src="<?php echo $foto; ?>" alt="<?php echo $row['descripcion']; ?>" style="width: 100px;"> </td>
                                <td class="titulo-pro"><?php echo $row['cantidad']; ?></td>
                                <td class="titulo-pro"><?php echo $row['precio_venta']; ?></td>
                            <?php } ?>
                        <?php } ?>
                            </tr>
                            <?php


                            ?>
                </table>
            </div>
        </div>
    </section>
</body>

</html>