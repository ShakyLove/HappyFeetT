<?php
        session_start();

    include "../bd/conn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de Productos</title>
</head>
<body>
	<?php include "includes/header.php" ?>
    <section id="container">
        <div class="tabla-usuario">
            <h1><i class="fas fa-archive"></i> Lista de Productos</h1>
            <div class="botones">
                <div class="botones-2">
                    <a href="registro_producto.php" class="btn-nuevo"><i class="fas fa-folder-plus"></i> Registrar Producto</a>
                    <a href="pdf_producto.php" class="btn-info">Exportar PDF <i class="fas fa-file-pdf"></i></a>
                        <form action="buscar_producto.php" method="get" class="form-search">
                            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="barra-search">
                            <input type="submit" value="Buscar" class="btn-search">
                        </form>
                </div>
            </div>
            <div class="table">
                <table class="table-productos">
                    <tr>
                        <th class="titulo-pro">Codigo</th>
                        <th>Descipción</th>
                        <th>Precio</th>
                        <th class="titulo-pro">Stock</th>
                        <th class="titulo-pro">Proveedor</th>
                        <th class="titulo-pro">Categoria</th>
                        <th class="titulo-pro">Foto</th>
                        <th class="titulo-pro">Acciones</th>
                    </tr>
                    <?php

                        //paginador
                        $sql_registe = mysqli_query($conn, "SELECT COUNT(*) as total_registro FROM productos WHERE estatus = 1");
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

                        $query = mysqli_query($conn, "SELECT p.codigo_prod, p.descripcion, FORMAT(p.precio,0) as precio, p.existencia, pr.proveedor, p.foto, c.descripcion as category
                                                            FROM ((productos p 
                                                            INNER JOIN proveedor pr ON p.proveedor = pr.nit_proveedor)
                                                            INNER JOIN categorias c ON p.category = c.categoria_id)
                                                            WHERE p.estatus = 1 ORDER BY pr.proveedor
                                                            LIMIT $desde, $por_pagina");

                        mysqli_close($conn);

                        $resltado = mysqli_num_rows($query);
                        if($resltado > 0){

                            while($row = mysqli_fetch_array($query)){

                            if($row['foto'] != 'img_producto.png'){

                                $foto = 'img/uploads/'.$row['foto'];
                            }else{

                                $foto = 'img/'.$row['foto'];
                            }
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
                                <td class="titulo-pro"><?php echo $row['codigo_prod']; ?></td>
                                <td><?php echo $row['descripcion']; ?></td>
                                <td><?php echo $row['precio']; ?></td>
                                <?php 
                                    if($row['existencia'] <= 30){
                                ?>
                                        <td style="background: red; color: white;" class="titulo-pro"><?php echo $row['existencia']; ?></td>
                                <?php    }else{ ?>
                                        <td class="titulo-pro"><?php echo $row['existencia']; ?></td>
                                <?php } ?>
                                <td class="titulo-pro"><?php echo $row['proveedor']; ?></td>
                                <td class="titulo-pro"><?php echo $row['category']; ?></td>
                                <td class="imagen-pro"><img src="<?php echo $foto;?>" alt="<?php echo $row['descripcion']; ?>" style="width: 150px;"> </td>
                                
                                <td class="titulo-pro">
                                    <a class="link_add add_product" href="agregar_producto.php?cod=<?php echo $row['codigo_prod']; ?>" >
                                        <i class="fas fa-plus"></i>
                                    </a>

                                    <a class="link_edit" href="editar_producto.php?cod=<?php echo $row['codigo_prod']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a class="link_delete" href="eliminar_producto.php?cod=<?php echo $row['codigo_prod']; ?>">
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
    </section>
<script type="text/javascript">
    let ubicacionPrincipal = window.pageYOffset;
    window.onscroll = function Scroll(){
        let desplazamiento = window.pageYOffset;
        if(desplazamiento == 0){
            document.getElementById('navegacion').style.display = 'block';
            document.getElementById('navegacion').style.transition = '0.25s'
            document.getElementById('header').style.background = 'initial';
        }else{
            document.getElementById('navegacion').style.display = 'none';
            document.getElementById('navegacion').style.transition = '0.25s'
            document.getElementById('header').style.background = 'white';
        }
        ubicacionPrincipal = desplazamiento;
    }
</script>
</body>
</html>