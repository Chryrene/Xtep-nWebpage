<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'roles';

$conex = mysqli_connect($host, $username, $password, $database);

if (!$conex) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard del Cliente</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container my-5">
    <h1 class="text-center mb-4">Bienvenido, <?php echo ($_SESSION['usuario']); ?></h1>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="list-group">
                    <a href="Visualizaradmin.php" class="list-group-item list-group-item-action list-group-item-warning">Actualizar catálogos de menú</a>
                    <a href="actprom.php" class="list-group-item list-group-item-action list-group-item-warning">Actualizar Promociones</a>
                    <a href="actbanquetes.php" class="list-group-item list-group-item-action list-group-item-warning">Actualizar catálogo de banquetes</a>
                    <a href="Alqhacienda.php" class="list-group-item list-group-item-action list-group-item-warning">Alquilar hacienda</a>
                    <a href="reservahist.php" class="list-group-item list-group-item-action list-group-item-warning">Historial de pedidos</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
