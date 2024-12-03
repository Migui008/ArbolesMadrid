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
    <?php require_once("dtbconnection.php"); ?>

    <?php
        // Obtener datos para los filtros
        $clases = $conn->query("SELECT DISTINCT p.clase FROM parques p ORDER BY p.clase;")->fetchAll(PDO::FETCH_COLUMN);
        $familias = $conn->query("SELECT DISTINCT p.familia FROM parques p ORDER BY p.familia;")->fetchAll(PDO::FETCH_COLUMN);
    ?>
</head>
<body>
  <?php require_once('header.php'); ?>

  <div id="parques_main">
    <!-- Formulario de selección de filtro -->
    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="parques_main_form">
      <select name="filter" id="parques_main_form_select">
        <?php 
        foreach ($parques_filter as $filtro => $datos) {
            echo "<option value='" . htmlspecialchars($filtro) . "' class='parques_main_form_select_option'>" . htmlspecialchars($datos['nombre']) . "</option>";
        }
        ?>
      </select>
      <input id="parques_main_form_submit" type="submit" value="Filtrar">
    </form>

    <?php
    // Si se ha enviado un filtro
    if (isset($_GET['filter']) && !empty($_GET['filter'])) {
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="parques_main_enlaces_form">
            <!-- Campo oculto para pasar el filtro seleccionado -->
            <input type="hidden" name="filter" value="<?php echo htmlspecialchars($_GET['filter']); ?>">

            <?php
            if ($parques_filter[$_GET['filter']]['tipo'] == "select") {
                ?>
                <select name="<?php echo $_GET['filter']; ?>" id="parques_main_enlaces_form_select">
                    <?php
                    if ($_GET['filter'] == "clase") {
                        foreach ($clases as $clase) {
                            echo "<option value='" . htmlspecialchars($clase) . "'>" . htmlspecialchars($clase) . "</option>";
                        }
                    } elseif ($_GET['filter'] == "familia") {
                        foreach ($familias as $familia) {
                            echo "<option value='" . htmlspecialchars($familia) . "'>" . htmlspecialchars($familia) . "</option>";
                        }
                    }
                    ?>
                </select>
                <?php
            } elseif ($parques_filter[$_GET['filter']]['tipo'] == "radio") {
                foreach ($parques_filter[$_GET['filter']]['opciones'] as $opcion) {
                    echo "<input type='radio' name='" . $_GET['filter'] . "' value='" . htmlspecialchars($opcion['rango']) . "'>" . htmlspecialchars($opcion['rango']) . "<br>";
                }
            } elseif ($parques_filter[$_GET['filter']]['tipo'] == "checkbox") {
                foreach ($parques_filter[$_GET['filter']]['opciones'] as $opcion) {
                    echo "<input type='checkbox' name='" . $_GET['filter'] . "[]' value='" . htmlspecialchars($opcion['rango']) . "'>" . htmlspecialchars($opcion['rango']) . "<br>";
                }
            }
            ?>
            <input type="submit" value="Filtrar">
        </form>
        <?php
    } else {
        // Mostrar todos los parques si no se ha seleccionado un filtro
        $parques = getAllParques();
        if (!empty($parques)) {
            foreach ($parques as $parque) {
                echo "<a href='parque.php?id_parque=" . $parque['id_parque'] . "' class='parques_main_enlaces_link'>" . htmlspecialchars($parque['nombre']) . "</a><br>";
            }
        } else {
            echo "<p>No se encontraron parques.</p>";
        }
    }
    ?>

    <?php
    // Procesar el segundo formulario con POST para mostrar los resultados filtrados
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $selectedFilter = $_POST['filter'] ?? null;
        if ($selectedFilter && isset($parques_filter[$selectedFilter])) {
            $conditionsArray = [];

            // Verificar el tipo de filtro y construir la condición de la consulta
            if ($parques_filter[$selectedFilter]['tipo'] == 'select') {
                $selectedValue = $_POST[$selectedFilter] ?? '';
                if (!empty($selectedValue)) {
                    $conditionsArray[] = $selectedValue;
                }
            } elseif ($parques_filter[$selectedFilter]['tipo'] == 'radio') {
                $selectedValue = $_POST[$selectedFilter] ?? '';
                if (!empty($selectedValue)) {
                    $conditionsArray[] = $selectedValue;
                }
            } elseif ($parques_filter[$selectedFilter]['tipo'] == 'checkbox') {
                $selectedValues = $_POST[$selectedFilter] ?? [];
                if (!empty($selectedValues)) {
                    $conditionsArray = $selectedValues;
                }
            }

            // Mostrar los resultados filtrados
            $parques = getFilteredParques($selectedFilter, $conditionsArray);
            if (!empty($parques)) {
                foreach ($parques as $parque) {
                    echo "<a href='parque.php?id_parque=" . $parque['id_parque'] . "' class='parques_main_enlaces_link'>" . htmlspecialchars($parque['nombre']) . "</a><br>";
                }
            } else {
                echo "<p>No se encontraron parques que coincidan con el filtro.</p>";
            }
        }
    }
    ?>
  </div>
</body>
</html>
