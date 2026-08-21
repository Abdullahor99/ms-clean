# Bausteine

Vorhandene HTML-Blöcke mit ihren Text-Slots. **Die Struktur ist verbindlich, der Text
nicht** — das Markup stammt aus einem funktionierenden Theme, die Texte darin sind das,
was ersetzt wird.

Längenangaben ernst nehmen: Die Blöcke sind auf bestimmte Textmengen ausgelegt, und ein
doppelt so langer Text sprengt das Raster auf Mobilgeräten.

---

## `.sec-title` — der Abschnittskopf

Steht vor fast jedem Abschnitt. Drei Slots.

```html
<div class="sec-title mb_60 centred">
    <span class="sub-title mb_19">Kicker</span>
    <h2>Überschrift mit <span>Akzent am Ende</span></h2>
    <p>Ein Satz, der die Überschrift konkretisiert.</p>
</div>
```

| Slot | Länge | Regel |
|---|---|---|
| `.sub-title` | 2–4 Wörter | Kicker, wird per CSS in Versalien gesetzt |
| `h2` | 4–9 Wörter | Der `<span>` färbt die **letzten** 2–3 Wörter gold |
| `p` | 1 Satz, ≤20 Wörter | Optional. Trägt idealerweise die Zahl |

Modifier: `centred` (zentriert), `light` (auf dunklem Grund), `mb_25`/`mb_50`/`mb_60`.

---

## `.trust-block-one` — Vertrauenskachel (4er-Raster)

Der wichtigste Baustein für diese Strategie. Eckig, 3px goldene Oberkante.

```html
<div class="col-lg-3 col-md-6 col-sm-12 trust-block">
    <div class="trust-block-one">
        <h3>Festpreis — auch wenn wir uns verrechnen</h3>
        <p>Wird Ihr Umzug aufwendiger als gedacht, ist das unser Problem. In zwei Jahren und mehr als 800 Umzügen haben wir noch nie nachberechnet.</p>
    </div>
</div>
```

**Textformel:** Überschrift 2–6 Wörter, dann **genau zwei Sätze**. Der erste benennt die
Sache, der zweite liefert den Beleg oder entkräftet die Angst. Nicht mehr — vier
Kacheln nebeneinander müssen gleich hoch wirken.

Vier Kacheln pro Reihe. Jede sollte eine der vier Kundenängste adressieren
(siehe `stimme.md`, Säule 3).

---

## `.chooseus-block-one` — Vertrauenskachel mit Icon (3er-Raster)

```html
<div class="col-lg-4 col-md-6 col-sm-12 chooseus-block">
    <div class="chooseus-block-one">
        <div class="inner-box">
            <div class="icon-box"><img src="assets/images/icons/icon-31.png" alt=""></div>
            <h4>Überschrift</h4>
            <p>Ein bis zwei Sätze mit Beleg.</p>
        </div>
    </div>
</div>
```

Überschrift ist hier `h4`, nicht `h3`. Icons rotieren `icon-31` / `icon-32` / `icon-33`.

---

## Zahlen-Leiste — neu, für die Vertrauens-Strategie

**Existiert im CSS noch nicht.** Vier bis fünf Zahlen nebeneinander sind der direkteste
Weg, Substanz zu zeigen — beim ersten Einsatz anlegen, nach dem Muster von
`.trust-block-one`: eckig, `#e6e1da`-Hairline oder 3px Goldkante, **kein**
`border-radius`, Hover `translateY(-6px)`.

```html
<section class="stats-section">
    <div class="auto-container">
        <div class="row clearfix">
            <div class="col-lg-3 col-md-6 col-sm-12 stats-block">
                <div class="stats-block-one">
                    <span class="stats-value">800+</span>
                    <span class="stats-label">Umzüge in zwei Jahren</span>
                </div>
            </div>
            <!-- 5,0 · Google-Bewertung -->
            <!-- 24 h · Antwort auf Ihre Anfrage -->
            <!-- 200 km · Einsatzradius um Mannheim -->
        </div>
    </div>
</section>
```

**Wert 2–5 Zeichen, Label 2–5 Wörter.** Nur belegte Zahlen aus `fakten.md`.
Die vier oben sind die belastbaren — keine erfinden, um die Reihe zu füllen.

---

## FAQ-Akkordeon

Variante für FAQ- und Unterseiten:

```html
<li class="accordion block active-block">   <!-- nur beim ersten: active-block -->
    <div class="acc-btn active">            <!-- nur beim ersten: active -->
        <div class="icon-outer"></div>
        <h3>Was kostet ein Umzug in Heidelberg?</h3>
    </div>
    <div class="acc-content current">       <!-- nur beim ersten: current -->
        <div class="text">
            <p>Antwort in 2–3 Sätzen.</p>
        </div>
    </div>
</li>
```

