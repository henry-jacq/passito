<?php

use App\Enum\OutpassStatus; ?>

<div class="flex flex-col min-h-screen bg-gray-50">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container px-6 py-8 mx-auto lg:px-12">
        <!-- Page Title -->
        <header class="flex flex-col py-4 mb-6 space-y-2 border-b sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Outpass History</h1>
                <p class="mt-1 text-base text-gray-500">Manage your outpass requests history.</p>
            </div>
            <!-- Search Bar -->
            <form action="" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" placeholder="Search by Outpass ID or Date"
                    class="w-full px-4 py-2 border rounded-lg sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit"
                    class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none">
                    Search
                </button>
            </form>
        </header>

        <!-- Status Table Section -->
        <section class="p-8 bg-white rounded-lg shadow">
            <!-- Status Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="text-xs font-medium text-gray-700 uppercase bg-blue-100">
                            <th class="px-4 py-3">Outpass ID</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Purpose</th>
                            <th class="px-4 py-3">Dates</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($outpasses as $outpass): ?>
                            <tr class="hover:bg-blue-50">
                                <td class="px-4 py-3"><?= $outpass->getId() ?></td>
                                <td class="px-4 py-3"><?= ucwords($outpass->getPassType()->value) ?></td>
                                <td class="px-4 py-3"><?= $outpass->getPurpose() ?></td>
                                <td class="px-4 py-3">
                                    <span class="block text-sm text-gray-800">
                                        <?= $outpass->getFromDate()->format('d M, Y') ?> - <?= $outpass->getToDate()->format('d M, Y') ?>
                                    </span>
                                    <span class="block text-xs text-gray-500"><?= $outpass->getFromTime()->format('h:i A') ?> - <?= $outpass->getToTime()->format('h:i A') ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php
                                    $status = $outpass->getStatus();
                                    switch ($status) {
                                        case OutpassStatus::APPROVED:
                                            $badgeClass = 'text-green-800 bg-green-100';
                                            break;
                                        case OutpassStatus::PENDING:
                                            $badgeClass = 'text-yellow-800 bg-yellow-100';
                                            break;
                                        case OutpassStatus::REJECTED:
                                            $badgeClass = 'text-red-800 bg-red-100';
                                            break;
                                        case OutpassStatus::EXPIRED:
                                            $badgeClass = 'text-gray-800 bg-gray-100';
                                            break;
                                        default:
                                            $badgeClass = 'text-gray-800 bg-gray-100';
                                    } ?>
                                    <span class="px-3 py-1 text-sm font-medium <?= $badgeClass ?> rounded-full"><?= ucwords($outpass->getStatus()->value) ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="#" class="text-blue-600 hover:underline">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-6 space-x-4">
                <button class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Previous
                </button>
                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Next
                </button>
            </div>
        </section>
    </main>
</div>