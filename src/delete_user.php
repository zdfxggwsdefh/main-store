<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: signin.html');
    exit;
}
include('../settings/database.php');

$user_id = $_GET['userID'] ?? 0;

$sql = "DELETE FROM users WHERE id = $1";
$res = pg_query_params($local_conn, $sql, [$user_id]);

if ($res) {
    echo "<script>alert('Usuario eliminado correctamente');</script>";
} else {
    echo "<script>alert('Error al eliminar: " . addslashes(pg_last_error($local_conn)) . "');</script>";
}
echo "<script>window.location.href='list_users.php';</script>";
pg_close($local_conn);
