<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Passito Settings</h2>
    <p class="text-gray-600 text-md mb-8">Manage institutions, hostels, and related data seamlessly.</p>

    <!-- Institutions Section -->
    <section class="bg-white shadow-sm rounded-lg p-6 mb-10">
        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Institutions</h3>
                <p class="text-sm text-gray-600">All available institutions are listed below.</p>
            </div>
            <button
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                onclick="openCreateInstitutionModal()"
            >
                <i class="fas fa-plus mr-2"></i> Create Institution
            </button>
        </div>
        <div class="overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left text-sm font-semibold text-gray-600 py-3 px-6">#</th>
                        <th class="text-left text-sm font-semibold text-gray-600 py-3 px-6">Institution Name</th>
                        <th class="text-left text-sm font-semibold text-gray-600 py-3 px-6">Institution Address</th>
                        <th class="text-left text-sm font-semibold text-gray-600 py-3 px-6">Institution Type</th>
                        <th class="text-right text-sm font-semibold text-gray-600 py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-6 text-sm text-gray-800">1</td>
                        <td class="py-3 px-6 text-sm text-gray-800">ABC College of Engineering</td>
                        <td class="py-3 px-6 text-sm text-gray-600">Anna Nagar, Chennai - 603104</td>
                        <td class="py-3 px-6 text-sm text-gray-600">College</td>
                        <td class="py-3 px-6 text-right">
                            <div class="inline-flex items-center space-x-4 text-gray-500">
                                <button title="Edit" class="text-gray-700 hover:text-gray-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button title="Delete" class="text-red-700 hover:text-red-800">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Repeat for more institutions -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- Hostels Section -->
    <section class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Hostels</h3>
                <p class="text-sm text-gray-600">Manage all hostels effectively.</p>
            </div>
            <button
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                onclick="openCreateHostelModal()"
            >
                <i class="fas fa-plus mr-2"></i> Create Hostel
            </button>
        </div>
        <div class="overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left text-sm font-semibold text-gray-600 py-3 px-6">#</th>
                        <th class="text-left text-sm font-semibold text-gray-600 py-3 px-6">Hostel Name</th>
                        <th class="text-left text-sm font-semibold text-gray-600 py-3 px-6">Institution Name</th>
                        <th class="text-center text-sm font-semibold text-gray-600 py-3 px-6">Warden Incharge</th>
                        <th class="text-right text-sm font-semibold text-gray-600 py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-6 text-sm text-gray-800">1</td>
                        <td class="py-3 px-6 text-sm text-gray-800">Hostel - 1</td>
                        <td class="py-3 px-6 text-sm text-gray-600">ABC College of Engineering</td>
                        <td class="py-3 px-6 text-sm text-gray-600 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
                                <span class="ml-2">John Doe</span>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-right">
                            <div class="inline-flex items-center space-x-4 text-gray-500">
                                <button title="Edit" class="text-gray-700 hover:text-gray-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button title="Delete" class="text-red-700 hover:text-red-800">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Repeat for more hostels -->
                </tbody>
            </table>
        </div>
    </section>
</main>
