<?php use App\Enum\VerifierStatus; ?>
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

    <?php if (!empty($verifiers)): ?>
    <section class="bg-white shadow-md rounded-lg overflow-auto select-none">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Device Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Location</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">IP Address</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Last Sync</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Auth Token</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach($verifiers as $verifier): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700"><?= $verifier->getName() ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700"><?= $verifier->getLocation() ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700"><?= $verifier->getIpAddress() !== null ? $verifier->getIpAddress() : 'N/A' ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <?php 
                        $statusValue = ucfirst($verifier->getStatus()->value);

                        if (VerifierStatus::isInactive($verifier->getStatus()->value)) {
                            echo "<span class=\"inline-block px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full\">$statusValue</span>";
                        } elseif (VerifierStatus::isActive($verifier->getStatus()->value)) {
                            echo "<span class=\"inline-block px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full\">$statusValue</span>";
                        } else {
                            echo "<span class=\"inline-block px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full\">$statusValue</span>";
                        }
                        ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php 
                        if (is_null($verifier->getLastSync())) {
                            echo '<span class="text-sm text-gray-400">N/A</span>';
                        } else {
                            $date = $verifier->getLastSync()->format('M d, Y');
                            $time = $verifier->getLastSync()->format('h:i A');

                            echo '<div class="text-sm"><span class="block text-gray-700">'. $date .'</span><span class="text-xs text-gray-500">'.$time.'</span></div>';
                        } ?>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <div class="flex items-center justify-center space-x-2">
                            <span class="truncate lg:max-w-[180px] md:max-w-[80px] block text-gray-700"><?= $verifier->getAuthToken() ?></span>
                            <button class="text-blue-600 hover:text-blue-800" title="Copy Token" onclick="copyToClipboard('<?= $verifier->getAuthToken() ?>')">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <div class="flex items-center justify-center space-x-2">
                            <?php if (VerifierStatus::isActive($verifier->getStatus()->value)): ?>
                                <button class="px-3 py-1 text-sm text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:ring focus:ring-yellow-400 transition duration-200">
                                    Deactivate
                                </button>
                            <?php elseif (VerifierStatus::isInactive($verifier->getStatus()->value)): ?>
                                <button class="px-3 py-1 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring focus:ring-green-400 transition duration-200">
                                    Activate
                                </button>
                            <?php endif; ?>
                            <button class="px-3 py-1 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring focus:ring-red-400 transition duration-200">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <?php endif; ?>
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