@if ($paginator->hasPages())
<nav class="pager">
    @if ($paginator->onFirstPage())
        <span class="btn btn-sm disabled">‹ Prev</span>
    @else
        <a class="btn btn-sm" href="{{ $paginator->previousPageUrl() }}">‹ Prev</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span>{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="btn btn-sm btn-primary">{{ $page }}</span>
                @else
                    <a class="btn btn-sm" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a class="btn btn-sm" href="{{ $paginator->nextPageUrl() }}">Next ›</a>
    @else
        <span class="btn btn-sm disabled">Next ›</span>
    @endif
</nav>
@endif