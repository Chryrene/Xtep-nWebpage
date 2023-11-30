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

// Inicializar mensaje de confirmación
$_SESSION['mensaje'] = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnAccion'])) {
        $nombre_producto = $_POST['nombreb']; // Cambiado para coincidir con el nombre de la columna
        $descrip_producto = $_POST['descripb']; // Cambiado para coincidir con el nombre de la columna
        $precio_producto = $_POST['preciob']; // Cambiado para coincidir con el nombre de la columna

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $producto_encontrado = array_search($nombre_producto, array_column($_SESSION['carrito'], 'nombreb'));
        if ($producto_encontrado === false) {
            $_SESSION['carrito'][] = [
                'nombreb' => $nombre_producto,
                'descripb' => $descrip_producto,
                'preciob' => $precio_producto,
                'cantidad' => 1
            ];
            $_SESSION['mensaje'] = 'Producto añadido al carrito';
        } else {
            $_SESSION['carrito'][$producto_encontrado]['cantidad']++; // Incrementar la cantidad si el producto ya está en el carrito
            $_SESSION['mensaje'] = 'Cantidad actualizada en el carrito';
        }
    }

    if (isset($_POST['btnAccion']) && $_POST['btnAccion'] == 'elimina') {
        $nombre_producto = $_POST['nombreb']; // Cambiado para coincidir con el nombre de la columna
        foreach ($_SESSION['carrito'] as $indice => $producto) {
            if ($producto['nombreb'] == $nombre_producto) {
                unset($_SESSION['carrito'][$indice]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar el array después de eliminar un producto
                $_SESSION['mensaje'] = 'Producto eliminado del carrito';
                break;
            }
        }
    }
}

$consulta = "SELECT * FROM promos";
$resultado = mysqli_query($conex, $consulta);
$total_items = count($_SESSION['carrito'] ?? []);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones</title>
    <link rel="stylesheet" href="CSS/stylec.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Mensaje de confirmación -->
<?php if(!empty($_SESSION['mensaje'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['mensaje']; ?>
    </div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="Visualizar2.php">Menú Xtepén</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="Cliente.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ver_carrito.php">Carrito<span class="badge bg-secondary"><?php echo $total_items; ?></span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Promociones -->
<div class="container mt-4">
    <div class="row">
        <?php while($promo = mysqli_fetch_assoc($resultado)): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                            <h5 class="card-title"><?php echo $promo['nombrep']; ?></h5>
                        <p class="card-text"><?php echo $promo['descripp']; ?></p>
                        <p class="card-text">$<?php echo $promo['preciop']; ?></p>
                        <form action="" method="post">
                            <input type="hidden" name="nombreb" value="<?php echo $promo['nombrep']; ?>">
                            <input type="hidden" name="descripb" value="<?php echo $promo['descripp']; ?>">
                            <input type="hidden" name="preciob" value="<?php echo $promo['preciop']; ?>">
                            <button class="btn btn-primary" name="btnAccion" value="agrega" type="submit">Añadir al carrito</button>
                            <button class="btn btn-danger" name="btnAccion" value="elimina" type="submit">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
