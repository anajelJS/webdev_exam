<?php
session_start();

$banMessage = $_SESSION['banMessage'] ?? null;

unset($_SESSION['banMessage']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Log in | Growum</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Log in</h1>

    <form action="loginAccount.php" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" name="loginAccount" value="Log in">
    </form>

<?php if ($banMessage !== null): ?>

<div class="ban-message">

    <h3>Your account has been suspended.</h3>

    <p>
        <strong>Reason for suspension:</strong>
    </p>

    <p>
        <?= htmlspecialchars($banMessage, ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        If you believe this was a mistake,
        please contact Growum support.
    </p>

</div>

<?php endif; ?>

    <a href="signup.php">Don't have an account? Create account</a>

</body>

</html>