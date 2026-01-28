{{-- resources/views/admin/partials/distribusi-grid-table.blade.php --}}
<table class="report-table">
    <thead>
        <tr>
            <th style="width: 60px;">No</th>
            <th>Nomor Grid</th>
            <th style="text-align: center;">Jumlah Survei</th>
            <th style="text-align: center;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($distribusiGrid as $index => $grid)
            <tr>
                <td style="font-weight: 600; color: #003366;">
                    {{ ($distribusiGrid->currentPage() - 1) * $distribusiGrid->perPage() + $index + 1 }}
                </td>
                <td>
                    <strong style="color: #003366;">Grid {{ $grid->nomor_kotak }}</strong>
                </td>
                <td style="text-align: center;">
                    <span style="
                        display: inline-block;
                        padding: 4px 12px;
                        border-radius: 20px;
                        font-size: 13px;
                        font-weight: 600;
                        {{ $grid->data_survei_count > 0 
                            ? 'background: #d4edda; color: #155724;' 
                            : 'background: #e9ecef; color: #6c757d;' 
                        }}
                    ">
                        {{ $grid->data_survei_count }} Survei
                    </span>
                </td>
                <td style="text-align: center;">
                    @if($grid->data_survei_count > 0)
                        <span style="color: #28a745; font-weight: 500;">
                            <i class="fas fa-check-circle"></i> Terisi
                        </span>
                    @else
                        <span style="color: #999;">
                            <i class="fas fa-minus-circle"></i> Kosong
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #999; padding: 40px;">
                    Tidak ada data grid
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
