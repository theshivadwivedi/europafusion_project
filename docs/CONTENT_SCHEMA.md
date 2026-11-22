# Page Content Schema (country & pathway pages)

Country pages (`/study-in-{country}`) and Germany pathway pages (`/germany/{pathway}`)
are rendered from JSON documents in `resources/content/pages/*.json` (one file per page,
read by `App\Models\Page` – no database). Edits show on the next page load. Each section
is rendered by `resources/views/blocks/{type}.blade.php`. Pages are listed in file-name order.

## Page document

```json
{
  "slug": "study-in-lithuania",          // URL path without leading slash ("germany/master" for pathways)
  "kind": "country",                     // "country" | "pathway"
  "destination": "lithuania",            // destination slug this page belongs to
  "title": "Study in Lithuania",         // <title>, breadcrumb, menus
  "nav_label": "Lithuania",              // short label
  "meta_description": "…",               // <= 160 chars, plain text
  "sections": [ { "type": "hero", … }, … ]
}
```

## Common block fields

Every block may use these (all optional except `type`):

| field     | meaning                                                                  |
|-----------|--------------------------------------------------------------------------|
| `type`    | **required** – one of the block types below                              |
| `id`      | anchor id (kebab-case) for in-page links                                 |
| `eyebrow` | small label above the heading (sentence case, e.g. "Fees & funding")     |
| `title`   | section heading (h2)                                                     |
| `intro`   | one paragraph under the heading                                          |
| `note`    | small print shown under the block (disclaimers, "figures are indicative")|
| `tone`    | `"light"` (default alternating) \| `"tint"` \| `"dark"`                  |

Text fields are plain text. Fields named `paragraphs`, `desc`, `a` and `text` may
contain these inline tags only: `<strong>`, `<em>`, `<br>`, `<a href="…">`.
`a` (FAQ answer) may additionally contain `<ul><li>` lists and `<p>`.

CTA objects: `{ "label": "Book Free Consultation", "action": "counselling" }` where
`action` is `"counselling"` (opens counselling modal), `"evaluation"` (opens profile
evaluation modal) or a site path such as `"/germany/master"` or `"/contact"`.

Icons are Bootstrap Icons names (see `node_modules/bootstrap-icons/icons/*.svg`),
e.g. `mortarboard`, `globe-europe-africa`, `briefcase`, `cash-coin`, `airplane`,
`house-door`, `file-earmark-text`, `translate`, `award`, `shield-check`, `people`,
`graph-up-arrow`, `building`, `clock`, `calendar-event`, `passport`, `heart-pulse`,
`cpu`, `bank`, `compass`, `geo-alt`, `check2-circle`, `journal-check`, `hospital`,
`clipboard-check`, `currency-euro`, `lightbulb`, `patch-check`, `piggy-bank`, `stars`.

## Block types

### `hero` (first block of every page)
`eyebrow`, `title`, `highlight` (exact substring of `title` to accent), `lead`,
`tagline` (short line such as "World-class education • Innovation-led research"),
`chips` [string], `ctas` [CTA], `facts` [{ `value`, `label` }] (up to 4 compact facts).

### `intro`
Narrative section. `eyebrow`, `title`, `paragraphs` [string],
`aside` { `title`, `items` [string] } (optional side box), `cta` CTA (optional).

### `stats`
`items` [{ `value`, `label`, `sub` }] – 3–6 headline figures.

### `cards`
Grid of cards. `style`: `"icon"` | `"numbered"` | `"plain"` | `"check"`,
`columns`: 2 | 3 | 4,
`items` [{ `title`, `desc`, `icon`?, `badge`?, `meta`?, `points`? [string], `tags`? [string] }].
Use for reasons-to-study, services, course types, campus types, work rules, etc.

### `checklist`
`items` [string] **or** `groups` [{ `title`, `intro`?, `items` [string] }], `columns`: 1 | 2.
Use for eligibility criteria, document lists, permit requirements.

### `universities`
`items` [{ `name`, `city`?, `type`?, `rank`?, `desc`? }], `cta_label` (default
"Explore courses", opens counselling modal).

### `table`
`columns` [string], `rows` [[string]], optional `chips_title` + `chips` [string].

### `costs`
`chart_title`, `scale_max` (number, EUR), `rows` [{ `label`, `value` (display text),
`min` (number), `max` (number) }], `cards_title`, `cards` [{ `title`, `desc` }].
Rows without numeric amounts may omit `min`/`max` (no bar is drawn).

### `steps`
Ordered process. `items` [{ `title`, `desc`, `phase`? , `tag`? }]. When `phase`
changes between items a phase heading is printed (e.g. "Phase one — Preparation").

### `timeline`
Dated/labelled milestones. `items` [{ `label` (e.g. "Oct", "Year 1"), `title`, `desc` }].

### `split`
Two (or three) side-by-side columns for comparisons.
`columns` [{ `badge`?, `title`, `subtitle`?, `paragraphs`? [string], `points`? [string] }].

### `chronology`
Horizontal year timeline. `scale` [number], `items` [{ `name`, `year` }].

### `testimonials`
`items` [{ `name`, `meta`?, `university`?, `program`?, `quote`? }].

### `faq`
`items` [{ `q`, `a` }] – rendered as an accessible accordion.

### `cta`
Closing call to action. `title`, `text`, `ctas` [CTA], `note`.

### `notice`
`text` – highlighted disclaimer/information box.
