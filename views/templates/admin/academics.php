<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Academics</h2>
    <p class="mb-8 text-gray-600 text-md">Manage institutions, programs and related data seamlessly.</p>

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

    <!-- Academic Years Section -->
    <section class="p-6 mb-10 bg-white rounded-lg shadow-sm select-none">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Academic Years</h3>
                <p class="text-sm text-gray-600">Manage academic years for your institution.</p>
            </div>
            <button class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-200 bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 add-academic-year-modal">
                <i class="mr-2 fas fa-plus"></i> Create Academic Year
            </button>
        </div>

        <div class="overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">Label</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">Start Date</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">End Date</th>
                        <th class="px-6 py-3 text-sm font-semibold text-right text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-800">1</td>
                        <td class="px-6 py-3 text-gray-800">2024-25</td>
                        <td class="px-6 py-3 text-gray-600">01-07-2024</td>
                        <td class="px-6 py-3 text-gray-600">30-06-2025</td>
                        <td class="px-6 py-3 text-right">
                            <div class="inline-flex items-center space-x-4 text-gray-500">
                                <button title="Edit" class="text-gray-700 hover:text-gray-800"><i class="fas fa-edit"></i></button>
                                <button title="Delete" class="text-red-700 hover:text-red-800"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-800">2</td>
                        <td class="px-6 py-3 text-gray-800">2025-26</td>
                        <td class="px-6 py-3 text-gray-600">01-07-2025</td>
                        <td class="px-6 py-3 text-gray-600">30-06-2026</td>
                        <td class="px-6 py-3 text-right">
                            <div class="inline-flex items-center space-x-4 text-gray-500">
                                <button title="Edit" class="text-gray-700 hover:text-gray-800"><i class="fas fa-edit"></i></button>
                                <button title="Delete" class="text-red-700 hover:text-red-800"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-800">3</td>
                        <td class="px-6 py-3 text-gray-800">2026-27</td>
                        <td class="px-6 py-3 text-gray-600">01-07-2026</td>
                        <td class="px-6 py-3 text-gray-600">30-06-2027</td>
                        <td class="px-6 py-3 text-right">
                            <div class="inline-flex items-center space-x-4 text-gray-500">
                                <button title="Edit" class="text-gray-700 hover:text-gray-800"><i class="fas fa-edit"></i></button>
                                <button title="Delete" class="text-red-700 hover:text-red-800"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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
                <i class="mr-2 fas fa-plus" aria-hidden="true"></i> Create Program
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full text-sm bg-white">
                <thead class="bg-gray-100">
                    <tr class="text-left text-gray-600">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Program Name</th>
                        <th class="px-6 py-3 font-semibold">Short Code</th>
                        <th class="px-6 py-3 font-semibold">Course Name</th>
                        <th class="px-4 py-3 font-semibold">Duration</th>
                        <th class="px-4 py-3 font-semibold text-center">Provided By</th>
                        <th class="px-6 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($programs as $key => $program): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-800"><?= $key + 1 ?></td>
                            <td class="px-6 py-3 text-gray-800"><?= $program->getProgramName() ?></td>
                            <td class="px-6 py-3 text-gray-800"><?= $program->getShortCode() ?></td>
                            <td class="px-6 py-3 text-gray-800"><?= $program->getCourseName() ?></td>
                            <td class="px-4 py-3 text-gray-800"><?= $program->getDuration() ?> Years</td>
                            <td class="px-4 py-3 text-center text-gray-600"><?= $program->getProvidedBy()->getName() ?></td>
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
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

</main>