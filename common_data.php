<?php
$arboles_filter = [
    "clase" => [
        "tipo" => "select",
        "nombre" => "Clase",
        "opciones" => [] 
    ],
    "familia" => [
        "tipo" => "select",
        "nombre" => "Familia",
        "opciones" => [] 
    ],
    "visitas" => [
        "tipo" => "radio",
        "nombre" => "Visitas",
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

$parques_filter = [
    "accesibilidad" => [
        "tipo" => "checkbox",
        "nombre" => "Accesibilidad",
        "opciones" => [
            [
                "rango" => "Bus",
                "condicion" => "p.transporte_bus IS NOT NULL"
            ],
            [
                "rango" => "Metro",
                "condicion" => "p.transporte_metro IS NOT NULL"
            ],
            [
                "rango" => "RENFE",
                "condicion" => "p.transporte_renfe IS NOT NULL"
            ]
        ]
    ],
    "visitas" => [
        "tipo" => "radio",
        "nombre" => "Visitas",
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
    ]
];
?>
