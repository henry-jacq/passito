<?php

use App\Enum\OutpassStatus;
?>
<!-- Main Content -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <!-- Page Header -->
    <div class="flex items-center justify-between pb-3 mb-5 space-x-4 border-b border-gray-300">
        <h2 class="text-2xl font-semibold text-gray-700">#<?= $outpass_id ?> Outpass Details</h2>
        <div class="flex space-x-4">
            <?php if ($outpass->getStatus() === OutpassStatus::PENDING): ?>
                <div>
                    <button class="px-4 py-2 text-sm font-medium text-white transition-all transition bg-green-600 rounded-lg hover:bg-green-700">Approve</button>
                    <button class="px-4 py-2 text-sm font-medium text-white transition-all transition bg-red-600 rounded-lg hover:bg-red-700">Reject</button>
                </div>
            <?php endif; ?>
            <a href="<?= $this->urlFor('admin.outpass.records') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 transition-all transition bg-gray-200 rounded-lg hover:bg-gray-300">Back to Records</a>
        </div>
    </div>

    <!-- Student & Outpass Details -->
    <section class="p-6 bg-white rounded-lg shadow-md md:p-8">
        <!-- Student Information -->
        <h2 class="pb-2 mb-4 text-lg font-semibold text-gray-700 border-b">Student Information</h2>
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-500">Student Name</label>
                <p class="text-base gray-900"><?= $outpass->getStudent()->getUser()->getName() ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Digital ID</label>
                <p class="text-base gray-900"><?= $outpass->getStudent()->getDigitalId() ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Contact</label>
                <p class="text-base gray-900"><?= $outpass->getStudent()->getUser()->getContactNo() ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Parent Contact</label>
                <p class="text-base text-gray-900"><?= $outpass->getStudent()->getParentNo() ?></p>
            </div>
        </div>

        <!-- Outpass Details -->
        <h2 class="pb-2 mb-4 text-lg font-semibold text-gray-700 border-b">Outpass Details</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-500">From Date & Time</label>
                <p class="text-base text-gray-900"><?= $outpass->getFromDate()->format('Y-m-d, ') . $outpass->getFromTime()->format('h:i A') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">To Date & Time</label>
                <p class="text-base text-gray-900"><?= $outpass->getToDate()->format('Y-m-d, ') . $outpass->getToTime()->format('h:i A') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Pass Type</label>
                <p class="text-base text-gray-900"><?= ucfirst($outpass->getPassType()->value) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Destination</label>
                <p class="text-base text-gray-900"><?= ucwords($outpass->getDestination()) ?></p>
            </div>
        </div>

        <!-- Status & Approval -->
        <h2 class="pb-2 mt-6 mb-4 text-lg font-semibold text-gray-700 border-b">Status & Approval</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <?php
                $status = $outpass->getStatus()->value;
                $color = match ($status) {
                    'approved' => 'green',
                    'rejected' => 'red',
                    'pending' => 'yellow',
                    'expired' => 'yellow',
                    default => 'gray'
                };
                ?>
                <span class="px-3 py-1 text-sm font-medium text-white bg-<?= $color ?>-600 rounded-full">
                    <?= ucfirst($status) ?>
                </span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Approval Time</label>
                <p class="text-base text-gray-900">
                    <?= $outpass->getApprovedTime() ? $outpass->getApprovedTime()->format('Y-m-d, h:i A') : '<span class="text-gray-500">Not Approved</span>' ?>
                </p>
            </div>
        </div>
    </section>
</main>