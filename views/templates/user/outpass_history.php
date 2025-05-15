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
        </header>

        <?php if (empty($outpasses)): ?>
            <section class="flex flex-col items-center my-4 space-y-6 bg-white rounded-lg shadow-lg py-22">
                <div class="flex items-center justify-center w-16 h-16 text-indigo-600 bg-indigo-100 rounded-full shadow-inner animate-bounce">
                    <i class="text-4xl fas fa-exclamation-circle"></i>
                </div>
                <div class="text-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        History Not Found
                    </h2>
                    <p class="max-w-md mt-2 text-base text-gray-600">
                        Your history is empty. If you have applied for an outpass, it will appear here once processed.
                    </p>
                </div>
            </section>
        <?php else: ?>

            <!-- Status Table Section -->
            <section class="p-8 bg-white rounded-lg shadow">
                <!-- Status Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="text-xs font-medium text-gray-700 uppercase bg-blue-100">
                                <th class="px-4 py-3">Outpass ID</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Reason</th>
                                <th class="px-4 py-3">Dates</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($outpasses as $outpass): ?>
                                <tr class="hover:bg-blue-50">
                                    <td class="px-4 py-3"><?= $outpass->getId() ?></td>
                                    <td class="px-4 py-3"><?= ucwords($outpass->getTemplate()->getName()) ?></td>
                                    <td class="px-4 py-3"><?= $outpass->getReason() ?></td>
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
        <?php endif; ?>
    </main>
</div>