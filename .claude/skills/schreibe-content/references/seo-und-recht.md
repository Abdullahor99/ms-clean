# SEO, KI-Suche und Recht

---

## Meta-Angaben

**Reihenfolge im `<head>`** (bei allen 12 aktiven Seiten identisch — beibehalten):

`charset` → gtag → google-site-verification → X-UA-Compatible → viewport →
`<link rel="preload">` fürs LCP-Bild → `<title>` → description → canonical →
og:type/title/description/url/image/locale → twitter:card/title/description/image →
Favicons → `fonts.css` → 12–13 Stylesheets → JSON-LD → `</head>`

### Title

31–63 Zeichen. Trenner ` – `, sekundär ` | `. Ort möglichst enthalten.
Drei bewährte Muster:

```
Umzugsunternehmen Heidelberg – Festpreis & vollversichert
Umzug Mannheim & Region – Privatumzug & Büroumzug
Unser Einsatzgebiet – 200 km rund um Mannheim | Ms Clean
```

### Description

**128–174 Zeichen, Ziel ~150.** Formel: *Leistungen + Ort – Vertrauensbeleg. CTA.*
Hier gehört eine Zahl hinein — sie ist im Suchergebnis der Unterschied zum Wettbewerber:

```
Umzug in Heidelberg zum Festpreis: Privatumzug, Büroumzug und Möbelmontage.
Mehr als 800 Umzüge, 5,0 bei Google. Antwort in 24 Stunden.
```

> `og:description` und `twitter:description` sind **byteidentisch** mit der
> Meta-Description. Ebenso `og:title` / `twitter:title` mit dem `<title>` (einzige
> gewollte Ausnahme im Bestand: `umzug.html`). Das ist auf allen Seiten so und bleibt so.

`og:image` und `twitter:image` sind überall
`https://msclean-mannheim.de/assets/real-images/og-image.jpg`.

### Überschriften

Genau **eine `h1`** pro Seite, im `.page-title`. Darunter `h2` für Abschnitte, `h3` für
Blöcke und FAQ-Fragen. Keine Ebene überspringen.

---

## JSON-LD

| Typ | Wo |
|---|---|
| `HomeAndConstructionBusiness` | auf **jeder** Seite. Nur `url` unterscheidet sich |
| `BreadcrumbList` | auf jeder Unterseite. `name` = Breadcrumb-Beschriftung |
| `FAQPage` | überall, wo sichtbare FAQ stehen — Antworten **wortgleich** |

`LocalBusiness` wird nie verwendet, der Typ ist immer
`HomeAndConstructionBusiness`. Beim Anlegen einer neuen Seite den Block aus
`umzug.html:60` kopieren und **nur `url` anpassen**.

**Kein `aggregateRating`, kein `Review`.** Bewertungen von Fremdplattformen als eigenes
Rating auszuzeichnen verstößt gegen Googles Richtlinien und kann eine manuelle Maßnahme
auslösen (Entscheidung vom 2026-08-15).

Neue Seiten außerdem in `sitemap.xml` eintragen — Priorität an den bestehenden Werten
orientieren (Startseite 1.0, `umzug.html` 0.9, `einsatzgebiet.html` 0.8, Rest 0.7).

---

## Für KI-Suchen (GEO)

ChatGPT, Perplexity, Google AI Overviews und Gemini zitieren Passagen, die **für sich
allein stehen können**. Das deckt sich mit der Substanz-Regel: zitierfähig ist, was eine
prüfbare Aussage macht.

1. **Die ersten ~200 Wörter beantworten die Hauptfrage direkt** — nicht hinführen,
   antworten. Wer „Was kostet ein Umzug in Heidelberg?" als Seitenthema hat, beantwortet
   das oben, nicht in Abschnitt vier.
2. **Jeder Abschnitt beginnt mit der Antwort**, der Kontext folgt danach.
3. **Absätze zwei bis drei Sätze.** Längere Blöcke werden seltener extrahiert.
4. **Frage-Antwort-Paare und Listen** werden deutlich häufiger zitiert als Fließtext —
   ein Grund mehr für echte FAQ-Abschnitte auf jeder Seite.
5. **Klare, überprüfbare Aussagen** statt Werbesprache. „Mehr als 800 Umzüge in zwei Jahren"
   ist zitierbar, „führender Anbieter der Region" nicht.

