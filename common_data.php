<?php
$arboles_filter=[
  "clase" =>[
    "tipo" => "select",
    "query" => ""
  ],
  "familia" =>[
    "tipo" => "select",
    "query" => ""
  ],
  "visitas" => [
    "tipo" => "radio",
    "opciones" => [
        [
            "rango" => "Menos de 50",
            "condicion" => "a.visitas < 50"
        ],
        [
            "rango" => "Entre 50 y 400",
            "condicion" => "a.visitas >= 50 AND a.visitas <= 400"
        ],
        [
            "rango" => "Más de 400",
            "condicion" => "a.visitas > 400"
        ]
    ]
  ]
];

$parques_filter=[
  "accesibilidad" => [
      "tipo" => "checkbox",
      "opciones" => [
        [
            "transporte" => "Bus",
            "condicion" => "p.transporte_bus != null"
        ],
        [
            "transporte" => "Metro",
            "condicion" => "p.transporte_metro != null"
        ],
        [
            "transporte" => "RENFE",
            "condicion" => "p.transporte_renfe != null"
        ]
      ]
  ]
  "visitas" => [
    "tipo" => "radio",
    "opciones" => [
        [
            "rango" => "Menos de 50",
            "condicion" => "p.visitas < 50"
        ],
        [
            "rango" => "Entre 50 y 400",
            "condicion" => "p.visitas >= 50 AND p.visitas <= 400"
        ],
        [
            "rango" => "Más de 400",
            "condicion" => "p.visitas > 400"
        ]
    ]
  ];
?>
