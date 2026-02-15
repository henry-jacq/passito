<section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Verifier Console</h2>
                    <p class="mt-1 text-sm text-gray-500">Validate student outpasses quickly.</p>
                </div>
            </div>

            <form id="manual-verifier-form" class="mt-6 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <button type="button" id="start-qr-scan"
                            class="px-4 py-2 text-sm text-white transition bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            Scan QR
                        </button>
                        <button id="qr-stop" type="button"
                            class="px-3 py-2 text-sm text-gray-700 transition bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Stop Camera
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <input id="manual-outpass-id" type="number" min="1" placeholder="Outpass ID"
                            class="w-40 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200" />
                        <button type="button" id="manual-use-id"
                            class="px-3 py-2 text-sm text-gray-700 transition bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Use ID
                        </button>
                    </div>
                </div>
                <div id="qr-scan-panel" class="hidden p-3 border border-gray-200 rounded-md bg-gray-50">
                    <p class="mb-2 text-xs text-gray-600">Point the camera at the QR code.</p>
                    <video id="qr-video" class="w-full rounded-md" playsinline muted></video>
                </div>
                <div id="qr-result" class="hidden p-4 bg-white border border-gray-200 rounded-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-800" id="qr-student-name"></p>
                            <p class="text-xs text-gray-500" id="qr-type"></p>
                            <p class="text-xs text-gray-500" id="qr-destination"></p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full" id="qr-status"></span>
                    </div>
                    <div class="grid grid-cols-1 gap-2 mt-3 text-xs text-gray-600 md:grid-cols-2">
                        <div>
                            <p class="font-medium text-gray-700">Depart</p>
                            <p id="qr-depart"></p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Return</p>
                            <p id="qr-return"></p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Check-Out</p>
                            <p id="qr-checkout-status"></p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Check-In</p>
                            <p id="qr-checkin-status"></p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 mt-4">
                        <button type="button" id="qr-checkout"
                            class="px-4 py-2 text-sm text-white transition bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Check Out
                        </button>
                        <button type="button" id="qr-checkin"
                            class="px-4 py-2 text-sm text-white transition bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                            Check In
                        </button>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Make sure the outpass is approved before checking out.</p>
            </form>
        </div>
    </div>

    <aside class="space-y-6">
        <div class="p-6 bg-white border border-gray-200 rounded-md shadow-md">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                    <?= strtoupper(substr($user->getName(), 0, 1)) ?>
                </span>
                <div>
                    <p class="text-sm font-semibold text-gray-800"><?= $user->getName() ?></p>
                    <p class="text-xs text-gray-500"><?= $user->getEmail() ?></p>
                </div>
            </div>
        </div>
    </aside>

    <aside class="space-y-6">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
            <?php if (empty($logs)): ?>
                <p class="mt-3 text-sm text-gray-500">No recent verifications yet.</p>
            <?php else: ?>
                <ul class="mt-4 space-y-3 text-sm text-gray-700">
                    <?php foreach ($logs as $log): ?>
                        <li class="p-3 border border-gray-200 rounded-md">
                            <p class="font-medium text-gray-800">Outpass #<?= $log->getOutpass()->getId() ?></p>
                            <p class="text-xs text-gray-500">
                                Check-Out: <?= $log->getOutTime() ? $log->getOutTime()->format('d M, h:i A') : 'Pending' ?>
                            </p>
                            <p class="text-xs text-gray-500">
                                Check-In: <?= $log->getInTime() ? $log->getInTime()->format('d M, h:i A') : 'Pending' ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </aside>
</section>