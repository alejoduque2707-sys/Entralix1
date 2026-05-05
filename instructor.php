<?php
session_start();

/* =========================
   VALIDAR SESIÓN
========================= */
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'instructor') {
    header("Location: login.php");
    exit();
}

$instructor = $_SESSION['nombre'];

/* =========================
   CONEXIÓN BD
========================= */
$conn = new mysqli("localhost", "root", "", "entralix");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

/* =========================
   TRAER FICHAS
========================= */
$stmt = $conn->prepare("SELECT * FROM fichas WHERE instructor = ?");

if (!$stmt) {
    die("Error en SQL: " . $conn->error);
}

$stmt->bind_param("s", $instructor);
$stmt->execute();
$resultado = $stmt->get_result();

$fichasInstructor = [];
while ($row = $resultado->fetch_assoc()) {
    $fichasInstructor[] = $row;
}

/* =========================
   INDICADORES
========================= */
$totalFichas = count($fichasInstructor);
$totalAprendices = array_sum(array_column($fichasInstructor, 'aprendices'));
$clasesHoy = $totalFichas;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard Instructor</title>

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
        <i class="fas fa-user-tie text-6xl text-gray-400 mb-3"></i>
        <h2 class="font-bold text-gray-800"><?= $instructor ?></h2>
        <p class="text-xs text-gray-500">Instructor</p>
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
        <a href="logout.php" class="flex items-center text-red-500 hover:text-red-700">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
        </a>
    </div>

</aside>

<!-- CONTENIDO -->
<main class="flex-1 overflow-y-auto">

<header class="bg-white p-4 shadow">
    <h1 class="font-bold text-gray-700">Bienvenido <?= $instructor ?></h1>
</header>

<div class="p-8">

<!-- TARJETAS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
    <p class="text-sm text-gray-500">Fichas</p>
    <h3 class="text-3xl font-bold"><?= $totalFichas ?></h3>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-400">
    <p class="text-sm text-gray-500">Aprendices</p>
    <h3 class="text-3xl font-bold"><?= $totalAprendices ?></h3>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-orange-400">
    <p class="text-sm text-gray-500">Clases Hoy</p>
    <h3 class="text-3xl font-bold"><?= $clasesHoy ?></h3>
</div>

</div>

<!-- FICHAS -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

<?php if (count($fichasInstructor) > 0): ?>

<?php foreach ($fichasInstructor as $f): ?>
<div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">

    <div class="<?= $f['tipo']=='Técnico' ? 'sena-green' : 'bg-blue-600' ?> p-4 text-white flex justify-between">
        <span class="text-xs font-bold"><?= $f['tipo'] ?></span>
        <span class="text-xs">Ficha: <?= $f['numero_ficha'] ?></span>
    </div>

    <div class="p-6">
        <h3 class="font-bold text-gray-800 mb-2"><?= $f['programa'] ?></h3>
        <p class="text-sm text-gray-600 mb-3"><?= $f['aprendices'] ?> aprendices</p>

        <div class="bg-gray-200 h-2 rounded mb-3">
            <div class="<?= $f['tipo']=='Técnico' ? 'sena-green' : 'bg-blue-600' ?> h-2 rounded"
                 style="width: <?= $f['avance'] ?>%">
            </div>
        </div>

        <p class="text-xs text-gray-500">Avance: <?= $f['avance'] ?>%</p>
    </div>

</div>
<?php endforeach; ?>

<?php else: ?>
<p class="text-gray-600">No tienes fichas asignadas.</p>
<?php endif; ?>

</div>

</div>

</main>

</div>

</body>
</html>