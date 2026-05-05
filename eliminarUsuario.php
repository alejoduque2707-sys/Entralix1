<?php
session_start();

/* 🔒 VALIDAR SESIÓN ADMIN */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$admin = $_SESSION['admin'];

$conn = new mysqli("localhost", "root", "", "entralix");

if ($conn->connect_error) {
    die("Error de conexión");
}

$mensaje = "";

/* 🔴 INACTIVAR USUARIO */
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE usuarios SET estado='inactivo' WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $mensaje = "ok";
    } else {
        $mensaje = "error";
    }
}

/* 🔹 TRAER ACTIVOS */
$result = $conn->query("SELECT * FROM usuarios WHERE estado='activo'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Administrar Usuarios</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.sena-green { background-color: #005da9; }
.sena-text { color: #005da9; }

.sidebar-item:hover {
    background-color: rgba(0,93,169,0.1);
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

        <a href="admin.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-home mr-3"></i> Inicio
        </a>

        <a href="registrar_usuario.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-user-plus mr-3"></i> Registrar
        </a>

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-700 font-semibold sena-text">
            <i class="fas fa-user-times mr-3"></i> Eliminar
        </a>

        <a href="editarUsuario.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-user-edit mr-3"></i> Editar
        </a>

    </nav>

    <div class="p-4 border-t">
        <a href="logout_admin.php" class="flex items-center text-red-500 hover:text-red-700">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
        </a>
    </div>

</aside>

<!-- CONTENIDO -->
<main class="flex-1 overflow-y-auto">

<!-- HEADER (igual al dashboard) -->
<header class="bg-white p-4 shadow">
    <h1 class="font-bold text-gray-700">Eliminar Usuarios</h1>
</header>

<div class="p-8">

<!-- ALERTA -->
<?php if ($mensaje): ?>
<div id="alerta" class="mb-4 p-3 rounded text-sm
    <?= ($mensaje=='ok') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
    
    <?= ($mensaje=='ok') 
        ? "Usuario inhabilitado correctamente." 
        : "Error al eliminar." ?>
</div>
<?php endif; ?>

<!-- CARD TABLA -->
<div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">

<h2 class="font-bold text-gray-700 mb-4">Usuarios Activos</h2>

<table class="w-full text-sm">

<thead class="bg-gray-100">
<tr>
    <th class="p-3 text-left">Nombre</th>
    <th class="p-3 text-left">Documento</th>
    <th class="p-3 text-left">Correo</th>
    <th class="p-3 text-left">Rol</th>
    <th class="p-3 text-center">Acción</th>
</tr>
</thead>

<tbody>

<?php while($user = $result->fetch_assoc()): ?>
<tr class="border-t hover:bg-gray-50">

<td class="p-3"><?= $user['nombre'] ?></td>
<td class="p-3"><?= $user['documento'] ?></td>
<td class="p-3"><?= $user['correo'] ?></td>
<td class="p-3"><?= $user['rol'] ?></td>

<td class="p-3 text-center">
<a href="?id=<?= $user['id'] ?>"
   onclick="return confirm('¿Eliminar usuario?')"
   class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs">
   Eliminar
</a>
</td>

</tr>
<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</main>

</div>

<script>
setTimeout(()=>{
 let a=document.getElementById("alerta");
 if(a){a.style.opacity="0"; setTimeout(()=>a.remove(),1000);}
},4000);
</script>

</body>
</html>