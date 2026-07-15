# SEO On-Page e Indexación Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Cumplir SEO on-page (title, description, H1–H3, URLs sin `.php`, alts) e indexación (`robots.txt`, `sitemap.xml`) en Corporate + LATAM sin modificar el copy visible.

**Architecture:** Extender `urlWithLang` para URLs amigables y canonical consistente; partial `seo-head.php` + mapa de metas por página/idioma; rewrite Apache en `latam/.htaccess`; metas Corporate vía JSON + `i18n.js`; assets estáticos en raíz.

**Tech Stack:** PHP 8.x (includes), HTML estático, JS vanilla, Apache `mod_rewrite`, JSON i18n.

**Spec:** `docs/specs/2026-07-15-seo-on-page-design.md`

## Global Constraints

- Dominio canónico: `https://mslcorporate.com/`
- Cero cambio de copy visible (textos en pantalla, labels, párrafos, botones)
- Headings: solo nivel de etiqueta (`h1`→`h2`, etc.)
- Imágenes: solo atributo `alt` (y `aria-hidden` si ya existe el patrón)
- Sin dependencias externas nuevas
- Reutilizar `i18n.php` / `urlWithLang` y `i18n.js`
- Canonical/`lang`: incluir `?lang=` solo si el idioma no es `es` (default LATAM)
- Fuera de alcance: Open Graph, Schema, hreflang, rutas `/es/...`
- No indexar `/latam/admin/` ni endpoints de formularios

## File Structure

| Archivo | Responsabilidad |
|---------|-----------------|
| `latam/src/utils/i18n.php` | Extender `urlWithLang` (friendly path + omit default lang); helpers `seoCanonicalUrl`, `seoPagePath` |
| `latam/src/data/seo-meta.php` | Mapa title/description por página e idioma |
| `latam/src/components/php/seo-head.php` | Emite title, description, canonical, robots |
| `latam/.htaccess` | Rewrite interno + 301 desde `.php` |
| `latam/tests/test_seo_urls.php` | Tests CLI de URLs/canonical |
| `latam/tests/test_seo_meta.php` | Tests CLI de resolución de metas |
| `latam/tests/assert.php` | Asserts mínimos (copiar patrón admin o require relativo) |
| `latam/index.php`, `about-us.php`, `services.php`, `offices.php`, `contact-us.php` | `$seoPage` + include seo-head; headings; alts |
| `latam/src/components/php/navbar.php`, `footer.php` | Alts; links ya vía `urlWithLang` |
| `latam/src/scripts/lang-preserver.js` | Soporte URLs sin `.php` |
| `robots.txt`, `sitemap.xml` | Indexación (raíz web) |
| `index.html`, `src/js/i18n.js`, `src/translations/{es,en,pt,sv,no}.json` | SEO Corporate |

---

### Task 1: URLs amigables en `urlWithLang` + helpers SEO

**Files:**
- Modify: `latam/src/utils/i18n.php`
- Create: `latam/tests/assert.php`
- Create: `latam/tests/test_seo_urls.php`
- Test: `latam/tests/test_seo_urls.php`

**Interfaces:**
- Consumes: `getCurrentLanguage()`, `DEFAULT_LANGUAGE`, `SUPPORTED_LANGUAGES` (ya existentes)
- Produces:
  - `seoPagePath(string $url): string` — quita `.php` de páginas públicas; `index.php` → `./`
  - `urlWithLang(string $url, ?string $lang = null, array $additionalParams = []): string` — path friendly + omite `lang` si `$lang === DEFAULT_LANGUAGE` (`es`)
  - `seoCanonicalUrl(string $pageKey, ?string $lang = null): string` — absolutiza `https://mslcorporate.com/latam/...` según convención de lang

**Páginas públicas mapeables:** `index`, `about-us`, `services`, `offices`, `contact-us` (con o sin `.php`, con hash opcional).

- [ ] **Step 1: Crear asserts y test fallido**

Crear `latam/tests/assert.php`:

```php
<?php
function assert_true($cond, $msg) {
    if (!$cond) {
        fwrite(STDERR, "FAIL: $msg\n");
        exit(1);
    }
    echo "OK: $msg\n";
}
function assert_eq($actual, $expected, $msg) {
    assert_true($actual === $expected, $msg . " (expected=" . var_export($expected, true) . ", actual=" . var_export($actual, true) . ")");
}
```

