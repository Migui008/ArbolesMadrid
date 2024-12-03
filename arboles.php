<?php
require_once('dtbconnection.php'); // Asegúrate de que la conexión a la base de datos esté configurada correctamente
require_once('common_data.php'); // Cargar el array de filtros

// Verifica si se ha recibido un filtro por GET
$selectedFilter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Mostrar el formulario correspondiente
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="image/icono.png">
    <title>Arboles</title>
</head>
<body>
    <?php require_once('header.php'); ?>
    <div id="arboles_main">
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="arboles_main_form">
            <select name="filter" id="arboles_main_form_select">
                <?php 
                foreach ($arboles_filter as $filtro => $datos) {
                    echo "<option value='$filtro'" . ($selectedFilter == $filtro ? ' selected' : '') . ">$datos[nombre]</option>";
                }
                ?>
            </select>
            <input id="arboles_main_form_submit" type="submit" value="Filtrar">
        </form>

        <?php if ($selectedFilter && isset($arboles_filter[$selectedFilter])): ?>
            <!-- Mostrar el formulario de filtrado según el tipo -->
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="arboles_main_enlaces_form">
                <?php
                if ($arboles_filter[$selectedFilter]['tipo'] == "select") {
                    // Mostrar opciones de tipo select
                    $query = "SELECT DISTINCT a.$selectedFilter FROM arboles a ORDER BY a.$selectedFilter;";
                    $stmt = $conn->query($query);
                    $options = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    echo "<select name='$selectedFilter' id='arboles_main_enlaces_form_select'>";
                    foreach ($options as $option) {
                        echo "<option value='$option'>$option</option>";
                    }
                    echo "</select>";
                } elseif ($arboles_filter[$selectedFilter]['tipo'] == "radio") {
                    // Mostrar opciones de tipo radio
                    foreach ($arboles_filter[$selectedFilter]['opciones'] as $opcion) {
                        echo "<input type='radio' name='$selectedFilter' value='" . htmlspecialchars($opcion['rango']) . "'> " . htmlspecialchars($opcion['rango']) . "<br>";
                    }
                }
                ?>
                <input type="submit" value="Filtrar">
            </form>
        <?php endif; ?>

        <?php
        // Procesar el filtrado si se ha enviado el formulario por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $whereClauses = [];
            $params = [];
            foreach ($_POST as $key => $value) {
                if (!empty($value)) {
                    if ($arboles_filter[$key]['tipo'] == 'radio') {
                        // Buscar el rango seleccionado y construir la condición correspondiente
                        foreach ($arboles_filter[$key]['opciones'] as $opcion) {
                            if ($opcion['rango'] == $value) {
                                $whereClauses[] = $opcion['condicion'];
                                break;
                            }
                        }
                    } elseif ($arboles_filter[$key]['tipo'] == 'select') {
                        $whereClauses[] = "a.$key = :value";
                        $params[":value"] = $value;
                    }
                }
            }

            // Construir la consulta con las condiciones WHERE
            $query = "SELECT a.id_arbol, a.nombre FROM arboles a";
            if (!empty($whereClauses)) {
                $query .= " WHERE " . implode(" AND ", $whereClauses);
            }

            $stmt = $conn->prepare($query);

            // Vincular los parámetros si hay valores en la consulta
            foreach ($params as $param => $val) {
                $stmt->bindParam($param, $val, PDO::PARAM_STR);
            }

            $stmt->execute();
            $arboles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar los enlaces de los árboles filtrados
            foreach ($arboles as $arbol) {
                echo "<a href='arbol.php?id_arbol=" . $arbol['id_arbol'] . "' class='arboles_main_enlaces_link'>" . htmlspecialchars($arbol['nombre']) . "</a><br>";
            }
        }
        ?>
    </div>
</body>
</html>
