<header class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="../home/home.php"><img src="../../public/images/logo.png" alt="logo" class="h-10"></a>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="../home/home.php" class="text-gray-300 hover:text-white">Inicio</a></li>
                <?php if ($_SESSION['role'] == 'admin') :?>
                <li><a href="../admin/dashboard.php" class="text-gray-300 hover:text-white">Admin</a></li>
                <?php endif; ?>
                <?php if ($_SESSION['role'] == 'escritor') :?>
                <li><a href="../home/post.php" class="text-gray-300 hover:text-white">Post</a></li>
                <?php endif; ?>
                <li><a href="../users/profile.php" class="text-gray-300 hover:text-white">Perfil</a></li>
                <li><a href="../home/chat.php" class="text-gray-300 hover:text-white">Mensajes</a></li>
                <li>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
                        style="display: inline;">
                        <button type="submit" name="logout" class="text-red-500 hover:text-red-600">Cerrar
                            Sesión
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</header>

<?php
use utils\Auth;
require_once __DIR__ . '/../../utils/Auth.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    $logout = new Auth();
    $logout->logout();
}
?>