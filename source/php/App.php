<?php

declare(strict_types=1);

namespace ModularityNavigationCard;

use ModularityNavigationCard\Module\NavigationCard;

class App
{
    public function __construct()
    {
        add_action('init', [$this, 'registerModule']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
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

    public function enqueueAssets(): void
    {
        $stylePath = MODULARITYNAVIGATIONCARD_PATH . 'assets/css/navigation-card.css';
        $scriptPath = MODULARITYNAVIGATIONCARD_PATH . 'assets/js/navigation-card.js';

        if (file_exists($stylePath)) {
            wp_enqueue_style(
                'modularity-navigation-card',
                MODULARITYNAVIGATIONCARD_URL . '/assets/css/navigation-card.css',
                [],
                (string) filemtime($stylePath),
            );
        }

        if (file_exists($scriptPath)) {
            wp_enqueue_script(
                'modularity-navigation-card',
                MODULARITYNAVIGATIONCARD_URL . '/assets/js/navigation-card.js',
                [],
                (string) filemtime($scriptPath),
                true,
            );
        }
    }
}
