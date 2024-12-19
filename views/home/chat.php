<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/Database.php';

use models\User;

$database = new \config\Database();
$dbConnection = $database->getConnection();
$userModel = new User($dbConnection);

$users = $userModel->all();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Mensajes - SnapPost</title>
</head>
<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-8">Mensajes privados</h1>
        <div class="space-y-4">
            <?php
            foreach ($users as $user) {
                if ($user['email'] != $_SESSION['email']) {
                    echo '<div class="bg-gray-800 p-4 rounded-lg shadow-lg flex items-center">';
                    echo '<img src="https://via.placeholder.com/40" alt="User Avatar" class="rounded-full w-10 h-10 mr-4">';
                    echo '<a href="user-chat.php?user_id=' . $user['id'] . '" class="text-blue-500 hover:text-blue-700">';
                    echo htmlspecialchars($user['username']);
                    echo '</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>