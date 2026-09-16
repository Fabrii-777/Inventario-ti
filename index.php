<?php
require_once 'conexion.php';

$mensaje_error = "";

// Procesa el registro de un nuevo equipo
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

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23505) {
            $mensaje_error = "El número de serie '" . htmlspecialchars($numero_serie) . "' ya está en uso. Ingresa uno diferente.";
        } else {
            $mensaje_error = "Error al guardar el equipo: " . $e->getMessage();
        }
    }
}

// Obtiene todos los equipos de Supabase
$resultado = $pdo->query("SELECT * FROM equipos ORDER BY id DESC");
$equipos = $resultado->fetchAll();

// Calcula métricas para las tarjetas superiores
$total_equipos = count($equipos);
$total_operativos = 0;
$total_mantenimiento = 0;

foreach ($equipos as $item) {
    if ($item['estado'] === 'Operativo') {
        $total_operativos++;
    } elseif ($item['estado'] === 'Mantenimiento') {
        $total_mantenimiento++;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario TI • Cloud Dashboard</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    
    <!-- Encabezado con logo y estado del servidor -->
    <header class="header-top">
        <div class="brand">
            <div class="brand-icon">💻</div>
            <div>
                <h1>Inventario Informático</h1>
                <p>Gestión de infraestructura TI</p>
            </div>
        </div>
        <div class="live-indicator">
            <div class="live-dot"></div>
            Supabase Cloud en Vivo
        </div>
    </header>

    <!-- Tarjetas de métricas superiores en tiempo real -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <span>Total Equipos</span>
                <h3><?php echo $total_equipos; ?></h3>
            </div>
            <div class="stat-badge badge-blue">📦</div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span>Operativos</span>
                <h3><?php echo $total_operativos; ?></h3>
            </div>
            <div class="stat-badge badge-green">⚡</div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span>En Mantenimiento</span>
                <h3><?php echo $total_mantenimiento; ?></h3>
            </div>
            <div class="stat-badge badge-orange">🛠️</div>
        </div>
    </section>

    <!-- Alerta en caso de error por duplicado -->
    <?php if (!empty($mensaje_error)): ?>
        <div style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
            <span>⚠️</span> <?php echo $mensaje_error; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para agregar hardware -->
    <section class="card">
        <h2 class="card-title">➕ Registrar Nuevo Dispositivo</h2>
        <form method="POST" class="form-grid">
            <div class="input-field">
                <label>Nombre o Modelo</label>
                <input type="text" name="nombre" placeholder="Ej: MacBook Pro 14" required>
            </div>

            <div class="input-field">
                <label>Categoría</label>
                <select name="tipo">
                    <option value="Laptop">Laptop</option>
                    <option value="Desktop">Desktop / PC</option>
                    <option value="Monitor">Monitor</option>
                    <option value="Redes">Switch / Router</option>
                    <option value="Impresora">Impresora</option>
                </select>
            </div>

            <div class="input-field">
                <label>Marca</label>
                <input type="text" name="marca" placeholder="Ej: Apple / Lenovo" required>
            </div>

            <div class="input-field">
                <label>N° Serie</label>
                <input type="text" name="numero_serie" placeholder="Ej: SN-48921" required>
            </div>

            <div class="input-field">
                <label>Estado</label>
                <select name="estado">
                    <option value="Operativo">Operativo</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                    <option value="Baja">Baja</option>
                </select>
            </div>

            <button type="submit" class="btn-primary">Guardar en Supabase</button>
        </form>
    </section>

    <!-- Tabla con el listado completo -->
    <section class="card">
        <h2 class="card-title">📋 Hardware Registrado</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dispositivo</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>N° Serie</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($equipos) > 0): ?>
                        <?php foreach ($equipos as $item): ?>
                        <tr>
                            <td><strong>#<?php echo $item['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($item['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($item['marca']); ?></td>
                            <td><span class="serial-tag"><?php echo htmlspecialchars($item['numero_serie']); ?></span></td>
                            <td>
                                <?php 
                                    $clase_estado = 'status-operativo';
                                    if ($item['estado'] === 'Mantenimiento') $clase_estado = 'status-mantenimiento';
                                    if ($item['estado'] === 'Baja') $clase_estado = 'status-baja';
                                ?>
                                <span class="status-pill <?php echo $clase_estado; ?>">
                                    ● <?php echo htmlspecialchars($item['estado']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="eliminar.php?id=<?php echo $item['id']; ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('¿Seguro que deseas eliminar este equipo?');">
                                   Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #94a3b8; padding: 25px;">No hay equipos registrados actualmente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

</body>
</html>
