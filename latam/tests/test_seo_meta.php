<?php
require_once __DIR__ . '/assert.php';
require_once dirname(__DIR__) . '/src/utils/i18n.php';
require_once dirname(__DIR__) . '/src/data/seo-meta.php';

$es = seoGetMeta('home', 'es');
assert_true(isset($es['title']) && isset($es['description']), 'home es has title+description');
assert_true(mb_strlen($es['title']) <= 65, 'home es title length');
assert_true(mb_strlen($es['description']) >= 120 && mb_strlen($es['description']) <= 170, 'home es description length');

$en = seoGetMeta('services', 'en');
assert_true(strpos($en['title'], 'MSL') !== false, 'services en mentions MSL');

$pt = seoGetMeta('contact-us', 'pt');
assert_true(strlen($pt['description']) > 50, 'contact pt description present');

foreach (['home', 'about-us', 'services', 'offices', 'contact-us'] as $page) {
    foreach (['es', 'en', 'pt'] as $lang) {
        $m = seoGetMeta($page, $lang);
        assert_true($m['title'] !== '' && $m['description'] !== '', "$page/$lang non-empty");
    }
}

echo "All meta tests passed\n";
