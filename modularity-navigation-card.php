<?php

declare(strict_types=1);

/**
 * Plugin Name:       Modularity Navigation Card
 * Description:       A Modularity module for parent pages and their child pages.
 * Version:           0.1.0
 * Author:            Consid Webbteamet
 * Text Domain:       modularity-navigation-card
 * Domain Path:       /languages
 */

namespace ModularityNavigationCard;

if (!defined('WPINC')) {
    die;
}

define('MODULARITYNAVIGATIONCARD_PATH', plugin_dir_path(__FILE__));
define('MODULARITYNAVIGATIONCARD_URL', plugins_url('', __FILE__));
define('MODULARITYNAVIGATIONCARD_MODULE_PATH', MODULARITYNAVIGATIONCARD_PATH . 'source/php/Module/');
define('MODULARITYNAVIGATIONCARD_MODULE_VIEW_PATH', MODULARITYNAVIGATIONCARD_PATH . 'source/php/Module/views');

add_action('init', static function (): void {
    load_plugin_textdomain('modularity-navigation-card', false, plugin_basename(dirname(__FILE__)) . '/languages');
});

$autoload = MODULARITYNAVIGATIONCARD_PATH . 'vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
} else {
    spl_autoload_register(static function (string $class): void {
        $prefix = __NAMESPACE__ . '\\';
        if (strpos($class, $prefix) !== 0) {
            return;
        }

        $relativeClass = substr($class, strlen($prefix));
        $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        $file = MODULARITYNAVIGATIONCARD_PATH . 'source/php/' . $relativePath;

        if (file_exists($file)) {
            require_once $file;
        }
    });
}

add_action('acf/init', static function (): void {
    if (class_exists('\\AcfExportManager\\AcfExportManager')) {
        $acfExportManager = new \AcfExportManager\AcfExportManager();
        $acfExportManager->setTextdomain('modularity-navigation-card');
        $acfExportManager->setExportFolder(MODULARITYNAVIGATIONCARD_PATH . 'source/php/AcfFields/');
        $acfExportManager->autoExport([
            'navigation-card-settings' => 'group_modularity_navigation_card_settings',
        ]);
        $acfExportManager->import();

        return;
    }

    $acfFields = MODULARITYNAVIGATIONCARD_PATH . 'source/php/AcfFields/php/navigation-card-settings.php';
    if (file_exists($acfFields)) {
        require_once $acfFields;
    }
});

add_action('plugins_loaded', static function (): void {
    if (!class_exists(App::class)) {
        return;
    }

    new App();
});
