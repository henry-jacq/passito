<main class="flex-1 p-8 mt-20 overflow-y-auto bg-gray-50">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Pending Outpass Requests</h2>

    <div class="mt-6">
        <!-- Requests Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Outpass Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Attachments</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">John Doe</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Weekend</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-10-12</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Family Function</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="#" class="inline-flex items-center text-indigo-600 hover:underline">
                                <i class="fas fa-paperclip mr-1"></i>View Attachments
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button class="inline-flex items-center bg-green-500 text-white font-semibold rounded-md px-3 py-1.5 transition duration-200 hover:bg-green-600 focus:ring focus:ring-green-300 focus:outline-none">
                                Approve
                            </button>
                            <button class="ml-2 inline-flex items-center bg-red-500 text-white font-semibold rounded-md px-3 py-1.5 transition duration-200 hover:bg-red-600 focus:ring focus:ring-red-300 focus:outline-none">
                                Reject
                            </button>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">Jane Smith</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Medical</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-10-11</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Medical Appointment</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="#" class="inline-flex items-center text-indigo-600 hover:underline">
                                <i class="fas fa-paperclip mr-1"></i>View Attachments
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button class="inline-flex items-center bg-green-500 text-white font-semibold rounded-md px-3 py-1.5 transition duration-200 hover:bg-green-600 focus:ring focus:ring-green-300 focus:outline-none">
                                Approve
                            </button>
                            <button class="ml-2 inline-flex items-center bg-red-500 text-white font-semibold rounded-md px-3 py-1.5 transition duration-200 hover:bg-red-600 focus:ring focus:ring-red-300 focus:outline-none">
                                Reject
                            </button>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="flex justify-between items-center p-4 bg-white border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    Showing <span class="font-semibold">1</span> to <span class="font-semibold">2</span> of <span class="font-semibold">10</span> results
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-300 focus:outline-none">Previous</button>
                    <button class="px-3 py-1 rounded-md bg-indigo-500 text-white text-sm font-medium hover:bg-indigo-600 focus:outline-none">Next</button>
                </div>
            </div>
        </div>
    </div>
</main>
