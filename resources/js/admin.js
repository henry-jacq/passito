// admin.js

// Import the Modal module
import Modal from './libs/modal';
import Ajax from './libs/ajax';
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

document.addEventListener("DOMContentLoaded", () => {
    const preloader = document.getElementById("preloader");
    let loaderTimeout;

    // Hide preloader immediately on load (prevent flicker)
    preloader.classList.add("opacity-0", "pointer-events-none");
    preloader.classList.remove("opacity-100");

    // Add click listeners to all internal links
    document.querySelectorAll("a[href]").forEach(link => {
        link.addEventListener("click", function (e) {
            const href = link.getAttribute("href");

            if (
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                link.target === '_blank' ||
                link.hasAttribute('data-no-loader')
            ) return;

            // Small delay to avoid flicker on extremely fast navigations
            loaderTimeout = setTimeout(() => {
                preloader.classList.remove("pointer-events-none", "opacity-0");
                preloader.classList.add("opacity-100", "select-none");
            }, 100); // Adjust delay as needed
        });
    });

    // In case of using browser's back/forward cache
    window.addEventListener("pageshow", () => {
        clearTimeout(loaderTimeout);
        preloader.classList.add("opacity-0", "pointer-events-none");
        preloader.classList.remove("opacity-100");
    });
});

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

    // Stop event bubbling
    document.querySelectorAll('.stop-bubbling').forEach((el) => {
        el.addEventListener('click', (event) => {
            event.stopPropagation();
        });
    });

    // Open attachments modal
    const attachmentButtons = document.querySelectorAll('.view-attachments');
    attachmentButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.stopPropagation();
            const outpassId = button.dataset.id;

            const attachmentLinks = await Ajax.post('/api/web/admin/outpass/files', {
                id: outpassId
            });

            if (!attachmentLinks.ok) {
                const toast = new Toast();
                toast.create({ message: attachmentLinks.message || 'Failed to fetch attachments.', position: "bottom-right", type: "warning", duration: 4000 });
                return;
            }

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "attachments",
                    outpass_id: outpassId,
                    attachments: attachmentLinks.data.data
                });

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Close',
                                class: `inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`,
                                onClick: Modal.close,
                            },
                        ],
                        size: 'sm:max-w-lg',
                        classes: 'custom-modal-class',
                        closeOnBackdropClick: true,
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

    // Accept Outpass
    const acceptOutpassButtons = document.querySelectorAll('.accept-outpass');
    acceptOutpassButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const outpassId = event.target.dataset.id;

            try {
                const response = await Ajax.post(`/api/web/admin/outpass/accept`, {
                    id: outpassId
                });

                if (response.ok) {
                    const data = response.data;
                    if (data.status) {
                        const tr = event.target.closest('tr');
                        if (tr) {
                            const table = tr.closest('table');
                            const dataRows = table.querySelectorAll('tbody tr');

                            if (dataRows.length <= 1) {
                                location.reload();
                            } else {
                                tr.remove();
                            }
                        }
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

    // Reject outpass
    const rejectOutpassButtons = document.querySelectorAll('.reject-outpass');
    rejectOutpassButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            const outpassId = event.target.dataset.id;

            try {
                const response = await Ajax.post('/api/web/admin/modal?template=reject_outpass');

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Reject',
                                class: `inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async () => {
                                    try {
                                        const reason = document.getElementById('rejection-reason').value.trim();
                                        const response = await Ajax.post(`/api/web/admin/outpass/reject`, {
                                            id: outpassId,
                                            reason
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
                        size: 'sm:max-w-lg',
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

    // Add Student modal
    const addStudentButton = document.querySelector('.add-student-modal');
    if (addStudentButton) {
        addStudentButton.addEventListener('click', async () => {
            const fetchHostels = await Ajax.post('/api/web/admin/hostels/fetch');
            const fetchPrograms = await Ajax.post('/api/web/admin/programs/fetch');

            
            if (fetchHostels.ok && fetchPrograms.ok) {
                let hostelsData = [];
                let programData = [];
                const hostels = fetchHostels.data;
                const programs = fetchPrograms.data;

                if (hostels.status && Array.isArray(hostels.data.hostels) && programs.status && Array.isArray(programs.data.programs)) {
                    hostelsData = hostels.data.hostels;
                    programData = programs.data.programs;
                } else {
                    const toast = new Toast();
                    toast.create({ message: hostels.message || 'Invalid hostel or program data.', position: "bottom-right", type: "warning", duration: 4000 });
                    return;
                }

                try {
                    const response = await Ajax.post('/api/web/admin/modal', {
                        template: "add_student",
                        hostels: hostelsData,
                        programs: programData
                    });

                    if (response.ok && response.data) {
                        Modal.open({
                            content: response.data,
                            actions: [
                                {
                                    label: 'Add Student',
                                    class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                    onClick: async (event) => {
                                        const studentName = document.getElementById('student-name').value;
                                        const email = document.getElementById('email').value;
                                        const digitalId = document.getElementById('digital-id').value;
                                        const year = document.getElementById('year').value;
                                        const roomNo = document.getElementById('room-no').value;
                                        const hostelNo = document.getElementById('hostel-no').value;
                                        const studentNo = document.getElementById('student-no').value;
                                        const parentNo = document.getElementById('parent-no').value;
                                        const programId = document.getElementById('program-id').value;

                                        event.target.disabled = true;
                                        event.target.textContent = 'Adding Student...';

                                        if (studentName && email && digitalId && year && roomNo && hostelNo && studentNo && parentNo && programId) {
                                            if (studentNo === parentNo) {
                                                alert("Student number and Parent number must not be the same.");
                                                event.target.textContent = 'Add Student';
                                                event.target.disabled = false;
                                                return;
                                            }

                                            try {
                                                const response = await Ajax.post('/api/web/admin/students/create', {
                                                    name: studentName, email,
                                                    digital_id: digitalId, year, room_no: roomNo,
                                                    hostel_no: hostelNo,
                                                    contact: studentNo,
                                                    parent_no: parentNo,
                                                    program: programId
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
                                                    event.target.textContent = 'Add Student';
                                                    event.target.disabled = false;
                                                }
                                            } catch (error) {
                                                console.error(error);
                                            } finally {
                                                Modal.close();
                                            }
                                        } else {
                                            alert('Please fill in all the required fields correctly.');
                                            event.target.textContent = 'Add Student';
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
                            size: 'sm:max-w-3xl',
                            classes: 'focus:outline-none focus:ring-0 focus:border-transparent',
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
                handleError(fetchHostels.status);
            }
        });
    }

    // Export student data
    const exportStudentsButton = document.querySelector('.export-students');
    if (exportStudentsButton) {
        exportStudentsButton.addEventListener('click', async (event) => {
            event.stopPropagation();

            const toast = new Toast();
            toast.create({ message: "Exporting, please wait...", position: "bottom-right", type: "queue", duration: 4000 });

            try {
                const response = await Ajax.download(`/api/web/admin/students/export`, 'POST', {
                    'Content-Type': 'application/json'
                });

                if (response.ok) {
                    const url = window.URL.createObjectURL(response.data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = response.filename || 'students_export.csv'; // fallback filename
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                    toast.create({ message: "Export completed successfully", position: "bottom-right", type: "success", duration: 4000 });
                } else {
                    toast.create({ message: response.message || "Failed to export student data", position: "bottom-right", type: "error", duration: 4000 });
                }
            } catch (error) {
                console.error('Export failed:', error);
                alert('An error occurred during export.');
                toast.create({ message: "An error occurred during export", position: "bottom-right", type: "error", duration: 4000 });
            } finally {
                Modal.close();
            }
        });
    }

    // Import students data
    const importStudentsButton = document.getElementById('import-btn');
    importStudentsButton.addEventListener('click', async (event) => {
        event.stopPropagation();

        try {
            const response = await Ajax.post('/api/web/admin/modal?template=import_students');

            if (response.ok && response.data) {
                Modal.open({
                    content: response.data,
                    actions: [
                        {
                            label: 'Perform',
                            class: `inline-flex justify-center rounded-lg bg-gray-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-gray-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50`,
                            onClick: async (event) => {
                                try {
                                    const fileInput = document.getElementById('file');
                                    const file = fileInput.files[0];

                                    const button = event.target;
                                    const originalHtml = button.innerHTML;

                                    button.disabled = true;
                                    button.innerHTML = `<span class="flex items-center"><i class="fa-solid fa-spinner fa-spin w-4 h-4 mr-1"></i>Performing</span>`;

                                    // Ensure file is selected
                                    if (!file) {
                                        alert('Please select a CSV file.');
                                        return;
                                    }

                                    // Prepare the form data for submission
                                    const formData = new FormData();
                                    formData.append('file', file);

                                    // Make an Ajax request to upload the data
                                    const response = await fetch('/api/web/admin/students/import', {
                                        method: 'POST',
                                        body: formData,
                                    });

                                    if (response.ok) {
                                        const data = await response.json();
                                        if (data.status) {
                                            location.reload();
                                        } else {
                                            alert(data.message);
                                        }
                                    } else {
                                        const errorData = await response.json();
                                        alert(errorData.message || 'An error occurred while importing students.');
                                    }
                                } catch (error) {
                                    console.error(error);
                                    alert('An error occurred while processing the request.');
                                } finally {
                                    event.target.innerHTML = originalHtml;
                                    event.target.disabled = false;
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
                    classes: 'focus:outline-none focus:ring-0 focus:border-transparent',
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

document.addEventListener("DOMContentLoaded", () => {
    const performBulkApproval = document.getElementById('bulkApproval');
    performBulkApproval.addEventListener('click', async (event) => {
        try {
            const response = await Ajax.post('/api/web/admin/modal?template=action_bulk');

            if (response.ok && response.data) {
                Modal.open({
                    content: response.data,
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
            } else {
                console.error('Error loading modal template:', response.data.message || 'Unknown error');
                alert(response.data.message || 'Failed to load modal template');
            }
        } catch (error) {
            console.error('Failed to load modal content:', error);
            alert('Failed to load modal content. Please try again later.');
        }
    });

    // Notify students
    const performNotifyStudents = document.getElementById('notifyStudents');
    performNotifyStudents.addEventListener('click', async (event) => {
        try {
            const response = await Ajax.post('/api/web/admin/modal?template=action_notify');

            if (response.ok && response.data) {
                Modal.open({
                    content: response.data,
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
            } else {
                console.error('Error loading modal template:', response.data.message || 'Unknown error');
                alert(response.data.message || 'Failed to load modal template');
            }
        } catch (error) {
            console.error('Failed to load modal content:', error);
            alert('Failed to load modal content. Please try again later.');
        }
    });

    // Unlock Requests
    const unlockBtn = document.getElementById('unlockRequests');

    if (unlockBtn) {
        unlockBtn.addEventListener('click', async () => {
            try {
                const response = await Ajax.post(`/api/web/admin/actions/lock`, {
                    'status': 'unlock'
                });

                if (response.ok) {
                    const data = response.data;
                    // alert(data.message);

                    if (data.status) {
                        // Optionally refresh UI state
                        location.reload();
                    }
                } else {
                    handleError(response.status);
                }
            } catch (error) {
                console.error("Unlock request failed:", error);
                alert("Something went wrong. Please try again.");
            }
        });
    }
    
    // Lock Requests
    const performLockRequests = document.getElementById('lockRequests');
    performLockRequests.addEventListener('click', async (event) => {
        try {
            const response = await Ajax.post('/api/web/admin/modal?template=action_lock');

            if (response.ok && response.data) {
                Modal.open({
                    content: response.data,
                    actions: [
                        {
                            label: 'Lock Requests',
                            class: `inline-flex justify-center rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200`,
                            onClick: async () => {
                                try {
                                    const response = await Ajax.post(`/api/web/admin/actions/lock`, {
                                        'status': 'lock'
                                    });

                                    if (response.ok) {
                                        const data = response.data;
                                        if (data.status) {
                                            // alert(data.message);
                                            Modal.close();
                                            setTimeout(() => {
                                                location.reload();
                                            }, 100);
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
            } else {
                console.error('Error loading modal template:', response.data.message || 'Unknown error');
                alert(response.data.message || 'Failed to load modal template');
            }
        } catch (error) {
            console.error('Failed to load modal content:', error);
            alert('Failed to load modal content. Please try again later.');
        }
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
