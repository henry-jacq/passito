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
                <h1 class="text-3xl font-bold text-gray-800">Outpass <?= '#' . $outpass->getId() ?> Details</h1>
                <p class="mt-1 text-base text-gray-500">View your outpass request details.</p>
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
                    <i class="mr-1 fa-solid fa-arrow-down"></i>
                    <span>Download Outpass</span>
                </a>
            </div>
        </header>

        <!-- Outpass Details Section -->
        <section class="p-6 mb-8 bg-white shadow-md rounded-xl md:p-8">
            <!-- Row 1: Dates and Times -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-9">
                <div class="flex items-center space-x-4 align-center">
                    <i class="text-2xl text-gray-500 fa-regular fa-clock"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">From Date & Time</label>
                        <p class="mt-1 text-base font-medium text-gray-800 md:text-lg"><?= $outpass->getFromDate()->format('Y-m-d, ') . $outpass->getFromTime()->format('h:i A') ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 align-center">
                    <i class="text-2xl text-gray-500 fa-regular fa-clock"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">To Date & Time</label>
                        <p class="mt-1 text-base font-medium text-gray-800 md:text-lg"><?= $outpass->getToDate()->format('Y-m-d, ') . $outpass->getToTime()->format('h:i A') ?></p>
                    </div>
                </div>
            </div>

            <!-- Row 2: Pass Type and Destination -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-9">
                <div class="flex items-center space-x-4 align-center">
                    <i class="text-2xl text-gray-500 fa-regular fa-id-card"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">Pass Type</label>
                        <p class="mt-1 text-base font-medium text-gray-800 md:text-lg"><?= ucfirst($outpass->getTemplate()->getName()) ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 align-center">
                    <i class="text-2xl text-gray-500 fa-solid fa-location-dot"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">Destination</label>
                        <p class="mt-1 text-base font-medium text-gray-800 md:text-lg"><?= ucwords($outpass->getDestination()) ?></p>
                    </div>
                </div>
            </div>

            <!-- Split Columns: Status, Purpose, and QR Code -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Column 1: Status, Purpose, Approval Time, and Remarks -->
                <div class="space-y-9">
                    <div class="flex items-center space-x-4 align-center">
                        <i class="text-2xl text-gray-500 fa-solid fa-info-circle"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Status</label>
                            <?php $color = match ($outpass->getStatus()->value) {
                                OutpassStatus::APPROVED->value => 'green',
                                OutpassStatus::PARENT_APPROVED->value => 'green',
                                OutpassStatus::PENDING->value => 'yellow',
                                OutpassStatus::PARENT_PENDING->value => 'yellow',
                                OutpassStatus::REJECTED->value => 'red',
                                OutpassStatus::PARENT_DENIED->value => 'red',
                                OutpassStatus::EXPIRED->value => 'gray',
                                default => 'gray',
                            }; ?>
                            <p class="text-base md:text-lg text-<?= $color ?>-800 font-medium mt-1"><?= ucwords(str_replace('_', ' ', $outpass->getStatus()->value)) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 align-center">
                        <i class="text-2xl text-gray-500 fa-solid fa-question-circle"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Reason</label>
                            <p class="mt-1 text-lg leading-relaxed text-gray-800">
                                <?php if (empty($outpass->getReason())): echo 'None';
                                else: echo (ucfirst($outpass->getReason()));
                                endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 align-center">
                        <i class="text-2xl text-gray-500 fas fa-calendar-check"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Approval Time</label>
                            <p class="mt-1 text-base text-gray-800 md:text-lg">
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
                    <div class="flex items-center space-x-4 align-center">
                        <i class="text-2xl text-gray-500 fas fa-pencil-alt"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Warden Remarks</label>
                            <p class="mt-1 text-base text-gray-800 md:text-lg"><?= empty($outpass->getRemarks()) ? 'None' : $outpass->getRemarks() ?></p>
                        </div>
                    </div>
                </div>

                <!-- Column 2: QR Code and Download Button -->
                <div class="space-y-4">
                    <div class="select-none bg-inherit mb-7">
                        <div class="flex flex-col items-center p-6 border rounded-lg">
                            <?php if ($outpass->getStatus() !== OutpassStatus::APPROVED): ?>
                                <div class="flex flex-col items-center justify-center w-48 h-48 gap-4">
                                    <i class="text-gray-600 fa-solid fa-qrcode text-7xl"></i>
                                    <p class="text-sm text-gray-600">QR Not Available</p>
                                </div>
                            <?php else: ?>
                                <img class="object-contain w-48 h-48 select-none" src="<?= $this->urlFor('storage.student', [
                                                                                            'id' => $outpass->getStudent()->getUser()->getId(),
                                                                                            'params' => 'qr_codes/' . $outpass->getQrCode()
                                                                                        ]) ?>" alt="Outpass QR Code" oncontextmenu="return false;" draggable="false">
                            <?php endif; ?>
                        </div>
                        <p class="my-1 font-medium text-gray-600 select-text">QR codes are only valid within the approved outpass time frame.</p>
                    </div>

                    <!-- QR Status and Download Button -->
                    <div class="flex items-center justify-between space-x-1">
                        <div class="flex items-center space-x-2">
                            <i class="text-xl text-gray-500 fa-solid fa-check-circle"></i>
                            <?php if ($outpass->getStatus() === OutpassStatus::APPROVED): ?>
                                <p class="text-sm text-gray-600 md:text-base">
                                    QR Health: <span class="px-3 py-1 font-medium text-green-800 bg-green-100 rounded-full text-md">Active</span>
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-gray-600 md:text-base">
                                    QR Health: <span class="px-3 py-1 font-medium text-red-800 bg-red-100 rounded-full text-md">Inactive</span>
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
                            <i class="mr-1 fa-solid fa-download"></i>
                            <span>Download QR</span>
                        </a>
                    </div>
                </div>
                <?php if (!empty($outpass->getAttachments()) && count($outpass->getAttachments()) > 0): ?>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 align-center">
                            <i class="text-2xl text-gray-500 fa-solid fa-file-alt"></i>
                            <div>
                                <label class="block text-base font-medium text-gray-500">Attachments</label>
                                <p class="mt-1 space-x-2 text-base text-gray-800 md:text-lg">
                                    <?php if (count($outpass->getAttachments()) > 0): ?>
                                        <?php foreach ($outpass->getAttachments() as $attachment):
                                            $url = htmlspecialchars($this->urlFor('storage.student', [
                                                'id' => $outpass->getStudent()->getUser()->getId(),
                                                'params' => $attachment
                                            ])); ?>
                                            <a href="<?= $url ?>" download class="text-blue-600 hover:text-blue-700 hover:underline">
                                                <i class="fa fa-link"></i>
                                                <?= basename($url) ?> 
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>