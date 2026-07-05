@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="user-pagination-nav">
        <div class="user-pagination-info">
            <p>
                Showing
                @if ($paginator->firstItem())
                    <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                of
                <span class="font-semibold">{{ $paginator->total() }}</span>
                results
            </p>
        </div>

        <ul class="user-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="user-page-item disabled">
                    <span class="user-page-link" aria-disabled="true">
                        <i class='bx bx-chevron-left'></i>
                    </span>
                </li>
            @else
                <li class="user-page-item">
                    <a class="user-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class='bx bx-chevron-left'></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="user-page-item disabled">
                        <span class="user-page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="user-page-item active">
                                <span class="user-page-link" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li class="user-page-item">
                                <a class="user-page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="user-page-item">
                    <a class="user-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class='bx bx-chevron-right'></i>
                    </a>
                </li>
            @else
                <li class="user-page-item disabled">
                    <span class="user-page-link" aria-disabled="true">
                        <i class='bx bx-chevron-right'></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