Crear `latam/tests/test_seo_urls.php`:

```php
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
```

- [ ] **Step 2: Correr test y verificar FAIL**

Run:

```powershell
php latam/tests/test_seo_urls.php
```

Expected: FAIL (función `seoPagePath` / `seoCanonicalUrl` undefined, o asserts fallan porque `urlWithLang` aún agrega siempre `lang` y deja `.php`).

- [ ] **Step 3: Implementar helpers en `i18n.php`**

Al final de `latam/src/utils/i18n.php` (antes del cierre), reemplazar la función `urlWithLang` existente por esta versión y agregar helpers. Mantener `t()`, `getCurrentLanguage()`, etc. intactos.

```php
define('SEO_SITE_ORIGIN', 'https://mslcorporate.com');

/**
 * Convierte path de página pública a URL amigable (sin .php).
 * No altera send-*-form.php ni rutas admin.
 */
function seoPagePath($url) {
    $hash = '';
    if (strpos($url, '#') !== false) {
        $parts = explode('#', $url, 2);
        $url = $parts[0];
        $hash = '#' . $parts[1];
    }

    $path = $url;
    if (strpos($url, '?') !== false) {
        $path = strstr($url, '?', true);
    }

    $base = basename($path);
    $public = ['index.php', 'about-us.php', 'services.php', 'offices.php', 'contact-us.php',
               'index', 'about-us', 'services', 'offices', 'contact-us'];

    if (!in_array($base, $public, true) && !in_array(preg_replace('/\.php$/', '', $base), ['index', 'about-us', 'services', 'offices', 'contact-us'], true)) {
        return $url . ($hash && strpos($url, '#') === false ? $hash : '');
    }

    $name = preg_replace('/\.php$/', '', $base);
    if ($name === 'index') {
        $friendly = './';
    } else {
        $friendly = $name;
    }

    return $friendly . $hash;
}

/**
 * URL relativa amigable con lang según convención SEO.
 */
function urlWithLang($url, $lang = null, $additionalParams = []) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }

    $hash = '';
    if (strpos($url, '#') !== false) {
        $parts = explode('#', $url, 2);
        $url = $parts[0];
        $hash = '#' . $parts[1];
    }

    $urlParts = parse_url($url);
    $baseUrl = isset($urlParts['path']) ? $urlParts['path'] : $url;
    $existingQuery = isset($urlParts['query']) ? $urlParts['query'] : '';
    parse_str($existingQuery, $existingParams);

    $baseUrl = seoPagePath($baseUrl);

    $params = $additionalParams;
    if ($lang !== DEFAULT_LANGUAGE) {
        $params['lang'] = $lang;
    }
    $allParams = array_merge($existingParams, $params);
    if ($lang === DEFAULT_LANGUAGE) {
        unset($allParams['lang']);
    }

    $queryString = http_build_query($allParams);
    return $baseUrl . ($queryString ? '?' . $queryString : '') . $hash;
}

/**
 * @param string $pageKey home|about-us|services|offices|contact-us
 */
function seoCanonicalUrl($pageKey, $lang = null) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }

    if ($pageKey === 'home' || $pageKey === 'index') {
        $path = '/latam/';
    } else {
        $path = '/latam/' . $pageKey;
    }

    $url = SEO_SITE_ORIGIN . $path;
    if ($lang !== DEFAULT_LANGUAGE) {
        $url .= '?lang=' . rawurlencode($lang);
    }
    return $url;
}
```

- [ ] **Step 4: Correr test y verificar PASS**

Run:

```powershell
php latam/tests/test_seo_urls.php
```

Expected: todas las líneas `OK:` y `All url tests passed`.

- [ ] **Step 5: Commit**

```powershell
git add latam/src/utils/i18n.php latam/tests/assert.php latam/tests/test_seo_urls.php
git commit -m "feat(seo): friendly URLs and canonical helpers in i18n"
```

---

### Task 2: Mapa SEO + `seo-head.php`

**Files:**
- Create: `latam/src/data/seo-meta.php`
- Create: `latam/src/components/php/seo-head.php`
- Create: `latam/tests/test_seo_meta.php`
- Test: `latam/tests/test_seo_meta.php`

