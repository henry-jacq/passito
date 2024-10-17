<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-3xl font-semibold text-gray-900 mb-6">Outpass Records</h2>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <!-- Row 1: Search Bar with Filter Button -->
        <div class="flex items-center justify-between">
            <div class="relative w-full">
                <input type="text" placeholder="Search by name or digital ID" class="border border-gray-300 rounded-lg p-2 pl-10 w-full text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                <span class="absolute left-3 top-2 text-gray-500">
                    <i class="fas fa-search"></i>
                </span>
            </div>

            <!-- Filter Button -->
            <button id="filter-button" class="ml-4 bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 focus:outline-none transition duration-200 flex items-center">
                <i class="fas fa-filter mr-1"></i>
                <span>Filter</span>
            </button>
        </div>

        <!-- Collapsible Filter Area -->
        <div id="filter-area" class="hidden transition-all duration-300 ease-in-out mt-4">
            <!-- Filter Section -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-inner mb-4 border border-gray-200">
                <!-- Outpass Filters Section -->
                <h3 class="uppercase font-semibold text-sm text-gray-600 mb-2">Outpass Filters</h3>
                <div class="flex flex-wrap gap-6 items-center mb-4">
                    <!-- Outpass Type Filter -->
                    <select class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        <option value="">Outpass Type</option>
                        <option value="weekend">Weekend</option>
                        <option value="emergency">Emergency</option>
                        <option value="medical">Medical</option>
                    </select>

                    <!-- Approval Date Filter -->
                    <input type="date" class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200" placeholder="Approval Date">

                    <!-- Status Filter -->
                    <select class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        <option value="">Status</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <!-- Student Filters Section -->
                <h3 class="uppercase font-semibold text-sm text-gray-600 mb-2">Student Filters</h3>
                <div class="flex flex-wrap gap-6 items-center mb-4">
                    <!-- Year Filter -->
                    <select class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        <option value="">Year</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>

                    <!-- Branch Filter -->
                    <select class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        <option value="">Branch</option>
                        <option value="CSE">CSE</option>
                        <option value="ECE">ECE</option>
                        <option value="ME">ME</option>
                    </select>

                    <!-- Institution Filter -->
                    <select class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        <option value="">Institution</option>
                        <option value="institution1">Institution 1</option>
                        <option value="institution2">Institution 2</option>
                    </select>

                    <!-- Hostel Filter -->
                    <select class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        <option value="">Hostel No.</option>
                        <option value="1">Hostel 1</option>
                        <option value="2">Hostel 2</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Digital ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Outpass Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Record Row Example -->
                <tr class="hover:bg-gray-50 transition duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">John Doe</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">D12345</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Weekend Outpass</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2024-10-12</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">Approved</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-indigo-600 hover:text-indigo-900 transition duration-200"><i class="fas fa-eye mr-1"></i>View</button>
                        <button class="text-red-600 hover:text-red-900 transition duration-200 ml-4"><i class="fas fa-trash-alt mr-1"></i>Delete</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Jane Smith</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">D67890</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Emergency Outpass</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2024-10-10</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600 font-semibold">Pending</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-indigo-600 hover:text-indigo-900 transition duration-200"><i class="fas fa-eye mr-1"></i>View</button>
                        <button class="text-red-600 hover:text-red-900 transition duration-200 ml-4"><i class="fas fa-trash-alt mr-1"></i>Delete</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Alex Johnson</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">D11121</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Medical Outpass</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2024-10-08</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">Rejected</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-indigo-600 hover:text-indigo-900 transition duration-200"><i class="fas fa-eye mr-1"></i>View</button>
                        <button class="text-red-600 hover:text-red-900 transition duration-200 ml-4"><i class="fas fa-trash-alt mr-1"></i>Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<script>
    // Toggle filter area visibility
    document.getElementById('filter-button').addEventListener('click', function() {
        const filterArea = document.getElementById('filter-area');
        if (filterArea.classList.contains('hidden')) {
            filterArea.classList.remove('hidden');
            filterArea.classList.add('block');
        } else {
            filterArea.classList.remove('block');
            filterArea.classList.add('hidden');
        }
    });
</script>
