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

    // Accept Outpass
    const acceptOutpassButtons = document.querySelectorAll('.accept-outpass');
    acceptOutpassButtons.forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.stopPropagation();
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
                            tr.remove();

                            if (table && table.querySelectorAll('tr').length < 2) {
                                location.reload();
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
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const outpassId = event.target.dataset.id;

            Modal.open({
                content: `
                <div class="px-3 space-y-4">
                    <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-300 pb-4">
                        Add Remarks for Rejection
                    </h3>
                    <div class="space-y-4">
                        <label for="rejection-reason" class="block text-sm font-medium text-gray-700">
                            Reason for Rejection <span class="text-gray-500 text-sm">(Optional)</span>
                        </label>
                        <textarea id="rejection-reason" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition duration-200 resize-none" rows="4" placeholder="Provide a reason for rejection..."></textarea>
                    </div>
                </div>
                `,
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
        });
    });


    // Add Student modal
    const addStudentButton = document.getElementById('add-student-modal');
    if (addStudentButton) {
        addStudentButton.addEventListener('click', async () => {
            const fetchHostels = await Ajax.post('/api/web/admin/facilities/hostels/fetch');
            let hostelOptions = '';

            if (fetchHostels.ok) {
                const hostels = fetchHostels.data;

                if (hostels.status && Array.isArray(hostels.data.hostels)) {
                    hostelOptions = hostels.data.hostels
                        .map(hostel => `<option value="${hostel.id}">${hostel.hostelName}</option>`)
                        .join('');
                } else {
                    const toast = new Toast();                    
                    toast.create({ message: hostels.message, position: "bottom-right", type: "warning", duration: 4000 });
                    return;
                }
            } else {
                handleError(fetchHostels.status);
            }

            Modal.open({
                content: `
            <div class="px-3 space-y-4">
                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3">Add New Student</h3>

                <div class="space-y-5">
                    <!-- Student Name and Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="student-name" class="block text-md font-semibold text-gray-700">Student Name</label>
                            <input type="text" id="student-name" name="student-name" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md transition duration-200" placeholder="Enter Name" required>
                        </div>
                        <div>
                            <label for="email" class="block text-md font-semibold text-gray-700">Email</label>
                            <input type="email" id="email" name="email" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200" placeholder="Enter Email" required>
                        </div>
                    </div>

                    <!-- Digital ID and Student Number -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="digital-id" class="block text-md font-semibold text-gray-700">Digital ID</label>
                            <input type="text" id="digital-id" name="digital-id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200" placeholder="Enter Digital ID" required>
                        </div>
                        <div>
                            <label for="student-no" class="block text-md font-semibold text-gray-700">Student Number</label>
                            <input type="text" id="student-no" name="student-no" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200" placeholder="Enter Student Number" required>
                        </div>
                    </div>

                    <!-- Department and Branch -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="department" class="block text-md font-semibold text-gray-700">Department</label>
                            <input type="text" id="department" name="department" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200" placeholder="Enter Department" required>
                        </div>
                        <div>
                            <label for="branch" class="block text-md font-semibold text-gray-700">Branch</label>
                            <input type="text" id="branch" name="branch" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200" placeholder="Enter Branch" required>
                        </div>
                    </div>

                    <!-- Year and Room Number -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="year" class="block text-md font-semibold text-gray-700">Year</label>
                            <input type="number" id="year" name="year" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200" placeholder="Enter Year" required>
                        </div>
                        <div>
                            <label for="room-no" class="block text-md font-semibold text-gray-700">Room Number</label>
                            <input type="text" id="room-no" name="room-no" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200" placeholder="Enter Room Number" required>
                        </div>
                    </div>

                    <!-- Hostel Number Dropdown -->
                    <div>
                        <label for="hostel-no" class="block text-md font-semibold text-gray-700">Hostel Number</label>
                        <select id="hostel-no" name="hostel-no" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200">
                            <option value="" disabled selected>Select Hostel</option>
                            ${hostelOptions}
                        </select>
                    </div>

                    <!-- Parent Number -->
                    <div>
                        <label for="parent-no" class="block text-md font-semibold text-gray-700">Parent Number</label>
                        <input type="text" id="parent-no" name="parent-no" class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md  transition duration-200" placeholder="Enter Parent Number" required>
                    </div>
                </div>
            </div>
            `,
                actions: [
                    {
                        label: 'Add Student',
                        class: `inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50`,
                        onClick: async (event) => {
                            const studentName = document.getElementById('student-name').value;
                            const email = document.getElementById('email').value;
                            const digitalId = document.getElementById('digital-id').value;
                            const department = document.getElementById('department').value;
                            const branch = document.getElementById('branch').value;
                            const year = document.getElementById('year').value;
                            const roomNo = document.getElementById('room-no').value;
                            const hostelNo = document.getElementById('hostel-no').value;
                            const studentNo = document.getElementById('student-no').value;
                            const parentNo = document.getElementById('parent-no').value;

                            // Disable button to prevent multiple clicks
                            event.target.disabled = true;
                            event.target.textContent = 'Adding Student...';

                            if (studentName && email && digitalId && department && branch && year && roomNo && hostelNo && studentNo && parentNo) {
                                try {
                                    const response = await Ajax.post('/api/web/admin/students/create', {
                                        name: studentName,
                                        email,
                                        digital_id: digitalId,
                                        department,
                                        branch,
                                        year,
                                        room_no: roomNo,
                                        hostel_no: hostelNo,
                                        contact: studentNo,
                                        parent_no: parentNo,
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
                classes: 'custom-modal-class',
                closeOnBackdropClick: false,
            });
        });
    }

});