**Interfaces:**
- Consumes: `getCurrentLanguage()`, `seoCanonicalUrl()`
- Produces:
  - `seoGetMeta(string $pageKey, ?string $lang = null): array{title: string, description: string}`
  - Partial espera variable `$seoPage` (`home|about-us|services|offices|contact-us`) en el scope del include

- [ ] **Step 1: Test fallido de metas**

`latam/tests/test_seo_meta.php`:

```php
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
```

- [ ] **Step 2: Correr test — FAIL**

```powershell
php latam/tests/test_seo_meta.php
```

Expected: FAIL (archivo / función inexistente).

- [ ] **Step 3: Crear `seo-meta.php` con textos completos**

`latam/src/data/seo-meta.php`:

```php
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
```

- [ ] **Step 4: Crear `seo-head.php`**

`latam/src/components/php/seo-head.php`:

```php
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
```

- [ ] **Step 5: Correr test — PASS**

```powershell
php latam/tests/test_seo_meta.php
```

Expected: `All meta tests passed`.

- [ ] **Step 6: Commit**

```powershell
git add latam/src/data/seo-meta.php latam/src/components/php/seo-head.php latam/tests/test_seo_meta.php
git commit -m "feat(seo): add LATAM meta map and seo-head partial"
```

---

### Task 3: Integrar `seo-head` en páginas LATAM

**Files:**
- Modify: `latam/index.php`, `latam/about-us.php`, `latam/services.php`, `latam/offices.php`, `latam/contact-us.php`

**Interfaces:**
- Consumes: `$seoPage` + `seo-head.php`
- Produces: cada página pública con title/description/canonical dinámicos

- [ ] **Step 1: En cada página, definir `$seoPage` y reemplazar `<title>...</title>`**

Patrón (ejemplo `index.php`): después de cargar i18n, antes del HTML:

```php
$seoPage = 'home';
```

En el `<head>`, **reemplazar** la línea `<title>MSL LATAM / Home</title>` por:

```php
<?php include 'src/components/php/seo-head.php'; ?>
```

Mapeo:

| Archivo | `$seoPage` |
|---------|------------|
| `index.php` | `home` |
| `about-us.php` | `about-us` |
| `services.php` | `services` |
| `offices.php` | `offices` |
| `contact-us.php` | `contact-us` |

No duplicar `<title>`. Dejar charset, viewport, CSS, favicon y GTM como están.

- [ ] **Step 2: Verificar render (smoke)**

```powershell
php -r "$_GET=['lang'=>'en']; include 'latam/about-us.php';" 2>&1 | Select-String -Pattern "canonical|meta name=\"description\"|<title>"
```

Expected: title EN de about-us, meta description, link canonical con `?lang=en`.

(Si el include imprime HTML completo con warnings de headers, basta con que el HTML contenga las tres etiquetas.)

- [ ] **Step 3: Commit**

```powershell
git add latam/index.php latam/about-us.php latam/services.php latam/offices.php latam/contact-us.php
git commit -m "feat(seo): wire seo-head into LATAM public pages"
```

---

### Task 4: `.htaccess` rewrite + 301

**Files:**
- Create: `latam/.htaccess`

**Interfaces:**
- Consumes: Apache `mod_rewrite`
- Produces: URLs `/latam/about-us` sirven `about-us.php`; `.php` público → 301

- [ ] **Step 1: Crear `latam/.htaccess`**

```apache
RewriteEngine On

# No tocar admin ni endpoints de formularios
RewriteRule ^admin/ - [L]
RewriteRule ^send-contact-form\.php$ - [L]
RewriteRule ^send-work-form\.php$ - [L]

# 301 home: index.php → /latam/
RewriteCond %{THE_REQUEST} \s/+latam/index\.php[\s?] [NC]
RewriteRule ^index\.php$ /latam/ [R=301,L,QSA]

# 301 resto: about-us|services|offices|contact-us.php → URL amigable (QSA preserva query)
RewriteCond %{THE_REQUEST} \s/+latam/(about-us|services|offices|contact-us)\.php[\s?] [NC]
RewriteRule ^(about-us|services|offices|contact-us)\.php$ /latam/$1 [R=301,L,QSA]

# Interno: URL amigable → .php si el archivo existe
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}.php -f
RewriteRule ^([a-z0-9\-]+)/?$ $1.php [L,QSA]
```

