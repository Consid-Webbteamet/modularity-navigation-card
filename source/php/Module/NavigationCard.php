<?php

declare(strict_types=1);

namespace ModularityNavigationCard\Module;

use WP_Post;

class NavigationCard extends \Modularity\Module
{
    public $slug = 'navigation-card';
    public $supports = [];
    public $isBlockCompatible = true;
    public $expectsTitleField = false;

    public function init(): void
    {
        $this->nameSingular = __('Navigationspuff', 'modularity-navigation-card');
        $this->namePlural = __('Navigationspuffar', 'modularity-navigation-card');
        $this->description = __(
            'Visar flera valda parentsidor och deras direkta undersidor.',
            'modularity-navigation-card',
        );
    }

    public function data(): array
    {
        $fields = $this->getFields();
        $cards = $this->normalizeCards($this->extractCardRows($fields));
        $columnCount = $this->normalizeColumnCount($fields['column_count'] ?? 3);

        return [
            'cards' => $cards,
            'columnCount' => $columnCount,
            'gridClass' => sprintf('navigation-card-grid--columns-%d', $columnCount),
        ];
    }

    public function template(): string
    {
        return 'navigation-card.blade.php';
    }

    /**
     * @param array<string, mixed> $viewData
     * @param array<string, mixed> $blockData
     * @return array<string, mixed>
     */
    public function hydrateViewDataFromRawBlockData(array $viewData, array $blockData): array
    {
        $cards = $this->normalizeCards($this->extractCardRows($blockData));
        $columnCount = $this->normalizeColumnCount($blockData['column_count'] ?? $viewData['columnCount'] ?? 3);

        $viewData['cards'] = $cards;
        $viewData['columnCount'] = $columnCount;
        $viewData['gridClass'] = sprintf('navigation-card-grid--columns-%d', $columnCount);

        return $viewData;
    }

    /**
     * @param array<string, mixed> $fields
     * @return array<int, array{parent_page:mixed}>
     */
    private function extractCardRows(array $fields): array
    {
        $cards = $fields['cards'] ?? [];

        if (is_array($cards)) {
            return array_values(array_filter($cards, 'is_array'));
        }

        $cardCount = absint($cards);

        if ($cardCount < 1) {
            return [];
        }

        $rows = [];

        for ($index = 0; $index < $cardCount; $index++) {
            $rows[] = [
                'parent_page' => $fields[sprintf('cards_%d_parent_page', $index)] ?? null,
            ];
        }

        return $rows;
    }

    /**
     * @param array<int, array{parent_page:mixed}> $cards
     * @return array<int, array{
     *     parent:array{url:string,title:string},
     *     visibleChildren:array<int, array{url:string,title:string}>,
     *     hiddenChildren:array<int, array{url:string,title:string}>,
     *     totalChildren:int,
     *     hasOverflow:bool,
     *     toggleLabel:string,
     *     toggleExpandedLabel:string,
     *     toggleId:string,
     *     toggleButtonId:string,
     *     headingId:string
     * }>
     */
    private function normalizeCards(array $cards): array
    {
        $normalizedCards = [];

        foreach ($cards as $card) {
            if (!is_array($card)) {
                continue;
            }

            $parentId = absint($card['parent_page'] ?? 0);
            $parentPost = $parentId > 0 ? get_post($parentId) : null;

            if (!$parentPost instanceof WP_Post || $parentPost->post_type !== 'page' || $parentPost->post_status !== 'publish') {
                continue;
            }

            $parent = $this->normalizePost($parentPost);

            if ($parent === null) {
                continue;
            }

            $children = $this->getChildren($parentPost->ID);
            $normalizedChildren = array_values(array_filter(array_map([$this, 'normalizePost'], $children)));
            $visibleChildren = array_slice($normalizedChildren, 0, 3);
            $hiddenChildren = array_slice($normalizedChildren, 3);
            $totalChildren = count($normalizedChildren);
            $toggleId = !empty($hiddenChildren) ? wp_unique_id('navigation-card-children-') : '';
            $headingId = wp_unique_id('navigation-card-heading-');

            $normalizedCards[] = [
                'parent' => $parent,
                'visibleChildren' => $visibleChildren,
                'hiddenChildren' => $hiddenChildren,
                'totalChildren' => $totalChildren,
                'hasOverflow' => !empty($hiddenChildren),
                'toggleLabel' => sprintf(
                    __('Visa alla (%d)', 'modularity-navigation-card'),
                    $totalChildren,
                ),
                'toggleExpandedLabel' => __('Dölj', 'modularity-navigation-card'),
                'toggleId' => $toggleId,
                'toggleButtonId' => $toggleId !== '' ? $toggleId . '-button' : '',
                'headingId' => $headingId,
            ];
        }

        return $normalizedCards;
    }

    /**
     * @return array<int, WP_Post>
     */
    private function getChildren(int $parentId): array
    {
        $children = get_posts([
            'post_type' => 'page',
            'post_parent' => $parentId,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => [
                'menu_order' => 'ASC',
                'title' => 'ASC',
            ],
            'order' => 'ASC',
            'suppress_filters' => false,
        ]);

        return array_values(array_filter(
            $children,
            static fn ($post): bool => $post instanceof WP_Post && $post->post_status === 'publish',
        ));
    }

    /**
     * @return array{url:string,title:string}|null
     */
    private function normalizePost(WP_Post $post): ?array
    {
        $url = (string) get_permalink($post);
        $title = trim(wp_strip_all_tags(get_the_title($post)));

        if ($url === '' || $title === '') {
            return null;
        }

        return [
            'url' => $url,
            'title' => $title,
        ];
    }

    /**
     * @param mixed $columnCount
     */
    private function normalizeColumnCount($columnCount): int
    {
        $normalizedCount = absint($columnCount);

        if ($normalizedCount < 1 || $normalizedCount > 3) {
            return 3;
        }

        return $normalizedCount;
    }
}
