<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/Message.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/Database.php';

use models\Message;
use models\User;

$database = new \config\Database();
$dbConnection = $database->getConnection();
$messageModel = new Message($dbConnection);
$userModel = new User($dbConnection);

$user = $userModel->findByEmail($_SESSION['email']);
$userId = $user['id'];
$chatUserId = $_GET['user_id'] ?? null;

if (!$chatUserId) {
    header('Location: chat.php');
    exit();
}

$chatUser = $userModel->read($chatUserId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageContent = $_POST['message'];
    if ($messageModel->store($messageContent, $userId, $chatUserId)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'fetch') {
    $messages = $messageModel->getConversation($userId, $chatUserId);
    echo json_encode($messages);
    exit();
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
    <title><?php echo htmlspecialchars($chatUser['username']); ?> - SnapPost</title>
</head>
<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="container mx-auto mt-10">
        <a href="chat.php" class="text-blue-500 hover:text-blue-700 mb-4 block">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <div class="flex items-center mb-4">
            <img src="https://via.placeholder.com/40" alt="User Avatar" class="rounded-full w-10 h-10 mr-4">
            <h2 class="text-2xl font-bold"><?php echo htmlspecialchars($chatUser['username']); ?></h2>
        </div>

        <div id="chatContainer" class="bg-gray-800 p-4 rounded-lg shadow-lg h-96 overflow-y-scroll"></div>
        <form id="messageForm" method="POST" data-chat-user-id="<?php echo $chatUserId; ?>" class="mt-4">
            <div class="flex">
                <input type="text" name="message" id="messageInput" placeholder="Escríbe algo" class="w-full p-2 rounded-lg bg-gray-800 text-white">
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-paper-plane"></i>
                </button>
            
            </div>
        </form>
    </div>
    <script>
        const userId = <?php echo json_encode($userId); ?>;
    </script>
    <!-- Que conste que esto en Laravel sería muchísimo más sencillo usando vistas Livewire -->
    <script src="../../public/js/chat.js"></script>
</body>
</html>
