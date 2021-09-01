<?php
session_start();

include "../bd/conn.php";

if (!empty($_POST)) {

    $alert = '';
    if (empty($_POST['nombre'])) {

        $alert = '<p class="msg_error">Llenar el campo</p>';
    } else {

        $nombre = $_POST['nombre'];
        $usuario = $_SESSION['codigo'];

        $query_insert = mysqli_query($conn, "INSERT INTO categorias(descripcion, usuario_id) VALUES('$nombre', '$usuario')");

        if ($query_insert) {

            $alert = '<p class="msg_save">Categoria agregada</p>';
        } else {

            $alert = '<p class="msg_error">Error al guardar el categoria</p>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista de Categorias</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if (empty($busqueda)) {

            header('location: listar_categoria.php');
            mysqli_close($conn);
        }
        ?>
        <div class="cont-categoria" style="display: flex; width: 100%; height: 100%; justify-content: center;">

            <div class="tabla-usuario" style="width: 60%; margin-top: 30px">
                <h1><i class="fas fa-clipboard-check"></i> Lista de Categoria</h1>
                <div class="botones" style="width: 95%; justify-content: flex-end;">
                    <div class="botones-2" style="width: 50%; ">
                        <form action="listar_categoria.php" method="get" class="form-search" style="width: 100%;">
                            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="barra-search">
                            <input type="submit" value="Buscar" class="btn-search">
                        </form>
                    </div>
                </div>
                <div class="table">
                    <table>
                        <tr>
                            <th style="text-align: center; width: 10%;">ID</th>
                            <th style="width: 50%;">Descripción</th>
                            <th style="width: 20%;">Usuario</th>
                            <th style="text-align: center; width: 20%">Acciones</th>
                        </tr>
                        <?php

                        //paginador
                        $sql_registe = mysqli_query($conn, "SELECT COUNT(*) as total_registro FROM categorias 
                                                                    WHERE ( categoria_id  LIKE '%.$busqueda.%' OR
                                                                            descripcion  LIKE '%$busqueda%' OR
                                                                            usuario_id LIKE '%$busqueda%')
                                                                    AND estatus = 1");
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

                        $query = mysqli_query($conn, "SELECT c.categoria_id, c.descripcion, u.usuario 
                                                            FROM categorias c INNER JOIN usuarios u ON c.usuario_id = u.codigo 
                                                            WHERE ( c.categoria_id  LIKE '%.$busqueda.%' OR
                                                                        c.descripcion  LIKE '%$busqueda%' OR
                                                                        u.usuario LIKE '%$busqueda%')
                                                            AND c.estatus = 1
                                                            ORDER BY c.categoria_id ASC 
                                                            LIMIT $desde, $por_pagina");

                        mysqli_close($conn);

                        $resltado = mysqli_num_rows($query);
                        if ($resltado > 0) {

                            while ($row = mysqli_fetch_array($query)) {

                                $nombre_c = $row['descripcion'];

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
                                    <td style="text-align: center;"><?php echo $row['categoria_id']; ?></td>
                                    <td><?php echo $row['descripcion']; ?></td>
                                    <td><?php echo $row['usuario']; ?></td>
                                    <td style="text-align: center;" class="acc">
                                        <a class="link_edit" href="editar_categoria.php?id_categoria=<?php echo $row['categoria_id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a class="link_delete" href="eliminar_categoria.php?id_categoria=<?php echo $row['categoria_id']; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php } ?>
                                    </td>
                                <?php } ?>
                                </tr>
                                <?php


                                ?>
                    </table>
                </div>
                <?php
                if ($total_registro != 0) {
                ?>
                    <div class="paginador_pr">
                        <div class="paginador">
                            <ul>
                                <?php
                                if ($pagina != 1) {


                                ?>
                                    <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-step-backward"></i></a></li>
                                    <li><a href="?pagina=<?php echo $pagina - 1 ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-backward"></i></a></li>
                                <?php
                                }
                                for ($i = 1; $i <= $total_paginas; $i++) {
                                    if ($i == $pagina) {

                                        echo '<li class="pageSelected">' . $i . '</li>';
                                    } else {
                                        echo '<li><a href="?pagina=' . $i . '&busqueda=' . $busqueda . '">' . $i . '</a></li>';
                                    }
                                }
                                if ($pagina != $total_paginas) {


                                ?>
                                    <li><a href="?pagina=<?php echo $pagina + 1 ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-forward"></i></a></li>
                                    <li><a href="?pagina=<?php echo $total_paginas ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-step-forward"></i></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="form_register" style="margin-top: 30px; width: 450px">
                <h1> Agregar Categoria</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <form action="" method="POST">

                    <label for="nombre">Categoria</label>
                    <input type="text" name="nombre" placeholder="Tipo de categoria" id="nombre">

                    <input type="submit" class="btn-save" value="Agregar Categoria">
                </form>
            </div>
        </div>
    </section>
</body>

</html>