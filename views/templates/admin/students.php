<?php
$flashKey = $this->session->getCurrentFlashKey() ?? null;
$successMessage = $this->session->getFlash('success')[$flashKey] ?? null;
$errorMessage = $this->session->getFlash('error')[$flashKey] ?? null;
?>

<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Students</h2>
    <p class="mb-8 text-gray-600 text-md">
        Manage student details, including their hostel details, academic information, and contact details.
    </p>

    <?php if (empty($students)): ?>
        <div class="flex flex-col gap-6 p-6 mb-4 leading-relaxed text-blue-800 border-l-4 rounded-lg shadow-md bg-blue-200/60 border-blue-800/80" role="alert">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-semibold">No Students Found</h3>
                    <p class="mt-1 text-sm">
                        It seems there are no student records available. Please add student records to manage them effectively.
                    </p>
                </div>
                <button class="inline-flex items-center justify-center px-2 py-2 text-sm font-medium text-white transition duration-200 bg-blue-500 rounded-lg shadow hover:bg-blue-600 add-student-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Student
                </button>
            </div>
        </div>

    <?php else: ?>
        <div class="flex flex-wrap items-center justify-between mb-6 space-x-8 space-y-4 md:space-y-0">
            <!-- Search Bar -->
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 flex items-center text-gray-400 left-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" placeholder="Search students..." class="w-full py-2 transition duration-200 border border-gray-300 rounded-md bg-gray-50 text-md ps-12 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />
            </div>

            <div class="flex space-x-2">
                <!-- Add Student Button -->
                <button class="px-3 py-2 text-white transition duration-200 bg-blue-600 rounded-md hover:bg-blue-700 add-student-modal focus:outline-none focus:ring-0 focus:border-transparent">
                    <i class="mr-2 fa-solid fa-user-plus fa-sm"></i>
                    Add Student
                </button>

                <!-- Export Button -->
                <button class="px-3 py-2 text-white transition duration-200 bg-indigo-600 rounded-md hover:bg-indigo-700 focus:ring focus:ring-indigo-400 export-students">
                    <i class="mr-2 fa-solid fa-arrow-up-from-bracket fa-sm"></i>
                    Export
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex flex-col gap-6 px-6 py-5 mb-4 leading-relaxed text-gray-800 border-l-4 rounded-lg shadow-md bg-gray-200/60 border-gray-800/80">
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <h3 class="text-base font-semibold">Import Students Records</h3>
                <p class="mt-1 text-sm">
                    Upload a CSV file to import student data.
                    <a href="#" class="text-blue-700 underline hover:text-blue-900">Download the template</a> for the correct format.
                </p>
            </div>
            <button id="import-btn" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white transition duration-200 bg-gray-500 rounded-lg shadow hover:bg-gray-600 focus:outline-none focus:ring-0 focus:border-transparent">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-file-import"></i>
                    <span>Upload File</span>
                </div>
            </button>
        </div>

        <input id="import-file" type="file" class="hidden w-full p-2 mt-3 text-sm border border-gray-300 rounded-md" accept=".csv">
        <div id="import-feedback" class="hidden mt-2 text-sm text-blue-700">Import in progress...</div>
    </div>

    <?php if ($successMessage): ?>
        <div class="p-4 mb-3 text-green-800 bg-green-100 rounded-lg" role="alert">
            <span class="font-semibold text-[15px] inline-block mr-4"><?= nl2br($successMessage) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="p-4 mb-3 text-red-800 bg-red-100 rounded-lg" role="alert">
            <span class="font-semibold text-[15px] inline-block mr-4"><?= nl2br($errorMessage) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($students)): ?>
        <section class="overflow-hidden bg-white rounded-lg shadow-md">
            <table class="w-full border-collapse table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Student Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Course</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Year</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Academic Year</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Hostel</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Room No.</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Parent Contact</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $student->getUser()->getName() ?></td>
                            <td class="px-4 py-3 text-sm text-center text-gray-700"><?= $student->getProgram()->getProgramName() . ' ' . $student->getProgram()->getShortCode() ?></td>
                            <td class="px-4 py-3 text-sm text-center text-gray-700"><?= formatStudentYear($student->getYear()) ?></td>
                            <td class="px-4 py-3 text-sm text-center text-gray-700"><?= $student->getAcademicYear()?->getLabel() ?? 'N/A' ?></td>
                            <td class="px-4 py-3 text-sm text-center text-gray-700"><?= $student->getHostel()->getName() ?></td>
                            <td class="px-4 py-3 text-sm text-center text-gray-700"><?= $student->getRoomNo() ?></td>
                            <td class="px-4 py-3 text-sm text-center text-gray-700"><?= $student->getParentNo() ?></td>
                            <td class="px-4 py-3 text-sm text-center">
                                <?php if ($student->getStatus()): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-3 text-sm text-center">
                                <button
                                    class="mr-4 text-gray-600 transition duration-200 hover:text-gray-800 edit-student-modal"
                                    data-id="<?= $student->getId() ?>"
                                    data-name="<?= $student->getUser()->getName() ?>"
                                    data-email="<?= $student->getUser()->getEmail() ?>"
                                    data-digital-id="<?= $student->getDigitalId() ?>"
                                    data-year="<?= $student->getYear() ?>"
                                    data-room-no="<?= $student->getRoomNo() ?>"
                                    data-hostel-id="<?= $student->getHostel()->getId() ?>"
                                    data-student-no="<?= $student->getUser()->getContactNo() ?>"
                                    data-parent-no="<?= $student->getParentNo() ?>"
                                    data-program-id="<?= $student->getProgram()->getId() ?>"
                                    data-academic-year-id="<?= $student->getAcademicYear()?->getId() ?>"
                                    data-status="<?= $student->getStatus() ? 1 : 0 ?>"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    class="text-red-600 transition duration-200 hover:text-red-800 remove-student-modal"
                                    data-id="<?= $student->getId() ?>"
                                    data-name="<?= $student->getUser()->getName() ?>"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="flex items-center justify-between px-4 py-2 bg-gray-100 border-t">
                <p class="text-sm text-gray-600">Showing 1-10 of 50 students</p>
                <div class="flex justify-end space-x-2">
                    <button class="px-3 py-1 text-sm bg-gray-200 rounded-md hover:bg-gray-300">Previous</button>
                    <button class="px-3 py-1 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">Next</button>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
