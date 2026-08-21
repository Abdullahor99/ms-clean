# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Ms Clean is a real service business website (Umzugsservice, Sitz in Frankenthal/Pfalz, Einsatzgebiet 200 km um Mannheim). The codebase is a static HTML5 multi-page site — no build system, no package manager, no test framework. Changes go directly into HTML/CSS/JS files.

**Schreibweise: „Ms Clean", nie „MS Clean".** Die aktiven Seiten schreiben es 105× so und 0× anders. Ältere Changelog-Einträge weiter unten verwenden noch die falsche Form — die bleiben als historische Notiz stehen, sind aber kein Vorbild.

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
| `umzug.html` | Umzug (einzige Leistungsseite) | **Deaktiviert** (Nav-Link auskommentiert, 302 auf `/`) |
| `einsatzgebiet.html` | Einsatzgebiet | Done |
| `kontakt.html` | Kontakt | Done |
| `angebot-anfordern.html` | Angebot anfordern | Done |
| `faq.html` | FAQ | Done |
| `error.html` | 404-Fehlerseite | Done |
| `impressum.html` | Impressum | Done |
| `datenschutz.html` | Datenschutz | Done |
| `danke.html` | Danke nach Formular | Done |
| `blog.html` | Blog – Beitragsübersicht | Hidden (Nav-Link auskommentiert) |
| `blog-details.html` | Blog – Einzelbeitrag | Hidden (Nav-Link auskommentiert) |

### Geschäftsfokus: nur noch Umzug

Küchenmontage und Reinigung werden **nicht mehr angeboten**. `kuechenmontage.html`
und `reinigung.html` wurden gelöscht, sämtliche Inhalte, Navigation, Formulare und
strukturierten Daten sind auf Umzug umgestellt. Ältere Notizen in `knowladge/`
stammen aus der Zeit davor und sind inhaltlich überholt.

### Pages Still to Create

**Service sub-pages:**
- `umzug/privatumzug.html`, `bueroumzug.html`, `moebelmontage.html`, `wohnungsaufloesung.html`

**Local SEO pages:**
- `einsatzgebiet/umzug-mannheim.html`, `-karlsruhe.html`, `-stuttgart.html`, `-heidelberg.html`, `-ludwigshafen.html`, `-heilbronn.html`

**Für beide gibt es den Skill `/schreibe-content`** (`.claude/skills/schreibe-content/`).
Er liefert Tonfall, Faktenblatt, Seitengliederung und die HTML-Bausteine und arbeitet
zweistufig: erst ein Text-Entwurf zur Freigabe, dann das HTML. Beachten: beide
Seitentypen liegen in einem **Unterordner**, alle Asset-Pfade müssen dort auf
`../assets/…` umgestellt werden.

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

### Formulare

Es gibt drei Formulare und zwei Mail-Skripte:

| Formular | action | Pflichtfelder |
|---|---|---|
| `kontakt.html` | `sendemail.php` (`formsource=kontakt`) | Name, E-Mail, Telefon, Nachricht |
| `faq.html` (Fragebox) | `sendemail.php` (`formsource=faq`) | Name, E-Mail, Betreff, Frage — **kein** Telefon |
| `angebot-anfordern.html` | `sendemail-appointment.php` | Name, E-Mail, Telefon + per JS erzeugtes `message` |

Alle drei enden bei Erfolg auf `danke.html` (dort feuert die Google-Ads-Conversion).
Im Fehlerfall geht es zurück zur Ausgangsseite mit `?message=Failed` bzw. `?status=Fehler`.

**Jedes Formular braucht drei Dinge**, sonst weist das PHP die Absendung ab:
- `<input type="hidden" name="form_ts" class="form-ts">` — `script.js` (`formTimestamp()`) füllt den Zeitstempel; serverseitig wird alles unter 3 Sekunden verworfen
- `.hp-field` mit `name="website"` — Honeypot; ist er gefüllt, wird stillschweigend verworfen
- `name="privacy"` Checkbox mit `required` — DSGVO-Einwilligung, serverseitig geprüft

Bots werden bewusst auf `danke.html` geleitet (nicht auf eine Fehlerseite), damit sie
nicht erkennen, dass sie geblockt wurden.

`RECIPIENT_NAME`/`RECIPIENT_EMAIL` stehen oben in beiden PHP-Dateien. Zugangsdaten in
`smtp-config.php` (schaltet automatisch zwischen DDEV/Mailpit und Hostinger um).
Lokal testen mit DDEV — Mailpit läuft unter `http://localhost:8025`.

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

### 2026-08-19 — `umzug.html` vorübergehend deaktiviert

Die Seite wird überarbeitet und ist bis dahin nicht mehr erreichbar. **Umbenannt wurde
nichts** — die Datei heißt weiter `umzug.html` und liegt vollständig auf der Platte.
Vorgehen wie beim Blog-Rückbau vom 2026-08-10.

| Was | Wo | Anzahl |
|---|---|---|
| Nav-Eintrag auskommentiert | alle 12 aktiven Seiten | 12 |
| Footer-Eintrag „Leistungen → Umzug" auskommentiert | alle 12 aktiven Seiten | 12 |
| Galerie-Links auf `angebot-anfordern.html` umgebogen | nur `index.html` | 8 |

**Kein JS geändert:** `script.js` klont `.main-menu` zur Laufzeit in Sticky-Header und
Mobilmenü — die auskommentierte Zeile verschwindet dadurch automatisch aus allen drei
Menüs. Im gerenderten DOM stehen 4 Treffer für `href="umzug.html"`, **alle innerhalb von
Kommentarknoten**, 0 aktive.

Auf `umzug.html` selbst trug der Eintrag `class="current"` — auch der ist auskommentiert.
Die vier Selbstreferenzen der Seite (`canonical`, `og:url`, JSON-LD `url`,
Breadcrumb-`item`) blieben **unangetastet**, sonst müsste man sie beim Wiedereinschalten
rekonstruieren.

**Die vier Galerie-Kacheln behalten ihre Klickfläche** (je Icon-Link + Überschriften-Link):
Privatumzug → `angebot-anfordern.html?leistung=privatumzug#anfrage` (der Slug existiert
in der Whitelist, `angebot-anfordern.html:850`), die drei anderen ohne Parameter auf
`#anfrage` — für Möbeltransport, Umzugshelfer und Schwergut gibt es keinen passenden
Slug. Die `aria-label` sind von „Mehr über … erfahren" auf „Angebot für … anfordern"
umgestellt.

**`.htaccess`: bewusst `Redirect 302`, nicht 301.** Ein 301 wird von Browsern hart
gecacht und von Google dauerhaft auf `/` konsolidiert — wer `/umzug.html` einmal
aufgerufen hat, landet auch nach dem Wiedereinschalten auf der Startseite, ohne den
Server zu fragen. Der 302 verhindert den 404, ohne die URL zu verbrennen. Wird die Seite
später ersatzlos gestrichen, kann man immer noch auf 301 umstellen.

**Bewusst nicht gemacht:** kein `Disallow` in `robots.txt` (Google würde die Weiterleitung
dann gar nicht sehen) und kein `noindex` in `umzug.html` (die Weiterleitung erledigt das;
ein vergessenes `noindex` wäre beim Wiedereinschalten ein stiller Fehler).
`sitemap.xml` hat den Eintrag verloren und führt jetzt 7 URLs.

**Zum Wiedereinschalten** sind vier Dinge nötig: die 24 auskommentierten `<li>`-Zeilen,
die 8 Links auf `index.html`, die `Redirect 302`-Zeile in `.htaccess` und der
`<url>`-Block in `sitemap.xml`.

