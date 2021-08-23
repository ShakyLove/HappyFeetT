<?php
    session_start();
    include "../bd/conn.php";

    if(!empty($_POST)){

        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])){

            $alert = 1;
        }else{

            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $usuario_id = $_SESSION['codigo'];

            $query_insert = mysqli_query($conn, "INSERT INTO proveedor(proveedor, contacto, telefono, direccion, usuario_id) 
                                                            VALUES('$proveedor', '$contacto', '$telefono', '$direccion', '$usuario_id')");

            if($query_insert){
                $alert = 2;
            }else{

                $alert = 3;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro de Proveedor</title>
</head>
<body>
	<?php include "includes/header.php" ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-people-carry"></i> Registro Proveedor</h1>
            <hr>
            <div class="alert"></div>
            <?php  isset($alert) ? $alert: ''; ?>
            <form action="" method="POST">
                <input type="hidden" id="valor_form" value="<?php echo $alert; ?>">
                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" placeholder="Nombre del proveedor" id="proveedor">

                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" placeholder="Nombre completo del contacto" id="contacto">

                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" placeholder="Numero de telefono" id="telefono">

                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" placeholder="Direccion completa" id="direccion">

                <input type="submit" class="btn-save" value="Guardar Proveedor">
                <a href="listar_proveedor.php" class="btn-save closeForm" style="width: 100%; margin-top: 1px; 
                border-radius: 5px; background: black; color: white; display: inline-block; text-align: center;">Cancelar</a>
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
                title: 'Proveedor registrado con éxito',
                icon: 'success',
                confirmButtonText: `Aceptar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                                
                    var url = 'listar_proveedor.php';
                    $(location).attr('href',url);
                } 
            });
        }else{
            if(valor == 3){

                Swal.fire({
                    title: 'Error al agregar proveedor',
                    icon: 'error',
                    confirmButtonText: `Aceptar`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {                
                
                        var url = 'listar_proveedor.php';
                        $(location).attr('href',url);
                    } 
                });
            }
        }
    }
</script>
</body>
</html>