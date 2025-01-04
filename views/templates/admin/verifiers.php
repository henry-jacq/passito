<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="space-y-2 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Verifiers</h2>
            <p class="text-gray-600 text-md mb-10">
                Manage verifier devices efficiently, including location, IP address, and operational status.
            </p>
        </div>
        <button id="open-add-device-modal" class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:ring focus:ring-indigo-400 transition">
            <i class="fa-solid fa-plus mr-2"></i> Add New Device
        </button>
    </div>

    <!-- Info Card -->
    <div class="mb-8 p-4 bg-indigo-100 border-l-4 border-indigo-400 rounded-lg">
        <ul class="list-disc pl-4 text-indigo-800 text-sm space-y-1">
            <li>Ensure tokens are securely handled as they <strong>cannot be regenerated</strong>.</li>
            <li>Verify QR scanning functionality before deploying devices.</li>
            <li>Devices must have stable internet access and server connectivity before activation.</li>
        </ul>
    </div>

    <!-- Verifiers Table -->
    <section class="bg-white shadow-md rounded-lg overflow-auto select-none">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Device Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Location</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">IP Address</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Last Sync</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">One-Time Token</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700">Verifier Device 1</td>
                    <td class="px-4 py-3 text-sm text-gray-700">Building A, Floor 2</td>
                    <td class="px-4 py-3 text-sm text-gray-700">192.168.1.10</td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <span class="inline-block px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                            Active
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm">
                            <span class="block text-gray-700">24 Dec, 2024</span>
                            <span class="text-xs text-gray-500">6:00:00 PM</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <div class="flex items-center justify-center space-x-2">
                            <span class="truncate lg:max-w-[180px] md:max-w-[80px] block text-gray-700">a610caf8c94879e97aff503b7a</span>
                            <button class="text-blue-600 hover:text-blue-800" title="Copy Token" onclick="copyToClipboard('a610caf8c94879e97aff503b7a')">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <div class="flex items-center justify-center space-x-2">
                            <button class="px-3 py-1 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring focus:ring-green-400 transition duration-200">
                                Activate
                            </button>
                            <button class="px-3 py-1 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring focus:ring-red-400 transition duration-200">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</main>
<script>
function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        // Use modern clipboard API
        navigator.clipboard.writeText(text).then(() => {
            alert('Token copied to clipboard!');
        }).catch(err => {
            console.error('Clipboard API failed:', err);
            fallbackCopyToClipboard(text);
        });
    } else {
        // Fallback for unsupported browsers
        fallbackCopyToClipboard(text);
    }
}

function fallbackCopyToClipboard(text) {
    // Create a temporary textarea element
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);

    // Select and copy the text
    textarea.select();
    try {
        document.execCommand('copy');
        alert('Token copied to clipboard!');
    } catch (err) {
        console.error('Fallback copy failed:', err);
        alert('Failed to copy token.');
    }

    // Remove the temporary textarea
    document.body.removeChild(textarea);
}
</script>