**Im Blick behalten:** `umzug.html` ist die **einzige Leistungsseite** und trägt das H1
„Umzugsservice in Mannheim und Umgebung" — nach der Startseite die stärkste
SEO-Landingpage für „Umzug Mannheim". Für die Dauer der Überarbeitung ist das in Ordnung,
als Dauerzustand nicht.

**Geprüft:** 24 Verweise, davon 0 aktive · `div`-Balance aller 12 Seiten unverändert
ausgeglichen · `sitemap.xml` valide, ohne `umzug.html` · Menü zeigt Startseite ·
Einsatzgebiet · FAQ · Kontakt ohne Lücke, Footer-Spalte „Leistungen" nur noch
„Angebot anfordern" · `?leistung=privatumzug` füllt weiterhin die `.bid-selected-note`
(„Ihre gewählte Leistung: Privatumzug"). Der 302 ist lokal **nicht** testbar (kein
Apache) — nach dem Deploy einmal `curl -I https://msclean-mannheim.de/umzug.html`.


### 2026-08-19 — Falsche Zusage „dieselbe Person" entfernt

Vom Inhaber klargestellt: **Wer die Anfrage bearbeitet, ist nicht dieselbe Person, die
am Umzugstag da oder erreichbar ist.** Die Website hat das an **zwei** Stellen behauptet,
beide auf `index.html` und nur eine Bildschirmhöhe auseinander.

| Stelle | vorher | jetzt |
|---|---|---|
| About, Absatz 2 (`:587`) | „Bei uns bearbeitet dieselbe Person Ihre Anfrage, die später auch am Umzugstag erreichbar ist …" | zwei Absätze: Erreichbarkeit Mo–So 8–20 Uhr + Antwort in 24 Stunden + Festpreis · und die Bewertungen als nachprüfbarer Beleg |
| Vertrauens-Kachel (`:827`) | „Ein fester Ansprechpartner / Dieselbe Person von der ersten Anfrage bis zum letzten Karton." | „Sie rufen an, es geht jemand ran / Keine Warteschleife, keine Ticketnummer, kein Rückrufformular …" |

Das Versprechen wandert von „immer **dieselbe Person**" auf „immer **jemand**" — belegbar
und für den Anrufer fast genauso viel wert. Der gestrichene Satz wurde nicht durch ein
anderes Versprechen ersetzt, sondern durch das, was der Leser selbst prüfen kann.

Ein Listenpunkt im About-Block getauscht: „Erreichbar Mo–So, 8:00–20:00 Uhr" →
**„Festpreis und Vollversicherung bei jedem Auftrag"**. Die Erreichbarkeit steht nach der
Änderung im Fließtext direkt daneben; der Platz trägt mehr, wenn dort die zweite große
Sorge („Meine Sachen gehen kaputt") beantwortet wird.

**Vom Inhaber bestätigt und deshalb unverändert geblieben:** die Kachel „Pünktliches
Team" („dasselbe Team – keine Tagelöhner") und die Premium-Paket-Zeile „Persönlicher
Ansprechpartner" (4 Stellen: `index.html:686`, `umzug.html:583` und beide JSON-LD-Blöcke)
— das ist eine echte Leistung **dieses Pakets**, keine allgemeine Zusage.
Ebenfalls unverändert: „Sechs Leistungen, ein Ansprechpartner, ein Festpreis"
(`index.html:512`, gemeint ist ein Betrieb statt mehrerer Firmen) und
`einsatzgebiet.html:519` (Aussage über Erreichbarkeit).

**Skill nachgezogen**, sonst schreibt der nächste `/schreibe-content`-Lauf die falsche
Aussage zurück: `references/stimme.md` Säule 2 hatte „Dieselbe Person von der
Besichtigung bis zum letzten Karton." ausdrücklich **empfohlen** — die Zeile steht jetzt
in der ❌-Spalte mit Begründung. `references/fakten.md` hat zwei neue Zeilen (feste
Belegschaft: ja · persönlicher Ansprechpartner: nur Premium) und den Hinweis, dass
Personenkontinuität nirgends zugesagt werden darf.

**Geprüft:** kein „dieselbe Person" mehr im Markup · `div`-Balance 266/266 · die neue,
längere Kachelüberschrift bleibt bei 1440px und 1100px zweizeilig (dort ist die Spalte
am schmalsten).


### 2026-08-19 — Neuer Bildbestand `assets/ai-images/`, Startseite umbebildert

16 hochgeladene Dateien mit Generator-Namen (`ChatGPT Image Aug 17, 2026, 01_57_29 PM.webp`,
`movers_carrying_refrigerator_5.webp`) sind aufgeräumt und auf `index.html` eingebaut.
Zwei Dateien waren **bytegleich** (MD5 `220cf2fe…`), eine davon ist gelöscht — bleiben 15.

**Namenskonvention:** deutsch, klein, Bindestriche, Umlaute umschrieben (`ue`/`ae`), kein
Ersteller und kein Datum — wie der Bestand in `assets/real-images/`. Beispiele:
`vier-umzugshelfer-verladen-kuehlschrank.webp`, `klaviertransport-ueber-treppe-altbau.webp`,
`schwerlast-auf-moebelheber-gesichert.webp`.

**Alle 15 Dateien neu kodiert** (`cwebp`, liest WebP direkt ein): **5,9 MB → 1,5 MB**.
Zielgrößen: Hero 1600px/q78, Galerie 1200px/q75 (die Datei ist zugleich das
Lightbox-Ziel), About und Festpreis 900px/q78, Restbestand längste Kante 1600px/q78.
Hochformate über die **Höhe** skalieren (`cwebp -resize 0 <h>`), sonst werden sie unnötig
groß. Die sieben eingebauten Fotos wiegen zusammen **616 KB**, der Hero 141 KB.

**Sieben Austausche auf `index.html`:**

| Stelle | vorher | jetzt |
|---|---|---|
| Hero (`:19` Preload **und** `:403` `bg-layer`) | `hamody7.webp` | `vier-umzugshelfer-verladen-kuehlschrank.webp` |
| About „Über Ms Clean" | `man-macht-umzug.webp` | `kuehlschrank-in-umzugswagen-verladen.webp` |
| Galerie 1 Privatumzug | `ein-man-mit-umzugs-kartons.webp` | `sofa-verpackt-zum-umzugswagen-tragen.webp` |
| Galerie 2 Transport | `ein-umzug-wagen.webp` | `umzugswagen-vor-lagerhalle.webp` |
| Galerie 3 Umzugshelfer | `man-macht-umzug.webp` | `umzugshelfer-mit-umzugskartons.webp` |
| Galerie 4 (Thema gewechselt) | `hamody2.webp` | `klaviertransport-ueber-treppe-altbau.webp` |
| Festpreis-Block (`transforming-section`) | `man-macht-umzug.webp` | `schwerlast-auf-moebelheber-gesichert.webp` |

`man-macht-umzug.webp` stand vorher **dreimal** auf derselben Seite (Person liegt hinter
Kartons am Boden), `ein-umzug-wagen.webp` zeigte einen fremden VW-Pritschenwagen unter
der Unterschrift „Sicherer Möbeltransport". Beide Dateien bleiben liegen — `blog.html`,
`blog-details.html` und `umzug.html` benutzen sie weiter. Frei geworden und jetzt
ungenutzt: `hamody2.webp`, `hamody7.webp`.

**Falle, die man vor dem nächsten Bildtausch kennen muss:** Der Hero-`bg-layer` ist nur
`width: calc(50% + 200px)`, rechtsbündig, `background-position: right center`
(`style.css:1866`). Das **linke Bilddrittel wird abgeschnitten**, links steht der Text auf
`#1d2a28`. Ein Hero-Motiv muss sein Geschehen also **rechts** tragen. Und der
`<link rel="preload">` in Zeile 19 muss immer mitgezogen werden, sonst lädt die Seite ein
Bild vor, das sie nicht mehr benutzt.

