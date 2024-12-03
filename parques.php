<?php
require_once('functions.php');
require_once('common_data.php');
require_once('dtbconnection.php');

global $conn;

$parques_filter = [
    "accesibilidad" => [
        "tipo" => "checkbox",
        "nombre" => "Accesibilidad",
        "opciones" => [
            [
                "rango" => "Bus",
                "condicion" => "p.transporte_bus IS NOT NULL"
            ],
            [
                "rango" => "Metro",
                "condicion" => "p.transporte_metro IS NOT NULL"
            ],
            [
                "rango" => "RENFE",
                "condicion" => "p.transporte_renfe IS NOT NULL"
            ]
        ]
    ],
    "visitas" => [
        "tipo" => "radio",
        "nombre" => "Visitas",
        "opciones" => [
            [
                "rango" => "Menos de 50",
                "condicion" => "p.visitas < 50"
            ],
            [
                "rango" => "Entre 50 y 400",
                "condicion" => "p.visitas >= 50 AND p.visitas <= 400"
            ],
            [
                "rango" => "Más de 400",
                "condicion" => "p.visitas > 400"
            ]
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parques</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="image/icono.png">
</head>
<body>
    <?php require_once('header.php'); ?>
    <div id="parques_main">
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="parques_main_form">
            <select name="filter" id="parques_main_form_select">
                <?php 
                foreach ($parques_filter as $filtro => $datos) {
                    echo "<option value='".$filtro."'>".$datos['nombre']."</option>";
                }
                ?>
            </select>
            <input type="submit" value="Filtrar">
        </form>

        <?php
        if (isset($_GET['filter']) && !empty($_GET['filter'])) {
            $selected_filter = $_GET['filter'];

            echo "<form method='post' action='".$_SERVER['PHP_SELF']."' id='parques_main_filter_form'>";
            
            if ($parques_filter[$selected_filter]['tipo'] == "checkbox") {
                foreach ($parques_filter[$selected_filter]['opciones'] as $opcion) {
                    echo "<input type='checkbox' name='accesibilidad[]' value='".$opcion['rango']."'> ".$opcion['rango']."<br>";
                }
            } elseif ($parques_filter[$selected_filter]['tipo'] == "radio") {
                foreach ($parques_filter[$selected_filter]['opciones'] as $opcion) {
                    echo "<input type='radio' name='visitas' value='".$opcion['rango']."'> ".$opcion['rango']."<br>";
                }
            }

            echo "<input type='submit' value='Filtrar'>";
            echo "</form>";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $where_clauses = [];
            
            if (isset($_POST['accesibilidad']) && !empty($_POST['accesibilidad'])) {
                $checkbox_conditions = [];
                foreach ($parques_filter['accesibilidad']['opciones'] as $opcion) {
                    if (in_array($opcion['rango'], $_POST['accesibilidad'])) {
                        $checkbox_conditions[] = $opcion['condicion'];
                    }
                }
                if (!empty($checkbox_conditions)) {
                    $where_clauses[] = "(" . implode(' AND ', $checkbox_conditions) . ")";
                }
            }

            if (isset($_POST['visitas']) && !empty($_POST['visitas'])) {
                $visitas_condition = $parques_filter['visitas']['opciones'][array_search($_POST['visitas'], array_column($parques_filter['visitas']['opciones'], 'rango'))]['condicion'];
                $where_clauses[] = $visitas_condition;
            }

            $sql = "SELECT p.id_parque, p.nombre FROM parques p";
            if (!empty($where_clauses)) {
                $sql .= " WHERE " . implode(' AND ', $where_clauses);
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $parques = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($parques) {
                foreach ($parques as $parque) {
                    echo "<a href='parque.php?id_parque=".$parque['id_parque']."' class='parques_main_enlaces_link'>".$parque['nombre']."</a><br>";
                }
            } else {
                echo "No se encontraron parques que coincidan con el filtro.";
            }
        } else {
            // Mostrar todos los parques por defecto al cargar la página
            $parques = getAllParques(); // Asegúrate de que esta función está definida en functions.php y que devuelve todos los parques.

            if ($parques) {
                foreach ($parques as $parque) {
                    echo "<a href='parque.php?id_parque=".$parque['id_parque']."' class='parques_main_enlaces_link'>".$parque['nombre']."</a><br>";
                }
            } else {
                echo "No se encontraron parques.";
            }
        }
        ?>
    </div>
</body>
</html>
