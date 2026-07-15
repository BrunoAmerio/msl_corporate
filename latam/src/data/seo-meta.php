<?php
/**
 * Metas SEO LATAM por página e idioma. No usar para copy visible de UI.
 */
function seoGetMeta($pageKey, $lang = null) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }

    $meta = [
        'home' => [
            'es' => [
                'title' => 'MSL LATAM | Operador Logístico Neutral en Latinoamérica',
                'description' => 'MSL LATAM ofrece soluciones logísticas integrales en transporte marítimo, aéreo y terrestre, warehousing y seguros en toda Latinoamérica.',
            ],
            'en' => [
                'title' => 'MSL LATAM | Neutral Logistics Operator in Latin America',
                'description' => 'MSL LATAM provides end-to-end logistics: ocean, air and truck transport, warehousing and cargo insurance across Latin America.',
            ],
            'pt' => [
                'title' => 'MSL LATAM | Operador Logístico Neutro na América Latina',
                'description' => 'A MSL LATAM oferece soluções logísticas completas em transporte marítimo, aéreo e terrestre, warehousing e seguros em toda a América Latina.',
            ],
        ],
        'about-us' => [
            'es' => [
                'title' => 'Quiénes somos | MSL LATAM — Operador Logístico Neutral',
                'description' => 'Conocé la historia y el alcance regional de MSL LATAM, operador logístico neutral con oficinas propias en Sudamérica.',
            ],
            'en' => [
                'title' => 'About Us | MSL LATAM — Neutral Logistics Operator',
                'description' => 'Learn about MSL LATAM’s history and regional reach as a neutral logistics operator with own offices across South America.',
            ],
            'pt' => [
                'title' => 'Quem somos | MSL LATAM — Operador Logístico Neutro',
                'description' => 'Conheça a história e o alcance regional da MSL LATAM, operador logístico neutro com escritórios próprios na América do Sul.',
            ],
        ],
        'services' => [
            'es' => [
                'title' => 'Servicios logísticos | Marítimo, aéreo y más | MSL LATAM',
                'description' => 'Servicios MSL LATAM: ocean freight, air freight, trucking, warehousing, e-logistics y seguro de mercadería para tu cadena de suministro.',
            ],
            'en' => [
                'title' => 'Logistics Services | Ocean, Air & More | MSL LATAM',
                'description' => 'MSL LATAM services: ocean freight, air freight, trucking, warehousing, e-logistics and cargo insurance for your supply chain.',
            ],
            'pt' => [
                'title' => 'Serviços logísticos | Marítimo, aéreo e mais | MSL LATAM',
                'description' => 'Serviços MSL LATAM: ocean freight, air freight, trucking, warehousing, e-logistics e seguro de mercadorias para a sua cadeia de suprimentos.',
            ],
        ],
        'offices' => [
            'es' => [
                'title' => 'Red de oficinas en Latinoamérica | MSL LATAM',
                'description' => 'Encontrá oficinas MSL LATAM en Argentina, Brasil, Chile, Colombia y más países de la región. Red propia en Latinoamérica.',
            ],
            'en' => [
                'title' => 'Office Network in Latin America | MSL LATAM',
                'description' => 'Find MSL LATAM offices in Argentina, Brazil, Chile, Colombia and more. Our own logistics network across Latin America.',
            ],
            'pt' => [
                'title' => 'Rede de escritórios na América Latina | MSL LATAM',
                'description' => 'Encontre escritórios MSL LATAM na Argentina, Brasil, Chile, Colômbia e outros países. Rede própria na América Latina.',
            ],
        ],
        'contact-us' => [
            'es' => [
                'title' => 'Contacto | MSL LATAM — Operador Logístico Neutral',
                'description' => 'Contactá a MSL LATAM para cotizaciones y consultas logísticas. También podés postularte para trabajar con nosotros.',
            ],
            'en' => [
                'title' => 'Contact | MSL LATAM — Neutral Logistics Operator',
                'description' => 'Contact MSL LATAM for logistics quotes and inquiries. You can also apply to work with our team across Latin America.',
            ],
            'pt' => [
                'title' => 'Contato | MSL LATAM — Operador Logístico Neutro',
                'description' => 'Fale com a MSL LATAM para cotações e consultas logísticas. Também pode candidatar-se para trabalhar conosco.',
            ],
        ],
    ];

    if (!isset($meta[$pageKey])) {
        $pageKey = 'home';
    }
    if (!isset($meta[$pageKey][$lang])) {
        $lang = DEFAULT_LANGUAGE;
    }

    return $meta[$pageKey][$lang];
}
