<?php
    session_start();
    include "../bd/conn.php";

    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove']) || empty($_POST['categoria'])){

            $alert = 1;
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
                $alert = 2;
            }else{

                $alert = 3;
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
            $codigo_prod = $row['codigo_prod'];
            $proveedor = $row['proveedor'];
            $id_proveedor = $row['nit_proveedor'];
            $id_cat = $row['categoria_id'];
            $nombre_cat = $row['tipo_c'];
            $precio = $row['precio'];
            $nombre_pro = $row['descripcion'];
            $picture = $row['foto'];

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
        <div class="form_register" style="width: 900px;">
            <h1><i class="fas fa-archive"></i> Actualizar Producto</h1>
            <hr>
            <div class="alert"></div>
            <?php isset($alert) ? $alert: ''; ?>
            <form action="" method="POST" enctype="multipart/form-data" style="display: flex;">
                <input type="hidden" id="valor_form" value="<?php echo $alert; ?>">
                <input type="hidden" name="id" value="<?php echo $codigo_prod; ?>">
                <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $picture; ?>">
                <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $picture; ?>">
                <div style="width: 50%; margin: 0 20px; margin-top: 45px;">
                    <div class="label">
                        <label for="proveedor">Proveedor</label>
                        <?php
                            $query_proveedor = mysqli_query($conn, "SELECT nit_proveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                            $resultado = mysqli_num_rows($query_proveedor);

                            $row = mysqli_fetch_array($query_proveedor);
                        ?>
                        <select name="proveedor" id="proveedor" class="notItemOne">
                        <option value="<?php echo $id_proveedor; ?>"><?php echo $proveedor; ?></option>
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
                    </div>

                    <div class="label">
                        <label for="producto">Producto</label>
                        <input type="text" name="producto" placeholder="Nombre del producto" id="producto" value="<?php echo $nombre_pro; ?>">
                    </div>

                    <div class="label">
                        <label for="precio">Precio</label>
                        <input type="number" name="precio" placeholder="Precio del producto" id="precio" value="<?php echo $precio; ?>">
                    </div>
                    <p id="parrafo" style="text-align: end; color: red;"></p>

                    <div class="label">
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
                    </div>
                </div>
                <div style="width: 50%;">
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
                    border-radius: 5px; background: black; color:white; display: inline-block; text-align: center;">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
<script type="text/javascript">
    let ubicacionPrincipal = window.pageYOffset;
    window.onscroll = function Scroll(){
        let desplazamiento = window.pageYOffset;
        if(desplazamiento == 0){
            document.getElementById('navegacion').style.display = 'block';
            document.getElementById('header').style.background = 'initial';
        }else{
            document.getElementById('navegacion').style.display = 'none';
            document.getElementById('header').style.background = 'white';
        }
        ubicacionPrincipal = desplazamiento;
    }

        $('input#precio').keypress(function(event){

            if (this.value.length >= 9) {
            $('#parrafo').html('Máximo 9 dígitos');
            return false;
        }else{
            if(this.value.length < 9){
                $('#parrafo').html('');
                return true;
            }
        }
    });

    valor = $('#valor_form').val();
    if(valor == 1){

        Swal.fire({
            title: 'Todos los campos son obligatorios',
            icon: 'error',
            confirmButtonText: `Aceptar`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {                
                
                $('#valor_form').val('0');
                
            } 
        });
    }else{
        if(valor == 2){

            Swal.fire({
                title: 'Producto actualizado con éxito',
                icon: 'success',
                confirmButtonText: `Aceptar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                                
                    var url = 'listar_productos.php';
                    $(location).attr('href',url);
                } 
            });
        }else{
            if(valor == 3){

                Swal.fire({
                    title: 'Error al actualizar producto',
                    icon: 'error',
                    confirmButtonText: `Aceptar`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {                
                
                        var url = 'listar_productos.php';
                        $(location).attr('href',url);
                    } 
                });
            }
        }
    }
</script>
</body>
</html>