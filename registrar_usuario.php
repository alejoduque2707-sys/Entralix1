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

$mensaje = "";

/* 🔹 GUARDAR USUARIO */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre    = trim($_POST['nombre']);
    $documento = trim($_POST['documento']);
    $tipo      = $_POST['tipo'] ?? '';
    $correo    = trim($_POST['correo']);
    $password  = trim($_POST['password']);
    $rol       = $_POST['rol'];
    $fichas    = $_POST['fichas'] ?? null;

    if (empty($nombre) || empty($documento) || empty($correo) || empty($password) || empty($tipo)) {
        $mensaje = "campos";
    } else {

        // 🔴 VALIDAR DOCUMENTO
        $checkDoc = $conn->prepare("SELECT id FROM usuarios WHERE documento = ?");
        $checkDoc->bind_param("s", $documento);
        $checkDoc->execute();
        $resDoc = $checkDoc->get_result();

        if ($resDoc->num_rows > 0) {
            $mensaje = "doc_existente";

        } else {

            // 🔴 VALIDAR CORREO
            $checkCorreo = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
            $checkCorreo->bind_param("s", $correo);
            $checkCorreo->execute();
            $resCorreo = $checkCorreo->get_result();

            if ($resCorreo->num_rows > 0) {
                $mensaje = "correo_existente";

            } else {

                // ✅ INSERTAR
                $stmt = $conn->prepare("
                    INSERT INTO usuarios 
                    (nombre, documento, tipo, correo, contraseña, rol, estado)
                    VALUES (?, ?, ?, ?, ?, ?, 'activo')
                ");

                $stmt->bind_param("ssssss", $nombre, $documento, $tipo, $correo, $password, $rol);

                if ($stmt->execute()) {

                    $usuario_id = $conn->insert_id;

                    // 🔥 INSERT EN APRENDIZ
                    if ($rol == "aprendices" && !empty($fichas)) {
                        $stmt2 = $conn->prepare("
                            INSERT INTO aprendices (usuarios_id, fichas_id)
                            VALUES (?, ?)
                        ");
                        $stmt2->bind_param("ii", $usuario_id, $fichas);
                        $stmt2->execute();
                    }

                    $mensaje = "ok";

                } else {
                    $mensaje = "error";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Usuario</title>

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

        <a href="admin.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-home mr-3"></i> Inicio
        </a>

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-700 sena-text-green font-semibold">
            <i class="fas fa-user-plus mr-3"></i> Registrar Usuario
        </a>

        <a href="eliminarUsuario.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-user-minus mr-3"></i> Eliminar 
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
    <h1 class="font-bold text-gray-700">Registrar Usuario</h1>
</header>

<!-- 🔥 CONTENEDOR CENTRADO -->
<div class="p-8 flex justify-center">

<!-- 🔥 CARD MÁS GRANDE -->
<div class="bg-white rounded-xl shadow-sm p-8 border-l-4 border-blue-500 max-w-5xl w-full">

<h2 class="font-bold text-gray-700 mb-6 text-lg">Nuevo Usuario</h2>

<!-- MENSAJE -->
<?php if ($mensaje): ?>
<div class="mb-4 p-3 rounded text-sm
    <?php echo ($mensaje=='ok') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
    
    <?php 
    if ($mensaje=='ok') echo "Usuario registrado correctamente.";
    if ($mensaje=='campos') echo "Completa todos los campos.";
    if ($mensaje=='error') echo "Error al registrar.";
    ?>
</div>
<?php endif; ?>

<form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

<input type="text" name="nombre" placeholder="Nombre"
    class="border p-3 rounded-lg w-full">

<input type="text" name="documento" placeholder="Documento"
    class="border p-3 rounded-lg w-full">
<select name="tipo" class="border p-3 rounded-lg w-full">
    <option value="">Tipo Documento</option>
    <option value="CC">CC</option>
    <option value="TI">TI</option>
    <option value="CE">CE</option>
    <option value="Pasaporte">Pasaporte</option>
</select>
<input type="email" name="correo" placeholder="Correo"
    class="border p-3 rounded-lg md:col-span-2 w-full">

<input type="password" name="password" placeholder="Contraseña"
    class="border p-3 rounded-lg md:col-span-2 w-full">

<div class="md:col-span-2">
    <label class="text-sm text-gray-600">Tipo de usuario</label>
    <div class="flex gap-6 mt-2">
        <label><input type="radio" name="rol" value="aprendiz" checked> Aprendiz</label>
        <label><input type="radio" name="rol" value="instructor"> Instructor</label>
    </div>
</div>

<div id="campoFicha" class="md:col-span-2">
    <input type="text" name="fichas" placeholder="ficha"
        class="border p-3 rounded-lg w-full">
</div>

<div class="md:col-span-2 flex justify-end gap-3 mt-6">

    <a href="admin.php"
        class="bg-gray-200 px-5 py-2 rounded-lg hover:bg-gray-300">
        Cancelar
    </a>

    <button type="submit"
        class="sena-green text-white px-6 py-2 rounded-lg hover:opacity-90">
        Guardar
    </button>

</div>

</form>

</div>

</div>

</main>

</div>

</body>
</html>