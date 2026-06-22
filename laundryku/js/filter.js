/* ======================================================
   FILTER.JS — Search & Status Filter Logic
   ====================================================== */

/**
 * Terapkan filter status dan pencarian ke data transaksi
 */
function applyFilterAndSearch() {
    let filtered = [...window._transactions];

    // Filter berdasarkan status
    if (window._currentFilter !== 'all') {
        filtered = filtered.filter(t => t.status === window._currentFilter);
    }

    // Filter berdasarkan kata kunci pencarian (nama, telepon, alamat, layanan)
    if (window._searchQuery) {
        const query = window._searchQuery.toLowerCase();
        filtered = filtered.filter(t =>
            (t.customer_name || '').toLowerCase().includes(query) ||
            (t.phone || '').toLowerCase().includes(query) ||
            (t.address || '').toLowerCase().includes(query) ||
            (SERVICES[t.service_type] ? SERVICES[t.service_type].name : t.service_type).toLowerCase().includes(query)
        );
    }

    renderTable(filtered);
}

/**
 * Inisialisasi event listener untuk search box dan tombol filter
 */
function initFilterListeners() {
    const searchInput = document.getElementById('search-input');
    const filterButtons = document.querySelectorAll('.filter-btn');

    searchInput.addEventListener('input', (e) => {
        window._searchQuery = e.target.value;
        applyFilterAndSearch();
    });

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            window._currentFilter = btn.dataset.filter;
            applyFilterAndSearch();
        });
    });
}
