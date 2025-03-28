const Modal = (function () {
    const modalWrapper = document.getElementById('modal-wrapper');
    const modalContent = document.getElementById('modal-content');
    const modalFooter = document.getElementById('modal-footer');
    const modalPanel = document.getElementById('modal-panel');
    const backdrop = modalWrapper.querySelector('[data-slot="backdrop"]');
    let lastFocusedElement = null; // To store the element that triggered the modal

    // Utility: Toggle classes
    function toggleClasses(element, addClasses = [], removeClasses = []) {
        element.classList.remove(...removeClasses);
        element.classList.add(...addClasses);
    }

    // Function to close the modal
    function close() {
        toggleClasses(backdrop, ['opacity-0'], ['opacity-75']);
        toggleClasses(modalPanel, ['opacity-0', 'scale-95'], ['opacity-100', 'scale-100']);

        setTimeout(() => {
            modalWrapper.classList.add('hidden');
            modalWrapper.classList.remove('block');
            modalContent.innerHTML = '';
            modalFooter.innerHTML = '';
        }, 300);

        modalWrapper.removeEventListener('click', handleBackdropClick);

        // Return focus to the last focused element
        if (lastFocusedElement) {
            lastFocusedElement.focus();
            lastFocusedElement = null;
        }
    }

    // Function to handle backdrop click (close modal when clicking outside)
    function handleBackdropClick(e) {
        if (e.target === modalWrapper || e.target.dataset.slot === 'backdrop') {
            close();
        }
    }

    // Trap focus within the modal
    function trapFocus(event) {
        const focusableElements = modalPanel.querySelectorAll(
            'a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])'
        );
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && document.activeElement === firstElement) {
            lastElement.focus();
            event.preventDefault();
        } else if (!event.shiftKey && document.activeElement === lastElement) {
            firstElement.focus();
            event.preventDefault();
        }
    }

    // Function to open the modal
    function open({ content, actions = [], size = 'sm:max-w-lg', classes = '', closeOnBackdropClick = true }) {
        // Store the currently focused element
        lastFocusedElement = document.activeElement;

        modalContent.innerHTML = content || '';
        modalFooter.innerHTML = '';

        modalPanel.className = `relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full ${size} ${classes} opacity-0 scale-95`;

        actions.forEach(action => {
            const button = document.createElement('button');
            button.textContent = action.label;
            button.className = action.class || 'mt-3 inline-flex justify-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-200 sm:mt-0 sm:w-auto';
            button.addEventListener('click', action.onClick);
            modalFooter.appendChild(button);
        });

        modalWrapper.classList.remove('hidden');
        setTimeout(() => {
            toggleClasses(backdrop, ['opacity-75'], ['opacity-0']);
            toggleClasses(modalPanel, ['opacity-100', 'scale-100'], ['opacity-0', 'scale-95']);
        }, 25);

        modalWrapper.classList.add('block');

        if (closeOnBackdropClick) {
            modalWrapper.addEventListener('click', handleBackdropClick);
        }

        // Trap focus within the modal
        document.addEventListener('keydown', event => {
            if (event.key === 'Tab') {
                trapFocus(event);
            } else if (event.key === 'Escape') {
                close();
            }
        });

        modalPanel.setAttribute('tabindex', '-1'); // Make it focusable
        modalPanel.focus();
    }

    return { open, close };
})();

export default Modal;