**Textformel:** Frage so stellen, wie der Kunde sie stellt (also „Was kostet …", nicht
„Preisgestaltung"). Antwort **beginnt mit der Antwort**, erklärt danach. 2–3 Sätze,
schließt mit dem 24-Stunden-Versprechen oder einem weichen CTA.

> **Falle:** Jede FAQ-Antwort steht **doppelt** — sichtbar hier und wortgleich im
> `FAQPage`-JSON-LD (`faq.html:153+`). Wer nur eine Stelle ändert, produziert einen
> Verstoß gegen Googles Richtlinien für strukturierte Daten.

---

## `.working-block-one` — Ablauf in 4 Schritten

```html
<div class="col-lg-3 col-md-6 col-sm-12 working-block">
    <div class="working-block-one">
        <div class="inner-box">
            <div class="icon-box">
                <div class="icon"><img src="assets/images/icons/icon-21.png" alt=""></div>
                <span>2</span>
            </div>
            <h3>Angebot erhalten</h3>
            <p>Ein bis zwei Sätze, was in diesem Schritt passiert.</p>
        </div>
    </div>
</div>
```

Immer genau **vier** Schritte. Überschrift 2–3 Wörter, aktives Verb. Jeder Schritt sagt,
was *der Kunde* davon merkt — nicht, was intern passiert.

---

## `.bid-card` — Paketkarte

Sechs Karten, **identisch auf `index.html:668-842` und `umzug.html:495-669`** — bei
Textänderungen beide Dateien anfassen. Slugs und Namen in `fakten.md`.

```html
<div class="bid-card bid-card-komfort">
    <div class="bid-card-head">
        <span class="bid-card-badge">Beliebt</span>   <!-- optional -->
        <h3>Komfort Paket</h3>
        <span class="bid-card-sub">Rundum-Service ohne Eigenarbeit</span>
        <span class="bid-card-price">Ab Anfrage</span>
    </div>
    <div class="bid-card-body">
        <span class="sr-only">Enthaltene Leistungen:</span>
        <ul class="bid-card-list">
            <li>Vollständiges Einpacken</li>
        </ul>
        <span class="sr-only">Nicht enthalten:</span>
        <ul class="bid-card-list is-out">
            <li><del>Endreinigung</del></li>
        </ul>
    </div>
    <div class="bid-card-foot">
        <a href="angebot-anfordern.html?paket=komfort#anfrage" class="theme-btn bid-card-btn">Komfort anfragen</a>
    </div>
</div>
```

`.bid-card-sub` ist die Positionierungszeile: **maximal 6 Wörter**. Preis ist immer
`Ab Anfrage`. Listenpunkte 2–4 Wörter, jede Liste endet auf Vollversicherung und
Festpreis. Die Paketbeschreibungen stehen zusätzlich als `Offer` im LocalBusiness-JSON-LD
— bei Änderungen mitziehen.

---

## CTA-Block

Die wirksamste und am besten wiederverwendbare Form:

```html
<div class="faq-cta-box centred mt_50">
    <h3>Frage, die der Leser sich gerade stellt?</h3>
    <p>Ein Satz Antwort, der das 24-Stunden-Versprechen enthält.</p>
    <div class="btn-box">
        <a href="angebot-anfordern.html" class="theme-btn">Kostenloses Angebot erhalten</a>
        <a href="tel:+4915780810894" class="theme-btn theme-btn-outline">Jetzt anrufen: 0157 80810894</a>
    </div>
</div>
```

**Formel:** Frage-Überschrift → ein Satz → Primärbutton + Telefon-Outline-Button.
Klassenvarianten: `.contact-cta-box`, `.google-review-box`.

Buttons sind immer `.theme-btn` (eckig, gold, Versalien). Auf dunklem Grund
`.theme-btn-outline-light`. Beschriftung sagt, was der Nutzer bekommt — nicht „Absenden".

---

## `.review-badges` — sozialer Beweis

Auf jeder Seite. **Nur diese drei Titelzeilen sind im Umlauf** — keine vierte erfinden:

- „So bewerten uns unsere Kundinnen und Kunden"
- „Was unsere Kundinnen und Kunden sagen"
- „Ihre Anfrage ist unverbindlich – so bewerten uns unsere Kundinnen und Kunden:"

Google 5,0 (5 Sterne), Check24 4,8 (4 Sterne + `fa-star-half-alt`), deutsches
Dezimalkomma, beide verlinkt auf das Quellprofil (URLs in `fakten.md`).
Modifier: `hero`, `start`, `compact`, `on-dark`.

**Kein `aggregateRating` im JSON-LD** — siehe Verbot im SKILL.md.

---

## `.page-title` — Kopf jeder Unterseite

```html
<section class="page-title p_relative">
    <div class="bg-layer" style="background-image: url(../assets/real-images/ein-umzug-wagen.webp);"></div>
    <div class="auto-container">
        <div class="content-box">
            <h1>Umzugsunternehmen in Heidelberg</h1>
            <ul class="bread-crumb clearfix">
                <li><a href="../index.html">Startseite</a></li>
                <li><a href="../einsatzgebiet.html">Einsatzgebiet</a></li>
                <li>Heidelberg</li>
            </ul>
        </div>
    </div>
</section>
```

> **Falle:** Die `<li>`-Beschriftungen müssen **exakt** den `name`-Feldern der
> `BreadcrumbList` im JSON-LD entsprechen.

Pfade im Unterordner mit `../` — siehe `seitentypen.md`.

---

## Bildtexte

`alt` ist Fließtext, kein Schlagwortlager. Muster aus dem Bestand: *„[Wer] von Ms Clean
[tut was]"*.

```html
alt="Umzugshelfer von Ms Clean trägt Umzugskartons bei einem Privatumzug in Heidelberg"
```

Dekorative Icons bleiben `alt=""`. Bilder unterhalb der Falz bekommen `loading="lazy"`;
das LCP-Bild bekommt stattdessen einen `<link rel="preload" as="image"
fetchpriority="high">` im `<head>`.
