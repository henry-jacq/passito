<?php

use App\Enum\ReportKey;
?>

<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <?php if ($data['lockRequests']): ?>
        <div class="flex items-center justify-between p-4 mb-6 border rounded-lg bg-amber-50 border-amber-200">
            <!-- Status Info -->
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-amber-100">
                    <i class="text-amber-600 fas fa-lock"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-800">Outpass Requests Locked</span>
                    </div>
                    <p class="text-sm text-gray-600">Students cannot submit new outpass requests</p>
                </div>
            </div>

            <!-- Unlock Button -->
            <button id="unlockRequests"
                class="flex items-center px-4 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                <i class="mr-2 fas fa-unlock"></i>
                <span>Unlock Requests</span>
            </button>
        </div>
    <?php endif; ?>

    <section class="mb-8">
        <h2 class="mb-4 text-2xl font-semibold text-gray-700">Dashboard</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700">Total Requests</h3>
                <p class="text-3xl text-blue-800"><?= $data['approved'] ?></p>
                <p class="mt-1 text-sm text-gray-500">All-time approved requests</p>
            </div>
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700">Pending Requests</h3>
                <p class="text-3xl text-yellow-800"><?= $data['pending'] ?></p>
                <p class="mt-1 text-sm font-medium text-green-500">
                    <i class="mr-1 fa-solid fa-arrow-up"></i>
                    5% from last week
                </p>
            </div>
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700">Rejected Requests</h3>
                <p class="text-3xl text-red-800"><?= $data['rejected'] ?></p>
                <p class="mt-1 text-sm text-gray-500">Denied by wardens</p>
            </div>
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700">Overdue Returns</h3>
                <p class="text-3xl text-green-800">0</p>
                <p class="mt-1 text-sm text-gray-500">Students overdue today</p>
            </div>
        </div>
    </section>

    <section class="mb-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Daily Movement Summary -->
            <div class="p-6 bg-white rounded-lg shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700">Daily Movement</h4>
                            <p class="text-sm text-gray-500">Today's check-in/out summary</p>
                        </div>
                    </div>
                    <button class="px-3 py-1 text-sm font-medium text-blue-600 transition bg-blue-100 rounded-lg hover:bg-blue-200 export-report-btn" data-key="<?= ReportKey::DAILY_MOVEMENT->value ?>">
                        <i class="mr-1 fa fa-download"></i>
                        Export CSV
                    </button>
                </div>

                <div class="pt-4 space-y-5">
                    <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Checked-out Today</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 text-sm font-semibold text-orange-800 bg-orange-100 rounded-full"><?= $data['checkedOut'] ?></span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Checked-in Today</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full"><?= $data['checkedIn'] ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Late Arrivals Report -->
            <div class="p-6 bg-white rounded-lg shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h4 class="text-lg font-semibold text-gray-700">Late Arrivals</h4>
                                <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                    <?php if (count($lateArrivals) > 9): echo '9+';
                                    else: echo count($lateArrivals);
                                    endif; ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">Students who checked in late today</p>
                        </div>
                    </div>
                    <button class="px-3 py-1 text-sm font-medium text-blue-600 transition bg-blue-100 rounded-lg hover:bg-blue-200 export-report-btn" data-key="<?= ReportKey::LATE_ARRIVALS->value ?>">
                        <i class="mr-1 fa fa-download"></i>
                        Export CSV
                    </button>
                </div>

                <?php if (empty($lateArrivals)): ?>
                    <div class="flex items-center justify-center w-full h-48 text-gray-500 border border-gray-200 rounded-lg bg-gray-50">
                        No late arrivals today
                    </div>
                <?php else: ?>
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Student Name</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Digital ID</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Hostel</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Late By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($lateArrivals as $arrival): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-800"><?= $arrival->getOutpass()->getStudent()->getUser()->getName() ?></td>
                                        <td class="px-4 py-3 text-gray-800"><?= $arrival->getOutpass()->getStudent()->getDigitalId() ?></td>
                                        <td class="px-4 py-3 text-gray-600"><?= $arrival->getOutpass()->getStudent()->getHostel()->getName() ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full"><?= $arrival->getLateDuration() ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-1 lg:grid-cols-3">
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold">Bulk Approval</h4>
                <p class="mb-3 text-sm">Quickly approve all pending requests.</p>
                <button id="bulkApproval" class="flex items-center px-2 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="mr-2 fas fa-play"></i>
                    <span>Perform</span>
                </button>
            </div>
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold">Notify Students</h4>
                <p class="mb-3 text-sm">Alert students who haven't checked in.</p>
                <button id="notifyStudents" class="flex items-center px-2 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="mr-2 fas fa-play"></i>
                    <span>Perform</span>
                </button>
            </div>
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold">Lock Requests</h4>
                <p class="mb-3 text-sm">Block new requests until unlocked.</p>
                <button id="lockRequests" class="flex items-center px-2 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="mr-2 fas fa-play"></i>
                    <span>Perform</span>
                </button>
            </div>
        </div>
    </section>

</main>
