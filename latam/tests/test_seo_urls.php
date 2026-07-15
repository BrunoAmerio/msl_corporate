<?php
require_once __DIR__ . '/assert.php';
require_once dirname(__DIR__) . '/src/utils/i18n.php';

assert_eq(seoPagePath('about-us.php'), 'about-us', 'seoPagePath strips php');
assert_eq(seoPagePath('services.php#ocean'), 'services#ocean', 'seoPagePath keeps hash');
assert_eq(seoPagePath('index.php'), './', 'seoPagePath maps index to ./');

$_GET = [];
assert_eq(urlWithLang('about-us.php', 'es'), 'about-us', 'es omits lang query');
assert_eq(urlWithLang('about-us.php', 'en'), 'about-us?lang=en', 'en keeps lang query');
assert_eq(urlWithLang('services.php#air', 'pt'), 'services?lang=pt#air', 'hash after query');
assert_eq(urlWithLang('index.php', 'es'), './', 'home es');
assert_eq(urlWithLang('index.php', 'en'), './?lang=en', 'home en');

assert_eq(
    seoCanonicalUrl('about-us', 'es'),
    'https://mslcorporate.com/latam/about-us',
    'canonical es'
);
assert_eq(
    seoCanonicalUrl('about-us', 'en'),
    'https://mslcorporate.com/latam/about-us?lang=en',
    'canonical en'
);
assert_eq(
    seoCanonicalUrl('home', 'es'),
    'https://mslcorporate.com/latam/',
    'canonical home es'
);
assert_eq(
    seoCanonicalUrl('home', 'pt'),
    'https://mslcorporate.com/latam/?lang=pt',
    'canonical home pt'
);

echo "All url tests passed\n";
