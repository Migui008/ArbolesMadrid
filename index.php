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
      <div id="index_main_top">
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
      </div>
      <div id="index_main_bibliografia">
        <h2 class="index_main_bibliografia_title">Autor:</h2>
        <p class="index_main_bibliografia_text">Miguel Salvador Rioja</p>
        <h2 class="index_main_bibliografia_title">Bibliografía:</h2>
        <p class="index_main_bibliografia_text">
Ayuntamiento de Madrid. (2023). Informe anual Arbolado parques históricos, singulares y forestales 2023. Ayuntamiento de Madrid.<br> 
Ayuntamiento de Madrid, Departamento de Transporte Público. <br>
Ayuntamiento de Madrid, Departamento de Planificación Urbana y Medio Ambiente.<br> 
Ayuntamiento de Madrid, en colaboración con la Compañía de Ferrocarriles Españoles (RENFE).<br> 
GBIF.org. (2024). Página de inicio de GBIF. Recuperado el 5 de junio de 2024, de https://www.gbif.org<br> 
<br>
Imágenes:<br> 
Icons8. (n.d.). Recuperado de https://icons8.com/<br> 
<br>
Otros:<br> 
ChatGPT. (Traducción y depuración). <br>
Google. (s.f.). Google Maps Platform. Recuperado de https://cloud.google.com/maps-platform <br>
PHP Documentation. (s.f.). Recuperado de https://www.php.net/docs.php <br>
MDN Web Docs. (s.f.). Recuperado de https://developer.mozilla.org/en-US/ <br>
 </p>
      </div>
    </div>
  </div>
</body>
</html>
