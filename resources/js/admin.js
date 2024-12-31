// admin.js

// Import the Modal module
import Modal from './libs/modal';

function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}


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
                <div class="p-3 space-y-6">
                    <h3 class="text-2xl font-bold text-gray-900">Add New Device</h3>

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label for="verifier-name" class="block text-md font-semibold text-gray-700">Verifier Name</label>
                            <input type="text" id="verifier-name" name="verifier-name" 
                                class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" 
                                placeholder="Enter verifier's name" required>
                        </div>

                        <div class="space-y-2">
                            <label for="device-location" class="block text-md font-semibold text-gray-700">Device Location</label>
                            <input type="text" id="device-location" name="device-location" 
                                class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" 
                                placeholder="Enter device location" required>
                        </div>

                        <p class="text-sm text-gray-500">Provide accurate details for the new verifier device to ensure proper setup.</p>
                    </div>
                </div>
            `,
                actions: [
                    {
                        label: 'Add Device',
                        class: `inline-flex justify-center rounded-lg bg-green-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-green-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2`,
                        onClick: () => {
                            const verifierName = document.getElementById('verifier-name').value;
                            const deviceLocation = document.getElementById('device-location').value;

                            if (verifierName && deviceLocation) {
                                alert(`Device added successfully for ${verifierName} at ${deviceLocation}`);
                                Modal.close();
                            } else {
                                alert('Please fill in both the verifier name and device location.');
                            }
                        },
                    },
                    {
                        label: 'Cancel',
                        class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                        onClick: Modal.close,
                    },
                ],
                size: 'sm:max-w-xl',
                classes: 'custom-modal-class',
                closeOnBackdropClick: false,
            });
        });
    }

    // Add Warden Modal example usage
    const openAddWardenModalButton = document.getElementById('open-add-warden-modal');
    if (openAddWardenModalButton) {
        openAddWardenModalButton.addEventListener('click', () => {
            Modal.open({
                content: `
                <div class="p-3 space-y-6">
                    <h3 class="text-2xl font-bold text-gray-900">Add New Warden</h3>

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label for="warden-name" class="block text-md font-semibold text-gray-700">Warden Name</label>
                            <input type="text" id="warden-name" name="warden-name" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" placeholder="Enter Name" required>
                        </div>

                        <div class="space-y-2">
                            <label for="warden-email" class="block text-md font-semibold text-gray-700">Warden Email</label>
                            <input type="email" id="warden-email" name="warden-email" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" placeholder="Enter Email" required>
                        </div>

                        <div class="space-y-2">
                            <label for="warden-contact" class="block text-md font-semibold text-gray-700">Contact Number</label>
                            <input type="text" id="warden-contact" name="warden-contact" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" placeholder="Enter Contact No" required>
                        </div>

                        <div class="space-y-2">
                            <label for="warden-hostel" class="block text-md font-semibold text-gray-700">Hostel to Assign</label>
                            <select id="warden-hostel" name="warden-hostel" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 sm:text-md transition duration-200"
                                required>
                                <option value="" disabled selected>Select a Hostel</option>
                                <option value="hostelA">Hostel A</option>
                                <option value="hostelB">Hostel B</option>
                                <option value="hostelC">Hostel C</option>
                            </select>
                        </div>
                    </div>
                </div>
            `,
                actions: [
                    {
                        label: 'Add Warden',
                        class: `inline-flex justify-center rounded-lg bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-indigo-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2`,
                        onClick: () => {
                            const wardenName = document.getElementById('warden-name').value;
                            const wardenEmail = document.getElementById('warden-email').value;
                            const wardenContact = document.getElementById('warden-contact').value;
                            const wardenHostel = document.getElementById('warden-hostel').value;

                            if (wardenName && wardenEmail && wardenContact && wardenHostel) {
                                alert(`Warden added successfully:\nName: ${wardenName}\nEmail: ${wardenEmail}\nContact: ${wardenContact}\nHostel: ${wardenHostel}`);
                                Modal.close();
                            } else {
                                alert('Please fill in all the fields.');
                            }
                        },
                    },
                    {
                        label: 'Cancel',
                        class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                        onClick: Modal.close,
                    },
                ],
                size: 'sm:max-w-xl',
                classes: 'custom-modal-class',
                closeOnBackdropClick: false,
            });
        });
    }
});
