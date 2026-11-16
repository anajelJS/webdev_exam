<?php

class Auth
{
    public function requireLogin(string $loginPath = '../login.php'): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . $loginPath);
            exit();
        }
    }
    public function validateCsrf(): void
{
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !is_string($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit("Invalid CSRF token.");
    }
}
public function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit("Method not allowed.");
    }
}

public function requireAdmin(PDO $pdo): void
{
    $this->requireLogin();

    $query = $pdo->prepare(
        "SELECT role.name
         FROM userAccount
         INNER JOIN role
             ON userAccount.role_id = role.id
         WHERE userAccount.id = ?"
    );

    $query->execute([(int) $_SESSION['user_id']]);

    $role = $query->fetchColumn();

    if ($role !== 'admin') {
        http_response_code(403);
        exit("Access denied. Administrators only.");
    }
}

}