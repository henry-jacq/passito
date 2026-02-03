<div class="px-2 space-y-6">
    <h3 class="text-xl font-bold text-gray-900">Edit Institution</h3>

    <div class="space-y-5">
        <div class="space-y-2">
            <label for="institution-name" class="block font-semibold text-gray-700 text-md">Institution Name</label>
            <input type="text" id="institution-name" name="institution-name" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Name" required
                value="<?= $institution['name'] ?? '' ?>">
        </div>
        <div class="space-y-2">
            <label for="institution-address" class="block font-semibold text-gray-700 text-md">Institution Address</label>
            <input type="text" id="institution-address" name="institution-address" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Address" required
                value="<?= $institution['address'] ?? '' ?>">
        </div>
        <div class="space-y-2">
            <label for="institution-type" class="block font-semibold text-gray-700 text-md">Institution Type</label>
            <select id="institution-type" name="institution-type" required
                class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md">
                <option value="" disabled>Select Type</option>
                <?php foreach (($types ?? []) as $type): ?>
                    <option value="<?= $type ?>" <?= ($institution['type'] ?? '') === $type ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
