<?php include("./bd/conn.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de contraseña</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Handlee&family=Rokkitt:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/login/login.css">
</head>
<body>
    <div class="container mt-5 shadow con">
        <div class="row align-items-stretch">
            <div class="col bg d-none d-lg-block">

            </div>
            <div class="col box-form rounded-end">
                <div class="text-center m-0 logo">
                    <img src="./img/logo.png" width="100px" height="100px" alt="">
                </div>
                <h2 class="text-center py-4">Recupera tu cuenta</h2>
                <?php if(isset($_SESSION['message'])){ ?>
                    <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show col-md-8 mx-auto" role="alert">
                    <?= $_SESSION['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php session_unset(); } ?>
                <form action="./bd/login/cambioContra.php" method="POST" >
                    <div class="mb-2 col-md-8 mx-auto">
                        <label for="user" class="form-label">Ingresa tu nombre de usuario</label>
                        <input type="text" class="form-control" name="usuario" autofocus>
                    </div>
                    <div class="mb-4 col-md-8 mx-auto">
                        <label for="password" class="form-label">Digita una nueva contraseña</label>
                        <input type="password" class="form-control" name="contraseña">
                    </div>
                    <div class="d-grid col-md-8 ingresa mx-auto mb-2">
                        <input type="submit" class="btn btn-primary" value="Enviar" name="change">
                    </div>
                    <div class="d-grid col-md-8 boton mx-auto mb-2">
                        <a href="./login.php" class="btn btn-dark bog" id="volver" role="button">volver</a>
                    </div>
                    <div class="my-3 link col-md-8 mx-auto text-center">
                        <span><a href="./register.php"></a></span><br>
                        <span><a href="./cambio.php"></a></span><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js" integrity="sha384-lpyLfhYuitXl2zRZ5Bn2fqnhNAKOAaM/0Kr9laMspuaMiZfGmfwRNFh8HlMy49eQ" crossorigin="anonymous"></script>
    <script src="../jquery/jquery-3.3.1.min.js"></script>
    <script src="../js/consul.js"></script>
    <script src="./bootstrap/js/bootstrap.min.js"></script>     
</body>
</html>