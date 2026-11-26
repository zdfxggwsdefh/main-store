<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: signin.html');
    exit;
}
include('../settings/database.php');

// Datos del formulario
$id = $_POST['id'] ?? 0;
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile_phone = trim($_POST['mobile_phone'] ?? '');

// -------- PARTE A: Actualizar los datos --------
$sql = "UPDATE users 
        SET firstname = $1, lastname = $2, email = $3, mobile_phone = $4 
        WHERE id = $5";
$params = [$firstname, $lastname, $email, $mobile_phone, $id];
$result = pg_query_params($local_conn, $sql, $params);

if (!$result) {
    echo "<script>alert('Error al actualizar los datos');</script>";
    echo "<script>history.back();</script>";
    exit;
}

// -------- PARTE B: Subir foto (solo si eligieron una) --------
if (!empty($_FILES['photo']['name'])) {
    // Obtener extensión en minúsculas
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    
    // Validar formato permitido
    if ($ext === 'jpg' || $ext === 'jpeg' || $ext === 'png') {
        
        // Nombre de la foto: número de celular + extensión
        $photo_url = "photos/" . $mobile_phone . "." . $ext;
        
        // Mover la foto desde la carpeta temporal a su destino
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_url)) {
            
            // Eliminar foto anterior si existe y NO es la por defecto
            $check_old = pg_query_params($local_conn, "SELECT photo_url FROM users WHERE id = $1", [$id]);
            $old = pg_fetch_assoc($check_old);
            if (!empty($old['photo_url']) && $old['photo_url'] !== 'photos/default.png' && file_exists($old['photo_url'])) {
                unlink($old['photo_url']);
            }
            
            // Guardar la nueva ruta en la base de datos
            $sql_foto = "UPDATE users SET photo_url = $1 WHERE id = $2";
            pg_query_params($local_conn, $sql_foto, [$photo_url, $id]);
            
        } else {
            echo "<script>alert('No se pudo guardar la foto. Revisa permisos en la carpeta photos.');</script>";
            echo "<script>history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Solo se permiten imágenes JPG, JPEG o PNG');</script>";
        echo "<script>history.back();</script>";
        exit;
    }
}

// -------- PARTE C: Volver a la lista --------
header('Location: list_users.php');
exit;
?>