(() => {
    const CARD_SELECTOR = '[data-js-navigation-card]';
    const TOGGLE_SELECTOR = '[data-js-navigation-card-toggle]';
    const PANEL_SELECTOR = '[data-js-navigation-card-panel]';
    const INITIALIZED_ATTRIBUTE = 'data-navigation-card-initialized';
    const PANEL_OPEN_CLASS = 'is-open';

    const syncButtonLabel = (button, isExpanded) => {
        const label = button.querySelector('.navigation-card__toggle-label');

        if (!(label instanceof HTMLElement)) {
            return;
        }

        const collapsedLabel = button.dataset.navigationCardCollapsedLabel ?? label.textContent?.trim() ?? '';
        const expandedLabel = button.dataset.navigationCardExpandedLabel ?? collapsedLabel;
        const collapsedAriaLabel = button.dataset.navigationCardCollapsedAriaLabel ?? button.getAttribute('aria-label') ?? '';
        const expandedAriaLabel = button.dataset.navigationCardExpandedAriaLabel ?? collapsedAriaLabel;

        label.textContent = isExpanded ? expandedLabel : collapsedLabel;
        button.setAttribute('aria-label', isExpanded ? expandedAriaLabel : collapsedAriaLabel);
    };

    const openPanel = (button, panel) => {
        requestAnimationFrame(() => {
            panel.classList.add(PANEL_OPEN_CLASS);
        });
        panel.removeAttribute('aria-hidden');
        panel.inert = false;
        button.setAttribute('aria-expanded', 'true');
        syncButtonLabel(button, true);
    };

    const closePanel = (button, panel) => {
        panel.classList.remove(PANEL_OPEN_CLASS);
        panel.setAttribute('aria-hidden', 'true');
        panel.inert = true;
        button.setAttribute('aria-expanded', 'false');
        syncButtonLabel(button, false);
    };

    const initCard = (card) => {
        if (!(card instanceof HTMLElement) || card.hasAttribute(INITIALIZED_ATTRIBUTE)) {
            return;
        }

        const button = card.querySelector(TOGGLE_SELECTOR);
        const panel = card.querySelector(PANEL_SELECTOR);

        if (!(button instanceof HTMLButtonElement) || !(panel instanceof HTMLElement)) {
            return;
        }

        card.setAttribute(INITIALIZED_ATTRIBUTE, 'true');

        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        panel.hidden = false;
        panel.classList.toggle(PANEL_OPEN_CLASS, isExpanded);
        panel.inert = !isExpanded;
        syncButtonLabel(button, isExpanded);

        if (isExpanded) {
            panel.removeAttribute('aria-hidden');
        } else {
            panel.setAttribute('aria-hidden', 'true');
        }

        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';

            if (expanded) {
                closePanel(button, panel);
                return;
            }

            openPanel(button, panel);
        });
    };

    const init = () => {
        if (typeof document === 'undefined') {
            return;
        }

        document.querySelectorAll(CARD_SELECTOR).forEach((card) => {
            initCard(card);
        });
    };

    document.addEventListener('DOMContentLoaded', init);
})();
