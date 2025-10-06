@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4 space-x-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-gray-400 bg-gray-100 border border-gray-300 rounded cursor-not-allowed">‹</span>
        @else
            <button wire:click="previousPage" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-indigo-100">‹</button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1 text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 text-white bg-indigo-600 border border-indigo-600 rounded font-semibold">
                            {{ $page }}
                        </span>
                    @else
                        <button wire:click="gotoPage({{ $page }})"
                            class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-indigo-100">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-indigo-100">›</button>
        @else
            <span class="px-3 py-1 text-gray-400 bg-gray-100 border border-gray-300 rounded cursor-not-allowed">›</span>
        @endif
    </nav>
@endif

