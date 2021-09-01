<?php
session_start();

include "../bd/conn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista de Salidas</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="tabla-usuario">
            <h1><i class="fas fa-dolly"></i> Lista de Salidas</h1>
            <div class="botones">
                <div class="botones-2">
                    <a href="nueva_venta.php" class="btn-nuevo"><i class="fas fa-folder-plus"></i> Generar Salida</a>
                    <a href="pdf_salida.php" class="btn-info">Exportar PDF <i class="fas fa-file-pdf"></i></a>
                    <form action="buscar_venta.php" method="get" class="form-search">
                        <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="barra-search">
                        <input type="submit" value="Buscar" class="btn-search">
                    </form>
                    <!--<div>
                        <h5 style="	color: #1c8fa3; font-size: 15px; font-family: 'Baloo 2';">Bucar por Fecha</h5>
                        <form style="padding: 5px; display: inline-flex; background: initial; border: none; width: 50%;" action="buscar_venta.php" method="POST" class="form_search_date">
                            <label style="margin: 10px; margin-top: 15px;" for="">De: </label>
                            <input style="width: 40%; margin-top: 10px;" type="date" name="fecha_de" id="fecha_de" require>
                            <label style="margin: 10px; margin-top: 15px;" for=""> A </label> 
                            <input style="width: 40%; margin-top: 10px;" type="date" name="fecha_a" id="fecha_a" require>
                            <input style="font-size:12px; cursor:pointer; background:#1c8fa3; color:white; margin-top: 10px; margin-left: 10px; width: 10%" type="submit" value="Buscar" class="btn-search">
                        </form>
                    </div> -->
                </div>
            </div>
            <div class="table">
                <table class="table-salidas">
                    <tr>
                        <th class="titulo-pro">No.</th>
                        <th class="titulo-pro">Fecha</th>
                        <th class="titulo-pro">Usuario</th>
                        <th style="text-align: end;">Total Salida</th>
                        <th class="titulo-pro">Estado</th>
                        <th class="titulo-pro">Acciones</th>
                    </tr>
                    <?php

                    //paginador
                    $sql_registe = mysqli_query($conn, "SELECT COUNT(*) as total_registro FROM salida WHERE estatus != 10");
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

                    $query = mysqli_query($conn, "SELECT s.id_salida, s.fecha_salida, s.estatus, u.usuario, FORMAT(s.total_salida,0) as total_salida
                                                                FROM salida s
                                                                INNER JOIN usuarios u ON s.usuario = u.codigo
                                                                WHERE s.estatus != 10 ORDER BY s.id_salida DESC LIMIT $desde, $por_pagina");

                    mysqli_close($conn);

                    $resltado = mysqli_num_rows($query);
                    if ($resltado > 0) {

                        while ($row = mysqli_fetch_array($query)) {

                            $formato = 'Y-m-d H:i:s';
                            $fecha = DateTime::createFromFormat($formato, $row['fecha_salida']);

                            if ($row['estatus'] == 1) {
                                $estado = '<span style="background: #0bb660; padding: 7px; color: white; border-radius: 5px;" class=""pagada">Generada</span>';
                            } else {
                                $estado = '<span style="background: #E30E0E; padding: 7px; color: white; border-radius: 5px;" class=""anulada">Anulada</span>';
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
                            <tr class="rol-<?php echo $clase ?>" id="row_<?php echo $row['id_salida']; ?>">
                                <td class="titulo-pro"><?php echo $row['id_salida']; ?></td>
                                <td class="titulo-pro"><?php echo $fecha->format('d-m-Y'); ?></td>
                                <td class="titulo-pro"><?php echo $row['usuario']; ?></td>
                                <td style="text-align: end;"><?php echo $row['total_salida']; ?></td>
                                <td class="titulo-pro"><?php echo $estado; ?></td>

                                <td class="acc titulo-pro">

                                    <?php if ($row['estatus'] == 1) { ?>
                                        <a class="link_edit" href="ver_salida.php?id_sal=<?php echo $row['id_salida']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a class="link_edit" href="ver_salida_anulada.php?id_sal=<?php echo $row['id_salida']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php } ?>

                                    <?php if ($row['estatus'] == 1) { ?>
                                        <a class="link_delete" href="eliminar_salida.php?id_sal=<?php echo $row['id_salida']; ?>">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a style="background: #A2A1A1" class="link_delete inactive">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                                </td>
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