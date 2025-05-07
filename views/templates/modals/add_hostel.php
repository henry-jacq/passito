<div class="px-2 space-y-6">
    <h3 class="text-2xl font-bold text-gray-900">Add New Hostel</h3>

    <div class="space-y-5">
        <div class="space-y-2">
            <label for="hostel-name" class="block font-semibold text-gray-700 text-md">Hostel Name</label>
            <input type="text" id="hostel-name" name="hostel-name" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Name" required>
        </div>
        <div class="space-y-2">
            <label for="hostel-category" class="block font-semibold text-gray-700 text-md">Hostel Category</label>
            <input type="text" id="hostel-category" name="hostel-category" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Name" required>
            <p class="text-xs italic leading-tight text-gray-500">
                Examples: Non-AC Shared (Common Bath), AC with Attached Bath and Balcony, AC Shared Room
            </p>
        </div>
        <div class="space-y-2">
            <label for="select-warden" class="block font-semibold text-gray-700 text-md">Select Warden</label>
            <select id="select-warden" name="select-warden" class="w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" required>
                <?php foreach ($wardens as $warden): ?>
                    <option value="<?= $warden['id'] ?>"><?= $warden['name'] . ' (' . $warden['email'] . ')' ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="space-y-2">
            <label for="select-institution" class="block font-semibold text-gray-700 text-md">Select Institution</label>
            <select id="select-institution" name="select-institution" class="w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" required>
                <?php foreach ($institutions as $institution): ?>
                    <option value="<?= $institution['id'] ?>"><?= $institution['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>