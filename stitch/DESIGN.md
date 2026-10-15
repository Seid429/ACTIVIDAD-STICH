---
name: Logística Peninsular
colors:
  surface: '#fef9ed'
  surface-dim: '#dedace'
  surface-bright: '#fef9ed'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f8f3e7'
  surface-container: '#f2ede2'
  surface-container-high: '#ede8dc'
  surface-container-highest: '#e7e2d6'
  on-surface: '#1d1c15'
  on-surface-variant: '#414845'
  inverse-surface: '#323029'
  inverse-on-surface: '#f5f0e4'
  outline: '#717975'
  outline-variant: '#c0c8c4'
  surface-tint: '#3f665a'
  primary: '#002920'
  on-primary: '#ffffff'
  primary-container: '#173f35'
  on-primary-container: '#82aa9d'
  inverse-primary: '#a6cfc1'
  secondary: '#9d4222'
  on-secondary: '#ffffff'
  secondary-container: '#fe8c65'
  on-secondary-container: '#742405'
  tertiary: '#1c251f'
  on-tertiary: '#ffffff'
  tertiary-container: '#323a34'
  on-tertiary-container: '#9ba49c'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#c1ecdd'
  primary-fixed-dim: '#a6cfc1'
  on-primary-fixed: '#002019'
  on-primary-fixed-variant: '#274e43'
  secondary-fixed: '#ffdbd0'
  secondary-fixed-dim: '#ffb59d'
  on-secondary-fixed: '#390c00'
  on-secondary-fixed-variant: '#7e2c0c'
  tertiary-fixed: '#dce5dc'
  tertiary-fixed-dim: '#c0c9c1'
  on-tertiary-fixed: '#151d18'
  on-tertiary-fixed-variant: '#404943'
  background: '#fef9ed'
  on-background: '#1d1c15'
  surface-variant: '#e7e2d6'
typography:
  headline-xl:
    fontFamily: Outfit
    fontSize: 40px
    fontWeight: '800'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-xl-mobile:
    fontFamily: Outfit
    fontSize: 30px
    fontWeight: '800'
    lineHeight: 36px
    letterSpacing: -0.01em
  headline-lg:
    fontFamily: Outfit
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Outfit
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: 0em
  headline-md:
    fontFamily: Outfit
    fontSize: 22px
    fontWeight: '700'
    lineHeight: 28px
  headline-sm:
    fontFamily: Outfit
    fontSize: 18px
    fontWeight: '700'
    lineHeight: 24px
  body-lg:
    fontFamily: DM Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: DM Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: DM Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-lg:
    fontFamily: DM Sans
    fontSize: 15px
    fontWeight: '700'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-md:
    fontFamily: DM Sans
    fontSize: 13px
    fontWeight: '700'
    lineHeight: 18px
    letterSpacing: 0.02em
  label-sm:
    fontFamily: DM Sans
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 14px
    letterSpacing: 0.04em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.25rem
  margin: 1.5rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.25rem
---

## Brand & Style

This design system is engineered for independent freight operators (*hombre-camión*) across the Yucatán Peninsula corridor (Mérida, Progreso, Umán, Caucel). The persona manages high-stakes cash flows, fuel spikes, tolls (*casetas*), and route profitability directly from mobile devices or workshop desktops in high-glare tropical environments. 

The aesthetic is **Tactile Modern Utility**:
- **Dignified & Grounded:** Rooted in regional textures—limestone, henequén, and terracotta—avoiding generic Silicon Valley minimalism in favor of a warm, reliable industrial tool.
- **High-Legibility Operational Clarity:** Prioritizes glanceable metrics, immediate profitability feedback, and prominent WhatsApp-first communication workflows common to Mexican transport operations.
- **Physical Metaphors:** Elevated crisp card surfaces sit on a rich cream baseline, using warm, sun-cast ambient shadows rather than synthetic gray blurs.

## Colors

The palette directly solves for outdoor legibility and intuitive risk evaluation:

- **Primary Deep Green (`#173F35`):** Serves as the structural anchor. Applied to the primary navigation drawer, top-level headings, key figures, and high-contrast structural dividers. Evokes institutional longevity and deep confidence.
- **Accent Terracotta (`#C45F3C`):** High-priority callouts, primary conversion buttons, real-time alerts, and net revenue highlights. Draws immediate eye tracking on critical margins.
- **Neutral Cream (`#F4EFE3`):** Canvas background. Reduces visual fatigue and glare under bright sunlight compared to pure `#FFFFFF`.
- **Card Surface (`#FFFFFF`):** High-contrast containment layer for dense logistics data, route manifests, and calculation sheets.
- **Muted Green-Gray (`#6D766F`):** Used for micro-copy, unit indicators (e.g., `/km`, `Lts`, `MXN`), inactive navigation targets, and subtle card borders.

### Operational Status Palette
- **Forest Green (`#2E7D5B`):** *Conviene / Aprobado / En Regla* — Positive unit economics, confirmed payments.
- **Warm Amber (`#D9A23B`):** *Pendiente / En Ruta / Margen Ajustado* — Cautionary alerts, active transits, moderate margin compression.
- **Brick Red (`#B3412F`):** *Pierdes Dinero / Atraso Crítico* — Immediate intervention required, unprofitable load offers, delinquent invoicing.

## Typography

The type scale combines **Outfit** for structural authority and high-impact visual numerical readouts with **DM Sans** for utilitarian legibility:

