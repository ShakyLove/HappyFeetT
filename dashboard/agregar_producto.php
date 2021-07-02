<?php
    session_start();

    include "../bd/conn.php";
    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['cantidad']) || empty($_POST['precio'])){

            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            $codigo_prod = $_POST['codigo_prod'];
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $id_usuario = $_SESSION['codigo'];

            $query_insert = mysqli_query($conn, "INSERT INTO entrada(cod_producto, cantidad, precio_entrada, usuario_id) 
                                                        VALUES('$codigo_prod', '$cantidad', '$precio', '$id_usuario')");
            
            if($query_insert){

                $query_upd = mysqli_query($conn, "CALL actualizar_precio_producto($cantidad, $precio, $codigo_prod)");
                $resultado = mysqli_num_rows($query_upd);

                if($resultado > 0){

                    $alert = '<p class="msg_save">Producto agregado correctamente</p>';
                }else{

                    $alert = '<p class="msg_error">Error al agregar producto</p>';
                }
            }else{

                $alert = '<p class="msg_error">Error al insertar datos</p>';
            }   
        }
    }



if(empty($_REQUEST['cod'])){

    header('location: listar_productos.php');
    mysqli_close($conn);
}else{

    $cod = $_REQUEST['cod'];
    $query = mysqli_query($conn, "SELECT p.codigo_prod, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto 
                                        FROM productos p INNER JOIN proveedor pr
                                        ON p.proveedor = pr.nit_proveedor   
                                        WHERE codigo_prod = $cod");
    mysqli_close($conn);
    $result = mysqli_num_rows($query);

    if($result > 0){
        while($row = mysqli_fetch_array($query)){

            if($row['foto'] != 'img_producto.png'){

                $foto = 'img/uploads/'.$row['foto'];
            }else{

                $foto = 'img/'.$row['foto'];
            }

            $descripcion = $row['descripcion'];
            $proveedor = $row['proveedor'];
        }
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
	<title>Agregar Producto</title>
</head>
<body>
	<?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-archive"></i> Agregar Producto</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert: ''; ?></div>
            <form action="" method="POST">

            <div class="contenido" style="text-align: center;">
                <h2 style="font-family: 'Baloo 2';"><?php echo $descripcion; ?></h2>
                <img src="<?php echo $foto;?>" alt="<?php echo $row['descripcion']; ?>" style="width: 150px;">  
            </div>

                <input type="hidden" name="codigo_prod" value="<?php echo $cod; ?>">
                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad" placeholder="Cantidad del producto" id="cantidad">
                
                <label for="precio">Precio</label>
                <input type="text" name="precio" placeholder="Precio del producto" id="precio">

                <input type="submit" class="btn-save" value="Agregar Producto">
            </form>
        </div>
    </section>
</body>
</html>