// admin.js

// Import the Modal module
import Modal from './libs/modal';
import Ajax from './libs/ajax';
import Toast from './libs/toast';
import Auth from './libs/auth';

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

    // Manage login sessions modal (admin settings page)
    const manageLoginsButton = document.getElementById('manage-logins-button');
    const openLoginSessionsModal = async () => {
        const sessionsResponse = await Ajax.post('/api/web/admin/sessions/fetch', {});
        if (!sessionsResponse.ok || !sessionsResponse.data?.status) {
            const toast = new Toast();
            toast.create({ message: sessionsResponse.data?.message || 'Failed to fetch sessions.', position: 'bottom-right', type: 'warning', duration: 4000 });
            return;
        }

        const modalResponse = await Ajax.post('/api/web/admin/modal', {
            template: 'login_sessions',
            sessions: sessionsResponse.data.data
        });

        if (!modalResponse.ok || !modalResponse.data) {
            const toast = new Toast();
            toast.create({ message: modalResponse.data?.message || 'Failed to open sessions modal.', position: 'bottom-right', type: 'warning', duration: 4000 });
            return;
        }

        Modal.open({
            content: modalResponse.data,
            actions: [
                {
                    label: 'Close',
                    class: 'inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 mx-4 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2',
                    onClick: Modal.close,
                },
            ],
            size: 'sm:max-w-4xl',
            classes: 'custom-modal-class',
            closeOnBackdropClick: true,
        });

        document.querySelectorAll('.revoke-login-session').forEach((button) => {
            button.addEventListener('click', async () => {
                const tokenId = button.dataset.tokenId;
                if (!tokenId) {
                    return;
                }

                button.disabled = true;
                const revokeResponse = await Ajax.post('/api/web/admin/sessions/revoke', {
                    token_id: tokenId
                });

                if (!revokeResponse.ok || !revokeResponse.data?.status) {
                    button.disabled = false;
                    const toast = new Toast();
                    toast.create({ message: revokeResponse.data?.message || 'Failed to revoke session.', position: 'bottom-right', type: 'warning', duration: 4000 });
                    return;
                }

                if (revokeResponse.data.force_logout) {
                    Auth.clearToken();
                    window.location.href = '/auth/login';
                    return;
                }

                await openLoginSessionsModal();
            });
        });

        document.querySelectorAll('.delete-login-session').forEach((button) => {
            button.addEventListener('click', async () => {
                const tokenId = button.dataset.tokenId;
                if (!tokenId) {
                    return;
                }

                button.disabled = true;
                const deleteResponse = await Ajax.post('/api/web/admin/sessions/delete', {
                    token_id: tokenId
                });

                if (!deleteResponse.ok || !deleteResponse.data?.status) {
                    button.disabled = false;
                    const toast = new Toast();
                    toast.create({ message: deleteResponse.data?.message || 'Failed to delete session entry.', position: 'bottom-right', type: 'warning', duration: 4000 });
                    return;
                }

                await openLoginSessionsModal();
            });
        });
    };

    if (manageLoginsButton) {
        manageLoginsButton.addEventListener('click', async (event) => {
            event.preventDefault();
            await openLoginSessionsModal();
        });
    }

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
        button.addEventListener('click', async () => {
            const outpassId = button.dataset.id;

            try {
                const response = await Ajax.post(`/api/web/admin/outpass/accept`, {
                    id: outpassId
                });

                if (response.ok) {
                    const data = response.data;
                    if (data.status) {
                        const hasTable = !!document.querySelector('table tbody');
                        if (!hasTable) {
                            location.reload();
                            return;
                        }
                        const tr = button.closest('tr');
                        if (tr) {
                            const table = tr.closest('table');
                            const dataRows = table.querySelectorAll('tbody tr');

                            if (dataRows.length <= 1) {
                                location.reload();
                            } else {
                                tr.remove();
                            }
                        } else {
                            location.reload();
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
                                        const rollNo = document.getElementById('roll-no').value;
                                        const year = document.getElementById('year').value;
                                        const roomNo = document.getElementById('room-no').value;
                                        const hostelNo = document.getElementById('hostel-no').value;
                                        const studentNo = document.getElementById('student-no').value;
                                        const parentNo = document.getElementById('parent-no').value;
                                        const programId = document.getElementById('program-id').value;
                                        const academicYearId = document.getElementById('academic-year-id').value;

                                        event.target.disabled = true;
                                        event.target.textContent = 'Adding Student...';

                                        if (studentName && email && rollNo && year && roomNo && hostelNo && studentNo && parentNo && programId && academicYearId) {
                                            if (studentNo === parentNo) {
                                                alert("Student number and Parent number must not be the same.");
                                                event.target.textContent = 'Add Student';
                                                event.target.disabled = false;
                                                return;
                                            }

                                            try {
                                                const response = await Ajax.post('/api/web/admin/students/create', {
                                                    name: studentName, email,
                                                    roll_no: rollNo, year, room_no: roomNo,
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

    // Edit Student modal
    const editStudentButtons = document.querySelectorAll('.edit-student-modal');
    editStudentButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const student = {
                id: button.dataset.id,
                name: button.dataset.name,
                email: button.dataset.email,
                roll_no: button.dataset.rollNo,
                year: button.dataset.year,
                room_no: button.dataset.roomNo,
                hostel_id: button.dataset.hostelId,
                student_no: button.dataset.studentNo,
                parent_no: button.dataset.parentNo,
                program_id: button.dataset.programId,
                academic_year_id: button.dataset.academicYearId,
                status: button.dataset.status,
            };

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
                        template: "edit_student",
                        hostels: hostelsData,
                        programs: programData,
                        academic_years: academicYearsData,
                        student: student
                    });

                    if (response.ok && response.data) {
                        Modal.open({
                            content: response.data,
                            actions: [
                                {
                                    label: 'Update Student',
                                    class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                                    onClick: async (event) => {
                                        const studentId = document.getElementById('student-id').value;
                                        const studentName = document.getElementById('student-name').value;
                                        const email = document.getElementById('email').value;
                                        const rollNo = document.getElementById('roll-no').value;
                                        const year = document.getElementById('year').value;
                                        const roomNo = document.getElementById('room-no').value;
                                        const hostelNo = document.getElementById('hostel-no').value;
                                        const studentNo = document.getElementById('student-no').value;
                                        const parentNo = document.getElementById('parent-no').value;
                                        const programId = document.getElementById('program-id').value;
                                        const academicYearId = document.getElementById('academic-year-id').value;
                                        const status = document.getElementById('status').value;

                                        event.target.disabled = true;
                                        event.target.textContent = 'Updating Student...';

                                        if (studentName && email && rollNo && year && roomNo && hostelNo && studentNo && parentNo && programId && academicYearId) {
                                            if (!isValidEmail(email)) {
                                                alert('Please enter a valid email address.');
                                                event.target.textContent = 'Update Student';
                                                event.target.disabled = false;
                                                return;
                                            }
                                            if (studentNo === parentNo) {
                                                alert("Student number and Parent number must not be the same.");
                                                event.target.textContent = 'Update Student';
                                                event.target.disabled = false;
                                                return;
                                            }

                                            try {
                                                const response = await Ajax.post('/api/web/admin/students/update', {
                                                    student_id: studentId,
                                                    name: studentName,
                                                    email,
                                                    roll_no: rollNo,
                                                    year,
                                                    room_no: roomNo,
                                                    hostel_no: hostelNo,
                                                    contact: studentNo,
                                                    parent_no: parentNo,
                                                    program: programId,
                                                    academic_year: academicYearId,
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
                                                    event.target.textContent = 'Update Student';
                                                    event.target.disabled = false;
                                                }
                                            } catch (error) {
                                                console.error(error);
                                            } finally {
                                                Modal.close();
                                            }
                                        } else {
                                            alert('Please fill in all the required fields correctly.');
                                            event.target.textContent = 'Update Student';
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
    });

    // Delete Student modal
    const removeStudentButtons = document.querySelectorAll('.remove-student-modal');
    removeStudentButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const studentId = button.dataset.id;
            const studentName = button.dataset.name;

            try {
                const response = await Ajax.post('/api/web/admin/modal', {
                    template: "delete_student",
                    studentName: studentName
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
                                        const response = await Ajax.post(`/api/web/admin/students/remove`, {
                                            student_id: studentId
                                        });

                                        if (response.ok) {
                                            const data = response.data;
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                toastMessage = data.message || 'Failed to delete student.';
                                            }
                                        } else {
                                            toastMessage = response.data?.message || `Failed to delete student. (HTTP ${response.status})`;
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

    // Shift students across academic years
    const shiftStudentsButton = document.getElementById('shift-students-btn');
    if (shiftStudentsButton) {
        shiftStudentsButton.addEventListener('click', async (event) => {
            event.preventDefault();

            try {
                const yearsResponse = await Ajax.post('/api/web/admin/academic_years/fetch');
                if (!yearsResponse.ok) {
                    handleError(yearsResponse.status);
                    return;
                }

                const yearsPayload = yearsResponse.data;
                const academicYears = yearsPayload?.data?.academic_years ?? [];
                if (!yearsPayload?.status || !Array.isArray(academicYears) || academicYears.length === 0) {
                    const toast = new Toast();
                    toast.create({
                        message: yearsPayload?.message || 'Academic years are not available.',
                        position: "bottom-right",
                        type: "warning",
                        duration: 5000
                    });
                    return;
                }

                const activeAcademicYears = academicYears.filter((year) => Boolean(year.status));
                if (activeAcademicYears.length === 0) {
                    const toast = new Toast();
                    toast.create({
                        message: 'Create or activate an academic year before shifting students.',
                        position: "bottom-right",
                        type: "warning",
                        duration: 5000
                    });
                    return;
                }

                const modalResponse = await Ajax.post('/api/web/admin/modal', {
                    template: 'shift_students',
                    academic_years: academicYears
                });

                if (!modalResponse.ok || !modalResponse.data) {
                    const toast = new Toast();
                    toast.create({
                        message: modalResponse.data?.message || 'Failed to load shift modal.',
                        position: "bottom-right",
                        type: "error",
                        duration: 5000
                    });
                    return;
                }

                Modal.open({
                    content: modalResponse.data,
                    actions: [
                                {
                                    label: 'Shift',
                                    class: `inline-flex justify-center rounded-lg bg-emerald-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-emerald-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50`,
                                    onClick: async (actionEvent) => {
                                const academicYearId = document.getElementById('shift-academic-year')?.value;
                                const promoteCurrentYear = document.getElementById('shift-promote-current-year')?.checked ?? true;
                                const deactivateExceeded = document.getElementById('shift-deactivate-exceeded')?.checked ?? true;

                                if (!academicYearId) {
                                    const toast = new Toast();
                                    toast.create({
                                        message: 'Select an academic batch.',
                                        position: "bottom-right",
                                        type: "warning",
                                        duration: 5000
                                    });
                                    return;
                                }

                                const button = actionEvent.target;
                                const originalHtml = button.innerHTML;
                                button.disabled = true;
                                button.innerHTML = `<span class="flex items-center"><i class="fa-solid fa-spinner fa-spin w-4 h-4 mr-1"></i>Shifting</span>`;

                                try {
                                    const shiftResponse = await Ajax.post('/api/web/admin/students/shift', {
                                        academic_year_id: academicYearId,
                                        promote_current_year: promoteCurrentYear ? 1 : 0,
                                        deactivate_exceeded: deactivateExceeded ? 1 : 0
                                    });

                                    if (!shiftResponse.ok || !shiftResponse.data?.status) {
                                        const toast = new Toast();
                                        toast.create({
                                            message: shiftResponse.data?.message || 'Failed to shift students.',
                                            position: "bottom-right",
                                            type: "error",
                                            duration: 5000
                                        });
                                        return;
                                    }

                                    const summary = shiftResponse.data?.data ?? {};
                                    Modal.close();

                                    const shifted = Number(summary.shifted_students ?? 0);
                                    const promoted = Number(summary.promoted_students ?? 0);
                                    const exceeded = Number(summary.exceeded_students ?? 0);
                                    const deactivated = Number(summary.deactivated_students ?? 0);
                                    const batchDeactivated = Boolean(summary.academic_year_deactivated ?? false);
                                    const messageParts = [
                                        `Year update completed for ${shifted} student${shifted === 1 ? '' : 's'}.`
                                    ];

                                    if (promoted > 0) {
                                        messageParts.push(`${promoted} moved to the next year.`);
                                    }

                                    if (exceeded > 0) {
                                        messageParts.push(`${exceeded} reached the course year limit.`);
                                    }

                                    if (deactivated > 0) {
                                        messageParts.push(`${deactivated} marked as inactive.`);
                                    }

                                    if (batchDeactivated) {
                                        messageParts.push('Academic batch marked inactive because no active students remain.');
                                    }

                                    const toast = new Toast();
                                    toast.create({
                                        message: messageParts.join(' '),
                                        position: "bottom-right",
                                        type: "success",
                                        duration: 8000
                                    });
                                } catch (error) {
                                    console.error(error);
                                    const toast = new Toast();
                                    toast.create({
                                        message: 'An error occurred while shifting students.',
                                        position: "bottom-right",
                                        type: "error",
                                        duration: 5000
                                    });
                                } finally {
                                    button.disabled = false;
                                    button.innerHTML = originalHtml;
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
                    classes: 'focus:outline-none focus:ring-0 focus:border-transparent',
                    closeOnBackdropClick: false,
                });
            } catch (error) {
                console.error('Failed to open shift modal:', error);
            }
        });
    }

    // Import students data
    const importStudentsButton = document.getElementById('import-btn');
    if (importStudentsButton) {
        importStudentsButton.addEventListener('click', async (event) => {
            event.stopPropagation();

            try {
                const hostelsResponse = await Ajax.post('/api/web/admin/hostels/fetch');
                if (!hostelsResponse.ok) {
                    handleError(hostelsResponse.status);
                    return;
                }

                const hostelsPayload = hostelsResponse.data;
                if (!hostelsPayload?.status || !Array.isArray(hostelsPayload?.data?.hostels) || hostelsPayload.data.hostels.length === 0) {
                    const toast = new Toast();
                    toast.create({
                        message: hostelsPayload?.message || 'Hostels have not been created. Please create at least one hostel before importing students.',
                        position: "bottom-right",
                        type: "warning",
                        duration: 5000
                    });
                    return;
                }

                const response = await Ajax.post('/api/web/admin/modal?template=import_students');

                if (response.ok && response.data) {
                    Modal.open({
                        content: response.data,
                        actions: [
                            {
                                label: 'Perform',
                                class: `inline-flex justify-center rounded-lg bg-gray-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-gray-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50`,
                                onClick: async (event) => {
                                    let toastMessage = null;
                                    let toastType = 'error';
                                    let originalHtml = event.target.innerHTML;
                                    try {
                                        const fileInput = document.getElementById('file');
                                        const file = fileInput.files[0];

                                        const button = event.target;

                                        button.disabled = true;
                                        button.innerHTML = `<span class="flex items-center"><i class="fa-solid fa-spinner fa-spin w-4 h-4 mr-1"></i>Performing</span>`;

                                        // Ensure file is selected
                                        if (!file) {
                                            toastMessage = 'Please select a CSV file.';
                                            return;
                                        }

                                        const readJson = async (res) => {
                                            try {
                                                return await res.json();
                                            } catch (error) {
                                                return null;
                                            }
                                        };

                                        // Prepare the form data for submission
                                        const formData = new FormData();
                                        formData.append('file', file);

                                        // Make an Ajax request to upload the data
                                        const response = await Ajax.post('/api/web/admin/students/import', formData);

                                        if (response.ok) {
                                            const data = response.data ?? (await readJson(response));
                                            if (data.status) {
                                                location.reload();
                                            } else {
                                                toastMessage = data?.errors?.bulk_upload || data?.message || 'Students could not be created.';
                                            }
                                        } else {
                                            const errorData = response.data ?? (await readJson(response));
                                            toastMessage = errorData?.errors?.bulk_upload || errorData?.message || `An error occurred while importing students. (HTTP ${response.status})`;
                                        }
                                    } catch (error) {
                                        console.error(error);
                                        toastMessage = 'An error occurred while processing the request.';
                                    } finally {
                                        event.target.innerHTML = originalHtml;
                                        event.target.disabled = false;
                                        Modal.close();
                                        if (toastMessage) {
                                            const toast = new Toast();
                                            toast.create({ message: toastMessage, position: "bottom-right", type: toastType, duration: 5000 });
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
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const filterButton = document.getElementById('filter-button');
    const filterArea = document.getElementById('filter-area');
    if (filterButton && filterArea) {
        filterButton.addEventListener('click', () => {
            const isHidden = filterArea.classList.contains('hidden');
            filterArea.classList.toggle('hidden', !isHidden);
            filterButton.setAttribute('aria-expanded', String(isHidden));
        });
    }

    const searchInput = document.getElementById('search-records');
    const tableBody = document.getElementById('records-table-body');
    if (searchInput && tableBody) {
        let timeout = null;
        const originalRows = Array.from(tableBody.children).map(row => row.cloneNode(true));

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim();

            clearTimeout(timeout);
            timeout = setTimeout(async () => {
                if (query === '') {
                    tableBody.innerHTML = '';
                    originalRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
                    return;
                }

                try {
                    const response = await Ajax.get('/api/web/admin/outpass/search', { query });
                    if (response.ok && response.data?.status && Array.isArray(response.data.data?.data) && response.data.data.data.length > 0) {
                        renderRecordsTable(tableBody, response.data.data.data);
                    } else {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500">No results found.</td>
                            </tr>`;
                    }
                } catch (error) {
                    console.error('Error during search:', error);
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-red-500">Search failed. Please try again.</td>
                        </tr>`;
                }
            }, 400);
        });
    }

    const limitSelect = document.getElementById('students-limit');
    if (limitSelect) {
        limitSelect.addEventListener('change', () => {
            const limit = limitSelect.value;
            const params = new URLSearchParams(window.location.search);
            params.set('limit', limit);
            params.set('page', 1);
            window.location.search = params.toString();
        });
    }

    document.querySelectorAll('tr[data-href]').forEach((row) => {
        row.addEventListener('click', (event) => {
            const interactive = event.target.closest('button, a, input, select, textarea, [data-no-row-click]');
            if (interactive) {
                return;
            }
            const href = row.dataset.href;
            if (href) {
                window.location.href = href;
            }
        });
    });

    document.querySelectorAll('.copy-token-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const token = btn.dataset.token;
            if (!token) return;
            copyToClipboard(token);
        });
    });
});

function renderRecordsTable(tableBody, outpasses) {
    tableBody.innerHTML = '';

    outpasses.forEach(outpass => {
        const row = document.createElement('tr');
        row.className = 'cursor-pointer hover:bg-gray-50';
        row.onclick = () => {
            window.location.href = `/admin/outpass/records/${outpass.id}`;
        };

        const statusColor = {
            approved: 'green',
            pending: 'yellow',
            parent_pending: 'blue',
            parent_approved: 'blue',
            rejected: 'red',
            parent_denied: 'red',
            expired: 'slate',
            cancelled: 'gray',
        }[outpass.status?.toLowerCase?.()] || 'gray';

        const statusBadge = `
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${statusColor}-100 text-${statusColor}-800">
                ${formatStatusLabel(outpass.status)}
            </span>`;

        row.innerHTML = `
            <td class="px-6 py-4 text-sm text-gray-900"># ${outpass.id}</td>
            <td class="px-6 py-4 text-sm text-gray-900">${outpass.student_name}</td>
            <td class="px-6 py-4 text-sm text-gray-900">${formatStudentYear(outpass.year)}</td>
            <td class="px-6 py-4 text-sm text-gray-900">${outpass.course} ${outpass.branch}</td>
            <td class="px-6 py-4 text-sm text-gray-900">${outpass.type}</td>
            <td class="px-6 py-4 text-sm text-gray-900">${outpass.destination}</td>
            <td class="px-6 py-4 text-sm text-center text-gray-900">${statusBadge}</td>
            <td class="px-6 py-4">
                <span class="block text-sm text-gray-900">${outpass.depart_date}</span>
                <span class="block text-xs text-gray-600">${outpass.depart_time}</span>
            </td>
            <td class="px-6 py-4">
                <span class="block text-sm text-gray-900">${outpass.return_date}</span>
                <span class="block text-xs text-gray-600">${outpass.return_time}</span>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

function formatStatusLabel(status) {
    if (!status) return '';
    return status
        .toString()
        .split('_')
        .map(capitalize)
        .join(' ');
}

function formatStudentYear(year) {
    const lastDigit = year % 10;
    const lastTwoDigits = year % 100;
    let suffix;
    if (lastTwoDigits === 11 || lastTwoDigits === 12 || lastTwoDigits === 13) {
        suffix = 'th';
    } else {
        switch (lastDigit) {
            case 1:
                suffix = 'st';
                break;
            case 2:
                suffix = 'nd';
                break;
            case 3:
                suffix = 'rd';
                break;
            default:
                suffix = 'th';
        }
    }
    return year + suffix;
}

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Token copied to clipboard!');
        }).catch((err) => {
            console.error('Clipboard API failed:', err);
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
}

function fallbackCopyToClipboard(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        alert('Token copied to clipboard!');
    } catch (err) {
        console.error('Fallback copy failed:', err);
        alert('Failed to copy token.');
    }
    document.body.removeChild(textarea);
}

window.toggleDropdown = function toggleDropdown(button) {
    const originalDropdown = button.parentElement?.querySelector('.dropdown-menu');
    if (!originalDropdown) return;
    if (originalDropdown.classList.contains('hidden')) {
        document.body.appendChild(originalDropdown);
        const rect = button.getBoundingClientRect();
        originalDropdown.style.width = '16rem';
        originalDropdown.style.position = 'absolute';
        originalDropdown.style.top = `${rect.bottom + window.scrollY + 8}px`;
        originalDropdown.style.left = `${rect.left + window.scrollX + rect.width / 2 - originalDropdown.offsetWidth / 2}px`;
        originalDropdown.classList.remove('hidden');
    } else {
        originalDropdown.classList.add('hidden');
    }
};

document.addEventListener('click', (e) => {
    document.querySelectorAll('.dropdown-menu').forEach((menu) => {
        if (!menu.contains(e.target) && !e.target.closest('[onclick="toggleDropdown(this)"]')) {
            menu.classList.add('hidden');
        }
    });
});

window.handleHostelFilterChange = function handleHostelFilterChange(value) {
    const params = new URLSearchParams(window.location.search);
    params.set('page', 1);

    if (value && value !== 'default') {
        params.set('hostel', value);
    } else {
        params.delete('hostel');
    }

    const pendingSearchInput = document.getElementById('search-pending');
    const pendingDateInput = document.getElementById('filter-pending-date');

    if (pendingSearchInput && pendingSearchInput.value) {
        params.set('q', pendingSearchInput.value);
    } else {
        params.delete('q');
    }

    if (pendingDateInput && pendingDateInput.value) {
        params.set('date', pendingDateInput.value);
    } else {
        params.delete('date');
    }

    window.location.search = params.toString();
};

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

    // Records filters/search handlers
    const recordsSearchInput = document.getElementById('search-records');
    const recordsFilterSelect = document.getElementById('filter-records');
    const recordsDateInput = document.getElementById('filter-records-date');

    const applyRecordsFilters = () => {
        const params = new URLSearchParams(window.location.search);
        params.set('page', 1);

        if (recordsSearchInput && recordsSearchInput.value) {
            params.set('q', recordsSearchInput.value);
        } else {
            params.delete('q');
        }

        if (recordsFilterSelect && recordsFilterSelect.value) {
            params.set('filter', recordsFilterSelect.value);
        } else {
            params.delete('filter');
        }

        if (recordsDateInput && recordsDateInput.value) {
            params.set('date', recordsDateInput.value);
        } else {
            params.delete('date');
        }

        window.location.search = params.toString();
    };

    if (recordsFilterSelect) {
        recordsFilterSelect.addEventListener('change', applyRecordsFilters);
    }
    if (recordsDateInput) {
        recordsDateInput.addEventListener('change', applyRecordsFilters);
    }
    const recordsSearchButton = document.getElementById('search-records-button');
    if (recordsSearchButton) {
        recordsSearchButton.addEventListener('click', applyRecordsFilters);
    }

    // Pending filters/search handlers
    const pendingSearchInput = document.getElementById('search-pending');
    const pendingDateInput = document.getElementById('filter-pending-date');
    const pendingHostelSelect = document.getElementById('hostelFilter');

    const applyPendingFilters = () => {
        const hostelValue = pendingHostelSelect ? pendingHostelSelect.value : 'default';
        window.handleHostelFilterChange(hostelValue);
    };

    if (pendingDateInput) {
        pendingDateInput.addEventListener('change', applyPendingFilters);
    }
    if (pendingHostelSelect) {
        pendingHostelSelect.addEventListener('change', applyPendingFilters);
    }
    const pendingSearchButton = document.getElementById('search-pending-button');
    if (pendingSearchButton) {
        pendingSearchButton.addEventListener('click', applyPendingFilters);
    }

    // Logbook filter change handler
    const logbookSearchInput = document.getElementById('search-logbook');
    const logbookDateInput = document.getElementById('filter-logbook-date');
    const logbookActionSelect = document.getElementById('filter-logbook-action');

    const updateLogbookFilters = () => {
        const params = new URLSearchParams(window.location.search);
        params.set('page', 1);
        if (logbookSearchInput && logbookSearchInput.value) {
            params.set('q', logbookSearchInput.value);
        } else {
            params.delete('q');
        }
        if (logbookDateInput && logbookDateInput.value) {
            params.set('date', logbookDateInput.value);
        } else {
            params.delete('date');
        }
        if (logbookActionSelect && logbookActionSelect.value) {
            params.set('action', logbookActionSelect.value);
        } else {
            params.delete('action');
        }
        window.location.search = params.toString();
    };

    if (logbookDateInput) {
        logbookDateInput.addEventListener('change', updateLogbookFilters);
    }
    if (logbookActionSelect) {
        logbookActionSelect.addEventListener('change', updateLogbookFilters);
    }

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
