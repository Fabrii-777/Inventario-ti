<?php
// Se conecta a Supabase
require_once 'conexion.php';

// Comprueba si llegó un ID por la barra de dirección
if (isset($_GET['id'])) {
    // Asegura que el dato sea un número entero
    $id = intval($_GET['id']);
    
    // Prepara la instrucción para borrar el registro
    $stmt = $pdo->prepare("DELETE FROM equipos WHERE id = ?");
    $stmt->execute([$id]);
}

// Vuelve automáticamente a la pantalla principal
header("Location: index.php");
exit();
?>