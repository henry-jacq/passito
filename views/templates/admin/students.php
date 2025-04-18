<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Students</h2>
    <p class="text-gray-600 text-md mb-8">
        Manage student details, including their hostel details, academic information, and contact details.
    </p>

    <?php if (empty($students)): ?>
        <div class="flex flex-col gap-6 bg-blue-200/60 border-l-4 rounded-lg border-blue-800/80 text-blue-800 p-6 shadow-md leading-relaxed mb-4" role="alert">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-semibold">No Students Found</h3>
                    <p class="text-sm mt-1">
                        It seems there are no student records available. Please add student records to manage them effectively.
                    </p>
                </div>
                <button id="add-student-modal" class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-2 py-2 text-sm font-medium text-white shadow hover:bg-blue-600 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Student
                </button>
            </div>
        </div>

    <?php else: ?>
        <div class="flex flex-wrap justify-between items-center mb-6 space-x-8 space-y-4 md:space-y-0">
            <!-- Search Bar -->
            <div class="flex-grow relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" placeholder="Search students..." class="w-full bg-gray-50 border border-gray-300 text-md rounded-md ps-12 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-200" />
            </div>

            <div class="flex space-x-2">
                <!-- Add Student Button -->
                <button id="add-student-modal" class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-400 transition duration-200">
                    <i class="fa-solid fa-user-plus fa-sm mr-2"></i>
                    Add Student
                </button>

                <!-- Export Button -->
                <button id="export-student-modal" class="bg-indigo-600 text-white px-3 py-2 rounded-md hover:bg-indigo-700 focus:ring focus:ring-indigo-400 transition duration-200">
                    <i class="fa-solid fa-arrow-up-from-bracket fa-sm mr-2"></i>
                    Export
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex flex-col gap-6 bg-gray-200/60 border-l-4 rounded-lg border-gray-800/80 text-gray-800 px-6 py-5 shadow-md leading-relaxed mb-4">
        <div class="flex justify-between items-center">
            <div class="space-y-2">
                <h3 class="text-base font-semibold">Upload Student Records</h3>
                <p class="text-sm mt-1">
                    Upload a CSV file to import student records. Ensure the file meets the required format.
                    <a href="#" class="text-blue-700 underline hover:text-blue-900">Download the sample template</a> to get started.
                </p>
            </div>
            <button id="import-btn" class="inline-flex items-center justify-center rounded-lg bg-gray-500 px-3 py-2 text-sm font-medium text-white shadow hover:bg-gray-600 transition duration-200">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-file-import"></i>
                    <span>Choose File</span>
                </div>
            </button>
        </div>

        <input id="import-file" type="file" class="mt-3 w-full border border-gray-300 text-sm rounded-md p-2 hidden" accept=".csv">
        <div id="import-feedback" class="mt-2 text-sm text-blue-700 hidden">Import in progress...</div>
    </div>

    <?php if (!empty($students)): ?>
        <section class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Student Name</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Hostel</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Year</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Branch</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Department</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Room No.</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Parent Contact</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $student->getUser()->getName() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $student->getHostel()->getName() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $student->getYear() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $student->getBranch() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $student->getDepartment() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $student->getRoomNo() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $student->getParentNo() ?></td>
                            <td class="px-4 py-3 text-sm">
                                <span class="text-green-600 font-medium"><?= $student->getStatus() ?></span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button class="text-gray-600 hover:text-gray-800 transition duration-200 mr-4" data-id="<?= $student->getId() ?>"><i class="fas fa-edit"></i></button>
                                <button class="text-red-600 hover:text-red-800 transition duration-200" data-id="<?= $student->getId() ?>"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="flex items-center justify-between px-4 py-2 border-t bg-gray-100">
                <p class="text-sm text-gray-600">Showing 1-10 of 50 students</p>
                <div class="flex justify-end space-x-2">
                    <button class="px-3 py-1 text-sm bg-gray-200 rounded-md hover:bg-gray-300">Previous</button>
                    <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Next</button>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>