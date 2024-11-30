<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
  	<link rel="icon" type="image/png" href="icono.png">
    <?php require_once('functions.php')?>
    <?php require_once('header.php')?>
    <?php
        if(isset($_GET["id_parque"])){
            $contenidoParque = loadParqueData($_GET["id_parque"]);
            $textoParque = loadTextParque($_GET["id_parque"]);
        }    
    ?>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["id_parque"])) {
        require_once("dtbconnection.php");
        global $conn;
        $id_parque = intval($_GET["id_parque"]);

        try {
            if (!isset($_POST['nombre'], $_POST['direccion'], $_POST['transporte_bus'], $_POST['transporte_metro'], $_POST['transporte_renfe'], $_POST['latitud'], $_POST['longitud'])) {
                throw new Exception('Error: Faltan datos en la solicitud POST.');
            }

            $updateParqueQuery = "
                UPDATE parques 
                SET 
                    nombre = :nombre,
                    direccion = :direccion,
                    transporte_bus = :transporte_bus,
                    transporte_metro = :transporte_metro,
                    transporte_renfe = :transporte_renfe,
                    latitud = :latitud,
                    longitud = :longitud
                WHERE id_parque = :id_parque;
            ";
            $stmtParque = $conn->prepare($updateParqueQuery);
            $stmtParque->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR);
            $stmtParque->bindParam(':direccion', $_POST['direccion'], PDO::PARAM_STR);
            $stmtParque->bindParam(':transporte_bus', $_POST['transporte_bus'], PDO::PARAM_STR);
            $stmtParque->bindParam(':transporte_metro', $_POST['transporte_metro'], PDO::PARAM_STR);
            $stmtParque->bindParam(':transporte_renfe', $_POST['transporte_renfe'], PDO::PARAM_STR);
            $stmtParque->bindParam(':latitud', $_POST['latitud'], PDO::PARAM_STR);
            $stmtParque->bindParam(':longitud', $_POST['longitud'], PDO::PARAM_STR);
            $stmtParque->bindParam(':id_parque', $id_parque, PDO::PARAM_INT);
            $stmtParque->execute();

            $updateContenidoQuery = "
                UPDATE contenido 
                SET 
                    titulo = :titulo,
                    titulo_en = :titulo_en,
                    texto = :texto,
                    texto_en = :texto_en
                WHERE 
                    id_referencia_p = :id_referencia_p
                    AND numero = :numero;
            ";
            $stmtContenido = $conn->prepare($updateContenidoQuery);

            foreach ($_POST as $key => $value) {
                if (preg_match('/^numero(\d+)$/', $key, $matches)) {
                    $numero = intval($matches[1]);
                    $titulo = $_POST["titulo$numero"] ?? '';
                    $titulo_en = $_POST["titulo_en$numero"] ?? '';
                    $texto = $_POST["texto$numero"] ?? '';
                    $texto_en = $_POST["texto_en$numero"] ?? '';

                    $stmtContenido->bindParam(':titulo', $titulo, PDO::PARAM_STR);
                    $stmtContenido->bindParam(':titulo_en', $titulo_en, PDO::PARAM_STR);
                    $stmtContenido->bindParam(':texto', $texto, PDO::PARAM_STR);
                    $stmtContenido->bindParam(':texto_en', $texto_en, PDO::PARAM_STR);
                    $stmtContenido->bindParam(':id_referencia_p', $id_arbol, PDO::PARAM_INT);
                    $stmtContenido->bindParam(':numero', $numero, PDO::PARAM_INT);
                    $stmtContenido->execute();
                }
            }
        } catch (PDOException $e) {
            echo "Error al actualizar: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>

    <title><?php echo $contenidoParque['nombre']; ?> editor</title>
</head>
<body>
  <div id="edit_main">
    <form id="edit_main_form">
      <label class="edit_main_form_label" for="parqueName" name="nombre">Nombre</label>
      <input class="edit_main_form_input" id="parqueName" type="text" value="<?php echo $contenidoParque['nombre']; ?>" >
      <label class="edit_main_form_label" for="direccion" name="direccion">Dirección</label>
      <input class="edit_main_form_input" id="direccion" type="text" value="<?php echo $contenidoParque['direccion']; ?>" >
      <label class="edit_main_form_label" for="transporte_bus" name="transporte_bus">Transporte Bus</label>
      <input class="edit_main_form_input" id="transporte_bus" type="text" value="<?php echo $contenidoParque['transporte_bus']; ?>" >
      <label class="edit_main_form_label" for="transporte_metro" name="transporte_metro">Transporte Metro</label>
      <input class="edit_main_form_input" id="transporte_metro" type="text" value="<?php echo $contenidoParque['transporte_metro']; ?>" >
      <label class="edit_main_form_label" for="transporte_metro" name="transporte_metro">Transporte RENFE</label>
      <input class="edit_main_form_input" id="transporte_metro" type="text" value="<?php echo $contenidoParque['transporte_renfe']; ?>" >
      <label class="edit_main_form_label" for="latitud" name="latitud">Latitud</label>
      <input class="edit_main_form_input" id="latitud" type="text" value="<?php echo $contenidoParque['latitud']; ?>" >
      <label class="edit_main_form_label" for="longitud" name="longitud">Longitud</label>
      <input class="edit_main_form_input" id="longitud" type="text" value="<?php echo $contenidoParque['longitud']; ?>" >
      <?php
        foreach($textoArbol as $textoSeccion){
          echo "<p class='edit_main_form_input_paragraph'>Número</p>";
          echo "<input class='edit_main_form_input_number' type='number' min='1' value='". $textoSeccion['numero'] ."' name='numero".$textoSeccion['numero']."'>";
          echo "<p class='edit_main_form_input_paragraph'>Titulo</p>";
          echo "<input class='edit_main_form_input' type='text' value='". $textoSeccion['titulo'] ."' name='titulo".$textoSeccion['numero']."' >";
          echo "<p class='edit_main_form_input_paragraph'>Titulo inglés</p>";
          echo "<input class='edit_main_form_input' type='text' value='". $textoSeccion['titulo_en'] ."' name='titulo_en".$textoSeccion['numero']."' >";
          echo "<p class='edit_main_form_input_paragraph'>Texto</p>";
          echo "<textarea class='edit_main_form_input_textarea' rows='20' cols='30' name='texto".$textoSeccion['numero']."' >". $textoSeccion['texto'] ."</textarea>";
          echo "<p class='edit_main_form_input_paragraph'>Texto inglés</p>";
          echo "<textarea class='edit_main_form_input_textarea' rows='20' cols='30' name='texto_en".$textoSeccion['numero']."' >". $textoSeccion['texto_en'] ."</textarea>";
        }
      ?>
      <input id="edit_main_form_submit" type="submit" value="Editar">
    </form>
  </div>
    <script>
    document.getElementById('edit_main_form').addEventListener('submit', function(event) {
        const inputs = document.querySelectorAll('.edit_main_form_input_number');
        const numeros = Array.from(inputs).map(input => parseInt(input.value, 10));

        numeros.sort((a, b) => a - b);

        for (let i = 0; i < numeros.length; i++) {
            if (numeros[i] !== i + 1) {
                alert(`Los números deben ser consecutivos y empezar desde 1.\nRevisa la posición: ${i + 1}`);
                event.preventDefault(); 
                return;
            }
        }
    });
</script>
</body>
</html>
