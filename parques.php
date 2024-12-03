<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="image/icono.png">
    <?php require_once('functions.php'); ?>
    <?php require_once('common_data.php'); ?>
    <title>Parques</title>
</head>
<body>
    <?php require_once('header.php'); ?>
    <div id="parques_main">
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="parques_main_form">
            <select name="filter" id="parques_main_form_select">
                <?php
                foreach ($parques_filter as $filtro => $datos) {
                    echo "<option value='" . $filtro . "' class='parques_main_form_select_option'>" . htmlspecialchars($datos['nombre'] ?? ucfirst($filtro)) . "</option>";
                }
                ?>
            </select>
            <input id="parques_main_form_submit" type="submit" value="Filtrar">
        </form>

        <div id="parques_main_enlaces">
            <?php
            if (isset($_GET['filter']) && !empty($_GET['filter'])) {
                // Mostrar el segundo formulario con los filtros correspondientes
                $selectedFilter = $_GET['filter'];
                if ($parques_filter[$selectedFilter]['tipo'] == "select") {
                    // Mostrar un <select> para el filtro
                    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "' id='parques_main_enlaces_form'>";
                    echo "<label for='" . $selectedFilter . "'>" . htmlspecialchars($parques_filter[$selectedFilter]['nombre']) . ":</label>";
                    echo "<select name='" . $selectedFilter . "' id='parques_main_enlaces_form_select'>";
                    foreach ($parques_filter[$selectedFilter]['opciones'] as $opcion) {
                        echo "<option value='" . $opcion['rango'] . "'>" . htmlspecialchars($opcion['rango']) . "</option>";
                    }
                    echo "</select>";
                    echo "<input type='submit' value='Filtrar'>";
                    echo "</form>";
                } elseif ($parques_filter[$selectedFilter]['tipo'] == "radio") {
                    // Mostrar botones de opción (radio) para el filtro
                    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "' id='parques_main_enlaces_form'>";
                    echo "<label>" . htmlspecialchars($parques_filter[$selectedFilter]['nombre']) . ":</label><br>";
                    foreach ($parques_filter[$selectedFilter]['opciones'] as $opcion) {
                        echo "<input type='radio' name='" . $selectedFilter . "' value='" . $opcion['rango'] . "'>" . htmlspecialchars($opcion['rango']) . "<br>";
                    }
                    echo "<input type='submit' value='Filtrar'>";
                    echo "</form>";
                } elseif ($parques_filter[$selectedFilter]['tipo'] == "checkbox") {
                    // Mostrar casillas de verificación (checkbox) para el filtro
                    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "' id='parques_main_enlaces_form'>";
                    echo "<label>" . htmlspecialchars($parques_filter[$selectedFilter]['nombre']) . ":</label><br>";
                    foreach ($parques_filter[$selectedFilter]['opciones'] as $opcion) {
                        echo "<input type='checkbox' name='" . $selectedFilter . "[]' value='" . $opcion['rango'] . "'>" . htmlspecialchars($opcion['rango']) . "<br>";
                    }
                    echo "<input type='submit' value='Filtrar'>";
                    echo "</form>";
                }
            }

            // Si se ha enviado el formulario con un filtro, mostrar los resultados
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['filter']) && !empty($_GET['filter'])) {
                $selectedFilter = $_GET['filter'];
                $condition = "";

                if ($selectedFilter == "accesibilidad" && isset($_POST[$selectedFilter])) {
                    $conditionsArray = [];
                    foreach ($_POST[$selectedFilter] as $selectedOption) {
                        foreach ($parques_filter[$selectedFilter]['opciones'] as $opcion) {
                            if ($opcion['rango'] == $selectedOption) {
                                $conditionsArray[] = $opcion['condicion'];
                            }
                        }
                    }
                    $condition = implode(" OR ", $conditionsArray);
                } elseif (isset($_POST[$selectedFilter])) {
                    foreach ($parques_filter[$selectedFilter]['opciones'] as $opcion) {
                        if ($opcion['rango'] == $_POST[$selectedFilter]) {
                            $condition = $opcion['condicion'];
                            break;
                        }
                    }
                }

                if (!empty($condition)) {
                    $parques = getFilteredParques($condition);
                    foreach ($parques as $parque) {
                        echo "<a href='parque.php?id_parque=" . $parque['id_parque'] . "' class='parques_main_enlaces_link'>" . htmlspecialchars($parque['nombre']) . "</a><br>";
                    }
                }
            } else {
                // Mostrar todos los parques si no hay filtro
                $parques = getAllParques();
                foreach ($parques as $parque) {
                    echo "<a href='parque.php?id_parque=" . $parque['id_parque'] . "' class='parques_main_enlaces_link'>" . htmlspecialchars($parque['nombre']) . "</a><br>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
