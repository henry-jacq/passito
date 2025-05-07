<?php

use App\Enum\UserRole; ?>
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="mb-4 text-2xl font-semibold text-gray-800">Settings</h2>
    <p class="mb-8 text-gray-600 text-md">Manage your account details and customize your Passito preferences.</p>

    <!-- Profile Section -->
    <section class="p-6 mb-8 bg-white rounded-lg shadow-md">
        <h3 class="mb-4 text-lg font-semibold text-gray-800">Profile</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" value="<?= $user->getName() ?>" class="w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border-gray-300 rounded-lg" readonly>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" value="<?= $user->getEmail() ?>" class="w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border-gray-300 rounded-lg" readonly>
            </div>
            <div>
                <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" id="contact_no" value="<?= $user->getContactNo() ?>" class="w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border-gray-300 rounded-lg" readonly>
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <input type="text" id="role" value="<?= ucwords(str_replace('_', ' ', $user->getRole()->value)) ?>" class="w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border-gray-300 rounded-lg" readonly>
            </div>
        </div>
    </section>

    <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
        <!-- Transfer Ownership -->
        <section class="p-6 mb-8 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-800">Transfer Ownership</h3>
                    <p class="text-sm text-gray-600">
                        Transfer super admin access to another user if the chief warden of the hostels is changing.
                    </p>
                </div>
                <button class="px-4 py-2 text-sm font-medium text-white transition bg-indigo-600 rounded-md shadow-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Transfer Ownership
                </button>
            </div>
        </section>
    <?php endif; ?>

    <!-- Manage Logins / Devices -->
    <section class="p-6 mb-8 bg-white rounded-lg shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="mb-2 text-lg font-semibold text-gray-800">Manage Logins / Devices</h3>
                <p class="text-sm text-gray-600">
                    View and manage active logins or connected devices.
                </p>
            </div>
            <button class="px-4 py-2 text-sm font-medium text-white transition bg-indigo-600 rounded-md shadow-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Manage Logins
            </button>
        </div>
    </section>

</main>

<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('maintenanceToggle');

        toggle.addEventListener('change', async () => {
            const isEnabled = toggle.checked;

            try {
                const response = await fetch('/admin/maintenance-mode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // Optional: if your backend checks for AJAX
                    },
                    body: JSON.stringify({
                        enabled: isEnabled
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    alert(`Maintenance mode ${isEnabled ? 'enabled' : 'disabled'} successfully.`);
                } else {
                    console.error(result);
                    alert('Failed to update maintenance mode.');
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred while updating maintenance mode.');
            }
        });
    });
</script> -->