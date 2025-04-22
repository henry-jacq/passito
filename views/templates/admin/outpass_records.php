<?php

use App\Enum\OutpassStatus;
?>
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="mb-4 text-2xl font-semibold text-gray-700">Outpass Records</h2>
    <p class="mb-8 text-base text-gray-700">View and manage the records of issued outpasses.</p>

    <?php if (empty($outpasses)): ?>
        <div class="p-6 space-y-2 leading-relaxed text-blue-800 border-l-4 rounded-lg shadow-md bg-blue-200/60 border-blue-800/80" role="alert" aria-live="polite">
            <h3 class="text-lg font-semibold">No Outpass Records Found</h3>
            <p class="text-sm">
                There are currently no outpass records available to display.
            </p>
        </div>
    <?php else: ?>
        <!-- Search and Filter Section -->
        <div class="p-6 mb-8 bg-white rounded-lg shadow">
            <!-- Row 1: Search Bar with Filter Button -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="relative flex-grow">
                    <input
                        type="text"
                        placeholder="Search by name or digital ID"
                        class="w-full p-2 pl-10 text-gray-600 transition duration-200 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        aria-label="Search by name or digital ID">
                    <span class="absolute text-gray-500 left-3 top-2">
                        <i class="fas fa-search"></i>
                    </span>
                </div>

                <!-- Filter Button -->
                <button
                    id="filter-button"
                    class="flex items-center px-4 py-2 text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none"
                    aria-expanded="false"
                    aria-controls="filter-area">
                    <i class="mr-1 fas fa-filter"></i>
                    <span>Filter</span>
                </button>
            </div>

            <!-- Collapsible Filter Area -->
            <div id="filter-area" class="hidden mt-4 transition-all duration-300 ease-in-out" data-expanded="">
                <!-- Filter Section -->
                <div class="p-4 mb-4 border border-gray-200 rounded-lg shadow-inner bg-gray-50">
                    <!-- Outpass Filters Section -->
                    <h3 class="mb-2 text-sm font-semibold text-gray-600 uppercase">Outpass Filters</h3>
                    <div class="flex flex-wrap items-center gap-6 mb-4">
                        <!-- Outpass Type Filter -->
                        <select
                            class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            aria-label="Outpass Type">
                            <option value="">Outpass Type</option>
                            <option value="weekend">Weekend</option>
                            <option value="emergency">Emergency</option>
                            <option value="medical">Medical</option>
                        </select>

                        <!-- Approval Date Filter -->
                        <input
                            type="date"
                            class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Approval Date"
                            aria-label="Approval Date">

                        <!-- Status Filter -->
                        <select
                            class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            aria-label="Status">
                            <option value="">Status</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <!-- Additional Filters -->
                    <h3 class="mb-2 text-sm font-semibold text-gray-600 uppercase">Student Filters</h3>
                    <div class="flex flex-wrap items-center gap-6 mb-4">
                        <!-- Year Filter -->
                        <select
                            class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            aria-label="Year">
                            <option value="">Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>

                        <!-- Branch Filter -->
                        <select
                            class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            aria-label="Branch">
                            <option value="">Branch</option>
                            <option value="CSE">CSE</option>
                            <option value="ECE">ECE</option>
                            <option value="ME">ME</option>
                        </select>

                        <!-- Institution Filter -->
                        <select
                            class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            aria-label="Institution">
                            <option value="">Institution</option>
                            <option value="institution1">Institution 1</option>
                            <option value="institution2">Institution 2</option>
                        </select>

                        <!-- Hostel Filter -->
                        <select
                            class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            aria-label="Hostel No">
                            <option value="">Hostel No.</option>
                            <option value="1">Hostel 1</option>
                            <option value="2">Hostel 2</option>
                        </select>
                    </div>
                </div>
            </div>
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
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($outpasses as $outpass): ?>
                        <tr onclick="location.href='<?= $this->urlFor('admin.outpass.records.details', ['outpass_id' => $outpass->getId()]) ?>'" class="cursor-pointer hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900"># <?= $outpass->getID() ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getUser()->getName() ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= formatStudentYear($outpass->getStudent()->getYear()) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getCourse() . ' ' . $outpass->getStudent()->getBranch() ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= ucwords($outpass->getPassType()->value) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getDestination() ?></td>
                            <td class="px-6 py-4 text-sm text-center text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $outpass->getStatus() === OutpassStatus::APPROVED ? 'green' : 'yellow' ?>-100 text-<?= $outpass->getStatus() === OutpassStatus::APPROVED ? 'green' : 'yellow' ?>-800">
                                    <?= ucwords(str_replace('_', ' ', $outpass->getStatus()->value)) ?>
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
                </tbody>
            </table>

            <?php if ($records['totalPages'] > 1): ?>
                <!-- Pagination Section -->
                <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 bg-gray-50 sm:px-6">
                    <div class="flex justify-between sm:hidden">
                        <?php if ($records['currentPage'] > 1): ?>
                            <button
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                onclick="location.href='?page=<?= $records['currentPage'] - 1 ?>'">Previous</button>
                        <?php endif; ?>
                        <?php if ($records['currentPage'] < $records['totalPages']): ?>
                            <button
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                onclick="location.href='?page=<?= $records['currentPage'] + 1 ?>'">Next</button>
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
                                    onclick="location.href='?page=<?= $records['currentPage'] - 1 ?>'">Previous</button>
                            <?php endif; ?>
                            <?php if ($records['currentPage'] < $records['totalPages']): ?>
                                <button
                                    class="px-3 py-1 text-sm text-white bg-blue-600 border rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none"
                                    onclick="location.href='?page=<?= $records['currentPage'] + 1 ?>'">Next</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>

<script>
    document.getElementById('filter-button').addEventListener('click', function() {
        const filterArea = document.getElementById('filter-area');
        const isHidden = filterArea.classList.contains('hidden');
        filterArea.classList.toggle('hidden', !isHidden);
        this.setAttribute('aria-expanded', isHidden);
    });
</script>