**Kachel 4 der Galerie hat das Thema gewechselt**: „Möbelmontage nach dem Umzug" →
„Klavier und Schwergut sicher transportiert". Grund: kein einziges der neuen Bilder zeigt
eine Montage, und die alte Kombination (Unterschrift „Möbelmontage", Foto: Mann mit
Kartons auf der Straße) passte ohnehin nicht zusammen. Möbelmontage bleibt als Leistung
über die Service-Karte und `?leistung=moebelmontage` erreichbar.

**Die Bilder sind KI-generiert — der Galerietext trägt dem Rechnung.** Überschrift
„Unsere neuesten Umzugsprojekte" → **„Das bewegen wir für Sie"**, Intro von
„Auswahl unserer abgeschlossenen Umzugsprojekte" auf eine Leistungsbeschreibung
umgestellt, darunter neu „Abbildungen sind Symbolbilder."
(`<p class="image-note">`, eine CSS-Regel bei `.project-section .title-box .text-box p`).
Generierte Bilder als dokumentierte Aufträge auszugeben wäre dieselbe UWG-Falle, wegen
der am 2026-08-14 die erfundenen Testimonials geflogen sind.

**Eine neue Layout-Regel:** `.about-section .image-box .image-cover img` bekommt
`height: 600px; object-fit: cover` (≤767px: 420px). Ohne sie wäre das Hochformat in der
470px-Spalte 1014px hoch geworden — der Textblock daneben misst rund 600px. Die Regel
hängt bewusst an der **Zusatzklasse** `image-cover`: `umzug.html` benutzt dieselbe
`.about-section` und bleibt unverändert. Querformat funktioniert an dieser Stelle nicht:
`cover` schneidet dort seitlich auf 52 % zu und köpft die äußeren Personen.

**Nicht eingebaut** (umbenannt, verkleinert, für `umzug.html` und die geplanten
Unterseiten verfügbar): `umzug-collage-vier-arbeitsschritte`,
`umzugshelfer-mit-kartons-rueckansicht`, `umzugsteam-vor-umzugswagen`,
`zwei-umzugshelfer-vor-umzugswagen`, `umzugsteam-traegt-geraet-ueber-laderampe`,
`drei-umzugshelfer-tragen-kuehlschrank`, `umzugsteam-traegt-geraet-vor-sprinter`,
`umzugsteam-traegt-verpacktes-moebelstueck` (die letzten vier sind sich sehr ähnlich).

**Geprüft** (Chrome headless, 1440px und 390/500px): kein toter Asset-Pfad ·
`div`-Balance 266/266 · alle vier Galerie-Kacheln gleich hoch (420px, `object-fit`
greift) · About-Spalte ausgewogen · Hero-Motiv sitzt im rechten Ausschnitt · kein
„MS Clean" im Markup. **Nicht per PHP getestet:** das lokale `php` ist defekt
(`icu4c`-Version, `libicuio.73.dylib` fehlt) — geprüft wurde über
`python3 -m http.server`, Formulare also nicht.


### 2026-08-19 — Aktions-Sidebar, Header scrollt auf Mobil mit

Zwei Conversion-Themen in einem Durchgang. Beide betreffen **alle 12 aktiven Seiten**.

**1. Neue Aktions-Sidebar** (`.action-sidebar`), fixiert, mit Telefon und
Angebotsformular. Desktop rechts mittig, ab **≤991px** unten mittig — genau in die
Lücke zwischen WhatsApp (`left: 20px`) und Scroll-to-Top (`right: 30px`). Das Markup
steht im bestehenden Floating-Block nach `</footer>`, direkt zwischen den beiden.

Bei Hover/`:focus-visible` fährt ein Label nach links aus (56px → 272px). **Die 272px
sind gemessen, nicht geschätzt:** „Angebot anfordern" braucht 211px Labelbreite +
56px Icon = 267px. Wer die Beschriftung verlängert, muss nachmessen, sonst wird der
Text vom `overflow: hidden` abgeschnitten.

**Falle, in die der erste Entwurf gelaufen ist:** Label und Icon als Flex-Kinder
funktionieren *nicht*. Das Icon hat `flex: 0 0 56px` und kann nicht schrumpfen, das
Label schrumpft nur bis auf sein Padding — zusammen 76px in einer 56px breiten
Ruhekachel. Ergebnis: das Icon wird nach rechts aus der Kachel geschoben und ist
unsichtbar, die Kachel bleibt leer orange. Deshalb ist **das Label absolut
positioniert** (`right: 56px`), das Icon ebenfalls (`right: 0`). In Ruhe liegt das
Label komplett außerhalb der Kachel und wird weggeschnitten; beim Aufziehen wandert
es hinein.

`z-index: 9990` — über `.main-header`/`.sticky-header`/`.whatsapp-float` (alle 999),
unter `.scroll-to-top` (90000) und weit unter `.mobile-menu` (999999). Damit legt
sich das geöffnete Burger-Menü korrekt darüber, ohne Zusatzregel.

Auf Mobil sind es zwei 56px-Kreise mit 14px Abstand. **Platzrechnung bei 320px:**
WhatsApp bis x=76, Sidebar 97–223, Scroll-to-Top ab x=240. Bleibt kollisionsfrei,
ist dort aber eng — wer Größe oder Abstand erhöht, muss das nachrechnen.

**2. Der Header scrollt jetzt auch auf Mobil mit.** Ursache war eine einzige Zeile:
`responsive.css` hat `.sticky-header` ab ≤1200px zusammen mit `.main-menu` per
`display: none !important` abgeschaltet. Der Nutzer kam nach dem ersten Scroll nicht
mehr ans Burger-Menü. `.sticky-header` ist aus dieser Liste gestrichen, **`.main-menu`
bleibt drin** — dadurch bleibt das per JS in den Sticky-Header geklonte Desktop-Menü
dort automatisch verborgen und es bleiben Logo + Burger übrig. Genau das ist gewollt.

Dafür brauchte `.sticky-header .menu-area` einen **eigenen `.mobile-nav-toggler`** —
der fehlte dort auf allen 12 Seiten, weil der Sticky-Header auf Mobil nie sichtbar
war. **Kein JS geändert:** `script.js:61` bindet `$('.mobile-nav-toggler')` beim
DOM-Ready an *alle* vorhandenen Toggler, der zweite steht jetzt im Markup.

**3. Begrüßungssatz „Willkommen bei Ms Clean …" ab ≤991px ausgeblendet**
(`.header-top .text-box`). Der Kopfbereich auf dem Handy schrumpft damit von vier auf
zwei Zeilen. Betrifft nur die 7 `header-style-five`-Seiten; die 5 Seiten der Variante A
(index, blog, blog-details, datenschutz, impressum) haben gar keine `.text-box`.
Auf Desktop bleibt der Satz stehen.

**Geprüft** (Chrome via CDP, echte Viewports, alle 12 Seiten bei 1440px und 390px):
`div`-Balance überall ausgeglichen (+2 pro Seite) · Sidebar an der richtigen Position,
kein Überlapp mit WhatsApp oder Scroll-to-Top · beide Icons innerhalb ihrer Kachel ·
kein horizontaler Dokument-Überlauf · Sticky-Header sichtbar, Burger sichtbar und
öffnet das Menü · Begrüßung ab 991px weg · Breakpoint-Wechsel bei 991/992px korrekt ·
beide Header-Varianten (dunkel und `header-style-five`) getestet · keine
Konsolenfehler außer dem in der Sandbox blockierten gtag.js.

