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

    <?php if (empty($outpasses)): ?>
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
        <div class="mb-8 rounded-lg ">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="relative flex-grow">
                    <input id="search-records" type="text" placeholder="Search records..."
                        class="w-full py-2 transition duration-200 border border-gray-300 rounded-md bg-gray-50 text-md ps-12 focus:outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-600/50"
                        aria-label="Search by digital ID">
                    <span class="absolute text-gray-500 left-3 top-2">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <div>
                    <select class="flex-grow p-2 text-gray-600 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 w-36 focus:border-blue-600/50 focus:ring-2 focus:ring-blue-600/50" aria-label="Outpass Type">
                        <option value="" disabled selected>
                            <i class="fa fa-filter"></i>
                            Filter By
                        </option>
                        <option value="home">Outpass ID</option>
                        <option value="emergency">Name</option>
                        <option value="medical">Course</option>
                        <option value="medical">Type</option>
                        <option value="medical">Status</option>
                    </select>
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
                <tbody id="records-table-body" class="divide-y divide-gray-200">
                    <?php foreach ($outpasses as $outpass): ?>
                        <tr onclick="location.href='<?= $this->urlFor('admin.outpass.records.details', ['outpass_id' => $outpass->getId()]) ?>'" class="cursor-pointer hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900"># <?= $outpass->getID() ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getUser()->getName() ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= formatStudentYear($outpass->getStudent()->getYear()) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $outpass->getStudent()->getProgram()->getProgramName() . ' ' . $outpass->getStudent()->getProgram()->getShortCode() ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= ucwords($outpass->getTemplate()->getName()) ?></td>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-records');
        const tableBody = document.getElementById('records-table-body');

        let timeout = null;
        const originalRows = Array.from(tableBody.children).map(row => row.cloneNode(true));

        searchInput.addEventListener('input', function() {
            const query = searchInput.value.trim();

            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if (query === '') {
                    tableBody.innerHTML = '';
                    originalRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
                    return;
                }

                fetch(`/api/web/admin/outpass/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.status && Array.isArray(data.data.data) && data.data.data.length > 0) {
                            renderTable(data.data.data);
                        } else {
                            tableBody.innerHTML = `
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500">No results found.</td>
                            </tr>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error during search:', error);
                        tableBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-red-500">Search failed. Please try again.</td>
                        </tr>`;
                    });
            }, 400);
        });

        function renderTable(outpasses) {
            tableBody.innerHTML = '';

            outpasses.forEach(outpass => {
                const row = document.createElement('tr');
                row.className = "cursor-pointer hover:bg-gray-50";
                row.onclick = () => {
                    window.location.href = `/admin/outpass/records/${outpass.id}`;
                };

                // Determine badge style
                const statusColor = {
                    approved: 'green',
                    rejected: 'red',
                    expired: 'yellow',
                    pending: 'blue'
                } [outpass.status.toLowerCase()] || 'gray';

                const statusBadge = `
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${statusColor}-100 text-${statusColor}-800">
                        ${capitalize(outpass.status)}
                    </span>`;

                row.innerHTML = `
                    <td class="px-6 py-4 text-sm text-gray-900"># ${outpass.id}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${outpass.student_name}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${formatStudentYear(outpass.year)}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${outpass.course} ${outpass.branch}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${outpass.type}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${outpass.destination}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-900">
                        ${statusBadge}
                    </td>
                    <td class="px-6 py-4">
                        <span class="block text-sm text-gray-900">${outpass.depart_date}</span>
                        <span class="block text-xs text-gray-600">${outpass.depart_time}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="block text-sm text-gray-900">${outpass.return_date}</span>
                        <span class="block text-xs text-gray-600">${outpass.return_time}</span>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        function capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
        }

        function formatStudentYear(year) {
            const lastDigit = year % 10;
            const lastTwoDigits = year % 100;

            let suffix;
            if (lastTwoDigits === 11 || lastTwoDigits === 12 || lastTwoDigits === 13) {
                suffix = 'th';
            } else {
                switch (lastDigit) {
                    case 1:
                        suffix = 'st';
                        break;
                    case 2:
                        suffix = 'nd';
                        break;
                    case 3:
                        suffix = 'rd';
                        break;
                    default:
                        suffix = 'th';
                }
            }

            return year + suffix;
        }
    });
</script>