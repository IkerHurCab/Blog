<?php
session_start();

use models\Comment;
use models\User;

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/Comment.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/Database.php';

$database = new \config\Database();
$dbConnection = $database->getConnection();
$commentModel = new Comment($dbConnection);
$userModel = new User($dbConnection);

$commentId = $_GET['id'] ?? null;
$postId = $_GET['postId'] ?? null;

if ($commentId && $postId) {
    $comment = $commentModel->findByPost($postId);
    $author = $userModel->findByEmail($_SESSION['email']);

    if ($comment && $author && $comment[0]['user_id'] == $author['id']) {
        $delete = $commentModel->destroy($commentId);
        if ($delete) {
            header("Location: post-comments.php?id=$postId");
            exit();
        } else {
            echo '<p class="text-red-500">Error al eliminar el comentario</p>';
        }
    } else {
        echo '<p class="text-red-500">No tienes permiso para eliminar este comentario</p>';
    }
} else {
    echo '<p class="text-red-500">ID del comentario o del post no proporcionado</p>';
}
?>
