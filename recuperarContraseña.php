<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entralix - Recuperar Contraseña</title>
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
        <div class="p-8 text-center border-b border-gray-100">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">                           
                <img src="Logo.png" alt="Logo" class="h-14 w-auto">
            </div>
            <h2 class="text-2xl font-bold text-gray-800">¿Olvidaste tu clave?</h2>
            <p class="text-gray-500 text-sm mt-2">
                Ingresa tu correo institucional para enviarte las instrucciones.
            </p>
        </div>

        <!-- FORMULARIO -->
        <form action="verificarCodigo.php" method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition-all"
                        placeholder="ejemplo@misena.edu.co">
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-purple-sena text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:bg-purple-700 transition-all">
                Enviar Código
            </button>

            <div class="text-center">
                <a href="login.php" class="text-sm font-bold text-purple-600 hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al inicio
                </a>
            </div>
        </form>
    </div>

</body>
</html>