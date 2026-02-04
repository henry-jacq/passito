<?php

?>

<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="space-y-1">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Residence</h2>
            <p class="max-w-3xl mb-10 text-gray-600 text-md">
                Efficiently manage wardens and hostels by updating entries and assigning wardens.
            </p>
        </div>
    </div>

    <section class="p-6 mb-6 bg-white rounded-lg shadow-sm">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Warden Assignments</h3>
                <p class="text-sm text-gray-600">Review and manage existing assignments.</p>
            </div>
        </div>

        <form class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
            <!-- Warden Selection -->
            <div>
                <label for="warden" class="block mb-2 text-sm font-medium text-gray-700">Select Warden</label>
                <select id="warden" name="warden" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Choose Warden...</option>
                    <?php foreach ($wardens as $warden): ?>
                        <option value="<?= $warden->getId() ?>"><?= htmlspecialchars($warden->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Hostel Selection -->
            <div>
                <label for="hostel" class="block mb-2 text-sm font-medium text-gray-700">Select Hostel</label>
                <select id="hostel" name="hostel" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Choose Hostel...</option>
                    <?php foreach ($hostels as $hostel): ?>
                        <option value="<?= $hostel->getId() ?>"><?= htmlspecialchars($hostel->getName()) ?> (<?= htmlspecialchars($hostel->getCategory()) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Action -->
            <div class="flex items-end md:col-span-2">
                <button type="submit" class="w-full px-5 py-2 text-sm font-medium text-white transition-all duration-200 ease-in-out bg-blue-600 rounded-lg shadow-md opacity-50 cursor-not-allowed hover:bg-blue-700 focus:ring focus:ring-blue-400" disabled="true">
                    <i class="mr-2 fas fa-plus"></i> Assign Warden
                </button>
            </div>
        </form>


        <div class="mt-6 overflow-x-auto rounded-md shadow-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Warden Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Assigned Hostel</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Assigned By</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($assignmentsView)): ?>
                        <?php foreach ($assignmentsView as $key => $view): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $key + 1; ?></td>
                                <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($view->assignment->getAssignedTo()->getName()); ?></td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700">
                                    <?= $view->resolvedTarget->getName() ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($view->assignment->getAssignedBy()->getName()); ?></td>
                                <td class="px-6 py-3 text-sm text-center">
                                    <button
                                        class="text-red-600 transition duration-200 hover:text-red-800 remove-assignment-modal"
                                        data-id="<?= $view->assignment->getId(); ?>"
                                        data-wardenname="<?= htmlspecialchars($view->assignment->getAssignedTo()->getName()); ?>"
                                        data-hostelname="<?= htmlspecialchars($view->resolvedTarget->getName()); ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
                                No warden assignments found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Wardens Section -->
    <section class="p-6 mb-6 bg-white rounded-lg shadow-sm select-none">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Wardens</h3>
                <p class="text-sm text-gray-600">Manage all wardens effectively.</p>
            </div>
            <button id="add-warden-modal" class="inline-flex items-center px-5 py-2 text-sm font-medium text-white transition-all duration-200 ease-in-out bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
                <i class="mr-2 fa-solid fa-plus"></i> Add Warden
            </button>
        </div>
        <?php if (empty($wardens)): ?>
            <div class="flex items-center justify-center py-8 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">No wardens found. Click the button above to create one.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto rounded-md shadow-md">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Warden Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Email</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Contact No.</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-center text-gray-600">Status</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Actions</th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($wardens as $key => $warden): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $key + 1 ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $warden->getName() ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $warden->getEmail() ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?= $warden->getContactNo() ?></td>
                                <td class="px-6 py-3 text-sm text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-center">
                                    <button
                                        class="mr-4 text-gray-600 transition duration-200 hover:text-gray-800 edit-warden-modal"
                                        data-id="<?= $warden->getId() ?>"
                                        data-name="<?= htmlspecialchars($warden->getName()) ?>"
                                        data-email="<?= htmlspecialchars($warden->getEmail()) ?>"
                                        data-contact="<?= htmlspecialchars($warden->getContactNo()) ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 transition duration-200 hover:text-red-800 remove-warden-modal" data-wardenname="<?= $warden->getName() ?>" data-id="<?= $warden->getId() ?>"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <!-- Hostels Section -->
    <section class="p-6 mb-6 bg-white rounded-lg shadow-sm select-none">
        <div class="flex items-center justify-between pb-4 mb-6 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Hostels</h3>
                <p class="text-sm text-gray-600">Manage all hostels effectively.</p>
            </div>
            <button class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition duration-200 bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed add-hostel-modal">
                <i class="mr-2 fas fa-plus"></i> Add Hostel
            </button>
        </div>
        <?php if (empty($hostels)): ?>
            <div class="flex items-center justify-center py-8 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600">No hostels found. Click the button above to create one.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto rounded-md shadow-md">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-600">Hostel Name</th>
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Category</th>
                            <th class="px-6 py-3 text-sm font-semibold text-right text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($hostels as $key => $hostel): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-800"><?= $key + 1 ?></td>
                                <td class="px-6 py-3 text-sm text-gray-800"><?= $hostel->getName() ?></td>
                                <td class="px-4 py-3 text-sm text-gray-800"><?= $hostel->getCategory() ?></td>
                                <td class="px-6 py-3 text-right">
                                    <div class="inline-flex items-center space-x-4 text-gray-500">
                                        <button
                                            title="Edit"
                                            class="text-gray-700 hover:text-gray-800 edit-hostel-modal"
                                            data-id="<?= $hostel->getId() ?>"
                                            data-name="<?= htmlspecialchars($hostel->getName()) ?>"
                                            data-category="<?= htmlspecialchars($hostel->getCategory()) ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button title="Delete" class="text-red-700 hover:text-red-800 remove-hostel-modal" data-id="<?= $hostel->getId() ?>" data-name="<?= $hostel->getName() ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
