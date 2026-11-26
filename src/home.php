<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit;
}
$es_admin = ($_SESSION['role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f4f8;
            margin: 0;
            padding: 50px 20px;
        }
        .container {
            background: white;
            max-width: 450px;
            margin: 0 auto;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2d3748;
            margin-top: 0;
        }
        .info {
            background: #f7fafc;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .info p {
            margin: 8px 0;
            color: #4a5568;
        }
        .btn {
            display: block;
            text-align: center;
            padding: 12px;
            background: #2b6cb0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn:hover {
            background: #2c5282;
        }
        .btn-logout {
            background: #e53e3e;
            margin-top: 20px;
        }
        .btn-logout:hover {
            background: #c53030;
        }
        .admin-section {
            border-top: 1px solid #e2e8f0;
            margin-top: 20px;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>👋 Bienvenido</h2>
        
        <div class="info">
            <p><strong>Nombre:</strong> <?php echo $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname']; ?></p>
            <p><strong>Rol:</strong> 
                <?php echo $es_admin ? '👑 Administrador' : '🧑 Usuario'; ?>
            </p>
            <p><strong>ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
        </div>

        <?php if ($es_admin): ?>
            <div class="admin-section">
                <h3>Herramientas de Administración</h3>
                <a href="list_users.php" class="btn">📋 Gestionar Usuarios</a>
            </div>
        <?php else: ?>
            <p style="text-align:center; color:#718096;">Esta es tu página personal.</p>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-logout">🚪 Cerrar Sesión</a>
    </div>
</body>
</html>