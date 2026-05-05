<?php
session_start();
$conn = new mysqli("localhost", "root", "", "entralix");

if ($conn->connect_error) {
    die("Error de conexión");
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario === "" || $password === "") {
        $error = "campos";
    } else {

        $sql = "SELECT * FROM usuarios WHERE nombre=? AND contraseña=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $_SESSION['admin'] = $row['nombre'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "credenciales";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.sena-blue { background-color: #005da9; }

.sidebar-item:hover {
    background-color: rgba(0,93,169,0.1);
    border-left: 4px solid #005da9;
}

/* INPUT estilo instructor */
.input-sena {
    width: 100%;
    margin-top: 6px;
    padding: 12px 14px;
    border: 1px solid #ddd;
    border-radius: 12px;
    outline: none;
    transition: all 0.2s;
}

.input-sena:focus {
    border-color: #005da9;
    box-shadow: 0 0 0 2px rgba(0,93,169,0.2);
}
</style>
</head>

<body class="bg-gray-100 font-sans min-h-screen">

<div class="flex min-h-screen">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-md hidden md:flex flex-col">

    <div class="p-6 text-center border-b">
        <i class="fas fa-user text-6xl text-gray-400 mb-3"></i>
        <h2 class="font-bold text-gray-800">Instructor</h2>
        <p class="text-xs text-gray-500">Acceso</p>
    </div>

   <nav class="flex-1 mt-4">

        <a href="instructor.php" class="sidebar-item flex items-center px-6 py-3 text-gray-700 sena-text-green font-semibold">
            <i class="fas fa-home mr-3"></i> Inicio
        </a>

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-book mr-3"></i> Mis Fichas
        </a>

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-calendar-alt mr-3"></i> Cronograma
        </a>

        <!-- BOTÓN ADMIN -->
        <a href="admin_login.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-user-shield mr-3"></i> Administrador
        </a>

    </nav>

     <div class="p-4 border-t">
        <a href="logout_admin.php" class="flex items-center text-red-500 hover:text-red-700">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
        </a>
    </div>
    
</aside>

<!-- CONTENIDO -->
<main class="flex-1 flex items-center justify-center">

<div class="w-full max-w-md">

    <div class="bg-white p-8 rounded-2xl shadow-xl">

        <h2 class="text-xl font-bold text-gray-700 mb-6 text-center">
            Iniciar Sesión
        </h2>

        <?php if ($error): ?>
        <div id="alerta" class="mb-4 p-3 rounded text-sm
            <?= ($error == 'campos') ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700' ?>">
            <?= ($error == 'campos') ? "Completa los campos" : "Credenciales incorrectas" ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">

            <div>
                <label class="text-sm font-bold text-gray-700">Usuario</label>
                <input type="text" name="usuario" class="input-sena" placeholder="Ej: admin">
            </div>

            <div>
                <label class="text-sm font-bold text-gray-700">Contraseña</label>
                <input type="password" name="password" class="input-sena" placeholder="••••••••">
            </div>

            <button class="w-full sena-blue text-white py-3 rounded-xl font-semibold hover:opacity-90 transition">
                Ingresar
            </button>

        </form>

    </div>

</div>

</main>

</div>

<!-- ALERTA AUTO -->
<script>
setTimeout(() => {
    const alerta = document.getElementById('alerta');
    if (alerta) {
        alerta.style.transition = "all 0.5s";
        alerta.style.opacity = "0";
        alerta.style.transform = "translateY(-10px)";
        setTimeout(() => alerta.remove(), 500);
    }
}, 5000);
</script>

</body>
</html>