// assets/js/admin.js
// Tansy Barbershop - Admin JavaScript

// Escape karakter HTML sebelum data pengguna ditampilkan lewat SweetAlert2 (title/html dirender sebagai HTML).
function escapeHtml(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, c => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[c]));
}

$(document).ready(function() {

    // Sidebar toggle.
    // Layar kecil (<= 768px): sidebar disembunyikan secara bawaan dan dibuka dengan class .open.
    // Layar besar: sidebar tampil dan bisa disembunyikan dengan class .collapsed.
    const layarKecil = () => window.matchMedia('(max-width: 768px)').matches;

    $('#sidebarToggle').on('click', function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('adminContent');
        if (!sidebar) return;
        if (layarKecil()) {
            sidebar.classList.toggle('open');
        } else {
            sidebar.classList.toggle('collapsed');
            if (content) content.classList.toggle('expanded');
        }
    });

    // Layar kecil: tutup sidebar saat area di luar sidebar disentuh.
    $(document).on('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && layarKecil() && sidebar.classList.contains('open')
            && !$(e.target).closest('#sidebar, #sidebarToggle').length) {
            sidebar.classList.remove('open');
        }
    });

    // Inisialisasi DataTables untuk semua tabel dengan class .dt-table
    if ($.fn.DataTable) {
        $('.dt-table').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "‹",
                    next: "›"
                },
                zeroRecords: "Tidak ada data ditemukan",
                emptyTable: "Tidak ada data tersedia"
            },
            pageLength: 10,
            responsive: true
        });
    }

    // Konfirmasi hapus global
    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        const nama = $(this).data('nama') || 'data ini';
        
        Swal.fire({
            title: 'Hapus ' + nama + '?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

});

// Fungsi AJAX request helper
function ajaxRequest(url, data, successCallback) {
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            successCallback(response);
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan koneksi. Coba lagi.',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
}

// Format rupiah di client side
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}
