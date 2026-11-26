<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: signin.html');
    exit;
}
include('../settings/database.php');

$id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM users WHERE id = $1";
$res = pg_query_params($local_conn, $sql, [$id]);
$user = pg_fetch_assoc($res);

if (!$user) {
    echo "<script>alert('Usuario no encontrado');</script>";
    echo "<script>window.location.href='list_users.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f4f8;
            margin: 0;
            padding: 30px 20px;
        }
        .container {
            background: white;
            max-width: 450px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2d3748;
            margin-top: 0;
        }
        .current-photo {
            text-align: center;
            margin-bottom: 20px;
        }
        .current-photo img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e2e8f0;
        }
        label {
            display: block;
            margin-top: 12px;
            color: #4a5568;
        }
        input, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #2b6cb0;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
        }
        button:hover {
            background: #2c5282;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #718096;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ Editar Usuario</h2>

        <div class="current-photo">
            <p><strong>Foto actual:</strong></p>
            <?php if (!empty($user['photo_url']) && file_exists($user['photo_url'])): ?>
                <img src="<?php echo htmlspecialchars($user['photo_url']); ?>" alt="Foto de perfil">
            <?php else: ?>
                <img src="photos/default.png" alt="Sin foto">
            <?php endif; ?>
        </div>

        <form action="update_user.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <label>Nombres:</label>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            
            <label>Apellidos:</label>
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
            
            <label>Correo Electrónico:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <label>Teléfono Móvil:</label>
            <input type="text" name="mobile_phone" value="<?php echo htmlspecialchars($user['mobile_phone'] ?: ''); ?>">
            
            <label>📷 Cambiar Foto de Perfil:</label>
            <input type="file" name="photo" accept=".jpg,.jpeg,.png">
            
            <button type="submit">💾 Guardar Cambios</button>
        </form>
        <a href="list_users.php" class="back-link">← Cancelar y volver a la lista</a>
    </div>
</body>
</html>
<?php pg_close($local_conn); ?>