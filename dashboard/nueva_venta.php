<?php
session_start();
include "../bd/conn.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Registro de Salida</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="cont-saldias" style="margin-top: 20px;">
            <div class="title-page">
                <h1><i class="fas fa-dolly"></i> Nueva Salida</h1>
            </div>

            <div class="datos-venta">
                <h4>Datos de Salida</h4>
                <div class="datos">
                    <div class="wd50">
                        <label>Empleado</label>
                        <p style="font-size: 18px;"><?php echo $_SESSION['nombre'];  ?></p>
                    </div>
                    <div class="wd50">
                        <label>Acciones</label>
                        <div id="acciones_venta">
                            <a href="#" class="btn-addVnta closeModal" id="btn_anular_venta" onclick="closeModal();">Anular</a>
                            <a style="display: none;" href="#" class="btn-add closeModal" id="btn_facturar_venta" onclick="closeModal();">Procesar</a>
                        </div>
                    </div>
                </div>
            </div>

            <table class="tbl-venta animate__animated animate__fadeInUp">
                <thead>
                    <tr>
                        <th width="200px">Código de producto</th>
                        <th>Descripción</th>
                        <th>Existencia</th>
                        <th width="100px">Cantidad</th>
                        <th class="textright">Precio</th>
                        <th class="textright">Precio Total</th>
                        <th>Acción</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="txt_cod_producto" id="txt_cod_producto" placeholder="Ejemplo: 20" autofocus></td>
                        <td id="txt_descripcion">-</td>
                        <td id="txt_existencia">-</td>
                        <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                        <td id="txt_precio" class="textright">0.00</td>
                        <td id="txt_precio_total" class="textright">0.00</td>
                        <td>
                            <a href="#" id="add_product_venta" class="link_add add_product">
                                <i class="fas fa-plus"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Código</th>
                        <th colspan="2">Descripción</th>
                        <th>Cantidad</th>
                        <th class="textright">Precio</th>
                        <th class="textright">Precio Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="detalle_venta">

                </tbody>

                <tfoot id="detalle_totales">

                </tfoot>
            </table>

        </div>
    </section>

    <script type="text/javascript">
        $(document).ready(function() {
            var usuarioid = '<?php echo $_SESSION['codigo']; ?>';
            searchForDetalle(usuarioid);
        });

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

        $('input#txt_cant_producto').keypress(function(event) {

            if (this.value.length >= 3) {
                $('#parrafo_cant').html('Máximo 3 dígitos');
                return false;
            } else {
                if (this.value.length < 3) {
                    $('#parrafo_cant').html('');
                    return true;
                }
            }
        });
    </script>

</body>

</html>