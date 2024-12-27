// admin.js

// Import the Modal module
import Modal from './libs/modal';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const header = document.querySelector('header');

    // Sidebar toggle event
    if (sidebar && sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            adjustHeaderWidth();
        });
    }

    // Adjust header width based on screen size
    function adjustHeaderWidth() {
        if (window.innerWidth >= 1024) {
            header.style.marginLeft = '16rem';
            header.style.width = `calc(100% - 16rem)`;
        } else {
            header.style.marginLeft = '0';
            header.style.width = '100%';
        }
    }

    // Window resize event
    window.addEventListener('resize', adjustHeaderWidth);
    adjustHeaderWidth();

    // Notification button and dropdown logic
    if (notificationButton && notificationDropdown) {
        notificationButton.addEventListener('click', (event) => {
            event.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
            notificationDropdown.classList.toggle('scale-95');
            notificationDropdown.classList.toggle('opacity-0');
        });

        window.addEventListener('click', (event) => {
            if (!notificationDropdown.classList.contains('hidden') &&
                !notificationDropdown.contains(event.target) &&
                !notificationButton.contains(event.target)) {
                notificationDropdown.classList.add('hidden');
                notificationDropdown.classList.add('scale-95');
                notificationDropdown.classList.add('opacity-0');
            }
        });
    }

    // Add Device Modal example usage
    const openAddDeviceModalButton = document.getElementById('open-add-device-modal');
    if (openAddDeviceModalButton) {
        openAddDeviceModalButton.addEventListener('click', () => {
            Modal.open({
                content: `
                    <div class="p-2 space-y-6">
                        <h3 class="text-xl font-semibold text-gray-900">Add New Device</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="verifier-name" class="block text-sm font-medium text-gray-700">Verifier Name</label>
                                <input type="text" id="verifier-name" name="verifier-name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter verifier's name" required>
                            </div>

                            <div>
                                <label for="device-location" class="block text-sm font-medium text-gray-700">Device Location</label>
                                <input type="text" id="device-location" name="device-location" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter device location" required>
                            </div>

                        <p class="mt-2 text-sm text-gray-500">Please provide the details for the new verifier device.</p>

                        </div>
                    </div>
                `,
                actions: [
                    {
                        label: 'Add Device',
                        class: 'inline-flex justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:ml-3 sm:w-auto',
                        onClick: () => {
                            const verifierName = document.getElementById('verifier-name').value;
                            const deviceLocation = document.getElementById('device-location').value;

                            if (verifierName && deviceLocation) {
                                alert(`Device added successfully for ${verifierName} at ${deviceLocation}`);
                                Modal.close();
                            } else {
                                alert('Please fill in both the verifier name and device location.');
                            }
                        }
                    },
                    {
                        label: 'Cancel',
                        class: 'inline-flex justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto',
                        onClick: Modal.close
                    }
                ],
                size: 'sm:max-w-lg',
                classes: 'custom-modal-class', // Optional custom classes
                closeOnBackdropClick: false
            });
        });
    }
});
