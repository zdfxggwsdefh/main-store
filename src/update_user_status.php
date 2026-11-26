<?php
session_start();

// 1. Verificar que sea administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: signin.html');
    exit;
}

// 2. Conectar a la base de datos
include('../settings/database.php');

// 3. Obtener el ID del usuario
$user_id = $_GET['userID'] ?? 0;

// 4. Ejecutar el cambio: INVERTIR el estado
$sql = "UPDATE users SET status = NOT status WHERE id = $1";
$res = pg_query_params($local_conn, $sql, [$user_id]);

// 5. Mensaje y redirección
if ($res) {
    header('Location: list_users.php?msg=ok');
} else {
    header('Location: list_users.php?msg=error');
}
exit;
?>