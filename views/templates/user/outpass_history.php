<div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container mx-auto p-6 space-y-8">
        <!-- Page Title -->
        <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-4 border-b space-y-2 sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Outpass History</h1>
                <p class="text-base text-gray-500 mt-1">Manage your outpass requests history.</p>
            </div>
            <!-- Search Bar -->
            <form action="" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" placeholder="Search by Outpass ID or Date"
                    class="w-full sm:w-64 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none">
                    Search
                </button>
            </form>
        </header>

        <!-- Status Table Section -->
        <section class="bg-white rounded-lg shadow p-8">
            <h2 class="text-lg font-medium text-gray-700 mb-4">Older Requests</h2>

            <!-- Status Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="bg-purple-100 text-gray-700 uppercase text-xs font-medium">
                            <th class="py-3 px-4">Outpass ID</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">Purpose</th>
                            <th class="py-3 px-4">Dates</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Dummy Data Rows -->
                        <tr class="hover:bg-purple-50">
                            <td class="py-3 px-4">12345</td>
                            <td class="py-3 px-4">Short Leave</td>
                            <td class="py-3 px-4">Family Visit</td>
                            <td class="py-3 px-4">
                                <span class="block text-sm text-gray-800">23 Dec, 2024 - 24 Dec, 2024</span>
                                <span class="block text-xs text-gray-500">10:00 AM to 6:00 PM</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Approved</span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="#" class="text-purple-600 hover:underline">View Details</a>
                            </td>
                        </tr>
                        <tr class="hover:bg-purple-50">
                            <td class="py-3 px-4">12346</td>
                            <td class="py-3 px-4">Outstation</td>
                            <td class="py-3 px-4">Personal Work</td>
                            <td class="py-3 px-4">
                                <span class="block text-sm text-gray-800">25 Dec, 2024 - 27 Dec, 2024</span>
                                <span class="block text-xs text-gray-500">8:00 AM to 8:00 PM</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="#" class="text-purple-600 hover:underline">View Details</a>
                            </td>
                        </tr>
                        <tr class="hover:bg-purple-50">
                            <td class="py-3 px-4">12347</td>
                            <td class="py-3 px-4">Short Leave</td>
                            <td class="py-3 px-4">Doctor Appointment</td>
                            <td class="py-3 px-4">
                                <span class="block text-sm text-gray-800">22 Dec, 2024</span>
                                <span class="block text-xs text-gray-500">2:00 PM to 4:00 PM</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Rejected</span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="#" class="text-purple-600 hover:underline">View Details</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center space-x-4">
                <button class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300">
                    Previous
                </button>
                <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Next
                </button>
            </div>
        </section>
    </main>
</div>
