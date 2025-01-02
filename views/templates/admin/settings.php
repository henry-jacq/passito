<?php use App\Enum\UserRole; ?>
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Settings</h2>
    <p class="text-gray-600 text-md mb-8">Manage your account details and customize your Passito preferences.</p>

    <!-- Profile Section -->
    <section class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Profile</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" value="<?= $user->getName() ?>" class="w-full mt-1 rounded-lg border-gray-300 bg-gray-100 px-4 py-2 text-gray-700" readonly>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" value="<?= $user->getEmail() ?>" class="w-full mt-1 rounded-lg border-gray-300 bg-gray-100 px-4 py-2 text-gray-700" readonly>
            </div>
            <div>
                <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" id="contact_no" value="<?= $user->getContactNo() ?>" class="w-full mt-1 rounded-lg border-gray-300 bg-gray-100 px-4 py-2 text-gray-700" readonly>
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <input type="text" id="role" value="<?= ucwords(str_replace('_', ' ', $user->getRole()->value)) ?>" class="w-full mt-1 rounded-lg border-gray-300 bg-gray-100 px-4 py-2 text-gray-700" readonly>
            </div>
        </div>
    </section>

    <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
    <!-- Transfer Ownership -->
    <section class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Transfer Ownership</h3>
        <p class="text-sm text-gray-600 mb-4">Transfer ownership to another user. Ensure the recipient has the necessary permissions.</p>
        <button class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
            Transfer Ownership
        </button>
    </section>
    <?php endif; ?>

    <!-- Manage Logins / Devices -->
    <section class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Manage Logins / Devices</h3>
        <p class="text-sm text-gray-600 mb-4">View and manage active logins or connected devices.</p>
        <button class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
            Manage Logins / Devices
        </button>
    </section>
</main>
