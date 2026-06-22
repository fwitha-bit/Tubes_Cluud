/* ======================================================
   FORM.JS — Form Submission, Cost Calculation, Service Selection, CRUD Actions
   ====================================================== */

/**
 * Hitung total biaya berdasarkan berat cucian dan layanan terpilih
 */
function calculateCost() {
    const formWeight = document.getElementById('weight');
    const totalCostDisplay = document.getElementById('calc-total-display');
    const totalCostInput = document.getElementById('total_cost');

    const weight = parseFloat(formWeight.value) || 0;
    if (!window._selectedService || !SERVICES[window._selectedService]) {
        totalCostDisplay.textContent = formatIDR(0);
        totalCostInput.value = 0;
        return;
    }

    const pricePerKg = SERVICES[window._selectedService].price;
    const total = Math.round(weight * pricePerKg);
    totalCostDisplay.textContent = formatIDR(total);
    totalCostInput.value = total;
}

function getFinalStatusOption() {
    const deliveryMethod = document.getElementById('delivery_method')?.value || 'Ambil Di Tempat';
    return deliveryMethod === 'Diantar' ? 'Sudah Diantar' : 'Sudah Diambil';
}

function updateStatusSelectOptions() {
    const statusSelect = document.getElementById('status');
    if (!statusSelect) return;

    const currentValue = statusSelect.value;
    const finalStatusOption = getFinalStatusOption();
    statusSelect.innerHTML = `
        <option value="Proses" ${currentValue === 'Proses' ? 'selected' : ''}>Proses</option>
        <option value="Selesai" ${currentValue === 'Selesai' ? 'selected' : ''}>Selesai</option>
        <option value="${finalStatusOption}" ${currentValue === finalStatusOption ? 'selected' : ''}>${finalStatusOption}</option>
    `;
}

/**
 * Inisialisasi event listeners untuk form di dalam modal
 */
function initFormListeners() {
    const transactionForm = document.getElementById('transaction-form');
    const formWeight = document.getElementById('weight');
    const deliveryMethodInput = document.getElementById('delivery_method');
    const serviceCards = document.querySelectorAll('.service-option-card');

    // Kalkulasi biaya otomatis saat berat diubah
    formWeight.addEventListener('input', calculateCost);

    // Ubah opsi status ketika jenis pengambilan diganti
    if (deliveryMethodInput) {
        deliveryMethodInput.addEventListener('change', () => {
            updateStatusSelectOptions();
        });
    }

    // Seleksi layanan via klik kartu (tanpa mengetik)
    serviceCards.forEach(card => {
        card.addEventListener('click', () => {
            serviceCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            window._selectedService = card.dataset.service;
            calculateCost();
        });
    });

    // Handle form submit (Create / Update)
    transactionForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!window._selectedService) {
            showToast('Pilih salah satu jenis layanan laundry.', 'error');
            return;
        }

        const payload = {
            customer_name: document.getElementById('customer_name').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            address: document.getElementById('address').value.trim(),
            service_type: window._selectedService,
            delivery_method: document.getElementById('delivery_method').value,
            weight: parseFloat(document.getElementById('weight').value),
            total_cost: parseInt(document.getElementById('total_cost').value),
            date: document.getElementById('date').value,
            status: document.getElementById('status').value
        };

        const url = window._isEditing
            ? `api.php?action=update&id=${window._editId}`
            : 'api.php?action=create';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();

            if (result.success) {
                showToast(
                    window._isEditing
                        ? 'Data transaksi berhasil diperbarui.'
                        : 'Transaksi baru berhasil ditambahkan.',
                    'success'
                );
                closeModal();
                fetchTransactions();
            } else {
                showToast('Gagal menyimpan data: ' + result.error, 'error');
            }
        } catch (error) {
            console.error('Submit error:', error);
            showToast('Gagal terhubung ke server.', 'error');
        }
    });
}

/**
 * Update status transaksi via dropdown di dalam tabel
 */
async function updateTransactionStatus(id, newStatus) {
    try {
        const response = await fetch(`api.php?action=update_status&id=${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: newStatus })
        });
        const result = await response.json();

        if (result.success) {
            showToast(`Status transaksi berhasil diperbarui menjadi "${newStatus}".`, 'success');
            fetchTransactions();
        } else {
            showToast('Gagal memperbarui status: ' + result.error, 'error');
            fetchTransactions();
        }
    } catch (error) {
        console.error('Status update error:', error);
        showToast('Kesalahan koneksi saat memperbarui status.', 'error');
        fetchTransactions();
    }
}

/**
 * Hapus transaksi
 */
async function deleteTransaction(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data transaksi ini? Data di database Firebase akan terhapus secara permanen.')) {
        return;
    }

    try {
        const response = await fetch(`api.php?action=delete&id=${id}`, {
            method: 'POST'
        });
        const result = await response.json();

        if (result.success) {
            showToast('Transaksi berhasil dihapus.', 'success');
            fetchTransactions();
        } else {
            showToast('Gagal menghapus: ' + result.error, 'error');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showToast('Kesalahan koneksi saat menghapus transaksi.', 'error');
    }
}
