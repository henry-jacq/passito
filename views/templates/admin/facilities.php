<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Facilities</h2>
    <p class="mb-8 text-gray-600 text-md">Manage institutions, hostels, and related data seamlessly.</p>

    <!-- Institutions Section -->
    <section class="p-6 mb-10 bg-white rounded-lg shadow-sm select-none">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Institutions</h3>
                <p class="text-sm text-gray-600">All available institutions are listed below.</p>
            </div>
            <button class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-200 bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 add-institution-modal">
                <i class="mr-2 fas fa-plus"></i> Create Institution
            </button>
        </div>
        <?php if (empty($institutions)): ?>
            <div class="flex items-center justify-center py-8 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">No institutions found. Click the button above to create one.</p>
            <?php else: ?>
                <div class="overflow-x-auto rounded-md shadow-md">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">Institution Name</th>
                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">Institution Address</th>
                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">Institution Type</th>
                                <th class="px-6 py-3 text-sm font-semibold text-right text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($institutions as $key => $institution): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm text-gray-800"><?= $key + 1 ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-800"><?= $institution->getName() ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-600"><?= $institution->getAddress() ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-600"><?= ucfirst($institution->getType()->value) ?></td>
                                    <td class="px-6 py-3 text-right">
                                        <div class="inline-flex items-center space-x-4 text-gray-500">
                                            <button title="Edit" class="text-gray-700 hover:text-gray-800" data-id="<?= $institution->getId() ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button title="Delete" class="text-red-700 hover:text-red-800" data-id="<?= $institution->getId() ?>">
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

    <!-- Hostels Section -->
    <section class="p-6 mb-10 bg-white rounded-lg shadow-sm select-none">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Hostels</h3>
                <p class="text-sm text-gray-600">Manage all hostels effectively.</p>
            </div>
            <button class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-200 bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed add-hostel-modal" <?php if (empty($institutions)): echo ("disabled");
                                                                                                                                                                                                                                                                                                                                endif; ?>>
                <i class="mr-2 fas fa-plus"></i> Create Hostel
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
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Programs Available</h3>
                <p class="text-sm text-gray-600">Manage institution programs.</p>
            </div>
            <button
                class="inline-flex items-center px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed add-course-modal">
                <i class="mr-2 fas fa-plus" aria-hidden="true"></i> Create Course
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full text-sm bg-white">
                <thead class="bg-gray-100">
                    <tr class="text-left text-gray-600">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Program Name</th>
                        <th class="px-6 py-3 font-semibold">Course Name</th>
                        <th class="px-6 py-3 font-semibold">Short Code</th>
                        <th class="px-4 py-3 font-semibold">Duration</th>
                        <th class="px-4 py-3 font-semibold text-center">Provided By</th>
                        <th class="px-6 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-800">1</td>
                        <td class="px-6 py-3 text-gray-800">B.Tech</td>
                        <td class="px-6 py-3 text-gray-800">Information Technology</td>
                        <td class="px-6 py-3 text-gray-800">IT</td>
                        <td class="px-4 py-3 text-gray-800">4 Years</td>
                        <td class="px-4 py-3 text-center text-gray-600">SSN College of Engineering</td>
                        <td class="px-6 py-3 text-right">
                            <div class="inline-flex items-center gap-4">
                                <button
                                    title="Edit"
                                    aria-label="Edit course"
                                    class="text-gray-700 transition hover:text-gray-900"
                                    data-id="1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    title="Delete"
                                    aria-label="Delete course"
                                    class="text-red-700 transition hover:text-red-900 remove-hostel-modal"
                                    data-id="1"
                                    data-name="B.Tech IT">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-800">2</td>
                        <td class="px-6 py-3 text-gray-800">B.Tech</td>
                        <td class="px-6 py-3 text-gray-800">Chemical Engineering</td>
                        <td class="px-6 py-3 text-gray-800">CHE</td>
                        <td class="px-4 py-3 text-gray-800">4 Years</td>
                        <td class="px-4 py-3 text-center text-gray-600">SSN College of Engineering</td>
                        <td class="px-6 py-3 text-right">
                            <div class="inline-flex items-center gap-4">
                                <button
                                    title="Edit"
                                    aria-label="Edit course"
                                    class="text-gray-700 transition hover:text-gray-900"
                                    data-id="4">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    title="Delete"
                                    aria-label="Delete course"
                                    class="text-red-700 transition hover:text-red-900 remove-hostel-modal"
                                    data-id="4"
                                    data-name="B.E Mech">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

</main>