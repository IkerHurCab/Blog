<?php
session_start();
header('Content-Type: application/json');

use models\Post;
use models\User;

ini_set('display_errors', 0);
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/../../models/Post.php';
    require_once __DIR__ . '/../../models/User.php';
    require_once __DIR__ . '/../../config/Database.php';

    $database = new \config\Database();
    $dbConnection = $database->getConnection();
    $postModel = new Post($dbConnection);
    $userModel = new User($dbConnection);

    if (!isset($_SESSION['user'])) {
        header('Location: ../auth/login.php');
    }

    $postId = $_POST['id'] ?? null;

    if ($postId) {
        $post = $postModel->read($postId);
        if (!$post) {
            echo json_encode(['success' => false, 'message' => 'El post no existe']);
            exit();
        }

        $userEmail = $_SESSION['email'];
        $user = $userModel->findByEmail($userEmail);

        $delete = $postModel->delete($postId);
        if ($delete) {
            header('Location: ../users/profile.php');
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el post']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del post no proporcionado']);
    }
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}
?>
