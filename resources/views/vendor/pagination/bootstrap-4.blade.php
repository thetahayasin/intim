@php
    // In some Livewire 3 rendering contexts the presenter does not inject $elements.
    // Rebuild from UrlWindow if missing; skip pagination entirely if $paginator is
    // not a real paginator (e.g. an Eloquent Collection during component rehydration).
    if (!isset($elements)) {
        if (method_exists($paginator, 'elements')) {
            $elements = $paginator->elements();
        } elseif ($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            $window = \Illuminate\Pagination\UrlWindow::make($paginator);
            $elements = array_filter([
                $window['first'],
                is_array($window['slider']) ? '...' : null,
                $window['slider'],
                is_array($window['last']) ? '...' : null,
                $window['last'],
            ]);
        } else {
            $elements = [];
        }
    }
@endphp

@if ($paginator instanceof \Illuminate\Contracts\Pagination\Paginator && $paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
