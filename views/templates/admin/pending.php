<?php

use App\Enum\UserRole; ?>

<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="mb-6 space-y-2">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Pending Requests</h2>
            <p class="mb-10 text-gray-600 text-md">Manage pending requests by approving, rejecting, or wiping them out.</p>
        </div>
    </div>

    <?php if (empty($pendingCount)): ?>
        <section class="flex flex-col items-center my-4 space-y-6 bg-white rounded-lg shadow-lg py-22">
            <div class="flex items-center justify-center w-16 h-16 text-blue-800 bg-blue-200 rounded-full shadow-inner">
                <i class="text-4xl fas fa-circle-info"></i>
            </div>
            <div class="text-center">
                <h2 class="text-xl font-bold text-blue-900">
                    No Pending Outpasses Found
                </h2>
                <p class="max-w-md mt-2 text-sm text-blue-800">
                    Currently, there are no pending outpass requests awaiting approval.
                </p>
            </div>
        </section>
    <?php else: ?>
        <?php if (UserRole::isAdmin($user->getRole()->value)): ?>
            <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-end">
                <div class="sm:flex-1">
                    <label for="hostelFilter" class="block mb-2 text-sm font-medium text-gray-700">Filter by Hostel</label>
                    <select id="hostelFilter" name="hostelFilter"
                        class="w-64 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        onchange="handleHostelFilterChange(this.value)">
                        <option value="default" <?= $hostelFilter == 'default' ? 'selected' : '' ?>>My Hostels</option>
                        <?php foreach ($unassignedHostels as $hostel): ?>
                            <option value="<?= $hostel->getId() ?>" <?= $hostelFilter == $hostel->getId() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($hostel->getName(), ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- <div class="flex gap-3 sm:ml-auto">
                    <button
                        class="inline-flex items-center justify-center px-5 py-2 mt-2 text-sm font-medium text-white transition duration-200 bg-green-600 rounded-lg shadow hover:bg-green-700 sm:mt-0">
                        <i class="mr-2 fas fa-check"></i> Approve All
                    </button>
                    <button
                        class="inline-flex items-center justify-center px-5 py-2 mt-2 text-sm font-medium text-white transition duration-200 bg-red-600 rounded-lg shadow hover:bg-red-700 sm:mt-0">
                        <i class="mr-2 fas fa-trash-alt"></i> Reject All
                    </button>
                </div> -->
            </div>
        <?php endif; ?>

        <?php if (empty($outpasses)): ?>
            <div class="flex items-start p-4 mb-8 text-yellow-800 border border-yellow-300 rounded-lg shadow-sm bg-yellow-50">
                <div>
                    <h3 class="text-sm font-medium">No pending outpasses</h3>
                    <p class="text-sm text-yellow-700">There are currently no pending outpass requests for the selected hostel.</p>
                </div>
            </div>
        <?php else: ?>
            <section class="overflow-hidden bg-white rounded-lg shadow-md select-none">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700"># ID</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Student Name</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">Year</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Course</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Type</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Destination</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Date & Duration</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">Files</th>
                            <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($outpasses as $outpass): ?>
                            <tr onclick="location.href='<?= $this->urlFor('admin.outpass.records.details', ['outpass_id' => $outpass->getId()]) ?>'" class="cursor-pointer hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-center"># <?= htmlspecialchars($outpass->getId()) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getUser()->getName() ?></td>
                                <td class="px-6 py-4 text-sm text-center text-gray-900"><?= formatStudentYear($outpass->getStudent()->getYear()) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getProgram()->getProgramName() . ' ' . $outpass->getStudent()->getProgram()->getShortCode() ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= ucwords($outpass->getTemplate()->getName()) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getDestination() ?></td>
                                <td class="px-6 py-4">
                                    <span class="block text-sm text-gray-900">
                                        <?= $outpass->getFromDate()->format('d M, Y') ?> - <?= $outpass->getToDate()->format('d M, Y') ?>
                                    </span>
                                    <span class="block text-xs italic text-gray-600">
                                        <?= $outpass->getFromTime()->format('h:i A') ?> - <?= $outpass->getToTime()->format('h:i A') ?>
                                    </span>
                                </td>

                                <td class="relative px-6 py-4 overflow-visible text-sm text-center">
                                    <?php if (!empty($outpass->getAttachments())): ?>
                                        <div class="relative inline-block text-center">
                                            <?php $attachments = $outpass->getAttachments();
                                            $attachmentCount = count($attachments); ?>

                                            <?php if ($attachmentCount === 0): ?>
                                                <span class="text-gray-500">N/A</span>
                                            <?php elseif ($attachmentCount === 1): ?>
                                                <?php $attachment = $attachments[0];
                                                $url = htmlspecialchars($this->urlFor('storage.admin', [
                                                    'id' => $user->getId(),
                                                    'params' => $attachment
                                                ])); ?>
                                                <a href="<?= $url ?>" target="_blank" class="flex items-center space-x-1 text-indigo-500 stop-bubbling hover:underline">
                                                    <i class="fa-solid fa-link"></i>
                                                    <span>View</span>
                                                </a>
                                            <?php else: ?>
                                                <button class="flex items-center space-x-1 text-indigo-500 view-attachments stop-bubbling hover:underline focus:outline-none" data-id="<?= $outpass->getId() ?>">
                                                    <i class="fa-solid fa-link"></i>
                                                    <span>View (<?= $attachmentCount ?>)</span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400">N/A</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 space-x-2 text-sm font-medium text-center whitespace-normal">
                                    <button class="text-green-600 transition duration-200 stop-bubbling hover:text-green-900 accept-outpass" data-id="<?= $outpass->getId() ?>"><i class="mr-1 fas fa-circle-check"></i>Accept</button>
                                    <button class="text-red-600 transition duration-200 stop-bubbling hover:text-red-900 reject-outpass" data-id="<?= $outpass->getId() ?>"><i class="mr-1 fas fa-trash-alt"></i>Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($records['totalPages'] > 1): ?>
                    <!-- Pagination Section -->
                    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 bg-gray-50 sm:px-6">
                        <div class="flex justify-between sm:hidden">
                            <button class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</button>
                            <button class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</button>
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
                                <?php
                                // Get current query params
                                $queryParams = ['hostel' => $hostelFilter, 'page' => $records['currentPage']];
                                ?>

                                <?php if ($records['currentPage'] > 1): ?>
                                    <?php
                                    $prevQuery = array_merge($queryParams, ['page' => $records['currentPage'] - 1]);
                                    $prevUrl = $this->urlFor($routeName, [], $prevQuery);
                                    ?>
                                    <button class="px-3 py-1 text-sm text-gray-600 bg-gray-200 border rounded-md hover:bg-gray-300 focus:ring focus:ring-blue-300 focus:outline-none" onclick="location.href='<?= $prevUrl ?>'">Previous</button>
                                <?php endif; ?>

                                <?php if ($records['currentPage'] < $records['totalPages']): ?>
                                    <?php
                                    $nextQuery = array_merge($queryParams, ['page' => $records['currentPage'] + 1]);
                                    $nextUrl = $this->urlFor($routeName, [], $nextQuery);
                                    ?>
                                    <button class="px-3 py-1 text-sm text-white bg-blue-600 border rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none" onclick="location.href='<?= $nextUrl ?>'">Next</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</main>
<script>
    function toggleDropdown(button) {
        const originalDropdown = button.parentElement.querySelector('.dropdown-menu');
        if (originalDropdown.classList.contains('hidden')) {
            document.body.appendChild(originalDropdown);
            const rect = button.getBoundingClientRect();
            originalDropdown.style.width = '16rem';
            originalDropdown.style.position = 'absolute';
            originalDropdown.style.top = (rect.bottom + window.scrollY + 8) + 'px';
            originalDropdown.style.left = (rect.left + window.scrollX + rect.width / 2 - originalDropdown.offsetWidth / 2) + 'px';
            originalDropdown.classList.remove('hidden');
        } else {
            originalDropdown.classList.add('hidden');
        }
    }

    document.addEventListener('click', function(e) {
        document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
            if (!menu.contains(e.target) && !e.target.closest('[onclick="toggleDropdown(this)"]')) {
                menu.classList.add('hidden');
            }
        });
    });

    function handleHostelFilterChange(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('hostel', value);
        window.location.href = url.toString();
    }
</script>