<?php
if (!isset($seoPage) || !is_string($seoPage)) {
    $seoPage = 'home';
}
require_once __DIR__ . '/../../data/seo-meta.php';

$seoLang = getCurrentLanguage();
$seoMeta = seoGetMeta($seoPage, $seoLang);
$seoCanonical = seoCanonicalUrl($seoPage, $seoLang);
$seoTitle = htmlspecialchars($seoMeta['title'], ENT_QUOTES, 'UTF-8');
$seoDescription = htmlspecialchars($seoMeta['description'], ENT_QUOTES, 'UTF-8');
$seoCanonicalEsc = htmlspecialchars($seoCanonical, ENT_QUOTES, 'UTF-8');
?>
<title><?php echo $seoTitle; ?></title>
<meta name="description" content="<?php echo $seoDescription; ?>">
<link rel="canonical" href="<?php echo $seoCanonicalEsc; ?>">
<meta name="robots" content="index,follow">
