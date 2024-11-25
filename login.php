<?php
session_start();

ini_set('display_errors', 1); 
error_reporting(E_ALL);

echo "Inicio del archivo login.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = htmlspecialchars(trim($_POST["username"]));
    $pass = htmlspecialchars(trim($_POST["password"]));

    if (!empty($user) && !empty($pass)) {
        if ($user == "admin" && $pass == "1234") {
            $_SESSION["user"] = $user;
            $_SESSION["id"] = 1;

            echo "Login exitoso, redirigiendo...";

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
