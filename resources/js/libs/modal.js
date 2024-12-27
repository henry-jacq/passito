// Modal.js

const Modal = (function () {
    const modalWrapper = document.getElementById('modal-wrapper');
    const modalContent = document.getElementById('modal-content');
    const modalFooter = document.getElementById('modal-footer');
    const modalPanel = document.getElementById('modal-panel');
    const backdrop = modalWrapper.querySelector('[data-slot="backdrop"]');

    // Function to close the modal
    function close() {
        // Fade out modal and backdrop
        backdrop.classList.remove('opacity-75');
        backdrop.classList.add('opacity-0');
        modalPanel.classList.remove('opacity-100', 'scale-100');
        modalPanel.classList.add('opacity-0', 'scale-95');

        // Hide modal after transition
        setTimeout(() => {
            modalWrapper.classList.add('hidden');
            modalWrapper.classList.remove('block');
            modalContent.innerHTML = '';
            modalFooter.innerHTML = '';
        }, 300);  // delay for transition effect (same as modal's transition duration)

        // Remove event listener for backdrop click when closing modal
        modalWrapper.removeEventListener('click', handleBackdropClick);
    }

    // Function to handle backdrop click (close modal when clicking outside)
    function handleBackdropClick(e) {
        if (e.target === modalWrapper || e.target.dataset.slot === 'backdrop') {
            close();
        }
    }

    // Function to open the modal
    function open({ content, actions = [], size = 'sm:max-w-lg', classes = '', closeOnBackdropClick = true }) {
        modalContent.innerHTML = content || '';
        modalFooter.innerHTML = '';

        // Apply size and custom classes
        modalPanel.className = `relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full ${size} ${classes} opacity-0 scale-95`;

        // Add actions (buttons) to the footer
        actions.forEach(action => {
            const button = document.createElement('button');
            button.textContent = action.label;
            button.className = action.class || 'mt-3 inline-flex justify-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-200 sm:mt-0 sm:w-auto';
            button.addEventListener('click', action.onClick);
            modalFooter.appendChild(button);
        });

        // Show modal with smooth transition
        modalWrapper.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-75');
            modalPanel.classList.remove('opacity-0', 'scale-95');
            modalPanel.classList.add('opacity-100', 'scale-100');
        }, 25);  // small delay for transition

        modalWrapper.classList.add('block');

        // Add event listener to close modal when clicking on the backdrop
        if (closeOnBackdropClick) {
            modalWrapper.addEventListener('click', handleBackdropClick);
        }
    }

    return { open, close };
})();

// Export the Modal object
export default Modal;
