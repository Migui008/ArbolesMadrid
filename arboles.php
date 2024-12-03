<?php
require_once("dtbconnection.php"); // Conexión a la base de datos
require_once("functions.php");    // Funciones adicionales
require_once("common_data.php");  // Array de filtros

$claseQuery = "SELECT DISTINCT a.clase FROM arboles a ORDER BY a.clase;";
$familiaQuery = "SELECT DISTINCT a.familia FROM arboles a ORDER BY a.familia;";

$clases = $conn->query($claseQuery)->fetchAll(PDO::FETCH_COLUMN);
$familias = $conn->query($familiaQuery)->fetchAll(PDO::FETCH_COLUMN);

$filterKey = $_GET['filter'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="image/icono.png">
    <title>Árboles</title>
</head>
<body>
    <?php require_once('header.php'); ?>

    <div id="arboles_main">
        <!-- Primer formulario (GET) -->
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="arboles_main_form">
            <label for="filter">Seleccione un filtro:</label>
            <select name="filter" id="arboles_main_form_select">
                <?php 
                foreach ($arboles_filter as $filtro => $datos) {
                    $selected = ($filtro === $filterKey) ? "selected" : "";
                    echo "<option value='$filtro' $selected>$filtro</option>";
                }
                ?>
            </select>
            <input id="arboles_main_form_submit" type="submit" value="Filtrar">
        </form>

        <!-- Segundo formulario (POST) -->
        <?php if ($filterKey && isset($arboles_filter[$filterKey])): ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="arboles_main_enlaces_form">
                <h3>Opciones para el filtro: <?php echo htmlspecialchars($filterKey); ?></h3>

                <?php
                $filter = $arboles_filter[$filterKey];
                if ($filter['tipo'] === "select") {
                    echo "<select name='$filterKey'>";
                    $options = ($filterKey === "clase") ? $clases : $familias;
                    foreach ($options as $option) {
                        echo "<option value='$option'>$option</option>";
                    }
                    echo "</select>";
                } elseif ($filter['tipo'] === "radio") {
                    foreach ($filter['opciones'] as $opcion) {
                        echo "<input type='radio' name='$filterKey' value='{$opcion['condicion']}'> {$opcion['rango']}<br>";
                    }
                } elseif ($filter['tipo'] === "checkbox") {
                    foreach ($filter['opciones'] as $opcion) {
                        echo "<input type='checkbox' name='{$filterKey}[]' value='{$opcion['condicion']}'> {$opcion['rango']}<br>";
                    }
                }
                ?>
                <input type="submit" value="Aplicar filtro">
            </form>
        <?php endif; ?>

        <!-- Resultados después del envío del formulario (POST) -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $filterKey) {
            $whereClauses = [];
            if ($arboles_filter[$filterKey]['tipo'] === "select") {
                $selectedValue = $_POST[$filterKey];
                $whereClauses[] = "a.$filterKey = '$selectedValue'";
            } elseif ($arboles_filter[$filterKey]['tipo'] === "radio") {
                $selectedCondition = $_POST[$filterKey];
                $whereClauses[] = $selectedCondition;
            } elseif ($arboles_filter[$filterKey]['tipo'] === "checkbox" && isset($_POST[$filterKey])) {
                $selectedConditions = $_POST[$filterKey];
                $whereClauses[] = "(" . implode(" OR ", $selectedConditions) . ")";
            }

            // Construir consulta y ejecutar
            if (!empty($whereClauses)) {
                $query = "SELECT a.id_arbol, a.nombre FROM arboles a WHERE " . implode(" AND ", $whereClauses);
                $result = $conn->query($query);

                echo "<div id='arboles_main_enlaces'>";
                foreach ($result as $row) {
                    echo "<a href='arbol.php?id_arbol={$row['id_arbol']}' class='arboles_main_enlaces_link'>{$row['nombre']}</a><br>";
                }
                echo "</div>";
            } else {
                echo "<p>No se han encontrado resultados para los filtros seleccionados.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
