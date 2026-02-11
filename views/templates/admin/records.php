<?php

use App\Enum\OutpassStatus;
?>
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="mb-6 space-y-2">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Outpass Records</h2>
            <p class="mb-10 text-gray-600 text-md">View and manage the records of issued outpasses</p>
        </div>
    </div>

    <?php if (empty($outpasses) && empty($search) && empty($filter) && empty($filterDate)): ?>
        <section class="flex flex-col items-center my-4 space-y-6 bg-white rounded-lg shadow-lg py-22">
            <div class="flex items-center justify-center w-16 h-16 text-blue-800 bg-blue-200 rounded-full shadow-inner">
                <i class="text-4xl fas fa-circle-info"></i>
            </div>
            <div class="text-center">
                <h2 class="text-xl font-bold text-blue-900">
                    No Outpass Records Found
                </h2>
                <p class="max-w-md mt-2 text-sm text-blue-800">
                    There are currently no outpass records available to display.
                </p>
            </div>
        </section>
    <?php else: ?>
        <div class="mb-8 rounded-lg">
            <form class="flex flex-wrap items-center justify-between gap-4" method="get" action="">
                <div class="relative flex-grow">
                    <input id="search-records" name="q" type="text" placeholder="Search by student name or digital ID..."
                        value="<?= $search ?? '' ?>"
                        class="w-full py-2 transition duration-200 border border-gray-300 rounded-md bg-gray-50 text-md ps-12 focus:outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-600/50"
                        aria-label="Search records">
                    <span class="absolute text-gray-500 left-3 top-2">
                        <i class="fas fa-search"></i>
                    </span>
                    <?php if (!empty($search)): ?>
                        <button
                            type="button"
                            class="absolute inset-y-0 flex items-center px-2 text-sm text-gray-500 right-2 hover:text-gray-700"
                            onclick="window.location.href='?'">
                            Clear
                        </button>
                    <?php else: ?>
                        <button
                            type="button"
                            id="search-records-button"
                            class="absolute inset-y-0 flex items-center px-2 text-sm text-blue-600 right-2 hover:text-blue-800"
                            aria-label="Apply search">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
                <div>
                    <input
                        id="filter-records-date"
                        name="date"
                        type="date"
                        value="<?= $filterDate ?? '' ?>"
                        class="p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 w-44 focus:border-blue-600/50 focus:ring-2 focus:ring-blue-600/50"
                        aria-label="Filter by date">
                </div>
                <div>
                    <select id="filter-records" name="filter" class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 w-44 focus:border-blue-600/50 focus:ring-2 focus:ring-blue-600/50" aria-label="Outpass status filter">
                        <option value="" <?= empty($filter) ? 'selected' : '' ?>>All Actions</option>
                        <option value="approved" <?= ($filter ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="expired" <?= ($filter ?? '') === 'expired' ? 'selected' : '' ?>>Expired</option>
                        <option value="rejected" <?= ($filter ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="parent_denied" <?= ($filter ?? '') === 'parent_denied' ? 'selected' : '' ?>>Parent Denied</option>
                    </select>
                </div>
            </form>
        </div>
        <section class="overflow-hidden bg-white rounded-lg shadow-md">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700"># ID</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Student Name</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Year</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Course</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Type</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Destination</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-center text-gray-700">Status</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Depart Time</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Return Time</th>
                    </tr>
                </thead>
                <tbody id="records-table-body" class="divide-y divide-gray-200">
                    <?php if (empty($outpasses) && !empty($search)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-6 text-sm text-gray-600">
                                <div class="flex flex-col items-center space-y-1 text-center">
                                    <span class="font-medium">No results found.</span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($outpasses as $outpass): ?>
                            <tr onclick="location.href='<?= $this->urlFor('admin.outpass.records.details', ['outpass_id' => $outpass->getId()]) ?>'" class="cursor-pointer hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900"># <?= $outpass->getId() ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getUser()->getName() ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= formatStudentYear($outpass->getStudent()->getYear()) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getProgram()->getProgramName() . ' ' . $outpass->getStudent()->getProgram()->getShortCode() ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= ucwords($outpass->getTemplate()->getName()) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getDestination() ?></td>
                                <td class="px-6 py-4 text-sm text-center text-gray-900">
                                    <?php
                                    $status = $outpass->getStatus();
                                    $color = $status->color() ?? 'gray';
                                    ?>
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full bg-<?= $color ?>-100 text-<?= $color ?>-800">
                                        <?= $status->label() ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="block text-sm text-gray-900">
                                        <?= $outpass->getFromDate()->format('d M, Y') ?>
                                    </span>
                                    <span class="block text-xs text-gray-600">
                                        <?= $outpass->getFromTime()->format('h:i A') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="block text-sm text-gray-900">
                                        <?= $outpass->getToDate()->format('d M, Y') ?>
                                    </span>
                                    <span class="block text-xs text-gray-600">
                                        <?= $outpass->getToTime()->format('h:i A') ?>
                                    </span>
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
                                onclick="location.href='?page=<?= $records['currentPage'] - 1 ?>&q=<?= urlencode($search ?? '') ?>&filter=<?= urlencode($filter ?? '') ?>&date=<?= urlencode($filterDate ?? '') ?>'">Previous</button>
                        <?php endif; ?>
                        <?php if ($records['currentPage'] < $records['totalPages']): ?>
                            <button
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                onclick="location.href='?page=<?= $records['currentPage'] + 1 ?>&q=<?= urlencode($search ?? '') ?>&filter=<?= urlencode($filter ?? '') ?>&date=<?= urlencode($filterDate ?? '') ?>'">Next</button>
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
                                    onclick="location.href='?page=<?= $records['currentPage'] - 1 ?>&q=<?= urlencode($search ?? '') ?>&filter=<?= urlencode($filter ?? '') ?>&date=<?= urlencode($filterDate ?? '') ?>'">Previous</button>
                            <?php endif; ?>
                            <?php if ($records['currentPage'] < $records['totalPages']): ?>
                                <button
                                    class="px-3 py-1 text-sm text-white bg-blue-600 border rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none"
                                    onclick="location.href='?page=<?= $records['currentPage'] + 1 ?>&q=<?= urlencode($search ?? '') ?>&filter=<?= urlencode($filter ?? '') ?>&date=<?= urlencode($filterDate ?? '') ?>'">Next</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>