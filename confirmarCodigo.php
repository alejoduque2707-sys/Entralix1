<?php
session_start();
$conn = new mysqli("localhost", "root", "", "entralix");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $codigo = $_POST['codigo'] ?? '';
    $correo = $_SESSION['correo_recuperacion'] ?? '';

    if (!$codigo || !$correo) {
        $error = "campos";
    } else {

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo=? AND codigo_recuperacion=?");
        $stmt->bind_param("ss", $correo, $codigo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            if (strtotime($row['expira_codigo']) < time()) {
                $error = "expirado";
            } else {
                $_SESSION['verificado'] = true;
                header("Location: nuevaContraseña.php");
                exit();
            }

        } else {
            $error = "incorrecto";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Confirmar Código</title>
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
        <h2 class="text-2xl font-bold text-gray-800">Verificar Código</h2>
        <p class="text-gray-500 text-sm mt-2">Ingresa el código enviado a tu correo</p>
    </div>

    <!-- ALERTAS -->
    <?php if ($error): ?>
    <div id="alerta" class="mx-8 mt-4 p-3 border rounded-lg text-sm
        <?php echo ($error == 'campos') 
            ? 'bg-yellow-100 border-yellow-400 text-yellow-700' 
            : 'bg-red-100 border-red-400 text-red-700'; ?>">
        
        <i class="fas fa-exclamation-triangle mr-2"></i>

        <?php 
        if ($error == 'campos') echo "Completa el campo.";
        if ($error == 'incorrecto') echo "Código incorrecto.";
        if ($error == 'expirado') echo "El código expiró.";
        ?>
    </div>
    <?php endif; ?>

    <!-- FORM -->
    <form method="POST" class="p-8 space-y-6">

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Código</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-key"></i>
                </span>
                <input type="text" name="codigo" required
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl"
                    placeholder="Ej: 123456">
            </div>
        </div>

        <button type="submit"
            class="w-full bg-purple-sena text-white py-4 rounded-xl font-bold">
            Verificar Código
        </button>

        <div class="text-center">
            <a href="login.php" class="text-sm text-purple-sena hover:underline">
                ← Volver al login
            </a>
        </div>

    </form>

</div>

<!-- 🔥 DESAPARECER ALERTA -->
<script>
setTimeout(() => {
    const alerta = document.getElementById('alerta');
    if (alerta) {
        alerta.style.transition = "opacity 1s";
        alerta.style.opacity = "0";
        setTimeout(() => alerta.remove(), 1000);
    }
}, 5000);
</script>

</body>
</html>