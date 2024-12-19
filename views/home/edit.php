<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/Post.php';
require_once __DIR__ . '/../../controllers/PostController.php';
require_once __DIR__ . '/../../config/Database.php';

use models\Post;
use controllers\PostController;

$database = new \config\Database();
$dbConnection = $database->getConnection();

$postModel = new Post($dbConnection);
$postController = new PostController($postModel);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $update = $postModel->update($id, $title, $content);
    if ($update) {
        header('Location: ../users/profile.php');
        exit();
    } else {
        $error = 'Error al actualizar el post';
    }
} else {
    $id = $_GET['id'];
    $post = $postModel->read($id);
    if (!$post) {
        header('Location: ../users/profile.php');
        exit();
    }
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
    <title>Editar Post - SnapPost</title>
</head>
<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="container mx-auto mt-10">
        <h3 class="pt-4 text-2xl mb-4 text-center">Editar Post</h3>
        <div class="flex justify-center items-center px-6">
            <div class="w-full lg:w-1/3 bg-gray-800 p-6 rounded-lg shadow-lg">
                <?php if (isset($error)): ?>
                    <p class="text-red-500 text-center"><?php echo $error; ?></p>
                <?php endif; ?>
                <form class="px-8 pt-6 pb-8 mb-4" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-bold text-white" for="title">
                            Título
                        </label>
                        <input
                            class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            id="title" name="title" type="text" value="<?php echo htmlspecialchars($post['title']); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-bold text-white" for="content">
                            Contenido
                        </label>
                        <textarea
                            class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            id="content" name="content"><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                    <div class="mb-6 text-center">
                        <button type="submit"
                            class="inline-block px-6 py-2 text-sm font-bold text-white bg-blue-500 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                            Actualizar Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