---

## E-E-A-T

Google bewertet **belegte Erfahrung** höher als behauptete Kompetenz. Für einen lokalen
Dienstleister heißt das konkret:

- echte Fotos statt Stockmaterial
- der Ablauf im Detail statt „professionelle Abwicklung"
- nachprüfbare Regionalkenntnis — genau das leistet eine gute Stadtseite nebenbei
- Bewertungen offen verlinkt statt nachgebaut
- Inhalte aktuell halten

---

## Recht

### UWG — irreführende Werbung (§ 5)

**Die Kernregel:** Sobald eine Aussage nachprüfbar ist, ist sie juristisch eine
Tatsachenbehauptung und muss beweisbar sein. Werbesprache („sorgfältig", „zuverlässig")
ist zulässig; alles Quantifizierbare nicht ohne Beleg.

| Zulässig | Unzulässig ohne Beleg |
|---|---|
| Erfahrene Umzugshelfer | Marktführer in Mannheim |
| Mehr als 800 Umzüge (belegt) | Tausende zufriedene Kunden |
| Faire Festpreise | Günstigster Anbieter der Region |
| Vollversichert | Geprüfter Fachbetrieb |

**Besonders heikel:** Zahlen und Jahresangaben eines Wettbewerbers zu übernehmen. Die
gesperrten Angaben stehen in `fakten.md`. Abmahnen würde das am ehesten genau der
Betrieb, dem sie gehören.

### Werbung mit Bewertungen

Wer mit Kundenbewertungen wirbt, muss angemessene Maßnahmen zur Prüfung ihrer Echtheit
ergreifen und darüber informieren. Deshalb verlinken die Badges auf die Originalprofile
— dort sind Anzahl und Einzelrezensionen öffentlich einsehbar und von der Plattform
verifiziert. **Keine Bewertung nachbauen oder zitieren**, immer verlinken.

Erfundene Testimonials wurden auf dieser Website bereits einmal aus genau diesem Grund
zurückgebaut.

### Preisangaben

Es stehen **keine Preise** auf der Website (`Ab Anfrage`). Damit greift die
Preisangabenverordnung nicht — sobald ein konkreter Preis genannt würde, müsste er als
Endpreis inklusive Umsatzsteuer ausgewiesen werden. Ein weiterer Grund, es bei
`Ab Anfrage` zu belassen.

### Impressum und Datenschutz

`impressum.html` und `datenschutz.html` fasst dieser Skill **nicht** an. Dort steht der
Inhabername, weil § 5 TMG und die DSGVO ihn vorschreiben.

> **Offener Punkt:** `impressum.html:378` nennt die Kleinunternehmerregelung nach
> § 19 UStG. Diese Angabe ist laut Inhaber veraltet und widerspricht der Aussage
> „mehr als 800 Umzüge" auf derselben Website. Vor dem Livegang neuer Texte korrigieren.

---

## Quellen

- [Search Engine Land — Generative Engine Optimization 2026](https://searchengineland.com/mastering-generative-engine-optimization-in-2026-full-guide-469142)
- [Keywords Everywhere — E-E-A-T Playbook](https://keywordseverywhere.com/blog/google-e-e-a-t-guidelines-an-overview/)
- [Binärfabrik — Lokale Landingpages](https://binaerfabrik.de/ratgeber/local-landingpages/lokale-landingpages-erstellen)
- [Online Solutions Group — Local SEO](https://www.onlinesolutionsgroup.de/magazin/local-seo/)
- [rheinspace — SEO-Text: Struktur und Länge](https://rheinspace.de/insights/seo-text/)
- [IHK — Merkblatt irreführende Werbung](https://www.ihk.de/nordschwarzwald/recht/recht/handels-und-wettbewerbsrecht/merkblatt-irrefuehrende-werbung-2612030)
- [Trusted Shops — Werbung mit Kundenbewertungen](https://business.trustedshops.de/blog/legal/werbung-mit-kundenbewertungen-wer-haftet)
- [CXL — High-Converting Landing Pages](https://cxl.com/blog/how-to-build-a-high-converting-landing-page/)
- [Wortliga — Floskeln vermeiden](https://wortliga.de/floskeln-vermeiden-11-inhaltsleere-phrasen/)
