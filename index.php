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
  <?php require_once('header.php')?>
  <?php require_once('sidebar.php')?>
  <?php require_once('common_data.php')?>
  <div id="index_main">
    <div id="index_main_arboles">
      <h2>Arboles</h2>
      <?php
      foreach($arboles_filter as $filter => $info){
        echo "<a class='index_main_arboles' href='".$_SERVER['PHP_SELF']."/arboles.php?filter=".$filter."'>".$filter."</a>";
      }
      ?>
    </div>
    <div id="index_main_parques">
      <h2>Parques</h2>
      <?php
      foreach($parques_filter as $filter => $info){
        echo "<a class='index_main_parques' href='".$_SERVER['PHP_SELF']."/parques.php?filter=".$filter."'>".$filter."</a>";
      }
      ?>
    </div>
    <div id="index_main_bibliografia"></div>
  </div>
</body>
</html>
