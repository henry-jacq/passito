<div class="px-2 space-y-6">
    <h3 class="text-xl font-bold text-gray-900">Add Program</h3>

    <div class="space-y-5">
        <div class="space-y-2">
            <label for="program-name" class="block font-semibold text-gray-700 text-md">Program Name</label>
            <input type="text" id="program-name" name="program-name" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Program Name" required>
        </div>
        <div class="space-y-2">
            <label for="program-shortcode" class="block font-semibold text-gray-700 text-md">Short Code</label>
            <input type="text" id="program-shortcode" name="program-shortcode" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Short Code" required>
        </div>
        <div class="space-y-2">
            <label for="program-course" class="block font-semibold text-gray-700 text-md">Course Name</label>
            <input type="text" id="program-course" name="program-course" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Course Name" required>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="space-y-2">
                <label for="program-duration" class="block font-semibold text-gray-700 text-md">Duration (Years)</label>
                <input type="number" id="program-duration" name="program-duration" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="e.g., 4" min="1" required>
            </div>
            <div class="space-y-2">
                <label for="program-institution" class="block font-semibold text-gray-700 text-md">Institution</label>
                <select id="program-institution" name="program-institution" required
                    class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md">
                    <option value="" disabled selected>Select Institution</option>
                    <?php foreach (($institutions ?? []) as $institution): ?>
                        <option value="<?= $institution['id'] ?>"><?= $institution['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>
