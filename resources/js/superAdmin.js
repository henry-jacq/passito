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

    // Add Hostel modal usage
    const addHostelButton = document.querySelector('.add-hostel-modal');
    if (addHostelButton) {
        addHostelButton.addEventListener('click', () => {
            // Fetch the list of wardens and institutions from the server
            const fetchWardens = Ajax.post('/api/web/admin/wardens/fetch');
            const fetchInstitutions = Ajax.post('/api/web/admin/institutions/fetch');

            Promise.all([fetchWardens, fetchInstitutions])
                .then(async (responses) => {

                    const wardenResponse = responses[0];
                    const institutionResponse = responses[1];

                    if (wardenResponse.ok && institutionResponse.ok) {
                        const wardens = wardenResponse.data.data.wardens;
                        const institutions = institutionResponse.data.data.institutions;

                        try {
                            const response = await Ajax.post('/api/web/admin/modal', {
                                template: "add_hostel",
                                wardens, institutions
                            });

                            if (response.ok && response.data) {
                                Modal.open({
                                    content: response.data,
                                    actions: [
                                        {
                                            label: 'Add Hostel',
                                            class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                            onClick: async (event) => {
                                                const hostelName = document.getElementById('hostel-name').value;
                                                const category = document.getElementById('hostel-category').value;
                                                const wardenId = document.getElementById('select-warden').value;
                                                const institutionId = document.getElementById('select-institution').value;

                                                event.target.disabled = true;
                                                event.target.textContent = 'Adding Hostel...';

                                                if (hostelName && wardenId && category && institutionId) {
                                                    try {
                                                        const response = await Ajax.post('/api/web/admin/hostels/create', {
                                                            hostel_name: hostelName,
                                                            warden_id: wardenId,
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
                                console.error('Error loading modal template:', response.data.message || 'Unknown error');
                                alert(response.data.message || 'Failed to load modal template');
                            }
                        } catch (error) {
                            console.error('Failed to load modal content:', error);
                            alert('Failed to load modal content. Please try again later.');
                        }
                    } else {
                        let toast = new Toast();

                        if (!wardenResponse.ok) {
                            const message = wardenResponse?.data?.message || "Failed to fetch wardens.";
                            toast.create({
                                message,
                                position: "bottom-right",
                                type: "warning",
                                duration: 4000,
                            });
                        }

                        if (!institutionResponse.ok) {
                            const message = institutionResponse?.data?.message || "Failed to fetch institutions.";
                            toast.create({
                                message,
                                position: "bottom-right",
                                type: "warning",
                                duration: 4000,
                            });
                        }
                    }
                })
                .catch((error) => {
                    console.error('Error fetching data:', error);
                    alert('An error occurred while fetching data.');
                });

        });
    }

    // Outpass Template Modal
    const addTemplate = document.getElementById('add-outpass-template');
    if (addTemplate) {
        addTemplate.addEventListener('click', async () => {
            try {
                const response = await Ajax.get('/api/web/admin/modal?template=outpass_template');

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Create Template',
                                class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    const templateName = document.getElementById('template-name').value;
                                    const templateDescription = document.getElementById('template-description').value;
                                    const visibility = document.querySelector('select').value;
                                    const allowAttachments = document.getElementById('allow-attachments').checked;

                                    // disable the button to prevent multiple clicks
                                    event.target.disabled = true;
                                    event.target.textContent = 'Creating Template...';

                                    if (templateName && templateDescription) {
                                        try {
                                            const response = await Ajax.post('/api/web/admin/templates/create', {
                                                name: templateName,
                                                description: templateDescription,
                                                visibility: visibility,
                                                allow_attachments: allowAttachments,
                                                fields: [
                                                    // User-defined fields
                                                    ...Array.from(document.querySelectorAll('#template-fields .group:not(.hidden)')).map(group => {
                                                        return {
                                                            name: group.querySelector('.field-name').value.trim(),
                                                            type: group.querySelector('.field-type').value,
                                                            required: group.querySelector('.field-required').checked
                                                        };
                                                    }).filter(f => f.name) // remove empty ones
                                                ]
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
                                                event.target.textContent = 'Create Template';
                                                event.target.disabled = false;
                                            }
                                        } catch (error) {
                                            console.error(error);
                                        } finally {
                                            Modal.close();
                                        }
                                    } else {
                                        alert('Please fill in all the required fields correctly.');
                                        event.target.textContent = 'Create Template';
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
    const typeSelect = document.getElementById("assignment_type");
    const hostelSelection = document.getElementById("hostel_selection");
    const yearSelection = document.getElementById("year_selection");
    const form = document.querySelector("form");
    const submitBtn = form.querySelector("button[type=submit]");
    const previewBox = document.getElementById("assignment_preview");
    const previewText = document.getElementById("preview_text");

    // Disable submit initially
    submitBtn.disabled = true;
    submitBtn.classList.add("opacity-50", "cursor-not-allowed");

    function toggleAssignmentOptions() {
        const type = typeSelect.value;

        // Hide all conditional fields
        hostelSelection.classList.add("hidden");
        yearSelection.classList.add("hidden");

        if (type === "hostel") {
            hostelSelection.classList.remove("hidden");
        } else if (type === "year") {
            yearSelection.classList.remove("hidden");
        }

        validateForm();
    }

    function updatePreview() {
        const wardenName = wardenSelect.options[wardenSelect.selectedIndex]?.text || "";
        const type = typeSelect.value;

        let chosenValues = [];
        if (type === "hostel") {
            chosenValues = [...hostelSelection.querySelectorAll("input[type=checkbox]:checked")].map(cb => cb.nextElementSibling?.innerText || cb.value);
        } else if (type === "year") {
            chosenValues = [...yearSelection.querySelectorAll("input[type=checkbox]:checked")].map(cb => cb.nextElementSibling?.innerText || cb.value);
        }

        if (wardenName && type && chosenValues.length > 0) {
            previewBox.classList.remove("hidden");
            previewText.textContent = `You’re about to assign ${wardenName} to ${type} → ${chosenValues.join(", ")}`;
        } else {
            previewBox.classList.add("hidden");
            previewText.textContent = "";
        }
    }

    function validateForm() {
        const wardenChosen = wardenSelect.value !== "";
        const typeChosen = typeSelect.value !== "";
        let valuesChosen = false;

        if (typeSelect.value === "hostel") {
            valuesChosen = hostelSelection.querySelectorAll("input[type=checkbox]:checked").length > 0;
        } else if (typeSelect.value === "year") {
            valuesChosen = yearSelection.querySelectorAll("input[type=checkbox]:checked").length > 0;
        }

        if (wardenChosen && typeChosen && valuesChosen) {
            submitBtn.disabled = false;
            submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add("opacity-50", "cursor-not-allowed");
        }

        updatePreview();
    }

    // Attach listeners
    wardenSelect.addEventListener("change", validateForm);
    typeSelect.addEventListener("change", toggleAssignmentOptions);

    [hostelSelection, yearSelection].forEach(section => {
        section.addEventListener("change", validateForm);
    });

    // Handle submit
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const wardenId = wardenSelect.value;
        const type = typeSelect.value;

        let values = [];
        if (type === "hostel") {
            values = [...hostelSelection.querySelectorAll("input[type=checkbox]:checked")].map(cb => cb.value);
        } else if (type === "year") {
            values = [...yearSelection.querySelectorAll("input[type=checkbox]:checked")].map(cb => cb.value);
        }

        const payload = {
            warden_id: wardenId,
            assignment_type: type,
            assignment_data: values
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

    // Initial preview update
    updatePreview();
});