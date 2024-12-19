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
$user = $userModel->read($userId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $userModel->update($userId, $username, $email, $user['password'], $role);
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
    <title>Editar Usuario - SnapPost</title>
</head>
<body class="bg-gray-900 text-white">
    <?php require_once '../components/header.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-8">Editar Usuario</h1>
        <form method="POST" action="edit-user.php?id=<?php echo $userId; ?>" class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold text-white" for="username">Nombre de Usuario</label>
                <input class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="username" name="username" type="text" value="<?php echo htmlspecialchars($user['username']); ?>">
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold text-white" for="email">Correo Electrónico</label>
                <input class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="email" name="email" type="email" value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold text-white" for="role">Rol</label>
                <select class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="role" name="role">
                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="escritor" <?php echo $user['role'] == 'escritor' ? 'selected' : ''; ?>>Escritor</option>
                    <option value="lector" <?php echo $user['role'] == 'lector' ? 'selected' : ''; ?>>Lector</option>
                </select>
            </div>
            <div class="mb-6 text-center">
                <button type="submit" class="inline-block px-6 py-2 text-sm font-bold text-white bg-blue-500 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline">Actualizar Usuario</button>
            </div>
        </form>
    </div>
</body>
</html>
