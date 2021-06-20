<?php
	session_start();
	include "../bd/conn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<link rel="stylesheet" href="./css/style2.css">
	<title>Sisteme Ventas</title>
</head>
<body>
	<?php include "includes/header.php" ?>
	<section id="container">
		<div class="cont-1">
			<div class="inicio1">
				<h1 class="welcome">Modulos</h1>
				<div class="cont-card">

					<div class="card">
						<a href="./listar_usuarios.php"><img src="./img/inicio/usuarios.png"></a>
						<h4>Usuarios</h4>
					</div>

					<div class="card">
						<img src="./img/inicio/productos.png">
						<h4>Productos</h4>
					</div>

					<div class="card">
						<img src="./img/inicio/proveedor.png">
						<h4>Proveedores</h4>
					</div>

					<div class="card">
						<img src="./img/inicio/salidas.png">
						<h4>Salidas</h4>
					</div>

					<div class="card">
						<img src="./img/inicio/entradas.png">
						<h4>Entradas</h4>
					</div>

					<div class="card">
						<img src="./img/inicio/categorias.png">
						<h4>Categorias</h4>
					</div>
				</div>
			</div>
			<div class="inicio2">
				<h1 class="welcome">Información</h1>
				<div class="cont-card2">
					<div class="card2">
						<div class="tabla1">
							<h1>Ultimos usuarios registrados</h1>
							<table class="tabla-usuario">
								<tr>
									<th>Id</th>
									<th>Nombre usuario</th>
									<th>Rol</th>
								</tr>
								<?php 
									$query = mysqli_query($conn, "SELECT u.codigo, u.usuario, r.rol FROM usuarios u INNER JOIN rol r ON u.rol = r.id_rol ORDER BY u.codigo DESC LIMIT 5");

									$resultado = mysqli_num_rows($query);

									if($resultado > 0){
										while($row = mysqli_fetch_array($query)){ ?>

											<tr>
												<td><?php echo $row['codigo']; ?></td>
												<td><?php echo $row['usuario']; ?></td>
												<td><?php echo $row['rol']; ?></td>
											</tr>
								<?php			
										}
									}
								?>
							</table>
						</div>
					</div>
					<div class="card3">
						<div class="tabla2">
							<h1>Registros</h1>
							<table class="tabla-usuario1">
								<tr>
									<th><i class="fas fa-user"></i></th>
									<th>Usuarios registrados</th>
									<?php 
										$query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma' FROM usuarios");
										$row = mysqli_fetch_array($query_sum);
									?>
									<th><?php echo $row['suma'] ?></th>
								</tr>
							</table>
	
							<table class="tabla-usuario2">
								<tr>
									<th><i class="fas fa-sign-out-alt"></i></th>
									<th>Salidas de productos</th>
									<?php 
										$query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma1' FROM salida");
										$row = mysqli_fetch_array($query_sum);
									?>
									<th><?php echo $row['suma1'] ?></th>
								</tr>
							</table>
							
							<table class="tabla-usuario3">
								<tr>
									<th><i class="fas fa-people-arrows"></i></th>
									<th>Registro proveedores</th>
									<?php 
										$query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma1' FROM proveedor");
										$row = mysqli_fetch_array($query_sum);
									?>
									<th><?php echo $row['suma1'] ?></th>
								</tr>
							</table>
							
						</div>
					</div>	
			</div>
		</div>
	</section>
</body>
</html>