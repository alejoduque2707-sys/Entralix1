<?php
session_start();
$conn = new mysqli("localhost", "root", "", "entralix");

if ($conn->connect_error) {
    die("Error de conexión");
}

// 🔒 Seguridad: solo si viene del código
if (!isset($_SESSION['verificado'])) {
    header("Location: login.php");
    exit();
}

$exito = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pass = $_POST['nueva_password'] ?? '';
    $correo = $_SESSION['correo_recuperacion'] ?? '';

    if ($pass && $correo) {

        // 🔐 OPCIONAL (recomendado): encriptar contraseña
        // $pass = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET contraseña=?, codigo_recuperacion=NULL, expira_codigo=NULL WHERE correo=?");
        $stmt->bind_param("ss", $pass, $correo);
        $stmt->execute();

        // limpiar sesión
        unset($_SESSION['verificado']);
        unset($_SESSION['correo_recuperacion']);

        $exito = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Entralix - Actualizar Contraseña</title>

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

<div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">
    
    <div class="p-8 text-center border-b border-gray-100 bg-gray-50/50">
        <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4">                           
            <img src="Logo.png" alt="Logo" class="h-14 w-auto">
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Seguridad de la Cuenta</h2>
        <p class="text-gray-500 text-sm mt-2">Cambia tu contraseña para proteger tu acceso</p>
    </div>

    <!-- FORMULARIO -->
    <form method="POST" onsubmit="return validarPasswords()" class="p-8 space-y-5">

        <div>
            <label class="block text-sm font-bold text-purple-700 mb-2">Nueva Contraseña</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" name="nueva_password" id="nueva_password" required
                    class="w-full pl-10 pr-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none shadow-sm"
                    placeholder="Nueva clave (mín. 8 caracteres)">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-purple-700 mb-2">Confirmar Nueva Contraseña</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    <i class="fas fa-check-double"></i>
                </span>
                <input type="password" id="confirmar_password" required
                    class="w-full pl-10 pr-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none shadow-sm"
                    placeholder="Repite tu nueva clave">
            </div>

            <p id="errorPassword" class="text-red-500 text-xs mt-2 hidden">
                Las contraseñas no coinciden
            </p>
        </div>

        <div class="pt-4">
            <button type="submit" 
                class="w-full bg-purple-sena text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:bg-purple-700 flex items-center justify-center">
                <i class="fas fa-shield-alt mr-2"></i> Actualizar Contraseña
            </button>
        </div>

    </form>
</div>

<!-- MODAL -->
<div id="modalExito" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl p-6 w-80 text-center shadow-xl">
        <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
        <h3 class="text-lg font-bold text-gray-800">¡Contraseña actualizada!</h3>
        <p class="text-gray-500 text-sm mt-2">
            Tu contraseña ha sido actualizada correctamente.
        </p>
        <button onclick="irLogin()" 
            class="mt-4 bg-purple-sena text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            Aceptar
        </button>
    </div>
</div>

<script>
function validarPasswords() {
    let pass1 = document.getElementById('nueva_password').value;
    let pass2 = document.getElementById('confirmar_password').value;
    let error = document.getElementById('errorPassword');

    if (pass1 !== pass2) {
        error.classList.remove('hidden');
        return false;
    }

    error.classList.add('hidden');
    return true;
}

function irLogin() {
    window.location.href = "login.php";
}
</script>

<?php if ($exito): ?>
<script>
document.getElementById('modalExito').classList.remove('hidden');
</script>
<?php endif; ?>

</body>
</html>