<?php
    session_start();

    include "../bd/conn.php";
    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['cantidad']) || empty($_POST['precio'])){

            $alert = 1;
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

                    $alert = 2;
                }else{

                    $alert = 3;
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
            <div class="alert"></div>
            <?php isset($alert) ? $alert: ''; ?>
            <form action="" method="POST">
            <input type="hidden" id="valor_form" value="<?php echo $alert; ?>">
            <div class="contenido" style="text-align: center;">
                <h2 style="font-family: 'Baloo 2';"><?php echo $descripcion; ?></h2>
                <img src="<?php echo $foto;?>" alt="<?php echo $row['descripcion']; ?>" style="width: 150px;">  
            </div>

                <input type="hidden" name="codigo_prod" value="<?php echo $cod; ?>">

                <div class="label">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" placeholder="Cantidad del producto" id="cantidad">
                </div>
                <p id="parrafo_cant" style="text-align: end; color: red;"></p>
                
                <div class="label">
                    <label for="precio">Precio</label>
                    <input type="text" name="precio" placeholder="Precio del producto" id="precio">
                </div>
                <p id="parrafo" style="text-align: end; color: red;"></p>

                <input type="submit" class="btn-save" value="Agregar Producto">
                <a href="listar_productos.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: black; color:white; display: inline-block; text-align: center;">Cancelar</a>
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

    $('input#cantidad').keypress(function(event){
    
            if (this.value.length >= 3) {
            $('#parrafo_cant').html('Máximo 3 dígitos');
            return false;
        }else{
            if(this.value.length < 3){
                $('#parrafo_cant').html('');
                return true;
            }
        }
        });

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
    unidad = $('#cantidad').val();
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
                title: 'Producto agregado con éxito',
                text: unidad,
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
                    title: 'Error al agregar producto',
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