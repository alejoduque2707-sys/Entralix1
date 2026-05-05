<?php
session_start();

/* =========================
   VALIDAR SESIÓN
========================= */
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'aprendiz') {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre'];
$usuario_id = $_SESSION['usuario_id'];

/* =========================
   CONEXIÓN BD
========================= */
$conn = new mysqli("localhost", "root", "", "entralix");

if ($conn->connect_error) {
    die("Error de conexión");
}

/* =========================
   TRAER FICHA DEL APRENDIZ
========================= */
$sqlFicha = "
SELECT f.*
FROM aprendiz a
JOIN ficha f ON a.ficha_idFicha = f.idFicha
WHERE a.idAprendiz = ?
";

$stmt = $conn->prepare($sqlFicha);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$ficha = $stmt->get_result()->fetch_assoc();

/* =========================
   TRAER CRONOGRAMA
========================= */
$sqlCrono = "
SELECT * FROM cronograma_detalle
WHERE numero_ficha = ?
ORDER BY dia, hora_inicio
";

$stmt2 = $conn->prepare($sqlCrono);
$stmt2->bind_param("i", $ficha['ficCodigoFicha']);
$stmt2->execute();
$cronograma = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cronograma</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.sena-green { background-color: #0049a9; }
.sena-text-green { color: #005da9; }
.sidebar-item:hover { background-color: rgba(57,169,0,0.1); border-left: 4px solid #0079a9; }
</style>
</head>

<body class="bg-gray-100 font-sans min-h-screen">

<div class="flex min-h-screen">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-md hidden md:flex flex-col">
    <div class="p-6 flex flex-col items-center border-b">
        <i class="fas fa-user-circle text-6xl text-gray-400 mb-3"></i>
        <h2 class="font-bold text-gray-800"><?= $nombre ?></h2>
        <p class="text-xs text-gray-500">Aprendiz</p>
    </div>

    <nav class="flex-1 mt-4">
        <a href="aprendiz.php" class="sidebar-item flex items-center px-6 py-3 text-gray-600">
            <i class="fas fa-home mr-3"></i> Inicio
        </a>

        <a href="#" class="sidebar-item flex items-center px-6 py-3 text-gray-700 sena-text-green font-semibold">
            <i class="fas fa-calendar-alt mr-3"></i> Cronograma
        </a>
    </nav>

    <div class="p-4 border-t">
        <a href="logout.php" class="flex items-center text-red-500">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
        </a>
    </div>
</aside>

<!-- CONTENIDO -->
<main class="flex-1">

<header class="bg-white shadow-sm px-8 py-4">
    <h1 class="text-xl font-bold text-gray-700">
        Cronograma - Ficha <?= $ficha['ficCodigoFicha'] ?>
    </h1>
</header>

<div class="p-8">

<!-- INFO FICHA -->
<div class="bg-white p-6 rounded-xl shadow-sm mb-6 border-l-4 border-blue-500">
    <h2 class="text-lg font-bold text-gray-800">
        <?= $ficha['ficProgramaFormacion'] ?>
    </h2>
    <p class="text-sm text-gray-600">
        Jornada: <?= $ficha['ficJornada'] ?> |
        Estado: <?= $ficha['ficEstado'] ?>
    </p>
</div>

<!-- TABLA CRONOGRAMA -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">

<table class="w-full text-sm">

<thead class="bg-gray-100">
<tr>
    <th class="p-3">Día</th>
    <th class="p-3">Inicio</th>
    <th class="p-3">Fin</th>
    <th class="p-3">Competencia</th>
    <th class="p-3">Instructor</th>
    <th class="p-3">Ambiente</th>
</tr>
</thead>

<tbody>
<?php while($c = $cronograma->fetch_assoc()): ?>
<tr class="border-t hover:bg-gray-50">

<td class="p-3 capitalize"><?= $c['dia'] ?></td>
<td class="p-3"><?= $c['hora_inicio'] ?></td>
<td class="p-3"><?= $c['hora_fin'] ?></td>
<td class="p-3"><?= $c['competencia'] ?></td>
<td class="p-3"><?= $c['instructor'] ?></td>
<td class="p-3"><?= $c['ambiente'] ?></td>

</tr>
<?php endwhile; ?>
</tbody>

</table>

</div>

</div>

</main>

</div>

</body>
</html>