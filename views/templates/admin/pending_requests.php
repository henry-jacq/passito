<!-- Pending Requests Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Pending Requests</h2>
    <p class="text-gray-600 text-md mb-8">Manage pending requests by approving, rejecting, or wiping them out.</p>

    <?php if (empty($outpasses)): ?>
        <div class="bg-indigo-50 border-l-4 space-y-2 rounded-lg border-indigo-400 text-indigo-800 p-6 shadow-md leading-relaxed" role="alert" aria-live="polite">
            <h3 class="text-lg font-semibold">No Pending Outpasses Found</h3>
            <p class="text-sm">
                There are currently no pending outpass requests awaiting approval.
            </p>
        </div>
    <?php else: ?>
        <section class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Student Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Course</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Year</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Destination</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Purpose</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date & Duration</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Files</th>
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
                            <td class="px-6 py-4 text-sm text-gray-900 truncate"><?= $outpass->getPurpose() ?></td>
                            <td class="px-6 py-4">
                                <span class="block text-sm text-gray-900">
                                    <?= $outpass->getFromDate()->format('d M, Y') ?> - <?= $outpass->getToDate()->format('d M, Y') ?>
                                </span>
                                <span class="block text-xs text-gray-600">
                                    <?= $outpass->getFromTime()->format('h:i A') ?> - <?= $outpass->getToTime()->format('h:i A') ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-center relative overflow-visible">
                                <?php if (!empty($outpass->getAttachments())): ?>
                                    <div class="relative inline-block text-center">
                                        <!-- Dropdown trigger -->
                                        <button onclick="toggleDropdown(this)"
                                            class="flex items-center space-x-1 text-indigo-500 hover:underline focus:outline-none">
                                            <i class="fa-solid fa-link"></i>
                                            <span>View (<?= count($outpass->getAttachments()) ?>)</span>
                                        </button>
                                        <div class="dropdown-menu hidden absolute z-50 mt-2 left-1/2 transform -translate-x-1/2">
                                            <div class="relative bg-white border border-gray-300 rounded-md shadow-lg px-2 pb-2">
                                                <!-- Dropdown content -->
                                                <div class="mt-2">
                                                    <?php foreach ($outpass->getAttachments() as $index => $attachment): ?>
                                                        <a href="<?= htmlspecialchars($this->urlFor('storage.admin', ['id' => $user->getId(), 'params' => $attachment])) ?>"
                                                            target="_blank"
                                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fa-solid fa-file mr-2"></i>
                                                            <span>File <?= $index + 1 ?></span>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400">No Files</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 whitespace-normal text-sm text-center font-medium space-x-2">
                                <button class="text-green-600 hover:text-green-900 transition duration-200 accept-outpass" data-id="<?= $outpass->getId() ?>"><i class="fas fa-circle-check mr-1"></i>Accept</button>
                                <button class="text-red-600 hover:text-red-900 transition duration-200 reject-outpass" data-id="<?= $outpass->getId() ?>"><i class="fas fa-trash-alt mr-1"></i>Reject</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (getLength($outpasses) > 10): ?>
                <!-- Pagination Section -->
                <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-3 sm:px-6">
                    <div class="flex justify-between sm:hidden">
                        <button class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</button>
                        <button class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</button>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">100</span> results
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="px-3 py-1 border rounded-md bg-gray-200 text-sm text-gray-600 hover:bg-gray-300 focus:ring focus:ring-blue-300 focus:outline-none">Previous</button>
                            <button class="px-3 py-1 border rounded-md bg-blue-600 text-sm text-white hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none">Next</button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
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
</script>