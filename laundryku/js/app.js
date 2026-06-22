/* ======================================================
   APP.JS — Main Application Initialization & Wiring
   ====================================================== */

document.addEventListener('DOMContentLoaded', () => {
    // ---- Global State ----
    window._transactions = [];
    window._currentFilter = 'all';
    window._searchQuery = '';
    window._selectedService = '';
    window._isEditing = false;
    window._editId = '';

    // ---- Bind Modal Open/Close Buttons ----
    document.getElementById('add-btn').addEventListener('click', () => openModal('create'));
    document.getElementById('close-modal').addEventListener('click', closeModal);
    document.getElementById('cancel-modal').addEventListener('click', closeModal);

    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

    // ---- Initialize Feature Modules ----
    initFilterListeners();   // filter.js
    initFormListeners();     // form.js

    // ---- Load Initial Data ----
    fetchTransactions();     // table.js
});
