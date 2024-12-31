<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-3xl font-semibold text-gray-900">Settings</h2>

    <!-- Profile Settings Section -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Profile Settings</h3>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="fullname" class="block text-gray-700 font-medium">Full Name</label>
                    <input type="text" id="fullname" class="mt-2 border-gray-300 rounded-lg p-3 w-full text-gray-700 focus:ring-indigo-500" placeholder="Enter your fullname" value="<?= $user->getName() ?>">
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Email Address</label>
                    <input type="email" id="email" class="mt-2 border-gray-300 rounded-lg p-3 w-full text-gray-700 focus:ring-indigo-500" placeholder="Enter your email" value="<?= $user->getEmail() ?>">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mt-6">
                <div>
                    <label for="contact" class="block text-gray-700 font-medium">Contact Info</label>
                    <input type="tel" id="contact" class="mt-2 border-gray-300 rounded-lg p-3 w-full text-gray-700 focus:ring-indigo-500" placeholder="Enter your contact number" value="<?= $user->getContactNo() ?>">
                </div>
                <div>
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <input type="password" id="password" class="mt-2 border-gray-300 rounded-lg p-3 w-full text-gray-700 focus:ring-indigo-500" placeholder="Enter your password">
                </div>
            </div>
            <button class="mt-6 bg-indigo-600 text-white rounded-md px-6 py-3 flex items-center justify-center space-x-2 hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-save"></i>
                <span>Update Profile</span>
            </button>
        </div>
    </div>

    <!-- Manage Institutions Section -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Manage Institutions</h3>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-gray-600 mb-4">Manage institutions associated with the hostel system:</p>
            <div class="space-y-4">
                <div>
                    <label for="institution-name" class="block text-gray-700">Institution Name</label>
                    <input type="text" id="institution-name" class="mt-2 border-gray-300 rounded-md p-2 w-full text-gray-700 focus:ring-indigo-500" placeholder="Enter institution name">
                </div>
                <div>
                    <label for="institution-address" class="block text-gray-700">Institution Address</label>
                    <input type="text" id="institution-address" class="mt-2 border-gray-300 rounded-md p-2 w-full text-gray-700 focus:ring-indigo-500" placeholder="Enter institution address">
                </div>
            </div>
            <button class="mt-6 bg-indigo-600 text-white rounded-md px-6 py-3 hover:bg-indigo-700 transition duration-200">
                Add Institution
            </button>

            <div class="mt-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Current Institutions</h4>
                <ul class="list-disc pl-4 text-gray-600">
                    <li>Institution 1 - <span class="text-sm text-indigo-600 hover:underline cursor-pointer">Edit</span></li>
                    <li>Institution 2 - <span class="text-sm text-indigo-600 hover:underline cursor-pointer">Edit</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Manage Hostels Section -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Manage Hostels</h3>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-gray-600 mb-4">Manage hostels associated with the institutions:</p>
            <div class="space-y-4">
                <div>
                    <label for="institution-hostel" class="block text-gray-700">Select Institution</label>
                    <select id="institution-hostel" class="mt-2 border-gray-300 rounded-md p-2 w-full text-gray-700 focus:ring-indigo-500">
                        <option value="">Select an institution</option>
                        <option value="institution1">Institution 1</option>
                        <option value="institution2">Institution 2</option>
                    </select>
                </div>
                <div>
                    <label for="hostel-name" class="block text-gray-700">Hostel Name</label>
                    <input type="text" id="hostel-name" class="mt-2 border-gray-300 rounded-md p-2 w-full text-gray-700 focus:ring-indigo-500" placeholder="Enter hostel name">
                </div>
                <div>
                    <label for="hostel-type" class="block text-gray-700">Hostel Type</label>
                    <select id="hostel-type" class="mt-2 border-gray-300 rounded-md p-2 w-full text-gray-700 focus:ring-indigo-500">
                        <option value="gents">Gents</option>
                        <option value="ladies">Ladies</option>
                    </select>
                </div>

                <!-- Ladies Hostel Security Options -->
                <div id="ladies-hostel-security" class="hidden mt-6">
                    <h4 class="text-md font-semibold text-gray-700 mb-2">Ladies Hostel Security</h4>
                    <div class="flex items-center space-x-4">
                        <input type="checkbox" id="verification-required" class="focus:ring-indigo-500">
                        <label for="verification-required" class="text-gray-700">Require Security Verification for Outpass Issuance</label>
                    </div>
                    <div class="flex items-center space-x-4 mt-4">
                        <input type="checkbox" id="outpass-limit" class="focus:ring-indigo-500">
                        <label for="outpass-limit" class="text-gray-700">Limit Number of Active Outpasses</label>
                    </div>
                </div>
            </div>
            <button class="mt-6 bg-indigo-600 text-white rounded-md px-6 py-3 hover:bg-indigo-700 transition duration-200">
                Add Hostel
            </button>
        </div>
    </div>

    <!-- Transfer Ownership Section (Super Admin only) -->
    <div class="mt-10">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Transfer Ownership</h3>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-gray-600 mb-4">Transfer ownership of the application to another super admin:</p>
            <div class="space-y-4">
                <div>
                    <label for="new-owner-email" class="block text-gray-700">New Owner's Email</label>
                    <input type="email" id="new-owner-email" class="mt-2 border-gray-300 rounded-md p-2 w-full text-gray-700 focus:ring-indigo-500" placeholder="Enter the new owner's email">
                </div>
            </div>
            <button class="mt-6 bg-indigo-600 text-white rounded-md px-6 py-3 hover:bg-indigo-700 transition duration-200">
                Transfer Ownership
            </button>
        </div>
    </div>

    <!-- Notification Preferences Section -->
    <div class="mt-10">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Notification Preferences</h3>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label for="email-notifications" class="text-gray-700">Email Notifications</label>
                    <input type="checkbox" id="email-notifications" class="toggle-checkbox focus:ring-indigo-500">
                </div>
                <div class="flex items-center justify-between">
                    <label for="sms-notifications" class="text-gray-700">SMS Notifications</label>
                    <input type="checkbox" id="sms-notifications" class="toggle-checkbox focus:ring-indigo-500">
                </div>
                <div class="flex items-center justify-between">
                    <label for="push-notifications" class="text-gray-700">Push Notifications</label>
                    <input type="checkbox" id="push-notifications" class="toggle-checkbox focus:ring-indigo-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Devices Section -->
    <div class="mt-10">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Manage Devices</h3>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-gray-700">Currently logged in devices:</p>
            <ul class="mt-4 text-gray-600 space-y-2">
                <li>Device 1 - Last active: 2024-10-10</li>
                <li>Device 2 - Last active: 2024-10-12</li>
            </ul>
            <button class="mt-6 text-indigo-600 font-medium hover:underline">
                Logout from all devices
            </button>
        </div>
    </div>

    <!-- Account Activity Log Section -->
    <div class="mt-10">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Account Activity Log</h3>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-gray-700">Recent account activity:</p>
            <ul class="mt-4 text-gray-600 space-y-2">
                <li>Login - 2024-10-10 10:00 AM</li>
                <li>Outpass approval - 2024-10-09 2:00 PM</li>
                <li>Password update - 2024-10-08 11:30 AM</li>
            </ul>
        </div>
    </div>

</main>

<!-- Include the necessary script to toggle ladies hostel security section based on hostel type -->
<script>
    document.getElementById('hostel-type').addEventListener('change', function () {
        const ladiesHostelSecurity = document.getElementById('ladies-hostel-security');
        if (this.value === 'ladies') {
            ladiesHostelSecurity.classList.remove('hidden');
        } else {
            ladiesHostelSecurity.classList.add('hidden');
        }
    });
</script>
