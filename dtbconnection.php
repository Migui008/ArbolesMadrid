<?php
$servername = "if0_38941525_db5015852102";
$username = "dbu2608582";
$password = "JG86AkWs_Ytg@ZG";
$dbname = "dbs12922058";
global $conn;
$conn = null;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
