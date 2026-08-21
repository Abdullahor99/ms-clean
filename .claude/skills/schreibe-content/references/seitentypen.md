# Seitentypen

Drei Aufträge: **Stadtseite**, **Leistungsseite**, **Überarbeitung**.

---

## Die Unterordner-Falle — zuerst lesen

Beide neuen Seitentypen liegen in einem **Unterverzeichnis**. Alle Pfade der Vorlage
sind relativ und zeigen ins Leere, sobald die Datei eine Ebene tiefer liegt. Genau
daran krankt der `archiv/`-Ordner.

**Beim Kopieren von Header und Footer aus `index.html` oder `umzug.html` umstellen:**

| Betrifft | von | nach |
|---|---|---|
| ~13 Stylesheets | `assets/css/…` | `../assets/css/…` |
| alle Skripte | `assets/js/…` | `../assets/js/…` |
| Bilder, Favicons, Manifest | `assets/…` | `../assets/…` |
| Navigation, Footer, CTAs | `index.html`, `umzug.html`, `angebot-anfordern.html` | `../index.html` usw. |
| Inline-Hintergrundbilder | `url(assets/…)` | `url(../assets/…)` |

Gegenprobe nach dem Bau — die Seite im Browser öffnen und die Konsole ansehen. Ein
einziger 404 bedeutet meist, dass ein ganzer Block nicht umgestellt wurde.

Absolute Angaben bleiben absolut und ändern sich **nicht** relativ, sondern inhaltlich:
`canonical`, `og:url`, `og:image` und die `url` im JSON-LD zeigen auf den neuen Pfad
unter `https://msclean-mannheim.de/`.

---

## Stadtseite — `einsatzgebiet/umzug-<stadt>.html`

Geplant laut `CLAUDE.md`: Mannheim, Karlsruhe, Stuttgart, Heidelberg, Ludwigshafen,
Heilbronn.

### Die 60-Prozent-Regel

**Mindestens 60 % des Textes muss wirklich stadtspezifisch sein.** Sechs Seiten, bei
denen nur der Städtename ausgetauscht ist, sind sechs wertlose Seiten — Google erkennt
das Muster, und der Leser auch.

Ortsbezug gehört in: URL · Meta-Title · Meta-Description · H1 · mehrere H2 · Fließtext ·
mindestens eine FAQ-Frage · `alt`-Texte.

### Rechercheauftrag — ohne das keine Stadtseite

Diese Punkte machen den Unterschied zwischen einer echten Stadtseite und einer
Dublette. Der Skill **recherchiert sie aktiv**, bevor er schreibt:

1. **Entfernung und Fahrzeit ab Frankenthal** — daraus wird eine Aussage über
   Verfügbarkeit („in zehn Minuten da", „auch für kurzfristige Termine").
2. **Bundesland** — siehe Städteliste in `fakten.md`.
3. **Konkrete Stadtteile** — drei bis fünf mit Namen. Wer Neuenheim, Handschuhsheim und
   die Weststadt nennt, beweist Ortskenntnis; wer „alle Stadtteile" schreibt, nicht.
4. **Typische Bebauung** — Altbau ohne Aufzug, enge Treppenhäuser, Fußgängerzone,
   Neubaugebiet. Das ist der Punkt, an dem es für den Kunden praktisch wird.
5. **Parksituation und Halteverbotszone** — **wo genau wird sie beantragt?** Die Behörde
   heißt in jeder Stadt anders (Ordnungsamt, Straßenverkehrsbehörde, Bürgeramt), hat
   andere Vorlauffristen und Gebühren. Das weiß kein Wettbewerber-Fließtext, und es ist
   der stärkste einzelne Beweis für Ortskenntnis.
6. **Besonderheiten** — Uni- und Studentenanteil (Heidelberg), Industrie und Pendler
   (Ludwigshafen), Innenstadt-Zufahrtsbeschränkungen.

Was sich nicht belegen lässt, wird `[BELEG NÖTIG: …]` — nicht erfunden.

### Gliederung

| Abschnitt | Inhalt |
|---|---|
| `.page-title` | H1 mit Stadt, Breadcrumb `Startseite / Einsatzgebiet / <Stadt>` |
| Intro | **Beantwortet die Hauptfrage in den ersten ~200 Wörtern**, statt sie aufzubauen. Fahrzeit, Radius, Festpreis, 24-Stunden-Antwort |
| Leistungen | 3–4 Blöcke, auf die Stadt bezogen |
| **Lokale Besonderheiten** | Der Kern. Stadtteile, Bebauung, Halteverbotszone, Parken |
| Ablauf | 4 Schritte (`.working-block-one`) |
| Pakete | Verweis auf `angebot-anfordern.html?paket=<slug>#anfrage` |
| FAQ | 3–5 Fragen **mit Stadtbezug** — plus `FAQPage`-JSON-LD |
| Bewertungen | `.review-badges` |
| CTA | Formular + Telefon |

### Titel- und Beschreibungsmuster

```
Title:       Umzugsunternehmen Heidelberg – Festpreis & vollversichert
Description: Umzug in Heidelberg zum Festpreis: Privatumzug, Büroumzug und
             Möbelmontage. Mehr als 800 Umzüge, 5,0 bei Google. Antwort in 24 Stunden.
```

---

## Leistungsseite — `umzug/<leistung>.html`

Geplant laut `CLAUDE.md`: `privatumzug.html`, `bueroumzug.html`, `moebelmontage.html`,
`wohnungsaufloesung.html`.

| Abschnitt | Inhalt |
|---|---|
| `.page-title` | H1 mit Leistung + Region, Breadcrumb `Startseite / Umzug / <Leistung>` |
| Intro | Was die Leistung genau umfasst — in den ersten Absätzen, ohne Anlauf |
| Leistungsumfang | Konkret und aufzählbar: was ist drin, was nicht |
| Ablauf | 4 Schritte |
| Abgrenzung | Welches der sechs Pakete passt, und warum |
| FAQ | 3–5 leistungsbezogene Fragen + `FAQPage`-JSON-LD |
| CTA | Formular + Telefon |

**Nach dem Bau verdrahten:** Die vier Kacheln auf `umzug.html:423-447`
(`.service-block-two`) stehen noch auf `href="#"`. Sie zeigen auf genau diese Seiten
und werden beim Anlegen der jeweiligen Seite verlinkt.

---

## Überarbeitung bestehender Abschnitte

Kein neues Gerüst — nur Text. Regeln:

1. **HTML-Struktur nicht anfassen.** Klassen, Verschachtelung und `wow`-Attribute
   bleiben. Nur Textknoten ändern.
2. **Längen respektieren.** Die Blöcke sind auf bestimmte Textmengen ausgelegt (siehe
   `bausteine.md`). Ein doppelt so langer Text sprengt das Raster auf Mobilgeräten.
3. **Zwei Orte prüfen**, sonst entstehen Widersprüche:
   - FAQ-Antworten stehen zusätzlich im `FAQPage`-JSON-LD (`faq.html:153+`)
   - Der Paket-Abschnitt existiert **doppelt**: `index.html:668-842` und
     `umzug.html:495-669` — beide Seiten ändern
   - Paketbeschreibungen stehen zusätzlich als `Offer` im LocalBusiness-JSON-LD
4. **`impressum.html` und `datenschutz.html` nie anfassen.**
5. Nach der Änderung prüfen, ob Meta-Description und Überschrift noch zusammenpassen.
