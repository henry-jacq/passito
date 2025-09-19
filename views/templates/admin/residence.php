<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="space-y-1">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Residence</h2>
            <p class="max-w-3xl mb-10 text-gray-600 text-md">
                Efficiently manage wardens and hostels by updating entries and assigning wardens.
            </p>
        </div>
    </div>

    <!-- Wardens Section -->
    <section class="p-6 mb-10 bg-white rounded-lg shadow-sm select-none">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Wardens</h3>
                <p class="text-sm text-gray-600">Manage all wardens effectively.</p>
            </div>
            <button id="add-warden-modal" class="inline-flex items-center px-5 py-2 text-sm font-medium text-white transition-all duration-200 ease-in-out bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
                <i class="mr-2 fa-solid fa-plus"></i> Add Warden
            </button>
        </div>
        <?php if (empty($wardens)): ?>
            <div class="flex items-center justify-center py-8 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">No wardens found. Click the button above to create one.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto rounded-md shadow-md">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Warden Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Email</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Contact No.</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Hostels Managed</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-center text-gray-600">Status</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Actions</th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($wardens as $key => $warden): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $key + 1 ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $warden->getName() ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $warden->getEmail() ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $warden->getContactNo() ?></td>
                                <td class="px-6 py-3 text-sm text-center text-gray-700">
                                    <?=
                                    empty($warden->getHostels()->toArray()) ? 'N/A' :
                                        implode(', ', array_map(
                                            fn($hostel) => htmlspecialchars($hostel->getName()),
                                            $warden->getHostels()->toArray()
                                        )) ?>
                                </td>
                                <td class="px-6 py-3 text-sm text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-center">
                                    <button class="mr-4 text-gray-600 transition duration-200 hover:text-gray-800 edit-warden-modal" data-id="<?= $warden->getId() ?>"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 transition duration-200 hover:text-red-800 remove-warden-modal" data-wardenname="<?= $warden->getName() ?>" data-id="<?= $warden->getId() ?>"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <!-- Hostels Section -->
    <section class="p-6 mb-10 bg-white rounded-lg shadow-sm select-none">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Hostels</h3>
                <p class="text-sm text-gray-600">Manage all hostels effectively.</p>
            </div>
            <button class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-200 bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed add-hostel-modal" <?php if (empty($wardens)): echo ("disabled");
                                                                                                                                                                                                                                                                                                                                endif; ?>>
                <i class="mr-2 fas fa-plus"></i> Add Hostel
            </button>
        </div>
        <?php if (empty($hostels)): ?>
            <div class="flex items-center justify-center py-8 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">No hostels found. Click the button above to create one.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto rounded-md shadow-md">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">Hostel Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Category</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Warden Incharge</th>
                            <th class="px-6 py-3 text-sm font-semibold text-right text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($hostels as $key => $hostel): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-800"><?= $key + 1 ?></td>
                                <td class="px-6 py-3 text-sm text-gray-800"><?= $hostel->getName() ?></td>
                                <td class="px-4 py-3 text-sm text-gray-800"><?= $hostel->getCategory() ?></td>
                                <td class="px-4 py-3 text-sm text-center text-gray-600">
                                    <div class="flex items-center justify-center space-x-2">
                                        <div class="w-6 h-6 bg-gray-300 rounded-full">
                                            <img class="rounded-full" src="https://ui-avatars.com/api/?name=<?= $hostel->getWarden()->getName() ?>&background=c7d2fe&color=3730a3&bold=true" alt="">
                                        </div>
                                        <span class="ml-2"><?= $hostel->getWarden()->getName() ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="inline-flex items-center space-x-4 text-gray-500">
                                        <button title="Edit" class="text-gray-700 hover:text-gray-800" data-id="<?= $hostel->getId() ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button title="Delete" class="text-red-700 hover:text-red-800 remove-hostel-modal" data-id="<?= $hostel->getId() ?>" data-name="<?= $hostel->getName() ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="p-6 bg-white rounded-lg shadow-sm">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Manage Warden Assignments</h3>
                <p class="text-sm text-gray-600">Review and manage existing assignments.</p>
            </div>
        </div>

        <form class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Warden Selection -->
            <div>
                <label for="warden" class="block mb-2 text-sm font-medium text-gray-700">Select Warden</label>
                <select id="warden" name="warden" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Choose Warden...</option>
                    <option value="warden1">Dr. A. Kumar</option>
                    <option value="warden2">Prof. B. Sharma</option>
                    <option value="warden3">Dr. C. Patel</option>
                    <option value="warden4">Prof. D. Singh</option>
                </select>
            </div>

            <!-- Assignment Type -->
            <div>
                <label for="assignment_type" class="block mb-2 text-sm font-medium text-gray-700">Assignment Type</label>
                <select id="assignment_type" name="assignment_type" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" onchange="toggleAssignmentOptions()">
                    <option value="">Select Type...</option>
                    <option value="hostel">By Hostel</option>
                    <option value="year">By Academic Year</option>
                </select>
            </div>

            <!-- Action -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-5 py-2 text-sm font-medium text-white transition-all duration-200 ease-in-out bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
                    <i class="mr-2 fas fa-plus"></i> Assign Warden
                </button>
            </div>
        </form>

        <!-- Conditional Assignment Options -->
        <div id="hostel_selection" class="hidden mt-6">
            <label for="hostels" class="block mb-2 text-sm font-medium text-gray-700">Select Hostels</label>
            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="hostels[]" value="hostel_a" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Hostel A (Boys)</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="hostels[]" value="hostel_b" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Hostel B (Boys)</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="hostels[]" value="hostel_c" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Hostel C (Girls)</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="hostels[]" value="hostel_d" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Hostel D (Girls)</span>
                </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">Select one or more hostels. Warden will handle requests from these hostels.</p>
        </div>

        <div id="year_selection" class="hidden mt-6">
            <label for="student_years" class="block mb-2 text-sm font-medium text-gray-700">Select Student Academic Years</label>
            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="student_years[]" value="1st_year" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">1st Year</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="student_years[]" value="2nd_year" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">2nd Year</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="student_years[]" value="3rd_year" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">3rd Year</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="student_years[]" value="4th_year" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">4th Year</span>
                </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">Select one or more years. Warden will handle requests from students of selected years.</p>
        </div>

        <div class="mt-6 overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Warden Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Assignment Type</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Assigned To</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm text-gray-700">1</td>
                        <td class="px-4 py-3 text-sm text-gray-700">Dr. A. Kumar</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Hostel Wise
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">Hostel A (Boys)</td>
                        <td class="px-6 py-3 text-sm text-center">
                            <button class="mr-4 text-gray-600 transition duration-200 hover:text-gray-800"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 transition duration-200 hover:text-red-800"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm text-gray-700">2</td>
                        <td class="px-4 py-3 text-sm text-gray-700">Prof. B. Sharma</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Hostel Wise
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">Hostel B (Boys)</td>
                        <td class="px-6 py-3 text-sm text-center">
                            <button class="mr-4 text-gray-600 transition duration-200 hover:text-gray-800"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 transition duration-200 hover:text-red-800"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm text-gray-700">3</td>
                        <td class="px-4 py-3 text-sm text-gray-700">Dr. C. Patel</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Academic Year
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">2025-26</td>
                        <td class="px-6 py-3 text-sm text-center">
                            <button class="mr-4 text-gray-600 transition duration-200 hover:text-gray-800"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 transition duration-200 hover:text-red-800"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm text-gray-700">6</td>
                        <td class="px-4 py-3 text-sm text-gray-700">Prof. B. Sharma</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Academic Year
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">2025-26</td>
                        <td class="px-6 py-3 text-sm text-center">
                            <button class="mr-4 text-gray-600 transition duration-200 hover:text-gray-800"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 transition duration-200 hover:text-red-800"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>

<script>
    function toggleAssignmentOptions() {
        const type = document.getElementById('assignment_type').value;
        const hostelDiv = document.getElementById('hostel_selection');
        const yearDiv = document.getElementById('year_selection');

        if (type === 'hostel') {
            hostelDiv.classList.remove('hidden');
            yearDiv.classList.add('hidden');
        } else if (type === 'year') {
            yearDiv.classList.remove('hidden');
            hostelDiv.classList.add('hidden');
        } else {
            hostelDiv.classList.add('hidden');
            yearDiv.classList.add('hidden');
        }
    }
</script>