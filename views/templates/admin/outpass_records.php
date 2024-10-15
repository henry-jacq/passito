<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800">Outpass Records</h2>

    <div class="mt-6">
        <!-- Search and Filter Section -->
        <div class="flex items-center justify-between mb-4">
            <div class="relative w-1/3">
                <input type="text" placeholder="Search outpass records" class="border rounded-md p-2 w-full text-gray-600" />
                <button class="absolute right-0 top-0 mt-2 mr-2 text-gray-500">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div>
                <button class="bg-indigo-600 text-white rounded-md px-4 py-2 transition duration-200 hover:bg-indigo-700">
                    Add New Outpass
                </button>
            </div>
        </div>

        <!-- Records Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outpass Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">John Doe</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Weekend Outpass</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-10-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">Approved</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900">View</button>
                            <button class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Jane Smith</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Emergency Outpass</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-10-16</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">Pending</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900">View</button>
                            <button class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
    </div>
</main>
