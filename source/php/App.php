<?php

declare(strict_types=1);

namespace ModularityNavigationCard;

class App
{
    public function __construct()
    {
        add_action('init', [$this, 'registerModule']);
        add_filter('/Modularity/externalViewPath', [$this, 'registerExternalViewPath'], 10, 1);
    }

    public function registerModule(): void
    {
        if (function_exists('modularity_register_module')) {
            modularity_register_module(MODULARITYNAVIGATIONCARD_MODULE_PATH, 'NavigationCard');
        }
    }

    public function registerExternalViewPath(array $paths): array
    {
        $paths['mod-navigation-card'] = MODULARITYNAVIGATIONCARD_MODULE_VIEW_PATH;

        return $paths;
    }
}
