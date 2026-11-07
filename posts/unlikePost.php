    <?php
    session_start();

    require_once "../includes/connection.php";
    require_once "../classes/like.php";
    require_once "../classes/auth.php";

    $auth = new Auth();

    $auth->requireLogin();
    $auth->requirePost();
    $auth->validateCsrf();


    $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

    if ($postId === false || $postId === null || $postId <= 0) {
        http_response_code(400);
        exit("Invalid post ID.");
    }

    $userId = $_SESSION['user_id'];

    $like = new Like($pdo);
    $like->unlikePost($userId, $postId);

    header("Location: ../home.php");
    exit();