<?php
// Archivo: index.php
// Datos simulados para la sección de noticias u ofertas
$noticias = [
    ["titulo" => "Convocatoria 2026", "desc" => "Inscríbete en programas tecnológicos presenciales.", "icon" => "fa-user-graduate"],
    ["titulo" => "Fondo Emprender", "desc" => "Convierte tu proyecto de grado en una empresa real.", "icon" => "fa-lightbulb"],
    ["titulo" => "Bilingüismo", "desc" => "Nuevos cupos para English Does Work nivel 1 al 13.", "icon" => "fa-language"]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entralix - Plataforma Académica</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-purple-sena { background-color: #7c3aed; } 
        .text-purple-sena { color: #7c3aed; }
        .hover-purple-sena:hover { background-color: #6d28d9; }
        .hero-pattern {
            background-color: #3b75f3;
            background-image: url("https://www.transparenttextures.com/patterns/cubes.png");
        }
        .transition-all { transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-white font-sans text-gray-800">

    <nav class="bg-white border-b sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">

                     <img src="Logo.png" alt="Logo" class="h-14 w-auto">
                    <span class="ml-4 font-bold text-2xl tracking-tight hidden md:block text-gray-700">Entralix</span>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="#" class="text-gray-600 hover:text-purple-sena font-medium transition-all">Ayuda</a>
                    <a href="login.php" class="bg-purple-sena text-white px-8 py-2.5 rounded-full font-bold hover-purple-sena transition shadow-lg flex items-center">
                        Iniciar Sesión <i class="fas fa-sign-in-alt ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <header class="hero-pattern py-24 border-b">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-6xl font-extrabold leading-tight mb-6 text-white">
                    Formación integral para el <span class="text-purple-300 uppercase">Futuro Académico</span>
                </h1>
                <p class="text-xl text-white mb-10 mr-12 opacity-95 leading-relaxed">
                    Accede a tus cursos, gestiona tus evidencias y mantente al día con tu proceso de formación profesional integral.
                </p>
                <div class="flex space-x-4">
                  <button onclick="window.location.href='https://betowa.sena.edu.co/oferta'"
    class="bg-gray-900 text-white px-10 py-4 rounded-xl font-bold hover:bg-black shadow-2xl transition-all">
    Explorar Cursos
</button>
                   <button onclick="window.location.href='https://disenometrologia.blogspot.com/'"
    class="border-2 border-white text-white px-10 py-4 rounded-xl font-bold hover:bg-white hover:text-blue-600 transition-all">
    Ver CDM
</button>
                </div>
            </div>
            
            <div class="md:w-1/2 flex justify-center">
                <div class="relative">
                    <div class="absolute -top-6 -left-6 w-80 h-80 bg-purple-sena rounded-3xl rotate-6 opacity-30 blur-sm"></div>
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Estudiantes" class="relative z-10 rounded-3xl shadow-2xl w-full max-w-md border-8 border-white/10">
                </div>
            </div>
        </div>
    </header>

    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800">Novedades del Centro</h2>
                <div class="h-1.5 w-24 bg-purple-sena mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php foreach($noticias as $item): ?>
                <div class="bg-white p-10 rounded-2xl shadow-sm hover:shadow-2xl transition-all border border-gray-100 group">
                    <div class="w-16 h-16 bg-purple-sena rounded-2xl flex items-center justify-center mb-8 shadow-md transform group-hover:scale-110 group-hover:-rotate-3 transition-all">
                        <i class="fas <?php echo $item['icon']; ?> text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800"><?php echo $item['titulo']; ?></h3>
                    <p class="text-gray-500 mb-6 leading-relaxed"><?php echo $item['desc']; ?></p>
                    <a href="#" class="text-purple-sena font-bold text-lg inline-flex items-center group-hover:translate-x-2 transition-all">
                        Leer más <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center mb-8">
                <div class="h-px w-12 bg-gray-700"></div>
                <span class="mx-4 font-bold text-xl italic opacity-50 uppercase tracking-widest">Entralix</span>
                <div class="h-px w-12 bg-gray-700"></div>
            </div>
            <p class="text-gray-500 text-sm">© 2026 Entralix - Sistema Integrado de Gestión Académica</p>
            <p class="text-gray-600 text-xs mt-2 uppercase tracking-tighter">Formación profesional integral</p>
        </div>
    </footer>

</body>
</html>

<!-- 🔥 SCRIPT PARA DESAPARECER ALERTA -->
<script>
setTimeout(() => {
    const alerta = document.getElementById('alerta');
    if (alerta) {
        alerta.style.transition = "opacity 1s";
        alerta.style.opacity = "0";
        setTimeout(() => alerta.remove(), 1000);
    }
}, 20000); // 20 segundos
</script>

</body>
</html>