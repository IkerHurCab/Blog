<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../controllers/UserController.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Post.php';
require_once __DIR__ . '/../../controllers/PostController.php';

use models\User;
use controllers\UserController;
use models\Post;
use controllers\PostController;

$database = new \config\Database();
$dbConnection = $database->getConnection();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Perfil - SnapPost</title>
</head>

<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="container mx-auto mt-10">
        <h3 class="pt-4 text-2xl mb-4 text-center">Tu perfil</h3>
        <div class="flex justify-center items-center px-6">
            <div class="w-full lg:w-1/3 bg-gray-800 p-6 rounded-lg shadow-lg">
                <div class="flex justify-center mb-4">
                    <img src="https://via.placeholder.com/150" alt="Profile Picture" class="rounded-full w-32 h-32">
                </div>
                <form class="px-8 pt-6 pb-8 mb-4" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $username = $_POST['username'];
                            $email = $_POST['email'];
                            $userModel = new User($dbConnection);
                            $userController = new UserController($userModel);
                            $user = $userModel->findByEmail($email);

                            if ($user) {
                                $update = $userModel->update($user['id'], $username, $email, $user['password'], $user['role']);
                                $exists = $userModel->checkUpdate($user['id'], $email, $username);
                                if ($update && !$exists) {
                                    $_SESSION['user'] = $username;
                                    $_SESSION['email'] = $email;
                                    echo '<p class="text-green-500 text-center">Perfil actualizado</p>';
                                } else {
                                    echo '<p class="text-red-500 text-center">Error: nombre de usuario o correo electrónico ya existentes</p>';
                                }
                            } else {
                                echo '<p class="text-red-500 text-center">Usuario no encontrado</p>';
                            }
                        }
                    ?>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-bold text-white" for="username">
                            Nombre de Usuario
                        </label>
                        <input
                            class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            id="username" name="username" type="text" value="<?php echo htmlspecialchars($_SESSION['user']); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-bold text-white" for="email">
                            Correo Electrónico
                        </label>
                        <input
                            class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            id="email" name="email" type="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
                    </div>
                    <div class="mb-6 text-center">
                        <button type="submit"
                            class="inline-block px-6 py-2 text-sm font-bold text-white bg-blue-500 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                            Actualizar perfil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($_SESSION['role'] == 'escritor') : ?>
        <h3 class="pt-4 text-2xl mb-4 text-center">Tus posts</h3>
        <div class="flex flex-col items-center">
            <?php
            $postModel = new Post($dbConnection);
            $postController = new PostController($postModel);
            $posts = $postModel->findByAuthor($_SESSION['email']);

            if ($posts) {
                foreach ($posts as $post) {
                    echo '<div class="w-full md:w-1/3 bg-gray-800 p-6 rounded-lg shadow-lg m-4">';
                    echo '<div class="flex justify-between items-center mb-4">';
                    echo '<h3 class="text-2xl font-bold">' . $post['title'] . '</h3>';
                    echo '<p class="text-gray-400">Creado por: ' . htmlspecialchars($_SESSION['user']) . '</p>';
                    echo '</div>';
                    echo '<p class="text-gray-400">' . $post['content'] . '</p>';
                    echo '<div class="flex justify-end mt-4 gap-4">';
                    echo '<a href="../home/edit.php?id=' . $post['id'] . '" class="text-blue-500 hover:text-blue-700">Editar</a>';
                    echo '<form method="POST" action="../home/delete_post.php">';
                    echo '<input type="hidden" name="id" value="' . $post['id'] . '">';
                    echo '<button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-white my-4 text-4xl">No has creado ningún post aún. ¡Anímate a compartir algo!</p>';
                echo '<a href="../home/post.php" class="text-blue-500 text-2xl hover:text-blue-700 mb-4">Subir post</a>';
            }
            ?>
        </div>
        <?php endif; ?>
    </div>

</body>

</html>