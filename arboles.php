<?php
// Incluir la conexión a la base de datos y funciones necesarias
require_once('dtbconnection.php');
require_once('functions.php');
require_once('common_data.php');

// Obtener la lista de árboles sin filtrar al cargar la página inicialmente
$arboles = getAllArboles();

// Mostrar los filtros en la página
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árboles</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once('header.php'); ?>

    <div id="arboles_main">
        <!-- Formulario para seleccionar el tipo de filtro -->
        <form method="get" action="<?php $_SERVER['PHP_SELF']; ?>" id="arboles_main_form">
            <select name="filter" id="arboles_main_form_select">
                <?php 
                foreach($arboles_filter as $filtro => $datos){
                    echo "<option value='".$filtro."'>".$datos['nombre']."</option>";
                }
                ?>
            </select>
            <input id="arboles_main_form_submit" type="submit" value="Filtrar">
        </form>

        <?php
        // Si se ha enviado un filtro, mostrar el segundo formulario para aplicar el filtro
        if (isset($_GET['filter']) && !empty($_GET['filter'])) {
            $selectedFilter = $_GET['filter'];
            ?>
            <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" id="arboles_main_enlaces_form">
                <?php
                if (isset($arboles_filter[$selectedFilter])) {
                    $tipoFiltro = $arboles_filter[$selectedFilter]['tipo'];

                    if ($tipoFiltro == 'select') {
                        echo "<select name='$selectedFilter' id='arboles_main_enlaces_form_select'>";
                        foreach ($arboles_filter[$selectedFilter] as $key => $value) {
                            if ($key !== 'tipo' && $key !== 'nombre') {
                                echo "<option value='$key'>$key</option>";
                            }
                        }
                        echo "</select>";
                    } elseif ($tipoFiltro == 'radio') {
                        foreach ($arboles_filter[$selectedFilter]['opciones'] as $opcion) {
                            echo "<input type='radio' name='$selectedFilter' value='" . htmlspecialchars($opcion['rango']) . "'>" . htmlspecialchars($opcion['rango']) . "<br>";
                        }
                    } elseif ($tipoFiltro == 'checkbox') {
                        foreach ($arboles_filter[$selectedFilter]['opciones'] as $opcion) {
                            echo "<input type='checkbox' name='" . $selectedFilter . "[]' value='" . htmlspecialchars($opcion['rango']) . "'>" . htmlspecialchars($opcion['rango']) . "<br>";
                        }
                    }
                }
                ?>
                <input type="submit" value="Filtrar">
            </form>
            <?php
        }

        // Filtrar los árboles según los criterios seleccionados
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $query = "SELECT a.id_arbol, a.nombre FROM arboles a";
            $params = [];
            $condiciones = [];

            // Revisión de filtros aplicados
            foreach ($arboles_filter as $filter => $datos) {
                if ($filter == 'visitas') {
                    // Radio: tomar solo el valor seleccionado
                    if (isset($_POST[$filter])) {
                        foreach ($arboles_filter[$filter]['opciones'] as $opcion) {
                            if ($opcion['rango'] === $_POST[$filter]) {
                                $condiciones[] = $opcion['condicion'];
                                break;
                            }
                        }
                    }
                } else {
                    // Otros filtros (select o checkbox)
                    if (isset($_POST[$filter]) && !empty($_POST[$filter])) {
                        if ($datos['tipo'] == 'checkbox') {
                            $checkboxConditions = [];
                            foreach ($arboles_filter[$filter]['opciones'] as $opcion) {
                                if (in_array($opcion['rango'], $_POST[$filter])) {
                                    $checkboxConditions[] = $opcion['condicion'];
                                }
                            }
                            if (!empty($checkboxConditions)) {
                                $condiciones[] = '(' . implode(' OR ', $checkboxConditions) . ')';
                            }
                        } else {
                            $selectedValue = $_POST[$filter];
                            $condiciones[] = "a.$filter = :$filter";
                            $params[":$filter"] = $selectedValue;
                        }
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

                $arboles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }

            if (empty($arboles)) {
                echo "No se encontraron árboles que coincidan con el filtro.";
            } else {
                foreach ($arboles as $arbol) {
                    echo "<a href='arbol.php?id_arbol=" . $arbol['id_arbol'] . "' class='arboles_main_enlaces_link'>" . htmlspecialchars($arbol['nombre']) . "</a><br>";
                }
            }
        } else {
            // Mostrar todos los árboles si no se ha enviado un filtro
            foreach ($arboles as $arbol) {
                echo "<a href='arbol.php?id_arbol=" . $arbol['id_arbol'] . "' class='arboles_main_enlaces_link'>" . htmlspecialchars($arbol['nombre']) . "</a><br>";
            }
        }
        ?>
    </div>
</body>
</html>
