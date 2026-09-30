<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create account | Growum</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Create account</h1>
    <form action="createAccount.php" method="post"> <br>
        <input type="text" name="fName" placeholder="First name"> <br>
        <input type="text" name="lName" placeholder="Last name"> <br>   
        <input type="text" name="username" placeholder="Username"> <br>
        <input type="radio" name="gender" value="male"> Male
        <input type="radio" name="gender" value="female"> Female
        <input type="radio" name="gender" value="other"> Other <br>
        <input type="email" name="email" placeholder="Email"> <br>
        <input type="password" name="password" placeholder="Password"> <br>
        <button type="submit" name="createAccount">Create account</button>
    </form>

    <a href="login.php">Already have an account? Log in</a>

</body>

</html>