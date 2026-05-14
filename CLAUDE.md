# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

MS Clean is a real service business website (Küchenmontage, Reinigung & Umzug, Baden-Württemberg). The codebase is a static HTML5 multi-page site — no build system, no package manager, no test framework. Changes go directly into HTML/CSS/JS files.

## Local Development

```bash
# Static-only (no forms)
python3 -m http.server 8000

# With PHP (required for contact form)
php -S localhost:8000

# DDEV (Docker-based, includes Mailpit for email testing)
ddev start && ddev launch
```

## Architecture

### Page Anatomy

Every page follows the same boilerplate:
1. `<head>` — five Google Font imports + ~13 CSS includes (always in this order: vendor → `color.css` → `style.css` → `responsive.css`)
2. `.loader-wrap` preloader
3. `.main-header` with `.sticky-header` duplicate (both share the same nav HTML)
4. `.mobile-menu` — populated automatically at runtime by `script.js` cloning `.main-menu`'s HTML
5. Page-specific content sections
6. `.main-footer`
7. Script includes: jQuery → plugins → `script.js`

When creating a new page, copy the full header/footer block from an existing active page, not from `archiv/`.

### Active Pages

| File | Purpose | Status |
|------|---------|--------|
| `index.html` | Startseite | Done |
| `service.html` | Küchenmontage (Hauptseite) | Done |
| `reinigung.html` | Reinigung (Hauptseite) | Done |
| `umzug.html` | Umzug (Hauptseite) | Done |
| `about.html` | Über uns | Done |
| `einsatzgebiet.html` | Einsatzgebiet | Done |
| `contact.html` | Kontakt | Done |
| `appointment.html` | Angebot anfordern | Done |
| `faq.html` | FAQ | Done |
| `error.html` | 404-Fehlerseite | Done |

### Pages Still to Create

**Service sub-pages:**
- `kuechenmontage/ikea.html`, `abbau.html`, `aufbau.html`, `arbeitsplatte.html`, `elektroanschluss.html`
- `reinigung/grundreinigung.html`, `unterhaltsreinigung.html`, `bueroreinigung.html`, `fensterreinigung.html`, `fassadenreinigung.html`
- `umzug/privatumzug.html`, `bueroumzug.html`, `kueche-umzug-aufbau.html`, `moebelmontage.html`

**Local SEO pages:**
- `einsatzgebiet/kuechenmontage-mannheim.html`, `-karlsruhe.html`, `-stuttgart.html`, `-heidelberg.html`, `-ludwigshafen.html`, `-heilbronn.html`

**Legal (required before launch):**
- `impressum.html`
- `datenschutz.html`

### CSS Load Order

Stylesheet order matters — always load in this sequence:
```html
font-awesome-all.css → flaticon.css → owl.css → bootstrap.css → jquery.fancybox.min.css
→ animate.css → nice-select.css → [timePicker.css if page has time input] → odometer.css
→ elpath.css → color.css → style.css → responsive.css
```

`color.css` controls all theme accent colors — edit it to change the color scheme site-wide.

### JavaScript

`assets/js/script.js` initializes all plugins on DOM ready. Plugin init order within the file:
- Preloader → header scroll fix → mobile nav clone → WOW.js animations
- Owl Carousel (hero/testimonials) → Isotope (portfolio grids) → Odometer (counters)
- jQuery Nice Select → jQuery Fancybox → date/time pickers → form validation

`validation.js` is a separate file for jQuery Validate rules — included on contact and appointment pages.

### Contact Form

`contact.html` → POST → `sendemail.php` → redirects to:
- `contact.html?message=Erfolgreich` on success
- `contact.html?message=Failed` on failure

Configure `RECIPIENT_NAME` and `RECIPIENT_EMAIL` constants at the top of `sendemail.php`. Note: `php mail()` won't deliver locally — use DDEV (Mailpit at `http://localhost:8025`) to capture outgoing mail during development.

### Archiv (`archiv/`)

Unused template variants kept as copy-paste reference. Asset paths inside these files are broken (they need `../assets/` not `assets/`). Useful references:

| File | Useful for |
|------|-----------|
| `project.html`, `project-2/3.html` | Isotope filter grid → Referenzen/Galerie |
| `project-details.html` | Vorher/Nachher detail layout |
| `testimonial.html` | Full-page reviews layout |
| `team.html` | Team member cards |
| `blog-details.html` | Long-form text page layout (SEO pages) |
| `index-2/3/4.html` | Alternative hero and CTA block patterns |

### Keep Track of Changes

Update this file after every major change.

## Changelog

### 2026-05-14 — Full rebrand + German translation
- Rebranded all active pages from "Kitchnox" template to "MS Clean"
- Preloader animation changed from "kitchnox" (8 letters) to "msclean" (7 letters) across all pages
- All remaining English strings translated to German site-wide
- `sendemail.php` success redirect: `?message=Successfull` → `?message=Erfolgreich`
- `appointment.html` `lang="en"` fixed to `lang="de"`
- `assets/css/style.css` comment updated to reference MS Clean
