<?php
$servername = "db5015852102.hosting-data.io";
$username = "dbu2608582";
$password = "JG86AkWs_Ytg@ZG";
$dbname = "dbs12922058";
global $conn;
$conn = null;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Aquí se realizan las consultas y otras operaciones con la base de datos

    // No es necesario llamar a close() en PDO, simplemente dejamos que el objeto $conn se elimine cuando ya no se necesite.
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
