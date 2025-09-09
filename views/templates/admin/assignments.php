<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <section>
        <div class="mb-6 space-y-2">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Assignments</h2>
            <p class="mb-10 text-gray-600 text-md">Assign wardens to hostels for a specific academic year and manage existing allocations.</p>
        </div>
    </section>

    <!-- Assignment Form -->
    <section class="p-6 mb-10 bg-white rounded-lg shadow">
        <h3 class="mb-4 text-lg font-semibold text-gray-700">Create New Assignment</h3>
        <form class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <!-- Academic Year -->
            <div>
                <label for="year" class="block mb-2 text-sm font-medium text-gray-700">Academic Year</label>
                <select id="year" name="year" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Year...</option>
                    <option value="2024-25">2024-25</option>
                    <option value="2025-26">2025-26</option>
                    <option value="2026-27">2026-27</option>
                </select>
            </div>

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
                <button type="submit" class="w-full px-4 py-2 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Assign Warden
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
            <p class="mt-2 text-xs text-gray-500">Select one or more hostels. Warden will receive outpass requests from students residing in selected hostels.</p>
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
            <p class="mt-2 text-xs text-gray-500">Select student academic years. Warden will receive outpass requests from students in selected academic years.</p>
        </div>
    </section>

    <!-- Current Assignments Table -->
    <section class="p-6 bg-white rounded-lg shadow">
        <div class="flex flex-col mb-4 md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-semibold text-gray-700">Current Warden Assignments</h3>

            <!-- Filters -->
            <div class="flex gap-3 mt-4 md:mt-0">
                <select class="px-4 py-2 text-sm bg-white border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Academic Years</option>
                    <option value="2024-25">2024-25</option>
                    <option value="2025-26">2025-26</option>
                    <option value="2026-27">2026-27</option>
                </select>
                <select class="px-3 py-2 text-sm bg-white border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Assignment Types</option>
                    <option value="hostel">Hostel Based</option>
                    <option value="year">Year Based</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Academic Year</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Warden</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Assignment Type</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Assigned To</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Created On</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 text-gray-800">2025-26</td>
                        <td class="px-4 py-3 text-gray-800">Dr. A. Kumar</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">Hostel Based</span>
                        </td>
                        <td class="px-4 py-3 text-gray-800">Hostel A, Hostel B</td>
                        <td class="px-4 py-3 text-gray-600">01 Sep 2025</td>
                        <td class="px-4 py-3">
                            <button class="px-3 py-1 mr-2 text-sm text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200">Edit</button>
                            <button class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-lg hover:bg-red-200">Remove</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-gray-800">2025-26</td>
                        <td class="px-4 py-3 text-gray-800">Prof. B. Sharma</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Year Based</span>
                        </td>
                        <td class="px-4 py-3 text-gray-800">1st Year, 2nd Year</td>
                        <td class="px-4 py-3 text-gray-600">03 Sep 2025</td>
                        <td class="px-4 py-3">
                            <button class="px-3 py-1 mr-2 text-sm text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200">Edit</button>
                            <button class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-lg hover:bg-red-200">Remove</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-gray-800">2026-27</td>
                        <td class="px-4 py-3 text-gray-800">Dr. C. Patel</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">Hostel Based</span>
                        </td>
                        <td class="px-4 py-3 text-gray-800">Hostel C (Girls)</td>
                        <td class="px-4 py-3 text-gray-600">05 Sep 2025</td>
                        <td class="px-4 py-3">
                            <button class="px-3 py-1 mr-2 text-sm text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200">Edit</button>
                            <button class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-lg hover:bg-red-200">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <script>
        function toggleAssignmentOptions() {
            const assignmentType = document.getElementById('assignment_type').value;
            const hostelSelection = document.getElementById('hostel_selection');
            const yearSelection = document.getElementById('year_selection');

            // Hide both sections first
            hostelSelection.classList.add('hidden');
            yearSelection.classList.add('hidden');

            // Show relevant section based on selection
            if (assignmentType === 'hostel') {
                hostelSelection.classList.remove('hidden');
            } else if (assignmentType === 'year') {
                yearSelection.classList.remove('hidden');
            }
        }
    </script>
</main>