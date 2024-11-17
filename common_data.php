<?php
$arboles_filter=[
  "clase" => 
      ["SELECT DISTINCT a.clase
      FROM arboles a ORDER BY a.clase;"],
  "familia" => 
      ["SELECT DISTINCT a.familia
      FROM arboles a ORDER BY a.familia;"],
  "altura" => 
      ["Menos de 10"=>"",
      "Entre 10 y 20"=>"",
      "Más de 20"=>""],
  "hoja" => 
      ["SELECT DISTINCT a.hoja
      FROM arboles a ORDER BY a.hoja;"],
  "visitas" => 
      ["Menos de 50"=>"SELECT a.id_arbol, a.nombre",
      "Entre 50 y 400"=>"",
      "Más de 400"=>""]
];

$parques_filter=[
  "zona o distrito" => 
      ["SELECT DISTINCT p.zona
      FROM parques p ORDER BY p.zona;"],
  "tipo" => 
      [""], //histórico, urbano, jardín botánico, parque forestal
  "accesibilidad" => 
      "", //bus, metro, renfe
  "visitas" => => 
      ["Menos de 50"=>"",
      "Entre 50 y 400"=>"",
      "Más de 400"=>""]
]
?>