Nota: si el hosting no usa prefijo `/latam/` en `THE_REQUEST` (vhost con DocumentRoot dentro de `latam/`), ajustar las reglas 301 a paths relativos:

```apache
RewriteCond %{THE_REQUEST} \s/+(index|about-us|services|offices|contact-us)\.php[\s?] [NC]
RewriteRule ^(index|about-us|services|offices|contact-us)\.php$ /$1 [R=301,L,QSA]
RewriteCond %{THE_REQUEST} \s/+index\.php[\s?] [NC]
RewriteRule ^index\.php$ / [R=301,L,QSA]
```

Elegir la variante según DocumentRoot real del deploy (`mslcorporate.com` sirve la raíz del repo → usar reglas con `/latam/`).

- [ ] **Step 2: Verificar en entorno local Apache (si hay)**

```powershell
# Con servidor local apuntando al repo:
curl -sI "http://localhost/latam/about-us.php" | Select-String "HTTP|Location"
curl -sI "http://localhost/latam/about-us" | Select-String "HTTP"
```

Expected: `.php` → `301` a URL sin extensión; URL amigable → `200`.

Si no hay Apache local: dejar documentado en el commit message que requiere `mod_rewrite` en producción.

- [ ] **Step 3: Commit**

```powershell
git add latam/.htaccess
git commit -m "feat(seo): add LATAM friendly URL rewrite and 301s"
```

---

### Task 5: Enlaces JS residuales + `lang-preserver`

**Files:**
- Modify: `latam/src/scripts/lang-preserver.js`
- Modify: `latam/index.php` (onclick/`offices.php#` en JS inline ~líneas 664, 716, 738)
- Modify: `latam/offices.php` si hubiera strings `.php` en JS

**Interfaces:**
- Consumes: convención friendly de Task 1
- Produces: navegación JS sin `.php`

- [ ] **Step 1: Actualizar `lang-preserver.js`**

Reemplazar la condición que solo mira `.php` para también tratar paths amigables internos:

```javascript
// Dentro del handler, donde hoy está:
// if (url.includes('.php') && !url.includes('lang=')) {
const isInternal = (url.includes('.php') || /^(.?\/)?(about-us|services|offices|contact-us|index)(\?|#|$)/.test(url) || url === './' || url.startsWith('./?'))
    && !url.includes('lang=')
    && !url.startsWith('http');
if (isInternal) {
```

Mantener la lógica de agregar `lang` + hash.

- [ ] **Step 2: En `index.php`, reemplazar referencias JS**

Buscar `` `offices.php#${data.slug}` `` (3 ocurrencias) y cambiar a:

```javascript
`offices#${data.slug}`
```

(Los `urlWithLang('*.php')` en PHP ya salen amigables tras Task 1; no reescribir copy de botones.)

- [ ] **Step 3: Grep de residuales públicos**

```powershell
rg -n "\.php" latam/index.php latam/about-us.php latam/services.php latam/offices.php latam/contact-us.php latam/src/components/php latam/src/scripts --glob "!admin/**"
```

Expected: solo `require`/`include`, `send-*-form.php`, admin links si los hubiera, y basename PHP_SELF — no hrefs públicos con `.php`.

- [ ] **Step 4: Commit**

```powershell
git add latam/src/scripts/lang-preserver.js latam/index.php latam/offices.php
git commit -m "fix(seo): update JS links and lang-preserver for friendly URLs"
```

---

### Task 6: Jerarquía de headings (solo tags)

**Files:**
- Modify: `latam/index.php`
- Modify: `latam/about-us.php`
- Modify: `latam/services.php`
- Modify: `latam/offices.php`
- Modify: `latam/contact-us.php` (solo si hace falta)
- Verify: `index.html` (Corporate — ya 1 H1; no cambiar copy)

**Regla:** cambiar solo la etiqueta; el contenido interno (`<?php echo t(...) ?>`, texto, clases, ids) queda igual.

- [ ] **Step 1: `latam/index.php` — un solo H1 (hero)**

Cambios de tag:

| Ubicación | De | A |
|-----------|----|----|
| `#we_are` título | `h1` | `h2` |
| `#services` título | `h1` | `h2` |
| `#offices` / offices title | `h1` | `h2` |
| CTA final título | `h1` | `h2` |

