<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-3xl font-semibold text-gray-900 mb-6">Manage Verifiers (Automated Devices)</h2>

    <!-- Search and Add Verifier Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="relative">
            <input 
                type="text" 
                placeholder="Search devices..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:outline-none focus:border-blue-500"
            >
        </div>
        <button 
            class="px-4 py-2 text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:ring focus:ring-blue-400 focus:outline-none">
            Add Device
        </button>
    </div>

    <!-- Devices Table -->
    <div class="overflow-hidden border border-gray-200 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Device Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        IP Address
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Last Check-in
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Example Row -->
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        Verifier-01
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        192.168.1.101
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        Online
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        2024-12-24 14:35:22
                    </td>
                    <td class="px-6 py-4 text-sm text-right whitespace-nowrap">
                        <button 
                            class="px-3 py-1 text-sm text-white bg-green-600 rounded shadow hover:bg-green-700 focus:ring focus:ring-green-400 focus:outline-none">
                            Edit
                        </button>
                        <button 
                            class="px-3 py-1 text-sm text-white bg-red-600 rounded shadow hover:bg-red-700 focus:ring focus:ring-red-400 focus:outline-none">
                            Delete
                        </button>
                    </td>
                </tr>
                <!-- Repeat Rows As Needed -->
            </tbody>
        </table>
    </div>
</main>
