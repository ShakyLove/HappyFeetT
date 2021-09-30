<?php include "./bd/conn.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Handlee&family=Rokkitt:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/login/login.css">
    <link rel="stylesheet" href="./dashboard/SweetAlert/dist/sweetalert2.min.css">
</head>

<body>
    <div class="container mt-5 shadow con">
        <div class="row align-items-stretch">
            <div class="col bg d-none d-lg-block">

            </div>
            <div class="col box-form rounded-end">
                <h2 class="text-center py-2">Registro</h2>
                <form action="" method="POST">

                    <div class="mb-2 col-md-8 mx-auto">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" autofocus id="nombre">
                        <p id="mensaje" style="color: red;"></p>
                    </div>
                    <div class="mb-2 col-md-8 mx-auto">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="correo" autofocus id="correo">
                    </div>
                    <div class="mb-2 col-md-8 mx-auto">
                        <label for="user" class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="usuario" autofocus id="Usuario">
                    </div>
                    <div class="mb-2 col-md-8 mx-auto">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="contraseña" id="contraseña">
                    </div>
                    <div class="d-grid col-md-8 ingresa mx-auto mb-2">
                        <input type="submit" class="btn btn-primary" value="Registrarse" name="registrar" id="enviar">
                    </div>
                    <div class="d-grid col-md-8 boton mx-auto mb-2">
                        <a href="./login.php" class="btn btn-dark bog" id="volver" role="button">volver</a>
                    </div>
                    <div class="my-3 link col-md-8 mx-auto text-center">
                        <span><a href="./register.php"></a></span><br>
                        <span><a href="./cambio.php"></a></span><br>
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
    <script src="jquery/jquery-3.3.1.min.js"></script>
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./dashboard/SweetAlert/dist/sweetalert2.all.min.js"></script>
    <script src="jquery/main2.js""></script>
</body>

</html>