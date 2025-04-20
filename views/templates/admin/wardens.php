<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Wardens</h2>
    <p class="text-gray-600 text-md mb-10">
        View, update, and assign wardens to ensure efficient hostel management.
    </p>

    <!-- If no wardens showcase a info -->
    <?php if (empty($wardens)): ?>
        <div class="flex items-center justify-between gap-4 bg-blue-200/60 border-l-4 rounded-lg border-blue-800/80 text-blue-800 p-6 shadow-md leading-relaxed" role="alert">
            <div>
                <h3 class="text-lg font-semibold">No Wardens Found</h3>
                <p class="text-sm mt-1">
                    It seems there are no wardens assigned to any hostel. Please add wardens to manage hostels effectively.
                </p>
            </div>
            <button id="add-warden-modal" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-2 py-2 text-sm font-medium text-white shadow hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Warden
            </button>
        </div>
    <?php else: ?>
        <!-- Add and Filter Section -->
        <div class="flex flex-wrap justify-between items-center mb-6 space-y-4 md:space-y-0">
            <!-- Add Warden Button -->
            <button id="add-warden-modal" class="relative px-3 py-2 rounded-lg border border-gray-300 hover:bg-gray-200 shadow-sm transition duration-300 hover:border-gray-500">
                <span class="absolute inset-0 w-full h-full rounded-lg transition duration-300 hover:bg-gray-100"></span>
                <span class="relative flex items-center text-gray-700 font-medium">
                    <i class="fa-solid fa-user-plus fa-sm mr-2"></i>
                    Add Warden
                </span>
            </button>

            <!-- Filters -->
            <div class="flex items-center space-x-4">
                <select class="bg-gray-100 border border-gray-300 hover:border-gray-500 text-md rounded-md py-2 pe-8 hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Hostels</option>
                    <option value="hostelA">Hostel A</option>
                    <option value="hostelB">Hostel B</option>
                    <option value="hostelC">Hostel C</option>
                </select>
                <button class="bg-gray-100 border border-gray-300 hover:border-gray-500 text-md rounded-md py-2 px-4 hover:bg-gray-200 transition duration-200">
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
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 text-center">Hostel Assigned</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 text-center">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wardens as $warden): ?>
                        <!-- Example Warden Row -->
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $warden->getName() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $warden->getEmail() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $warden->getContactNo() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-center">
                                <?= implode(', ', array_map(
                                    fn($hostel) => htmlspecialchars($hostel->getName()),
                                    $warden->getHostels()->toArray()
                                )) ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <button class="text-indigo-600 hover:text-indigo-800 transition duration-200 mr-4 edit-warden-modal" data-id="<?= $warden->getId() ?>">Edit</button>
                                <button class="text-red-600 hover:text-red-800 transition duration-200 remove-warden-modal" data-wardenname="<?= $warden->getName() ?>" data-id="<?= $warden->getId() ?>">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <!-- Repeat rows as needed -->
                </tbody>
            </table>
        </section>
    <?php endif ?>

</main>