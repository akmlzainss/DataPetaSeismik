{{-- Partial view untuk tabel survei terbaru dengan pagination --}}
<table class="report-table">
    <thead>
        <tr>
            <th>Tanggal Upload</th>
            <th>Judul Survei</th>
            <th>Tipe</th>
            <th>Wilayah</th>
            <th style="text-align: center;">Marker</th>
            <th>Diunggah Oleh</th>
        </tr>
    </thead>
    <tbody>
        @forelse($surveiTerbaru as $survei)
            <tr>
                <td style="white-space: nowrap;">{{ $survei->created_at->format('d/m/Y H:i') }}</td>
                <td><strong>{{ Str::limit($survei->judul, 40) }}</strong></td>
                <td>
                    <span
                        class="badge-report {{ strtolower($survei->tipe) == '2d' ? 'blue' : (strtolower($survei->tipe) == '3d' ? 'purple' : (strtolower($survei->tipe) == 'hr' ? 'orange' : 'teal')) }}">
                        {{ $survei->tipe }}
                    </span>
                </td>
                <td>{{ Str::limit($survei->wilayah, 30) }}</td>
                <td style="text-align: center;">
                    @if ($survei->lokasi)
                        <span style="color: #28a745; font-weight: 600;">✓ Ada</span>
                    @else
                        <span style="color: #999;">— Belum</span>
                    @endif
                </td>
                <td>{{ $survei->pengunggah->nama ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #999; padding: 40px;">
                    Tidak ada data survei untuk filter yang dipilih
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
