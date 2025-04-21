<!-- Logbook Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="mb-6 space-y-2">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Verifier Logbook</h2>
            <p class="mb-10 text-gray-600 text-md">View and manage logs of student check-in and check-out activities.</p>
        </div>
    </div>

    <?php if (empty($logbook)): ?>
        <section class="flex flex-col items-center my-4 space-y-6 bg-white rounded-lg shadow-lg py-22">
            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full shadow-inner text-blue-700/80 animate-bounce">
                <i class="text-4xl fas fa-exclamation-circle"></i>
            </div>
            <div class="text-center">
                <h2 class="text-xl font-bold text-gray-800">
                    Logbook is Empty!
                </h2>
                <p class="max-w-md mt-2 text-base text-gray-600">
                    There are no logs available at the moment. Please check later.
                </p>
            </div>
        </section>
    <?php else: ?>
        <section class="overflow-auto bg-white rounded-lg shadow-md select-none">
            <table class="w-full border-collapse table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Outpass ID</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Student Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Digital ID</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Department</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Check-Out</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Check-In</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Device</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($logbook as $log): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <a href="<?= $this->urlFor('admin.outpass.records.details', ['outpass_id' => $log->getOutpass()->getId()]) ?>" class="text-gray-600 hover:text-gray-800">#<?= htmlspecialchars($log->getOutpass()->getId()) ?></a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($log->getOutpass()->getStudent()->getUser()->getName()) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($log->getOutpass()->getStudent()->getDigitalId()) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($log->getOutpass()->getStudent()->getDepartment()) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <?php
                                $status = ucfirst($log->getOutpass()->getStatus()->value);
                                $colorMap = [
                                    'Approved' => 'green',
                                    'Pending' => 'yellow',
                                    'Rejected' => 'red',
                                    'Checked-in' => 'blue',
                                    'Checked-out' => 'indigo'
                                ];
                                $color = $colorMap[$status] ?? 'gray';
                                ?>
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-<?= $color ?>-100 text-<?= $color ?>-700 rounded-full"><?= $status ?></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <?php if ($log->getOutTime()): ?>
                                    <div class="text-sm">
                                        <span class="block text-gray-700"><?= $log->getOutTime()->format('M d, Y') ?></span>
                                        <span class="text-xs text-gray-500"><?= $log->getOutTime()->format('h:i A') ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <?php if ($log->getInTime()): ?>
                                    <div class="text-sm">
                                        <span class="block text-gray-700"><?= $log->getInTime()->format('M d, Y') ?></span>
                                        <span class="text-xs text-gray-500"><?= $log->getInTime()->format('h:i A') ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($log->getVerifier()->getName()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>
</main>