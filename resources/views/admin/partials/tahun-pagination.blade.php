{{-- Partial view untuk tabel tahun dengan pagination --}}
<table class="simple-table">
    <thead>
        <tr>
            <th>Tahun</th>
            <th style="text-align: center;">2D</th>
            <th style="text-align: center;">3D</th>
            <th style="text-align: center;">HR</th>
            <th style="text-align: center;">Lainnya</th>
            <th style="text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($surveiPerTahun as $tahun)
            <tr>
                <td><strong>{{ $tahun->tahun }}</strong></td>
                <td style="text-align: center;">{{ number_format($tahun->tipe_2d ?? 0) }}</td>
                <td style="text-align: center;">{{ number_format($tahun->tipe_3d ?? 0) }}</td>
                <td style="text-align: center;">{{ number_format($tahun->tipe_hr ?? 0) }}</td>
                <td style="text-align: center;">{{ number_format($tahun->tipe_lainnya ?? 0) }}</td>
                <td style="text-align: right; font-weight: 700; color: #003366;">
                    {{ number_format($tahun->total) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #999; padding: 30px;">Belum ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
