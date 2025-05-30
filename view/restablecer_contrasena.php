<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - Running Web</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="assets/img/icon.ico" type="image/x-icon">
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
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }
        .smooth-transition {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 flex-col padding">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="assets/img/icon-black.jpg" alt="Logo RunningWeb" class="w-16 h-16 rounded-full">
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Running Web</h1>
            <p class="text-gray-600">Establece tu nueva contraseña</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-primary-600 text-white p-6">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-lock mr-3"></i>
                    Nueva Contraseña
                </h2>
            </div>

            <div class="p-6">
                <form id="nueva-password-form" class="space-y-5">
                    <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition" 
                                   placeholder="Mínimo 8 caracteres" required
                                   pattern=".{8,}" title="La contraseña debe tener al menos 8 caracteres">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                        </div>
                    </div>

                    <div>
                        <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                        <div class="relative">
                            <input type="password" id="confirm-password" name="confirm-password" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition" 
                                   placeholder="Repite tu contraseña" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm-password')"></i>
                        </div>
                    </div>

                    <!-- <div id="password-requirements" class="text-xs text-gray-500">
                        <p class="font-medium">La contraseña debe contener:</p>
                        <ul class="list-disc pl-5 mt-1">
                            <li id="req-length">Mínimo 8 caracteres</li>
                            <li id="req-uppercase">Al menos una mayúscula</li>
                            <li id="req-number">Al menos un número</li>
                            <li id="req-special">Al menos un carácter especial</li>
                        </ul>
                    </div> -->

                    <button type="submit" id="submit-btn" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium smooth-transition flex items-center justify-center">
                        <span id="btn-text">Establecer nueva contraseña</span>
                        <i id="btn-spinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                    </button>
                </form>

                <div id="form-message" class="mt-4 text-sm text-center hidden"></div>
            </div>
        </div>
        <footer class="mt-6 text-center text-sm text-gray-500">
            <p>© 2024 RunningWeb. Todos los derechos reservados.</p>
        </footer>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/restablecer.js"></script>
</body>
</html>