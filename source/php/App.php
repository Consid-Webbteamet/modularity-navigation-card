<?php

declare(strict_types=1);

namespace ModularityNavigationCard;

use ModularityNavigationCard\Module\NavigationCard;

class App
{
    public function __construct()
    {
        add_action('init', [$this, 'registerModule']);
        add_filter('/Modularity/externalViewPath', [$this, 'registerExternalViewPath'], 10, 1);
        add_filter('Modularity/Block/acf/navigation-card/Data', [$this, 'normalizeBlockData'], 10, 3);
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

    public function normalizeBlockData(array $viewData, array $block, object $module): array
    {
        if (!$module instanceof NavigationCard) {
            return $viewData;
        }

        return $module->hydrateViewDataFromRawBlockData($viewData, (array) ($block['data'] ?? []));
    }
}
