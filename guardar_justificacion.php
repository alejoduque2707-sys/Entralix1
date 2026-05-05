<?php
session_start();

/* =========================
   CONEXIÓN
========================= */
$conn = new mysqli("localhost", "root", "", "entralix");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8");

/* =========================
   VALIDAR SESIÓN
========================= */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$mensaje = "";




/* =========================
   GUARDAR JUSTIFICACIÓN
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario_id = $_SESSION['usuario_id']; // ID del usuario logueado
    $nombre     = $_SESSION['nombre'];     // Nombre de la sesión

    $tipo_doc    = $_POST['tipo_documento'];
    $documento   = $_POST['documento'];
    $descripcion = $_POST['descripcion'];

    // 📂 MANEJO DE ARCHIVO PDF
    $archivoNombre = "";

    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $ruta = "uploads/";
        
        // Crear carpeta si no existe
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        // Obtener extensión para validar que sea PDF
        $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
        
        if (strtolower($ext) === 'pdf') {
            // Nombre único: ID_Tiempo_NombreOriginal.pdf
            $archivoNombre = $usuario_id . "_" . time() . ".pdf";
            move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta . $archivoNombre);
        } else {
            $mensaje = "solo_pdf";
        }
    }

    // Solo procedemos si no hubo error de formato de archivo
    if ($mensaje !== "solo_pdf") {
        /* 🔥 CONEXIÓN CON TU TABLA 'justificaciones' 
           Campos: id (AI), usuario_id, nombre, tipo_documento, documento, descripcion, archivo, fecha
        */
        $stmt = $conn->prepare("INSERT INTO justificaciones (usuario_id, nombre, tipo_documento, documento, descripcion, archivo) VALUES (?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            die("Error en SQL: " . $conn->error);
        }

        // Corrección de variable: $usuario_id (sin 's' al final)
        $stmt->bind_param("isssss", $usuario_id, $nombre, $tipo_doc, $documento, $descripcion, $archivoNombre);

        if ($stmt->execute()) {
            $mensaje = "ok";
        } else {
            $mensaje = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entralix - Justificaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-purple-sena { background-color: #7c3aed; }
        .hero-pattern {
            background-color: #f3f4f6;
            background-image: url("https://www.transparenttextures.com/patterns/cubes.png");
        }
    </style>
</head>

<body class="hero-pattern flex items-center justify-center min-h-screen p-4">

<div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100">

    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-purple-sena rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
            <i class="fas fa-file-alt text-white text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Justificación</h2>
        <p class="text-gray-500 text-sm">Registra tu inasistencia aquí</p>
    </div>

    <?php if ($mensaje == "ok"): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-5 rounded-r-lg text-sm flex items-center">
            <i class="fas fa-check-circle mr-2"></i> Justificación guardada exitosamente.
        </div>
    <?php endif; ?>

    <?php if ($mensaje == "error"): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-5 rounded-r-lg text-sm flex items-center">
            <i class="fas fa-times-circle mr-2"></i> Error al guardar en la base de datos.
        </div>
    <?php endif; ?>

    <?php if ($mensaje == "solo_pdf"): ?>
        <div id="cuadroRojo" class="bg-red-600 text-white p-4 mb-5 rounded-xl text-sm font-bold shadow-lg animate-pulse">
            <i class="fas fa-exclamation-triangle mr-2"></i> INSERTE SOLO ARCHIVOS PDF
        </div>
        <script>
            setTimeout(() => { document.getElementById('cuadroRojo').remove(); }, 5000);
        </script>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Tipo Documento</label>
            <select name="tipo_documento" required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                <option value="">Seleccione...</option>
                <option value="CC">Cédula de Ciudadanía</option>
                <option value="TI">Tarjeta de Identidad</option>
                <option value="CE">Cédula de Extranjería</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Número de Documento</label>
            <input type="text" name="documento" required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none"
                placeholder="Ej: 1000123456">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Motivo / Descripción</label>
            <textarea name="descripcion" required rows="3"
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none"
                placeholder="Explica brevemente el motivo..."></textarea>
        </div>

        <div class="relative">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Soporte (PDF)</label>
            <input type="file" name="archivo" id="inputPdf" accept=".pdf" required
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
        </div>

        <button class="w-full bg-purple-sena hover:bg-purple-700 text-white py-4 rounded-2xl font-bold shadow-lg transition-all transform hover:scale-[1.01] active:scale-95">
            <i class="fas fa-paper-plane mr-2"></i> Enviar Justificación
        </button>

        <a href="aprendiz.php" class="block text-center text-xs font-bold text-gray-400 hover:text-purple-600 transition-colors mt-4">
            Volver al panel principal
        </a>
    </form>
</div>

<script>
// Validación del lado del cliente para el cuadro rojo de 5 segundos
document.getElementById('inputPdf').addEventListener('change', function() {
    const file = this.files[0];
    if (file && file.type !== "application/pdf") {
        this.value = '';
        alert("Por favor, selecciona solo archivos PDF.");
    }
});
</script>

</body>
</html>