Dejar el H1 del hero. No tocar H2 de webtools ni H2 de cards de servicios.

- [ ] **Step 2: `latam/about-us.php`**

| Ubicación | De | A |
|-----------|----|----|
| `who-we-are` `.reval` | `h1` | `h2` |
| `our-history` título | `h4` | `h2` |
| `regional-reach` título | `h4` | `h2` |

Dejar H1 del header. Los `h3` de números (+40, etc.) se mantienen (métricas, no secciones de contenido narrativo).

- [ ] **Step 3: `latam/services.php`**

| Ubicación | De | A |
|-----------|----|----|
| `services_page.info.title` | `h1` | `h2` |
| ocean / air / truck / warehousing / elogistics / insurance titles | `h1` | `h2` |

Dejar un solo H1: `services_page.header.title`.

- [ ] **Step 4: `latam/offices.php`**

Cambiar `<h1 id="country-name">` → `<h2 id="country-name">` (mismo `id` para no romper JS).

- [ ] **Step 5: Verificar conteo H1**

```powershell
rg -c "<h1" latam/index.php latam/about-us.php latam/services.php latam/offices.php latam/contact-us.php
```

Expected: `1` en cada archivo (contact ya tiene 1).

- [ ] **Step 6: Commit**

```powershell
git add latam/index.php latam/about-us.php latam/services.php latam/offices.php
git commit -m "fix(seo): enforce single H1 and heading hierarchy on LATAM pages"
```

---

### Task 7: Atributos `alt` (sin cambiar `src`/layout)

**Files:**
- Modify: `latam/index.php`, `about-us.php`, `services.php`, `offices.php`, `contact-us.php`
- Modify: `latam/src/components/php/navbar.php`, `footer.php`

**Regla:** solo `alt` (y `aria-hidden="true"` en decorativos si ya hay patrón). Cero cambio de copy visible.

- [ ] **Step 1: Correcciones prioritarias**

Ejemplos concretos (aplicar el mismo criterio al resto de genéricos/`faltantes`):

`latam/index.php`:
- `alt="we are MLS"` → `alt="we are MSL"`
- `alt="image"` en previews → `alt="Ocean freight"`, `alt="Air freight"`, etc. según servicio (en inglés neutro o sin texto UI nuevo largo)
- Iconos sin alt (`ocean_icon.svg`, etc.) → `alt=""` + `aria-hidden="true"` (decorativos junto a título visible)
- Flechas UI → `alt=""` `aria-hidden="true"`

`latam/about-us.php`:
- `alt="logo"` → `alt="MSL Logo"`
- `alt="image"` → alts descriptivos cortos (`MSL team`, `MSL operations`, etc.)

`latam/services.php`:
- `alt="background"` → `alt=""` `aria-hidden="true"`
- `alt="image"` por servicio → descriptivo (`Ocean freight service`, …)
- `nlo_logo.svg` / `parallax_nlo.png` sin alt → `alt="Neutral Logistics Operator"` / decorativo vacío según rol

`latam/offices.php`:
- `alt="flag"` → `alt="Argentina"`, `alt="Chile"`, … según país
- `alt="map"` → `alt="Latin America map"`
- `alt="background"` → `alt=""` `aria-hidden="true"`
- En template JS: `` alt="flag" `` → `` alt="${countryName || countryId}" ``; flechas → `alt=""` 

`latam/contact-us.php`:
- `alt="contact"` → `alt="MSL contact"`

`navbar.php` / `footer.php`:
- Flechas/world decorativos → `alt=""` `aria-hidden="true"`
- Redes: `alt="LinkedIn"`, `alt="Instagram"`, `alt="Facebook"` (capitalización consistente)
- Si hay `<img>` de redes sin `alt` en navbar, agregarlo

- [ ] **Step 2: Grep de alts débiles**

```powershell
rg -n "alt=\"(image|logo|flag|arrow|map|background|contact)\"" latam --glob "!admin/**"
rg -n "<img(?![^>]*alt=)" latam --glob "!admin/**" -P
```

