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
        <form method="get" action="<?php $_SERVER['PHP_SELF']; ?>" id="parques_main_form">
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
            // Si no se ha seleccionado un filtro, muestra todos los parques
            if (!isset($_GET['filter']) || empty($_GET['filter'])) {
                $parques = getAllParques(); // Llama a la función para obtener todos los parques
            } else {
                // Si se ha seleccionado un filtro, filtra los resultados
                if (isset($_POST[$_GET['filter']])) {
                    $selectedFilter = $_GET['filter'];
                    $condition = $parques_filter[$selectedFilter]['opciones'][$_POST[$selectedFilter]]['condicion'];

                    // Llama a la función para obtener los parques filtrados
                    $parques = getFilteredParques($condition);
                }
            }

            // Muestra los enlaces de los parques
            foreach ($parques as $parque) {
                echo "<a href='parque.php?id_parque=" . $parque['id_parque'] . "' class='parques_main_enlaces_link'>" . htmlspecialchars($parque['nombre']) . "</a><br>";
            }
            ?>
        </div>
    </div>
</body>
</html>
