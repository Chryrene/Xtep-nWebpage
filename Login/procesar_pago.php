<?php
session_start();

// Incluir el archivo que contiene la función actualizarEstatusPedido
require_once 'pedidojson.php'; // Cambia esto por la ruta real a tu archivo

// Suponiendo que tienes el folio del pedido disponible, por ejemplo, a través de POST
$folioPedido = $_POST['folio'] ?? null;

if ($folioPedido) {
    // Llamar a la función para actualizar el estatus del pedido a "pagado"
    actualizarEstatusPedido($folioPedido, 'pagado');

}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombreTarjeta = $_POST['nombreTarjeta'] ?? '';
    $numeroTarjeta = $_POST['numeroTarjeta'] ?? '';
    $fechaExpiracion = $_POST['fechaExpiracion'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    // Crear un array con los datos del pago
    $datosPago = [
        'nombreTarjeta' => $nombreTarjeta,
        'numeroTarjeta' => $numeroTarjeta,
        'direccion' => $direccion,
        'telefono' => $numero,
        'fechaExpiracion' => $fechaExpiracion,
        'cvv' => $cvv
        
    ];

    // Convertir los datos a JSON
    $jsonDatosPago = json_encode($datosPago, JSON_PRETTY_PRINT);

    // Guardar los datos en un archivo JSON
    file_put_contents('datos_pago.json', $jsonDatosPago);

    // Redirigir a reservahist.php usando JavaScript
    echo "<script>window.location.href = 'reservahist.php';</script>";
} else {
    // Redirigir al formulario de pago si se accede directamente a este archivo
    header('Location: pagina_pago.php');
    exit;
}
?>