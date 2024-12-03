<?php
require_once('functions.php');
require_once('common_data.php');
require_once('dtbconnection.php');

global $conn;

// Llenar las opciones de los selects de 'clase' y 'familia'
$arboles_filter['clase']['opciones'] = getOptionsForSelect('clase', 'arboles');
$arboles_filter['familia']['opciones'] = getOptionsForSelect('familia', 'arboles');
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
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="arboles_main_form">
            <select name="filter" id="arboles_main_form_select">
                <?php 
                foreach ($arboles_filter as $filtro => $datos) {
                    echo "<option value='".$filtro."'>".$datos['nombre']."</option>";
                }
                ?>
            </select>
            <input type="submit" value="Filtrar">
        </form>

        <?php
        if (isset($_GET['filter']) && !empty($_GET['filter'])) {
            $selected_filter = $_GET['filter'];

            echo "<form method='post' action='".$_SERVER['PHP_SELF']."' id='arboles_main_filter_form'>";
            
            if ($arboles_filter[$selected_filter]['tipo'] == "select") {
                // Imprimir las opciones de clase y familia
                echo "<select name='".$selected_filter."'>";
                foreach ($arboles_filter[$selected_filter]['opciones'] as $opcion) {
                    echo "<option value='".$opcion[$selected_filter]."'>".$opcion[$selected_filter]."</option>";
                }
                echo "</select>";
            } elseif ($arboles_filter[$selected_filter]['tipo'] == "radio") {
                foreach ($arboles_filter[$selected_filter]['opciones'] as $opcion) {
                    echo "<input type='radio' name='visitas' value='".$opcion['rango']."'> ".$opcion['rango']."<br>";
                }
            }

            echo "<input type='submit' value='Filtrar'>";
            echo "</form>";
        }

        // Lógica de filtrado y mostrar resultados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $where_clauses = [];
            
            if (isset($_POST['clase']) && !empty($_POST['clase'])) {
                $where_clauses[] = "a.clase = '" . $_POST['clase'] . "'";
            }

            if (isset($_POST['familia']) && !empty($_POST['familia'])) {
                $where_clauses[] = "a.familia = '" . $_POST['familia'] . "'";
            }

            if (isset($_POST['visitas']) && !empty($_POST['visitas'])) {
                $visitas_condition = $arboles_filter['visitas']['opciones'][array_search($_POST['visitas'], array_column($arboles_filter['visitas']['opciones'], 'rango'))]['condicion'];
                $where_clauses[] = $visitas_condition;
            }

            $sql = "SELECT a.id_arbol, a.nombre FROM arboles a";
            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(' AND ', $where_clauses);
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $arboles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($arboles) {
                foreach ($arboles as $arbol) {
                    echo "<a href='arbol.php?id_arbol=".$arbol['id_arbol']."' class='arboles_main_enlaces_link'>".$arbol['nombre']."</a><br>";
                }
            } else {
                echo "No se encontraron árboles que coincidan con el filtro.";
            }
        } else {
            // Mostrar todos los árboles por defecto al cargar la página
            $arboles = getAllArboles(); // Asegúrate de que esta función está definida en functions.php y que devuelve todos los árboles.

            if ($arboles) {
                foreach ($arboles as $arbol) {
                    echo "<a href='arbol.php?id_arbol=".$arbol['id_arbol']."' class='arboles_main_enlaces_link'>".$arbol['nombre']."</a><br>";
                }
            } else {
                echo "No se encontraron árboles.";
            }
        }
        ?>
    </div>
</body>
</html>
