<?php

use App\Enum\VerifierStatus; ?>
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="mb-6 space-y-2">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Verifiers</h2>
            <p class="mb-10 text-gray-600 text-md">
                Deploy and manage your verifier devices to ensure secure and efficient verification processes.
            </p>
        </div>
        <button id="open-add-device-modal" class="px-5 py-2 text-sm font-medium text-white transition-all duration-200 ease-in-out bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
            <i class="mr-2 fa-solid fa-plus"></i> Add New Device
        </button>
    </div>

    <!-- Info Card -->
    <div class="p-4 mb-8 border-l-4 rounded-lg bg-blue-500/20 border-blue-800/80">
        <h3 class="mb-2 text-base font-semibold text-blue-900">
            Important Notes
        </h3>
        <ul class="pl-4 space-y-1 text-sm text-blue-800 list-disc">
            <li>Install the <strong>Verifier Tool</strong> on a Raspberry Pi to activate the device.</li>
            <li>
                Download from the
                <a href="https://github.com/henry-jacq/passito-verifier" target="_blank" class="text-blue-600 underline hover:text-blue-800">GitHub repository</a>. Follow the README to complete setup.
            </li>
            <li>Ensure QR scanning and internet connectivity are tested before deployment.</li>
            <li>Auth token is required for verifier authentication and secure communication, and cannot be regenerated.</li>
        </ul>

    </div>

    <?php if (!empty($verifiers)): ?>
        <section class="overflow-auto bg-white rounded-lg shadow-md select-none">
            <table class="w-full border-collapse table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Device Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Location</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">IP Address</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Last Sync</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Auth Token</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($verifiers as $verifier): ?>
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

                                    echo '<div class="text-sm"><span class="block text-gray-700">' . $date . '</span><span class="text-xs text-gray-500">' . $time . '</span></div>';
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
                                        <button class="px-3 py-1 text-sm text-white transition duration-200 bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:ring focus:ring-yellow-400 deactivate-verifier-modal" data-id="<?= $verifier->getId() ?>">
                                            Deactivate
                                        </button>
                                    <?php elseif (VerifierStatus::isInactive($verifier->getStatus()->value)): ?>
                                        <button class="px-3 py-1 text-sm text-white transition duration-200 bg-green-600 rounded-lg hover:bg-green-700 focus:ring focus:ring-green-400 activate-verifier-modal" data-id="<?= $verifier->getId() ?>">
                                            Activate
                                        </button>
                                    <?php endif; ?>
                                    <button class="px-3 py-1 text-sm text-white transition duration-200 bg-red-600 rounded-lg hover:bg-red-700 focus:ring focus:ring-red-400 delete-verifier-modal" data-id="<?= $verifier->getId() ?>" data-name="<?= $verifier->getName() ?>">
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
    <!-- Verifier / Server Logs Section -->
    <section class="mt-8 select-none">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h3 class="font-medium text-gray-800 text-md">
                    <i class="mr-2 fa-solid fa-circle-info"></i>
                    Verifier Log
                    <i class="ml-1 text-sm italic font-normal">(Read-only)</i>
                </h3>
                <!-- <p class="text-sm text-gray-500">Monitor server-side WebSocket logs, device connections, and errors in real-time.</p> -->
            </div>
            <!-- <button onclick="clearLogs()" class="px-3 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring focus:ring-red-400">
                Clear Logs
            </button> -->
        </div>

        <div id="logs-container" class="h-48 p-4 overflow-y-auto font-mono text-sm text-green-400 border border-gray-300 rounded-lg bg-gray-50">
            <div class="text-gray-500"></div>
        </div>
    </section>

    <script>
        const logsContainer = document.getElementById('logs-container');

        function addLog(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');

            logEntry.classList.add('whitespace-pre-wrap');

            // Color based on type
            if (type === 'error') {
                logEntry.classList.add('text-red-600');
            } else if (type === 'warning') {
                logEntry.classList.add('text-yellow-600');
            } else {
                logEntry.classList.add('text-green-600');
            }

            logEntry.innerHTML = `<span class="text-gray-600">[${timestamp}]</span> ${message}`;

            logsContainer.appendChild(logEntry);

            // Auto-scroll to bottom
            logsContainer.scrollTop = logsContainer.scrollHeight;
        }

        function clearLogs() {
            logsContainer.innerHTML = '<div class="text-gray-500">Logs cleared. Awaiting new logs...</div>';
        }

        // Example: Simulate receiving logs (You can replace this with actual WebSocket integration)
        setInterval(() => {
            const fakeLogs = [
                "Verifier 'Pi-Entrance-01' connected successfully.",
                "Ping received from 'Pi-Entrance-01'.",
                "Error: Lost connection to Verifier 'Pi-Backgate-02'.",
                "Authentication failed for unknown device.",
                "Reconnection attempt from 'Pi-Backgate-02' successful."
            ];
            const randomLog = fakeLogs[Math.floor(Math.random() * fakeLogs.length)];
            const type = randomLog.toLowerCase().includes('error') || randomLog.toLowerCase().includes('failed') ? 'error' : 'info';
            addLog(randomLog, type);
        }, 2000);
    </script>

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