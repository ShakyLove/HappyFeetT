<?php
	session_start();
	include "../../bd/conn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "../includes/scripts.php"; ?>
	<link rel="stylesheet" href="../css/style2.css">
	<title>Happy Feet T.</title>
</head>
<body>
	<?php include "../includes/header.php" ?>
	<section id="container">
		<div class="cont-1">
			<div class="inicio1">
				<h1 class="welcome">Modulos</h1>
				<div class="cont-card">

					<div class="card">
						<a href="./listar_usuarios.php"><img src="../img/inicio/usuarios.png"></a>
						<h4>Usuarios</h4>
					</div>

					<div class="card">
						<a href="./listar_productos.php"><img width="100%" src="../img/inicio/producto.png"></a>
						<h4>Productos</h4>
					</div>

					<div class="card">
						<a href="listar_categoria.php"><img src="../img/inicio/categorias1.png"></a>
						<h4>Categorias</h4>
					</div>

					<div class="card">
						<a href="./listar_proveedor.php"><img src="../img/inicio/proveedor.png"></a>
						<h4>Proveedores</h4>
					</div>

					<div class="card">
						<a href="listar_entradas.php"><img src="../img/inicio/entrada.png"></a>
						<h4>Entradas</h4>
					</div>

					<div class="card">
						<a href="listar_salidas.php"><img src="../img/inicio/ventas.png"></a>
						<h4>Salidas</h4>
					</div>
				</div>
			</div>

			<div class="inicio2">
				<div class="cont-card2">
					<div class="card2">
						<div class="tabla1">
							<h1>Ultimas entradas</h1>
							<table class="tabla-usuario">
								<tr>
									<th>Cantidad</th>
									<th>Producto</th>
									<th>Fecha</th>
								</tr>
								<?php 
									$query = mysqli_query($conn, "SELECT e.cantidad, e.fecha_entrada, p.descripcion FROM entrada e INNER JOIN productos p ON e.cod_producto = p.codigo_prod ORDER BY e.id_entrada DESC LIMIT 5");

									$resultado = mysqli_num_rows($query);

									if($resultado > 0){
										while($row = mysqli_fetch_array($query)){ 
											
											$formato = 'Y-m-d H:i:s';
											$fecha = DateTime::createFromFormat($formato, $row['fecha_entrada']);
											?>

											<tr>
												<td><?php echo $row['cantidad']; ?></td>
												<td><?php echo $row['descripcion']; ?></td>
												<td><?php echo $fecha->format('d-m-Y'); ?></td>
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
									<th style="width: 15%;"><i class="fas fa-user"></i></th>
									<th style="text-align: start; width: 70%;">Usuarios registrados</th>
									<?php 
										$query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma' FROM usuarios");
										$row = mysqli_fetch_array($query_sum);
									?>
									<th style="text-align: center; width: 15%;"><?php echo $row['suma'] ?></th>
								</tr>
							</table>
	
							<table class="tabla-usuario2">
								<tr>
									<th style="width: 15%;"><i class="fas fa-clipboard-check"></i></th>
									<th style="text-align: start; width: 70%">Entradas de productos</th>
									<?php 
										$query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma1' FROM entrada");
										$row = mysqli_fetch_array($query_sum);
									?>
									<th style="text-align: center; width: 15%;"><?php echo $row['suma1'] ?></th>
								</tr>
							</table>
							
							<table class="tabla-usuario3">
								<tr>
									<th style="width: 15%;"><i class="fas fa-dolly-flatbed"></i></th>
									<th style="text-align: start; width: 70%;">Ventas realizadas</th>
									<?php 
										$query_sum = mysqli_query($conn, "SELECT COUNT(*) AS 'suma1' FROM salida");
										$row = mysqli_fetch_array($query_sum);
									?>
									<th style="text-align: center; width: 15%;"><?php echo $row['suma1'] ?></th>
								</tr>
							</table>
						</div>
					</div>
				</div>	
			</div>
			
			<div class="inicio3">
				<div class="card4">
						<div class="tabla4">
							<h1>Productos agregados recientemente</h1>
							<table class="tabla-productos">
								<tr>
									<th>Foto</th>
									<th>Descripcion</th>
								</tr>
								<?php 
									$query = mysqli_query($conn, "SELECT * FROM productos ORDER BY codigo_prod DESC LIMIT 4");

									$resultado = mysqli_num_rows($query);

									if($resultado > 0){
										while($row = mysqli_fetch_array($query)){ 
											
											if($row['foto'] != 'img_producto.png'){

												$foto = '../img/uploads/'.$row['foto'];
											}else{
				
												$foto = '../img/'.$row['foto'];
											}
									?>

											<tr>
												<td class="img-index"><img src="<?php echo $foto;?>" alt="<?php echo $row['descripcion']; ?>" style="width: 50px;"> </td>
												<td><?php echo $row['descripcion']; ?></td>
											</tr>
								<?php			
										}
									}
								?>
							</table>
						</div>
					</div>
			</div>
		</div>
	</section>
</body>
</html>