<div id="sidebar">
  <div id="sidebar_mostVisited">
    <p id="sidebar_mostVisited_title">Artículos más visitados</p>
    <?php
    require_once('dtbconnection.php');

    global $conn;

    try {
    $sql = "
        SELECT 'arbol' AS tipo, id_arbol AS id, nombre, visitas 
        FROM arboles 
        UNION ALL
        SELECT 'parque' AS tipo, id_parque AS id, nombre, visitas 
        FROM parques 
        ORDER BY visitas DESC 
        LIMIT 5;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        foreach ($result as $row) {
            if ($row['tipo'] === 'arbol') {
                echo "<a href='arbol.php?id_arbol=" . $row['id'] . "' class='sidebar_mostVisited_link'>Arbol: " . htmlspecialchars($row['nombre']) . " - Visitas: " . $row['visitas'] . "</a><br>";
            } elseif ($row['tipo'] === 'parque') {
                echo "<a href='parque.php?id_parque=" . $row['id'] . "' class='sidebar_mostVisited_link'>Parque: " . htmlspecialchars($row['nombre']) . " - Visitas: " . $row['visitas'] . "</a><br>";
            }
        }
    } else {
        echo "No se encontraron resultados.";
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
  </div>
</div>
