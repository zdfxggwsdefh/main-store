<?php
session_start();
include('../settings/database.php');

$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$ident_number = trim($_POST['ident_number'] ?? '');
$mobile_phone = trim($_POST['mobile_phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['pswd'] ?? '';
$role = 'user'; // Por defecto todos son usuarios normales

if (empty($firstname) || empty($lastname) || empty($ident_number) || empty($email) || empty($password)) {
    echo "<script>alert('Completa todos los campos obligatorios');</script>";
    echo "<script>history.back();</script>";
    exit;
}

$check_email = pg_query_params($local_conn, "SELECT id FROM users WHERE email = $1", [$email]);
if (pg_num_rows($check_email) > 0) {
    echo "<script>alert('Este correo ya está registrado');</script>";
    echo "<script>history.back();</script>";
    exit;
}

$check_id = pg_query_params($local_conn, "SELECT id FROM users WHERE ident_number = $1", [$ident_number]);
if (pg_num_rows($check_id) > 0) {
    echo "<script>alert('Este número de identificación ya está registrado');</script>";
    echo "<script>history.back();</script>";
    exit;
}

$hashed_pass = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (firstname, lastname, ident_number, mobile_phone, email, password, role) 
        VALUES ($1, $2, $3, $4, $5, $6, $7)";
$result = pg_query_params($local_conn, $sql, [$firstname, $lastname, $ident_number, $mobile_phone, $email, $hashed_pass, $role]);

if ($result) {
    echo "<script>alert('Usuario registrado con éxito. Ahora inicia sesión.');</script>";
    echo "<script>window.location.href='signin.html';</script>";
} else {
    echo "<script>alert('Error al registrar: " . addslashes(pg_last_error($local_conn)) . "');</script>";
    echo "<script>history.back();</script>";
}

pg_close($local_conn);

