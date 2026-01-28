{{-- Universal Pagination Controls --}}
@if ($paginator->hasPages())
    <div class="pagination-wrapper" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
        {{-- Pagination Controls --}}
        <div class="ajax-pagination" data-page-param="{{ $pageParam }}" style="display: flex; align-items: center; gap: 4px;">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" title="Halaman Sebelumnya">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                    </svg>
                    <span class="btn-text">Sebelumnya</span>
                </span>
            @else
                <button type="button" class="pagination-btn ajax-page-btn" data-page="{{ $paginator->currentPage() - 1 }}"
                    title="Halaman Sebelumnya">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                    </svg>
                    <span class="btn-text">Sebelumnya</span>
                </button>
            @endif

            {{-- Page Numbers --}}
            @php
                $start = max(1, $paginator->currentPage() - 2);
                $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
            @endphp

            @if ($start > 1)
                <button type="button" class="pagination-btn ajax-page-btn" data-page="1" title="Halaman 1">1</button>
                @if ($start > 2)
                    <span class="pagination-btn disabled">...</span>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <span class="pagination-btn active"
                        title="Halaman {{ $page }} (Aktif)">{{ $page }}</span>
                @else
                    <button type="button" class="pagination-btn ajax-page-btn" data-page="{{ $page }}"
                        title="Halaman {{ $page }}">{{ $page }}</button>
                @endif
            @endfor

            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <span class="pagination-btn disabled">...</span>
                @endif
                <button type="button" class="pagination-btn ajax-page-btn" data-page="{{ $paginator->lastPage() }}"
                    title="Halaman {{ $paginator->lastPage() }}">{{ $paginator->lastPage() }}</button>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <button type="button" class="pagination-btn ajax-page-btn" data-page="{{ $paginator->currentPage() + 1 }}"
                    title="Halaman Selanjutnya">
                    <span class="btn-text">Selanjutnya</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                    </svg>
                </button>
            @else
                <span class="pagination-btn disabled" title="Halaman Selanjutnya">
                    <span class="btn-text">Selanjutnya</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                    </svg>
                </span>
            @endif
        </div>

        {{-- Pagination Info --}}
        <div class="pagination-info" style="font-size: 14px; color: #666; white-space: nowrap;">
            Menampilkan <strong style="color: #003366;">{{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }}</strong>
            dari <strong style="color: #003366;">{{ $paginator->total() }}</strong> {{ $pageParam == 'tahun_page' ? 'tahun' : 'data' }}
        </div>
    </div>
@endif
