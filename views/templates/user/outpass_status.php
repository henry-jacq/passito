<?php

use App\Enum\OutpassStatus; ?>
<div class="min-h-screen bg-gray-100">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container mx-auto py-8 px-6 lg:px-12">
        <!-- Page Title -->
        <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-4 border-b space-y-2 sm:space-y-0 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Outpass Status</h1>
                <p class="text-base text-gray-500 mt-1">View your current and recent outpass status.</p>
            </div>
            <!-- Apply Outpass Button -->
            <div class="mt-4 md:mt-0">
                <button onclick="window.location.href='<?= $this->urlFor('student.outpass.request') ?>'" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow-md transition focus:outline-none focus:ring focus:ring-blue-300">
                    Apply for New Outpass
                </button>
            </div>
        </header>

        <?php if (empty($outpasses)): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center text-gray-600">No outpass records found.</div>
        <?php else: ?>
            <!-- Current Outpass Section -->
            <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-700 mb-4">Latest Outpass</h2>
                <div class="flex items-center bg-gray-50 text-gray-800 rounded-lg p-6 shadow-sm hover:shadow-md transition border-l-4 border-gray-200 cursor-pointer"
                    onclick="window.location.href='<?= $this->urlFor('student.outpass.status') . '/' . $outpasses[0]->getId() ?>';">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold"><?= ucfirst($outpasses[0]->getPassType()->value) . ' Pass' ?></h3>
                        <p class="mt-2 text-gray-600">Outpass from <strong><?= $outpasses[0]->getFromDate()->format('Y-m-d') ?></strong> to <strong><?= $outpasses[0]->getToDate()->format('Y-m-d') ?></strong></p>
                        <p class="mt-1 text-gray-500">Destination: <?= $outpasses[0]->getDestination() ?></p>
                    </div>
                    <div class="ml-6 flex items-center space-x-3">
                        <?php
                        $statusMapping = [
                            OutpassStatus::APPROVED->value => ['text' => 'Approved', 'color' => 'green', 'icon' => 'fa-check'],
                            OutpassStatus::PENDING->value => ['text' => 'Pending', 'color' => 'yellow', 'icon' => 'fa-clock'],
                            OutpassStatus::REJECTED->value => ['text' => 'Rejected', 'color' => 'red', 'icon' => 'fa-xmark'],
                            OutpassStatus::EXPIRED->value => ['text' => 'Expired', 'color' => 'gray', 'icon' => 'fa-hourglass-end'],
                        ];
                        $status = $statusMapping[$outpasses[0]->getStatus()->value];
                        ?>
                        <span class="px-3 py-1 rounded-full bg-<?= $status['color'] ?>-200 text-<?= $status['color'] ?>-800 text-base font-medium">
                            <i class="fa-solid <?= $status['icon'] ?> me-1"></i> <?= $status['text'] ?>
                        </span>
                    </div>
                </div>
            </section>

            <!-- Recent Outpasses Section -->
            <section class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-700">Past Outpasses</h2>
                    <a href="<?= $this->urlFor('student.outpass.history') ?>" class="text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">
                        View All History &rarr;
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($outpasses as $i => $pass): if ($i === 0) continue; ?>
                        <?php $status = $statusMapping[$pass->getStatus()->value]; ?>
                        <div class="bg-gray-50 border-l-4 border-gray-200 rounded-lg p-5 shadow-md hover:shadow-lg transition">
                            <h3 class="text-lg font-semibold text-gray-700"><?= ucfirst($pass->getPassType()->value) . ' Pass' ?></h3>
                            <p class="mt-2 text-gray-600">Outpass from <strong><?= $pass->getFromDate()->format('Y-m-d') ?></strong> to <strong><?= $pass->getToDate()->format('Y-m-d') ?></strong></p>
                            <p class="text-gray-500">Destination: <?= $pass->getDestination() ?></p>
                            <div class="mt-4 flex justify-between items-center">
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-<?= $status['color'] ?>-100 text-<?= $status['color'] ?>-800">
                                    <i class="fa <?= $status['icon'] ?> me-2"></i><?= $status['text'] ?>
                                </span>
                                <a href="<?= $this->urlFor('student.outpass.status') . '/' . $pass->getId() ?>" class="text-sm text-blue-500 hover:underline">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
</div>