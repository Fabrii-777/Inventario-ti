<?php
require_once 'conexion.php';

// Variable para mostrar avisos en pantalla si hay error
$mensaje_error = "";

// Si el usuario envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = trim($_POST['nombre']);
    $tipo         = trim($_POST['tipo']);
    $marca        = trim($_POST['marca']);
    $numero_serie = trim($_POST['numero_serie']);
    $estado       = trim($_POST['estado']);

    try {
        $sql = "INSERT INTO equipos (nombre, tipo, marca, numero_serie, estado) VALUES (?, ?, ?, ?, ?)";
        $consulta = $pdo->prepare($sql);
        $consulta->execute([$nombre, $tipo, $marca, $numero_serie, $estado]);

        // Si se guardó con éxito, recarga la página
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        // Código 23505 = número de serie repetido en Supabase
        if ($e->getCode() == 23505) {
            $mensaje_error = "⚠️ El número de serie '" . htmlspecialchars($numero_serie) . "' ya está registrado. Ingresa uno diferente.";
        } else {
            $mensaje_error = "⚠️ Error al guardar: " . $e->getMessage();
        }
    }
}

// Consulta los equipos en tiempo real desde Supabase
$resultado = $pdo->query("SELECT * FROM equipos ORDER BY id DESC");
$equipos = $resultado->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario TI</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h1>Inventario Informático (Conectado a Supabase)</h1>

    <!-- Si hay un error, muestra esta alerta visual elegante -->
    <?php if (!empty($mensaje_error)): ?>
        <div style="background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
            <?php echo $mensaje_error; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <div class="card">
        <form method="POST" class="form-grid">
            <div>
                <label>Nombre o Modelo</label>
                <input type="text" name="nombre" placeholder="Ej: Monitor 24 LG" required>
            </div>
            <div>
                <label>Tipo</label>
                <select name="tipo">
                    <option value="Laptop">Laptop</option>
                    <option value="Desktop">Desktop</option>
                    <option value="Monitor">Monitor</option>
                    <option value="Redes">Equipo de Redes</option>
                    <option value="Impresora">Impresora</option>
                </select>
            </div>
            <div>
                <label>Marca</label>
                <input type="text" name="marca" placeholder="Ej: HP" required>
            </div>
            <div>
                <label>N° Serie</label>
                <input type="text" name="numero_serie" placeholder="Ej: SN-998822" required>
            </div>
            <div>
                <label>Estado</label>
                <select name="estado">
                    <option value="Operativo">Operativo</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                    <option value="Baja">Baja</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Guardar en Supabase</button>
        </form>
    </div>

    <!-- Tabla -->
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipo</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>N° Serie</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $item): ?>
                <tr>
                    <td>#<?php echo $item['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($item['nombre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($item['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($item['marca']); ?></td>
                    <td><code><?php echo htmlspecialchars($item['numero_serie']); ?></code></td>
                    <td><span class="badge-ok"><?php echo htmlspecialchars($item['estado']); ?></span></td>
                    <td>
                        <a href="eliminar.php?id=<?php echo $item['id']; ?>" class="btn-delete" onclick="return confirm('¿Dar de baja este equipo?');">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
