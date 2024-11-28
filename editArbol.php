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
    <title><?php echo $contenidoArbol['nombre']; ?> editor</title>
</head>
<body>
  <div id="edit_main">
    <form id="edit_main_form">
      <label class="edit_main_form_label" for="arbolName" name="nombre">Nombre</label>
      <input class="edit_main_form_input" id="arbolName" type="text" value="<?php $contenidoArbol['nombre'];" ?> >
      <label class="edit_main_form_label" for="nombreCientifico" name="nombreCien">Nombre científico</label>
      <input class="edit_main_form_input" id="nombreCientifico" type="text" value="<?php $contenidoArbol['nombre_cientifico']; ?>" >
      <label class="edit_main_form_label" for="familia" name="familia">Familia</label>
      <input class="edit_main_form_input" id="familia" type="text" value="<?php $contenidoArbol['familia']; ?>" >
      <label class="edit_main_form_label" for="clase" name="clase">Clase</label>
      <input class="edit_main_form_input" id="clase" type="text" value="<?php $contenidoArbol['clase']; ?>" >
      <?php
        foreach($textoArbol as $textoSeccion){
          echo "<p class='edit_main_form_input_paragraph'>Número</p>";
          echo "<input class='edit_main_form_input_number' type='number' min='1' value='". $textoSeccion['numero'] ."' name='numero".$textoSeccion['numero']."'>";
          echo "<p class='edit_main_form_input_paragraph'>Titulo</p>";
          echo "<input class='edit_main_form_input' type='text' value='". $textoSeccion['titulo'] ." name='titulo".$textoSeccion['numero']."' >'";
          echo "<p class='edit_main_form_input_paragraph'>Titulo inglés</p>";
          echo "<input class='edit_main_form_input' type='text' value='". $textoSeccion['titulo_en'] ."' name='titulo_en".$textoSeccion['numero']."' >";
          echo "<p class='edit_main_form_input_paragraph'>Texto</p>";
          echo "<textarea class='edit_main_form_input_textarea' rows='20' cols='30' name='texto".$textoSeccion['numero']."' >". $textoSeccion['texto'] ."</textarea>";
          echo "<p class='edit_main_form_input_paragraph'>Texto inglés</p>";
          echo "<textarea class='edit_main_form_input_textarea' rows='20' cols='30' name='texto_en".$textoSeccion['numero']."' >". $textoSeccion['texto_en'] ."</textarea>";
        }

      foreach($textoArbol as $checknumero){
        if($_POST['numero'.$checkNumero['numero']] != $checkNumero['numero'] ){
        
        }
      }
      ?>
    </form>
  </div>
</body>
</html>
