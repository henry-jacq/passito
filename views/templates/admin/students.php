<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Students</h2>
    <p class="text-gray-600 text-md mb-8">
        Manage student details, including their hostel details, academic information, and contact details.
    </p>

    <?php if (empty($students)): ?>
    <div class="mb-6 bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex justify-between items-center">
            <div class="space-y-2">
                <h3 class="text-md font-display font-semibold text-gray-900">No students found</h3>
                <p class="text-gray-600 text-sm">
                    No student records found. Click the button to add a new student record.
                </p>
            </div>
            <button id="add-student-modal" class="bg-indigo-600 text-white px-3 py-2 rounded-md hover:bg-indigo-700 focus:ring focus:ring-indigo-400 transition duration-200">
                <i class="fa-solid fa-user-plus fa-sm mr-2"></i>
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
            <input type="text" placeholder="Search students..." class="w-full bg-gray-50 border border-gray-300 text-md rounded-md ps-12 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200"/>
        </div>

        <div class="flex space-x-2">
            <!-- Add Student Button -->
            <button id="add-student-modal" class="bg-indigo-600 text-white px-3 py-2 rounded-md hover:bg-indigo-700 focus:ring focus:ring-indigo-400 transition duration-200">
                <i class="fa-solid fa-user-plus fa-sm mr-2"></i>
                Add Student
            </button>

            <!-- Export Button -->
            <button id="export-student-modal" class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-400 transition duration-200">
                <i class="fa-solid fa-arrow-up-from-bracket fa-sm mr-2"></i>
                Export
            </button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bulk Import Section -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <div class="flex justify-between items-center">
            <div class="space-y-2">
                <h3 class="text-md font-display font-semibold text-gray-900">Upload Student Records</h3>
                <p class="text-gray-600 text-sm">
                    Upload a CSV file to import student records. Ensure the file meets the required format. 
                    <a href="#" class="text-blue-500 underline hover:text-blue-600">Download the sample template</a> to get started.
                </p>
            </div>
            <button id="import-btn" class="bg-green-600 text-white text-md px-3 py-2 rounded-md hover:bg-green-700 transition duration-200">
                <i class="fa-solid fa-file-import fa-sm mr-1"></i>
                Choose File
            </button>
        </div>

        <input id="import-file" type="file" class="mt-4 w-full border border-gray-300 text-sm rounded-md p-2 hidden" accept=".csv"/>
        <div id="import-feedback" class="mt-2 text-sm text-gray-500 hidden">Import in progress...</div>
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
                <?php foreach($students as $student): ?>
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
                        <button class="text-indigo-600 hover:text-indigo-800 transition duration-200 mr-4" data-id="<?= $student->getId() ?>">Edit</button>
                        <button class="text-red-600 hover:text-red-800 transition duration-200" data-id="<?= $student->getId() ?>">Remove</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="flex items-center justify-between px-4 py-2 border-t bg-gray-100">
            <p class="text-sm text-gray-600">Showing 1-10 of 50 students</p>
            <div class="flex justify-end space-x-2">
                <button class="px-3 py-1 text-sm bg-gray-200 rounded-md hover:bg-gray-300">Previous</button>
                <button class="px-3 py-1 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Next</button>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<script>
    document.getElementById("import-btn").addEventListener("click", () => {
        const importFile = document.getElementById("import-file");
        importFile.classList.toggle("hidden");
    });

    document.getElementById("import-file").addEventListener("change", () => {
        const feedback = document.getElementById("import-feedback");
        feedback.classList.remove("hidden");
        feedback.textContent = "Import in progress...";

        setTimeout(() => {
            feedback.textContent = "Import successful!";
            feedback.classList.add("text-green-600");
        }, 3000);
    });
</script>
