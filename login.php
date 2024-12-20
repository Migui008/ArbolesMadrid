<?php
require_once("functions.php");
session_start();

ini_set('display_errors', 1); 
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = htmlspecialchars(trim($_POST["username"]));
    $pass = htmlspecialchars(trim($_POST["password"]));

    if (!empty($user) && !empty($pass)) {
        $login = loginVerification($user, $pass);

        if ($login) {
            $_SESSION["user"] = $login["username"];
            $_SESSION["id"] = $login["user_id"];

            header("Location: index.php");
            exit;
        } else {
            echo "Credenciales incorrectas";
        }
    } else {
        echo "Por favor, ingrese usuario y contraseña";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="image/icono.png">
</head>
<body>
    <div id="login_main">
        <form method="post" action="login.php" id="login_main_form">
            <input type="text" name="username" class="login_main_form_input" placeholder="Username">
            <input type="password" name="password" class="login_main_form_input" placeholder="Password">
            <button type="submit" id="login_main_form_submit">Login</button>
        </form>
    </div>
</body>
</html>
