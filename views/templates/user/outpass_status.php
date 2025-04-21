<?php

use App\Enum\OutpassStatus; ?>
<div class="min-h-screen bg-gray-100">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container px-6 py-8 mx-auto lg:px-12">
        <!-- Page Title -->
        <header class="flex flex-col py-4 mb-6 space-y-2 border-b sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Outpass Status</h1>
                <p class="mt-1 text-base text-gray-500">View your current and recent outpass status.</p>
            </div>
            <!-- Apply Outpass Button -->
            <div class="mt-4 md:mt-0">
                <button onclick="window.location.href='<?= $this->urlFor('student.outpass.request') ?>'" class="px-4 py-2 text-white transition bg-blue-600 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                    Apply for New Outpass
                </button>
            </div>
        </header>

        <?php if (empty($outpasses)): ?>
            <section class="flex flex-col items-center my-4 space-y-6 bg-white rounded-lg shadow-lg py-22">
                <div class="flex items-center justify-center w-16 h-16 text-indigo-600 bg-indigo-100 rounded-full shadow-inner animate-bounce">
                    <i class="text-4xl fas fa-exclamation-circle"></i>
                </div>
                <div class="text-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        No Outpass Found
                    </h2>
                    <p class="max-w-md mt-2 text-base text-gray-600">
                        It seems like you haven’t applied for an outpass yet. Use the button in the top-right corner to create one.
                    </p>
                </div>
            </section>


        <?php else: ?>
            <!-- Current Outpass Section -->
            <section class="p-6 mb-8 bg-white rounded-lg shadow-lg">
                <h2 class="mb-4 text-2xl font-bold text-gray-700">Latest Outpass</h2>
                <div class="flex items-center p-6 text-gray-800 transition border-l-4 border-gray-200 rounded-lg shadow-sm cursor-pointer bg-gray-50 hover:shadow-md"
                    onclick="window.location.href='<?= $this->urlFor('student.outpass.status') . '/' . $outpasses[0]->getId() ?>';">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold"><?= ucfirst($outpasses[0]->getPassType()->value) . ' Pass' ?></h3>
                        <p class="mt-2 text-gray-600">Outpass from <strong><?= $outpasses[0]->getFromDate()->format('Y-m-d') ?></strong> to <strong><?= $outpasses[0]->getToDate()->format('Y-m-d') ?></strong></p>
                        <p class="mt-1 text-gray-500">Destination: <?= $outpasses[0]->getDestination() ?></p>
                    </div>
                    <div class="flex items-center ml-6 space-x-3">
                        <?php
                        $statusMapping = [
                            OutpassStatus::APPROVED->value => ['text' => 'Approved', 'color' => 'green', 'icon' => 'fa-check'],
                            OutpassStatus::PENDING->value => ['text' => 'Pending', 'color' => 'yellow', 'icon' => 'fa-clock'],
                            OutpassStatus::PARENT_PENDING->value => ['text' => 'Pending', 'color' => 'yellow', 'icon' => 'fa-clock'],
                            OutpassStatus::PARENT_APPROVED->value => ['text' => 'Pending', 'color' => 'yellow', 'icon' => 'fa-clock'],
                            OutpassStatus::REJECTED->value => ['text' => 'Rejected', 'color' => 'red', 'icon' => 'fa-xmark'],
                            OutpassStatus::PARENT_DENIED->value => ['text' => 'Rejected', 'color' => 'red', 'icon' => 'fa-xmark'],
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

            <?php if (count($outpasses) > 1): ?>
                <section class="p-6 bg-white rounded-lg shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-semibold text-gray-700">Past Outpasses</h2>
                        <a href="<?= $this->urlFor('student.outpass.history') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                            View All History &rarr;
                        </a>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($outpasses as $i => $pass): if ($i === 0) continue; ?>
                            <?php $status = $statusMapping[$pass->getStatus()->value]; ?>
                            <div class="p-5 transition border-l-4 border-gray-200 rounded-lg shadow-md bg-gray-50 hover:shadow-lg">
                                <h3 class="text-lg font-semibold text-gray-700"><?= ucfirst($pass->getPassType()->value) . ' Pass' ?></h3>
                                <p class="mt-2 text-gray-600">Outpass from <strong><?= $pass->getFromDate()->format('Y-m-d') ?></strong> to <strong><?= $pass->getToDate()->format('Y-m-d') ?></strong></p>
                                <p class="text-gray-500">Destination: <?= $pass->getDestination() ?></p>
                                <div class="flex items-center justify-between mt-4">
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
        <?php endif; ?>
    </main>
</div>