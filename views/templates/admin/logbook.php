<!-- Logbook Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Verifier Logbook</h2>
    <p class="text-gray-600 text-md mb-8">View and manage logs of student check-in and check-out activities.</p>

    <section class="bg-white shadow-md rounded-lg p-6">
        <?php if (empty($logbook)): ?>
        <div class="flex flex-col items-center justify-center h-64 space-y-4">
            <i class="fas fa-exclamation-circle text-red-500 text-4xl"></i>
            <p class="text-gray-600 text-lg font-medium">Logbook is Empty!</p>
        </div>
        <?php else: ?>
        <div class="mb-4 flex justify-between items-center">
            <div>
                <p class="text-gray-600 text-sm">Total Logs: <span class="font-semibold text-gray-800">200</span></p>
            </div>
            <div>
                <input 
                    type="text" 
                    placeholder="Search logs..." 
                    class="px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300 focus:outline-none text-sm"
                />
            </div>
        </div>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-sm uppercase text-gray-600 font-semibold">
                    <th class="p-3 text-left">Outpass ID</th>
                    <th class="p-3 text-left">Student Name</th>
                    <th class="p-3 text-left">Digital ID</th>
                    <th class="p-3 text-left">Department</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Check-Out</th>
                    <th class="p-3 text-left">Check-In</th>
                    <th class="p-3 text-left">Verifier</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($logbook as $log): ?>
                <tr class="text-sm border-b hover:bg-gray-50">
                    <td class="p-3 text-gray-800 font-medium"><?= $log->getOutpass()->getId() ?></td>
                    <td class="p-3 text-gray-800"><?= $log->getOutpass()->getStudent()->getUser()->getName() ?></td>
                    <td class="p-3 text-gray-800"><?= $log->getOutpass()->getStudent()->getDigitalId() ?></td>
                    <td class="p-3 text-gray-800"><?= $log->getOutpass()->getStudent()->getDepartment() ?></td>
                    <td class="p-3 text-gray-800"><?= ucfirst($log->getOutpass()->getStatus()->value) ?></td>
                    <td class="p-3 text-gray-800"><?= $log->getOutTime()->format('Y-m-d h:i A') ?></td>
                    <td class="p-3 text-gray-800"><?php
                    if ($log->getInTime()) {
                        echo $log->getInTime()->format('Y-m-d h:i A');
                    } else {
                        echo '<span class="text-red-500">Not returned</span>';
                    }
                    ?></td>
                    <td class="p-3 text-gray-800 flex items-center">
                        <span class="mr-2 w-2.5 h-2.5 <?= $log->getVerifier()->getName() ? 'bg-green-500' : 'bg-red-500' ?> rounded-full"></span> 
                        <?= $log->getVerifier()->getName() ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <p>Showing 1 to 10 of 200 entries</p>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 border rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300 focus:ring focus:ring-blue-300 focus:outline-none">Previous</button>
                <button class="px-3 py-1 border rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:ring focus:ring-blue-300 focus:outline-none">Next</button>
            </div>
        </div>
        <?php endif; ?>
    </section>
</main>
