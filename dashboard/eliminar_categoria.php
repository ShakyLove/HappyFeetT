<?php
        session_start();

    include "../bd/conn.php";
    
    if(!empty($_POST)){


            $id_cat = $_POST['categoria_id'];

            $query_upda = mysqli_query($conn, "UPDATE categorias SET estatus = 0 WHERE categoria_id = '$id_cat'");

            if($query_upda ){

                header('location: listar_categoria.php');
            }else{

                echo 'error al eliminar';
            }
        }


    //mostrar datos 
    if(empty($_REQUEST['id_categoria'])){

        header('location: listar_proveedor.php');

    }
    $categoria_id = $_REQUEST['id_categoria'];

    $query = mysqli_query($conn, "SELECT * FROM categorias WHERE categoria_id = $categoria_id");

    $result_sql = mysqli_num_rows($query);

    if($result_sql == 0){

        header('location: listar_categoria.php');
    }else{
        while($row = mysqli_fetch_array($query)){

            $id_categoria = $row['categoria_id'];
            $descripcion = $row['descripcion'];

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
    <div class="cont-categoria" style="display: flex; width: 100%; height: 100%; justify-content: center;">
    
        <div class="tabla-usuario" style="width: 60%; margin-top: 30px">
            <h1><i class="fas fa-clipboard-check"></i> Lista de Categoria</h1>
            <div class="botones" style="width: 95%; justify-content: flex-end;">
                <div class="botones-2" style="width: 50%; ">
                        <form action="buscar_entrada.php" method="get" class="form-search" style="width: 100%;">
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
                        $sql_registe = mysqli_query($conn, "SELECT COUNT(*) as total_registro FROM categorias WHERE estatus = 1");
                        $row_registe = mysqli_fetch_array($sql_registe);
                        $total_registro = $row_registe['total_registro'];

                        $por_pagina = 6 ;

                        if(empty($_GET['pagina'])){
                            $pagina = 1;
                        }else{
                            $pagina = $_GET['pagina'];
                        }

                        $desde = ($pagina - 1 ) * $por_pagina;
                        $total_paginas = ceil($total_registro / $por_pagina);

                        $query = mysqli_query($conn, "SELECT c.categoria_id, c.descripcion, u.usuario 
                        FROM categorias c INNER JOIN usuarios u ON c.usuario_id = u.codigo 
                        WHERE c.estatus = 1
                        ORDER BY c.categoria_id ASC LIMIT $desde, $por_pagina");
                        mysqli_close($conn);

                        $resltado = mysqli_num_rows($query);
                        if($resltado > 0){

                            while($row = mysqli_fetch_array($query)){

                                $nombre_c = $row['descripcion'];

                    ?>
                            <?php 
                            $clase = 0;
                            if($_SESSION['rol'] == 1){ 
                                $clase = 1;
                            }else{ 
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
            <div class="paginador_pr">
                <div class="paginador">
                        <ul>
                        <?php
                            if($pagina != 1){

                            
                        ?>
                            <li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
                            <li><a href="?pagina=<?php echo $pagina - 1 ?>"><i class="fas fa-backward"></i></a></li>
                    <?php
                        }
                        for ($i=1; $i <= $total_paginas; $i++){
                            if($i == $pagina){

                                echo '<li class="pageSelected">'.$i.'</li>';
                            }else{                                
                                echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                            }
                        }
                        if($pagina != $total_paginas){
                        
                    ?>
                            <li><a href="?pagina=<?php echo $pagina + 1 ?>"><i class="fas fa-forward"></i></a></li>
                            <li><a href="?pagina=<?php echo $total_paginas ?>"><i class="fas fa-step-forward"></i></a></li>
                    <?php } ?>
                        </ul>
                </div>
            </div>
        </div>

        <div class="container-delete" style="width: 40%; margin-top:0;">
            <div class="data-delete" style="width: 100%; background: initial">
                <h2>¿Está seguro de eliminar la categoria?</h2><br>
                <div class="row-delete">
                    <div class="datos-delete">
                        <i class="fas fa-calendar-times fa-3x"></i>
                        <p>Id categoria: <span><?php echo $id_categoria; ?></span></p>
                        <p>Descripcion: <span><?php echo $descripcion; ?></span></p>

                        <form action="" method="POST" style="display: contents;">
                            <input type="hidden" name="categoria_id" value="<?php echo $id_categoria; ?>">
                            <input type="submit" value="Aceptar" class="btn-ok">
                            <a href="listar_categoria.php" class="btn-cancel">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</body>
</html>