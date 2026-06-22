<!-- ======================================================
     TABLE.PHP — Transaction Data Table
     ====================================================== -->
<main class="glass-card table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Alamat</th>
                    <th>Layanan</th>
                    <th>Jenis</th>
                    <th>Berat (kg)</th>
                    <th>Total Biaya</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    </th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-state" class="table-loading">
        <div class="spinner"></div>
        <p>Menghubungkan ke Firebase Database...</p>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="empty-state" style="display: none;">
        <i class="fas fa-folder-open"></i>
        <h3>Tidak Ada Data Transaksi</h3>
        <p>Belum ada transaksi laundry yang terdaftar atau pencarian Anda tidak ditemukan.</p>
    </div>
</main>
