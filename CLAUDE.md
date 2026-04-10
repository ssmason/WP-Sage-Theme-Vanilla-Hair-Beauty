# Vanilla Hair & Beauty — Project Context

## Stack
- WordPress via Bedrock (Composer-managed)
- Sage theme (Blade templating, Laravel conventions)
- PHP 8.2
- ACF Free
- MySQL 8

## Code Standards
All code must pass:
- PHPCS with WordPress coding standards ruleset
- Laravel Pint for PHP formatting
- ESLint for JavaScript
- No errors or warnings — clean output only

PHP rules:
- Strict types declared on every file: `declare(strict_types=1);`
- Type hints on all function parameters and return types
- Proper sanitization and escaping on all input/output
- Nonces on all forms and AJAX requests
- Prepared statements for all direct DB queries

## Project Structure
```
web/
  app/
    themes/vanilla/
      app/                  ← PHP — controllers, setup, fields
        setup.php           ← CPT registration, theme support, menus
        filters.php         ← WordPress filters
        fields/             ← ACF field group registration
      resources/
        views/              ← Blade templates
          layouts/          ← Base layouts
          partials/         ← Reusable components
          sections/         ← Page sections
        scripts/            ← JS modules
      public/               ← Compiled assets (do not edit)
      composer.json
  plugins/
config/                     ← Bedrock environment config
composer.json               ← Root Composer
.env                        ← Environment variables (never commit)
```

## Design Tokens
Defined in `tailwind.config.js` — extend the default theme:

```js
colors: {
  white:    '#FDFAF5',
  cream:    '#F5F0E8',
  warm:     '#EDE6D6',
  sand:     '#D4C5A9',
  stone:    '#9A8A75',
  mid:      '#5C4E3A',
  dark:     '#1C1712',
  terra:    '#B5704A',
  'terra-dk': '#8C4E2F',
},
fontFamily: {
  serif: ['Cormorant Garamond', 'Georgia', 'serif'],
  sans:  ['DM Sans', 'sans-serif'],
},
```

## Typography Scale
| Role | Font | Size | Weight |
|---|---|---|---|
| Display / H1 | Cormorant Garamond | 38–52px | 300 |
| H2 | Cormorant Garamond | 28px | 400 |
| H3 / card title | Cormorant Garamond | 20px | 300 |
| Eyebrow | DM Sans | 11px | 500 + uppercase |
| Body | DM Sans | 15px | 300 |
| Caption | DM Sans | 12px | 400 |

## Styling
- Tailwind CSS only — no custom CSS files
- Utility classes in Blade templates
- No `<style>` blocks, no inline styles, no SCSS
- Tailwind config extends design tokens (colours, fonts)

## Custom Fields
- ACF Free (not Pro)
- Custom blocks plugin (bespoke) enables query loops with ACF fields inserted — use this pattern for dynamic CPT display rather than building custom PHP loops where possible

## Custom Post Types

### Services (`service`)
ACF Fields:
- `service_description` — textarea
- `service_image` — image
- `service_price_from` — text

### Portfolio (`portfolio`)
ACF Fields:
- `portfolio_description` — textarea
- `portfolio_image` — image
- `portfolio_service` — relationship → service

### Team (`team_member`)
ACF Fields:
- `member_role` — text
- `member_bio` — textarea
- `member_photo` — image
- `member_specialities` — repeater → `speciality` (text)

## Homepage Structure
Sections in order:
1. Sticky nav — logo left, 3 links + Book Now CTA right
2. Hero — split layout, serif headline, terracotta italic accent, two CTAs
3. Services grid — 3-col, image-led cards, links to service pages
4. Testimonials — dark background, 3-col, star rating + quote + author
5. Gallery strip — 4-up, links to full portfolio page
6. Footer CTA band — terracotta background, single booking CTA

## Conventions
- No jQuery — vanilla JS only
- Tailwind utility classes only — no BEM, no custom CSS
- Blade components for anything used more than once
- All CPTs registered in `app/setup.php`
- All ACF field groups registered in `app/fields/`
- Images always via ACF image fields, never hardcoded
- Strings always translation-ready via `__()`
- No inline styles in templates

## Build Status
- [ ] Tailwind config — tokens, fonts, custom colours
- [ ] Base layout — `layouts/app.blade.php`
- [ ] Navigation partial
- [ ] Hero section
- [ ] Services CPT registration
- [ ] Services ACF field group
- [ ] Services grid section
- [ ] Portfolio CPT registration
- [ ] Portfolio ACF field group
- [ ] Portfolio page template
- [ ] Team CPT registration
- [ ] Team ACF field group
- [ ] Team page template
- [ ] Testimonials section
- [ ] Gallery strip section
- [ ] Footer CTA band
- [ ] Single service page template
- [ ] Homepage assembly (`front-page.blade.php`)

## Design Reference
Full design spec including palette, type specimens and wireframe:
`docs/design-spec.html`

## Asset Compilation
- `npm run dev` — watch mode during development
- `npm run build` — production build
- Compiled assets output to `public/` — never edit directly

## Notes
- Bedrock uses `web/` as document root — not standard WP structure
- Environment config via `.env` — never hardcode credentials
- WP CLI available — prefer it over direct DB queries where possible