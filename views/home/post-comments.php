<?php

use models\Comment;
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/Post.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Comment.php';
require_once __DIR__ . '/../../controllers/PostController.php';
require_once __DIR__ . '/../../config/Database.php';

use models\Post;
use models\User;
use controllers\PostController;

$database = new \config\Database();
$dbConnection = $database->getConnection();
$postModel = new Post($dbConnection);
$commentModel = new Comment($dbConnection);

$postId = $_GET['id'] ?? null;
$post = $postModel->read($postId);
$comments = $commentModel->findByPost($postId);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Comentarios - SnapPost</title>
</head>
<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="container mx-auto mt-10">
        <h2 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($post['title']); ?></h2>
        <p class="text-gray-400 mb-8"><?php echo htmlspecialchars($post['content']); ?></p>
        
        <h3 class="text-2xl font-bold mb-4">Comentarios</h3>
        <div class="space-y-4">
            <?php
            if ($comments) {
                foreach ($comments as $comment) {
                    $userModel = new User($dbConnection);
                    $author = $userModel->read($comment['user_id']);
                    $user = $userModel->findByEmail($_SESSION['email']);
                    if ($author) {
                        echo '<div class="bg-gray-800 p-4 rounded-lg shadow-lg">';
                        echo '<div class="flex items-center mb-2">';
                        echo '<img src="https://via.placeholder.com/40" alt="User Avatar" class="rounded-full w-10 h-10 mr-4">';
                        echo '<div>';
                        echo '<p class="text-sm font-bold">' . htmlspecialchars($author['username']) . '</p>';
                        echo '<p class="text-gray-300">' . htmlspecialchars($comment['content']) . '</p>';
                        echo '</div>';
                        if ($user['id'] == $comment['user_id'] || $user['role'] == 'admin') {
                            echo '<a href="delete-comment.php?id=' . $comment['id'] . '&postId=' . $postId . '" class="ml-4 text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></a>';
                        }
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="bg-gray-800 p-4 rounded-lg shadow-lg">';
                        echo '<p class="text-red-500">Autor no encontrado</p>';
                        echo '</div>';
                    }
                }
            } else {
                echo '<p class="text-white">No hay comentarios aún. ¡Sé el primero en comentar!</p>';
            }
            ?>
        </div>
        <form method="POST" action="post-comments.php?id=<?php echo $postId; ?>" class="mt-8">
            <textarea name="content" rows="4" class="w-full p-2 rounded-lg bg-gray-800 text-white" placeholder="Añadir un comentario..."></textarea>
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Comentar</button>
        </form>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $content = $_POST['content'];
        $userModel = new User($dbConnection);
        $author = $userModel->findByEmail($_SESSION['email']);
        $userId = $author['id']; 
        $commentModel->store($content, $userId, $postId); 
        header("Location: post-comments.php?id=$postId");
        exit();
    }
    ?>
</body>
</html>