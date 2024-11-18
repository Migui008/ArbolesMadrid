<?php
$arboles_filter=[
  "clase" => 
      ["SELECT DISTINCT a.clase
      FROM arboles a ORDER BY a.clase;"],
  "familia" => 
      ["SELECT DISTINCT a.familia
      FROM arboles a ORDER BY a.familia;"],
  "altura" => 
      ["Menos de 10"=>"SELECT a.nombre, a.id_arbol
      FROM arboles a WHERE a.altura < 10;",
      "Entre 10 y 20"=>"SELECT a.nombre, a.id_arbol
      FROM arboles a WHERE a.altura >= 10 AND a.altura <=20;",
      "Más de 20"=>"SELECT a.nombre, a.id_arbol
      FROM arboles a WHERE a.altura > 20;"],
  "hoja" => 
      ["SELECT DISTINCT a.hoja
      FROM arboles a ORDER BY a.hoja;"],
  "visitas" => 
      ["Menos de 50"=>"SELECT a.id_arbol, a.nombre
      FROM arboles a WHERE a.visitas < 50",
      "Entre 50 y 400"=>"SELECT a.nombre, a.id_arbol
      FROM arboles a WHERE a.visitas >= 50 AND a.visitas <= 400;",
      "Más de 400"=>"SELECT a.nombre, a.id_arbol
      FROM arboles a WHERE a.visitas > 400;"]
];

$parques_filter=[
  "zona" => 
      ["SELECT DISTINCT p.zona
      FROM parques p ORDER BY p.zona;"],
  "tipo" => 
      ["SELECT DISTINCT p.tipo
      FROM parques p ORDER BY p.tipo"], //histórico, urbano, jardín botánico, parque forestal
  "accesibilidad" => 
      ["1" => "SELECT p.id_parque, p.nombre
      FROM parques p WHERE :t1 != null;",
      "2" => "SELECT p.id_parque, p.nombre
      FROM parques p WHERE :t1 != null AND :t2 != null;",
      "3" => "SELECT p.id_parque, p.nombre
      FROM parques p WHERE :t1 != null AND :t2 != null AND :t3 != null"], //bus, metro, renfe
  "visitas" => => 
      ["Menos de 50"=>"SELECT p.id_parque, p.nombre
      FROM parques p WHERE p.visitas < 50",
      "Entre 50 y 400"=>"SELECT p.nombre, p.id_arbol
      FROM parques p WHERE p.visitas >= 50 AND p.visitas <= 400;",
      "Más de 400"=>"SELECT p.nombre, p.id_arbol
      FROM parques p WHERE p.visitas > 400;"]
]
?>
