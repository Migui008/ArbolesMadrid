<?php
// Incluir la conexión a la base de datos y funciones necesarias
require_once('dtbconnection.php');
require_once('functions.php');
require_once('common_data.php');

// Obtener la lista de parques sin filtrar al cargar la página inicialmente
$parques = getAllParques();

// Mostrar los filtros en la página
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parques</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once('header.php'); ?>

    <div id="parques_main">
        <!-- Formulario para seleccionar el tipo de filtro -->
        <form method="get" action="<?php $_SERVER['PHP_SELF']; ?>" id="parques_main_form">
            <select name="filter" id="parques_main_form_select">
                <?php 
                foreach($parques_filter as $filtro => $datos){
                    echo "<option value='".$filtro."'>".$datos['nombre']."</option>";
                }
                ?>
            </select>
            <input id="parques_main_form_submit" type="submit" value="Filtrar">
        </form>

        <?php
        // Si se ha enviado un filtro, mostrar el segundo formulario para aplicar el filtro
        if (isset($_GET['filter']) && !empty($_GET['filter'])) {
            $selectedFilter = $_GET['filter'];
            ?>
            <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" id="parques_main_enlaces_form">
                <?php
                if (isset($parques_filter[$selectedFilter])) {
                    $tipoFiltro = $parques_filter[$selectedFilter]['tipo'];

                    if ($tipoFiltro == 'select') {
                        echo "<select name='$selectedFilter' id='parques_main_enlaces_form_select'>";
                        foreach ($parques_filter[$selectedFilter] as $key => $value) {
                            if ($key !== 'tipo' && $key !== 'nombre') {
                                echo "<option value='$key'>$key</option>";
                            }
                        }
                        echo "</select>";
                    } elseif ($tipoFiltro == 'radio') {
                        foreach ($parques_filter[$selectedFilter]['opciones'] as $opcion) {
                            echo "<input type='radio' name='$selectedFilter' value='" . htmlspecialchars($opcion['rango']) . "'>" . htmlspecialchars($opcion['rango']) . "<br>";
                        }
                    } elseif ($tipoFiltro == 'checkbox') {
                        foreach ($parques_filter[$selectedFilter]['opciones'] as $opcion) {
                            echo "<input type='checkbox' name='" . $selectedFilter . "[]' value='" . htmlspecialchars($opcion['rango']) . "'>" . htmlspecialchars($opcion['rango']) . "<br>";
                        }
                    }
                }
                ?>
                <input type="submit" value="Filtrar">
            </form>
            <?php
        }

        // Filtrar los parques según los criterios seleccionados
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $query = "SELECT p.id_parque, p.nombre FROM parques p";
            $params = [];
            $condiciones = [];

            // Revisión de filtros aplicados
            foreach ($parques_filter as $filter => $datos) {
                if ($filter == 'accesibilidad') {
                    // Checkbox: construir condiciones para cada opción seleccionada
                    if (isset($_POST[$filter])) {
                        $checkboxConditions = [];
                        foreach ($parques_filter[$filter]['opciones'] as $opcion) {
                            if (in_array($opcion['rango'], $_POST[$filter])) {
                                $checkboxConditions[] = $opcion['condicion'];
                            }
                        }
                        if (!empty($checkboxConditions)) {
                            $condiciones[] = '(' . implode(' OR ', $checkboxConditions) . ')';
                        }
                    }
                } elseif ($filter == 'visitas') {
                    // Radio: tomar solo el valor seleccionado
                    if (isset($_POST[$filter])) {
                        foreach ($parques_filter[$filter]['opciones'] as $opcion) {
                            if ($opcion['rango'] === $_POST[$filter]) {
                                $condiciones[] = $opcion['condicion'];
                                break;
                            }
                        }
                    }
                } else {
                    // Otros filtros (select)
                    if (isset($_POST[$filter]) && !empty($_POST[$filter])) {
                        $selectedValue = $_POST[$filter];
                        $condiciones[] = "p.$filter = :$filter";
                        $params[":$filter"] = $selectedValue;
                    }
                }
            }

            // Agregar las condiciones a la consulta si existen
            if (!empty($condiciones)) {
                $query .= " WHERE " . implode(' AND ', $condiciones);
            }

            try {
                $stmt = $conn->prepare($query);
                $stmt->execute($params);

                $parques = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }

            if (empty($parques)) {
                echo "No se encontraron parques que coincidan con el filtro.";
            } else {
                foreach ($parques as $parque) {
                    echo "<a href='parque.php?id_parque=" . $parque['id_parque'] . "' class='parques_main_enlaces_link'>" . htmlspecialchars($parque['nombre']) . "</a><br>";
                }
            }
        } else {
            // Mostrar todos los parques si no se ha enviado un filtro
            foreach ($parques as $parque) {
                echo "<a href='parque.php?id_parque=" . $parque['id_parque'] . "' class='parques_main_enlaces_link'>" . htmlspecialchars($parque['nombre']) . "</a><br>";
            }
        }
        ?>
    </div>
</body>
</html>
