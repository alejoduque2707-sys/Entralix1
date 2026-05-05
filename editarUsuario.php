<?php
session_start();

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

/* 🔹 ACTUALIZAR USUARIO */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id        = $_POST['id'];
    $nombre    = $_POST['nombre'];
    $correo    = $_POST['correo'];
    $documento = $_POST['documento'];
    $rol       = $_POST['rol'];
    $estado    = $_POST['estado'];

    $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, correo=?, documento=?, rol=?, estado=? WHERE id=?");
    $stmt->bind_param("sssssi", $nombre, $correo, $documento, $rol, $estado, $id);

    $mensaje = $stmt->execute() ? "ok" : "error";
}

/* 🔹 TRAER USUARIOS */
$result = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
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

        <a href="eliminarUsuario.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
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

<header class="bg-white p-4 shadow">
    <h1 class="font-bold text-gray-700">Usuarios</h1>
</header>

<div class="p-8">

<?php if ($mensaje): ?>
<div class="mb-4 p-3 rounded text-sm
<?= ($mensaje=='ok') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
<?= ($mensaje=='ok') ? "Usuario actualizado correctamente" : "Error al actualizar" ?>
</div>
<?php endif; ?>

<!-- CARD -->
<div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 overflow-x-auto">

<h2 class="font-bold text-gray-700 mb-4">Lista de Usuarios</h2>

<table class="w-full text-sm text-left border-collapse">

<thead class="bg-gray-100 text-gray-700">
<tr>
    <th class="p-3">Nombre</th>
    <th class="p-3">Documento</th>
    <th class="p-3">Correo</th>
    <th class="p-3">Rol</th>
    <th class="p-3">Estado</th>
    <th class="p-3 text-center">Acción</th>
</tr>
</thead>

<tbody>

<?php while($u = $result->fetch_assoc()): ?>
<tr class="border-t hover:bg-gray-50 transition">

    <td class="p-3"><?= $u['nombre'] ?></td>
    <td class="p-3"><?= $u['documento'] ?></td>
    <td class="p-3"><?= $u['correo'] ?></td>
    <td class="p-3 capitalize"><?= $u['rol'] ?></td>

    <td class="p-3">
        <span class="px-2 py-1 rounded-full text-xs font-semibold
        <?= ($u['estado']=='activo') 
            ? 'bg-green-100 text-green-700' 
            : 'bg-red-100 text-red-600' ?>">
            <?= $u['estado'] ?>
        </span>
    </td>

    <td class="p-3 text-center">
        <button onclick='abrirModal(<?= json_encode($u) ?>)'
        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
        Editar
        </button>
    </td>

</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>

</div>
</main>
</div>

<!-- MODAL -->
<div id="modal"
class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">

<div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">

<h2 class="text-lg font-bold mb-4 text-gray-700">Editar Usuario</h2>

<form method="POST" class="space-y-3">

<input type="hidden" name="id" id="id">

<input type="text" name="nombre" id="nombre" class="border p-2 w-full rounded">
<input type="email" name="correo" id="correo" class="border p-2 w-full rounded">
<input type="text" name="documento" id="documento" class="border p-2 w-full rounded">

<select name="rol" id="rol" class="border p-2 w-full rounded">
<option value="aprendiz">Aprendiz</option>
<option value="instructor">Instructor</option>
<option value="admin">Admin</option>
</select>

<select name="estado" id="estado" class="border p-2 w-full rounded">
<option value="activo">Activo</option>
<option value="inactivo">Inactivo</option>
</select>

<div class="flex justify-end gap-2 pt-2">
<button type="button" onclick="cerrarModal()" class="bg-gray-300 px-4 py-2 rounded">
Cancelar
</button>

<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
Guardar
</button>
</div>

</form>

</div>
</div>

<script>
function abrirModal(user){
    let modal = document.getElementById('modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('id').value = user.id;
    document.getElementById('nombre').value = user.nombre;
    document.getElementById('correo').value = user.correo;
    document.getElementById('documento').value = user.documento;
    document.getElementById('rol').value = user.rol;
    document.getElementById('estado').value = user.estado;
}

function cerrarModal(){
    let modal = document.getElementById('modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');



}


setTimeout(() => {
    const alerta = document.querySelector('.bg-green-100');
    if (alerta) {
        alerta.style.transition = "opacity 0.5s";
        alerta.style.opacity = "0";

        setTimeout(() => {
            alerta.remove();
        }, 500);
    }
}, 5000);

</script>

</body>
</html>