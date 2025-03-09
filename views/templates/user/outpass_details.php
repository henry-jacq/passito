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
                <h1 class="text-3xl font-bold text-gray-800">Outpass <?= '#' . $outpass->getId() ?> Details</h1>
                <p class="text-base text-gray-500 mt-1">View your outpass request details.</p>
            </div>
            <!-- Apply outpass button -->
            <div class="mt-4 md:mt-0">
                <?php $document = $outpass->getDocument();
                $downloadUrl = $this->urlFor('storage.student', [
                    'id' => $outpass->getStudent()->getUser()->getId(),
                    'params' => 'outpasses/' . $document
                ]); ?>
                <a <?php if ($document): ?>
                    href="<?= htmlspecialchars($downloadUrl) ?>" download
                    <?php else: ?>
                    href="javascript:void(0)" aria-disabled="true" tabindex="-1"
                    <?php endif; ?>
                    class="px-4 py-2 text-white bg-blue-600 rounded-md shadow-md transition focus:outline-none inline-flex items-center <?= $document ? 'hover:bg-blue-700 focus:ring focus:ring-blue-300' : 'opacity-50 cursor-not-allowed' ?>">
                    <i class="fa-solid fa-arrow-down mr-1"></i>
                    <span>Download Outpass</span>
                </a>
            </div>
        </header>

        <!-- Outpass Details Section -->
        <section class="bg-white rounded-xl shadow-md p-6 md:p-8 mb-8">
            <!-- Row 1: Dates and Times -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-9">
                <div class="flex items-center align-center space-x-4">
                    <i class="fa-regular fa-clock text-2xl text-gray-500"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">From Date & Time</label>
                        <p class="text-base md:text-lg text-gray-800 font-medium mt-1"><?= $outpass->getFromDate()->format('Y-m-d, ') . $outpass->getFromTime()->format('h:i A') ?></p>
                    </div>
                </div>
                <div class="flex items-center align-center space-x-4">
                    <i class="fa-regular fa-clock text-2xl text-gray-500"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">To Date & Time</label>
                        <p class="text-base md:text-lg text-gray-800 font-medium mt-1"><?= $outpass->getToDate()->format('Y-m-d, ') . $outpass->getToTime()->format('h:i A') ?></p>
                    </div>
                </div>
            </div>

            <!-- Row 2: Pass Type and Destination -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-9">
                <div class="flex items-center align-center space-x-4">
                    <i class="fa-regular fa-id-card text-2xl text-gray-500"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">Pass Type</label>
                        <p class="text-base md:text-lg text-gray-800 font-medium mt-1"><?= ucfirst($outpass->getPassType()->value) ?></p>
                    </div>
                </div>
                <div class="flex items-center align-center space-x-4">
                    <i class="fa-solid fa-location-dot text-2xl text-gray-500"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">Destination</label>
                        <p class="text-base md:text-lg text-gray-800 font-medium mt-1"><?= ucwords($outpass->getDestination()) ?></p>
                    </div>
                </div>
            </div>

            <!-- Split Columns: Status, Purpose, and QR Code -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column 1: Status, Purpose, Approval Time, and Remarks -->
                <div class="space-y-9">
                    <div class="flex items-center align-center space-x-4">
                        <i class="fa-solid fa-info-circle text-2xl text-gray-500"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Status</label>
                            <?php $color = match ($outpass->getStatus()->value) {
                                OutpassStatus::APPROVED->value => 'green',
                                OutpassStatus::PENDING->value => 'yellow',
                                OutpassStatus::REJECTED->value => 'red',
                                OutpassStatus::EXPIRED->value => 'gray',
                                default => 'gray',
                            }; ?>
                            <p class="text-base md:text-lg text-<?= $color ?>-800 font-medium mt-1"><?= ucfirst($outpass->getStatus()->value) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center align-center space-x-4">
                        <i class="fa-solid fa-question-circle text-2xl text-gray-500"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Purpose</label>
                            <p class="text-lg text-gray-800 leading-relaxed mt-1">
                                <?php if (empty($outpass->getPurpose())): echo 'None'; else: echo(ucfirst($outpass->getPurpose())); endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center align-center space-x-4">
                        <i class="fas fa-calendar-check text-2xl text-gray-500"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Approval Time</label>
                            <p class="text-base md:text-lg text-gray-800 mt-1">
                                <?php 
                                if (!empty($outpass->getApprovedTime())):
                                    echo $outpass->getApprovedTime()->format('Y-m-d, h:i A');
                                else:
                                    echo 'Not Approved';
                                endif;
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center align-center space-x-4">
                        <i class="fas fa-pencil-alt text-2xl text-gray-500"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Warden Remarks</label>
                            <p class="text-base md:text-lg text-gray-800 mt-1"><?= empty($outpass->getRemarks()) ? 'None' : $outpass->getRemarks() ?></p>
                        </div>
                    </div>
                </div>

                <!-- Column 2: QR Code and Download Button -->
                <div class="space-y-4">
                    <div class="bg-inherit select-none mb-7">
                        <div class="flex flex-col items-center rounded-lg border p-6">
                            <?php if ($outpass->getStatus() !== OutpassStatus::APPROVED): ?>
                                <div class="flex flex-col justify-center items-center gap-4 w-48 h-48">
                                    <i class="fa-solid fa-qrcode text-gray-600 text-7xl"></i>
                                    <p class="text-gray-600 text-sm">QR Not Available</p>
                                </div>
                            <?php else: ?>
                                <img class="w-48 h-48 object-contain select-none" src="<?= $this->urlFor('storage.student', [
                                    'id' => $outpass->getStudent()->getUser()->getId(),
                                    'params' => 'qr_codes/' . $outpass->getQrCode()
                                ]) ?>" alt="Outpass QR Code" oncontextmenu="return false;" draggable="false">
                            <?php endif; ?>
                        </div>
                        <p class="font-medium text-gray-600 select-text my-1">QR codes are only valid within the approved outpass time frame.</p>
                    </div>

                    <!-- QR Status and Download Button -->
                    <div class="flex justify-between items-center space-x-1">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-check-circle text-gray-500 text-xl"></i>
                            <?php if ($outpass->getStatus() === OutpassStatus::APPROVED): ?>
                                <p class="text-sm md:text-base text-gray-600">
                                    QR Health: <span class="px-3 py-1 rounded-full text-md font-medium bg-green-100 text-green-800">Active</span>
                                </p>
                            <?php else: ?>
                                <p class="text-sm md:text-base text-gray-600">
                                    QR Health: <span class="px-3 py-1 rounded-full text-md font-medium bg-red-100 text-red-800">Inactive</span>
                                </p>
                            <?php endif; ?>
                        </div>
                        <?php
                        $qrCode = $outpass->getQrCode();
                        $qrDownloadUrl = $this->urlFor('storage.student', [
                            'id' => $outpass->getStudent()->getUser()->getId(),
                            'params' => 'qr_codes/' . $qrCode
                        ]); ?>
                        <a <?php if ($qrCode): ?>
                            href="<?= htmlspecialchars($qrDownloadUrl) ?>" download
                            <?php else: ?>
                            href="javascript:void(0)" aria-disabled="true" tabindex="-1"
                            <?php endif; ?>
                            class="text-blue-600 hover:text-blue-700 text-base disabled:opacity-50 <?= $qrCode ? 'hover:underline' : 'opacity-50 cursor-not-allowed' ?>">
                            <i class="fa-solid fa-download mr-1"></i>
                            <span>Download QR</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>