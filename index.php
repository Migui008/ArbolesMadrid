<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arboles Madrid</title>
  <?php
  ?>
</head>

<body>
  <?= require_once('header.php')?>
  <?= require_once('sidebar.php')?>
  <div id="index_main">
    <div id="index_main_arboles">
      <h2>Arboles</h2>
      <!--
      Arboles
      for each filtro
      <div id="index_main_arboles_$filtro">$filtro</div>
      -->
    </div>
    <div id="index_main_parques">
      <h2>Parques</h2>
      <!--
      Parques
      for each filtro
      <div id="index_main_parques_$filtro">$filtro</div>
      -->
    </div>
    <div id="index_main_bibliografia"></div>
  </div>
</body>
</html>
