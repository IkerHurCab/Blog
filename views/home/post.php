<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'escritor') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../models/Post.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/Database.php';

use models\Post;
use models\User;

$database = new \config\Database();
$dbConnection = $database->getConnection();
$postModel = new Post($dbConnection);

$userModel = new User($dbConnection);
$userEmail = $_SESSION['email'];
$user = $userModel->findByEmail($userEmail);
$userId = $user['id'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Crear Post - SnapPost</title>
</head>

<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>

    <div class="container mx-auto mt-10">
        <h2 class="text-2xl mb-4 text-center">Crear nuevo post</h2>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="w-full max-w-lg mx-auto">
            <div class="mb-4">
                <label class="block text-white text-sm font-bold mb-2" for="title">
                    Título
                </label>
                <input class="w-full px-3 py-2 text-gray-700 bg-white rounded" id="title" name="title" type="text" required>
            </div>
            <div class="mb-4">
                <label class="block text-white text-sm font-bold mb-2" for="content">
                    Contenido
                </label>
                <textarea class="w-full px-3 py-2 text-gray-700 bg-white rounded" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Publicar
                </button>
            </div>
        </form>
    </div>
</body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $store = $postModel->store($title, $content, $userId);

    if ($store) {
        header('Location: home.php');
        exit();
    } else {
        $error = 'Error al crear el post';
    }
}
?>
</html>