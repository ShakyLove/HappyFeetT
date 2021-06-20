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
        <?php
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){

                header('location: listar_usuarios.php');
                mysqli_close($conn);
            }
        ?>
        <div class="tabla-usuario">
            <h1>Lista de Usuarios</h1>
            <div class="botones">
                <div class="botones-2">
                <?php 
                    if($_SESSION['rol'] != 1){ ?>
                    <a href="pdf_usuario.php" class="btn-info">Exportar PDF</a>
                        <form action="buscar_usuario.php" method="get" class="form-search">
                            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="barra-search">
                            <input type="submit" value="Buscar" class="btn-search">
                        </form>
                    <?php }else{ ?>
                    <a href="registro_usuario.php" class="btn-nuevo">Crear Usuario</a>
                    <a href="pdf_usuario.php" class="btn-info">Exportar PDF</a>
                        <form action="buscar_usuario.php" method="get" class="form-search">
                            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="barra-search">
                            <input type="submit" value="Buscar" class="btn-search">
                        </form>
                    <?php } ?>
                </div>
            </div>
            <div class="table">
                <table>
                    <tr>
                        <th>Id Usuario</th>
                        <th>Nombre</th>
                        <th>Correo Electronico</th>
                        <th>Nombre de Usuario</th>
                        <th>Rol</th>
                        <?php 
                            if($_SESSION['rol'] != 1){

                            }else{
                        ?>
                        <th>Acciones</th>
                        <?php } ?>
                    </tr>
                    <?php

                        //paginador
                        $rol = '';
                        if($busqueda == 'administrador'){

                            $rol = "OR rol LIKE '%1%'";
                        }else if($busqueda == 'empleado'){

                            $rol = "OR rol LIKE '%2%'";
                        }

                        $sql_registe = mysqli_query($conn, "SELECT COUNT(*) as total_registro 
                                                                FROM usuarios 
                                                                WHERE ( codigo  LIKE '%.$busqueda.%' OR
                                                                        nombre  LIKE '%$busqueda%' OR
                                                                        correo  LIKE '%$busqueda%' OR
                                                                        usuario LIKE  '%$busqueda%' 
                                                                        $rol )
                                                                AND estatus = 1");
                        $row_registe = mysqli_fetch_array($sql_registe);
                        $total_registro = $row_registe['total_registro'];

                        $por_pagina = 6;

                        if(empty($_GET['pagina'])){
                            $pagina = 1;
                        }else{
                            $pagina = $_GET['pagina'];
                        }

                        $desde = ($pagina - 1 ) * $por_pagina;
                        $total_paginas = ceil($total_registro / $por_pagina);

                        $query = mysqli_query($conn, "SELECT u.codigo, u.nombre, u.correo, u.usuario, r.rol 
                                                            FROM usuarios u 
                                                            INNER JOIN rol r ON u.rol = r.id_rol 
                                                            WHERE     ( u.codigo  LIKE '%$busqueda%' OR
                                                                        u.nombre  LIKE '%$busqueda%' OR
                                                                        u.correo  LIKE '%$busqueda%' OR
                                                                        u.usuario LIKE '%$busqueda%' OR
                                                                        r.rol     LIKE '%$busqueda%' )
                                                            AND estatus = 1 
                                                            ORDER BY u.codigo ASC
                                                            LIMIT $desde, $por_pagina");
                        mysqli_close($conn);
                        $resltado = mysqli_num_rows($query);

                        if($resltado > 0){

                            while($row = mysqli_fetch_array($query)){
                    ?>
                                <?php 
                            $clase = 0;
                            if($_SESSION['rol'] == 1){ 
                                $clase = 1;
                            }else{ 
                                $clase = 2;
                            } 
                            ?>
                                <tr class="rol-<?php echo $clase ?>">
                                <td><?php echo $row['codigo']; ?></td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><?php echo $row['correo']; ?></td>
                                <td><?php echo $row['usuario']; ?></td>
                                <?php if(!$row['rol']){
                                    ?>
                                    <td><?php echo "DEFINIR"; ?></td>
                                <?php
                                }else{
                                ?>
                                    <td><?php echo $row['rol'] ?></td>
                                <?php
                                }
                                ?>
                                <?php if($_SESSION['rol'] != 1){ 
                                    
                                }else{?>
                                <td class="acc">
                                    <a class="link_edit" href="editar_usuario.php?codigo=<?php echo $row['codigo']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                <?php if($row['codigo'] != 1){  ?>

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
    <?php 
        if($total_registro != 0){
    ?>
            <div class="paginador_pr">
                <div class="paginador">
                        <ul>
                        <?php
                            if($pagina != 1){

                            
                        ?>
                            <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
                            <li><a href="?pagina=<?php echo $pagina - 1 ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
                    <?php
                        }
                        for ($i=1; $i <= $total_paginas; $i++){
                            if($i == $pagina){

                                echo '<li class="pageSelected">'.$i.'</li>';
                            }else{                                
                                echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                            }
                        }
                        if($pagina != $total_paginas){

                        
                    ?>
                            <li><a href="?pagina=<?php echo $pagina + 1 ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
                            <li><a href="?pagina=<?php echo $total_paginas ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>
                    <?php } ?>
                        </ul>
                </div>
            </div>
    <?php } ?>
        </div>
    </section>
</body>
</html>