Expected: sin genéricos listados; sin `<img` públicos sin `alt`.

- [ ] **Step 3: Commit**

```powershell
git add latam/index.php latam/about-us.php latam/services.php latam/offices.php latam/contact-us.php latam/src/components/php/navbar.php latam/src/components/php/footer.php
git commit -m "fix(seo): improve image alt attributes on LATAM pages"
```

---

### Task 8: SEO Corporate (`index.html` + i18n)

**Files:**
- Modify: `index.html`
- Modify: `src/js/i18n.js`
- Modify: `src/translations/es.json`, `en.json`, `pt.json`, `sv.json`, `no.json`

**Interfaces:**
- Consumes: `t('seo.title')`, `t('seo.description')` vía `applyTranslations` extendido
- Produces: title + meta description que cambian con el idioma

- [ ] **Step 1: Agregar claves `seo` en cada JSON**

En **cada** archivo `src/translations/{lang}.json`, agregar al root (junto a `nav`, `header`, …):

`en.json`:
```json
"seo": {
  "title": "MSL Corporate | Neutral Logistics Operator",
  "description": "MSL Corporate connects regional logistics networks across LATAM, USA and Nordic. Access our global neutral logistics operator hubs."
}
```

`es.json`:
```json
"seo": {
  "title": "MSL Corporate | Operador Logístico Neutral",
  "description": "MSL Corporate conecta las redes logísticas regionales de LATAM, USA y Nordic. Accedé a los hubs del operador logístico neutral."
}
```

`pt.json`:
```json
"seo": {
  "title": "MSL Corporate | Operador Logístico Neutro",
  "description": "A MSL Corporate conecta redes logísticas regionais na LATAM, USA e Nordic. Acesse os hubs do operador logístico neutro."
}
```

`sv.json`:
```json
"seo": {
  "title": "MSL Corporate | Neutral Logistics Operator",
  "description": "MSL Corporate kopplar samman regionala logistiknätverk i LATAM, USA och Norden. Hitta våra hubbar för neutral logistics."
}
```

`no.json`:
```json
"seo": {
  "title": "MSL Corporate | Neutral Logistics Operator",
  "description": "MSL Corporate kobler sammen regionale logistikknettverk i LATAM, USA og Norden. Finn hubene til vår nøytrale logistikkoperatør."
}
```

(Validar JSON con coma correcta respecto a la clave anterior.)

- [ ] **Step 2: Metas en `index.html`**

Tras el `<title>` existente, dejar title inicial EN (default Corporate) y agregar:

```html
<title>MSL Corporate | Neutral Logistics Operator</title>
<meta name="description" content="MSL Corporate connects regional logistics networks across LATAM, USA and Nordic. Access our global neutral logistics operator hubs.">
<link rel="canonical" href="https://mslcorporate.com/">
<meta name="robots" content="index,follow">
```

Reemplazar el title viejo `MSL Corporate - Neutral Logistics Operator` por el de arriba (mismo significado, formato alineado al mapa SEO).

- [ ] **Step 3: Extender `applyTranslations` en `src/js/i18n.js`**

Al final de `applyTranslations()`, antes de cerrar la función:

```javascript
  const seoTitle = t('seo.title');
  const seoDescription = t('seo.description');
  if (seoTitle && seoTitle !== 'seo.title') {
    document.title = seoTitle;
  }
  const metaDesc = document.querySelector('meta[name="description"]');
  if (metaDesc && seoDescription && seoDescription !== 'seo.description') {
    metaDesc.setAttribute('content', seoDescription);
  }
  document.documentElement.setAttribute('lang', currentLanguage);
```

Nota: la línea de `lang` ya existe al final de `applyTranslations`; no duplicarla — integrar solo title/description antes del set de `lang` existente.

- [ ] **Step 4: Smoke manual**

Abrir `index.html` en browser, cambiar idioma en el switcher.

Expected: `document.title` y `meta[name=description]` cambian; copy visible de secciones sigue igual.

- [ ] **Step 5: Commit**

```powershell
git add index.html src/js/i18n.js src/translations/es.json src/translations/en.json src/translations/pt.json src/translations/sv.json src/translations/no.json
git commit -m "feat(seo): add Corporate meta tags with i18n updates"
```

