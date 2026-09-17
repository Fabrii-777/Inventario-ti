<?php
// 1. Cambia el host por el del pooler (región São Paulo: sa-east-1)
$host = "aws-0-sa-east-1.pooler.supabase.com";

// 2. Puerto del pooler (si te dio 6543 en Supabase, pon 6543; si dio 5432, déjalo en 5432)
$port = "5432"; 

// 3. La base de datos sigue siendo postgres
$dbname = "postgres";

// 4. ATENCIÓN: El usuario del pooler lleva tu Project ID agregado
$user = "postgres.llafbklyqnvtojblzjyu";

// 5. La contraseña que creaste al fundar el proyecto
$password = "INVENTARIO-TI";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Error al conectar con Supabase: " . $e->getMessage());
}
?>