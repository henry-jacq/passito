<!-- Manage Wardens Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Manage Wardens</h2>
    <p class="text-gray-700 text-normal mb-8">Add and assign wardens as hostel in-charges to manage hostel operations effectively.</p>

    <!-- Add Warden Button -->
    <div class="mb-6">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
            Add New Warden
        </button>
    </div>

    <!-- Warden Table -->
    <section class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Warden Name</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Hostel Assigned</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Warden Row -->
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-700">John Doe</td>
                    <td class="px-4 py-2 text-sm text-gray-700">Hostel A</td>
                    <td class="px-4 py-2 text-sm text-gray-700">john.doe@example.com</td>
                    <td class="px-4 py-2 text-sm">
                        <button class="text-indigo-600 hover:text-indigo-800 transition duration-200">Edit</button>
                        <button class="text-red-600 hover:text-red-800 transition duration-200 ml-4">Remove</button>
                    </td>
                </tr>
                <!-- Repeat above row for each warden -->
            </tbody>
        </table>
    </section>
</main>
