<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="image/icono.png">
    <?php require_once('functions.php'); ?>
    <?php require_once('common_data.php'); ?>
    <title>Árboles</title>
</head>
<body>
    <?php require_once('header.php'); ?>
    <div id="arboles_main">
        <form method="get" action="<?php $_SERVER['PHP_SELF']; ?>" id="arboles_main_form">
            <select name="filter" id="arboles_main_form_select">
                <?php
                foreach ($arboles_filter as $filtro => $datos) {
                    echo "<option value='" . $filtro . "' class='arboles_main_form_select_option'>" . htmlspecialchars($datos['nombre']) . "</option>";
                }
                ?>
            </select>
            <input id="arboles_main_form_submit" type="submit" value="Filtrar">
        </form>
        <div id="arboles_main_enlaces">
            <?php
            // Llama a la función para obtener todos los árboles si no hay un filtro activo
            if (!isset($_GET['filter']) || empty($_GET['filter'])) {
                $arboles = getAllArboles(); // Obtiene todos los árboles de la base de datos
            } else {
                // Aquí se hace la llamada a la base de datos usando la condición del filtro
                if (isset($_POST[$_GET['filter']])) {
                    $selectedFilter = $_GET['filter'];
                    $condition = $arboles_filter[$selectedFilter]['opciones'][$_POST[$selectedFilter]]['condicion'];

                    // Ejecuta la consulta con el filtro aplicado
                    $arboles = getFilteredArboles($condition);
                }
            }

            // Muestra los enlaces de los árboles
            foreach ($arboles as $arbol) {
                echo "<a href='arbol.php?id_arbol=" . $arbol['id_arbol'] . "' class='arboles_main_enlaces_link'>" . htmlspecialchars($arbol['nombre']) . "</a><br>";
            }
            ?>
        </div>
    </div>
</body>
</html>
