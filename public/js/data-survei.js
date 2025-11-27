// public/js/data-survei.js — VERSI FINAL & PASTI JALAN
document.addEventListener('DOMContentLoaded', function () {

    // TOMBOL SIMPAN (baik .btn-submit atau .btn-tambah-data)
    document.querySelectorAll('.btn-submit, .btn-tambah-data').forEach(btn => {
        if (btn.tagName === 'BUTTON' && btn.type === 'submit') {
            btn.addEventListener('click', () => showLoading('Menyimpan data...'));
        }
    });

    // Backup submit event
    document.querySelectorAll('form').forEach(form => {
        if (form.classList.contains('no-loading')) return;
        if (form.querySelector('input[name="_method"][value="DELETE"]')) return;
        form.addEventListener('submit', () => showLoading('Menyimpan data...'));
    });

    // MODAL HAPUS
    let deleteForm = null;
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            deleteForm = btn.closest('form');
            showDeleteModal();
        });
    });

    window.confirmDelete = () => {
        hideDeleteModal();
        showLoading('Menghapus data...');
        deleteForm?.submit();
    };

    window.cancelDelete = () => {
        hideDeleteModal();
        deleteForm = null;
    };

    document.getElementById('deleteModal')?.addEventListener('click', e => {
        if (e.target === e.currentTarget) cancelDelete();
    });
});

function showLoading(text = 'Memproses...') {
    const o = document.getElementById('loadingOverlay');
    const t = document.getElementById('loadingText');
    if (o && t) { t.textContent = text; o.classList.add('active'); }
}
function hideLoading() { document.getElementById('loadingOverlay')?.classList.remove('active'); }
function showDeleteModal() { document.getElementById('deleteModal')?.classList.add('active'); }
function hideDeleteModal() { document.getElementById('deleteModal')?.classList.remove('active'); }



// Tambahkan ini di akhir file public/js/data-survei.js

// Upload dengan Progress Bar (hanya di create & edit)
if (document.getElementById('uploadForm')) {
    const form = document.getElementById('uploadForm');
    const fileInput = document.getElementById('gambarInput');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingText = document.getElementById('loadingText');

    form.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        if (file && file.size > 550 * 1024 * 1024) { // > 550MB
            alert('File terlalu besar! Maksimal 500MB.');
            e.preventDefault();
            return;
        }

        if (file) {
            e.preventDefault();
            showLoading('Mengunggah gambar besar...');

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressContainer.style.display = 'block';
                    progressFill.style.width = percent + '%';
                    progressFill.textContent = percent + '%';
                    progressText.textContent = `Mengunggah... ${percent}%`;
                }
            });

            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    document.body.innerHTML = xhr.response;
                } else {
                    alert('Upload gagal. Coba lagi.');
                    hideLoading();
                }
            });

            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        }
    });
}