<?php
session_start();
include "../bd/conn.php";

if (!empty($_POST)) {

    $alert = '';
    if (empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {

        $alert = 1;
    } else {

        $nit_prov = $_POST['nit_proveedor'];
        $proveedor = $_POST['proveedor'];
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];

        $sql_edit = mysqli_query($conn, "UPDATE proveedor 
                                                SET proveedor = '$proveedor', contacto = '$contacto', telefono = '$telefono', direccion = '$direccion'
                                                WHERE nit_proveedor = $nit_prov ");

        if ($sql_edit) {

            $alert = 2;
        } else {

            $alert = 3;
        }
    }
}

//mostrar datos 
if (empty($_REQUEST['nit'])) {

    header('location: listar_proveedor.php');
}
$nit_proveedor = $_REQUEST['nit'];

$query = mysqli_query($conn, "SELECT * FROM proveedor WHERE nit_proveedor = $nit_proveedor");

$result_sql = mysqli_num_rows($query);

if ($result_sql == 0) {

    header('location: listar_proveedor.php');
} else {
    while ($row = mysqli_fetch_array($query)) {

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
            <div class="alert"></div>
            <?php isset($alert) ? $alert : ''; ?>
            <form action="" method="POST" class="animate__animated animate__fadeInLeft">
                <input type="hidden" id="valor_form" value="<?php echo $alert; ?>">
                <input type="hidden" name="nit_proveedor" value="<?php echo $nit; ?>">

                <div class="label">
                    <label for="proveedor">Proveedor</label>
                    <input type="text" name="proveedor" placeholder="Nombre del proveedor" id="proveedor" value="<?php echo $proveedor; ?>">
                </div>

                <div class="label">
                    <label for="contacto">Contacto</label>
                    <input type="text" name="contacto" placeholder="Nombre completo del contacto" id="contacto" value="<?php echo $contacto; ?>">
                </div>

                <div class="label">
                    <label for="telefono">Teléfono</label>
                    <input type="number" name="telefono" placeholder="Número de telefono" id="telefono" value="<?php echo $telefono; ?>">
                </div>
                <p id="parrafo" style="text-align: end; color: red;"></p>

                <div class="label">
                    <label for="direccion">Direccion</label>
                    <input type="text" name="direccion" placeholder="Direccion completa" id="direccion" value="<?php echo $direccion; ?>">
                </div>
                <input type="submit" class="btn-save" value="Actualizar Proveedor" id="saveForm">
                <a id="cancel" href="#" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: black; color: white; display: inline-block; text-align: center;">Cancelar</a>
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

        $('input#telefono').keypress(function(event) {

            if (this.value.length >= 11) {
                $('#parrafo').html('Máximo 11 dígitos');
                return false;
            } else {
                if (this.value.length <= 10) {
                    $('#parrafo').html('');
                    return true;
                }
            }
        });

        valor = $('#valor_form').val();

        $('#saveForm').click(function(e) {

            if ($('#proveedor').val() == '' || $('#contacto').val() == '' || $('#telefono').val() == '' || $('#direccion').val() == '') {
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
            } else if ($.isNumeric($('#contacto').val())) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error al actualizar',
                    icon: 'error',
                    text: 'No se permiten números en el nombre de contacto',
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

            if ($('#proveedor').val() != '' || $('#contacto').val() != '' || $('#telefono').val() != '' || $('#direccion').val() != '') {
                Swal.fire({
                    title: '¿Está seguro de cancelar la actualización?',
                    icon: 'warning',
                    confirmButtonText: `Aceptar`,
                    showCancelButton: true,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var url = 'listar_proveedor.php';
                        $(location).attr('href', url);
                    }
                });
            } else {
                var url = 'listar_proveedor.php';
                $(location).attr('href', url);
            }
        })

        if (valor == 2) {

            Swal.fire({
                title: 'Datos de proveedor actualizados',
                icon: 'success',
                confirmButtonText: `Aceptar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    var url = 'listar_proveedor.php';
                    $(location).attr('href', url);
                }
            });
        } else if (valor == 3) {

            Swal.fire({
                title: 'Error al agregar proveedor',
                icon: 'error',
                confirmButtonText: `Aceptar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    var url = 'listar_proveedor.php';
                    $(location).attr('href', url);
                }
            });
        }
    </script>
</body>

</html>