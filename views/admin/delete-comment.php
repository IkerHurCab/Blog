<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/Comment.php';
require_once __DIR__ . '/../../config/Database.php';

use models\Comment;

$database = new \config\Database();
$dbConnection = $database->getConnection();
$commentModel = new Comment($dbConnection);

$commentId = $_GET['id'] ?? null;

if ($commentId) {
    $commentModel->destroy($commentId);
    header('Location: dashboard.php');
    exit();
} else {
    echo '<p class="text-red-500">ID del comentario no proporcionado</p>';
}
?>
