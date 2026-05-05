<?php
session_start();

/* 🔒 VALIDAR SESIÓN ADMIN */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$admin = $_SESSION['admin'];

/* 🔹 CONEXIÓN */
$conn = new mysqli("localhost", "root", "", "entralix");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

/* 🔹 TRAER USUARIOS */
$result = $conn->query("SELECT * FROM usuarios");

$totalUsuarios = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.sena-green { background-color: #005da9; }
.sena-text-green { color: #005da9; }

.sidebar-item:hover {
    background-color: rgba(57,169,0,0.1);
    border-left: 4px solid #005da9;
}
</style>
</head>

<body class="bg-gray-100 font-sans min-h-screen">

<div class="flex min-h-screen">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-md hidden md:flex flex-col">

    <div class="p-6 text-center border-b">
        <i class="fas fa-user-shield text-6xl text-gray-400 mb-3"></i>
        <h2 class="font-bold text-gray-800"><?= $admin ?></h2>
        <p class="text-xs text-gray-500">Administrador</p>
    </div>

    <nav class="flex-1 mt-4">

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-700 sena-text-green font-semibold">
            <i class="fas fa-home mr-3"></i> Inicio
        </a>

        <a href="registrar_usuario.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-user-plus mr-3"></i> Registrar Usuario
        </a>

         <a href="eliminarUsuario.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-user-plus mr-3"></i> Eliminar 
        </a>

          <a href="editarUsuario.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-user-edit mr-3"></i> Editar
        </a>


      

    </nav>

    <div class="p-4 border-t">
        <a href="logout_admin.php" class="flex items-center text-red-500 hover:text-red-700">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
        </a>
    </div>

</aside>

<!-- CONTENIDO -->
<main class="flex-1 overflow-y-auto">

<header class="bg-white p-4 shadow">
    <h1 class="font-bold text-gray-700">Bienvenido <?= $admin ?></h1>
</header>

<div class="p-8">

<!-- TARJETAS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
    <p class="text-sm text-gray-500">Usuarios registrados</p>
    <h3 class="text-3xl font-bold"><?= $totalUsuarios ?></h3>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-400">
    <p class="text-sm text-gray-500">Administradores</p>
    <h3 class="text-3xl font-bold">
        <?php
        $admins = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE nombre='admin'");
        echo $admins->fetch_assoc()['total'];
        ?>
    </h3>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-orange-400">
    <p class="text-sm text-gray-500">Sistema</p>
    <h3 class="text-3xl font-bold">Activo</h3>
</div>

</div>

<!-- TABLA USUARIOS -->
<div class="bg-white rounded-xl shadow-sm p-6">

<h2 class="font-bold text-gray-700 mb-4">Lista de Usuarios</h2>

<table class="w-full text-sm text-left">
<thead class="bg-gray-100">
<tr>
    <th class="p-3">ID</th>
    <th class="p-3">Nombre</th>
    <th class="p-3">Correo</th>
    <th class="p-3">Documento</th>
</tr>
</thead>

<tbody>
<?php while($u = $result->fetch_assoc()): ?>
<tr class="border-b hover:bg-gray-50">
    <td class="p-3"><?= $u['id'] ?></td>
    <td class="p-3"><?= $u['nombre'] ?></td>
    <td class="p-3"><?= $u['correo'] ?></td>
    <td class="p-3"><?= $u['documento'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>

</table>

</div>

</div>

</main>

</div>

</body>
</html>