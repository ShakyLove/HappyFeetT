<?php
session_start();

include "../bd/conn.php";

if (empty($_GET['cod'])) {

    header('location: listar_productos.php');
    mysqli_close($conn);
} else {

    $codigo = $_GET['cod'];
    $query = mysqli_query($conn, "SELECT p.codigo_prod, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto 
                                            FROM productos p INNER JOIN proveedor pr
                                            ON p.proveedor = pr.nit_proveedor   
                                            WHERE codigo_prod = $codigo");
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
    <title>Agregar Producto</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-archive"></i> Agregar Producto</h1>
            <hr>
            <div class="alert"></div>
            <?php isset($alert) ? $alert : ''; ?>
            <form action="" method="POST" class="animate__animated animate__fadeInLeft">
                <div class="contenido" style="text-align: center;">
                    <h2 id="titulo" style="font-family: 'Baloo 2';"><?php echo $descripcion; ?></h2>
                    <img src="<?php echo $foto; ?>" alt="<?php echo $row['descripcion']; ?>" style="width: 150px;">
                </div>

                <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>">

                <div class="label">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" placeholder="Cantidad del producto" id="cantidad">
                </div>
                <p id="parrafo_cant" style="text-align: end; color: red;"></p>

                <div class="label">
                    <label for="precio">Precio</label>
                    <input type="text" name="precio" placeholder="Precio del producto" id="precio">
                </div>
                <p id="parrafo" style="text-align: end; color: red;"></p>

                <input type="submit" class="btn-save" value="Agregar Producto" id="saveForm">
                <a href="listar_productos.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: black; color:white; display: inline-block; text-align: center;">Cancelar</a>
            </form>
        </div>
    </section>
    <script src="js/main2.js"></script>
    <script type="text/javascript">
        let ubicacionPrincipal = window.pageYOffset;
        window.onscroll = function Scroll() {
            let desplazamiento = window.pageYOffset;
            if (desplazamiento == 0) {
                document.getElementById('navegacion').style.display = 'block';
                document.getElementById('header').style.background = 'initial';
            } else {
                document.getElementById('navegacion').style.display = 'none';
                document.getElementById('header').style.background = 'white';
            }
            ubicacionPrincipal = desplazamiento;
        }

        $('input#cantidad').keypress(function(event) {

            if (this.value.length >= 3 ) {
                $('#parrafo_cant').html('Máximo 3 dígitos');
                return false;
            } else {
                if (this.value.length < 3) {
                    $('#parrafo_cant').html('');
                    return true;
                }
            }
        });

        $('input#precio').keypress(function(event) {

            if (this.value.length >= 9) {
                $('#parrafo').html('Máximo 9 dígitos');
                return false;
            } else {
                if (this.value.length < 9) {
                    $('#parrafo').html('');
                    return true;
                }
            }
        });

    </script>
</body>

</html>