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
            const fetchAcademicYears = await Ajax.post('/api/web/admin/academic_years/fetch');

            
            if (fetchHostels.ok && fetchPrograms.ok && fetchAcademicYears.ok) {
                let hostelsData = [];
                let programData = [];
                let academicYearsData = [];
                const hostels = fetchHostels.data;
                const programs = fetchPrograms.data;
                const academicYears = fetchAcademicYears.data;

                if (hostels.status === false) {
                    const toast = new Toast();
                    toast.create({ message: hostels.message || 'Unable to fetch hostels.', position: "bottom-right", type: "warning", duration: 4000 });
                    return;
                }

                if (programs.status === false) {
                    const toast = new Toast();
                    toast.create({ message: programs.message || 'Unable to fetch programs.', position: "bottom-right", type: "warning", duration: 4000 });
                    return;
                }

                if (academicYears.status === false) {
                    const toast = new Toast();
                    toast.create({ message: academicYears.message || 'Unable to fetch academic years.', position: "bottom-right", type: "warning", duration: 4000 });
                    return;
                }

                if (
                    hostels.status && Array.isArray(hostels.data.hostels)
                    && programs.status && Array.isArray(programs.data.programs)
                    && academicYears.status && Array.isArray(academicYears.data.academic_years)
                ) {
                    hostelsData = hostels.data.hostels;
                    programData = programs.data.programs;
                    academicYearsData = academicYears.data.academic_years;
                } else {
                    const toast = new Toast();
                    const errorMessage =
                        (academicYears && academicYears.status === false && academicYears.message)
                        || (hostels && hostels.status === false && hostels.message)
                        || (programs && programs.status === false && programs.message)
                        || academicYears?.message
                        || hostels?.message
                        || programs?.message
                        || 'Invalid hostel, program, or academic year data.';
                    toast.create({ message: errorMessage, position: "bottom-right", type: "warning", duration: 4000 });
                    return;
                }

                try {
                    const response = await Ajax.post('/api/web/admin/modal', {
                        template: "add_student",
                        hostels: hostelsData,
                        programs: programData,
                        academic_years: academicYearsData
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
                                        const academicYearId = document.getElementById('academic-year-id').value;

                                        event.target.disabled = true;
                                        event.target.textContent = 'Adding Student...';

                                        if (studentName && email && digitalId && year && roomNo && hostelNo && studentNo && parentNo && programId && academicYearId) {
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
                                                    program: programId,
                                                    academic_year: academicYearId
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

    // Export Report CSV
    const exportReportButtons = document.querySelectorAll('.export-report-btn');

    if (exportReportButtons.length > 0) {
        exportReportButtons.forEach((button) => {
            button.addEventListener('click', async (event) => {
                event.stopPropagation();
                const key = button.getAttribute('data-key');

                const toast = new Toast();
                toast.create({
                    message: "Exporting, please wait...",
                    position: "bottom-right",
                    type: "queue",
                    duration: 4000
                });

                try {
                    const response = await Ajax.download(
                        `/api/web/admin/reports/export?key=${key}`,
                        'POST',
                        { 'Content-Type': 'application/json' }
                    );

                    if (response.ok) {
                        const url = window.URL.createObjectURL(response.data);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = response.filename || `${key}_export.csv`; // fallback filename
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);

                        toast.create({
                            message: "Export completed successfully",
                            position: "bottom-right",
                            type: "success",
                            duration: 4000
                        });
                    } else {
                        toast.create({
                            message: response.message || "Failed to export data",
                            position: "bottom-right",
                            type: "error",
                            duration: 4000
                        });
                    }
                } catch (error) {
                    console.error('Export failed:', error);
                    toast.create({
                        message: "An error occurred during export",
                        position: "bottom-right",
                        type: "error",
                        duration: 4000
                    });
                } finally {
                    Modal.close();
                }
            });
        });
    }
});
