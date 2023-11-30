<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Pago</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .container {
            padding-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Página de Pago</h2>
    <form action="procesar_pago.php" method="post">
        <div class="mb-3">
            <label for="nombreTarjeta" class="form-label">Nombre en la Tarjeta</label>
            <input type="text" class="form-control" id="nombreTarjeta" name="nombreTarjeta" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="direccion" class="form-control" id="direccion" name="direccion" required>
        </div>
        <div class="mb-3">
            <label for="numeroTarjeta" class="form-label">Número de Teléfono</label>
            <input type="phone-number" class="form-control" id="numerotelefono" name="numerotelefono" required>
        </div>
        <div class="mb-3">
            <label for="numeroTarjeta" class="form-label">Número de Tarjeta</label>
            <input type="text" class="form-control" id="numeroTarjeta" name="numeroTarjeta" required>
        </div>
        <div class="mb-3">
            <label for="fechaExpiracion" class="form-label">Fecha de Expiración</label>
            <input type="month" class="form-control" id="fechaExpiracion" name="fechaExpiracion" required>
        </div>
        <div class="mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="text" class="form-control" id="cvv" name="cvv" required>
        </div>
        <button type="submit" class="btn btn-primary">Realizar Pago</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>