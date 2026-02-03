<div class="px-3 space-y-4">
    <h3 class="pb-3 text-xl font-bold text-gray-800 border-b border-gray-200">Edit Academic Year</h3>

    <div class="space-y-4">
        <div>
            <label for="academic-year-label" class="block text-sm font-medium text-gray-700">Label</label>
            <input type="text" id="academic-year-label" name="academic-year-label" placeholder="e.g., 2025-26" required
                value="<?= $academic_year['label'] ?? '' ?>"
                class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="academic-year-start" class="block text-sm font-medium text-gray-700">Start Year</label>
                <input type="number" id="academic-year-start" name="academic-year-start" placeholder="e.g., 2025" required
                    value="<?= $academic_year['start_year'] ?? '' ?>"
                    class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="academic-year-end" class="block text-sm font-medium text-gray-700">End Year</label>
                <input type="number" id="academic-year-end" name="academic-year-end" placeholder="e.g., 2026" required
                    value="<?= $academic_year['end_year'] ?? '' ?>"
                    class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div>
            <label for="academic-year-status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="academic-year-status" name="academic-year-status" required
                class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <option value="1" <?= ($academic_year['status'] ?? true) ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= ($academic_year['status'] ?? true) ? '' : 'selected' ?>>Inactive</option>
            </select>
        </div>
    </div>
</div>
