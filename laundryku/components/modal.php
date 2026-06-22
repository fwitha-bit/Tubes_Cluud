<!-- ======================================================
     MODAL.PHP — Add/Edit Transaction Modal Form
     ====================================================== -->
<div id="transaction-modal" class="modal-overlay">
    <div class="glass-card modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Tambah Transaksi Laundry</h2>
            <button class="modal-close" id="close-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="transaction-form">
            <div class="modal-body">
                <!-- Form Grid -->
                <div class="form-grid">
                    <div class="form-group">
                        <label for="customer_name">Nama Pelanggan</label>
                        <input type="text" id="customer_name" class="form-control" required placeholder="Masukkan nama...">
                    </div>

                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="tel" id="phone" class="form-control" required placeholder="Contoh: 08123456789">
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Alamat Lengkap</label>
                        <textarea id="address" class="form-control" required placeholder="Alamat penjemputan/pengantaran..."></textarea>
                    </div>

                    <!-- Pilihan Menu Layanan (Klik kartu, tanpa mengetik) -->
                    <div class="form-group full-width">
                        <label>Pilih Layanan Laundry</label>
                        <div class="services-selector">
                            <?php foreach ($services as $key => $service): ?>
                                <div class="service-option-card" data-service="<?php echo $key; ?>">
                                    <div class="service-option-icon">
                                        <i class="<?php echo $service['icon']; ?>"></i>
                                    </div>
                                    <div class="service-option-name"><?php echo $service['name']; ?></div>
                                    <div class="service-option-desc"><?php echo $service['desc']; ?></div>
                                    <div class="service-option-price">Rp <?php echo number_format($service['price'], 0, ',', '.'); ?> / kg</div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="delivery_method">Jenis Pengambilan</label>
                        <select id="delivery_method" class="form-control" required>
                            <option value="Ambil Di Tempat">Ambil Di Tempat</option>
                            <option value="Diantar">Diantar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="weight">Berat Cucian (kg)</label>
                        <input type="number" id="weight" class="form-control" step="0.1" min="0.1" required placeholder="Contoh: 2.5">
                    </div>

                    <div class="form-group">
                        <label for="date">Tanggal Transaksi</label>
                        <input type="date" id="date" class="form-control" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="status">Status Awal</label>
                        <select id="status" class="form-control" required style="background-color: #12131a;">
                            <option value="Proses">Proses</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Sudah Diambil">Sudah Diambil</option>
                        </select>
                    </div>
                </div>

                <!-- Hidden Cost field & visual Display panel -->
                <input type="hidden" id="total_cost" value="0">
                <div class="calc-summary-panel">
                    <span class="calc-label">Total Biaya (Otomatis):</span>
                    <span class="calc-value" id="calc-total-display">Rp 0</span>
                </div>

                <div class="save-instruction">
                    Pilih jenis pengambilan di atas, lalu tekan tombol simpan.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancel-modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="save-transaction-btn">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>
