<?php

declare(strict_types=1);

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_modularity_navigation_card_settings',
    'title' => __('Navigationspuff', 'modularity-navigation-card'),
    'fields' => [
        [
            'key' => 'field_navigation_card_editor_info',
            'label' => __('Instruktion', 'modularity-navigation-card'),
            'name' => '',
            'type' => 'message',
            'message' => __('Välj antal kolumner för modulen och lägg sedan till en eller flera navigationspuffar. Varje puff hämtar den valda parentsidan och dess direkta undersidor automatiskt.', 'modularity-navigation-card'),
            'new_lines' => 'wpautop',
            'esc_html' => 0,
        ],
        [
            'key' => 'field_navigation_card_column_count',
            'label' => __('Antal kolumner', 'modularity-navigation-card'),
            'name' => 'column_count',
            'type' => 'select',
            'required' => 1,
            'instructions' => __('Välj hur många kolumner som ska visas från desktop och uppåt.', 'modularity-navigation-card'),
            'choices' => [
                '1' => __('1 kolumn', 'modularity-navigation-card'),
                '2' => __('2 kolumner', 'modularity-navigation-card'),
                '3' => __('3 kolumner', 'modularity-navigation-card'),
            ],
            'default_value' => '3',
            'return_format' => 'value',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'placeholder' => '',
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
        ],
        [
            'key' => 'field_navigation_card_cards',
            'label' => __('Navigationspuffar', 'modularity-navigation-card'),
            'name' => 'cards',
            'type' => 'repeater',
            'required' => 1,
            'instructions' => __('Lägg till en eller flera puffar. Varje rad hämtar innehåll från vald parentsida.', 'modularity-navigation-card'),
            'layout' => 'row',
            'button_label' => __('Lägg till puff', 'modularity-navigation-card'),
            'min' => 1,
            'collapsed' => 'field_navigation_card_card_parent_page',
            'sub_fields' => [
                [
                    'key' => 'field_navigation_card_card_parent_page',
                    'label' => __('Parentsida', 'modularity-navigation-card'),
                    'name' => 'parent_page',
                    'type' => 'page_link',
                    'required' => 1,
                    'instructions' => __('Välj den sida som ska visas överst i navigationskortet.', 'modularity-navigation-card'),
                    'post_type' => [
                        'page',
                    ],
                    'post_status' => [
                        'publish',
                    ],
                    'taxonomy' => '',
                    'allow_archives' => 0,
                    'multiple' => 0,
                    'allow_null' => 0,
                    'return_format' => 'id',
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-navigation-card',
            ],
        ],
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/navigation-card',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
]);
