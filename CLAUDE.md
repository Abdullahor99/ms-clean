# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Kitchnox is a static HTML5 multi-page website template for a kitchen/creative services business. There is no build system, package manager, or test framework — changes are made directly to HTML, CSS, and JS files and previewed in a browser.

## Development

No build step required. Open HTML files directly in a browser or serve locally:

```bash
# Quick local server (Python)
python3 -m http.server 8000

# Or with PHP (needed for contact form)
php -S localhost:8000
```

The contact form (`sendemail.php`) requires a PHP server to function.

## Architecture

### File Layout

- `*.html` — 7 active pages at root (see Active Pages below)
- `archiv/` — 13 archived template variants (kept as element reference, not served)
- `assets/css/` — All stylesheets
- `assets/js/` — All scripts
- `assets/fonts/` — Font Awesome and custom fonts
- `assets/images/` — Site imagery
- `knowladge/` — MS Clean business knowledge & planned site structure

### Active Pages (MS Clean)

| File | Purpose |
|------|---------|
| `index.html` | Startseite |
| `service.html` | Küchenmontage (Hauptseite) |
| `about.html` | Über uns |
| `contact.html` | Kontakt |
| `appointment.html` | Angebot anfordern |
| `faq.html` | FAQ |
| `error.html` | 404-Fehlerseite |

### Archiv (`archiv/`)

Unused template variants — kept because they contain reusable elements:

| File | Useful Element |
|------|---------------|
| `index-2/3/4.html` | Alternative Hero-Layouts, CTA-Blöcke |
| `project.html`, `project-2/3.html` | Isotope-Filter-Grid → Referenzen/Galerie |
| `project-details.html` | Vorher/Nachher-Detail-Layout |
| `testimonial.html` | Vollseitige Bewertungsseite |
| `team.html` | Team-Member-Cards (für about.html) |
| `blog-details.html` | Langer Textseiten-Layout (für SEO-Texte) |
| `service-2.html` | Alternate Service-Abschnitt-Reihenfolge |

> Pfade in archivierten Dateien zeigen auf `assets/` (gebrochen nach dem Verschieben). Zum Prüfen temporär auf `../assets/` ändern.

### Pages still to create

- `reinigung.html` + Unterseiten (grundreinigung, unterhaltsreinigung, bueroreinigung, fensterreinigung, fassadenreinigung)
- `umzug.html` + Unterseiten (privatumzug, bueroumzug, kueche-umzug-aufbau, moebelmontage)
- `kuechenmontage/` Unterseiten (ikea, abbau, aufbau, arbeitsplatte, elektroanschluss)
- `einsatzgebiet.html` + Stadtseiten (mannheim, karlsruhe, stuttgart, heidelberg, ludwigshafen, heilbronn)
- `impressum.html`
- `datenschutz.html`

### CSS Structure

| File | Purpose |
|------|---------|
| `style.css` | Primary custom styles |
| `color.css` | Theme color overrides (swap to change color scheme) |
| `responsive.css` | Breakpoint-specific rules |
| `bootstrap.css` | Bootstrap 4 grid/components |
| `animate.css`, `owl.css`, etc. | Third-party component styles |

### JavaScript Architecture

`assets/js/script.js` is the main custom JS file (~520 lines). It initializes all third-party plugins on DOM ready:
- **Owl Carousel** — hero sliders, testimonial carousels
- **WOW.js** — scroll-triggered entrance animations
- **Isotope** — portfolio/project filtering by category
- **Odometer** — animated number counters
- **jQuery Nice Select** — styled `<select>` dropdowns
- **jQuery Fancybox** — image/video lightboxes
- **jQuery Validate** — contact/appointment form validation

### Contact Form Flow

`contact.html` → POST → `sendemail.php` → redirects to `contact.html?success=1` or `?fail=1`. Configure the recipient address inside `sendemail.php`.

### Theming

To change the site color scheme, edit `assets/css/color.css`. CSS custom properties (or direct hex replacements) in that file control accent and primary colors site-wide.


### keep track of changes
update this file after every major change to be aware of the changes
