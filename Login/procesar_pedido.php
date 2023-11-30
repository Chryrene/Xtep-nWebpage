<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Función para calcular el total del carrito
function calcularTotalDelCarrito($carrito) {
    $total = 0;
    foreach ($carrito as $producto) {
        if (isset($producto['precio'])) {
            $precio = floatval(preg_replace('/[^\d.]/', '', $producto['precio']));
            $cantidad = intval($producto['cantidad']);
            $total += $precio * $cantidad;
        } elseif (isset($producto['preciob'])) {
            $preciob = floatval(preg_replace('/[^\d.]/', '', $producto['preciob']));
            $cantidadb = intval($producto['cantidad']);
            $total += $preciob * $cantidadb;
        }
    }
    return $total;
}

// Asegúrate de que el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

// Verificar si el carrito existe y tiene productos
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    $_SESSION['error'] = 'No hay productos en el carrito.';
    header('Location: ver_carrito.php');
    exit;
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $total = calcularTotalDelCarrito($_SESSION['carrito']);

    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);

    $errores = [];
    if (empty($nombre)) { $errores[] = 'El nombre es obligatorio.'; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errores[] = 'El correo electrónico no es válido.'; }
    if (empty($direccion)) { $errores[] = 'La dirección es obligatoria.'; }

    if (!empty($errores)) {
        $_SESSION['errores'] = $errores;
        header('Location: ver_carrito.php');
        exit;
    }

    $folio = uniqid();
    $estatus_reserva = 'pendiente';

    $pedido = [
        'folio' => $folio,
        'fecha' => date('Y-m-d H:i:s'),
        'usuario' => $_SESSION['usuario'],
        'email' => $email,
        'direccion' => $direccion,
        'productos' => array_map(function($producto) {
            if (isset($producto['precio'])) {
                $producto['menu'] = [
                    'nombre' => $producto['nombre'],
                    'precio' => floatval(preg_replace('/[^\d.]/', '', $producto['precio'])),
                    'cantidad' => intval($producto['cantidad']),
                    'subtotal' => floatval(preg_replace('/[^\d.]/', '', $producto['precio'])) * intval($producto['cantidad'])
                ];
            }
            if (isset($producto['preciob'])) {
                $producto['banquete'] = [
                    'nombre' => $producto['nombreb'],
                    'precio' => floatval(preg_replace('/[^\d.]/', '', $producto['preciob'])),
                    'cantidad' => intval($producto['cantidad']),
                    'subtotal' => floatval(preg_replace('/[^\d.]/', '', $producto['preciob'])) * intval($producto['cantidad'])
                ];
            }
            return $producto;
        }, $_SESSION['carrito']),
        'total' => $total,
        'estatus' => $estatus_reserva
    ];

    $archivo_pedido = __DIR__ . '/pedidos/' . $_SESSION['usuario'] . '_pedidos.json';
    if (!file_exists(__DIR__ . '/pedidos')) {
        mkdir(__DIR__ . '/pedidos', 0777, true);
    }

    $pedidos_existentes = file_exists($archivo_pedido) ? json_decode(file_get_contents($archivo_pedido), true) : [];
    if (!is_array($pedidos_existentes)) {
        $pedidos_existentes = [];
    }

    $pedidos_existentes[] = $pedido;
    $json_pedidos = json_encode($pedidos_existentes, JSON_PRETTY_PRINT);
    file_put_contents($archivo_pedido, $json_pedidos, LOCK_EX);

    $_SESSION['mensaje'] = "Pedido guardado con éxito. Folio: " . $folio;
    header('Location: ver_carrito.php');
    exit;
}

?>
