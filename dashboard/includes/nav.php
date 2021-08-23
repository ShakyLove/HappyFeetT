<nav id="navegacion">
			<ul>
				<li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
				<?php
					if($_SESSION['rol'] ==1){
				?>
				<li class="principal">
					<a href="listar_usuarios.php"><i class="fas fa-users"></i> Usuarios</a>
					<ul>
						<li><a href="registro_usuario.php">Nuevo usuario</a></li>
						<li><a href="listar_usuarios.php">Lista de Usuarios</a></li>
					</ul>
				</li>
				<?php }else{ ?>
				<li class="principal">
					<a href="listar_usuarios.php"><i class="fas fa-users"></i> Usuarios</a>
					<ul>
						<li><a href="listar_usuarios.php">Lista de Usuarios</a></li>
					</ul>
				</li>
				<?php }?>
				<li class="principal">
					<a href="listar_proveedor.php"><i class="fas fa-people-carry"></i> Proveedores</a>
					<ul>
						<li><a href="registro_proveedor.php">Nuevo Proveedor</a></li>
						<li><a href="listar_proveedor.php">Lista de Proveedores</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="listar_productos.php"><i class="fas fa-archive"></i> Productos</a>
					<ul>
						<li><a href="registro_producto.php">Nuevo Producto</a></li>
						<li><a href="listar_productos.php">Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="listar_categoria.php"><i class="fas fa-align-justify"></i> Categorías</a>
				</li>
				<li class="principal">
					<a href="listar_entradas.php"><i class="fas fa-clipboard-check"></i> Entradas</a>
				</li>
				<li class="principal">
					<a href="listar_salidas.php"><i class="fas fa-dolly"></i> Salidas</a>
					<ul>
						<li><a href="nueva_venta.php">Generar Salida</a></li>
						<li><a href="listar_salidas.php">Lista de Salidas</a></li>
					</ul>
				</li>
			</ul>
		</nav>