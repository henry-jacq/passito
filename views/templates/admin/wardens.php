<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="space-y-1">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Wardens</h2>
            <p class="max-w-3xl mb-10 text-gray-600 text-md">
                View, update, and assign wardens to ensure efficient hostel management.
            </p>
        </div>
        <button id="add-warden-modal" class="inline-flex items-center px-5 py-2 text-sm font-medium text-white transition-all duration-200 ease-in-out bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
            <i class="mr-2 fa-solid fa-plus"></i> Add Warden
        </button>
    </div>

    <div class="p-4 mb-8 border-l-4 rounded-lg bg-blue-500/20 border-blue-800/80">
        <h3 class="mb-2 text-base font-semibold text-blue-900">Important Notes</h3>
        <ul class="pl-4 space-y-1 text-sm text-blue-800 list-disc">
            <li>Wardens may be assigned to manage multiple hostels.</li>
            <li>Wardens are allowed to approve the outpass from their managed hostels.</li>
            <li>In case of emergency, they're allowed to approve other hostel's outpass.</li>
            <li>Before removing a warden, reassign all managed hostels to other wardens to ensure operational continuity.</li>
        </ul>
    </div>
    <?php if (!empty($wardens)): ?>
        <section class="overflow-hidden bg-white rounded-lg shadow-md">
            <table class="w-full border-collapse table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">#</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Warden Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Email</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Contact No.</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Hostels Managed</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-center text-gray-600">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wardens as $warden): ?>
                        <!-- Example Warden Row -->
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $warden->getId() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $warden->getName() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $warden->getEmail() ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $warden->getContactNo() ?></td>
                            <td class="px-4 py-3 text-sm text-center text-gray-700">
                                <?=
                                empty($warden->getHostels()->toArray()) ? 'N/A' :
                                    implode(', ', array_map(
                                        fn($hostel) => htmlspecialchars($hostel->getName()),
                                        $warden->getHostels()->toArray()
                                    )) ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <button class="mr-4 text-gray-600 transition duration-200 hover:text-gray-800 edit-warden-modal" data-id="<?= $warden->getId() ?>"><i class="fas fa-edit"></i></button>
                                <button class="text-red-600 transition duration-200 hover:text-red-800 remove-warden-modal" data-wardenname="<?= $warden->getName() ?>" data-id="<?= $warden->getId() ?>"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <!-- Repeat rows as needed -->
                </tbody>
            </table>
        </section>
    <?php endif ?>
</main>