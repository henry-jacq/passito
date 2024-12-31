<!-- Logbook Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Verifier Logbook</h2>
    <p class="text-gray-600 text-md mb-8">View and manage logs of student check-in and check-out activities.</p>

    <!-- Logbook Table -->
    <section class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <p class="text-gray-600 text-sm">Total Logs: <span class="font-semibold text-gray-800">200</span></p>
            </div>
            <div>
                <input 
                    type="text" 
                    placeholder="Search logs..." 
                    class="px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300 focus:outline-none text-sm"
                />
            </div>
        </div>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-sm uppercase text-gray-600 font-semibold">
                    <th class="p-3 text-left">Outpass ID</th>
                    <th class="p-3 text-left">Student Name</th>
                    <th class="p-3 text-left">Student ID</th>
                    <th class="p-3 text-left">Department</th>
                    <th class="p-3 text-left">Check-In</th>
                    <th class="p-3 text-left">Check-Out</th>
                    <th class="p-3 text-left">Verifier</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample Row -->
                <tr class="text-sm border-b hover:bg-gray-50">
                    <td class="p-3 text-gray-800 font-medium">OP12345</td>
                    <td class="p-3 text-gray-800">John Doe</td>
                    <td class="p-3 text-gray-800">ST98765</td>
                    <td class="p-3 text-gray-800">Computer Science</td>
                    <td class="p-3 text-gray-800">2024-12-29 08:00</td>
                    <td class="p-3 text-gray-800">2024-12-29 18:00</td>
                    <td class="p-3 text-gray-800 flex items-center">
                        <span class="mr-2 w-2.5 h-2.5 bg-green-500 rounded-full"></span> Verifier 1
                    </td>
                </tr>
                <tr class="text-sm border-b hover:bg-gray-50">
                    <td class="p-3 text-gray-800 font-medium">OP12346</td>
                    <td class="p-3 text-gray-800">Jane Smith</td>
                    <td class="p-3 text-gray-800">ST98766</td>
                    <td class="p-3 text-gray-800">Electrical Engineering</td>
                    <td class="p-3 text-gray-800">2024-12-29 09:00</td>
                    <td class="p-3 text-gray-800">2024-12-29 17:30</td>
                    <td class="p-3 text-gray-800 flex items-center">
                        <span class="mr-2 w-2.5 h-2.5 bg-green-500 rounded-full"></span> Verifier 2
                    </td>
                </tr>
                <!-- Add more rows dynamically -->
            </tbody>
        </table>
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <p>Showing 1 to 10 of 200 entries</p>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 border rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300 focus:ring focus:ring-blue-300 focus:outline-none">Previous</button>
                <button class="px-3 py-1 border rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none">Next</button>
            </div>
        </div>
    </section>
</main>
