---
name: schreibe-content
description: Schreibt Website-Texte für Ms Clean (Umzugsservice Mannheim) — neue Stadtseiten (einsatzgebiet/umzug-<stadt>.html), neue Leistungsseiten (umzug/<leistung>.html) und Überarbeitungen bestehender Abschnitte. Nutzen, sobald es um Text, Content, Copy, Überschriften, Meta-Beschreibungen, SEO-Texte, Landingpages oder eine neue Unterseite dieser Website geht. Erzeugt Vertrauen über belegbare Fakten statt über Adjektive.
---

# schreibe-content — Vertrauens-Content für msclean-mannheim.de

Ziel jedes Textes: **der Leser soll leichter eine Entscheidung treffen können.**
Das gelingt nicht durch Adjektive, sondern durch Fakten, die er nachprüfen kann.

## Das Kernproblem, das dieser Skill löst

Der Bestandstext dieser Website ist **austauschbar**. „Ihr zuverlässiger Partner",
„erfahrene Umzugshelfer", „fachgerecht und sorgfältig" — setzt man den Namen eines
Wettbewerbers ein, merkt es niemand. Das ist kein Stilproblem, sondern ein
Substanzproblem: **Text, der nichts Nachprüfbares behauptet, kann jeder schreiben.**

Der Bestand ist deshalb **keine Vorlage**. Er ist das, was ersetzt wird.
Verbindlich am Bestand sind nur zwei Dinge: die **HTML-Struktur** (`bausteine.md`)
und die **Fakten** (`fakten.md`). Der Ton wird hier neu gesetzt.

---

## Die Substanz-Regel

Die eine Regel, die alles andere trägt. Nach **jedem** geschriebenen Absatz prüfen:

> **Könnte ein Wettbewerber diesen Absatz wortgleich auf seine Website stellen?**
> Wenn ja, fehlt der Beleg. Umschreiben, bis die Antwort nein ist.

Der Test ist hart und soll es sein. Er scheitert an fast jedem Satz, der mit
„Wir bieten", „Wir stehen für" oder „Unser Team" beginnt.

Beweiskraft-Hierarchie — immer in dieser Reihenfolge versuchen:

| Stufe | Beispiel |
|---|---|
| 1. **Zahl** | „Mehr als 800 Umzüge in zwei Jahren" |
| 2. **Überprüfbarer Beleg** | „5,0 bei Google — lesen Sie die Rezensionen" (verlinkt) |
| 3. **Konkretes Beispiel** | „Sie erreichen uns auch sonntags um 19 Uhr" |
| 4. *Behauptung* | *nur, wenn 1–3 nachweislich nicht möglich sind* |

---

## Ablauf — zweistufig, mit Stopp

**Der Skill liefert erst einen Text-Entwurf und wartet auf Freigabe. Er baut niemals
sofort HTML.**

### Stufe 1 — Entwurf

1. **Auftrag klären.** Welcher Seitentyp (Stadtseite / Leistungsseite / Überarbeitung)?
   Welche Stadt bzw. Leistung? Nur fragen, was wirklich fehlt — keine Fragebogen-Salve.
2. **`references/fakten.md` und `references/stimme.md` lesen**, bevor ein Wort entsteht.
   Bei neuen Seiten zusätzlich `references/seitentypen.md`.
3. **Recherche.** Bei Stadtseiten Pflicht (Umfang siehe `seitentypen.md`) — ohne echte
   lokale Substanz keine Stadtseite. Sonst: Welche Belege gibt es für diese Seite?
4. **Entwurf als Markdown** in den Scratchpad schreiben, in dieser Reihenfolge:
   - Meta-Title und Meta-Description
   - die Seite Abschnitt für Abschnitt, mit Überschriftenebene
   - **Warnliste** aller `[BELEG NÖTIG: …]`-Stellen am Ende
5. **Stopp.** Entwurf vorlegen, Freigabe abwarten.

### Stufe 2 — HTML (erst nach Freigabe)

6. Header und Footer aus einer **aktiven** Seite kopieren (`index.html`, `umzug.html`) —
   **nie aus `archiv/`**, dort sind die Asset-Pfade kaputt.
7. Bausteine aus `references/bausteine.md`, Meta und JSON-LD aus
   `references/seo-und-recht.md`.
8. Bei neuen Seiten: `sitemap.xml` ergänzen, interne Links setzen.
9. Checkliste unten durchgehen.

---

## Harte Verbote

Diese Punkte sind nicht verhandelbar. Sie stehen hier und nicht in einer Referenzdatei,
damit sie immer im Kontext sind.

### 1. Keine fremden Zahlen

`schachservice.de` dient als **Stil**-Vorbild, nicht als Faktenquelle. Diese Angaben
gehören einem anderen Betrieb und sind gesperrt:

> „15+ Jahre" · „seit 2009" · „5.000+ Umzüge" · „4.9" · „312 Bewertungen" ·
> „80 Einzelleistungen" · „9 Serviceregionen" · „geprüfter Fachbetrieb" ·
> „geprüftes Fachpersonal"

Sie in Ms-Clean-Texte zu übernehmen wäre irreführende Werbung nach § 5 UWG — und
abmahnen würde das am ehesten genau dieser Wettbewerber. Die echten Zahlen stehen in
`references/fakten.md` und sind stark genug.

### 2. Kein Inhabername im Content

