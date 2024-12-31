<!-- Manage Students Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Manage Students</h2>
    <p class="text-gray-700 text-normal mb-8">Manage student details including their hostel assignments, academic information, and contact details.</p>

    <!-- Add Student Button -->
    <div class="mb-6">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
            Add New Student
        </button>
    </div>

    <!-- Bulk Import Section -->
    <div class="mb-6">
        <div class="flex items-center">
            <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                Bulk Import Students
            </button>
            <span class="ml-4 text-gray-600 text-sm">Upload a CSV file to bulk import students.</span>
        </div>
        <p class="text-gray-500 text-sm mt-2">Ensure the CSV file contains the following columns: Name, Hostel, Year, Branch, Department, Room No., Parent Contact, Status.</p>
        <input type="file" class="mt-2" accept=".csv" />
    </div>

    <!-- Student Table -->
    <section class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Student Name</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Hostel</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Year</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Branch</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Department</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Room No.</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Parent Contact</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Student Row -->
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-700">Jane Doe</td>
                    <td class="px-4 py-2 text-sm text-gray-700">Hostel A</td>
                    <td class="px-4 py-2 text-sm text-gray-700">2nd Year</td>
                    <td class="px-4 py-2 text-sm text-gray-700">Computer Science</td>
                    <td class="px-4 py-2 text-sm text-gray-700">Engineering</td>
                    <td class="px-4 py-2 text-sm text-gray-700">101</td>
                    <td class="px-4 py-2 text-sm text-gray-700">9876543210</td>
                    <td class="px-4 py-2 text-sm text-gray-700">Active</td>
                    <td class="px-4 py-2 text-sm">
                        <button class="text-indigo-600 hover:text-indigo-800 transition duration-200">Edit</button>
                        <button class="text-red-600 hover:text-red-800 transition duration-200 ml-4">Remove</button>
                    </td>
                </tr>
                <!-- Repeat above row for each student -->
            </tbody>
        </table>
    </section>
</main>
