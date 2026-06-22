/* ======================================================
   TABLE.JS — Fetch Data, Render Table, Update Stats
   ====================================================== */

/**
 * Ambil semua transaksi dari Firebase via API
 */
async function fetchTransactions() {
    const tableBody = document.getElementById('table-body');
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');

    tableBody.style.display = 'none';
    loadingState.style.display = 'flex';
    emptyState.style.display = 'none';

    try {
        const response = await fetch('api.php?action=read');
        const result = await response.json();

        if (result.success) {
            window._transactions = result.data || [];
            updateStats();
            applyFilterAndSearch();
        } else {
            showToast('Gagal memuat data: ' + result.error, 'error');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        showToast('Kesalahan koneksi ke server.', 'error');
    } finally {
        loadingState.style.display = 'none';
    }
}

/**
 * Perbarui panel statistik dashboard
 */
function updateStats() {
    const transactions = window._transactions;
    const total = transactions.length;
    const proses = transactions.filter(t => t.status === 'Proses').length;
    const selesai = transactions.filter(t => t.status === 'Selesai').length;

    const revenue = transactions
        .filter(t => t.status === 'Sudah Diambil' || t.status === 'Sudah Diantar')
        .reduce((sum, t) => sum + (parseInt(t.total_cost) || 0), 0);

    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-proses').textContent = proses;
    document.getElementById('stat-selesai').textContent = selesai;
    document.getElementById('stat-revenue').textContent = formatIDR(revenue);
}

/**
 * Render baris-baris tabel transaksi
 */
function renderTable(data) {
    const tableBody = document.getElementById('table-body');
    const emptyState = document.getElementById('empty-state');
    tableBody.innerHTML = '';

    if (data.length === 0) {
        tableBody.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    emptyState.style.display = 'none';
    tableBody.style.display = 'table-row-group';

    data.forEach(t => {
        const tr = document.createElement('tr');

        const serviceKey = t.service_type;
        const serviceName = SERVICES[serviceKey] ? SERVICES[serviceKey].name : serviceKey;
        const serviceIcon = SERVICES[serviceKey] ? SERVICES[serviceKey].icon : 'fas fa-box';
        const deliveryType = t.delivery_method || 'Ambil Di Tempat';
        const finalStatusOption = deliveryType === 'Diantar' ? 'Sudah Diantar' : 'Sudah Diambil';

        let statusClass = 'proses';
        if (t.status === 'Selesai') statusClass = 'selesai';
        if (t.status === 'Sudah Diambil' || t.status === 'Sudah Diantar') statusClass = 'diambil';

        tr.innerHTML = `
            <td>
                <div class="customer-cell">
                    <span class="customer-name">${escapeHTML(t.customer_name)}</span>
                    <span class="customer-sub">${escapeHTML(t.phone)}</span>
                </div>
            </td>
            <td>
                <span class="customer-sub" title="${escapeHTML(t.address)}">
                    ${escapeHTML(t.address.length > 25 ? t.address.substring(0, 25) + '...' : t.address)}
                </span>
            </td>
            <td>
                <div class="service-badge">
                    <i class="${serviceIcon}"></i>
                    <span>${serviceName}</span>
                </div>
            </td>
            <td><strong>${escapeHTML(deliveryType)}</strong></td>
            <td><strong>${t.weight} kg</strong></td>
            <td><strong style="color: var(--accent-blue)">${formatIDR(t.total_cost)}</strong></td>
            <td>${formatDate(t.date)}</td>
            <td>
                <span class="status-badge ${statusClass}">${t.status}</span>
            </td>
            <td>
                <div class="action-cell">
                    <div class="status-select-wrapper">
                        <select class="status-select" data-id="${t.id}">
                            <option value="Proses" ${t.status === 'Proses' ? 'selected' : ''}>Proses</option>
                            <option value="Selesai" ${t.status === 'Selesai' ? 'selected' : ''}>Selesai</option>
                            <option value="${finalStatusOption}" ${t.status === finalStatusOption ? 'selected' : ''}>${finalStatusOption}</option>
                        </select>
                    </div>
                    <button class="btn btn-secondary btn-icon edit-action-btn" data-id="${t.id}" title="Edit Data">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-icon delete-action-btn" data-id="${t.id}" title="Hapus">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        `;

        // Bind status change
        tr.querySelector('.status-select').addEventListener('change', (e) => {
            updateTransactionStatus(t.id, e.target.value);
        });

        // Bind edit button
        tr.querySelector('.edit-action-btn').addEventListener('click', () => {
            openModal('edit', t);
        });

        // Bind delete button
        tr.querySelector('.delete-action-btn').addEventListener('click', () => {
            deleteTransaction(t.id);
        });

        tableBody.appendChild(tr);
    });
}
