<?php
    session_start();
    include "../bd/conn.php";

    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove']) || empty($_POST['categoria'])){

            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            $codproducto = $_POST['id'];
            $proveedor = $_POST['proveedor'];
            $producto = $_POST['producto'];
            $precio = $_POST['precio'];
            $imgProducto = $_POST['foto_actual'];
            $imgRemove = $_POST['foto_remove'];
            $categoria = $_POST['categoria'];
            $usuario_id = $_SESSION['codigo'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];
            $upd = '';

            if($nombre_foto != ''){

                $destino = 'img/uploads/';
                $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
                $imgProducto = $img_nombre.'.jpg';
                $src = $destino.$imgProducto;
            }else{

                if($_POST['foto_actual'] != $_POST['foto_remove']){
                    $imgProducto = 'img_producto.png';
                }
            }

            $query_update = mysqli_query($conn, "UPDATE productos SET 
                                                        descripcion = '$producto', proveedor = '$proveedor', precio = '$precio', foto = '$imgProducto', category = '$categoria' 
                                                        WHERE codigo_prod = '$codproducto'");

            if($query_update){

                if(($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove'])){

                    unlink('img/uploads/'.$_POST['foto_actual']);
                }
                if($nombre_foto != ''){
                    move_uploaded_file($url_temp,$src);
                }
                $alert = '<p class="msg_save">Producto actualizado correctamente</p>';
            }else{

                $alert = '<p class="msg_error">Error al actualizar el producto</p>';
            }
        }
    }

    //validar producto
    if(empty($_REQUEST['cod'])){

        header('location: listar_productos.php');
    }else{

        $id_producto = $_REQUEST['cod'];
        if(!is_numeric(($id_producto))){

            header('location: listar_productos.php');
        }
        $query_producto = mysqli_query($conn, "SELECT p.codigo_prod, p.descripcion, p.precio, p.existencia, pr.proveedor, pr.nit_proveedor, p.foto, c.descripcion as tipo_c, c.categoria_id
                                            FROM ((productos p 
                                            INNER JOIN proveedor pr ON p.proveedor = pr.nit_proveedor )
                                            INNER JOIN categorias c ON p.category  = c.categoria_id) 
                                            WHERE codigo_prod = $id_producto
                                            AND p.estatus = 1");

        $resultado_producto = mysqli_num_rows($query_producto);

        $foto = '';
        $classRemove = 'notBlock';

        if($resultado_producto > 0){

            $row = mysqli_fetch_assoc($query_producto);

            if($row['foto'] != 'img_producto.png'){

                $classRemove = '';
                $foto = '<img id="img" src= "img/uploads/'.$row['foto'].'" alt="Producto">';
            }
            $id_cat = $row['categoria_id'];
            $nombre_cat = $row['tipo_c'];
            $precio = $row['precio'];
            $nombre_pro = $row['descripcion'];

        }else{

            header('location: listar_productos.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Producto</title>
</head>
<body>
	<?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-archive"></i> Actualizar Producto</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['codigo_prod']; ?>">
                <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $row['foto']; ?>">
                <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $row['foto']; ?>">

                <label for="proveedor">Proveedor</label>
                <?php
                    $query_proveedor = mysqli_query($conn, "SELECT nit_proveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                    $resultado = mysqli_num_rows($query_proveedor);
                ?>
                <select name="proveedor" id="proveedor" class="notItemOne">
                <option value="<?php echo $row['nit_proveedor']; ?>"><?php echo $row['proveedor']; ?></option>
                <?php 
                    if($resultado > 0){
                        while($row = mysqli_fetch_array($query_proveedor)){

                ?>
                    <option value="<?php echo $row['nit_proveedor']; ?>"><?php echo $row['proveedor']; ?></option>

                <?php
                        }
                    }
                ?>
                </select>

                <label for="producto">Producto</label>
                <input type="text" name="producto" placeholder="Nombre del producto" id="producto" value="<?php echo $nombre_pro; ?>">

                <label for="precio">Precio</label>
                <input type="number" name="precio" placeholder="Precio del producto" id="precio" value="<?php echo $precio; ?>">

                <label for="categoria">Categoria</label>
                <?php
                    $query_categoria = mysqli_query($conn, "SELECT * FROM categorias WHERE estatus = 1 ORDER BY descripcion ASC");
                    $resultado = mysqli_num_rows($query_categoria);
                ?>
                <select name="categoria" id="categoria" class="notItemOne">
                <option value="<?php echo $id_cat; ?>"><?php echo $nombre_cat; ?></option>
                <?php 
                    if($resultado > 0){
                        while($row = mysqli_fetch_array($query_categoria)){

                ?>
                    <option value="<?php echo $row['categoria_id']; ?>"><?php echo $row['descripcion']; ?></option>

                <?php
                        }
                    }
                ?>
                </select>

                <div class="photo">
                    <label for="foto">Foto</label>
                        <div class="prevPhoto">
                            <span class="delPhoto <?php echo $classRemove; ?>">X</span>
                            <label for="foto"></label>
                            <?php echo $foto; ?>
                        </div>
                        <div class="upimg">
                            <input type="file" name="foto" id="foto">
                        </div>
                        <div id="form_alert"></div>
                </div>

                <input type="submit" class="btn-save" value="Actualizar Producto">
                <a href="listar_productos.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: #df4759; display: inline-block; text-align: center;">Cancelar</a>
            </form>
        </div>
    </section>
</body>
</html>