<?php
session_start();
include "../bd/conn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <link rel="stylesheet" href="./css/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <title>Happy Feet T.</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">

        <div class="prin-2">
            <div class="divInfoSistema">
                <h1 class="titlePanelControl" style="margin-top: 20px;">Información</h1>

                <div class="containerPerfil">

                    <div style="display: none;" class="containerDataUser">
                        <div class="logoUser">
                            <img src="img/avatar.jpg">
                        </div>
                        <div class="divDataUser">
                            <h4>Información Personal</h4>
                            <div>
                                <label for="">Nombre: </label> <span>Abel OS</span>
                            </div>
                            <div>
                                <label for="">Correo: </label> <span>isaacbolivar234@gmail.com</span>
                            </div>
                            <h4>Datos Usuario</h4>
                            <div>
                                <label for="">Rol: </label> <span>Administrador</span>
                            </div>
                            <div>
                                <label for="">Usuario: </label> <span>Abel OS</span>
                            </div>
                            <h4>Cambio de contraseña</h4>
                            <form action="" method="POST" name="frmChangePass" id="frmChangePass">
                                <div>
                                    <input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña actual" required>
                                </div>
                                <div>
                                    <input type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva contraseña" required>
                                </div>
                                <div>
                                    <input type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Confirmar Contraseña" required>
                                </div>
                                <div>
                                    <button type="button" class="btn-save bntChangePass">
                                        Cambiar Contraseña
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="direcciones">
                        <a href="#" class="atras" onclick="avanzaSlide(-1)">&#10094;</a>
                    </div>

                    <div style="display: flex;" class="containerDataProductos">
                        <div style="width: 70%;" class="logoEmpresa">
                            <img src="img/zapatos.jpg">
                        </div>
                        <div style="width: 90%;" class="table-info">
                            <h4>Productos agregados recientemente</h4>
                            <table>
                                <tr>
                                    <th class="titulo-pro">Foto</th>
                                    <th class="titulo-pro">Producto</th>
                                </tr>
                                <?php
                                $query = mysqli_query($conn, "SELECT * FROM productos ORDER BY codigo_prod DESC LIMIT 4");

                                $resultado = mysqli_num_rows($query);

                                if ($resultado > 0) {
                                    while ($row = mysqli_fetch_array($query)) {

                                        if ($row['foto'] != 'img_producto.png') {

                                            $foto = 'img/uploads/' . $row['foto'];
                                        } else {

                                            $foto = 'img/' . $row['foto'];
                                        }
                                ?>

                                        <tr>
                                            <td class="img-index titulo-pro"><img src="<?php echo $foto; ?>" alt="<?php echo $row['descripcion']; ?>" style="width: 80px;"> </td>
                                            <td class="titulo-pro"><?php echo $row['descripcion']; ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>

                    <div style="display: flex;" class="containerDataProductos">
                        <div style="width: 70%;" class="logoEmpresa">
                            <img src="img/zapatos2.jpg">
                        </div>
                        <div style="width: 90%; " class="table-info entradasProd">
                            <h4>Ultimas entradas de productos</h4>
                            <table>
                                <tr>
                                    <th class="titulo-pro">Cantidad</th>
                                    <th class="titulo-pro">Producto</th>
                                    <th class="titulo-pro">Fecha</th>
                                </tr>
                                <?php
                                $query = mysqli_query($conn, "SELECT e.cantidad, e.fecha_entrada, p.descripcion FROM entrada e INNER JOIN productos p ON e.cod_producto = p.codigo_prod ORDER BY e.id_entrada DESC LIMIT 5");

                                $resultado = mysqli_num_rows($query);

                                if ($resultado > 0) {
                                    while ($row = mysqli_fetch_array($query)) {

                                        $formato = 'Y-m-d H:i:s';
                                        $fecha = DateTime::createFromFormat($formato, $row['fecha_entrada']);
                                ?>

                                        <tr>
                                            <td class="titulo-pro"><?php echo $row['cantidad']; ?></td>
                                            <td class="titulo-pro"><?php echo $row['descripcion']; ?></td>
                                            <td class="titulo-pro"><?php echo $fecha->format('d-m-Y'); ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>

                    <div style="display: flex;" class="containerDataProductos">
                        <div style="width: 70%;" class="logoEmpresa">
                            <img src="img/zapatos4.jpg">
                        </div>
                        <div style="width: 90%;" class="table-info entradasProd">
                            <h4>Seguimiento de salidas</h4>
                            <table>
                                <tr>
                                    <th class="titulo-pro">Fecha</th>
                                    <th class="titulo-pro">Ganacia</th>
                                </tr>
                                <?php
                                $query = mysqli_query($conn, "SELECT s.id_salida, s.fecha_salida, s.estatus, u.usuario, FORMAT(s.total_salida,0) as total_salida
									FROM salida s
									INNER JOIN usuarios u ON s.usuario = u.codigo
									WHERE s.estatus != 10 ORDER BY s.id_salida DESC LIMIT 6");

                                $resultado = mysqli_num_rows($query);

                                if ($resultado > 0) {
                                    while ($row = mysqli_fetch_array($query)) {

                                        $formato = 'Y-m-d H:i:s';
                                        $fecha = DateTime::createFromFormat($formato, $row['fecha_salida']);

                                        if ($row['estatus'] == 1) {
                                            $estado = '<span style="background: #0bb660; padding: 7px; color: white; border-radius: 5px;" class=""pagada">Generada</span>';
                                        } else {
                                            $estado = '<span style="background: #E30E0E; padding: 7px; color: white; border-radius: 5px;" class=""anulada">Anulada</span>';
                                        }
                                ?>

                                        <tr>
                                            <td class="titulo-pro"><?php echo $fecha->format('d-m-Y'); ?></td>
                                            <?php if ($row['estatus'] == 1) { ?>
                                                <td class="titulo-pro">$<?php echo $row['total_salida']; ?></td>
                                            <?php } else { ?>
                                                <td class="titulo-pro">$ <del><?php echo $row['total_salida']; ?></del></td>
                                            <?php } ?>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>

                    <div style="display: flex;" class="containerDataProductos">
                        <div style="width: 70%;" class="logoEmpresa">
                            <img src="img/zapatos5.jpg">
                        </div>
                        <div style="width: 90%;" class="table-info entradasProd">
                            <h4 style="margin-top: 10%;">Registros</h4>
                            <table>
                                <tr>
                                    <?php
                                    $query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma' FROM usuarios");
                                    $row = mysqli_fetch_array($query_sum);
                                    ?>
                                    <td style="color:#E1306C;" class="titulo-pro"><i class="fas fa-users fa-1x"></i></td>
                                    <td class="titulo-pro">Usuarios</td>
                                    <td class="titulo-pro"><?php echo $row['suma']; ?></td>
                                </tr>
                                <tr>
                                    <?php
                                    $query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma' FROM proveedor");
                                    $row_pro = mysqli_fetch_array($query_sum);
                                    ?>
                                    <td style="color:#E1306C;" class="titulo-pro"><i class="fas fa-people-carry fa-1x"></i></td>
                                    <td class="titulo-pro">Proveedores</td>
                                    <td class="titulo-pro"><?php echo $row_pro['suma']; ?></td>
                                </tr>
                                <tr>
                                    <?php
                                    $query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma' FROM productos");
                                    $row_prod = mysqli_fetch_array($query_sum);
                                    ?>
                                    <td style="color:#E1306C;" class="titulo-pro"><i class="fas fa-archive fa-1x"></i></td>
                                    <td class="titulo-pro">Productos</td>
                                    <td class="titulo-pro"><?php echo $row_prod['suma']; ?></td>
                                </tr>
                                <tr>
                                    <?php
                                    $query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma' FROM categorias");
                                    $row_cat = mysqli_fetch_array($query_sum);
                                    ?>
                                    <td style="color:#E1306C;" class="titulo-pro"><i class="fas fa-align-justify fa-1x"></i></td>
                                    <td class="titulo-pro">Categorias</td>
                                    <td class="titulo-pro"><?php echo $row_cat['suma']; ?></td>
                                </tr>
                                <tr>
                                    <?php
                                    $query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma' FROM entrada");
                                    $row_ent = mysqli_fetch_array($query_sum);
                                    ?>
                                    <td style="color:#E1306C;" class="titulo-pro"><i class="fas fa-clipboard-check fa-1x"></i></td>
                                    <td class="titulo-pro">Entradas</td>
                                    <td class="titulo-pro"><?php echo $row_ent['suma']; ?></td>
                                </tr>
                                <tr>
                                    <?php
                                    $query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma1' FROM salida");
                                    $row_salidas = mysqli_fetch_array($query_sum);
                                    ?>
                                    <td style="color:#E1306C;" class="titulo-pro"><i class="fas fa-dolly fa-1x"></i></td>
                                    <td class="titulo-pro">Salidas</td>
                                    <td class="titulo-pro"><?php echo $row_salidas['suma1']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div style="display: none; " class="containerData">
                        <div style="width: 50%;" class="logoEmpresa">
                            <img style="height: 90%; margin-top: inherit;" src="img/empresas2.jpg">
                        </div>
                        <div style="width: 50%;" class="data-empresa">
                            <h4>Agregar Información de la empresa</h4>
                            <form style="border: none;" action="" method="POST" name="frmEmpresa" id="frmEmpresa">
                                <input type="hidden" name="action" value="updateDataEmpresa">
                                <div>
                                    <label for="">Nit:</label><input type="text" name="txtNit" id="txtNit" placeholder="Nit de la empresa" value="" require>
                                </div>
                                <div>
                                    <label for="">Nombre:</label><input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la empresa" value="" require>
                                </div>
                                <div>
                                    <label for="">Télefono:</label><input type="text" name="txtTelEmpresa" id="txtTelEmpresa" placeholder="Número de télefono" value="" require>
                                </div>
                                <div>
                                    <label for="">Correo electrónico:</label><input type="text" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholder="Correo electrónico" value="" require>
                                </div>
                                <div>
                                    <label for="">Dirección:</label><input type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Dirección de la empresa" value="" require>
                                </div>
                                <div class="alertFormEmpresa" style="display: none;"></div>
                                <div>
                                    <button type="submit" class="btn-save btnChangePass">
                                        Guardar datos
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="direcciones">
                        <a href="#" class="siguiente" onclick="avanzaSlide(1)">&#10095;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="js/main.js"></script>
</body>

</html>