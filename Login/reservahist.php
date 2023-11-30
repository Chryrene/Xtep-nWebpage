<?php
session_start();

// Definir la ruta del archivo JSON que deseas leer
$archivo_pedidos = __DIR__ . "/pedidos/Mmoreno_pedidos.json"; // Asegúrate de que este archivo exista en tu servidor

$pedidos = [];
if (file_exists($archivo_pedidos)) {
    $json_data = file_get_contents($archivo_pedidos);
    $pedidos = json_decode($json_data, true);

    if (is_null($pedidos)) {
        error_log("Error al decodificar el JSON del archivo: " . $archivo_pedidos);
        $pedidos = [];
    }
} else {
    error_log("El archivo de pedidos no existe: " . $archivo_pedidos);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 56px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            margin-top: 20px;
        }
        .navbar-brand {
            font-weight: bold;
            color: black;
        }
        .nav-link {
            color: black;
        }
        .nav-link:hover {
            color: black;
        }
        .bg-custom {
            background-color: black;
        }
        .thead-custom {
            background-color: #0056b3;
            color: #fff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-custom fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="Cliente.php">Volver Atrás</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Historial de Pedidos</h2>
    <?php if (!empty($pedidos)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['folio'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($pedido['email'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($pedido['direccion'] ?? 'N/A'); ?></td>
                            <td>
                                <?php foreach ($pedido['productos'] as $producto): ?>
                                    <?php
                                    // Verificar si es un producto regular o una promoción
                                    if (isset($producto['nombre'])) {
                                        $nombre_producto = $producto['nombre'];
                                        $precio_producto = preg_replace('/[^0-9.]+/', '', $producto['precio']);
                                    } elseif (isset($producto['nombreb'])) {
                                        $nombre_producto = $producto['nombreb'];
                                        $precio_producto = preg_replace('/[^0-9.]+/', '', $producto['preciob']);
                                    } else {
                                        $nombre_producto = 'Producto no especificado';
                                        $precio_producto = 0;
                                    }

                                    $precio_producto = floatval($precio_producto);
                                    ?>
                                    <p><?php echo htmlspecialchars($nombre_producto); ?> - <?php echo htmlspecialchars($producto['cantidad'] ?? 0); ?> x $<?php echo number_format($precio_producto, 2); ?></p>
                                <?php endforeach; ?>
                            </td>
                            <td>$<?php echo number_format($pedido['total'] ?? 0, 2); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($pedido['estatus'])); ?></td>
                            <td>
                                <?php if ($pedido['estatus'] === 'pendiente'): ?>
                                    <a href="pagar_pedido.php?folio=<?php echo urlencode($pedido['folio']); ?>" class="btn btn-primary">Pagar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center">No hay historial de pedidos para mostrar.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
