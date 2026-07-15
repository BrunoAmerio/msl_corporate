# Diseño: SEO On-Page e indexación — MSL Corporate

**Fecha:** 2026-07-15  
**Dominio canónico:** `https://mslcorporate.com/`  
**Alcance:** Corporate (`index.html`) + LATAM (`/latam/*`)  
**Enfoque:** Partial SEO centralizado + assets estáticos (Enfoque 2)

---

## 1. Objetivo

Cumplir los elementos básicos de SEO on-page y de indexación en Google, **sin modificar el contenido visible** de la web (copy, layout, imágenes `src`, estilos).

### Checklist de entrega

| Requisito | Cómo se cumple |
|-----------|----------------|
| Metatítulo (Title Tag) | Por página e idioma |
| Meta description | Por página e idioma |
| H1 / H2 / H3 | Un H1 por página; H2/H3 jerárquicos (solo cambio de etiqueta) |
| URLs amigables | Sin extensión `.php` vía rewrite + 301 |
| Atributo `alt` en imágenes | Descriptivo o vacío decorativo |
| `sitemap.xml` | En raíz del sitio |
| `robots.txt` | En raíz del sitio |

### Fuera de alcance

- Open Graph / Twitter Cards  
- JSON-LD / Schema.org  
- `hreflang`  
- Rutas con idioma en path (`/latam/es/servicios`)  
- Cambios de copy, diseño visual o comportamiento de formularios  
- Indexación del panel admin  

---

## 2. Arquitectura

```
msl_corporate/
├── index.html                          # Metas Corporate + i18n JS para title/description
├── robots.txt                          # NUEVO
├── sitemap.xml                         # NUEVO
├── src/js/i18n.js                      # Extender: actualizar document.title y meta description
├── src/translations/*.json             # Claves seo.title / seo.description por idioma
└── latam/
    ├── .htaccess                       # Rewrite sin .php + 301 desde .php
    ├── index.php, about-us.php, ...    # Usan seo-head; headings/alts corregidos
    └── src/components/php/
        └── seo-head.php                # NUEVO: title, description, canonical, robots
```

### LATAM — `seo-head.php`

- Recibe (o lee) un identificador de página (`home`, `about-us`, `services`, `offices`, `contact-us`).
- Resuelve idioma actual vía i18n existente (`?lang=es|en|pt`).
- Emite:
  - `<title>`
  - `<meta name="description">`
  - `<link rel="canonical">` (URL limpia + `lang` activo)
  - Opcional: `<meta name="robots" content="index,follow">` en páginas públicas
- Los textos SEO viven en un mapa PHP en el partial (o archivo de datos dedicado), no mezclados con el copy de UI.

### Corporate — `index.html` + i18n

- Metas iniciales en `<head>` (idioma por defecto del documento).
- Al cambiar idioma con el switcher existente, el JS de i18n actualiza `document.title` y el `content` de meta description.
- Claves nuevas en JSON de traducciones: `seo.title`, `seo.description`.
- No se altera el copy visible de secciones.

---

## 3. URLs canónicas

| Página | URL canónica |
|--------|----------------|
| Corporate home | `https://mslcorporate.com/` |
| LATAM home | `https://mslcorporate.com/latam/` |
| About | `https://mslcorporate.com/latam/about-us` |
| Services | `https://mslcorporate.com/latam/services` |
| Offices | `https://mslcorporate.com/latam/offices` |
| Contact | `https://mslcorporate.com/latam/contact-us` |

### Reglas

- Query `?lang=es|en|pt` se mantiene en LATAM para cambiar idioma.
- **Convención canónica (única):** incluir `?lang=` solo si el idioma **no** es el default (`es`). Default `es` → URL sin query. Ej.: `/latam/about-us` (es), `/latam/about-us?lang=en` (en). Misma regla en rewrite de links, `canonical` y `sitemap.xml`.

### Rewrite (`latam/.htaccess`)

- Interno: `/latam/about-us` → `about-us.php` (y equivalentes).
- Externo: request a `*.php` de páginas públicas → **301** a la URL sin `.php` (preservando query string).
- No aplicar rewrite a `admin/`, `send-contact-form.php`, `send-work-form.php`.

### Enlaces internos

- Actualizar navbar, footer y CTAs LATAM para apuntar a URLs sin `.php` (misma ruta lógica; sin cambiar textos de ancla).
- Corporate: enlace a LATAM sin `.php` donde aplique.

---

## 4. Metatítulos y meta descriptions

Redacción a cargo de la implementación (borradores SEO). Criterios:

