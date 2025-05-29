<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - RunningWeb</title>
    <link rel="shortcut icon" href="assets/img/icon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        .smooth-transition {
            transition: all 0.3s ease;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="assets/img/icon-black.jpg" alt="Logo RunningWeb" class="w-16 h-16 rounded-full">
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Running Web</h1>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover smooth-transition">
            <div class="gradient-bg text-white p-6">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-key mr-3"></i>
                    Recuperación de contraseña
                </h2>
            </div>

            <div class="p-6">
                <div id="forgot-password-form">
                    <form id="recuperar-form" class="space-y-5">
                        <div>
                            <label for="recuperar-email" class="block text-sm font-medium text-gray-700 mb-1">Correo
                                electrónico</label>
                            <div class="relative">
                                <input type="email" id="recuperar-email"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-10 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition"
                                    placeholder="tucorreo@ejemplo.com" required>
                                <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                        </div>

                        <button type="submit" id="submit-btn"
                            class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium smooth-transition flex items-center justify-center">
                            <span id="btn-text">Enviar enlace de recuperación</span>
                            <i id="btn-spinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                        </button>
                    </form>

                    <div id="recuperar-msg" class="mt-4 text-sm text-center hidden"></div>
                </div>

                <div class="mt-6 text-center">
                    <a href="iniciar_sesion.html"
                        class="text-primary-600 hover:text-primary-800 text-sm font-medium smooth-transition">
                        <i class="fas fa-arrow-left mr-1"></i> Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>

        
        <footer class="mt-6 text-center text-sm text-gray-500">
            <p>© 2024 RunningWeb. Todos los derechos reservados.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/recuperacion.js"></script>
</body>

</html>