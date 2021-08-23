<?php

    if(empty($_SESSION['active'])){

        header('location: ../login.php');
    }
?>
    <header id="header">
		<div class="header">
			<a href="#" class="ver_modal"><img class="photouser2" src="img/logo.png" alt="logo"></a>
			<h1>Happy Feet Technology</h1>
			<div class="optionsBar">
				<?php if($_SESSION['rol'] != 1){ ?>
				<span class="user"><?php echo $_SESSION['user']; ?> | Empleado</span>
				<?php }else{ ?>
				<span class="user"><?php echo $_SESSION['user']; ?> | Administrador</span>
				<?php } ?>
				<a href="#" onclick="openModal();"><img class="photouser" src="img/usuario.png" alt="Usuario"></a>
				<a href="./php/salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
        <?php include "nav.php"; ?>
	</header>
	<div class="modal">
		<div class="bodyModal">
		<div style="display: flex; border-radius: 20px;" class="containerDataUser">
            <div class="logoUser">
                <img src="img/avatar.jpg" >
            </div>
            <div class="divDataUser">
                <h4>Información Personal</h4>
				<?php 

					$user = $_SESSION['codigo'];

					$query = mysqli_query($conn, "SELECT u.codigo, u.correo, u.nombre, u.usuario, u.contraseña, u.rol as id_rol, r.rol FROM usuarios u INNER JOIN rol r
					WHERE u.codigo =  '$user'");
					$resultado = mysqli_num_rows($query);

					if($resultado > 0){
						$row = mysqli_fetch_array($query);
					}
				
				?>
                <div>
                    <label for="">Nombre: </label> <span><?php echo $row['nombre']; ?></span>
                </div>
                <div>
                    <label for="">Correo: </label> <span><?php echo $row['correo']; ?></span>
                </div>
                    <h4>Datos Usuario</h4>
                <div>
					<?php if($_SESSION['rol'] != 1){ ?>
                    <label for="">Rol: </label> <span>Empleado</span>
					<?php }else{ ?>
					<label for="">Rol: </label> <span>Administrador</span>
					<?php } ?>
                </div>
                <div>
                    <label for="">Usuario: </label> <span><?php echo $row['usuario']; ?></span>
                </div>
            </div>
			<a href="#" class="btn-addC closeModal" onclick="closeModal();">X</a>
        </div>
		</div>
	</div>