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
        if(isset($_GET["id_arbol"])){
            $contenidoArbol = loadArbolData($_GET["id_arbol"]);
            $textoArbol = loadTextArbol($_GET["id_arbol"]);
        }    
    ?>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["id_arbol"])) {
        require_once("dtbconnection.php");
        global $conn;
        $id_arbol = intval($_GET["id_arbol"]);

        try {
            if (!isset($_POST['nombre'], $_POST['nombreCien'], $_POST['familia'], $_POST['clase'])) {
                throw new Exception('Error: Faltan datos en la solicitud POST.');
            }

            $updateArbolQuery = "
                UPDATE arboles 
                SET 
                    nombre = :nombre,
                    nombre_cientifico = :nombre_cientifico,
                    familia = :familia,
                    clase = :clase
                WHERE id_arbol = :id_arbol;
            ";
            $stmtArbol = $conn->prepare($updateArbolQuery);
            $stmtArbol->bindParam(':nombre', $_POST['nombre'], PDO::PARAM_STR);
            $stmtArbol->bindParam(':nombre_cientifico', $_POST['nombreCien'], PDO::PARAM_STR);
            $stmtArbol->bindParam(':familia', $_POST['familia'], PDO::PARAM_STR);
            $stmtArbol->bindParam(':clase', $_POST['clase'], PDO::PARAM_STR);
            $stmtArbol->bindParam(':id_arbol', $id_arbol, PDO::PARAM_INT);
            $stmtArbol->execute();

            $updateContenidoQuery = "
                UPDATE contenido 
                SET 
                    titulo = :titulo,
                    titulo_en = :titulo_en,
                    texto = :texto,
                    texto_en = :texto_en
                WHERE 
                    id_referencia_a = :id_referencia_a
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
                    $stmtContenido->bindParam(':id_referencia_a', $id_arbol, PDO::PARAM_INT);
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

    <title><?php echo $contenidoArbol['nombre']; ?> editor</title>
</head>
<body>
  <div id="edit_main">
    <form id="edit_main_form">
      <label class="edit_main_form_label" for="arbolName" name="nombre">Nombre</label>
      <input class="edit_main_form_input" id="arbolName" type="text" value="<?php echo $contenidoArbol['nombre']; ?>" >
      <label class="edit_main_form_label" for="nombreCientifico" name="nombreCien">Nombre científico</label>
      <input class="edit_main_form_input" id="nombreCientifico" type="text" value="<?php echo $contenidoArbol['nombre_cientifico']; ?>" >
      <label class="edit_main_form_label" for="familia" name="familia">Familia</label>
      <input class="edit_main_form_input" id="familia" type="text" value="<?php echo $contenidoArbol['familia']; ?>" >
      <label class="edit_main_form_label" for="clase" name="clase">Clase</label>
      <input class="edit_main_form_input" id="clase" type="text" value="<?php echo $contenidoArbol['clase']; ?>" >
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
