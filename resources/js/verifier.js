import Ajax from './libs/ajax';
import Toast from './libs/toast';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('manual-verifier-form');
    if (!form) return;

    const outpassInput = document.getElementById('outpass-id');
    const buttons = form.querySelectorAll('button[data-action]');

    buttons.forEach((button) => {
        button.addEventListener('click', async () => {
            const outpassId = outpassInput?.value?.trim();
            const action = button.dataset.action;

            if (!outpassId) {
                const toast = new Toast();
                toast.create({ message: 'Please enter a valid outpass ID.', position: "bottom-right", type: "warning", duration: 4000 });
                return;
            }

            button.disabled = true;

            try {
                const response = await Ajax.post('/api/web/verifier/log', {
                    outpass_id: outpassId,
                    action
                });

                const toast = new Toast();
                if (response.ok && response.data?.status) {
                    toast.create({ message: response.data.message || 'Verification successful.', position: "bottom-right", type: "success", duration: 4000 });
                    window.location.reload();
                } else {
                    toast.create({ message: response.data?.message || 'Verification failed.', position: "bottom-right", type: "error", duration: 5000 });
                }
            } catch (error) {
                const toast = new Toast();
                toast.create({ message: 'An error occurred while verifying the outpass.', position: "bottom-right", type: "error", duration: 5000 });
            } finally {
                button.disabled = false;
            }
        });
    });
});
