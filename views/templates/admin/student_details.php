<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="mb-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Student Details</h2>
                <p class="mt-1 text-sm text-gray-500">View student profile, academic details, and residence info.</p>
            </div>
            <div class="flex items-center gap-2">
                <button
                    class="px-3 py-2 text-sm text-gray-700 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200"
                    onclick="location.href='<?= $this->urlFor('admin.manage.students') ?>'">
                    Back to Students
                </button>
                <button
                    class="px-3 py-2 text-sm text-white transition duration-200 bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 edit-student-modal"
                    data-id="<?= $student->getId() ?>"
                    data-name="<?= $student->getUser()->getName() ?>"
                    data-email="<?= $student->getUser()->getEmail() ?>"
                    data-roll-no="<?= $student->getRollNo() ?>"
                    data-year="<?= $student->getYear() ?>"
                    data-room-no="<?= $student->getRoomNo() ?>"
                    data-hostel-id="<?= $student->getHostel()->getId() ?>"
                    data-student-no="<?= $student->getUser()->getContactNo() ?>"
                    data-parent-no="<?= $student->getParentNo() ?>"
                    data-program-id="<?= $student->getProgram()->getId() ?>"
                    data-academic-year-id="<?= $student->getAcademicYear()?->getId() ?>"
                    data-status="<?= $student->getUser()->getStatus() === \App\Enum\UserStatus::ACTIVE ? 1 : 0 ?>">
                    Edit
                </button>
                <button
                    class="px-3 py-2 text-sm text-white transition duration-200 bg-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 remove-student-modal"
                    data-id="<?= $student->getId() ?>"
                    data-name="<?= $student->getUser()->getName() ?>">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <section class="space-y-6">
        <div class="p-5 bg-white border border-gray-100 rounded-lg shadow-sm">
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 text-lg font-semibold text-blue-700 bg-blue-100 rounded-full">
                        <?= strtoupper(substr($student->getUser()->getName(), 0, 1)) ?>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Student</p>
                        <p class="text-lg font-semibold text-gray-900"><?= $student->getUser()->getName() ?></p>
                        <p class="text-sm text-gray-600"><?= $student->getUser()->getEmail() ?></p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 ml-auto">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full <?= $student->getUser()->getStatus() === \App\Enum\UserStatus::ACTIVE ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $student->getUser()->getStatus() === \App\Enum\UserStatus::ACTIVE ? 'Active' : 'Inactive' ?>
                    </span>
                    <span class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-full">
                        <?= $student->getRollNo() ?>
                    </span>
                    <span class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-full">
                        Room <?= $student->getRoomNo() ?>
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 mt-4 text-sm text-gray-700 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-xs text-gray-500">Student Number</p>
                    <p class="font-medium text-gray-800"><?= $student->getUser()->getContactNo() ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Parent Number</p>
                    <p class="font-medium text-gray-800"><?= $student->getParentNo() ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Created</p>
                    <p class="font-medium text-gray-800"><?= $student->getUser()->getCreatedAt()->format('d M, Y, h:i A') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Last Updated</p>
                    <p class="font-medium text-gray-800"><?= $student->getUpdatedAt()->format('d M, Y, h:i A') ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-5">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-800">Residence</h3>
                    <div class="grid grid-cols-1 gap-4 mt-4 text-sm text-gray-700">
                        <div>
                            <p class="text-xs text-gray-500">Hostel</p>
                            <p class="font-medium text-gray-800"><?= $student->getHostel()->getHostelName() ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Hostel Category</p>
                            <p class="font-medium text-gray-800"><?= $student->getHostel()->getCategory() ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Room No</p>
                            <p class="font-medium text-gray-800"><?= $student->getRoomNo() ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-7">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-800">Academic Details</h3>
                    <div class="grid grid-cols-1 gap-4 mt-4 text-sm text-gray-700 sm:grid-cols-2">
                        <div>
                            <p class="text-xs text-gray-500">Program</p>
                            <p class="font-medium text-gray-800">
                                <?= $student->getProgram()->getProgramName() . ' ' . $student->getProgram()->getShortCode() ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Course</p>
                            <p class="font-medium text-gray-800"><?= $student->getProgram()->getCourseName() ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Academic Year</p>
                            <p class="font-medium text-gray-800"><?= $student->getAcademicYear()?->getLabel() ?? 'N/A' ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Current Year</p>
                            <p class="font-medium text-gray-800"><?= formatStudentYear($student->getYear()) ?> Year</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Provider</p>
                            <p class="font-medium text-gray-800"><?= $student->getProgram()->getProvidedBy()?->getName() ?? 'N/A' ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Program Duration</p>
                            <p class="font-medium text-gray-800"><?= $student->getProgram()->getDuration() ?> Years</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
