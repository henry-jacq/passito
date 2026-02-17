<?php
$student = $student ?? [];
$studentId = $student['id'] ?? null;
$studentName = $student['name'] ?? '';
$studentEmail = $student['email'] ?? '';
$rollNo = $student['roll_no'] ?? '';
$year = $student['year'] ?? '';
$roomNo = $student['room_no'] ?? '';
$hostelId = $student['hostel_id'] ?? '';
$studentNo = $student['student_no'] ?? '';
$parentNo = $student['parent_no'] ?? '';
$programId = $student['program_id'] ?? '';
$academicYearId = $student['academic_year_id'] ?? '';
$status = (int)($student['status'] ?? 1);
?>

<div class="px-3 space-y-4">
    <h3 class="pb-3 text-xl font-bold text-gray-800 border-b border-gray-200">Edit Student</h3>

    <div class="space-y-6">
        <div class="space-y-3">
            <input type="hidden" id="student-id" value="<?= $studentId ?>">
            <div class="grid grid-cols-1 gap-4 mb-5 sm:grid-cols-2">
                <div>
                    <label for="student-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="student-name" name="student-name" placeholder="e.g., John Doe" required
                        value="<?= $studentName ?>"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="e.g., student@email.com" required
                        value="<?= $studentEmail ?>"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="roll-no" class="block text-sm font-medium text-gray-700">Roll No</label>
                    <input type="text" id="roll-no" name="roll-no" placeholder="e.g., 2212025" required
                        value="<?= $rollNo ?>"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="student-no" class="block text-sm font-medium text-gray-700">Student Number</label>
                    <input type="text" id="student-no" name="student-no" placeholder="e.g., 9876543210" required
                        value="<?= $studentNo ?>"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="parent-no" class="block text-sm font-medium text-gray-700">Parent Number</label>
                    <input type="text" id="parent-no" name="parent-no" placeholder="e.g., 9876543210" required
                        value="<?= $parentNo ?>"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
        <div class="space-y-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="program-id" class="block text-sm font-medium text-gray-700">Program</label>
                    <select id="program-id" name="program-id" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled>Select Program</option>
                        <?php foreach (($programs ?? []) as $program): ?>
                            <option value="<?= $program['id'] ?>" <?= (string) $programId === (string) $program['id'] ? 'selected' : '' ?>>
                                <?= $program['programName'] . ' ' . $program['courseName'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="academic-year-id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select id="academic-year-id" name="academic-year-id" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled>Select Academic Year</option>
                        <?php foreach (($academic_years ?? []) as $yearOption): ?>
                            <option value="<?= $yearOption['id'] ?>" <?= (string) $academicYearId === (string) $yearOption['id'] ? 'selected' : '' ?>>
                                <?= $yearOption['label'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="hostel-no" class="block text-sm font-medium text-gray-700">Hostel</label>
                    <select id="hostel-no" name="hostel-no" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled>Select Hostel</option>
                        <?php foreach (($hostels ?? []) as $hostel): ?>
                            <option value="<?= $hostel['id'] ?>" <?= (string) $hostelId === (string) $hostel['id'] ? 'selected' : '' ?>>
                                <?= $hostel['hostelName'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="space-y-2">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="room-no" class="block text-sm font-medium text-gray-700">Room Number</label>
                    <input type="text" id="room-no" name="room-no" placeholder="e.g., A-102" required
                        value="<?= $roomNo ?>"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                    <input type="number" id="year" name="year" placeholder="e.g., 2" required
                        value="<?= $year ?>"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <option value="1" <?= $status === 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= $status === 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
