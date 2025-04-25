// superAdmin.js

// Import the Modal module
import Ajax from './libs/ajax';
import Modal from './libs/modal';
import Toast from './libs/toast';

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
                <div class="px-2 space-y-6">
                    <h3 class="text-xl font-bold text-gray-800 border-b pb-3">Add New Device</h3>

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label for="verifier-name" class="block text-md font-semibold text-gray-700">Verifier Name</label>
                            <input type="text" id="verifier-name" name="verifier-name" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Name" required>
                        </div>
                        <div class="space-y-2">

                            <label for="device-location" class="block text-md font-semibold text-gray-700">Device Location</label>
                            <input type="text" id="device-location" name="device-location" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Location" required>
                        </div>

                        <p class="text-sm text-gray-500">Provide accurate details for the new verifier device to ensure proper setup.</p>
                    </div>
                </div>
                `,
                actions: [
                    {
                        label: 'Add Device',
                        class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                        onClick: async () => {
                            const verifierName = document.getElementById('verifier-name').value;
                            const deviceLocation = document.getElementById('device-location').value;

                            if (verifierName && deviceLocation) {
                                try {
                                    const response = await Ajax.post('/api/web/admin/verifiers/create', {
                                        verifier_name: verifierName,
                                        location: deviceLocation,
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
                                }
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
                <div class="px-2 space-y-6">
                    <h3 class="text-2xl font-bold text-gray-900">Add New Warden</h3>

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label for="warden-name" class="block text-md font-semibold text-gray-700">Warden Name</label>
                            <input type="text" id="warden-name" name="warden-name" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Name" required>
                        </div>

                        <div class="space-y-2">
                            <label for="warden-email" class="block text-md font-semibold text-gray-700">Warden Email</label>
                            <input type="email" id="warden-email" name="warden-email" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Email" required>
                        </div>

                        <div class="space-y-2">
                            <label for="warden-contact" class="block text-md font-semibold text-gray-700">Contact Number</label>
                            <input type="text" id="warden-contact" name="warden-contact" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Contact No" required>
                        </div>
                    </div>
                </div>
            `,
                actions: [
                    {
                        label: 'Add Warden',
                        class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
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
            const wardenId = event.currentTarget.dataset.id;
            const wardenName = event.currentTarget.dataset.wardenname;

            Modal.open({
                content: `
                <div class="px-2 space-y-6">
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
                <div class="px-2 space-y-6">
                    <h3 class="text-2xl font-bold text-gray-900">Add New Institution</h3>

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label for="institution-name" class="block text-md font-semibold text-gray-700">Institution Name</label>
                            <input type="text" id="institution-name" name="institution-name" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Name" required>
                        </div>
                        <div class="space-y-2">
                            <label for="institution-address" class="block text-md font-semibold text-gray-700">Institution Address</label>
                            <input type="text" id="institution-address" name="institution-address" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Address" required>
                        </div>
                        <div class="space-y-2">
                            <label for="institution-type" class="block text-md font-semibold text-gray-700">Institution Type</label>
                            <select id="institution-type" name="institution-type" class="mt-1 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" required>
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
                        class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
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
            // Fetch the list of institutions from the server
            const fetchInstitutions = Ajax.post('/api/web/admin/facilities/institutions/fetch');

            fetchInstitutions
                .then((response) => {
                    if (response.ok) {
                        const institutions = response.data.data.institutions;

                        const institutionOptions = institutions
                            .map((institution) => `<option value="${institution.id}">${institution.name}</option>`)
                            .join('');

                        // Open the modal with dynamically populated options
                        Modal.open({
                            content: `
                            <div class="px-2 space-y-6">
                                <h3 class="text-2xl font-bold text-gray-900">Add New Hostel</h3>

                                <div class="space-y-5">
                                    <div class="space-y-2">
                                        <label for="hostel-name" class="block text-md font-semibold text-gray-700">Hostel Name</label>
                                        <input type="text" id="hostel-name" name="hostel-name" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Name" required>
                                    </div>
                                    <div class="space-y-2">
                                        <label for="hostel-category" class="block text-md font-semibold text-gray-700">Hostel Category</label>
                                        <input type="text" id="hostel-category" name="hostel-category" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Name" required>
                                        <label class="block text-sm font-normal text-gray-600">Eg., Non-AC Shared (Common Bath), AC with Attached Bath and Balcony, AC Shared Room (Common Bath)...</label>
                                    </div>
                                    <div class="space-y-2">
                                        <label for="select-institution" class="block text-md font-semibold text-gray-700">Select Institution</label>
                                        <select id="select-institution" name="select-institution" class="mt-1 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" required>
                                            ${institutionOptions}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            `,
                            actions: [
                                {
                                    label: 'Add Hostel',
                                    class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                    onClick: async (event) => {
                                        const hostelName = document.getElementById('hostel-name').value;
                                        const category = document.getElementById('hostel-category').value;
                                        const institutionId = document.getElementById('select-institution').value;

                                        event.target.disabled = true;
                                        event.target.textContent = 'Adding Hostel...';

                                        if (hostelName && category && institutionId) {
                                            try {
                                                const response = await Ajax.post('/api/web/admin/facilities/hostels/create', {
                                                    hostel_name: hostelName,
                                                    category: category,
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

    // Activate verifer buttons
    const activateVerifierButtons = document.querySelectorAll('.activate-verifier-modal');

    // Iterate over each button and attach an event listener
    activateVerifierButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const activateId = event.target.dataset.id;

            try {
                const response = await Ajax.post(`/api/web/admin/verifiers/activate`, {
                    verifier_id: activateId
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
        });
    });
    
    // Activate verifer buttons
    const deactivateVerifierButtons = document.querySelectorAll('.deactivate-verifier-modal');
    deactivateVerifierButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const activateId = event.target.dataset.id;

            try {
                const response = await Ajax.post(`/api/web/admin/verifiers/deactivate`, {
                    verifier_id: activateId
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
        });
    });
    
    // Delete verifier modal
    const deleteVerifierModal = document.querySelectorAll('.delete-verifier-modal');
    deleteVerifierModal.forEach((button) => {
        button.addEventListener('click', (event) => {
            const verifierId = event.target.dataset.id;
            const verifierName = event.target.dataset.name;

            Modal.open({
                content: `
                <div class="px-2 space-y-6">
                    <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Delete Verifier</h3>
                    <div class="space-y-4">
                        <p class="text-gray-700">
                            You are about to delete the verifier <span class="font-semibold">${verifierName}</span>. 
                            This action will prevent the verifier from sending check-in and check-out data for students. Additionally, all existing logs associated with this verifier will be permanently deleted.
                        </p>
                        <p class="text-gray-700">
                            If you are unsure, consider deactivating the verifier instead of deleting it.
                        </p>
                    </div>
                </div>
                `,
                actions: [
                    {
                        label: 'Delete Verifier',
                        class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2`,
                        onClick: async () => {
                            try {
                                const response = await Ajax.post(`/api/web/admin/verifiers/delete`, {
                                    verifier_id: verifierId
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

    // Perform bulk approval
    const performBulkApproval = document.getElementById('bulkApproval');
    performBulkApproval.addEventListener('click', (event) => {
        Modal.open({
            content: `
        <div class="space-y-4">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">
                <i class="fas fa-check-circle mr-2 text-green-500"></i> Bulk Approval
            </h3>
            <div class="space-y-4">
                <p class="text-gray-700">
                    You are about to approve multiple pending outpass requests. Once approved, students will be able to check out as per the outpass details.
                </p>
                <p class="text-gray-700">
                    This action cannot be undone. Ensure you have verified the requests before proceeding.
                </p>
            </div>
        </div>
        `,
            actions: [
                {
                    label: 'Approve Requests',
                    class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200`,
                    onClick: async (btn) => {
                        // Disable button and change text
                        btn.disabled = true;
                        btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Performing...`;

                        // Close modal before initiating request
                        Modal.close();

                        try {
                            const response = await Ajax.post(`/api/web/admin/actions/bulk`, {});

                            if (response.ok) {
                                const data = response.data;
                                if (data.status) {
                                    const toast = new Toast();
                                    toast.create({ message: data.message, position: "bottom-right", type: "success", duration: 4000 });
                                    return;
                                } else {
                                    alert(data.message);
                                }
                            } else {
                                handleError(response.status);
                            }
                        } catch (error) {
                            console.error(error);
                        } finally {
                            // Re-enable the button if needed (in case of error)
                            btn.disabled = false;
                            btn.innerHTML = 'Approve Requests';
                        }
                    },
                },
                {
                    label: 'Cancel',
                    class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200`,
                    onClick: Modal.close,
                },
            ],
            size: 'sm:max-w-xl',
            classes: 'custom-modal-class',
            closeOnBackdropClick: false,
        });
    });

    // Notify students approval
    const performNotifyStudents = document.getElementById('notifyStudents');
    performNotifyStudents.addEventListener('click', (event) => {
        Modal.open({
            content: `
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">
                    <i class="fas fa-bell mr-2 text-red-500"></i> Notify Students
                </h3>
                <div class="space-y-4">
                    <p class="text-gray-700">
                        Some students have not checked in after their outpass duration expired.
                        You can send an alert to remind them to check in immediately.
                    </p>
                    <p class="text-gray-700">
                        This notification will be sent to all students who haven't checked in.
                    </p>
                </div>
            </div>
            `,
            actions: [
                {
                    label: 'Notify Students',
                    class: `inline-flex justify-center rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200`,
                    onClick: async () => {
                        try {
                            const response = await Ajax.post(`/api/web/admin/actions/notify`, {});

                            if (response.ok) {
                                const data = response.data;
                                if (data.status) {
                                    alert("Notification sent successfully!");
                                    // location.reload();
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
                    label: `Cancel`,
                    class: `inline-flex items-center justify-center rounded-lg bg-gray-100 px-5 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200`,
                    onClick: Modal.close,
                },
            ],
            size: 'sm:max-w-xl',
            classes: 'custom-modal-class',
            closeOnBackdropClick: false,
        });
    });
});
