<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800">Settings</h2>

    <!-- Profile Settings Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Profile Settings</h3>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="mb-4">
                <label for="username" class="block text-gray-600">Username</label>
                <input type="text" id="username" class="border rounded-md p-2 w-full text-gray-600" placeholder="Enter your username">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-600">Email Address</label>
                <input type="email" id="email" class="border rounded-md p-2 w-full text-gray-600" placeholder="Enter your email">
            </div>
            <div class="mb-4">
                <label for="contact" class="block text-gray-600">Contact Info</label>
                <input type="tel" id="contact" class="border rounded-md p-2 w-full text-gray-600" placeholder="Enter your contact number">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-600">Password</label>
                <input type="password" id="password" class="border rounded-md p-2 w-full text-gray-600" placeholder="Enter your password">
            </div>
            <button class="mt-4 bg-indigo-600 text-white rounded-md px-4 py-2 transition duration-200 hover:bg-indigo-700">
                Update Profile
            </button>
        </div>
    </div>

    <!-- Notification Preferences Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Notification Preferences</h3>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <label for="email-notifications" class="text-gray-600">Email Notifications</label>
                <input type="checkbox" id="email-notifications" class="toggle-checkbox">
            </div>
            <div class="flex items-center justify-between mb-4">
                <label for="sms-notifications" class="text-gray-600">SMS Notifications</label>
                <input type="checkbox" id="sms-notifications" class="toggle-checkbox">
            </div>
            <div class="flex items-center justify-between mb-4">
                <label for="push-notifications" class="text-gray-600">Push Notifications</label>
                <input type="checkbox" id="push-notifications" class="toggle-checkbox">
            </div>
        </div>
    </div>

    <!-- Manage Devices Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Manage Devices</h3>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-600">Currently logged in devices:</p>
            <ul class="mt-2 text-gray-600">
                <li>Device 1 - Last active: 2024-10-10</li>
                <li>Device 2 - Last active: 2024-10-12</li>
            </ul>
            <button class="mt-4 text-indigo-600 hover:underline">Logout from all devices</button>
        </div>
    </div>

    <!-- Account Activity Log Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Account Activity Log</h3>
        <div class="bg-white p-4 rounded-lg shadow">
            <ul class="text-gray-600">
                <li>2024-10-10: Password changed</li>
                <li>2024-10-11: Email updated</li>
                <li>2024-10-12: Profile updated</li>
            </ul>
        </div>
    </div>

    <!-- System Settings Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">System Settings</h3>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="mb-4">
                <label for="outpass-limit" class="block text-gray-600">Outpass Approval Limits</label>
                <input type="number" id="outpass-limit" class="border rounded-md p-2 w-full text-gray-600" placeholder="Enter outpass approval limit">
            </div>
            <div class="mb-4">
                <label for="auto-approval" class="block text-gray-600">Auto-Approvals</label>
                <select id="auto-approval" class="border rounded-md p-2 text-gray-600">
                    <option value="none">None</option>
                    <option value="criteria1">Criteria 1</option>
                    <option value="criteria2">Criteria 2</option>
                </select>
            </div>
            <button class="mt-4 bg-indigo-600 text-white rounded-md px-4 py-2 transition duration-200 hover:bg-indigo-700">
                Save System Settings
            </button>
        </div>
    </div>

    <!-- Hostel Block Settings Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Hostel Block Settings</h3>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-600">Manage settings related to hostel blocks here.</p>
            <button class="mt-4 bg-indigo-600 text-white rounded-md px-4 py-2 transition duration-200 hover:bg-indigo-700">
                Manage Blocks
            </button>
        </div>
    </div>
</main>

<script>
    // Toggle switch styling (if necessary)
    const toggles = document.querySelectorAll('.toggle-checkbox');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                toggle.parentElement.classList.add('bg-indigo-100');
            } else {
                toggle.parentElement.classList.remove('bg-indigo-100');
            }
        });
    });
</script>
