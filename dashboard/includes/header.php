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
				<span>|</span>
				<span class="user"><?php echo $_SESSION['user']; ?></span>
				<img class="photouser" src="img/usuario.png" alt="Usuario">
				<a href="./php/salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
        <?php include "nav.php"; ?>
	</header>