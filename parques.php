<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
  	<link rel="icon" type="image/png" href="icono.png">
    <?php require_once('functions.php')?>
    <?php require_once('common_data.php')?>
    <title>Parques</title>
</head>
<body>
  <?php require_once('header.php')?>
  <div id="parques_main">
  <form method="get" action="<?php $_SERVER['PHP_SELF']; ?>" id="parques_main_form">
    <select name="filter" id="parques_main_form_select">
    <?php 
    foreach($parques_filter as $filtro => $datos){
      echo "<option value='".$filtro."' class='parques_main_form_select_option'>".$filtro."</option>";
    }
    ?>
    </select>
    <input id="parques_main_form_submit" type="submit" value="Filtrar">
  </form>
    <div id="parques_main_enlaces">
    <?php
    if(isset($_GET['filter']) && !empty($_GET['filter'])){
      echo "Aun no está";
    } else {
      $parques = getAllParques();
      foreach($parques as $parque){
        echo "<a href='parque.php'?id_parque=".$parque['id_parque']." class='parques_main_enlaces_link'>".$parque['nombre']."</a>";
      }
    }
    ?>
    </div>
  </div>
</body>
</html>
