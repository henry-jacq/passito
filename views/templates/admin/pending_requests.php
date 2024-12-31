<!-- Pending Requests Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Pending Requests</h2>
    <p class="text-gray-600 text-md mb-8">Manage pending requests by approving, rejecting, or wiping them out.</p>

    <section class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Request ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Student Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Department</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date & Duration</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Files</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">REQ001</td>
                    <td class="px-6 py-4 text-sm text-gray-900">John Doe</td>
                    <td class="px-6 py-4 text-sm text-gray-900">Computer Science</td>
                    <td class="px-6 py-4 text-sm text-gray-900">Outpass</td>
                    <td class="px-6 py-4">
                        <span class="block text-sm text-gray-900">23 Dec, 2024 - 24 Dec, 2024</span>
                        <span class="block text-xs text-gray-600">10:00 AM to 6:00 PM</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <a href="#" class="text-indigo-500 hover:underline">
                            <i class="fa-solid fa-link"></i>
                            <span class="ml-1">Open</span>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-normal text-sm font-medium space-x-2">
                        <button class="text-green-600 hover:text-green-900 transition duration-200"><i class="fas fa-circle-check mr-1"></i>Accept</button>
                        <button class="text-red-600 hover:text-red-900 transition duration-200"><i class="fas fa-trash-alt mr-1"></i>Reject</button>
                    </td>
                </tr>
                <!-- More rows can be added here dynamically -->
            </tbody>
        </table>

        <!-- Pagination Section -->
        <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-3 sm:px-6">
            <div class="flex justify-between sm:hidden">
                <button class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</button>
                <button class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</button>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">100</span> results
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 border rounded-md bg-gray-200 text-sm text-gray-600 hover:bg-gray-300 focus:ring focus:ring-blue-300 focus:outline-none">Previous</button>
                    <button class="px-3 py-1 border rounded-md bg-blue-600 text-sm text-white hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none">Next</button>
                </div>
            </div>
        </div>
    </section>
</main>
