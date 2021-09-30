<?php
session_start();

include "../bd/conn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista de Usuarios</title>
</head>

<body>
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="tabla-usuario">
            <h1><i class="fas fa-users"></i> Lista de Usuarios</h1>
            <div class="botones">
                <div class="botones-2">
                    <?php
                    if ($_SESSION['rol'] != 1) { ?>
                        <form action="buscar_usuario.php" method="get" class="form-search">
                            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="barra-search">
                            <input type="submit" value="Buscar" class="btn-search">
                        </form>
                    <?php } else { ?>
                        <a href="registro_usuario.php" class="btn-nuevo"><i class="fas fa-user-plus"></i> Crear Usuario</a>
                        <a href="pdf_usuario.php" class="btn-info">Exportar PDF <i class="fas fa-file-pdf"></i></a>
                        <form action="buscar_usuario.php" method="get" class="form-search">
                            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="barra-search">
                            <input type="submit" value="Buscar" class="btn-search">
                        </form>
                    <?php } ?>
                </div>
            </div>
            <div class="table">
                <table class="table-usuarios animate__animated animate__fadeInUp" >
                    <tr>
                        <th>Id Usuario</th>
                        <th>Nombre</th>
                        <th>Correo Electronico</th>
                        <th>Nombre de Usuario</th>
                        <th>Rol</th>
                        <?php
                        if ($_SESSION['rol'] != 1) {
                        } else {
                        ?>
                            <th>Acciones</th>
                        <?php } ?>
                    </tr>
                    <?php

                    //paginador
                    $sql_registe = mysqli_query($conn, "SELECT COUNT(*) as total_registro FROM usuarios WHERE estatus = 1");
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

                    $query = mysqli_query($conn, "SELECT u.codigo, u.nombre, u.correo, u.usuario, r.rol 
                                                            FROM usuarios u 
                                                            INNER JOIN rol r ON u.rol = r.id_rol 
                                                            WHERE estatus = 1 
                                                            ORDER BY u.codigo ASC
                                                            LIMIT $desde, $por_pagina");

                    mysqli_close($conn);

                    $resltado = mysqli_num_rows($query);
                    if ($resltado > 0) {

                        while ($row = mysqli_fetch_array($query)) {
                    ?>
                            <?php
                            $clase = 0;
                            if ($_SESSION['rol'] == 1) {
                                $clase = 1;
                            } else {
                                $clase = 2;
                            }
                            ?>
                            <tr class="rol-<?php echo $clase ?>">
                                <td><?php echo $row['codigo']; ?></td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><?php echo $row['correo']; ?></td>
                                <td><?php echo $row['usuario']; ?></td>
                                <?php if (!$row['rol']) {
                                ?>
                                    <td><?php echo "DEFINIR"; ?></td>
                                <?php
                                } else {
                                ?>
                                    <td><?php echo $row['rol'] ?></td>
                                <?php
                                }
                                ?>
                                <?php if ($_SESSION['rol'] != 1) {
                                } else { ?>
                                    <td class="acc">
                                        <a class="link_edit" href="editar_usuario.php?codigo=<?php echo $row['codigo']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <?php if ($row['codigo'] != 1) {  ?>

                                            <a class="link_delete" href="eliminar_usuario.php?codigo=<?php echo $row['codigo']; ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                            </tr>
                    <?php
                        }
                    }
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