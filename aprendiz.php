<?php
session_start();

/* =========================
   PROTECCIÓN DE ACCESO
========================= */
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'aprendiz') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre'];

/* =========================
   SECCIÓN DINÁMICA
========================= */
$seccion = $_GET['seccion'] ?? 'inicio';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Aprendiz - SENA</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.sena-green { background-color: #0049a9; }
.sena-text-green { color: #005da9; }
.sidebar-item:hover { background-color: rgba(57, 169, 0, 0.1); border-left: 4px solid #0079a9; }
.active { background-color: rgba(57,169,0,0.1); border-left: 4px solid #0079a9; font-weight: bold; }
</style>
</head>

<body class="bg-gray-100 font-sans min-h-screen">

<div class="flex min-h-screen">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-md hidden md:flex flex-col">

    <div class="p-6 flex flex-col items-center border-b">
        <div class="w-20 h-20 bg-gray-200 rounded-full mb-3 flex items-center justify-center">
            <i class="fas fa-user-circle text-6xl text-gray-400"></i>
        </div>

        <h2 class="font-bold text-gray-800"><?= $nombre ?></h2>
        <p class="text-xs text-gray-500">Aprendiz ADSO</p>
    </div>

    <nav class="flex-1 mt-4">

        <a href="?seccion=inicio"
           class="sidebar-item flex items-center px-6 py-3 <?= $seccion=='inicio'?'active':'' ?>">
            <i class="fas fa-home mr-3"></i> Inicio
        </a>

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-book mr-3"></i> Mis Fichas
        </a>

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-calendar-alt mr-3"></i> Cronograma
        </a>

        <!-- 🔥 SOLO CAMBIAMOS ESTE -->
        <a href="?seccion=justificacion"
           class="sidebar-item flex items-center px-6 py-3 <?= $seccion=='justificacion'?'active':'' ?>">
            <i class="fas fa-file-alt mr-3"></i> Justificación de Falla
        </a>

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-certificate mr-3"></i> Calificaciones
        </a>

    </nav>

    <div class="p-4 border-t">
        <a href="logout.php" class="flex items-center text-red-500 hover:text-red-700">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
        </a>
    </div>

</aside>

<!-- MAIN -->
<main class="flex-1">

<!-- HEADER (NO SE TOCA) -->
<header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
    <div class="flex items-center">
        <img src="Logo.png" class="h-14 mr-3">
        <h1 class="text-xl font-bold text-gray-700">Portal de Formación SENA</h1>
    </div>

    <div class="flex items-center space-x-4">
        <i class="fas fa-bell text-gray-500"></i>
        <span class="text-gray-600 text-sm">Centro de diseño y metrología</span>
    </div>
</header>

<div class="p-8">

<!-- =========================
     DASHBOARD ORIGINAL
========================= -->
<?php if ($seccion == 'inicio'): ?>

<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">
        ¡Bienvenido de nuevo, <?= $nombre ?>!
    </h2>
    <p class="text-gray-600">Aquí tienes el resumen de tu proceso formativo.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
        <p class="text-sm text-gray-500 font-medium">Fichas Activas</p>
        <h3 class="text-3xl font-bold text-gray-800">2</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-orange-400">
        <p class="text-sm text-gray-500 font-medium">Evidencias Pendientes</p>
        <h3 class="text-3xl font-bold text-gray-800">4</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-400">
        <p class="text-sm text-gray-500 font-medium">Asistencia Promedio</p>
        <h3 class="text-3xl font-bold text-gray-800">95%</h3>
    </div>

</div>

<h3 class="text-lg font-semibold text-gray-700 mb-4">Mis Programas de Formación</h3>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="sena-green p-4 text-white flex justify-between">
            <span>TÉCNICO</span>
            <span>Ficha: 2871452</span>
        </div>

        <div class="p-6">
            <h4 class="font-bold">Análisis y Desarrollo de Software</h4>
            <p class="text-sm text-gray-600">Instructor: Carlos Arrieta</p>

            <div class="bg-gray-200 h-2 mt-3 rounded">
                <div class="sena-green h-2 rounded" style="width:65%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-blue-600 p-4 text-white flex justify-between">
            <span>TRANSVERSAL</span>
            <span>Ficha: 2871452</span>
        </div>

        <div class="p-6">
            <h4 class="font-bold">Inglés Técnico - Nivel 1</h4>
            <p class="text-sm text-gray-600">Instructor: María López</p>

            <div class="bg-gray-200 h-2 mt-3 rounded">
                <div class="bg-blue-600 h-2 rounded" style="width:30%"></div>
            </div>
        </div>
    </div>

</div>

<?php endif; ?>


<!-- =========================
     JUSTIFICACIÓN
========================= -->
<?php if ($seccion == 'justificacion'): ?>

<div class="bg-white p-8 rounded-xl shadow max-w-xl mx-auto">

    <h2 class="text-xl font-bold mb-4">
        Justificación de Inasistencia
    </h2>

    <form action="guardar_justificacion.php" method="POST" enctype="multipart/form-data" class="space-y-4">

        <input type="date" name="fecha" class="w-full p-3 border rounded-xl" required>

        <textarea name="motivo" placeholder="Explica el motivo..."
        class="w-full p-3 border rounded-xl"></textarea>

        <input type="file" name="archivo" class="w-full p-3 border rounded-xl">

        <button class="w-full bg-blue-600 text-white py-3 rounded-xl">
            Enviar Justificación
        </button>

    </form>

</div>

<?php endif; ?>

</div>
</main>
</div>

</body>
</html>