Der Name des Inhabers erscheint in **keiner** Überschrift, keinem Fließtext, keinem
Testimonial, keinem `alt`-Attribut und keinem JSON-LD-Feld.

Einzige Ausnahme sind `impressum.html` und `datenschutz.html`, wo § 5 TMG bzw. die
DSGVO ihn vorschreiben. **Diese beiden Dateien fasst der Skill nicht an.**

Persönlichkeit wird über **Rolle und Erreichbarkeit** transportiert: „ein fester
Ansprechpartner", „dieselbe Person von der Anfrage bis zum letzten Karton", „Sie rufen
an, es geht jemand ran".

### 3. Kein Euro-Betrag

Der sichtbare Preis ist überall die Zeichenkette `Ab Anfrage`. Die Beträge in
`knowladge/` (650 €, 1.332–1.545 €) sind interne Kalkulation aus der Küchenmontage-Zeit
und gehören nicht auf die Website.

### 4. Keine Superlative ohne Beleg

„Marktführer", „bestes Umzugsunternehmen der Region", „günstigster Anbieter". Sobald
eine Aussage nachprüfbar ist, ist sie juristisch eine Tatsachenbehauptung und muss
beweisbar sein. „Erfahrene Umzugshelfer" ist zulässig, „Marktführer in Mannheim" nicht.

### 5. Keine erfundenen Testimonials

Wurde auf dieser Website bereits einmal wegen UWG-Risiko zurückgebaut. Sozialer Beweis
läuft ausschließlich über `.review-badges` mit Link auf das Quellprofil.

### 6. Kein `aggregateRating` im JSON-LD

Bewusste Entscheidung vom 2026-08-15: Bewertungen von Fremdplattformen als eigenes
Rating auszuzeichnen verstößt gegen Googles Richtlinien für strukturierte Daten und kann
eine manuelle Maßnahme auslösen.

### 7. Schreibweise „Ms Clean"

Nie „MS Clean". Die Website schreibt es 105× so und 0× anders — `CLAUDE.md` schreibt es
durchgängig falsch, das ist kein Vorbild.

### 8. Keine Küchenmontage, keine Reinigung als Leistung

Das Geschäft ist ausschließlich Umzug. Zwei gewollte Überbleibsel dürfen bleiben: die
Kachel „Küche mitnehmen & aufbauen" und die Endreinigung als Zeile im Premium-Paket.

### 9. `knowladge/` ist Archiv

Bundesland, Navigation, Keywords, Preise und Leistungen dort sind vom Live-Stand
überholt. Die Live-HTML ist die einzige Quelle der Wahrheit.

---

## Unbelegte Aussagen

Wenn eine Formulierung stark wäre, aber der Beleg fehlt: **nicht weglassen und nicht
erfinden**, sondern markieren.

```
Über [BELEG NÖTIG: Anzahl] Umzüge allein in Heidelberg.
```

Alle Markierungen am Ende des Entwurfs als Warnliste sammeln:

```
## Offene Belege
- [ ] Anzahl Umzüge speziell in Heidelberg — bislang nur die Gesamtzahl 800 belegt
- [ ] Aktuelle Anzahl Google-Bewertungen für „5,0 aus N Bewertungen"
```

Nichts Unbelegtes geht ungeprüft ins HTML.

---

## Referenzdateien

Bei Bedarf lesen — nicht alle auf einmal.

| Datei | Wofür |
|---|---|
| `references/fakten.md` | **Immer zuerst.** Zahlen, Kontakt, Versprechen, Städte, Pakete |
| `references/stimme.md` | **Immer.** Die vier Säulen, Formulierungsmuster, Verbotslisten |
| `references/seitentypen.md` | Bei neuen Seiten: Gliederung, Recherchepflicht, Pfad-Falle |
| `references/bausteine.md` | Beim HTML-Bau: welcher Block nimmt welchen Text auf |
| `references/seo-und-recht.md` | Meta, JSON-LD, KI-Suche, UWG |

---

## Checkliste vor der Abgabe

**Substanz**
- [ ] Jeder Absatz besteht den Wettbewerber-Test
- [ ] Jede Qualitätsaussage hat ihren Beleg im selben Sichtfeld
- [ ] Keine Floskel aus der Liste in `stimme.md`

**Fakten**
- [ ] Keine gesperrte Fremdzahl im Text
- [ ] Zahlen stimmen mit `fakten.md` überein; Betriebsalter (2 Jahre) und
      Branchenerfahrung (~5 Jahre) sind nicht vermischt
- [ ] Kein Euro-Betrag
- [ ] Kein Inhabername — gegen den in `impressum.html` genannten Namen greppen,
      das Ergebnis muss leer sein
- [ ] „Ms Clean" korrekt geschrieben
- [ ] Telefon, E-Mail, Adresse, Öffnungszeiten, Radius unverändert korrekt

**Bei neuen Seiten zusätzlich**
- [ ] Stadtseiten: mindestens 60 % des Textes wirklich stadtspezifisch
- [ ] Asset-Pfade auf `../assets/…` umgestellt (Unterordner!)
- [ ] `canonical`, `og:url` und JSON-LD-`url` zeigen auf denselben neuen Pfad
- [ ] Eintrag in `sitemap.xml`, interner Link von der Elternseite
- [ ] Breadcrumb-Beschriftungen stimmen mit der `BreadcrumbList` überein
- [ ] Bei geänderten FAQ: Antwort **auch** im `FAQPage`-JSON-LD angepasst
