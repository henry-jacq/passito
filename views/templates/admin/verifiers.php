<main class="flex-1 p-8 mt-20 overflow-y-auto">
    <div class="max-w-7xl mx-auto">
        <!-- Page Heading -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-4xl font-bold text-gray-800">Manage Verifiers</h2>
            <button 
                class="px-6 py-2 text-lg font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:ring focus:ring-blue-400 focus:outline-none">
                Add New Device
            </button>
        </div>

        <!-- Devices Table -->
        <div class="overflow-hidden border border-gray-200 rounded-lg shadow-lg bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-sm font-semibold tracking-wider text-left text-gray-700 uppercase">
                            Device Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-sm font-semibold tracking-wider text-left text-gray-700 uppercase">
                            Location
                        </th>
                        <th scope="col" class="px-6 py-3 text-sm font-semibold tracking-wider text-center text-gray-700 uppercase">
                            IP Address
                        </th>
                        <th scope="col" class="px-6 py-3 text-sm font-semibold tracking-wider text-center text-gray-700 uppercase">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-sm font-semibold tracking-wider text-center text-gray-700 uppercase">
                            Last Sync
                        </th>
                        <th scope="col" class="px-6 py-3 text-sm font-semibold tracking-wider text-center text-gray-700 uppercase">
                            Token
                        </th>
                        <th scope="col" class="px-6 py-3 text-sm font-semibold tracking-wider text-center text-gray-700 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Example Row 1 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            Verifier-01
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            Library Entrance
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center whitespace-nowrap">
                            192.168.1.101
                        </td>
                        <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            2024-12-24 14:35:22
                        </td>
                        <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="text-gray-900 truncate max-w-[150px] block overflow-hidden overflow-ellipsis" title="a610caf8c94879e97aff503b7a">
                                    a610caf8c94879e97aff503b7a
                                </span>
                                <button 
                                    class="text-gray-600 hover:text-gray-800 focus:outline-none" 
                                    title="Copy Token" 
                                    onclick="navigator.clipboard.writeText('a610caf8c94879e97aff503b7a')">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-right whitespace-nowrap">
                            <button 
                                class="px-3 py-1 text-sm text-white bg-yellow-600 rounded shadow hover:bg-yellow-700 focus:ring focus:ring-yellow-400 focus:outline-none">
                                Deactivate
                            </button>
                            <button 
                                class="px-3 py-1 text-sm text-white bg-gray-600 rounded shadow hover:bg-gray-700 focus:ring focus:ring-gray-400 focus:outline-none">
                                Remove
                            </button>
                        </td>
                    </tr>
                    <!-- Example Row 2 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            Verifier-02
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            Main Gate
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center whitespace-nowrap">
                            192.168.1.102
                        </td>
                        <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            2024-12-25 08:15:47
                        </td>
                        <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                            <a href="javascript:void(0)" class="flex items-center justify-center gap-1 text-blue-600 hover:text-blue-500 hover:underline" title="Generate a new token" onclick="generateToken()">
                                <i class="fa fa-key"></i>
                                <span>Generate Token</span>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-right whitespace-nowrap">
                            <button 
                                class="px-3 py-1 text-sm text-white bg-green-600 rounded shadow hover:bg-activate-700 focus:ring focus:ring-green-400 focus:outline-none">
                                Activate
                            </button>
                            <button 
                                class="px-3 py-1 text-sm text-white bg-gray-600 rounded shadow hover:bg-gray-700 focus:ring focus:ring-gray-400 focus:outline-none">
                                Remove
                            </button>
                        </td>
                    </tr>
                    <!-- Example Row 3 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            Verifier-03
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            Canteen
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center whitespace-nowrap">
                            192.168.1.103
                        </td>
                        <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                Inactive
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            2024-12-20 12:50:30
                        </td>
                        <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="text-gray-900 truncate max-w-[150px] block overflow-hidden overflow-ellipsis" title="none">
                                    ---
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-right whitespace-nowrap">
                            <button 
                                class="px-3 py-1 text-sm text-white bg-green-600 rounded shadow hover:bg-green-700 focus:ring focus:ring-green-400 focus:outline-none">
                                Activate
                            </button>
                            <button 
                                class="px-3 py-1 text-sm text-white bg-gray-600 rounded shadow hover:bg-gray-700 focus:ring focus:ring-gray-400 focus:outline-none">
                                Remove
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
