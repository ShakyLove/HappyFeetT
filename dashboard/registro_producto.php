<?php
    session_start();
    include "../bd/conn.php";

    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['cantidad']) || empty($_POST['categoria'])){

            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            $proveedor = $_POST['proveedor'];
            $producto = $_POST['producto'];
            $precio = $_POST['precio'];
            $cantidad = $_POST['cantidad'];
            $categoria = $_POST['categoria'];
            $usuario_id = $_SESSION['codigo'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];
            $imgProducto = 'img_producto.png';

            if($nombre_foto != ''){

                $destino = 'img/uploads/';
                $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
                $imgProducto = $img_nombre.'.jpg';
                $src = $destino.$imgProducto;
            }

            $query_insert = mysqli_query($conn, "INSERT INTO productos(proveedor, descripcion, precio, existencia, usuario_id, foto, category) 
                                                            VALUES('$proveedor', '$producto', '$precio', '$cantidad', '$usuario_id','$imgProducto', '$categoria')");

            if($query_insert){
                if($nombre_foto != ''){
                    move_uploaded_file($url_temp,$src);
                }
                $alert = '<p class="msg_save">Producto guardado correctamente</p>';
            }else{

                $alert = '<p class="msg_error">Error al guardar el producto</p>';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro de Producto</title>
</head>
<body>
	<?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-archive"></i> Registro Producto</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>
            <form action="" method="POST" enctype="multipart/form-data">

                <label for="proveedor">Proveedor</label>
                <?php
                    $query_proveedor = mysqli_query($conn, "SELECT nit_proveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                    $resultado = mysqli_num_rows($query_proveedor);
                ?>
                <select name="proveedor" id="proveedor">
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
                <input type="text" name="producto" placeholder="Nombre del producto" id="producto">

                <label for="precio">Precio</label>
                <input type="number" name="precio" placeholder="Precio del producto" id="precio">

                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad" placeholder="Cantidad del producto" id="cantidad">

                <label for="categoria">Categoria</label>
                <?php
                    $query_categoria = mysqli_query($conn, "SELECT * FROM categorias WHERE estatus = 1 ORDER BY descripcion ASC");
                    $resultado = mysqli_num_rows($query_categoria);
                ?>
                <select name="categoria" id="categoria">
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
                            <span class="delPhoto notBlock">X</span>
                            <label for="foto"></label>
                        </div>
                        <div class="upimg">
                            <input type="file" name="foto" id="foto">
                        </div>
                        <div id="form_alert"></div>
                </div>

                <input type="submit" class="btn-save" value="Guardar Producto">
                <a href="listar_productos.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: #df4759; display: inline-block; text-align: center;">Cancelar</a>
            </form>
        </div>
    </section>
</body>
</html>