**Dabei aufgefallen, nicht behoben:** `datenschutz.html` lädt `scrolltop.min.js`
nicht — der Scroll-to-Top-Button ist dort dauerhaft unsichtbar und funktionslos.
Dazu schaltet `script.js:23,26,29` die Klasse `.open` auf dem Selektor
`$('.scroll-top')`, den es im Markup nirgends gibt (das Element heißt
`.scroll-to-top`) — toter Code.

### 2026-08-19 — Radius auf 200 km, „mehr als 800", Premium-Badge entfernt

Drei vom Inhaber bestätigte Faktenkorrekturen. Die ersten beiden sind Textersetzungen,
die dritte zieht sich durch alle Seiten, die strukturierten Daten und die Städteliste.

**1. „mehr als 800 Umzüge in zwei Jahren."** Alle sechs Fundstellen liegen in
`index.html` (Meta/og/twitter, Hero-`sub-title`, Karte Privatumzug, Über-uns-`h2`,
Listenpunkt, Festpreisgarantie). Auch die Stellen, die vorher „Über 800" schrieben,
sind mit vereinheitlicht — sonst stünden zwei Formulierungen nebeneinander.
**Bewusst unverändert:** die Zahlenkachel `800+`. Das `+` sagt dasselbe, und
„Mehr als 800" als `stats-value` würde die Kachelreihe optisch sprengen.

**2. Badge „Empfohlen" beim Premium-Paket entfernt** (`index.html`, `umzug.html`).
Die Klasse `.bid-card-premium` bleibt, die Karte behält Goldkopf und dunklen Rahmen.
Dazu **eine neue CSS-Regel**: `.bid-card-head h3` hält global `padding-right: 90px`
fürs Badge frei; ohne Badge ist der Platz verschenkt und stand zwischen 992 und
1199px (Spalte nur ~320px) unnötig eng am Umbruchrand. Deshalb
`.bid-card-premium .bid-card-head h3 { padding-right: 0; }`.

**3. Einsatzradius 130 → 200 km, plus „auf Anfrage auch darüber hinaus".**
Ein Satzmuster site-weit, damit keine Seite der anderen widerspricht:

> Im Umkreis von 200 km um Mannheim sind wir Ihr Anbieter vor Ort —
> auf Anfrage fahren wir auch darüber hinaus.

Betroffen: `geoRadius` (130000 → 200000) und die JSON-LD-`description` auf **11**
Seiten · der Sidebar-Satz „Unser erfahrenes Team ist im Umkreis von … unterwegs" auf
**11** Seiten (`umzug.html` hat diesen Block gar nicht — die Zahl 12 aus dem Plan war
falsch) · Fließtext auf `index.html` (4 Stellen + Button), `umzug.html`, `kontakt.html`,
`faq.html` und `einsatzgebiet.html` (Title, 3× Meta-Paar, `h1`, `h2`, Intro, CTA).

**Die FAQ-Antwort steht doppelt** — `faq.html:203` im `FAQPage`-JSON-LD und `:509` als
Sichttext. Beide wurden wortgleich geändert; alles andere wäre ein Richtlinienverstoß.

**Städteliste auf `einsatzgebiet.html`: 26 → 39 Städte, fünf → sechs Bundesländer**
(neu: Nordrhein-Westfalen). Die 80–130-km-Gruppe hat drei Nachträge bekommen
(Tübingen ~115, Offenburg ~120, Reutlingen ~125), dazu eine neue Gruppe
**130–200 km**: Trier ~135 · Fulda ~145 · Marburg ~150 · Siegen ~160 · Ulm ~165 ·
Bonn ~170 · Freiburg im Breisgau ~175 · Bamberg ~180 · Nürnberg ~190 · Köln ~195.
Darunter neu ein Hinweis „Ihre Stadt steht nicht dabei?".

**Die Kilometerangaben der Liste sind Luftlinie ab Mannheim, keine Straßenkilometer.**
Das ist die Konvention des Bestands (Karlsruhe ~54, Frankfurt ~75, Würzburg ~110,
Koblenz ~115 — alles Luftlinienwerte) und war vorher nirgends dokumentiert. Wer eine
Stadt ergänzt, muss sie einhalten, sonst steht zweierlei Maß auf einer Seite.
*(Einziger Ausreißer im Bestand, unangetastet gelassen: Stuttgart steht mit ~110,
Luftlinie sind ~95 km.)*

