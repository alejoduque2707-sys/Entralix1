<?php
session_start();

/* =========================
   CONEXIÓN
========================= */
$conn = new mysqli("localhost", "root", "", "entralix");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

/* =========================
   SESIÓN ACTIVA
========================= */
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol'] == 'aprendiz') {
        header('Location: aprendiz.php');
    } else {
        header('Location: instructor.php');
    }
    exit;
}

/* =========================
   LOGIN
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre    = trim($_POST['usuario'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $tipo_doc  = trim($_POST['tipo_documento'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $rol       = $_POST['rol'] ?? '';

    /* 🔴 VALIDAR CAMPOS */
    if (empty($nombre) || empty($correo) || empty($documento) || empty($password) || empty($rol) || empty($tipo_doc)) {
        header("Location: login.php?error=campos");
        exit;
    }

    /* 🔥 CONSULTA CON TIPO DE DOCUMENTO */
    $stmt = $conn->prepare("
        SELECT * FROM usuarios 
        WHERE correo = ? 
        AND documento = ? 
        AND tipo = ?
    ");

    $stmt->bind_param("sss", $correo, $documento, $tipo_doc);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $user = $resultado->fetch_assoc();

        /* 🔴 VALIDAR ESTADO */
        if ($user['estado'] !== 'activo') {
            header("Location: login.php?error=inactivo");
            exit;
        }

        /* 🔐 VALIDAR DATOS */
        if (
            $user['nombre'] === $nombre &&
            $user['rol'] === $rol &&
            $password === $user['contraseña']
        ) {

            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['correo'] = $user['correo'];
            $_SESSION['rol'] = $user['rol'];

            if ($user['rol'] == 'aprendiz') {
                header("Location: aprendiz.php");
            } else {
                header("Location: instructor.php");
            }
            exit;

        } else {
            header("Location: login.php?error=credenciales");
            exit;
        }

    } else {
        header("Location: login.php?error=credenciales");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Entralix - Iniciar Sesión</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.bg-purple-sena { background-color: #7c3aed; }
.hero-pattern {
    background-color: #3b75f3;
    background-image: url("https://www.transparenttextures.com/patterns/cubes.png");
    min-height: 100vh;
}
</style>
</head>

<body class="hero-pattern flex items-center justify-center p-4">

<div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">

    <!-- HEADER -->
    <div class="p-8 text-center border-b border-gray-100">
        <div class="w-16 h-16 bg-purple-sena rounded-2xl flex items-center justify-center mx-auto mb-4">
            <img src="Logo.png" class="h-14 w-auto">
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Bienvenido a Entralix</h2>
        <p class="text-gray-500 text-sm mt-2">Ingresa tus credenciales</p>
    </div>

    <!-- ALERTAS -->
    <?php if (isset($_GET['error'])): ?>
    <div id="alerta" class="mx-8 mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
        <i class="fas fa-exclamation-triangle mr-2"></i>

        <?php 
        if ($_GET['error'] == 'credenciales') echo "Datos incorrectos.";
        if ($_GET['error'] == 'campos') echo "Completa todos los campos.";
        if ($_GET['error'] == 'inactivo') echo "Usuario inactivo. Contacta al administrador.";
        ?>
    </div>
    <?php endif; ?>

    <!-- FORMULARIO -->
    <form action="login.php" method="POST" class="p-8 space-y-6">

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Nombre</label>
            <input type="text" name="usuario" required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl"
                placeholder="Ej: carlo8">
        </div>
           <!-- DOCUMENTO CON TIPO -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2"> Tipo de Documento</label>

            <div class="flex gap-2">

                <select name="tipo_documento" required
                    class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    <option value="">Tipo</option>
                    <option value="CC">CC</option>
                    <option value="TI">TI</option>
                    <option value="CE">CE</option>
                    <option value="Pasaporte">Pasaporte</option>
                </select>
        </div>


        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Documento</label>
            <input type="text" name="documento" required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl"
                placeholder="Ej: 12345678">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Correo</label>
            <input type="email" name="correo" required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl"
                placeholder="correo@misena.edu.co">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
            <input type="password" name="password" required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl"
                placeholder="••••••••">
        </div>
<br>
        <div>
    <label class="block text-sm font-bold text-gray-700 mb-3">Tipo de Usuario</label>

    <div class="grid grid-cols-2 gap-4">

        <!-- APRENDIZ -->
        <label class="cursor-pointer">
            <input type="radio" name="rol" value="aprendiz" class="peer hidden" checked>

            <div class="p-2 text-center border rounded-xl border-gray-200 
                        peer-checked:border-purple-600 
                        peer-checked:bg-purple-50 
                        peer-checked:text-purple-600 
                        transition-all">

                <i class="fas fa-user-graduate text-xl mb-2"></i>
                <p class="text-sm font-bold uppercase">Aprendiz</p>

            </div>
        </label>

        <!-- INSTRUCTOR -->
        <label class="cursor-pointer">
            <input type="radio" name="rol" value="instructor" class="peer hidden">

            <div class="p-2 text-center border rounded-xl border-gray-200 
                        peer-checked:border-purple-600 
                        peer-checked:bg-purple-50 
                        peer-checked:text-purple-600 
                        transition-all">

                <i class="fas fa-chalkboard-teacher text-xl mb-2"></i>
                <p class="text-sm font-bold uppercase">Instructor</p>

            </div>
        </label>

    </div>
</div>
<br>
        <button type="submit"
            class="w-full bg-purple-sena text-white py-3 rounded-xl font-bold">
            Entrar al Portal
        </button>

        <div class="text-center">
            <a href="recuperarContraseña.php" class="text-sm font-bold text-purple-sena hover:underline">
                ¿Olvidaste tu contraseña?
            </a>
        </div>

        <div class="text-center">
            <a href="index.php" class="text-sm font-bold text-purple-sena hover:underline">
                Volver al inicio
            </a>
        </div>

    </form>
</div>

<!-- 🔥 AUTO OCULTAR ALERTA -->
<script>
setTimeout(() => {
    let alerta = document.getElementById("alerta");
    if(alerta){
        alerta.style.transition = "opacity 1s";
        alerta.style.opacity = "0";
        setTimeout(()=> alerta.remove(),1000);
    }
},5000);
</script>

</body>
</html>
