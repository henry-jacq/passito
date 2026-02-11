<!-- Logbook Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="mb-6 space-y-2">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Verifier Logbook</h2>
            <p class="mb-10 text-gray-600 text-md">View and manage logs of student check-in and check-out activities.</p>
        </div>
    </div>

    <?php

    use App\Enum\OutpassStatus;

    use App\Enum\UserRole;

    if (empty($logbook) && empty($search)): ?>
        <section class="flex flex-col items-center my-4 space-y-6 bg-white rounded-lg shadow-lg py-22">
            <div class="flex items-center justify-center w-16 h-16 text-blue-800 bg-blue-200 rounded-full shadow-inner">
                <i class="text-4xl fas fa-circle-info"></i>
            </div>
            <div class="text-center">
                <h2 class="text-xl font-bold text-blue-900">
                    Logbook is Empty!
                </h2>
                <p class="max-w-md mt-2 text-sm text-blue-800">
                    There are no logs available at the moment. Please check later.
                </p>
            </div>
        </section>
    <?php else: ?>
        <div class="mb-6">
            <form class="flex flex-wrap items-center justify-between gap-4" method="get" action="">
                <div class="relative flex-grow">
                    <input id="search-logbook" name="q" type="text" placeholder="Search by name or digital ID..."
                        value="<?= $search ?? '' ?>"
                        class="w-full py-2 transition duration-200 border border-gray-300 rounded-md bg-gray-50 text-md ps-12 focus:outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-600/50"
                        aria-label="Search logbook">
                    <span class="absolute text-gray-500 left-3 top-2">
                        <i class="fas fa-search"></i>
                    </span>
                    <?php if (!empty($search)): ?>
                        <button
                            type="button"
                            class="absolute inset-y-0 flex items-center px-2 text-sm text-gray-500 right-2 hover:text-gray-700"
                            onclick="window.location.href='?date=<?= urlencode($filterDate ?? '') ?>&action=<?= urlencode($filterAction ?? '') ?>'">
                            Clear
                        </button>
                    <?php endif; ?>
                </div>
                <div>
                    <input
                        id="filter-logbook-date"
                        name="date"
                        type="date"
                        value="<?= $filterDate ?? '' ?>"
                        class="p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 w-44 focus:border-blue-600/50 focus:ring-2 focus:ring-blue-600/50"
                        aria-label="Filter by date">
                </div>
                <div>
                    <select id="filter-logbook-action" name="action" class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 w-44 focus:border-blue-600/50 focus:ring-2 focus:ring-blue-600/50" aria-label="Filter by action">
                        <option value="" <?= empty($filterAction) ? 'selected' : '' ?>>All Actions</option>
                        <option value="checkout" <?= ($filterAction ?? '') === 'checkout' ? 'selected' : '' ?>>Check-Out</option>
                        <option value="checkin" <?= ($filterAction ?? '') === 'checkin' ? 'selected' : '' ?>>Check-In</option>
                    </select>
                </div>
            </form>
        </div>
        <section class="overflow-hidden bg-white rounded-lg shadow-md select-none">
            <table class="min-w-full border-collapse table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-1 py-3 text-sm font-semibold text-center text-gray-600">Outpass ID</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Student Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Digital ID</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Course</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Check-Out</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Check-In</th>
                        <th class="px-2 py-3 text-sm font-semibold text-center text-gray-600">Verified By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($logbook) && !empty($search)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-6 text-sm text-gray-600">
                                <div class="flex flex-col items-center space-y-1 text-center">
                                    <span class="font-medium">No results found.</span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logbook as $log): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-1 py-3 text-sm text-center text-gray-700">
                                    <a href="<?= $this->urlFor('admin.outpass.records.details', ['outpass_id' => $log->getOutpass()->getId()]) ?>" class="text-gray-600 hover:text-gray-800"># <?= htmlspecialchars($log->getOutpass()->getId()) ?></a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($log->getOutpass()->getStudent()->getUser()->getName()) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($log->getOutpass()->getStudent()->getDigitalId()) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($log->getOutpass()->getStudent()->getProgram()->getProgramName() . ' ' . $log->getOutpass()->getStudent()->getProgram()->getShortCode()) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    <?php
                                    $status = $log->getOutpass()->getStatus();
                                    $color = $status->color() ?? 'gray';
                                    ?>
                                    <span class="inline-block px-2 py-1 text-xs font-medium text-<?= $color ?>-800 bg-<?= $color ?>-100 rounded-full">
                                        <?= $status->label() ?>
                                    </span>
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
                                <td class="px-2 py-3 text-sm text-center text-gray-700">
                                    <?php
                                    $verifier = $log->getVerifier();
                                    $verifierName = htmlspecialchars($verifier->getVerifierName());
                                    $statusClass = $verifierName ? 'bg-green-500' : 'bg-red-500'; ?>

                                    <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                                        <a href="<?= $this->urlFor('admin.manage.verifiers') ?>" class="text-gray-600 hover:text-gray-800 hover:underline">
                                            <span class="inline-block mr-1 w-2.5 h-2.5 <?= $statusClass ?> rounded-full"></span>
                                            <?= $verifierName ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-600">
                                            <span class="inline-block mr-1 w-2.5 h-2.5 <?= $statusClass ?> rounded-full"></span>
                                            <?= $verifierName ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if ($records['totalPages'] > 1): ?>
                <!-- Pagination Section -->
                <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 bg-gray-50 sm:px-6">
                    <div class="flex justify-between sm:hidden">
                        <?php if ($records['currentPage'] > 1): ?>
                            <button
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                onclick="location.href='?page=<?= $records['currentPage'] - 1 ?>&q=<?= urlencode($search ?? '') ?>&date=<?= urlencode($filterDate ?? '') ?>&action=<?= urlencode($filterAction ?? '') ?>'">Previous</button>
                        <?php endif; ?>
                        <?php if ($records['currentPage'] < $records['totalPages']): ?>
                            <button
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                onclick="location.href='?page=<?= $records['currentPage'] + 1 ?>&q=<?= urlencode($search ?? '') ?>&date=<?= urlencode($filterDate ?? '') ?>&action=<?= urlencode($filterAction ?? '') ?>'">Next</button>
                        <?php endif; ?>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium"><?= ($records['currentPage'] - 1) * 10 + 1 ?></span>
                                to <span class="font-medium"><?= min($records['currentPage'] * 10, $records['totalRecords']) ?></span>
                                of <span class="font-medium"><?= $records['totalRecords'] ?></span> results
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <?php if ($records['currentPage'] > 1): ?>
                                <button
                                    class="px-3 py-1 text-sm text-gray-600 bg-gray-200 border rounded-md hover:bg-gray-300 focus:ring focus:ring-blue-300 focus:outline-none"
                                    onclick="location.href='?page=<?= $records['currentPage'] - 1 ?>&q=<?= urlencode($search ?? '') ?>&date=<?= urlencode($filterDate ?? '') ?>&action=<?= urlencode($filterAction ?? '') ?>'">Previous</button>
                            <?php endif; ?>
                            <?php if ($records['currentPage'] < $records['totalPages']): ?>
                                <button
                                    class="px-3 py-1 text-sm text-white bg-blue-600 border rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none"
                                    onclick="location.href='?page=<?= $records['currentPage'] + 1 ?>&q=<?= urlencode($search ?? '') ?>&date=<?= urlencode($filterDate ?? '') ?>&action=<?= urlencode($filterAction ?? '') ?>'">Next</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>