- Title: ~50–60 caracteres; keyword principal + marca (`MSL` / `MSL LATAM`).
- Description: ~140–160 caracteres; única por página e idioma; sin keyword stuffing.
- Idiomas:
  - LATAM: `es`, `en`, `pt`
  - Corporate: `es`, `en`, `pt`, `sv`, `no` (según traducciones existentes)

Páginas LATAM a cubrir: home, about-us, services, offices, contact-us.  
Corporate: una sola página (home/hub).

---

## 5. Jerarquía de encabezados

**Regla dura:** cambiar solo el nivel de etiqueta (`h1`→`h2`, etc.). El texto interno no se modifica.

| Página | Acción |
|--------|--------|
| Corporate `index.html` | Mantener 1 H1; revisar coherencia H2/H3 (sin tocar copy) |
| LATAM `index.php` | Un solo H1 (hero); resto de H1 de sección → H2 |
| LATAM `about-us.php` | Un H1; segundo H1 → H2; corregir saltos H4→H3 donde sea solo tag |
| LATAM `services.php` | Un H1 de página; cada servicio H1 → H2 |
| LATAM `offices.php` | Un H1 de página; H1 de país → H2 (markup + JS template si aplica) |
| LATAM `contact-us.php` | Ya razonable; validar y ajustar mínimo si hace falta |

---

## 6. Atributos `alt`

- Imágenes informativas: alt descriptivo (marca, servicio, región, persona/contexto).
- Decorativas / fondos: `alt=""` (y `aria-hidden` si el patrón actual lo usa).
- Corregir genéricos (`image`, `logo`, `flag`, `arrow`, `map`, `background`) y faltantes (iconos de servicios, logos redes en navbar, etc.).
- Corregir typo conocido: `we are MLS` → `we are MSL` **solo en el atributo `alt`**, no en copy visible.
- No cambiar `src`, clases, dimensiones ni estructura DOM más allá del atributo `alt`.

---

## 7. Indexación: `robots.txt` y `sitemap.xml`

### `robots.txt` (raíz del documento web)

```
User-agent: *
Allow: /
Disallow: /latam/admin/
Disallow: /latam/send-contact-form.php
Disallow: /latam/send-work-form.php

Sitemap: https://mslcorporate.com/sitemap.xml
```

(Ajustar Disallow adicionales si aparecen rutas internas sensibles durante la implementación.)

### `sitemap.xml`

Incluye:

- `https://mslcorporate.com/`
- `https://mslcorporate.com/latam/` (+ variantes `?lang=en`, `?lang=pt`)
- `https://mslcorporate.com/latam/about-us` (+ `lang` no default)
- `https://mslcorporate.com/latam/services` (+ `lang` no default)
- `https://mslcorporate.com/latam/offices` (+ `lang` no default)
- `https://mslcorporate.com/latam/contact-us` (+ `lang` no default)

No incluye: admin, formularios POST, assets, anclas `#`.

Corporate multi-idioma es client-side (misma URL): una sola entrada en sitemap para `/`.

---

## 8. Restricciones de implementación

1. **Cero cambio de copy visible** (textos en pantalla, labels, párrafos, botones).
2. Headings: solo nivel de tag.
3. Imágenes: solo `alt` (y aria decorativo si ya existe el patrón).
4. No agregar dependencias externas.
5. Reutilizar i18n LATAM (`i18n.php`, `urlWithLang`) y i18n Corporate (`i18n.js`) existentes.
6. Antes de crear funciones nuevas, reutilizar las existentes del código tocado (p. ej. `urlWithLang` para armar canonical y links).

---

## 9. Criterios de aceptación

- Cada página pública tiene title y meta description únicos y coherentes al idioma.
- Un solo H1 visible/semántico por página; H2/H3 estructuran secciones.
- URLs públicas LATAM funcionan sin `.php`; `.php` redirige 301.
- Imágenes relevantes tienen `alt` útil o vacío decorativo.
- `https://mslcorporate.com/robots.txt` y `.../sitemap.xml` accesibles.
- Contenido visual y textos de UI idénticos a pre-cambio (salvo jerarquía semántica y metas en `<head>`).

---

## 10. Riesgos y mitigaciones

| Riesgo | Mitigación |
|--------|------------|
| Hosting sin `mod_rewrite` | Documentar requisito Apache; fallback: URLs `.php` siguen existiendo vía 301 solo si rewrite está activo |
| Links hardcodeados con `.php` | Barrido de navbar/footer/CTAs/JS |
| Corporate i18n no actualiza metas | Extender `i18n.js` explícitamente para title + description |
| Canonical inconsistente con sitemap | Misma función/helper de URL canónica y misma convención de `lang` default |