---

### Task 9: `robots.txt` + `sitemap.xml`

**Files:**
- Create: `robots.txt` (raíz del repo / document root)
- Create: `sitemap.xml` (raíz)

- [ ] **Step 1: Crear `robots.txt`**

```txt
User-agent: *
Allow: /
Disallow: /latam/admin/
Disallow: /latam/send-contact-form.php
Disallow: /latam/send-work-form.php

Sitemap: https://mslcorporate.com/sitemap.xml
```

- [ ] **Step 2: Crear `sitemap.xml`**

Incluir Corporate + LATAM (es sin query; en/pt con `?lang=`):

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://mslcorporate.com/</loc></url>
  <url><loc>https://mslcorporate.com/latam/</loc></url>
  <url><loc>https://mslcorporate.com/latam/?lang=en</loc></url>
  <url><loc>https://mslcorporate.com/latam/?lang=pt</loc></url>
  <url><loc>https://mslcorporate.com/latam/about-us</loc></url>
  <url><loc>https://mslcorporate.com/latam/about-us?lang=en</loc></url>
  <url><loc>https://mslcorporate.com/latam/about-us?lang=pt</loc></url>
  <url><loc>https://mslcorporate.com/latam/services</loc></url>
  <url><loc>https://mslcorporate.com/latam/services?lang=en</loc></url>
  <url><loc>https://mslcorporate.com/latam/services?lang=pt</loc></url>
  <url><loc>https://mslcorporate.com/latam/offices</loc></url>
  <url><loc>https://mslcorporate.com/latam/offices?lang=en</loc></url>
  <url><loc>https://mslcorporate.com/latam/offices?lang=pt</loc></url>
  <url><loc>https://mslcorporate.com/latam/contact-us</loc></url>
  <url><loc>https://mslcorporate.com/latam/contact-us?lang=en</loc></url>
  <url><loc>https://mslcorporate.com/latam/contact-us?lang=pt</loc></url>
</urlset>
```

- [ ] **Step 3: Commit**

```powershell
git add robots.txt sitemap.xml
git commit -m "feat(seo): add robots.txt and sitemap.xml"
```

---

### Task 10: Verificación final end-to-end

**Files:** ninguno nuevo (solo checklist)

- [ ] **Step 1: Re-correr tests PHP**

```powershell
php latam/tests/test_seo_urls.php
php latam/tests/test_seo_meta.php
```

Expected: PASS ambos.

- [ ] **Step 2: Checklist vs spec**

| Requisito | Verificación |
|-----------|--------------|
| Title + description LATAM | View-source home/about/services/offices/contact en `es` y `en` |
| Title + description Corporate | View-source + cambio de idioma |
| 1 H1 por página | `rg -c "<h1" latam/*.php` → 1 cada una |
| URLs sin `.php` | Links navbar generan `about-us` / `about-us?lang=en` |
| Alts | Sin genéricos `image|logo|flag|arrow|map|background` |
| robots.txt / sitemap.xml | Existen en raíz; URLs canónicas alineadas |
| Copy visible | Diff no debe mostrar cambios en strings de UI/traducciones de contenido (solo `seo.*` nuevos y alts/tags) |

- [ ] **Step 3: Commit vacío no requerido** — si hubo fixes del checklist, commit atómico:

```powershell
git add -A
git status
git commit -m "fix(seo): address final verification findings"
```

(Solo si hay cambios.)

---

## Self-Review (plan vs spec)

| Spec section | Task |
|--------------|------|
| Metatítulo / meta description LATAM | 2, 3 |
| Metas Corporate + i18n | 8 |
| H1/H2/H3 | 6 |
| URLs amigables + 301 | 1, 4, 5 |
| Canonical + convención lang | 1, 2, 9 |
| Alts | 7 |
| robots.txt / sitemap.xml | 9 |
| No tocar copy visible | Global + Tasks 6–8 |
| No admin index | 4, 9 |
| Reusar urlWithLang | 1 |

Sin placeholders TBD. Firmas consistentes: `seoPagePath`, `seoCanonicalUrl`, `seoGetMeta`, `$seoPage`.