**Nachgezogen**, sonst schreibt der nächste `/schreibe-content`-Lauf alles zurück:
`references/fakten.md` (Radius-Zeile, Radius-Absatz, Städtegruppen, Luftlinien-Regel,
Premium-Badge-Spalte auf „—"), `stimme.md`, `bausteine.md`, `seitentypen.md`,
`seo-und-recht.md`, `SKILL.md` sowie `sitemap.xml` (alle acht `lastmod` auf 2026-08-19).

**Geprüft:** `div`-Balance aller 12 Seiten unverändert ausgeglichen · JSON-LD aller
12 Seiten valide · `sitemap.xml` valide · FAQ-Antwort 2× identisch · kein `130`-Rest
außer den beabsichtigten Bandbeschriftungen `80 – 130` und `130 – 200`.

**Weiterhin offen** (unverändert): „MS Clean" in `site.webmanifest` (2×),
`datenschutz.html:448` und beiden Mail-Skripten · `impressum.html:378` (§ 19 UStG,
laut Inhaber veraltet) · das Header-Logo mit der falschen Schreibweise, das eine
neue Grafik braucht.


### 2026-08-17 — Startseite neu strukturiert und getextet

`index.html` folgt jetzt einer vorgegebenen Reihenfolge; der Bestandstext war der
klassische Fall aus dem Skill: „Ihr zuverlässiger Partner", „Qualität, die man sieht",
„Wir arbeiten nach demselben hohen Standard" — Sätze, die jeder Wettbewerber wortgleich
übernehmen könnte. Ersetzt durch Text, der Zahlen statt Adjektive trägt.

**Neue Abschnittsfolge** (drei bestehende Sektionen wurden nur verschoben, keine gelöscht):

```
Hero → 4 Schritte → Leistungs-Karten → Über Ms Clean → Pakete
     → Vertrauen + Zahlenleiste → Galerie → FAQ → Festpreis-Block → Einsatzgebiet
```

**Zwei neue CSS-Bausteine** (`style.css` am Dateiende, responsive in `responsive.css`):

| Baustein | Was |
|---|---|
| `.service-card` | Leistungs-Karte mit Icon, Text und Button. Gleiche Kante, gleicher Schatten, gleicher Hover wie `.trust-block-one` und `.bid-card` — eckig, 3px Goldkante, `translateY(-6px)`. `.service-card-btn` ist **nur ein Modifier von `.theme-btn`** (wie `.bid-card-btn`); ein eigener `:hover` würde die Zwei-Flächen-Animation killen |
| `.stats-block-one` | Zahlenleiste, vier Kacheln. `display:flex` + `justify-content:center`, weil nur zwei der vier eine Sternreihe tragen — ohne Zentrierung wirkte die Reihe verrutscht |

**Icons sind jetzt Font Awesome, nicht die PNGs.** Der Satz in `assets/images/icons/`
stammt aus dem Küchen-Template (Herdplatten, Schränke, Grundrisse) und hat **kein
einziges Umzugs-Motiv**. Betrifft die sechs Leistungs-Karten, die sechs
Vertrauens-Kacheln und die vier Ablauf-Schritte (`.working-block-one .icon-box .icon i`,
48px, Theme-Farbe). Font Awesome 6.2 Free Solid liegt vollständig lokal unter
`assets/fonts/fa-solid-900.woff2` — alle verwendeten Glyphen wurden gegen
`font-awesome-all.css` geprüft.

**Neu: Geschäftsfokus erweitert.** Vom Inhaber bestätigt: **Fernumzug (deutschlandweit)**
und **Entrümpelung** werden angeboten. Beides ist in `fakten.md`, im JSON-LD
(`makesOffer`) und in der Meta-Description ergänzt. **Achtung, offener Widerspruch:**
Der Fernumzug reicht über die 130 km hinaus, die restlichen elf Seiten tragen aber
weiterhin die reine 130-km-Aussage. Auf `index.html` ist das über die Formulierung
„innerhalb 130 km Ihr Anbieter vor Ort, darüber hinaus deutschlandweit" gelöst — die
anderen Seiten wurden bewusst nicht angefasst und sind eine eigene Aufgabe.

**Küchenmontage bleibt gestrichen.** Gewünscht war eine Karte „Küchenmontage"; sie heißt
jetzt „Küche mitnehmen & aufbauen" und ist ausdrücklich Teil des Umzugs. Das Verbot aus
dem Skill bleibt damit intakt.

**`?leistung=<slug>` verdrahtet.** Die sechs Karten-Buttons führen ins Angebotsformular
und übergeben die Leistung — gleiche Whitelist-Mechanik wie `?paket=` (nur bekannte
Slugs, sonst wird der Parameter verworfen). `angebot-anfordern.html` zeigt eine
`.bid-selected-note` und schreibt die Zeile in die Anfrage-Mail. Slugs: `privatumzug`,
`firmenumzug`, `fernumzug`, `entruempelung`, `moebelmontage`, `kueche`.
Gegen `<img src=x onerror=…>` in der URL getestet: Parameter wird verworfen.

**Nebenbei behoben — Galerie lief aus ihrem Hintergrund heraus.** Die vier Fotos haben
sehr verschiedene Seitenverhältnisse (1400×2100 hochkant gegen 1920×544 panorama), das
Raster hatte keine Höhenvorgabe. Jetzt `height: 420px; object-fit: cover` auf
`.project-block-one .inner-box .image-box img`. Vorbestehender Fehler, durch die
Verschiebung nur sichtbarer geworden.

**Geprüft:** `div`-Balance 267/267 · JSON-LD valide · 78 Verweise, kein totes Ziel ·
keine Konsolenfehler · kein Inhabername · keine gesperrte Fremdzahl · kein Euro-Betrag ·
alle 12 Kartenüberschriften bleiben bei 320/360/380px zweizeilig und alle Buttons
einzeilig.

**Weiterhin offen:** „MS Clean" steht noch in `site.webmanifest` (2×),
`datenschutz.html:448` und in beiden Mail-Skripten als Absendername. Dazu
`impressum.html:378` (§ 19 UStG, laut Inhaber veraltet).

**Neu aufgefallen:** Das **Header-Logo zeigt „MS Clean Umzüge"** — die falsche
Schreibweise steht also auf jeder Seite ganz oben. `assets/images/logo.svg` und
`logo-2.svg` enthalten keinen durchsuchbaren Text (die Wortmarke ist in Pfade
umgewandelt), deshalb hat kein bisheriges `grep` das gefunden und deshalb lässt es
sich auch nicht per Textersetzung beheben — dafür braucht es eine neue Grafik.

### 2026-08-17 — Skill `/schreibe-content` angelegt

Erster Skill im Projekt: `.claude/skills/schreibe-content/` (SKILL.md + fünf
Referenzdateien). Deckt lokale SEO-Seiten, Leistungs-Unterseiten und die Überarbeitung
bestehender Abschnitte ab — **keine** Blog-Artikel.

**Die Prämisse, die man kennen muss:** Der Bestandstext dieser Website ist für den Skill
**kein Vorbild, sondern das zu ersetzende Material.** Die Kritik daran lautete
*austauschbar, zu wenig Substanz, zu wenig lokal* — drei Symptome derselben Ursache:
Text, der nichts Nachprüfbares behauptet, kann jeder schreiben. Verbindlich am Bestand
sind nur HTML-Struktur und Fakten, nicht der Ton.

**Kernmechanik — die Substanz-Regel:** Nach jedem Absatz prüfen, ob ein Wettbewerber ihn
wortgleich übernehmen könnte. Wenn ja, fehlt der Beleg. Dazu eine Beweiskraft-Hierarchie:
Zahl → überprüfbarer Beleg → konkretes Beispiel → (Behauptung nur als letzte Wahl).

**Vertrauenszahlen** (vom Inhaber bestätigt, stehen in `references/fakten.md`):
über 800 Umzüge · seit 2 Jahren am Markt · ~5 Jahre Branchenerfahrung · Google 5,0 ·
Check24 4,8. Betriebsalter und Branchenerfahrung **nie vermischen** — der Betrieb ist
zwei Jahre alt, die Erfahrung älter.

**Zwei harte Verbote, die leicht verletzt werden:**
- **Kein Inhabername im Content.** Nur `impressum.html` und `datenschutz.html` dürfen ihn
  führen (§ 5 TMG / DSGVO); diese beiden Dateien fasst der Skill nicht an. Persönlichkeit
  läuft über Rolle und Erreichbarkeit („ein fester Ansprechpartner"), nicht über Namen.
- **Keine Zahlen von `schachservice.de`.** Die Seite diente als Stil-Vorbild und ist in
  Struktur und Paketnamen ohnehin schon Pate gestanden. Ihre Kennzahlen („15+ Jahre",
  „5.000+ Umzüge", „4.9", „312 Bewertungen", „geprüftes Fachpersonal") gehören einem
  anderen Betrieb — sie zu übernehmen wäre irreführende Werbung nach § 5 UWG. Die Liste
  steht namentlich in `fakten.md`.

Ablauf ist **zweistufig**: erst Markdown-Entwurf mit Warnliste offener Belege
(`[BELEG NÖTIG: …]`), Stopp, dann nach Freigabe das HTML.

**Offene Punkte, die dabei aufgefallen sind** (jeweils eigene Aufgabe):
1. `impressum.html:378` nennt die Kleinunternehmerregelung nach § 19 UStG. Laut Inhaber
   veraltet — und im Widerspruch zu „über 800 Umzüge" auf derselben Website. Vor dem
   Livegang neuer Texte korrigieren.
2. „MS Clean" steht noch an vier Stellen im Live-Code: `assets/Favicons/site.webmanifest`
   (2×, erscheint beim Installieren als Web-App), `datenschutz.html:448` (sichtbarer
   Rechtstext) und als Absendername in `sendemail.php` / `sendemail-appointment.php`
   (steht in **jeder** Kunden-E-Mail). Bewusst nicht mitgeändert, weil Rechtstext und
   ausgehende Mail-Identität betroffen sind.
3. Bewertungsanzahl ergänzen — „5,0 aus N Bewertungen" ist stärker und rechtlich
   sicherer als „5,0" allein.

### 2026-08-17 — Paket-Karten auf den Theme-Look gebracht

Die `.bid-card`-Karten sahen nach Fremdkörper aus. Der Grund war benennbar: sie
benutzten an vier Stellen Werte, die es **sonst nirgends** im Stylesheet gibt.
Alles davon ist jetzt weg.

| war | ist | warum |
|---|---|---|
| `.bid-card` `border-radius: 16px` | eckig, dazu `border-top: 3px solid var(--theme-color)` + Ruheschatten | die einzigen 16px im ganzen `style.css`; Karten sind hier eckig. Die Karte ist jetzt Geschwister von `.trust-block-one` (identische Kante, identischer Schatten, identischer Hover) |
| eigener Pill-Button `border-radius: 999px` | echter `.theme-btn` | siehe unten |
| `var(--cormorant)` in `h3` und `.bid-card-price` | Fira Sans (`h3`, 24/32/600 wie `.google-review-box h3`) bzw. Jost-Versal-Label (`.bid-card-price`, wie `.sub-title`) | **die einzigen zwei Cormorant-Nutzungen der Seite.** Die Schrift wird in `fonts.css` weiterhin geladen, ist aber jetzt nirgends mehr in Gebrauch |
| Kopf-Tönungen `#eef4fb`, `#eef1f6`, `#f2ede5` | `#f7f5f2` / `#f2efe9` | Blau und Kaltgrau in einer Gold-Slate-Palette. Jetzt nur noch die warmen Neutraltöne, die der Rest der Seite benutzt |
| Badge: transparent mit Gold-Rand, `border-radius: 999px` | eckiger, gefüllter Gold-Chip mit weißer Schrift | der Sonder-Override `.bid-card-premium .bid-card-badge` wurde damit überflüssig und ist gelöscht |

**`.bid-card-btn` ist jetzt nur noch ein Modifier von `.theme-btn`**, kein eigener
Button mehr. Markup: `class="theme-btn bid-card-btn"`; die CSS-Regel macht nur noch
`display: block; width: 100%` plus schmaleres Seiten-Padding. Farbe, Versalien und
die Hover-Animation (zwei dunkle Flächen, die von oben und unten zusammenlaufen)
kommen aus `.theme-btn`. Wer den eigenen `:hover` wieder einbaut, killt diese
Animation. Die Pfeil-Icons `<i class="fas fa-arrow-right">` sind entfallen — kein
anderer Button der Seite hat eins.

**Falle beim Seiten-Padding:** `.theme-btn` bringt `padding: 16px 35px 14px` mit. Bei
einem Button über die volle Kartenbreite bringt das seitliche Padding nichts (der Text
ist ohnehin zentriert), kostet aber Platz — zwischen **992 und 1199px** ist die
`col-lg-4`-Spalte nur 320px breit, das ließ „Senioren anfragen" zweizeilig umbrechen.
Deshalb `padding-left/right: 12px` im Modifier. An allen vier Breakpoint-Breiten
(380 / 320 / 360 / 540px Spalte) bleiben alle sechs Buttons einzeilig (56px). Wer die
Button-Beschriftungen verlängert, muss das nachmessen.

**Weitere Falle:** `.bid-card-premium` setzt `border-color: var(--secondary-color)` —
das Shorthand frisst die goldene Oberkante. Deshalb steht dort zusätzlich
`border-top-color: var(--theme-color)`.

Geändert: `index.html:668–842` und `umzug.html:495–669` (identischer Block, je sechs
Button-Zeilen), `assets/css/style.css:7064–7334`, `assets/css/responsive.css:1049–1071`
(Mobil-`h3` von 26px auf 22px, sonst wäre es größer als der neue Desktop-Wert von 24px).
`?paket=<slug>`-Links und damit die Auswertung in `angebot-anfordern.html`
(`.bid-selected-note`) blieben unangetastet und wurden nachgeprüft.

### 2026-08-15 — Echte Profil-URLs eingetragen

Die drei Platzhalter sind site-weit durch die echten Links ersetzt, die Bewertungs-Links
sind damit live. **Es sind nur noch zwei URLs im Umlauf:**

| Platzhalter (weg) | jetzt |
|---|---|
| `GOOGLE_PROFIL_URL` | `https://maps.app.goo.gl/NMVGRRBsDjpwtY37A` |
| `CHECK24_PROFIL_URL` | `https://umzug.check24.de/umzug/profil/movzoywnbg` |
| `GOOGLE_BEWERTEN_URL` | **ersatzweise ebenfalls der Google-Maps-Link** |

Beide URLs geprüft (HTTP 200, der Kurzlink löst auf das Maps-Profil „MS Clean" auf).
Die veralteten `<!-- Platzhalter … ersetzen -->`-Kommentare wurden entfernt bzw. auf
`<!-- Bewertungen -->` / `<!-- review-bar -->` gekürzt. `sameAs` im LocalBusiness-JSON-LD
von 11 Seiten trägt jetzt beide Profil-URLs.

**Noch verbesserbar:** Die zwei Buttons „Uns auf Google bewerten" (`index.html`,
Trust-Block) und „Jetzt auf Google bewerten" (`danke.html`) zeigen mangels eigenem
Kurzlink ebenfalls auf das Maps-Profil. Das funktioniert – der Nutzer landet auf dem
Profil und kann dort bewerten –, aber der Kurzlink aus dem Google-Unternehmensprofil
(*Rezensionen → Mehr Rezensionen erhalten*, Form `https://g.page/r/…/review`) öffnet das
Bewertungsfenster direkt und konvertiert deutlich besser. Sobald er vorliegt: an diesen
zwei Stellen eintragen.

### 2026-08-15 — Bewertungen in den Hero, Logos statt Text, Slider-Pfeile weg

Nachschärfung des Eintrags darunter. Drei Punkte:

**1. Bewertungen im Hero statt in einer eigenen Leiste.** Die Sektion
`<section class="review-bar">` unterhalb des Heros auf `index.html` ist ersatzlos
entfallen; die Badges stehen jetzt als `.hero-review-box` direkt unter den beiden
CTA-Buttons in der `.content-box`. Sie fahren mit derselben Mechanik ein wie die
Buttons darüber (`.banner-carousel .active .content-box …`, `style.css:1949/1972`) –
wer den Block anfasst, muss dieses Regelpaar mitdenken, sonst steht er auf
`opacity: 0`. Die `.review-bar`-CSS bleibt bestehen, `umzug.html` nutzt sie weiter.

**2. Plattform-Logos statt Textnamen**, site-weit in allen 20 Badges:
- `assets/images/logo-google.svg` – das vierfarbige „G", steht neben dem Wort „Google"
- `assets/images/logo-check24.svg` – **nachempfundene** Wortmarke, kein offizielles
  Asset: weiße kursive Schrift auf blauem Grund (`#5B9BD5`) mit dem Pfeil-Schwung unter
  der „24". Liegt das Original oder das CHECK24-Partnersiegel vor, genügt es, **diese
  eine Datei zu ersetzen** (gleicher Name, Seitenverhältnis ~2,75:1); am HTML ändert
  sich nichts. Der Block trägt Schrift auf Farbfläche und braucht deshalb mehr Höhe als
  das Google-G: 26px (kompakt 22px) gegen 18px, sonst ist die Wortmarke nicht lesbar.

Die `on-dark`-Variante setzt die Kacheln **nicht** mehr auf transparentes Weiß: das
CHECK24-Blau war auf dunklem Grund unlesbar. Kacheln sind überall weiß, `on-dark`
färbt nur noch die Überschrift. Im Hero und in der schmalen Footer-Spalte stapelt das
Badge (Logo oben, Sterne + Note darunter) – nebeneinander lief „Google" dort aus der
Kachel heraus.

**3. Slider-Pfeile entfernt.** Der Hero hat nur noch einen Slide.
`script.js` setzt `nav` jetzt abhängig von der Slide-Anzahl
(`$('.banner-carousel .slide-item').length > 1`) – kommt später ein zweiter Slide dazu,
sind die Pfeile automatisch wieder da. **Achtung, das allein reichte nicht:** bei
`nav:false` baut Owl den Pfeil-Container trotzdem und markiert ihn nur mit `.disabled`.
Das mitgelieferte `owl.css` blendet ihn nicht aus, weil es noch auf die alte
`.owl-controls`-Struktur zielt. Deshalb zusätzlich `.owl-carousel .owl-nav.disabled
{ display: none }` in `style.css` (direkt bei `.owl-nav-none`).

### 2026-08-15 — Bewertungen (Google 5,0 / Check24 4,8) site-weit eingebunden

Bis dahin gab es **keinen einzigen sichtbaren Bewertungshinweis**; der vorbereitete
Block in `index.html` stand seit dem SEO-Pass auf `display:none` mit Platzhaltern.
Jetzt liegen echte Bewertungen vor und werden an den entscheidungsrelevanten Stellen
gezeigt — bewusst mehrfach, vor allem rund um das Angebotsformular.

**Neuer Baustein `.review-badges`** (CSS in `style.css` direkt hinter dem Abschnitt
„Google-Bewertungen", responsive in `responsive.css` hinter dem Trust-Section-Block).
Ein Badge ist ein Link auf das Quellprofil und zeigt Note, Sterne und Plattformnamen:

```html
<a class="review-badge" href="…" target="_blank" rel="noopener" aria-label="… 5,0 von 5 Sternen">
    <span class="score-box"><span class="score">5,0</span><span class="stars" aria-hidden="true">…</span></span>
    <span class="meta"><span class="platform">Google</span><span class="action">Bewertungen ansehen</span></span>
</a>
```

Modifier auf dem Container `.review-badges`: `compact` (schmal, für Spalten und Footer),
`on-dark` (helle Schrift), `start` (linksbündig statt zentriert). Dazu `.review-bar`
(schmale Sektion, optional `bg-white`), `.review-badges-title` und `.form-review-note`.
Sterne kommen aus Font Awesome (`fa-star`, `fa-star-half-alt` für die 4,8) — **keine
Bilddateien, keine Plattform-Logos**, damit keine Markenrechtsfragen entstehen.
Unter 767px stapeln die Badges automatisch auf volle Breite.

**Platzierungen** (9 Seiten + Footer auf allen 12):
`index.html` Bar direkt unter dem Hero und der ausgebaute Block in der Trust-Section ·
`angebot-anfordern.html` Bar über dem Formular **und** kompakt direkt über dem
Absende-Button · `kontakt.html` in der Info-Spalte · `umzug.html` vor der
`consulting-section` · `faq.html` über der `faq-cta-box` · `einsatzgebiet.html` in der
dunklen `cta-section` (`on-dark`) · `danke.html` als aktive Bitte um eine Bewertung
(`GOOGLE_BEWERTEN_URL`) · Footer als vierte `links-column` „Bewertungen" (der vierte
Grid-Platz war frei; dort ist `.action` per CSS ausgeblendet, weil die Spalte zu schmal
für „Bewertungen ansehen" ist).

**Strukturierte Daten:** `sameAs` mit beiden Profil-URLs in den LocalBusiness-Block von
11 Seiten (`blog-details.html` hat `BlogPosting` statt LocalBusiness). **Bewusst kein
`aggregateRating`** — Bewertungen von Drittplattformen als eigenes Rating auszuzeichnen
verstößt gegen Googles Richtlinien für strukturierte Daten und kann eine manuelle
Maßnahme auslösen.

**Rechtlich:** Keine Bewertungsanzahl genannt (auf Wunsch); stattdessen führt jeder
Badge auf das Original-Profil, wo Anzahl und Einzelrezensionen öffentlich sind, und die
Note steht immer mit Plattformnennung. Wächst die Bewertungsbasis, ist die Angabe der
Anzahl die rechtlich sicherere Variante.

~~**OFFEN — vor dem Deploy zwingend:** Drei Platzhalter site-weit ersetzen.~~ →
erledigt am 2026-08-15, siehe Eintrag „Echte Profil-URLs eingetragen" oben.

### 2026-08-15 — Angebotsformular repariert und optisch überarbeitet

**Der Fehler:** In `angebot-anfordern.html` stand ein `</div>` zu viel — der Wrapper
`<div id="section-umzug">` um den Umzug-Block war irgendwann entfernt worden,
sein schließendes Tag blieb stehen. Der HTML-Parser hat damit
`.appointment-form-box` und implizit das `<form>` geschlossen: **alles ab
„Vollständiger Name*" lag außerhalb des Formulars**. Keine einzige
`.appointment-form …`-Regel griff dort noch, die Felder waren rahmenlos und nackt.
Abgeschickt wurden sie trotzdem (der Parser hängt sie über den Form-Pointer an) —
der Bug war rein optisch, aber ein massiver Conversion-Killer. Aus demselben Grund
stand `.address-row` (im CSS als `.service-section .address-row` definiert) nie im
2-Spalten-Grid, PLZ und Ort lagen untereinander.

**Merke:** Bei „CSS greift plötzlich nicht mehr" auf dieser Seite zuerst die
`<div>`-Balance prüfen:
`grep -o '<div' datei.html | wc -l` gegen `grep -o '</div>' datei.html | wc -l`.

**Behoben und verbessert**
- Wrapper `#section-umzug` wiederhergestellt → Formular ist wieder ein Baum,
  Adressfelder wieder zweispaltig. **Ohne** Klasse `service-section`: die ist global
  belegt (`style.css:3166`, `responsive.css:572` gibt ihr unter 991px
  `padding: 65px 0 40px`) und würde den Formularblock auf Mobil aufblähen
- Formularspalte `col-xl-9` → `col-12` (rechts stand eine leere Vierteilspalte),
  Kontaktfelder auf `col-lg-6 col-md-6`
- Block „Gewünschte Leistung" mit der einzigen, immer aktiven Umzug-Karte entfernt →
  `<input type="hidden" name="service" value="umzug">`; das Submit-JS liest jetzt
  `$('input[name="service"]').val()`
- Wunschtermin: drei Dropdowns (Tag/Monat/Jahr) + loses Kalender-Icon → ein
  `<input type="date">` mit `min` = heute. `dobpicker.js` auf dieser Seite entfernt,
  in der Mail wird das ISO-Datum nach `TT.MM.JJJJ` gedreht
- Alle Labels als `<label for>` an ihr Feld gebunden, `autocomplete` auf
  Name/E-Mail/Telefon, Abschnittsüberschrift „Ihre Kontaktdaten" ergänzt

**CSS** (`style.css`, alles unter `.appointment-form` — die Klasse gibt es nur auf
dieser Seite): Felder 40 → 52px hoch, `border-radius: 6px`, 16px Schrift, Fokus mit
Theme-Rahmen und weichem Ring; Textarea 140px und vertikal skalierbar; native
`select.ignore` mit `appearance:none` und SVG-Chevron auf denselben Look gebracht;
Labels 18 → 16px; Checkboxen 13 → 20px mit Theme-Häkchen statt grauem Verlauf;
`.address-row` und `.section-heading` vom Selektor `.service-section …` auf
`.appointment-form …` umgehängt, damit sie nicht mehr am Wrapper-Namen hängen;
Submit-Button auf Mobil volle Breite. Die toten `.date-box`/`.time-box`/
`#dobday`-Regeln und die doppelte `input[type="tel"]`-Regel (die die Felder auf 40px
zurückgesetzt hat) entfernt, ebenso `responsive.css:891`.

`sendemail-appointment.php` blieb unverändert — alle Feldnamen sind gleich geblieben.

### 2026-08-14 — Lead-Optimierung: SEO-Audit umgesetzt

Abgleich des SEO-Audits der Content-Profi mit dem Code. Vieles war bereits erledigt
(H1 oben im Hero mit Standort, echtes Startbild, umbenannte URLs mit 301-Redirects,
flache Navigation ohne „Leistungen"-Dropdown, kurze Titles). Umgesetzt wurde:

**Conversion-Killer behoben**
- Homepage-Galerie: 4× `href="project.html"` zeigte auf eine **nicht existierende Datei** (4 × 404). Jetzt auf `umzug.html`. Die Lightboxen öffneten graue `413×563`-Platzhalter aus `assets/images/project/` — jetzt die echten Fotos; der Platzhalter-Ordner wurde gelöscht
- 4× identisches `<h3>Unser Umzugsprojekt</h3>` → vier unterschiedliche, keyword-tragende Titel
- FAQ-Formular hatte `action="faq.html"` und **versendete gar nichts** → jetzt `sendemail.php` mit `formsource=faq`
- Erfundene Testimonials entfernt (UWG-Risiko) → Trust-Block „Worauf Sie sich bei uns verlassen können" + vorbereiteter Google-Bewertungsblock (`display:none`, wartet auf die echten URLs — Platzhalter `GOOGLE_PROFIL_URL` / `GOOGLE_BEWERTEN_URL` in `index.html`)
- Hero-CTA „Angebot anfordern" → „Kostenloses Angebot erhalten", zweiter Telefon-CTA ergänzt
- In-Page-CTAs auf `faq.html` und `kontakt.html` ergänzt
- Honeypot + Zeitfalle + DSGVO-Checkbox in allen drei Formularen (siehe Abschnitt „Formulare")

**SEO**
- `FAQPage`-Schema auf `faq.html` (aus den 8 sichtbaren Q&A generiert — bei Textänderungen mit anpassen, sonst verstößt es gegen Googles Richtlinien), `BreadcrumbList` auf 7 Unterseiten
- LocalBusiness-JSON-LD site-weit erweitert: 4 statt 1 Service, `openingHoursSpecification`, `@id`, `email`, `logo`, `description`; `telephone` auf `+49…`
- H1 `umzug.html` „Umzug" → „Umzugsservice in Mannheim und Umgebung"; `kontakt.html` „Kontakt" → „Kontakt zu Ms Clean in Mannheim"; doppelte H1 auf `error.html` entfernt
- Meta-Descriptions von 152–172 auf 128–148 Zeichen gekürzt (inkl. og/twitter)
- 5 FAQ-Fragen mit Standortbezug versehen
- Navigation auf allen 12 Seiten vereinheitlicht: Startseite · Umzug · Einsatzgebiet · FAQ · Kontakt (FAQ fehlte vorher komplett, „Startseite" auf 3 Seiten)
- 48 leere Logo-`alt=""` gefüllt, `aria-label` auf den Icon-Links der Galerie
- `og:image` site-weit auf neues `assets/real-images/og-image.jpg` (1200×630, echtes Team-Foto) — vorher ein nicht verwendetes Stockfoto, auf `datenschutz.html` sogar noch ein Küchenbild
- `robots.txt`: `archiv/`, `Documentation/`, `knowladge/`, `phpmailer/` gesperrt

**Performance**
- `assets/real-images/` von 26 MB auf 3,8 MB: ungenutzte Küchen-/Reinigungsbilder gelöscht (in Git wiederherstellbar), Restbilder auf sinnvolle Maße gerechnet, `faq.png` (1,6 MB) → `faq.jpg` (168 KB)
- `<link rel="preload" as="image" fetchpriority="high">` für das LCP-Hintergrundbild auf 7 Seiten
- gtag-Block hinter `<meta charset>` verschoben, 52 `tel:`-Links auf internationales Format

**Bugfix responsive**: Die Banner-H1 hatte in `responsive.css` **keine** Regeln (nur `h2`) und blieb auf jeder Bildschirmgröße 70px/84px — auf dem Handy schob das die CTAs unter die Falz. Jetzt Breakpoints bei 991/767/599px.

~~**Offen (Ihre Aufgabe)**: Google-Unternehmensprofil ausfüllen, Bewertungs-Kurzlink erzeugen und in `index.html` eintragen, dann den Bewertungsblock sichtbar schalten und `sameAs` + später `AggregateRating` ergänzen.~~ → erledigt am 2026-08-15, siehe Eintrag „Bewertungen site-weit eingebunden" oben. `AggregateRating` wird bewusst **nicht** ergänzt (Richtlinienverstoß).

### 2026-08-10 — Blog navbar link also commented out on blog pages
- Follow-up to the earlier nav hide: commented out the Blog nav link on `blog.html` and `blog-details.html` themselves so the link never renders in any navbar (breadcrumb link in `blog-details.html` kept as-is)

### 2026-08-10 — Blog pages hidden from navigation
- Commented out the `<li><a href="blog.html">Blog</a></li>` nav entry on all 12 active pages (index, kuechenmontage, reinigung, umzug, einsatzgebiet, contact, appointment, faq, error, impressum, datenschutz, danke)
- One edit per page covers all three menus — the `.sticky-header` nav and `.mobile-menu` are cloned from `.main-menu` at runtime by `script.js`
- `blog.html` and `blog-details.html` remain fully intact on disk and are still reachable by direct URL (no `noindex`, no `.htaccess` block) — they are simply no longer linked from anywhere on the site
- To re-enable: uncomment that single line on the 12 pages

### 2026-07-11 — Blog pages added, favicon updated, mobile slider fix
- Added `blog.html` (blog listing with 4 test posts) and `blog-details.html` (single post detail with sidebar, comments form)
- Wrote all blog-specific CSS from scratch in `style.css` (news cards, sidebar widgets, comments, tags) + responsive rules in `responsive.css` — archiv template had zero blog CSS
- Added "Blog" nav link to all 14 active pages (main nav between Einsatzgebiet and Kontakt)
- Updated favicons site-wide: replaced old `assets/images/favicon.ico` with modern favicon set in `assets/Favicons/` (ico, svg, png, apple-touch-icon, webmanifest)
- Fixed `site.webmanifest` icon paths from absolute `/` to relative, updated name to "MS Clean"
- Fixed mobile slider: removed `display: none` on `.bg-layer` in `responsive.css` ≤991px breakpoint, added `text-shadow: 1px 1px 5px black` on banner headings for mobile readability
- Updated CLAUDE.md active pages table (was missing danke, impressum, datenschutz, blog pages)

### 2026-06-20 — Business address change: Recklinghausen → Frankenthal
- Updated registered business address across all 11 active pages: street `Hertener Straße 64` → `Schnurgasse 14`, postal code `45657` → `67227`, city `Recklinghausen` → `Frankenthal` (JSON-LD `PostalAddress` + visible Impressum text)
- `impressum.html` "Zuständige Aufsichtsbehörde" updated to `Gewerbeamt der Stadt Frankenthal` (street/PLZ for the authority dropped pending confirmation of the correct Frankenthal office address)

### 2026-06-07 — SEO pass: head metadata, structured data, image accessibility
- Added unique `<meta name="description">`, `<link rel="canonical">`, Open Graph and Twitter Card tags to all 11 active pages
- Fixed homepage missing `<h1>` — converted the `.sec-title` heading "Ihr zuverlässiger Partner für Küche, Reinigung & Umzug in der Region" from `<h2>` to `<h1>` (extended `.sec-title h2` CSS rules in `style.css`/`responsive.css` to also cover `.sec-title h1` so the visual style is unchanged)
- Removed `maximum-scale=1.0, user-scalable=0` from the viewport meta site-wide (was disabling pinch-zoom — a mobile-usability/accessibility issue)
- Added `HomeAndConstructionBusiness` JSON-LD structured data (LocalBusiness schema with NAP, service area, services) to every page's `<head>`
- Filled in descriptive German `alt` text on all `assets/real-images/*` content photos and footer logos (decorative icons intentionally kept `alt=""`)
- Added `loading="lazy"` to below-the-fold images site-wide
- Standardized a few generic page titles (`kuechenmontage.html`, `contact.html`, `appointment.html`, `faq.html`) to include "Mannheim"/region for local-search relevance

### 2026-05-14 — Full rebrand + German translation
- Rebranded all active pages from "Kitchnox" template to "MS Clean"
- Preloader animation changed from "kitchnox" (8 letters) to "msclean" (7 letters) across all pages
- All remaining English strings translated to German site-wide
- `sendemail.php` success redirect: `?message=Successfull` → `?message=Erfolgreich`
- `appointment.html` `lang="en"` fixed to `lang="de"`
- `assets/css/style.css` comment updated to reference MS Clean
