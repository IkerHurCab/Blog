<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/Database.php';

use models\User;

$database = new \config\Database();
$dbConnection = $database->getConnection();
$userModel = new User($dbConnection);

$userId = $_GET['id'] ?? null;

if ($userId) {
    $userModel->delete($userId);
    header('Location: dashboard.php');
    exit();
} else {
    echo '<p class="text-red-500">ID del usuario no proporcionado</p>';
}
?>
