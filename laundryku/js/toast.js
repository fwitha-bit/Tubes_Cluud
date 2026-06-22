/* ======================================================
   TOAST.JS — Toast Notification System
   ====================================================== */

/**
 * Menampilkan toast notification pop-up
 * @param {string} message - Pesan yang ditampilkan
 * @param {string} type - Tipe: 'info' | 'success' | 'error'
 */
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    let icon = 'fa-info-circle';
    if (type === 'success') icon = 'fa-check-circle';
    if (type === 'error') icon = 'fa-exclamation-circle';

    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span class="toast-message">${message}</span>
    `;

    container.appendChild(toast);

    // Trigger reflow to apply CSS animation
    toast.offsetHeight;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 400);
    }, 3000);
}
