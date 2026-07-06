# Custom WordPress Theme

Bespoke WordPress theme for an architecture studio. 

**Stack:** WordPress · PHP · SCSS · Bootstrap 5 (grid only) · ACF Free · Polylang

## What's in here

- Custom post types: portfolio projects, publications, press coverage, services
- ACF field groups registered programmatically (no JSON sync)
- Custom gallery metabox via `wp.media` (workaround for ACF Free's missing Gallery field)
- Multilingual setup with Polylang, with per-field copy/translate rules
- SCSS design tokens: palette, font weight variables, utility classes
- Self-hosted fonts via `theme.json` + Google Fonts for body text
- `sp_logo('black' | 'white')` helper for header variants
