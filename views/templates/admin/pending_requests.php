<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800">Pending Outpass Requests</h2>

    <div class="mt-6">
        <!-- Requests Table -->
        <div class="bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">John Doe</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2024-10-12</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Family Function</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">Pending</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <button class="bg-green-600 text-white rounded-md px-2 py-1 transition duration-200 hover:bg-green-700">Approve</button>
                            <button class="bg-red-600 text-white rounded-md px-2 py-1 transition duration-200 hover:bg-red-700">Reject</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Jane Smith</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2024-10-11</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Medical Appointment</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">Pending</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <button class="bg-green-600 text-white rounded-md px-2 py-1 transition duration-200 hover:bg-green-700">Approve</button>
                            <button class="bg-red-600 text-white rounded-md px-2 py-1 transition duration-200 hover:bg-red-700">Reject</button>
                        </td>
                    </tr>
                    <!-- Add more requests as needed -->
                </tbody>
            </table>
        </div>
    </div>
</main>
