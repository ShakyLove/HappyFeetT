<?php
session_start();
include "../bd/conn.php";

if (!empty($_POST)) {

    $alert = '';
    if (empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {

        $alert = 1;
    } elseif (is_numeric($_POST['contacto'])) {
        $alert = 5;
        $text = 'No se permiten números en el nombre de contacto';
    } else {

        $proveedor = strtoupper($_POST['proveedor']);
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $usuario_id = $_SESSION['codigo'];

        $query_insert = mysqli_query($conn, "INSERT INTO proveedor(proveedor, contacto, telefono, direccion, usuario_id) 
                                                            VALUES('$proveedor', '$contacto', '$telefono', '$direccion', '$usuario_id')");

        if ($query_insert) {
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
    <title>Registro de Proveedor</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-people-carry"></i> Registro Proveedor</h1>
            <hr>
            <div class="alert"></div>
            <?php isset($alert) ? $alert : ''; ?>
            <form action="" method="POST" class="animate__animated animate__fadeInLeft">
                <input type="hidden" id="valor_form" value="<?php echo $alert; ?>">
                <input type="hidden" id="text" value="<?php echo $text; ?>">

                <div class="label">
                    <label for="proveedor">Proveedor</label>
                    <input type="text" name="proveedor" placeholder="Nombre del proveedor" id="proveedor">
                </div>

                <div class="label">
                    <label for="contacto">Contacto</label>
                    <input type="text" name="contacto" placeholder="Nombre completo del contacto" id="contacto">
                </div>

                <div class="label">
                    <label for="telefono">Teléfono</label>
                    <input type="number" name="telefono" placeholder="Número de teléfono" id="telefono">
                </div>
                <p id="parrafo" style="text-align: end; color: red;"></p>

                <div class="label">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" placeholder="Dirección completa" id="direccion">
                </div>

                <input type="submit" class="btn-save" value="Guardar Proveedor" id="saveForm">
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
                    title: 'Error en el registro',
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
                    title: '¿Está seguro de cancelar el registro?',
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
                title: 'Proveedor registrado con éxito',
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