<?php

    if(empty($_SESSION['active'])){

        header('location: ../login.php');
    }
?>
    <header>
		<div class="header">
			<img class="photouser2" src="img/logo.png" alt="logo">
			<h1>Happy Feet Technology</h1>
			<div class="optionsBar">
				<?php if($_SESSION['rol'] != 1){ ?>
				<span class="user"><?php echo $_SESSION['user']; ?> | Empleado</span>
				<?php }else{ ?>
				<span class="user"><?php echo $_SESSION['user']; ?> | Administrador</span>
				<?php } ?>
				<img class="photouser" src="img/usuario.png" alt="Usuario">
				<a href="./php/salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
        <?php include "nav.php"; ?>
	</header>
	<div class="modal">
		<div class="bodyModal">
			<form action="" method="POST" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">

				<h1><i class="fas fa-archive"></i> Agregar Producto</h1><hr>
				<h2 class="nameProducto"></h2><br>
				<img src="" alt="" id="imgProd">

				<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del producto" require><br>

				<input type="text" name="precio" id="txtPrecio" placeholder="Precio del producto" require><br>

				<input type="hidden" name="producto_id" id="producto_id"  require>

				<input type="hidden" name="action" value="addProduct" require>

				<div class="alert alertAddProduct"></div>

				<button type="submit" class="btn-add">Agregar</button>
				<a href="#" class="btn-addC closeModal" onclick="closeModal();">Cerrar</a>
			</form>
		</div>
	</div>