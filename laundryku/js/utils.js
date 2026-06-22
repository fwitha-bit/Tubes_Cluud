/* ======================================================
   UTILS.JS — Helper/Utility Functions
   ====================================================== */

/**
 * Format angka ke mata uang IDR
 */
function formatIDR(value) {
    return 'Rp ' + parseInt(value || 0).toLocaleString('id-ID');
}

/**
 * Escape HTML untuk mencegah XSS
 */
function escapeHTML(str) {
    if (!str) return '';
    return str.replace(/[&<>'"]/g,
        tag => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[tag] || tag)
    );
}

/**
 * Format tanggal dari YYYY-MM-DD ke format lokal Indonesia
 */
function formatDate(dateStr) {
    if (!dateStr) return '-';
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    try {
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            const year = parts[0];
            const month = months[parseInt(parts[1]) - 1];
            const day = parts[2];
            return `${day} ${month} ${year}`;
        }
    } catch (e) { /* fallback */ }
    return dateStr;
}
