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

// Consulta para obtener la información actual de las promociones
$consulta = "SELECT * FROM promos";
$resultado = mysqli_query($conex, $consulta);

// Verificar si el formulario de actualización fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnActualizar'])) {
    $id_prom = $_POST['id_prom'];
    $nuevo_nombrep = $_POST['nuevo_nombrep'];
    $nueva_descripp = $_POST['nueva_descripp'];
    $nuevo_preciop = $_POST['nuevo_preciop'];

    // Realizar la actualización en la base de datos
    $actualizar_consulta = "UPDATE promos SET nombrep = '$nuevo_nombrep', descripp = '$nueva_descripp', preciop = '$nuevo_preciop' WHERE id_prom = $id_prom";
    if (mysqli_query($conex, $actualizar_consulta)) {
        $_SESSION['mensaje'] = 'Promoción actualizada con éxito';
        // Redirigir a la página actual para actualizar la vista
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        $_SESSION['mensaje'] = 'Error al actualizar la promoción: ' . mysqli_error($conex);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Administración</title>
    <link rel="stylesheet" href="CSS/stylec.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Mensaje de confirmación -->
<?php if (!empty($_SESSION['mensaje'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['mensaje']; ?>
    </div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard de Administración</a>
        <a class="navbar-brand" href="Admin.php">Volver atrás</a>
    </div>
</nav>

<div class="container mt-4">
    <h2>Promociones</h2>
    <table class="table">
        <!-- Encabezados de la tabla -->
        <tbody>
        <?php while ($promo = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo htmlspecialchars($promo['nombrep']); ?></td>
                <td><?php echo htmlspecialchars($promo['descripp']); ?></td>
                <td><?php echo htmlspecialchars($promo['preciop']); ?></td>
                <td>
                    <!-- Botón para abrir el formulario de actualización -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#actualizarModal<?php echo $promo['id_prom']; ?>">Actualizar</button>
                </td>
            </tr>

            <!-- Modal de actualización -->
            <div class="modal fade" id="actualizarModal<?php echo $promo['id_prom']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar Promoción</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <input type="hidden" name="id_prom" value="<?php echo htmlspecialchars($promo['id_prom']); ?>">
                                <div class="mb-3">
                                    <label for="nuevo_nombrep" class="form-label">Nuevo Nombre</label>
                                    <input type="text" class="form-control" id="nuevo_nombrep" name="nuevo_nombrep" value="<?php echo htmlspecialchars($promo['nombrep']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="nueva_descripp" class="form-label">Nueva Descripción</label>
                                    <textarea class="form-control" id="nueva_descripp" name="nueva_descripp"><?php echo htmlspecialchars($promo['descripp']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="nuevo_preciop" class="form-label">Nuevo Precio</label>
                                    <input type="text" class="form-control" id="nuevo_preciop" name="nuevo_preciop" value="<?php echo htmlspecialchars($promo['preciop']); ?>" pattern="\d+(\.\d{2})?" title="Ingrese un precio válido (ejemplo: 10.99)" required>
                                </div>

                                <button type="submit" class="btn btn-primary" name="btnActualizar">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    function recargarPagina() {
        setTimeout(function() {
            location.reload();
        }, 1000); // Recargar la página después de 1 segundo (1000 milisegundos)
    }
</script>
</body>
</html>
