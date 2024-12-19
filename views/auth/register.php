<?php
require_once '../../config/Database.php';
require_once '../../controllers/UserController.php';
require_once '../../models/User.php';

$database = new \config\Database();
$dbConnection = $database->getConnection();

session_start();
if (isset($_SESSION['user'])) {
    header('Location: ../home/home.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="../../public/js/change-password-visibility.js"></script>
    <title>Registrarse - SnapPost</title>
</head>

<body class="bg-gray-900">
    <div class="min-h-screen text-white">
        <div class="flex flex-col md:flex-row items-center justify-center gap-10 min-h-screen p-4">
            <img src="../../public/images/logo.png" alt="logo">
            <div class="flex flex-col space-y-6 ">
                <h1 class="text-3xl md:text-5xl font-bold text-center md:text-left">Tus blogs de siempre.</h1>
                <h1 class="text-3xl md:text-5xl font-bold text-center md:text-left">Más accesibles que nunca.</h1>
                <h2 class="text-xl md:text-2xl text-center md:text-left">Regístrate en SnapPost</h2>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
                    class="mt-4 space-y-4 ">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $username = $_POST['username'];
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $checkPassword = $_POST['verify-password'];
                        $role = $_POST['role'];

                        $userModel = new \models\User($dbConnection);
                        $exists = $userModel->findByEmailOrUsername($email, $username);

                        if ($password !== $checkPassword) {
                            echo '<p class="text-red-500">Las contraseñas no coinciden</p>';
                        } else if ($exists) {
                            echo '<p class="text-red-500">Nombre de usuario o correo electrónico ya existentes</p>';
                        } else {
                            $userController = new \controllers\UserController($userModel);
                            $register = $userController->register($username, $email, $password, $role);

                            if ($register) {
                                header('Location: login.php');
                            } else {
                                echo '<p class="text-red-500">Error al registrar el usuario</p>';
                            }
                        }
                    }
                    ?>
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-300">Nombre de usuario</label>
                        <input type="text" id="username" name="username" required
                            class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-300">Correo electrónico</label>
                        <input type="email" id="email" name="email" required
                            class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4 relative">
                        <label for="password" class="block text-sm font-medium text-gray-300">Contraseña</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <i class="fa fa-eye cursor-pointer absolute right-3 top-9"
                            onclick="togglePasswordVisibility('password')"></i>
                    </div>
                    <div class="mb-4 relative">
                        <label for="verify-password" class="block text-sm font-medium text-gray-300">Confirmar
                            contraseña</label>
                        <input type="password" id="verify-password" name="verify-password" required
                            class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <i class="fa fa-eye cursor-pointer absolute right-3 top-9"
                            onclick="togglePasswordVisibility('verify-password')"></i>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-300">¿Qué te consideras?</label>
                        <div class="flex gap-4 mt-2">
                            <div class="flex items-center">
                                <input id="lector" name="role" type="radio" value="lector" required
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="lector" class="ml-3 block text-sm font-medium text-gray-300">Lector</label>
                            </div>
                            <div class="flex items-center">
                                <input id="escritor" name="role" type="radio" value="escritor" required
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="escritor"
                                    class="ml-3 block text-sm font-medium text-gray-300">Escritor</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md mb-4 md:mb-0">Registrarse</button>
                        <p>¿Ya tienes cuenta? <a href="login.php"
                                class="text-blue-400 hover:text-blue-600 cursor-pointer">Inicia sesión</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>