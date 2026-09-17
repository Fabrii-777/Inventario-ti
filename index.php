<?php
// Llama al archivo de conexión creado en el paso anterior
require_once 'conexion.php';

// Si el usuario presionó el botón "Registrar", entra a este bloque
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guarda los datos enviados desde los inputs del formulario
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $marca = $_POST['marca'];
    $numero_serie = $_POST['numero_serie'];
    $estado = $_POST['estado'];

    // Prepara la orden SQL de inserción para evitar ataques cibernéticos
    $sql = "INSERT INTO equipos (nombre, tipo, marca, numero_serie, estado) VALUES (?, ?, ?, ?, ?)";
    $consulta = $pdo->prepare($sql);
    
    // Envía los datos seguros directamente a Supabase
    $consulta->execute([$nombre, $tipo, $marca, $numero_serie, $estado]);
    
    // Recarga la página para refrescar la tabla y limpiar el formulario
    header("Location: index.php");
    exit();
}

// Consulta todos los equipos registrados en Supabase en tiempo real
$resultado = $pdo->query("SELECT * FROM equipos ORDER BY id DESC");
$equipos = $resultado->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario TI</title>
    <!-- Vincula el archivo de estilos visuales -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h1>Inventario Informático (Conectado a Supabase)</h1>

    <!-- Formulario para cargar hardware -->
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
                </select>
            </div>
            <button type="submit" class="btn-primary">Guardar en Supabase</button>
        </form>
    </div>

    <!-- Tabla que lista los equipos -->
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
                <!-- Recorre los registros de Supabase uno por uno -->
                <?php foreach ($equipos as $item): ?>
                <tr>
                    <td>#<?php echo $item['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($item['nombre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($item['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($item['marca']); ?></td>
                    <td><code><?php echo htmlspecialchars($item['numero_serie']); ?></code></td>
                    <td><span class="badge-ok"><?php echo htmlspecialchars($item['estado']); ?></span></td>
                    <td>
                        <!-- Botón de eliminar que envía el ID a eliminar.php -->
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