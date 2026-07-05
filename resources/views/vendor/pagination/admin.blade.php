@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="admin-pagination-nav">
        <div class="admin-pagination-info">
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

        <ul class="admin-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="admin-page-item disabled">
                    <span class="admin-page-link" aria-disabled="true">
                        <i class='bx bx-chevron-left'></i>
                    </span>
                </li>
            @else
                <li class="admin-page-item">
                    <a class="admin-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class='bx bx-chevron-left'></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="admin-page-item disabled">
                        <span class="admin-page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="admin-page-item active">
                                <span class="admin-page-link" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li class="admin-page-item">
                                <a class="admin-page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="admin-page-item">
                    <a class="admin-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class='bx bx-chevron-right'></i>
                    </a>
                </li>
            @else
                <li class="admin-page-item disabled">
                    <span class="admin-page-link" aria-disabled="true">
                        <i class='bx bx-chevron-right'></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
