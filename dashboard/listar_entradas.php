<?php
session_start();

include "../bd/conn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista de Entradas</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="tabla-usuario">
            <h1><i class="fas fa-clipboard-check"></i> Lista de Entradas</h1>
            <div class="botones">
                <div class="botones-2">
                    <a href="pdf_entrada.php" class="btn-info">Exportar PDF <i class="fas fa-file-pdf"></i></a>
                    <form action="buscar_entrada.php" method="get" class="form-search">
                        <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="barra-search">
                        <input type="submit" value="Buscar" class="btn-search">
                    </form>
                </div>
            </div>
            <div class="table">
                <table class="table-entradas animate__animated animate__fadeInUp">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Fecha de entrada</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Precio toltal de entrada</th>
                        <th>Usuario</th>
                    </tr>
                    <?php

                    //paginador
                    $sql_registe = mysqli_query($conn, "SELECT COUNT(*) as total_registro FROM entrada ");
                    $row_registe = mysqli_fetch_array($sql_registe);
                    $total_registro = $row_registe['total_registro'];

                    $por_pagina = 6;

                    if (empty($_GET['pagina'])) {
                        $pagina = 1;
                    } else {
                        $pagina = $_GET['pagina'];
                    }

                    $desde = ($pagina - 1) * $por_pagina;
                    $total_paginas = ceil($total_registro / $por_pagina);

                    $query = mysqli_query($conn, "SELECT e.id_entrada, p.descripcion, e.fecha_entrada, e.cantidad, e.precio_entrada, u.usuario  
                                                            FROM ((entrada e
                                                            INNER JOIN productos p ON e.cod_producto = p.codigo_prod)
                                                            INNER JOIN usuarios u ON e.usuario_id = u.codigo)   
                                                            ORDER BY id_entrada DESC LIMIT $desde, $por_pagina");

                    mysqli_close($conn);

                    $resltado = mysqli_num_rows($query);
                    if ($resltado > 0) {

                        while ($row = mysqli_fetch_array($query)) {

                            //$precio = $row['precio_entrada'];
                            //$cantidad = $row['cantidad'];
                            //$total = ($precio * $cantidad);
                            $formato = 'Y-m-d H:i:s';
                            $fecha = DateTime::createFromFormat($formato, $row['fecha_entrada']);
                    ?>
                            <?php
                            $clase = 0;
                            if ($_SESSION['rol'] == 1) {
                                $clase = 2;
                            } else {
                                $clase = 2;
                            }
                            ?>
                            <tr class="rol-<?php echo $clase ?>">
                                <td><?php echo $row['id_entrada']; ?></td>
                                <td><?php echo $row['descripcion']; ?></td>
                                <td><?php echo $fecha->format('d-m-Y'); ?></td>
                                <td><?php echo $row['cantidad']; ?></td>
                                <td><?php echo $row['precio_entrada']; ?></td>
                                <td id="total"><?php echo $row['precio_entrada'] * $row['cantidad']; ?></td>
                                <td><?php echo $row['usuario']; ?></td>



                            <?php } ?>

                        <?php } ?>
                            </tr>
                            <?php


                            ?>
                </table>
            </div>
            <div class="paginador_pr">
                <div class="paginador">
                    <ul>
                        <?php
                        if ($pagina != 1) {


                        ?>
                            <li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
                            <li><a href="?pagina=<?php echo $pagina - 1 ?>"><i class="fas fa-backward"></i></a></li>
                        <?php
                        }
                        for ($i = 1; $i <= $total_paginas; $i++) {
                            if ($i == $pagina) {

                                echo '<li class="pageSelected">' . $i . '</li>';
                            } else {
                                echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                        if ($pagina != $total_paginas) {

                        ?>
                            <li><a href="?pagina=<?php echo $pagina + 1 ?>"><i class="fas fa-forward"></i></a></li>
                            <li><a href="?pagina=<?php echo $total_paginas ?>"><i class="fas fa-step-forward"></i></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
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
    </script>
</body>

</html>