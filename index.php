<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Growum</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <main>

        <h1>Growum</h1>

        <p>Better content.<br>Better day.</p>

        <a href="signup.php">Create account</a>

        <a href="login.php">Log in</a>

        <p>Grow your profile, nurture your life</p>

    </main>

</body>

</html>