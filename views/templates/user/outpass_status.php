<?php

use App\Enum\OutpassStatus;
?>
<div class="min-h-screen antialiased bg-gray-50">

    <?= $this->getComponent('user/header', ['routeName' => $routeName]) ?>

    <main class="container px-6 py-8 mx-auto lg:px-12">

        <header class="flex flex-col py-4 mb-6 space-y-2 border-b border-gray-300 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Outpass Status</h1>
                <p class="mt-1 text-base text-gray-500">View your current and recent outpass status</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button onclick="window.location.href='<?= $this->urlFor('student.outpass.request') ?>'"
                    class="inline-flex items-center px-3 py-3 font-medium text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg shadow-sm text-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Apply for New Outpass
                </button>
            </div>
        </header>

        <?php if (empty($outpasses)): ?>
            <section class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-8 py-16 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 mb-6 rounded-full bg-blue-50">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="mb-3 text-xl font-semibold text-gray-900">No Outpass Found</h2>
                    <p class="max-w-md mx-auto mb-8 text-base text-gray-600">
                        It seems like you haven't applied for an outpass yet. Use the button above to create your first request.
                    </p>
                    <button onclick="window.location.href='<?= $this->urlFor('student.outpass.request') ?>'"
                        class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Your First Outpass
                    </button>
                </div>
            </section>

        <?php else: ?>
            <section class="mb-8">
                <div class="overflow-hidden transition-shadow duration-200 bg-white border border-gray-200 rounded-lg shadow-sm cursor-pointer hover:shadow-md"
                    onclick="window.location.href='<?= $this->urlFor('student.outpass.status') . '/' . $outpasses[0]->getId() ?>';">

                    <?php
                    $statusMapping = [
                        OutpassStatus::APPROVED->value => ['text' => 'Approved', 'color' => 'emerald', 'bgColor' => 'bg-emerald-50', 'textColor' => 'text-emerald-700', 'borderColor' => 'border-emerald-200', 'icon' => 'fa-check-circle'],
                        OutpassStatus::PENDING->value => ['text' => 'Pending', 'color' => 'amber', 'bgColor' => 'bg-amber-50', 'textColor' => 'text-amber-700', 'borderColor' => 'border-amber-200', 'icon' => 'fa-clock'],
                        OutpassStatus::PARENT_PENDING->value => ['text' => 'Parent Approval Pending', 'color' => 'blue', 'bgColor' => 'bg-blue-50', 'textColor' => 'text-blue-700', 'borderColor' => 'border-blue-200', 'icon' => 'fa-user-clock'],
                        OutpassStatus::PARENT_APPROVED->value => ['text' => 'Admin Review Pending', 'color' => 'blue', 'bgColor' => 'bg-blue-50', 'textColor' => 'text-blue-700', 'borderColor' => 'border-blue-200', 'icon' => 'fa-hourglass-half'],
                        OutpassStatus::REJECTED->value => ['text' => 'Rejected', 'color' => 'red', 'bgColor' => 'bg-red-50', 'textColor' => 'text-red-700', 'borderColor' => 'border-red-200', 'icon' => 'fa-times-circle'],
                        OutpassStatus::PARENT_DENIED->value => ['text' => 'Parent Denied', 'color' => 'red', 'bgColor' => 'bg-red-50', 'textColor' => 'text-red-700', 'borderColor' => 'border-red-200', 'icon' => 'fa-user-times'],
                        OutpassStatus::EXPIRED->value => ['text' => 'Expired', 'color' => 'slate', 'bgColor' => 'bg-slate-50', 'textColor' => 'text-slate-700', 'borderColor' => 'border-slate-200', 'icon' => 'fa-calendar-times'],
                    ];
                    $status = $statusMapping[$outpasses[0]->getStatus()->value];
                    ?>

                    <div class="h-1 bg-<?= $status['color'] ?>-400"></div>

                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-start gap-6 mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900">#<?= $outpasses[0]->getId() . ' ' . ucfirst($outpasses[0]->getTemplate()->getName()) ?></h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-base font-medium border <?= $status['bgColor'] ?> <?= $status['textColor'] ?> <?= $status['borderColor'] ?>">
                                        <i class="fa-solid <?= $status['icon'] ?> mr-1.5 text-lg"></i>
                                        <?= $status['text'] ?>
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6M7 7h10l1 10H6L7 7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-base font-medium text-gray-500 uppercase">Duration</p>
                                            <p class="text-lg font-medium text-gray-900"><?= $outpasses[0]->getFromDate()->format('M d') ?> - <?= $outpasses[0]->getToDate()->format('M d, Y') ?></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-base font-medium text-gray-500 uppercase">Destination</p>
                                            <p class="text-lg font-medium text-gray-900"><?= $outpasses[0]->getDestination() ?></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-base font-medium text-gray-500 uppercase">Applied On</p>
                                            <p class="text-lg font-medium text-gray-900"><?= $outpasses[0]->getCreatedAt()->format('M d, Y') ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center ml-6">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php if (count($outpasses) > 1): ?>
                <section class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">Recent Outpass</h2>
                            <a href
                                <?= $this->urlFor('student.outpass.history') ?>"
                                class="inline-flex items-center text-base font-medium text-blue-600 transition-colors duration-150 hover:text-blue-700">
                                View All History
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-200">
                        <?php foreach ($outpasses as $i => $pass): if ($i === 0) continue; ?>
                            <?php $status = $statusMapping[$pass->getStatus()->value]; ?>

                            <div class="p-6 transition-colors duration-150 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-start gap-6 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">#<?= $pass->getId() . ' ' . ucfirst($pass->getTemplate()->getName()) ?></h3>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-base font-medium border <?= $status['bgColor'] ?> <?= $status['textColor'] ?> <?= $status['borderColor'] ?>">
                                                <i class="fa-solid <?= $status['icon'] ?> mr-1.5 text-lg"></i>
                                                <?= $status['text'] ?>
                                            </span>
                                        </div>

                                        <div class="flex items-center space-x-6 text-base text-gray-600">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6M7 7h10l1 10H6L7 7z"></path>
                                                </svg>
                                                <span><?= $pass->getFromDate()->format('M d') ?> - <?= $pass->getToDate()->format('M d, Y') ?></span>
                                            </div>

                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span><?= $pass->getDestination() ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ml-4">
                                        <a href="<?= $this->urlFor('student.outpass.status') . '/' . $pass->getId() ?>"
                                            class="inline-flex items-center text-base font-medium text-blue-600 transition-colors duration-150 hover:text-blue-700">
                                            View Details
                                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</div>