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
        openAddDeviceModalButton.addEventListener('click', async () => {
            try {
                const response = await Ajax.get('/api/web/admin/modal?template=add_verifier');

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Add Device',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async () => {
                                    const verifierName = document.getElementById('verifier-name')?.value;
                                    const deviceLocation = document.getElementById('device-location')?.value;

                                    if (verifierName && deviceLocation) {
                                        try {
                                            const createResponse = await Ajax.post('/api/web/admin/verifiers/create', {
                                                verifier_name: verifierName,
                                                location: deviceLocation,
                                            });

                                            if (createResponse.ok && createResponse.data?.status) {
                                                location.reload();
                                            } else {
                                                alert(createResponse.data?.message || 'Failed to add device.');
                                            }
                                        } catch (err) {
                                            console.error(err);
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
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    }

    // Add Manual Verifier Modal
    const openAddManualVerifierButton = document.getElementById('open-add-manual-verifier-modal');
    if (openAddManualVerifierButton) {
        openAddManualVerifierButton.addEventListener('click', async () => {
            try {
                const response = await Ajax.get('/api/web/admin/modal?template=add_manual_verifier');

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Add Manual Verifier',
                                class: `inline-flex justify-center rounded-lg bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-indigo-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async () => {
                                    const name = document.getElementById('manual-verifier-name')?.value;
                                    const email = document.getElementById('manual-verifier-email')?.value;
                                    const contact = document.getElementById('manual-verifier-contact')?.value;
                                    const verifierLocation = document.getElementById('manual-verifier-location')?.value;

                                    if (!name || !email || !contact || !verifierLocation) {
                                        alert('Please fill in all the fields.');
                                        return;
                                    }

                                    if (!isValidEmail(email)) {
                                        alert('Please enter a valid email address.');
                                        return;
                                    }

                                    try {
                                        const createResponse = await Ajax.post('/api/web/admin/manual_verifiers/create', {
                                            name,
                                            email,
                                            contact,
                                            location: verifierLocation,
                                        });

                                        if (createResponse.ok && createResponse.data?.status) {
                                            location.reload();
                                        } else {
                                            alert(createResponse.data?.message || 'Failed to add manual verifier.');
                                        }
                                    } catch (err) {
                                        console.error(err);
                                    }

                                    Modal.close();
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
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    }

    // Add Warden Modal example usage
    const openAddWardenModalButton = document.getElementById('add-warden-modal');
    if (openAddWardenModalButton) {
        openAddWardenModalButton.addEventListener('click', async () => {

            try {
                const response = await Ajax.get('/api/web/admin/modal?template=add_warden');

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Add Warden',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
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
                                                    toastMessage = data.message || 'Warden not created.';
                                                }
                                            } else {
                                                toastMessage = response.data?.message || `Warden not created. (HTTP ${response.status})`;
                                                event.target.textContent = 'Add Warden';
                                                event.target.disabled = false;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                            toastMessage = 'An error occurred while creating warden.';
                                        } finally {
                                            Modal.close();
                                            if (toastMessage) {
                                                const toast = new Toast();
                                                toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                            }
                                        }
                                    } else {
                                        const toast = new Toast();
                                        toast.create({ message: 'Please fill in all the required fields correctly.', position: "bottom-right", type: "warning", duration: 4000 });
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
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    }

    // Select all buttons with the class 'remove-warden-modal'
    const removeWardenButtons = document.querySelectorAll('.remove-warden-modal');

    // Iterate over each button and attach an event listener
    removeWardenButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const wardenId = event.currentTarget.dataset.id;
            const wardenName = event.currentTarget.dataset.wardenname;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "remove_warden",
                    wardenId: wardenId,
                    wardenName: wardenName
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
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
                                                const toast = new Toast();
                                                toast.create({ message: data.message || 'Failed to delete hostel.', position: "bottom-right", type: "error", duration: 5000 });
                                            }
                                        } else {
                                            const toast = new Toast();
                                            toast.create({ message: `Failed to delete hostel. (HTTP ${response.status})`, position: "bottom-right", type: "error", duration: 5000 });
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
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Edit Warden modal
    const editWardenButtons = document.querySelectorAll('.edit-warden-modal');
    editWardenButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const warden = {
                id: button.dataset.id,
                name: button.dataset.name,
                email: button.dataset.email,
                contact: button.dataset.contact
            };

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "edit_warden",
                    warden: warden
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Update Warden',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    const wardenName = document.getElementById('warden-name').value;
                                    const wardenEmail = document.getElementById('warden-email').value;
                                    const wardenContact = document.getElementById('warden-contact').value;

                                    event.target.disabled = true;
                                    event.target.textContent = 'Updating Warden...';

                                    if (wardenName && isValidEmail(wardenEmail) && wardenContact) {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/wardens/update', {
                                                warden_id: warden.id,
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
                                                event.target.textContent = 'Update Warden';
                                                event.target.disabled = false;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                        } finally {
                                            Modal.close();
                                        }
                                    } else {
                                        alert('Please fill in all the required fields correctly.');
                                        event.target.textContent = 'Update Warden';
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
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Add Institution modal usage
    const addInstitutionButton = document.querySelector('.add-institution-modal');
    if (addInstitutionButton) {
        addInstitutionButton.addEventListener('click', async () => {
            try {
                const response = await Ajax.get('/api/web/admin/modal?template=add_institution');

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
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
                                            const response = await Ajax.post('/api/web/admin/institutions/create', {
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
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    }

    // Edit Institution modal
    const editInstitutionButtons = document.querySelectorAll('.edit-institution-modal');
    editInstitutionButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const institution = {
                id: button.dataset.id,
                name: button.dataset.name,
                address: button.dataset.address,
                type: button.dataset.type
            };

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "edit_institution",
                    institution: institution,
                    types: ['college', 'university']
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Update Institution',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    const name = document.getElementById('institution-name').value;
                                    const address = document.getElementById('institution-address').value;
                                    const type = document.getElementById('institution-type').value;

                                    event.target.disabled = true;
                                    event.target.textContent = 'Updating Institution...';

                                    if (name && address && type) {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/institutions/update', {
                                                institution_id: institution.id,
                                                name: name,
                                                address: address,
                                                type: type
                                            });

                                            if (response.ok) {
                                                const data = response.data;
                                                if (data.status) {
                                                    location.reload();
                                                } else {
                                                    toastMessage = data.message || 'Institution not updated.';
                                                }
                                            } else {
                                                toastMessage = response.data?.message || `Institution not updated. (HTTP ${response.status})`;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                            toastMessage = 'An error occurred while updating institution.';
                                        } finally {
                                            Modal.close();
                                            if (toastMessage) {
                                                const toast = new Toast();
                                                toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                            }
                                        }
                                    } else {
                                        const toast = new Toast();
                                        toast.create({ message: 'Please fill in all the required fields.', position: "bottom-right", type: "warning", duration: 4000 });
                                        event.target.textContent = 'Update Institution';
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
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Delete Institution modal
    const deleteInstitutionButtons = document.querySelectorAll('.delete-institution-modal');
    deleteInstitutionButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const institutionId = button.dataset.id;
            const institutionName = button.dataset.name;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_institution",
                    institutionName: institutionName
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Delete',
                                class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    event.target.disabled = true;
                                    event.target.textContent = 'Deleting...';

                                    try {
                                        const response = await Ajax.post(`/api/web/admin/institutions/remove`, {
                                            institution_id: institutionId
                                        });

                                        if (response.ok) {
                                            const data = response.data;
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                toastMessage = data.message || 'Failed to delete institution.';
                                            }
                                        } else {
                                            toastMessage = response.data?.message || `Failed to delete institution. (HTTP ${response.status})`;
                                        }
                                    } catch (error) {
                                        console.error(error);
                                        toastMessage = 'An error occurred while deleting institution.';
                                    } finally {
                                        Modal.close();
                                        if (toastMessage) {
                                            const toast = new Toast();
                                            toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                        }
                                    }
                                },
                            },
                            {
                                label: 'Cancel',
                                class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                                onClick: Modal.close,
                            },
                        ],
                        size: 'sm:max-w-md',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error(error);
            }
        });
    });

    // Add Program modal
    const addProgramButton = document.querySelector('.add-program-modal');
    if (addProgramButton) {
        addProgramButton.addEventListener('click', async () => {
            try {
                const fetchInstitutions = await Ajax.post('/api/web/admin/institutions/fetch');
                if (!fetchInstitutions.ok || !fetchInstitutions.data?.status) {
                    const toast = new Toast();
                    toast.create({ message: fetchInstitutions.data?.message || 'Failed to load institutions.', position: "bottom-right", type: "error", duration: 5000 });
                    return;
                }

                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "add_program",
                    institutions: fetchInstitutions.data.data.institutions
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Create Program',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    const programName = document.getElementById('program-name').value;
                                    const shortCode = document.getElementById('program-shortcode').value;
                                    const courseName = document.getElementById('program-course').value;
                                    const duration = document.getElementById('program-duration').value;
                                    const institutionId = document.getElementById('program-institution').value;

                                    event.target.disabled = true;
                                    event.target.textContent = 'Creating Program...';

                                    if (programName && shortCode && courseName && duration && institutionId) {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/programs/create', {
                                                program_name: programName,
                                                short_code: shortCode,
                                                course_name: courseName,
                                                duration: duration,
                                                institution_id: institutionId
                                            });

                                            if (response.ok) {
                                                const data = response.data;
                                                if (data.status) {
                                                    location.reload();
                                                } else {
                                                    toastMessage = data.message || 'Program not created.';
                                                }
                                            } else {
                                                toastMessage = response.data?.message || `Program not created. (HTTP ${response.status})`;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                            toastMessage = 'An error occurred while creating program.';
                                        } finally {
                                            Modal.close();
                                            if (toastMessage) {
                                                const toast = new Toast();
                                                toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                            }
                                        }
                                    } else {
                                        const toast = new Toast();
                                        toast.create({ message: 'Please fill in all the required fields.', position: "bottom-right", type: "warning", duration: 4000 });
                                        event.target.textContent = 'Create Program';
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
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    }

    // Edit Program modal
    const editProgramButtons = document.querySelectorAll('.edit-program-modal');
    editProgramButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const program = {
                id: button.dataset.id,
                program_name: button.dataset.programName,
                course_name: button.dataset.courseName,
                short_code: button.dataset.shortCode,
                duration: button.dataset.duration,
                institution_id: button.dataset.institutionId
            };

            try {
                const fetchInstitutions = await Ajax.post('/api/web/admin/institutions/fetch');
                if (!fetchInstitutions.ok || !fetchInstitutions.data?.status) {
                    const toast = new Toast();
                    toast.create({ message: fetchInstitutions.data?.message || 'Failed to load institutions.', position: "bottom-right", type: "error", duration: 5000 });
                    return;
                }

                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "edit_program",
                    program: program,
                    institutions: fetchInstitutions.data.data.institutions
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Update Program',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    const programName = document.getElementById('program-name').value;
                                    const shortCode = document.getElementById('program-shortcode').value;
                                    const courseName = document.getElementById('program-course').value;
                                    const duration = document.getElementById('program-duration').value;
                                    const institutionId = document.getElementById('program-institution').value;

                                    event.target.disabled = true;
                                    event.target.textContent = 'Updating Program...';

                                    if (programName && shortCode && courseName && duration && institutionId) {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/programs/update', {
                                                program_id: program.id,
                                                program_name: programName,
                                                short_code: shortCode,
                                                course_name: courseName,
                                                duration: duration,
                                                institution_id: institutionId
                                            });

                                            if (response.ok) {
                                                const data = response.data;
                                                if (data.status) {
                                                    location.reload();
                                                } else {
                                                    toastMessage = data.message || 'Program not updated.';
                                                }
                                            } else {
                                                toastMessage = response.data?.message || `Program not updated. (HTTP ${response.status})`;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                            toastMessage = 'An error occurred while updating program.';
                                        } finally {
                                            Modal.close();
                                            if (toastMessage) {
                                                const toast = new Toast();
                                                toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                            }
                                        }
                                    } else {
                                        const toast = new Toast();
                                        toast.create({ message: 'Please fill in all the required fields.', position: "bottom-right", type: "warning", duration: 4000 });
                                        event.target.textContent = 'Update Program';
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
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Delete Program modal
    const deleteProgramButtons = document.querySelectorAll('.delete-program-modal');
    deleteProgramButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const programId = button.dataset.id;
            const programName = button.dataset.name;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_program",
                    programName: programName
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Delete',
                                class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    event.target.disabled = true;
                                    event.target.textContent = 'Deleting...';

                                    try {
                                        const response = await Ajax.post(`/api/web/admin/programs/remove`, {
                                            program_id: programId
                                        });

                                        if (response.ok) {
                                            const data = response.data;
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                toastMessage = data.message || 'Failed to delete program.';
                                            }
                                        } else {
                                            toastMessage = response.data?.message || `Failed to delete program. (HTTP ${response.status})`;
                                        }
                                    } catch (error) {
                                        console.error(error);
                                        toastMessage = 'An error occurred while deleting program.';
                                    } finally {
                                        Modal.close();
                                        if (toastMessage) {
                                            const toast = new Toast();
                                            toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                        }
                                    }
                                },
                            },
                            {
                                label: 'Cancel',
                                class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                                onClick: Modal.close,
                            },
                        ],
                        size: 'sm:max-w-md',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error(error);
            }
        });
    });

    // Add Academic Year modal
    const addAcademicYearButton = document.querySelector('.add-academic-year-modal');
    if (addAcademicYearButton) {
        addAcademicYearButton.addEventListener('click', async () => {
            try {
                const response = await Ajax.get('/api/web/admin/modal?template=add_academic_year');

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Add Academic Year',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    const label = document.getElementById('academic-year-label').value;
                                    const startYear = document.getElementById('academic-year-start').value;
                                    const endYear = document.getElementById('academic-year-end').value;
                                    const status = document.getElementById('academic-year-status').value;

                                    event.target.disabled = true;
                                    event.target.textContent = 'Adding Academic Year...';

                                    if (label && startYear && endYear && status !== '') {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/academic_years/create', {
                                                label: label,
                                                start_year: startYear,
                                                end_year: endYear,
                                                status: status
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
                                                event.target.textContent = 'Add Academic Year';
                                                event.target.disabled = false;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                        } finally {
                                            Modal.close();
                                        }
                                    } else {
                                        alert('Please fill in all the required fields correctly.');
                                        event.target.textContent = 'Add Academic Year';
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
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    }

    // Edit Academic Year modal
    const editAcademicYearButtons = document.querySelectorAll('.edit-academic-year');
    editAcademicYearButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const academicYear = {
                id: button.dataset.id,
                label: button.dataset.label,
                start_year: button.dataset.start,
                end_year: button.dataset.end,
                status: button.dataset.status,
            };

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "edit_academic_year",
                    academic_year: academicYear
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Update Academic Year',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    const label = document.getElementById('academic-year-label').value;
                                    const startYear = document.getElementById('academic-year-start').value;
                                    const endYear = document.getElementById('academic-year-end').value;
                                    const status = document.getElementById('academic-year-status').value;

                                    event.target.disabled = true;
                                    event.target.textContent = 'Updating Academic Year...';

                                    if (label && startYear && endYear && status !== '') {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/academic_years/update', {
                                                academic_year_id: academicYear.id,
                                                label: label,
                                                start_year: startYear,
                                                end_year: endYear,
                                                status: status
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
                                                event.target.textContent = 'Update Academic Year';
                                                event.target.disabled = false;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                        } finally {
                                            Modal.close();
                                        }
                                    } else {
                                        alert('Please fill in all the required fields correctly.');
                                        event.target.textContent = 'Update Academic Year';
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
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Delete Academic Year
    const deleteAcademicYearButtons = document.querySelectorAll('.delete-academic-year');
    deleteAcademicYearButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const academicYearId = button.dataset.id;
            const academicYearLabel = button.dataset.label;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_academic_year",
                    academic_year: {
                        id: academicYearId,
                        label: academicYearLabel
                    }
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Delete',
                                class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    event.target.disabled = true;
                                    event.target.textContent = 'Deleting...';

                                    try {
                                        const response = await Ajax.post('/api/web/admin/academic_years/remove', {
                                            academic_year_id: academicYearId
                                        });

                                        if (response.ok) {
                                            const data = response.data;
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                const toast = new Toast();
                                                toast.create({ message: data.message || 'Failed to delete hostel.', position: "bottom-right", type: "error", duration: 5000 });
                                            }
                                        } else {
                                            const toast = new Toast();
                                            toast.create({ message: response.data?.message || `Failed to delete hostel. (HTTP ${response.status})`, position: "bottom-right", type: "error", duration: 5000 });
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
                        size: 'sm:max-w-md',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error(error);
            }
        });
    });

    // Add Hostel modal usage
    const addHostelButton = document.querySelector('.add-hostel-modal');
    if (addHostelButton) {
        addHostelButton.addEventListener('click', async () => {
            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "add_hostel"
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Add Hostel',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    const hostelName = document.getElementById('hostel-name').value;
                                    const category = document.getElementById('hostel-category').value;

                                    event.target.disabled = true;
                                    event.target.textContent = 'Adding Hostel...';

                                    if (hostelName && category) {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/hostels/create', {
                                                hostel_name: hostelName,
                                                category: category
                                            });

                                            if (response.ok) {
                                                const data = response.data;
                                                if (data.status) {
                                                    location.reload();
                                                } else {
                                                    toastMessage = data.message || 'Hostel not created.';
                                                }
                                            } else {
                                                toastMessage = response.data?.message || `Hostel not created. (HTTP ${response.status})`;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                            toastMessage = 'An error occurred while creating hostel.';
                                        } finally {
                                            event.target.textContent = 'Add Hostel';
                                            event.target.disabled = false;
                                            Modal.close();
                                            if (toastMessage) {
                                                const toast = new Toast();
                                                toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                            }
                                        }
                                    } else {
                                        const toast = new Toast();
                                        toast.create({ message: 'Please fill in all the required fields.', position: "bottom-right", type: "warning", duration: 4000 });
                                        event.target.textContent = 'Add Hostel';
                                        event.target.disabled = false;
                                    }
                                }
                            },
                            {
                                label: 'Cancel',
                                class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                                onClick: Modal.close,
                            }
                        ],
                        size: 'sm:max-w-xl',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    }

    // Edit Hostel modal
    const editHostelButtons = document.querySelectorAll('.edit-hostel-modal');
    editHostelButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const hostel = {
                id: button.dataset.id,
                name: button.dataset.name,
                category: button.dataset.category
            };

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "edit_hostel",
                    hostel: hostel
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Update Hostel',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    const hostelName = document.getElementById('hostel-name').value;
                                    const category = document.getElementById('hostel-category').value;

                                    event.target.disabled = true;
                                    event.target.textContent = 'Updating Hostel...';

                                    if (hostelName && category) {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/hostels/update', {
                                                hostel_id: hostel.id,
                                                hostel_name: hostelName,
                                                category: category
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
                                                event.target.textContent = 'Update Hostel';
                                                event.target.disabled = false;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                        } finally {
                                            Modal.close();
                                        }
                                    } else {
                                        alert('Please fill in all the required fields.');
                                        event.target.textContent = 'Update Hostel';
                                        event.target.disabled = false;
                                    }
                                }
                            },
                            {
                                label: 'Cancel',
                                class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                                onClick: Modal.close,
                            }
                        ],
                        size: 'sm:max-w-xl',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Delete Hostel modal
    const deleteHostelButtons = document.querySelectorAll('.remove-hostel-modal');
    deleteHostelButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const hostelId = button.dataset.id;
            const hostelName = button.dataset.name;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_hostel",
                    hostelName: hostelName
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Delete',
                                class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    event.target.disabled = true;
                                    event.target.textContent = 'Deleting...';

                                    try {
                                        const response = await Ajax.post(`/api/web/admin/hostels/remove`, {
                                            hostel_id: hostelId
                                        });

                                        if (response.ok) {
                                            const data = response.data;
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                toastMessage = data.message || 'Failed to delete hostel.';
                                            }
                                        } else {
                                            toastMessage = response.data?.message || `Failed to delete hostel. (HTTP ${response.status})`;
                                        }
                                    } catch (error) {
                                        console.error(error);
                                        toastMessage = 'An error occurred while processing the request.';
                                    } finally {
                                        Modal.close();
                                        if (toastMessage) {
                                            const toast = new Toast();
                                            toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                        }
                                    }
                                },
                            },
                            {
                                label: 'Cancel',
                                class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                                onClick: Modal.close,
                            },
                        ],
                        size: 'sm:max-w-md',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error(error);
            }
        });
    });

    // Remove Assignment modal
    const removeAssignmentButtons = document.querySelectorAll('.remove-assignment-modal');
    removeAssignmentButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const assignmentId = button.dataset.id;
            const wardenName = button.dataset.wardenname;
            const hostelName = button.dataset.hostelname;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_assignment",
                    wardenName: wardenName,
                    hostelName: hostelName
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Remove',
                                class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    event.target.disabled = true;
                                    event.target.textContent = 'Removing...';

                                    try {
                                        const response = await Ajax.post(`/api/web/admin/wardens/assignments/remove`, {
                                            assignment_id: assignmentId
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
                        size: 'sm:max-w-md',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error(error);
            }
        });
    });

    const openTemplateModal = async (templateData = null) => {
        try {
            const response = templateData
                ? await Ajax.post('/api/web/admin/modal?template=outpass_template', {
                    template: 'outpass_template',
                    template_data: templateData
                })
                : await Ajax.get('/api/web/admin/modal?template=outpass_template');

            if (response.ok && response.data) {
                Modal.open({
                    content: response.data,
                    actions: [
                        {
                            label: (templateData && templateData.id) ? 'Update Template' : 'Create Template',
                            class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                            onClick: async (event) => {
                                const templateId = document.getElementById('template-id')?.value || null;
                                const baseLabel = templateId ? 'Update Template' : 'Create Template';
                                const templateName = document.getElementById('template-name').value;
                                const templateDescription = document.getElementById('template-description').value;
                                const visibility = document.querySelector('select')?.value;
                                const allowAttachments = document.getElementById('allow-attachments').checked;
                                const isActive = document.getElementById('template-active')?.checked;
                                const weekdayCollegeHoursStart = document.getElementById('weekday-college-hours-start')?.value || null;
                                const weekdayCollegeHoursEnd = document.getElementById('weekday-college-hours-end')?.value || null;
                                const weekdayOvernightStart = document.getElementById('weekday-overnight-start')?.value || null;
                                const weekdayOvernightEnd = document.getElementById('weekday-overnight-end')?.value || null;
                                const weekendStartTime = document.getElementById('weekend-start-time')?.value || null;
                                const weekendEndTime = document.getElementById('weekend-end-time')?.value || null;

                                event.target.disabled = true;
                                event.target.textContent = templateId ? 'Updating Template...' : 'Creating Template...';

                                if (templateName && templateDescription) {
                                    try {
                                        const payload = {
                                            name: templateName,
                                            description: templateDescription,
                                            allow_attachments: allowAttachments,
                                            is_active: isActive,
                                            weekday_college_hours_start: weekdayCollegeHoursStart,
                                            weekday_college_hours_end: weekdayCollegeHoursEnd,
                                            weekday_overnight_start: weekdayOvernightStart,
                                            weekday_overnight_end: weekdayOvernightEnd,
                                            weekend_start_time: weekendStartTime,
                                            weekend_end_time: weekendEndTime,
                                            fields: [
                                                ...Array.from(document.querySelectorAll('#template-fields .group:not(.hidden)')).map(group => {
                                                    return {
                                                        name: group.querySelector('.field-name').value.trim(),
                                                        type: group.querySelector('.field-type').value,
                                                        required: group.querySelector('.field-required').checked
                                                    };
                                                }).filter(f => f.name)
                                            ]
                                        };

                                        let endpoint = '/api/web/admin/templates/create';
                                        if (templateId) {
                                            endpoint = '/api/web/admin/templates/update';
                                            payload.template_id = templateId;
                                        } else if (visibility !== undefined) {
                                            payload.visibility = visibility;
                                        }

                                        const submitResponse = await Ajax.post(endpoint, payload);

                                        if (submitResponse.ok) {
                                            const data = submitResponse.data;
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                alert(data.message);
                                            }
                                        } else {
                                            handleError(submitResponse.status);
                                            event.target.textContent = baseLabel;
                                            event.target.disabled = false;
                                        }
                                    } catch (error) {
                                        console.error(error);
                                    } finally {
                                        Modal.close();
                                    }
                                } else {
                                    alert('Please fill in all the required fields correctly.');
                                    event.target.textContent = baseLabel;
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
                    size: 'sm:max-w-2xl',
                    classes: 'custom-modal-class',
                    closeOnBackdropClick: false,
                });

                // Attach event listeners after modal is rendered
                setTimeout(() => {
                    const addFieldBtn = document.getElementById("add-field");
                    const fieldContainer = document.getElementById("template-fields");
                    const maxFields = 4;  // Set the max fields limit

                    if (addFieldBtn && fieldContainer) {
                        addFieldBtn.addEventListener("click", () => {
                            // Count the current visible fields
                            const currentFields = fieldContainer.querySelectorAll(".group:not(.hidden)").length;

                            if (currentFields < maxFields) {
                                const template = fieldContainer.querySelector(".group.hidden");
                                if (template) {
                                    const clone = template.cloneNode(true);
                                    clone.classList.remove("hidden");

                                    // Reset input values in the cloned field
                                    clone.querySelectorAll("input, select, textarea").forEach((el) => {
                                        if (el.type === "checkbox") {
                                            el.checked = false;
                                        } else {
                                            el.value = "";
                                        }
                                    });

                                    // Reattach remove button logic
                                    const removeBtn = clone.querySelector(".remove-field");
                                    if (removeBtn) {
                                        removeBtn.addEventListener("click", () => {
                                            clone.remove();
                                        });
                                    }

                                    fieldContainer.appendChild(clone);
                                }
                            } else {
                                alert(`You can only add up to ${maxFields} fields.`);
                            }
                        });
                    }

                    // Also attach remove logic to any existing visible fields
                    fieldContainer.querySelectorAll(".group:not(.hidden) .remove-field").forEach(btn => {
                        btn.addEventListener("click", (e) => {
                            e.currentTarget.closest(".group")?.remove();
                        });
                    });
                }, 0);
            } else {
                console.error('Error loading modal template:', response.data.message || 'Unknown error');
                alert(response.data.message || 'Failed to load modal template');
            }
        } catch (error) {
            console.error('Failed to load modal content:', error);
            alert('Failed to load modal content. Please try again later.');
        }
    };

    // Outpass Template Modal
    const addTemplate = document.getElementById('add-outpass-template');
    if (addTemplate) {
        addTemplate.addEventListener('click', async () => {
            await openTemplateModal();
        });
    }

    // Automated verifier activation/deactivation removed (devices are active when registered)
    // Delete verifier modal
    const deleteVerifierModal = document.querySelectorAll('.delete-verifier-modal');
    deleteVerifierModal.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const verifierId = event.target.dataset.id;
            const verifierName = event.target.dataset.name;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_verifier",
                    verifierId, verifierName
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
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
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Edit Outpass Template
    document.querySelectorAll('.edit-template-btn').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const templateId = btn.dataset.id;
            if (!templateId) return;

            try {
                const fetchResponse = await Ajax.post('/api/web/admin/templates/fetch', {
                    template_id: templateId
                });

                if (!fetchResponse.ok || !fetchResponse.data?.status) {
                    alert(fetchResponse.data?.message || 'Failed to fetch template.');
                    return;
                }

                await openTemplateModal(fetchResponse.data.data);
            } catch (error) {
                console.error('Error loading edit template modal:', error);
                alert('Failed to load template.');
            }
        });
    });

    // Delete Outpass Template
    document.querySelectorAll('.delete-template-btn').forEach((button) => {
        button.addEventListener('click', async () => {
            const templateId = button.dataset.id;
            const templateName = button.dataset.name;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_template",
                    templateName: templateName
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Delete',
                                class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    event.target.disabled = true;
                                    event.target.textContent = 'Deleting...';

                                    try {
                                        const response = await Ajax.post(`/api/web/admin/templates/remove`, {
                                            template_id: templateId
                                        });

                                        if (response.ok) {
                                            const data = response.data;
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                toastMessage = data.message || 'Failed to delete template.';
                                            }
                                        } else {
                                            toastMessage = response.data?.message || `Failed to delete template. (HTTP ${response.status})`;
                                        }
                                    } catch (error) {
                                        console.error(error);
                                        toastMessage = 'An error occurred while deleting template.';
                                    } finally {
                                        Modal.close();
                                        if (toastMessage) {
                                            const toast = new Toast();
                                            toast.create({ message: toastMessage, position: "bottom-right", type: "error", duration: 5000 });
                                        }
                                    }
                                },
                            },
                            {
                                label: 'Cancel',
                                class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                                onClick: Modal.close,
                            },
                        ],
                        size: 'sm:max-w-md',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Activate manual verifier buttons
    const activateManualVerifierButtons = document.querySelectorAll('.activate-manual-verifier-modal');
    activateManualVerifierButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const activateId = event.target.dataset.id;

            try {
                const response = await Ajax.post(`/api/web/admin/manual_verifiers/activate`, {
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

    // Deactivate manual verifier buttons
    const deactivateManualVerifierButtons = document.querySelectorAll('.deactivate-manual-verifier-modal');
    deactivateManualVerifierButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const activateId = event.target.dataset.id;

            try {
                const response = await Ajax.post(`/api/web/admin/manual_verifiers/deactivate`, {
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

    // Delete manual verifier modal
    const deleteManualVerifierModal = document.querySelectorAll('.delete-manual-verifier-modal');
    deleteManualVerifierModal.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const verifierId = event.target.dataset.id;
            const verifierName = event.target.dataset.name;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_manual_verifier",
                    verifierId, verifierName
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Delete Manual Verifier',
                                class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2`,
                                onClick: async () => {
                                    try {
                                        const response = await Ajax.post(`/api/web/admin/manual_verifiers/delete`, {
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
                } else {
                    console.error('Error loading modal template:', response.data.message || 'Unknown error');
                    alert(response.data.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });

    // Report Status Toggle
    const toggleReportStatusButtons = document.querySelectorAll('.toggle-report-status');

    toggleReportStatusButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const reportId = button.getAttribute('data-id');
            try {
                const response = await Ajax.post(`/api/web/admin/reports/status`, {
                    report_id: reportId
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
    
    // Report Settings
    const reportSettingsButtons = document.querySelectorAll('.report-settings-button');

    reportSettingsButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const settingId = event.target.dataset.id;

            try {
                // fetch wardens and report config in parallel
                const [fetchWardens, fetchConfig] = await Promise.all([
                    Ajax.post('/api/web/admin/wardens/fetch'),
                    Ajax.post('/api/web/admin/reports/fetch_config', { report_id: settingId })
                ]);

                if (!fetchWardens.ok) {
                    alert(fetchWardens.data?.message || 'Failed to load wardens');
                    return;
                }

                if (!fetchConfig.ok) {
                    alert(fetchConfig.data?.message || 'Failed to load report config');
                    return;
                }

                const wardens = fetchWardens.data.data.wardens;
                const reportConfig = fetchConfig.data.data;

                const response = await Ajax.post('/api/web/admin/modal?template=report_settings', {
                    wardens,
                    reportConfig
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Save Settings',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async () => {
                                    try {
                                        const modal = document.getElementById('modal-panel');
                                        const frequency = modal.querySelector('#frequency')?.value || '';

                                        // dynamically pick the correct day field
                                        let dayOfMonth = '';
                                        if (frequency === 'monthly') {
                                            dayOfMonth = modal.querySelector('#day-of-month')?.value || '';
                                        } else if (frequency === 'yearly') {
                                            dayOfMonth = modal.querySelector('#yearly-day-of-month')?.value || '';
                                        }

                                        const formData = {
                                            report_id: settingId,
                                            frequency,
                                            dayOfWeek: modal.querySelector('#day-of-week')?.value || '',
                                            dayOfMonth, // <- dynamic
                                            month: modal.querySelector('#month')?.value || '',
                                            time: modal.querySelector('#time')?.value || '',
                                            recipients: Array.from(
                                                modal.querySelectorAll('input[name="recipients[]"]:checked')
                                            ).map(input => parseInt(input.value)),
                                        };

                                        const saveResponse = await Ajax.post(`/api/web/admin/reports/save_config`, formData);

                                        if (saveResponse.ok) {
                                            const data = saveResponse.data;
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                alert(data.message);
                                            }
                                        } else {
                                            handleError(saveResponse.status);
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
                        size: 'sm:max-w-lg',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: false,
                    });

                    // Initialize frequency-dependent fields and pre-check recipients
                    const initModalFields = () => {
                        const modalPanel = document.getElementById('modal-panel');
                        if (!modalPanel) return;

                        const freqSelect = modalPanel.querySelector('#frequency');
                        const weekly = modalPanel.querySelector('#weekly-options');
                        const monthly = modalPanel.querySelector('#monthly-options');
                        const yearly = modalPanel.querySelector('#yearly-options');

                        const dayOfWeek = modalPanel.querySelector('#day-of-week');
                        const dayOfMonth = modalPanel.querySelector('#day-of-month');
                        const yearlyDay = modalPanel.querySelector('#yearly-day-of-month');
                        const month = modalPanel.querySelector('#month');

                        const toggleSections = () => {
                            const val = freqSelect.value;

                            // reset dependent fields
                            if (dayOfWeek) dayOfWeek.value = '';
                            if (dayOfMonth) dayOfMonth.value = '';
                            if (yearlyDay) yearlyDay.value = '';
                            if (month) month.value = '';

                            // Weekly
                            if (weekly) {
                                if (val === 'weekly') {
                                    weekly.classList.remove('hidden');
                                } else {
                                    weekly.classList.add('hidden');
                                }
                            }

                            // Monthly
                            if (monthly) {
                                if (val === 'monthly') {
                                    monthly.classList.remove('hidden');
                                } else {
                                    monthly.classList.add('hidden');
                                }
                            }

                            // Yearly
                            if (yearly) {
                                if (val === 'yearly') {
                                    yearly.classList.remove('hidden');
                                    yearly.classList.add('md:flex', 'space-x-4');
                                } else {
                                    yearly.classList.add('hidden');
                                    yearly.classList.remove('md:flex', 'space-x-4');
                                }
                            }
                        };

                        toggleSections(); // set initial visibility
                        freqSelect.addEventListener('change', toggleSections);

                        // pre-check recipients
                        const preSelectedIds = reportConfig.recipients.map(r => parseInt(r.id));
                        modalPanel.querySelectorAll('input[name="recipients[]"]').forEach(input => {
                            input.checked = preSelectedIds.includes(parseInt(input.value));
                        });

                        // pre-fill saved values (only if available)
                        if (dayOfWeek) dayOfWeek.value = reportConfig.dayOfWeek || '';
                        if (dayOfMonth) dayOfMonth.value = reportConfig.dayOfMonth || '';
                        if (yearlyDay) yearlyDay.value = reportConfig.dayOfMonth || '';
                        if (month) month.value = reportConfig.month || '';

                        const timeInput = modalPanel.querySelector('#time');
                        if (timeInput) timeInput.value = reportConfig.time || '00:00';
                    };

                    // Small delay to ensure innerHTML is rendered
                    setTimeout(initModalFields, 50);
                } else {
                    console.error('Error loading modal template:', response.data?.message || 'Unknown error');
                    alert(response.data?.message || 'Failed to load modal template');
                }
            } catch (error) {
                console.error('Failed to load modal content:', error);
                alert('Failed to load modal content. Please try again later.');
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", () => {
    const wardenSelect = document.getElementById("warden");
    const hostelSelect = document.getElementById("hostel");
    const form = document.querySelector("form");
    const submitBtn = form.querySelector("button[type=submit]");

    // Function to update the submit button state based on the form
    function updateSubmitButtonState() {
        const wardenChosen = wardenSelect.value !== "";
        const hostelChosen = hostelSelect.value !== "";

        if (wardenChosen && hostelChosen) {
            submitBtn.disabled = false;
            submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add("opacity-50", "cursor-not-allowed");
        }
    }

    // Attach listeners
    wardenSelect.addEventListener("change", updateSubmitButtonState);
    hostelSelect.addEventListener("change", updateSubmitButtonState);

    // Handle submit
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const wardenId = wardenSelect.value;
        const hostelId = hostelSelect.value;

        const payload = {
            warden_id: wardenId,
            assignment_type: "hostel",
            assignment_data: [hostelId]
        };

        try {
            const response = await Ajax.post(`/api/web/admin/wardens/assign`, payload);

            if (response.ok && response.data.status) {
                location.reload();
            } else {
                alert(response.data?.message || "Failed to assign warden");
            }
        } catch (error) {
            console.error(error);
            alert("Unexpected error assigning warden");
        }
    });

    // Initial state update
    updateSubmitButtonState();
});
