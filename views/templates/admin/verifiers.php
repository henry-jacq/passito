<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <!-- Page Heading -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-semibold text-gray-700">Manage Verifiers</h2>
        <button id="open-add-device-modal" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:ring focus:ring-blue-400 ">
            Add New Device
        </button>
    </div>
    <div class="max-w-7xl mx-auto">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <ul class="list-disc pl-6 text-sm">
                <li><strong>Tokens are crucial for device setup</strong> and must remain confidential as they <strong>cannot be regenerated</strong>.</li>
                <li><strong>Verify QR Scanning functionality</strong> before deploying the devices.</li>
                <li><strong>Ensure devices are connected to the internet</strong> and have access to the server <strong>before activating them</strong>.</li>
            </ul>
        </div>


        <!-- Devices Table -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
            <!-- Table for Larger Screens -->
            <div class="overflow-x-auto hidden lg:block">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700 uppercase">Device Name</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700 uppercase">Location</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700 uppercase">IP Address</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700 uppercase">Last Sync</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700 uppercase">Token</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Example Row -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">Verifier-01</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Library Entrance</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">192.168.1.101</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">2024-12-24 14:35:22</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <span class="truncate max-w-[150px] block overflow-ellipsis overflow-hidden">a610caf8c94879e97aff503b7a</span>
                                    <button class="text-gray-600 hover:text-gray-800" title="Copy Token">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-end">
                                <button class="px-3 py-1 bg-yellow-600 text-white rounded shadow hover:bg-yellow-700">Deactivate</button>
                                <button class="px-3 py-1 bg-gray-600 text-white rounded shadow hover:bg-gray-700">Remove</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">Verifier-02</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Main Gate</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">192.168.1.102</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                    Inactive
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">2024-12-24 14:35:22</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <span
                                        class="truncate max-w-[150px] block overflow-ellipsis overflow-hidden">a610caf8c94879e97aff503b7a</span>
                                    <button class="text-gray-600 hover:text-gray-800" title="Copy Token">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-end">
                                <button class="px-3 py-1 bg-green-600 text-white rounded shadow hover:bg-green-700 disabled:bg-green-800 disabled:text-gray-300 disabled:cursor-not-allowed">Activate</button>
                                <button class="px-3 py-1 bg-gray-600 text-white rounded shadow hover:bg-gray-700">Remove</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">Verifier-03</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Canteen</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">192.168.1.103</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">2024-12-24 14:35:22</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <a href="javascript:void(0)" class="flex items-center justify-center gap-1 text-blue-600 hover:text-blue-500 hover:underline" title="Generate a new token" onclick="generateToken()">
                                    <i class="fa fa-key"></i>
                                    <span>Generate Token</span>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-end">
                                <button class="px-3 py-1 bg-green-600 text-white rounded shadow hover:bg-green-700 disabled:bg-green-800 disabled:text-gray-300 disabled:cursor-not-allowed" disabled>Activate</button>
                                <button class="px-3 py-1 bg-gray-600 text-white rounded shadow hover:bg-gray-700">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Card Layout for Mobile -->
            <div class="lg:hidden divide-y divide-gray-200">
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-800">Device Name: Verifier-01</h3>
                    <p class="text-sm text-gray-900">Location: Library Entrance</p>
                    <p class="text-sm text-gray-900">IP Address: 192.168.1.101</p>
                    <p class="text-sm text-gray-900">Status: 
                        <span class="inline-block px-2 py-0.5 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>
                    </p>
                    <p class="text-sm text-gray-900">Last Sync: 2024-12-24 14:35:22</p>
                    <div class="flex justify-start items-center space-x-2">
                        <span class="text-sm text-gray-900">Token:</span>
                        <span class="truncate max-w-[200px] text-sm text-gray-900 block overflow-ellipsis overflow-hidden">a610caf8c94879e97aff503b7a</span>
                        <button class="text-gray-600 hover:text-gray-800" title="Copy Token">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button class="px-3 py-1 bg-yellow-600 text-white rounded shadow hover:bg-yellow-700">Deactivate</button>
                        <button class="px-3 py-1 bg-gray-600 text-white rounded shadow hover:bg-gray-700">Remove</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
