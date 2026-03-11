@if (!empty($cards))
    <div class="navigation-card-grid {{ $gridClass }}">
        @foreach ($cards as $card)
            <div class="navigation-card" data-js-navigation-card>
                <div class="navigation-card__body">
                    <a class="navigation-card__parent" href="{{ $card['parent']['url'] }}">
                        <h2 id="{{ $card['headingId'] }}" class="navigation-card__parent-title">{{ $card['parent']['title'] }}</h2>
                        <span
                            class="c-icon material-symbols navigation-card__parent-icon"
                            data-material-symbol=":höger:"
                            aria-hidden="true"
                        ></span>
                    </a>

                    @if (!empty($card['visibleChildren']))
                        <ul class="navigation-card__list" aria-labelledby="{{ $card['headingId'] }}">
                            @foreach ($card['visibleChildren'] as $child)
                                <li class="navigation-card__item">
                                    <a class="navigation-card__link" href="{{ $child['url'] }}">
                                        <span class="navigation-card__link-title">{{ $child['title'] }}</span>
                                        <span
                                            class="c-icon material-symbols navigation-card__link-icon"
                                            data-material-symbol=":höger:"
                                            aria-hidden="true"
                                        ></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if ($card['hasOverflow'] && !empty($card['toggleId']))
                    <div class="navigation-card__overflow">
                        <button
                            id="{{ $card['toggleButtonId'] }}"
                            class="navigation-card__toggle"
                            type="button"
                            aria-label="{{ $card['toggleAccessibleLabel'] }}"
                            aria-expanded="false"
                            aria-controls="{{ $card['toggleId'] }}"
                            data-js-navigation-card-toggle
                        >
                            <span class="navigation-card__toggle-label">{{ $card['toggleLabel'] }}</span>
                            <span
                                class="c-icon material-symbols navigation-card__toggle-icon"
                                data-material-symbol="keyboard_arrow_down"
                                aria-hidden="true"
                            ></span>
                        </button>

                        <div
                            id="{{ $card['toggleId'] }}"
                            class="navigation-card__hidden"
                            role="region"
                            aria-label="{{ $card['toggleAccessibleLabel'] }}"
                            data-js-navigation-card-panel
                            hidden
                        >
                            <ul
                                class="navigation-card__list navigation-card__list--hidden"
                                aria-label="{{ $card['toggleAccessibleLabel'] }}"
                            >
                                @foreach ($card['hiddenChildren'] as $child)
                                    <li class="navigation-card__item">
                                        <a class="navigation-card__link" href="{{ $child['url'] }}">
                                            <span class="navigation-card__link-title">{{ $child['title'] }}</span>
                                            <span
                                                class="c-icon material-symbols navigation-card__link-icon"
                                                data-material-symbol=":höger:"
                                                aria-hidden="true"
                                            ></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
