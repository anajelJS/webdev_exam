<?php
session_start();

require_once '../includes/connection.php';
require_once '../classes/auth.php';

$auth = new Auth();
$auth->requireAdmin($pdo);
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
define('GROWUM_ADMIN', true);

$page = $_GET['page'] ?? 'dashboard';
?>

<h1>Growum Admin Dashboard 🌱</h1>

<nav>
    <a href="index.php?page=dashboard">Overview</a> |
    <a href="index.php?page=users">Manage Users</a> |
    <a href="../home.php">Back to Feed</a>
    <a href="index.php?page=posts">Manage Posts</a> |
    <a href="index.php?page=comments">Manage Comments</a> |
</nav>

<hr>

<?php
switch ($page) {
    case 'dashboard':
        include __DIR__ . '/dashboard.php';
        break;

    case 'users':
        include __DIR__ . '/users.php';
        break;
    case 'posts':
        include __DIR__ . '/posts.php';
        break;
    case 'editPost':
        include __DIR__ . '/posts.php';
        include __DIR__ . '/editPost.php';
        break;
    case 'comments':
        include __DIR__ . '/comments.php';
        break;
    case 'editComment':
        include __DIR__ . '/comments.php';
        include __DIR__ . '/editComment.php';
        break;
    default:
        http_response_code(404);
        echo "<p>Page not found.</p>";
        break;
        case 'banUser':
            include __DIR__ . '/users.php';
            include __DIR__ . '/banUser.php';
            break;
    
        
}
?>

