<div class="px-3 space-y-4">
    <h3 class="pb-3 text-xl font-bold text-gray-800 border-b border-gray-200">Add New Student</h3>

    <div class="space-y-6">
        <div class="space-y-3">
            <div class="grid grid-cols-1 gap-4 mb-5 sm:grid-cols-2">
                <div>
                    <label for="student-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="student-name" name="student-name" placeholder="e.g., John Doe" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="e.g., student@email.com" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="roll-no" class="block text-sm font-medium text-gray-700">Roll No</label>
                    <input type="text" id="roll-no" name="roll-no" placeholder="e.g., 2212025" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="student-no" class="block text-sm font-medium text-gray-700">Student Number</label>
                    <input type="text" id="student-no" name="student-no" placeholder="e.g., 9876543210" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="parent-no" class="block text-sm font-medium text-gray-700">Parent Number</label>
                    <input type="text" id="parent-no" name="parent-no" placeholder="e.g., 9876543210" required
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
                        <option value="" disabled selected>Select Program</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?= $program['id'] ?>"><?= $program['programName'] . ' ' . $program['courseName'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="academic-year-id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select id="academic-year-id" name="academic-year-id" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select Academic Year</option>
                        <?php foreach (($academic_years ?? []) as $year): ?>
                            <option value="<?= $year['id'] ?>"><?= $year['label'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="hostel-no" class="block text-sm font-medium text-gray-700">Hostel</label>
                    <select id="hostel-no" name="hostel-no"
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select Hostel</option>
                        <?php foreach ($hostels as $hostel): ?>
                            <option value="<?= $hostel['id'] ?>"><?= $hostel['hostelName'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="space-y-2">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="room-no" class="block text-sm font-medium text-gray-700">Room Number</label>
                    <input type="text" id="room-no" name="room-no" placeholder="e.g., A-102" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                    <input type="number" id="year" name="year" placeholder="e.g., 2" required
                        class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
    </div>
</div>