- **Outfit (Headlines & Big Numbers):** Weights 700 and 800 provide geometric weight to route rates, currency totals, and truck configuration titles (e.g., *Torton 2 Ejes*, *Rabón 14T*). Numbers in Outfit must use tabular figures (`font-variant-numeric: tabular-nums`) when comparing rate matrices.
- **DM Sans (Body & Operational Metadata):** 16px serves as the strict minimum baseline for all readable body text to guarantee readability on mobile dashboards mounted to truck dashboards. 
- **Labels & Badges:** Utilize DM Sans Bold in uppercase or title-case with slightly tracked spacing for rapid scanning under vibration or poor illumination.

## Layout & Spacing

The layout utilizes a structured **12-column fluid grid** for desktop and wide-tablet viewports, condensing to a single column on mobile devices.

### Breakpoints & Adaptations
- **Desktop (1024px+):** Fixed 280px navigation sidebar anchored to `#173F35`, main operational board spanning fluid columns with 20px (`1.25rem`) gutters, and an optional persistent 380px contextual calculation rail (Profit Estimator).
- **Tablet (768px - 1023px):** Sidebar collapses to an icon rail (72px). Metric KPI modules drop from a 4-column layout to a 2x2 grid. Estimator shifts to a flyout bottom sheet.
- **Mobile (< 768px):** Single-column stack. Page margins compress to 16px (`1rem`). Critical actions (Accept Route, Call Dispatch, Send WhatsApp Quote) are fixed to a sticky bottom action bar with 56px minimum touch height.

Rhythm is maintained strictly through multiples of 4px, leaning on `space-md` (16px) for interior card padding and `space-lg` (24px) for inter-card separation.

## Elevation & Depth

Visual hierarchy uses **tonal surfaces backed by warm, sun-cast ambient shadows** to reflect the natural regional daylight and eliminate cold digital greys:

- **Level 0 (Base Canvas):** Flat `#F4EFE3` background.
- **Level 1 (Operational Cards & Modules):** Pure white (`#FFFFFF`) surface with a 1px border using `rgba(109, 118, 111, 0.18)` and an ambient warm shadow: `0 4px 16px -2px rgba(23, 63, 55, 0.06), 0 2px 6px -1px rgba(196, 95, 60, 0.04)`.
- **Level 2 (Active Panels & Popovers):** Dynamic Estimator drawer, dropdown selectors, and active tooltips: `0 12px 32px -4px rgba(23, 63, 55, 0.12), 0 4px 12px -2px rgba(0, 0, 0, 0.04)`.
- **Level 3 (Modal Dialogs & WhatsApp Simulators):** Floating critical dialogs: `0 24px 48px -8px rgba(23, 63, 55, 0.22)`.

## Shapes

The interface adopts a sturdy, balanced corner radius:
- **Base Surfaces & Cards:** Standardized to 16px (`1rem`), softening technical tables and financial density while feeling modern and durable.
- **Interactive Controls (Inputs, Buttons, Dropdowns):** 10px to 12px radius, providing a tactile, pill-adjacent surface that invites touch on glove-friendly or mobile displays.
- **Status Pills, Tags, and Audio Scrubber Handles:** Fully rounded (`9999px`) to immediately communicate self-contained metadata without being mistaken for data-entry containers.

## Components

### Buttons
- **Primary:** Background `#C45F3C`, text `#FFFFFF`, font `Outfit 700`, 12px radius, min-height 48px (52px on mobile). Hover: `#AD4F30`. Active: scales down to `0.98`.
- **Secondary:** Background `#173F35`, text `#FFFFFF`, font `Outfit 700`. Used for confirmation actions (e.g., *Confirmar Flete*).
- **Outlined / Tertiary:** Border 1.5px solid `#173F35`, text `#173F35`, background transparent. Hover: background `rgba(23, 63, 55, 0.05)`.

### KPI Metric Cards
- White `#FFFFFF` card with 16px corner radius.
- Top row: Muted label (`#6D766F`, `label-md`) paired with an icon in a low-opacity circular tint.
- Middle row: Metric value in `Outfit 800` (`#173F35`, 32px), with micro-indicators (e.g., `MXN`, `/km`).
- Bottom row: Status Trend Chip (e.g., Forest Green pill: `+12.4% vs semana ant.`).

### Profit Margin Estimator (Side Panel)
- Sticky side-rail component styled with an internal sub-card hierarchy.
- **Margin Pill:** Dynamic status display at the top:
  - If net margin > 22%: Forest Green background (`#2E7D5B`), label: *Conviene (31% Bruto)*.
  - If net margin 10–21%: Amber background (`#D9A23B`), label: *Margen Ajustado (14%)*.
  - If net margin < 10%: Brick Red background (`#B3412F`), label: *Pierdes Dinero (-4%)*.
- Dedicated breakdown rows for *Diésel estimado*, *Casetas (IAPOS/SCT)*, *Comisión maniobras*, and *Sueldo chofer*.

### WhatsApp Simulator Bubble
- Emulates client and broker communication flows in Mérida:
- Card surface with light green tinted interior (`#E7F3EC`) for inbound broker messages; white surface (`#FFFFFF`) for operator counter-offers.
- Embedded **Audio Note Player Preview**:
  - Horizontal scrub line with a Terracotta (`#C45F3C`) playback head.
  - Rounded play button (36px), duration timestamp (`0:42`), and voice waveform bars dynamically animated.

### Input Fields & Selectors
- Background `#FFFFFF`, border 1.5px `#6D766F` at 30% opacity. Height 48px.
- Focus state: Border transitions to `#173F35` with an outer ring of `0 0 0 3px rgba(23, 63, 55, 0.15)`.
- Truck class selector chips (e.g., *Torton*, *Rabón*, *Trailer 53'*): Segmented button toggles with active background `#173F35` and white text.