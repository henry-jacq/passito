<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <!-- Reports Header -->
    <section>
        <div class="mb-6 space-y-2">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Reports</h2>
            <p class="mb-10 text-gray-600 text-md">View and manage outpass reports and analytics.</p>
        </div>
    </section>

    <!-- Report Actions -->
    <section class="mb-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Create Report -->
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold text-gray-700">Create Report</h4>
                <p class="mb-3 text-sm text-gray-500">Create custom reports and widgets.</p>
                <a href="<?= $this->urlFor('admin.reports.create') ?>" class="inline-flex items-center px-3 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-plus"></i>
                    <span>Create</span>
                </a>
            </div>

            <!-- Schedule Report -->
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold text-gray-700">Schedule Report</h4>
                <p class="mb-3 text-sm text-gray-500">Set up automated reports to run at regular intervals.</p>
                <button class="flex items-center px-3 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-calendar-alt"></i>
                    <span>Schedule</span>
                </button>
            </div>

            <!-- Export CSV -->
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold text-gray-700">Export CSV</h4>
                <p class="mb-3 text-sm text-gray-500">Download the report data as a CSV file for analysis.</p>
                <button class="flex items-center px-3 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-file-csv"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Scheduled Reports Section -->
    <section class="mb-6">
        <h3 class="mb-4 text-xl font-semibold text-gray-700">Scheduled Reports</h3>
        <div class="space-y-4">
            <!-- Example Scheduled Report Card -->
            <div class="flex flex-col p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg md:flex-row md:items-center md:justify-between">
                <div class="flex-1 mb-3 md:mb-0">
                    <div class="flex items-center mb-1 space-x-2">
                        <h4 class="font-semibold text-gray-700">Daily Movement Summary</h4>
                        <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Running</span>
                    </div>
                    <p class="text-sm text-gray-500">
                        Frequency: Daily | Next Run: 2025-09-10 08:00 AM | Last Run: 2025-09-09 08:00 AM
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="flex items-center px-3 py-1 text-sm text-white transition duration-200 bg-yellow-600 rounded-lg hover:bg-yellow-700">
                        <i class="mr-2 fas fa-stop"></i>
                        <span>Stop</span>
                    </button>
                    <button class="flex items-center px-3 py-1 text-sm text-white transition duration-200 bg-red-600 rounded-lg hover:bg-red-700">
                        <i class="mr-2 fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-col p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg md:flex-row md:items-center md:justify-between">
                <div class="flex-1 mb-3 md:mb-0">
                    <div class="flex items-center mb-1 space-x-2">
                        <h4 class="font-semibold text-gray-700">Late Arrivals Report</h4>
                        <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Stopped</span>
                    </div>
                    <p class="text-sm text-gray-500">
                        Frequency: Weekly | Next Run: 2025-09-12 09:00 AM | Last Run: 2025-09-05 08:00 AM
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="flex items-center px-3 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="mr-2 fas fa-play"></i>
                        <span>Start</span>
                    </button>
                    <button class="flex items-center px-3 py-1 text-sm text-white transition duration-200 bg-red-600 rounded-lg hover:bg-red-700">
                        <i class="mr-2 fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Reports Grid -->
    <section>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Daily Movement Summary -->
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <div class="flex items-center mb-4 space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                        <i class="text-blue-600 fas fa-chart-line"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-700">Daily Movement</h4>
                        <p class="text-sm text-gray-500">Today's check-in/out summary</p>
                    </div>
                    <button class="text-sm font-medium text-blue-600 hover:text-blue-800">
                        View Timeline <i class="ml-1 fas fa-arrow-right"></i>
                    </button>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Checked-in Today</span>
                        </div>
                        <span class="px-1 text-xs font-normal text-blue-800 bg-blue-100 rounded">24</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Checked-out Today</span>
                        </div>
                        <span class="px-1 text-xs font-normal text-blue-800 bg-blue-100 rounded">16</span>
                    </div>
                </div>
            </div>

            <!-- Late Arrivals Report -->
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <div class="flex items-center mb-4 space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                        <i class="text-red-600 fas fa-clock"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-700">Late Arrivals</h4>
                        <p class="text-sm text-gray-500">Students who checked in late today</p>
                    </div>
                    <button class="text-sm font-medium text-blue-600 hover:text-blue-800">
                        View All <i class="ml-1 fas fa-arrow-right"></i>
                    </button>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-gray-600">
                                <th class="px-4 py-2 font-medium text-left">Student</th>
                                <th class="px-4 py-2 font-medium text-left">Hostel</th>
                                <th class="px-4 py-2 font-medium text-left">Late By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-gray-900">John Doe</td>
                                <td class="px-4 py-2 text-gray-600">Block A</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs text-red-800 bg-red-100 rounded">2h 15m</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-gray-900">Jane Smith</td>
                                <td class="px-4 py-2 text-gray-600">Block B</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs text-red-800 bg-red-100 rounded">1h 45m</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-gray-900">Mike Johnson</td>
                                <td class="px-4 py-2 text-gray-600">Block C</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs text-red-800 bg-red-100 rounded">45m</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
