<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: signin.html');
    exit;
}
include('../settings/database.php');

$sql = "SELECT id, firstname, lastname, email, ident_number, mobile_phone, status, role, photo_url 
        FROM users ORDER BY id";
$res = pg_query($local_conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f4f8;
            margin: 0;
            padding: 30px 20px;
        }
        .container {
            background: white;
            max-width: 1200px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #2b6cb0;
            color: white;
            padding: 12px 8px;
            text-align: center;
        }
        td {
            padding: 12px 8px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            text-align: center;
        }
        tr:hover {
            background: #f7fafc;
        }
        .user-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
        }
        .btn-sm {
            padding: 5px 8px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
            margin: 2px 1px;
        }
        .edit { background: #ed8936; color: white; }
        .edit:hover { background: #dd6b20; }
        .toggle-on { background: #38a169; color: white; }
        .toggle-on:hover { background: #2f855a; }
        .toggle-off { background: #d69e2e; color: white; }
        .toggle-off:hover { background: #b7791f; }
        .delete { background: #e53e3e; color: white; }
        .delete:hover { background: #c53030; }
        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            background: #718096;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #4a5568;
        }
        .no-actions {
            color: #a0aec0;
            font-style: italic;
            font-size: 13px;
        }
        .status-active { color: #38a169; font-weight: bold; }
        .status-inactive { color: #e53e3e; font-weight: bold; }
        .role-admin { color: #2b6cb0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📋 Lista de Usuarios</h2>
        
        <table>
            <tr>
                <th>Foto</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Identificación</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = pg_fetch_assoc($res)): 
    // Convertimos una sola vez al principio
    $is_active = ($row['status'] === 't' || $row['status'] === true || $row['status'] === '1');
?>
<tr>
    <td>
        <?php if (!empty($row['photo_url']) && file_exists($row['photo_url'])): ?>
            <img src="<?php echo htmlspecialchars($row['photo_url']); ?>" alt="Foto" class="user-photo">
        <?php else: ?>
            <img src="photos/default.png" alt="Sin foto" class="user-photo">
        <?php endif; ?>
    </td>
    <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
    <td><?php echo htmlspecialchars($row['email']); ?></td>
    <td><?php echo htmlspecialchars($row['ident_number']); ?></td>
    <td><?php echo htmlspecialchars($row['mobile_phone'] ?: '-'); ?></td>
    
    <!-- ✅ COLUMNA ESTADO - CORREGIDA -->
    <td>
        <?php if ($is_active): ?>
            <span class="status-active">✅ Activo</span>
        <?php else: ?>
            <span class="status-inactive">❌ Inactivo</span>
        <?php endif; ?>
    </td>
    
    <td>
        <?php if ($row['role'] === 'admin'): ?>
            <span class="role-admin">👑 Admin</span>
        <?php else: ?>
            Usuario
        <?php endif; ?>
    </td>
    
    <!-- ✅ COLUMNA ACCIONES - CORREGIDA -->
    <td>
        <?php if ($row['role'] !== 'admin'): ?>
            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn-sm edit">✏️ Editar</a>
            
            <?php if ($is_active): ?>
                <a href="update_user_status.php?userID=<?php echo $row['id']; ?>" class="btn-sm toggle-on">🔴 Desactivar</a>
            <?php else: ?>
                <a href="update_user_status.php?userID=<?php echo $row['id']; ?>" class="btn-sm toggle-off">🟢 Activar</a>
            <?php endif; ?>
            
            <a href="delete_user.php?userID=<?php echo $row['id']; ?>" class="btn-sm delete" onclick="return confirm('¿Eliminar este usuario? Esta acción no se puede deshacer.')">🗑️ Eliminar</a>
        <?php else: ?>
            <span class="no-actions">Sin acciones</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
        </table>
          <a href="home.php" class="back-btn">← Volver al Inicio</a>
    </div>
</body>
</html>
<?php pg_close($local_conn); ?>