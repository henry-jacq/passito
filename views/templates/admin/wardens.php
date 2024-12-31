<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Wardens</h2>
    <p class="text-gray-600 text-md mb-10">
        View, update, and assign wardens to ensure efficient hostel management.
    </p>

    <!-- Add and Filter Section -->
    <div class="flex flex-wrap justify-between items-center mb-6 space-y-4 md:space-y-0">
        <!-- Add Warden Button -->
        <button id="open-add-warden-modal" class="relative px-3 py-2 rounded-lg border border-gray-300 hover:bg-gray-200 shadow-sm transition duration-300 hover:border-gray-500">
            <span class="absolute inset-0 w-full h-full rounded-lg transition duration-300 hover:bg-gray-100"></span>
            <span class="relative flex items-center text-gray-700 font-medium">
                <i class="fa-solid fa-user-plus fa-sm mr-2"></i>
                Add Warden
            </span>
        </button>

        <!-- Filters -->
        <div class="flex items-center space-x-4">
            <select
                class="bg-gray-100 border border-gray-300 hover:border-gray-500 text-md rounded-md py-2 pe-8 hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <option value="">All Hostels</option>
                <option value="hostelA">Hostel A</option>
                <option value="hostelB">Hostel B</option>
                <option value="hostelC">Hostel C</option>
            </select>
            <button
                class="bg-gray-100 border border-gray-300 hover:border-gray-500 text-md rounded-md py-2 px-4 hover:bg-gray-200 transition duration-200"
            >
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Warden Table -->
    <section class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Warden Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Contact No.</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Hostel Assigned</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Warden Row -->
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700">John Doe</td>
                    <td class="px-4 py-3 text-sm text-gray-700">john.doe@example.com</td>
                    <td class="px-4 py-3 text-sm text-gray-700">9876543210</td>
                    <td class="px-4 py-3 text-sm text-gray-700">Hostel A</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 text-xs font-medium text-white bg-green-500 rounded-md">
                            Active
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <button class="text-indigo-600 hover:text-indigo-800 transition duration-200 mr-4">Edit</button>
                        <button class="text-red-600 hover:text-red-800 transition duration-200">Remove</button>
                    </td>
                </tr>
                <!-- Repeat rows as needed -->
            </tbody>
        </table>
    </section>
</main>
