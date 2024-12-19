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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Home - SnapPost</title>
</head>
<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="min-h-screen m-4">
        <h1 class="text-3xl md:text-5xl font-bold">Bienvenido/a, <?php echo ucfirst(htmlspecialchars($_SESSION['user'])); ?>!</h1>
        <div class= "flex flex-col items-center">
            <h2 class="text-2xl md:text-4xl font-bold mt-4">Últimos posts</h2>
            <?php
            $postModel = new Post($dbConnection);
            $postController = new PostController($postModel);
            $posts = $postModel->all();

            if ($posts) {
                foreach ($posts as $post) {
                    $userModel = new User($dbConnection);
                    $author = $userModel->read($post['author_id']);
                    echo '<div class="w-full md:w-1/3 bg-gray-800 p-6 rounded-lg shadow-lg m-4">';
                    echo '<div class="flex justify-between items-center mb-4">';
                    echo '<h3 class="text-2xl font-bold">' . $post['title'] . '</h3>';
                    echo '<p class="text-gray-400">Creado por: ' . htmlspecialchars($author['username']) . '</p>';
                    echo '</div>';
                    
                    echo '<p class="text-gray-400">' . $post['content'] . '</p>';

                    
                    $comments = new Comment(    $dbConnection);
                    echo '<div class="flex justify-end gap-4 items-center">';
                    echo count($comments->findByPost($post['id'])) . ' comentarios</p>';
                    echo '<a href="post-comments.php?id=' . $post['id'] . '" class=" text-white font-bold py-2 px-4 rounded">Comentarios</a>';
                    echo '</div>';

                }
            } else {
                echo '<p class="text-white mt-8 text-4xl">No hay posts disponibles. Espera a que se rompa el hielo.</p>';
                
            }

            ?>
        </div>
        
    </div>
</body>
</html>