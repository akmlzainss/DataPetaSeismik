@extends('layouts.admin')
@section('title', 'Manajemen Pegawai ESDM - Admin BBSPGL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data-survei.css') }}">
    <style>
        .approval-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .approval-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .approval-table th {
            background: #003366;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .approval-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .approval-table tr:last-child td {
            border-bottom: none;
        }

        .approval-table tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-verified {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s;
        }

        .btn-approve {
            background: #28a745;
            color: white;
        }

        .btn-approve:hover {
            background: #218838;
        }

        .btn-reject {
            background: #dc3545;
            color: white;
        }

        .btn-reject:hover {
            background: #c82333;
        }

        .btn-revoke {
            background: #ffc107;
            color: #000;
        }

        .btn-revoke:hover {
            background: #e0a800;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Manajemen Pegawai ESDM</h1>
        <p>Approval dan manajemen akun Pegawai ESDM ESDM</p>
    </div>

    <div class="welcome-section">
        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert-success-form">✓ {{ session('success') }}</div>
        @endif

        @if (session('warning'))
            <div class="alert-warning" style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
                ⚠️ {{ session('warning') }}
            </div>
        @endif

        @if (session('info'))
            <div class="alert-info" style="background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #17a2b8;">
                ℹ️ {{ session('info') }}
            </div>
        @endif

        {{-- Pending Approval Section --}}
        <div style="margin-bottom: 40px;">
            <h2 style="color: #003366; margin-bottom: 20px;">
                <i class="fas fa-clock"></i> Menunggu Approval
                <span style="background: #ffc107; color: #000; padding: 4px 12px; border-radius: 20px; font-size: 14px; margin-left: 10px;">
                    {{ $pendingPegawai->total() }}
                </span>
            </h2>

            @if ($pendingPegawai->count() > 0)
                <div class="approval-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email ESDM</th>
                                <th>NIP</th>
                                <th>Jabatan</th>
                                <th>Terdaftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingPegawai as $pegawai)
                                <tr>
                                    <td><strong>{{ $pegawai->nama }}</strong></td>
                                    <td><i class="fas fa-envelope"></i> {{ $pegawai->email }}</td>
                                    <td>{{ $pegawai->nip ?? '-' }}</td>
                                    <td>{{ $pegawai->jabatan ?? '-' }}</td>
                                    <td>{{ $pegawai->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if ($pegawai->email_verified_at)
                                            <span class="status-badge status-verified">
                                                <i class="fas fa-check-circle"></i> Email Verified
                                            </span>
                                        @else
                                            <span class="status-badge status-pending">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.pegawai.approve', $pegawai->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn btn-approve"
                                                onclick="return confirm('Approve pegawai {{ $pegawai->nama }}?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pegawai.reject', $pegawai->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn btn-reject"
                                                onclick="return confirm('Tolak dan hapus pendaftaran {{ $pegawai->nama }}?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $pendingPegawai->links() }}
                </div>
            @else
                <div style="background: #e7f3ff; padding: 40px; text-align: center; border-radius: 12px; border: 2px dashed #003366;">
                    <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
                    <p style="font-size: 18px; color: #666;">Tidak ada pegawai yang menunggu approval</p>
                </div>
            @endif
        </div>

        {{-- Approved Pegawai Section --}}
        <div>
            <h2 style="color: #003366; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Pegawai Ter-Approve
                <span style="background: #28a745; color: white; padding: 4px 12px; border-radius: 20px; font-size: 14px; margin-left: 10px;">
                    {{ $approvedPegawai->total() }}
                </span>
            </h2>

            @if ($approvedPegawai->count() > 0)
                <div class="approval-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email ESDM</th>
                                <th>NIP</th>
                                <th>Jabatan</th>
                                <th>Di-approve</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($approvedPegawai as $pegawai)
                                <tr>
                                    <td><strong>{{ $pegawai->nama }}</strong></td>
                                    <td><i class="fas fa-envelope"></i> {{ $pegawai->email }}</td>
                                    <td>{{ $pegawai->nip ?? '-' }}</td>
                                    <td>{{ $pegawai->jabatan ?? '-' }}</td>
                                    <td>
                                        @if ($pegawai->approved_at)
                                            {{ $pegawai->approved_at->diffForHumans() }}
                                            @if ($pegawai->approver)
                                                <br><small style="color: #666;">oleh {{ $pegawai->approver->nama }}</small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-approved">
                                            <i class="fas fa-check-double"></i> Approved
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.pegawai.revoke', $pegawai->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn btn-revoke"
                                                onclick="return confirm('Cabut approval untuk {{ $pegawai->nama }}? User tidak akan bisa login sampai di-approve lagi.')">
                                                <i class="fas fa-ban"></i> Revoke
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $approvedPegawai->links() }}
                </div>
            @else
                <div style="background: #f8f9fa; padding: 40px; text-align: center; border-radius: 12px; border: 2px dashed #ccc;">
                    <i class="fas fa-users" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                    <p style="font-size: 18px; color: #999;">Belum ada pegawai yang di-approve</p>
                </div>
            @endif
        </div>
    </div>
@endsection

