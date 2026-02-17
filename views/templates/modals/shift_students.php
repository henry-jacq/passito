<div class="px-2 space-y-4">
    <h3 class="pb-3 text-xl font-bold text-gray-800 border-b border-gray-200">Update Student Current Year</h3>

    <div class="space-y-4">
        <div>
            <label for="shift-academic-year" class="block text-sm font-medium text-gray-700">Academic Batch</label>
            <select id="shift-academic-year" class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500">
                <option value="" disabled selected>Select Academic Batch</option>
                <?php foreach (($academic_years ?? []) as $year): ?>
                    <option value="<?= $year['id'] ?>"><?= htmlspecialchars($year['label']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="space-y-2">
            <label class="flex items-center space-x-3">
                <input id="shift-promote-current-year" type="checkbox" checked class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                <span class="text-sm text-gray-700">Increase current year (example: 1 to 2)</span>
            </label>
            <label class="flex items-center space-x-3">
                <input id="shift-deactivate-exceeded" type="checkbox" checked class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                <span class="text-sm text-gray-700">Mark exceeded-course-year students as inactive</span>
            </label>
        </div>

        <div class="p-3 text-sm border rounded-lg bg-amber-50 border-amber-200 text-amber-800">
            This updates students' current year only for the selected academic batch. If students exceed their course year limit, they can be marked inactive, and the academic batch is also marked inactive when no active students remain.
        </div>
    </div>
</div>
