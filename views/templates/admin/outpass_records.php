<?php

use App\Enum\OutpassStatus;
?>
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Outpass Records</h2>
    <p class="text-gray-700 text-base mb-8">View and manage the records of issued outpasses.</p>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <!-- Row 1: Search Bar with Filter Button -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="relative flex-grow">
                <input
                    type="text"
                    placeholder="Search by name or digital ID"
                    class="border border-gray-300 rounded-lg p-2 pl-10 w-full text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                    aria-label="Search by name or digital ID">
                <span class="absolute left-3 top-2 text-gray-500">
                    <i class="fas fa-search"></i>
                </span>
            </div>

            <!-- Filter Button -->
            <button
                id="filter-button"
                class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 focus:outline-none transition duration-200 flex items-center"
                aria-expanded="false"
                aria-controls="filter-area">
                <i class="fas fa-filter mr-1"></i>
                <span>Filter</span>
            </button>
        </div>

        <!-- Collapsible Filter Area -->
        <div id="filter-area" class="hidden mt-4 transition-all duration-300 ease-in-out">
            <!-- Filter Section -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-inner mb-4 border border-gray-200">
                <!-- Outpass Filters Section -->
                <h3 class="uppercase font-semibold text-sm text-gray-600 mb-2">Outpass Filters</h3>
                <div class="flex flex-wrap gap-6 items-center mb-4">
                    <!-- Outpass Type Filter -->
                    <select
                        class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                        aria-label="Outpass Type">
                        <option value="">Outpass Type</option>
                        <option value="weekend">Weekend</option>
                        <option value="emergency">Emergency</option>
                        <option value="medical">Medical</option>
                    </select>

                    <!-- Approval Date Filter -->
                    <input
                        type="date"
                        class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                        placeholder="Approval Date"
                        aria-label="Approval Date">

                    <!-- Status Filter -->
                    <select
                        class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                        aria-label="Status">
                        <option value="">Status</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <!-- Additional Filters -->
                <h3 class="uppercase font-semibold text-sm text-gray-600 mb-2">Student Filters</h3>
                <div class="flex flex-wrap gap-6 items-center mb-4">
                    <!-- Year Filter -->
                    <select
                        class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                        aria-label="Year">
                        <option value="">Year</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>

                    <!-- Branch Filter -->
                    <select
                        class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                        aria-label="Branch">
                        <option value="">Branch</option>
                        <option value="CSE">CSE</option>
                        <option value="ECE">ECE</option>
                        <option value="ME">ME</option>
                    </select>

                    <!-- Institution Filter -->
                    <select
                        class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                        aria-label="Institution">
                        <option value="">Institution</option>
                        <option value="institution1">Institution 1</option>
                        <option value="institution2">Institution 2</option>
                    </select>

                    <!-- Hostel Filter -->
                    <select
                        class="flex-grow border border-gray-300 rounded-lg p-2 text-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                        aria-label="Hostel No">
                        <option value="">Hostel No.</option>
                        <option value="1">Hostel 1</option>
                        <option value="2">Hostel 2</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Records Table
    ?>
    <section class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Student Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Course</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Year</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Destination</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date & Duration</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($outpasses as $outpass): ?>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getUser()->getName() ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getDepartment() . ' ' . $outpass->getStudent()->getBranch() ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?= formatStudentYear($outpass->getStudent()->getYear()) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?= ucwords($outpass->getPassType()->value) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getDestination() ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $outpass->getStatus() === OutpassStatus::APPROVED ? 'green' : 'yellow' ?>-100 text-<?= $outpass->getStatus() === OutpassStatus::APPROVED ? 'green' : 'yellow' ?>-800">
                                <?= ucwords($outpass->getStatus()->value) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="block text-sm text-gray-900">
                                <?= $outpass->getFromDate()->format('d M, Y') ?> - <?= $outpass->getToDate()->format('d M, Y') ?>
                            </span>
                            <span class="block text-xs text-gray-600">
                                <?= $outpass->getFromTime()->format('h:i A') ?> - <?= $outpass->getToTime()->format('h:i A') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-normal text-sm text-center font-medium space-x-2">
                            <button class="text-indigo-600 hover:text-indigo-900 transition duration-200" data-id="<?= $outpass->getId() ?>"><i class="fas fa-eye mr-1"></i>View</button>
                            <button class="text-red-600 hover:text-red-900 transition duration-200" data-id="<?= $outpass->getId() ?>"><i class="fas fa-trash-alt mr-1"></i>Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($records['totalPages'] > 1): ?>
            <!-- Pagination Section -->
            <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-3 sm:px-6">
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
                                class="px-3 py-1 border rounded-md bg-gray-200 text-sm text-gray-600 hover:bg-gray-300 focus:ring focus:ring-blue-300 focus:outline-none"
                                onclick="location.href='?page=<?= $records['currentPage'] - 1 ?>'">Previous</button>
                        <?php endif; ?>
                        <?php if ($records['currentPage'] < $records['totalPages']): ?>
                            <button
                                class="px-3 py-1 border rounded-md bg-blue-600 text-sm text-white hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none"
                                onclick="location.href='?page=<?= $records['currentPage'] + 1 ?>'">Next</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
    document.getElementById('filter-button').addEventListener('click', function() {
        const filterArea = document.getElementById('filter-area');
        const isHidden = filterArea.classList.contains('hidden');
        filterArea.classList.toggle('hidden', !isHidden);
        this.setAttribute('aria-expanded', isHidden);
    });
</script>