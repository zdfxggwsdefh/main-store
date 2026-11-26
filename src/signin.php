<?php
session_start();
include('../settings/database.php');

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_home.php');
    } else {
        header('Location: home.php');
    }
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['pswd'] ?? '';

if (empty($email) || empty($password)) {
    echo "<script>alert('Completa todos los campos');</script>";
    echo "<script>history.back();</script>";
    exit;
}

$sql = "SELECT id, firstname, lastname, password, status, role FROM users WHERE email = $1";
$result = pg_query_params($local_conn, $sql, [$email]);

if (pg_num_rows($result) === 1) {
    $user = pg_fetch_assoc($result);

    if (!$user['status']) {
        echo "<script>alert('Tu cuenta está desactivada');</script>";
        echo "<script>history.back();</script>";
        exit;
    }

   // ... después de verificar contraseña ...
if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_firstname'] = $user['firstname'];
    $_SESSION['user_lastname'] = $user['lastname'];
    $_SESSION['role'] = $user['role'];

    header('Location: home.php'); // ← Todos van aquí
    exit;
    }
    }