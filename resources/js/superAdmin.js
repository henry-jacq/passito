// superAdmin.js

// Import the Modal module
import Modal from './libs/modal';
import Ajax from './libs/ajax';

function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function handleError(status) {
    switch (status) {
        case 400:
            alert('Bad Request: Please check the form data and try again.');
            break;
        case 401:
            alert('Unauthorized: Please login and try again.');
            break;
        case 403:
            alert('Forbidden: You do not have permission to perform this action.');
            break;
        case 404:
            alert('Not Found: The requested resource was not found.');
            break;
        case 500:
            alert('Internal Server Error: Please try again later.');
            break;
        default:
            alert('An error occurred while processing the request.');
            break;
    }
}

document.addEventListener('DOMContentLoaded', () => {
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
    const openAddWardenModalButton = document.getElementById('add-warden-modal');
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
                    </div>
                </div>
            `,
                actions: [
                    {
                        label: 'Add Warden',
                        class: `inline-flex justify-center rounded-lg bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-indigo-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50`,
                        onClick: async (event) => {
                            const wardenName = document.getElementById('warden-name').value;
                            const wardenEmail = document.getElementById('warden-email').value;
                            const wardenContact = document.getElementById('warden-contact').value;

                            // disable the button to prevent multiple clicks
                            event.target.disabled = true;
                            event.target.textContent = 'Adding Warden...';

                            if (wardenName && isValidEmail(wardenEmail) && wardenContact) {
                                try {
                                    const response = await Ajax.post('/api/web/admin/wardens/create', {
                                        name: wardenName,
                                        email: wardenEmail,
                                        contact: wardenContact
                                    });

                                    if (response.ok) {
                                        const data = response.data;
                                        if (data.status) {
                                            location.reload();
                                        } else {
                                            alert(data.message);
                                        }
                                    } else {
                                        handleError(response.status);
                                        event.target.textContent = 'Add Warden';
                                        event.target.disabled = false;
                                    }
                                } catch (error) {
                                    console.error(error);
                                } finally {
                                    Modal.close();
                                }
                            } else {
                                alert('Please fill in all the required fields correctly.');
                                event.target.textContent = 'Add Warden';
                                event.target.disabled = false;
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

    // Select all buttons with the class 'remove-warden-modal'
    const removeWardenButtons = document.querySelectorAll('.remove-warden-modal');

    // Iterate over each button and attach an event listener
    removeWardenButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            const wardenId = event.target.dataset.id;
            const wardenName = event.target.dataset.wardenname;

            Modal.open({
                content: `
                <div class="p-3 space-y-6">
                    <h3 class="text-2xl font-bold text-gray-900">Remove Warden</h3>

                    <div class="space-y-5">
                        <p class="text-gray-700">Are you sure you want to remove the warden <span class="font-semibold">${wardenName}</span>?</p>
                    </div>
                </div>
            `,
                actions: [
                    {
                        label: 'Remove Warden',
                        class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2`,
                        onClick: async () => {
                            try {
                                const response = await Ajax.post(`/api/web/admin/wardens/remove`, {
                                    warden_id: wardenId
                                });

                                if (response.ok) {
                                    const data = response.data;
                                    if (data.status) {
                                        location.reload();
                                    } else {
                                        alert(data.message);
                                    }
                                } else {
                                    handleError(response.status);
                                }
                            } catch (error) {
                                console.error(error);
                            } finally {
                                Modal.close();
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
    });

    // Add Institution modal usage
    const addInstitutionButton = document.querySelector('.add-institution-modal');
    if (addInstitutionButton) {
        addInstitutionButton.addEventListener('click', () => {
            Modal.open({
                content: `
                <div class="p-3 space-y-6">
                    <h3 class="text-2xl font-bold text-gray-900">Add New Institution</h3>

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label for="institution-name" class="block text-md font-semibold text-gray-700">Institution Name</label>
                            <input type="text" id="institution-name" name="institution-name" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" placeholder="Enter Name" required>
                        </div>
                        <div class="space-y-2">
                            <label for="institution-address" class="block text-md font-semibold text-gray-700">Institution Address</label>
                            <input type="text" id="institution-address" name="institution-address" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" placeholder="Enter Address" required>
                        </div>
                        <div class="space-y-2">
                            <label for="institution-type" class="block text-md font-semibold text-gray-700">Institution Type</label>
                            <select id="institution-type" name="institution-type" class="mt-1 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" required>
                                <option value="college">College</option>
                                <option value="university">University</option>
                            </select>
                        </div>
                    </div>
                </div>
            `,
                actions: [
                    {
                        label: 'Add Institution',
                        class: `inline-flex justify-center rounded-lg bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-indigo-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50`,
                        onClick: async (event) => {
                            const institutionName = document.getElementById('institution-name').value;
                            const institutionAddress = document.getElementById('institution-address').value;
                            const institutionType = document.getElementById('institution-type').value;

                            // disable the button to prevent multiple clicks
                            event.target.disabled = true;
                            event.target.textContent = 'Adding Institution...';

                            if (institutionName && institutionAddress && institutionType) {
                                try {
                                    const response = await Ajax.post('/api/web/admin/facilities/institutions/create', {
                                        name: institutionName,
                                        address: institutionAddress,
                                        type: institutionType
                                    });

                                    if (response.ok) {
                                        const data = response.data;
                                        if (data.status) {
                                            location.reload();
                                        } else {
                                            alert(data.message);
                                        }
                                    } else {
                                        handleError(response.status);
                                        event.target.textContent = 'Add Institution';
                                        event.target.disabled = false;
                                    }
                                } catch (error) {
                                    console.error(error);
                                } finally {
                                    Modal.close();
                                }
                            } else {
                                alert('Please fill in all the required fields correctly.');
                                event.target.textContent = 'Add Institution';
                                event.target.disabled = false;
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

    // Add Hostel modal usage
    const addHostelButton = document.querySelector('.add-hostel-modal');
    if (addHostelButton) {
        addHostelButton.addEventListener('click', () => {
            // Fetch the list of wardens and institutions from the server
            const fetchWardens = Ajax.post('/api/web/admin/wardens/fetch');
            const fetchInstitutions = Ajax.post('/api/web/admin/facilities/institutions/fetch');

            Promise.all([fetchWardens, fetchInstitutions])
                .then((responses) => {
                    const wardenResponse = responses[0];
                    const institutionResponse = responses[1];

                    if (wardenResponse.ok && institutionResponse.ok) {
                        const wardens = wardenResponse.data.data.wardens;
                        const institutions = institutionResponse.data.data.institutions;

                        // Generate options for wardens and institutions
                        const wardenOptions = wardens
                            .map((warden) => `<option value="${warden.id}">${warden.name} (${warden.email})</option>`)
                            .join('');
                        const institutionOptions = institutions
                            .map((institution) => `<option value="${institution.id}">${institution.name}</option>`)
                            .join('');

                        // Open the modal with dynamically populated options
                        Modal.open({
                            content: `
                            <div class="p-3 space-y-6">
                                <h3 class="text-2xl font-bold text-gray-900">Add New Hostel</h3>

                                <div class="space-y-5">
                                    <div class="space-y-2">
                                        <label for="hostel-name" class="block text-md font-semibold text-gray-700">Hostel Name</label>
                                        <input type="text" id="hostel-name" name="hostel-name" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" placeholder="Enter Name" required>
                                    </div>
                                    <div class="space-y-2">
                                        <label for="select-warden" class="block text-md font-semibold text-gray-700">Assign Warden</label>
                                        <select id="select-warden" name="select-warden" class="mt-1 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" required>
                                            ${wardenOptions}
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label for="select-institution" class="block text-md font-semibold text-gray-700">Select Institution</label>
                                        <select id="select-institution" name="select-institution" class="mt-1 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-md transition duration-200" required>
                                            ${institutionOptions}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            `,
                            actions: [
                                {
                                    label: 'Add Hostel',
                                    class: `inline-flex justify-center rounded-lg bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-indigo-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50`,
                                    onClick: async (event) => {
                                        const hostelName = document.getElementById('hostel-name').value;
                                        const wardenId = document.getElementById('select-warden').value;
                                        const institutionId = document.getElementById('select-institution').value;

                                        event.target.disabled = true;
                                        event.target.textContent = 'Adding Hostel...';

                                        if (hostelName && wardenId && institutionId) {
                                            try {
                                                const response = await Ajax.post('/api/web/admin/facilities/hostels/create', {
                                                    hostel_name: hostelName,
                                                    warden_id: wardenId,
                                                    institution_id: institutionId
                                                });

                                                if (response.ok) {
                                                    const data = response.data;
                                                    if (data.status) {
                                                        location.reload();
                                                    } else {
                                                        alert(data.message);
                                                    }
                                                } else {
                                                    handleError(response.status);
                                                }
                                            } catch (error) {
                                                console.error(error);
                                            } finally {
                                                event.target.textContent = 'Add Hostel';
                                                event.target.disabled = false;
                                                Modal.close();
                                            }
                                        } else {
                                            alert('Please fill in all the required fields.');
                                            event.target.textContent = 'Add Hostel';
                                            event.target.disabled = false;
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
                    } else {
                        alert('Failed to fetch wardens or institutions.');
                    }
                })
                .catch((error) => {
                    console.error('Error fetching data:', error);
                    alert('An error occurred while fetching data.');
                });

        });
    }
    // Select all buttons with the class 'remove-hostel-modal'
    const removeHostelButtons = document.querySelectorAll('.remove-hostel-modal');

    // Iterate over each button and attach an event listener
    removeHostelButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            const hostelId = event.target.closest('button').dataset.id;
            const hostelName = event.target.closest('button').dataset.name;

            Modal.open({
                content: `
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-800 border-b pb-3">Remove Hostel</h3>
                    <p class="text-gray-600">
                        Are you sure you want to remove the hostel 
                        <span class="font-semibold text-gray-800">${hostelName}</span>?
                    </p>
                    <div class="bg-red-100 border-l-4 border-red-400 text-red-600 p-4 rounded-md">
                        <p class="font-semibold">Warning:</p>
                        <p class="space-y-1">
                            Ensure all students are moved to another hostel before proceeding.
                        </p>
                    </div>
                </div>
            `,
                actions: [
                    {
                        label: 'Remove Hostel',
                        class: `inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 mx-2 text-white font-medium shadow-sm hover:bg-red-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200`,
                        onClick: async () => {
                            try {
                                const response = await Ajax.post(`/api/web/admin/facilities/hostels/remove`, {
                                    hostel_id: hostelId,
                                });

                                if (response.ok) {
                                    const data = response.data;
                                    if (data.status) {
                                        location.reload(); // Refresh the page on success
                                    } else {
                                        alert(data.message); // Show error message
                                    }
                                } else {
                                    handleError(response.status); // Handle server error
                                }
                            } catch (error) {
                                console.error(error); // Log unexpected errors
                            } finally {
                                Modal.close(); // Close the modal
                            }
                        },
                    },
                    {
                        label: 'Cancel',
                        class: `inline-flex justify-center rounded-lg bg-gray-100 px-4 py-2 text-gray-700 font-medium shadow-sm hover:bg-gray-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all duration-200`,
                        onClick: Modal.close, // Close modal on cancel
                    },
                ],

                size: 'sm:max-w-xl', // Set modal width
                classes: 'custom-modal-class', // Add custom class if needed
                closeOnBackdropClick: false, // Disable backdrop click to avoid accidental closure
            });
        });
    });
});
