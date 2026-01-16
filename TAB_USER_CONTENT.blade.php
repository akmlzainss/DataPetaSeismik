{{-- TAB 3: USER (PEGAWAI INTERNAL) - TABLE WITH CRUD --}}
<div id="user" class="settings-tab-content">
    {{-- Pegawai Internal Table --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-title">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
                Daftar Akun Pegawai Internal
            </div>
            <div class="settings-card-subtitle">Manage akun pegawai internal ESDM yang terdaftar di sistem ({{ $pegawaiInternal->total() }} total)</div>
        </div>
        <div class="settings-card-body">
            @if ($pegawaiInternal->count() > 0)
                {{-- Table Responsive Wrapper --}}
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #003366;">
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">#</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">Nama</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">Email</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">NIP</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #003366;">Jabatan</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; color: #003366;">Status</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; color: #003366;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pegawaiInternal as $index => $pegawai)
                                <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                                    <td style="padding: 12px; color: #666;">{{ $pegawaiInternal->firstItem() + $index }}</td>
                                    <td style="padding: 12px;">
                                        <strong style="color: #003366;">{{ $pegawai->nama }}</strong>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span style="color: #666; font-size: 14px;">
                                            <i class="fas fa-envelope" style="margin-right: 5px; color: #003366;"></i>
                                            {{ $pegawai->email }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; color: #666;">{{ $pegawai->nip ?? '-' }}</td>
                                    <td style="padding: 12px; color: #666;">{{ $pegawai->jabatan ?? '-' }}</td>
                                    <td style="padding: 12px; text-align: center;">
                                        @if ($pegawai->is_approved)
                                            <span style="background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-check-circle"></i> Approved
                                            </span>
                                        @else
                                            <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <button onclick="openEditModal({{ $pegawai->id }}, '{{ addslashes($pegawai->nama) }}', '{{ $pegawai->email }}', '{{ $pegawai->nip }}', '{{ $pegawai->jabatan }}')" 
                                                style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-right: 5px; font-size: 13px;" 
                                                title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button onclick="confirmDeletePegawai({{ $pegawai->id }}, '{{ addslashes($pegawai->nama) }}')" 
                                                style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 13px;" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pagination-wrapper" style="margin-top: 20px;">
                    {{ $pegawaiInternal->links() }}
                    @if ($pegawaiInternal->hasPages())
                        <div class="pagination-info" style="text-align: center; color: #666; margin-top: 10px; font-size: 14px;">
                            Menampilkan {{ $pegawaiInternal->firstItem() }} - {{ $pegawaiInternal->lastItem() }} dari {{ $pegawaiInternal->total() }} pegawai
                        </div>
                    @endif
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 8px;">
                    <i class="fas fa-users" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                    <h3 style="color: #666; margin-bottom: 10px;">Belum Ada Pegawai Terdaftar</h3>
                    <p style="color: #999;">Pegawai akan muncul di sini setelah mereka mendaftar di sistem.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL EDIT PEGAWAI --}}
<div id="editPegawaiModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div class="modal-container" style="background: white; width: 90%; max-width: 500px; border-radius: 12px; padding: 30px; position: relative;">
        <button onclick="closeEditModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">×</button>
        
        <h3 style="color: #003366; margin-bottom: 20px; font-size: 20px;">
            <i class="fas fa-user-edit"></i> Edit Data Pegawai
        </h3>
        
        <form id="editPegawaiForm" method="POST" action="">
            @csrf
            @method('PUT')
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" name="nama" id="edit_nama" required class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Email ESDM <span style="color: red;">*</span></label>
                <input type="email" name="email" id="edit_email" required class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">NIP</label>
                <input type="text" name="nip" id="edit_nip" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Jabatan</label>
                <input type="text" name="jabatan" id="edit_jabatan" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeEditModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Batal
                </button>
                <button type="submit" style="background: #003366; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DELETE CONFIRMATION --}}
<div id="deletePegawaiModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div class="modal-container" style="background: white; width: 90%; max-width: 400px; border-radius: 12px; padding: 30px; text-align: center;">
        <div style="width: 60px; height: 60px; background: #fee; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-exclamation-triangle" style="font-size: 30px; color: #dc3545;"></i>
        </div>
        
        <h3 style="color: #003366; margin-bottom: 10px; font-size: 18px;">Konfirmasi Hapus</h3>
        <p style="color: #666; margin-bottom: 20px;">
            Apakah Anda yakin ingin menghapus pegawai <strong id="deletePegawaiName"></strong>?<br>
            Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>.
        </p>
        
        <form id="deletePegawaiForm" method="POST" action="">
            @csrf
            @method('DELETE')
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" onclick="closeDeleteModal()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Batal
                </button>
                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-trash"></i> Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript for Modal --}}
<script>
function openEditModal(id, nama, email, nip, jabatan) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_nip').value = nip || '';
    document.getElementById('edit_jabatan').value = jabatan || '';
    
    // Set form action
    document.getElementById('editPegawaiForm').action = `/bbspgl-admin/pengaturan/pegawai/${id}`;
    
    // Show modal
    document.getElementById('editPegawaiModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editPegawaiModal').style.display = 'none';
}

function confirmDeletePegawai(id, nama) {
    document.getElementById('deletePegawaiName').textContent = nama;
    
    // Set form action
    document.getElementById('deletePegawaiForm').action = `/bbspgl-admin/pengaturan/pegawai/${id}`;
    
    // Show modal
    document.getElementById('deletePegawaiModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deletePegawaiModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('editPegawaiModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.getElementById('deletePegawaiModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
