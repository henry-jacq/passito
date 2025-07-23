<?php

use App\Enum\OutpassStatus;
?>
<div class="flex items-center justify-center min-h-screen px-4 py-10 font-sans bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="w-full max-w-3xl p-8 space-y-8 transition-all duration-300 ease-in-out bg-white border border-gray-200 rounded-lg shadow-xl sm:p-10">

        <!-- Header -->
        <div class="mb-4 space-y-3 text-center">
            <img class="mx-auto h-14" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/Sri_Sivasubramaniya_Nadar_College_of_Engineering.svg/225px-Sri_Sivasubramaniya_Nadar_College_of_Engineering.png" alt="Institution Logo">
            <h2 class="text-2xl font-semibold text-gray-800 font-heading">Parental Verification</h2>
            <?php if (!isset($response)): ?>
                <p class="text-base text-gray-600">Kindly review the outpass request below and provide your response.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($response) && !empty($response)): ?>
            <?php if ($response === OutpassStatus::PARENT_APPROVED->value): ?>
                <div class="flex items-center justify-start gap-3 px-4 py-3 mb-4 text-sm text-green-800 bg-green-100 border border-green-300 rounded-md">
                    <i class="mr-2 fa fa-check-circle"></i>
                    <span>Parental approval for outpass #<?= $outpass->getId() ?> is approved. You can close this page!</span>
                </div>
            <?php else: ?>
                <div class="flex items-center justify-start gap-3 px-4 py-3 mb-4 text-sm text-red-800 bg-red-100 border border-red-300 rounded-md">
                    <i class="mr-2 text-red-600 fa fa-info-circle"></i>
                    <span>Parental verification for outpass #<?= $outpass->getId() ?> is denied. You can close this page!</span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Student Information -->
        <section class="p-6 transition-all duration-300 ease-in-out border border-gray-200 rounded-md shadow-sm bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="mb-4 text-sm font-semibold tracking-wider text-gray-800 uppercase">Student Details</h3>
            <ul class="space-y-3 text-sm text-gray-700">
                <li><strong class="text-gray-900">Name:</strong> <?= $student->getUser()->getName() ?></li>
                <li><strong class="text-gray-900">Digital ID:</strong> <?= $student->getDigitalId() ?></li>
                <li><strong class="text-gray-900">Institution:</strong> <?= $student->getInstitution()->getName() ?></li>
            </ul>
            <br>
            <h3 class="mb-4 text-sm font-semibold tracking-wider text-gray-800 uppercase">Outpass Information</h3>
            <div class="grid grid-cols-1 gap-4 text-sm text-gray-700 sm:grid-cols-2">
                <div><strong class="text-gray-900">ID:</strong> #<?= $outpass->getId() ?></div>
                <div><strong class="text-gray-900">Type:</strong> <span class="font-medium text-gray-600"><?= ucfirst($outpass->getTemplate()->getName()) ?></span></div>
                <div><strong class="text-gray-900">From:</strong> <?= $outpass->getFromDate()->format('d M Y, ') . $outpass->getFromTime()->format('h:i A') ?></div>
                <div><strong class="text-gray-900">To:</strong> <?= $outpass->getToDate()->format('d M Y, ') . $outpass->getToTime()->format('h:i A') ?></div>
                <div class="sm:col-span-2"><strong class="text-gray-900">Purpose:</strong> <?= ucfirst($outpass->getReason()) ?></div>
                <div class="sm:col-span-2"><strong class="text-gray-900">Location:</strong> <?= ucfirst($outpass->getDestination()) ?></div>
            </div>
        </section>

        <?php if (!isset($response)): ?>
            <!-- Action Buttons -->
            <div class="flex flex-col justify-center gap-2 sm:flex-row">
                <form method="POST" action="<?= $this->urlFor('parent.verify', [], [
                                                'token' => $verification->getVerificationToken(),
                                                'response' => OutpassStatus::PARENT_APPROVED->value,
                                            ]) ?>" class="w-full sm:w-auto">
                    <button type="submit"
                        class="w-full px-4 py-2 text-sm font-medium tracking-wide text-white transition-all duration-200 ease-in-out bg-green-600 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Allow
                    </button>
                </form>
                <form method="POST" action="<?= $this->urlFor('parent.verify', [], [
                                                'token' => $verification->getVerificationToken(),
                                                'response' => OutpassStatus::PARENT_DENIED->value,
                                            ]) ?>" class="w-full sm:w-auto">
                    <button type="submit"
                        class="w-full px-4 py-2 text-sm font-medium tracking-wide text-white transition-all duration-200 ease-in-out bg-red-600 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Deny
                    </button>
                </form>
            </div>
            <p class="text-xs italic text-center text-gray-400">Your response will be securely logged and processed.</p>
        <?php endif; ?>
    </div>
</div>