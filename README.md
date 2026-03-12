# Modularity Navigation Card

Standalone Modularity module for rendering parent pages together with their child pages as navigation cards in Municipio.

## Purpose

- Render selected parent pages as cards.
- Show up to three child pages directly in each card.
- Show remaining child pages behind a toggle.
- Support both classic Modularity modules and Gutenberg block rendering.

## Plugin structure

- Bootstrap: `modularity-navigation-card.php`
- App class: `source/php/App.php`
- Module class: `source/php/Module/NavigationCard.php`
- View: `source/php/Module/views/navigation-card.blade.php`
- ACF PHP export: `source/php/AcfFields/php/navigation-card-settings.php`
- ACF JSON export: `source/php/AcfFields/json/navigation-card-settings.json`

## Behavior

Each card contains:

- A parent page link
- Up to three visible child links
- An expandable section for remaining child links

The module normalizes card data in PHP and renders the markup through Blade. Toggle labels are generated from the total number of child pages.

## Gutenberg compatibility

The module supports block rendering through the `Modularity/Block/acf/navigation-card/Data` filter in `source/php/App.php`.

This is used to normalize raw block data before rendering so the module behaves consistently in block mode and regular Modularity mode.

## ACF setup

The plugin uses `AcfExportManager` when available and falls back to the PHP export when needed.

Field group:

- Slug: `navigation-card-settings`
- Key: `group_modularity_navigation_card_settings`

## Development notes

- PHP 8+ is required.
- The plugin is intended to be installed as a standalone Composer-managed WordPress plugin.
- View overrides and project-specific UI behavior can be handled outside this repository when a project needs local customization without changing the shared module.

## Typical change flow

1. Update module PHP, Blade view, or ACF definition in this plugin.
2. Validate the module in WordPress and Gutenberg.
3. Commit and push the plugin change.
4. Update the package version or reference in the consuming deployment repository.
