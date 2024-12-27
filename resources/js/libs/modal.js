// Modal.js

const Modal = (function () {
    const modalWrapper = document.getElementById('modal-wrapper');
    const modalContent = document.getElementById('modal-content');
    const modalFooter = document.getElementById('modal-footer');
    const modalPanel = document.getElementById('modal-panel');

    function open({ content, actions = [], size = 'sm:max-w-lg', classes = '' }) {
        modalContent.innerHTML = content || '';
        modalFooter.innerHTML = '';

        // Apply size and custom classes
        modalPanel.className = `relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full ${size} ${classes}`;

        // Add actions
        actions.forEach(action => {
            const button = document.createElement('button');
            button.textContent = action.label;
            button.className = action.class || 'mt-3 inline-flex justify-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-200 sm:mt-0 sm:w-auto';
            button.addEventListener('click', action.onClick);
            modalFooter.appendChild(button);
        });

        // Show modal
        modalWrapper.classList.remove('hidden');
        modalWrapper.classList.add('block');
    }

    function close() {
        modalWrapper.classList.add('hidden');
        modalWrapper.classList.remove('block');
        modalContent.innerHTML = '';
        modalFooter.innerHTML = '';
    }

    // Event listener to close modal when clicking on backdrop
    modalWrapper.addEventListener('click', (e) => {
        if (e.target === modalWrapper || e.target.dataset.slot === 'backdrop') close();
    });

    return { open, close };
})();

// Export the Modal object
export default Modal;
