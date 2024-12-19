<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Post.php';
require_once __DIR__ . '/../../models/Comment.php';
require_once __DIR__ . '/../../config/Database.php';

use models\User;
use models\Post;
use models\Comment;

$database = new \config\Database();
$dbConnection = $database->getConnection();

$userModel = new User($dbConnection);
$postModel = new Post($dbConnection);
$commentModel = new Comment($dbConnection);

$users = $userModel->all();
$posts = $postModel->all();
$comments = $commentModel->all();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Dashboard - SnapPost</title>
</head>

<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-8">Dashboard</h1>

        <section class="mb-10">
            <h2 class="text-2xl font-bold mb-4">Gestión de Usuarios</h2>
            <table class="min-w-full bg-gray-800 rounded-lg text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4">ID</th>
                        <th class="py-2 px-4">Nombre de Usuario</th>
                        <th class="py-2 px-4">Correo Electrónico</th>
                        <th class="py-2 px-4">Rol</th>
                        <th class="py-2 px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($user['id']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="py-2 px-4">
                                <a href="edit-user.php?id=<?php echo $user['id']; ?>"
                                    class="text-blue-500 hover:text-blue-700">Editar</a>
                                <a href="delete-user.php?id=<?php echo $user['id']; ?>"
                                    class="text-red-500 hover:text-red-700 ml-4">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="mb-10">
            <h2 class="text-2xl font-bold mb-4">Gestión de Posts</h2>
            <table class="min-w-full bg-gray-800 rounded-lg text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4">ID</th>
                        <th class="py-2 px-4">Título</th>
                        <th class="py-2 px-4">Contenido</th>
                        <th class="py-2 px-4">Autor</th>
                        <th class="py-2 px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($post['id']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($post['title']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($post['content']); ?></td>
                            <td class="py-2 px-4">
                                <?php echo htmlspecialchars($userModel->read($post['author_id'])['username']); ?></td>
                            <td class="py-2 px-4">
                                <a href="../home/edit.php?id=<?php echo $post['id']; ?>"
                                    class="text-blue-500 hover:text-blue-700">Editar</a>
                                <a href="../home/delete_post.php?id=<?php echo $post['id']; ?>"
                                    class="text-red-500 hover:text-red-700 ml-4">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2 class="text-2xl font-bold mb-4">Gestión de Comentarios</h2>
            <table class="min-w-full bg-gray-800 rounded-lg text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4">ID</th>
                        <th class="py-2 px-4">Contenido</th>
                        <th class="py-2 px-4">Autor</th>
                        <th class="py-2 px-4">Post</th>
                        <th class="py-2 px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($comment['id']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($comment['content']); ?></td>
                            <td class="py-2 px-4">
                                <?php echo htmlspecialchars($userModel->read($comment['user_id'])['username']); ?></td>
                            <td class="py-2 px-4">
                                <?php echo htmlspecialchars($postModel->read($comment['post_id'])['title']); ?></td>
                            <td class="py-2 px-4">
                                <a href="edit-comment.php?id=<?php echo $comment['id']; ?>"
                                    class="text-blue-500 hover:text-blue-700">Editar</a>
                                <a href="delete-comment.php?id=<?php echo $comment['id']; ?>"
                                    class="text-red-500 hover:text-red-700 ml-4">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>