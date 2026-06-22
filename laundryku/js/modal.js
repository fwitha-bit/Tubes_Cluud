/* ======================================================
   MODAL.JS — Modal Open/Close & Form Population
   ====================================================== */

/**
 * Buka modal Tambah/Edit Transaksi
 * @param {string} mode - 'create' atau 'edit'
 * @param {object|null} data - Data transaksi (untuk mode edit)
 */
function openModal(mode, data = null) {
    const transactionModal = document.getElementById('transaction-modal');
    const modalTitle = document.getElementById('modal-title');
    const transactionForm = document.getElementById('transaction-form');
    const formName = document.getElementById('customer_name');
    const formPhone = document.getElementById('phone');
    const formAddress = document.getElementById('address');
    const formWeight = document.getElementById('weight');
    const formDate = document.getElementById('date');
    const formStatus = document.getElementById('status');
    const deliveryMethod = document.getElementById('delivery_method');
    const serviceCards = document.querySelectorAll('.service-option-card');
    const totalCostDisplay = document.getElementById('calc-total-display');
    const totalCostInput = document.getElementById('total_cost');

    window._isEditing = (mode === 'edit');
    transactionModal.classList.add('active');
    document.body.style.overflow = 'hidden';

    if (window._isEditing && data) {
        modalTitle.textContent = 'Edit Transaksi Laundry';
        window._editId = data.id;

        // Populate form fields
        formName.value = data.customer_name || '';
        formPhone.value = data.phone || '';
        formAddress.value = data.address || '';
        formWeight.value = data.weight || '';
        formDate.value = data.date || '';
        formStatus.value = data.status || 'Proses';
        deliveryMethod.value = data.delivery_method || 'Ambil Di Tempat';

        // Highlight the correct service card
        window._selectedService = data.service_type || '';
        serviceCards.forEach(card => {
            card.classList.toggle('selected', card.dataset.service === window._selectedService);
        });

        updateStatusSelectOptions();
        calculateCost();
    } else {
        modalTitle.textContent = 'Tambah Transaksi Laundry';
        window._editId = '';
        transactionForm.reset();

        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        formDate.value = today;
        formStatus.value = 'Proses';
        deliveryMethod.value = 'Ambil Di Tempat';

        // Select the first service automatically so user can continue quickly
        serviceCards.forEach(card => card.classList.remove('selected'));
        const firstCard = serviceCards[0];
        if (firstCard) {
            firstCard.classList.add('selected');
            window._selectedService = firstCard.dataset.service || '';
        } else {
            window._selectedService = '';
        }

        calculateCost();
    }
}

/**
 * Tutup modal
 */
function closeModal() {
    const transactionModal = document.getElementById('transaction-modal');
    transactionModal.classList.remove('active');
    document.body.style.overflow = '';
}
