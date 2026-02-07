<section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Manual Verification</h2>
                    <p class="mt-1 text-sm text-gray-500">Enter the outpass ID to check out or check in.</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $verifier && $verifier->getStatus()->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                    <?= $verifier && $verifier->getStatus()->value === 'active' ? 'Active' : 'Inactive' ?>
                </span>
            </div>

            <form id="manual-verifier-form" class="mt-6 space-y-4">
                <div>
                    <label for="outpass-id" class="block text-sm font-medium text-gray-700">Outpass ID</label>
                    <input id="outpass-id" name="outpass_id" type="number" min="1" placeholder="e.g., 1024"
                        class="w-full px-3 py-2 mt-1 text-sm text-gray-800 transition border border-gray-300 rounded-md bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" data-action="checkout"
                        class="px-4 py-2 text-sm text-white transition bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Check Out
                    </button>
                    <button type="button" data-action="checkin"
                        class="px-4 py-2 text-sm text-white transition bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Check In
                    </button>
                </div>
                <p class="text-xs text-gray-500">Make sure the outpass is approved before checking out.</p>
            </form>
        </div>
    </div>

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
