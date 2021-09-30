<?php
session_start();
include "../bd/conn.php";

if (!empty($_POST)) {

    $alert = '';
    if (empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['cantidad']) || empty($_POST['categoria'])) {

        $alert = 1;
    } elseif (is_numeric($_POST['producto'])) {
        $alert = 5;
        $text = 'No se permiten números en el nombre del producto';
    } else {

        $proveedor = $_POST['proveedor'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $categoria = $_POST['categoria'];
        $usuario_id = $_SESSION['codigo'];

        $foto = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type = $foto['type'];
        $url_temp = $foto['tmp_name'];
        $imgProducto = 'img_producto.png';

        if ($nombre_foto != '') {

            $destino = 'img/uploads/';
            $img_nombre = 'img_' . md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src = $destino . $imgProducto;
        }

        $query_insert = mysqli_query($conn, "INSERT INTO productos(proveedor, descripcion, precio, existencia, usuario_id, foto, category) 
                                                            VALUES('$proveedor', '$producto', '$precio', '$cantidad', '$usuario_id','$imgProducto', '$categoria')");

        if ($query_insert) {
            if ($nombre_foto != '') {
                move_uploaded_file($url_temp, $src);
            }
            $alert = 2;
        } else {

            $alert = 3;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Registro de Producto</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register" style="width: 900px;">
            <h1><i class="fas fa-archive"></i> Registro Producto</h1>
            <hr>
            <div class="alert"></div>
            <?php isset($alert) ? $alert : ''; ?>
            <form action="" method="POST" enctype="multipart/form-data" style="display: flex;" class="animate__animated animate__fadeInLeft">
                <input type="hidden" id="valor_form" value="<?php echo $alert; ?>">
                <input type="hidden" id="text" value="<?php echo $text; ?>">
                <div style="width: 50%; margin: 0 20px; margin-top: 30px;">
                    <div class="label">
                        <label for="proveedor">Proveedor</label>
                        <?php
                        $query_proveedor = mysqli_query($conn, "SELECT nit_proveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                        $resultado = mysqli_num_rows($query_proveedor);
                        ?>
                        <select name="proveedor" id="proveedor">
                            <?php
                            if ($resultado > 0) {
                                while ($row = mysqli_fetch_array($query_proveedor)) {

                            ?>
                                    <option value="<?php echo $row['nit_proveedor']; ?>"><?php echo $row['proveedor']; ?></option>

                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="label">
                        <label for="producto">Producto</label>
                        <input type="text" name="producto" placeholder="Nombre del producto" id="producto">
                    </div>

                    <div class="label">
                        <label for="precio">Precio</label>
                        <input type="number" name="precio" placeholder="Precio del producto" id="precio">
                    </div>
                    <p id="parrafo" style="text-align: end; color: red;"></p>

                    <div class="label">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" name="cantidad" placeholder="Cantidad del producto" id="cantidad">
                    </div>
                    <p id="parrafo_cant" style="text-align: end; color: red;"></p>

                    <div class="label">
                        <label for="categoria">Categoría</label>
                        <?php
                        $query_categoria = mysqli_query($conn, "SELECT * FROM categorias WHERE estatus = 1 ORDER BY descripcion ASC");
                        $resultado = mysqli_num_rows($query_categoria);
                        ?>
                        <select name="categoria" id="categoria">
                            <?php
                            if ($resultado > 0) {
                                while ($row = mysqli_fetch_array($query_categoria)) {

                            ?>
                                    <option value="<?php echo $row['categoria_id']; ?>"><?php echo $row['descripcion']; ?></option>

                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div style="width: 50%;">
                    <div class="photo">
                        <label for="foto">Foto</label>
                        <div class="prevPhoto">
                            <span class="delPhoto notBlock">X</span>
                            <label for="foto"></label>
                        </div>
                        <div class="upimg">
                            <input type="file" name="foto" id="foto">
                        </div>
                        <div id="form_alert"></div>
                    </div>
                    <input type="submit" class="btn-save" value="Guardar Producto" id="saveForm">
                    <a id="cancel" href="#" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                    border-radius: 5px; background: black; color: white; display: inline-block; text-align: center;">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
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

        $('input#cantidad').keyup(function(event) {
            if (this.value.length >= 4) {
                $('#parrafo_cant').html('Máximo 3 dígitos');
                this.value = this.value.slice(0, 3);
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

        valor = $('#valor_form').val();

        $('#saveForm').click(function(e) {

            if ($('#proveedor').val() == '' || $('#producto').val() == '' || $('#precio').val() == '' || $('#cantidad').val() == '' || $('#categoria').val() == '') {
                e.preventDefault();
                Swal.fire({
                    title: 'Todos los campos son obligatorios',
                    icon: 'error',
                    confirmButtonText: `Aceptar`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $('#valor_form').val('0');
                    }
                });
            }
        });

        $('#cancel').click(function(e) {

            if ($('#proveedor').val() != '' || $('#producto').val() != '' || $('#precio').val() != '' || $('#cantidad').val() != '' || $('#categoria').val() != '') {
                Swal.fire({
                    title: '¿Está seguro de cancelar el registro?',
                    icon: 'warning',
                    confirmButtonText: `Aceptar`,
                    showCancelButton: true,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var url = 'listar_productos.php';
                        $(location).attr('href', url);
                    }
                });
            } else {
                var url = 'listar_productos.php';
                $(location).attr('href', url);
            }
        })

        let responseCode = {
            2: () => {
                Swal.fire({
                    title: 'Producto registrado con éxito',
                    icon: 'success',
                    confirmButtonText: `Aceptar`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {

                        var url = 'listar_productos.php';
                        $(location).attr('href', url);
                    }
                });
            },
            3: () => {
                Swal.fire({
                    title: 'Error al agregar producto',
                    icon: 'error',
                    confirmButtonText: `Aceptar`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {

                        var url = 'listar_productos.php';
                        $(location).attr('href', url);
                    }
                });
            }
        }

        valor in responseCode && responseCode[valor]();
    </script>
</body>

</html>