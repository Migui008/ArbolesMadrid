<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arboles Madrid</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="image/icono.png">
  <?php
  ?>
</head>

<body>
  <?php require_once('header.php')?>
  <?php require_once('common_data.php')?>
  <div id="index_container">
    <?php require_once('sidebar.php')?>
    <div id="index_main">
      <div id="index_main_arboles">
        <h2>Arboles</h2>
        <?php
        foreach($arboles_filter as $filter => $info){
          echo "<a class='index_main_arboles' href='arboles.php?filter=".$filter."'>".$filter."</a><br>";
        }
        ?>
      </div>
      <div id="index_main_parques">
        <h2>Parques</h2>
        <?php
        foreach($parques_filter as $filter => $info){
          echo "<a class='index_main_parques' href='parques.php?filter=".$filter."'>".$filter."</a><br>";
        }
        ?>
      </div>
      <div id="index_main_bibliografia">
        <h2 class="index_main_bibliografia_title">Autor:</h2>
        <p class="index_main_bibliografia_text">Miguel Salvador Rioja</p>
      </div>
    </div>
  </div>
</body>
</html>
