<?php
session_start();
if (!isset($_SESSION['user'])) {
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
$comment = $commentModel->read($commentId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];
    $commentModel->update($commentId, $content);
    header('Location: dashboard.php');
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
    <title>Editar Comentario - SnapPost</title>
</head>
<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-8">Editar Comentario</h1>
        <form method="POST" action="edit-comment.php?id=<?php echo $commentId; ?>" class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold text-white" for="content">Contenido</label>
                <textarea class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="content" name="content" rows="4"><?php echo htmlspecialchars($comment['content']); ?></textarea>
            </div>
            <div class="mb-6 text-center">
                <button type="submit" class="inline-block px-6 py-2 text-sm font-bold text-white bg-blue-500 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline">Actualizar Comentario</button>
            </div>
        </form>
    </div>
</body>
</html>
