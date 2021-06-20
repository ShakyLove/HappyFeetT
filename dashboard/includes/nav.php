<nav>
			<ul>
				<li><a href="index.php">Inicio</a></li>
				<?php
					if($_SESSION['rol'] ==1){
				?>
				<li class="principal">
					<a href="#">Usuarios</a>
					<ul>
						<li><a href="#">Perfil de Usuario</a></li>
						<li><a href="registro_usuario.php">Nuevo usuario</a></li>
						<li><a href="listar_usuarios.php">Lista de Usuarios</a></li>
					</ul>
				</li>
				<?php }else{ ?>
				<li class="principal">
					<a href="#">Usuarios</a>
					<ul>
						<li><a href="#">Perfil de Usuario</a></li>
						<li><a href="listar_usuarios.php">Lista de Usuarios</a></li>
					</ul>
				</li>
				<?php }?>
				<li class="principal">
					<a href="#">Salida</a>
					<ul>
						<li><a href="#">Lista de Salida</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Proveedores</a>
					<ul>
						<li><a href="#">Lista de Proveedores</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Productos</a>
					<ul>
						<li><a href="#">Lista de Productos</a></li>
                        <li><a href="#">Entradas de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Categoria</a>
					<ul>
						<li><a href="#">Lista de categorias</a></li>
					</ul>
				</li>
			</ul>
		</nav>